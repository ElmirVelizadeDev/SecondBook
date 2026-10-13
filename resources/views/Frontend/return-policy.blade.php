
@extends('Layout.Frontend.master')

@section('title', 'Return Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/return-policy.css') }}">
@endpush

@section('content')
<div class="sb-return-progress" aria-hidden="true"></div>

<main class="sb-return-page">
    {{-- =========================================================
         HERO
    ========================================================== --}}
    <section class="sb-return-hero">
        <div class="sb-return-hero-bg" aria-hidden="true">
            <span class="sb-return-orb sb-return-orb-1"></span>
            <span class="sb-return-orb sb-return-orb-2"></span>
            <span class="sb-return-ring sb-return-ring-1"></span>
            <span class="sb-return-ring sb-return-ring-2"></span>
        </div>

        <div class="container">
            <div class="sb-return-hero-inner" data-reveal>
                <div class="sb-return-eyebrow">
                    <span class="sb-return-eyebrow-line"></span>
                    <i class="bi bi-arrow-return-left" aria-hidden="true"></i>
                    <span>RETURN INFORMATION</span>
                    <span class="sb-return-eyebrow-line"></span>
                </div>

                <h1>Returns <em>&amp; Refunds</em></h1>

                <p>
                    We want every SecondBook purchase to feel clear and confident.
                    Here's what you need to know about returns, eligibility, and refunds.
                </p>

                <div class="sb-return-meta">
                    <span>
                        <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                        Simple return process
                    </span>

                    <span class="sb-return-meta-divider" aria-hidden="true"></span>

                    <span>
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        We're here to help
                    </span>
                </div>
            </div>
        </div>

        <div class="sb-return-hero-bottom-line" aria-hidden="true"></div>
    </section>

    <div class="container sb-return-container">
        {{-- =========================================================
             INTRO
        ========================================================== --}}
        <section class="sb-return-intro" data-reveal>
            <div class="sb-return-intro-icon">
                <i class="bi bi-box-seam" aria-hidden="true"></i>
            </div>

            <div class="sb-return-intro-content">
                <span class="sb-return-intro-label">WELCOME TO SECONDBOOK</span>

                <h2>A simple process when something goes wrong.</h2>

                <p>
                    If your order does not match the listing or arrives with a serious issue,
                    you can contact our support team and request a return review.
                </p>
            </div>

            <span class="sb-return-intro-decoration" aria-hidden="true">
                <i class="bi bi-book-half"></i>
            </span>
        </section>

        {{-- =========================================================
             RETURN PROCESS
        ========================================================== --}}
        <section class="sb-return-section" id="return-process">
            <div class="sb-return-section-heading" data-reveal>
                <span class="sb-return-section-label">RETURN OVERVIEW</span>

                <h2>What happens when<br>you need to return a book?</h2>

                <p>
                    Our return process is designed to keep each request clear,
                    organized, and easy to understand.
                </p>
            </div>

            <div class="sb-return-card-grid">
                <article class="sb-return-card" data-reveal>
                    <span class="sb-return-card-number">01</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                    </div>

                    <h3>Contact us</h3>

                    <p>
                        Get in touch with our support team and tell us what went wrong
                        with your order.
                    </p>

                    <span class="sb-return-card-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>

                <article class="sb-return-card" data-reveal>
                    <span class="sb-return-card-number">02</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-search" aria-hidden="true"></i>
                    </div>

                    <h3>We review the request</h3>

                    <p>
                        We review the order details and the reason for the return
                        to determine the appropriate next step.
                    </p>

                    <span class="sb-return-card-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>

                <article class="sb-return-card" data-reveal>
                    <span class="sb-return-card-number">03</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-box-seam" aria-hidden="true"></i>
                    </div>

                    <h3>Return the book</h3>

                    <p>
                        If the return is approved, you will receive instructions
                        for sending the book back.
                    </p>

                    <span class="sb-return-card-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>

                <article class="sb-return-card" data-reveal>
                    <span class="sb-return-card-number">04</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-wallet2" aria-hidden="true"></i>
                    </div>

                    <h3>Refund is processed</h3>

                    <p>
                        Once the return is completed and approved,
                        the applicable refund will be processed.
                    </p>

                    <span class="sb-return-card-bottom" aria-hidden="true">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </article>
            </div>
        </section>

        {{-- =========================================================
             ELIGIBILITY
        ========================================================== --}}
        <section class="sb-return-section" id="return-eligibility">
            <div class="sb-return-section-heading" data-reveal>
                <span class="sb-return-section-label">RETURN ELIGIBILITY</span>

                <h2>When can a book<br>be returned?</h2>

                <p>
                    Returns are reviewed according to the condition of the order
                    and the information provided in the listing.
                </p>
            </div>

            <div class="sb-return-eligibility-grid">
                <article class="sb-return-policy-card sb-return-policy-card-positive" data-reveal>
                    <div class="sb-return-policy-icon">
                        <i class="bi bi-check2-circle" aria-hidden="true"></i>
                    </div>

                    <div class="sb-return-policy-content">
                        <h3>Eligible situations</h3>

                        <ul>
                            <li>
                                <i class="bi bi-check" aria-hidden="true"></i>
                                <span>The book is significantly different from its listing.</span>
                            </li>
                            <li>
                                <i class="bi bi-check" aria-hidden="true"></i>
                                <span>The received book has undisclosed damage.</span>
                            </li>
                            <li>
                                <i class="bi bi-check" aria-hidden="true"></i>
                                <span>The wrong book was delivered.</span>
                            </li>
                            <li>
                                <i class="bi bi-check" aria-hidden="true"></i>
                                <span>The order arrived with a serious issue.</span>
                            </li>
                        </ul>
                    </div>
                </article>

                <article class="sb-return-policy-card sb-return-policy-card-neutral" data-reveal>
                    <div class="sb-return-policy-icon">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                    </div>

                    <div class="sb-return-policy-content">
                        <h3>Situations that may not qualify</h3>

                        <ul>
                            <li>
                                <i class="bi bi-dash" aria-hidden="true"></i>
                                <span>You simply changed your mind after receiving the book.</span>
                            </li>
                            <li>
                                <i class="bi bi-dash" aria-hidden="true"></i>
                                <span>Minor signs of normal second-hand use were already described.</span>
                            </li>
                            <li>
                                <i class="bi bi-dash" aria-hidden="true"></i>
                                <span>The book matches the condition shown in the listing.</span>
                            </li>
                            <li>
                                <i class="bi bi-dash" aria-hidden="true"></i>
                                <span>The return request does not meet the applicable return requirements.</span>
                            </li>
                        </ul>
                    </div>
                </article>
            </div>
        </section>

        {{-- =========================================================
             GOOD TO KNOW
        ========================================================== --}}
        <section class="sb-return-feature" data-reveal>
            <div class="sb-return-feature-icon">
                <i class="bi bi-lightbulb" aria-hidden="true"></i>
            </div>

            <div class="sb-return-feature-content">
                <span class="sb-return-section-label">GOOD TO KNOW</span>

                <h2>A few things to keep in mind</h2>

                <div class="sb-return-feature-list">
                    <div class="sb-return-feature-item" data-reveal>
                        <span class="sb-return-feature-number">01</span>
                        <div>
                            <h3>Keep your order information</h3>
                            <p>Have your order details available when contacting our support team.</p>
                        </div>
                    </div>

                    <div class="sb-return-feature-item" data-reveal>
                        <span class="sb-return-feature-number">02</span>
                        <div>
                            <h3>Provide photos when useful</h3>
                            <p>Photos can help us understand issues concerning the condition of a book.</p>
                        </div>
                    </div>

                    <div class="sb-return-feature-item" data-reveal>
                        <span class="sb-return-feature-number">03</span>
                        <div>
                            <h3>Follow return instructions</h3>
                            <p>Approved return requests should follow the instructions provided by our team.</p>
                        </div>
                    </div>

                    <div class="sb-return-feature-item" data-reveal>
                        <span class="sb-return-feature-number">04</span>
                        <div>
                            <h3>Refund timing may vary</h3>
                            <p>Processing time can depend on the payment method and return review process.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- =========================================================
             FAQ
        ========================================================== --}}
        <section class="sb-return-section" id="return-faq">
            <div class="sb-return-section-heading" data-reveal>
                <span class="sb-return-section-label">RETURN QUESTIONS</span>

                <h2>Frequently asked<br>return questions.</h2>
            </div>

            <div class="sb-return-faq-grid">
                <article
                    class="sb-return-faq-card"
                    data-faq-url="{{ route('frontend.faq', ['search' => 'refund']) }}#faqSearch"
                    role="link"
                    tabindex="0"
                    data-reveal
                >
                    <div class="sb-return-faq-icon">
                        <i class="bi bi-clock-history" aria-hidden="true"></i>
                    </div>

                    <div class="sb-return-faq-content">
                        <h3>How long does a refund take?</h3>
                        <p>Refund processing time can vary depending on the payment method and the status of the return.</p>
                    </div>

                    <i class="bi bi-arrow-up-right sb-return-faq-arrow" aria-hidden="true"></i>
                </article>

                <article
                    class="sb-return-faq-card"
                    data-faq-url="{{ route('frontend.faq', ['search' => 'photos']) }}#faqSearch"
                    role="link"
                    tabindex="0"
                    data-reveal
                >
                    <div class="sb-return-faq-icon">
                        <i class="bi bi-camera" aria-hidden="true"></i>
                    </div>

                    <div class="sb-return-faq-content">
                        <h3>Should I provide photos?</h3>
                        <p>If the issue is related to the book's condition, photos can help our team understand the problem.</p>
                    </div>

                    <i class="bi bi-arrow-up-right sb-return-faq-arrow" aria-hidden="true"></i>
                </article>

                <article
                    class="sb-return-faq-card"
                    data-faq-url="{{ route('frontend.faq', ['search' => 'return']) }}#faqSearch"
                    role="link"
                    tabindex="0"
                    data-reveal
                >
                    <div class="sb-return-faq-icon">
                        <i class="bi bi-person-check" aria-hidden="true"></i>
                    </div>

                    <div class="sb-return-faq-content">
                        <h3>Who reviews my request?</h3>
                        <p>Our support team reviews the order information and the reason provided with the return request.</p>
                    </div>

                    <i class="bi bi-arrow-up-right sb-return-faq-arrow" aria-hidden="true"></i>
                </article>

                <article
                    class="sb-return-faq-card"
                    data-faq-url="{{ route('frontend.faq') }}"
                    role="link"
                    tabindex="0"
                    data-reveal
                >
                    <div class="sb-return-faq-icon">
                        <i class="bi bi-question-circle" aria-hidden="true"></i>
                    </div>

                    <div class="sb-return-faq-content">
                        <h3>Still have questions?</h3>
                        <p>Visit our FAQ page or contact the SecondBook support team for further assistance.</p>
                    </div>

                    <i class="bi bi-arrow-up-right sb-return-faq-arrow" aria-hidden="true"></i>
                </article>
            </div>
        </section>

        {{-- =========================================================
             CONTACT CTA
        ========================================================== --}}
        <section class="sb-return-contact" data-reveal>
            <div class="sb-return-contact-icon">
                <i class="bi bi-headset" aria-hidden="true"></i>
            </div>

            <div class="sb-return-contact-content">
                <span class="sb-return-section-label">NEED HELP?</span>

                <h2>Something not right with your order?</h2>

                <p>
                    Our support team is here to help you understand your return options
                    and next steps.
                </p>
            </div>

            <div class="sb-return-contact-actions">
                <a href="{{ route('frontend.contact') }}" class="sb-return-contact-btn">
                    <span>Contact Support</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>

                <a href="{{ route('frontend.faq') }}" class="sb-return-contact-link">
                    View FAQ
                    <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        {{-- =========================================================
             BOTTOM META
        ========================================================== --}}
        <div class="sb-return-bottom" data-reveal>
            <span>
                <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                Simple returns, clear communication
            </span>

            <a href="mailto:support@secondbook.com">support@secondbook.com</a>
        </div>
    </div>
</main>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.sb-return-page');

    if (!page) {
        return;
    }

    const progress = document.querySelector('.sb-return-progress');
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
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(updateProgress);
        }
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

    page.querySelectorAll('.sb-return-faq-card[data-faq-url]').forEach(function (card) {
        const navigate = function () {
            const url = card.dataset.faqUrl;

            if (url) {
                window.location.href = url;
            }
        };

        card.addEventListener('click', navigate);

        card.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                navigate();
            }
        });
    });
});
</script>
@endpush
