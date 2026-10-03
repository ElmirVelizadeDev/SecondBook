<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Store;
use App\Models\Setting;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Request $request, Store $store)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        /*
        |--------------------------------------------------------------------------
        | Store must be active
        |--------------------------------------------------------------------------
        */

        if (!$store->isActive()) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Store Seller
        |--------------------------------------------------------------------------
        */

        $store->load('seller');

        /*
        |--------------------------------------------------------------------------
        | Store Books
        |--------------------------------------------------------------------------
        */

        $booksQuery = Book::with([
            'author',
            'category',
        ])
            ->where('seller_id', $store->seller_id)
            ->where('status', 'approved');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            if ($search !== '') {
                $booksQuery->where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('isbn', 'LIKE', "%{$search}%")
                        ->orWhereHas('author', function ($authorQuery) use ($search) {
                            $authorQuery->where(
                                'name',
                                'LIKE',
                                "%{$search}%"
                            );
                        })
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where(
                                'name',
                                'LIKE',
                                "%{$search}%"
                            );
                        });
                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $booksQuery->where(
                'category_id',
                $request->input('category')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($request->input('sort')) {
            case 'price_low':
                $booksQuery->orderBy('price', 'asc');
                break;

            case 'price_high':
                $booksQuery->orderBy('price', 'desc');
                break;

            case 'oldest':
                $booksQuery->orderBy('created_at', 'asc');
                break;

            case 'newest':
                $booksQuery->orderBy('created_at', 'desc');
                break;

            default:
                $booksQuery->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Paginated Books
        |--------------------------------------------------------------------------
        */

        $books = $booksQuery
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Store Statistics
        |--------------------------------------------------------------------------
        */

        $approvedBooksQuery = Book::where('seller_id', $store->seller_id)
            ->where('status', 'approved');

        $booksCount = (clone $approvedBooksQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Store Reviews
        |--------------------------------------------------------------------------
        |
        | Reviews belong to books, so the store rating is calculated
        | from approved reviews belonging to this seller's approved books.
        |
        */

        $reviewsQuery = \App\Models\Review::query()
            ->where('status', 'approved')
            ->whereHas('book', function ($query) use ($store) {
                $query->where('seller_id', $store->seller_id)
                    ->where('status', 'approved');
            });

        $reviewsCount = (clone $reviewsQuery)->count();

        $averageRating = (clone $reviewsQuery)->avg('rating');

        $averageRating = $averageRating !== null
            ? round((float) $averageRating, 1)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Rating Breakdown
        |--------------------------------------------------------------------------
        */

        $ratingBreakdown = [];

        for ($rating = 5; $rating >= 1; $rating--) {
            $ratingBreakdown[$rating] = (clone $reviewsQuery)
                ->where('rating', $rating)
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Recent Reviews
        |--------------------------------------------------------------------------
        */

        $reviews = (clone $reviewsQuery)
            ->with([
                'user',
                'book',
            ])
            ->latest()
            ->paginate(5, ['*'], 'reviews_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Categories Available In Store
        |--------------------------------------------------------------------------
        */

        $categories = \App\Models\Category::whereHas('books', function ($query) use ($store) {
            $query->where('seller_id', $store->seller_id)
                ->where('status', 'approved');
        })
            ->orderBy('name')
            ->get();

        return view(
            'Frontend.store',
            compact(
                'store',
                'books',
                'categories',
                'booksCount',
                'reviews',
                'reviewsCount',
                'averageRating',
                'ratingBreakdown'
            )
        );
    }
}