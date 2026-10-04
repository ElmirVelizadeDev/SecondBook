<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        $sixMonthsAgo = now()->subMonths(6);

        $query = Review::with([
            'user',
            'book',
        ])
        ->whereHas('book', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
        ->whereNull('archived_at')
        ->where('created_at', '>=', $sixMonthsAgo);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($query) use ($search) {

                $query->where(
                    'comment',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('book', function ($query) use ($search) {

                    $query->where(
                        'title',
                        'like',
                        "%{$search}%"
                    );

                })

                ->orWhereHas('user', function ($query) use ($search) {

                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
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
        }

        /*
        |--------------------------------------------------------------------------
        | Rating Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('rating')) {

            $query->where(
                'rating',
                $request->rating
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $reviews = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $baseQuery = Review::whereHas('book', function ($query) use ($sellerId) {

            $query->where(
                'seller_id',
                $sellerId
            );

        })
        ->whereNull('archived_at')
        ->where(
            'created_at',
            '>=',
            $sixMonthsAgo
        );

        $totalReviews = (clone $baseQuery)
            ->count();

        $averageRating = (clone $baseQuery)
            ->avg('rating');

        $fiveStarReviews = (clone $baseQuery)
            ->where('rating', 5)
            ->count();

        $oneStarReviews = (clone $baseQuery)
            ->where('rating', 1)
            ->count();

        return view(
            'seller.reviews.index',
            compact(
                'reviews',
                'totalReviews',
                'averageRating',
                'fiveStarReviews',
                'oneStarReviews'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Review Details
    |--------------------------------------------------------------------------
    */

    public function show(Review $review)
    {
        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $review->load([
            'user',
            'book',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Seller Ownership
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $review->book &&
            $review->book->seller_id === auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Show Review
        |--------------------------------------------------------------------------
        |
        | Archived reviews can still be opened directly.
        | The 6-month restriction is intentionally not applied here.
        |
        */

        return view(
            'seller.reviews.show',
            compact('review')
        );
    }
}