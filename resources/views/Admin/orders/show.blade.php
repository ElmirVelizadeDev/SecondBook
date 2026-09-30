@extends('layout.admin.master')

@section('title', 'Order Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/orders.css') }}">
@endpush

@section('content')

@php
    $customerName = trim(
        ($order->user->first_name ?? '') . ' ' .
        ($order->user->last_name ?? '')
    );
    $customerName = $customerName ?: ($order->user->name ?? $order->user->username ?? '—');

    $paymentStatus = strtolower($order->payment_status ?? 'pending');
    $orderStatus   = strtolower($order->order_status ?? 'pending');

    $knownPayments = ['paid', 'pending', 'failed', 'refunded'];
    $knownStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    $paymentClass = in_array($paymentStatus, $knownPayments) ? 'payment-' . $paymentStatus : 'payment-default';
    $statusClass  = in_array($orderStatus, $knownStatuses) ? 'order-status-' . $orderStatus : 'order-status-default';
@endphp

<div class="orders-show-page">

    {{-- HEADER --}}
    <div class="orders-edit-header">

        <div class="orders-edit-heading">
            <div class="orders-edit-eyebrow">
                <i class="bi bi-receipt"></i>
                Order Management
            </div>

            <h1>Order Details</h1>

            <p>View complete order information.</p>
        </div>

        <div class="orders-show-header-actions">
            <a href="{{ route('admin.orders.index') }}" class="orders-edit-back">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Orders</span>
            </a>

            <a href="{{ route('admin.orders.edit', $order->id) }}" class="orders-edit-submit">
                <i class="bi bi-pencil"></i>
                <span>Edit Order</span>
            </a>
        </div>

    </div>


    {{-- SUMMARY --}}
    <div class="orders-show-summary">

        <div class="orders-show-summary-item">
            <span>Order Number</span>
            <strong>{{ $order->order_number ?? '#' . $order->id }}</strong>
        </div>

        <div class="orders-show-summary-item">
            <span>Total</span>
            <strong>${{ number_format($order->total_price, 2) }}</strong>
        </div>

        <div class="orders-show-summary-item">
            <span>Payment</span>
            <em class="order-status-pill {{ $paymentClass }}">
                <i class="bi bi-circle-fill"></i>
                {{ ucfirst($paymentStatus) }}
            </em>
        </div>

        <div class="orders-show-summary-item">
            <span>Status</span>
            <em class="order-status-pill {{ $statusClass }}">
                <i class="bi bi-circle-fill"></i>
                {{ ucfirst($orderStatus) }}
            </em>
        </div>

    </div>


    {{-- CARDS --}}
    <div class="orders-show-grid">

        {{-- Order Information --}}
        <div class="orders-edit-card">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-cart-check"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Order Information</strong>
                    <span>Quantity, pricing and dates</span>
                </div>
            </div>

            <div class="orders-show-body">
                <div class="orders-show-row">
                    <span>Order Number</span>
                    <strong>{{ $order->order_number ?? '—' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Quantity</span>
                    <strong>{{ $order->quantity }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Book Price</span>
                    <strong>${{ number_format($order->book_price, 2) }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Total Price</span>
                    <strong>${{ number_format($order->total_price, 2) }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Created</span>
                    <strong>{{ $order->created_at->format('d M Y H:i') }}</strong>
                </div>
            </div>
        </div>


        {{-- Customer --}}
        <div class="orders-edit-card">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-person"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Customer</strong>
                    <span>Who placed this order</span>
                </div>
            </div>

            <div class="orders-show-body">
                <div class="orders-show-row">
                    <span>Name</span>
                    <strong>{{ $order->full_name ?: '—' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>User</span>
                    <strong>{{ $customerName }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Email</span>
                    <strong>{{ $order->user->email ?? '—' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Phone</span>
                    <strong>{{ $order->phone ?: '—' }}</strong>
                </div>
            </div>
        </div>


        {{-- Book --}}
        <div class="orders-edit-card">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-book"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Book</strong>
                    <span>Ordered item details</span>
                </div>
            </div>

            <div class="orders-show-body">
                <div class="orders-show-row">
                    <span>Title</span>
                    <strong>{{ $order->book->title ?? 'N/A' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Author</span>
                    <strong>{{ $order->book->author->name ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>


        {{-- Payment --}}
        <div class="orders-edit-card">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-credit-card"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Payment</strong>
                    <span>Method and payment state</span>
                </div>
            </div>

            <div class="orders-show-body">
                <div class="orders-show-row">
                    <span>Method</span>
                    <strong>{{ ucwords(str_replace('_', ' ', $order->payment_method ?? '—')) }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Status</span>
                    <em class="order-status-pill {{ $paymentClass }}">
                        <i class="bi bi-circle-fill"></i>
                        {{ ucfirst($paymentStatus) }}
                    </em>
                </div>
            </div>
        </div>


        {{-- Order Status --}}
        <div class="orders-edit-card">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-truck"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Status</strong>
                    <span>Delivery progress</span>
                </div>
            </div>

            <div class="orders-show-body">
                <div class="orders-show-row">
                    <span>Order Status</span>
                    <em class="order-status-pill {{ $statusClass }}">
                        <i class="bi bi-circle-fill"></i>
                        {{ ucfirst($orderStatus) }}
                    </em>
                </div>
            </div>
        </div>


        {{-- Shipping --}}
        <div class="orders-edit-card">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-geo-alt"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Shipping</strong>
                    <span>Delivery address</span>
                </div>
            </div>

            <div class="orders-show-body">
                <div class="orders-show-row">
                    <span>Country</span>
                    <strong>{{ $order->country ?: '—' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>City</span>
                    <strong>{{ $order->city ?: '—' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Postal Code</span>
                    <strong>{{ $order->postal_code ?: '—' }}</strong>
                </div>
                <div class="orders-show-row">
                    <span>Address</span>
                    <strong>{{ $order->address ?: '—' }}</strong>
                </div>
            </div>
        </div>


        {{-- Note --}}
        <div class="orders-edit-card orders-show-full">
            <div class="orders-edit-card-header">
                <div class="orders-edit-card-icon"><i class="bi bi-chat-left-text"></i></div>
                <div class="orders-edit-card-title">
                    <strong>Note</strong>
                    <span>Internal note about this order</span>
                </div>
            </div>

            <div class="orders-show-body">
                <p class="orders-show-note">
                    {{ $order->note ?: 'No note available.' }}
                </p>
            </div>
        </div>

    </div>

</div>

@endsection