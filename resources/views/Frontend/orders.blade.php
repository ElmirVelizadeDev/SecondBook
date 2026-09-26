@extends('Layout.Frontend.master')

@section('title', 'My Orders | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/orders.css') }}">
@endpush

@section('content')

<main class="sb-orders-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <section class="sb-orders-hero">
        <div class="container">

            <div class="sb-orders-breadcrumb">
                <a href="{{ route('frontend.home') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>

                <i class="bi bi-chevron-right"></i>

                <span class="is-current">Orders</span>
            </div>

            <div class="sb-orders-hero-grid">

                <div class="sb-orders-hero-content">

                    <span class="sb-orders-eyebrow">
                        <span class="sb-orders-eyebrow-icon">
                            <i class="bi bi-receipt"></i>
                        </span>
                        Purchase History
                    </span>

                    <h1>
                        Your orders,
                        <span>all in one place.</span>
                    </h1>

                    <p>
                        Keep track of your purchases, delivery progress,
                        payment details, and everything you've ordered.
                    </p>

                </div>

                <div class="sb-orders-hero-card">

                    <div class="sb-orders-hero-card-icon">
                        <i class="bi bi-bag-heart"></i>
                    </div>

                    <div class="sb-orders-hero-card-content">
                        <span>SecondBook</span>
                        <strong>My Purchases</strong>
                    </div>

                    <i class="bi bi-arrow-up-right sb-orders-hero-card-arrow"></i>

                </div>

            </div>

        </div>
    </section>


    {{-- =====================================================
         MAIN
    ====================================================== --}}
    <section class="sb-orders-section">
        <div class="container">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="sb-orders-alert sb-alert-success">

                    <span class="sb-alert-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>

                    <div class="sb-alert-content">
                        <strong>Order Updated</strong>
                        <span>{{ session('success') }}</span>
                    </div>

                </div>
            @endif

            @if(session('error'))
                <div class="sb-orders-alert sb-alert-error">

                    <span class="sb-alert-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </span>

                    <div class="sb-alert-content">
                        <strong>Something went wrong</strong>
                        <span>{{ session('error') }}</span>
                    </div>

                </div>
            @endif


            @if($orders->count())

                {{-- Section Heading --}}
                <div class="sb-orders-heading-row">

                    <div class="sb-orders-heading">

                        <span class="sb-orders-section-kicker">
                            <i class="bi bi-box-seam"></i>
                            Your Purchases
                        </span>

                        <h2>Your Orders</h2>

                        <p>
                            Review your recent purchases and track every order.
                        </p>

                    </div>

                    <a
                        href="{{ route('frontend.books') }}"
                        class="sb-orders-continue-btn"
                    >
                        <i class="bi bi-book"></i>
                        <span>Continue Shopping</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>


                {{-- Orders --}}
                <div class="sb-orders-list">

                    @foreach($orders as $order)

                        @php

                            $statusClass = match($order->order_status) {
                                'pending' => 'status-pending',
                                'processing' => 'status-processing',
                                'shipped' => 'status-shipped',
                                'delivered' => 'status-delivered',
                                'cancelled' => 'status-cancelled',
                                default => 'status-pending',
                            };

                            $paymentMethod = $order->payment_method
                                ? ucwords(
                                    str_replace(
                                        '\_',
                                        ' ',
                                        $order->payment_method
                                    )
                                )
                                : 'Not selected';

                            $paymentStatus = $order->payment_status
                                ? ucfirst($order->payment_status)
                                : 'Pending';

                            $canCancelOrder =
                                $cancelOrderPeriod > 0 &&
                                in_array(
                                    $order->order_status,
                                    ['pending', 'processing']
                                ) &&
                                $order->created_at
                                    ->copy()
                                    ->addDays($cancelOrderPeriod)
                                    ->isFuture();

                        @endphp


                        <article class="sb-order-card">

                            {{-- Order Header --}}
                            <div class="sb-order-card-header">

                                <div class="sb-order-card-heading">

                                    <div class="sb-order-card-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>

                                    <div>
                                        <span class="sb-order-card-kicker">
                                            Order Number
                                        </span>

                                        <h2>
                                            #{{ $order->order_number }}
                                        </h2>
                                    </div>

                                </div>


                                <div class="sb-order-date">

                                    <span class="sb-order-date-icon">
                                        <i class="bi bi-calendar3"></i>
                                    </span>

                                    <div>
                                        <span>Placed on</span>

                                        <strong>
                                            {{ $order->created_at->format('M d, Y') }}
                                        </strong>
                                    </div>

                                </div>

                            </div>


                            {{-- Order Body --}}
                            <div class="sb-order-card-body">

                                {{-- Book --}}
                                <div class="sb-order-book">

                                    <div class="sb-order-book-image">

                                        @if($order->book && !empty($order->book->cover))

                                            @php

                                                $cover = $order->book->cover;

                                                $coverUrl = filter_var(
                                                    $cover,
                                                    FILTER_VALIDATE_URL
                                                )
                                                    ? $cover
                                                    : asset(
                                                        'storage/' .
                                                        ltrim($cover, '/')
                                                    );

                                            @endphp

                                            <img
                                                src="{{ $coverUrl }}"
                                                alt="{{ $order->book->title }}"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            >

                                            <div
                                                class="sb-order-book-placeholder"
                                                style="display: none;"
                                            >
                                                <i class="bi bi-book"></i>
                                            </div>

                                        @else

                                            <div class="sb-order-book-placeholder">
                                                <i class="bi bi-book"></i>
                                            </div>

                                        @endif

                                    </div>


                                    <div class="sb-order-book-content">

                                        <span class="sb-order-book-kicker">
                                            Book
                                        </span>

                                        <h3>
                                            {{ $order->book->title ?? 'Book unavailable' }}
                                        </h3>

                                        <div class="sb-order-book-meta">

                                            <span>
                                                <i class="bi bi-box-seam"></i>
                                                {{ $order->quantity }}
                                                {{ $order->quantity == 1 ? 'copy' : 'copies' }}
                                            </span>

                                            <span class="sb-order-meta-divider"></span>

                                            <span>
                                                ${{ number_format($order->book_price, 2) }}
                                                each
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                {{-- Total --}}
                                <div class="sb-order-info-card">

                                    <span>Total</span>

                                    <strong>
                                        ${{ number_format($order->total_price, 2) }}
                                    </strong>

                                </div>


                                {{-- Payment --}}
                                <div class="sb-order-info-card">

                                    <span>Payment</span>

                                    <strong class="sb-order-payment-method">
                                        {{ $paymentMethod }}
                                    </strong>

                                </div>


                                {{-- Status --}}
                                <div class="sb-order-info-card sb-order-status-info">

                                    <span>Order Status</span>

                                    <span class="sb-order-status-badge {{ $statusClass }}">
                                        <span class="sb-order-status-dot"></span>
                                        {{ ucfirst($order->order_status) }}
                                    </span>

                                </div>

                            </div>


                            {{-- Footer --}}
                            <div class="sb-order-card-footer">

                                <div class="sb-order-payment-status">

                                    <div class="sb-order-payment-icon">
                                        <i class="bi bi-credit-card-2-front"></i>
                                    </div>

                                    <div class="sb-order-payment-content">

                                        <span>Payment Status</span>

                                        <strong>
                                            {{ $paymentStatus }}
                                        </strong>

                                    </div>

                                </div>


                                <div class="sb-order-actions">

                                    <a
                                        href="{{ route('frontend.orders.show', $order->id) }}"
                                        class="sb-order-details-btn"
                                    >
                                        <span>View Details</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>


                                    @if(
                                        $order->order_status === 'delivered' &&
                                        $order->book &&
                                        !in_array(
                                            $order->book_id,
                                            $reviewedBookIds
                                        )
                                    )

                                        <a
                                            href="{{ route('frontend.reviews.create', $order->id) }}"
                                            class="sb-order-review-btn"
                                        >
                                            <i class="bi bi-star"></i>
                                            <span>Write Review</span>
                                        </a>

                                    @endif


                                    @if($canCancelOrder)

                                        <form
                                            action="{{ route('frontend.orders.cancel', $order->id) }}"
                                            method="POST"
                                            class="sb-cancel-order-form"
                                        >

                                            @csrf

                                            <button
                                                type="button"
                                                class="sb-order-cancel-btn js-cancel-order"
                                                data-order="{{ $order->order_number }}"
                                            >
                                                <i class="bi bi-x-circle"></i>
                                                <span>Cancel Order</span>
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>


                {{-- Pagination --}}
                @if($orders->hasPages())

                    <div class="sb-orders-pagination">
                        {{ $orders->links() }}
                    </div>

                @endif


            @else

                {{-- Empty --}}
                <div class="sb-orders-empty">

                    <div class="sb-orders-empty-icon">
                        <i class="bi bi-bag-x"></i>
                    </div>

                    <span class="sb-orders-empty-eyebrow">
                        Your shopping journey starts here
                    </span>

                    <h2>No Orders Yet</h2>

                    <p>
                        You haven't placed any orders yet.
                        Explore our collection and find your next book.
                    </p>

                    <a
                        href="{{ route('frontend.books') }}"
                        class="sb-orders-shop-btn"
                    >
                        <i class="bi bi-book"></i>
                        <span>Browse Books</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            @endif

        </div>
    </section>

</main>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('.js-cancel-order')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const form = this.closest('.sb-cancel-order-form');
                const orderNumber = this.dataset.order;

                Swal.fire({
                    title: 'Cancel Order?',
                    text:
                        `Are you sure you want to cancel order #${orderNumber}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel Order',
                    cancelButtonText: 'Keep Order',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'swal-confirm-btn',
                        cancelButton: 'swal-cancel-btn'
                    }
                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });

});
</script>
@endpush