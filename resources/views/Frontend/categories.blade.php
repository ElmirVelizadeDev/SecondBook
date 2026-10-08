@extends('Layout.Frontend.master')

@section('title', 'Categories | SecondBook')

@push('css') <link rel="stylesheet" href="{{ asset('frontend-assets/css/categories.css') }}">
@endpush

@section('content')

<section id="categories-page">


{{-- =========================================================
    HERO
========================================================== --}}

<section class="categories-hero">

    <div class="categories-hero-shape categories-shape-one"></div>
    <div class="categories-hero-shape categories-shape-two"></div>

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <div class="categories-hero-content">

                    <div class="categories-eyebrow">
                        <span></span>
                        Curated for every reader
                    </div>

                    <h1>
                        Every story has a place.
                        <em>Find yours.</em>
                    </h1>

                    <p>
                        Books have different worlds, different voices and
                        different reasons to be remembered. Explore SecondBook
                        by category and discover the stories that feel right
                        for your next chapter.
                    </p>

                    <div class="categories-hero-actions">

                        <a
                            href="{{ route('frontend.books') }}"
                            class="categories-primary-btn"
                        >
                            <span>Explore All Books</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <div class="categories-book-count">
                            <strong>{{ $categories->count() }}</strong>

                            <span>
                                Curated<br>
                                categories
                            </span>
                        </div>

                    </div>

                    <div class="categories-hero-note">
                        <i class="bi bi-bookmark-star"></i>

                        <span>
                            From timeless classics to modern discoveries,
                            there is always another story waiting.
                        </span>
                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="categories-hero-visual">

                    <div class="hero-visual-circle"></div>

                    <div class="hero-main-image">

                        <img
                            src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1400&q=90"
                            alt="SecondBook book collection"
                            loading="eager"
                        >

                        <div class="hero-image-overlay"></div>

                        <div class="hero-image-caption">
                            <span>SECOND BOOK</span>
                            <strong>A world of stories</strong>
                        </div>

                    </div>


                    <div class="hero-floating-card hero-floating-top">

                        <div class="floating-icon">
                            <i class="bi bi-book"></i>
                        </div>

                        <div>
                            <span>Discover</span>
                            <strong>Something new</strong>
                        </div>

                    </div>


                    <div class="hero-floating-card hero-floating-bottom">

                        <div class="mini-book-cover">
                            <i class="bi bi-book-half"></i>
                        </div>

                        <div>
                            <span>SecondBook</span>
                            <strong>Read. Discover. Repeat.</strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    INTRO
========================================================== --}}

<section class="categories-intro">

    <div class="container">

        <div class="categories-intro-grid">

            <div class="categories-intro-index">
                <span>01</span>
                <small>THE COLLECTION</small>
            </div>

            <div class="categories-intro-main">

                <div class="categories-label">
                    <span></span>
                    More than a category
                </div>

                <h2>
                    Start with what
                    <em>speaks to you.</em>
                </h2>

                <p>
                    Sometimes you know exactly what you want to read.
                    Sometimes you simply want to discover something unexpected.
                    Categories make it easier to navigate the collection,
                    whether you're looking for knowledge, imagination,
                    inspiration or a story to escape into.
                </p>

            </div>

            <div class="categories-intro-side">

                <div class="intro-stat">
                    <strong>{{ $categories->count() }}</strong>
                    <span>Ways to<br>discover</span>
                </div>

                <div class="intro-line"></div>

                <p>
                    Choose a direction and let the next story find you.
                </p>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    CATEGORY SECTION
========================================================== --}}

<section class="categories-section">

    <div class="container">

        <div class="categories-section-heading">

            <div>

                <div class="categories-label">
                    <span></span>
                    Browse collection
                </div>

                <h2>
                    Explore by
                    <em>category</em>
                </h2>

            </div>

            <div class="categories-section-description">

                <span>02 / DISCOVER</span>

                <p>
                    Pick a category that matches your curiosity
                    and explore books waiting to be discovered.
                </p>

            </div>

        </div>


        @if($categories->count())

            <div class="categories-grid">

                @foreach($categories as $index => $category)

                    @php

                        $categoryImage = null;

                        if (!empty($category->image)) {

                            $categoryImage = filter_var(
                                $category->image,
                                FILTER_VALIDATE_URL
                            )
                                ? $category->image
                                : asset('storage/' . $category->image);

                        }

                    @endphp

                    <a
                        href="{{ route('frontend.books', ['search' => $category->name]) }}"
                        class="category-card"
                    >

                        <div class="category-card-image">

                            @if($categoryImage)

                                <img
                                    src="{{ $categoryImage }}"
                                    alt="{{ $category->name }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="category-card-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>

                            @endif

                            <div class="category-image-overlay"></div>

                            <div class="category-card-number">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="category-card-arrow">
                                <i class="bi bi-arrow-up-right"></i>
                            </div>

                            <div class="category-card-image-label">
                                Explore collection
                            </div>

                        </div>


                        <div class="category-card-body">

                            <div>

                                <span class="category-card-label">
                                    Category {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <h3>
                                    {{ $category->name }}
                                </h3>

                            </div>

                            <div class="category-card-link">
                                Discover books
                                <i class="bi bi-arrow-right"></i>
                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="categories-empty">

                <div class="empty-icon">
                    <i class="bi bi-book"></i>
                </div>

                <span class="categories-label">
                    Collection
                </span>

                <h3>
                    No categories available
                </h3>

                <p>
                    Categories will appear here once they are added.
                    Check back soon to explore the collection.
                </p>

                <a
                    href="{{ route('frontend.books') }}"
                    class="categories-primary-btn"
                >
                    <span>Browse Books</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        @endif

    </div>

</section>


{{-- =========================================================
    READING MOODS
========================================================== --}}

<section class="categories-moods">

    <div class="container">

        <div class="categories-moods-heading">

            <div class="categories-label">
                <span></span>
                Find your reading mood
            </div>

            <h2>
                Not sure what to read?
                <em>Start with a feeling.</em>
            </h2>

            <p>
                Your next favorite book doesn't always begin with a title.
                Sometimes it begins with a mood, a question or a moment.
            </p>

        </div>


        <div class="categories-moods-grid">

            <a href="{{ route('frontend.books') }}" class="mood-card">

                <div class="mood-card-number">01</div>

                <div class="mood-card-icon">
                    <i class="bi bi-lightbulb"></i>
                </div>

                <h3>Learn Something</h3>

                <p>
                    Discover ideas, knowledge and perspectives
                    that stay with you.
                </p>

                <span>
                    Explore
                    <i class="bi bi-arrow-up-right"></i>
                </span>

            </a>


            <a href="{{ route('frontend.books') }}" class="mood-card">

                <div class="mood-card-number">02</div>

                <div class="mood-card-icon">
                    <i class="bi bi-stars"></i>
                </div>

                <h3>Escape Somewhere</h3>

                <p>
                    Step away from everyday life and enter
                    another world through stories.
                </p>

                <span>
                    Explore
                    <i class="bi bi-arrow-up-right"></i>
                </span>

            </a>


            <a href="{{ route('frontend.books') }}" class="mood-card">

                <div class="mood-card-number">03</div>

                <div class="mood-card-icon">
                    <i class="bi bi-heart"></i>
                </div>

                <h3>Feel Something</h3>

                <p>
                    Find stories that make you laugh, think,
                    remember and feel.
                </p>

                <span>
                    Explore
                    <i class="bi bi-arrow-up-right"></i>
                </span>

            </a>


            <a href="{{ route('frontend.books') }}" class="mood-card">

                <div class="mood-card-number">04</div>

                <div class="mood-card-icon">
                    <i class="bi bi-compass"></i>
                </div>

                <h3>Discover Something New</h3>

                <p>
                    Leave your usual choices behind and
                    see where curiosity takes you.
                </p>

                <span>
                    Explore
                    <i class="bi bi-arrow-up-right"></i>
                </span>

            </a>

        </div>

    </div>

</section>


{{-- =========================================================
    FEATURED DISCOVERY
========================================================== --}}

<section class="category-discovery">

    <div class="container">

        <div class="discovery-box">

            <div class="discovery-content">

                <div class="categories-label discovery-label">
                    <span></span>
                    A world of stories
                </div>

                <h2>
                    One shelf.
                    <br>
                    <em>Endless possibilities.</em>
                </h2>

                <p>
                    Every book has a story, and every reader has a
                    different journey. Browse SecondBook and find
                    something worth taking home.
                </p>

                <div class="discovery-meta">

                    <div>
                        <strong>01</strong>
                        <span>Choose</span>
                    </div>

                    <div>
                        <strong>02</strong>
                        <span>Discover</span>
                    </div>

                    <div>
                        <strong>03</strong>
                        <span>Read</span>
                    </div>

                </div>

                <a
                    href="{{ route('frontend.books') }}"
                    class="discovery-btn"
                >
                    <span>View All Books</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            <div class="discovery-visual">

                <div class="discovery-book discovery-book-one">
                    <div class="book-spine"></div>
                    <span>STORIES</span>
                </div>

                <div class="discovery-book discovery-book-two">
                    <div class="book-spine"></div>
                    <span>READ</span>
                </div>

                <div class="discovery-book discovery-book-three">
                    <div class="book-spine"></div>
                    <span>WORDS</span>
                </div>

                <div class="discovery-circle"></div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    BOTTOM CTA
========================================================== --}}

<section class="categories-bottom-cta">

    <div class="categories-cta-orbit categories-cta-orbit-one"></div>
    <div class="categories-cta-orbit categories-cta-orbit-two"></div>

    <div class="container">

        <div class="categories-cta-content">

            <div class="categories-label">
                <span></span>
                SecondBook
            </div>

            <h2>
                Your next chapter
                <em>starts here.</em>
            </h2>

            <p>
                There is always another book to discover,
                another idea to explore and another story
                waiting for its reader.
            </p>

            <a
                href="{{ route('frontend.books') }}"
                class="categories-primary-btn"
            >
                <span>Start Exploring</span>
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</section>


</section>

@endsection
