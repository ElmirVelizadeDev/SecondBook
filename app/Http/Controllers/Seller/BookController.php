<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Publisher;
use App\Models\User;
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
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'book_created',
            'title' => 'Book Created',
            'message' => "\"{$book->title}\" has been added to your books and is waiting for admin approval.",
            'read_at' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin Notification
        |--------------------------------------------------------------------------
        */

        $this->notifyAdmins(
            'book_created',
            'New Seller Book',
            'Seller "' . $this->sellerName() . '" added "' .
            $book->title .
            '" and it is waiting for approval.'
        );

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
        |--------------------------------------------------------------------------
        | Seller Edit Requires Admin Approval
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'pending';

        $book->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'book_updated',
            'title' => 'Book Updated',
            'message' => "\"{$book->title}\" has been updated successfully and is waiting for admin approval again.",
            'read_at' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin Notification
        |--------------------------------------------------------------------------
        */

        $this->notifyAdmins(
            'book_updated',
            'Seller Book Updated',
            'Seller "' . $this->sellerName() . '" updated "' .
            $book->title .
            '". Approval is required again.'
        );

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

        $bookTitle = $book->title;

        try {
            /*
            |--------------------------------------------------------------------------
            | Delete Restriction
            |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Seller Notification
            |--------------------------------------------------------------------------
            */

            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'book_deleted',
                'title' => 'Book Deleted',
                'message' => "\"{$bookTitle}\" has been removed from your books.",
                'read_at' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Admin Notification
            |--------------------------------------------------------------------------
            */

            $this->notifyAdmins(
                'book_deleted',
                'Seller Book Deleted',
                'Seller "' . $this->sellerName() . '" deleted "' .
                $bookTitle .
                '".'
            );

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

    /**
     * Get authenticated seller name.
     */
    private function sellerName(): string
    {
        $seller = auth()->user();

        return $seller?->full_name
            ?: $seller?->name
            ?: 'Seller';
    }

    /**
     * Send notification to all active admins.
     */
    private function notifyAdmins(
        string $type,
        string $title,
        string $message
    ): void {
        $adminIds = User::query()
            ->where('role', 'admin')
            ->where('status', 'active')
            ->pluck('id');

        if ($adminIds->isEmpty()) {
            return;
        }

        $now = now();

        $notifications = $adminIds->map(function ($adminId) use (
            $type,
            $title,
            $message,
            $now
        ) {
            return [
                'user_id' => $adminId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        Notification::insert($notifications);
    }
}
