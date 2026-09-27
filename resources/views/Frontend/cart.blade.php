@extends('Layout.Frontend.master')

@section('title', 'Your Books | SecondBook')

@push('css')

<link rel="stylesheet" href="{{ asset('frontend-assets/css/cart.css') }}">

@endpush

@section('content')

<main class="sb-cart-page">

    <div class="container">

        {{-- =====================================================
            PAGE HEADER
        ====================================================== --}}

        <header class="sb-cart-header">

            <div class="sb-cart-header-content">

                <nav class="sb-cart-breadcrumb" aria-label="Breadcrumb">

                    <a href="{{ route('frontend.home') }}">
                        Home
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        Your Cart
                    </span>

                </nav>

                <span class="sb-cart-eyebrow">

                    <i class="bi bi-bag-heart"></i>

                    Ready When You Are

                </span>

                <h1>
                    Your Books, Together.
                </h1>

                <p>
                    Take a final look at your selected books before moving on to checkout.
                </p>

            </div>

            <a
                href="{{ route('frontend.books') }}"
                class="sb-continue-shopping"
            >

                <i class="bi bi-arrow-left"></i>

                <span>
                    Continue Shopping
                </span>

            </a>

        </header>


        {{-- =====================================================
            ALERTS
        ====================================================== --}}

        @if(session('success'))

            <div class="sb-cart-alert sb-cart-alert-success">

                <span class="sb-cart-alert-icon">
                    <i class="bi bi-check-lg"></i>
                </span>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        @if(session('error'))

            <div class="sb-cart-alert sb-cart-alert-error">

                <span class="sb-cart-alert-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </span>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        @endif


        {{-- =====================================================
            SHIPPING CALCULATIONS
        ====================================================== --}}

        @php

            $shippingEnabled = \App\Models\Setting::get(
                'shipping_enabled',
                true
            );

            $defaultShippingFee = (float) \App\Models\Setting::get(
                'default_shipping_fee',
                0
            );

            $freeShippingThreshold = (float) \App\Models\Setting::get(
                'free_shipping_threshold',
                0
            );

            $shippingFee = 0;

            if ($shippingEnabled) {

                if (
                    $freeShippingThreshold <= 0 ||
                    $subtotal < $freeShippingThreshold
                ) {
                    $shippingFee = $defaultShippingFee;
                }

            }

            $grandTotal = $subtotal + $shippingFee;

        @endphp


        {{-- =====================================================
            CART CONTENT
        ====================================================== --}}

        @if(count($cart) > 0)

            <div class="sb-cart-layout">


                {{-- =================================================
                    LEFT SIDE
                ================================================== --}}

                <section class="sb-cart-main">


                    {{-- =================================================
                        CART SECTION HEADER
                    ================================================== --}}

                    <div class="sb-cart-items-header">

                        <div class="sb-cart-items-title">

                            <div class="sb-cart-items-icon">
                                <i class="bi bi-bag"></i>
                            </div>

                            <div>

                                <span class="sb-cart-section-label">
                                    YOUR SELECTION
                                </span>

                                <h2>
                                    Your Cart
                                </h2>

                                <span class="sb-cart-items-count">

                                    {{ $totalItems }}

                                    {{ $totalItems === 1 ? 'item' : 'items' }}

                                </span>

                            </div>

                        </div>


                        {{-- CLEAR CART --}}

                        <form
                            action="{{ route('frontend.cart.clear') }}"
                            method="POST"
                            class="sb-clear-cart-form"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="sb-clear-cart"
                            >

                                <i class="bi bi-trash3"></i>

                                <span>
                                    Clear Cart
                                </span>

                            </button>

                        </form>

                    </div>


                    {{-- =================================================
                        CART ITEMS
                    ================================================== --}}

                    <div class="sb-cart-items-list">

                        @foreach($cart as $item)

                            @php

                                $cartCover = null;

                                if (!empty($item['cover'])) {

                                    $cartCover = filter_var(
                                        $item['cover'],
                                        FILTER_VALIDATE_URL
                                    )
                                        ? $item['cover']
                                        : asset(
                                            'storage/' . ltrim(
                                                $item['cover'],
                                                '/'
                                            )
                                        );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | CURRENT ITEM PRICE
                                |--------------------------------------------------------------------------
                                |
                                | CartController should store the actual
                                | selling price in $item['price'].
                                |
                                | If a discounted price was stored,
                                | this is the price used for calculations.
                                |
                                */

                                $itemPrice = (float) ($item['price'] ?? 0);

                                $itemQuantity = (int) ($item['quantity'] ?? 1);

                                $itemTotal = $itemPrice * $itemQuantity;


                                /*
                                |--------------------------------------------------------------------------
                                | OPTIONAL ORIGINAL PRICE
                                |--------------------------------------------------------------------------
                                |
                                | If CartController provides original_price,
                                | the cart can show the original price as
                                | crossed out.
                                |
                                */

                                $originalPrice = isset($item['original_price'])
                                    ? (float) $item['original_price']
                                    : $itemPrice;

                                $hasDiscount =
                                    $originalPrice > $itemPrice &&
                                    $itemPrice >= 0;


                                /*
                                |--------------------------------------------------------------------------
                                | DISCOUNT LABEL
                                |--------------------------------------------------------------------------
                                */

                                $discountLabel = $item['discount_label'] ?? null;

                            @endphp


                            {{-- =================================================
                                CART ITEM
                            ================================================== --}}

                            <article
                                class="sb-cart-item"
                                data-cart-item="{{ $item['id'] }}"
                            >


                                {{-- =================================================
                                    COVER
                                ================================================== --}}

                                <div class="sb-cart-item-cover">

                                    @if($cartCover)

                                        <img
                                            src="{{ $cartCover }}"
                                            alt="{{ $item['title'] }}"
                                            loading="lazy"
                                            decoding="async"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >

                                        <div
                                            class="sb-cart-no-cover"
                                            style="display: none;"
                                        >

                                            <i class="bi bi-book"></i>

                                        </div>

                                    @else

                                        <div class="sb-cart-no-cover">

                                            <i class="bi bi-book"></i>

                                        </div>

                                    @endif

                                </div>


                                {{-- =================================================
                                    ITEM CONTENT
                                ================================================== --}}

                                <div class="sb-cart-item-content">


                                    {{-- =================================================
                                        ITEM TOP
                                    ================================================== --}}

                                    <div class="sb-cart-item-top">


                                        {{-- BOOK DETAILS --}}

                                        <div class="sb-cart-item-details">

                                            <span class="sb-cart-item-label">
                                                BOOK
                                            </span>

                                            <h3>
                                                {{ $item['title'] }}
                                            </h3>

                                            <div class="sb-cart-stock">

                                                @if($item['stock'] > 0)

                                                    <i class="bi bi-check-circle-fill"></i>

                                                    <span>
                                                        {{ $item['stock'] }}
                                                        available
                                                    </span>

                                                @else

                                                    <i class="bi bi-x-circle-fill"></i>

                                                    <span>
                                                        Out of stock
                                                    </span>

                                                @endif

                                            </div>

                                        </div>


                                        {{-- UNIT PRICE --}}

                                        <div class="sb-cart-unit-price">

                                            <span>
                                                Unit Price
                                            </span>


                                            @if($hasDiscount)

                                                <div class="sb-cart-price-wrap">

                                                    <span class="sb-cart-old-price">
                                                        ${{ number_format($originalPrice, 2) }}
                                                    </span>

                                                    <strong class="sb-cart-discounted-price">
                                                        ${{ number_format($itemPrice, 2) }}
                                                    </strong>

                                                </div>

                                                @if($discountLabel)

                                                    <small class="sb-cart-discount-label">
                                                        {{ $discountLabel }}
                                                    </small>

                                                @endif

                                            @else

                                                <strong>
                                                    ${{ number_format($itemPrice, 2) }}
                                                </strong>

                                            @endif

                                        </div>


                                        {{-- REMOVE --}}

                                        <form
                                            action="{{ route('frontend.cart.remove', $item['id']) }}"
                                            method="POST"
                                            class="sb-remove-form"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="sb-remove-item"
                                                aria-label="Remove {{ $item['title'] }}"
                                                title="Remove item"
                                            >

                                                <i class="bi bi-x-lg"></i>

                                            </button>

                                        </form>

                                    </div>


                                    {{-- =================================================
                                        ITEM BOTTOM
                                    ================================================== --}}

                                    <div class="sb-cart-item-bottom">


                                        {{-- =================================================
                                            QUANTITY
                                        ================================================== --}}

                                        <form
                                            action="{{ route('frontend.cart.update', $item['id']) }}"
                                            method="POST"
                                            class="sb-quantity-form"
                                            data-quantity-form
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <span class="sb-quantity-label">
                                                Quantity
                                            </span>

                                            <div class="sb-quantity-control">

                                                <button
                                                    type="button"
                                                    class="sb-quantity-btn"
                                                    data-action="decrease"
                                                    aria-label="Decrease quantity"
                                                >

                                                    <i class="bi bi-dash"></i>

                                                </button>


                                                <input
                                                    type="text"
                                                    name="quantity"
                                                    value="{{ $item['quantity'] }}"
                                                    min="1"
                                                    max="{{ $item['stock'] }}"
                                                    readonly
                                                    aria-label="Quantity"
                                                >


                                                <button
                                                    type="button"
                                                    class="sb-quantity-btn"
                                                    data-action="increase"
                                                    aria-label="Increase quantity"
                                                >

                                                    <i class="bi bi-plus"></i>

                                                </button>

                                            </div>

                                        </form>


                                        {{-- =================================================
                                            THIS PRODUCT TOTAL
                                        ================================================== --}}

                                        <div class="sb-cart-item-total">

                                            <span>
                                                Item Total
                                            </span>

                                            <strong data-item-total>
                                                ${{ number_format($itemTotal, 2) }}
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>

                </section>


                {{-- =================================================
                    ORDER SUMMARY
                ================================================== --}}

                <aside class="sb-cart-summary">

                    <div class="sb-summary-card">


                        {{-- =================================================
                            SUMMARY HEADER
                        ================================================== --}}

                        <div class="sb-summary-header">

                            <div class="sb-summary-icon">
                                <i class="bi bi-receipt"></i>
                            </div>

                            <div>

                                <span>
                                    YOUR ORDER
                                </span>

                                <h2>
                                    Order Summary
                                </h2>

                            </div>

                        </div>


                        {{-- =================================================
                            SUMMARY CONTENT
                        ================================================== --}}

                        <div class="sb-summary-content">


                            {{-- =================================================
                                ITEMS
                            ================================================== --}}

                            <div class="sb-summary-row">

                                <span>
                                    Items
                                </span>

                                <strong data-cart-total-items>

                                    {{ $totalItems }}

                                    {{ $totalItems === 1 ? 'item' : 'items' }}

                                </strong>

                            </div>


                            {{-- =================================================
                                SUBTOTAL
                            ================================================== --}}

                            <div class="sb-summary-row">

                                <span>
                                    Subtotal
                                </span>

                                <strong data-cart-subtotal>
                                    ${{ number_format($subtotal, 2) }}
                                </strong>

                            </div>


                            {{-- =================================================
                                SHIPPING
                            ================================================== --}}

                            <div class="sb-summary-row">

                                <span>
                                    Shipping
                                </span>

                                @if(!$shippingEnabled)

                                    <strong data-cart-shipping>
                                        Disabled
                                    </strong>

                                @elseif($shippingFee <= 0)

                                    <strong
                                        class="sb-free-shipping"
                                        data-cart-shipping
                                    >
                                        Free
                                    </strong>

                                @else

                                    <strong data-cart-shipping>
                                        ${{ number_format($shippingFee, 2) }}
                                    </strong>

                                @endif

                            </div>


                            {{-- =================================================
                                SHIPPING NOTE
                            ================================================== --}}

                            @if($shippingEnabled && $freeShippingThreshold > 0)

                                <div class="sb-shipping-note">

                                    <span class="sb-shipping-note-icon">
                                        <i class="bi bi-truck"></i>
                                    </span>

                                    <span>

                                        @if($shippingFee > 0)

                                            Free shipping on orders over
                                            ${{ number_format($freeShippingThreshold, 2) }}.

                                        @else

                                            Free shipping is included with your order.

                                        @endif

                                    </span>

                                </div>

                            @endif


                            {{-- =================================================
                                DIVIDER
                            ================================================== --}}

                            <div class="sb-summary-divider"></div>


                            {{-- =================================================
                                GRAND TOTAL
                            ================================================== --}}

                            <div class="sb-summary-total">

                                <span>
                                    Total
                                </span>

                                <strong data-cart-grand-total>
                                    ${{ number_format($grandTotal, 2) }}
                                </strong>

                            </div>


                            {{-- =================================================
                                CHECKOUT
                            ================================================== --}}

                            <a
                                href="{{ route('frontend.checkout') }}"
                                class="sb-checkout-btn"
                            >

                                <span>
                                    Proceed to Checkout
                                </span>

                                <i class="bi bi-arrow-right"></i>

                            </a>


                            {{-- =================================================
                                SECURE CHECKOUT
                            ================================================== --}}

                            <div class="sb-secure-checkout">

                                <span class="sb-secure-icon">
                                    <i class="bi bi-shield-check"></i>
                                </span>

                                <div>

                                    <strong>
                                        Secure checkout
                                    </strong>

                                    <span>
                                        Protected payment experience
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </aside>

            </div>


        @else


            {{-- =====================================================
                EMPTY CART
            ====================================================== --}}

            <section class="sb-empty-cart">

                <div
                    class="sb-empty-cart-decoration sb-decoration-one"
                ></div>

                <div
                    class="sb-empty-cart-decoration sb-decoration-two"
                ></div>


                <div class="sb-empty-cart-icon">
                    <i class="bi bi-bag-heart"></i>
                </div>


                <span class="sb-empty-cart-label">
                    YOUR COLLECTION
                </span>


                <h2>
                    Nothing here yet.
                </h2>


                <p>
                    Explore our collection and discover the books
                    waiting to become part of your next reading journey.
                </p>


                <a
                    href="{{ route('frontend.books') }}"
                    class="sb-empty-cart-btn"
                >

                    <i class="bi bi-book"></i>

                    <span>
                        Browse Books
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            </section>

        @endif

    </div>

</main>

@endsection


{{-- =============================================================
    CART QUANTITY JAVASCRIPT
============================================================= --}}

@push('js')

<script>

(function () {

    function initCartQuantity() {

        const cartPage =
            document.querySelector('.sb-cart-page');

        if (!cartPage) {
            return;
        }


        /* =========================================================
           MONEY FORMAT
        ========================================================= */

        function formatMoney(value) {

            return '$' + Number(value).toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

        }


        /* =========================================================
           UPDATE CART
        ========================================================= */

        function updateCart(
            form,
            input,
            previousValue
        ) {

            if (form.dataset.updating === 'true') {
                return;
            }

            form.dataset.updating = 'true';


            const buttons =
                form.querySelectorAll(
                    '.sb-quantity-btn'
                );


            buttons.forEach(function (button) {

                button.disabled = true;

            });


            const formData =
                new FormData(form);


            fetch(
                form.action,
                {
                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                            form.querySelector(
                                'input[name="_token"]'
                            ).value,

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'

                    },

                    body: formData

                }
            )

            .then(function (response) {

                return response.json()

                    .then(function (data) {

                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Unable to update your cart.'
                            );

                        }

                        return data;

                    });

            })

            .then(function (data) {

                if (!data.success) {

                    throw new Error(
                        data.message ||
                        'Unable to update your cart.'
                    );

                }


                /* =================================================
                   ITEM TOTAL
                ================================================== */

                const cartItem =
                    form.closest(
                        '.sb-cart-item'
                    );


                if (
                    cartItem &&
                    data.item_total !== undefined
                ) {

                    const itemTotal =
                        cartItem.querySelector(
                            '[data-item-total]'
                        );


                    if (itemTotal) {

                        itemTotal.textContent =
                            formatMoney(
                                data.item_total
                            );

                    }

                }


                /* =================================================
                   TOTAL ITEMS
                ================================================== */

                const totalItems =
                    cartPage.querySelector(
                        '[data-cart-total-items]'
                    );


                if (
                    totalItems &&
                    data.total_items !== undefined
                ) {

                    totalItems.textContent =
                        data.total_items +
                        (
                            data.total_items === 1
                                ? ' item'
                                : ' items'
                        );

                }


                /* =================================================
                   SUBTOTAL
                ================================================== */

                const subtotal =
                    cartPage.querySelector(
                        '[data-cart-subtotal]'
                    );


                if (
                    subtotal &&
                    data.subtotal !== undefined
                ) {

                    subtotal.textContent =
                        formatMoney(
                            data.subtotal
                        );

                }


                /* =================================================
                   SHIPPING
                ================================================== */

                const shipping =
                    cartPage.querySelector(
                        '[data-cart-shipping]'
                    );


                if (
                    shipping &&
                    data.shipping_fee !== undefined
                ) {

                    if (!data.shipping_enabled) {

                        shipping.textContent =
                            'Disabled';

                        shipping.classList.remove(
                            'sb-free-shipping'
                        );

                    }

                    else if (
                        Number(data.shipping_fee) <= 0
                    ) {

                        shipping.textContent =
                            'Free';

                        shipping.classList.add(
                            'sb-free-shipping'
                        );

                    }

                    else {

                        shipping.textContent =
                            formatMoney(
                                data.shipping_fee
                            );

                        shipping.classList.remove(
                            'sb-free-shipping'
                        );

                    }

                }


                /* =================================================
                   GRAND TOTAL
                ================================================== */

                const grandTotal =
                    cartPage.querySelector(
                        '[data-cart-grand-total]'
                    );


                if (
                    grandTotal &&
                    data.grand_total !== undefined
                ) {

                    grandTotal.textContent =
                        formatMoney(
                            data.grand_total
                        );

                }


                /* =================================================
                   HEADER CART COUNT
                ================================================== */

                const headerCartCount =
                    document.querySelector(
                        '#header-cart-count'
                    );


                if (
                    headerCartCount &&
                    data.total_items !== undefined
                ) {

                    if (data.total_items > 0) {

                        headerCartCount.textContent =
                            data.total_items > 99
                                ? '99+'
                                : data.total_items;

                        headerCartCount.style.display =
                            '';

                    }

                    else {

                        headerCartCount.style.display =
                            'none';

                    }

                }

            })

            .catch(function (error) {

                console.error(error);

                input.value =
                    previousValue;

                showCartUpdateError(
                    error.message
                );

            })

            .finally(function () {

                form.dataset.updating =
                    'false';


                buttons.forEach(function (button) {

                    button.disabled =
                        false;

                });

            });

        }


        /* =========================================================
           ERROR ALERT
        ========================================================= */

        function showCartUpdateError(message) {

            const existingAlert =
                cartPage.querySelector(
                    '.sb-cart-update-error'
                );


            if (existingAlert) {
                existingAlert.remove();
            }


            const alert =
                document.createElement('div');


            alert.className =
                'sb-cart-alert sb-cart-alert-error sb-cart-update-error';


            alert.innerHTML = `

                <span class="sb-cart-alert-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </span>

                <span>
                    ${message || 'Unable to update your cart. Please try again.'}
                </span>

            `;


            const layout =
                cartPage.querySelector(
                    '.sb-cart-layout'
                );


            if (layout) {

                layout.parentNode.insertBefore(
                    alert,
                    layout
                );

            }


            setTimeout(function () {

                alert.style.opacity =
                    '0';


                setTimeout(function () {

                    if (alert.parentNode) {
                        alert.remove();
                    }

                }, 250);

            }, 3000);

        }


        /* =========================================================
           QUANTITY BUTTONS
        ========================================================= */

        cartPage.addEventListener(
            'click',
            function (event) {

                const button =
                    event.target.closest(
                        '.sb-quantity-btn'
                    );


                if (!button) {
                    return;
                }


                event.preventDefault();


                const form =
                    button.closest(
                        '[data-quantity-form]'
                    );


                if (!form) {
                    return;
                }


                if (
                    form.dataset.updating ===
                    'true'
                ) {
                    return;
                }


                const control =
                    button.closest(
                        '.sb-quantity-control'
                    );


                if (!control) {
                    return;
                }


                const input =
                    control.querySelector(
                        'input[name="quantity"]'
                    );


                if (!input) {
                    return;
                }


                let value =
                    parseInt(
                        input.value,
                        10
                    );


                if (Number.isNaN(value)) {
                    value = 1;
                }


                const previousValue =
                    value;


                const min =
                    parseInt(
                        input.getAttribute('min'),
                        10
                    ) || 1;


                const max =
                    parseInt(
                        input.getAttribute('max'),
                        10
                    ) || 999;


                const action =
                    button.getAttribute(
                        'data-action'
                    );


                if (
                    action === 'increase' &&
                    value < max
                ) {

                    value++;

                }


                if (
                    action === 'decrease' &&
                    value > min
                ) {

                    value--;

                }


                if (
                    value === previousValue
                ) {

                    return;

                }


                input.value =
                    value;


                /* =================================================
                   DEBOUNCE
                ================================================== */

                clearTimeout(
                    form._quantityUpdateTimer
                );


                form._quantityUpdateTimer =
                    setTimeout(function () {

                        updateCart(
                            form,
                            input,
                            previousValue
                        );

                    }, 180);

            }
        );

    }


    /* =============================================================
       INITIALIZE
    ============================================================= */

    if (
        document.readyState ===
        'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initCartQuantity
        );

    }

    else {

        initCartQuantity();

    }

})();

</script>

@endpush

