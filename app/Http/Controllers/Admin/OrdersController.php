<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'book']);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function ($bookQuery) use ($search) {
                        $bookQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Order Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'order_status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment')) {
            $query->where(
                'payment_status',
                $request->payment
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {
            $query->whereDate(
                'created_at',
                $request->date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::count();

        $totalPending = Order::where(
            'order_status',
            'pending'
        )->count();

        $totalDelivered = Order::where(
            'order_status',
            'delivered'
        )->count();

        $revenue = Order::where(
            'order_status',
            'delivered'
        )->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | Send data to view
        |--------------------------------------------------------------------------
        */

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'totalPending',
            'totalDelivered',
            'revenue'
        ));
    }

    /**
     * Show create order page.
     */
    public function create()
    {
        $users = User::orderBy('first_name')->get();
        $books = Book::orderBy('title')->get();

        return view(
            'admin.orders.create',
            compact('users', 'books')
        );
    }

    /**
     * Store new order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'book_price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'order_status' => 'required',
            'full_name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-' . time(),
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'book_price' => $request->book_price,
            'quantity' => $request->quantity,
            'total_price' =>
                $request->book_price * $request->quantity,
            'payment_method' =>
                $request->payment_method,
            'payment_status' =>
                $request->payment_status,
            'order_status' =>
                $request->order_status,
            'full_name' =>
                $request->full_name,
            'phone' =>
                $request->phone,
            'country' =>
                $request->country,
            'city' =>
                $request->city,
            'postal_code' =>
                $request->postal_code,
            'address' =>
                $request->address,
            'note' =>
                $request->note,
        ]);

        $this->activityLogService->log(
            'created',
            'Orders',
            "Order \"{$order->order_number}\" was created."
        );

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                'Order created successfully.'
            );
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'book',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    /**
     * Edit order page.
     */
    public function edit(Order $order)
    {
        $users = User::orderBy('first_name')->get();
        $books = Book::orderBy('title')->get();

        return view(
            'admin.orders.edit',
            compact(
                'order',
                'users',
                'books'
            )
        );
    }

    /**
     * Update order.
     */
    public function update(
        Request $request,
        Order $order
    ) {
        $request->validate([
            'user_id' =>
                'required|exists:users,id',

            'book_id' =>
                'required|exists:books,id',

            'book_price' =>
                'required|numeric|min:0',

            'quantity' =>
                'required|integer|min:1',

            'payment_method' =>
                'required',

            'payment_status' =>
                'required',

            'order_status' =>
                'required',

            'full_name' =>
                'required|string',

            'phone' =>
                'required|string',

            'country' =>
                'required|string',

            'city' =>
                'required|string',

            'postal_code' =>
                'nullable|string',

            'address' =>
                'required|string',

            'note' =>
                'nullable|string',
        ]);

        $oldOrderStatus = $order->order_status;
        $oldPaymentStatus = $order->payment_status;

        $order->update([
            'user_id' =>
                $request->user_id,

            'book_id' =>
                $request->book_id,

            'book_price' =>
                $request->book_price,

            'quantity' =>
                $request->quantity,

            'total_price' =>
                $request->book_price * $request->quantity,

            'payment_method' =>
                $request->payment_method,

            'payment_status' =>
                $request->payment_status,

            'order_status' =>
                $request->order_status,

            'full_name' =>
                $request->full_name,

            'phone' =>
                $request->phone,

            'country' =>
                $request->country,

            'city' =>
                $request->city,

            'postal_code' =>
                $request->postal_code,

            'address' =>
                $request->address,

            'note' =>
                $request->note,
        ]);

        $this->activityLogService->log(
            'updated',
            'Orders',
            "Order \"{$order->order_number}\" was updated."
        );

        if ($oldOrderStatus !== $order->order_status) {
            $this->activityLogService->log(
                'updated',
                'Orders',
                "Order \"{$order->order_number}\" status changed from \"{$oldOrderStatus}\" to \"{$order->order_status}\"."
            );
        }

        if ($oldPaymentStatus !== $order->payment_status) {
            $this->activityLogService->log(
                'updated',
                'Orders',
                "Order \"{$order->order_number}\" payment status changed from \"{$oldPaymentStatus}\" to \"{$order->payment_status}\"."
            );
        }

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                'Order updated successfully.'
            );
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order)
    {
        // Orders with refunds cannot be deleted.
        if ($order->refunds()->exists()) {
            $message = 'This order cannot be deleted because it has refund records.';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return redirect()
                ->route('admin.orders.index')
                ->with('error', $message);
        }

        // Paid orders cannot be deleted.
        if ($order->payment_status === 'paid') {
            $message = 'This order cannot be deleted because the payment has already been completed.';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return redirect()
                ->route('admin.orders.index')
                ->with('error', $message);
        }

        // Delivered orders cannot be deleted.
        if ($order->order_status === 'delivered') {
            $message = 'This order cannot be deleted because it has already been delivered.';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return redirect()
                ->route('admin.orders.index')
                ->with('error', $message);
        }

        $orderNumber = $order->order_number;

        $order->delete();

        $this->activityLogService->log(
            'deleted',
            'Orders',
            "Order \"{$orderNumber}\" was deleted."
        );

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                'Order deleted successfully.'
            );
    }
}

