@extends('Layout.Frontend.master')

@section('title', 'Categories | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/categories.css') }}">
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

                {{-- Hero Text --}}

                <div class="col-lg-6">

                    <div class="categories-hero-content">

                        <div class="categories-eyebrow">
                            <span></span>
                            Explore by category
                        </div>

                        <h1>
                            Find your next
                            <em>favorite book.</em>
                        </h1>

                        <p>
                            From timeless classics to modern stories,
                            explore our collection by category and discover
                            books that match your interests.
                        </p>

                        <div class="categories-hero-actions">

                            <a
                                href="{{ route('frontend.books') }}"
                                class="categories-primary-btn"
                            >
                                <span>Explore Books</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>

                            <div class="categories-book-count">

                                <strong>
                                    {{ $categories->count() }}
                                </strong>

                                <span>
                                    Categories<br>
                                    to explore
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Hero Visual --}}

                <div class="col-lg-6">

                    <div class="categories-hero-visual">

                        <div class="hero-visual-circle"></div>

                        <div class="hero-main-image">

                            <img
                                src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1400&q=90"
                                alt="Books collection"
                                loading="eager"
                            >

                            <div class="hero-image-overlay"></div>

                        </div>


                        {{-- Floating Card --}}

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

                <p>
                    Choose a category and discover books selected
                    for every kind of reader.
                </p>

            </div>


            {{-- =====================================================
                CATEGORIES GRID
            ====================================================== --}}

            @if($categories->count())

                <div class="categories-grid">

                    @foreach($categories as $index => $category)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | Category Image
                            |--------------------------------------------------------------------------
                            | Supports:
                            | 1. External image URLs
                            | 2. Local storage images
                            */

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

                            {{-- =================================================
                                CATEGORY IMAGE
                            ================================================== --}}

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

                            </div>


                            {{-- =================================================
                                CATEGORY CONTENT
                            ================================================== --}}

                            <div class="category-card-body">

                                <div>

                                    <span class="category-card-label">
                                        Category
                                    </span>

                                    <h3>
                                        {{ $category->name }}
                                    </h3>

                                </div>

                                <div class="category-card-link">
                                    Explore
                                    <i class="bi bi-arrow-right"></i>
                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="categories-empty">

                    <div class="empty-icon">
                        <i class="bi bi-book"></i>
                    </div>

                    <h3>
                        No categories available
                    </h3>

                    <p>
                        Categories will appear here once they are added.
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
                    Explore our books and discover stories waiting
                    to become part of your collection.
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

