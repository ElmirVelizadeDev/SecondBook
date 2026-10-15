<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Order $order, Request $request)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $payment = Payment::where('order_id', $order->id)
            ->latest()
            ->first();

        abort_unless($payment, 404);

        return response()->json([
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_status' => $payment->payment_status,
                'paid_at' => $payment->paid_at,
            ],
        ]);
    }
}
