@extends('layout.admin.master')

@section('title', 'Refund Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/refunds.css') }}">
@endpush

@section('content')

<div class="refunds-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}
    <div class="refund-hero">

        <div class="refund-hero-content">

            <span class="refund-hero-badge">
                <i class="bi bi-receipt"></i>
                Payments Recovery
            </span>

            <h1>Refund Details</h1>

            <p>
                Review refund information, order details and processing status.
            </p>

        </div>

        <div class="refund-hero-actions">

            <a
                href="{{ route('admin.refunds.index') }}"
                class="refund-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back to Refunds</span>
            </a>

            @if($refund->status !== 'processed')

                <a
                    href="{{ route('admin.refunds.edit', $refund) }}"
                    class="refund-add-btn"
                >
                    <i class="bi bi-pencil"></i>
                    <span>Edit Refund</span>
                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}
    <div class="refund-summary">

        <div class="refund-summary-item">

            <span>Refund Number</span>

            <strong>
                {{ $refund->refund_number }}
            </strong>

        </div>


        <div class="refund-summary-item">

            <span>Amount</span>

            <strong class="refund-detail-amount">
                ${{ number_format($refund->amount, 2) }}
            </strong>

        </div>


        <div class="refund-summary-item">

            <span>Status</span>

            <span class="refund-status status-{{ $refund->status }}">
                <i class="bi bi-circle-fill"></i>
                {{ ucfirst($refund->status) }}
            </span>

        </div>


        <div class="refund-summary-item">

            <span>Requested</span>

            <strong>
                {{ $refund->requested_at?->format('d M Y H:i') ?? '—' }}
            </strong>

        </div>

    </div>


    {{-- =========================================================
         MAIN GRID
    ========================================================== --}}
    <div class="refund-show-grid">

        {{-- =====================================================
             LEFT COLUMN
        ====================================================== --}}
        <div class="refund-show-main">


            {{-- =================================================
                 REFUND INFORMATION
            ================================================== --}}
            <div class="refund-show-card">

                <div class="refund-show-header">

                    <div>

                        <span class="eyebrow">
                            REFUND
                        </span>

                        <h5>
                            Refund Information
                        </h5>

                        <p>
                            Complete information about this refund request.
                        </p>

                    </div>

                    <span class="refund-status status-{{ $refund->status }}">
                        <i class="bi bi-circle-fill"></i>
                        {{ ucfirst($refund->status) }}
                    </span>

                </div>


                <div class="refund-details">

                    <div class="refund-detail">

                        <span class="refund-detail-label">
                            Refund Number
                        </span>

                        <strong>
                            {{ $refund->refund_number }}
                        </strong>

                    </div>


                    <div class="refund-detail">

                        <span class="refund-detail-label">
                            Refund Amount
                        </span>

                        <strong class="refund-detail-amount">
                            ${{ number_format($refund->amount, 2) }}
                        </strong>

                    </div>


                    <div class="refund-detail">

                        <span class="refund-detail-label">
                            Status
                        </span>

                        <span class="refund-status status-{{ $refund->status }}">
                            <i class="bi bi-circle-fill"></i>
                            {{ ucfirst($refund->status) }}
                        </span>

                    </div>


                    <div class="refund-detail">

                        <span class="refund-detail-label">
                            Requested At
                        </span>

                        <strong>
                            {{ $refund->requested_at?->format('d M Y H:i') ?? '—' }}
                        </strong>

                    </div>


                    <div class="refund-detail">

                        <span class="refund-detail-label">
                            Processed At
                        </span>

                        <strong>
                            {{ $refund->processed_at?->format('d M Y H:i') ?? '—' }}
                        </strong>

                    </div>


                    <div class="refund-detail">

                        <span class="refund-detail-label">
                            Processed By
                        </span>

                        <strong>
                            {{ $refund->processor?->name ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 REFUND REASON
            ================================================== --}}
            <div class="refund-show-card">

                <div class="refund-show-header">

                    <div>

                        <span class="eyebrow">
                            REQUEST
                        </span>

                        <h5>
                            Refund Reason
                        </h5>

                        <p>
                            Reason and additional information provided for this refund.
                        </p>

                    </div>

                </div>


                <div class="refund-show-body">

                    <h6 class="refund-show-heading">

                        <span class="refund-show-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </span>

                        Reason

                    </h6>


                    <div class="refund-content-box">
                        {{ $refund->reason ?: 'No reason provided.' }}
                    </div>


                    @if($refund->note)

                        <div class="refund-note">

                            <strong>
                                <i class="bi bi-sticky"></i>
                                Additional Note
                            </strong>

                            <p>
                                {{ $refund->note }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 ORDER INFORMATION
            ================================================== --}}
            <div class="refund-show-card">

                <div class="refund-show-header">

                    <div>

                        <span class="eyebrow">
                            ORDER
                        </span>

                        <h5>
                            Order Information
                        </h5>

                        <p>
                            Order connected to this refund.
                        </p>

                    </div>

                </div>


                <div class="refund-show-body">

                    @if($refund->order)

                        <div class="refund-show-order">

                            <div class="refund-show-order-main">

                                <div class="refund-order-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>

                                <div>

                                    <span>
                                        Order Number
                                    </span>

                                    <strong>
                                        #{{ $refund->order->order_number }}
                                    </strong>

                                </div>

                            </div>


                            <div class="refund-show-order-meta">

                                <div>

                                    <span>
                                        Order Total
                                    </span>

                                    <strong>
                                        ${{ number_format($refund->order->total_price, 2) }}
                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Order Status
                                    </span>

                                    <strong>
                                        {{ ucfirst(str_replace('_', ' ', $refund->order->order_status ?? '—')) }}
                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Payment Status
                                    </span>

                                    <strong>
                                        {{ ucfirst(str_replace('_', ' ', $refund->order->payment_status ?? '—')) }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="refund-empty">

                            <div class="refund-empty-icon">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>

                            <strong>
                                Order Information Unavailable
                            </strong>

                            <span>
                                No order is currently linked to this refund.
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT COLUMN
        ====================================================== --}}
        <div class="refund-show-sidebar">


            {{-- =================================================
                 CUSTOMER
            ================================================== --}}
            <div class="refund-show-card">

                <div class="refund-show-header">

                    <div>

                        <span class="eyebrow">
                            CUSTOMER
                        </span>

                        <h5>
                            Customer
                        </h5>

                        <p>
                            Refund requester.
                        </p>

                    </div>

                </div>


                <div class="refund-show-body">

                    @if($refund->user)

                        <div class="refund-customer-profile">

                            <div class="refund-customer-profile-avatar">

                                {{ strtoupper(substr($refund->user->name ?? 'U', 0, 1)) }}

                            </div>


                            <div class="refund-customer-details">

                                <strong>
                                    {{ $refund->user->name }}
                                </strong>

                                <span>
                                    {{ $refund->user->email }}
                                </span>

                            </div>

                        </div>


                        <div class="refund-details">

                            <div class="refund-detail">

                                <span class="refund-detail-label">
                                    Username
                                </span>

                                <strong>
                                    {{ $refund->user->username ?? '—' }}
                                </strong>

                            </div>


                            <div class="refund-detail">

                                <span class="refund-detail-label">
                                    Phone
                                </span>

                                <strong>
                                    {{ $refund->user->phone ?? '—' }}
                                </strong>

                            </div>

                        </div>

                    @else

                        <div class="refund-empty">

                            <div class="refund-empty-icon">
                                <i class="bi bi-person-x"></i>
                            </div>

                            <strong>
                                Customer Unavailable
                            </strong>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 PAYMENT
            ================================================== --}}
            <div class="refund-show-card">

                <div class="refund-show-header">

                    <div>

                        <span class="eyebrow">
                            PAYMENT
                        </span>

                        <h5>
                            Payment
                        </h5>

                        <p>
                            Payment information connected to the refund.
                        </p>

                    </div>

                </div>


                <div class="refund-show-body">

                    @if($refund->payment)

                        <div class="refund-payment-info">

                            <div>

                                <span>
                                    Transaction ID
                                </span>

                                <strong>
                                    {{ $refund->payment->transaction_id ?? '—' }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Payment Amount
                                </span>

                                <strong>
                                    ${{ number_format($refund->payment->amount, 2) }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Method
                                </span>

                                <strong>
                                    {{ ucfirst(str_replace('_', ' ', $refund->payment->payment_method ?? '—')) }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Status
                                </span>

                                <strong>
                                    {{ ucfirst(str_replace('_', ' ', $refund->payment->payment_status ?? '—')) }}
                                </strong>

                            </div>

                        </div>

                    @else

                        <div class="refund-empty">

                            <div class="refund-empty-icon">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>

                            <strong>
                                No Payment Record
                            </strong>

                            <span>
                                No payment record is linked to this refund.
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}
            <div class="refund-show-card">

                <div class="refund-show-header">

                    <div>

                        <span class="eyebrow">
                            MANAGEMENT
                        </span>

                        <h5>
                            Actions
                        </h5>

                        <p>
                            Manage this refund.
                        </p>

                    </div>

                </div>


                <div class="refund-show-body">

                    <div class="refund-show-actions">


                        {{-- PENDING --}}
                        @if($refund->status === 'pending')

                            <form
                                method="POST"
                                action="{{ route('admin.refunds.status', $refund) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="approved"
                                >

                                <button
                                    type="submit"
                                    class="refund-action-large refund-action-approve"
                                >
                                    <i class="bi bi-check-circle"></i>
                                    Approve Refund
                                </button>

                            </form>


                            <form
                                method="POST"
                                action="{{ route('admin.refunds.status', $refund) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="rejected"
                                >

                                <button
                                    type="submit"
                                    class="refund-action-large refund-action-reject"
                                >
                                    <i class="bi bi-x-circle"></i>
                                    Reject Refund
                                </button>

                            </form>


                        {{-- APPROVED --}}
                        @elseif($refund->status === 'approved')

                            <form
                                method="POST"
                                action="{{ route('admin.refunds.status', $refund) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="processed"
                                >

                                <button
                                    type="submit"
                                    class="refund-action-large refund-action-process"
                                >
                                    <i class="bi bi-arrow-repeat"></i>
                                    Process Refund
                                </button>

                            </form>


                            <form
                                method="POST"
                                action="{{ route('admin.refunds.status', $refund) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="cancelled"
                                >

                                <button
                                    type="submit"
                                    class="refund-action-large refund-action-cancel"
                                >
                                    <i class="bi bi-slash-circle"></i>
                                    Cancel Refund
                                </button>

                            </form>


                        {{-- PROCESSED --}}
                        @elseif($refund->status === 'processed')

                            <div class="refund-status-message">

                                <i class="bi bi-check-circle-fill"></i>

                                <div>

                                    <strong>
                                        Refund Processed
                                    </strong>

                                    <span>
                                        This refund has been successfully processed.
                                    </span>

                                </div>

                            </div>


                        {{-- REJECTED --}}
                        @elseif($refund->status === 'rejected')

                            <div class="refund-status-message refund-status-danger">

                                <i class="bi bi-x-circle-fill"></i>

                                <div>

                                    <strong>
                                        Refund Rejected
                                    </strong>

                                    <span>
                                        This refund request was rejected.
                                    </span>

                                </div>

                            </div>


                        {{-- CANCELLED --}}
                        @elseif($refund->status === 'cancelled')

                            <div class="refund-status-message">

                                <i class="bi bi-slash-circle-fill"></i>

                                <div>

                                    <strong>
                                        Refund Cancelled
                                    </strong>

                                    <span>
                                        This refund request has been cancelled.
                                    </span>

                                </div>

                            </div>

                        @endif


                        {{-- EDIT --}}
                        @if($refund->status !== 'processed')

                            <a
                                href="{{ route('admin.refunds.edit', $refund) }}"
                                class="refund-action-large refund-action-edit"
                            >
                                <i class="bi bi-pencil-square"></i>
                                Edit Refund
                            </a>

                        @else

                            <span class="refund-action-large refund-action-edit disabled">
                                <i class="bi bi-pencil-square"></i>
                                Edit Refund
                            </span>

                        @endif


                        {{-- BACK --}}
                        <a
                            href="{{ route('admin.refunds.index') }}"
                            class="refund-action-large refund-action-back"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Back to Refunds
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection