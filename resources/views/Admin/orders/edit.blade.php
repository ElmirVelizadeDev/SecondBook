@extends('layout.admin.master')

@section('title', 'Edit Order')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/orders.css') }}">
@endpush

@section('content')

<div class="orders-edit-page">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="orders-edit-header">

        <div class="orders-edit-heading">

            <div class="orders-edit-eyebrow">
                <i class="bi bi-pencil-square"></i>
                Order Management
            </div>

            <h1>Edit Order</h1>

            <p>
                Update customer, payment, delivery and order information.
            </p>

        </div>

        <a href="{{ route('admin.orders.index') }}" class="orders-edit-back">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Orders</span>
        </a>

    </div>


    {{-- =========================================================
         MAIN CARD
         ========================================================= --}}

    <div class="orders-edit-card">

        <div class="orders-edit-card-header">

            <div class="orders-edit-card-icon">
                <i class="bi bi-cart-check"></i>
            </div>

            <div class="orders-edit-card-title">
                <strong>Order #{{ $order->id }}</strong>
                <span>
                    Modify the information associated with this order.
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
                action="{{ route('admin.orders.update', $order->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                     CUSTOMER & BOOK
                     ================================================= --}}

                <div class="orders-edit-section">

                    <div class="orders-edit-section-heading">
                        <i class="bi bi-person-vcard"></i>
                        <span>Customer & Book</span>
                    </div>

                    <div class="orders-edit-grid">

                        {{-- Customer --}}
                        <div class="orders-edit-field span-6">

                            <label for="user_id" class="orders-edit-label">
                                Customer
                                <span class="required">*</span>
                            </label>

                            <select
                                id="user_id"
                                name="user_id"
                                class="orders-edit-select"
                            >

                                @foreach($users as $user)

                                    <option
                                        value="{{ $user->id }}"
                                        @selected($order->user_id == $user->id)
                                    >
                                        {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? $user->username ?? 'User') }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Book --}}
                        <div class="orders-edit-field span-6">

                            <label for="book_id" class="orders-edit-label">
                                Book
                                <span class="required">*</span>
                            </label>

                            <select
                                id="book_id"
                                name="book_id"
                                class="orders-edit-select"
                            >

                                @foreach($books as $book)

                                    <option
                                        value="{{ $book->id }}"
                                        @selected($order->book_id == $book->id)
                                    >
                                        {{ $book->title }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CUSTOMER INFORMATION
                     ================================================= --}}

                <div class="orders-edit-section">

                    <div class="orders-edit-section-heading">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Customer Information</span>
                    </div>

                    <div class="orders-edit-grid">

                        {{-- Full Name --}}
                        <div class="orders-edit-field span-6">

                            <label for="full_name" class="orders-edit-label">
                                Full Name
                            </label>

                            <input
                                id="full_name"
                                type="text"
                                name="full_name"
                                class="orders-edit-input"
                                value="{{ old('full_name', $order->full_name) }}"
                            >

                        </div>


                        {{-- Phone --}}
                        <div class="orders-edit-field span-6">

                            <label for="phone" class="orders-edit-label">
                                Phone
                            </label>

                            <input
                                id="phone"
                                type="text"
                                name="phone"
                                class="orders-edit-input"
                                value="{{ old('phone', $order->phone) }}"
                            >

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ORDER & PAYMENT
                     ================================================= --}}

                <div class="orders-edit-section">

                    <div class="orders-edit-section-heading">
                        <i class="bi bi-receipt"></i>
                        <span>Order & Payment</span>
                    </div>

                    <div class="orders-edit-grid">

                        {{-- Quantity --}}
                        <div class="orders-edit-field span-4">

                            <label for="quantity" class="orders-edit-label">
                                Quantity
                            </label>

                            <input
                                id="quantity"
                                type="number"
                                name="quantity"
                                class="orders-edit-input"
                                min="1"
                                value="{{ old('quantity', $order->quantity) }}"
                            >

                        </div>


                        {{-- Price --}}
                        <div class="orders-edit-field span-4">

                            <label for="book_price" class="orders-edit-label">
                                Book Price
                            </label>

                            <input
                                id="book_price"
                                type="number"
                                name="book_price"
                                class="orders-edit-input"
                                step="0.01"
                                min="0"
                                value="{{ old('book_price', $order->book_price) }}"
                            >

                        </div>


                        {{-- Payment Method --}}
                        <div class="orders-edit-field span-4">

                            <label for="payment_method" class="orders-edit-label">
                                Payment Method
                            </label>

                            <select
                                id="payment_method"
                                name="payment_method"
                                class="orders-edit-select"
                            >

                                <option
                                    value="cash_on_delivery"
                                    @selected($order->payment_method == 'cash_on_delivery')
                                >
                                    Cash On Delivery
                                </option>

                                <option
                                    value="credit_card"
                                    @selected($order->payment_method == 'credit_card')
                                >
                                    Credit Card
                                </option>

                                <option
                                    value="debit_card"
                                    @selected($order->payment_method == 'debit_card')
                                >
                                    Debit Card
                                </option>

                                <option
                                    value="paypal"
                                    @selected($order->payment_method == 'paypal')
                                >
                                    PayPal
                                </option>

                            </select>

                        </div>


                        {{-- Payment Status --}}
                        <div class="orders-edit-field span-6">

                            <label for="payment_status" class="orders-edit-label">
                                Payment Status
                            </label>

                            <select
                                id="payment_status"
                                name="payment_status"
                                class="orders-edit-select"
                            >

                                <option
                                    value="pending"
                                    @selected($order->payment_status == 'pending')
                                >
                                    Pending
                                </option>

                                <option
                                    value="paid"
                                    @selected($order->payment_status == 'paid')
                                >
                                    Paid
                                </option>

                                <option
                                    value="failed"
                                    @selected($order->payment_status == 'failed')
                                >
                                    Failed
                                </option>

                                <option
                                    value="refunded"
                                    @selected($order->payment_status == 'refunded')
                                >
                                    Refunded
                                </option>

                            </select>

                        </div>


                        {{-- Order Status --}}
                        <div class="orders-edit-field span-6">

                            <label for="order_status" class="orders-edit-label">
                                Order Status
                            </label>

                            <select
                                id="order_status"
                                name="order_status"
                                class="orders-edit-select"
                            >

                                <option
                                    value="pending"
                                    @selected($order->order_status == 'pending')
                                >
                                    Pending
                                </option>

                                <option
                                    value="processing"
                                    @selected($order->order_status == 'processing')
                                >
                                    Processing
                                </option>

                                <option
                                    value="shipped"
                                    @selected($order->order_status == 'shipped')
                                >
                                    Shipped
                                </option>

                                <option
                                    value="delivered"
                                    @selected($order->order_status == 'delivered')
                                >
                                    Delivered
                                </option>

                                <option
                                    value="cancelled"
                                    @selected($order->order_status == 'cancelled')
                                >
                                    Cancelled
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SHIPPING ADDRESS
                     ================================================= --}}

                <div class="orders-edit-section">

                    <div class="orders-edit-section-heading">
                        <i class="bi bi-geo-alt"></i>
                        <span>Shipping Address</span>
                    </div>

                    <div class="orders-edit-grid">

                        {{-- Country --}}
                        <div class="orders-edit-field span-4">

                            <label for="country" class="orders-edit-label">
                                Country
                            </label>

                            <input
                                id="country"
                                type="text"
                                name="country"
                                class="orders-edit-input"
                                value="{{ old('country', $order->country) }}"
                            >

                        </div>


                        {{-- City --}}
                        <div class="orders-edit-field span-4">

                            <label for="city" class="orders-edit-label">
                                City
                            </label>

                            <input
                                id="city"
                                type="text"
                                name="city"
                                class="orders-edit-input"
                                value="{{ old('city', $order->city) }}"
                            >

                        </div>


                        {{-- Postal Code --}}
                        <div class="orders-edit-field span-4">

                            <label for="postal_code" class="orders-edit-label">
                                Postal Code
                            </label>

                            <input
                                id="postal_code"
                                type="text"
                                name="postal_code"
                                class="orders-edit-input"
                                value="{{ old('postal_code', $order->postal_code) }}"
                            >

                        </div>


                        {{-- Address --}}
                        <div class="orders-edit-field span-12">

                            <label for="address" class="orders-edit-label">
                                Address
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                class="orders-edit-textarea"
                                rows="3"
                            >{{ old('address', $order->address) }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ORDER NOTE
                     ================================================= --}}

                <div class="orders-edit-section">

                    <div class="orders-edit-section-heading">
                        <i class="bi bi-chat-left-text"></i>
                        <span>Order Note</span>
                    </div>

                    <div class="orders-edit-grid">

                        <div class="orders-edit-field span-12">

                            <label for="note" class="orders-edit-label">
                                Note
                            </label>

                            <textarea
                                id="note"
                                name="note"
                                class="orders-edit-textarea"
                                rows="3"
                                placeholder="Add an internal note about this order..."
                            >{{ old('note', $order->note) }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                     ================================================= --}}

                <div class="orders-edit-footer">

                    <a
                        href="{{ route('admin.orders.index') }}"
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
                        Update Order
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection