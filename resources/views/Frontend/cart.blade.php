
@extends('Layout.Frontend.master')

@section('title', 'Your Books | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/cart.css') }}">
@endpush

@section('content')
<main class="sb-cart-page">
    <div class="sb-cart-background" aria-hidden="true">
        <span class="sb-cart-orb sb-cart-orb-one"></span>
        <span class="sb-cart-orb sb-cart-orb-two"></span>
        <span class="sb-cart-grid"></span>
    </div>

    <div class="container sb-cart-container">

        {{-- =====================================================
            PAGE HEADER
        ====================================================== --}}

        <header class="sb-cart-header">
            <div class="sb-cart-header-content">
                <nav class="sb-cart-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('frontend.home') }}">Home</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Your Cart</span>
                </nav>

                <span class="sb-cart-eyebrow">
                    <span class="sb-cart-eyebrow-icon"><i class="bi bi-bag-heart"></i></span>
                    YOUR READING JOURNEY
                </span>

                <h1>Your Books, <span>Together.</span></h1>

                <p>
                    Your next great read is waiting. Review your selection
                    and get ready to turn the page.
                </p>

                <div class="sb-cart-header-trust">
                    <span><i class="bi bi-book-half"></i> Curated for readers</span>
                    <span><i class="bi bi-shield-check"></i> Secure checkout</span>
                </div>
            </div>

            <a href="{{ route('frontend.books') }}" class="sb-continue-shopping">
                <i class="bi bi-arrow-left"></i>
                <span>Continue Shopping</span>
            </a>
        </header>

        {{-- =====================================================
            SESSION ALERTS
        ====================================================== --}}

        @if(session('success'))
            <div class="sb-cart-alert sb-cart-alert-success" role="status">
                <span class="sb-cart-alert-icon"><i class="bi bi-check-lg"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="sb-cart-alert sb-cart-alert-error" role="alert">
                <span class="sb-cart-alert-icon"><i class="bi bi-exclamation-lg"></i></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- =====================================================
            SHIPPING CALCULATIONS
        ====================================================== --}}

        @php
            $shippingEnabled = \App\Models\Setting::get('shipping_enabled', true);

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
                    CART ITEMS
                ================================================== --}}

                <section class="sb-cart-main">
                    <div class="sb-cart-items-header">
                        <div class="sb-cart-items-title">
                            <div class="sb-cart-items-icon">
                                <i class="bi bi-bag"></i>
                            </div>

                            <div>
                                <span class="sb-cart-section-label">YOUR SELECTION</span>
                                <h2>Your Cart</h2>
                                <span class="sb-cart-items-count">
                                    {{ $totalItems }}
                                    {{ $totalItems === 1 ? 'item' : 'items' }}
                                </span>
                            </div>
                        </div>

                        <form
                            action="{{ route('frontend.cart.clear') }}"
                            method="POST"
                            class="sb-clear-cart-form"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="sb-clear-cart">
                                <i class="bi bi-trash3"></i>
                                <span>Clear Cart</span>
                            </button>
                        </form>
                    </div>

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
                                            'storage/' . ltrim($item['cover'], '/')
                                        );
                                }

                                $itemPrice = (float) ($item['price'] ?? 0);
                                $itemQuantity = (int) ($item['quantity'] ?? 1);
                                $itemStock = (int) ($item['stock'] ?? 0);
                                $itemTotal = $itemPrice * $itemQuantity;

                                $originalPrice = isset($item['original_price'])
                                    ? (float) $item['original_price']
                                    : $itemPrice;

                                $hasDiscount = $originalPrice > $itemPrice
                                    && $itemPrice >= 0;

                                $discountLabel = $item['discount_label'] ?? null;
                            @endphp

                            <article class="sb-cart-item" data-cart-item="{{ $item['id'] }}">
                                <div class="sb-cart-item-cover">
                                    @if($cartCover)
                                        <img
                                            src="{{ $cartCover }}"
                                            alt="{{ $item['title'] }}"
                                            loading="lazy"
                                            decoding="async"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >

                                        <div class="sb-cart-no-cover" style="display: none;">
                                            <i class="bi bi-book"></i>
                                        </div>
                                    @else
                                        <div class="sb-cart-no-cover">
                                            <i class="bi bi-book"></i>
                                        </div>
                                    @endif

                                    <span class="sb-cart-cover-shine" aria-hidden="true"></span>
                                </div>

                                <div class="sb-cart-item-content">
                                    <div class="sb-cart-item-top">
                                        <div class="sb-cart-item-details">
                                            <span class="sb-cart-item-label">BOOK SELECTION</span>
                                            <h3>{{ $item['title'] }}</h3>

                                            <div class="sb-cart-stock {{ $itemStock > 0 ? 'is-available' : 'is-unavailable' }}">
                                                @if($itemStock > 0)
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    <span>{{ $itemStock }} available</span>
                                                @else
                                                    <i class="bi bi-x-circle-fill"></i>
                                                    <span>Out of stock</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="sb-cart-unit-price">
                                            <span class="sb-cart-price-caption">UNIT PRICE</span>

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
                                                <strong>${{ number_format($itemPrice, 2) }}</strong>
                                            @endif
                                        </div>

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

                                    <div class="sb-cart-item-bottom">
                                        <form
                                            action="{{ route('frontend.cart.update', $item['id']) }}"
                                            method="POST"
                                            class="sb-quantity-form"
                                            data-quantity-form
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <span class="sb-quantity-label">Quantity</span>

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
                                                    value="{{ $itemQuantity }}"
                                                    min="1"
                                                    max="{{ $itemStock }}"
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

                                        <div class="sb-cart-item-total">
                                            <span>ITEM TOTAL</span>
                                            <strong data-item-total>
                                                ${{ number_format($itemTotal, 2) }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <a href="{{ route('frontend.books') }}" class="sb-cart-discover-link">
                        <span class="sb-cart-discover-icon"><i class="bi bi-plus-lg"></i></span>
                        <span>
                            <strong>Found another book?</strong>
                            <small>Keep exploring the collection</small>
                        </span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </section>

                {{-- =================================================
                    ORDER SUMMARY
                ================================================== --}}

                <aside class="sb-cart-summary">
                    <div class="sb-summary-card">
                        <div class="sb-summary-top-decoration" aria-hidden="true"></div>

                        <div class="sb-summary-header">
                            <div class="sb-summary-icon">
                                <i class="bi bi-receipt"></i>
                            </div>

                            <div>
                                <span>THE FINAL CHAPTER</span>
                                <h2>Order Summary</h2>
                            </div>
                        </div>

                        <div class="sb-summary-content">
                            <div class="sb-summary-row">
                                <span>Items</span>
                                <strong data-cart-total-items>
                                    {{ $totalItems }}
                                    {{ $totalItems === 1 ? 'item' : 'items' }}
                                </strong>
                            </div>

                            <div class="sb-summary-row">
                                <span>Subtotal</span>
                                <strong data-cart-subtotal>
                                    ${{ number_format($subtotal, 2) }}
                                </strong>
                            </div>

                            <div class="sb-summary-row">
                                <span>Shipping</span>

                                @if(!$shippingEnabled)
                                    <strong data-cart-shipping>Disabled</strong>
                                @elseif($shippingFee <= 0)
                                    <strong class="sb-free-shipping" data-cart-shipping>
                                        Free
                                    </strong>
                                @else
                                    <strong data-cart-shipping>
                                        ${{ number_format($shippingFee, 2) }}
                                    </strong>
                                @endif
                            </div>

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

                            <div class="sb-summary-divider"></div>

                            <div class="sb-summary-total">
                                <span>Total</span>
                                <strong data-cart-grand-total>
                                    ${{ number_format($grandTotal, 2) }}
                                </strong>
                            </div>

                            <a href="{{ route('frontend.checkout') }}" class="sb-checkout-btn">
                                <span>Proceed to Checkout</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>

                            <div class="sb-secure-checkout">
                                <span class="sb-secure-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </span>

                                <div>
                                    <strong>Secure checkout</strong>
                                    <span>Protected payment experience</span>
                                </div>

                                <i class="bi bi-patch-check-fill sb-secure-check"></i>
                            </div>

                            <div class="sb-summary-payment-note">
                                <i class="bi bi-heart"></i>
                                <span>Every book begins a new journey.</span>
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
                <div class="sb-empty-cart-decoration sb-decoration-one" aria-hidden="true"></div>
                <div class="sb-empty-cart-decoration sb-decoration-two" aria-hidden="true"></div>

                <div class="sb-empty-cart-icon">
                    <span class="sb-empty-cart-icon-ring"></span>
                    <i class="bi bi-bag-heart"></i>
                </div>

                <span class="sb-empty-cart-label">YOUR NEXT CHAPTER AWAITS</span>

                <h2>Your story starts <span>with a book.</span></h2>

                <p>
                    Your cart is taking a little break. Explore our collection
                    and discover the books waiting to become part of your next
                    reading journey.
                </p>

                <a href="{{ route('frontend.books') }}" class="sb-empty-cart-btn">
                    <i class="bi bi-book"></i>
                    <span>Explore the Collection</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <div class="sb-empty-cart-footnote">
                    <i class="bi bi-stars"></i>
                    A good book is always worth discovering.
                </div>
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
        const cartPage = document.querySelector('.sb-cart-page');

        if (!cartPage) {
            return;
        }

        function formatMoney(value) {
            return '$' + Number(value).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function showCartUpdateError(message) {
            const existingAlert = cartPage.querySelector('.sb-cart-update-error');

            if (existingAlert) {
                existingAlert.remove();
            }

            const alert = document.createElement('div');
            alert.className = 'sb-cart-alert sb-cart-alert-error sb-cart-update-error';

            const icon = document.createElement('span');
            icon.className = 'sb-cart-alert-icon';
            icon.innerHTML = '<i class="bi bi-exclamation-lg"></i>';

            const text = document.createElement('span');
            text.textContent = message || 'Unable to update your cart. Please try again.';

            alert.append(icon, text);

            const layout = cartPage.querySelector('.sb-cart-layout');

            if (layout) {
                layout.parentNode.insertBefore(alert, layout);
            }

            setTimeout(function () {
                alert.style.opacity = '0';

                setTimeout(function () {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 250);
            }, 3000);
        }

        function updateCart(form, input, previousValue) {
            if (form.dataset.updating === 'true') {
                return;
            }

            form.dataset.updating = 'true';

            const buttons = form.querySelectorAll('.sb-quantity-btn');

            buttons.forEach(function (button) {
                button.disabled = true;
            });

            const tokenInput = form.querySelector('input[name="_token"]');

            if (!tokenInput) {
                form.dataset.updating = 'false';
                buttons.forEach(function (button) {
                    button.disabled = false;
                });
                showCartUpdateError('Security token missing. Please refresh the page.');
                return;
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tokenInput.value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) {
                        throw new Error(data.message || 'Unable to update your cart.');
                    }

                    return data;
                });
            })
            .then(function (data) {
                if (!data.success) {
                    throw new Error(data.message || 'Unable to update your cart.');
                }

                const cartItem = form.closest('.sb-cart-item');

                if (cartItem && data.item_total !== undefined) {
                    const itemTotal = cartItem.querySelector('[data-item-total]');

                    if (itemTotal) {
                        itemTotal.textContent = formatMoney(data.item_total);
                    }
                }

                const totalItems = cartPage.querySelector('[data-cart-total-items]');

                if (totalItems && data.total_items !== undefined) {
                    totalItems.textContent = data.total_items
                        + (data.total_items === 1 ? ' item' : ' items');
                }

                const subtotal = cartPage.querySelector('[data-cart-subtotal]');

                if (subtotal && data.subtotal !== undefined) {
                    subtotal.textContent = formatMoney(data.subtotal);
                }

                const shipping = cartPage.querySelector('[data-cart-shipping]');

                if (shipping && data.shipping_fee !== undefined) {
                    if (!data.shipping_enabled) {
                        shipping.textContent = 'Disabled';
                        shipping.classList.remove('sb-free-shipping');
                    } else if (Number(data.shipping_fee) <= 0) {
                        shipping.textContent = 'Free';
                        shipping.classList.add('sb-free-shipping');
                    } else {
                        shipping.textContent = formatMoney(data.shipping_fee);
                        shipping.classList.remove('sb-free-shipping');
                    }
                }

                const grandTotal = cartPage.querySelector('[data-cart-grand-total]');

                if (grandTotal && data.grand_total !== undefined) {
                    grandTotal.textContent = formatMoney(data.grand_total);
                }

                const headerCartCount = document.querySelector('#header-cart-count');

                if (headerCartCount && data.total_items !== undefined) {
                    if (data.total_items > 0) {
                        headerCartCount.textContent = data.total_items > 99
                            ? '99+'
                            : data.total_items;

                        headerCartCount.style.display = '';
                    } else {
                        headerCartCount.style.display = 'none';
                    }
                }
            })
            .catch(function (error) {
                console.error(error);
                input.value = previousValue;
                showCartUpdateError(error.message);
            })
            .finally(function () {
                form.dataset.updating = 'false';

                buttons.forEach(function (button) {
                    button.disabled = false;
                });
            });
        }

        cartPage.addEventListener('click', function (event) {
            const button = event.target.closest('.sb-quantity-btn');

            if (!button) {
                return;
            }

            event.preventDefault();

            const form = button.closest('[data-quantity-form]');

            if (!form || form.dataset.updating === 'true') {
                return;
            }

            const control = button.closest('.sb-quantity-control');

            if (!control) {
                return;
            }

            const input = control.querySelector('input[name="quantity"]');

            if (!input) {
                return;
            }

            let value = parseInt(input.value, 10);

            if (Number.isNaN(value)) {
                value = 1;
            }

            const previousValue = value;
            const min = parseInt(input.getAttribute('min'), 10) || 1;
            const max = parseInt(input.getAttribute('max'), 10) || 999;
            const action = button.getAttribute('data-action');

            if (action === 'increase' && value < max) {
                value++;
            }

            if (action === 'decrease' && value > min) {
                value--;
            }

            if (value === previousValue) {
                return;
            }

            input.value = value;

            clearTimeout(form._quantityUpdateTimer);

            form._quantityUpdateTimer = setTimeout(function () {
                updateCart(form, input, previousValue);
            }, 180);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCartQuantity);
    } else {
        initCartQuantity();
    }
})();
</script>
@endpush
