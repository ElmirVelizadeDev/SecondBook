<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = Author::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());
                $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $authors->getCollection()->transform(
            fn (Author $author) => $author->only(['id', 'name', 'bio', 'photo', 'status'])
        );

        return response()->json(BookController::pagination($authors));
    }

    public function show(Author $author)
    {
        $books = Book::with(['author', 'category'])
            ->where('author_id', $author->id)
            ->where('status', 'approved')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $books->getCollection()->transform(
            fn (Book $book) => BookController::serializeBook($book)
        );

        return response()->json([
            'data' => [
                'author' => $author->only(['id', 'name', 'bio', 'photo']),
                'books' => BookController::pagination($books),
            ],
        ]);
    }
}
