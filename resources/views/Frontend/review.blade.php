@extends('Layout.Frontend.master')

@section('title', 'Write a Review | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/review.css') }}">
@endpush

@section('content')

<div class="review-page">

    {{-- =========================================================
         BACKGROUND DECORATION
    ========================================================== --}}

    <div class="review-orb review-orb-one"></div>
    <div class="review-orb review-orb-two"></div>
    <div class="review-grid-pattern"></div>


    <div class="review-container">

        {{-- =====================================================
             BACK LINK
        ====================================================== --}}

        <div class="review-back">

            <a href="{{ route('frontend.orders.show', $order->id) }}">

                <span class="review-back-icon">
                    <i class="bi bi-arrow-left"></i>
                </span>

                <span>
                    Back to Order Details
                </span>

            </a>

        </div>


        {{-- =====================================================
             HERO
        ====================================================== --}}

        <section class="review-hero">

            <div class="review-hero-content">

                <span class="review-eyebrow">

                    <span class="review-eyebrow-line"></span>

                    Your reading experience

                </span>


                <h1>
                    Every book has
                    <span>a story.</span>
                </h1>


                <p>
                    Tell other readers what this one meant to you.
                    Your thoughts can help someone discover their next
                    favourite book.
                </p>

            </div>


            <div class="review-hero-art">

                <div class="review-hero-star review-star-one">
                    <i class="bi bi-star-fill"></i>
                </div>

                <div class="review-hero-star review-star-two">
                    <i class="bi bi-star-fill"></i>
                </div>

                <div class="review-hero-star review-star-three">
                    <i class="bi bi-star-fill"></i>
                </div>


                <div class="review-hero-circle">

                    <div class="review-hero-circle-inner">

                        <i class="bi bi-quote"></i>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             VALIDATION ERROR
        ====================================================== --}}

        @if($errors->any())

            <div class="review-alert">

                <div class="review-alert-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div>

                    <strong>
                        Please review your submission
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- =====================================================
             MAIN REVIEW EXPERIENCE
        ====================================================== --}}

        <div class="review-layout">


            {{-- =================================================
                 BOOK PANEL
            ================================================== --}}

            <aside class="review-book-panel">

                <div class="review-book-panel-top">

                    <span class="review-panel-label">
                        Reviewing
                    </span>

                    <span class="review-panel-number">
                        #{{ $order->order_number }}
                    </span>

                </div>


                <div class="review-book-stage">

                    <div class="review-book-glow"></div>

                    <div class="review-book-cover">

                        @if($order->book->cover)

                            @php

                                $cover = $order->book->cover;

                                if (
                                    str_starts_with($cover, 'http://') ||
                                    str_starts_with($cover, 'https://')
                                ) {

                                    $coverUrl = $cover;

                                } elseif (
                                    str_starts_with($cover, 'storage/')
                                ) {

                                    $coverUrl = asset($cover);

                                } else {

                                    $coverUrl =
                                        asset('storage/' . $cover);

                                }

                            @endphp

                            <img
                                src="{{ $coverUrl }}"
                                alt="{{ $order->book->title }}"
                            >

                        @else

                            <div class="review-book-placeholder">

                                <i class="bi bi-book"></i>

                            </div>

                        @endif

                    </div>

                </div>


                <div class="review-book-details">

                    <h2>
                        {{ $order->book->title }}
                    </h2>


                    @if($order->book->author)

                        <p class="review-book-author">

                            <span>by</span>

                            {{ $order->book->author->name }}

                        </p>

                    @endif


                    <div class="review-book-meta">

                        <div class="review-meta-item">

                            <span class="review-meta-icon">
                                <i class="bi bi-receipt"></i>
                            </span>

                            <div>

                                <small>
                                    Order
                                </small>

                                <strong>
                                    #{{ $order->order_number }}
                                </strong>

                            </div>

                        </div>


                        <div class="review-meta-item">

                            <span class="review-meta-icon">
                                <i class="bi bi-bookmark-heart"></i>
                            </span>

                            <div>

                                <small>
                                    Experience
                                </small>

                                <strong>
                                    Your opinion
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="review-book-quote">

                    <i class="bi bi-quote"></i>

                    <p>
                        “A reader lives a thousand lives before
                        he dies.”
                    </p>

                    <span>
                        — George R. R. Martin
                    </span>

                </div>

            </aside>


            {{-- =================================================
                 FORM PANEL
            ================================================== --}}

            <section class="review-form-panel">

                <div class="review-form-header">

                    <div>

                        <span class="review-form-kicker">
                            Share your thoughts
                        </span>

                        <h2>
                            How was your
                            <span>reading experience?</span>
                        </h2>

                    </div>


                    <div class="review-form-badge">

                        <i class="bi bi-feather"></i>

                        <span>
                            Reader's note
                        </span>

                    </div>

                </div>


                <form
                    action="{{ route('frontend.reviews.store', $order->id) }}"
                    method="POST"
                    class="review-form"
                >

                    @csrf


                    {{-- =================================================
                         RATING
                    ================================================== --}}

                    <div class="review-form-group review-rating-group">

                        <div class="review-label-row">

                            <div>

                                <label class="review-label">
                                    Your Rating
                                </label>

                                <p class="review-help">
                                    Choose the number of stars this book deserves.
                                </p>

                            </div>


                            <div
                                class="rating-score"
                                id="ratingScore"
                            >
                                —
                            </div>

                        </div>


                        <div class="rating-area">

                            <div class="rating-stars">

                                {{-- IMPORTANT:
                                     DOM is 5 -> 1.
                                     CSS reverses visual direction.
                                     Selection fills 1 -> selected.
                                --}}

                                @for($rating = 5; $rating >= 1; $rating--)

                                    <input
                                        type="radio"
                                        name="rating"
                                        id="rating-{{ $rating }}"
                                        value="{{ $rating }}"
                                        {{ old('rating') == $rating ? 'checked' : '' }}
                                    >

                                    <label
                                        for="rating-{{ $rating }}"
                                        data-rating="{{ $rating }}"
                                        title="{{ $rating }} {{ $rating == 1 ? 'star' : 'stars' }}"
                                    >
                                        <i class="bi bi-star-fill"></i>
                                    </label>

                                @endfor

                            </div>


                            <div class="rating-feedback">

                                <span
                                    class="rating-text"
                                    id="ratingText"
                                >
                                    Select a rating
                                </span>

                                <span class="rating-description">
                                    Your rating helps other readers.
                                </span>

                            </div>

                        </div>


                        @error('rating')

                            <div class="review-error">

                                <i class="bi bi-exclamation-circle"></i>

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         COMMENT
                    ================================================== --}}

                    <div class="review-form-group review-comment-group">

                        <div class="review-label-row">

                            <div>

                                <label
                                    for="comment"
                                    class="review-label"
                                >
                                    Your Review
                                </label>

                                <p class="review-help">
                                    What stayed with you after turning the last page?
                                </p>

                            </div>


                            <div class="review-counter">

                                <span id="commentCount">0</span>

                                <span>/</span>

                                <span>2000</span>

                            </div>

                        </div>


                        <div class="review-textarea-wrapper">

                            <textarea
                                name="comment"
                                id="comment"
                                class="review-textarea"
                                rows="8"
                                maxlength="2000"
                                placeholder="Write about the story, characters, emotions or anything that stood out to you..."
                            >{{ old('comment') }}</textarea>


                            <div class="review-textarea-footer">

                                <span>
                                    <i class="bi bi-pencil"></i>
                                    Write freely
                                </span>

                                <span id="commentHint">
                                    2000 characters available
                                </span>

                            </div>

                        </div>


                        @error('comment')

                            <div class="review-error">

                                <i class="bi bi-exclamation-circle"></i>

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         MODERATION NOTICE
                    ================================================== --}}

                    <div class="review-notice">

                        <div class="review-notice-icon">

                            <i class="bi bi-shield-check"></i>

                        </div>


                        <div class="review-notice-content">

                            <strong>
                                A quick note before you submit
                            </strong>

                            <p>
                                Your review will be submitted for moderation
                                before becoming visible to other readers.
                            </p>

                        </div>


                        <div class="review-notice-mark">
                            <i class="bi bi-check2"></i>
                        </div>

                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}

                    <div class="review-actions">

                        <a
                            href="{{ route('frontend.orders.show', $order->id) }}"
                            class="review-cancel"
                        >

                            <i class="bi bi-arrow-left"></i>

                            <span>
                                Cancel
                            </span>

                        </a>


                        <button
                            type="submit"
                            class="review-submit"
                        >

                            <span class="review-submit-icon">
                                <i class="bi bi-send-fill"></i>
                            </span>

                            <span>
                                Submit Review
                            </span>

                            <i class="bi bi-arrow-up-right review-submit-arrow"></i>

                        </button>

                    </div>

                </form>

            </section>

        </div>


        {{-- =====================================================
             BOTTOM TRUST ROW
        ====================================================== --}}

        <div class="review-bottom">

            <div class="review-bottom-item">

                <i class="bi bi-person-check"></i>

                <span>
                    Written by a verified reader
                </span>

            </div>


            <div class="review-bottom-divider"></div>


            <div class="review-bottom-item">

                <i class="bi bi-heart"></i>

                <span>
                    Help other readers discover books
                </span>

            </div>


            <div class="review-bottom-divider"></div>


            <div class="review-bottom-item">

                <i class="bi bi-stars"></i>

                <span>
                    Every opinion matters
                </span>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================== */

    const ratingInputs =
        document.querySelectorAll('.rating-stars input');

    const ratingLabels =
        document.querySelectorAll('.rating-stars label');

    const ratingText =
        document.getElementById('ratingText');

    const ratingScore =
        document.getElementById('ratingScore');

    const comment =
        document.getElementById('comment');

    const commentCount =
        document.getElementById('commentCount');

    const commentHint =
        document.getElementById('commentHint');


    /* =========================================================
       RATING TEXT
    ========================================================== */

    const ratingLabelsText = {

        1: 'Very poor',

        2: 'Poor',

        3: 'Average',

        4: 'Good',

        5: 'Excellent'

    };


    const ratingDescriptions = {

        1: 'The book did not meet your expectations.',

        2: 'There were some good moments, but also issues.',

        3: 'A balanced reading experience.',

        4: 'A very enjoyable reading experience.',

        5: 'An exceptional book worth recommending.'

    };


    /* =========================================================
       RATING UPDATE
    ========================================================== */

    function updateRating(rating) {

        if (!rating) {

            ratingText.textContent =
                'Select a rating';

            ratingScore.textContent =
                '—';

            return;

        }


        ratingText.textContent =
            ratingLabelsText[rating];


        ratingScore.textContent =
            rating + '/5';


        const description =
            document.querySelector('.rating-description');

        if (description) {

            description.textContent =
                ratingDescriptions[rating];

        }


        /*
         * Mark selected rating.
         *
         * CSS handles the visual
         * left-to-right star filling.
         */

        ratingLabels.forEach(function (label) {

            const labelRating =
                Number(label.dataset.rating);

            label.classList.toggle(
                'is-selected',
                labelRating <= Number(rating)
            );

        });

    }


    /* =========================================================
       RATING CHANGE
    ========================================================== */

    ratingInputs.forEach(function (input) {

        input.addEventListener('change', function () {

            updateRating(this.value);

        });

    });


    /* =========================================================
       STAR HOVER PREVIEW
    ========================================================== */

    ratingLabels.forEach(function (label) {

        label.addEventListener('mouseenter', function () {

            const hoverRating =
                Number(this.dataset.rating);


            ratingLabels.forEach(function (item) {

                const itemRating =
                    Number(item.dataset.rating);

                item.classList.toggle(
                    'is-hovered',
                    itemRating <= hoverRating
                );

            });

        });


        label.addEventListener('mouseleave', function () {

            ratingLabels.forEach(function (item) {

                item.classList.remove('is-hovered');

            });

        });

    });


    /* =========================================================
       EXISTING OLD VALUE
    ========================================================== */

    const checkedRating =
        document.querySelector(
            '.rating-stars input:checked'
        );


    if (checkedRating) {

        updateRating(
            checkedRating.value
        );

    }


    /* =========================================================
       COMMENT COUNTER
    ========================================================== */

    function updateCommentCount() {

        if (!comment || !commentCount) {
            return;
        }


        const length =
            comment.value.length;


        const remaining =
            2000 - length;


        commentCount.textContent =
            length;


        if (commentHint) {

            if (remaining === 0) {

                commentHint.textContent =
                    'Character limit reached';

            } else {

                commentHint.textContent =
                    remaining +
                    ' characters available';

            }

        }


        /*
         * Visual state
         */

        if (length >= 1800) {

            commentCount.classList.add(
                'is-near-limit'
            );

        } else {

            commentCount.classList.remove(
                'is-near-limit'
            );

        }

    }


    if (comment) {

        comment.addEventListener(
            'input',
            updateCommentCount
        );

        updateCommentCount();

    }

});

</script>

@endpush