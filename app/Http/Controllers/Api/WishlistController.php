<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = $request->user()
            ->wishlists()
            ->with(['book.author', 'book.category'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $wishlists->getCollection()->transform(fn ($wishlist) => [
            'id' => $wishlist->id,
            'book' => $wishlist->book
                ? BookController::serializeBook($wishlist->book)
                : null,
        ]);

        return response()->json(BookController::pagination($wishlists));
    }

    public function state(Book $book, Request $request)
    {
        return response()->json([
            'wishlisted' => $request->user()
                ->wishlists()
                ->where('book_id', $book->id)
                ->exists(),
        ]);
    }
}
