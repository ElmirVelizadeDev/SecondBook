<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!Setting::get('marketplace_enabled', true)) {
            return response()->json([
                'message' => 'Marketplace is currently unavailable.',
            ], 503);
        }

        $baseQuery = static fn () => Book::with(['author', 'category'])
            ->where('status', 'approved');

        $bestSellers = $baseQuery()
            ->leftJoin(
                DB::raw('(SELECT book_id, SUM(quantity) AS total_sold FROM orders GROUP BY book_id) AS sales'),
                'books.id',
                '=',
                'sales.book_id'
            )
            ->select('books.*')
            ->orderByDesc(DB::raw('COALESCE(sales.total_sold, 0)'))
            ->limit(8)
            ->get();

        $now = now();

        $specialOffers = Book::with(['author', 'category'])
            ->where('status', 'approved')
            ->where('discount_type', '!=', 'none')
            ->where('discount_value', '>', 0)
            ->where(fn ($query) => $query->whereNull('discount_start_at')->orWhere('discount_start_at', '<=', $now))
            ->where(fn ($query) => $query->whereNull('discount_end_at')->orWhere('discount_end_at', '>=', $now))
            ->latest()
            ->limit(5)
            ->get();

        $books = static fn ($items) => $items->map(
            fn (Book $book) => BookController::serializeBook($book)
        )->values();

        return response()->json([
            'categories' => Category::where('status', 1)
                ->latest()
                ->get(['id', 'name', 'slug', 'description', 'image']),
            'featured_books' => $books($baseQuery()->latest()->limit(4)->get()),
            'best_sellers' => $books($bestSellers),
            'trending_books' => $books($baseQuery()->latest()->limit(8)->get()),
            'new_arrivals' => $books($baseQuery()->latest('created_at')->limit(8)->get()),
            'editor_picks' => $books($baseQuery()->latest()->limit(8)->get()),
            'most_loved_books' => $books($baseQuery()->latest()->limit(8)->get()),
            'budget_deals' => $books($baseQuery()->orderBy('price')->limit(8)->get()),
            'special_offers' => $books($specialOffers),
        ]);
    }
}
