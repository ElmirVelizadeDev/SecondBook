@extends('Layout.Frontend.master')

@section('title', $store->name . ' | SecondBook')

@push('css')

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend-assets/css/store.css') }}">

@endpush

@section('content')

@php

    $cartRouteName = 'cart.add';

    $cartEnabled = \Illuminate\Support\Facades\Route::has($cartRouteName);

    $resolveImage = function ($path) {

        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/') || str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    };

    $storeLogoUrl = $resolveImage($store->logo);

    $displayRating = $averageRating !== null
        ? number_format((float) $averageRating, 1)
        : '—';

    $acceptsOrders = (bool) ($store->accept_orders ?? true);

    $ratingPercentages = [];

    foreach ($ratingBreakdown as $ratingValue => $count) {

        $ratingPercentages[$ratingValue] = $reviewsCount > 0
            ? round(($count / $reviewsCount) * 100)
            : 0;
    }

    $selectedCategory = request('category')
        ? $categories->firstWhere('id', request('category'))
        : null;

    $storeUrl = fn (array $except = [], array $add = []) => route(
        'store.show',
        array_merge(
            ['store' => $store->slug],
            request()->except(array_merge($except, ['page', 'reviews_page'])),
            $add
        )
    ) . '#store-books';

    $hasFilters = request('search') || request('category') || request('sort');

@endphp

<div class="store-page">

    {{-- =========================================================
        FLASH
        ========================================================= --}}
    @if(session('success') || session('error'))

        <div class="store-toast {{ session('error') ? 'is-error' : 'is-success' }}" role="status">

            <i class="bi {{ session('error') ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill' }}"></i>

            <span>{{ session('error') ?? session('success') }}</span>

            <button type="button" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

    @endif


    {{-- =========================================================
        BREADCRUMB
        ========================================================= --}}
    <div class="store-container">

        <nav class="store-breadcrumb" aria-label="Breadcrumb">

            <a href="{{ route('frontend.home') }}">
                <i class="bi bi-house"></i>
                <span>Home</span>
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('frontend.books') }}">
                Books
            </a>

            <i class="bi bi-chevron-right"></i>

            <strong>{{ $store->name }}</strong>

        </nav>

    </div>


    {{-- =========================================================
        HERO
        ========================================================= --}}
    <section class="store-hero">

        <div class="store-hero-bg" aria-hidden="true">

            <span class="store-hero-orb store-hero-orb-one"></span>

            <span class="store-hero-orb store-hero-orb-two"></span>

        </div>

        <div class="store-container">

            <div class="store-hero-main">

                <div class="store-logo">

                    @if($storeLogoUrl)

                        <img
                            src="{{ $storeLogoUrl }}"
                            alt="{{ $store->name }}"
                        >

                    @else

                        <div class="store-logo-placeholder">
                            <i class="bi bi-shop"></i>
                        </div>

                    @endif

                </div>


                <div class="store-identity">

                    <span class="store-kicker">
                        <span></span>
                        Official Store
                    </span>

                    <div class="store-name-row">

                        <h1>{{ $store->name }}</h1>

                        <span class="store-verified">
                            <i class="bi bi-patch-check-fill"></i>
                            Verified
                        </span>

                    </div>

                    <div class="store-meta">

                        <span class="store-meta-item">

                            <i class="bi bi-person"></i>

                            {{ $store->seller?->name ?? 'Seller' }}

                        </span>

                        @if($store->address)

                            <span class="store-meta-item">

                                <i class="bi bi-geo-alt"></i>

                                {{ $store->address }}

                            </span>

                        @endif

                        <span class="store-meta-item store-status {{ $acceptsOrders ? 'is-open' : 'is-paused' }}">

                            <span class="store-status-dot"></span>

                            {{ $acceptsOrders ? 'Active Store' : 'Orders Paused' }}

                        </span>

                    </div>

                </div>


                <div class="store-hero-action">

                    @if(auth()->check())
                        <button
                            type="button"
                            class="store-btn store-btn-light"
                            data-bs-toggle="modal"
                            data-bs-target="#contactStoreModal"
                        >
                            <i class="bi bi-chat-left-text"></i>
                            <span>Contact Store</span>
                        </button>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="store-btn store-btn-light"
                        >
                            <i class="bi bi-chat-left-text"></i>
                            <span>Login to Contact</span>
                        </a>
                    @endif

                    <a
                        href="#store-books"
                        class="store-btn store-btn-ghost"
                    >
                        <i class="bi bi-book"></i>
                        <span>Browse Books</span>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        STATS
        ========================================================= --}}
    <div class="store-container">

        <div class="store-stats">

            <div class="store-stat">

                <div class="store-stat-icon">
                    <i class="bi bi-book"></i>
                </div>

                <div>
                    <strong>{{ number_format($booksCount) }}</strong>
                    <span>{{ $booksCount === 1 ? 'Book' : 'Books' }}</span>
                </div>

            </div>


            <div class="store-stat">

                <div class="store-stat-icon">
                    <i class="bi bi-star-fill"></i>
                </div>

                <div>
                    <strong>{{ $displayRating }}</strong>
                    <span>Average Rating</span>
                </div>

            </div>


            <div class="store-stat">

                <div class="store-stat-icon">
                    <i class="bi bi-chat-square-text"></i>
                </div>

                <div>
                    <strong>{{ number_format($reviewsCount) }}</strong>
                    <span>{{ $reviewsCount === 1 ? 'Review' : 'Reviews' }}</span>
                </div>

            </div>


            <div class="store-stat">

                <div class="store-stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <strong>Verified</strong>
                    <span>Marketplace Seller</span>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ABOUT
        ========================================================= --}}
    <section class="store-about">

        <div class="store-container">

            <div class="store-about-card">

                <div class="store-about-text">

                    <span class="store-eyebrow">
                        <span></span>
                        About This Store
                    </span>

                    <h2>{{ $store->name }}</h2>

                    @if($store->description)

                        <p>{{ $store->description }}</p>

                    @else

                        <p class="store-muted">
                            This store has not added a description yet.
                        </p>

                    @endif

                </div>


                <div class="store-about-info">

                    <div class="store-info-item">

                        <div class="store-info-icon">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>
                            <span>Seller</span>
                            <strong>{{ $store->seller?->name ?? 'Seller' }}</strong>
                        </div>

                    </div>


                    @if($store->address)

                        <div class="store-info-item">

                            <div class="store-info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>

                            <div>
                                <span>Location</span>
                                <strong>{{ $store->address }}</strong>
                            </div>

                        </div>

                    @endif


                    @if($store->phone)

                        <div class="store-info-item">

                            <div class="store-info-icon">
                                <i class="bi bi-telephone"></i>
                            </div>

                            <div>
                                <span>Phone</span>
                                <strong>{{ $store->phone }}</strong>
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        CONTENT
        ========================================================= --}}
    <section class="store-content">

        <div class="store-container">

            <div class="store-layout">

                {{-- =================================================
                    SIDEBAR
                    ================================================= --}}
                <aside class="store-sidebar">


                    {{-- ================= SEARCH ================= --}}
                    <div class="store-filter-card store-filter-card--search">

                        <div class="store-filter-heading">

                            <h3>Find a Book</h3>

                            <i class="bi bi-search"></i>

                        </div>


                        <form
                            action="{{ route('store.show', $store->slug) }}#store-books"
                            method="GET"
                            class="store-search-form"
                        >

                            @if(request('category'))

                                <input
                                    type="hidden"
                                    name="category"
                                    value="{{ request('category') }}"
                                >

                            @endif


                            @if(request('sort'))

                                <input
                                    type="hidden"
                                    name="sort"
                                    value="{{ request('sort') }}"
                                >

                            @endif


                            <div class="store-search">

                                <i class="bi bi-search"></i>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search this store..."
                                    autocomplete="off"
                                >

                                @if(request('search'))

                                    <a
                                        href="{{ $storeUrl(['search']) }}"
                                        class="store-search-clear"
                                        aria-label="Clear search"
                                    >
                                        <i class="bi bi-x"></i>
                                    </a>

                                @endif

                            </div>

                        </form>

                    </div>


                    {{-- ================= CATEGORIES ================= --}}
                    <div class="store-filter-card store-filter-card--categories">

                        <div class="store-filter-heading">

                            <h3>Categories</h3>

                            <i class="bi bi-grid"></i>

                        </div>


                        <div class="store-category-list">

                            <a
                                href="{{ $storeUrl(['category']) }}"
                                class="store-category-item {{ ! request('category') ? 'active' : '' }}"
                            >

                                <span>
                                    <i class="bi bi-grid-3x3-gap"></i>
                                    All Books
                                </span>

                                <i class="bi bi-arrow-right"></i>

                            </a>


                            @foreach($categories as $category)

                                <a
                                    href="{{ $storeUrl(['category'], ['category' => $category->id]) }}"
                                    class="store-category-item {{ (string) request('category') === (string) $category->id ? 'active' : '' }}"
                                >

                                    <span>
                                        <i class="bi bi-bookmark"></i>
                                        {{ $category->name }}
                                    </span>

                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            @endforeach

                        </div>

                    </div>


                    {{-- ================= RATING ================= --}}
                    <div class="store-filter-card store-filter-card--rating">

                        <div class="store-filter-heading">

                            <h3>Store Rating</h3>

                            <i class="bi bi-star"></i>

                        </div>


                        @if($averageRating !== null)

                            <div class="store-rating-summary">

                                <strong>{{ $displayRating }}</strong>

                                <div>

                                    <div class="store-rating-stars">

                                        @for($i = 1; $i <= 5; $i++)

                                            <i class="bi bi-star-fill"></i>

                                        @endfor

                                    </div>

                                    <span>

                                        {{ number_format($reviewsCount) }}

                                        approved

                                        {{ $reviewsCount === 1 ? 'review' : 'reviews' }}

                                    </span>

                                </div>

                            </div>


                            <div class="store-rating-breakdown">

                                @for($ratingValue = 5; $ratingValue >= 1; $ratingValue--)

                                    <div class="store-rating-row">

                                        <span>
                                            {{ $ratingValue }}
                                            <i class="bi bi-star-fill"></i>
                                        </span>

                                        <div class="store-rating-progress">

                                            <span
                                                style="width: {{ $ratingPercentages[$ratingValue] ?? 0 }}%"
                                            ></span>

                                        </div>

                                        <small>
                                            {{ $ratingBreakdown[$ratingValue] ?? 0 }}
                                        </small>

                                    </div>

                                @endfor

                            </div>

                        @else

                            <div class="store-no-rating">

                                <div class="store-no-rating-icon">
                                    <i class="bi bi-star"></i>
                                </div>

                                <strong>No ratings yet</strong>

                                <span>
                                    Reviews will appear here once approved.
                                </span>

                            </div>

                        @endif

                    </div>

                </aside>


                {{-- =================================================
                    MAIN
                    ================================================= --}}
                <main class="store-main">


                    {{-- ================= BOOKS HEADING ================= --}}
                    <div
                        class="store-books-heading"
                        id="store-books"
                    >

                        <div class="store-books-title">

                            <span class="store-eyebrow">
                                <span></span>
                                Store Collection
                            </span>

                            <h2>
                                Books from {{ $store->name }}
                            </h2>

                            <p>
                                Explore the latest books available from this store.
                            </p>

                        </div>


                        <form
                            action="{{ route('store.show', $store->slug) }}#store-books"
                            method="GET"
                            class="store-sort-form"
                        >

                            @if(request('search'))

                                <input
                                    type="hidden"
                                    name="search"
                                    value="{{ request('search') }}"
                                >

                            @endif


                            @if(request('category'))

                                <input
                                    type="hidden"
                                    name="category"
                                    value="{{ request('category') }}"
                                >

                            @endif


                            <label for="store-sort">
                                Sort
                            </label>

                            <div class="store-sort">

                                <select
                                    id="store-sort"
                                    name="sort"
                                >

                                    <option
                                        value=""
                                        {{ ! request('sort') ? 'selected' : '' }}
                                    >
                                        Newest
                                    </option>

                                    <option
                                        value="price_low"
                                        {{ request('sort') === 'price_low' ? 'selected' : '' }}
                                    >
                                        Price: Low to High
                                    </option>

                                    <option
                                        value="price_high"
                                        {{ request('sort') === 'price_high' ? 'selected' : '' }}
                                    >
                                        Price: High to Low
                                    </option>

                                    <option
                                        value="oldest"
                                        {{ request('sort') === 'oldest' ? 'selected' : '' }}
                                    >
                                        Oldest
                                    </option>

                                </select>

                                <i class="bi bi-chevron-down"></i>

                            </div>

                        </form>

                    </div>


                    {{-- ================= ACTIVE FILTERS ================= --}}
                    @if($hasFilters)

                        <div class="store-active-filters">

                            <span class="store-filter-label">

                                <i class="bi bi-funnel"></i>

                                Filters

                            </span>


                            <div class="store-filter-chips">

                                @if(request('search'))

                                    <span class="store-filter-chip">

                                        <span>
                                            Search:
                                            <strong>{{ request('search') }}</strong>
                                        </span>

                                        <a
                                            href="{{ $storeUrl(['search']) }}"
                                            aria-label="Remove search"
                                        >
                                            <i class="bi bi-x"></i>
                                        </a>

                                    </span>

                                @endif


                                @if($selectedCategory)

                                    <span class="store-filter-chip">

                                        <span>
                                            Category:
                                            <strong>{{ $selectedCategory->name }}</strong>
                                        </span>

                                        <a
                                            href="{{ $storeUrl(['category']) }}"
                                            aria-label="Remove category"
                                        >
                                            <i class="bi bi-x"></i>
                                        </a>

                                    </span>

                                @endif


                                @if(request('sort'))

                                    <span class="store-filter-chip">

                                        <span>

                                            Sort:

                                            <strong>

                                                @switch(request('sort'))

                                                    @case('price_low')
                                                        Price: Low to High
                                                        @break

                                                    @case('price_high')
                                                        Price: High to Low
                                                        @break

                                                    @case('oldest')
                                                        Oldest
                                                        @break

                                                    @default
                                                        Newest

                                                @endswitch

                                            </strong>

                                        </span>

                                        <a
                                            href="{{ $storeUrl(['sort']) }}"
                                            aria-label="Remove sort"
                                        >
                                            <i class="bi bi-x"></i>
                                        </a>

                                    </span>

                                @endif

                            </div>


                            <a
                                href="{{ route('store.show', $store->slug) }}#store-books"
                                class="store-clear-filters"
                            >
                                Clear all
                            </a>

                        </div>

                    @endif


                    {{-- =================================================
                        BOOK GRID
                        ================================================= --}}
                    @if($books->count())

                        <div class="store-books-grid">

                            @foreach($books as $book)

                                @php

                                    $bookCoverUrl = $resolveImage($book->cover);

                                    $isDiscounted =
                                        $book->discount_value !== null
                                        && (
                                            $book->discount_start_at === null
                                            || now()->greaterThanOrEqualTo($book->discount_start_at)
                                        )
                                        && (
                                            $book->discount_end_at === null
                                            || now()->lessThanOrEqualTo($book->discount_end_at)
                                        );

                                    $finalPrice = (float) $book->price;

                                    if ($isDiscounted) {

                                        $finalPrice = $book->discount_type === 'percentage'
                                            ? $book->price - ($book->price * ($book->discount_value / 100))
                                            : max(0, $book->price - $book->discount_value);
                                    }

                                    $inStock = $book->stock > 0;

                                @endphp


                                <article class="store-book-card {{ $inStock ? '' : 'is-sold-out' }}">

                                    <a
                                        href="{{ route('frontend.books.show', $book) }}"
                                        class="store-book-cover"
                                    >

                                        @if($bookCoverUrl)

                                            <img
                                                src="{{ $bookCoverUrl }}"
                                                alt="{{ $book->title }}"
                                                loading="lazy"
                                            >

                                        @else

                                            <div class="store-book-cover-placeholder">

                                                <i class="bi bi-book"></i>

                                                <span>No Cover</span>

                                            </div>

                                        @endif


                                        @if($isDiscounted)

                                            <span class="store-discount-badge">
                                                Sale
                                            </span>

                                        @endif


                                        @if($book->condition)

                                            <span class="store-condition-badge">

                                                {{ ucwords(str_replace('_', ' ', $book->condition)) }}

                                            </span>

                                        @endif

                                    </a>


                                    <div class="store-book-body">

                                        @if($book->category)

                                            <span class="store-book-category">
                                                {{ $book->category->name }}
                                            </span>

                                        @endif


                                        <h3>

                                            <a href="{{ route('frontend.books.show', $book) }}">
                                                {{ $book->title }}
                                            </a>

                                        </h3>


                                        @if($book->author)

                                            <p class="store-book-author">

                                                <i class="bi bi-person"></i>

                                                <span>
                                                    {{ $book->author->name }}
                                                </span>

                                            </p>

                                        @endif


                                        @if($book->pages)

                                            <div class="store-book-meta">

                                                <span>
                                                    <i class="bi bi-file-text"></i>
                                                    {{ $book->pages }} pages
                                                </span>

                                            </div>

                                        @endif


                                        <div class="store-book-footer">

                                            <div class="store-price">

                                                <strong>
                                                    {{ number_format($finalPrice, 2) }}
                                                    <small>AZN</small>
                                                </strong>

                                            </div>


                                            <span class="store-stock {{ $inStock ? 'in-stock' : 'out-stock' }}">

                                                <span></span>

                                                {{ $inStock ? 'In Stock' : 'Out of Stock' }}

                                            </span>

                                        </div>


                                        {{-- ADD TO CART --}}
                                        <div class="store-cart-slot">

                                            @if($inStock && $acceptsOrders && $cartEnabled)

                                                <form
                                                    action="{{ route($cartRouteName, $book) }}"
                                                    method="POST"
                                                    class="store-cart-form"
                                                >

                                                    @csrf

                                                    <input
                                                        type="hidden"
                                                        name="quantity"
                                                        value="1"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="store-cart-btn"
                                                    >

                                                        <i class="bi bi-cart-plus"></i>

                                                        <span>
                                                            Add to Cart
                                                        </span>

                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="store-cart-btn"
                                                    disabled
                                                >

                                                    <i class="bi {{ $inStock ? 'bi-pause-circle' : 'bi-slash-circle' }}"></i>

                                                    <span>

                                                        @if(! $inStock)

                                                            Out of Stock

                                                        @elseif(! $acceptsOrders)

                                                            Orders Paused

                                                        @else

                                                            Unavailable

                                                        @endif

                                                    </span>

                                                </button>

                                            @endif

                                        </div>

                                    </div>

                                </article>

                            @endforeach

                        </div>


                        {{-- BOOK PAGINATION --}}
                        @if($books->hasPages())

                            <div class="store-pagination">

                                {{ $books
                                    ->withQueryString()
                                    ->fragment('store-books')
                                    ->onEachSide(1)
                                    ->links('')
                                }}

                            </div>

                        @endif

                    @else

                        <div class="store-empty">

                            <div class="store-empty-icon">
                                <i class="bi bi-book"></i>
                            </div>

                            <h3>No books found</h3>

                            <p>
                                We couldn't find any books matching your current filters.
                            </p>

                            <a
                                href="{{ route('store.show', $store->slug) }}#store-books"
                                class="store-btn store-btn-brand"
                            >

                                <i class="bi bi-arrow-counterclockwise"></i>

                                <span>
                                    Reset Filters
                                </span>

                            </a>

                        </div>

                    @endif


                    {{-- =================================================
                        REVIEWS
                        ================================================= --}}
                    <section
                        class="store-reviews-section"
                        id="store-reviews"
                    >

                        <div class="store-reviews-heading">

                            <div>

                                <span class="store-eyebrow">
                                    <span></span>
                                    Customer Feedback
                                </span>

                                <h2>
                                    Store Reviews
                                </h2>

                                <p>
                                    Feedback from customers who purchased books from this store.
                                </p>

                            </div>


                            @if($averageRating !== null)

                                <div class="store-review-score">

                                    <strong>
                                        {{ $displayRating }}
                                    </strong>

                                    <div>

                                        <div class="store-rating-stars">

                                            @for($i = 1; $i <= 5; $i++)

                                                <i class="bi bi-star-fill"></i>

                                            @endfor

                                        </div>

                                        <span>

                                            {{ $reviewsCount }}

                                            {{ $reviewsCount === 1 ? 'review' : 'reviews' }}

                                        </span>

                                    </div>

                                </div>

                            @endif

                        </div>


                        @if($reviews->count())

                            <div class="store-review-list">

                                @foreach($reviews as $review)

                                    <article class="store-review-card">

                                        <div class="store-review-top">

                                            <div class="store-review-user">

                                                <div class="store-review-avatar">

                                                    @if($review->user?->avatar)

                                                        <img
                                                            src="{{ asset('storage/' . ltrim($review->user->avatar, '/')) }}"
                                                            alt="{{ $review->user->name }}"
                                                        >

                                                    @else

                                                        {{ strtoupper(mb_substr($review->user?->name ?? 'U', 0, 1)) }}

                                                    @endif

                                                </div>


                                                <div>

                                                    <strong>
                                                        {{ $review->user?->name ?? 'Customer' }}
                                                    </strong>

                                                    <span>
                                                        {{ $review->created_at->format('M d, Y') }}
                                                    </span>

                                                </div>

                                            </div>


                                            <div class="store-review-rating">

                                                @for($i = 1; $i <= 5; $i++)

                                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>

                                                @endfor

                                            </div>

                                        </div>


                                        @if($review->book)

                                            <a
                                                href="{{ route('frontend.books.show', $review->book) }}"
                                                class="store-review-book"
                                            >

                                                <i class="bi bi-book"></i>

                                                <span>
                                                    {{ $review->book->title }}
                                                </span>

                                                <i class="bi bi-arrow-up-right"></i>

                                            </a>

                                        @endif


                                        @if($review->comment)

                                            <p class="store-review-comment">
                                                “{{ $review->comment }}”
                                            </p>

                                        @else

                                            <p class="store-review-comment store-muted">
                                                No written comment was provided.
                                            </p>

                                        @endif

                                    </article>

                                @endforeach

                            </div>


                            {{-- REVIEW PAGINATION --}}
                            @if($reviews->hasPages())

                                <div class="store-pagination">

                                    {{ $reviews
                                        ->withQueryString()
                                        ->fragment('store-reviews')
                                        ->onEachSide(1)
                                        ->links('')
                                    }}

                                </div>

                            @endif

                        @else

                            <div class="store-no-reviews">

                                <div class="store-no-reviews-icon">
                                    <i class="bi bi-chat-square-text"></i>
                                </div>

                                <h3>
                                    No reviews yet
                                </h3>

                                <p>
                                    This store has not received any approved customer reviews yet.
                                </p>

                            </div>

                        @endif

                    </section>

                </main>

            </div>

        </div>

    </section>

</div>

@auth
    {{-- =========================================================
        CONTACT STORE MODAL
        ========================================================= --}}
    <div
        class="modal fade"
        id="contactStoreModal"
        tabindex="-1"
        aria-labelledby="contactStoreModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content store-contact-modal">

                {{-- HEADER --}}
                <div class="modal-header">

                    <div>
                        <h5
                            class="modal-title"
                            id="contactStoreModalLabel"
                        >
                            Contact Store
                        </h5>

                        <p>
                            Get in touch with {{ $store->name }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="store-contact-layout">

                    {{-- =================================================
                        LEFT — MESSAGE FORM
                        ================================================= --}}
                    <div class="store-contact-form-section">

                        <div class="store-contact-section-heading">

                            <div class="store-contact-section-icon">
                                <i class="bi bi-chat-left-text"></i>
                            </div>

                            <div>
                                <h3>Send a Message</h3>

                                <p>
                                    Write directly to the store seller.
                                </p>
                            </div>

                        </div>


                        <form
                            action="{{ route('store.contact.send', $store->slug) }}"
                            method="POST"
                        >

                            @csrf

                            <div class="modal-body">

                                {{-- CURRENT USER --}}
                                <div class="store-contact-user">

                                    <div class="store-contact-user-icon">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ auth()->user()->full_name ?: auth()->user()->name }}
                                        </strong>

                                        <span>
                                            {{ auth()->user()->email }}
                                        </span>

                                    </div>

                                </div>


                                {{-- SUBJECT --}}
                                <div class="store-contact-field">

                                    <label for="storeContactSubject">
                                        Subject
                                    </label>

                                    <div class="store-contact-select">

                                        <select
                                            id="storeContactSubject"
                                            name="subject"
                                            required
                                        >

                                            <option
                                                value=""
                                                disabled
                                                {{ old('subject') ? '' : 'selected' }}
                                            >
                                                Select a subject
                                            </option>

                                            <option
                                                value="Book Inquiry"
                                                {{ old('subject') === 'Book Inquiry' ? 'selected' : '' }}
                                            >
                                                Book Inquiry
                                            </option>

                                            <option
                                                value="Price & Availability"
                                                {{ old('subject') === 'Price & Availability' ? 'selected' : '' }}
                                            >
                                                Price & Availability
                                            </option>

                                            <option
                                                value="Order & Delivery"
                                                {{ old('subject') === 'Order & Delivery' ? 'selected' : '' }}
                                            >
                                                Order & Delivery
                                            </option>

                                            <option
                                                value="Book Condition"
                                                {{ old('subject') === 'Book Condition' ? 'selected' : '' }}
                                            >
                                                Book Condition
                                            </option>

                                            <option
                                                value="Return & Refund"
                                                {{ old('subject') === 'Return & Refund' ? 'selected' : '' }}
                                            >
                                                Return & Refund
                                            </option>

                                            <option
                                                value="Store Information"
                                                {{ old('subject') === 'Store Information' ? 'selected' : '' }}
                                            >
                                                Store Information
                                            </option>

                                            <option
                                                value="Seller Question"
                                                {{ old('subject') === 'Seller Question' ? 'selected' : '' }}
                                            >
                                                Seller Question
                                            </option>

                                            <option
                                                value="General Inquiry"
                                                {{ old('subject') === 'General Inquiry' ? 'selected' : '' }}
                                            >
                                                General Inquiry
                                            </option>

                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>


                                    @error('subject')

                                        <small class="store-contact-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- MESSAGE --}}
                                <div class="store-contact-field">

                                    <label for="storeContactMessage">
                                        Message
                                    </label>

                                    <textarea
                                        id="storeContactMessage"
                                        name="message"
                                        rows="6"
                                        placeholder="Write your message..."
                                        maxlength="5000"
                                        required
                                    >{{ old('message') }}</textarea>


                                    <div class="store-contact-message-footer">

                                        <span>
                                            Please provide as much detail as possible.
                                        </span>

                                        <span>
                                            <b id="storeMessageCount">0</b>/5000
                                        </span>

                                    </div>


                                    @error('message')

                                        <small class="store-contact-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>

                            </div>


                            {{-- FOOTER --}}
                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="store-btn store-btn-ghost"
                                    data-bs-dismiss="modal"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="store-btn store-btn-primary"
                                    id="storeContactSubmit"
                                >

                                    <i class="bi bi-send"></i>

                                    <span>
                                        Send Message
                                    </span>

                                </button>

                            </div>

                        </form>

                    </div>


                    {{-- =================================================
                        RIGHT — STORE INFORMATION
                        ================================================= --}}
                    <aside class="store-contact-info">

                        <div class="store-contact-info-heading">

                            <span class="store-contact-info-eyebrow">
                                Store Information
                            </span>

                            <h3>
                                {{ $store->name }}
                            </h3>

                            <p>
                                Contact the seller directly using
                                the information below.
                            </p>

                        </div>


                        {{-- EMAIL --}}
                        @if($store->seller?->email)

                            <a
                                href="mailto:{{ $store->seller->email }}"
                                class="store-contact-info-item"
                            >

                                <div class="store-contact-info-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>

                                <div>

                                    <span>
                                        Email
                                    </span>

                                    <strong>
                                        {{ $store->seller->email }}
                                    </strong>

                                </div>

                                <i class="bi bi-arrow-up-right"></i>

                            </a>

                        @endif


                        {{-- PHONE --}}
                        @if($store->phone)

                            <a
                                href="tel:{{ $store->phone }}"
                                class="store-contact-info-item"
                            >

                                <div class="store-contact-info-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>

                                <div>

                                    <span>
                                        Phone
                                    </span>

                                    <strong>
                                        {{ $store->phone }}
                                    </strong>

                                </div>

                                <i class="bi bi-arrow-up-right"></i>

                            </a>

                        @endif


                        {{-- ADDRESS --}}
                        @if($store->address)

                            <div class="store-contact-info-item">

                                <div class="store-contact-info-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div>

                                    <span>
                                        Address
                                    </span>

                                    <strong>
                                        {{ $store->address }}
                                    </strong>

                                </div>

                            </div>

                        @endif


                        {{-- SELLER --}}
                        <div class="store-contact-info-item">

                            <div class="store-contact-info-icon">
                                <i class="bi bi-person"></i>
                            </div>

                            <div>

                                <span>
                                    Seller
                                </span>

                                <strong>
                                    {{ $store->seller?->full_name ?: $store->seller?->name ?? 'Seller' }}
                                </strong>

                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div class="store-contact-status">

                            <span class="store-contact-status-dot"></span>

                            <div>

                                <strong>
                                    {{ $acceptsOrders
                                        ? 'Store is Active'
                                        : 'Orders are Paused'
                                    }}
                                </strong>

                                <span>
                                    {{ $acceptsOrders
                                        ? 'The seller is currently accepting orders.'
                                        : 'The seller has temporarily paused orders.'
                                    }}
                                </span>

                            </div>

                        </div>

                    </aside>

                </div>

            </div>
        </div>
    </div>
@endauth


{{-- =============================================================
    STORE AJAX NAVIGATION
    Search / Category / Sort / Filters / Pagination
    ============================================================= --}}
<script>

(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const storePage = document.querySelector('.store-page');

    if (!storePage) {
        return;
    }


    const getLayout = function (documentObject) {

        return documentObject.querySelector('.store-layout');

    };


    const getCurrentUrl = function (url) {

        return new URL(url, window.location.origin);

    };


    const setLoading = function (state) {

        storePage.classList.toggle('is-ajax-loading', state);

    };


    const scrollToTarget = function (hash) {

        if (!hash) {
            return;
        }

        const targetId = hash.replace('#', '');

        const target = document.getElementById(targetId);

        if (!target) {
            return;
        }

        /*
        | Slight delay allows the newly rendered DOM to finish layout.
        */
        window.requestAnimationFrame(function () {

            const offset = 90;

            const top =
                target.getBoundingClientRect().top
                + window.scrollY
                - offset;

            window.scrollTo({
                top: Math.max(0, top),
                behavior: 'smooth'
            });

        });

    };


    /*
    |--------------------------------------------------------------------------
    | AJAX GET
    |--------------------------------------------------------------------------
    */

    const loadStoreContent = async function (url, options = {}) {

        const targetUrl = getCurrentUrl(url);

        setLoading(true);


        try {

            const response = await fetch(targetUrl.href, {

                method: 'GET',

                headers: {

                    'X-Requested-With': 'XMLHttpRequest',

                    'X-Partial-Request': 'store',

                    'Accept': 'text/html'

                },

                credentials: 'same-origin'

            });


            if (!response.ok) {
                throw new Error('Request failed');
            }


            const html = await response.text();


            const parser = new DOMParser();

            const newDocument = parser.parseFromString(
                html,
                'text/html'
            );


            const newLayout = getLayout(newDocument);

            const currentLayout = getLayout(document);


            /*
            |--------------------------------------------------------------------------
            | Make sure server returned the expected Store layout.
            |--------------------------------------------------------------------------
            */

            if (!newLayout || !currentLayout) {
                throw new Error('Store layout not found');
            }


            /*
            |--------------------------------------------------------------------------
            | Replace only the store content.
            |
            | Hero / stats / about stay untouched.
            | Sidebar + books + reviews are replaced.
            |--------------------------------------------------------------------------
            */

            currentLayout.replaceWith(
                newLayout
            );


            /*
            |--------------------------------------------------------------------------
            | Update browser URL without full page reload.
            |--------------------------------------------------------------------------
            */

            if (options.replaceState) {

                window.history.replaceState(
                    {
                        storeAjax: true
                    },
                    '',
                    targetUrl.href
                );

            } else {

                window.history.pushState(
                    {
                        storeAjax: true
                    },
                    '',
                    targetUrl.href
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Restore requested section.
            |--------------------------------------------------------------------------
            */

            if (options.scroll !== false) {

                scrollToTarget(
                    options.hash || targetUrl.hash
                );

            }


        } catch (error) {

            /*
            |--------------------------------------------------------------------------
            | If AJAX fails, use normal browser navigation.
            | This keeps the page functional even if JS/fetch fails.
            |--------------------------------------------------------------------------
            */

            window.location.href = targetUrl.href;

            return;

        } finally {

            setLoading(false);

        }

    };


    /*
    |--------------------------------------------------------------------------
    | SEARCH FORM
    |--------------------------------------------------------------------------
    |
    | Search happens when the user presses Enter.
    | The page itself does NOT reload.
    |
    */

    storePage.addEventListener('submit', function (event) {

        const form = event.target.closest('.store-search-form');

        if (!form) {
            return;
        }


        event.preventDefault();


        const formData = new FormData(form);

        const params = new URLSearchParams();


        formData.forEach(function (value, key) {

            if (value !== '') {

                params.set(
                    key,
                    value
                );

            }

        });


        /*
        | Search starts from page 1.
        */

        params.delete('page');

        params.delete('reviews_page');


        const action = form.getAttribute('action')
            || window.location.pathname;


        const url = new URL(
            action,
            window.location.origin
        );


        url.search = params.toString();

        url.hash = 'store-books';


        loadStoreContent(
            url.href,
            {
                hash: '#store-books'
            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

    storePage.addEventListener('change', function (event) {

        const select = event.target.closest('#store-sort');

        if (!select) {
            return;
        }


        const form = select.closest('form');

        if (!form) {
            return;
        }


        const formData = new FormData(form);

        const params = new URLSearchParams();


        formData.forEach(function (value, key) {

            if (value !== '') {

                params.set(
                    key,
                    value
                );

            }

        });


        /*
        | Changing sort always starts books from page 1.
        */

        params.delete('page');


        const url = new URL(
            form.getAttribute('action') || window.location.href,
            window.location.origin
        );


        url.search = params.toString();

        url.hash = 'store-books';


        loadStoreContent(
            url.href,
            {
                hash: '#store-books'
            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | CATEGORY / FILTER / PAGINATION
    |--------------------------------------------------------------------------
    |
    | Event delegation is intentional.
    | The DOM gets replaced after every AJAX request, so we do NOT
    | attach listeners directly to individual links.
    |
    */

    storePage.addEventListener('click', function (event) {

        const link = event.target.closest('a');

        if (!link) {
            return;
        }


        const href = link.getAttribute('href');

        if (!href || href === '#') {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        const categoryLink =
            link.closest('.store-category-item');


        if (categoryLink) {

            event.preventDefault();


            const url = getCurrentUrl(href);


            /*
            | Category selection resets both book and review pages.
            */

            url.searchParams.delete('page');

            url.searchParams.delete('reviews_page');


            url.hash = 'store-books';


            loadStoreContent(
                url.href,
                {
                    hash: '#store-books'
                }
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH CLEAR
        |--------------------------------------------------------------------------
        */

        const searchClear =
            link.closest('.store-search-clear');


        if (searchClear) {

            event.preventDefault();


            const url = getCurrentUrl(href);

            url.searchParams.delete('page');

            url.searchParams.delete('reviews_page');

            url.hash = 'store-books';


            loadStoreContent(
                url.href,
                {
                    hash: '#store-books'
                }
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVE FILTER REMOVE
        |--------------------------------------------------------------------------
        */

        const filterChip =
            link.closest('.store-filter-chip');


        if (filterChip) {

            event.preventDefault();


            const url = getCurrentUrl(href);

            url.searchParams.delete('page');

            url.searchParams.delete('reviews_page');


            /*
            | Keep whichever section the filter belongs to.
            */

            url.hash = 'store-books';


            loadStoreContent(
                url.href,
                {
                    hash: '#store-books'
                }
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR ALL / RESET FILTERS
        |--------------------------------------------------------------------------
        */

        const clearFilters =
            link.closest('.store-clear-filters, .store-empty .store-btn');


        if (clearFilters) {

            event.preventDefault();


            const url = getCurrentUrl(href);

            url.searchParams.delete('page');

            url.searchParams.delete('reviews_page');

            url.hash = 'store-books';


            loadStoreContent(
                url.href,
                {
                    hash: '#store-books'
                }
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | BOOK PAGINATION
        |--------------------------------------------------------------------------
        */

        const pagination =
            link.closest('.store-pagination');


        if (pagination) {

            event.preventDefault();


            const url = getCurrentUrl(href);


            /*
            | Laravel pagination already generates the correct page
            | parameter. We simply fetch that URL.
            */

            let hash = url.hash;


            /*
            | If Laravel did not preserve the fragment, determine which
            | pagination belongs to which section.
            */

            if (!hash) {

                const reviewsSection =
                    pagination.closest('#store-reviews');


                if (reviewsSection) {

                    hash = '#store-reviews';

                } else {

                    hash = '#store-books';

                }

            }


            url.hash = hash.replace('#', '');


            loadStoreContent(
                url.href,
                {
                    hash: hash
                }
            );

            return;
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Browser Back / Forward
    |--------------------------------------------------------------------------
    |
    | Back and Forward now also work without a full page refresh.
    |
    */

    window.addEventListener('popstate', function () {

        loadStoreContent(
            window.location.href,
            {
                replaceState: true,
                hash: window.location.hash
            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Add to cart
    |--------------------------------------------------------------------------
    |
    | Prevent double submission.
    | This remains a normal POST request intentionally.
    |
    */

    const bindCartState = function () {

        storePage
            .querySelectorAll('.store-cart-form')
            .forEach(function (form) {

                if (form.dataset.cartBound === '1') {
                    return;
                }


                form.dataset.cartBound = '1';


                form.addEventListener('submit', function (event) {

                    const button =
                        form.querySelector('.store-cart-btn');


                    if (!button) {
                        return;
                    }


                    if (button.classList.contains('is-loading')) {

                        event.preventDefault();

                        return;

                    }


                    button.classList.add('is-loading');


                    const text =
                        button.querySelector('span');


                    if (text) {

                        text.textContent = 'Adding...';

                    }

                });

            });

    };


    /*
    | Initial cart bindings.
    */

    bindCartState();


    /*
    |--------------------------------------------------------------------------
    | Toast
    |--------------------------------------------------------------------------
    */

    const bindToast = function () {

        const toast =
            document.querySelector('.store-toast');


        if (!toast) {
            return;
        }


        const closeButton =
            toast.querySelector('button');


        const hide = function () {

            toast.classList.add('is-hiding');

        };


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                hide
            );

        }


        setTimeout(
            hide,
            4500
        );

    };


    bindToast();


    /*
    |--------------------------------------------------------------------------
    | pageshow
    |--------------------------------------------------------------------------
    |
    | We do NOT reload the page anymore.
    | Browser back/forward is handled through popstate.
    |
    */

    window.addEventListener('pageshow', function () {

        /*
        | Reset cart loading states when page is restored from bfcache.
        */

        document
            .querySelectorAll('.store-cart-btn.is-loading')
            .forEach(function (button) {

                button.classList.remove('is-loading');

                const text =
                    button.querySelector('span');


                if (text) {

                    text.textContent = 'Add to Cart';

                }

            });

    });

})();



</script>

@endsection