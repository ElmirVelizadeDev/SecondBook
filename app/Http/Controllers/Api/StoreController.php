<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Store $store, Request $request)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            return response()->json([
                'message' => 'Marketplace is currently unavailable.',
            ], 503);
        }

        abort_unless($store->isActive(), 404);
        $store->load('seller:id,name');

        $booksQuery = Book::with(['author', 'category'])
            ->where('seller_id', $store->seller_id)
            ->where('status', 'approved')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhereHas('author', fn ($author) => $author->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->input('category')));

        match ($request->input('sort')) {
            'price_low' => $booksQuery->orderBy('price'),
            'price_high' => $booksQuery->orderByDesc('price'),
            'oldest' => $booksQuery->oldest(),
            default => $booksQuery->latest(),
        };

        $books = $booksQuery->paginate(12)->withQueryString();
        $books->getCollection()->transform(
            fn (Book $book) => BookController::serializeBook($book)
        );

        $reviewsQuery = Review::where('status', 'approved')
            ->whereHas('book', fn ($query) => $query
                ->where('seller_id', $store->seller_id)
                ->where('status', 'approved'));

        $reviews = (clone $reviewsQuery)
            ->with(['user:id,name', 'book:id,title'])
            ->latest()
            ->paginate(5, ['*'], 'reviews_page')
            ->withQueryString();

        $reviews->getCollection()->transform(fn (Review $review) => [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'created_at' => $review->created_at,
            'reviewer' => $review->user?->only(['id', 'name']),
            'book' => $review->book?->only(['id', 'title']),
        ]);

        $ratingBreakdown = [];
        for ($rating = 5; $rating >= 1; $rating--) {
            $ratingBreakdown[$rating] = (clone $reviewsQuery)
                ->where('rating', $rating)
                ->count();
        }

        return response()->json([
            'store' => $store->only([
                'id',
                'name',
                'slug',
                'description',
                'logo',
                'phone',
                'address',
                'accept_orders',
                'processing_time',
                'minimum_order_amount',
            ]),
            'seller' => $store->seller?->only(['id', 'name']),
            'books' => BookController::pagination($books),
            'categories' => Category::whereHas('books', fn ($query) => $query
                ->where('seller_id', $store->seller_id)
                ->where('status', 'approved'))
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'book_count' => Book::where('seller_id', $store->seller_id)
                ->where('status', 'approved')
                ->count(),
            'reviews' => BookController::pagination($reviews),
            'review_count' => (clone $reviewsQuery)->count(),
            'average_rating' => ($average = (clone $reviewsQuery)->avg('rating')) !== null
                ? round((float) $average, 1)
                : null,
            'rating_breakdown' => $ratingBreakdown,
        ]);
    }
}
