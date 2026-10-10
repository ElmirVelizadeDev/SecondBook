@extends('layout.admin.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/reviews.css') }}">
@endpush

@section('title', 'Review Details')

@section('content')

<div class="dashboard-section reviews-page">

    {{-- Page Header --}}
    <div class="dashboard-panel review-detail-header mb-4">
        <div class="panel-header mb-0">

            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="mb-0">
                        Review Details
                    </h5>
                </div>

                <p class="text-muted mb-0 small">
                    View customer feedback and review information
                </p>
            </div>

            <div>
                @if($review->status === 'approved')
                    <span class="review-status-badge approved">
                        <i class="bi bi-check-circle-fill"></i>
                        Approved
                    </span>
                @elseif($review->status === 'pending')
                    <span class="review-status-badge pending">
                        <i class="bi bi-clock-fill"></i>
                        Pending
                    </span>
                @elseif($review->status === 'rejected')
                    <span class="review-status-badge rejected">
                        <i class="bi bi-x-circle-fill"></i>
                        Rejected
                    </span>
                @else
                    <span class="review-status-badge unknown">
                        {{ ucfirst($review->status) }}
                    </span>
                @endif
            </div>

        </div>
    </div>


    <div class="row g-4">

        {{-- LEFT SIDE --}}
        <div class="col-xl-8">

            {{-- Customer & Book --}}
            <div class="dashboard-panel review-detail-card mb-4">

                <div class="review-detail-card-header">
                    <div>
                        <h6 class="mb-1">
                            Review Information
                        </h6>

                        <p class="text-muted small mb-0">
                            Customer and book details
                        </p>
                    </div>

                    <div class="review-detail-id">
                        #{{ $review->id }}
                    </div>
                </div>


                <div class="review-info-grid">

                    {{-- Customer --}}
                    <div class="review-info-item">

                        <span class="review-info-label">
                            Customer
                        </span>

                        <div class="review-customer">

                            <div class="review-detail-avatar">

                                @if($review->user?->profile_photo)

                                    <img
                                        src="{{ asset('storage/' . $review->user->profile_photo) }}"
                                        alt="{{ $review->user->name }}"
                                    >

                                @else

                                    {{ strtoupper(
                                        substr(
                                            $review->user?->name ?? 'U',
                                            0,
                                            1
                                        )
                                    ) }}

                                @endif

                            </div>

                            <div>

                                <strong>
                                    {{ $review->user?->name ?? 'Unknown User' }}
                                </strong>

                                <span>
                                    {{ $review->user?->email ?? '-' }}
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Book --}}
                    <div class="review-info-item">

                        <span class="review-info-label">
                            Book
                        </span>

                        <div class="review-book-detail">

                            @if($review->book?->cover)

                                @php
                                    $cover = $review->book->cover;

                                    $coverUrl = filter_var(
                                        $cover,
                                        FILTER_VALIDATE_URL
                                    )
                                        ? $cover
                                        : asset('storage/' . $cover);
                                @endphp

                                <img
                                    src="{{ $coverUrl }}"
                                    alt="{{ $review->book->title }}"
                                    class="review-detail-book-cover"
                                >

                            @else

                                <div class="review-detail-book-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>

                            @endif

                            <div>

                                <strong>
                                    {{ $review->book?->title ?? 'Deleted Book' }}
                                </strong>

                                <span>
                                    {{ $review->book?->author?->name ?? 'Unknown Author' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Rating --}}
            <div class="dashboard-panel review-detail-card mb-4">

                <div class="review-detail-card-header">

                    <div>
                        <h6 class="mb-1">
                            Customer Rating
                        </h6>

                        <p class="text-muted small mb-0">
                            Rating given by the customer
                        </p>
                    </div>

                    <div class="review-rating-number">
                        {{ $review->rating }}/5
                    </div>

                </div>


                <div class="review-large-rating">

                    <div class="review-large-stars">

                        @for($star = 1; $star <= 5; $star++)

                            @if($star <= $review->rating)

                                <i class="bi bi-star-fill"></i>

                            @else

                                <i class="bi bi-star"></i>

                            @endif

                        @endfor

                    </div>

                    <span>
                        Customer rating
                    </span>

                </div>

            </div>


            {{-- Comment --}}
            <div class="dashboard-panel review-detail-card">

                <div class="review-detail-card-header">

                    <div>
                        <h6 class="mb-1">
                            Customer Review
                        </h6>

                        <p class="text-muted small mb-0">
                            Feedback submitted by the customer
                        </p>
                    </div>

                    <i class="bi bi-chat-square-text review-section-icon"></i>

                </div>


                @if($review->comment)

                    <div class="review-full-comment">
                        {{ $review->comment }}
                    </div>

                @else

                    <div class="review-no-comment">
                        <i class="bi bi-chat-square"></i>

                        <span>
                            This customer did not leave a comment.
                        </span>
                    </div>

                @endif

            </div>

        </div>


        {{-- RIGHT SIDE --}}
        <div class="col-xl-4">

            {{-- Review Summary --}}
            <div class="dashboard-panel review-detail-card mb-4">

                <div class="review-detail-card-header">

                    <div>
                        <h6 class="mb-1">
                            Review Summary
                        </h6>

                        <p class="text-muted small mb-0">
                            Review information
                        </p>
                    </div>

                </div>


                <div class="review-summary-list">

                    <div class="review-summary-row">

                        <span>
                            <i class="bi bi-hash"></i>
                            Review ID
                        </span>

                        <strong>
                            #{{ $review->id }}
                        </strong>

                    </div>


                    <div class="review-summary-row">

                        <span>
                            <i class="bi bi-person"></i>
                            Customer
                        </span>

                        <strong>
                            {{ $review->user?->name ?? 'Unknown' }}
                        </strong>

                    </div>


                    <div class="review-summary-row">

                        <span>
                            <i class="bi bi-star"></i>
                            Rating
                        </span>

                        <strong>
                            {{ $review->rating }}/5
                        </strong>

                    </div>


                    <div class="review-summary-row">

                        <span>
                            <i class="bi bi-calendar3"></i>
                            Submitted
                        </span>

                        <strong>
                            {{ $review->created_at->format('M d, Y') }}
                        </strong>

                    </div>


                    <div class="review-summary-row">

                        <span>
                            <i class="bi bi-clock"></i>
                            Time
                        </span>

                        <strong>
                            {{ $review->created_at->format('H:i') }}
                        </strong>

                    </div>


                    <div class="review-summary-row">

                        <span>
                            <i class="bi bi-activity"></i>
                            Status
                        </span>

                        <strong class="text-capitalize">
                            {{ $review->status }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="dashboard-panel review-detail-card">

                <div class="review-detail-card-header">

                    <div>
                        <h6 class="mb-1">
                            Review Actions
                        </h6>

                        <p class="text-muted small mb-0">
                            Manage this customer review
                        </p>
                    </div>

                </div>


                <div class="review-actions">

                    @if($review->status !== 'approved')

                        <form
                            action="{{ route('admin.reviews.approve', $review->id) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="review-action-btn approve"
                            >
                                <i class="bi bi-check-lg"></i>

                                <span>
                                    Approve Review
                                </span>
                            </button>

                        </form>

                    @endif


                    @if($review->status !== 'rejected')

                        <form
                            action="{{ route('admin.reviews.reject', $review->id) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="review-action-btn reject"
                            >
                                <i class="bi bi-x-lg"></i>

                                <span>
                                    Reject Review
                                </span>
                            </button>

                        </form>

                    @endif


                    <form
                        action="{{ route('admin.reviews.destroy', $review->id) }}"
                        method="POST"
                        id="reviewDeleteForm"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="review-action-btn delete"
                            id="reviewDeleteButton"
                        >
                            <i class="bi bi-trash3"></i>

                            <span>
                                Delete Review
                            </span>
                        </button>
                    </form>


                    <a
                        href="{{ route('admin.reviews.index') }}"
                        class="review-action-btn back"
                    >
                        <i class="bi bi-arrow-left"></i>

                        <span>
                            Back to Reviews
                        </span>
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteForm = document.getElementById('reviewDeleteForm');

    if (!deleteForm) {
        return;
    }

    deleteForm.addEventListener('submit', function (event) {

        event.preventDefault();

        Swal.fire({
            title: 'Delete Review?',
            text: 'This review will be permanently deleted.',
            icon: 'warning',
            width: 430,
            padding: '30px',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'review-delete-popup',
                confirmButton: 'review-delete-confirm',
                cancelButton: 'review-delete-cancel'
            }
        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            const csrfToken = deleteForm.querySelector(
                'input[name="_token"]'
            ).value;

            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while the review is being deleted.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function () {
                    Swal.showLoading();
                }
            });

            fetch(deleteForm.action, {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },

                body: new URLSearchParams({
                    _token: csrfToken,
                    _method: 'DELETE'
                })
            })
            .then(async function (response) {

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Something went wrong.'
                    );
                }

                return data;
            })
            .then(function (data) {

                Swal.fire({
                    icon: 'success',
                    title: 'Review Deleted',
                    text: data.message ||
                        'The review has been deleted successfully.',
                    timer: 1400,
                    showConfirmButton: false
                }).then(function () {

                    window.location.href =
                        "{{ route('admin.reviews.index') }}";

                });

            })
            .catch(function (error) {

                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: error.message ||
                        'Something went wrong while deleting the review.',
                    confirmButtonText: 'OK'
                });

            });

        });

    });

});
</script>

@endpush