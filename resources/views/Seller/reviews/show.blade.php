@extends('Layout.Seller.master')

@section('title', 'Review Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/reviews.css') }}">
@endpush

@section('content')

<div class="seller-reviews-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="seller-page-heading">

        <div>
            <h1>Review Details</h1>
            <p>View detailed information about this customer review.</p>
        </div>

        <a
            href="{{ route('seller.reviews.index') }}"
            class="seller-review-show-back"
        >
            <i class="bi bi-arrow-left"></i>
            <span>Back to Reviews</span>
        </a>

    </div>


    @php

        /*
        |--------------------------------------------------------------------------
        | BOOK
        |--------------------------------------------------------------------------
        */

        $book = $review->book ?? null;

        /*
        |--------------------------------------------------------------------------
        | BUYER
        |--------------------------------------------------------------------------
        */

        $buyer = $review->user ?? null;

        $buyerName = $buyer?->name
            ?? trim(
                ($buyer?->first_name ?? '') . ' ' .
                ($buyer?->last_name ?? '')
            )
            ?: 'Unknown Buyer';

        $buyerInitial = strtoupper(
            substr(
                $buyer?->name
                    ?? $buyer?->first_name
                    ?? 'U',
                0,
                1
            )
        );

        /*
        |--------------------------------------------------------------------------
        | RATING
        |--------------------------------------------------------------------------
        */

        $rating = (float) $review->rating;

    @endphp


    {{-- =====================================================
         REVIEW DETAIL
    ====================================================== --}}
    <div class="seller-review-show-grid">

        {{-- =================================================
             MAIN REVIEW CARD
        ================================================== --}}
        <div class="seller-review-show-main">

            {{-- Review Header --}}
            <div class="seller-review-show-header">

                <div class="seller-review-show-header-icon">
                    <i class="bi bi-star-half"></i>
                </div>

                <div>
                    <h5>Customer Review</h5>
                    <p>
                        Review submitted
                        {{ $review->created_at?->format('M d, Y') ?? '—' }}
                    </p>
                </div>

            </div>


            {{-- Rating --}}
            <div class="seller-review-show-rating-section">

                <div class="seller-review-show-rating-label">
                    <span>Rating</span>
                    <strong>{{ number_format($rating, 1) }} / 5.0</strong>
                </div>

                <div
                    class="seller-review-show-stars"
                    aria-label="{{ $rating }} out of 5 stars"
                >
                    @for($i = 1; $i <= 5; $i++)

                        <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}"></i>

                    @endfor
                </div>

            </div>


            {{-- Comment --}}
            <div class="seller-review-show-comment">

                <div class="seller-review-show-section-label">
                    <i class="bi bi-chat-square-text"></i>
                    <span>Customer Comment</span>
                </div>

                @if($review->comment)

                    <div class="seller-review-show-comment-box">
                        <i class="bi bi-quote"></i>

                        <p>
                            {{ $review->comment }}
                        </p>
                    </div>

                @else

                    <div class="seller-review-show-empty-comment">

                        <i class="bi bi-chat-square"></i>

                        <div>
                            <strong>No comment provided</strong>
                            <span>
                                The customer submitted a rating without written feedback.
                            </span>
                        </div>

                    </div>

                @endif

            </div>


            {{-- Review Meta --}}
            <div class="seller-review-show-meta">

                <div class="seller-review-show-meta-item">

                    <span>Review ID</span>

                    <strong>
                        #{{ $review->id }}
                    </strong>

                </div>

                <div class="seller-review-show-meta-item">

                    <span>Submitted</span>

                    <strong>
                        {{ $review->created_at?->format('M d, Y') ?? '—' }}
                    </strong>

                </div>

                <div class="seller-review-show-meta-item">

                    <span>Time</span>

                    <strong>
                        {{ $review->created_at?->format('H:i') ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             SIDE INFORMATION
        ================================================== --}}
        <div class="seller-review-show-side">

            {{-- =================================================
                 BUYER CARD
            ================================================== --}}
            <div class="seller-review-show-card">

                <div class="seller-review-show-card-heading">

                    <div class="seller-review-show-card-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <div>
                        <h5>Buyer</h5>
                        <span>Customer information</span>
                    </div>

                </div>


                <div class="seller-review-show-buyer">

                    <div class="seller-review-show-avatar">
                        {{ $buyerInitial }}
                    </div>

                    <div class="seller-review-show-buyer-info">

                        <strong>
                            {{ $buyerName }}
                        </strong>

                        <span>
                            {{ $buyer?->email ?? 'No email available' }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 BOOK CARD
            ================================================== --}}
            <div class="seller-review-show-card">

                <div class="seller-review-show-card-heading">

                    <div class="seller-review-show-card-icon">
                        <i class="bi bi-book"></i>
                    </div>

                    <div>
                        <h5>Book</h5>
                        <span>Reviewed book</span>
                    </div>

                </div>


                @if($book)

                    <div class="seller-review-show-book">

                        <div class="seller-review-show-book-cover">

                            @if($book->cover)

                                <img
                                    src="{{ asset('storage/' . $book->cover) }}"
                                    alt="{{ $book->title }}"
                                >

                            @else

                                <i class="bi bi-book"></i>

                            @endif

                        </div>


                        <div class="seller-review-show-book-info">

                            <strong>
                                {{ $book->title ?? '—' }}
                            </strong>

                            @if($book->author)

                                <span>
                                    {{ $book->author->name }}
                                </span>

                            @endif

                            @if($book->isbn)

                                <small>
                                    ISBN: {{ $book->isbn }}
                                </small>

                            @endif

                        </div>

                    </div>

                @else

                    <div class="seller-review-show-deleted-book">

                        <i class="bi bi-book"></i>

                        <div>
                            <strong>Book unavailable</strong>
                            <span>
                                This book is no longer available.
                            </span>
                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 REVIEW STATUS
            ================================================== --}}
            <div class="seller-review-show-card">

                <div class="seller-review-show-card-heading">

                    <div class="seller-review-show-card-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h5>Review Information</h5>
                        <span>Additional details</span>
                    </div>

                </div>


                <div class="seller-review-show-details">

                    <div>
                        <span>Status</span>

                        <strong class="seller-review-show-status">
                            <i class="bi bi-check-circle-fill"></i>
                            Published
                        </strong>
                    </div>

                    <div>
                        <span>Rating</span>

                        <strong>
                            {{ number_format($rating, 1) }} / 5.0
                        </strong>
                    </div>

                    <div>
                        <span>Created</span>

                        <strong>
                            {{ $review->created_at?->format('M d, Y H:i') ?? '—' }}
                        </strong>
                    </div>

                    @if($review->updated_at)

                        <div>
                            <span>Updated</span>

                            <strong>
                                {{ $review->updated_at->format('M d, Y H:i') }}
                            </strong>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection