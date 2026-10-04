<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BookController extends Controller
{
    /**
     * Display seller books.
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        $query = Book::with([
            'category',
            'author',
            'publisher',
        ])->where('seller_id', $sellerId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $books = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalBooks = Book::where('seller_id', $sellerId)->count();

        $approvedBooks = Book::where('seller_id', $sellerId)
            ->where('status', 'approved')
            ->count();

        $pendingBooks = Book::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->count();

        $rejectedBooks = Book::where('seller_id', $sellerId)
            ->where('status', 'rejected')
            ->count();

        return view('seller.books.index', compact(
            'books',
            'totalBooks',
            'approvedBooks',
            'pendingBooks',
            'rejectedBooks'
        ));
    }


    /**
     * Show create book page.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('seller.books.create', compact(
            'categories',
            'authors',
            'publishers'
        ));
    }


    /**
     * Store a new book.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'isbn' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'author_id' => [
                'nullable',
                'exists:authors,id',
            ],

            'publisher_id' => [
                'nullable',
                'exists:publishers,id',
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'cover' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],

            'pages' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'language' => [
                'nullable',
                'string',
                'max:100',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:1',
            ],

            'condition' => [
                'required',
                'in:new,like_new,good,fair',
            ],
        ]);

        $coverPath = null;

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')
                ->store('books', 'public');
        }

        $book = Book::create([
            'seller_id' => auth()->id(),
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'author_id' => $validated['author_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover' => $coverPath,
            'publication_year' => $validated['publication_year'] ?? null,
            'pages' => $validated['pages'] ?? null,
            'language' => $validated['language'] ?? 'English',
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'condition' => $validated['condition'],
            'status' => 'pending',
        ]);

        /*
         * Seller notification
         */
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'book_created',
            'title' => 'Book Created',
            'message' => "\"{$book->title}\" has been added to your books and is waiting for admin approval.",
            'read_at' => null,
        ]);

        return redirect()
            ->route('seller.books.index')
            ->with(
                'success',
                'Book added successfully and is waiting for admin approval.'
            );
    }


    /**
     * Display a single book.
     */
    public function show(Book $book)
    {
        if ($book->seller_id !== auth()->id()) {
            abort(403);
        }

        $book->load([
            'category',
            'author',
            'publisher',
        ]);

        return view('seller.books.show', compact('book'));
    }


    /**
     * Show edit book page.
     */
    public function edit(Book $book)
    {
        if ($book->seller_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('seller.books.edit', compact(
            'book',
            'categories',
            'authors',
            'publishers'
        ));
    }


    /**
     * Update seller book.
     */
    public function update(Request $request, Book $book)
    {
        if ($book->seller_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'isbn' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'author_id' => [
                'nullable',
                'exists:authors,id',
            ],

            'publisher_id' => [
                'nullable',
                'exists:publishers,id',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'cover' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],

            'pages' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'language' => [
                'nullable',
                'string',
                'max:100',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:1',
            ],

            'condition' => [
                'required',
                'in:new,like_new,good,fair',
            ],
        ]);

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }

            $validated['cover'] = $request->file('cover')
                ->store('books', 'public');
        } else {
            $validated['cover'] = $book->cover;
        }

        /*
         * Any seller edit requires admin approval again.
         */
        $validated['status'] = 'pending';

        $book->update($validated);

        /*
         * Seller notification
         */
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'book_updated',
            'title' => 'Book Updated',
            'message' => "\"{$book->title}\" has been updated successfully and is waiting for admin approval again.",
            'read_at' => null,
        ]);

        return redirect()
            ->route('seller.books.index')
            ->with(
                'success',
                'Book updated successfully and is waiting for admin approval.'
            );
    }


    /**
     * Delete seller book.
     */
    public function destroy(Book $book)
    {
        if ($book->seller_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this book.',
            ], 403);
        }

        /*
        * Save the title before deleting the book.
        */
        $bookTitle = $book->title;

        try {

            /*
            * A book that already exists in an order
            * must not be physically deleted.
            *
            * Orders are historical records and must remain
            * connected to their original book.
            */
            if ($book->orders()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This book cannot be deleted because it is already associated with an order.',
                ], 422);
            }

            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }

            $book->delete();

            /*
            * Seller notification
            *
            * The notification is created after the book is
            * deleted, using the saved title.
            */
            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'book_deleted',
                'title' => 'Book Deleted',
                'message' => "\"{$bookTitle}\" has been removed from your books.",
                'read_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully.',
            ]);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete the book.',
            ], 500);
        }
    }
}
