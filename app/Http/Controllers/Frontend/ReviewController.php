<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Review
    |--------------------------------------------------------------------------
    */

    public function create(Order $order, Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Check Order Ownership
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Review Only Delivered Orders
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $order->order_status === 'delivered',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Load Book
        |--------------------------------------------------------------------------
        */

        $order->load('book');

        abort_unless(
            $order->book,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Check Existing Review
        |--------------------------------------------------------------------------
        */

        $existingReview = Review::where('user_id', auth()->id())
            ->where('book_id', $order->book_id)
            ->first();

        if ($existingReview) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You have already reviewed this book.',
                ], 409);
            }

            return redirect()
                ->route('frontend.orders.show', $order->id)
                ->with(
                    'error',
                    'You have already reviewed this book.'
                );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'order_id' => $order->id,
                    'book' => $order->book->only(['id', 'title', 'cover']),
                ],
            ]);
        }

        return view(
            'Frontend.review',
            compact('order')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Review
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Order $order)
    {
        /*
        |--------------------------------------------------------------------------
        | Check Order Ownership
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Review Only Delivered Orders
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $order->order_status === 'delivered',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Check Book
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $order->book_id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Reviews
        |--------------------------------------------------------------------------
        */

        $existingReview = Review::where('user_id', auth()->id())
            ->where('book_id', $order->book_id)
            ->exists();

        if ($existingReview) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You have already reviewed this book.',
                ], 409);
            }

            return redirect()
                ->route('frontend.orders.show', $order->id)
                ->with(
                    'error',
                    'You have already reviewed this book.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Review
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Review
        |--------------------------------------------------------------------------
        */

        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $order->book_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your review has been submitted and is awaiting approval.',
                'status' => 'pending',
            ], 201);
        }

        return redirect()
            ->route('frontend.orders.show', $order->id)
            ->with(
                'success',
                'Your review has been submitted and is awaiting approval.'
            );
    }
}