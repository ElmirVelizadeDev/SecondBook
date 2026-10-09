<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RefundsController extends Controller
{
    private const STATUSES = [
        'pending',
        'approved',
        'rejected',
        'processed',
        'cancelled',
    ];

    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $sort = $request->query('sort', 'newest');

        $query = Refund::with([
            'order',
            'user',
            'payment',
        ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('refund_number', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($order) => $order->where(
                        'order_number',
                        'like',
                        "%{$search}%"
                    ))
                    ->orWhereHas('user', fn ($user) => $user
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                    );
            });
        }

        if (in_array($status, self::STATUSES, true)) {
            $query->where('status', $status);
        }

        match ($sort) {
            'oldest' => $query->oldest('requested_at'),
            'highest' => $query->orderByDesc('amount'),
            'lowest' => $query->orderBy('amount'),
            default => $query->latest('requested_at'),
        };

        $refunds = $query
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Refund::count(),
            'pending' => Refund::where('status', 'pending')->count(),
            'processed' => Refund::where('status', 'processed')->count(),
            'amount' => Refund::where('status', 'processed')->sum('amount'),
        ];

        if ($request->expectsJson()) {
            $html = view(
                'Admin.refunds.partials.table',
                compact('refunds')
            )->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'stats' => $stats,
                'pagination' => $refunds->links()->render(),
            ]);
        }

        return view(
            'Admin.refunds.index',
            compact(
                'refunds',
                'stats',
                'search',
                'status',
                'sort'
            )
        );
    }

    public function create()
    {
        $orders = Order::with([
            'user',
            'payment',
        ])
            ->latest()
            ->get();

        return view(
            'Admin.refunds.create',
            compact('orders')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateRefund($request);

        $order = Order::with('payment')
            ->findOrFail($validated['order_id']);

        $limit = $order->payment?->amount ?? $order->total_price;

        $alreadyRefunded = Refund::where('order_id', $order->id)
            ->whereIn('status', ['approved', 'processed'])
            ->sum('amount');

        if (
            (float) $validated['amount']
            > ((float) $limit - (float) $alreadyRefunded)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' =>
                        'Refund amount cannot exceed the remaining refundable amount.',
                ]);
        }

        $refund = Refund::create([
            ...$validated,
            'payment_id' => $order->payment?->id,
            'user_id' => $order->user_id,
            'refund_number' => $this->nextRefundNumber(),
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $this->activityLogService->log(
            'created',
            'Refunds',
            "Refund \"{$refund->refund_number}\" was created for order \"{$order->order_number}\"."
        );

        return redirect()
            ->route('admin.refunds.show', $refund)
            ->with('success', 'Refund created successfully.');
    }

    public function show(Refund $refund)
    {
        $refund->load([
            'order.user',
            'order.payment',
            'payment',
            'user',
            'processor',
        ]);

        return view(
            'Admin.refunds.show',
            compact('refund')
        );
    }

    public function edit(Refund $refund)
    {
        if ($refund->status === 'processed') {
            return back()->with(
                'error',
                'Processed refunds cannot be edited.'
            );
        }

        return view(
            'Admin.refunds.edit',
            compact('refund')
        );
    }

    public function update(Request $request, Refund $refund)
    {
        if ($refund->status === 'processed') {
            return back()->with(
                'error',
                'Processed refunds cannot be edited.'
            );
        }

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'reason' => [
                'required',
                'string',
                'max:255',
            ],
            'note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $refund->update($validated);

        $this->activityLogService->log(
            'updated',
            'Refunds',
            "Refund \"{$refund->refund_number}\" was updated."
        );

        return redirect()
            ->route('admin.refunds.show', $refund)
            ->with('success', 'Refund updated successfully.');
    }

    public function updateStatus(Request $request, Refund $refund)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'approved',
                    'rejected',
                    'processed',
                    'cancelled',
                ]),
            ],
        ]);

        $next = $validated['status'];

        $allowed = match ($refund->status) {
            'pending' => [
                'approved',
                'rejected',
                'cancelled',
            ],
            'approved' => [
                'processed',
                'cancelled',
            ],
            default => [],
        };

        if (!in_array($next, $allowed, true)) {
            $message = 'This refund status transition is not allowed.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()->with('error', $message);
        }

        $oldStatus = $refund->status;

        DB::transaction(function () use ($refund, $next) {
            $refund->update([
                'status' => $next,
                'processed_by' => auth()->id(),
                'processed_at' => $next === 'processed'
                    ? now()
                    : null,
            ]);

            if ($next === 'processed') {
                $refund->payment?->update([
                    'payment_status' => 'refunded',
                ]);

                $refund->order->update([
                    'payment_status' => 'refunded',
                ]);
            }
        });

        $this->activityLogService->log(
            'updated',
            'Refunds',
            "Refund \"{$refund->refund_number}\" status changed from {$oldStatus} to {$next}."
        );

        $message = 'Refund ' . $next . ' successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $next,
                'refund_id' => $refund->id,
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Request $request, Refund $refund)
    {
        if ($refund->status === 'processed') {
            $message = 'Processed refunds cannot be deleted.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()->with('error', $message);
        }

        $refundNumber = $refund->refund_number;

        $this->activityLogService->log(
            'deleted',
            'Refunds',
            "Refund \"{$refundNumber}\" was deleted."
        );

        $refund->delete();

        $message = "Refund {$refundNumber} deleted successfully.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'refund_id' => $refund->id,
            ]);
        }

        return redirect()
            ->route('admin.refunds.index')
            ->with('success', 'Refund deleted successfully.');
    }

    private function validateRefund(Request $request): array
    {
        return $request->validate([
            'order_id' => [
                'required',
                'exists:orders,id',
            ],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'reason' => [
                'required',
                'string',
                'max:255',
            ],
            'note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);
    }

    private function nextRefundNumber(): string
    {
        do {
            $number = 'REF-'
                . now()->format('Ymd')
                . '-'
                . str_pad(
                    (string) random_int(1, 9999),
                    4,
                    '0',
                    STR_PAD_LEFT
                );
        } while (
            Refund::where('refund_number', $number)->exists()
        );

        return $number;
    }
}