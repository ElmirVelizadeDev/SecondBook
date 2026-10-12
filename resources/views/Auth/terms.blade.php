@extends('Layout.Frontend.master')

@section('title', 'Terms & Conditions | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/terms.css') }}">
@endpush

@section('content')

{{-- =========================================================
     Bütün mətnlər, ikonlar və linklər saxlanılıb.
     16 bənd bir massivdən yaradılır: həm kartlar, həm də
     sol tərəfdəki məzmun (TOC) eyni mənbədən gəlir.
========================================================== --}}

@php
    $termsSupportEmail = 'support@secondbook.com';

    $terms = [
        [
            'icon'  => 'bi-check2-circle',
            'title' => 'Acceptance of Terms',
            'short' => 'Acceptance',
            'text'  => 'By accessing or using SecondBook, you agree to be bound by these Terms and Conditions and all applicable laws and regulations. If you do not agree with any part of these terms, you must not use our platform.',
        ],
        [
            'icon'  => 'bi-person-check',
            'title' => 'User Accounts',
            'short' => 'Accounts',
            'text'  => 'You are responsible for maintaining the confidentiality of your account credentials and for all activity conducted under your account. You must provide accurate information and promptly update any changes to your profile.',
        ],
        [
            'icon'  => 'bi-cart3',
            'title' => 'Buying Books',
            'short' => 'Buying',
            'text'  => 'Buyers may purchase books listed on SecondBook subject to availability, pricing, and the seller’s description. We recommend reviewing the listing details and condition notes before completing a purchase.',
        ],
        [
            'icon'  => 'bi-bag-plus',
            'title' => 'Selling Books',
            'short' => 'Selling',
            'text'  => 'Sellers are responsible for accurately describing each book, including its condition, edition, and any defects. SecondBook does not verify the authenticity of every item beyond the information provided by the seller.',
        ],
        [
            'icon'  => 'bi-book',
            'title' => 'Book Listings',
            'short' => 'Listings',
            'text'  => 'Listings must be honest, relevant, and compliant with our marketplace standards. Misleading titles, duplicate listings, or content that violates intellectual property rights may be removed.',
        ],
        [
            'icon'  => 'bi-credit-card',
            'title' => 'Payments',
            'short' => 'Payments',
            'text'  => 'Payments for completed transactions must be made through the approved methods available on the platform. SecondBook is not responsible for transactions conducted outside the designated payment process.',
        ],
        [
            'icon'  => 'bi-truck',
            'title' => 'Shipping & Delivery',
            'short' => 'Shipping',
            'text'  => 'Shipping timelines and delivery responsibilities may vary based on the seller’s location and chosen shipping method. Buyers and sellers are expected to communicate clearly regarding dispatch and delivery status.',
        ],
        [
            'icon'  => 'bi-arrow-return-left',
            'title' => 'Returns & Refunds',
            'short' => 'Returns',
            'text'  => 'Returns and refunds are handled in accordance with the applicable listing terms and our support policies. Requests must be submitted promptly with relevant evidence and order information.',
        ],
        [
            'icon'  => 'bi-shield-check',
            'title' => 'User Responsibilities',
            'short' => 'Responsibilities',
            'text'  => 'Users must act honestly, respectfully, and lawfully while using SecondBook. Any abusive behavior, deceptive practices, or harassment may result in enforcement action.',
        ],
        [
            'icon'  => 'bi-slash-circle',
            'title' => 'Prohibited Activities',
            'short' => 'Prohibited',
            'text'  => 'The following are prohibited: fraud, impersonation, spam, unauthorized reselling, copyright infringement, and any activity that disrupts the integrity of the marketplace.',
        ],
        [
            'icon'  => 'bi-cpu',
            'title' => 'Intellectual Property',
            'short' => 'Property',
            'text'  => 'All platform content, branding, and design elements are owned by SecondBook or its licensors. Users may not copy, reproduce, or distribute our materials without authorization.',
        ],
        [
            'icon'  => 'bi-lock',
            'title' => 'Privacy',
            'short' => 'Privacy',
            'text'  => 'We collect and process personal information only as necessary to operate the marketplace and provide support. Please review our Privacy Policy for more details on how your data is managed.',
        ],
        [
            'icon'  => 'bi-exclamation-octagon',
            'title' => 'Account Suspension',
            'short' => 'Suspension',
            'text'  => 'SecondBook reserves the right to suspend or terminate accounts that violate these terms, engage in fraudulent activity, or compromise the safety of other users.',
        ],
        [
            'icon'  => 'bi-brightness-high',
            'title' => 'Limitation of Liability',
            'short' => 'Liability',
            'text'  => 'SecondBook shall not be liable for indirect, incidental, or consequential damages arising from your use of the platform, except where prohibited by law.',
        ],
        [
            'icon'  => 'bi-arrow-repeat',
            'title' => 'Changes to These Terms',
            'short' => 'Changes',
            'text'  => 'We may update these Terms and Conditions from time to time. Continued use of the platform after any changes constitutes your acceptance of the revised terms.',
        ],
        [
            'icon'  => 'bi-envelope',
            'title' => 'Contact Information',
            'short' => 'Contact',
            'text'  => 'For questions or concerns about these terms, please contact us at',
            'email' => true,
        ],
    ];
@endphp


<div class="terms-progress" aria-hidden="true"></div>

<main class="terms-page">


    {{-- =====================================================
         HERO
    ====================================================== --}}

    <section class="terms-hero">

        <div class="terms-hero-bg" aria-hidden="true">
            <i class="terms-orb terms-orb-1"></i>
            <i class="terms-orb terms-orb-2"></i>
            <i class="terms-ring terms-ring-1"></i>
            <i class="terms-ring terms-ring-2"></i>
        </div>

        <div class="container">

            <div class="terms-hero-inner">

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

            </div>

        </div>

    </section>


    <div class="container">

        {{-- =================================================
             INTRO CARD (hero ilə kəsişir)
        ================================================== --}}

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


        {{-- =================================================
             TOC + TERMS
        ================================================== --}}

        <div class="terms-layout">

            <aside class="terms-toc" aria-label="Table of contents">

                <span class="terms-toc-label">
                    ON THIS PAGE
                </span>

                <nav class="terms-toc-list">

                    @foreach($terms as $index => $term)

                        @php
                            $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                        @endphp

                        <a href="#terms-{{ $number }}" class="terms-toc-link" data-terms-link>

                            <span class="terms-toc-number">{{ $number }}</span>

                            <span class="terms-toc-text">{{ $term['short'] }}</span>

                        </a>

                    @endforeach

                </nav>

            </aside>


            <div class="terms-grid">

                @foreach($terms as $index => $term)

                    @php
                        $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    @endphp

                    <article
                        class="terms-card {{ $loop->first ? 'terms-card-wide' : '' }}"
                        id="terms-{{ $number }}"
                        data-terms-section
                    >

                        <div class="terms-number">{{ $number }}</div>

                        <div class="terms-card-content">

                            <div class="terms-icon">
                                <i class="bi {{ $term['icon'] }}"></i>
                            </div>

                            <div>

                                <h2>{{ $term['title'] }}</h2>

                                <p>
                                    {{ $term['text'] }}

                                    @if(!empty($term['email']))

                                        <a href="mailto:{{ $termsSupportEmail }}">
                                            {{ $termsSupportEmail }}
                                        </a>.

                                    @endif
                                </p>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

        </div>


        {{-- =================================================
             FOOTER NOTE
        ================================================== --}}

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

            <a href="mailto:{{ $termsSupportEmail }}" class="terms-contact-btn">
                <i class="bi bi-envelope"></i>
                <span>Contact Support</span>
            </a>

        </section>


        <div class="terms-bottom">

            <span>
                <i class="bi bi-shield-check"></i>
                Last Updated: August 2026
            </span>

            <span>
                {{ $termsSupportEmail }}
            </span>

        </div>

    </div>

</main>


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const links = Array.from(document.querySelectorAll('[data-terms-link]'));
    const sections = Array.from(document.querySelectorAll('[data-terms-section]'));

    if (!links.length || !sections.length) {
        return;
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Yumşaq keçid */
    links.forEach(function (link) {

        link.addEventListener('click', function (event) {

            const target = document.querySelector(link.getAttribute('href'));

            if (!target) {
                return;
            }

            event.preventDefault();

            target.scrollIntoView({
                behavior: reduceMotion ? 'auto' : 'smooth',
                block: 'start'
            });

            history.replaceState(null, '', link.getAttribute('href'));

        });

    });

    /* Aktiv bəndin işıqlandırılması */
    if (!('IntersectionObserver' in window)) {
        return;
    }

    function setActive(id) {

        links.forEach(function (link) {

            const isActive = link.getAttribute('href') === '#' + id;

            link.classList.toggle('is-active', isActive);

            if (isActive) {

                link.setAttribute('aria-current', 'true');

                /* mobil üfüqi zolaqda aktiv çipi görünən saxla */
                const list = link.parentElement;

                if (list && list.scrollWidth > list.clientWidth) {

                    list.scrollTo({
                        left: link.offsetLeft - list.clientWidth / 2 + link.clientWidth / 2,
                        behavior: reduceMotion ? 'auto' : 'smooth'
                    });

                }

            } else {

                link.removeAttribute('aria-current');

            }

        });

    }

    const observer = new IntersectionObserver(function (entries) {

        entries.forEach(function (entry) {

            if (entry.isIntersecting) {
                setActive(entry.target.id);
            }

        });

    }, {
        rootMargin: '-35% 0px -55% 0px',
        threshold: 0
    });

    sections.forEach(function (section) {
        observer.observe(section);
    });

});
</script>

@endpush

@endsection