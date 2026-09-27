@extends('Layout.Frontend.master')

@section('title', 'Help Center | SecondBook')

@push('css')

<link rel="stylesheet" href="{{ asset('frontend-assets/css/help-center.css') }}">

@endpush

@section('content')

<main class="sb-help-page">

```
{{-- =========================================================
     HERO
========================================================== --}}

<section class="sb-help-hero">

    <div class="sb-help-hero-decoration sb-help-decoration-one"></div>
    <div class="sb-help-hero-decoration sb-help-decoration-two"></div>
    <div class="sb-help-hero-decoration sb-help-decoration-three"></div>

    <div class="container">

        <div class="sb-help-hero-content">

            <div class="sb-help-eyebrow">

                <span class="sb-help-eyebrow-icon">
                    <i class="bi bi-life-preserver"></i>
                </span>

                <span>SecondBook Help Center</span>

            </div>

            <h1>
                How can we
                <span>help?</span>
            </h1>

            <p>
                Find helpful guides, answers and information about
                buying books, selling books, orders, payments,
                shipping and your SecondBook account.
            </p>

            {{-- SEARCH --}}

            <div class="sb-help-search-wrapper">

                <div class="sb-help-search">

                    <span class="sb-help-search-icon">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        id="helpSearch"
                        placeholder="Search for help..."
                        autocomplete="off"
                        aria-label="Search Help Center"
                    >

                    <button
                        type="button"
                        class="sb-help-search-button"
                        aria-label="Search"
                    >
                        <span>Search</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </div>

            </div>

            <div class="sb-help-popular">

                <span>Popular:</span>

                <a href="{{ route('frontend.faq', ['search' => 'buying']) }}#faqSearch">
                    Buying a book
                </a>

                <a href="{{ route('frontend.faq', ['search' => 'selling']) }}#faqSearch">
                    Selling a book
                </a>

                <a href="{{ route('frontend.faq', ['search' => 'order']) }}#faqSearch">
                    Track my order
                </a>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     CATEGORIES
========================================================== --}}

<section
    class="sb-help-categories-section"
    id="helpCategories"
>

    <div class="container">

        <div class="sb-help-section-heading">

            <div>

                <span class="sb-help-section-label">
                    <i class="bi bi-grid"></i>
                    Help Topics
                </span>

                <h2>
                    What can we
                    <span>help you with?</span>
                </h2>

            </div>

            <p>
                Choose a topic to find useful information
                and helpful resources.
            </p>

        </div>



        <div class="row g-4">

            {{-- BUYING --}}

            <div class="col-12 col-md-6 col-xl-4">

                <a
                    href="{{ route('frontend.faq', ['search' => 'buying']) }}#faqSearch"
                    class="sb-help-category-card"
                >

                    <span class="sb-help-category-number">
                        01
                    </span>

                    <span class="sb-help-category-icon">
                        <i class="bi bi-bag"></i>
                    </span>

                    <span class="sb-help-category-content">

                        <strong>
                            Buying Books
                        </strong>

                        <span>
                            Learn how to find, choose and
                            purchase books on SecondBook.
                        </span>

                        <small>
                            Explore topic
                            <i class="bi bi-arrow-up-right"></i>
                        </small>

                    </span>

                </a>

            </div>



            {{-- SELLING --}}

            <div class="col-12 col-md-6 col-xl-4">

                <a
                    href="{{ route('frontend.faq', ['search' => 'selling']) }}#faqSearch"
                    class="sb-help-category-card"
                >

                    <span class="sb-help-category-number">
                        02
                    </span>

                    <span class="sb-help-category-icon">
                        <i class="bi bi-shop"></i>
                    </span>

                    <span class="sb-help-category-content">

                        <strong>
                            Selling Books
                        </strong>

                        <span>
                            Learn how to list your books,
                            manage sales and reach buyers.
                        </span>

                        <small>
                            Explore topic
                            <i class="bi bi-arrow-up-right"></i>
                        </small>

                    </span>

                </a>

            </div>



            {{-- ORDERS --}}

            <div class="col-12 col-md-6 col-xl-4">

                <a
                    href="{{ route('frontend.faq', ['search' => 'order']) }}#faqSearch"
                    class="sb-help-category-card"
                >

                    <span class="sb-help-category-number">
                        03
                    </span>

                    <span class="sb-help-category-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <span class="sb-help-category-content">

                        <strong>
                            Orders
                        </strong>

                        <span>
                            Get help with order status,
                            tracking and order details.
                        </span>

                        <small>
                            Explore topic
                            <i class="bi bi-arrow-up-right"></i>
                        </small>

                    </span>

                </a>

            </div>



            {{-- PAYMENTS --}}

            <div class="col-12 col-md-6 col-xl-4">

                <a
                    href="{{ route('frontend.faq', ['search' => 'payment']) }}#faqSearch"
                    class="sb-help-category-card"
                >

                    <span class="sb-help-category-number">
                        04
                    </span>

                    <span class="sb-help-category-icon">
                        <i class="bi bi-credit-card"></i>
                    </span>

                    <span class="sb-help-category-content">

                        <strong>
                            Payments
                        </strong>

                        <span>
                            Find information about payment
                            methods, charges and refunds.
                        </span>

                        <small>
                            Explore topic
                            <i class="bi bi-arrow-up-right"></i>
                        </small>

                    </span>

                </a>

            </div>



            {{-- ACCOUNT --}}

            <div class="col-12 col-md-6 col-xl-4">

                <a
                    href="{{ route('frontend.faq', ['search' => 'account']) }}#faqSearch"
                    class="sb-help-category-card"
                >

                    <span class="sb-help-category-number">
                        05
                    </span>

                    <span class="sb-help-category-icon">
                        <i class="bi bi-person"></i>
                    </span>

                    <span class="sb-help-category-content">

                        <strong>
                            Account
                        </strong>

                        <span>
                            Manage your profile, password,
                            settings and account information.
                        </span>

                        <small>
                            Explore topic
                            <i class="bi bi-arrow-up-right"></i>
                        </small>

                    </span>

                </a>

            </div>



            {{-- SHIPPING --}}

            <div class="col-12 col-md-6 col-xl-4">

                <a
                    href="{{ route('frontend.faq', ['search' => 'shipping']) }}#faqSearch"
                    class="sb-help-category-card"
                >

                    <span class="sb-help-category-number">
                        06
                    </span>

                    <span class="sb-help-category-icon">
                        <i class="bi bi-truck"></i>
                    </span>

                    <span class="sb-help-category-content">

                        <strong>
                            Shipping
                        </strong>

                        <span>
                            Learn about delivery, shipping
                            information and order arrival.
                        </span>

                        <small>
                            Explore topic
                            <i class="bi bi-arrow-up-right"></i>
                        </small>

                    </span>

                </a>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     POPULAR QUESTIONS
========================================================== --}}

<section class="sb-help-popular-section">

    <div class="container">

        <div class="sb-help-popular-layout">

            <div class="sb-help-popular-intro">

                <span class="sb-help-section-label">
                    <i class="bi bi-stars"></i>
                    Popular Questions
                </span>

                <h2>
                    Answers to the
                    <span>most common questions.</span>
                </h2>

                <p>
                    Looking for a quick answer? Browse some of
                    the questions users ask most often.
                </p>

                <a
                    href="{{ route('frontend.faq') }}#faqSearch"
                    class="sb-help-outline-btn"
                >
                    <span>View All FAQs</span>
                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>



            <div class="sb-help-question-list">

                <a
                    href="{{ route('frontend.faq', ['search' => 'buying']) }}#faqSearch"
                    class="sb-help-question"
                >

                    <span class="sb-help-question-index">
                        01
                    </span>

                    <span class="sb-help-question-icon">
                        <i class="bi bi-question-lg"></i>
                    </span>

                    <span class="sb-help-question-content">

                        <strong>
                            How do I buy a book?
                        </strong>

                        <small>
                            Learn more about finding and
                            purchasing books.
                        </small>

                    </span>

                    <i class="bi bi-arrow-up-right sb-help-question-arrow"></i>

                </a>



                <a
                    href="{{ route('frontend.faq', ['search' => 'selling']) }}#faqSearch"
                    class="sb-help-question"
                >

                    <span class="sb-help-question-index">
                        02
                    </span>

                    <span class="sb-help-question-icon">
                        <i class="bi bi-question-lg"></i>
                    </span>

                    <span class="sb-help-question-content">

                        <strong>
                            How can I sell my book?
                        </strong>

                        <small>
                            Learn how to list your books
                            for other users.
                        </small>

                    </span>

                    <i class="bi bi-arrow-up-right sb-help-question-arrow"></i>

                </a>



                <a
                    href="{{ route('frontend.faq', ['search' => 'order']) }}#faqSearch"
                    class="sb-help-question"
                >

                    <span class="sb-help-question-index">
                        03
                    </span>

                    <span class="sb-help-question-icon">
                        <i class="bi bi-question-lg"></i>
                    </span>

                    <span class="sb-help-question-content">

                        <strong>
                            Where can I see my orders?
                        </strong>

                        <small>
                            Find your order history and
                            current order status.
                        </small>

                    </span>

                    <i class="bi bi-arrow-up-right sb-help-question-arrow"></i>

                </a>



                <a
                    href="{{ route('frontend.faq', ['search' => 'payment']) }}#faqSearch"
                    class="sb-help-question"
                >

                    <span class="sb-help-question-index">
                        04
                    </span>

                    <span class="sb-help-question-icon">
                        <i class="bi bi-question-lg"></i>
                    </span>

                    <span class="sb-help-question-content">

                        <strong>
                            What payment methods are available?
                        </strong>

                        <small>
                            Learn about the payment options
                            available on SecondBook.
                        </small>

                    </span>

                    <i class="bi bi-arrow-up-right sb-help-question-arrow"></i>

                </a>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     QUICK GUIDES
========================================================== --}}

<section class="sb-help-guides-section">

    <div class="container">

        <div class="sb-help-section-heading">

            <div>

                <span class="sb-help-section-label">
                    <i class="bi bi-journal-text"></i>
                    Quick Guides
                </span>

                <h2>
                    Helpful information,
                    <span>made simple.</span>
                </h2>

            </div>

            <p>
                A few simple guides to help you get started
                with SecondBook.
            </p>

        </div>



        <div class="row g-4">

            {{-- GUIDE 01 --}}

            <div class="col-12 col-md-4">

                <div class="sb-help-guide-card">

                    <div class="sb-help-guide-top">

                        <span class="sb-help-guide-number">
                            01
                        </span>

                        <span class="sb-help-guide-icon">
                            <i class="bi bi-search"></i>
                        </span>

                    </div>

                    <h3>
                        Find your next book
                    </h3>

                    <p>
                        Browse categories, authors and books
                        to discover something worth reading.
                    </p>

                    <a href="{{ route('frontend.books') }}">
                        Browse Books
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>



            {{-- GUIDE 02 --}}

            <div class="col-12 col-md-4">

                <div class="sb-help-guide-card">

                    <div class="sb-help-guide-top">

                        <span class="sb-help-guide-number">
                            02
                        </span>

                        <span class="sb-help-guide-icon">
                            <i class="bi bi-shop"></i>
                        </span>

                    </div>

                    <h3>
                        Start selling
                    </h3>

                    <p>
                        Turn books you no longer need into
                        opportunities for other readers.
                    </p>

                    <a href="{{ route('frontend.faq', ['search' => 'selling']) }}#faqSearch">
                        Learn About Selling
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>



            {{-- GUIDE 03 --}}

            <div class="col-12 col-md-4">

                <div class="sb-help-guide-card">

                    <div class="sb-help-guide-top">

                        <span class="sb-help-guide-number">
                            03
                        </span>

                        <span class="sb-help-guide-icon">
                            <i class="bi bi-person-check"></i>
                        </span>

                    </div>

                    <h3>
                        Manage your account
                    </h3>

                    <p>
                        Keep your profile, personal details
                        and account settings up to date.
                    </p>

                    @auth

                        <a href="{{ route('frontend.account.settings') }}">
                            View Account Settings
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    @else

                        <a href="{{ route('frontend.auth.login') }}">
                            Sign In
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     CONTACT CTA
========================================================== --}}

<section class="sb-help-contact">

    <div class="container">

        <div class="sb-help-contact-box">

            <div class="sb-help-contact-decoration"></div>
            <div class="sb-help-contact-orbit"></div>

            <div class="sb-help-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-help-contact-content">

                <span>
                    Still need assistance?
                </span>

                <h2>
                    We're here to help.
                </h2>

                <p>
                    If you couldn't find what you were looking for,
                    our support team is ready to assist you.
                </p>

            </div>

            <a
                href="{{ route('frontend.contact') }}"
                class="sb-help-contact-btn"
            >
                <span>Contact Support</span>
                <i class="bi bi-arrow-up-right"></i>
            </a>

        </div>

    </div>

</section>
```

</main>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('helpSearch');

    if (!searchInput) {
        return;
    }

    const searchButton =
        document.querySelector('.sb-help-search-button');

    const performSearch = function () {

        const value =
            searchInput.value.trim();

        if (!value) {
            searchInput.focus();
            return;
        }

        const faqUrl =
            @json(route('frontend.faq'));

        window.location.href =
            faqUrl +
            '?search=' +
            encodeURIComponent(value) +
            '#faqSearch';
    };


    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();

            performSearch();

        }
    );


    if (searchButton) {

        searchButton.addEventListener(
            'click',
            function () {

                performSearch();

            }
        );

    }

});
</script>

@endpush
