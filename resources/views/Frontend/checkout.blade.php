@extends('Layout.Frontend.master')

@section('title', 'Checkout | SecondBook')

@push('css') <link rel="stylesheet" href="{{ asset('frontend-assets/css/checkout.css') }}">
@endpush

@section('content')

<main class="sb-checkout-page">


{{-- =========================================================
    HERO
========================================================== --}}

<section class="sb-checkout-hero">

    <div class="container">

        <div class="sb-checkout-breadcrumb">

            <a href="{{ route('frontend.cart') }}">
                <i class="bi bi-arrow-left"></i>
                <span>Shopping Cart</span>
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>Checkout</span>

        </div>

        <div class="sb-checkout-hero-grid">

            <div class="sb-checkout-hero-content">

                <span class="sb-checkout-eyebrow">

                    <span class="sb-checkout-eyebrow-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>

                    Secure Checkout

                </span>

                <h1>
                    Almost there.
                    <span>Complete your order.</span>
                </h1>

                <p>
                    Enter your delivery details, choose your shipping
                    and payment method, and we'll take care of the rest.
                </p>

            </div>

            <div class="sb-checkout-progress">

                <div class="sb-progress-step is-complete">

                    <span class="sb-progress-icon">
                        <i class="bi bi-check2"></i>
                    </span>

                    <span>Cart</span>

                </div>

                <span class="sb-progress-line is-active"></span>

                <div class="sb-progress-step is-active">

                    <span class="sb-progress-icon">
                        <i class="bi bi-bag-check"></i>
                    </span>

                    <span>Checkout</span>

                </div>

                <span class="sb-progress-line"></span>

                <div class="sb-progress-step">

                    <span class="sb-progress-icon">
                        <i class="bi bi-check-lg"></i>
                    </span>

                    <span>Complete</span>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    CHECKOUT
========================================================== --}}

<section class="sb-checkout-section">

    <div class="container">

        {{-- =====================================================
            ALERTS
        ====================================================== --}}

        @if(session('success'))

            <div class="sb-checkout-alert sb-alert-success">

                <span class="sb-alert-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </span>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        @if(session('error'))

            <div class="sb-checkout-alert sb-alert-error">

                <span class="sb-alert-icon">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </span>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        @endif


        @if($errors->any())

            <div class="sb-checkout-alert sb-alert-error">

                <span class="sb-alert-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </span>

                <div class="sb-alert-content">

                    <strong>
                        Please check the following:
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


        <form
            action="{{ route('frontend.checkout.store') }}"
            method="POST"
            class="sb-checkout-form"
        >

            @csrf

            <div class="row g-4 g-xl-5">

                {{-- =================================================
                    LEFT
                ================================================== --}}

                <div class="col-lg-7">


                    {{-- =================================================
                        CUSTOMER INFORMATION
                    ================================================== --}}

                    <section class="sb-checkout-card">

                        <div class="sb-card-header">

                            <div class="sb-card-heading">

                                <div class="sb-card-icon">
                                    <i class="bi bi-person-vcard"></i>
                                </div>

                                <div>

                                    <span class="sb-card-kicker">
                                        Delivery Details
                                    </span>

                                    <h2>
                                        Customer Information
                                    </h2>

                                    <p>
                                        Tell us where you'd like your
                                        order delivered.
                                    </p>

                                </div>

                            </div>

                            <span class="sb-required-badge">

                                <i class="bi bi-asterisk"></i>

                                Required

                            </span>

                        </div>


                        <div class="sb-card-body">

                            <div class="row g-3 g-md-4">

                                {{-- Full Name --}}

                                <div class="col-12">

                                    <label
                                        for="full_name"
                                        class="sb-form-label"
                                    >
                                        Full Name
                                        <span>*</span>
                                    </label>

                                    <div class="sb-input-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="full_name"
                                            name="full_name"
                                            class="sb-form-input"
                                            value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                            placeholder="Enter your full name"
                                            autocomplete="name"
                                            required
                                        >

                                    </div>

                                    @error('full_name')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- Phone --}}

                                <div class="col-md-6">

                                    <label
                                        for="phone"
                                        class="sb-form-label"
                                    >
                                        Phone Number
                                        <span>*</span>
                                    </label>

                                    <div class="sb-input-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-telephone"></i>
                                        </span>

                                        <input
                                            type="tel"
                                            id="phone"
                                            name="phone"
                                            class="sb-form-input"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                            placeholder="+994 XX XXX XX XX"
                                            autocomplete="tel"
                                            required
                                        >

                                    </div>

                                    @error('phone')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- Country --}}

                                <div class="col-md-6">

                                    <label
                                        for="country"
                                        class="sb-form-label"
                                    >
                                        Country
                                        <span>*</span>
                                    </label>

                                    <div class="sb-input-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-globe2"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="country"
                                            name="country"
                                            class="sb-form-input"
                                            value="{{ old('country') }}"
                                            placeholder="Enter your country"
                                            autocomplete="country-name"
                                            required
                                        >

                                    </div>

                                    @error('country')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- City --}}

                                <div class="col-md-6">

                                    <label
                                        for="city"
                                        class="sb-form-label"
                                    >
                                        City
                                        <span>*</span>
                                    </label>

                                    <div class="sb-input-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-buildings"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="city"
                                            name="city"
                                            class="sb-form-input"
                                            value="{{ old('city') }}"
                                            placeholder="Enter your city"
                                            autocomplete="address-level2"
                                            required
                                        >

                                    </div>

                                    @error('city')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- Postal --}}

                                <div class="col-md-6">

                                    <label
                                        for="postal_code"
                                        class="sb-form-label"
                                    >
                                        Postal Code
                                        <small>Optional</small>
                                    </label>

                                    <div class="sb-input-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-mailbox"></i>
                                        </span>

                                        <input
                                            type="text"
                                            id="postal_code"
                                            name="postal_code"
                                            class="sb-form-input"
                                            value="{{ old('postal_code') }}"
                                            placeholder="Enter postal code"
                                            autocomplete="postal-code"
                                        >

                                    </div>

                                    @error('postal_code')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- Address --}}

                                <div class="col-12">

                                    <label
                                        for="address"
                                        class="sb-form-label"
                                    >
                                        Delivery Address
                                        <span>*</span>
                                    </label>

                                    <div class="sb-input-wrap sb-textarea-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-geo-alt"></i>
                                        </span>

                                        <textarea
                                            id="address"
                                            name="address"
                                            class="sb-form-input sb-form-textarea"
                                            rows="4"
                                            placeholder="Street, building, apartment and other delivery details"
                                            autocomplete="street-address"
                                            required
                                        >{{ old('address') }}</textarea>

                                    </div>

                                    @error('address')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>


                                {{-- Note --}}

                                <div class="col-12">

                                    <label
                                        for="note"
                                        class="sb-form-label"
                                    >
                                        Order Note
                                        <small>Optional</small>
                                    </label>

                                    <div class="sb-input-wrap sb-textarea-wrap">

                                        <span class="sb-input-icon">
                                            <i class="bi bi-chat-left-text"></i>
                                        </span>

                                        <textarea
                                            id="note"
                                            name="note"
                                            class="sb-form-input sb-form-textarea sb-note-textarea"
                                            rows="3"
                                            placeholder="Any special instructions for your order?"
                                        >{{ old('note') }}</textarea>

                                    </div>

                                    @error('note')

                                        <small class="sb-field-error">
                                            {{ $message }}
                                        </small>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </section>


                    {{-- =================================================
                        SHIPPING
                    ================================================== --}}

                    @if($shippingEnabled && $shippingMethods->isNotEmpty())

                        <section class="sb-checkout-card sb-shipping-card">

                            <div class="sb-card-header">

                                <div class="sb-card-heading">

                                    <div class="sb-card-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>

                                    <div>

                                        <span class="sb-card-kicker">
                                            Delivery
                                        </span>

                                        <h2>
                                            Shipping Method
                                        </h2>

                                        <p>
                                            Choose how you'd like your
                                            order delivered.
                                        </p>

                                    </div>

                                </div>

                                <span class="sb-required-badge">

                                    <i class="bi bi-asterisk"></i>

                                    Required

                                </span>

                            </div>


                            <div class="sb-card-body">

                                <div class="sb-shipping-list">

                                    @foreach($shippingMethods as $shipping)

                                        @php

                                            $isSelected =
                                                (int) $selectedShippingId ===
                                                (int) $shipping->id;

                                            $isFreeByThreshold =
                                                $freeShippingThreshold > 0 &&
                                                $subtotal >= $freeShippingThreshold;

                                        @endphp


                                        <label
                                            class="sb-choice-card shipping-option {{ $isSelected ? 'is-selected' : '' }}"
                                        >

                                            <input
                                                type="radio"
                                                name="shipping_id"
                                                value="{{ $shipping->id }}"
                                                data-price="{{ $shipping->price }}"
                                                data-delivery="{{ $shipping->delivery_time }}"
                                                {{ $isSelected ? 'checked' : '' }}
                                                required
                                            >


                                            <span class="sb-choice-radio">
                                                <span></span>
                                            </span>


                                            <span class="sb-choice-icon">

                                                @if(str_contains(
                                                    strtolower($shipping->name),
                                                    'express'
                                                ))

                                                    <i class="bi bi-lightning-charge"></i>

                                                @else

                                                    <i class="bi bi-truck"></i>

                                                @endif

                                            </span>


                                            <span class="sb-choice-content">

                                                <strong>
                                                    {{ $shipping->name }}
                                                </strong>

                                                @if($shipping->description)

                                                    <small>
                                                        {{ $shipping->description }}
                                                    </small>

                                                @endif

                                                @if($shipping->delivery_time)

                                                    <span class="sb-delivery-time">

                                                        <i class="bi bi-clock"></i>

                                                        {{ $shipping->delivery_time }}

                                                    </span>

                                                @endif

                                            </span>


                                            {{-- =================================================
                                                SHIPPING PRICE
                                                Uses method price normally.
                                                Threshold makes the selected method FREE.
                                            ================================================== --}}

                                            <span class="sb-choice-price">

                                                @if($isFreeByThreshold)

                                                    <strong class="is-free">
                                                        FREE
                                                    </strong>

                                                    @if($shipping->price > 0)

                                                        <small>
                                                            Free shipping applied
                                                        </small>

                                                    @endif

                                                @else

                                                    @if((float) $shipping->price <= 0)

                                                        <strong class="is-free">
                                                            FREE
                                                        </strong>

                                                    @else

                                                        <strong>
                                                            ${{ number_format(
                                                                (float) $shipping->price,
                                                                2
                                                            ) }}
                                                        </strong>

                                                    @endif

                                                @endif

                                            </span>

                                        </label>

                                    @endforeach

                                </div>


                                @error('shipping_id')

                                    <small class="sb-field-error">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>

                        </section>

                    @elseif($shippingEnabled)

                        <div class="sb-checkout-alert sb-alert-error">

                            <span class="sb-alert-icon">
                                <i class="bi bi-truck"></i>
                            </span>

                            <span>
                                No shipping methods are currently available.
                            </span>

                        </div>

                    @endif


                    {{-- =================================================
                        PAYMENT
                    ================================================== --}}

                    @if($paymentsEnabled)

                        <section class="sb-checkout-card sb-payment-card">

                            <div class="sb-card-header">

                                <div class="sb-card-heading">

                                    <div class="sb-card-icon">
                                        <i class="bi bi-credit-card-2-front"></i>
                                    </div>

                                    <div>

                                        <span class="sb-card-kicker">
                                            Payment
                                        </span>

                                        <h2>
                                            Payment Method
                                        </h2>

                                        <p>
                                            Choose your preferred way to pay.
                                        </p>

                                    </div>

                                </div>

                                <span class="sb-secure-badge">

                                    <i class="bi bi-shield-lock"></i>

                                    Secure

                                </span>

                            </div>


                            <div class="sb-card-body">

                                <div class="sb-payment-list">

                                    {{-- Cash --}}

                                    @if(in_array(
                                        'cash_on_delivery',
                                        $paymentMethods
                                    ))

                                        <label class="sb-choice-card payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="cash_on_delivery"
                                                {{ old(
                                                    'payment_method',
                                                    $defaultPaymentMethod
                                                ) === 'cash_on_delivery'
                                                    ? 'checked'
                                                    : ''
                                                }}
                                                required
                                            >

                                            <span class="sb-choice-radio">
                                                <span></span>
                                            </span>

                                            <span class="sb-choice-icon">
                                                <i class="bi bi-cash-stack"></i>
                                            </span>

                                            <span class="sb-choice-content">

                                                <strong>
                                                    Cash on Delivery
                                                </strong>

                                                <small>
                                                    Pay when your order arrives.
                                                </small>

                                            </span>

                                        </label>

                                    @endif


                                    {{-- Credit Card --}}

                                    @if(in_array(
                                        'credit_card',
                                        $paymentMethods
                                    ))

                                        <label class="sb-choice-card payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="credit_card"
                                                {{ old(
                                                    'payment_method',
                                                    $defaultPaymentMethod
                                                ) === 'credit_card'
                                                    ? 'checked'
                                                    : ''
                                                }}
                                                required
                                            >

                                            <span class="sb-choice-radio">
                                                <span></span>
                                            </span>

                                            <span class="sb-choice-icon">
                                                <i class="bi bi-credit-card"></i>
                                            </span>

                                            <span class="sb-choice-content">

                                                <strong>
                                                    Credit Card
                                                </strong>

                                                <small>
                                                    Pay securely with your credit card.
                                                </small>

                                            </span>

                                        </label>

                                    @endif


                                    {{-- Debit Card --}}

                                    @if(in_array(
                                        'debit_card',
                                        $paymentMethods
                                    ))

                                        <label class="sb-choice-card payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="debit_card"
                                                {{ old(
                                                    'payment_method',
                                                    $defaultPaymentMethod
                                                ) === 'debit_card'
                                                    ? 'checked'
                                                    : ''
                                                }}
                                                required
                                            >

                                            <span class="sb-choice-radio">
                                                <span></span>
                                            </span>

                                            <span class="sb-choice-icon">
                                                <i class="bi bi-wallet2"></i>
                                            </span>

                                            <span class="sb-choice-content">

                                                <strong>
                                                    Debit Card
                                                </strong>

                                                <small>
                                                    Pay securely with your debit card.
                                                </small>

                                            </span>

                                        </label>

                                    @endif


                                    {{-- PayPal --}}

                                    @if(in_array(
                                        'paypal',
                                        $paymentMethods
                                    ))

                                        <label class="sb-choice-card payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="paypal"
                                                {{ old(
                                                    'payment_method',
                                                    $defaultPaymentMethod
                                                ) === 'paypal'
                                                    ? 'checked'
                                                    : ''
                                                }}
                                                required
                                            >

                                            <span class="sb-choice-radio">
                                                <span></span>
                                            </span>

                                            <span class="sb-choice-icon">
                                                <i class="bi bi-paypal"></i>
                                            </span>

                                            <span class="sb-choice-content">

                                                <strong>
                                                    PayPal
                                                </strong>

                                                <small>
                                                    Pay securely through PayPal.
                                                </small>

                                            </span>

                                        </label>

                                    @endif

                                </div>

                            </div>

                        </section>

                    @else

                        <div class="sb-checkout-alert sb-alert-error">

                            <span class="sb-alert-icon">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>

                            <span>
                                Payments are currently disabled.
                            </span>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    RIGHT / SUMMARY
                ================================================== --}}

                <div class="col-lg-5">

                    <aside class="sb-checkout-sidebar">

                        <div class="sb-summary-card">

                            {{-- Header --}}

                            <div class="sb-summary-header">

                                <div>

                                    <span class="sb-summary-kicker">

                                        <i class="bi bi-bag"></i>

                                        Your Cart

                                    </span>

                                    <h2>
                                        Order Summary
                                    </h2>

                                </div>

                                <span class="sb-summary-count">

                                    {{ $totalItems }}

                                    {{ $totalItems === 1
                                        ? 'item'
                                        : 'items'
                                    }}

                                </span>

                            </div>


                            {{-- Items --}}

                            <div class="sb-summary-items">

                                @foreach($cart as $item)

                                    @php

                                        $checkoutCover = null;

                                        if (!empty($item['cover'])) {

                                            $checkoutCover =
                                                filter_var(
                                                    $item['cover'],
                                                    FILTER_VALIDATE_URL
                                                )
                                                    ? $item['cover']
                                                    : asset(
                                                        'storage/' .
                                                        $item['cover']
                                                    );
                                        }

                                        $itemPrice =
                                            (float) ($item['price'] ?? 0);

                                        $itemQuantity =
                                            (int) ($item['quantity'] ?? 1);

                                        $originalPrice =
                                            isset($item['original_price'])
                                                ? (float) $item['original_price']
                                                : $itemPrice;

                                        $hasDiscount =
                                            $originalPrice >
                                            $itemPrice;

                                        $discountLabel =
                                            $item['discount_label'] ?? null;

                                    @endphp


                                    <div class="sb-summary-item">

                                        <div class="sb-summary-cover">

                                            @if($checkoutCover)

                                                <img
                                                    src="{{ $checkoutCover }}"
                                                    alt="{{ $item['title'] }}"
                                                >

                                            @else

                                                <div class="sb-summary-cover-placeholder">

                                                    <i class="bi bi-book"></i>

                                                </div>

                                            @endif

                                            <span class="sb-summary-quantity">
                                                {{ $itemQuantity }}
                                            </span>

                                        </div>


                                        <div class="sb-summary-item-info">

                                            <h3>
                                                {{ $item['title'] }}
                                            </h3>

                                            @if($hasDiscount)

                                                <span>

                                                    <s>
                                                        ${{ number_format(
                                                            $originalPrice,
                                                            2
                                                        ) }}
                                                    </s>

                                                    <strong>
                                                        ${{ number_format(
                                                            $itemPrice,
                                                            2
                                                        ) }}
                                                    </strong>

                                                    each

                                                </span>

                                                @if($discountLabel)

                                                    <small>
                                                        {{ $discountLabel }}
                                                    </small>

                                                @endif

                                            @else

                                                <span>
                                                    ${{ number_format(
                                                        $itemPrice,
                                                        2
                                                    ) }}
                                                    each
                                                </span>

                                            @endif

                                        </div>


                                        <strong class="sb-summary-item-total">

                                            ${{ number_format(
                                                $itemPrice *
                                                $itemQuantity,
                                                2
                                            ) }}

                                        </strong>

                                    </div>

                                @endforeach

                            </div>


                            {{-- Totals --}}

                            <div class="sb-summary-totals">

                                <div class="sb-summary-row">

                                    <span>
                                        Subtotal
                                    </span>

                                    <strong>
                                        ${{ number_format(
                                            $subtotal,
                                            2
                                        ) }}
                                    </strong>

                                </div>


                                @if($shippingEnabled)

                                    <div class="sb-summary-row">

                                        <span>
                                            Shipping
                                        </span>

                                        <strong
                                            id="checkout-shipping-fee"
                                            class="{{ $shippingFee <= 0 ? 'is-free' : '' }}"
                                        >

                                            @if($shippingFee <= 0)

                                                FREE

                                            @else

                                                ${{ number_format(
                                                    $shippingFee,
                                                    2
                                                ) }}

                                            @endif

                                        </strong>

                                    </div>

                                @else

                                    <div class="sb-summary-row">

                                        <span>
                                            Shipping
                                        </span>

                                        <strong class="is-free">
                                            Disabled
                                        </strong>

                                    </div>

                                @endif


                                {{-- Threshold --}}

                                @if(
                                    $shippingEnabled &&
                                    $freeShippingThreshold > 0 &&
                                    $subtotal < $freeShippingThreshold
                                )

                                    <div class="sb-shipping-note">

                                        <span class="sb-shipping-note-icon">
                                            <i class="bi bi-truck"></i>
                                        </span>

                                        <span>

                                            Free shipping on orders over
                                            ${{ number_format(
                                                $freeShippingThreshold,
                                                2
                                            ) }}.

                                        </span>

                                    </div>

                                @elseif(
                                    $shippingEnabled &&
                                    $freeShippingThreshold > 0 &&
                                    $subtotal >= $freeShippingThreshold
                                )

                                    <div class="sb-shipping-note is-qualified">

                                        <span class="sb-shipping-note-icon">
                                            <i class="bi bi-check2-circle"></i>
                                        </span>

                                        <span>
                                            You qualify for free shipping.
                                        </span>

                                    </div>

                                @endif


                                <div class="sb-summary-divider"></div>


                                <div class="sb-summary-total">

                                    <span>
                                        Total
                                    </span>

                                    <strong id="checkout-grand-total">
                                        ${{ number_format(
                                            $grandTotal,
                                            2
                                        ) }}
                                    </strong>

                                </div>

                            </div>


                            {{-- Delivery Estimate --}}

                            @if(
                                $shippingEnabled &&
                                !empty($checkoutDeliveryEstimate)
                            )

                                <div class="sb-delivery-estimate">

                                    <span class="sb-delivery-estimate-icon">
                                        <i class="bi bi-truck"></i>
                                    </span>

                                    <div>

                                        <strong>
                                            Estimated Delivery
                                        </strong>

                                        <span id="checkout-delivery-estimate">
                                            {{ $checkoutDeliveryEstimate }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                            {{-- CTA --}}

                            <button
                                type="submit"
                                class="sb-place-order-btn"
                                @disabled(!$paymentsEnabled)
                            >

                                <span class="sb-place-order-content">

                                    <span class="sb-place-order-icon">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>

                                    <span>

                                        <strong>
                                            Place Order
                                        </strong>

                                        <small>
                                            Secure checkout
                                        </small>

                                    </span>

                                </span>


                                <span
                                    class="sb-place-order-price"
                                    id="checkout-place-order-price"
                                >
                                    ${{ number_format(
                                        $grandTotal,
                                        2
                                    ) }}
                                </span>

                            </button>


                            {{-- Back --}}

                            <a
                                href="{{ route('frontend.cart') }}"
                                class="sb-back-cart"
                            >

                                <i class="bi bi-arrow-left"></i>

                                Back to Cart

                            </a>


                            {{-- Security --}}

                            <div class="sb-secure-checkout">

                                <div class="sb-secure-checkout-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>

                                <div class="sb-secure-checkout-content">

                                    <strong class="sb-secure-checkout-title">
                                        Secure &amp; Protected
                                    </strong>

                                    <span class="sb-secure-checkout-text">
                                        Your payment and personal information are protected.
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Trust --}}

                        <div class="sb-checkout-trust">

                            <div class="sb-trust-item">

                                <span class="sb-trust-icon">
                                    <i class="bi bi-truck"></i>
                                </span>

                                <div>

                                    <strong id="checkout-trust-shipping-title">

                                        @if($shippingEnabled)

                                            {{ $shippingFee <= 0
                                                ? 'Free Shipping'
                                                : 'Shipping Available'
                                            }}

                                        @else

                                            Shipping

                                        @endif

                                    </strong>

                                    <span id="checkout-trust-shipping-text">

                                        @if(!$shippingEnabled)

                                            Currently unavailable

                                        @elseif($shippingFee <= 0)

                                            Available on this order

                                        @else

                                            Delivery available

                                        @endif

                                    </span>

                                </div>

                            </div>


                            <div class="sb-trust-item">

                                <span class="sb-trust-icon">
                                    <i class="bi bi-arrow-repeat"></i>
                                </span>

                                <div>

                                    <strong>
                                        Easy Returns
                                    </strong>

                                    <span>
                                        Simple return process
                                    </span>

                                </div>

                            </div>


                            <div class="sb-trust-item">

                                <span class="sb-trust-icon">
                                    <i class="bi bi-headset"></i>
                                </span>

                                <div>

                                    <strong>
                                        Support
                                    </strong>

                                    <span>
                                        We're here to help
                                    </span>

                                </div>

                            </div>

                        </div>

                    </aside>

                </div>

            </div>

        </form>

    </div>

</section>


</main>

@endsection

{{-- =========================================================
SHIPPING JAVASCRIPT
========================================================= --}}

@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const shippingOptions =
        document.querySelectorAll(
            'input[name="shipping_id"]'
        );

    const shippingFeeElement =
        document.getElementById(
            'checkout-shipping-fee'
        );

    const grandTotalElement =
        document.getElementById(
            'checkout-grand-total'
        );

    const placeOrderPriceElement =
        document.getElementById(
            'checkout-place-order-price'
        );

    const deliveryEstimateElement =
        document.getElementById(
            'checkout-delivery-estimate'
        );

    const trustShippingTitle =
        document.getElementById(
            'checkout-trust-shipping-title'
        );

    const trustShippingText =
        document.getElementById(
            'checkout-trust-shipping-text'
        );


    /*
    |--------------------------------------------------------------------------
    | Checkout Values
    |--------------------------------------------------------------------------
    */

    const subtotal =
        {{ (float) $subtotal }};

    const freeShippingThreshold =
        {{ (float) $freeShippingThreshold }};

    const defaultShippingFee =
        {{ (float) $defaultShippingFee }};


    /*
    |--------------------------------------------------------------------------
    | Format Price
    |--------------------------------------------------------------------------
    */

    function formatPrice(value) {

        return '$' +
            Number(value).toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | Update Shipping
    |--------------------------------------------------------------------------
    */

    function updateShipping() {

        const selected =
            document.querySelector(
                'input[name="shipping_id"]:checked'
            );

        if (!selected) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Delivery
        |--------------------------------------------------------------------------
        */

        const delivery =
            selected.dataset.delivery || '';


        /*
        |--------------------------------------------------------------------------
        | Shipping Fee
        |--------------------------------------------------------------------------
        |
        | Below threshold:
        |     Admin default shipping fee
        |
        | At / above threshold:
        |     FREE
        |
        */

        let shippingFee =
            defaultShippingFee;


        /*
        |--------------------------------------------------------------------------
        | Free Shipping Threshold
        |--------------------------------------------------------------------------
        */

        if (
            freeShippingThreshold > 0 &&
            subtotal >= freeShippingThreshold
        ) {

            shippingFee = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        const grandTotal =
            subtotal + shippingFee;


        /*
        |--------------------------------------------------------------------------
        | Shipping Fee
        |--------------------------------------------------------------------------
        */

        if (shippingFee <= 0) {

            if (shippingFeeElement) {

                shippingFeeElement.textContent =
                    'FREE';

                shippingFeeElement.classList.add(
                    'is-free'
                );

            }

        } else {

            if (shippingFeeElement) {

                shippingFeeElement.textContent =
                    formatPrice(
                        shippingFee
                    );

                shippingFeeElement.classList.remove(
                    'is-free'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        if (grandTotalElement) {

            grandTotalElement.textContent =
                formatPrice(
                    grandTotal
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Place Order Price
        |--------------------------------------------------------------------------
        */

        if (placeOrderPriceElement) {

            placeOrderPriceElement.textContent =
                formatPrice(
                    grandTotal
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Delivery Estimate
        |--------------------------------------------------------------------------
        */

        if (deliveryEstimateElement) {

            deliveryEstimateElement.textContent =
                delivery;

        }


        /*
        |--------------------------------------------------------------------------
        | Trust Section
        |--------------------------------------------------------------------------
        */

        if (trustShippingTitle) {

            trustShippingTitle.textContent =
                shippingFee <= 0
                    ? 'Free Shipping'
                    : 'Shipping Available';

        }


        if (trustShippingText) {

            trustShippingText.textContent =
                shippingFee <= 0
                    ? 'Available on this order'
                    : 'Delivery available';

        }


        /*
        |--------------------------------------------------------------------------
        | Selected Shipping
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.shipping-option'
            )
            .forEach(
                function (option) {

                    option.classList.remove(
                        'is-selected'
                    );

                }
            );


        const selectedOption =
            selected.closest(
                '.shipping-option'
            );


        if (selectedOption) {

            selectedOption.classList.add(
                'is-selected'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Shipping Change
    |--------------------------------------------------------------------------
    */

    shippingOptions.forEach(
        function (option) {

            option.addEventListener(
                'change',
                updateShipping
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Payment Selected State
    |--------------------------------------------------------------------------
    */

    const paymentOptions =
        document.querySelectorAll(
            '.payment-option input[name="payment_method"]'
        );


    function updatePaymentState() {

        document
            .querySelectorAll(
                '.payment-option'
            )
            .forEach(
                function (option) {

                    option.classList.remove(
                        'is-selected'
                    );

                }
            );


        const selectedPayment =
            document.querySelector(
                'input[name="payment_method"]:checked'
            );


        if (selectedPayment) {

            const selectedOption =
                selectedPayment.closest(
                    '.payment-option'
                );


            if (selectedOption) {

                selectedOption.classList.add(
                    'is-selected'
                );

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Payment Change
    |--------------------------------------------------------------------------
    */

    paymentOptions.forEach(
        function (option) {

            option.addEventListener(
                'change',
                updatePaymentState
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updateShipping();

    updatePaymentState();

});

</script>

@endpush
