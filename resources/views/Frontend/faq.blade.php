@extends('Layout.Frontend.master')

@section('title', 'FAQ | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/faq.css') }}">
@endpush

@section('content')

<main class="sb-faq-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="sb-faq-hero">

        <div class="sb-faq-hero-decoration sb-faq-decoration-one"></div>
        <div class="sb-faq-hero-decoration sb-faq-decoration-two"></div>

        <div class="container">

            <div class="sb-faq-hero-content">

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

            <div class="sb-faq-section-heading">

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
                    Browse the questions below or search for a
                    specific answer.
                </p>

            </div>


            {{-- SEARCH --}}

            <div class="sb-faq-search-wrapper">

                <div class="sb-faq-search">

                    <span class="sb-faq-search-icon">
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

                <div class="sb-faq-filter-row">

                    <div class="sb-faq-filter-title">
                        <i class="bi bi-funnel"></i>
                        <span>Browse by category</span>
                    </div>

                    <div class="sb-faq-categories">

                        <button
                            type="button"
                            class="sb-faq-category active"
                            data-category="all"
                        >
                            <i class="bi bi-grid"></i>
                            <span>All Questions</span>
                        </button>

                        @foreach($categories as $category)

                            <button
                                type="button"
                                class="sb-faq-category"
                                data-category="{{ strtolower($category) }}"
                            >
                                <span>{{ $category }}</span>
                            </button>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- FAQ LIST --}}

            <div class="sb-faq-content">

                <div
                    class="sb-faq-list"
                    id="faqList"
                >

                    @forelse($faqs as $faq)

                        <article
                            class="sb-faq-item"
                            data-category="{{ strtolower($faq->category ?? '') }}"
                            data-question="{{ strtolower($faq->question ?? '') }}"
                            data-answer="{{ strtolower($faq->answer ?? '') }}"
                        >

                            <button
                                type="button"
                                class="sb-faq-question"
                                aria-expanded="false"
                            >

                                <span class="sb-faq-question-left">

                                    <span class="sb-faq-icon">
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

                                <span class="sb-faq-toggle">
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

                                        <p>
                                            {{ $faq->answer }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="sb-faq-empty">

                            <div class="sb-faq-empty-icon">
                                <i class="bi bi-question-circle"></i>
                            </div>

                            <span class="sb-faq-empty-label">
                                Knowledge Base
                            </span>

                            <h3>
                                No FAQs available
                            </h3>

                            <p>
                                There are currently no frequently
                                asked questions available.
                            </p>

                        </div>

                    @endforelse


                    {{-- NO SEARCH RESULTS --}}

                    <div
                        class="sb-faq-no-results"
                        id="faqNoResults"
                        style="display: none;"
                    >

                        <div class="sb-faq-empty-icon">
                            <i class="bi bi-search"></i>
                        </div>

                        <span class="sb-faq-empty-label">
                            Search
                        </span>

                        <h3>
                            No matching questions
                        </h3>

                        <p>
                            We couldn't find anything matching your
                            search. Try another keyword or category.
                        </p>

                    </div>

                </div>


                {{-- FAQ SIDE NOTE --}}

                <aside class="sb-faq-side-card">

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

                </aside>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CONTACT CTA
    ========================================================== --}}

    <section class="sb-faq-contact">

        <div class="container">

            <div class="sb-faq-contact-box">

                <div class="sb-faq-contact-decoration"></div>

                <div class="sb-faq-contact-icon">
                    <i class="bi bi-chat-square-text"></i>
                </div>

                <div class="sb-faq-contact-content">

                    <span>
                        Still need help?
                    </span>

                    <h2>
                        We're here for you.
                    </h2>

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

    const searchInput = document.getElementById('faqSearch');
    const clearButton = document.getElementById('faqSearchClear');
    const noResults = document.getElementById('faqNoResults');
    const items = document.querySelectorAll('.sb-faq-item');
    const categoryButtons = document.querySelectorAll('.sb-faq-category');

    let selectedCategory = 'all';


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

            const isOpen = item.classList.contains('active');

            items.forEach(function (otherItem) {

                otherItem.classList.remove('active');

                const otherQuestion =
                    otherItem.querySelector('.sb-faq-question');

                if (otherQuestion) {
                    otherQuestion.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }

            });

            if (!isOpen) {

                item.classList.add('active');

                question.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

    function filterFaqs() {

        const searchValue = searchInput
            ? searchInput.value.trim().toLowerCase()
            : '';

        let visibleCount = 0;

        items.forEach(function (item) {

            const category =
                item.dataset.category || '';

            const question =
                item.dataset.question || '';

            const answer =
                item.dataset.answer || '';

            const matchesCategory =
                selectedCategory === 'all' ||
                category === selectedCategory;

            const matchesSearch =
                !searchValue ||
                question.includes(searchValue) ||
                answer.includes(searchValue) ||
                category.includes(searchValue);

            if (matchesCategory && matchesSearch) {

                item.style.display = '';
                visibleCount++;

            } else {

                item.style.display = 'none';
                item.classList.remove('active');

                const questionButton =
                    item.querySelector('.sb-faq-question');

                if (questionButton) {

                    questionButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            }

        });

        if (noResults) {

            noResults.style.display =
                visibleCount === 0 ? 'block' : 'none';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterFaqs
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR SEARCH
    |--------------------------------------------------------------------------
    */

    if (clearButton) {

        clearButton.addEventListener(
            'click',
            function () {

                if (!searchInput) {
                    return;
                }

                searchInput.value = '';

                filterFaqs();

                searchInput.focus();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY FILTER
    |--------------------------------------------------------------------------
    */

    categoryButtons.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                categoryButtons.forEach(
                    function (btn) {
                        btn.classList.remove('active');
                    }
                );

                button.classList.add('active');

                selectedCategory =
                    button.dataset.category || 'all';

                filterFaqs();

            }
        );

    });

});
</script>

@endpush