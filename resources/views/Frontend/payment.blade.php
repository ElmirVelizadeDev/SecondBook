@extends('Layout.Frontend.master')

@section('title', 'Payment | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/payment.css') }}">
@endpush

@section('content')

<main class="sb-payment-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="sb-payment-hero">
        <div class="container">

            <div class="sb-payment-breadcrumb">
                <a href="{{ route('frontend.cart') }}">
                    <i class="bi bi-cart3"></i>
                    <span>Shopping Cart</span>
                </a>

                <i class="bi bi-chevron-right"></i>

                <a href="{{ route('frontend.orders') }}">
                    <span>Orders</span>
                </a>

                <i class="bi bi-chevron-right"></i>

                <span class="is-current">Payment</span>
            </div>

            <div class="sb-payment-hero-grid">

                <div class="sb-payment-hero-content">

                    <span class="sb-payment-eyebrow">
                        <span class="sb-payment-eyebrow-icon">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        Secure Payment
                    </span>

                    <h1>Complete Your Payment</h1>

                    <p>
                        Review your order and choose a secure payment
                        method to complete your purchase.
                    </p>

                </div>

                <div class="sb-payment-security">
                    <div class="sb-payment-security-icon">
                        <i class="bi bi-lock-fill"></i>
                    </div>

                    <div class="sb-payment-security-content">
                        <strong>Secure Checkout</strong>
                        <span>Your payment details are protected.</span>
                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}
    <section class="sb-payment-section">
        <div class="container">

            {{-- Success --}}
            @if(session('success'))
                <div class="sb-payment-alert sb-payment-alert-success">
                    <div class="sb-payment-alert-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div class="sb-payment-alert-content">
                        <strong>Success</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif


            {{-- Error --}}
            @if(session('error'))
                <div class="sb-payment-alert sb-payment-alert-error">
                    <div class="sb-payment-alert-icon">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>

                    <div class="sb-payment-alert-content">
                        <strong>Payment Error</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif


            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="sb-payment-alert sb-payment-alert-error">
                    <div class="sb-payment-alert-icon">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>

                    <div class="sb-payment-alert-content">
                        <strong>Please check your payment details</strong>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif


            <div class="row g-4 g-xl-5 align-items-start">

                {{-- =================================================
                    PAYMENT FORM
                ================================================== --}}
                <div class="col-lg-7">

                    <form
                        action="{{ route('frontend.payment.process', $order->id) }}"
                        method="POST"
                        class="sb-payment-form"
                        id="paymentForm"
                    >
                        @csrf

                        <div class="sb-payment-card">

                            {{-- Card Header --}}
                            <div class="sb-payment-card-header">

                                <div class="sb-payment-card-heading">

                                    <div class="sb-payment-card-icon">
                                        <i class="bi bi-credit-card-2-front"></i>
                                    </div>

                                    <div>
                                        <span class="sb-payment-card-kicker">
                                            Payment Details
                                        </span>

                                        <h2>Choose Payment Method</h2>
                                    </div>

                                </div>

                                <span class="sb-payment-step">01</span>

                            </div>


                            <div class="sb-payment-card-body">

                                {{-- =================================================
                                    PAYMENT METHODS
                                ================================================== --}}
                                <div class="sb-payment-method-section">

                                    <div class="sb-payment-section-title">

                                        <div>
                                            <span>Payment method</span>

                                            <small>
                                                Select your preferred payment option
                                            </small>
                                        </div>

                                        <i class="bi bi-wallet2"></i>

                                    </div>


                                    <div class="sb-payment-method-grid">

                                        {{-- Credit Card --}}
                                        <label class="sb-payment-method">
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="credit_card"
                                                {{ old('payment_method', $payment->payment_method) === 'credit_card' ? 'checked' : '' }}
                                            >

                                            <span class="sb-payment-method-box">

                                                <span class="sb-payment-method-top">
                                                    <span class="sb-payment-method-icon">
                                                        <i class="bi bi-credit-card"></i>
                                                    </span>

                                                    <span class="sb-payment-radio"></span>
                                                </span>

                                                <strong>Credit Card</strong>

                                                <small>
                                                    Pay securely with your credit card.
                                                </small>

                                            </span>
                                        </label>


                                        {{-- Debit Card --}}
                                        <label class="sb-payment-method">
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="debit_card"
                                                {{ old('payment_method', $payment->payment_method) === 'debit_card' ? 'checked' : '' }}
                                            >

                                            <span class="sb-payment-method-box">

                                                <span class="sb-payment-method-top">
                                                    <span class="sb-payment-method-icon">
                                                        <i class="bi bi-wallet2"></i>
                                                    </span>

                                                    <span class="sb-payment-radio"></span>
                                                </span>

                                                <strong>Debit Card</strong>

                                                <small>
                                                    Pay securely with your debit card.
                                                </small>

                                            </span>
                                        </label>


                                        {{-- PayPal --}}
                                        <label class="sb-payment-method">
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="paypal"
                                                {{ old('payment_method', $payment->payment_method) === 'paypal' ? 'checked' : '' }}
                                            >

                                            <span class="sb-payment-method-box">

                                                <span class="sb-payment-method-top">
                                                    <span class="sb-payment-method-icon">
                                                        <i class="bi bi-paypal"></i>
                                                    </span>

                                                    <span class="sb-payment-radio"></span>
                                                </span>

                                                <strong>PayPal</strong>

                                                <small>
                                                    Pay through your PayPal account.
                                                </small>

                                            </span>
                                        </label>


                                        {{-- Cash on Delivery --}}
                                        <label class="sb-payment-method">
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="cash_on_delivery"
                                                {{ old('payment_method', $payment->payment_method) === 'cash_on_delivery' ? 'checked' : '' }}
                                            >

                                            <span class="sb-payment-method-box">

                                                <span class="sb-payment-method-top">
                                                    <span class="sb-payment-method-icon">
                                                        <i class="bi bi-cash-stack"></i>
                                                    </span>

                                                    <span class="sb-payment-radio"></span>
                                                </span>

                                                <strong>Cash on Delivery</strong>

                                                <small>
                                                    Pay when your order arrives.
                                                </small>

                                            </span>
                                        </label>

                                    </div>

                                </div>


                                {{-- =================================================
                                    CARD DETAILS
                                ================================================== --}}
                                <div
                                    class="sb-card-details"
                                    id="cardDetails"
                                >

                                    <div class="sb-payment-section-title">

                                        <div>
                                            <span>Card information</span>

                                            <small>
                                                Enter your card details below.
                                            </small>
                                        </div>

                                        <i class="bi bi-credit-card-2-back"></i>

                                    </div>


                                    <div class="row g-3">

                                        {{-- Cardholder --}}
                                        <div class="col-12">

                                            <label
                                                for="cardholder_name"
                                                class="sb-payment-label"
                                            >
                                                Cardholder Name
                                            </label>

                                            <div class="sb-payment-input-wrap">

                                                <input
                                                    type="text"
                                                    id="cardholder_name"
                                                    name="cardholder_name"
                                                    class="sb-payment-input"
                                                    value="{{ old('cardholder_name') }}"
                                                    placeholder="John Doe"
                                                    autocomplete="cc-name"
                                                >

                                                <i class="bi bi-person"></i>

                                            </div>

                                        </div>


                                        {{-- Card Number --}}
                                        <div class="col-12">

                                            <label
                                                for="card_number"
                                                class="sb-payment-label"
                                            >
                                                Card Number
                                            </label>

                                            <div class="sb-payment-input-wrap">

                                                <input
                                                    type="text"
                                                    id="card_number"
                                                    name="card_number"
                                                    class="sb-payment-input"
                                                    value="{{ old('card_number') }}"
                                                    placeholder="1234 5678 9012 3456"
                                                    inputmode="numeric"
                                                    autocomplete="cc-number"
                                                    maxlength="19"
                                                >

                                                <i class="bi bi-credit-card"></i>

                                            </div>

                                        </div>


                                        {{-- Expiry --}}
                                        <div class="col-md-6">

                                            <label
                                                for="expiry_date"
                                                class="sb-payment-label"
                                            >
                                                Expiry Date
                                            </label>

                                            <div class="sb-payment-input-wrap">

                                                <input
                                                    type="text"
                                                    id="expiry_date"
                                                    name="expiry_date"
                                                    class="sb-payment-input"
                                                    value="{{ old('expiry_date') }}"
                                                    placeholder="MM/YY"
                                                    inputmode="numeric"
                                                    autocomplete="cc-exp"
                                                    maxlength="5"
                                                >

                                                <i class="bi bi-calendar3"></i>

                                            </div>

                                        </div>


                                        {{-- CVV --}}
                                        <div class="col-md-6">

                                            <label
                                                for="cvv"
                                                class="sb-payment-label"
                                            >
                                                CVV
                                            </label>

                                            <div class="sb-payment-input-wrap">

                                                <input
                                                    type="password"
                                                    id="cvv"
                                                    name="cvv"
                                                    class="sb-payment-input"
                                                    value="{{ old('cvv') }}"
                                                    placeholder="123"
                                                    inputmode="numeric"
                                                    autocomplete="cc-csc"
                                                    maxlength="4"
                                                >

                                                <i class="bi bi-shield-lock"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                    PAYPAL
                                ================================================== --}}
                                <div
                                    class="sb-paypal-details"
                                    id="paypalDetails"
                                >

                                    <div class="sb-payment-section-title">

                                        <div>
                                            <span>PayPal information</span>

                                            <small>
                                                Enter the email address connected to your PayPal account.
                                            </small>
                                        </div>

                                        <i class="bi bi-paypal"></i>

                                    </div>


                                    <div class="sb-paypal-info">

                                        <div class="sb-paypal-info-icon">
                                            <i class="bi bi-paypal"></i>
                                        </div>

                                        <div>
                                            <strong>Pay with PayPal</strong>

                                            <span>
                                                You can use your PayPal account to complete this payment.
                                            </span>
                                        </div>

                                    </div>


                                    <div class="sb-paypal-input">

                                        <label
                                            for="paypal_email"
                                            class="sb-payment-label"
                                        >
                                            PayPal Email
                                        </label>

                                        <div class="sb-payment-input-wrap">

                                            <input
                                                type="email"
                                                id="paypal_email"
                                                name="paypal_email"
                                                class="sb-payment-input"
                                                value="{{ old('paypal_email') }}"
                                                placeholder="your@email.com"
                                                autocomplete="email"
                                            >

                                            <i class="bi bi-envelope"></i>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                    CASH ON DELIVERY
                                ================================================== --}}
                                <div
                                    class="sb-cod-details"
                                    id="codDetails"
                                >

                                    <div class="sb-cod-box">

                                        <div class="sb-cod-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>

                                        <div class="sb-cod-content">

                                            <strong>Cash on Delivery</strong>

                                            <p>
                                                No card or online payment is required.
                                                You will pay when your order arrives.
                                            </p>

                                            <span>
                                                <i class="bi bi-check-circle-fill"></i>
                                                Your order can be confirmed immediately.
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                                    SECURITY NOTICE
                                ================================================== --}}
                                <div class="sb-payment-security-note">

                                    <div class="sb-payment-security-note-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>

                                    <div>
                                        <strong>Your payment is secure</strong>

                                        <p>
                                            Your payment information is handled
                                            securely and protected during checkout.
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}
                        <div class="sb-payment-actions">

                            <a
                                href="{{ route('frontend.orders') }}"
                                class="sb-payment-back"
                            >
                                <i class="bi bi-arrow-left"></i>
                                <span>Back to Orders</span>
                            </a>

                            <button
                                type="submit"
                                class="sb-payment-submit"
                                id="paymentSubmitBtn"
                            >
                                <span id="paymentSubmitText">
                                    Pay ${{ number_format($payment->amount, 2) }}
                                </span>

                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </form>

                </div>


                {{-- =================================================
                    ORDER SUMMARY
                ================================================== --}}
                <div class="col-lg-5">

                    <aside class="sb-payment-summary">

                        <div class="sb-payment-summary-header">

                            <div>
                                <span class="sb-summary-eyebrow">
                                    Your Order
                                </span>

                                <h2>Order Summary</h2>
                            </div>

                            <span class="sb-summary-order">
                                #{{ $order->order_number }}
                            </span>

                        </div>


                        {{-- Product --}}
                        <div class="sb-payment-product">

                            <div class="sb-payment-product-image">

                                @if($order->book && !empty($order->book->cover))

                                    <img
                                        src="{{ filter_var($order->book->cover, FILTER_VALIDATE_URL)
                                            ? $order->book->cover
                                            : asset('storage/' . $order->book->cover) }}"
                                        alt="{{ $order->book->title }}"
                                    >

                                @else

                                    <div class="sb-payment-product-placeholder">
                                        <i class="bi bi-book"></i>
                                    </div>

                                @endif

                            </div>


                            <div class="sb-payment-product-info">

                                <span>Book</span>

                                <h3>
                                    {{ $order->book->title ?? 'Book' }}
                                </h3>

                                <p>
                                    <i class="bi bi-box-seam"></i>

                                    {{ $order->quantity }}

                                    {{ $order->quantity == 1 ? 'item' : 'items' }}
                                </p>

                            </div>

                        </div>


                        {{-- Price --}}
                        <div class="sb-payment-price-list">

                            <div class="sb-payment-price-row">
                                <span>Unit Price</span>

                                <strong>
                                    ${{ number_format($order->book_price, 2) }}
                                </strong>
                            </div>

                            <div class="sb-payment-price-row">
                                <span>Quantity</span>

                                <strong>
                                    ×{{ $order->quantity }}
                                </strong>
                            </div>

                            <div class="sb-payment-price-row">
                                <span>Shipping</span>

                                <strong class="sb-payment-free">
                                    FREE
                                </strong>
                            </div>

                        </div>


                        <div class="sb-payment-summary-divider"></div>


                        {{-- Total --}}
                        <div class="sb-payment-total">

                            <div>
                                <span>Total Amount</span>

                                <small>Including shipping</small>
                            </div>

                            <strong>
                                ${{ number_format($payment->amount, 2) }}
                            </strong>

                        </div>


                        {{-- Status --}}
                        <div class="sb-payment-status">

                            <div class="sb-payment-status-icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                            <div>
                                <strong id="paymentStatusTitle">
                                    Payment Pending
                                </strong>

                                <span id="paymentStatusText">
                                    Complete your payment to continue.
                                </span>
                            </div>

                        </div>


                        {{-- Order Info --}}
                        <div class="sb-payment-order-info">

                            <div>
                                <span>Order Date</span>

                                <strong>
                                    {{ $order->created_at->format('M d, Y') }}
                                </strong>
                            </div>

                            <div>
                                <span>Payment Method</span>

                                <strong id="summaryPaymentMethod">
                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            old(
                                                'payment_method',
                                                $payment->payment_method
                                            )
                                        )
                                    ) }}
                                </strong>
                            </div>

                        </div>


                        {{-- Trust --}}
                        <div class="sb-payment-trust">

                            <div>
                                <i class="bi bi-shield-check"></i>
                                <span>Secure</span>
                            </div>

                            <div>
                                <i class="bi bi-lock"></i>
                                <span>Protected</span>
                            </div>

                            <div>
                                <i class="bi bi-headset"></i>
                                <span>Support</span>
                            </div>

                        </div>

                    </aside>

                </div>

            </div>

        </div>
    </section>

</main>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const paymentMethods = document.querySelectorAll(
        'input[name="payment_method"]'
    );

    const cardDetails = document.getElementById('cardDetails');
    const paypalDetails = document.getElementById('paypalDetails');
    const codDetails = document.getElementById('codDetails');

    const cardholderName =
        document.getElementById('cardholder_name');

    const cardNumber =
        document.getElementById('card_number');

    const expiryDate =
        document.getElementById('expiry_date');

    const cvv =
        document.getElementById('cvv');

    const paypalEmail =
        document.getElementById('paypal_email');

    const submitText =
        document.getElementById('paymentSubmitText');

    const summaryPaymentMethod =
        document.getElementById('summaryPaymentMethod');

    const paymentStatusTitle =
        document.getElementById('paymentStatusTitle');

    const paymentStatusText =
        document.getElementById('paymentStatusText');


    function setFieldState(field, enabled) {

        if (!field) {
            return;
        }

        field.disabled = !enabled;

        if (enabled) {
            field.removeAttribute('disabled');
        } else {
            field.setAttribute('disabled', 'disabled');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Expiry Date - MM/YY
    |--------------------------------------------------------------------------
    */

    if (expiryDate) {

        expiryDate.addEventListener('input', function () {

            let value = this.value
                .replace(/\D/g, '')
                .slice(0, 4);

            if (value.length >= 3) {

                value =
                    value.substring(0, 2) +
                    '/' +
                    value.substring(2);

            }

            this.value = value;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Card Number Formatting
    |--------------------------------------------------------------------------
    */

    if (cardNumber) {

        cardNumber.addEventListener('input', function () {

            let value = this.value
                .replace(/\D/g, '')
                .slice(0, 16);

            value = value
                .replace(/(.{4})/g, '$1 ')
                .trim();

            this.value = value;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | CVV
    |--------------------------------------------------------------------------
    */

    if (cvv) {

        cvv.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 4);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Payment UI
    |--------------------------------------------------------------------------
    */

    function updatePaymentUI() {

        const selected = document.querySelector(
            'input[name="payment_method"]:checked'
        );

        if (!selected) {
            return;
        }

        const method = selected.value;


        cardDetails.style.display = 'none';
        paypalDetails.style.display = 'none';
        codDetails.style.display = 'none';


        setFieldState(cardholderName, false);
        setFieldState(cardNumber, false);
        setFieldState(expiryDate, false);
        setFieldState(cvv, false);
        setFieldState(paypalEmail, false);


        /*
        |--------------------------------------------------------------------------
        | Credit / Debit Card
        |--------------------------------------------------------------------------
        */

        if (
            method === 'credit_card' ||
            method === 'debit_card'
        ) {

            cardDetails.style.display = 'block';

            setFieldState(cardholderName, true);
            setFieldState(cardNumber, true);
            setFieldState(expiryDate, true);
            setFieldState(cvv, true);

            submitText.textContent =
                'Pay ${{ number_format($payment->amount, 2) }}';

            paymentStatusTitle.textContent =
                'Payment Pending';

            paymentStatusText.textContent =
                'Complete your card payment to continue.';
        }


        /*
        |--------------------------------------------------------------------------
        | PayPal
        |--------------------------------------------------------------------------
        */

        else if (method === 'paypal') {

            paypalDetails.style.display = 'block';

            setFieldState(paypalEmail, true);

            submitText.textContent =
                'Continue with PayPal';

            paymentStatusTitle.textContent =
                'PayPal Payment';

            paymentStatusText.textContent =
                'Enter your PayPal email to continue.';
        }


        /*
        |--------------------------------------------------------------------------
        | Cash on Delivery
        |--------------------------------------------------------------------------
        */

        else if (method === 'cash_on_delivery') {

            codDetails.style.display = 'block';

            submitText.textContent =
                'Confirm Order';

            paymentStatusTitle.textContent =
                'Cash on Delivery';

            paymentStatusText.textContent =
                'You will pay when your order arrives.';
        }


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        if (summaryPaymentMethod) {

            const methodNames = {

                credit_card: 'Credit Card',
                debit_card: 'Debit Card',
                paypal: 'PayPal',
                cash_on_delivery: 'Cash on Delivery'

            };

            summaryPaymentMethod.textContent =
                methodNames[method] || method;
        }
    }


    paymentMethods.forEach(function (method) {

        method.addEventListener('change', function () {
            updatePaymentUI();
        });

    });


    updatePaymentUI();

});
</script>
@endpush