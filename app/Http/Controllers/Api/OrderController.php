<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('book:id,title,cover')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $orders->getCollection()->transform(fn (Order $order) => [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'quantity' => $order->quantity,
            'total_price' => $order->total_price,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'created_at' => $order->created_at,
            'book' => $order->book?->only(['id', 'title', 'cover']),
        ]);

        return response()->json(BookController::pagination($orders));
    }

    public function show(Order $order, Request $request)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['book.author', 'payment', 'shipping']);

        return response()->json([
            'data' => $order->only([
                'id',
                'order_number',
                'book_id',
                'book_price',
                'quantity',
                'total_price',
                'shipping_fee',
                'payment_method',
                'payment_status',
                'order_status',
                'full_name',
                'phone',
                'country',
                'city',
                'postal_code',
                'address',
                'delivery_estimate',
                'created_at',
            ]) + [
                'book' => $order->book
                    ? BookController::serializeBook($order->book)
                    : null,
                'payment' => $order->payment?->only([
                    'payment_status',
                    'payment_method',
                    'paid_at',
                ]),
                'shipping' => $order->shipping?->only([
                    'name',
                    'delivery_time',
                ]),
                'reviewed' => Review::where('user_id', $request->user()->id)
                    ->where('book_id', $order->book_id)
                    ->exists(),
                'cancel_order_period' => (int) Setting::get('cancel_order_period', 0),
            ],
        ]);
    }

    public function tracking(Order $order, Request $request)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return response()->json([
            'data' => [
                'order_number' => $order->order_number,
                'order_status' => $order->order_status,
                'created_at' => $order->created_at,
                'book' => $order->book()->first(['id', 'title', 'cover']),
            ],
        ]);
    }
}
