@extends('Layout.Frontend.master')

@section('title', $book->title . ' | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/book-details.css') }}">
@endpush

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Cover
    |--------------------------------------------------------------------------
    */

    $cover = $book->cover;

    if ($cover) {

        if (
            str_starts_with($cover, 'http://') ||
            str_starts_with($cover, 'https://')
        ) {

            $coverUrl = $cover;

        } elseif (
            str_starts_with($cover, 'storage/') ||
            str_starts_with($cover, 'uploads/')
        ) {

            $coverUrl = asset($cover);

        } else {

            $coverUrl = asset(
                'storage/' . ltrim($cover, '/')
            );
        }

    } else {

        $coverUrl = asset(
            'frontend-assets/images/book-placeholder.jpg'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Book Data
    |--------------------------------------------------------------------------
    */

    $condition = $book->condition
        ? ucwords(
            str_replace(
                '_',
                ' ',
                $book->condition
            )
        )
        : 'Good';

    $language = $book->language ?: 'English';

    $stock = (int) ($book->stock ?? 0);

    $conditionClass = match (
        strtolower($book->condition ?? 'good')
    ) {

        'new' => 'condition-new',

        'like_new' => 'condition-like-new',

        'good' => 'condition-good',

        'fair' => 'condition-fair',

        default => 'condition-good',

    };


    /*
    |--------------------------------------------------------------------------
    | Pricing
    |--------------------------------------------------------------------------
    */

    $originalPrice = (float) $book->price;

    $discountedPrice = (float) $book->discounted_price;


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

@endphp


<main class="book-details-page">


    {{-- =====================================================
         HERO / BREADCRUMB
    ====================================================== --}}

    <section class="book-details-hero">

        <div class="container">

            <div class="book-details-breadcrumb">

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

            </div>

        </div>

    </section>


    {{-- =====================================================
         BACK TO BOOKS
    ====================================================== --}}

    <div class="book-details-back">

        <div class="container">

            <a
                href="{{ route('frontend.books') }}"
                class="book-back-button"
            >

                <i class="bi bi-arrow-left"></i>

                <span>
                    Back to Books
                </span>

            </a>

        </div>

    </div>


    {{-- =====================================================
         MAIN BOOK SECTION
    ====================================================== --}}

    <section class="book-details-section">

        <div class="container">

            <div class="book-details-layout">


                {{-- =================================================
                     LEFT — BOOK COVER
                ================================================== --}}

                <div class="book-cover-column">

                    <div class="book-cover-frame">

                        <div class="book-cover-glow"></div>

                        <div class="book-cover-wrapper">

                            <img
                                src="{{ $coverUrl }}"
                                alt="{{ $book->title }}"
                                class="book-cover-image"
                            >


                            {{-- DISCOUNT BADGE --}}

                            @if($book->is_discount_active)

                                <span class="book-details-discount">

                                    {{ $book->discount_label }}

                                </span>

                            @endif


                            {{-- CONDITION --}}

                            <div
                                class="book-cover-condition {{ $conditionClass }}"
                            >

                                <span class="condition-dot"></span>

                                {{ $condition }}

                            </div>

                        </div>

                    </div>


                    <div class="book-cover-caption">

                        <i class="bi bi-shield-check"></i>

                        <span>
                            Quality checked by SecondBook
                        </span>

                    </div>

                </div>


                {{-- =================================================
                     RIGHT — BOOK INFORMATION
                ================================================== --}}

                <div class="book-info-column">


                    {{-- CATEGORY --}}

                    <div class="book-category-label">

                        <span class="category-line"></span>

                        <span>
                            {{ $book->category->name ?? 'Uncategorized' }}
                        </span>

                    </div>


                    {{-- TITLE --}}

                    <h1 class="book-title">

                        {{ $book->title }}

                    </h1>


                    {{-- AUTHOR --}}

                    <div class="book-author">

                        <span class="author-icon">

                            <i class="bi bi-person"></i>

                        </span>

                        <span class="author-label">
                            Written by
                        </span>

                        <strong>
                            {{ $book->author->name ?? 'Unknown Author' }}
                        </strong>

                    </div>


                    {{-- DESCRIPTION --}}

                    @if($book->description)

                        <div class="book-description">

                            <div class="description-heading">

                                <span>
                                    About this book
                                </span>

                            </div>

                            <p>
                                {{ $book->description }}
                            </p>

                        </div>

                    @endif


                    {{-- =================================================
                         PURCHASE PANEL
                    ================================================== --}}

                    <div class="book-purchase-panel">


                        <div class="purchase-top">


                            {{-- PRICE --}}

                            <div class="price-block">

                                <span class="price-label">
                                    Current price
                                </span>


                                @if($book->is_discount_active)

                                    <div class="book-details-price-wrap">

                                        <span class="book-details-old-price">
                                            ${{ number_format($originalPrice, 2) }}
                                        </span>

                                        <strong class="book-price book-details-discounted-price">
                                            ${{ number_format($discountedPrice, 2) }}
                                        </strong>

                                    </div>

                                    <span class="book-details-discount-label">
                                        {{ $book->discount_label }}
                                    </span>

                                @else

                                    <strong class="book-price">
                                        ${{ number_format($originalPrice, 2) }}
                                    </strong>

                                @endif

                            </div>


                            {{-- STOCK --}}

                            <div
                                class="stock-status {{ $stock > 0 ? 'stock-available' : 'stock-unavailable' }}"
                            >

                                <span class="stock-icon">

                                    @if($stock > 0)

                                        <i class="bi bi-check2"></i>

                                    @else

                                        <i class="bi bi-x"></i>

                                    @endif

                                </span>


                                <div>

                                    <strong>

                                        {{ $stock > 0
                                            ? 'In stock'
                                            : 'Out of stock'
                                        }}

                                    </strong>


                                    @if($stock > 0)

                                        <span>

                                            {{ $stock }}

                                            {{ $stock === 1
                                                ? 'copy'
                                                : 'copies'
                                            }}

                                            available

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="book-actions">


                            {{-- =================================================
                                 WISHLIST
                            ================================================== --}}

                            @auth

                                <form
                                    action="{{ $isWishlisted
                                        ? route('frontend.wishlist.remove', $book->id)
                                        : route('frontend.wishlist.add', $book->id)
                                    }}"
                                    method="POST"
                                    class="book-details-wishlist-form"
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
                                        class="book-wishlist-button {{ $isWishlisted ? 'active' : '' }}"
                                        data-wishlist-button
                                        title="{{ $isWishlisted
                                            ? 'Remove from wishlist'
                                            : 'Add to wishlist'
                                        }}"
                                    >

                                        <i
                                            class="bi {{ $isWishlisted
                                                ? 'bi-heart-fill'
                                                : 'bi-heart'
                                            }}"
                                        ></i>

                                        <span>
                                            {{ $isWishlisted
                                                ? 'Saved'
                                                : 'Wishlist'
                                            }}
                                        </span>

                                    </button>

                                </form>


                            @else

                                <a
                                    href="{{ route('frontend.auth.login') }}"
                                    class="book-wishlist-button"
                                >

                                    <i class="bi bi-heart"></i>

                                    <span>
                                        Wishlist
                                    </span>

                                </a>

                            @endauth


                            {{-- =================================================
                                 CART
                            ================================================== --}}

                            @if($stock > 0)

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="book-cart-form"
                                    data-book-cart
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >


                                    <button
                                        type="submit"
                                        class="book-cart-button"
                                        data-cart-button
                                    >

                                        <i class="bi bi-bag-plus"></i>

                                        <span>
                                            Add to Cart
                                        </span>

                                    </button>

                                </form>


                            @else

                                <button
                                    type="button"
                                    class="book-cart-button disabled"
                                    disabled
                                >

                                    <i class="bi bi-bag-x"></i>

                                    <span>
                                        Out of Stock
                                    </span>

                                </button>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                         QUICK BOOK INFO
                    ================================================== --}}

                    <div class="book-quick-info">


                        {{-- ISBN --}}

                        <div class="quick-info-item">

                            <span class="quick-info-icon">

                                <i class="bi bi-upc-scan"></i>

                            </span>

                            <div>

                                <span>
                                    ISBN
                                </span>

                                <strong>
                                    {{ $book->isbn ?: 'Not specified' }}
                                </strong>

                            </div>

                        </div>


                        {{-- Publisher --}}

                        <div class="quick-info-item">

                            <span class="quick-info-icon">

                                <i class="bi bi-building"></i>

                            </span>

                            <div>

                                <span>
                                    Publisher
                                </span>

                                <strong>
                                    {{ $book->publisher->name ?? 'Not specified' }}
                                </strong>

                            </div>

                        </div>


                        {{-- Published --}}

                        <div class="quick-info-item">

                            <span class="quick-info-icon">

                                <i class="bi bi-calendar3"></i>

                            </span>

                            <div>

                                <span>
                                    Published
                                </span>

                                <strong>
                                    {{ $book->publication_year ?: 'Not specified' }}
                                </strong>

                            </div>

                        </div>


                        {{-- Pages --}}

                        <div class="quick-info-item">

                            <span class="quick-info-icon">

                                <i class="bi bi-file-earmark-text"></i>

                            </span>

                            <div>

                                <span>
                                    Pages
                                </span>

                                <strong>
                                    {{ $book->pages ?: 'Not specified' }}
                                </strong>

                            </div>

                        </div>


                        {{-- Language --}}

                        <div class="quick-info-item">

                            <span class="quick-info-icon">

                                <i class="bi bi-translate"></i>

                            </span>

                            <div>

                                <span>
                                    Language
                                </span>

                                <strong>
                                    {{ $language }}
                                </strong>

                            </div>

                        </div>


                        {{-- Condition --}}

                        <div class="quick-info-item">

                            <span class="quick-info-icon">

                                <i class="bi bi-stars"></i>

                            </span>

                            <div>

                                <span>
                                    Condition
                                </span>

                                <strong>
                                    {{ $condition }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                STORE CARD
            ====================================================== --}}
            @php
                $store = $book->seller?->store;
            @endphp

            @if($store && $store->isActive())

                @php
                    $storeLogo = $store->logo;

                    if ($storeLogo) {
                        if (
                            str_starts_with($storeLogo, 'http://') ||
                            str_starts_with($storeLogo, 'https://')
                        ) {
                            $storeLogoUrl = $storeLogo;
                        } elseif (
                            str_starts_with($storeLogo, 'storage/') ||
                            str_starts_with($storeLogo, 'uploads/')
                        ) {
                            $storeLogoUrl = asset($storeLogo);
                        } else {
                            $storeLogoUrl = asset(
                                'storage/' . ltrim($storeLogo, '/')
                            );
                        }
                    } else {
                        $storeLogoUrl = null;
                    }
                @endphp

                <div class="seller-card">

                    <div class="seller-left">

                        <div class="seller-avatar">
                            @if($storeLogoUrl)
                                <img
                                    src="{{ $storeLogoUrl }}"
                                    alt="{{ $store->name }}"
                                >
                            @else
                                <i class="bi bi-shop"></i>
                            @endif
                        </div>

                        <div class="seller-info">
                            <span>Sold by</span>

                            <strong>
                                {{ $store->name }}
                            </strong>

                            <div class="seller-meta">
                                <span>
                                    <i class="bi bi-patch-check-fill"></i>
                                    Verified Store
                                </span>

                                @if(!empty($store->address))
                                    <span class="seller-meta-separator">·</span>

                                    <span>
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $store->address }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="seller-right">

                        <a
                            href="{{ route('store.show', $store->slug) }}"
                            class="seller-store-button"
                        >
                            <span>View Store</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            @elseif($book->seller)

                <div class="seller-card">

                    <div class="seller-left">

                        <div class="seller-avatar">
                            <i class="bi bi-person"></i>
                        </div>

                        <div class="seller-info">
                            <span>Sold by</span>

                            <strong>
                                {{ $book->seller->name }}
                            </strong>
                        </div>

                    </div>

                    <div class="seller-verified">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Verified Seller</span>
                    </div>

                </div>

            @endif

        </div>

    </section>

</main>


{{-- =========================================================
    CART AJAX
========================================================= --}}

@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       CART FORM
    ========================================================= */

    const cartForm = document.querySelector(
        '[data-book-cart]'
    );


    if (cartForm) {

        cartForm.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();
                event.stopPropagation();


                const button =
                    cartForm.querySelector(
                        '[data-cart-button]'
                    );


                const token =
                    cartForm.querySelector(
                        'input[name="_token"]'
                    );


                if (
                    !button ||
                    !token ||
                    button.disabled
                ) {

                    return;

                }


                const icon =
                    button.querySelector('i');


                const text =
                    button.querySelector('span');


                const originalIcon =
                    icon
                        ? icon.className
                        : '';


                const originalText =
                    text
                        ? text.textContent
                        : 'Add to Cart';


                button.disabled = true;

                button.classList.add(
                    'loading'
                );


                try {

                    const response =
                        await fetch(
                            cartForm.action,
                            {
                                method: 'POST',

                                headers: {

                                    'X-CSRF-TOKEN':
                                        token.value,

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'

                                },

                                body:
                                    new FormData(
                                        cartForm
                                    ),

                                credentials:
                                    'same-origin'
                            }
                        );


                    const contentType =
                        response.headers.get(
                            'content-type'
                        ) || '';


                    if (
                        !contentType.includes(
                            'application/json'
                        )
                    ) {

                        throw new Error(
                            'The server did not return JSON.'
                        );

                    }


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        throw new Error(
                            data.message ||
                            'Unable to add the book to cart.'
                        );

                    }


                    /* =================================================
                       UPDATE HEADER CART COUNT
                    ================================================== */

                    updateHeaderCartCount(
                        data.cart_count
                    );


                    /* =================================================
                       SUCCESS STATE
                    ================================================== */

                    if (icon) {

                        icon.className =
                            'bi bi-check2';

                    }


                    if (text) {

                        text.textContent =
                            'Added to Cart';

                    }


                    /* =================================================
                       RESET BUTTON
                    ================================================== */

                    setTimeout(
                        function () {

                            if (icon) {

                                icon.className =
                                    originalIcon;

                            }


                            if (text) {

                                text.textContent =
                                    originalText;

                            }


                            button.disabled =
                                false;

                            button.classList.remove(
                                'loading'
                            );

                        },
                        1200
                    );


                } catch (error) {

                    console.error(
                        'SecondBook Cart Error:',
                        error
                    );


                    button.disabled =
                        false;

                    button.classList.remove(
                        'loading'
                    );

                }

            }
        );

    }


    /* =========================================================
       UPDATE HEADER CART COUNT
    ========================================================= */

    function updateHeaderCartCount(count) {

        const cartCount =
            document.getElementById(
                'header-cart-count'
            );


        if (!cartCount) {

            return;

        }


        const numericCount =
            Number(count) || 0;


        if (numericCount > 0) {

            cartCount.textContent =
                numericCount > 99
                    ? '99+'
                    : numericCount;


            cartCount.classList.add(
                'is-visible'
            );


        } else {

            cartCount.textContent =
                '0';


            cartCount.classList.remove(
                'is-visible'
            );

        }

    }


    /* =========================================================
       CART ALERT
    ========================================================= */

    function showCartAlert(
        message,
        type = 'success'
    ) {

        const existingAlert =
            document.querySelector(
                '.book-cart-ajax-alert'
            );


        if (existingAlert) {

            existingAlert.remove();

        }


        const alert =
            document.createElement(
                'div'
            );


        alert.className =
            type === 'error'
                ? 'book-cart-alert book-cart-alert-error book-cart-ajax-alert'
                : 'book-cart-alert book-cart-alert-success book-cart-ajax-alert';


        alert.innerHTML = `

            <div class="book-cart-alert-icon">

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
                    ${escapeHtml(message)}
                </span>

            </div>

        `;


        const purchasePanel =
            document.querySelector(
                '.book-purchase-panel'
            );


        if (purchasePanel) {

            purchasePanel.parentNode.insertBefore(
                alert,
                purchasePanel
            );

        } else {

            document.body.prepend(
                alert
            );

        }


        setTimeout(
            function () {

                alert.style.opacity =
                    '0';

                alert.style.transform =
                    'translateY(-6px)';

                alert.style.transition =
                    'opacity 0.25s ease, transform 0.25s ease';


                setTimeout(
                    function () {

                        alert.remove();

                    },
                    250
                );

            },
            2500
        );

    }


    /* =========================================================
       ESCAPE ALERT TEXT
    ========================================================= */

    function escapeHtml(value) {

        const div =
            document.createElement(
                'div'
            );


        div.textContent =
            value;


        return div.innerHTML;

    }


    /* =========================================================
       WISHLIST AJAX
    ========================================================= */

    if (
        !window.secondBookWishlistInitialized
    ) {

        window.secondBookWishlistInitialized =
            true;


        document.addEventListener(
            'submit',
            async function (event) {

                const form =
                    event.target.closest(
                        '[data-book-wishlist]'
                    );


                if (!form) {

                    return;

                }


                event.preventDefault();
                event.stopPropagation();


                const button =
                    form.querySelector(
                        '[data-wishlist-button]'
                    );


                const icon =
                    button
                        ? button.querySelector('i')
                        : null;


                const text =
                    button
                        ? button.querySelector('span')
                        : null;


                const token =
                    form.querySelector(
                        'input[name="_token"]'
                    );


                if (
                    !button ||
                    !icon ||
                    !text ||
                    !token ||
                    button.disabled
                ) {

                    return;

                }


                const action =
                    form.dataset.action;


                const url =
                    action === 'remove'
                        ? form.dataset.removeUrl
                        : form.dataset.addUrl;


                const method =
                    action === 'remove'
                        ? 'DELETE'
                        : 'POST';


                if (!url) {

                    return;

                }


                button.disabled =
                    true;


                button.classList.add(
                    'loading'
                );


                try {

                    const response =
                        await fetch(
                            url,
                            {
                                method:
                                    method,

                                headers: {

                                    'X-CSRF-TOKEN':
                                        token.value,

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'

                                },

                                credentials:
                                    'same-origin'
                            }
                        );


                    const contentType =
                        response.headers.get(
                            'content-type'
                        ) || '';


                    if (
                        !contentType.includes(
                            'application/json'
                        )
                    ) {

                        throw new Error(
                            'The server did not return JSON.'
                        );

                    }


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        throw new Error(
                            data.message ||
                            'Unable to update wishlist.'
                        );

                    }


                    /* =================================================
                       REMOVE
                    ================================================== */

                    if (
                        action === 'remove'
                    ) {

                        form.dataset.action =
                            'add';


                        form.action =
                            form.dataset.addUrl;


                        const methodInput =
                            form.querySelector(
                                'input[name="_method"]'
                            );


                        if (methodInput) {

                            methodInput.value =
                                'POST';

                        }


                        button.classList.remove(
                            'active'
                        );


                        icon.classList.remove(
                            'bi-heart-fill'
                        );


                        icon.classList.add(
                            'bi-heart'
                        );


                        text.textContent =
                            'Wishlist';


                        button.title =
                            'Add to wishlist';

                    }


                    /* =================================================
                       ADD
                    ================================================== */

                    else {

                        form.dataset.action =
                            'remove';


                        form.action =
                            form.dataset.removeUrl;


                        const methodInput =
                            form.querySelector(
                                'input[name="_method"]'
                            );


                        if (methodInput) {

                            methodInput.value =
                                'DELETE';

                        }


                        button.classList.add(
                            'active'
                        );


                        icon.classList.remove(
                            'bi-heart'
                        );


                        icon.classList.add(
                            'bi-heart-fill'
                        );


                        text.textContent =
                            'Saved';


                        button.title =
                            'Remove from wishlist';

                    }


                } catch (error) {

                    console.error(
                        'SecondBook Wishlist Error:',
                        error
                    );

                } finally {

                    button.disabled =
                        false;

                    button.classList.remove(
                        'loading'
                    );

                }

            },
            true
        );

    }

});

</script>

@endpush

@endsection

