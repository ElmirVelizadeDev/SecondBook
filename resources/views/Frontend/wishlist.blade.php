@extends('Layout.Frontend.master')

@section('title', 'My Wishlist | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/wishlist.css') }}">
@endpush

@section('content')

<main class="sb-wishlist-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}

    <section class="wishlist-hero">
        <div class="container">

            <div class="wishlist-breadcrumb">
                <a href="{{ route('frontend.home') }}">
                    Home
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>Wishlist</span>
            </div>

            <div class="wishlist-hero-wrapper">

                <div class="wishlist-hero-content">

                    <span class="wishlist-eyebrow">
                        <i class="bi bi-heart-fill"></i>
                        Your Personal Collection
                    </span>

                    <h1>
                        Books Worth
                        <span>Coming Back To.</span>
                    </h1>

                    <p>
                        Save the books that catch your eye and keep your
                        next great read just one click away.
                    </p>

                </div>

                <div class="wishlist-hero-decoration">

                    <div class="wishlist-floating-card">

                        <div class="wishlist-floating-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>

                        <div class="wishlist-floating-content">

                            <span>Saved for later</span>

                            <strong>
                                {{ $wishlists->total() }}
                                {{ $wishlists->total() === 1 ? 'book' : 'books' }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}

    <section class="wishlist-section">

        <div class="container">

            {{-- =====================================================
                SESSION ALERTS
            ====================================================== --}}

            @if(session('success'))

                <div class="wishlist-alert wishlist-alert-success">

                    <div class="wishlist-alert-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div>
                        <strong>Success</strong>
                        <span>{{ session('success') }}</span>
                    </div>

                </div>

            @endif


            @if(session('error'))

                <div class="wishlist-alert wishlist-alert-error">

                    <div class="wishlist-alert-icon">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>

                    <div>
                        <strong>Something went wrong</strong>
                        <span>{{ session('error') }}</span>
                    </div>

                </div>

            @endif


            {{-- =====================================================
                WISHLIST WITH BOOKS
            ====================================================== --}}

            @if($wishlists->count())

                {{-- =================================================
                    TOPBAR
                ================================================== --}}

                <div class="wishlist-topbar">

                    <div class="wishlist-topbar-left">

                        <span class="wishlist-section-label">
                            <i class="bi bi-bookmark-heart"></i>
                            Saved Books
                        </span>

                        <h2>
                            Your Wishlist
                        </h2>

                        <p>
                            You currently have
                            <strong>
                                {{ $wishlists->total() }}
                                {{ $wishlists->total() === 1 ? 'book' : 'books' }}
                            </strong>
                            saved in your collection.
                        </p>

                    </div>


                    <a
                        href="{{ route('frontend.books') }}"
                        class="wishlist-browse-btn"
                    >
                        <span>Browse More Books</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>


                {{-- =================================================
                    BOOK GRID
                ================================================== --}}

                <div class="wishlist-grid">

                    @foreach($wishlists as $wishlist)

                        @php

                            $book = $wishlist->book;

                            $coverUrl = null;

                            if ($book && $book->cover) {

                                if (
                                    str_starts_with($book->cover, 'http://') ||
                                    str_starts_with($book->cover, 'https://')
                                ) {

                                    $coverUrl = $book->cover;

                                } elseif (
                                    str_starts_with($book->cover, 'storage/')
                                ) {

                                    $coverUrl = asset($book->cover);

                                } elseif (
                                    str_starts_with($book->cover, 'uploads/')
                                ) {

                                    $coverUrl = asset($book->cover);

                                } else {

                                    $coverUrl = asset(
                                        'storage/' . ltrim($book->cover, '/')
                                    );

                                }

                            }

                            $condition = $book
                                ? ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $book->condition ?? 'good'
                                    )
                                )
                                : null;

                        @endphp


                        @if($book)

                            <article
                                class="wishlist-card"
                                data-wishlist-card
                            >

                                {{-- =====================================
                                    BOOK COVER
                                ====================================== --}}

                                <div class="wishlist-cover-wrapper">

                                    <a
                                        href="{{ route('frontend.books.show', $book->id) }}"
                                        class="wishlist-cover-link"
                                        aria-label="View {{ $book->title }}"
                                    >

                                        <div class="wishlist-cover">

                                            @if($coverUrl)

                                                <img
                                                    src="{{ $coverUrl }}"
                                                    alt="{{ $book->title }}"
                                                    loading="lazy"
                                                    onerror="
                                                        this.style.display='none';
                                                        this.nextElementSibling.style.display='flex';
                                                    "
                                                >

                                                <div
                                                    class="wishlist-no-cover"
                                                    style="display: none;"
                                                >
                                                    <i class="bi bi-book-half"></i>

                                                    <span>
                                                        No cover available
                                                    </span>
                                                </div>

                                            @else

                                                <div class="wishlist-no-cover">

                                                    <i class="bi bi-book-half"></i>

                                                    <span>
                                                        No cover available
                                                    </span>

                                                </div>

                                            @endif

                                        </div>

                                    </a>


                                    {{-- CONDITION --}}

                                    @if($condition)

                                        <span class="wishlist-condition-badge">

                                            <i class="bi bi-stars"></i>

                                            {{ $condition }}

                                        </span>

                                    @endif


                                    {{-- REMOVE HEART --}}

                                    <form
                                        action="{{ route('frontend.wishlist.remove', $book->id) }}"
                                        method="POST"
                                        class="wishlist-heart-form wishlist-delete-form"
                                        data-wishlist-delete
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="wishlist-heart-btn"
                                            aria-label="Remove {{ $book->title }} from wishlist"
                                            title="Remove from wishlist"
                                            data-delete-button
                                        >

                                            <i class="bi bi-heart-fill"></i>

                                        </button>

                                    </form>


                                    {{-- OUT OF STOCK --}}

                                    @if($book->stock <= 0)

                                        <div class="wishlist-out-overlay">

                                            <span>
                                                Out of stock
                                            </span>

                                        </div>

                                    @endif

                                </div>


                                {{-- =====================================
                                    CARD CONTENT
                                ====================================== --}}

                                <div class="wishlist-card-content">


                                    {{-- META --}}

                                    <div class="wishlist-card-meta">

                                        <span class="wishlist-category">

                                            <i class="bi bi-grid"></i>

                                            {{ $book->category->name ?? 'Book' }}

                                        </span>


                                        @if($book->stock > 0)

                                            <span class="wishlist-stock available">

                                                <span class="wishlist-stock-dot"></span>

                                                In Stock

                                            </span>

                                        @else

                                            <span class="wishlist-stock unavailable">

                                                <span class="wishlist-stock-dot"></span>

                                                Out of Stock

                                            </span>

                                        @endif

                                    </div>


                                    {{-- TITLE --}}

                                    <h3 class="wishlist-book-title">

                                        <a
                                            href="{{ route('frontend.books.show', $book->id) }}"
                                        >
                                            {{ $book->title }}
                                        </a>

                                    </h3>


                                    {{-- AUTHOR --}}

                                    <div class="wishlist-author">

                                        <div class="wishlist-author-icon">

                                            <i class="bi bi-person"></i>

                                        </div>

                                        <div>

                                            <span>
                                                Written by
                                            </span>

                                            <strong>
                                                {{ $book->author->name ?? 'Unknown Author' }}
                                            </strong>

                                        </div>

                                    </div>


                                    {{-- DESCRIPTION --}}

                                    @if($book->description)

                                        <p class="wishlist-description">

                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags($book->description),
                                                95
                                            ) }}

                                        </p>

                                    @endif


                                    {{-- DIVIDER --}}

                                    <div class="wishlist-card-divider"></div>


                                    {{-- PRICE --}}

                                    <div class="wishlist-price-row">

                                        <div class="wishlist-price-info">

                                            <span class="wishlist-price-label">
                                                Price
                                            </span>

                                            <strong class="wishlist-price">
                                                ${{ number_format($book->price, 2) }}
                                            </strong>

                                        </div>


                                        @if($book->stock > 0)

                                            <div class="wishlist-availability">

                                                <i class="bi bi-box-seam"></i>

                                                <span>
                                                    {{ $book->stock }}
                                                    available
                                                </span>

                                            </div>

                                        @endif

                                    </div>


                                    {{-- =================================
                                        ACTIONS
                                    ================================== --}}

                                    <div class="wishlist-actions">

                                        <a
                                            href="{{ route('frontend.books.show', $book->id) }}"
                                            class="wishlist-view-btn"
                                        >

                                            <span>
                                                View Details
                                            </span>

                                            <i class="bi bi-arrow-up-right"></i>

                                        </a>


                                        <form
                                            action="{{ route('frontend.wishlist.remove', $book->id) }}"
                                            method="POST"
                                            class="wishlist-delete-form"
                                            data-wishlist-delete
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="wishlist-remove-btn"
                                                title="Remove from wishlist"
                                                aria-label="Remove {{ $book->title }} from wishlist"
                                                data-delete-button
                                            >

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </article>

                        @endif

                    @endforeach

                </div>


                {{-- =================================================
                    PAGINATION
                ================================================== --}}

                @if($wishlists->hasPages())

                    <div class="wishlist-pagination">
                        {{ $wishlists->links() }}
                    </div>

                @endif


            @else

                {{-- =================================================
                    EMPTY WISHLIST
                ================================================== --}}

                <div class="wishlist-empty">

                    <div class="wishlist-empty-decoration">

                        <span class="wishlist-empty-circle circle-one"></span>
                        <span class="wishlist-empty-circle circle-two"></span>
                        <span class="wishlist-empty-circle circle-three"></span>

                        <div class="wishlist-empty-icon">

                            <i class="bi bi-heart"></i>

                        </div>

                    </div>


                    <span class="wishlist-empty-eyebrow">

                        <i class="bi bi-bookmark-heart"></i>

                        Your Wishlist

                    </span>


                    <h2>

                        Your next favorite book

                        <span>
                            could be waiting.
                        </span>

                    </h2>


                    <p>

                        Your wishlist is empty right now. Explore our books,
                        save the ones you love and build a collection you can
                        return to anytime.

                    </p>


                    <div class="wishlist-empty-actions">

                        <a
                            href="{{ route('frontend.books') }}"
                            class="wishlist-empty-btn"
                        >

                            <span>
                                Explore Books
                            </span>

                            <i class="bi bi-arrow-right"></i>

                        </a>


                        <a
                            href="{{ route('frontend.home') }}"
                            class="wishlist-empty-home-btn"
                        >

                            <i class="bi bi-house"></i>

                            Back Home

                        </a>

                    </div>

                </div>

            @endif

        </div>

    </section>

</main>


{{-- =========================================================
    WISHLIST DELETE AJAX
========================================================= --}}

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteForms = document.querySelectorAll(
        '[data-wishlist-delete]'
    );


    /* =========================================================
       DELETE WISHLIST ITEM
    ========================================================= */

    deleteForms.forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();
            event.stopPropagation();

            const button = form.querySelector(
                '[data-delete-button]'
            );

            const card = form.closest(
                '[data-wishlist-card]'
            );

            const token = form.querySelector(
                'input[name="_token"]'
            );

            if (!button || !card || !token) {
                return;
            }

            if (button.disabled) {
                return;
            }

            button.disabled = true;

            form.classList.add('is-removing');


            try {

                const response = await fetch(
                    form.action,
                    {
                        method: 'DELETE',

                        headers: {
                            'X-CSRF-TOKEN': token.value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


                let data = {};

                try {

                    data = await response.json();

                } catch (jsonError) {

                    data = {};

                }


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Failed to remove wishlist item.'
                    );

                }


                /* =================================================
                   REMOVE CARD
                ================================================= */

                card.style.transition =
                    'opacity 0.25s ease, transform 0.25s ease';

                card.style.opacity = '0';

                card.style.transform = 'scale(0.96)';


                setTimeout(function () {

                    card.remove();

                    updateWishlistCount();

                    showWishlistAlert(
                        data.message ||
                        'Book removed from wishlist successfully!'
                    );

                }, 250);


            } catch (error) {

                console.error(
                    'Wishlist delete error:',
                    error
                );

                button.disabled = false;

                form.classList.remove(
                    'is-removing'
                );

                showWishlistAlert(
                    error.message ||
                    'Something went wrong. Please try again.',
                    'error'
                );

            }

        });

    });


    /* =========================================================
       UPDATE WISHLIST COUNTS
    ========================================================= */

    function updateWishlistCount() {

        const remainingCards =
            document.querySelectorAll(
                '[data-wishlist-card]'
            ).length;


        /* =====================================================
           HERO COUNT
        ====================================================== */

        const heroCount =
            document.querySelector(
                '.wishlist-floating-card strong'
            );

        if (heroCount) {

            heroCount.textContent =
                remainingCards +
                (
                    remainingCards === 1
                        ? ' book'
                        : ' books'
                );

        }


        /* =====================================================
           TOPBAR COUNT
        ====================================================== */

        const topbarCount =
            document.querySelector(
                '.wishlist-topbar p strong'
            );

        if (topbarCount) {

            topbarCount.textContent =
                remainingCards +
                (
                    remainingCards === 1
                        ? ' book'
                        : ' books'
                );

        }


        /* =====================================================
           HEADER WISHLIST COUNT
        ====================================================== */

        const headerWishlistCount =
            document.getElementById(
                'header-wishlist-count'
            );

        if (headerWishlistCount) {

            headerWishlistCount.textContent =
                remainingCards > 99
                    ? '99+'
                    : remainingCards;

        }


        /* =====================================================
           EMPTY STATE
        ====================================================== */

        if (remainingCards === 0) {

            showEmptyWishlist();

        }

    }


    /* =========================================================
       SHOW EMPTY WISHLIST WITHOUT RELOAD
    ========================================================= */

    function showEmptyWishlist() {

        const wishlistGrid =
            document.querySelector(
                '.wishlist-grid'
            );

        const wishlistTopbar =
            document.querySelector(
                '.wishlist-topbar'
            );

        const wishlistPagination =
            document.querySelector(
                '.wishlist-pagination'
            );


        if (wishlistGrid) {
            wishlistGrid.remove();
        }

        if (wishlistTopbar) {
            wishlistTopbar.remove();
        }

        if (wishlistPagination) {
            wishlistPagination.remove();
        }


        if (
            document.querySelector(
                '.wishlist-empty'
            )
        ) {
            return;
        }


        const container =
            document.querySelector(
                '.wishlist-section .container'
            );

        if (!container) {
            return;
        }


        const emptyWishlist =
            document.createElement('div');

        emptyWishlist.className =
            'wishlist-empty';


        emptyWishlist.innerHTML = `

            <div class="wishlist-empty-decoration">

                <span class="wishlist-empty-circle circle-one"></span>
                <span class="wishlist-empty-circle circle-two"></span>
                <span class="wishlist-empty-circle circle-three"></span>

                <div class="wishlist-empty-icon">
                    <i class="bi bi-heart"></i>
                </div>

            </div>


            <span class="wishlist-empty-eyebrow">

                <i class="bi bi-bookmark-heart"></i>

                Your Wishlist

            </span>


            <h2>

                Your next favorite book

                <span>
                    could be waiting.
                </span>

            </h2>


            <p>

                Your wishlist is empty right now. Explore our books,
                save the ones you love and build a collection you can
                return to anytime.

            </p>


            <div class="wishlist-empty-actions">

                <a
                    href="{{ route('frontend.books') }}"
                    class="wishlist-empty-btn"
                >

                    <span>
                        Explore Books
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>


                <a
                    href="{{ route('frontend.home') }}"
                    class="wishlist-empty-home-btn"
                >

                    <i class="bi bi-house"></i>

                    Back Home

                </a>

            </div>

        `;


        container.appendChild(
            emptyWishlist
        );

    }


    /* =========================================================
       WISHLIST AJAX ALERT
    ========================================================= */

    function showWishlistAlert(
        message,
        type = 'success'
    ) {

        const existingAlert =
            document.querySelector(
                '.wishlist-ajax-alert'
            );

        if (existingAlert) {
            existingAlert.remove();
        }


        const container =
            document.querySelector(
                '.wishlist-section .container'
            );

        if (!container) {
            return;
        }


        const alert =
            document.createElement('div');


        alert.className =
            type === 'error'
                ? 'wishlist-alert wishlist-alert-error wishlist-ajax-alert'
                : 'wishlist-alert wishlist-alert-success wishlist-ajax-alert';


        alert.innerHTML = `

            <div class="wishlist-alert-icon">

                <i class="bi ${
                    type === 'error'
                        ? 'bi-exclamation-lg'
                        : 'bi-check-lg'
                }"></i>

            </div>


            <div>

                <strong>
                    ${
                        type === 'error'
                            ? 'Something went wrong'
                            : 'Success'
                    }
                </strong>

                <span>
                    ${message}
                </span>

            </div>

        `;


        const topbar =
            container.querySelector(
                '.wishlist-topbar'
            );


        if (topbar) {

            container.insertBefore(
                alert,
                topbar
            );

        } else {

            container.prepend(
                alert
            );

        }


        setTimeout(function () {

            alert.style.opacity = '0';

            alert.style.transform =
                'translateY(-6px)';

            alert.style.transition =
                'opacity 0.25s ease, transform 0.25s ease';


            setTimeout(function () {

                alert.remove();

            }, 250);

        }, 2500);

    }

});
</script>

@endpush

@endsection