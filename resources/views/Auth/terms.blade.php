@extends('Layout.Frontend.master')

@section('title', 'Terms & Conditions | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/terms.css') }}">
@endpush

@section('content')

<main class="terms-page">

    <div class="container">

        {{-- HERO --}}
        <section class="terms-hero">

            <div class="terms-eyebrow">
                <span class="terms-eyebrow-line"></span>
                <i class="bi bi-file-earmark-text"></i>
                <span>LEGAL INFORMATION</span>
                <span class="terms-eyebrow-line"></span>
            </div>

            <h1>
                Terms <em>&</em> Conditions
            </h1>

            <p>
                Please take a moment to understand the terms that govern
                your experience with SecondBook.
            </p>

            <div class="terms-meta">
                <span>
                    <i class="bi bi-calendar3"></i>
                    Last updated: August 2026
                </span>

                <span class="terms-meta-divider"></span>

                <span>
                    <i class="bi bi-shield-check"></i>
                    Your trust matters
                </span>
            </div>

        </section>


        {{-- INTRO CARD --}}
        <section class="terms-intro">

            <div class="terms-intro-icon">
                <i class="bi bi-book-half"></i>
            </div>

            <div>
                <span class="terms-intro-label">WELCOME TO SECONDBOOK</span>

                <h2>
                    A simple agreement for a better marketplace.
                </h2>

                <p>
                    These Terms and Conditions explain the rules and
                    responsibilities that apply when using SecondBook,
                    our marketplace for buying and selling second-hand books.
                </p>
            </div>

        </section>


        {{-- TERMS --}}
        <div class="terms-grid">

            {{-- 01 --}}
            <article class="terms-card terms-card-wide">

                <div class="terms-number">01</div>

                <div class="terms-card-content">

                    <div class="terms-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <div>
                        <h2>Acceptance of Terms</h2>

                        <p>
                            By accessing or using SecondBook, you agree to be
                            bound by these Terms and Conditions and all
                            applicable laws and regulations. If you do not
                            agree with any part of these terms, you must not
                            use our platform.
                        </p>
                    </div>

                </div>

            </article>


            {{-- 02 --}}
            <article class="terms-card">

                <div class="terms-number">02</div>

                <div class="terms-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <h2>User Accounts</h2>

                <p>
                    You are responsible for maintaining the confidentiality
                    of your account credentials and for all activity conducted
                    under your account. You must provide accurate information
                    and promptly update any changes to your profile.
                </p>

            </article>


            {{-- 03 --}}
            <article class="terms-card">

                <div class="terms-number">03</div>

                <div class="terms-icon">
                    <i class="bi bi-cart3"></i>
                </div>

                <h2>Buying Books</h2>

                <p>
                    Buyers may purchase books listed on SecondBook subject to
                    availability, pricing, and the seller’s description. We
                    recommend reviewing the listing details and condition
                    notes before completing a purchase.
                </p>

            </article>


            {{-- 04 --}}
            <article class="terms-card">

                <div class="terms-number">04</div>

                <div class="terms-icon">
                    <i class="bi bi-bag-plus"></i>
                </div>

                <h2>Selling Books</h2>

                <p>
                    Sellers are responsible for accurately describing each
                    book, including its condition, edition, and any defects.
                    SecondBook does not verify the authenticity of every item
                    beyond the information provided by the seller.
                </p>

            </article>


            {{-- 05 --}}
            <article class="terms-card">

                <div class="terms-number">05</div>

                <div class="terms-icon">
                    <i class="bi bi-book"></i>
                </div>

                <h2>Book Listings</h2>

                <p>
                    Listings must be honest, relevant, and compliant with our
                    marketplace standards. Misleading titles, duplicate
                    listings, or content that violates intellectual property
                    rights may be removed.
                </p>

            </article>


            {{-- 06 --}}
            <article class="terms-card">

                <div class="terms-number">06</div>

                <div class="terms-icon">
                    <i class="bi bi-credit-card"></i>
                </div>

                <h2>Payments</h2>

                <p>
                    Payments for completed transactions must be made through
                    the approved methods available on the platform.
                    SecondBook is not responsible for transactions conducted
                    outside the designated payment process.
                </p>

            </article>


            {{-- 07 --}}
            <article class="terms-card">

                <div class="terms-number">07</div>

                <div class="terms-icon">
                    <i class="bi bi-truck"></i>
                </div>

                <h2>Shipping & Delivery</h2>

                <p>
                    Shipping timelines and delivery responsibilities may vary
                    based on the seller’s location and chosen shipping method.
                    Buyers and sellers are expected to communicate clearly
                    regarding dispatch and delivery status.
                </p>

            </article>


            {{-- 08 --}}
            <article class="terms-card">

                <div class="terms-number">08</div>

                <div class="terms-icon">
                    <i class="bi bi-arrow-return-left"></i>
                </div>

                <h2>Returns & Refunds</h2>

                <p>
                    Returns and refunds are handled in accordance with the
                    applicable listing terms and our support policies.
                    Requests must be submitted promptly with relevant evidence
                    and order information.
                </p>

            </article>


            {{-- 09 --}}
            <article class="terms-card">

                <div class="terms-number">09</div>

                <div class="terms-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <h2>User Responsibilities</h2>

                <p>
                    Users must act honestly, respectfully, and lawfully while
                    using SecondBook. Any abusive behavior, deceptive
                    practices, or harassment may result in enforcement action.
                </p>

            </article>


            {{-- 10 --}}
            <article class="terms-card">

                <div class="terms-number">10</div>

                <div class="terms-icon">
                    <i class="bi bi-slash-circle"></i>
                </div>

                <h2>Prohibited Activities</h2>

                <p>
                    The following are prohibited: fraud, impersonation, spam,
                    unauthorized reselling, copyright infringement, and any
                    activity that disrupts the integrity of the marketplace.
                </p>

            </article>


            {{-- 11 --}}
            <article class="terms-card">

                <div class="terms-number">11</div>

                <div class="terms-icon">
                    <i class="bi bi-cpu"></i>
                </div>

                <h2>Intellectual Property</h2>

                <p>
                    All platform content, branding, and design elements are
                    owned by SecondBook or its licensors. Users may not copy,
                    reproduce, or distribute our materials without
                    authorization.
                </p>

            </article>


            {{-- 12 --}}
            <article class="terms-card">

                <div class="terms-number">12</div>

                <div class="terms-icon">
                    <i class="bi bi-lock"></i>
                </div>

                <h2>Privacy</h2>

                <p>
                    We collect and process personal information only as
                    necessary to operate the marketplace and provide support.
                    Please review our Privacy Policy for more details on how
                    your data is managed.
                </p>

            </article>


            {{-- 13 --}}
            <article class="terms-card">

                <div class="terms-number">13</div>

                <div class="terms-icon">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>

                <h2>Account Suspension</h2>

                <p>
                    SecondBook reserves the right to suspend or terminate
                    accounts that violate these terms, engage in fraudulent
                    activity, or compromise the safety of other users.
                </p>

            </article>


            {{-- 14 --}}
            <article class="terms-card">

                <div class="terms-number">14</div>

                <div class="terms-icon">
                    <i class="bi bi-brightness-high"></i>
                </div>

                <h2>Limitation of Liability</h2>

                <p>
                    SecondBook shall not be liable for indirect, incidental,
                    or consequential damages arising from your use of the
                    platform, except where prohibited by law.
                </p>

            </article>


            {{-- 15 --}}
            <article class="terms-card">

                <div class="terms-number">15</div>

                <div class="terms-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>

                <h2>Changes to These Terms</h2>

                <p>
                    We may update these Terms and Conditions from time to
                    time. Continued use of the platform after any changes
                    constitutes your acceptance of the revised terms.
                </p>

            </article>


            {{-- 16 --}}
            <article class="terms-card">

                <div class="terms-number">16</div>

                <div class="terms-icon">
                    <i class="bi bi-envelope"></i>
                </div>

                <h2>Contact Information</h2>

                <p>
                    For questions or concerns about these terms, please
                    contact us at
                    <a href="mailto:support@secondbook.com">
                        support@secondbook.com
                    </a>.
                </p>

            </article>

        </div>


        {{-- FOOTER NOTE --}}
        <section class="terms-footer">

            <div class="terms-footer-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>
                <strong>Questions about these terms?</strong>

                <p>
                    We're here to help. Contact our support team if you need
                    clarification about any part of these Terms & Conditions.
                </p>
            </div>

            <a href="mailto:support@secondbook.com" class="terms-contact-btn">
                <i class="bi bi-envelope"></i>
                Contact Support
            </a>

        </section>


        <div class="terms-bottom">

            <span>
                <i class="bi bi-shield-check"></i>
                Last Updated: August 2026
            </span>

            <span>
                support@secondbook.com
            </span>

        </div>

    </div>

</main>

@endsection