<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Notification;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class BookRequestController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Display pending seller book requests.
     */
    public function index(Request $request)
    {
        $query = Book::with([
            'seller',
            'category',
            'author',
            'publisher',
        ])
            ->whereNotNull('seller_id')
            ->where('status', 'pending')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('isbn', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('seller', function ($sellerQuery) use ($search) {
                            $sellerQuery->where(
                                'name',
                                'LIKE',
                                '%' . $search . '%'
                            );
                        })
                        ->orWhereHas('author', function ($authorQuery) use ($search) {
                            $authorQuery->where(
                                'name',
                                'LIKE',
                                '%' . $search . '%'
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
        | Results
        |--------------------------------------------------------------------------
        */

        $bookRequests = $query->get();

        return view(
            'admin.book-requests.index',
            compact('bookRequests')
        );
    }

    /**
     * Store is not used for admin book requests.
     */
    public function store(Request $request)
    {
        return redirect()
            ->route('admin.book.requests.index')
            ->with(
                'error',
                'Book requests are created by sellers from the Sell a Book page.'
            );
    }

    /**
     * Show/edit a seller book request.
     */
    public function edit(Book $book)
    {
        $book->load([
            'seller',
            'category',
            'author',
            'publisher',
        ]);

        return view(
            'admin.book-requests.edit',
            [
                'bookRequest' => $book,
            ]
        );
    }

    /**
     * Update seller book request.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,approved,rejected,changes_requested',
            ],
            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $book->load('seller');

        $status = $validated['status'];
        $message = trim($validated['message'] ?? '');

        $oldStatus = $book->status;

        /*
        |--------------------------------------------------------------------------
        | Update Book Status
        |--------------------------------------------------------------------------
        */

        $book->update([
            'status' => $status,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create User Notification
        |--------------------------------------------------------------------------
        */

        if ($book->seller_id && $message !== '') {
            $title = match ($status) {
                'approved' => 'Book Request Approved',
                'rejected' => 'Book Request Rejected',
                'changes_requested' => 'Changes Requested',
                default => 'Book Request Updated',
            };

            Notification::create([
                'user_id' => $book->seller_id,
                'type' => 'book_request',
                'title' => $title,
                'message' => $message,
                'read_at' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->log(
            'updated',
            'Book Requests',
            "Book request \"{$book->title}\" status changed from {$oldStatus} to {$status}."
        );

        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        $successMessage = match ($status) {
            'approved' => 'Book request approved successfully.',
            'rejected' => 'Book request rejected and seller notified.',
            'changes_requested' => 'Changes requested and seller notified.',
            default => 'Book request status updated successfully.',
        };

        return redirect()
            ->route('admin.book.requests.index')
            ->with(
                'success',
                $successMessage
            );
    }

    /**
     * Delete a seller book request.
     */
    public function destroy(Book $book)
    {
        $bookTitle = $book->title;

        $this->activityLogService->log(
            'deleted',
            'Book Requests',
            "Book request \"{$bookTitle}\" was deleted."
        );

        $book->delete();

        return redirect()
            ->route('admin.book.requests.index')
            ->with(
                'success',
                'Book request deleted successfully.'
            );
    }
}

