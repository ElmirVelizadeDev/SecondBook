@extends('layout.admin.master')

@section('title', 'Create Payment')

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
            <i class="bi bi-credit-card-2-front"></i>
            Payment Management
        </div>

        <h1>Create Payment</h1>

        <p>
            Create a new customer payment manually.
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
            <i class="bi bi-credit-card-2-front"></i>
        </div>

        <div class="orders-edit-card-title">

            <strong>New Payment</strong>

            <span>
                Fill in the information associated with this payment.
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
            action="{{ route('admin.payments.store') }}"
            method="POST"
        >

            @csrf


            {{-- =================================================
                 ORDER & AMOUNT
                 ================================================= --}}

            <div class="orders-edit-section">

                <div class="orders-edit-section-heading">

                    <i class="bi bi-receipt"></i>

                    <span>Order & Amount</span>

                </div>


                <div class="orders-edit-grid">

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
                            class="orders-edit-select @error('order_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Order
                            </option>

                            @foreach($orders as $order)

                                <option
                                    value="{{ $order->id }}"
                                    @selected(old('order_id') == $order->id)
                                >

                                    {{ $order->order_number }}
                                    -
                                    {{ trim(
                                        ($order->user->first_name ?? '') . ' ' .
                                        ($order->user->last_name ?? '')
                                    ) ?: ($order->user->name ?? $order->user->username ?? 'User') }}
                                    -
                                    ${{ number_format($order->total_price, 2) }}

                                </option>

                            @endforeach

                        </select>

                        @error('order_id')
                            <div class="orders-edit-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


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
                            class="orders-edit-input @error('amount') is-invalid @enderror"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            value="{{ old('amount') }}"
                            required
                        >

                        @error('amount')
                            <div class="orders-edit-field-error">
                                {{ $message }}
                            </div>
                        @enderror

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
                            class="orders-edit-select @error('payment_method') is-invalid @enderror"
                            required
                        >

                            @foreach([
                                'cash_on_delivery' => 'Cash On Delivery',
                                'credit_card'      => 'Credit Card',
                                'debit_card'       => 'Debit Card',
                                'paypal'           => 'PayPal',
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old('payment_method', 'cash_on_delivery') === $value
                                    )
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                        @error('payment_method')
                            <div class="orders-edit-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Payment Status --}}

                    <div class="orders-edit-field span-6">

                        <label
                            for="payment_status"
                            class="orders-edit-label"
                        >
                            Payment Status
                            <span class="required">*</span>
                        </label>

                        <select
                            id="payment_status"
                            name="payment_status"
                            class="orders-edit-select @error('payment_status') is-invalid @enderror"
                            required
                        >

                            @foreach([
                                'pending',
                                'paid',
                                'failed',
                                'refunded'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old('payment_status', 'pending') === $status
                                    )
                                >
                                    {{ ucfirst($status) }}
                                </option>

                            @endforeach

                        </select>

                        @error('payment_status')
                            <div class="orders-edit-field-error">
                                {{ $message }}
                            </div>
                        @enderror

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
                            class="orders-edit-input @error('paid_at') is-invalid @enderror"
                            value="{{ old('paid_at') }}"
                        >

                        @error('paid_at')
                            <div class="orders-edit-field-error">
                                {{ $message }}
                            </div>
                        @enderror

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
                            rows="3"
                            placeholder="Add an internal note about this payment..."
                            class="orders-edit-textarea @error('note') is-invalid @enderror"
                        >{{ old('note') }}</textarea>

                        @error('note')
                            <div class="orders-edit-field-error">
                                {{ $message }}
                            </div>
                        @enderror

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
                    Create Payment
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
