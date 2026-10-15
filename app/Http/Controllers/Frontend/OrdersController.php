<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Orders
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::with('book')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $reviewedBookIds = Review::where('user_id', auth()->id())
            ->pluck('book_id')
            ->toArray();

        $cancelOrderPeriod = (int) Setting::get(
            'cancel_order_period',
            0
        );

        return view(
            'Frontend.orders',
            compact(
                'orders',
                'reviewedBookIds',
                'cancelOrderPeriod'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Order Details
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load([
            'book.author',
            'payment',
        ]);

        $reviewed = Review::where('user_id', auth()->id())
            ->where('book_id', $order->book_id)
            ->exists();

        $cancelOrderPeriod = (int) Setting::get(
            'cancel_order_period',
            0
        );

        return view(
            'Frontend.order-details',
            compact(
                'order',
                'reviewed',
                'cancelOrderPeriod'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Cancel Order
    |--------------------------------------------------------------------------
    */

    public function cancel(Order $order, Request $request)
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $cancelOrderPeriod = (int) Setting::get(
            'cancel_order_period',
            0
        );

        if ($cancelOrderPeriod <= 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Order cancellation is currently disabled.',
                ], 422);
            }

            return back()->with(
                'error',
                'Order cancellation is currently disabled.'
            );
        }

        if (
            in_array(
                $order->order_status,
                ['shipped', 'delivered', 'cancelled'],
                true
            )
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This order can no longer be cancelled.',
                ], 422);
            }

            return back()->with(
                'error',
                'This order can no longer be cancelled.'
            );
        }

        if (
            $order->created_at
                ->copy()
                ->addDays($cancelOrderPeriod)
                ->isPast()
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The cancellation period for this order has expired.',
                ], 422);
            }

            return back()->with(
                'error',
                'The cancellation period for this order has expired.'
            );
        }

        try {
            DB::transaction(function () use ($order) {
                $lockedOrder = Order::where('id', $order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    in_array(
                        $lockedOrder->order_status,
                        ['shipped', 'delivered', 'cancelled'],
                        true
                    )
                ) {
                    throw new \RuntimeException(
                        'This order can no longer be cancelled.'
                    );
                }

                $book = $lockedOrder->book()
                    ->lockForUpdate()
                    ->first();

                if ($book) {
                    $book->increment(
                        'stock',
                        $lockedOrder->quantity
                    );
                }

                $lockedOrder->update([
                    'order_status' => 'cancelled',
                ]);
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully.',
                ]);
            }

            return back()->with(
                'success',
                'Order cancelled successfully.'
            );

        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with(
                'error',
                $e->getMessage()
            );

        } catch (\Throwable $e) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong while cancelling the order.',
                ], 500);
            }

            return back()->with(
                'error',
                'Something went wrong while cancelling the order.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Order Tracking
    |--------------------------------------------------------------------------
    */

    public function tracking(Order $order)
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load('book');

        $statuses = [
            'pending' => [
                'label' => 'Order Placed',
                'icon' => 'bi-bag-check',
                'description' => 'Your order has been placed successfully.',
            ],

            'processing' => [
                'label' => 'Processing',
                'icon' => 'bi-box-seam',
                'description' => 'Your order is being prepared.',
            ],

            'shipped' => [
                'label' => 'Shipped',
                'icon' => 'bi-truck',
                'description' => 'Your order has been shipped.',
            ],

            'delivered' => [
                'label' => 'Delivered',
                'icon' => 'bi-house-check',
                'description' => 'Your order has been delivered successfully.',
            ],
        ];

        return view(
            'Frontend.order-tracking',
            compact(
                'order',
                'statuses'
            )
        );
    }
}