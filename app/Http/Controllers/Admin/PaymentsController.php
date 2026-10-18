<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentsController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $query = Payment::with([
            'order.user',
            'order.book',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where(
                            'order_number',
                            'like',
                            "%{$search}%"
                        )
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where(
                                    'first_name',
                                    'like',
                                    "%{$search}%"
                                )
                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'email',
                                        'like',
                                        "%{$search}%"
                                    );
                            });
                    });
            });
        }

        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        if ($request->filled('date')) {
            $query->whereDate(
                'created_at',
                $request->date
            );
        }

        $payments = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPayments = Payment::count();

        $pendingPayments = Payment::where(
            'payment_status',
            'pending'
        )->count();

        $paidPayments = Payment::where(
            'payment_status',
            'paid'
        )->count();

        $totalRevenue = Payment::where(
            'payment_status',
            'paid'
        )->sum('amount');

        return view(
            'Admin.payments.index',
            compact(
                'payments',
                'totalPayments',
                'pendingPayments',
                'paidPayments',
                'totalRevenue'
            )
        );
    }

    public function create()
    {
        $orders = Order::with([
            'user',
            'book',
        ])
            ->latest()
            ->get();

        return view(
            'Admin.payments.create',
            compact('orders')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                'exists:orders,id',
                'unique:payments,order_id',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'payment_method' => [
                'required',
                'in:cash_on_delivery,credit_card,debit_card,paypal',
            ],
            'payment_status' => [
                'required',
                'in:pending,paid,failed,refunded',
            ],
            'paid_at' => [
                'nullable',
                'date',
            ],
            'note' => [
                'nullable',
                'string',
            ],
        ]);

        if ($request->payment_status === 'paid') {
            $validated['paid_at'] = $request->paid_at
                ? $request->paid_at
                : now();
        } else {
            $validated['paid_at'] = null;
        }

        $validated['transaction_id'] =
            'TXN-' . strtoupper(Str::random(10));

        $payment = Payment::create($validated);

        $this->activityLogService->log(
            'created',
            'Payments',
            "Payment \"{$payment->transaction_id}\" was created."
        );

        return redirect()
            ->route('admin.payments.index')
            ->with(
                'success',
                'Payment created successfully.'
            );
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'order.user',
            'order.book',
        ]);

        return view(
            'Admin.payments.show',
            compact('payment')
        );
    }

    public function edit(Payment $payment)
    {
        $orders = Order::with([
            'user',
            'book',
        ])
            ->latest()
            ->get();

        return view(
            'Admin.payments.edit',
            compact(
                'payment',
                'orders'
            )
        );
    }

    public function update(
        Request $request,
        Payment $payment
    ) {
        $validated = $request->validate([
            'order_id' => [
                'required',
                'exists:orders,id',
                'unique:payments,order_id,' . $payment->id,
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'payment_method' => [
                'required',
                'in:cash_on_delivery,credit_card,debit_card,paypal',
            ],
            'payment_status' => [
                'required',
                'in:pending,paid,failed,refunded',
            ],
            'paid_at' => [
                'nullable',
                'date',
            ],
            'note' => [
                'nullable',
                'string',
            ],
        ]);

        $oldPaymentStatus = $payment->payment_status;

        if ($request->payment_status === 'paid') {
            $validated['paid_at'] = $request->paid_at
                ? $request->paid_at
                : ($payment->paid_at ?? now());
        } else {
            $validated['paid_at'] = null;
        }

        $payment->update($validated);

        $this->activityLogService->log(
            'updated',
            'Payments',
            "Payment \"{$payment->transaction_id}\" was updated."
        );

        if ($oldPaymentStatus !== $payment->payment_status) {
            $this->activityLogService->log(
                'updated',
                'Payments',
                "Payment \"{$payment->transaction_id}\" status changed from \"{$oldPaymentStatus}\" to \"{$payment->payment_status}\"."
            );
        }

        return redirect()
            ->route('admin.payments.index')
            ->with(
                'success',
                'Payment updated successfully.'
            );
    }

    public function destroy(Payment $payment)
    {
        $transactionId = $payment->transaction_id;

        $this->activityLogService->log(
            'deleted',
            'Payments',
            "Payment \"{$transactionId}\" was deleted."
        );

        $payment->delete();

        return redirect()
            ->route('admin.payments.index')
            ->with(
                'success',
                'Payment deleted successfully.'
            );
    }
}

