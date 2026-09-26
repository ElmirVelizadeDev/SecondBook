@extends('Layout.Frontend.master')

@section('title', 'Track Order | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/order-tracking.css') }}">
@endpush

@section('content')

@php
    $orderStatus = $order->order_status ?? 'pending';

    $coverUrl = null;

    if ($order->book && !empty($order->book->cover)) {
        $coverUrl = filter_var($order->book->cover, FILTER_VALIDATE_URL)
            ? $order->book->cover
            : asset('storage/' . ltrim($order->book->cover, '/'));
    }

    $statusOrder = [
        'pending',
        'processing',
        'shipped',
        'delivered'
    ];

    $currentIndex = array_search($orderStatus, $statusOrder);
@endphp


<main class="sb-order-tracking-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <section class="tracking-hero">

        <div class="container">

            <div class="tracking-breadcrumb">

                <a href="{{ route('frontend.home') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>

                <i class="bi bi-chevron-right"></i>

                <a href="{{ route('frontend.orders') }}">
                    <span>My Orders</span>
                </a>

                <i class="bi bi-chevron-right"></i>

                <span class="is-current">
                    Track Order
                </span>

            </div>


            <div class="tracking-hero-grid">

                <div class="tracking-hero-content">

                    <span class="tracking-eyebrow">

                        <span class="tracking-eyebrow-icon">
                            <i class="bi bi-truck"></i>
                        </span>

                        Order Tracking

                    </span>


                    <h1>
                        Track Your
                        <span>Order</span>
                    </h1>


                    <p>
                        Follow your order's journey from placement
                        to delivery and stay updated every step of the way.
                    </p>

                </div>


                <div class="tracking-hero-order">

                    <span class="tracking-hero-order-label">
                        ORDER
                    </span>

                    <strong>
                        #{{ $order->order_number }}
                    </strong>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         CONTENT
    ====================================================== --}}
    <section class="tracking-section">

        <div class="container">


            {{-- =================================================
                 ORDER OVERVIEW
            ================================================== --}}
            <article class="tracking-order-card">

                <div class="tracking-order-top">

                    <div>

                        <span class="tracking-small-label">
                            Order Reference
                        </span>

                        <div class="tracking-order-number">
                            #{{ $order->order_number }}
                        </div>

                    </div>


                    <span class="tracking-order-badge">
                        <i class="bi bi-box-seam"></i>
                        Order Tracking
                    </span>

                </div>


                <div class="tracking-order-info">

                    <div class="tracking-order-info-item">

                        <span class="tracking-label">
                            <i class="bi bi-calendar3"></i>
                            Order Date
                        </span>

                        <strong>
                            {{ $order->created_at->format('M d, Y') }}
                        </strong>

                    </div>


                    <div class="tracking-order-info-item">

                        <span class="tracking-label">
                            <i class="bi bi-clock"></i>
                            Order Time
                        </span>

                        <strong>
                            {{ $order->created_at->format('H:i') }}
                        </strong>

                    </div>


                    <div class="tracking-order-info-item">

                        <span class="tracking-label">
                            <i class="bi bi-cash-stack"></i>
                            Total
                        </span>

                        <strong class="tracking-total">
                            ₼{{ number_format($order->total_price, 2) }}
                        </strong>

                    </div>

                </div>

            </article>


            {{-- =================================================
                 TRACKING CARD
            ================================================== --}}
            <article class="tracking-card">

                <div class="tracking-card-header">

                    <div class="tracking-card-heading">

                        <span class="tracking-section-eyebrow">
                            ORDER STATUS
                        </span>

                        <h2>
                            {{ ucfirst(str_replace('_', ' ', $orderStatus)) }}
                        </h2>

                        <p>
                            Here's the current progress of your order.
                        </p>

                    </div>


                    <span class="tracking-status-badge status-{{ $orderStatus }}">

                        <span class="tracking-status-dot"></span>

                        {{ ucfirst(str_replace('_', ' ', $orderStatus)) }}

                    </span>

                </div>


                <div class="tracking-timeline">

                    <div class="tracking-line"></div>

                    @foreach($statusOrder as $index => $status)

                        @php
                            $step = $statuses[$status];

                            $isCompleted = $currentIndex !== false
                                && $index < $currentIndex;

                            $isCurrent = $currentIndex === $index;
                        @endphp


                        <div
                            class="tracking-step
                                {{ $isCompleted ? 'completed' : '' }}
                                {{ $isCurrent ? 'current' : '' }}"
                        >

                            <div class="tracking-step-marker">

                                <div class="tracking-step-icon">
                                    <i class="bi {{ $step['icon'] }}"></i>
                                </div>

                            </div>


                            <div class="tracking-step-content">

                                <span class="tracking-step-number">
                                    0{{ $index + 1 }}
                                </span>

                                <h3>
                                    {{ $step['label'] }}
                                </h3>

                                <p>
                                    {{ $step['description'] }}
                                </p>


                                @if($isCompleted)

                                    <span class="tracking-step-state completed-state">
                                        <i class="bi bi-check2"></i>
                                        Completed
                                    </span>

                                @elseif($isCurrent)

                                    <span class="tracking-step-state current-state">
                                        <span></span>
                                        Current Status
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </article>


            {{-- =================================================
                 CANCELLED
            ================================================== --}}
            @if($orderStatus === 'cancelled')

                <div class="tracking-cancelled">

                    <div class="tracking-cancelled-icon">
                        <i class="bi bi-x-lg"></i>
                    </div>

                    <div class="tracking-cancelled-content">

                        <span>
                            ORDER UPDATE
                        </span>

                        <h3>
                            Order Cancelled
                        </h3>

                        <p>
                            This order has been cancelled and will not
                            continue through the delivery process.
                        </p>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 ORDER ITEM
            ================================================== --}}
            <article class="tracking-product-card">

                <div class="tracking-card-heading-row">

                    <div class="tracking-section-heading">

                        <span class="tracking-heading-icon">
                            <i class="bi bi-book-half"></i>
                        </span>

                        <div>

                            <span>
                                ORDER ITEM
                            </span>

                            <h2>
                                Your Book
                            </h2>

                        </div>

                    </div>

                    <span class="tracking-card-number">
                        01
                    </span>

                </div>


                <div class="tracking-product">

                    <div class="tracking-product-image">

                        @if($coverUrl)

                            <img
                                src="{{ $coverUrl }}"
                                alt="{{ $order->book->title }}"
                                loading="lazy"
                            >

                        @else

                            <div class="tracking-no-cover">
                                <i class="bi bi-book"></i>
                            </div>

                        @endif

                    </div>


                    <div class="tracking-product-content">

                        @if($order->book)

                            <span class="tracking-product-label">
                                BOOK
                            </span>

                            <h3>
                                {{ $order->book->title }}
                            </h3>

                        @else

                            <span class="tracking-product-label">
                                PRODUCT
                            </span>

                            <h3>
                                Book no longer available
                            </h3>

                        @endif


                        <div class="tracking-product-meta">

                            <span>
                                <i class="bi bi-box"></i>
                                Quantity:
                                <strong>{{ $order->quantity }}</strong>
                            </span>

                            <span>
                                <i class="bi bi-tag"></i>
                                Unit Price:
                                <strong>
                                    ₼{{ number_format($order->book_price, 2) }}
                                </strong>
                            </span>

                        </div>

                    </div>

                </div>

            </article>


            {{-- =================================================
                 SHIPPING
            ================================================== --}}
            <article class="tracking-shipping-card">

                <div class="tracking-card-heading-row">

                    <div class="tracking-section-heading">

                        <span class="tracking-heading-icon">
                            <i class="bi bi-geo-alt"></i>
                        </span>

                        <div>

                            <span>
                                DELIVERY
                            </span>

                            <h2>
                                Shipping Information
                            </h2>

                        </div>

                    </div>

                    <span class="tracking-card-number">
                        02
                    </span>

                </div>


                <div class="tracking-shipping-grid">

                    <div class="tracking-shipping-item">

                        <span>
                            Full Name
                        </span>

                        <strong>
                            {{ $order->full_name }}
                        </strong>

                    </div>


                    <div class="tracking-shipping-item">

                        <span>
                            Phone
                        </span>

                        <strong>
                            {{ $order->phone }}
                        </strong>

                    </div>


                    <div class="tracking-shipping-item">

                        <span>
                            Country
                        </span>

                        <strong>
                            {{ $order->country }}
                        </strong>

                    </div>


                    <div class="tracking-shipping-item">

                        <span>
                            City
                        </span>

                        <strong>
                            {{ $order->city }}
                        </strong>

                    </div>


                    @if($order->postal_code)

                        <div class="tracking-shipping-item">

                            <span>
                                Postal Code
                            </span>

                            <strong>
                                {{ $order->postal_code }}
                            </strong>

                        </div>

                    @endif


                    <div class="tracking-shipping-item full-width">

                        <span>
                            Address
                        </span>

                        <strong>
                            {{ $order->address }}
                        </strong>

                    </div>

                </div>

            </article>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}
            <div class="tracking-actions">

                <a
                    href="{{ route('frontend.orders.show', $order->id) }}"
                    class="tracking-action secondary"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Order Details</span>
                </a>


                <a
                    href="{{ route('frontend.orders') }}"
                    class="tracking-action primary"
                >
                    <i class="bi bi-receipt"></i>
                    <span>My Orders</span>
                </a>

            </div>

        </div>

    </section>

</main>

@endsection