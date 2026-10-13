@extends('Layout.Frontend.master')

@section('title', 'FAQ | SecondBook')

@push('css')
<link rel="stylesheet" href="{{ asset('frontend-assets/css/faq.css') }}">
@endpush

@section('content')

<div class="sb-faq-progress" id="sbFaqProgress" aria-hidden="true"></div>

<main class="sb-faq-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="sb-faq-hero">

        <div class="sb-faq-hero-decoration sb-faq-decoration-one"></div>
        <div class="sb-faq-hero-decoration sb-faq-decoration-two"></div>

        <div class="container">

            <div class="sb-faq-hero-content" data-reveal>

                <div class="sb-faq-eyebrow">
                    <span class="sb-faq-eyebrow-icon">
                        <i class="bi bi-question-circle"></i>
                    </span>
                    <span>SecondBook Help Center</span>
                </div>

                <h1>
                    Everything you need to
                    <span>know.</span>
                </h1>

                <p>
                    Find clear answers to the most common questions
                    about buying, selling, orders, payments and your
                    SecondBook account.
                </p>

                <div class="sb-faq-hero-meta">

                    <div class="sb-faq-meta-item">
                        <span class="sb-faq-meta-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </span>

                        <div>
                            <strong>Quick answers</strong>
                            <span>Find what you need faster</span>
                        </div>
                    </div>

                    <div class="sb-faq-meta-divider"></div>

                    <div class="sb-faq-meta-item">
                        <span class="sb-faq-meta-icon">
                            <i class="bi bi-headset"></i>
                        </span>

                        <div>
                            <strong>Need more help?</strong>
                            <span>Our support team is here</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
         FAQ CONTENT
    ========================================================== --}}

    <section class="sb-faq-section">

        <div class="container">

            <div class="sb-faq-section-heading" data-reveal>

                <div>
                    <span class="sb-faq-section-label">
                        <i class="bi bi-bookmark-star"></i>
                        Knowledge Base
                    </span>

                    <h2>
                        Frequently asked
                        <span>questions</span>
                    </h2>
                </div>

                <p>
                    Browse a category or search for a specific answer.
                </p>

            </div>


            {{-- SEARCH --}}

            <div class="sb-faq-search-wrapper" data-reveal>

                <div class="sb-faq-search">

                    <span class="sb-faq-search-icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        id="faqSearch"
                        placeholder="Search your question..."
                        autocomplete="off"
                        aria-label="Search frequently asked questions"
                    >

                    <button
                        type="button"
                        id="faqSearchClear"
                        class="sb-faq-search-clear"
                        aria-label="Clear search"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>

                </div>

            </div>


            {{-- CATEGORY FILTER --}}

            @if($categories->count())

                <div class="sb-faq-filter-row" data-reveal>

                    <div class="sb-faq-filter-title">
                        <i class="bi bi-funnel"></i>
                        <span>Browse by category</span>
                    </div>

                    <div class="sb-faq-categories">

                        @foreach($categories as $category)

                            <button
                                type="button"
                                class="sb-faq-category"
                                data-category="{{ strtolower($category) }}"
                                aria-pressed="false"
                            >
                                <span>{{ $category }}</span>
                            </button>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- FAQ CONTENT --}}

            <div class="sb-faq-content">

                <div class="sb-faq-list" id="faqList">

                    @forelse($faqs as $faq)

                        <article
                            class="sb-faq-item"
                            data-category="{{ strtolower($faq->category ?? '') }}"
                            data-question="{{ strtolower($faq->question ?? '') }}"
                            data-answer="{{ strtolower($faq->answer ?? '') }}"
                            data-reveal
                            style="display: none;"
                        >

                            <button
                                type="button"
                                class="sb-faq-question"
                                aria-expanded="false"
                            >

                                <span class="sb-faq-question-left">

                                    <span class="sb-faq-icon" aria-hidden="true">
                                        <i class="bi bi-question-lg"></i>
                                    </span>

                                    <span class="sb-faq-question-content">

                                        @if($faq->category)
                                            <span class="sb-faq-question-category">
                                                {{ $faq->category }}
                                            </span>
                                        @endif

                                        <span class="sb-faq-question-text">
                                            {{ $faq->question }}
                                        </span>

                                    </span>

                                </span>

                                <span class="sb-faq-toggle" aria-hidden="true">
                                    <i class="bi bi-chevron-down"></i>
                                </span>

                            </button>

                            <div class="sb-faq-answer">

                                <div class="sb-faq-answer-inner">

                                    <div class="sb-faq-answer-line"></div>

                                    <div class="sb-faq-answer-content">

                                        <div class="sb-faq-answer-label">
                                            <i class="bi bi-check2-circle"></i>
                                            Answer
                                        </div>

                                        <p>{{ $faq->answer }}</p>

                                    </div>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="sb-faq-empty" data-reveal>

                            <div class="sb-faq-empty-icon">
                                <i class="bi bi-question-circle"></i>
                            </div>

                            <span class="sb-faq-empty-label">
                                Knowledge Base
                            </span>

                            <h3>No FAQs available</h3>

                            <p>
                                There are currently no frequently
                                asked questions available.
                            </p>

                        </div>

                    @endforelse


                    {{-- INITIAL EMPTY STATE --}}

                    @if($faqs->count())

                        <div
                            class="sb-faq-discovery"
                            id="faqDiscovery"
                            data-reveal
                        >

                            <div class="sb-faq-discovery-visual">

                                <span class="sb-faq-discovery-orbit sb-faq-orbit-one"></span>
                                <span class="sb-faq-discovery-orbit sb-faq-orbit-two"></span>

                                <div class="sb-faq-discovery-book">
                                    <i class="bi bi-journal-richtext"></i>
                                </div>

                                <span class="sb-faq-discovery-bubble sb-faq-bubble-one">
                                    <i class="bi bi-search"></i>
                                </span>

                                <span class="sb-faq-discovery-bubble sb-faq-bubble-two">
                                    <i class="bi bi-lightbulb"></i>
                                </span>

                            </div>

                            <span class="sb-faq-discovery-label">
                                YOUR GUIDE TO SECONDBOOK
                            </span>

                            <h3>What can we help you with?</h3>

                            <p>
                                Choose a topic to explore helpful answers,
                                or search for something specific.
                            </p>

                            <div class="sb-faq-discovery-options">

                                <button
                                    type="button"
                                    class="sb-faq-discovery-option"
                                    data-discovery-search="buy"
                                >
                                    <span class="sb-faq-discovery-option-icon">
                                        <i class="bi bi-book"></i>
                                    </span>

                                    <span>
                                        <strong>Buying books</strong>
                                        <small>Orders and purchases</small>
                                    </span>

                                    <i class="bi bi-arrow-up-right"></i>
                                </button>

                                <button
                                    type="button"
                                    class="sb-faq-discovery-option"
                                    data-discovery-search="sell"
                                >
                                    <span class="sb-faq-discovery-option-icon">
                                        <i class="bi bi-shop"></i>
                                    </span>

                                    <span>
                                        <strong>Selling books</strong>
                                        <small>Stores and listings</small>
                                    </span>

                                    <i class="bi bi-arrow-up-right"></i>
                                </button>

                                <button
                                    type="button"
                                    class="sb-faq-discovery-option"
                                    data-discovery-search="account"
                                >
                                    <span class="sb-faq-discovery-option-icon">
                                        <i class="bi bi-person-circle"></i>
                                    </span>

                                    <span>
                                        <strong>My account</strong>
                                        <small>Profile and settings</small>
                                    </span>

                                    <i class="bi bi-arrow-up-right"></i>
                                </button>

                            </div>

                            <div class="sb-faq-discovery-footer">
                                <i class="bi bi-info-circle"></i>
                                <span>
                                    You can also select any category above.
                                </span>
                            </div>

                        </div>

                    @endif


                    {{-- NO SEARCH RESULTS --}}

                    <div
                        class="sb-faq-no-results"
                        id="faqNoResults"
                        style="display: none;"
                        aria-live="polite"
                    >

                        <div class="sb-faq-empty-icon">
                            <i class="bi bi-search"></i>
                        </div>

                        <span class="sb-faq-empty-label">
                            Search
                        </span>

                        <h3>No matching questions</h3>

                        <p>
                            We couldn't find anything matching your
                            search. Try another keyword or category.
                        </p>

                        <button
                            type="button"
                            class="sb-faq-reset-btn"
                            id="faqResetFilters"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset filters
                        </button>

                    </div>

                </div>


                {{-- FAQ SIDE NOTE --}}

                <aside class="sb-faq-side-card" data-reveal>

                    <div class="sb-faq-side-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <span class="sb-faq-side-label">
                        Helpful tip
                    </span>

                    <h3>
                        Can't find what you're looking for?
                    </h3>

                    <p>
                        Try using a shorter keyword or browse through
                        the categories above.
                    </p>

                    <div class="sb-faq-side-divider"></div>

                    <div class="sb-faq-side-stat">

                        <span class="sb-faq-side-stat-icon">
                            <i class="bi bi-patch-question"></i>
                        </span>

                        <div>
                            <strong>{{ $faqs->count() }}</strong>
                            <span>Questions available</span>
                        </div>

                    </div>

                    <a
                        href="{{ route('frontend.contact') }}"
                        class="sb-faq-side-contact"
                    >
                        <span>Contact Support</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </aside>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CONTACT CTA
    ========================================================== --}}

    <section class="sb-faq-contact">

        <div class="container">

            <div class="sb-faq-contact-box" data-reveal>

                <div class="sb-faq-contact-decoration"></div>

                <div class="sb-faq-contact-icon">
                    <i class="bi bi-chat-square-text"></i>
                </div>

                <div class="sb-faq-contact-content">

                    <span>Still need help?</span>

                    <h2>We're here for you.</h2>

                    <p>
                        Didn't find the answer you were looking for?
                        Our support team will be happy to help.
                    </p>

                </div>

                <a
                    href="{{ route('frontend.contact') }}"
                    class="sb-faq-contact-btn"
                >
                    <span>Contact Us</span>
                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>

        </div>

    </section>

</main>

@endsection


{{-- =========================================================
     FAQ JAVASCRIPT
========================================================== --}}

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const page = document.querySelector('.sb-faq-page');

    if (!page) {
        return;
    }

    const searchInput = document.getElementById('faqSearch');
    const clearButton = document.getElementById('faqSearchClear');
    const resetButton = document.getElementById('faqResetFilters');
    const noResults = document.getElementById('faqNoResults');
    const discovery = document.getElementById('faqDiscovery');
    const progressBar = document.getElementById('sbFaqProgress');

    const items = Array.from(page.querySelectorAll('.sb-faq-item'));

    const categoryButtons = Array.from(
        page.querySelectorAll('.sb-faq-category')
    );

    const discoveryButtons = Array.from(
        page.querySelectorAll('[data-discovery-search]')
    );

    let selectedCategory = '';

    /*
    |--------------------------------------------------------------------------
    | SCROLL PROGRESS
    |--------------------------------------------------------------------------
    */

    function updateProgress() {

        if (!progressBar) {
            return;
        }

        const scrollableHeight =
            document.documentElement.scrollHeight - window.innerHeight;

        const progress = scrollableHeight > 0
            ? (window.scrollY / scrollableHeight) * 100
            : 0;

        progressBar.style.width = Math.min(progress, 100) + '%';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('resize', updateProgress);

    updateProgress();


    /*
    |--------------------------------------------------------------------------
    | SCROLL REVEAL
    |--------------------------------------------------------------------------
    */

    const revealElements = Array.from(
        page.querySelectorAll('[data-reveal]')
    );

    if ('IntersectionObserver' in window) {

        page.classList.add('sb-faq-reveal-enabled');

        revealElements.forEach(function (element, index) {

            element.style.setProperty(
                '--faq-reveal-delay',
                Math.min(index % 5, 4) * 75 + 'ms'
            );

        });

        const revealObserver = new IntersectionObserver(
            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);

                });

            },
            {
                threshold: 0.08,
                rootMargin: '0px 0px -35px 0px'
            }
        );

        revealElements.forEach(function (element) {
            revealObserver.observe(element);
        });

    } else {

        revealElements.forEach(function (element) {
            element.classList.add('is-visible');
        });

    }


    /*
    |--------------------------------------------------------------------------
    | ACCORDION
    |--------------------------------------------------------------------------
    */

    items.forEach(function (item) {

        const question = item.querySelector('.sb-faq-question');

        if (!question) {
            return;
        }

        question.addEventListener('click', function () {

            if (item.style.display === 'none') {
                return;
            }

            const isOpen = item.classList.contains('active');

            items.forEach(function (otherItem) {

                otherItem.classList.remove('active');

                const otherQuestion =
                    otherItem.querySelector('.sb-faq-question');

                if (otherQuestion) {
                    otherQuestion.setAttribute('aria-expanded', 'false');
                }

            });

            if (!isOpen) {

                item.classList.add('active');
                question.setAttribute('aria-expanded', 'true');

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FILTER FAQS
    |--------------------------------------------------------------------------
    */

    function filterFaqs() {

        const searchValue = searchInput
            ? searchInput.value.trim().toLowerCase()
            : '';

        const hasSearch = searchValue.length > 0;
        const hasCategory = selectedCategory !== '';

        let visibleCount = 0;

        items.forEach(function (item) {

            const category = (item.dataset.category || '')
                .trim()
                .toLowerCase();

            const question = (item.dataset.question || '')
                .toLowerCase();

            const answer = (item.dataset.answer || '')
                .toLowerCase();

            const matchesCategory =
                hasCategory && category === selectedCategory;

            const matchesSearch =
                hasSearch &&
                (
                    question.includes(searchValue) ||
                    answer.includes(searchValue) ||
                    category.includes(searchValue)
                );

            const isVisible =
                (hasCategory || hasSearch) &&
                (!hasCategory || matchesCategory) &&
                (!hasSearch || matchesSearch);

            item.style.display = isVisible ? '' : 'none';

            if (isVisible) {

                visibleCount++;

            } else {

                item.classList.remove('active');

                const questionButton =
                    item.querySelector('.sb-faq-question');

                if (questionButton) {
                    questionButton.setAttribute('aria-expanded', 'false');
                }

            }

        });

        const hasFilter = hasCategory || hasSearch;

        if (discovery) {
            discovery.style.display = !hasFilter ? '' : 'none';
        }

        if (noResults) {
            noResults.style.display =
                hasFilter && visibleCount === 0 ? 'block' : 'none';
        }

        categoryButtons.forEach(function (button) {

            const isActive =
                (button.dataset.category || '').trim().toLowerCase() ===
                selectedCategory && hasCategory;

            button.classList.toggle('active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE URL PARAMETERS
    |--------------------------------------------------------------------------
    */

    function updateUrlParameter(key, value) {

        const url = new URL(window.location.href);

        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }

        window.history.replaceState({}, '', url);
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH INPUT
    |--------------------------------------------------------------------------
    */

    if (searchInput) {

        searchInput.addEventListener('input', function () {

            filterFaqs();

            updateUrlParameter(
                'search',
                searchInput.value.trim()
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR SEARCH
    |--------------------------------------------------------------------------
    */

    function clearSearch() {

        if (searchInput) {
            searchInput.value = '';
        }

        updateUrlParameter('search', '');

        filterFaqs();

        if (searchInput) {
            searchInput.focus();
        }

    }

    if (clearButton) {
        clearButton.addEventListener('click', clearSearch);
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY FILTER
    |--------------------------------------------------------------------------
    */

    categoryButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const clickedCategory = (
                button.dataset.category || ''
            ).trim().toLowerCase();

            selectedCategory =
                selectedCategory === clickedCategory
                    ? ''
                    : clickedCategory;

            updateUrlParameter('category', selectedCategory);

            filterFaqs();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | DISCOVERY SHORTCUTS
    |--------------------------------------------------------------------------
    */

    discoveryButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const keyword = (
                button.dataset.discoverySearch || ''
            ).trim();

            selectedCategory = '';

            categoryButtons.forEach(function (categoryButton) {
                categoryButton.classList.remove('active');
                categoryButton.setAttribute('aria-pressed', 'false');
            });

            if (searchInput) {
                searchInput.value = keyword;
            }

            updateUrlParameter('category', '');
            updateUrlParameter('search', keyword);

            filterFaqs();

            if (searchInput) {
                searchInput.focus();
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | RESET FILTERS
    |--------------------------------------------------------------------------
    */

    function resetFilters() {

        selectedCategory = '';

        if (searchInput) {
            searchInput.value = '';
        }

        updateUrlParameter('search', '');
        updateUrlParameter('category', '');

        filterFaqs();

    }

    if (resetButton) {
        resetButton.addEventListener('click', resetFilters);
    }


    /*
    |--------------------------------------------------------------------------
    | READ URL PARAMETERS
    |--------------------------------------------------------------------------
    */

    const url = new URL(window.location.href);

    const urlSearch = url.searchParams.get('search');
    const urlCategory = url.searchParams.get('category');


    /*
    |--------------------------------------------------------------------------
    | APPLY SEARCH FROM URL
    |--------------------------------------------------------------------------
    */

    if (urlSearch && searchInput) {
        searchInput.value = urlSearch;
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY CATEGORY FROM URL
    |--------------------------------------------------------------------------
    */

    if (urlCategory) {

        const normalizedCategory = urlCategory.trim().toLowerCase();

        const matchingCategory = categoryButtons.find(function (button) {

            return (
                (button.dataset.category || '').trim().toLowerCase() ===
                normalizedCategory
            );

        });

        if (matchingCategory) {
            selectedCategory = normalizedCategory;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL FILTER
    |--------------------------------------------------------------------------
    */

    filterFaqs();

});
</script>

@endpush
