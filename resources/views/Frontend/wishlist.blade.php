
@extends('Layout.Frontend.master')

@section('title', 'My Wishlist | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/wishlist.css') }}">
@endpush

@section('content')

<main class="sb-wishlist-page">

    {{-- =========================================================
        ANIMATED BACKGROUND
    ========================================================== --}}

    <div class="wishlist-ambient" aria-hidden="true">
        <div class="wishlist-ambient-orb wishlist-orb-one"></div>
        <div class="wishlist-ambient-orb wishlist-orb-two"></div>
        <div class="wishlist-ambient-orb wishlist-orb-three"></div>
        <div class="wishlist-ambient-grid"></div>
        <div class="wishlist-ambient-grain"></div>
    </div>

    {{-- =========================================================
        HERO
    ========================================================== --}}

    <section class="wishlist-hero">
        <div class="container">

            <nav class="wishlist-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}">
                    <i class="bi bi-house"></i>
                    <span>Home</span>
                </a>

                <i class="bi bi-chevron-right breadcrumb-divider"></i>

                <span class="breadcrumb-current">Wishlist</span>
            </nav>

            <div class="wishlist-hero-grid">

                <div class="wishlist-hero-content">

                    <div class="wishlist-eyebrow">
                        <span class="wishlist-eyebrow-icon">
                            <i class="bi bi-heart-fill"></i>
                        </span>
                        <span>YOUR PERSONAL LIBRARY</span>
                        <span class="wishlist-eyebrow-line"></span>
                    </div>

                    <h1 class="wishlist-hero-title">
                        Some stories
                        <span>stay with you.</span>
                    </h1>

                    <p class="wishlist-hero-description">
                        A little corner for the books that caught your eye.
                        Keep your next great discovery close, until the
                        moment feels right to turn the first page.
                    </p>

                    <div class="wishlist-hero-bottom">

                        <a href="{{ route('frontend.books') }}" class="wishlist-hero-cta">
                            <span>Discover more books</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <div class="wishlist-hero-note">
                            <i class="bi bi-book-half"></i>
                            <span>A collection of possibilities</span>
                        </div>

                    </div>

                </div>

                <div class="wishlist-hero-art" aria-hidden="true">

                    <div class="wishlist-art-orbit wishlist-art-orbit-one"></div>
                    <div class="wishlist-art-orbit wishlist-art-orbit-two"></div>

                    <div class="wishlist-art-star wishlist-star-one">✦</div>
                    <div class="wishlist-art-star wishlist-star-two">✧</div>
                    <div class="wishlist-art-star wishlist-star-three">✦</div>

                    <div class="wishlist-art-book wishlist-art-book-back">
                        <div class="wishlist-art-book-spine"></div>
                        <div class="wishlist-art-book-lines"></div>
                    </div>

                    <div class="wishlist-art-book wishlist-art-book-front">
                        <div class="wishlist-art-book-cover">
                            <span class="wishlist-art-cover-mark">S</span>
                            <span class="wishlist-art-cover-rule"></span>
                            <span class="wishlist-art-cover-caption">A WORLD<br>OF STORIES</span>
                            <span class="wishlist-art-cover-flower">✳</span>
                        </div>
                    </div>

                    <div class="wishlist-art-heart">
                        <i class="bi bi-heart-fill"></i>
                    </div>

                    <div class="wishlist-art-caption">
                        <span class="wishlist-art-caption-line"></span>
                        <span>Made of stories</span>
                    </div>

                    <div class="wishlist-collection-chip">
                        <span class="wishlist-chip-icon">
                            <i class="bi bi-bookmark-heart-fill"></i>
                        </span>
                        <span class="wishlist-chip-copy">
                            <small>YOUR COLLECTION</small>
                            <strong>
                                {{ $wishlists->total() }}
                                {{ $wishlists->total() === 1 ? 'book' : 'books' }}
                            </strong>
                        </span>
                    </div>

                </div>

            </div>

            <div class="wishlist-hero-footer">
                <span>COLLECT THE STORIES THAT MOVE YOU</span>
                <span class="wishlist-hero-footer-line"></span>
                <span class="wishlist-hero-footer-number">SB / 01</span>
            </div>

        </div>
    </section>

    {{-- =========================================================
        WISHLIST CONTENT
    ========================================================== --}}

    <section class="wishlist-section">
        <div class="container">

            {{-- SESSION ALERTS --}}

            @if(session('success'))
                <div class="wishlist-alert wishlist-alert-success" role="status">
                    <span class="wishlist-alert-icon">
                        <i class="bi bi-check2"></i>
                    </span>
                    <div class="wishlist-alert-copy">
                        <strong>All good</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                    <span class="wishlist-alert-accent"></span>
                </div>
            @endif

            @if(session('error'))
                <div class="wishlist-alert wishlist-alert-error" role="alert">
                    <span class="wishlist-alert-icon">
                        <i class="bi bi-exclamation-lg"></i>
                    </span>
                    <div class="wishlist-alert-copy">
                        <strong>Something went wrong</strong>
                        <span>{{ session('error') }}</span>
                    </div>
                    <span class="wishlist-alert-accent"></span>
                </div>
            @endif

            @if($wishlists->count())

                {{-- COLLECTION HEADER --}}

                <div class="wishlist-topbar">

                    <div class="wishlist-topbar-heading">

                        <div class="wishlist-section-kicker">
                            <span class="wishlist-kicker-dot"></span>
                            THE BOOKS YOU CHOSE
                        </div>

                        <h2>
                            Your <span>collection.</span>
                        </h2>

                        <p>
                            Every saved title has a place here.
                            Take your time finding your next read.
                        </p>

                    </div>

                    <div class="wishlist-topbar-side">

                        <div class="wishlist-total-panel">
                            <span class="wishlist-total-label">COLLECTION SIZE</span>
                            <div class="wishlist-total-number">
                                <strong data-wishlist-total>{{ $wishlists->total() }}</strong>
                                <span>{{ $wishlists->total() === 1 ? 'TITLE' : 'TITLES' }}</span>
                            </div>
                            <span class="wishlist-total-rule"></span>
                        </div>

                        <a href="{{ route('frontend.books') }}" class="wishlist-browse-btn">
                            <span>Explore the shelves</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>

                <div class="wishlist-results-line">
                    <span>
                        <i class="bi bi-grid-3x3-gap"></i>
                        YOUR SAVED TITLES
                    </span>
                    <span class="wishlist-results-count">
                        {{ $wishlists->firstItem() ?? 0 }}–{{ $wishlists->lastItem() ?? 0 }}
                        <span class="wishlist-results-muted">OF</span>
                        {{ $wishlists->total() }}
                    </span>
                </div>

                {{-- BOOK GRID --}}

                <div class="wishlist-grid">

                    @foreach($wishlists as $wishlist)

                        @php
                            $book = $wishlist->book;
                            $coverUrl = null;

                            if ($book && $book->cover) {
                                if (
                                    str_starts_with($book->cover, 'http://') ||
                                    str_starts_with($book->cover, 'https://')
                                ) {
                                    $coverUrl = $book->cover;
                                } elseif (
                                    str_starts_with($book->cover, 'storage/')
                                ) {
                                    $coverUrl = asset($book->cover);
                                } elseif (
                                    str_starts_with($book->cover, 'uploads/')
                                ) {
                                    $coverUrl = asset($book->cover);
                                } else {
                                    $coverUrl = asset(
                                        'storage/' . ltrim($book->cover, '/')
                                    );
                                }
                            }

                            $condition = $book
                                ? ucfirst(str_replace('_', ' ', $book->condition ?? 'good'))
                                : null;
                        @endphp

                        @if($book)

                            <article class="wishlist-card" data-wishlist-card>

                                <div class="wishlist-card-visual">

                                    <a
                                        href="{{ route('frontend.books.show', $book->id) }}"
                                        class="wishlist-cover-link"
                                        aria-label="View {{ $book->title }}"
                                    >
                                        <div class="wishlist-cover">

                                            <div class="wishlist-cover-light"></div>

                                            @if($coverUrl)
                                                <img
                                                    src="{{ $coverUrl }}"
                                                    alt="{{ $book->title }}"
                                                    loading="lazy"
                                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                                                >

                                                <div class="wishlist-no-cover" style="display:none;">
                                                    <i class="bi bi-book-half"></i>
                                                    <span>Cover unavailable</span>
                                                </div>
                                            @else
                                                <div class="wishlist-no-cover">
                                                    <i class="bi bi-book-half"></i>
                                                    <span>Cover unavailable</span>
                                                </div>
                                            @endif

                                            <span class="wishlist-cover-corner"></span>

                                        </div>
                                    </a>

                                    <span class="wishlist-cover-index">
                                        SB <span>/</span> {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    @if($condition)
                                        <span class="wishlist-condition-badge">
                                            <i class="bi bi-stars"></i>
                                            {{ $condition }}
                                        </span>
                                    @endif

                                    <form
                                        action="{{ route('frontend.wishlist.remove', $book->id) }}"
                                        method="POST"
                                        class="wishlist-heart-form"
                                        data-wishlist-delete
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="wishlist-heart-btn"
                                            aria-label="Remove {{ $book->title }} from wishlist"
                                            title="Remove from wishlist"
                                            data-delete-button
                                        >
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </form>

                                    @if($book->stock <= 0)
                                        <div class="wishlist-out-overlay">
                                            <span class="wishlist-out-icon">
                                                <i class="bi bi-hourglass"></i>
                                            </span>
                                            <span>Currently unavailable</span>
                                        </div>
                                    @endif

                                </div>

                                <div class="wishlist-card-content">

                                    <div class="wishlist-card-meta">

                                        <span class="wishlist-category">
                                            <i class="bi bi-bookmark"></i>
                                            {{ $book->category->name ?? 'Book' }}
                                        </span>

                                        @if($book->stock > 0)
                                            <span class="wishlist-stock available">
                                                <span class="wishlist-stock-dot"></span>
                                                In stock
                                            </span>
                                        @else
                                            <span class="wishlist-stock unavailable">
                                                <span class="wishlist-stock-dot"></span>
                                                Out of stock
                                            </span>
                                        @endif

                                    </div>

                                    <h3 class="wishlist-book-title">
                                        <a href="{{ route('frontend.books.show', $book->id) }}">
                                            {{ $book->title }}
                                        </a>
                                    </h3>

                                    <div class="wishlist-author">
                                        <span class="wishlist-author-icon">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <span class="wishlist-author-copy">
                                            <small>WRITTEN BY</small>
                                            <strong>{{ $book->author->name ?? 'Unknown Author' }}</strong>
                                        </span>
                                    </div>

                                    @if($book->description)
                                        <p class="wishlist-description">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($book->description), 100) }}
                                        </p>
                                    @else
                                        <p class="wishlist-description wishlist-description-empty">
                                            A story waiting to be discovered.
                                        </p>
                                    @endif

                                    <div class="wishlist-card-divider"></div>

                                    <div class="wishlist-price-row">

                                        <div class="wishlist-price-info">
                                            <span class="wishlist-price-label">CURRENT PRICE</span>
                                            <strong class="wishlist-price">
                                                <span class="wishlist-currency">$</span>{{ number_format($book->price, 2) }}
                                            </strong>
                                        </div>

                                        @if($book->stock > 0)
                                            <div class="wishlist-availability">
                                                <i class="bi bi-box-seam"></i>
                                                <span>
                                                    {{ $book->stock }}
                                                    {{ $book->stock === 1 ? 'copy' : 'copies' }}
                                                </span>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="wishlist-actions">

                                        <a
                                            href="{{ route('frontend.books.show', $book->id) }}"
                                            class="wishlist-view-btn"
                                        >
                                            <span>Explore this book</span>
                                            <i class="bi bi-arrow-up-right"></i>
                                        </a>

                                        <form
                                            action="{{ route('frontend.wishlist.remove', $book->id) }}"
                                            method="POST"
                                            class="wishlist-delete-form"
                                            data-wishlist-delete
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="wishlist-remove-btn"
                                                title="Remove from wishlist"
                                                aria-label="Remove {{ $book->title }} from wishlist"
                                                data-delete-button
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            </article>

                        @endif

                    @endforeach

                </div>

                {{-- PAGINATION --}}

                @if($wishlists->hasPages())
                    <div class="wishlist-pagination">
                        {{ $wishlists->links() }}
                    </div>
                @endif

                <div class="wishlist-bottom-note">
                    <span class="wishlist-bottom-note-icon">
                        <i class="bi bi-book-half"></i>
                    </span>
                    <p>
                        Good books are worth coming back to.
                        <a href="{{ route('frontend.books') }}">Find another story.</a>
                    </p>
                    <span class="wishlist-bottom-note-mark">SB.</span>
                </div>

            @else

                {{-- EMPTY WISHLIST --}}

                <div class="wishlist-empty">

                    <div class="wishlist-empty-art" aria-hidden="true">

                        <span class="wishlist-empty-orbit wishlist-empty-orbit-one"></span>
                        <span class="wishlist-empty-orbit wishlist-empty-orbit-two"></span>
                        <span class="wishlist-empty-star wishlist-empty-star-one">✦</span>
                        <span class="wishlist-empty-star wishlist-empty-star-two">✧</span>

                        <div class="wishlist-empty-book">
                            <div class="wishlist-empty-book-cover">
                                <span class="wishlist-empty-book-mark">S</span>
                                <span class="wishlist-empty-book-rule"></span>
                                <i class="bi bi-heart"></i>
                            </div>
                        </div>

                        <div class="wishlist-empty-heart">
                            <i class="bi bi-heart-fill"></i>
                        </div>

                    </div>

                    <div class="wishlist-empty-kicker">
                        <span></span>
                        A LITTLE SPACE FOR BIG STORIES
                        <span></span>
                    </div>

                    <h2>
                        Your next favorite
                        <span>is still out there.</span>
                    </h2>

                    <p>
                        There are wonderful stories waiting to find you.
                        Explore the shelves, follow your curiosity, and save
                        the books you would love to return to.
                    </p>

                    <div class="wishlist-empty-actions">

                        <a href="{{ route('frontend.books') }}" class="wishlist-empty-btn">
                            <span>Explore the collection</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a href="{{ route('frontend.home') }}" class="wishlist-empty-home-btn">
                            <i class="bi bi-house"></i>
                            <span>Return home</span>
                        </a>

                    </div>

                    <div class="wishlist-empty-footnote">
                        <i class="bi bi-stars"></i>
                        EVERY GREAT COLLECTION STARTS WITH ONE BOOK
                    </div>

                </div>

            @endif

        </div>
    </section>

</main>

{{-- =========================================================
    WISHLIST AJAX
========================================================= --}}

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.sb-wishlist-page');

    if (!page) {
        return;
    }

    const deleteForms = page.querySelectorAll('[data-wishlist-delete]');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            event.stopPropagation();

            const button = form.querySelector('[data-delete-button]');
            const card = form.closest('[data-wishlist-card]');
            const token = form.querySelector('input[name="_token"]');

            if (!button || !card || !token || button.disabled) {
                return;
            }

            button.disabled = true;
            form.classList.add('is-removing');

            try {
                const response = await fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token.value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                let data = {};

                try {
                    data = await response.json();
                } catch (jsonError) {
                    data = {};
                }

                if (!response.ok) {
                    throw new Error(
                        data.message || 'Failed to remove this book. Please try again.'
                    );
                }

                card.classList.add('wishlist-card-removing');

                window.setTimeout(function () {
                    card.remove();
                    updateWishlistCount();

                    showWishlistAlert(
                        data.message || 'Book removed from your wishlist.'
                    );
                }, 280);

            } catch (error) {
                console.error('Wishlist delete error:', error);

                button.disabled = false;
                form.classList.remove('is-removing');

                showWishlistAlert(
                    error.message || 'Something went wrong. Please try again.',
                    'error'
                );
            }
        });
    });

    function updateWishlistCount() {
        const cards = page.querySelectorAll('[data-wishlist-card]');
        const remaining = cards.length;
        const heroCount = page.querySelector('.wishlist-collection-chip strong');
        const totalCount = page.querySelector('[data-wishlist-total]');
        const topbar = page.querySelector('.wishlist-topbar');
        const resultsLine = page.querySelector('.wishlist-results-line');
        const bottomNote = page.querySelector('.wishlist-bottom-note');
        const pagination = page.querySelector('.wishlist-pagination');
        const headerCount = document.getElementById('header-wishlist-count');

        if (heroCount) {
            heroCount.textContent = remaining + (remaining === 1 ? ' book' : ' books');
        }

        if (totalCount) {
            totalCount.textContent = remaining;
        }

        if (headerCount) {
            headerCount.textContent = remaining > 99 ? '99+' : remaining;
        }

        if (remaining === 0) {
            showEmptyWishlist();
            return;
        }

        const totalLabel = page.querySelector('.wishlist-total-number span');

        if (totalLabel) {
            totalLabel.textContent = remaining === 1 ? 'TITLE' : 'TITLES';
        }

        if (topbar) {
            const total = topbar.querySelector('.wishlist-total-number strong');

            if (total) {
                total.textContent = remaining;
            }
        }

        if (resultsLine) {
            const resultCount = resultsLine.querySelector('.wishlist-results-count');

            if (resultCount) {
                resultCount.textContent = remaining + ' ' + (remaining === 1 ? 'TITLE' : 'TITLES');
            }
        }

        if (pagination && remaining === 0) {
            pagination.remove();
        }

        if (bottomNote && remaining === 0) {
            bottomNote.remove();
        }
    }

    function showEmptyWishlist() {
        const grid = page.querySelector('.wishlist-grid');
        const topbar = page.querySelector('.wishlist-topbar');
        const resultsLine = page.querySelector('.wishlist-results-line');
        const pagination = page.querySelector('.wishlist-pagination');
        const bottomNote = page.querySelector('.wishlist-bottom-note');

        [grid, topbar, resultsLine, pagination, bottomNote].forEach(function (element) {
            if (element) {
                element.remove();
            }
        });

        if (page.querySelector('.wishlist-empty')) {
            return;
        }

        const container = page.querySelector('.wishlist-section .container');

        if (!container) {
            return;
        }

        const empty = document.createElement('div');
        empty.className = 'wishlist-empty';

        const art = document.createElement('div');
        art.className = 'wishlist-empty-art';
        art.setAttribute('aria-hidden', 'true');

        const orbitOne = document.createElement('span');
        orbitOne.className = 'wishlist-empty-orbit wishlist-empty-orbit-one';

        const orbitTwo = document.createElement('span');
        orbitTwo.className = 'wishlist-empty-orbit wishlist-empty-orbit-two';

        const starOne = document.createElement('span');
        starOne.className = 'wishlist-empty-star wishlist-empty-star-one';
        starOne.textContent = '✦';

        const starTwo = document.createElement('span');
        starTwo.className = 'wishlist-empty-star wishlist-empty-star-two';
        starTwo.textContent = '✧';

        const book = document.createElement('div');
        book.className = 'wishlist-empty-book';

        const bookCover = document.createElement('div');
        bookCover.className = 'wishlist-empty-book-cover';

        const bookMark = document.createElement('span');
        bookMark.className = 'wishlist-empty-book-mark';
        bookMark.textContent = 'S';

        const bookRule = document.createElement('span');
        bookRule.className = 'wishlist-empty-book-rule';

        const bookHeart = document.createElement('i');
        bookHeart.className = 'bi bi-heart';

        bookCover.append(bookMark, bookRule, bookHeart);
        book.appendChild(bookCover);

        const floatingHeart = document.createElement('div');
        floatingHeart.className = 'wishlist-empty-heart';

        const floatingHeartIcon = document.createElement('i');
        floatingHeartIcon.className = 'bi bi-heart-fill';
        floatingHeart.appendChild(floatingHeartIcon);

        art.append(orbitOne, orbitTwo, starOne, starTwo, book, floatingHeart);

        const kicker = document.createElement('div');
        kicker.className = 'wishlist-empty-kicker';

        const kickerLeft = document.createElement('span');
        const kickerRight = document.createElement('span');
        const kickerText = document.createElement('span');

        kickerText.textContent = 'A LITTLE SPACE FOR BIG STORIES';
        kicker.append(kickerLeft, kickerText, kickerRight);

        const heading = document.createElement('h2');
        heading.append(document.createTextNode('Your next favorite '));

        const headingAccent = document.createElement('span');
        headingAccent.textContent = 'is still out there.';
        heading.appendChild(headingAccent);

        const description = document.createElement('p');
        description.textContent =
            'There are wonderful stories waiting to find you. Explore the shelves, follow your curiosity, and save the books you would love to return to.';

        const actions = document.createElement('div');
        actions.className = 'wishlist-empty-actions';

        const explore = document.createElement('a');
        explore.href = @json(route('frontend.books'));
        explore.className = 'wishlist-empty-btn';

        const exploreText = document.createElement('span');
        exploreText.textContent = 'Explore the collection';

        const exploreIcon = document.createElement('i');
        exploreIcon.className = 'bi bi-arrow-up-right';

        explore.append(exploreText, exploreIcon);

        const home = document.createElement('a');
        home.href = @json(route('frontend.home'));
        home.className = 'wishlist-empty-home-btn';

        const homeIcon = document.createElement('i');
        homeIcon.className = 'bi bi-house';

        const homeText = document.createElement('span');
        homeText.textContent = 'Return home';

        home.append(homeIcon, homeText);
        actions.append(explore, home);

        const footnote = document.createElement('div');
        footnote.className = 'wishlist-empty-footnote';

        const footnoteIcon = document.createElement('i');
        footnoteIcon.className = 'bi bi-stars';

        const footnoteText = document.createElement('span');
        footnoteText.textContent = 'EVERY GREAT COLLECTION STARTS WITH ONE BOOK';

        footnote.append(footnoteIcon, footnoteText);

        empty.append(art, kicker, heading, description, actions, footnote);
        container.appendChild(empty);
    }

    function showWishlistAlert(message, type = 'success') {
        const section = page.querySelector('.wishlist-section .container');

        if (!section) {
            return;
        }

        const existing = section.querySelector('.wishlist-ajax-alert');

        if (existing) {
            existing.remove();
        }

        const alert = document.createElement('div');
        alert.className = type === 'error'
            ? 'wishlist-alert wishlist-alert-error wishlist-ajax-alert'
            : 'wishlist-alert wishlist-alert-success wishlist-ajax-alert';

        alert.setAttribute('role', type === 'error' ? 'alert' : 'status');

        const iconWrap = document.createElement('span');
        iconWrap.className = 'wishlist-alert-icon';

        const icon = document.createElement('i');
        icon.className = type === 'error' ? 'bi bi-exclamation-lg' : 'bi bi-check2';
        iconWrap.appendChild(icon);

        const copy = document.createElement('div');
        copy.className = 'wishlist-alert-copy';

        const title = document.createElement('strong');
        title.textContent = type === 'error' ? 'Something went wrong' : 'Saved changes';

        const messageText = document.createElement('span');
        messageText.textContent = message;

        copy.append(title, messageText);

        const accent = document.createElement('span');
        accent.className = 'wishlist-alert-accent';

        alert.append(iconWrap, copy, accent);

        const topbar = section.querySelector('.wishlist-topbar');

        if (topbar) {
            section.insertBefore(alert, topbar);
        } else {
            section.prepend(alert);
        }

        window.setTimeout(function () {
            alert.classList.add('wishlist-alert-hiding');

            window.setTimeout(function () {
                alert.remove();
            }, 300);
        }, 2800);
    }
});
</script>
@endpush

@endsection
