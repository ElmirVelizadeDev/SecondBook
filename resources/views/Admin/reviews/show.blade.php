@extends('Layout.Admin.master')

@push('css')
    @vite('resources/css/admin/pages/reviews.css')
@endpush

@section('title', 'Review Details')

@section('content')
    @php
        $cover = $review->book?->cover;
        $avatar = $review->user?->profile_photo;
    @endphp
    <div
        data-vue-page="reviews-admin"
        data-payload="{{ json_encode([
            'view' => 'show',
            'review' => [
                'id' => $review->id, 'rating' => $review->rating, 'comment' => $review->comment,
                'status' => $review->status, 'date' => $review->created_at?->format('M d, Y'),
                'time' => $review->created_at?->format('H:i'),
                'customer' => [
                    'name' => $review->user?->name ?? 'Unknown User', 'email' => $review->user?->email ?? '-',
                    'avatar' => $avatar ? asset('storage/' . $avatar) : null,
                ],
                'book' => [
                    'title' => $review->book?->title ?? 'Deleted Book',
                    'author' => $review->book?->author?->name ?? 'Unknown Author',
                    'cover' => $cover ? (filter_var($cover, FILTER_VALIDATE_URL) ? $cover : asset('storage/' . $cover)) : null,
                ],
            ],
            'urls' => [
                'index' => route('admin.reviews.index'),
                'approve' => route('admin.reviews.approve', $review->id),
                'reject' => route('admin.reviews.reject', $review->id),
                'delete' => route('admin.reviews.destroy', $review->id),
            ],
        ]) }}"
    ></div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
@endpush
