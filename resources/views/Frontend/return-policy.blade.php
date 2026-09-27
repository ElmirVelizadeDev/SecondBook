@extends('Layout.Frontend.master')

@section('title', 'Return Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/return-policy.css') }}">
@endpush

@section('content')

<main class="sb-return-page">

    <div class="container">

        {{-- =========================================================
             HERO
        ========================================================== --}}
        <section class="sb-return-hero">

            <div class="sb-return-eyebrow">
                <span class="sb-return-eyebrow-line"></span>

                <i class="bi bi-arrow-return-left"></i>

                <span>RETURN INFORMATION</span>

                <span class="sb-return-eyebrow-line"></span>
            </div>

            <h1>
                Returns <em>&</em> Refunds
            </h1>

            <p>
                We want every SecondBook purchase to feel clear and
                confident. Here's what you need to know about returns,
                eligibility, and refunds.
            </p>

            <div class="sb-return-meta">

                <span>
                    <i class="bi bi-arrow-repeat"></i>
                    Simple return process
                </span>

                <span class="sb-return-meta-divider"></span>

                <span>
                    <i class="bi bi-shield-check"></i>
                    We're here to help
                </span>

            </div>

        </section>


        {{-- =========================================================
             INTRO
        ========================================================== --}}
        <section class="sb-return-intro">

            <div class="sb-return-intro-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>

                <span class="sb-return-intro-label">
                    WELCOME TO SECONDBOOK
                </span>

                <h2>
                    A simple process when something goes wrong.
                </h2>

                <p>
                    If your order does not match the listing or arrives
                    with a serious issue, you can contact our support team
                    and request a return review.
                </p>

            </div>

        </section>


        {{-- =========================================================
             RETURN PROCESS
        ========================================================== --}}
        <section class="sb-return-section">

            <div class="sb-return-section-heading">

                <span class="sb-return-section-label">
                    RETURN OVERVIEW
                </span>

                <h2>
                    What happens when<br>
                    you need to return a book?
                </h2>

                <p>
                    Our return process is designed to keep each request
                    clear, organized, and easy to understand.
                </p>

            </div>


            <div class="sb-return-card-grid">

                <article class="sb-return-card">

                    <span class="sb-return-card-number">01</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>

                    <h3>Contact us</h3>

                    <p>
                        Get in touch with our support team and tell us
                        what went wrong with your order.
                    </p>

                </article>


                <article class="sb-return-card">

                    <span class="sb-return-card-number">02</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h3>We review the request</h3>

                    <p>
                        We review the order details and the reason for
                        the return to determine the appropriate next step.
                    </p>

                </article>


                <article class="sb-return-card">

                    <span class="sb-return-card-number">03</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <h3>Return the book</h3>

                    <p>
                        If the return is approved, you will receive
                        instructions for sending the book back.
                    </p>

                </article>


                <article class="sb-return-card">

                    <span class="sb-return-card-number">04</span>

                    <div class="sb-return-card-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <h3>Refund is processed</h3>

                    <p>
                        Once the return is completed and approved,
                        the applicable refund will be processed.
                    </p>

                </article>

            </div>

        </section>


        {{-- =========================================================
             ELIGIBILITY
        ========================================================== --}}
        <section class="sb-return-section">

            <div class="sb-return-section-heading">

                <span class="sb-return-section-label">
                    RETURN ELIGIBILITY
                </span>

                <h2>
                    When can a book<br>
                    be returned?
                </h2>

                <p>
                    Returns are reviewed according to the condition of
                    the order and the information provided in the listing.
                </p>

            </div>


            <div class="sb-return-eligibility-grid">

                <article class="sb-return-policy-card">

                    <div class="sb-return-policy-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <div class="sb-return-policy-content">

                        <h3>Eligible situations</h3>

                        <ul>

                            <li>
                                <i class="bi bi-check"></i>
                                <span>
                                    The book is significantly different
                                    from its listing.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-check"></i>
                                <span>
                                    The received book has undisclosed damage.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-check"></i>
                                <span>
                                    The wrong book was delivered.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-check"></i>
                                <span>
                                    The order arrived with a serious issue.
                                </span>
                            </li>

                        </ul>

                    </div>

                </article>


                <article class="sb-return-policy-card">

                    <div class="sb-return-policy-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div class="sb-return-policy-content">

                        <h3>Situations that may not qualify</h3>

                        <ul>

                            <li>
                                <i class="bi bi-dash"></i>
                                <span>
                                    You simply changed your mind after
                                    receiving the book.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-dash"></i>
                                <span>
                                    Minor signs of normal second-hand use
                                    were already described.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-dash"></i>
                                <span>
                                    The book matches the condition shown
                                    in the listing.
                                </span>
                            </li>

                            <li>
                                <i class="bi bi-dash"></i>
                                <span>
                                    The return request does not meet the
                                    applicable return requirements.
                                </span>
                            </li>

                        </ul>

                    </div>

                </article>

            </div>

        </section>


        {{-- =========================================================
             GOOD TO KNOW
        ========================================================== --}}
        <section class="sb-return-feature">

            <div class="sb-return-feature-icon">
                <i class="bi bi-lightbulb"></i>
            </div>

            <div class="sb-return-feature-content">

                <span class="sb-return-section-label">
                    GOOD TO KNOW
                </span>

                <h2>
                    A few things to keep in mind
                </h2>

                <div class="sb-return-feature-list">

                    <div class="sb-return-feature-item">

                        <span class="sb-return-feature-number">01</span>

                        <div>
                            <h3>Keep your order information</h3>

                            <p>
                                Have your order details available when
                                contacting our support team.
                            </p>
                        </div>

                    </div>


                    <div class="sb-return-feature-item">

                        <span class="sb-return-feature-number">02</span>

                        <div>
                            <h3>Provide photos when useful</h3>

                            <p>
                                Photos can help us understand issues
                                concerning the condition of a book.
                            </p>
                        </div>

                    </div>


                    <div class="sb-return-feature-item">

                        <span class="sb-return-feature-number">03</span>

                        <div>
                            <h3>Follow return instructions</h3>

                            <p>
                                Approved return requests should follow
                                the instructions provided by our team.
                            </p>
                        </div>

                    </div>


                    <div class="sb-return-feature-item">

                        <span class="sb-return-feature-number">04</span>

                        <div>
                            <h3>Refund timing may vary</h3>

                            <p>
                                Processing time can depend on the payment
                                method and return review process.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             FAQ
        ========================================================== --}}
        <section class="sb-return-section">

            <div class="sb-return-section-heading">

                <span class="sb-return-section-label">
                    RETURN QUESTIONS
                </span>

                <h2>
                    Frequently asked<br>
                    return questions.
                </h2>

            </div>


            <div class="sb-return-faq-grid">

                <article class="sb-return-faq-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'refund']) }}#faqSearch">

                    <div class="sb-return-faq-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>

                        <h3>
                            How long does a refund take?
                        </h3>

                        <p>
                            Refund processing time can vary depending on
                            the payment method and the status of the return.
                        </p>

                    </div>

                </article>


                <article class="sb-return-faq-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'photos']) }}#faqSearch">

                    <div class="sb-return-faq-icon">
                        <i class="bi bi-camera"></i>
                    </div>

                    <div>

                        <h3>
                            Should I provide photos?
                        </h3>

                        <p>
                            If the issue is related to the book's condition,
                            photos can help our team understand the problem.
                        </p>

                    </div>

                </article>


                <article class="sb-return-faq-card"
                         data-faq-url="{{ route('frontend.faq', ['search' => 'return']) }}#faqSearch">

                    <div class="sb-return-faq-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                    <div>

                        <h3>
                            Who reviews my request?
                        </h3>

                        <p>
                            Our support team reviews the order information
                            and the reason provided with the return request.
                        </p>

                    </div>

                </article>


                <article class="sb-return-faq-card"
                         data-faq-url="{{ route('frontend.faq') }}">

                    <div class="sb-return-faq-icon">
                        <i class="bi bi-question-circle"></i>
                    </div>

                    <div>

                        <h3>
                            Still have questions?
                        </h3>

                        <p>
                            Visit our FAQ page or contact the SecondBook
                            support team for further assistance.
                        </p>

                    </div>

                </article>

            </div>

        </section>


        {{-- =========================================================
             CONTACT CTA
        ========================================================== --}}
        <section class="sb-return-contact">

            <div class="sb-return-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-return-contact-content">

                <span class="sb-return-section-label">
                    NEED HELP?
                </span>

                <h2>
                    Something not right with your order?
                </h2>

                <p>
                    Our support team is here to help you understand
                    your return options and next steps.
                </p>

            </div>

            <div class="sb-return-contact-actions">

                <a href="{{ route('frontend.contact') }}"
                   class="sb-return-contact-btn">
                    Contact Support
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('frontend.faq') }}"
                   class="sb-return-contact-link">
                    View FAQ
                </a>

            </div>

        </section>


        {{-- =========================================================
             BOTTOM META
        ========================================================== --}}
        <div class="sb-return-bottom">

            <span>
                <i class="bi bi-arrow-repeat"></i>
                Simple returns, clear communication
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

    const faqCards = document.querySelectorAll(
        '.sb-return-faq-card[data-faq-url]'
    );

    faqCards.forEach(function (card) {

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

