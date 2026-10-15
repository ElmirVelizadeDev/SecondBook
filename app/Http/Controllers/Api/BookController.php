<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            return response()->json([
                'message' => 'Marketplace is currently unavailable.',
            ], 503);
        }

        $query = Book::with(['author', 'category'])
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($authorQuery) use ($search) {
                        $authorQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $categoryId = $request->input('category_id', $request->input('category'));
        if ($categoryId !== null && $categoryId !== '') {
            $query->where('category_id', $categoryId);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

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
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        $books->getCollection()->transform(
            fn (Book $book) => self::serializeBook($book)
        );

        return response()->json([
            'data' => $books->items(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
            'links' => [
                'first' => $books->url(1),
                'last' => $books->url($books->lastPage()),
                'prev' => $books->previousPageUrl(),
                'next' => $books->nextPageUrl(),
            ],
            'categories' => Category::orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function show(Book $book)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            return response()->json([
                'message' => 'Marketplace is currently unavailable.',
            ], 503);
        }

        abort_unless($book->status === 'approved', 404);

        $book->load([
            'author:id,name,bio,photo',
            'category:id,name,slug',
            'publisher:id,name,logo',
            'seller:id,name',
            'seller.store:seller_id,name,slug,logo',
        ]);

        $relatedBooks = Book::with(['author', 'category'])
            ->where('status', 'approved')
            ->where('category_id', $book->category_id)
            ->whereKeyNot($book->id)
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn (Book $relatedBook) => self::serializeBook($relatedBook))
            ->values();

        $authorBooks = Book::with(['author', 'category'])
            ->where('status', 'approved')
            ->where('author_id', $book->author_id)
            ->whereKeyNot($book->id)
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn (Book $authorBook) => self::serializeBook($authorBook))
            ->values();

        $reviews = Review::where('book_id', $book->id)
            ->where('status', 'approved')
            ->with('user:id,name')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $reviews->getCollection()->transform(fn (Review $review) => [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'created_at' => $review->created_at,
            'reviewer' => $review->user?->only(['id', 'name']),
        ]);

        $bookData = self::serializeBook($book);
        $bookData['seller'] = $book->seller?->only(['id', 'name']);

        return response()->json([
            'data' => $bookData + [
                'publisher' => $book->publisher?->only(['id', 'name', 'logo']),
                'store' => $book->seller?->store?->only(['name', 'slug', 'logo']),
            ],
            'related_books' => $relatedBooks,
            'author_books' => $authorBooks,
            'reviews' => self::pagination($reviews),
        ]);
    }

    public static function serializeBook(Book $book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'isbn' => $book->isbn,
            'description' => $book->description,
            'cover' => $book->cover,
            'cover_url' => $book->cover
                ? (filter_var($book->cover, FILTER_VALIDATE_URL)
                    ? $book->cover
                    : asset('storage/' . $book->cover))
                : null,
            'publication_year' => $book->publication_year,
            'pages' => $book->pages,
            'language' => $book->language,
            'price' => $book->price,
            'discounted_price' => $book->discounted_price,
            'discount_label' => $book->discount_label,
            'stock' => $book->stock,
            'condition' => $book->condition,
            'author' => $book->author?->only(['id', 'name']),
            'category' => $book->category?->only(['id', 'name']),
        ];
    }

    public static function pagination(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
    }
}
