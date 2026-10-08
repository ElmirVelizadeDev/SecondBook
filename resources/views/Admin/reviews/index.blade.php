@extends('layout.admin.master')

@section('title', 'Reviews')

@section('content')

<div class="dashboard-section reviews-page">


{{-- Page Header --}}
<div class="dashboard-panel reviews-header-panel mb-4">

    <div class="reviews-page-header">

        <div>

            <div class="reviews-title-row">

                <div class="reviews-title-icon">
                    <i class="bi bi-chat-square-text"></i>
                </div>

                <div>

                    <h5 class="mb-1">
                        Reviews
                    </h5>

                    <p class="text-muted mb-0 small">
                        Manage customer reviews and feedback on SecondBook
                    </p>

                </div>

            </div>

        </div>

        <div class="reviews-header-count">

            <span class="reviews-header-count-label">
                Total
            </span>

            <strong id="reviewsTotalHeader">
                {{ $totalReviews }}
            </strong>

        </div>

    </div>

</div>


{{-- Statistics --}}
<div class="row g-3 mb-4">

    {{-- Total --}}
    <div class="col-xl-3 col-md-6">

        <div class="dashboard-panel review-stat-card h-100">

            <div class="review-stat-content">

                <div class="review-stat-icon total">
                    <i class="bi bi-chat-square-text"></i>
                </div>

                <div class="review-stat-info">

                    <span class="review-stat-label">
                        Total Reviews
                    </span>

                    <strong id="totalReviewsCount">
                        {{ $totalReviews }}
                    </strong>

                </div>

            </div>

            <div class="review-stat-footer">

                <span>
                    All customer reviews
                </span>

                <i class="bi bi-arrow-up-right"></i>

            </div>

        </div>

    </div>


    {{-- Pending --}}
    <div class="col-xl-3 col-md-6">

        <div class="dashboard-panel review-stat-card h-100">

            <div class="review-stat-content">

                <div class="review-stat-icon pending">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div class="review-stat-info">

                    <span class="review-stat-label">
                        Pending
                    </span>

                    <strong id="pendingReviewsCount">
                        {{ $pendingReviews }}
                    </strong>

                </div>

            </div>

            <div class="review-stat-footer">

                <span>
                    Waiting for review
                </span>

                <i class="bi bi-hourglass-split"></i>

            </div>

        </div>

    </div>


    {{-- Approved --}}
    <div class="col-xl-3 col-md-6">

        <div class="dashboard-panel review-stat-card h-100">

            <div class="review-stat-content">

                <div class="review-stat-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div class="review-stat-info">

                    <span class="review-stat-label">
                        Approved
                    </span>

                    <strong id="approvedReviewsCount">
                        {{ $approvedReviews }}
                    </strong>

                </div>

            </div>

            <div class="review-stat-footer">

                <span>
                    Published reviews
                </span>

                <i class="bi bi-check2"></i>

            </div>

        </div>

    </div>


    {{-- Rejected --}}
    <div class="col-xl-3 col-md-6">

        <div class="dashboard-panel review-stat-card h-100">

            <div class="review-stat-content">

                <div class="review-stat-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div class="review-stat-info">

                    <span class="review-stat-label">
                        Rejected
                    </span>

                    <strong id="rejectedReviewsCount">
                        {{ $rejectedReviews }}
                    </strong>

                </div>

            </div>

            <div class="review-stat-footer">

                <span>
                    Rejected reviews
                </span>

                <i class="bi bi-x-lg"></i>

            </div>

        </div>

    </div>

</div>


{{-- Filters --}}
<div class="dashboard-panel reviews-filter-panel mb-4">

    <div class="reviews-filter-header">

        <div>

            <h6 class="mb-1">
                Filter Reviews
            </h6>

            <p class="text-muted small mb-0">
                Search and filter customer feedback
            </p>

        </div>

        <div class="reviews-filter-icon">
            <i class="bi bi-funnel"></i>
        </div>

    </div>


    <form
        method="GET"
        action="{{ route('admin.reviews.index') }}"
        class="reviews-filter-form"
    >

        {{-- Search --}}
        <div class="reviews-search-field">

            <label class="form-label">
                Search
            </label>

            <div class="reviews-search-group">

                <div class="reviews-search-input-wrap">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="User, email, book or review..."
                    >

                </div>

                <button
                    type="submit"
                    class="reviews-search-button"
                >
                    <i class="bi bi-search"></i>
                    <span>
                        Search
                    </span>
                </button>

            </div>

        </div>


        {{-- Status --}}
        <div class="reviews-filter-field">

            <label class="form-label">
                Status
            </label>

            <select
                name="status"
                class="form-select"
            >

                <option value="">
                    All Status
                </option>

                <option
                    value="approved"
                    @selected(request('status') === 'approved')
                >
                    Approved
                </option>

                <option
                    value="pending"
                    @selected(request('status') === 'pending')
                >
                    Pending
                </option>

                <option
                    value="rejected"
                    @selected(request('status') === 'rejected')
                >
                    Rejected
                </option>

            </select>

        </div>


        {{-- Rating --}}
        <div class="reviews-filter-field">

            <label class="form-label">
                Rating
            </label>

            <select
                name="rating"
                class="form-select"
            >

                <option value="">
                    All Ratings
                </option>

                @for($rating = 5; $rating >= 1; $rating--)

                    <option
                        value="{{ $rating }}"
                        @selected((string) request('rating') === (string) $rating)
                    >
                        {{ $rating }} Stars
                    </option>

                @endfor

            </select>

        </div>


        {{-- Actions --}}
        <div class="reviews-filter-actions">

            <button
                type="submit"
                class="reviews-filter-button"
            >
                <i class="bi bi-funnel"></i>
                Filter
            </button>

            <a
                href="{{ route('admin.reviews.index') }}"
                class="reviews-reset-button"
            >
                <i class="bi bi-arrow-counterclockwise"></i>
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Reviews List --}}
<div class="dashboard-panel reviews-list-panel">

    <div class="reviews-list-header">

        <div>

            <div class="reviews-list-title">

                <i class="bi bi-chat-left-text"></i>

                <h6 class="mb-0">
                    Review List
                </h6>

            </div>

            <p class="text-muted small mb-0">
                Customer feedback submitted on SecondBook
            </p>

        </div>

        <div
            class="reviews-list-count"
            id="reviewsListCount"
        >
            {{ $reviews->total() }}
            {{ $reviews->total() == 1 ? 'review' : 'reviews' }}
        </div>

    </div>


    <div class="table-responsive reviews-table-wrapper">

        <table class="table reviews-table align-middle">

            <thead>

                <tr>

                    <th class="review-number-column">
                        #
                    </th>

                    <th>
                        Customer
                    </th>

                    <th class="d-none d-md-table-cell">
                        Book
                    </th>

                    <th>
                        Rating
                    </th>

                    <th class="d-none d-lg-table-cell">
                        Review
                    </th>

                    <th>
                        Status
                    </th>

                    <th class="d-none d-xl-table-cell">
                        Date
                    </th>

                    <th class="text-end">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody id="reviewsTableBody">

                @forelse($reviews as $key => $review)

                    <tr id="review-row-{{ $review->id }}">

                        {{-- Number --}}
                        <td class="review-number-cell">
                            {{ $reviews->firstItem() + $key }}
                        </td>


                        {{-- Customer --}}
                        <td>

                            <div class="review-customer-cell">

                                <div class="review-avatar">

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


                                <div class="review-customer-info">

                                    <strong>
                                        {{ $review->user?->name ?? 'Unknown User' }}
                                    </strong>

                                    <span>
                                        {{ $review->user?->email ?? '-' }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- Book --}}
                        <td class="d-none d-md-table-cell">

                            <div class="review-book-cell">

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
                                        class="review-book-cover"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="review-book-placeholder">
                                        <i class="bi bi-book"></i>
                                    </div>

                                @endif


                                <div class="review-book-info">

                                    <strong>
                                        {{ $review->book?->title ?? 'Deleted Book' }}
                                    </strong>

                                    <span>
                                        {{ $review->book?->author?->name ?? '-' }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- Rating --}}
                        <td>

                            <div class="review-rating-cell">

                                <div class="review-stars">

                                    @for($star = 1; $star <= 5; $star++)

                                        @if($star <= $review->rating)

                                            <i class="bi bi-star-fill"></i>

                                        @else

                                            <i class="bi bi-star"></i>

                                        @endif

                                    @endfor

                                </div>

                                <span>
                                    {{ $review->rating }}/5
                                </span>

                            </div>

                        </td>


                        {{-- Review --}}
                        <td class="d-none d-lg-table-cell">

                            @if($review->comment)

                                <div class="review-comment-preview">
                                    {{ \Illuminate\Support\Str::limit($review->comment, 70) }}
                                </div>

                            @else

                                <span class="review-no-comment">
                                    No comment
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

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
                                    <i class="bi bi-question-circle-fill"></i>
                                    Unknown
                                </span>

                            @endif

                        </td>


                        {{-- Date --}}
                        <td class="d-none d-xl-table-cell">

                            <div class="review-date-cell">

                                <strong>
                                    {{ $review->created_at->format('M d, Y') }}
                                </strong>

                                <span>
                                    {{ $review->created_at->format('H:i') }}
                                </span>

                            </div>

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="review-actions-cell">

                                {{-- View --}}
                                <a
                                    href="{{ route('admin.reviews.show', $review->id) }}"
                                    class="review-action-btn view"
                                    title="View Review"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>


                                {{-- Approve --}}
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
                                            title="Approve Review"
                                        >
                                            <i class="bi bi-check-lg"></i>
                                        </button>

                                    </form>

                                @endif


                                {{-- Reject --}}
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
                                            title="Reject Review"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </button>

                                    </form>

                                @endif


                                {{-- Delete --}}
                                <form
                                    action="{{ route('admin.reviews.destroy', $review->id) }}"
                                    method="POST"
                                    class="review-delete-form"
                                    data-review-id="{{ $review->id }}"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="review-action-btn delete review-delete-btn"
                                        title="Delete Review"
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr id="reviews-empty-row">

                        <td
                            colspan="8"
                            class="reviews-empty-cell"
                        >

                            <div class="reviews-empty-state">

                                <div class="reviews-empty-icon">
                                    <i class="bi bi-chat-square-text"></i>
                                </div>

                                <h6>
                                    No reviews found
                                </h6>

                                <p>
                                    There are no reviews matching your current filters.
                                </p>

                                <a
                                    href="{{ route('admin.reviews.index') }}"
                                    class="reviews-reset-empty"
                                >
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Clear Filters
                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($reviews->hasPages())

        <div class="reviews-pagination">

            <div class="reviews-pagination-info">

                Showing

                <strong>{{ $reviews->firstItem() }}</strong>

                to

                <strong>{{ $reviews->lastItem() }}</strong>

                of

                <strong>{{ $reviews->total() }}</strong>

                results

            </div>


            <div class="reviews-pagination-links">

                @if($reviews->onFirstPage())

                    <span class="review-page-btn disabled">‹</span>

                @else

                    <a
                        href="{{ $reviews->previousPageUrl() }}"
                        class="review-page-btn"
                    >
                        ‹
                    </a>

                @endif


                @php
                    $current = $reviews->currentPage();
                    $last = $reviews->lastPage();
                @endphp


                @for($page = 1; $page <= $last; $page++)

                    @if(
                        $page <= 6 ||
                        $page >= $last - 1 ||
                        abs($page - $current) <= 1
                    )

                        <a
                            href="{{ $reviews->url($page) }}"
                            class="review-page-btn {{ $page == $current ? 'active' : '' }}"
                        >
                            {{ $page }}
                        </a>

                    @elseif($page == 7 || $page == $last - 2)

                        <span class="review-page-btn disabled">
                            ...
                        </span>

                    @endif

                @endfor


                @if($reviews->hasMorePages())

                    <a
                        href="{{ $reviews->nextPageUrl() }}"
                        class="review-page-btn"
                    >
                        ›
                    </a>

                @else

                    <span class="review-page-btn disabled">
                        ›
                    </span>

                @endif

            </div>

        </div>

    @endif

</div>


</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const page = document.querySelector('.reviews-page');

    if (!page) {
        return;
    }


    function loadReviews(url, pushState = true) {

        page.classList.add('is-loading');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Request failed');
            }

            return response.text();

        })
        .then(html => {

            const parser = new DOMParser();
            const documentHtml = parser.parseFromString(html, 'text/html');
            const newPage = documentHtml.querySelector('.reviews-page');

            if (!newPage) {
                window.location.href = url;
                return;
            }

            page.innerHTML = newPage.innerHTML;

            if (pushState) {
                window.history.pushState({}, '', url);
            }

            page.classList.remove('is-loading');

        })
        .catch(() => {

            page.classList.remove('is-loading');
            window.location.href = url;

        });

    }


    document.addEventListener('submit', function (event) {

        const form = event.target.closest('.reviews-filter-form');

        if (form) {

            event.preventDefault();

            const formData = new FormData(form);
            const params = new URLSearchParams();

            formData.forEach((value, key) => {

                if (value !== '') {
                    params.append(key, value);
                }

            });

            const url = form.action + (
                params.toString()
                    ? '?' + params.toString()
                    : ''
            );

            loadReviews(url);

            return;
        }


        const deleteForm = event.target.closest('.review-delete-form');

        if (!deleteForm) {
            return;
        }

        event.preventDefault();

        deleteReview(deleteForm);

    });


    document.addEventListener('click', function (event) {

        const resetButton = event.target.closest(
            '.reviews-reset-button, .reviews-reset-empty'
        );

        if (resetButton) {

            event.preventDefault();

            loadReviews(resetButton.href);

            return;
        }


        const paginationLink = event.target.closest(
            '.reviews-pagination-links a'
        );

        if (paginationLink) {

            event.preventDefault();

            loadReviews(paginationLink.href);

        }

    });


    async function deleteReview(form) {

        const reviewId = form.dataset.reviewId;
        const deleteUrl = form.getAttribute('action');
        const row = document.getElementById(
            'review-row-' + reviewId
        );

        if (!row || !deleteUrl) {
            return;
        }

        const result = await Swal.fire({

            title: 'Delete Review?',

            text: 'This review will be permanently deleted.',

            icon: 'warning',

            width: 430,

            padding: '30px',

            showCancelButton: true,

            confirmButtonText: 'Delete',

            cancelButtonText: 'Cancel',

            buttonsStyling: false,

            reverseButtons: false,

            customClass: {
                popup: 'review-delete-popup',
                title: 'review-delete-title',
                htmlContainer: 'review-delete-text',
                confirmButton: 'review-delete-confirm',
                cancelButton: 'review-delete-cancel'
            }

        });


        if (!result.isConfirmed) {
            return;
        }


        const deleteButton = form.querySelector(
            '.review-delete-btn'
        );

        const originalButtonHTML = deleteButton.innerHTML;

        deleteButton.disabled = true;

        deleteButton.innerHTML =
            '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>';


        try {

            const csrfToken = form.querySelector(
                'input[name="_token"]'
            )?.value;


            const response = await fetch(deleteUrl, {

                method: 'DELETE',

                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }

            });


            const data = await response.json();


            if (!response.ok || !data.success) {

                throw new Error(
                    data.message || 'Failed to delete review.'
                );

            }


            row.style.transition =
                'opacity 0.25s ease, transform 0.25s ease';

            row.style.opacity = '0';

            row.style.transform = 'translateX(12px)';


            setTimeout(function () {

                row.remove();

                updateReviewStatistics(data);

            }, 250);


            await Swal.fire({

                icon: 'success',

                title: 'Review Deleted',

                text: data.message ||
                    'The review has been deleted successfully.',

                timer: 1600,

                showConfirmButton: false,

                width: 390,

                customClass: {
                    popup: 'review-success-popup'
                }

            });

        } catch (error) {

            deleteButton.disabled = false;

            deleteButton.innerHTML = originalButtonHTML;


            Swal.fire({

                icon: 'error',

                title: 'Delete Failed',

                text: error.message ||
                    'Something went wrong while deleting the review.',

                confirmButtonText: 'OK',

                buttonsStyling: false,

                width: 410,

                customClass: {
                    popup: 'review-error-popup',
                    confirmButton: 'review-error-confirm'
                }

            });

        }

    }


    function updateReviewStatistics(data) {

        if (typeof data.total_reviews !== 'undefined') {

            const totalCount =
                document.getElementById('totalReviewsCount');

            const totalHeader =
                document.getElementById('reviewsTotalHeader');

            if (totalCount) {
                totalCount.textContent = data.total_reviews;
            }

            if (totalHeader) {
                totalHeader.textContent = data.total_reviews;
            }

        }


        if (typeof data.pending_reviews !== 'undefined') {

            const element =
                document.getElementById('pendingReviewsCount');

            if (element) {
                element.textContent = data.pending_reviews;
            }

        }


        if (typeof data.approved_reviews !== 'undefined') {

            const element =
                document.getElementById('approvedReviewsCount');

            if (element) {
                element.textContent = data.approved_reviews;
            }

        }


        if (typeof data.rejected_reviews !== 'undefined') {

            const element =
                document.getElementById('rejectedReviewsCount');

            if (element) {
                element.textContent = data.rejected_reviews;
            }

        }


        const listCount =
            document.getElementById('reviewsListCount');

        if (listCount) {

            const currentText =
                listCount.textContent.trim();

            const match =
                currentText.match(/\d+/);

            if (match) {

                const currentCount =
                    parseInt(match[0], 10);

                const newCount =
                    Math.max(currentCount - 1, 0);

                listCount.textContent =
                    newCount +
                    (newCount === 1 ? ' review' : ' reviews');

            }

        }


        const tbody =
            document.getElementById('reviewsTableBody');

        if (
            tbody &&
            tbody.querySelectorAll(
                'tr[id^="review-row-"]'
            ).length === 0
        ) {

            const existingEmptyRow =
                document.getElementById('reviews-empty-row');

            if (!existingEmptyRow) {

                const emptyRow =
                    document.createElement('tr');

                emptyRow.id =
                    'reviews-empty-row';

                emptyRow.innerHTML = `
                    <td
                        colspan="8"
                        class="reviews-empty-cell"
                    >
                        <div class="reviews-empty-state">

                            <div class="reviews-empty-icon">
                                <i class="bi bi-chat-square-text"></i>
                            </div>

                            <h6>
                                No reviews found
                            </h6>

                            <p>
                                There are no reviews matching your current filters.
                            </p>

                            <a
                                href="{{ route('admin.reviews.index') }}"
                                class="reviews-reset-empty"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Clear Filters
                            </a>

                        </div>
                    </td>
                `;

                tbody.appendChild(emptyRow);

            }

        }

    }


    window.addEventListener('popstate', function () {

        loadReviews(window.location.href, false);

    });

});
</script>

@endpush
