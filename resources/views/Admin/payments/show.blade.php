@extends('layout.admin.master')

@section('title', 'Payment Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/payments.css') }}">
@endpush

@section('content')

@php
    $paymentStatus = strtolower($payment->payment_status ?? 'pending');

    $knownStatuses = ['paid', 'pending', 'failed', 'refunded'];

    $statusClass = in_array($paymentStatus, $knownStatuses)
        ? 'payment-status-' . $paymentStatus
        : 'payment-status-default';

    $customerName = trim(
        ($payment->order->user->first_name ?? '') . ' ' .
        ($payment->order->user->last_name ?? '')
    );

    $customerName = $customerName
        ?: ($payment->order->user->name
        ?? $payment->order->user->username
        ?? '—');

    $paymentMethod = ucwords(
        str_replace('_', ' ', $payment->payment_method ?? '—')
    );
@endphp

<div class="orders-show-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="orders-edit-header">

        <div class="orders-edit-heading">

            <div class="orders-edit-eyebrow">
                <i class="bi bi-credit-card"></i>
                Payment Management
            </div>

            <h1>Payment Details</h1>

            <p>View complete payment information.</p>

        </div>

        <div class="orders-show-header-actions">

            <a href="{{ route('admin.payments.index') }}"
               class="orders-edit-back">

                <i class="bi bi-arrow-left"></i>
                <span>Back to Payments</span>

            </a>

            <a href="{{ route('admin.payments.edit', $payment->id) }}"
               class="orders-edit-submit">

                <i class="bi bi-pencil"></i>
                <span>Edit Payment</span>

            </a>

        </div>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}
    <div class="orders-show-summary">

        <div class="orders-show-summary-item">

            <span>Payment ID</span>

            <strong>
                #{{ $payment->id }}
            </strong>

        </div>


        <div class="orders-show-summary-item">

            <span>Amount</span>

            <strong>
                ${{ number_format($payment->amount, 2) }}
            </strong>

        </div>


        <div class="orders-show-summary-item">

            <span>Method</span>

            <strong>
                {{ $paymentMethod }}
            </strong>

        </div>


        <div class="orders-show-summary-item">

            <span>Status</span>

            <em class="payment-status-pill {{ $statusClass }}">

                <i class="bi bi-circle-fill"></i>

                {{ ucfirst($paymentStatus) }}

            </em>

        </div>

    </div>


    {{-- =====================================================
         DETAILS GRID
    ====================================================== --}}
    <div class="orders-show-grid">


        {{-- =================================================
             PAYMENT INFORMATION
        ================================================== --}}
        <div class="orders-edit-card">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-credit-card"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Payment Information</strong>

                    <span>Transaction and payment details</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Payment ID</span>

                    <strong>
                        #{{ $payment->id }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Transaction ID</span>

                    <strong>
                        {{ $payment->transaction_id ?: '—' }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Amount</span>

                    <strong>
                        ${{ number_format($payment->amount, 2) }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Method</span>

                    <strong>
                        {{ $paymentMethod }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Status</span>

                    <em class="payment-status-pill {{ $statusClass }}">

                        <i class="bi bi-circle-fill"></i>

                        {{ ucfirst($paymentStatus) }}

                    </em>

                </div>


                <div class="orders-show-row">

                    <span>Created</span>

                    <strong>
                        {{ $payment->created_at?->format('d M Y H:i') ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             CUSTOMER
        ================================================== --}}
        <div class="orders-edit-card">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-person"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Customer</strong>

                    <span>Payment customer information</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Name</span>

                    <strong>
                        {{ $customerName }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Email</span>

                    <strong>
                        {{ $payment->order->user->email ?? '—' }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Phone</span>

                    <strong>
                        {{ $payment->order->phone ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             ORDER
        ================================================== --}}
        <div class="orders-edit-card">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-cart-check"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Order</strong>

                    <span>Related order information</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Order Number</span>

                    <strong>
                        {{ $payment->order->order_number ?? '—' }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Order Status</span>

                    <strong>
                        {{ ucfirst($payment->order->order_status ?? '—') }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Order Total</span>

                    <strong>
                        ${{ number_format($payment->order->total_price ?? 0, 2) }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             BOOK
        ================================================== --}}
        <div class="orders-edit-card">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-book"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Book</strong>

                    <span>Related ordered book</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Title</span>

                    <strong>
                        {{ $payment->order->book->title ?? '—' }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Author</span>

                    <strong>
                        {{ $payment->order->book->author->name ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             PAYMENT STATUS
        ================================================== --}}
        <div class="orders-edit-card">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Payment Status</strong>

                    <span>Current payment state</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Status</span>

                    <em class="payment-status-pill {{ $statusClass }}">

                        <i class="bi bi-circle-fill"></i>

                        {{ ucfirst($paymentStatus) }}

                    </em>

                </div>


                <div class="orders-show-row">

                    <span>Method</span>

                    <strong>
                        {{ $paymentMethod }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             PAYMENT DATE
        ================================================== --}}
        <div class="orders-edit-card">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Payment Date</strong>

                    <span>Payment creation information</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Date</span>

                    <strong>
                        {{ $payment->created_at?->format('d M Y') ?? '—' }}
                    </strong>

                </div>


                <div class="orders-show-row">

                    <span>Time</span>

                    <strong>
                        {{ $payment->created_at?->format('H:i') ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =================================================
             TRANSACTION
        ================================================== --}}
        <div class="orders-edit-card orders-show-full">

            <div class="orders-edit-card-header">

                <div class="orders-edit-card-icon">
                    <i class="bi bi-receipt"></i>
                </div>

                <div class="orders-edit-card-title">

                    <strong>Transaction</strong>

                    <span>Transaction reference information</span>

                </div>

            </div>


            <div class="orders-show-body">

                <div class="orders-show-row">

                    <span>Transaction ID</span>

                    <strong>
                        {{ $payment->transaction_id ?: 'No transaction ID available.' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection