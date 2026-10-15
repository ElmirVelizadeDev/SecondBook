<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment Page
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        /*
        |--------------------------------------------------------------------------
        | Make sure this order belongs to the logged-in user
        |--------------------------------------------------------------------------
        */

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Payment
        |--------------------------------------------------------------------------
        */

        $payment = Payment::where('order_id', $order->id)
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Payment must exist
        |--------------------------------------------------------------------------
        */

        if (!$payment) {
            return redirect()
                ->route('frontend.orders')
                ->with(
                    'error',
                    'Payment information was not found for this order.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Paid
        |--------------------------------------------------------------------------
        */

        if ($payment->payment_status === 'paid') {
            return redirect()
                ->route('frontend.orders')
                ->with(
                    'success',
                    'This order has already been paid.'
                );
        }

        return redirect()
            ->route('frontend.orders')
            ->with(
                'error',
                'Payment processing is unavailable because no payment provider is configured. The payment remains pending.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Process Payment
    |--------------------------------------------------------------------------
    */

    public function process(Request $request, Order $order)
    {
        /*
        |--------------------------------------------------------------------------
        | Make sure this order belongs to the logged-in user
        |--------------------------------------------------------------------------
        */

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Payment
        |--------------------------------------------------------------------------
        */

        $payment = Payment::where('order_id', $order->id)
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Payment must exist
        |--------------------------------------------------------------------------
        */

        if (!$payment) {
            return redirect()
                ->route('frontend.orders')
                ->with(
                    'error',
                    'Payment information was not found for this order.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Payment
        |--------------------------------------------------------------------------
        */

        if ($payment->payment_status === 'paid') {
            return redirect()
                ->route('frontend.orders')
                ->with(
                    'success',
                    'This order has already been paid.'
                );
        }

        $message = 'Payment processing is unavailable because no payment provider is configured. The payment remains pending.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'payment_status' => $payment->payment_status,
            ], 503);
        }

        return redirect()
            ->route('frontend.orders')
            ->with('error', $message);
    }
}
