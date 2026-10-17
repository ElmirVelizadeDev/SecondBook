@extends('Layout.Frontend.master')

@section('title', 'Books | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/books.css') }}">
@endpush

@section('content')

<section class="books-hero">
    <div class="books-hero-bg"></div>
    <div class="books-hero-noise"></div>
    <div class="books-hero-pattern"></div>

    <div class="books-hero-orbit books-hero-orbit-one"></div>
    <div class="books-hero-orbit books-hero-orbit-two"></div>
    <div class="books-hero-orbit books-hero-orbit-three"></div>

    <div class="container">
        <div class="books-hero-breadcrumb">
            <a href="{{ route('frontend.home') }}">Home</a>
            <span>/</span>
            <span>Books</span>
        </div>

        <div class="books-hero-grid">
            <div class="books-hero-content">
                <div class="books-hero-eyebrow">
                    <span class="books-hero-eyebrow-line"></span>
                    <i class="bi bi-book-half"></i>
                    <span>SECOND BOOK COLLECTION</span>
                </div>

                <h1 class="books-hero-title">
                    Every Book
                    <span class="books-hero-title-accent">Has Another</span>
                    <span class="books-hero-title-last">Chapter.</span>
                </h1>

                <p class="books-hero-description">
                    Discover pre-loved books, uncover hidden gems and
                    give unforgettable stories another chance to be read.
                    Your next favourite book is waiting to be found.
                </p>

                <div class="books-hero-actions">
                    <a href="#books-collection" class="books-hero-btn">
                        <span>Explore Collection</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                    <a href="#available-books" class="books-hero-secondary-btn">
                        <span>Discover Books</span>
                        <i class="bi bi-arrow-down"></i>
                    </a>
                </div>

                <div class="books-hero-footer">
                    <div class="books-hero-trust-icons">
                        <span><i class="bi bi-book"></i></span>
                        <span><i class="bi bi-heart"></i></span>
                        <span><i class="bi bi-arrow-repeat"></i></span>
                    </div>

                    <div class="books-hero-trust-copy">
                        <strong>Stories worth sharing</strong>
                        <span>One book. Another beginning.</span>
                    </div>

                    <div class="books-hero-footer-divider"></div>

                    <div class="books-hero-count">
                        <strong>{{ $books->total() }}+</strong>
                        <span>Books to discover</span>
                    </div>
                </div>
            </div>

            <div class="books-hero-visual">
                <div class="books-hero-visual-glow"></div>

                <div class="books-hero-visual-label">
                    <span>THE ART OF READING</span>
                    <i class="bi bi-arrow-down-right"></i>
                </div>

                <div class="books-hero-image-frame">
                    <img
                        src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=85"
                        alt="A beautiful collection of books"
                    >
                    <div class="books-hero-image-overlay"></div>

                    <div class="books-hero-image-caption">
                        <span>CHAPTER NO.</span>
                        <strong>01</strong>
                    </div>
                </div>

                <div class="books-hero-ring books-hero-ring-one"></div>
                <div class="books-hero-ring books-hero-ring-two"></div>

                <div class="books-hero-floating-card books-hero-floating-top">
                    <div class="books-hero-floating-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>
                        <strong>Find Your Next Read</strong>
                        <span>Something worth discovering</span>
                    </div>
                </div>

                <div class="books-hero-book-stack">
                    @php
                        $heroBooks = $books->take(3);
                    @endphp

                    @foreach($heroBooks as $index => $heroBook)
                        @php
                            $heroCoverUrl = null;

                            if (!empty($heroBook->cover)) {
                                $heroCoverUrl = filter_var(
                                    $heroBook->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $heroBook->cover
                                    : asset('storage/' . $heroBook->cover);
                            }
                        @endphp

                        <div class="books-hero-stack-book books-hero-stack-book-{{ $index + 1 }}">
                            @if($heroCoverUrl)
                                <img
                                    src="{{ $heroCoverUrl }}"
                                    alt="{{ $heroBook->title }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="books-hero-stack-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="books-hero-floating-card books-hero-floating-bottom">
                    <div class="books-hero-bottom-icon">
                        <i class="bi bi-bookmark-heart"></i>
                    </div>

                    <div>
                        <strong>Give Books New Life</strong>
                        <span>Pass the story forward</span>
                    </div>
                </div>

                <div class="books-hero-stamp">
                    <span>READ</span>
                    <span>LOVE</span>
                    <span>REPEAT</span>
                </div>

                <div class="books-hero-number">01</div>

                <div class="books-hero-scroll">
                    <span>SCROLL TO DISCOVER</span>
                    <div class="books-hero-scroll-line"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="books-stats-section">
    <div class="container">
        <div class="books-stats">
            <div class="books-stat-item">
                <div class="books-stat-icon">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <strong>{{ $books->total() }}+</strong>
                    <span>Books Available</span>
                </div>
            </div>

            <div class="books-stat-item">
                <div class="books-stat-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div>
                    <strong>Pre-Loved</strong>
                    <span>Ready for New Readers</span>
                </div>
            </div>

            <div class="books-stat-item">
                <div class="books-stat-icon">
                    <i class="bi bi-tags"></i>
                </div>
                <div>
                    <strong>Great Value</strong>
                    <span>Affordable Books</span>
                </div>
            </div>

            <div class="books-stat-item">
                <div class="books-stat-icon">
                    <i class="bi bi-heart"></i>
                </div>
                <div>
                    <strong>One More</strong>
                    <span>Chapter to Every Book</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="books-section" id="books-collection">
    <div class="container">
        <div class="books-section-heading">
            <div>
                <span class="books-results-label">EXPLORE OUR COLLECTION</span>
                <h2>
                    Find Your Next
                    <em>Favorite Book.</em>
                </h2>
            </div>

            <p>
                Search through our growing collection of
                pre-loved books and discover something special.
            </p>
        </div>

        <div class="books-toolbar">
            <div class="books-toolbar-header">
                <div class="books-toolbar-title">
                    <div class="toolbar-icon">
                        <i class="bi bi-sliders2"></i>
                    </div>
                    <div>
                        <strong>Find a Book</strong>
                        <span>Search and filter our collection</span>
                    </div>
                </div>

                <div class="books-total">
                    <span>{{ $books->total() }}</span>
                    books
                </div>
            </div>

            <form
                action="{{ route('frontend.books') }}"
                method="GET"
                class="books-filter-form"
                id="books-filter-form"
            >
                @if(request()->filled('category'))
                    <input
                        type="hidden"
                        name="category"
                        value="{{ request('category') }}"
                    >
                @endif

                <div class="books-filter-column books-search-column">
                    <label for="book-search">Search</label>

                    <div class="books-search">
                        <i class="bi bi-search"></i>
                        <input
                            id="book-search"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search books by title..."
                        >
                    </div>
                </div>

                <div class="books-filter-column">
                    <label for="book-condition">Condition</label>

                    <select id="book-condition" name="condition" class="books-select">
                        <option value="">All Conditions</option>
                        <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>New</option>
                        <option value="like_new" {{ request('condition') === 'like_new' ? 'selected' : '' }}>Like New</option>
                        <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ request('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                    </select>
                </div>

                <div class="books-filter-column">
                    <label for="book-sort">Sort By</label>

                    <select id="book-sort" name="sort" class="books-select">
                        <option value="">Default</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>

                <div class="books-filter-actions">
                    <button type="submit" class="books-filter-btn" id="books-search-btn">
                        <i class="bi bi-search"></i>
                        <span>Search</span>
                    </button>

                    @if(request()->hasAny(['search', 'condition', 'sort', 'category']))
                        <a
                            href="{{ route('frontend.books') }}"
                            class="books-clear-filter"
                            aria-label="Clear filters"
                            title="Clear filters"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Clear</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="books-results-header" id="available-books">
            <div>
                <span class="books-results-label">OUR COLLECTION</span>
                <h2>
                    Available
                    <em>Books</em>
                </h2>
            </div>

            <div class="books-results-meta">
                Showing
                <strong>{{ $books->firstItem() ?? 0 }}</strong>
                –
                <strong>{{ $books->lastItem() ?? 0 }}</strong>
                of
                <strong>{{ $books->total() }}</strong>
            </div>
        </div>

        @if($books->count())
            <div class="row g-4">
                @foreach($books as $book)
                    @php
                        $bookCoverUrl = null;

                        if (!empty($book->cover)) {
                            $bookCoverUrl = filter_var(
                                $book->cover,
                                FILTER_VALIDATE_URL
                            )
                                ? $book->cover
                                : asset('storage/' . $book->cover);
                        }

                        $originalPrice = (float) $book->price;
                        $discountedPrice = (float) $book->discounted_price;
                    @endphp

                    <div class="col-6 col-sm-6 col-lg-4 col-xl-3">
                        <article class="book-card">
                            <div class="book-cover">
                                @if($bookCoverUrl)
                                    <img
                                        src="{{ $bookCoverUrl }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="book-cover-placeholder">
                                        <i class="bi bi-book"></i>
                                        <span>No Cover</span>
                                    </div>
                                @endif

                                <span class="book-condition">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ ucwords(str_replace('_', ' ', $book->condition)) }}
                                </span>

                                @if($book->is_discount_active)
                                    <span class="book-discount">
                                        {{ $book->discount_label }}
                                    </span>
                                @endif

                                @auth
                                    @php
                                        $isWishlisted = auth()->user()
                                            ->wishlists()
                                            ->where('book_id', $book->id)
                                            ->exists();
                                    @endphp

                                    @if($isWishlisted)
                                        <form
                                            action="{{ route('frontend.wishlist.remove', $book->id) }}"
                                            method="POST"
                                            class="book-wishlist-form"
                                            data-wishlist-form
                                            data-action="remove"
                                            data-add-url="{{ route('frontend.wishlist.add', $book->id) }}"
                                            data-remove-url="{{ route('frontend.wishlist.remove', $book->id) }}"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="book-wishlist active"
                                                aria-label="Remove from wishlist"
                                                title="Remove from wishlist"
                                            >
                                                <i class="bi bi-heart-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form
                                            action="{{ route('frontend.wishlist.add', $book->id) }}"
                                            method="POST"
                                            class="book-wishlist-form"
                                            data-wishlist-form
                                            data-action="add"
                                            data-add-url="{{ route('frontend.wishlist.add', $book->id) }}"
                                            data-remove-url="{{ route('frontend.wishlist.remove', $book->id) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="book-wishlist"
                                                aria-label="Add to wishlist"
                                                title="Add to wishlist"
                                            >
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a
                                        href="{{ route('frontend.auth.login') }}"
                                        class="book-wishlist"
                                        aria-label="Login to add to wishlist"
                                        title="Login to add to wishlist"
                                    >
                                        <i class="bi bi-heart"></i>
                                    </a>
                                @endauth

                                <div class="book-cover-hover">
                                    <span>Discover this book</span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </div>
                            </div>

                            <div class="book-content">
                                <span class="book-mini-label">SECONDBOOK</span>
                                <h3>{{ $book->title }}</h3>

                                <p class="book-author">
                                    <i class="bi bi-person"></i>
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="book-card-divider"></div>

                                <div class="book-bottom">
                                    <div>
                                        <span class="book-price-label">PRICE</span>

                                        @if($book->is_discount_active)
                                            <div class="book-price-wrap">
                                                <span class="book-old-price">
                                                    ₼{{ number_format($originalPrice, 2) }}
                                                </span>

                                                <strong class="book-price book-discounted-price">
                                                    ₼{{ number_format($discountedPrice, 2) }}
                                                </strong>
                                            </div>
                                        @else
                                            <strong class="book-price">
                                                ₼{{ number_format($originalPrice, 2) }}
                                            </strong>
                                        @endif
                                    </div>

                                    <div class="book-card-actions">
                                        <a
                                            href="{{ route('frontend.books.show', $book->id) }}"
                                            class="book-view-btn"
                                        >
                                            View
                                            <i class="bi bi-arrow-right"></i>
                                        </a>

                                        @auth
                                            @if($book->stock > 0 && $book->status === 'approved')
                                                <form
                                                    action="{{ route('frontend.cart.add', $book->id) }}"
                                                    method="POST"
                                                    class="book-cart-form"
                                                    data-cart-form
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="book-cart-btn"
                                                        title="Add to Cart"
                                                        aria-label="Add {{ $book->title }} to cart"
                                                    >
                                                        <i class="bi bi-bag-plus"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button
                                                    type="button"
                                                    class="book-cart-btn book-cart-btn-disabled"
                                                    disabled
                                                    title="Out of Stock"
                                                    aria-label="Out of Stock"
                                                >
                                                    <i class="bi bi-bag-x"></i>
                                                </button>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('frontend.auth.login') }}"
                                                class="book-cart-btn"
                                                title="Login to add to cart"
                                                aria-label="Login to add this book to cart"
                                            >
                                                <i class="bi bi-bag-plus"></i>
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            @if($books->hasPages())
                <div class="books-pagination">
                    <div class="books-pagination-info">
                        Showing
                        <strong>{{ $books->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $books->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $books->total() }}</strong>
                        results
                    </div>

                    <div class="books-pagination-links">
                        @if($books->onFirstPage())
                            <span class="books-page-link disabled">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        @else
                            <a
                                href="{{ $books->previousPageUrl() }}"
                                class="books-page-link"
                                data-page="{{ $books->currentPage() - 1 }}"
                                aria-label="Previous page"
                            >
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif

                        @if($books->currentPage() > 3)
                            <a href="{{ $books->url(1) }}" class="books-page-link" data-page="1">1</a>

                            @if($books->currentPage() > 4)
                                <span class="books-page-ellipsis">...</span>
                            @endif
                        @endif

                        @foreach($books->getUrlRange(
                            max(1, $books->currentPage() - 2),
                            min($books->lastPage(), $books->currentPage() + 2)
                        ) as $page => $url)
                            @if($page == $books->currentPage())
                                <span class="books-page-link active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="books-page-link" data-page="{{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($books->currentPage() < $books->lastPage() - 2)
                            @if($books->currentPage() < $books->lastPage() - 3)
                                <span class="books-page-ellipsis">...</span>
                            @endif

                            <a
                                href="{{ $books->url($books->lastPage()) }}"
                                class="books-page-link"
                                data-page="{{ $books->lastPage() }}"
                            >
                                {{ $books->lastPage() }}
                            </a>
                        @endif

                        @if($books->hasMorePages())
                            <a
                                href="{{ $books->nextPageUrl() }}"
                                class="books-page-link"
                                data-page="{{ $books->currentPage() + 1 }}"
                                aria-label="Next page"
                            >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="books-page-link disabled">
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="books-empty">
                <div class="books-empty-visual">
                    <div class="empty-book empty-book-one"></div>
                    <div class="empty-book empty-book-two"></div>
                    <div class="empty-book empty-book-three"></div>
                    <i class="bi bi-search"></i>
                </div>

                <span>NOTHING FOUND</span>
                <h3>No Books Found</h3>

                <p>
                    We couldn't find any books matching
                    your current filters.
                </p>

                <a href="{{ route('frontend.books') }}" class="books-clear-btn">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    View All Books
                </a>
            </div>
        @endif
    </div>
</section>

<section class="books-sell-section">
    <div class="container">
        <div class="books-sell-card">
            <div class="books-sell-image">
                <img
                    src="https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=900&q=85"
                    alt="Books"
                    loading="lazy"
                >
            </div>

            <div class="books-sell-content">
                <span>GIVE YOUR BOOK ANOTHER CHAPTER</span>

                <h2>
                    Have Books Sitting
                    <em>Unused?</em>
                </h2>

                <p>
                    Sell your books on SecondBook and let
                    someone else discover the story you once loved.
                </p>

                <a href="#" class="books-sell-btn">
                    Sell Your Books
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
<script>
(function () {
    "use strict";

    /* =========================================================
       CART BUTTON ANIMATION FIX
    ========================================================= */

    function installCartAnimationFix() {
        if (document.getElementById('books-cart-animation-fix')) {
            return;
        }

        const style = document.createElement('style');
        style.id = 'books-cart-animation-fix';

        style.textContent = `
            .book-cart-btn.cart-ajax-processing::before,
            .book-cart-btn.cart-ajax-processing::after,
            .book-cart-btn.is-adding::before,
            .book-cart-btn.is-adding::after {
                content: none !important;
                display: none !important;
                animation: none !important;
            }

            .book-cart-btn.cart-ajax-processing {
                white-space: nowrap;
                overflow: hidden;
            }
        `;

        document.head.appendChild(style);
    }

    /* =========================================================
       CART SUCCESS NOTIFICATION
    ========================================================= */

    function showCartAlert() {
        let alertElement = document.getElementById('cart-success-alert');

        if (!alertElement) {
            alertElement = document.createElement('div');
            alertElement.id = 'cart-success-alert';
            alertElement.className = 'cart-success-alert';

            alertElement.innerHTML = `
                <div class="cart-alert-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div class="cart-alert-content">
                    <strong>Success</strong>
                    <span>Book added to cart successfully!</span>
                </div>

                <button type="button" class="cart-alert-close" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            `;

            document.body.appendChild(alertElement);

            alertElement.querySelector('.cart-alert-close')
                ?.addEventListener('click', hideCartAlert);
        }

        alertElement.classList.add('is-visible');

        window.clearTimeout(window.cartAlertTimeout);
        window.cartAlertTimeout = window.setTimeout(hideCartAlert, 3000);
    }

    function hideCartAlert() {
        document.getElementById('cart-success-alert')
            ?.classList.remove('is-visible');
    }

    /* =========================================================
       WISHLIST AJAX
    ========================================================= */

    function initWishlist() {
        if (document.documentElement.dataset.wishlistReady === 'true') {
            return;
        }

        document.documentElement.dataset.wishlistReady = 'true';

        document.addEventListener('submit', async function (event) {
            const form = event.target.closest('[data-wishlist-form]');

            if (!form) {
                return;
            }

            event.preventDefault();

            const button = form.querySelector('button[type="submit"]');
            const icon = form.querySelector('i');

            if (!button || !icon || button.disabled) {
                return;
            }

            const isActive = button.classList.contains('active') ||
                form.dataset.active === 'true';

            const addUrl = form.dataset.addUrl;
            const removeUrl = form.dataset.removeUrl;
            const url = isActive ? removeUrl : addUrl;

            if (!url) {
                console.error('Wishlist URL not found.');
                return;
            }

            const csrfInput = form.querySelector('input[name="_token"]');
            const csrfToken = csrfInput
                ? csrfInput.value
                : document.querySelector('meta[name="csrf-token"]')?.content;

            if (!csrfToken) {
                console.error('CSRF token not found.');
                return;
            }

            try {
                button.disabled = true;

                const response = await fetch(url, {
                    method: isActive ? 'DELETE' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const contentType = response.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {
                    throw new Error('Wishlist server did not return a JSON response.');
                }

                const data = await response.json();

                if (!response.ok ||
                    (data.success !== true && data.status !== 'success')) {
                    throw new Error(data.message || 'Unable to update wishlist.');
                }

                if (isActive) {
                    button.classList.remove('active');
                    form.dataset.active = 'false';

                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');

                    form.setAttribute('action', addUrl);

                    form.querySelector('input[name="_method"]')?.remove();

                    button.setAttribute('aria-label', 'Add to wishlist');
                    button.setAttribute('title', 'Add to wishlist');
                } else {
                    button.classList.add('active');
                    form.dataset.active = 'true';

                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');

                    form.setAttribute('action', removeUrl);

                    let methodInput = form.querySelector('input[name="_method"]');

                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                    }

                    button.setAttribute('aria-label', 'Remove from wishlist');
                    button.setAttribute('title', 'Remove from wishlist');
                }
            } catch (error) {
                console.error('Wishlist error:', error);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: error.message || 'Unable to update wishlist.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    window.alert(error.message || 'Unable to update wishlist.');
                }
            } finally {
                button.disabled = false;
            }
        });
    }

    /* =========================================================
       CART AJAX
    ========================================================= */

    function initCart() {
        installCartAnimationFix();

        document.querySelectorAll('[data-cart-form]').forEach(function (form) {
            if (form.dataset.ajaxReady === 'true') {
                return;
            }

            form.dataset.ajaxReady = 'true';

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();

                const button = form.querySelector('button[type="submit"]');

                if (!button || button.disabled ||
                    button.dataset.cartProcessing === 'true') {
                    return;
                }

                button.dataset.cartProcessing = 'true';

                const originalHtml =
                    '<i class="bi bi-bag-plus" aria-hidden="true"></i>';

                const csrfToken =
                    form.querySelector('input[name="_token"]')?.value ||
                    document.querySelector('meta[name="csrf-token"]')?.content;

                let succeeded = false;

                try {
                    if (!csrfToken) {
                        throw new Error('CSRF token not found.');
                    }

                    button.disabled = true;
                    button.classList.add('is-adding', 'cart-ajax-processing');
                    button.setAttribute('aria-busy', 'true');

                    button.innerHTML =
                        '<span class="cart-loading-spinner" aria-hidden="true"></span>';

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    });

                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        throw new Error('Server JSON cavabı qaytarmadı.');
                    }

                    const data = await response.json();

                    if (!response.ok ||
                        (data.success !== true && data.status !== 'success')) {
                        throw new Error(
                            data.message || 'Kitabı səbətə əlavə etmək mümkün olmadı.'
                        );
                    }

                    succeeded = true;

                    const cartCount = document.getElementById('header-cart-count');

                    if (cartCount && data.cart_count !== undefined) {
                        const count = Number(data.cart_count);

                        cartCount.textContent = count > 99 ? '99+' : String(count);
                        cartCount.style.display = count > 0 ? 'inline-flex' : 'none';

                        cartCount.classList.remove('cart-count-bump');
                        void cartCount.offsetWidth;
                        cartCount.classList.add('cart-count-bump');
                    }

                    showCartAlert();

                    button.innerHTML =
                        '<span class="cart-success-icon" aria-hidden="true">' +
                            '<i class="bi bi-check-lg"></i>' +
                        '</span>';
                } catch (error) {
                    console.error('Cart error:', error);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: error.message || 'Unable to add the book to cart.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        window.alert(
                            error.message || 'Unable to add the book to cart.'
                        );
                    }
                } finally {
                    window.setTimeout(function () {
                        button.innerHTML = originalHtml;

                        button.classList.remove(
                            'is-adding',
                            'cart-ajax-processing'
                        );

                        button.removeAttribute('aria-busy');
                        button.disabled = false;

                        delete button.dataset.cartProcessing;
                    }, succeeded ? 900 : 0);
                }
            });
        });
    }

    /* =========================================================
       AJAX COLLECTION UPDATE
       Pagination, filters, clear filters and browser history.
    ========================================================= */

    let booksRequestController = null;

    function scrollToAvailableBooks() {
        const availableBooks = document.getElementById('available-books');

        if (!availableBooks) {
            return;
        }

        const position = availableBooks.getBoundingClientRect().top +
            window.pageYOffset - 30;

        window.scrollTo({
            top: position,
            behavior: 'smooth'
        });
    }

    function setBooksLoading(isLoading) {
        const collection = document.getElementById('books-collection');

        if (collection) {
            collection.classList.toggle('is-loading', isLoading);
            collection.setAttribute('aria-busy', isLoading ? 'true' : 'false');
        }
    }

    async function loadBooks(url, options = {}) {
        const settings = {
            pushHistory: true,
            scroll: true,
            ...options
        };

        const collection = document.getElementById('books-collection');

        if (!collection || !url) {
            return;
        }

        if (booksRequestController) {
            booksRequestController.abort();
        }

        booksRequestController = new AbortController();
        setBooksLoading(true);

        try {
            const response = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                signal: booksRequestController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error('Kitabları yükləmək mümkün olmadı.');
            }

            const html = await response.text();
            const parsedDocument = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            const newCollection =
                parsedDocument.getElementById('books-collection');

            if (!newCollection) {
                throw new Error('Books collection tapılmadı.');
            }

            /*
             * The outer collection element remains in place.
             * Only its inner content is replaced, so delegated handlers survive.
             */
            collection.innerHTML = newCollection.innerHTML;

            if (settings.pushHistory) {
                window.history.pushState({}, '', url);
            }

            initCart();

            if (settings.scroll) {
                window.requestAnimationFrame(scrollToAvailableBooks);
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error('Books AJAX error:', error);

            /*
             * Graceful fallback if the server response cannot be processed.
             */
            if (settings.fallback !== false) {
                window.location.href = url;
            }
        } finally {
            setBooksLoading(false);
            booksRequestController = null;
        }
    }

    function initBooksFilter() {
        const collection = document.getElementById('books-collection');

        if (!collection || collection.dataset.ajaxPaginationReady === 'true') {
            return;
        }

        collection.dataset.ajaxPaginationReady = 'true';

        /*
         * Event delegation is attached to document because the filter form
         * and pagination are replaced after each successful AJAX request.
         */
        document.addEventListener('submit', function (event) {
            const form = event.target.closest('#books-filter-form');

            if (!form) {
                return;
            }

            event.preventDefault();

            const formData = new FormData(form);
            const params = new URLSearchParams();

            formData.forEach(function (value, key) {
                if (value !== null && String(value).trim() !== '') {
                    params.append(key, value);
                }
            });

            const baseUrl = form.getAttribute('action');
            const queryString = params.toString();
            const url = queryString
                ? `${baseUrl}?${queryString}`
                : baseUrl;

            loadBooks(url, {
                pushHistory: true,
                scroll: true
            });
        });

        /*
         * Pagination and clear-filter links are intercepted without
         * changing any existing route or link URL.
         */
        document.addEventListener('click', function (event) {
            const paginationLink = event.target.closest(
                '#books-collection .books-pagination-links a.books-page-link'
            );

            const clearFilterLink = event.target.closest(
                '#books-collection .books-clear-filter, #books-collection .books-clear-btn'
            );

            const link = paginationLink || clearFilterLink;

            if (!link) {
                return;
            }

            if (
                event.button !== 0 ||
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey
            ) {
                return;
            }

            const url = link.getAttribute('href');

            if (!url || url === '#') {
                return;
            }

            event.preventDefault();

            loadBooks(url, {
                pushHistory: true,
                scroll: true
            });
        });

        /*
         * Restore the previous/next filtered result through AJAX.
         */
        window.addEventListener('popstate', function () {
            loadBooks(window.location.href, {
                pushHistory: false,
                scroll: true,
                fallback: false
            });
        });
    }

    /* =========================================================
       INITIALIZATION
    ========================================================= */

    function initBooksPage() {
        initWishlist();
        initCart();
        initBooksFilter();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBooksPage);
    } else {
        initBooksPage();
    }
})();
</script>
@endpush
