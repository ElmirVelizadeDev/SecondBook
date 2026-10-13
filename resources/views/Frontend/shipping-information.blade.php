
@extends('Layout.Frontend.master')

@section('title', 'Shipping Information | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/shipping-information.css') }}">
@endpush

@section('content')
<div class="sb-shipping-progress" aria-hidden="true"></div>

<main class="sb-shipping-page">
    {{-- =========================================================
         HERO
    ========================================================== --}}
    <section class="sb-shipping-hero">
        <div class="sb-shipping-hero-bg" aria-hidden="true">
            <span class="sb-shipping-decoration sb-shipping-decoration-one"></span>
            <span class="sb-shipping-decoration sb-shipping-decoration-two"></span>
            <span class="sb-shipping-ring sb-shipping-ring-one"></span>
            <span class="sb-shipping-ring sb-shipping-ring-two"></span>
        </div>

        <div class="container">
            <div class="sb-shipping-hero-content" data-reveal>
                <div class="sb-shipping-eyebrow">
                    <span class="sb-shipping-eyebrow-line"></span>
                    <i class="bi bi-truck" aria-hidden="true"></i>
                    <span>SECONDBOOK SHIPPING</span>
                    <span class="sb-shipping-eyebrow-line"></span>
                </div>

                <h1>
                    Shipping made
                    <em>simple.</em>
                </h1>

                <p>
                    Everything you need to know about delivery,
                    shipping times, order tracking and receiving
                    your books from SecondBook.
                </p>

                <div class="sb-shipping-meta">
                    <span>
                        <i class="bi bi-box-seam" aria-hidden="true"></i>
                        Carefully prepared orders
                    </span>

                    <span class="sb-shipping-meta-divider" aria-hidden="true"></span>

                    <span>
                        <i class="bi bi-house-check" aria-hidden="true"></i>
                        Delivery information
                    </span>
                </div>
            </div>
        </div>

        <div class="sb-shipping-hero-bottom-line" aria-hidden="true"></div>
    </section>

    <div class="container sb-shipping-container">
        {{-- =========================================================
             INTRO
        ========================================================== --}}
        <section class="sb-shipping-intro" data-reveal>
            <div class="sb-shipping-intro-icon">
                <i class="bi bi-box-seam" aria-hidden="true"></i>
            </div>

            <div class="sb-shipping-intro-content">
                <span class="sb-shipping-intro-label">YOUR ORDER JOURNEY</span>

                <h2>From checkout to your doorstep.</h2>

                <p>
                    We keep the delivery process straightforward,
                    so you know what to expect after placing your order.
                    Find helpful information about preparation, delivery
                    and order updates below.
                </p>
            </div>

            <span class="sb-shipping-intro-decoration" aria-hidden="true">
                <i class="bi bi-book-half"></i>
            </span>
        </section>

        {{-- =========================================================
             SHIPPING OVERVIEW
        ========================================================== --}}
        <section class="sb-shipping-section" id="shipping-overview">
            <div class="sb-shipping-section-heading" data-reveal>
                <span class="sb-shipping-section-label">
                    <i class="bi bi-info-circle" aria-hidden="true"></i>
                    Shipping Overview
                </span>

                <h2>
                    What happens after
                    <em>you place an order?</em>
                </h2>

                <p>
                    We keep the delivery process straightforward,
                    so you always know what to expect.
                </p>
            </div>

            <div class="sb-shipping-step-grid">
                <article class="sb-shipping-step" data-reveal>
                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">01</span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-cart-check" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">
                        <h3>Order placed</h3>

                        <p>
                            Your order is received and the details
                            are prepared for processing.
                        </p>
                    </div>

                    <span class="sb-shipping-step-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>

                <article class="sb-shipping-step" data-reveal>
                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">02</span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">
                        <h3>Order prepared</h3>

                        <p>
                            The book is prepared and packaged
                            carefully for delivery.
                        </p>
                    </div>

                    <span class="sb-shipping-step-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>

                <article class="sb-shipping-step" data-reveal>
                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">03</span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-truck" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">
                        <h3>On the way</h3>

                        <p>
                            Your package is handed over for delivery
                            to the provided address.
                        </p>
                    </div>

                    <span class="sb-shipping-step-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>

                <article class="sb-shipping-step" data-reveal>
                    <div class="sb-shipping-step-top">
                        <span class="sb-shipping-step-number">04</span>

                        <span class="sb-shipping-step-icon">
                            <i class="bi bi-house-check" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="sb-shipping-step-content">
                        <h3>Delivered</h3>

                        <p>
                            Your book arrives at the delivery address
                            you provided at checkout.
                        </p>
                    </div>

                    <span class="sb-shipping-step-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>
            </div>
        </section>

        {{-- =========================================================
             SHIPPING DETAILS
        ========================================================== --}}
        <section class="sb-shipping-section" id="shipping-details">
            <div class="sb-shipping-section-heading" data-reveal>
                <span class="sb-shipping-section-label">
                    <i class="bi bi-box2" aria-hidden="true"></i>
                    Delivery Details
                </span>

                <h2>
                    Everything you need to know about
                    <em>your delivery.</em>
                </h2>

                <p>
                    Review the main details about delivery times,
                    addresses, order status and getting support.
                </p>
            </div>

            <div class="sb-shipping-details-grid">
                <article class="sb-shipping-info-card" data-reveal>
                    <div class="sb-shipping-info-top">
                        <span class="sb-shipping-info-icon">
                            <i class="bi bi-clock" aria-hidden="true"></i>
                        </span>

                        <span class="sb-shipping-info-label">Delivery Time</span>
                    </div>

                    <h3>How long does delivery take?</h3>

                    <p>
                        Delivery time may vary depending on the seller,
                        destination and shipping arrangements for your order.
                    </p>

                    <span class="sb-shipping-info-line" aria-hidden="true"></span>
                </article>

                <article class="sb-shipping-info-card" data-reveal>
                    <div class="sb-shipping-info-top">
                        <span class="sb-shipping-info-icon">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                        </span>

                        <span class="sb-shipping-info-label">Delivery Address</span>
                    </div>

                    <h3>Where will my order arrive?</h3>

                    <p>
                        Orders are delivered to the address entered during
                        checkout. Please make sure your delivery details are correct.
                    </p>

                    <span class="sb-shipping-info-line" aria-hidden="true"></span>
                </article>

                <article class="sb-shipping-info-card" data-reveal>
                    <div class="sb-shipping-info-top">
                        <span class="sb-shipping-info-icon">
                            <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                        </span>

                        <span class="sb-shipping-info-label">Order Status</span>
                    </div>

                    <h3>How can I check my order?</h3>

                    <p>
                        You can view your order information and track its
                        current status from your account after placing an order.
                    </p>

                    <span class="sb-shipping-info-line" aria-hidden="true"></span>
                </article>

                <article class="sb-shipping-info-card" data-reveal>
                    <div class="sb-shipping-info-top">
                        <span class="sb-shipping-info-icon">
                            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                        </span>

                        <span class="sb-shipping-info-label">Delivery Issues</span>
                    </div>

                    <h3>Something went wrong?</h3>

                    <p>
                        If your order has a delivery problem, contact our
                        support team and provide your order details for assistance.
                    </p>

                    <span class="sb-shipping-info-line" aria-hidden="true"></span>
                </article>
            </div>
        </section>

        {{-- =========================================================
             SHIPPING NOTES
        ========================================================== --}}
        <section class="sb-shipping-notes" data-reveal>
            <div class="sb-shipping-notes-icon">
                <i class="bi bi-lightbulb" aria-hidden="true"></i>
            </div>

            <div class="sb-shipping-notes-content">
                <span class="sb-shipping-section-label">GOOD TO KNOW</span>

                <h2>A few things to keep in mind.</h2>

                <div class="sb-shipping-notes-list">
                    <div class="sb-shipping-note" data-reveal>
                        <span class="sb-shipping-note-icon">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                        </span>

                        <p>
                            Double-check your delivery address
                            before placing an order.
                        </p>
                    </div>

                    <div class="sb-shipping-note" data-reveal>
                        <span class="sb-shipping-note-icon">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                        </span>

                        <p>
                            Delivery times can vary depending
                            on the destination and seller.
                        </p>
                    </div>

                    <div class="sb-shipping-note" data-reveal>
                        <span class="sb-shipping-note-icon">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                        </span>

                        <p>
                            Keep your order information available
                            if you need support.
                        </p>
                    </div>

                    <div class="sb-shipping-note" data-reveal>
                        <span class="sb-shipping-note-icon">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                        </span>

                        <p>
                            Contact support if your order does
                            not arrive as expected.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- =========================================================
             FAQ
        ========================================================== --}}
        <section class="sb-shipping-section" id="shipping-faq">
            <div class="sb-shipping-section-heading" data-reveal>
                <span class="sb-shipping-section-label">
                    <i class="bi bi-question-circle" aria-hidden="true"></i>
                    Shipping Questions
                </span>

                <h2>
                    Need a quick
                    <em>answer?</em>
                </h2>

                <p>
                    Find more answers to common questions
                    in our Help Center and FAQ.
                </p>
            </div>

            <div class="sb-shipping-faq-grid">
                <a
                    href="{{ route('frontend.faq', ['search' => 'order']) }}#faqSearch"
                    class="sb-shipping-faq-card"
                    data-reveal
                >
                    <span class="sb-shipping-faq-icon">
                        <i class="bi bi-question-lg" aria-hidden="true"></i>
                    </span>

                    <span class="sb-shipping-faq-text">
                        <strong>Where can I find my order status?</strong>
                        <small>Learn more about orders and updates.</small>
                    </span>

                    <span class="sb-shipping-faq-arrow">
                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </span>
                </a>

                <a
                    href="{{ route('frontend.faq', ['search' => 'shipping']) }}#faqSearch"
                    class="sb-shipping-faq-card"
                    data-reveal
                >
                    <span class="sb-shipping-faq-icon">
                        <i class="bi bi-question-lg" aria-hidden="true"></i>
                    </span>

                    <span class="sb-shipping-faq-text">
                        <strong>What should I do if my order is late?</strong>
                        <small>Find information about delivery issues.</small>
                    </span>

                    <span class="sb-shipping-faq-arrow">
                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </span>
                </a>

                <a
                    href="{{ route('frontend.contact') }}"
                    class="sb-shipping-faq-card"
                    data-reveal
                >
                    <span class="sb-shipping-faq-icon">
                        <i class="bi bi-headset" aria-hidden="true"></i>
                    </span>

                    <span class="sb-shipping-faq-text">
                        <strong>How can I contact support?</strong>
                        <small>Get help from the SecondBook team.</small>
                    </span>

                    <span class="sb-shipping-faq-arrow">
                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </span>
                </a>
            </div>
        </section>

        {{-- =========================================================
             CONTACT CTA
        ========================================================== --}}
        <section class="sb-shipping-contact" data-reveal>
            <div class="sb-shipping-contact-decoration" aria-hidden="true"></div>

            <div class="sb-shipping-contact-icon">
                <i class="bi bi-headset" aria-hidden="true"></i>
            </div>

            <div class="sb-shipping-contact-content">
                <span class="sb-shipping-section-label">NEED MORE HELP?</span>

                <h2>We're here for you.</h2>

                <p>
                    If you have a question about your delivery,
                    our support team is ready to help.
                </p>
            </div>

            <div class="sb-shipping-contact-actions">
                <a href="{{ route('frontend.contact') }}" class="sb-shipping-contact-btn">
                    <span>Contact Support</span>
                    <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                </a>

                <a href="{{ route('frontend.faq') }}" class="sb-shipping-contact-link">
                    Visit Help Center
                    <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        {{-- =========================================================
             BOTTOM META
        ========================================================== --}}
        <div class="sb-shipping-bottom" data-reveal>
            <span>
                <i class="bi bi-truck" aria-hidden="true"></i>
                Clear information for every delivery
            </span>

            <a href="mailto:support@secondbook.com">support@secondbook.com</a>
        </div>
    </div>
</main>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.sb-shipping-page');

    if (!page) {
        return;
    }

    const progress = document.querySelector('.sb-shipping-progress');
    let ticking = false;

    const updateProgress = function () {
        if (!progress) {
            return;
        }

        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const percentage = scrollable > 0
            ? Math.min(100, Math.max(0, (window.scrollY / scrollable) * 100))
            : 0;

        progress.style.width = percentage + '%';
        ticking = false;
    };

    const requestProgressUpdate = function () {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(updateProgress);
    };

    updateProgress();

    window.addEventListener('scroll', requestProgressUpdate, { passive: true });
    window.addEventListener('resize', requestProgressUpdate);

    const revealItems = page.querySelectorAll('[data-reveal]');

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -35px 0px'
        });

        revealItems.forEach(function (item, index) {
            item.style.setProperty('--reveal-delay', (index % 4) * 75 + 'ms');
            revealObserver.observe(item);
        });
    } else {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
    }
});
</script>
@endpush
