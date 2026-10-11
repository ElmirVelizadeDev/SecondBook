@extends('Layout.Frontend.master')

@section('title', $book->title . ' | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/book-details.css') }}">
@endpush

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Şəkil yolu köməkçisi (cover, mağaza loqosu, oxşar kitablar üçün)
    |--------------------------------------------------------------------------
    */

    $resolveImage = function (?string $path) {

        if (!$path) {
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


    /*
    |--------------------------------------------------------------------------
    | Cover
    |--------------------------------------------------------------------------
    */

    $coverUrl = $resolveImage($book->cover)
        ?: asset('frontend-assets/images/book-placeholder.jpg');


    /*
    |--------------------------------------------------------------------------
    | Book Data
    |--------------------------------------------------------------------------
    */

    $condition = $book->condition
        ? ucwords(str_replace('_', ' ', $book->condition))
        : 'Good';

    $language = $book->language ?: 'English';

    $stock = (int) ($book->stock ?? 0);

    $conditionClass = match (strtolower($book->condition ?? 'good')) {
        'new'      => 'bd-cond-new',
        'like_new' => 'bd-cond-like-new',
        'good'     => 'bd-cond-good',
        'fair'     => 'bd-cond-fair',
        default    => 'bd-cond-good',
    };


    /*
    |--------------------------------------------------------------------------
    | Pricing
    |--------------------------------------------------------------------------
    */

    $originalPrice   = (float) $book->price;
    $discountedPrice = (float) $book->discounted_price;
    $savedAmount     = max(0, $originalPrice - $discountedPrice);


    /*
    |--------------------------------------------------------------------------
    | Wishlist
    |--------------------------------------------------------------------------
    */

    $isWishlisted = false;

    if (auth()->check()) {

        $isWishlisted = auth()->user()
            ->wishlists()
            ->where('book_id', $book->id)
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | Hero helpers
    |--------------------------------------------------------------------------
    */

    $titleWords = preg_split('/\s+/', trim($book->title)) ?: [$book->title];

    $authorName   = $book->author->name ?? null;
    $categoryName = $book->category->name ?? null;

    $store = $book->seller?->store;

@endphp


<main class="book-details-page">


    {{-- =====================================================
         HERO / SHOWCASE
    ====================================================== --}}

    <section class="bd-hero">

        <div class="bd-hero-bg" aria-hidden="true">
            <i class="bd-orb bd-orb-1"></i>
            <i class="bd-orb bd-orb-2"></i>
            <i class="bd-ring bd-ring-1"></i>
            <i class="bd-ring bd-ring-2"></i>
            <i class="bd-sweep"></i>
        </div>

        <div class="container">

            {{-- BREADCRUMB + BACK --}}

            <div class="bd-topbar">

                <nav class="bd-breadcrumb" aria-label="Breadcrumb">

                    <a href="{{ route('frontend.home') }}">
                        Home
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <a href="{{ route('frontend.books') }}">
                        Books
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        {{ $book->title }}
                    </span>

                </nav>

                <a
                    href="{{ route('frontend.books') }}"
                    class="bd-back"
                >

                    <i class="bi bi-arrow-left"></i>

                    <span>
                        Back to Books
                    </span>

                </a>

            </div>


            <div class="bd-hero-grid">


                {{-- =============================================
                     LEFT — INFORMATION
                ============================================== --}}

                <div class="bd-hero-info">

                    {{-- BADGES --}}

                    <div class="bd-badges">

                        @if($categoryName)

                            <a
                                href="{{ route('frontend.books', ['search' => $categoryName]) }}"
                                class="bd-badge bd-badge-category"
                            >
                                <i class="bi bi-bookmark-star"></i>
                                {{ $categoryName }}
                            </a>

                        @endif

                        <span class="bd-badge bd-badge-condition {{ $conditionClass }}">
                            <span class="bd-dot"></span>
                            {{ $condition }}
                        </span>

                        <span class="bd-badge bd-badge-stock {{ $stock > 0 ? 'is-available' : 'is-unavailable' }}">
                            <span class="bd-dot"></span>
                            {{ $stock > 0 ? 'In stock' : 'Out of stock' }}
                        </span>

                    </div>


                    {{-- TITLE --}}

                    <h1 class="bd-title" aria-label="{{ $book->title }}">

                        @foreach($titleWords as $wordIndex => $word)
                            <span class="bd-word" style="--w: {{ $wordIndex }}"><span>{{ $word }}</span></span>
                        @endforeach

                    </h1>


                    {{-- AUTHOR --}}

                    <div class="bd-author">

                        <span class="bd-author-icon">
                            <i class="bi bi-person"></i>
                        </span>

                        <span class="bd-author-label">
                            Written by
                        </span>

                        @if($authorName)

                            <a
                                href="{{ route('frontend.books', ['search' => $authorName]) }}"
                                class="bd-author-link"
                            >
                                {{ $authorName }}
                            </a>

                        @else

                            <strong>
                                Unknown Author
                            </strong>

                        @endif

                    </div>


                    {{-- EXCERPT --}}

                    @if($book->description)

                        <p class="bd-excerpt">

                            {{ \Illuminate\Support\Str::limit($book->description, 230) }}

                            <a href="#bd-about" class="bd-excerpt-more">
                                Read more
                            </a>

                        </p>

                    @endif


                    {{-- KEY FEATURES --}}

                    <ul class="bd-chips">

                        <li>
                            <i class="bi bi-translate"></i>
                            {{ $language }}
                        </li>

                        @if($book->pages)

                            <li>
                                <i class="bi bi-file-earmark-text"></i>
                                {{ $book->pages }} pages
                            </li>

                        @endif

                        @if($book->publication_year)

                            <li>
                                <i class="bi bi-calendar3"></i>
                                {{ $book->publication_year }}
                            </li>

                        @endif

                    </ul>


                    {{-- =========================================
                         PURCHASE PANEL
                    ========================================== --}}

                    <div class="bd-purchase" id="bd-purchase">

                        <div class="bd-purchase-top">

                            <div class="bd-price-block">

                                <span class="bd-price-label">
                                    Current price
                                </span>

                                @if($book->is_discount_active)

                                    <div class="bd-price-row">

                                        <strong class="bd-price bd-price-discounted">
                                            ${{ number_format($discountedPrice, 2) }}
                                        </strong>

                                        <span class="bd-old-price">
                                            ${{ number_format($originalPrice, 2) }}
                                        </span>

                                    </div>

                                    <div class="bd-price-tags">

                                        <span class="bd-discount-pill">
                                            {{ $book->discount_label }}
                                        </span>

                                        @if($savedAmount > 0)

                                            <span class="bd-save">
                                                You save ${{ number_format($savedAmount, 2) }}
                                            </span>

                                        @endif

                                    </div>

                                @else

                                    <strong class="bd-price">
                                        ${{ number_format($originalPrice, 2) }}
                                    </strong>

                                @endif

                            </div>


                            <div class="bd-stock {{ $stock > 0 ? 'is-available' : 'is-unavailable' }}">

                                <span class="bd-stock-icon">

                                    @if($stock > 0)
                                        <i class="bi bi-check2"></i>
                                    @else
                                        <i class="bi bi-x"></i>
                                    @endif

                                </span>

                                <div>

                                    <strong>
                                        {{ $stock > 0 ? 'In stock' : 'Out of stock' }}
                                    </strong>

                                    @if($stock > 0)

                                        <span>
                                            {{ $stock }}
                                            {{ $stock === 1 ? 'copy' : 'copies' }}
                                            available
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- ACTIONS --}}

                        <div class="bd-actions">


                            {{-- CART --}}

                            @if($stock > 0)

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="bd-cart-form"
                                    id="bd-cart-form"
                                    data-book-cart
                                >

                                    @csrf

                                    <div class="bd-qty" data-qty>

                                        <button
                                            type="button"
                                            class="bd-qty-btn"
                                            data-qty-minus
                                            aria-label="Decrease quantity"
                                        >
                                            <i class="bi bi-dash"></i>
                                        </button>

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="{{ $stock }}"
                                            inputmode="numeric"
                                            class="bd-qty-input"
                                            data-qty-input
                                            aria-label="Quantity"
                                        >

                                        <button
                                            type="button"
                                            class="bd-qty-btn"
                                            data-qty-plus
                                            aria-label="Increase quantity"
                                        >
                                            <i class="bi bi-plus"></i>
                                        </button>

                                    </div>


                                    <button
                                        type="submit"
                                        class="bd-btn bd-btn-cart"
                                        data-cart-button
                                    >

                                        <i class="bi bi-bag-plus"></i>

                                        <span>
                                            Add to Cart
                                        </span>

                                    </button>

                                </form>

                            @else

                                <div class="bd-cart-form">

                                    <button
                                        type="button"
                                        class="bd-btn bd-btn-cart is-disabled"
                                        disabled
                                    >

                                        <i class="bi bi-bag-x"></i>

                                        <span>
                                            Out of Stock
                                        </span>

                                    </button>

                                </div>

                            @endif


                            {{-- WISHLIST --}}

                            @auth

                                <form
                                    action="{{ $isWishlisted
                                        ? route('frontend.wishlist.remove', $book->id)
                                        : route('frontend.wishlist.add', $book->id)
                                    }}"
                                    method="POST"
                                    class="bd-wishlist-form"
                                    data-book-wishlist
                                    data-action="{{ $isWishlisted ? 'remove' : 'add' }}"
                                    data-add-url="{{ route('frontend.wishlist.add', $book->id) }}"
                                    data-remove-url="{{ route('frontend.wishlist.remove', $book->id) }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="_method"
                                        value="{{ $isWishlisted ? 'DELETE' : 'POST' }}"
                                    >

                                    <button
                                        type="submit"
                                        class="bd-btn bd-btn-wishlist {{ $isWishlisted ? 'active' : '' }}"
                                        data-wishlist-button
                                        title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}"
                                    >

                                        <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>

                                        <span>
                                            {{ $isWishlisted ? 'Saved' : 'Wishlist' }}
                                        </span>

                                    </button>

                                </form>

                            @else

                                <a
                                    href="{{ route('frontend.auth.login') }}"
                                    class="bd-btn bd-btn-wishlist"
                                >

                                    <i class="bi bi-heart"></i>

                                    <span>
                                        Wishlist
                                    </span>

                                </a>

                            @endauth

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     RIGHT — 3D BOOK SHOWCASE
                ============================================== --}}

                <div class="bd-hero-visual">

                    <div class="bd-stage" data-book-stage>

                        <div class="bd-halo" aria-hidden="true"></div>
                        <div class="bd-stage-ring" aria-hidden="true"></div>

                        <div class="bd-book-float">

                            <div class="bd-book" data-book-3d>

                                <div class="bd-book-front">

                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="{{ $book->title }}"
                                        class="bd-cover-img"
                                        data-cover-img
                                    >

                                    <div class="bd-book-fallback" aria-hidden="true">
                                        <i class="bi bi-book"></i>
                                        <strong>{{ $book->title }}</strong>
                                        <span>SecondBook</span>
                                    </div>

                                    @if($book->is_discount_active)

                                        <span class="bd-cover-discount">
                                            {{ $book->discount_label }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="bd-book-shadow" aria-hidden="true"></div>


                        {{-- FLOATING INFO CARDS --}}

                        <div class="bd-float-card bd-float-card-a">

                            <span class="bd-float-icon">
                                <i class="bi bi-stars"></i>
                            </span>

                            <div>
                                <span>Condition</span>
                                <strong>{{ $condition }}</strong>
                            </div>

                        </div>

                        <div class="bd-float-card bd-float-card-b">

                            <span class="bd-float-icon">
                                <i class="bi bi-shield-check"></i>
                            </span>

                            <div>
                                <span>SecondBook</span>
                                <strong>Quality checked</strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         FACTS STRIP (hero ilə kəsişən kart)
    ====================================================== --}}

    <section class="bd-facts-section">

        <div class="container">

            <div class="bd-facts">

                <div class="bd-fact">

                    <span class="bd-fact-icon">
                        <i class="bi bi-stars"></i>
                    </span>

                    <div>
                        <span>Condition</span>
                        <strong>{{ $condition }}</strong>
                    </div>

                </div>

                <div class="bd-fact">

                    <span class="bd-fact-icon">
                        <i class="bi bi-translate"></i>
                    </span>

                    <div>
                        <span>Language</span>
                        <strong>{{ $language }}</strong>
                    </div>

                </div>

                <div class="bd-fact">

                    <span class="bd-fact-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>

                    <div>
                        <span>Pages</span>
                        <strong>{{ $book->pages ?: 'Not specified' }}</strong>
                    </div>

                </div>

                <div class="bd-fact">

                    <span class="bd-fact-icon">
                        <i class="bi bi-calendar3"></i>
                    </span>

                    <div>
                        <span>Published</span>
                        <strong>{{ $book->publication_year ?: 'Not specified' }}</strong>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         ABOUT THIS BOOK
    ====================================================== --}}

    @if($book->description)

        <section class="bd-about" id="bd-about">

            <div class="container">

                <div class="bd-about-grid">

                    <div class="bd-about-head">

                        <span class="bd-label">
                            THE STORY
                        </span>

                        <h2>
                            About this
                            <em>book.</em>
                        </h2>

                        <div class="bd-about-mark" aria-hidden="true">
                            ”
                        </div>

                    </div>

                    <div class="bd-about-text">

                        {{ $book->description }}

                    </div>

                </div>

            </div>

        </section>

    @endif


    {{-- =====================================================
         BOOK SPECIFICATIONS
    ====================================================== --}}

    <section class="bd-specs" id="bd-specs">

        <div class="container">

            <div class="bd-section-head">

                <div>

                    <span class="bd-label">
                        THE DETAILS
                    </span>

                    <h2>
                        Book
                        <em>specifications.</em>
                    </h2>

                </div>

                <p>
                    Everything you need to know about this copy,
                    listed clearly before you decide.
                </p>

            </div>


            <div class="bd-ledger">

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-person"></i> Author</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $authorName ?? 'Unknown Author' }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-upc-scan"></i> ISBN</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $book->isbn ?: 'Not specified' }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-building"></i> Publisher</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $book->publisher->name ?? 'Not specified' }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-bookmark-star"></i> Category</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $categoryName ?? 'Uncategorized' }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-calendar3"></i> Published</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $book->publication_year ?: 'Not specified' }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-file-earmark-text"></i> Pages</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $book->pages ?: 'Not specified' }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-translate"></i> Language</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $language }}</strong>
                </div>

                <div class="bd-spec">
                    <span class="bd-spec-label"><i class="bi bi-stars"></i> Condition</span>
                    <span class="bd-spec-dots"></span>
                    <strong class="bd-spec-value">{{ $condition }}</strong>
                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         SELLER / STORE
    ====================================================== --}}

    @if($store && $store->isActive())

        @php
            $storeLogoUrl = $resolveImage($store->logo);
        @endphp

        <section class="bd-seller-section" id="bd-seller">

            <div class="container">

                <div class="bd-seller">

                    <div class="bd-seller-left">

                        <div class="bd-seller-avatar">

                            @if($storeLogoUrl)

                                <img
                                    src="{{ $storeLogoUrl }}"
                                    alt="{{ $store->name }}"
                                >

                            @else

                                <i class="bi bi-shop"></i>

                            @endif

                        </div>

                        <div class="bd-seller-info">

                            <span class="bd-seller-eyebrow">
                                Sold by
                            </span>

                            <strong>
                                {{ $store->name }}
                            </strong>

                            <div class="bd-seller-meta">

                                <span class="bd-verified">
                                    <i class="bi bi-patch-check-fill"></i>
                                    Verified Store
                                </span>

                                @if(!empty($store->address))

                                    <span class="bd-seller-dot"></span>

                                    <span>
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $store->address }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                    <a
                        href="{{ route('store.show', $store->slug) }}"
                        class="bd-btn bd-btn-store"
                    >

                        <span>View Store</span>

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </section>

    @elseif($book->seller)

        <section class="bd-seller-section" id="bd-seller">

            <div class="container">

                <div class="bd-seller">

                    <div class="bd-seller-left">

                        <div class="bd-seller-avatar">
                            <i class="bi bi-person"></i>
                        </div>

                        <div class="bd-seller-info">

                            <span class="bd-seller-eyebrow">
                                Sold by
                            </span>

                            <strong>
                                {{ $book->seller->name }}
                            </strong>

                        </div>

                    </div>

                    <span class="bd-verified bd-verified-pill">
                        <i class="bi bi-patch-check-fill"></i>
                        Verified Seller
                    </span>

                </div>

            </div>

        </section>

    @endif


    {{-- =====================================================
         RELATED BOOKS
         Yalnız controller `$relatedBooks` ötürəndə göstərilir.
    ====================================================== --}}

    @if(isset($relatedBooks) && $relatedBooks->count())

        <section class="bd-related" id="bd-related">

            <div class="container">

                <div class="bd-section-head">

                    <div>

                        <span class="bd-label">
                            KEEP READING
                        </span>

                        <h2>
                            You may also
                            <em>like.</em>
                        </h2>

                    </div>

                    <a href="{{ route('frontend.books') }}" class="bd-link-all">
                        <span>Browse all books</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>


                <div class="bd-related-grid">

                    @foreach($relatedBooks as $related)

                        @php
                            $relatedCover = $resolveImage($related->cover);
                        @endphp

                        <a
                            href="{{ route('frontend.books.show', $related->id) }}"
                            class="bd-rel-card"
                            style="--i: {{ $loop->index }}"
                        >

                            <div class="bd-rel-cover">

                                @if($relatedCover)

                                    <img
                                        src="{{ $relatedCover }}"
                                        alt="{{ $related->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="bd-rel-placeholder">
                                        <i class="bi bi-book"></i>
                                    </div>

                                @endif

                                @if($related->is_discount_active)

                                    <span class="bd-rel-discount">
                                        {{ $related->discount_label }}
                                    </span>

                                @endif

                            </div>

                            <div class="bd-rel-body">

                                <h3>{{ $related->title }}</h3>

                                <p>{{ $related->author->name ?? 'Unknown Author' }}</p>

                                <div class="bd-rel-bottom">

                                    @if($related->is_discount_active)

                                        <strong class="is-sale">
                                            ${{ number_format((float) $related->discounted_price, 2) }}
                                        </strong>

                                        <s>${{ number_format((float) $related->price, 2) }}</s>

                                    @else

                                        <strong>
                                            ${{ number_format((float) $related->price, 2) }}
                                        </strong>

                                    @endif

                                    <span class="bd-rel-arrow">
                                        <i class="bi bi-arrow-up-right"></i>
                                    </span>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        </section>

    @endif

</main>


{{-- =========================================================
     STICKY BUY BAR (hero-dakı alış paneli görünməz olanda çıxır)
========================================================== --}}

@if($stock > 0)

    <div class="bd-sticky" id="bd-sticky" aria-hidden="true">

        <div class="bd-sticky-inner">

            <div class="bd-sticky-book">

                <img
                    src="{{ $coverUrl }}"
                    alt=""
                    data-sticky-cover
                >

                <div>

                    <strong>{{ $book->title }}</strong>

                    <span>
                        @if($book->is_discount_active)
                            ${{ number_format($discountedPrice, 2) }}
                        @else
                            ${{ number_format($originalPrice, 2) }}
                        @endif
                    </span>

                </div>

            </div>

            <button
                type="submit"
                form="bd-cart-form"
                class="bd-btn bd-btn-cart bd-btn-sticky"
                data-cart-button-sticky
                tabindex="-1"
            >

                <i class="bi bi-bag-plus"></i>

                <span>
                    Add to Cart
                </span>

            </button>

        </div>

    </div>

@endif


{{-- =========================================================
     SCRIPTS
========================================================== --}}

@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;


    /* =========================================================
       COVER FALLBACK (şəkil yüklənməsə premium placeholder)
    ========================================================= */

    function applyCoverFallback(img) {

        const book = img.closest('.bd-book');

        if (book) {
            book.classList.add('is-fallback');
        }

        img.remove();

    }

    document.querySelectorAll('[data-cover-img]').forEach(function (img) {

        if (img.complete && img.naturalWidth === 0) {

            applyCoverFallback(img);

        } else {

            img.addEventListener('error', function () {
                applyCoverFallback(img);
            }, { once: true });

        }

    });

    document.querySelectorAll('[data-sticky-cover]').forEach(function (img) {

        img.addEventListener('error', function () {
            img.style.visibility = 'hidden';
        }, { once: true });

    });


    /* =========================================================
       3D BOOK — yüngül maus reaksiyası
    ========================================================= */

    const stage = document.querySelector('[data-book-stage]');
    const book3d = document.querySelector('[data-book-3d]');

    if (stage && book3d && finePointer && !reduceMotion) {

        stage.addEventListener('mousemove', function (event) {

            const rect = stage.getBoundingClientRect();

            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;

            book3d.style.setProperty('--ry', (22 + x * 18).toFixed(2) + 'deg');
            book3d.style.setProperty('--rx', (4 - y * 10).toFixed(2) + 'deg');

        });

        stage.addEventListener('mouseleave', function () {

            book3d.style.removeProperty('--ry');
            book3d.style.removeProperty('--rx');

        });

    }


    /* =========================================================
       QUANTITY SELECTOR
    ========================================================= */

    const qtyWrap = document.querySelector('[data-qty]');

    if (qtyWrap) {

        const input = qtyWrap.querySelector('[data-qty-input]');
        const minus = qtyWrap.querySelector('[data-qty-minus]');
        const plus = qtyWrap.querySelector('[data-qty-plus]');

        const min = Number(input.min) || 1;
        const max = Number(input.max) || 1;

        function clampQty() {

            let value = parseInt(input.value, 10);

            if (isNaN(value) || value < min) {
                value = min;
            }

            if (value > max) {
                value = max;
            }

            input.value = value;

            minus.disabled = value <= min;
            plus.disabled = value >= max;

        }

        minus.addEventListener('click', function () {
            input.value = (parseInt(input.value, 10) || min) - 1;
            clampQty();
        });

        plus.addEventListener('click', function () {
            input.value = (parseInt(input.value, 10) || min) + 1;
            clampQty();
        });

        input.addEventListener('change', clampQty);

        clampQty();

    }


    /* =========================================================
       TOAST
    ========================================================= */

    function escapeHtml(value) {

        const div = document.createElement('div');

        div.textContent = value;

        return div.innerHTML;

    }

    function showToast(message, type) {

        const existing = document.querySelector('.bd-toast');

        if (existing) {
            existing.remove();
        }

        const isError = type === 'error';

        const toast = document.createElement('div');

        toast.className = 'bd-toast ' + (isError ? 'is-error' : 'is-success');

        toast.innerHTML = `
            <span class="bd-toast-icon">
                <i class="bi ${isError ? 'bi-exclamation-lg' : 'bi-check-lg'}"></i>
            </span>
            <div>
                <strong>${isError ? 'Something went wrong' : 'Success'}</strong>
                <span>${escapeHtml(message)}</span>
            </div>
        `;

        document.body.appendChild(toast);

        requestAnimationFrame(function () {
            toast.classList.add('is-visible');
        });

        setTimeout(function () {

            toast.classList.remove('is-visible');

            setTimeout(function () {
                toast.remove();
            }, 400);

        }, 2800);

    }


    /* =========================================================
       UPDATE HEADER CART COUNT
    ========================================================= */

    function updateHeaderCartCount(count) {

        const cartCount = document.getElementById('header-cart-count');

        if (!cartCount) {
            return;
        }

        const numericCount = Number(count) || 0;

        if (numericCount > 0) {

            cartCount.textContent = numericCount > 99 ? '99+' : numericCount;
            cartCount.classList.add('is-visible');

        } else {

            cartCount.textContent = '0';
            cartCount.classList.remove('is-visible');

        }

    }


    /* =========================================================
       CART FORM
    ========================================================= */

    const cartForm = document.querySelector('[data-book-cart]');

    if (cartForm) {

        cartForm.addEventListener('submit', async function (event) {

            event.preventDefault();
            event.stopPropagation();

            const button = cartForm.querySelector('[data-cart-button]');
            const stickyButton = document.querySelector('[data-cart-button-sticky]');
            const token = cartForm.querySelector('input[name="_token"]');

            if (!button || !token || button.disabled) {
                return;
            }

            const icon = button.querySelector('i');
            const text = button.querySelector('span');

            const originalIcon = icon ? icon.className : '';
            const originalText = text ? text.textContent : 'Add to Cart';

            button.disabled = true;
            button.classList.add('loading');

            if (stickyButton) {
                stickyButton.disabled = true;
                stickyButton.classList.add('loading');
            }

            try {

                const response = await fetch(cartForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token.value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(cartForm),
                    credentials: 'same-origin'
                });

                const contentType = response.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {
                    throw new Error('The server did not return JSON.');
                }

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to add the book to cart.');
                }

                updateHeaderCartCount(data.cart_count);

                if (icon) {
                    icon.className = 'bi bi-check2';
                }

                if (text) {
                    text.textContent = 'Added to Cart';
                }

                showToast('Book added to cart successfully!', 'success');

            } catch (error) {

                console.error('SecondBook Cart Error:', error);

                showToast(error.message || 'Unable to add the book to cart.', 'error');

            } finally {

                setTimeout(function () {

                    if (icon) {
                        icon.className = originalIcon;
                    }

                    if (text) {
                        text.textContent = originalText;
                    }

                    button.disabled = false;
                    button.classList.remove('loading');

                    if (stickyButton) {
                        stickyButton.disabled = false;
                        stickyButton.classList.remove('loading');
                    }

                }, 1200);

            }

        });

    }


    /* =========================================================
       STICKY BUY BAR
    ========================================================= */

    const purchase = document.getElementById('bd-purchase');
    const sticky = document.getElementById('bd-sticky');

    if (purchase && sticky && 'IntersectionObserver' in window) {

        const observer = new IntersectionObserver(function (entries) {

            entries.forEach(function (entry) {

                const show = !entry.isIntersecting && entry.boundingClientRect.top < 0;

                sticky.classList.toggle('is-visible', show);
                sticky.setAttribute('aria-hidden', show ? 'false' : 'true');

                const stickyBtn = sticky.querySelector('button');

                if (stickyBtn) {
                    stickyBtn.tabIndex = show ? 0 : -1;
                }

            });

        }, { threshold: 0 });

        observer.observe(purchase);

    }


    /* =========================================================
       WISHLIST AJAX
    ========================================================= */

    if (!window.secondBookWishlistInitialized) {

        window.secondBookWishlistInitialized = true;

        document.addEventListener('submit', async function (event) {

            const form = event.target.closest('[data-book-wishlist]');

            if (!form) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            const button = form.querySelector('[data-wishlist-button]');
            const icon = button ? button.querySelector('i') : null;
            const text = button ? button.querySelector('span') : null;
            const token = form.querySelector('input[name="_token"]');

            if (!button || !icon || !text || !token || button.disabled) {
                return;
            }

            const action = form.dataset.action;

            const url = action === 'remove'
                ? form.dataset.removeUrl
                : form.dataset.addUrl;

            const method = action === 'remove' ? 'DELETE' : 'POST';

            if (!url) {
                return;
            }

            button.disabled = true;
            button.classList.add('loading');

            try {

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': token.value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const contentType = response.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {
                    throw new Error('The server did not return JSON.');
                }

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to update wishlist.');
                }

                const methodInput = form.querySelector('input[name="_method"]');

                if (action === 'remove') {

                    form.dataset.action = 'add';
                    form.action = form.dataset.addUrl;

                    if (methodInput) {
                        methodInput.value = 'POST';
                    }

                    button.classList.remove('active');

                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');

                    text.textContent = 'Wishlist';
                    button.title = 'Add to wishlist';

                } else {

                    form.dataset.action = 'remove';
                    form.action = form.dataset.removeUrl;

                    if (methodInput) {
                        methodInput.value = 'DELETE';
                    }

                    button.classList.add('active');

                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');

                    text.textContent = 'Saved';
                    button.title = 'Remove from wishlist';

                }

            } catch (error) {

                console.error('SecondBook Wishlist Error:', error);

                showToast(error.message || 'Unable to update wishlist.', 'error');

            } finally {

                button.disabled = false;
                button.classList.remove('loading');

            }

        }, true);

    }

});

</script>

@endpush

@endsection