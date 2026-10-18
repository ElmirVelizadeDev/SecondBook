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
        $baseQuery = $this->sellerReviewsQuery($sellerId, $sixMonthsAgo);
        $query = $this->filterReviews(clone $baseQuery, $request);

        $reviews = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

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
            'Seller.reviews.index',
            compact(
                'reviews',
                'totalReviews',
                'averageRating',
                'fiveStarReviews',
                'oneStarReviews'
            )
        );
    }

    /** Return the same seller-scoped review listing as JSON for the Vue page. */
    public function indexData(Request $request)
    {
        $sellerId = auth()->id();
        $sixMonthsAgo = now()->subMonths(6);
        $baseQuery = $this->sellerReviewsQuery($sellerId, $sixMonthsAgo);

        $reviews = $this->filterReviews(clone $baseQuery, $request)
            ->with('book.author')
            ->latest()
            ->paginate(10)
            ->withPath(route('seller.reviews.index'))
            ->withQueryString();

        $totalReviews = (clone $baseQuery)->count();
        $averageRating = (clone $baseQuery)->avg('rating');
        $fiveStarReviews = (clone $baseQuery)->where('rating', 5)->count();
        $oneStarReviews = (clone $baseQuery)->where('rating', 1)->count();

        $reviews->getCollection()->transform(function (Review $review) {
            $buyer = $review->user;
            $buyerName = $buyer?->name
                ?? trim(($buyer?->first_name ?? '') . ' ' . ($buyer?->last_name ?? ''))
                ?: 'Unknown Buyer';

            return [
                'id' => $review->id,
                'rating' => (int) $review->rating,
                'comment' => $review->comment,
                'created_date' => $review->created_at?->format('M d, Y') ?? '—',
                'created_time' => $review->created_at?->format('H:i') ?? '—',
                'buyer' => [
                    'name' => $buyerName,
                    'initial' => strtoupper(substr($buyer?->name ?? $buyer?->first_name ?? 'U', 0, 1)),
                    'email' => $buyer?->email,
                ],
                'book' => $review->book ? [
                    'title' => $review->book->title,
                    'cover_url' => $review->book->cover ? asset('storage/' . $review->book->cover) : null,
                    'author' => $review->book->author?->name,
                ] : null,
                'show_url' => route('seller.reviews.show', $review),
            ];
        });

        return response()->json([
            'statistics' => [
                'total_reviews' => $totalReviews,
                'average_rating' => $averageRating ? number_format($averageRating, 1) : '0.0',
                'five_star_reviews' => $fiveStarReviews,
                'one_star_reviews' => $oneStarReviews,
            ],
            'reviews' => $reviews,
        ]);
    }

    private function sellerReviewsQuery($sellerId, $sixMonthsAgo)
    {
        return Review::with(['user', 'book'])
            ->whereHas('book', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo);
    }

    private function filterReviews($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('book', function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        return $query;
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
            'Seller.reviews.show',
            compact('review')
        );
    }

    /** Return the data used by the seller's read-only review details page. */
    public function showData(Review $review)
    {
        $review->load([
            'user',
            'book.author',
        ]);

        abort_unless(
            $review->book &&
            $review->book->seller_id === auth()->id(),
            403
        );

        $buyer = $review->user;
        $buyerName = $buyer?->name
            ?? trim(($buyer?->first_name ?? '') . ' ' . ($buyer?->last_name ?? ''))
            ?: 'Unknown Buyer';

        return response()->json([
            'id' => $review->id,
            'rating' => (float) $review->rating,
            'comment' => $review->comment,
            'created_date' => $review->created_at?->format('M d, Y') ?? '—',
            'created_time' => $review->created_at?->format('H:i') ?? '—',
            'created_full' => $review->created_at?->format('M d, Y H:i') ?? '—',
            'updated_full' => $review->updated_at?->format('M d, Y H:i'),
            'buyer' => [
                'name' => $buyerName,
                'initial' => strtoupper(substr($buyer?->name ?? $buyer?->first_name ?? 'U', 0, 1)),
                'email' => $buyer?->email,
            ],
            'book' => $review->book ? [
                'title' => $review->book->title,
                'cover_url' => $review->book->cover ? asset('storage/' . $review->book->cover) : null,
                'author' => $review->book->author?->name,
                'isbn' => $review->book->isbn,
            ] : null,
        ]);
    }
}
