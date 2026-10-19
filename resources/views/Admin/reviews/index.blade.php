@extends('Layout.Admin.master')

@push('css')
    @vite('resources/css/admin/pages/reviews.css')
@endpush

@section('title', 'Reviews')

@section('content')
    <div
        data-vue-page="reviews-admin"
        data-payload="{{ json_encode([
            'view' => 'index',
            'reviews' => $reviews->getCollection()->map(function ($review) {
                $cover = $review->book?->cover;
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'status' => $review->status,
                    'date' => $review->created_at?->format('M d, Y'),
                    'time' => $review->created_at?->format('H:i'),
                    'customer' => [
                        'name' => $review->user?->name ?? 'Unknown User',
                        'email' => $review->user?->email ?? '-',
                        'avatar' => $review->user?->profile_photo ? asset('storage/' . $review->user->profile_photo) : null,
                    ],
                    'book' => [
                        'title' => $review->book?->title ?? 'Deleted Book',
                        'author' => $review->book?->author?->name ?? '-',
                        'cover' => $cover ? (filter_var($cover, FILTER_VALIDATE_URL) ? $cover : asset('storage/' . $cover)) : null,
                    ],
                    'show_url' => route('admin.reviews.show', $review->id),
                    'approve_url' => route('admin.reviews.approve', $review->id),
                    'reject_url' => route('admin.reviews.reject', $review->id),
                    'delete_url' => route('admin.reviews.destroy', $review->id),
                ];
            })->values(),
            'statistics' => [
                'total' => $totalReviews,
                'pending' => $pendingReviews,
                'approved' => $approvedReviews,
                'rejected' => $rejectedReviews,
            ],
            'filters' => ['search' => request('search', ''), 'status' => request('status', ''), 'rating' => request('rating', '')],
            'pagination' => [
                'total' => $reviews->total(), 'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(), 'first_item' => $reviews->firstItem(),
                'last_item' => $reviews->lastItem(), 'links' => $reviews->linkCollection()->toArray(),
            ],
            'urls' => ['index' => route('admin.reviews.index')],
            'success' => session('success'),
        ]) }}"
    ></div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
@endpush
