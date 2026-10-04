<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display seller orders.
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();
        $sixMonthsAgo = now()->subMonths(6);

        $query = Order::with([
            'user',
            'book',
        ])
            ->whereHas('book', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->where(function ($query) use ($sixMonthsAgo) {
                $query->where('order_status', '!=', 'delivered')
                    ->orWhere(function ($query) use ($sixMonthsAgo) {
                        $query->where('order_status', 'delivered')
                            ->whereNull('archived_at')
                            ->where('created_at', '>=', $sixMonthsAgo);
                    });
            });

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Order Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_status')) {
            $query->where(
                'order_status',
                $request->order_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
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

        $baseQuery = Order::whereHas('book', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
            ->where(function ($query) use ($sixMonthsAgo) {
                $query->where('order_status', '!=', 'delivered')
                    ->orWhere(function ($query) use ($sixMonthsAgo) {
                        $query->where('order_status', 'delivered')
                            ->whereNull('archived_at')
                            ->where('created_at', '>=', $sixMonthsAgo);
                    });
            });

        $totalOrders = (clone $baseQuery)->count();

        $pendingOrders = (clone $baseQuery)
            ->where('order_status', 'pending')
            ->count();

        $processingOrders = (clone $baseQuery)
            ->where('order_status', 'processing')
            ->count();

        $deliveredOrders = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->count();

        return view('seller.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders'
        ));
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        $sellerId = auth()->id();

        $order->load([
            'user',
            'book',
            'payment',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (!$order->book || $order->book->seller_id !== $sellerId) {
            abort(403);
        }

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $sellerId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (!$order->book || $order->book->seller_id !== $sellerId) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Status
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'order_status' => [
                'required',
                'in:pending,processing,shipped,delivered,cancelled',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Status Change
        |--------------------------------------------------------------------------
        */

        $oldStatus = $order->order_status;
        $newStatus = $validated['order_status'];

        if ($oldStatus !== $newStatus) {
            $order->update([
                'order_status' => $newStatus,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Seller Notification
            |--------------------------------------------------------------------------
            */

            $orderNumber = $order->order_number
                ?? '#' . $order->id;

            $statusLabel = ucfirst(
                str_replace('_', ' ', $newStatus)
            );

            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'order_status_updated',
                'title' => 'Order Status Updated',
                'message' => "Order {$orderNumber} status has been changed to {$statusLabel}.",
                'read_at' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect To Orders Index With Success Message
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('seller.orders.index')
            ->with(
                'success',
                'Order status updated successfully.'
            );
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order)
    {
        $sellerId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (!$order->book || $order->book->seller_id !== $sellerId) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this order.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Restriction
        |--------------------------------------------------------------------------
        */

        if (!in_array($order->order_status, [
            'pending',
            'cancelled',
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending or cancelled orders can be deleted.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Save Order Number Before Deletion
        |--------------------------------------------------------------------------
        */

        $orderNumber = $order->order_number
            ?? '#' . $order->id;

        $order->delete();

        /*
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'order_deleted',
            'title' => 'Order Deleted',
            'message' => "Order {$orderNumber} has been deleted from your orders.",
            'read_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.',
        ]);
    }
}

