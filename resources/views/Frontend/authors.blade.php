@extends('Layout.Frontend.master')

@section('title', 'Authors | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/authors.css') }}">
@endpush

@section('content')

{{-- =========================================================
     AUTHORS HERO
========================================================= --}}

<section class="authors-hero">

    <div class="authors-hero-noise"></div>
    <div class="authors-hero-pattern"></div>

    <div class="authors-hero-orbit authors-hero-orbit-one"></div>
    <div class="authors-hero-orbit authors-hero-orbit-two"></div>

    <div class="container">

        <div class="authors-breadcrumb">
            <a href="{{ route('frontend.home') }}">Home</a>
            <span>/</span>
            <span>Authors</span>
        </div>

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <div class="authors-hero-content">

                    <span class="authors-eyebrow">
                        <span class="authors-eyebrow-line"></span>
                        <i class="bi bi-pen"></i>
                        Meet the Authors
                    </span>

                    <h1>
                        Stories begin with
                        <span>great authors.</span>
                    </h1>

                    <p>
                        Discover the writers behind the books available
                        on SecondBook. Explore their ideas, stories and
                        unforgettable worlds.
                    </p>

                    <div class="authors-hero-actions">

                        <a
                            href="{{ route('frontend.books') }}"
                            class="authors-primary-btn"
                        >
                            <span>Explore Books</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a
                            href="#authors-list"
                            class="authors-secondary-btn"
                        >
                            <span>Browse Authors</span>
                            <i class="bi bi-arrow-down"></i>
                        </a>

                    </div>

                    <div class="authors-hero-meta">

                        <div class="authors-hero-meta-item">
                            <strong>{{ $authors->total() }}</strong>
                            <span>Authors</span>
                        </div>

                        <div class="authors-hero-meta-divider"></div>

                        <div class="authors-hero-meta-item">
                            <strong>01</strong>
                            <span>Shared purpose</span>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="authors-hero-visual">

                    <div class="authors-art-label">
                        <span>THE VOICES</span>
                        <i class="bi bi-arrow-down-right"></i>
                    </div>

                    <div class="authors-main-image">

                        <img
                            src="https://images.pexels.com/photos/9572569/pexels-photo-9572569.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Author reading a book"
                        >

                        <div class="authors-main-image-overlay"></div>

                    </div>

                    <div class="authors-floating-card authors-floating-card-top">

                        <div class="floating-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div>
                            <strong>{{ $authors->total() }}</strong>
                            <span>Authors</span>
                        </div>

                    </div>

                    <div class="authors-floating-card authors-floating-card-bottom">

                        <div class="floating-avatar">
                            <i class="bi bi-bookmark-heart"></i>
                        </div>

                        <div>
                            <strong>Unique Stories</strong>
                            <span>One reader at a time</span>
                        </div>

                    </div>

                    <div class="authors-circle circle-one"></div>
                    <div class="authors-circle circle-two"></div>

                    <div class="authors-hero-stamp">
                        <span>READ</span>
                        <span>DISCOVER</span>
                        <span>REPEAT</span>
                    </div>

                    <div class="authors-hero-number">
                        01
                    </div>

                </div>

            </div>

        </div>

        <div class="authors-hero-scroll">
            <span>SCROLL TO DISCOVER</span>
            <div></div>
        </div>

    </div>

</section>


{{-- =========================================================
     INTRO
========================================================= --}}

<section class="authors-intro">

    <div class="container">

        <div class="authors-intro-grid">

            <div class="authors-section-index">
                <span>02</span>
                <div></div>
                <small>THE AUTHORS</small>
            </div>

            <div class="authors-section-heading">

                <span class="authors-small-label">
                    DISCOVER THEIR WORK
                </span>

                <h2>
                    Behind every
                    <em>great book</em>
                    is a voice.
                </h2>

                <p>
                    Browse our growing community of authors and discover
                    the people behind the stories waiting to be found.
                </p>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     AUTHOR SPOTLIGHT
========================================================= --}}

@if($authors->count())

    @php
        $spotlightAuthor = $authors->first();

        $spotlightImage = null;

        if (!empty($spotlightAuthor->photo)) {
            $spotlightImage = filter_var(
                $spotlightAuthor->photo,
                FILTER_VALIDATE_URL
            )
                ? $spotlightAuthor->photo
                : asset('storage/' . $spotlightAuthor->photo);
        }
    @endphp

    <section class="authors-spotlight">

        <div class="container">

            <div class="authors-spotlight-header">

                <div>
                    <span class="authors-small-label">
                        AUTHOR SPOTLIGHT
                    </span>

                    <h2>
                        Meet a voice
                        <em>worth discovering.</em>
                    </h2>
                </div>

                <span class="authors-section-number">
                    03 / FEATURED
                </span>

            </div>


            <article class="authors-spotlight-card">

                <div class="authors-spotlight-image">

                    @if($spotlightImage)

                        <img
                            src="{{ $spotlightImage }}"
                            alt="{{ $spotlightAuthor->name }}"
                            loading="lazy"
                        >

                    @else

                        <div class="authors-spotlight-placeholder">
                            <i class="bi bi-person"></i>
                        </div>

                    @endif

                    <div class="authors-spotlight-image-overlay"></div>

                    <span class="authors-spotlight-image-label">
                        FEATURED AUTHOR
                    </span>

                </div>


                <div class="authors-spotlight-content">

                    <span class="authors-spotlight-eyebrow">
                        THE VOICE BEHIND THE STORY
                    </span>

                    <h3>
                        {{ $spotlightAuthor->name }}
                    </h3>

                    <p>
                        {{ $spotlightAuthor->bio
                            ? \Illuminate\Support\Str::limit($spotlightAuthor->bio, 260)
                            : 'Discover books and stories from ' . $spotlightAuthor->name . '.'
                        }}
                    </p>

                    <div class="authors-spotlight-rule"></div>

                    <div class="authors-spotlight-footer">

                        <div class="authors-spotlight-mark">
                            <span>SB</span>
                        </div>

                        <div>
                            <span>SECOND BOOK</span>
                            <small>Stories deserve another reader.</small>
                        </div>

                        <a
                            href="{{ route('frontend.books', ['search' => $spotlightAuthor->name]) }}"
                            class="authors-spotlight-btn"
                        >
                            <span>Explore Author</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>

            </article>

        </div>

    </section>

@endif


{{-- =========================================================
     AUTHORS LIST
========================================================= --}}

<section class="authors-list-section" id="authors-list">

    <div class="container">

        <div class="authors-list-header">

            <div>

                <span class="authors-small-label">
                    THE COLLECTION
                </span>

                <h2>
                    Authors worth
                    <em>knowing.</em>
                </h2>

            </div>

            <p>
                Explore writers, ideas and stories from the
                SecondBook collection.
            </p>

        </div>


        {{-- =====================================================
             AUTHOR SEARCH
        ====================================================== --}}

        <div class="authors-search-wrapper">

            <div class="authors-search-heading">

                <div>
                    <span>FIND AN AUTHOR</span>
                    <strong>Search the collection.</strong>
                </div>

                <i class="bi bi-search"></i>

            </div>

            <form
                action="{{ route('frontend.authors') }}"
                method="GET"
                class="authors-search-form"
                id="authors-search-form"
            >

                <div class="authors-search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by author name..."
                        autocomplete="off"
                        aria-label="Search authors"
                    >

                    @if(request('search'))

                        <a
                            href="{{ route('frontend.authors') }}"
                            class="authors-search-clear"
                            aria-label="Clear search"
                        >
                            <i class="bi bi-x"></i>
                        </a>

                    @endif

                </div>

                <button
                    type="submit"
                    class="authors-search-btn"
                >
                    <span>Search Authors</span>
                    <i class="bi bi-arrow-up-right"></i>
                </button>

            </form>

        </div>


        {{-- =====================================================
             AUTHORS RESULTS
        ====================================================== --}}

        @if($authors->count())

            <div class="authors-results-meta">

                <div>

                    <span class="authors-results-label">
                        AUTHORS
                    </span>

                    <strong>
                        {{ $authors->total() }}
                    </strong>

                </div>

                @if(request('search'))

                    <div class="authors-active-search">
                        Results for
                        <strong>"{{ request('search') }}"</strong>
                    </div>

                @else

                    <div class="authors-active-search">
                        Discover the collection
                    </div>

                @endif

            </div>


            <div class="authors-grid" id="authors-grid">

                @foreach($authors as $index => $author)

                    @php
                        $authorImage = null;

                        if (!empty($author->photo)) {
                            $authorImage = filter_var(
                                $author->photo,
                                FILTER_VALIDATE_URL
                            )
                                ? $author->photo
                                : asset('storage/' . $author->photo);
                        }
                    @endphp

                    <article class="author-card">

                        <div class="author-card-image">

                            @if($authorImage)

                                <img
                                    src="{{ $authorImage }}"
                                    alt="{{ $author->name }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="author-card-placeholder">
                                    <i class="bi bi-person"></i>
                                </div>

                            @endif

                            <div class="author-image-overlay"></div>

                            <span class="author-number">
                                {{ str_pad(
                                    ($authors->currentPage() - 1) * $authors->perPage() + $loop->iteration,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </span>

                            <span class="author-card-view">
                                <i class="bi bi-arrow-up-right"></i>
                            </span>

                        </div>


                        <div class="author-card-content">

                            <div class="author-card-top">

                                <span class="author-label">
                                    AUTHOR
                                </span>

                                <span class="author-card-line"></span>

                            </div>

                            <h3>
                                {{ $author->name }}
                            </h3>

                            <p>
                                {{ \Illuminate\Support\Str::limit(
                                    $author->bio ?: 'Discover books and stories from ' . $author->name . '.',
                                    120
                                ) }}
                            </p>

                            <a
                                href="{{ route('frontend.books', ['search' => $author->name]) }}"
                                class="author-view-btn"
                            >
                                <span>View Books</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            @if($authors->hasPages())

                @php
                    $pagination = $authors->appends(request()->except('page'));
                    $currentPage = $authors->currentPage();
                    $lastPage = $authors->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp

                <div
                    class="authors-pagination-wrapper"
                    id="authors-pagination"
                >

                    <div class="authors-pagination">

                        <div class="authors-pagination-info">

                            <span>SHOWING</span>

                            <strong>
                                {{ $authors->firstItem() }}
                            </strong>

                            <span>—</span>

                            <strong>
                                {{ $authors->lastItem() }}
                            </strong>

                            <span>OF</span>

                            <strong>
                                {{ $authors->total() }}
                            </strong>

                            <span>AUTHORS</span>

                        </div>


                        <div class="authors-pagination-links">

                            @if($authors->onFirstPage())

                                <span
                                    class="authors-page-link disabled"
                                    aria-disabled="true"
                                >
                                    <i class="bi bi-arrow-left"></i>
                                </span>

                            @else

                                <a
                                    href="{{ $pagination->previousPageUrl() }}"
                                    class="authors-page-link"
                                    rel="prev"
                                    aria-label="Previous page"
                                >
                                    <i class="bi bi-arrow-left"></i>
                                </a>

                            @endif


                            @if($startPage > 1)

                                <a
                                    href="{{ $pagination->url(1) }}"
                                    class="authors-page-link"
                                >
                                    1
                                </a>

                            @endif


                            @if($startPage > 2)

                                <span class="authors-page-ellipsis">
                                    ...
                                </span>

                            @endif


                            @for($page = $startPage; $page <= $endPage; $page++)

                                @if($page == $currentPage)

                                    <span
                                        class="authors-page-link active"
                                        aria-current="page"
                                    >
                                        {{ $page }}
                                    </span>

                                @else

                                    <a
                                        href="{{ $pagination->url($page) }}"
                                        class="authors-page-link"
                                    >
                                        {{ $page }}
                                    </a>

                                @endif

                            @endfor


                            @if($endPage < $lastPage - 1)

                                <span class="authors-page-ellipsis">
                                    ...
                                </span>

                            @endif


                            @if($endPage < $lastPage)

                                <a
                                    href="{{ $pagination->url($lastPage) }}"
                                    class="authors-page-link"
                                >
                                    {{ $lastPage }}
                                </a>

                            @endif


                            @if($authors->hasMorePages())

                                <a
                                    href="{{ $pagination->nextPageUrl() }}"
                                    class="authors-page-link"
                                    rel="next"
                                    aria-label="Next page"
                                >
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            @else

                                <span
                                    class="authors-page-link disabled"
                                    aria-disabled="true"
                                >
                                    <i class="bi bi-arrow-right"></i>
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @endif

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="authors-empty">

                <div class="authors-empty-number">
                    00
                </div>

                <div class="authors-empty-icon">
                    <i class="bi bi-person-lines-fill"></i>
                </div>

                @if(request('search'))

                    <span class="authors-small-label">
                        SEARCH RESULTS
                    </span>

                    <h3>
                        No authors found.
                    </h3>

                    <p>
                        We couldn't find any authors matching
                        "<strong>{{ request('search') }}</strong>".
                        Try another search.
                    </p>

                    <a
                        href="{{ route('frontend.authors') }}"
                        class="authors-primary-btn"
                    >
                        <span>View All Authors</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                @else

                    <span class="authors-small-label">
                        THE COLLECTION
                    </span>

                    <h3>
                        No authors available yet.
                    </h3>

                    <p>
                        Our author collection is growing.
                        Check back soon for new writers and stories.
                    </p>

                    <a
                        href="{{ route('frontend.books') }}"
                        class="authors-primary-btn"
                    >
                        <span>Browse Books</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                @endif

            </div>

        @endif

    </div>

</section>


{{-- =========================================================
     AUTHOR DISCOVERY
========================================================= --}}

<section class="authors-discovery">

    <div class="container">

        <div class="authors-discovery-box">

            <div class="discovery-decoration discovery-decoration-one"></div>
            <div class="discovery-decoration discovery-decoration-two"></div>

            <div class="row align-items-center g-5">

                <div class="col-lg-7">

                    <span class="discovery-eyebrow">
                        <i class="bi bi-stars"></i>
                        Keep Exploring
                    </span>

                    <h2>
                        Every author has a
                        <span>story to tell.</span>
                    </h2>

                    <p>
                        From timeless classics to modern discoveries,
                        find your next favourite book and explore the
                        minds behind the pages.
                    </p>

                    <a
                        href="{{ route('frontend.books') }}"
                        class="discovery-btn"
                    >
                        <span>Explore the Collection</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>


                <div class="col-lg-5">

                    <div class="discovery-books">

                        <div class="discovery-book book-one">
                            <span>STORIES</span>
                        </div>

                        <div class="discovery-book book-two">
                            <span>IDEAS</span>
                        </div>

                        <div class="discovery-book book-three">
                            <span>WORDS</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     BOTTOM CTA
========================================================= --}}

<section class="authors-cta">

    <div class="authors-cta-orbit authors-cta-orbit-one"></div>
    <div class="authors-cta-orbit authors-cta-orbit-two"></div>

    <div class="container">

        <div class="authors-cta-content">

            <div class="authors-cta-copy">

                <span>
                    <i class="bi bi-bookmark-heart"></i>
                    FIND YOUR NEXT READ
                </span>

                <h2>
                    A new story is
                    <strong>waiting for you.</strong>
                </h2>

            </div>

            <a
                href="{{ route('frontend.books') }}"
                class="authors-cta-btn"
            >
                <span>Browse Books</span>
                <i class="bi bi-arrow-up-right"></i>
            </a>

        </div>

    </div>

</section>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const authorsGrid = document.getElementById('authors-grid');
    const paginationContainer = document.getElementById('authors-pagination');
    const searchForm = document.getElementById('authors-search-form');

    function loadAuthors(url, scrollToAuthors = true) {

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newGrid = doc.querySelector('#authors-grid');
            const newPagination = doc.querySelector('#authors-pagination');

            if (newGrid && authorsGrid) {
                authorsGrid.innerHTML = newGrid.innerHTML;
            }

            if (paginationContainer) {

                if (newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                    paginationContainer.style.display = '';
                } else {
                    paginationContainer.innerHTML = '';
                    paginationContainer.style.display = 'none';
                }

            }

            bindPagination();

            if (scrollToAuthors) {

                const search = document.getElementById('authors-search-form');

                if (search) {

                    const offset = 20;

                    const position =
                        search.getBoundingClientRect().bottom +
                        window.scrollY +
                        offset;

                    window.scrollTo({
                        top: position,
                        behavior: 'smooth'
                    });

                }

            }

        })
        .catch(error => {
            console.error('Authors pagination error:', error);
        });

    }


    function bindPagination() {

        const links = document.querySelectorAll(
            '#authors-pagination a'
        );

        links.forEach(link => {

            link.addEventListener('click', function (event) {

                event.preventDefault();

                const url = this.href;

                window.history.pushState({}, '', url);

                loadAuthors(url, true);

            });

        });

    }


    if (searchForm) {

        searchForm.addEventListener('submit', function (event) {

            event.preventDefault();

            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);

            const url =
                searchForm.action +
                (params.toString() ? '?' + params.toString() : '');

            window.history.pushState({}, '', url);

            loadAuthors(url, false);

            const search = document.getElementById('authors-list');

            if (search) {

                search.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

            }

        });

    }


    window.addEventListener('popstate', function () {

        loadAuthors(window.location.href, false);

    });


    bindPagination();

});
</script>

@endpush