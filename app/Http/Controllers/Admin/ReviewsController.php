<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewsController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display all reviews.
     */
    public function index(Request $request): View
    {
        $query = Review::with([
            'user',
            'book',
        ])->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('book', function ($bookQuery) use ($search) {
                        $bookQuery->where(
                            'title',
                            'like',
                            "%{$search}%"
                        );
                    })
                    ->orWhere(
                        'comment',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        // Rating Filter
        if ($request->filled('rating')) {
            $query->where(
                'rating',
                $request->input('rating')
            );
        }

        $reviews = $query
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalReviews = Review::count();

        $pendingReviews = Review::where(
            'status',
            'pending'
        )->count();

        $approvedReviews = Review::where(
            'status',
            'approved'
        )->count();

        $rejectedReviews = Review::where(
            'status',
            'rejected'
        )->count();

        return view(
            'Admin.reviews.index',
            compact(
                'reviews',
                'totalReviews',
                'pendingReviews',
                'approvedReviews',
                'rejectedReviews'
            )
        );
    }

    /**
     * Display a single review.
     */
    public function show(Review $review): View
    {
        $review->load([
            'user',
            'book',
        ]);

        return view(
            'Admin.reviews.show',
            compact('review')
        );
    }

    /**
     * Approve a review.
     */
    public function approve(Review $review): RedirectResponse
    {
        $oldStatus = $review->status;

        $review->update([
            'status' => 'approved',
        ]);

        $this->activityLogService->log(
            'updated',
            'Reviews',
            "Review status changed from \"{$oldStatus}\" to \"approved\"."
        );

        return redirect()
            ->route('admin.reviews.index')
            ->with(
                'success',
                'Review approved successfully.'
            );
    }

    /**
     * Reject a review.
     */
    public function reject(Review $review): RedirectResponse
    {
        $oldStatus = $review->status;

        $review->update([
            'status' => 'rejected',
        ]);

        $this->activityLogService->log(
            'updated',
            'Reviews',
            "Review status changed from \"{$oldStatus}\" to \"rejected\"."
        );

        return redirect()
            ->route('admin.reviews.index')
            ->with(
                'success',
                'Review rejected successfully.'
            );
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review)
    {
        $reviewId = $review->id;

        $this->activityLogService->log(
            'deleted',
            'Reviews',
            "Review #{$reviewId} was deleted."
        );

        $review->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with(
                'success',
                'Review deleted successfully.'
            );
    }
}

