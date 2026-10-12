
@extends('Layout.Frontend.master')

@section('title', 'Contact Us | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/contact.css') }}">
@endpush

@section('content')

<section id="contact-page" class="contact-page">

    {{-- =========================================================
        Page Hero
    ========================================================== --}}

    <div class="contact-hero contact-hero-premium">

        {{-- Animated Background Decorations --}}
        <div class="contact-hero-decoration contact-hero-decoration-1"></div>
        <div class="contact-hero-decoration contact-hero-decoration-2"></div>
        <div class="contact-hero-decoration contact-hero-decoration-3"></div>

        <div class="contact-hero-grid"></div>

        <div class="container">
            <div class="contact-hero-inner">

                {{-- Hero Content --}}
                <div class="contact-hero-content" data-aos="fade-right" data-aos-duration="1000">

                    <div class="contact-hero-eyebrow">
                        <span class="contact-hero-eyebrow-icon">
                            <i class="bi bi-chat-square-heart"></i>
                        </span>

                        <span>WE'RE HERE FOR YOU</span>

                        <span class="contact-hero-eyebrow-line"></span>
                    </div>

                    <h1 class="section-title contact-hero-title">
                        Every great story
                        <span class="contact-hero-title-accent">
                            starts with
                            <em>a conversation.</em>
                        </span>
                    </h1>

                    <p class="contact-hero-description">
                        Questions, ideas, or simply a little help finding your way?
                        We're listening. Reach out to the SecondBook team and let's
                        make your next chapter a great one.
                    </p>

                    {{-- Contact Details --}}
                    <div class="contact-hero-meta">

                        <a href="mailto:support@secondbook.com"
                           class="contact-hero-meta-item">

                            <span class="contact-hero-meta-icon">
                                <i class="bi bi-envelope-heart"></i>
                            </span>

                            <span class="contact-hero-meta-content">
                                <span class="contact-hero-meta-label">SEND US AN EMAIL</span>
                                <strong>support@secondbook.com</strong>
                            </span>

                            <i class="bi bi-arrow-up-right contact-hero-meta-arrow"></i>
                        </a>

                        <div class="contact-hero-meta-item">

                            <span class="contact-hero-meta-icon">
                                <i class="bi bi-clock-history"></i>
                            </span>

                            <span class="contact-hero-meta-content">
                                <span class="contact-hero-meta-label">OUR AVAILABILITY</span>
                                <strong>Mon – Fri · 09:00 – 18:00</strong>
                            </span>

                            <span class="contact-hero-status-dot"></span>
                        </div>

                    </div>

                    {{-- Trust Note --}}
                    <div class="contact-hero-trust">
                        <span class="contact-hero-trust-icon">
                            <i class="bi bi-heart-fill"></i>
                        </span>

                        <span>
                            Real people. Thoughtful answers.
                            <strong>Every message matters.</strong>
                        </span>
                    </div>

                </div>

                {{-- Hero Visual --}}
                <div class="contact-hero-visual contact-hero-visual-premium"
                     data-aos="fade-left"
                     data-aos-duration="1100"
                     data-aos-delay="150">

                    {{-- Ambient Glow --}}
                    <div class="contact-hero-visual-glow"></div>

                    {{-- Decorative Orbit --}}
                    <div class="contact-hero-orbit">
                        <span class="contact-hero-orbit-dot contact-hero-orbit-dot-1"></span>
                        <span class="contact-hero-orbit-dot contact-hero-orbit-dot-2"></span>
                        <span class="contact-hero-orbit-dot contact-hero-orbit-dot-3"></span>
                    </div>

                    {{-- Decorative Rings --}}
                    <div class="contact-hero-ring contact-hero-ring-1"></div>
                    <div class="contact-hero-ring contact-hero-ring-2"></div>

                    {{-- Main Message Card --}}
                    <div class="contact-hero-card contact-hero-card-main">

                        <div class="contact-hero-card-top">
                            <span class="contact-hero-card-brand">
                                <span class="contact-hero-card-brand-icon">
                                    <i class="bi bi-book-half"></i>
                                </span>

                                <span>SECOND<span>BOOK</span></span>
                            </span>

                            <span class="contact-hero-card-menu">
                                <i class="bi bi-three-dots"></i>
                            </span>
                        </div>

                        <div class="contact-hero-card-illustration">

                            <div class="contact-hero-card-illustration-circle"></div>

                            <div class="contact-hero-book contact-hero-book-back">
                                <span></span>
                            </div>

                            <div class="contact-hero-book contact-hero-book-front">
                                <span class="contact-hero-book-symbol">
                                    <i class="bi bi-chat-heart"></i>
                                </span>

                                <span class="contact-hero-book-title">
                                    YOUR
                                    <strong>STORY</strong>
                                </span>

                                <span class="contact-hero-book-decoration"></span>
                            </div>

                            <span class="contact-hero-spark contact-hero-spark-1">
                                <i class="bi bi-stars"></i>
                            </span>

                            <span class="contact-hero-spark contact-hero-spark-2">
                                <i class="bi bi-plus-lg"></i>
                            </span>

                            <span class="contact-hero-spark contact-hero-spark-3">
                                <i class="bi bi-asterisk"></i>
                            </span>

                        </div>

                        <div class="contact-hero-card-message">
                            <span class="contact-hero-card-small">A NOTE FROM OUR TEAM</span>

                            <h2>
                                We'd love
                                <em>to hear from you.</em>
                            </h2>

                            <p>
                                Every question opens a door to a better reading experience.
                            </p>
                        </div>

                        <div class="contact-hero-card-footer">
                            <span class="contact-hero-card-footer-icon">
                                <i class="bi bi-chat-dots"></i>
                            </span>

                            <span>Let's start a conversation</span>

                            <i class="bi bi-arrow-up-right"></i>
                        </div>

                    </div>

                    {{-- Floating Response Card --}}
                    <div class="contact-hero-floating contact-hero-floating-top">

                        <span class="contact-hero-floating-icon">
                            <i class="bi bi-envelope-check"></i>
                        </span>

                        <span class="contact-hero-floating-content">
                            <strong>We're listening</strong>
                            <span>Your message matters</span>
                        </span>

                        <span class="contact-hero-floating-indicator"></span>

                    </div>

                    {{-- Floating Community Card --}}
                    <div class="contact-hero-floating contact-hero-floating-bottom">

                        <span class="contact-hero-floating-bottom-icon">
                            <i class="bi bi-heart"></i>
                        </span>

                        <span class="contact-hero-floating-bottom-content">
                            <strong>Made for book lovers</strong>
                            <span>Connected through stories</span>
                        </span>

                    </div>

                    {{-- Decorative Floating Elements --}}
                    <span class="contact-hero-deco-star contact-hero-deco-star-1">
                        <i class="bi bi-stars"></i>
                    </span>

                    <span class="contact-hero-deco-star contact-hero-deco-star-2">
                        <i class="bi bi-plus-lg"></i>
                    </span>

                </div>

            </div>

            {{-- Bottom Accent --}}
            <div class="contact-hero-bottom-accent">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>

    </div>

    {{-- =========================================================
        Contact Content
    ========================================================== --}}

    <section class="contact-content py-5">

        <div class="container">

            <div class="row g-5 align-items-stretch">

                {{-- Contact Information --}}

                <div class="col-lg-5">

                    <div class="contact-info" data-aos="fade-right">

                        <div class="section-header">

                            <div class="title">
                                <span>Let's talk</span>
                            </div>

                            <h2 class="section-title">
                                Get In Touch
                            </h2>

                        </div>

                        <p class="contact-intro">
                            Whether you need help with an order, want to
                            report an issue, or simply have a question about
                            SecondBook, we'd love to hear from you.
                        </p>

                        <div class="contact-info-list">

                            {{-- Email --}}

                            <div class="contact-info-item">

                                <div class="contact-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>

                                <div>
                                    <span>Email</span>

                                    <a href="mailto:support@secondbook.com">
                                        support@secondbook.com
                                    </a>
                                </div>

                            </div>

                            {{-- Phone --}}

                            <div class="contact-info-item">

                                <div class="contact-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>

                                <div>
                                    <span>Phone</span>

                                    <a href="tel:+994501234567">
                                        +994 50 123 45 67
                                    </a>
                                </div>

                            </div>

                            {{-- Address --}}

                            <div class="contact-info-item">

                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div>
                                    <span>Address</span>

                                    <p>Nakhchivan, Azerbaijan</p>
                                </div>

                            </div>

                            {{-- Working Hours --}}

                            <div class="contact-info-item">

                                <div class="contact-icon">
                                    <i class="bi bi-clock"></i>
                                </div>

                                <div>
                                    <span>Working Hours</span>

                                    <p>Monday – Friday, 09:00 – 18:00</p>
                                </div>

                            </div>

                        </div>

                        {{-- Contact Note --}}

                        <div class="contact-note">

                            <i class="bi bi-chat-square-text"></i>

                            <div>

                                <strong>
                                    Need help with an order?
                                </strong>

                                <p>
                                    Please include your order number in your
                                    message so we can assist you faster.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Contact Form --}}

                <div class="col-lg-7">

                    <div class="contact-form-card" data-aos="fade-left">

                        <div class="contact-form-header">

                            <div class="title">
                                <span>Send us a message</span>
                            </div>

                            <h2>How Can We Help?</h2>

                            <p>
                                Fill out the form below and we'll get back
                                to you as soon as possible.
                            </p>

                        </div>

                        {{-- Success Message --}}

                        @if(session('success'))

                            <div
                                class="alert alert-success contact-alert"
                                role="alert"
                            >
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>

                        @endif

                        {{-- Error Message --}}

                        @if(session('error'))

                            <div
                                class="alert alert-danger contact-alert"
                                role="alert"
                            >
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>

                        @endif

                        {{-- Validation Errors --}}

                        @if($errors->any())

                            <div
                                class="alert alert-danger contact-alert"
                                role="alert"
                            >

                                <div class="fw-semibold mb-2">
                                    Please check the following:
                                </div>

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        {{-- Authenticated User Form --}}

                        @auth

                            <form
                                action="{{ route('frontend.contact.store') }}"
                                method="POST"
                                class="contact-form"
                            >

                                @csrf

                                <div class="row">

                                    {{-- Name --}}

                                    <div class="col-md-6">

                                        <div class="contact-field">

                                            <label for="name">
                                                Your Name
                                            </label>

                                            <div class="contact-input-wrap">

                                                <i class="bi bi-person"></i>

                                                <input
                                                    type="text"
                                                    id="name"
                                                    name="name"
                                                    value="{{ auth()->user()->name }}"
                                                    placeholder="Your name"
                                                    maxlength="100"
                                                    autocomplete="name"
                                                    readonly
                                                    required
                                                >

                                            </div>

                                        </div>

                                    </div>

                                    {{-- Email --}}

                                    <div class="col-md-6">

                                        <div class="contact-field">

                                            <label for="email">
                                                Email Address
                                            </label>

                                            <div class="contact-input-wrap">

                                                <i class="bi bi-envelope"></i>

                                                <input
                                                    type="email"
                                                    id="email"
                                                    name="email"
                                                    value="{{ auth()->user()->email }}"
                                                    placeholder="Your email address"
                                                    maxlength="255"
                                                    autocomplete="email"
                                                    readonly
                                                    required
                                                >

                                            </div>

                                        </div>

                                    </div>

                                    {{-- Subject --}}

                                    <div class="col-12">

                                        <div class="contact-field">

                                            <label for="subject">
                                                Subject
                                            </label>

                                            <div class="contact-input-wrap">

                                                <i class="bi bi-chat-left-text"></i>

                                                <select
                                                    id="subject"
                                                    name="subject"
                                                    required
                                                >

                                                    <option
                                                        value="General Inquiry"
                                                        {{ old('subject', 'General Inquiry') === 'General Inquiry' ? 'selected' : '' }}
                                                    >
                                                        General Inquiry
                                                    </option>

                                                    <option
                                                        value="Order Issue"
                                                        {{ old('subject') === 'Order Issue' ? 'selected' : '' }}
                                                    >
                                                        Order Issue
                                                    </option>

                                                    <option
                                                        value="Payment Issue"
                                                        {{ old('subject') === 'Payment Issue' ? 'selected' : '' }}
                                                    >
                                                        Payment Issue
                                                    </option>

                                                    <option
                                                        value="Shipping & Delivery"
                                                        {{ old('subject') === 'Shipping & Delivery' ? 'selected' : '' }}
                                                    >
                                                        Shipping & Delivery
                                                    </option>

                                                    <option
                                                        value="Book Information"
                                                        {{ old('subject') === 'Book Information' ? 'selected' : '' }}
                                                    >
                                                        Book Information
                                                    </option>

                                                    <option
                                                        value="Account Problem"
                                                        {{ old('subject') === 'Account Problem' ? 'selected' : '' }}
                                                    >
                                                        Account Problem
                                                    </option>

                                                    <option
                                                        value="Wishlist Issue"
                                                        {{ old('subject') === 'Wishlist Issue' ? 'selected' : '' }}
                                                    >
                                                        Wishlist Issue
                                                    </option>

                                                    <option
                                                        value="Cart Issue"
                                                        {{ old('subject') === 'Cart Issue' ? 'selected' : '' }}
                                                    >
                                                        Cart Issue
                                                    </option>

                                                    <option
                                                        value="Return & Refund"
                                                        {{ old('subject') === 'Return & Refund' ? 'selected' : '' }}
                                                    >
                                                        Return & Refund
                                                    </option>

                                                    <option
                                                        value="Selling on SecondBook"
                                                        {{ old('subject') === 'Selling on SecondBook' ? 'selected' : '' }}
                                                    >
                                                        Selling on SecondBook
                                                    </option>

                                                    <option
                                                        value="Seller Support"
                                                        {{ old('subject') === 'Seller Support' ? 'selected' : '' }}
                                                    >
                                                        Seller Support
                                                    </option>

                                                    <option
                                                        value="Technical Issue"
                                                        {{ old('subject') === 'Technical Issue' ? 'selected' : '' }}
                                                    >
                                                        Technical Issue
                                                    </option>

                                                    <option
                                                        value="Suggestion / Feedback"
                                                        {{ old('subject') === 'Suggestion / Feedback' ? 'selected' : '' }}
                                                    >
                                                        Suggestion / Feedback
                                                    </option>

                                                    <option
                                                        value="Report a Problem"
                                                        {{ old('subject') === 'Report a Problem' ? 'selected' : '' }}
                                                    >
                                                        Report a Problem
                                                    </option>

                                                    <option
                                                        value="Privacy & Security"
                                                        {{ old('subject') === 'Privacy & Security' ? 'selected' : '' }}
                                                    >
                                                        Privacy & Security
                                                    </option>

                                                    <option
                                                        value="Partnership / Business Inquiry"
                                                        {{ old('subject') === 'Partnership / Business Inquiry' ? 'selected' : '' }}
                                                    >
                                                        Partnership / Business Inquiry
                                                    </option>

                                                    <option
                                                        value="Other"
                                                        {{ old('subject') === 'Other' ? 'selected' : '' }}
                                                    >
                                                        Other
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    {{-- Message --}}

                                    <div class="col-12">

                                        <div class="contact-field">

                                            <label for="message">
                                                Message
                                            </label>

                                            <div class="contact-textarea-wrap">

                                                <i class="bi bi-pencil-square"></i>

                                                <textarea
                                                    id="message"
                                                    name="message"
                                                    rows="7"
                                                    minlength="10"
                                                    maxlength="5000"
                                                    placeholder="Write your message here..."
                                                    required
                                                >{{ old('message') }}</textarea>

                                            </div>

                                        </div>

                                    </div>

                                    {{-- Form Footer --}}

                                    <div class="col-12">

                                        <div class="contact-form-footer">

                                            <p>
                                                <i class="bi bi-shield-check"></i>
                                                Your message will be handled securely.
                                            </p>

                                            <button
                                                type="submit"
                                                class="btn btn-accent contact-submit"
                                            >
                                                Send Message
                                                <i class="bi bi-arrow-right"></i>
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </form>

                        @else

                            {{-- Guest Login / Register Notice --}}

                            <div class="contact-auth-notice">

                                <div class="contact-auth-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>

                                <h3>Sign In to Contact Us</h3>

                                <p>
                                    To send a message to our team, please log
                                    in to your SecondBook account or create a
                                    new account. Your name and email address
                                    will be linked to your account.
                                </p>

                                <div class="contact-auth-actions">

                                    <a
                                        href="{{ route('frontend.auth.login') }}"
                                        class="btn btn-accent"
                                    >
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Login
                                    </a>

                                    <a
                                        href="{{ route('frontend.auth.register') }}"
                                        class="btn btn-outline-secondary"
                                    >
                                        <i class="bi bi-person-plus"></i>
                                        Register
                                    </a>

                                </div>

                            </div>

                        @endauth

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- =========================================================
        Bottom CTA
    ========================================================== --}}

    <section class="contact-cta">

        <div class="contact-cta-decoration contact-cta-decoration-1"></div>
        <div class="contact-cta-decoration contact-cta-decoration-2"></div>

        <div class="container">

            <div class="contact-cta-inner" data-aos="fade-up">

                {{-- CTA Content --}}

                <div class="contact-cta-content">

                    <div class="contact-cta-badge">

                        <i class="bi bi-book-half"></i>

                        <span>SecondBook marketplace</span>

                    </div>

                    <h2>
                        Your next
                        <em>great read</em>
                        is waiting.
                    </h2>

                    <p>
                        Explore affordable books from trusted sellers and
                        discover something new for your shelf.
                    </p>

                    <div class="contact-cta-actions">

                        <a
                            href="{{ route('frontend.books') }}"
                            class="contact-cta-button"
                        >
                            <span>Browse Books</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>

                {{-- CTA Visual --}}

                <div class="contact-cta-visual">

                    <div class="contact-cta-circle"></div>

                    <div class="contact-cta-book">

                        <div class="contact-cta-book-spine"></div>

                        <div class="contact-cta-book-content">

                            <i class="bi bi-book"></i>

                            <span>FIND YOUR</span>

                            <strong>
                                NEXT
                                <br>
                                CHAPTER
                            </strong>

                        </div>

                    </div>

                    <div class="contact-cta-floating">

                        <i class="bi bi-stars"></i>

                        <span>
                            Discover
                            <strong>something new.</strong>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>

</section>

@endsection
