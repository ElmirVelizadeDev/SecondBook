@extends('Layout.Frontend.master')

@section('title', 'Shipping Information | SecondBook')

@push('css') <link rel="stylesheet" href="{{ asset('frontend-assets/css/shipping-information.css') }}">
@endpush

@section('content')

<main class="sb-shipping-page">


{{-- =========================================================
     HERO
========================================================== --}}

<section class="sb-shipping-hero">

    <div class="sb-shipping-decoration sb-shipping-decoration-one"></div>
    <div class="sb-shipping-decoration sb-shipping-decoration-two"></div>

    <div class="container">

        <div class="sb-shipping-hero-content">

            <div class="sb-shipping-eyebrow">
                <span class="sb-shipping-eyebrow-icon">
                    <i class="bi bi-truck"></i>
                </span>

                <span>SecondBook Shipping</span>
            </div>

            <h1>
                Shipping made
                <span>simple.</span>
            </h1>

            <p>
                Everything you need to know about delivery,
                shipping times, order tracking and receiving
                your books from SecondBook.
            </p>

        </div>

    </div>

</section>


{{-- =========================================================
     SHIPPING OVERVIEW
========================================================== --}}

<section class="sb-shipping-overview">

    <div class="container">

        <div class="sb-shipping-section-heading">

            <div>
                <span class="sb-shipping-section-label">
                    <i class="bi bi-info-circle"></i>
                    Shipping Overview
                </span>

                <h2>
                    What happens after
                    <span>you place an order?</span>
                </h2>
            </div>

            <p>
                We keep the delivery process straightforward,
                so you always know what to expect.
            </p>

        </div>


        <div class="row g-4">

            {{-- STEP 01 --}}

            <div class="col-12 col-md-6 col-xl-3">

                <div class="sb-shipping-step">

                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">
                            01
                        </span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-cart-check"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">

                        <h3>
                            Order placed
                        </h3>

                        <p>
                            Your order is received and the
                            details are prepared for processing.
                        </p>

                    </div>

                </div>

            </div>


            {{-- STEP 02 --}}

            <div class="col-12 col-md-6 col-xl-3">

                <div class="sb-shipping-step">

                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">
                            02
                        </span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-box-seam"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">

                        <h3>
                            Order prepared
                        </h3>

                        <p>
                            The book is prepared and packaged
                            carefully for delivery.
                        </p>

                    </div>

                </div>

            </div>


            {{-- STEP 03 --}}

            <div class="col-12 col-md-6 col-xl-3">

                <div class="sb-shipping-step">

                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">
                            03
                        </span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-truck"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">

                        <h3>
                            On the way
                        </h3>

                        <p>
                            Your package is handed over for
                            delivery to the provided address.
                        </p>

                    </div>

                </div>

            </div>


            {{-- STEP 04 --}}

            <div class="col-12 col-md-6 col-xl-3">

                <div class="sb-shipping-step">

                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">
                            04
                        </span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-house-check"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">

                        <h3>
                            Delivered
                        </h3>

                        <p>
                            Your book arrives at the delivery
                            address you provided at checkout.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SHIPPING DETAILS
========================================================== --}}

<section class="sb-shipping-details">

    <div class="container">

        <div class="sb-shipping-details-heading">

            <span class="sb-shipping-section-label">
                <i class="bi bi-box2"></i>
                Delivery Details
            </span>

            <h2>
                Everything you need to know about
                <span>your delivery.</span>
            </h2>

        </div>


        <div class="sb-shipping-details-grid">

            {{-- DELIVERY TIME --}}

            <div class="sb-shipping-info-card">

                <div class="sb-shipping-info-top">

                    <span class="sb-shipping-info-icon">
                        <i class="bi bi-clock"></i>
                    </span>

                    <span class="sb-shipping-info-label">
                        Delivery Time
                    </span>

                </div>

                <h3>
                    How long does delivery take?
                </h3>

                <p>
                    Delivery time may vary depending on
                    the seller, destination and shipping
                    arrangements for your order.
                </p>

                <span class="sb-shipping-info-line"></span>

            </div>


            {{-- DELIVERY ADDRESS --}}

            <div class="sb-shipping-info-card">

                <div class="sb-shipping-info-top">

                    <span class="sb-shipping-info-icon">
                        <i class="bi bi-geo-alt"></i>
                    </span>

                    <span class="sb-shipping-info-label">
                        Delivery Address
                    </span>

                </div>

                <h3>
                    Where will my order arrive?
                </h3>

                <p>
                    Orders are delivered to the address
                    entered during checkout. Please make
                    sure your delivery details are correct.
                </p>

                <span class="sb-shipping-info-line"></span>

            </div>


            {{-- ORDER STATUS --}}

            <div class="sb-shipping-info-card">

                <div class="sb-shipping-info-top">

                    <span class="sb-shipping-info-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </span>

                    <span class="sb-shipping-info-label">
                        Order Status
                    </span>

                </div>

                <h3>
                    How can I check my order?
                </h3>

                <p>
                    You can view your order information
                    and track its current status from your
                    account after placing an order.
                </p>

                <span class="sb-shipping-info-line"></span>

            </div>


            {{-- DELIVERY ISSUES --}}

            <div class="sb-shipping-info-card">

                <div class="sb-shipping-info-top">

                    <span class="sb-shipping-info-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </span>

                    <span class="sb-shipping-info-label">
                        Delivery Issues
                    </span>

                </div>

                <h3>
                    Something went wrong?
                </h3>

                <p>
                    If your order has a delivery problem,
                    contact our support team and provide
                    your order details for assistance.
                </p>

                <span class="sb-shipping-info-line"></span>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SHIPPING NOTES
========================================================== --}}

<section class="sb-shipping-notes">

    <div class="container">

        <div class="sb-shipping-notes-box">

            <div class="sb-shipping-notes-icon">
                <i class="bi bi-lightbulb"></i>
            </div>

            <div class="sb-shipping-notes-content">

                <span class="sb-shipping-notes-label">
                    Good to know
                </span>

                <h2>
                    A few things to keep in mind.
                </h2>

                <div class="sb-shipping-notes-list">

                    <div class="sb-shipping-note">
                        <i class="bi bi-check2-circle"></i>

                        <span>
                            Double-check your delivery address
                            before placing an order.
                        </span>
                    </div>

                    <div class="sb-shipping-note">
                        <i class="bi bi-check2-circle"></i>

                        <span>
                            Delivery times can vary depending
                            on the destination and seller.
                        </span>
                    </div>

                    <div class="sb-shipping-note">
                        <i class="bi bi-check2-circle"></i>

                        <span>
                            Keep your order information available
                            if you need support.
                        </span>
                    </div>

                    <div class="sb-shipping-note">
                        <i class="bi bi-check2-circle"></i>

                        <span>
                            Contact support if your order does
                            not arrive as expected.
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     FAQ
========================================================== --}}

<section class="sb-shipping-faq">

    <div class="container">

        <div class="sb-shipping-section-heading">

            <div>
                <span class="sb-shipping-section-label">
                    <i class="bi bi-question-circle"></i>
                    Shipping Questions
                </span>

                <h2>
                    Need a quick
                    <span>answer?</span>
                </h2>
            </div>

            <p>
                Find more answers to common questions
                in our Help Center and FAQ.
            </p>

        </div>


        <div class="sb-shipping-faq-grid">

            <a
                href="{{ route('frontend.faq', ['search' => 'order']) }}#faqSearch"
                class="sb-shipping-faq-card"
            >

                <span class="sb-shipping-faq-icon">
                    <i class="bi bi-question-lg"></i>
                </span>

                <span class="sb-shipping-faq-text">

                    <strong>
                        Where can I find my order status?
                    </strong>

                    <small>
                        Learn more about orders and updates.
                    </small>

                </span>

                <i class="bi bi-arrow-up-right"></i>

            </a>


            <a
                href="{{ route('frontend.faq', ['search' => 'shipping']) }}#faqSearch"
                class="sb-shipping-faq-card"
            >

                <span class="sb-shipping-faq-icon">
                    <i class="bi bi-question-lg"></i>
                </span>

                <span class="sb-shipping-faq-text">

                    <strong>
                        What should I do if my order is late?
                    </strong>

                    <small>
                        Find information about delivery issues.
                    </small>

                </span>

                <i class="bi bi-arrow-up-right"></i>

            </a>


            <a
                href="{{ route('frontend.contact') }}"
                class="sb-shipping-faq-card"
            >

                <span class="sb-shipping-faq-icon">
                    <i class="bi bi-question-lg"></i>
                </span>

                <span class="sb-shipping-faq-text">

                    <strong>
                        How can I contact support?
                    </strong>

                    <small>
                        Get help from the SecondBook team.
                    </small>

                </span>

                <i class="bi bi-arrow-up-right"></i>

            </a>

        </div>

    </div>

</section>


{{-- =========================================================
     CONTACT CTA
========================================================== --}}

<section class="sb-shipping-contact">

    <div class="container">

        <div class="sb-shipping-contact-box">

            <div class="sb-shipping-contact-decoration"></div>

            <div class="sb-shipping-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-shipping-contact-content">

                <span>
                    Need more help?
                </span>

                <h2>
                    We're here for you.
                </h2>

                <p>
                    If you have a question about your delivery,
                    our support team is ready to help.
                </p>

            </div>

            <a
                href="{{ route('frontend.contact') }}"
                class="sb-shipping-contact-btn"
            >
                <span>
                    Contact Support
                </span>

                <i class="bi bi-arrow-up-right"></i>
            </a>

        </div>

    </div>

</section>


</main>

@endsection
