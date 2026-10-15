<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Category::latest()->get([
                'id',
                'name',
                'slug',
                'description',
                'image',
                'status',
            ]),
        ]);
    }

    public function show(Category $category, Request $request)
    {
        $books = Book::with(['author', 'category'])
            ->where('category_id', $category->id)
            ->where('status', 'approved')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());
                $query->where(fn ($query) => $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%"));
            })
            ->when($request->filled('condition'), fn ($query) => $query->where('condition', $request->input('condition')))
            ->when($request->input('sort') === 'price_low', fn ($query) => $query->orderBy('price'))
            ->when($request->input('sort') === 'price_high', fn ($query) => $query->orderByDesc('price'))
            ->when(!in_array($request->input('sort'), ['price_low', 'price_high'], true), fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $books->getCollection()->transform(
            fn (Book $book) => BookController::serializeBook($book)
        );

        return response()->json([
            'data' => [
                'category' => $category->only([
                    'id',
                    'name',
                    'slug',
                    'description',
                    'image',
                ]),
                'books' => BookController::pagination($books),
            ],
        ]);
    }
}
