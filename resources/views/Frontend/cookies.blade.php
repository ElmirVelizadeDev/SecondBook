
@extends('Layout.Frontend.master')

@section('title', 'Cookie Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/cookies.css') }}">
@endpush

@section('content')

<main class="sb-cookies-page">

    <div class="sb-cookies-progress" aria-hidden="true">
        <span id="sbCookiesProgressBar"></span>
    </div>

    <div class="container">

        {{-- =========================================================
             HERO
        ========================================================== --}}

        <section class="sb-cookies-hero" data-reveal>

            <div class="sb-cookies-hero-orb sb-cookies-hero-orb-one"></div>
            <div class="sb-cookies-hero-orb sb-cookies-hero-orb-two"></div>

            <div class="sb-cookies-eyebrow">
                <span class="sb-cookies-eyebrow-line"></span>
                <i class="bi bi-cookie"></i>
                <span>COOKIE INFORMATION</span>
                <span class="sb-cookies-eyebrow-line"></span>
            </div>

            <h1>Cookie <em>Policy</em></h1>

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

            <div class="sb-cookies-hero-decoration" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </section>


        {{-- =========================================================
             INTRO
        ========================================================== --}}

        <section class="sb-cookies-intro" data-reveal>

            <div class="sb-cookies-intro-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div class="sb-cookies-intro-content">
                <span class="sb-cookies-intro-label">
                    WELCOME TO SECONDBOOK
                </span>

                <h2>A little information about cookies.</h2>

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

            <div class="sb-cookies-section-heading" data-reveal>
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

                <article class="sb-cookies-card" data-reveal>
                    <span class="sb-cookies-card-number">01</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <h3>Small Data Files</h3>

                    <p>
                        Cookies are small pieces of information that a
                        website can store in your browser for later use.
                    </p>

                    <span class="sb-cookies-card-line"></span>
                </article>

                <article class="sb-cookies-card" data-reveal>
                    <span class="sb-cookies-card-number">02</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                    <h3>Remember Preferences</h3>

                    <p>
                        Cookies can help remember certain choices and
                        preferences so your experience can be more convenient.
                    </p>

                    <span class="sb-cookies-card-line"></span>
                </article>

                <article class="sb-cookies-card" data-reveal>
                    <span class="sb-cookies-card-number">03</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <h3>Support Security</h3>

                    <p>
                        Certain cookies may support authentication,
                        session management, and important security features.
                    </p>

                    <span class="sb-cookies-card-line"></span>
                </article>

                <article class="sb-cookies-card" data-reveal>
                    <span class="sb-cookies-card-number">04</span>

                    <div class="sb-cookies-card-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>

                    <h3>Improve Experience</h3>

                    <p>
                        Cookie-related information may help us understand
                        website usage and improve functionality.
                    </p>

                    <span class="sb-cookies-card-line"></span>
                </article>

            </div>

        </section>


        {{-- =========================================================
             HOW WE USE COOKIES
        ========================================================== --}}

        <section class="sb-cookies-feature" data-reveal>

            <div class="sb-cookies-feature-icon">
                <i class="bi bi-gear"></i>
            </div>

            <div class="sb-cookies-feature-content">

                <span class="sb-cookies-section-label">
                    HOW WE USE COOKIES
                </span>

                <h2>What can cookies help us do?</h2>

                <div class="sb-cookies-feature-grid">

                    <div class="sb-cookies-feature-item">
                        <span class="sb-cookies-feature-number">01</span>

                        <div>
                            <h3>Maintain sessions</h3>
                            <p>
                                To help maintain your session and support
                                account-related functionality.
                            </p>
                        </div>
                    </div>

                    <div class="sb-cookies-feature-item">
                        <span class="sb-cookies-feature-number">02</span>

                        <div>
                            <h3>Remember preferences</h3>
                            <p>
                                To remember selected preferences and
                                improve your browsing experience.
                            </p>
                        </div>
                    </div>

                    <div class="sb-cookies-feature-item">
                        <span class="sb-cookies-feature-number">03</span>

                        <div>
                            <h3>Support functionality</h3>
                            <p>
                                To help essential website features operate
                                correctly and consistently.
                            </p>
                        </div>
                    </div>

                    <div class="sb-cookies-feature-item">
                        <span class="sb-cookies-feature-number">04</span>

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

            <div class="sb-cookies-types-heading" data-reveal>
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

                <article class="sb-cookies-types-item" data-reveal>
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

                    <i class="bi bi-arrow-up-right sb-cookies-types-arrow"></i>
                </article>

                <article class="sb-cookies-types-item" data-reveal>
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

                    <i class="bi bi-arrow-up-right sb-cookies-types-arrow"></i>
                </article>

                <article class="sb-cookies-types-item" data-reveal>
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

                    <i class="bi bi-arrow-up-right sb-cookies-types-arrow"></i>
                </article>

            </div>

        </section>


        {{-- =========================================================
             COOKIE MANAGEMENT
        ========================================================== --}}

        <section class="sb-cookies-intro sb-cookies-management" data-reveal>

            <div class="sb-cookies-intro-icon">
                <i class="bi bi-sliders"></i>
            </div>

            <div class="sb-cookies-intro-content">
                <span class="sb-cookies-intro-label">
                    MANAGING COOKIES
                </span>

                <h2>You can manage cookies through your browser.</h2>

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

        <section class="sb-cookies-note" data-reveal>

            <div class="sb-cookies-note-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>
                <span class="sb-cookies-section-label">IMPORTANT</span>

                <h2>This policy may be updated.</h2>

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

        <section class="sb-cookies-contact" data-reveal>

            <div class="sb-cookies-contact-decoration" aria-hidden="true"></div>

            <div class="sb-cookies-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-cookies-contact-content">
                <span class="sb-cookies-section-label">HAVE A QUESTION?</span>

                <h2>Need more information about cookies?</h2>

                <p>
                    Contact the SecondBook team if you have questions
                    about this Cookie Policy or how cookies are used.
                </p>
            </div>

            <a href="{{ route('frontend.contact') }}"
               class="sb-cookies-contact-btn">

                <span>Contact Support</span>
                <i class="bi bi-arrow-right"></i>

            </a>

        </section>


        {{-- =========================================================
             BOTTOM META
        ========================================================== --}}

        <div class="sb-cookies-bottom" data-reveal>
            <span>
                <i class="bi bi-cookie"></i>
                Last Updated: September 2026
            </span>

            <span>support@secondbook.com</span>
        </div>

    </div>

</main>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.sb-cookies-page');

    if (!page) {
        return;
    }

    const progressBar = page.querySelector('#sbCookiesProgressBar');
    const revealItems = page.querySelectorAll('[data-reveal]');

    function updateProgress() {
        if (!progressBar) {
            return;
        }

        const pageTop = page.getBoundingClientRect().top + window.scrollY;
        const pageHeight = page.offsetHeight;
        const scrollableHeight = pageHeight - window.innerHeight;

        if (scrollableHeight <= 0) {
            progressBar.style.width = '100%';
            return;
        }

        const progress = Math.min(
            100,
            Math.max(0, ((window.scrollY - pageTop) / scrollableHeight) * 100)
        );

        progressBar.style.width = progress + '%';
    }

    if ('IntersectionObserver' in window) {
        page.classList.add('sb-cookies-reveal-enabled');

        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -35px 0px'
        });

        revealItems.forEach(function (item, index) {
            item.style.setProperty('--reveal-delay', (index % 4) * 90 + 'ms');
            revealObserver.observe(item);
        });
    } else {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
    }

    let ticking = false;

    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(function () {
                updateProgress();
                ticking = false;
            });

            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', updateProgress, { passive: true });

    updateProgress();
});
</script>
@endpush
