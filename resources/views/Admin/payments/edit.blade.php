@extends('layout.admin.master')

@section('title', 'Edit Payment')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/orders.css') }}"> <link rel="stylesheet" href="{{ asset('admin/css/payments.css') }}">
@endpush

@section('content')

<div class="orders-edit-page">


{{-- =========================================================
     HEADER
     ========================================================= --}}

<div class="orders-edit-header">

    <div class="orders-edit-heading">

        <div class="orders-edit-eyebrow">
            <i class="bi bi-credit-card"></i>
            Payment Management
        </div>

        <h1>Edit Payment</h1>

        <p>
            Update transaction, order, payment and status information.
        </p>

    </div>

    <a href="{{ route('admin.payments.index') }}"
       class="orders-edit-back">

        <i class="bi bi-arrow-left"></i>
        <span>Back to Payments</span>

    </a>

</div>


{{-- =========================================================
     MAIN CARD
     ========================================================= --}}

<div class="orders-edit-card">

    <div class="orders-edit-card-header">

        <div class="orders-edit-card-icon">
            <i class="bi bi-credit-card"></i>
        </div>

        <div class="orders-edit-card-title">

            <strong>Payment #{{ $payment->id }}</strong>

            <span>
                Modify the information associated with this payment.
            </span>

        </div>

    </div>


    <div class="orders-edit-card-body">

        {{-- =================================================
             ERRORS
             ================================================= --}}

        @if($errors->any())

            <div class="orders-edit-errors">

                <div class="orders-edit-errors-title">
                    <i class="bi bi-exclamation-triangle"></i>
                    Please fix the following errors:
                </div>

                <ul>

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('admin.payments.update', $payment->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                 TRANSACTION & ORDER
                 ================================================= --}}

            <div class="orders-edit-section">

                <div class="orders-edit-section-heading">

                    <i class="bi bi-receipt"></i>

                    <span>Transaction & Order</span>

                </div>


                <div class="orders-edit-grid">

                    {{-- Transaction ID --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="transaction_id"
                            class="orders-edit-label"
                        >
                            Transaction ID
                        </label>

                        <input
                            id="transaction_id"
                            type="text"
                            class="orders-edit-input"
                            value="{{ $payment->transaction_id }}"
                            disabled
                        >

                    </div>


                    {{-- Order --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="order_id"
                            class="orders-edit-label"
                        >
                            Order
                            <span class="required">*</span>
                        </label>

                        <select
                            id="order_id"
                            name="order_id"
                            class="orders-edit-select"
                            required
                        >

                            @foreach($orders as $order)

                                <option
                                    value="{{ $order->id }}"
                                    @selected($payment->order_id == $order->id)
                                >
                                    {{ $order->order_number }}
                                    -
                                    {{ trim(
                                        ($order->user->first_name ?? '') . ' ' .
                                        ($order->user->last_name ?? '')
                                    ) ?: ($order->user->name ?? $order->user->username ?? 'User') }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PAYMENT INFORMATION
                 ================================================= --}}

            <div class="orders-edit-section">

                <div class="orders-edit-section-heading">

                    <i class="bi bi-wallet2"></i>

                    <span>Payment Information</span>

                </div>


                <div class="orders-edit-grid">

                    {{-- Amount --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="amount"
                            class="orders-edit-label"
                        >
                            Amount
                            <span class="required">*</span>
                        </label>

                        <input
                            id="amount"
                            type="number"
                            name="amount"
                            class="orders-edit-input"
                            step="0.01"
                            min="0"
                            value="{{ old('amount', $payment->amount) }}"
                            required
                        >

                    </div>


                    {{-- Payment Method --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="payment_method"
                            class="orders-edit-label"
                        >
                            Payment Method
                            <span class="required">*</span>
                        </label>

                        <select
                            id="payment_method"
                            name="payment_method"
                            class="orders-edit-select"
                            required
                        >

                            <option
                                value="cash_on_delivery"
                                @selected(
                                    old(
                                        'payment_method',
                                        $payment->payment_method
                                    ) === 'cash_on_delivery'
                                )
                            >
                                Cash On Delivery
                            </option>

                            <option
                                value="credit_card"
                                @selected(
                                    old(
                                        'payment_method',
                                        $payment->payment_method
                                    ) === 'credit_card'
                                )
                            >
                                Credit Card
                            </option>

                            <option
                                value="debit_card"
                                @selected(
                                    old(
                                        'payment_method',
                                        $payment->payment_method
                                    ) === 'debit_card'
                                )
                            >
                                Debit Card
                            </option>

                            <option
                                value="paypal"
                                @selected(
                                    old(
                                        'payment_method',
                                        $payment->payment_method
                                    ) === 'paypal'
                                )
                            >
                                PayPal
                            </option>

                        </select>

                    </div>


                    {{-- Payment Status --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="payment_status"
                            class="orders-edit-label"
                        >
                            Payment Status
                        </label>

                        <select
                            id="payment_status"
                            name="payment_status"
                            class="orders-edit-select"
                        >

                            <option
                                value="pending"
                                @selected(
                                    old(
                                        'payment_status',
                                        $payment->payment_status
                                    ) === 'pending'
                                )
                            >
                                Pending
                            </option>

                            <option
                                value="paid"
                                @selected(
                                    old(
                                        'payment_status',
                                        $payment->payment_status
                                    ) === 'paid'
                                )
                            >
                                Paid
                            </option>

                            <option
                                value="failed"
                                @selected(
                                    old(
                                        'payment_status',
                                        $payment->payment_status
                                    ) === 'failed'
                                )
                            >
                                Failed
                            </option>

                            <option
                                value="refunded"
                                @selected(
                                    old(
                                        'payment_status',
                                        $payment->payment_status
                                    ) === 'refunded'
                                )
                            >
                                Refunded
                            </option>

                        </select>

                    </div>


                    {{-- Paid At --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="paid_at"
                            class="orders-edit-label"
                        >
                            Paid At
                        </label>

                        <input
                            id="paid_at"
                            type="datetime-local"
                            name="paid_at"
                            class="orders-edit-input"
                            value="{{ old(
                                'paid_at',
                                $payment->paid_at
                                    ? $payment->paid_at->format('Y-m-d\TH:i')
                                    : ''
                            ) }}"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PAYMENT NOTE
                 ================================================= --}}

            <div class="orders-edit-section">

                <div class="orders-edit-section-heading">

                    <i class="bi bi-chat-left-text"></i>

                    <span>Payment Note</span>

                </div>


                <div class="orders-edit-grid">

                    <div class="orders-edit-field span-12">

                        <label
                            for="note"
                            class="orders-edit-label"
                        >
                            Note
                        </label>

                        <textarea
                            id="note"
                            name="note"
                            class="orders-edit-textarea"
                            rows="3"
                            placeholder="Add an internal note about this payment..."
                        >{{ old('note', $payment->note) }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
                 ================================================= --}}

            <div class="orders-edit-footer">

                <a
                    href="{{ route('admin.payments.index') }}"
                    class="orders-edit-cancel"
                >
                    <i class="bi bi-x-lg"></i>
                    Cancel
                </a>


                <button
                    type="submit"
                    class="orders-edit-submit"
                >
                    <i class="bi bi-check-circle"></i>
                    Update Payment
                </button>

            </div>


        </form>

    </div>

</div>


</div>

@endsection
