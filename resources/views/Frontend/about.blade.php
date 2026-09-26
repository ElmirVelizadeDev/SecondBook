@extends('Layout.Frontend.master')

@section('title', 'About Us | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/about.css') }}">
@endpush

@section('content')

{{-- =========================================================
     SECOND BOOK — PREMIUM ABOUT EXPERIENCE
========================================================= --}}

<main class="sb-about">

    {{-- =====================================================
         HERO
    ====================================================== --}}

    <section class="sb-about-hero">

        <div class="sb-hero-noise"></div>

        <div class="sb-hero-orbit sb-hero-orbit-1"></div>
        <div class="sb-hero-orbit sb-hero-orbit-2"></div>

        <div class="container">

            <div class="sb-hero-layout">

                <div class="sb-hero-copy">

                    <div class="sb-eyebrow">
                        <span class="sb-eyebrow-line"></span>
                        <span>SECOND BOOK / ABOUT</span>
                    </div>

                    <h1>
                        Books don't
                        <span>belong</span>
                        to just one
                        reader.
                    </h1>

                    <div class="sb-hero-bottom">

                        <p>
                            We believe a great book should keep moving.
                            From one shelf to another, from one reader
                            to the next — every story deserves another life.
                        </p>

                        <a
                            href="{{ route('frontend.books') }}"
                            class="sb-circle-link"
                            aria-label="Explore books"
                        >
                            <span>EXPLORE</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                <div class="sb-hero-art">

                    <div class="sb-art-label">
                        <span>THE IDEA</span>
                        <i class="bi bi-arrow-down-right"></i>
                    </div>

                    <div class="sb-art-ring sb-art-ring-outer"></div>
                    <div class="sb-art-ring sb-art-ring-inner"></div>

                    <div class="sb-hero-photo">
                        <img
                            src="https://images.pexels.com/photos/590493/pexels-photo-590493.jpeg"
                            alt="Books arranged on a wooden table"
                        >
                    </div>

                    <div class="sb-hero-stamp">
                        <span>READ</span>
                        <span>SHARE</span>
                        <span>REPEAT</span>
                    </div>

                    <div class="sb-hero-bookmark">
                        <i class="bi bi-bookmark-heart"></i>
                    </div>

                    <div class="sb-hero-number">
                        01
                    </div>

                </div>

            </div>

            <div class="sb-hero-scroll">
                <span>SCROLL TO DISCOVER</span>
                <div></div>
            </div>

        </div>

    </section>


    {{-- =====================================================
         MANIFESTO
    ====================================================== --}}

    <section class="sb-manifesto">

        <div class="container">

            <div class="sb-manifesto-grid">

                <div class="sb-section-index">
                    <span>02</span>
                    <div></div>
                    <small>OUR PHILOSOPHY</small>
                </div>

                <div class="sb-manifesto-content">

                    <span class="sb-small-label">
                        A SIMPLE IDEA
                    </span>

                    <h2>
                        A finished book
                        <em>isn't</em>
                        a finished story.
                    </h2>

                    <div class="sb-manifesto-columns">

                        <p>
                            A book can sit quietly on a shelf for years,
                            but the story inside it never stops being
                            valuable. SecondBook exists to give that story
                            another opportunity to be discovered.
                        </p>

                        <p>
                            We connect readers with pre-loved books in a
                            marketplace designed around discovery,
                            accessibility and the simple joy of finding
                            your next favourite read.
                        </p>

                    </div>

              
                    <div class="sb-manifesto-signature">

                        <div class="sb-signature-mark">
                            <span>SB</span>
                        </div>

                        <div class="sb-signature-text">
                            <strong>SECOND LIFE FOR STORIES</strong>
                            <span>One reader at a time.</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         EDITORIAL IMAGE BAND
    ====================================================== --}}

    <section class="sb-editorial">

        <div class="sb-editorial-image">

            <img
                src="https://images.pexels.com/photos/159711/books-bookstore-book-reading-159711.jpeg"
                alt="Books in a bookstore"
                loading="lazy"
            >

            <div class="sb-editorial-overlay"></div>

            <div class="sb-editorial-caption">
                <span>01 / 03</span>
                <p>Every shelf holds a story waiting to move.</p>
            </div>

            <div class="sb-editorial-word">
                SECOND<br>
                <em>LIFE</em>
            </div>

        </div>

    </section>


    {{-- =====================================================
         WHY SECOND BOOK
    ====================================================== --}}

    <section class="sb-values">

        <div class="container">

            <div class="sb-values-header">

                <div>
                    <span class="sb-small-label">
                        WHY SECOND BOOK
                    </span>

                    <h2>
                        Designed around
                        <em>the love of reading.</em>
                    </h2>
                </div>

                <p>
                    More than a place to buy books.
                    A space where books continue their journey
                    and readers discover what comes next.
                </p>

            </div>


            <div class="sb-values-grid">

                <article class="sb-value">

                    <div class="sb-value-top">
                        <span>01</span>
                        <i class="bi bi-search"></i>
                    </div>

                    <div class="sb-value-body">
                        <span>DISCOVER</span>
                        <h3>Find the unexpected.</h3>
                        <p>
                            Browse different categories, authors and
                            conditions to discover books you might
                            never have searched for directly.
                        </p>
                    </div>

                    <div class="sb-value-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>

                </article>


                <article class="sb-value sb-value-dark">

                    <div class="sb-value-top">
                        <span>02</span>
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div class="sb-value-body">
                        <span>ACCESS</span>
                        <h3>More stories within reach.</h3>
                        <p>
                            Second-hand books make discovering new
                            authors and familiar classics more accessible.
                        </p>
                    </div>

                    <div class="sb-value-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>

                </article>


                <article class="sb-value">

                    <div class="sb-value-top">
                        <span>03</span>
                        <i class="bi bi-arrow-repeat"></i>
                    </div>

                    <div class="sb-value-body">
                        <span>REUSE</span>
                        <h3>Let stories travel.</h3>
                        <p>
                            Instead of remaining forgotten on a shelf,
                            a book can become part of somebody else's
                            reading journey.
                        </p>
                    </div>

                    <div class="sb-value-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>

                </article>


                <article class="sb-value">

                    <div class="sb-value-top">
                        <span>04</span>
                        <i class="bi bi-people"></i>
                    </div>

                    <div class="sb-value-body">
                        <span>COMMUNITY</span>
                        <h3>Readers meet sellers.</h3>
                        <p>
                            A marketplace built around people who
                            appreciate books and want to pass them on.
                        </p>
                    </div>

                    <div class="sb-value-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>

                </article>

            </div>

        </div>

    </section>



        {{-- =====================================================
            JOURNEY
        ====================================================== --}}

        <section class="sb-journey">

            <div class="container">

                <div class="sb-journey-heading">

                    <div class="sb-section-index">
                        <span>03</span>
                        <div></div>
                        <small>THE JOURNEY</small>
                    </div>

                    <div>
                        <span class="sb-small-label">HOW IT WORKS</span>

                        <h2>
                            From one shelf
                            <em>to another.</em>
                        </h2>
                    </div>

                </div>


                <div class="sb-journey-grid">

                    {{-- Journey connecting line --}}
                    <div class="sb-journey-line"></div>


                    {{-- STEP 01 --}}
                    <div class="sb-journey-item">

                        <div class="sb-journey-number">01</div>

                        <div class="sb-journey-icon">
                            <i class="bi bi-search"></i>
                        </div>

                        <span>DISCOVER</span>

                        <h3>Find your book.</h3>

                        <p>
                            Explore the collection and find a story
                            that feels like it belongs with you.
                        </p>

                    </div>


                    {{-- STEP 02 --}}
                    <div class="sb-journey-item">

                        <div class="sb-journey-number">02</div>

                        <div class="sb-journey-icon">
                            <i class="bi bi-bag"></i>
                        </div>

                        <span>CHOOSE</span>

                        <h3>Make it yours.</h3>

                        <p>
                            Select your book, review its condition
                            and place your order with ease.
                        </p>

                    </div>


                    {{-- STEP 03 --}}
                    <div class="sb-journey-item">

                        <div class="sb-journey-number">03</div>

                        <div class="sb-journey-icon">
                            <i class="bi bi-book-half"></i>
                        </div>

                        <span>READ</span>

                        <h3>Open a new chapter.</h3>

                        <p>
                            Your book reaches a new reader —
                            and its next chapter begins.
                        </p>

                    </div>

                </div>

            </div>

        </section>




    {{-- =====================================================
         NUMBERS / DARK PANEL
    ====================================================== --}}

    <section class="sb-numbers">

        <div class="container">

            <div class="sb-numbers-panel">

                <div class="sb-numbers-intro">

                    <span class="sb-small-label">
                        THE SECOND BOOK EFFECT
                    </span>

                    <h2>
                        Small numbers.
                        <br>
                        <em>Big journeys.</em>
                    </h2>

                    <p>
                        Every book listed, every order placed and
                        every reader who discovers something new
                        becomes part of the journey.
                    </p>

                </div>


                <div class="sb-number-grid">

                    <div class="sb-number-item">
                        <span class="sb-number-icon">
                            <i class="bi bi-book"></i>
                        </span>

                        <strong>100<span>+</span></strong>
                        <small>BOOKS</small>
                    </div>


                    <div class="sb-number-item">
                        <span class="sb-number-icon">
                            <i class="bi bi-person-heart"></i>
                        </span>

                        <strong>50<span>+</span></strong>
                        <small>READERS</small>
                    </div>


                    <div class="sb-number-item">
                        <span class="sb-number-icon">
                            <i class="bi bi-grid"></i>
                        </span>

                        <strong>20<span>+</span></strong>
                        <small>CATEGORIES</small>
                    </div>


                    <div class="sb-number-item">
                        <span class="sb-number-icon">
                            <i class="bi bi-clock"></i>
                        </span>

                        <strong>24<span>/7</span></strong>
                        <small>ONLINE ACCESS</small>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         FINAL CTA
    ====================================================== --}}

    <section class="sb-final">

        <div class="sb-final-decoration sb-final-decoration-1"></div>
        <div class="sb-final-decoration sb-final-decoration-2"></div>

        <div class="container">

            <div class="sb-final-grid">

                <div class="sb-final-copy">

                    <span class="sb-final-label">
                        <i class="bi bi-bookmark-heart"></i>
                        YOUR NEXT CHAPTER
                    </span>

                    <h2>
                        There is always
                        <em>another book.</em>
                    </h2>

                    <p>
                        Somewhere on a shelf, a story is waiting
                        for its next reader. Maybe it's waiting for you.
                    </p>

                    <a
                        href="{{ route('frontend.books') }}"
                        class="sb-final-button"
                    >
                        <span>Explore the collection</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>


                <div class="sb-final-visual">

                    <div class="sb-final-orbit sb-final-orbit-1"></div>
                    <div class="sb-final-orbit sb-final-orbit-2"></div>

                    <div class="sb-final-book">

                        <div class="sb-final-spine"></div>

                        <div class="sb-final-cover">

                            <div class="sb-final-cover-mark">
                                <i class="bi bi-book-half"></i>
                            </div>

                            <div class="sb-final-cover-title">
                                SECOND
                                <br>
                                BOOK
                            </div>

                            <div class="sb-final-cover-rule"></div>

                            <small>
                                EVERY STORY<br>
                                DESERVES ANOTHER READER
                            </small>

                        </div>

                    </div>

                    <div class="sb-final-note">
                        <i class="bi bi-stars"></i>
                        <div>
                            <strong>One more chapter.</strong>
                            <span>One more reader.</span>
                        </div>
                    </div>

                    <div class="sb-final-mark">
                        <span>SB</span>
                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

@endsection

