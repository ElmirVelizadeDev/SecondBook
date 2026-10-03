@extends('Layout.Seller.master')

@section('title', 'Dashboard')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/dashboard.css') }}">
@endpush

@section('content')

<div class="seller-dashboard-page">

    {{-- =========================================================
        PAGE HEADER
        ========================================================= --}}
    <div class="seller-dashboard-header">

        <div class="seller-dashboard-heading">

            <div class="seller-dashboard-heading-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>

            <div>
                <h1>Seller Dashboard</h1>

                <p>
                    Manage your store, books, orders and sales from one place.
                </p>
            </div>

        </div>

        <div class="seller-dashboard-header-actions">

            <a
                href="{{ route('seller.books.create') }}"
                class="seller-dashboard-primary-btn"
            >
                <i class="bi bi-plus-lg"></i>
                Add New Book
            </a>

        </div>

    </div>


    {{-- =========================================================
        OVERVIEW STATISTICS
        ========================================================= --}}
    <div class="row g-4 seller-dashboard-stats">

        {{-- Total Books --}}
        <div class="col-xl-3 col-md-6">

            <div class="seller-dashboard-stat-card">

                <div class="seller-dashboard-stat-top">

                    <div class="seller-dashboard-stat-icon books">
                        <i class="bi bi-book"></i>
                    </div>

                    <span class="seller-dashboard-stat-label">
                        Total Books
                    </span>

                </div>

                <div class="seller-dashboard-stat-value">
                    {{ number_format($totalBooks) }}
                </div>

                <div class="seller-dashboard-stat-footer">
                    <span>
                        <i class="bi bi-collection"></i>
                        Your inventory
                    </span>

                    <a href="{{ route('seller.books.index') }}">
                        View
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>

        </div>


        {{-- Pending Books --}}
        <div class="col-xl-3 col-md-6">

            <div class="seller-dashboard-stat-card">

                <div class="seller-dashboard-stat-top">

                    <div class="seller-dashboard-stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <span class="seller-dashboard-stat-label">
                        Pending Books
                    </span>

                </div>

                <div class="seller-dashboard-stat-value">
                    {{ number_format($pendingBooks) }}
                </div>

                <div class="seller-dashboard-stat-footer">

                    <span>
                        <i class="bi bi-clock"></i>
                        Awaiting review
                    </span>

                    <a href="{{ route('seller.books.index') }}">
                        Manage
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>


        {{-- Total Orders --}}
        <div class="col-xl-3 col-md-6">

            <div class="seller-dashboard-stat-card">

                <div class="seller-dashboard-stat-top">

                    <div class="seller-dashboard-stat-icon orders">
                        <i class="bi bi-bag"></i>
                    </div>

                    <span class="seller-dashboard-stat-label">
                        Total Orders
                    </span>

                </div>

                <div class="seller-dashboard-stat-value">
                    {{ number_format($totalOrders) }}
                </div>

                <div class="seller-dashboard-stat-footer">

                    <span>
                        <i class="bi bi-receipt"></i>
                        All orders
                    </span>

                    <a href="{{ route('seller.orders.index') }}">
                        View
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>


        {{-- Total Sales --}}
        <div class="col-xl-3 col-md-6">

            <div class="seller-dashboard-stat-card sales">

                <div class="seller-dashboard-stat-top">

                    <div class="seller-dashboard-stat-icon sales">
                        <i class="bi bi-currency-dollar"></i>
                    </div>

                    <span class="seller-dashboard-stat-label">
                        Total Sales
                    </span>

                </div>

                <div class="seller-dashboard-stat-value">
                    ${{ number_format($totalSales, 2) }}
                </div>

                <div class="seller-dashboard-stat-footer">

                    <span>
                        <i class="bi bi-graph-up-arrow"></i>
                        Revenue
                    </span>

                    <a href="{{ route('seller.orders.index') }}">
                        Details
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MAIN DASHBOARD GRID
        ========================================================= --}}
    <div class="row g-4 seller-dashboard-main-grid">


        {{-- =====================================================
            RECENT ORDERS
            ===================================================== --}}
        <div class="col-xl-8">

            <div class="seller-dashboard-panel">

                <div class="seller-dashboard-panel-header">

                    <div class="seller-dashboard-panel-title">

                        <div class="seller-dashboard-panel-icon">
                            <i class="bi bi-bag-check"></i>
                        </div>

                        <div>
                            <h5>Recent Orders</h5>

                            <p>
                                Latest orders for your books
                            </p>
                        </div>

                    </div>

                    <a
                        href="{{ route('seller.orders.index') }}"
                        class="seller-dashboard-view-all"
                    >
                        View All
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>


                @if($recentOrders->count())

                    <div class="seller-dashboard-orders-wrapper">

                        <div class="table-responsive">

                            <table class="table seller-dashboard-orders-table align-middle mb-0">

                                <thead>

                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Book</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($recentOrders as $order)

                                        <tr>

                                            {{-- Order --}}
                                            <td>

                                                <div class="seller-dashboard-order-number">

                                                    <span class="seller-dashboard-order-icon">
                                                        <i class="bi bi-receipt"></i>
                                                    </span>

                                                    <strong>
                                                        {{ $order->order_number }}
                                                    </strong>

                                                </div>

                                            </td>


                                            {{-- Customer --}}
                                            <td>

                                                <div class="seller-dashboard-customer">

                                                    <div class="seller-dashboard-customer-avatar">

                                                        {{
                                                            strtoupper(
                                                                substr(
                                                                    $order->user?->name ?: 'U',
                                                                    0,
                                                                    1
                                                                )
                                                            )
                                                        }}

                                                    </div>

                                                    <span>
                                                        {{ $order->user?->name ?? 'Unknown' }}
                                                    </span>

                                                </div>

                                            </td>


                                            {{-- Book --}}
                                            <td>

                                                <div class="seller-dashboard-book">

                                                    <span>
                                                        {{ $order->book?->title ?? 'Deleted Book' }}
                                                    </span>

                                                </div>

                                            </td>


                                            {{-- Quantity --}}
                                            <td>

                                                <span class="seller-dashboard-quantity">
                                                    {{ $order->quantity }}
                                                </span>

                                            </td>


                                            {{-- Total --}}
                                            <td>

                                                <strong class="seller-dashboard-order-total">
                                                    ${{ number_format($order->total_price, 2) }}
                                                </strong>

                                            </td>


                                            {{-- Status --}}
                                            <td>

                                                @php
                                                    $status = strtolower($order->order_status ?? 'pending');

                                                    $statusClass = match ($status) {
                                                        'delivered',
                                                        'completed' => 'success',

                                                        'processing',
                                                        'shipped' => 'info',

                                                        'cancelled',
                                                        'canceled',
                                                        'refunded' => 'danger',

                                                        default => 'warning',
                                                    };
                                                @endphp

                                                <span class="seller-dashboard-status {{ $statusClass }}">

                                                    <span class="seller-dashboard-status-dot"></span>

                                                    {{ ucwords(str_replace('_', ' ', $status)) }}

                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @else

                    <div class="seller-dashboard-empty">

                        <div class="seller-dashboard-empty-icon">
                            <i class="bi bi-bag-x"></i>
                        </div>

                        <h6>No Orders Yet</h6>

                        <p>
                            Orders for your books will appear here.
                        </p>

                        <a
                            href="{{ route('seller.books.create') }}"
                            class="seller-dashboard-empty-btn"
                        >
                            <i class="bi bi-plus-lg"></i>
                            Add Your First Book
                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            QUICK ACTIONS
            ===================================================== --}}
        <div class="col-xl-4">

            <div class="seller-dashboard-panel seller-dashboard-actions-panel">

                <div class="seller-dashboard-panel-header">

                    <div class="seller-dashboard-panel-title">

                        <div class="seller-dashboard-panel-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </div>

                        <div>
                            <h5>Quick Actions</h5>

                            <p>
                                Frequently used actions
                            </p>
                        </div>

                    </div>

                </div>


                <div class="seller-dashboard-actions">

                    <a
                        href="{{ route('seller.books.create') }}"
                        class="seller-dashboard-action primary"
                    >

                        <span class="seller-dashboard-action-icon">
                            <i class="bi bi-plus-lg"></i>
                        </span>

                        <span class="seller-dashboard-action-content">
                            <strong>Add New Book</strong>
                            <small>List a new book in your store</small>
                        </span>

                        <i class="bi bi-chevron-right seller-dashboard-action-arrow"></i>

                    </a>


                    <a
                        href="{{ route('seller.books.index') }}"
                        class="seller-dashboard-action"
                    >

                        <span class="seller-dashboard-action-icon">
                            <i class="bi bi-book"></i>
                        </span>

                        <span class="seller-dashboard-action-content">
                            <strong>Manage Books</strong>
                            <small>View and manage your inventory</small>
                        </span>

                        <i class="bi bi-chevron-right seller-dashboard-action-arrow"></i>

                    </a>


                    <a
                        href="{{ route('seller.orders.index') }}"
                        class="seller-dashboard-action"
                    >

                        <span class="seller-dashboard-action-icon">
                            <i class="bi bi-bag"></i>
                        </span>

                        <span class="seller-dashboard-action-content">
                            <strong>View Orders</strong>
                            <small>Manage your customer orders</small>
                        </span>

                        <i class="bi bi-chevron-right seller-dashboard-action-arrow"></i>

                    </a>


                    <a
                        href="{{ route('seller.messages.index') }}"
                        class="seller-dashboard-action"
                    >

                        <span class="seller-dashboard-action-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </span>

                        <span class="seller-dashboard-action-content">
                            <strong>Messages</strong>
                            <small>Communicate with customers</small>
                        </span>

                        <i class="bi bi-chevron-right seller-dashboard-action-arrow"></i>

                    </a>


                    <a
                        href="{{ route('seller.store') }}"
                        class="seller-dashboard-action"
                    >

                        <span class="seller-dashboard-action-icon">
                            <i class="bi bi-shop"></i>
                        </span>

                        <span class="seller-dashboard-action-content">
                            <strong>Store Settings</strong>
                            <small>Manage your store information</small>
                        </span>

                        <i class="bi bi-chevron-right seller-dashboard-action-arrow"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        LOWER DASHBOARD
        ========================================================= --}}
    <div class="row g-4 seller-dashboard-lower-grid">


        {{-- =====================================================
            BUSINESS SNAPSHOT
            ===================================================== --}}
        <div class="col-xl-4">

            <div class="seller-dashboard-panel seller-dashboard-snapshot-panel">

                <div class="seller-dashboard-panel-header">

                    <div class="seller-dashboard-panel-title">

                        <div class="seller-dashboard-panel-icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>

                        <div>
                            <h5>Business Snapshot</h5>

                            <p>
                                Current store overview
                            </p>
                        </div>

                    </div>

                </div>


                <div class="seller-dashboard-snapshot-list">

                    <div class="seller-dashboard-snapshot-item">

                        <div class="seller-dashboard-snapshot-left">

                            <span class="seller-dashboard-snapshot-icon books">
                                <i class="bi bi-book"></i>
                            </span>

                            <span>
                                Total Books
                            </span>

                        </div>

                        <strong>
                            {{ number_format($totalBooks) }}
                        </strong>

                    </div>


                    <div class="seller-dashboard-snapshot-item">

                        <div class="seller-dashboard-snapshot-left">

                            <span class="seller-dashboard-snapshot-icon pending">
                                <i class="bi bi-hourglass-split"></i>
                            </span>

                            <span>
                                Pending Review
                            </span>

                        </div>

                        <strong>
                            {{ number_format($pendingBooks) }}
                        </strong>

                    </div>


                    <div class="seller-dashboard-snapshot-item">

                        <div class="seller-dashboard-snapshot-left">

                            <span class="seller-dashboard-snapshot-icon orders">
                                <i class="bi bi-bag"></i>
                            </span>

                            <span>
                                Total Orders
                            </span>

                        </div>

                        <strong>
                            {{ number_format($totalOrders) }}
                        </strong>

                    </div>


                    <div class="seller-dashboard-snapshot-item">

                        <div class="seller-dashboard-snapshot-left">

                            <span class="seller-dashboard-snapshot-icon sales">
                                <i class="bi bi-currency-dollar"></i>
                            </span>

                            <span>
                                Total Revenue
                            </span>

                        </div>

                        <strong>
                            ${{ number_format($totalSales, 2) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SELLER PERFORMANCE
            ===================================================== --}}
        <div class="col-xl-4">

            <div class="seller-dashboard-panel seller-dashboard-performance-panel">

                <div class="seller-dashboard-panel-header">

                    <div class="seller-dashboard-panel-title">

                        <div class="seller-dashboard-panel-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div>
                            <h5>Store Performance</h5>

                            <p>
                                Your current marketplace activity
                            </p>
                        </div>

                    </div>

                </div>


                <div class="seller-dashboard-performance">

                    <div class="seller-dashboard-performance-card">

                        <div class="seller-dashboard-performance-icon">
                            <i class="bi bi-book-half"></i>
                        </div>

                        <div>
                            <span>Books Listed</span>
                            <strong>{{ number_format($totalBooks) }}</strong>
                        </div>

                    </div>


                    <div class="seller-dashboard-performance-card">

                        <div class="seller-dashboard-performance-icon">
                            <i class="bi bi-cart-check"></i>
                        </div>

                        <div>
                            <span>Orders Received</span>
                            <strong>{{ number_format($totalOrders) }}</strong>
                        </div>

                    </div>


                    <div class="seller-dashboard-performance-card">

                        <div class="seller-dashboard-performance-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div>
                            <span>Total Revenue</span>
                            <strong>${{ number_format($totalSales, 2) }}</strong>
                        </div>

                    </div>

                </div>


                <div class="seller-dashboard-performance-note">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Keep your inventory updated and respond to customer
                        messages regularly.
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SELLER GUIDE
            ===================================================== --}}
        <div class="col-xl-4">

            <div class="seller-dashboard-panel seller-dashboard-guide-panel">

                <div class="seller-dashboard-guide">

                    <div class="seller-dashboard-guide-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div class="seller-dashboard-guide-content">

                        <span class="seller-dashboard-guide-label">
                            Seller Tip
                        </span>

                        <h5>
                            Keep your store active
                        </h5>

                        <p>
                            Add clear book information, maintain accurate
                            stock levels and respond to customer questions
                            quickly.
                        </p>

                        <a href="{{ route('seller.books.create') }}">
                            Add a Book
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DASHBOARD FOOTER
        ========================================================= --}}
    <div class="seller-dashboard-footer">

        <div>

            <span class="seller-dashboard-footer-icon">
                <i class="bi bi-shield-check"></i>
            </span>

            <div>
                <strong>Your Seller Account</strong>
                <span>Manage your marketplace activity from your dashboard.</span>
            </div>

        </div>

        <a href="{{ route('seller.store') }}">
            Manage Store
            <i class="bi bi-arrow-right"></i>
        </a>

    </div>

</div>

@endsection