<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display books listing.
     */
    public function index(Request $request)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        $query = Book::with([
            'author',
            'category',
        ])->where('status', 'approved');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
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
            $query->where(
                'category_id',
                $request->input('category')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Condition Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('condition')) {
            $query->where(
                'condition',
                $request->input('condition')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($request->input('sort')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('price', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $books = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name', 'asc')->get();

        return view(
            'Frontend.books',
            compact(
                'books',
                'categories'
            )
        );
    }

    /**
     * Display a single book.
     */

    public function show(Book $book)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        if ($book->status !== 'approved') {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Book Relations
        |--------------------------------------------------------------------------
        */

        $book->load([
            'author',
            'category',
            'publisher',
            'seller.store',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Related Books
        |--------------------------------------------------------------------------
        */

        // Eyni kateqoriyadan olan digər kitablar
        $relatedBooks = Book::with([
            'author',
            'category',
        ])
            ->where('status', 'approved')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->latest()
            ->take(4)
            ->get();

        // Eyni müəllifin digər kitabları
        $authorBooks = Book::with([
            'author',
            'category',
        ])
            ->where('status', 'approved')
            ->where('author_id', $book->author_id)
            ->where('id', '!=', $book->id)
            ->latest()
            ->take(4)
            ->get();

        return view(
            'Frontend.book-details',
            compact(
                'book',
                'relatedBooks',
                'authorBooks'
            )
        );
    }
}

