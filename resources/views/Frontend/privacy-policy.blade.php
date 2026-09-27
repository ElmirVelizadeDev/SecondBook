@extends('Layout.Frontend.master')

@section('title', 'Privacy Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/privacy-policy.css') }}">
@endpush

@section('content')

<main class="sb-privacy-page">

    <div class="container">

        {{-- =========================================================
             HERO
        ========================================================== --}}
        <section class="sb-privacy-hero">

            <div class="sb-privacy-eyebrow">
                <span class="sb-privacy-eyebrow-line"></span>

                <i class="bi bi-shield-check"></i>

                <span>PRIVACY INFORMATION</span>

                <span class="sb-privacy-eyebrow-line"></span>
            </div>

            <h1>
                Privacy <em>Policy</em>
            </h1>

            <p>
                Learn how SecondBook collects, uses, protects, and manages
                your information while you use our marketplace.
            </p>

            <div class="sb-privacy-meta">

                <span>
                    <i class="bi bi-calendar3"></i>
                    Last updated: September 2026
                </span>

                <span class="sb-privacy-meta-divider"></span>

                <span>
                    <i class="bi bi-shield-check"></i>
                    Your privacy matters
                </span>

            </div>

        </section>


        {{-- =========================================================
             INTRO
        ========================================================== --}}
        <section class="sb-privacy-intro">

            <div class="sb-privacy-intro-icon">
                <i class="bi bi-lock"></i>
            </div>

            <div>

                <span class="sb-privacy-intro-label">
                    WELCOME TO SECONDBOOK
                </span>

                <h2>
                    Your information deserves careful handling.
                </h2>

                <p>
                    This Privacy Policy explains what information may be
                    collected when you use SecondBook, why it may be used,
                    and the choices available to you.
                </p>

            </div>

        </section>


        {{-- =========================================================
             INFORMATION WE COLLECT
        ========================================================== --}}
        <section class="sb-privacy-section">

            <div class="sb-privacy-section-heading">

                <span class="sb-privacy-section-label">
                    INFORMATION WE COLLECT
                </span>

                <h2>
                    What information may<br>
                    we collect?
                </h2>

                <p>
                    The information we collect depends on how you interact
                    with SecondBook and the services you use.
                </p>

            </div>


            <div class="sb-privacy-card-grid">

                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'account']) }}#faqSearch">

                    <span class="sb-privacy-card-number">01</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <h3>Account Information</h3>

                    <p>
                        Information you provide when creating or managing
                        your account, such as your name, email address,
                        phone number, and account details.
                    </p>

                </article>


                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'order']) }}#faqSearch">

                    <span class="sb-privacy-card-number">02</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-bag"></i>
                    </div>

                    <h3>Order Information</h3>

                    <p>
                        Information required to process purchases, deliveries,
                        payments, returns, and other marketplace transactions.
                    </p>

                </article>


                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'support']) }}#faqSearch">

                    <span class="sb-privacy-card-number">03</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>

                    <h3>Communication</h3>

                    <p>
                        Information you provide when contacting support,
                        submitting requests, sending messages, or communicating
                        with SecondBook.
                    </p>

                </article>


                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'privacy']) }}#faqSearch">

                    <span class="sb-privacy-card-number">04</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>

                    <h3>Usage Information</h3>

                    <p>
                        Technical and usage information may be collected to
                        help maintain website functionality, security, and
                        overall service performance.
                    </p>

                </article>

            </div>

        </section>


        {{-- =========================================================
             HOW WE USE INFORMATION
        ========================================================== --}}
        <section class="sb-privacy-feature">

            <div class="sb-privacy-feature-icon">
                <i class="bi bi-gear"></i>
            </div>

            <div class="sb-privacy-feature-content">

                <span class="sb-privacy-section-label">
                    HOW WE USE INFORMATION
                </span>

                <h2>
                    Why do we use your information?
                </h2>

                <div class="sb-privacy-feature-grid">

                    <div class="sb-privacy-feature-item">

                        <span class="sb-privacy-feature-number">01</span>

                        <div>
                            <h3>Provide our services</h3>

                            <p>
                                To create accounts, process orders,
                                support marketplace features, and provide
                                requested services.
                            </p>
                        </div>

                    </div>


                    <div class="sb-privacy-feature-item">

                        <span class="sb-privacy-feature-number">02</span>

                        <div>
                            <h3>Process transactions</h3>

                            <p>
                                To manage purchases, payments, shipping,
                                refunds, and related order activities.
                            </p>
                        </div>

                    </div>


                    <div class="sb-privacy-feature-item">

                        <span class="sb-privacy-feature-number">03</span>

                        <div>
                            <h3>Improve SecondBook</h3>

                            <p>
                                To understand how our services are used
                                and improve functionality and usability.
                            </p>
                        </div>

                    </div>


                    <div class="sb-privacy-feature-item">

                        <span class="sb-privacy-feature-number">04</span>

                        <div>
                            <h3>Protect our platform</h3>

                            <p>
                                To detect suspicious activity, maintain
                                security, and help prevent misuse.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             DATA PROTECTION
        ========================================================== --}}
        <section class="sb-privacy-section sb-privacy-protection">

            <div class="sb-privacy-protection-heading">

                <span class="sb-privacy-section-label">
                    DATA PROTECTION
                </span>

                <h2>
                    We take reasonable steps<br>
                    to protect your data.
                </h2>

                <p>
                    We use appropriate technical and organizational
                    measures intended to protect information against
                    unauthorized access, misuse, alteration, or loss.
                </p>

            </div>


            <div class="sb-privacy-protection-list">

                <div class="sb-privacy-protection-item">

                    <span class="sb-privacy-protection-icon">
                        <i class="bi bi-lock"></i>
                    </span>

                    <div>
                        <h3>Account security</h3>

                        <p>
                            Account information is handled with security
                            measures designed to help protect your access.
                        </p>
                    </div>

                </div>


                <div class="sb-privacy-protection-item">

                    <span class="sb-privacy-protection-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>

                    <div>
                        <h3>Limited access</h3>

                        <p>
                            Access to information should be limited to
                            purposes connected with providing our services.
                        </p>
                    </div>

                </div>


                <div class="sb-privacy-protection-item">

                    <span class="sb-privacy-protection-icon">
                        <i class="bi bi-eye-slash"></i>
                    </span>

                    <div>
                        <h3>Responsible handling</h3>

                        <p>
                            We aim to handle personal information in a
                            transparent and responsible manner.
                        </p>
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             COOKIES
        ========================================================== --}}
        <section class="sb-privacy-intro sb-privacy-cookies">

            <div class="sb-privacy-intro-icon">
                <i class="bi bi-cookie"></i>
            </div>

            <div>

                <span class="sb-privacy-intro-label">
                    COOKIES & TECHNOLOGIES
                </span>

                <h2>
                    About cookies.
                </h2>

                <p>
                    SecondBook may use cookies and similar technologies
                    to remember preferences, support essential website
                    functionality, maintain sessions, and understand
                    how the website is used.
                </p>

                <p class="sb-privacy-secondary-text">
                    Depending on your browser and settings, you may be
                    able to manage or restrict cookies through your
                    browser controls.
                </p>

            </div>

        </section>


        {{-- =========================================================
             YOUR RIGHTS
        ========================================================== --}}
        <section class="sb-privacy-section">

            <div class="sb-privacy-section-heading">

                <span class="sb-privacy-section-label">
                    YOUR PRIVACY CHOICES
                </span>

                <h2>
                    You have control<br>
                    over your information.
                </h2>

                <p>
                    Depending on applicable law, you may have rights regarding
                    the personal information associated with your account.
                </p>

            </div>


            <div class="sb-privacy-card-grid">

                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'access']) }}#faqSearch">

                    <span class="sb-privacy-card-number">01</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>

                    <h3>Access</h3>

                    <p>
                        Request information about the personal data associated
                        with your account.
                    </p>

                </article>


                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'account']) }}#faqSearch">

                    <span class="sb-privacy-card-number">02</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <h3>Update</h3>

                    <p>
                        Review and update certain account information through
                        your profile and account settings.
                    </p>

                </article>


                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'privacy']) }}#faqSearch">

                    <span class="sb-privacy-card-number">03</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-trash3"></i>
                    </div>

                    <h3>Deletion</h3>

                    <p>
                        Depending on applicable requirements, you may request
                        deletion of certain personal information.
                    </p>

                </article>


                <article class="sb-privacy-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'privacy']) }}#faqSearch">

                    <span class="sb-privacy-card-number">04</span>

                    <div class="sb-privacy-card-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

                    <h3>Questions</h3>

                    <p>
                        Contact us if you have questions about how your
                        information is handled.
                    </p>

                </article>

            </div>

        </section>


        {{-- =========================================================
             POLICY NOTE
        ========================================================== --}}
        <section class="sb-privacy-note">

            <div class="sb-privacy-note-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>

                <span class="sb-privacy-section-label">
                    IMPORTANT
                </span>

                <h2>
                    This policy may be updated.
                </h2>

                <p>
                    As SecondBook grows and our services change, this
                    Privacy Policy may be updated from time to time.
                    Any updated version should replace the previous
                    version on this page.
                </p>

            </div>

        </section>


        {{-- =========================================================
             CONTACT
        ========================================================== --}}
        <section class="sb-privacy-contact">

            <div class="sb-privacy-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-privacy-contact-content">

                <span class="sb-privacy-section-label">
                    HAVE A QUESTION?
                </span>

                <h2>
                    Need more information about privacy?
                </h2>

                <p>
                    Contact the SecondBook team if you have questions
                    about this Privacy Policy or your personal information.
                </p>

            </div>

            <a href="{{ route('frontend.contact') }}"
               class="sb-privacy-contact-btn">
                Contact Support
                <i class="bi bi-arrow-right"></i>
            </a>

        </section>


        {{-- =========================================================
             BOTTOM META
        ========================================================== --}}
        <div class="sb-privacy-bottom">

            <span>
                <i class="bi bi-shield-check"></i>
                Last Updated: September 2026
            </span>

            <span>
                support@secondbook.com
            </span>

        </div>

    </div>

</main>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const privacyCards = document.querySelectorAll(
        '.sb-privacy-card[data-faq-url]'
    );

    privacyCards.forEach(function (card) {

        card.addEventListener('click', function () {

            const url = card.dataset.faqUrl;

            if (url) {
                window.location.href = url;
            }

        });

        card.addEventListener('keydown', function (event) {

            if (event.key === 'Enter' || event.key === ' ') {

                event.preventDefault();

                const url = card.dataset.faqUrl;

                if (url) {
                    window.location.href = url;
                }

            }

        });

    });

});
</script>
@endpush
```
