@extends('Layout.Frontend.master')

@section('title', 'Cookie Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/cookies.css') }}">
@endpush

@section('content')

<main class="sb-cookies-page">

    <div class="container">

        {{-- =========================================================
             HERO
        ========================================================== --}}

        <section class="sb-cookies-hero">

            <div class="sb-cookies-eyebrow">

                <span class="sb-cookies-eyebrow-line"></span>

                <i class="bi bi-cookie"></i>

                <span>COOKIE INFORMATION</span>

                <span class="sb-cookies-eyebrow-line"></span>

            </div>

            <h1>
                Cookie <em>Policy</em>
            </h1>

            <p>
                Learn how SecondBook uses cookies and similar technologies
                to support essential functionality and improve your browsing
                experience.
            </p>

            <div class="sb-cookies-meta">

                <span>
                    <i class="bi bi-calendar3"></i>
                    Last updated: September 2026
                </span>

                <span class="sb-cookies-meta-divider"></span>

                <span>
                    <i class="bi bi-cookie"></i>
                    Your choices matter
                </span>

            </div>

        </section>


        {{-- =========================================================
             INTRO
        ========================================================== --}}

        <section class="sb-cookies-intro">

            <div class="sb-cookies-intro-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>

                <span class="sb-cookies-intro-label">
                    WELCOME TO SECONDBOOK
                </span>

                <h2>
                    A little information about cookies.
                </h2>

                <p>
                    This Cookie Policy explains what cookies are, how
                    SecondBook may use them, and how you can manage
                    cookie preferences through your browser.
                </p>

            </div>

        </section>


        {{-- =========================================================
             WHAT ARE COOKIES
        ========================================================== --}}

        <section class="sb-cookies-section">

            <div class="sb-cookies-section-heading">

                <span class="sb-cookies-section-label">
                    UNDERSTANDING COOKIES
                </span>

                <h2>
                    What are cookies<br>
                    and why are they used?
                </h2>

                <p>
                    Cookies are small text files stored on your device
                    when you visit a website. They help websites remember
                    information and support different features.
                </p>

            </div>


            <div class="sb-cookies-card-grid">

                <article class="sb-cookies-card">

                    <span class="sb-cookies-card-number">01</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <h3>Small Data Files</h3>

                    <p>
                        Cookies are small pieces of information that a
                        website can store in your browser for later use.
                    </p>

                </article>


                <article class="sb-cookies-card">

                    <span class="sb-cookies-card-number">02</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                    <h3>Remember Preferences</h3>

                    <p>
                        Cookies can help remember certain choices and
                        preferences so your experience can be more convenient.
                    </p>

                </article>


                <article class="sb-cookies-card">

                    <span class="sb-cookies-card-number">03</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <h3>Support Security</h3>

                    <p>
                        Certain cookies may support authentication,
                        session management, and important security features.
                    </p>

                </article>


                <article class="sb-cookies-card">

                    <span class="sb-cookies-card-number">04</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>

                    <h3>Improve Experience</h3>

                    <p>
                        Cookie-related information may help us understand
                        website usage and improve functionality.
                    </p>

                </article>

            </div>

        </section>


        {{-- =========================================================
             HOW WE USE COOKIES
        ========================================================== --}}

        <section class="sb-cookies-feature">

            <div class="sb-cookies-feature-icon">
                <i class="bi bi-gear"></i>
            </div>

            <div class="sb-cookies-feature-content">

                <span class="sb-cookies-section-label">
                    HOW WE USE COOKIES
                </span>

                <h2>
                    What can cookies help us do?
                </h2>


                <div class="sb-cookies-feature-grid">

                    <div class="sb-cookies-feature-item">

                        <span class="sb-cookies-feature-number">
                            01
                        </span>

                        <div>

                            <h3>Maintain sessions</h3>

                            <p>
                                To help maintain your session and support
                                account-related functionality.
                            </p>

                        </div>

                    </div>


                    <div class="sb-cookies-feature-item">

                        <span class="sb-cookies-feature-number">
                            02
                        </span>

                        <div>

                            <h3>Remember preferences</h3>

                            <p>
                                To remember selected preferences and
                                improve your browsing experience.
                            </p>

                        </div>

                    </div>


                    <div class="sb-cookies-feature-item">

                        <span class="sb-cookies-feature-number">
                            03
                        </span>

                        <div>

                            <h3>Support functionality</h3>

                            <p>
                                To help essential website features operate
                                correctly and consistently.
                            </p>

                        </div>

                    </div>


                    <div class="sb-cookies-feature-item">

                        <span class="sb-cookies-feature-number">
                            04
                        </span>

                        <div>

                            <h3>Understand usage</h3>

                            <p>
                                To help us understand how the website is
                                used and identify areas for improvement.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             TYPES OF COOKIES
        ========================================================== --}}

        <section class="sb-cookies-section sb-cookies-types">

            <div class="sb-cookies-types-heading">

                <span class="sb-cookies-section-label">
                    TYPES OF COOKIES
                </span>

                <h2>
                    Different cookies can<br>
                    serve different purposes.
                </h2>

                <p>
                    Cookies may be used for different functions depending
                    on what is required to operate and improve the website.
                </p>

            </div>


            <div class="sb-cookies-types-list">

                <div class="sb-cookies-types-item">

                    <span class="sb-cookies-types-icon">
                        <i class="bi bi-lock"></i>
                    </span>

                    <div>

                        <h3>Essential Cookies</h3>

                        <p>
                            These cookies may be necessary for core website
                            functionality, authentication, and security.
                        </p>

                    </div>

                </div>


                <div class="sb-cookies-types-item">

                    <span class="sb-cookies-types-icon">
                        <i class="bi bi-sliders"></i>
                    </span>

                    <div>

                        <h3>Preference Cookies</h3>

                        <p>
                            These cookies may remember preferences and
                            selected settings to provide a smoother experience.
                        </p>

                    </div>

                </div>


                <div class="sb-cookies-types-item">

                    <span class="sb-cookies-types-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>

                    <div>

                        <h3>Analytics Cookies</h3>

                        <p>
                            These cookies may help us understand website
                            usage and improve services and performance.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             COOKIE MANAGEMENT
        ========================================================== --}}

        <section class="sb-cookies-intro sb-cookies-management">

            <div class="sb-cookies-intro-icon">
                <i class="bi bi-sliders"></i>
            </div>

            <div>

                <span class="sb-cookies-intro-label">
                    MANAGING COOKIES
                </span>

                <h2>
                    You can manage cookies through your browser.
                </h2>

                <p>
                    Most browsers allow you to view, delete, block, or
                    restrict cookies through their settings. Disabling
                    certain cookies may affect some website functionality.
                </p>

                <p class="sb-cookies-secondary-text">
                    Your browser controls may vary depending on the browser
                    and device you use.
                </p>

            </div>

        </section>


        {{-- =========================================================
             POLICY NOTE
        ========================================================== --}}

        <section class="sb-cookies-note">

            <div class="sb-cookies-note-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>

                <span class="sb-cookies-section-label">
                    IMPORTANT
                </span>

                <h2>
                    This policy may be updated.
                </h2>

                <p>
                    As SecondBook grows and our services change, this
                    Cookie Policy may be updated from time to time.
                    Any updated version should replace the previous
                    version on this page.
                </p>

            </div>

        </section>


        {{-- =========================================================
             CONTACT
        ========================================================== --}}

        <section class="sb-cookies-contact">

            <div class="sb-cookies-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-cookies-contact-content">

                <span class="sb-cookies-section-label">
                    HAVE A QUESTION?
                </span>

                <h2>
                    Need more information about cookies?
                </h2>

                <p>
                    Contact the SecondBook team if you have questions
                    about this Cookie Policy or how cookies are used.
                </p>

            </div>

            <a href="{{ route('frontend.contact') }}"
               class="sb-cookies-contact-btn">

                Contact Support

                <i class="bi bi-arrow-right"></i>

            </a>

        </section>


        {{-- =========================================================
             BOTTOM META
        ========================================================== --}}

        <div class="sb-cookies-bottom">

            <span>
                <i class="bi bi-cookie"></i>
                Last Updated: September 2026
            </span>

            <span>
                support@secondbook.com
            </span>

        </div>

    </div>

</main>

@endsection

