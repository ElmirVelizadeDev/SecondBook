@php
    $popularTabs = [
        [
            'id'          => 'all-genre',
            'label'       => 'Best Selling',
            'badge'       => 'Best Selling',
            'icon'        => 'bi-bar-chart-line',
            'books'       => $bestSellingBooks,
            'empty_title' => 'No Best Selling Books Available',
            'empty_text'  => 'Best selling books will appear here once they are available.',
        ],
        [
            'id'          => 'business',
            'label'       => 'Trending Now',
            'badge'       => 'Trending',
            'icon'        => 'bi-fire',
            'books'       => $trendingBooks,
            'empty_title' => 'No Trending Books Available',
            'empty_text'  => 'Trending books will appear here once they are available.',
        ],
        [
            'id'          => 'technology',
            'label'       => 'New Arrivals',
            'badge'       => 'New Arrival',
            'icon'        => 'bi-stars',
            'books'       => $newArrivals,
            'empty_title' => 'No New Arrivals Available',
            'empty_text'  => 'New arrivals will appear here once they are added.',
        ],
        [
            'id'          => 'romantic',
            'label'       => 'Editor Picks',
            'badge'       => 'Editor Pick',
            'icon'        => 'bi-pencil-square',
            'books'       => $editorPicks,
            'empty_title' => 'No Editor Picks Available',
            'empty_text'  => 'Editor picks will appear here once they are selected.',
        ],
        [
            'id'          => 'adventure',
            'label'       => 'Most Loved',
            'badge'       => 'Most Loved',
            'icon'        => 'bi-heart',
            'books'       => $mostLovedBooks,
            'empty_title' => 'No Loved Books Available',
            'empty_text'  => 'Loved books will appear here once readers start discovering them.',
        ],
        [
            'id'          => 'fictional',
            'label'       => 'Budget Deals',
            'badge'       => 'Budget Deal',
            'icon'        => 'bi-tags',
            'books'       => $budgetDeals,
            'empty_title' => 'No Budget Deals Available',
            'empty_text'  => 'Budget deals will appear here once they are added.',
        ],
    ];

    $fallbackCover = 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90';
@endphp

<section id="popular-books" class="popular-books-section">
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <header class="popular-books-header">

            <div class="popular-books-heading">
                <span class="popular-books-eyebrow">Top picks from our marketplace</span>
                <h2 class="popular-books-title">Popular Books</h2>
            </div>

            <div class="popular-books-aside">
                <p class="popular-books-description">
                    Explore books readers are buying, discovering, loving,
                    and adding to their shelves across the SecondBook marketplace.
                </p>

                <a href="{{ route('frontend.books') }}" class="popular-books-view-all">
                    <span>View All Books</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </header>


        {{-- ================= TABS ================= --}}
        <div class="popular-books-tabs-wrapper">
            <ul class="popular-books-tabs">
                @foreach($popularTabs as $tabItem)
                    <li
                        data-tab-target="#{{ $tabItem['id'] }}"
                        class="tab {{ $loop->first ? 'active' : '' }}"
                    >
                        <i class="bi {{ $tabItem['icon'] }}"></i>
                        <span>{{ $tabItem['label'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>


        {{-- ================= TAB CONTENT ================= --}}
        <div class="popular-books-content">

            @foreach($popularTabs as $tabItem)

                <div
                    id="{{ $tabItem['id'] }}"
                    data-tab-content
                    @if($loop->first) class="active" @endif
                >
                    <div class="popular-books-grid">

                        @forelse($tabItem['books'] as $book)

                            @php
                                $bookImage = null;

                                if (!empty($book->cover)) {
                                    $bookImage = filter_var($book->cover, FILTER_VALIDATE_URL)
                                        ? $book->cover
                                        : asset('storage/' . $book->cover);
                                }

                                $originalPrice   = (float) $book->price;
                                $discountedPrice = (float) $book->discounted_price;
                            @endphp

                            <article class="popular-book-card"  onclick="if (!event.target.closest('.add-to-cart-form')) window.location.href = 
                                '{{ route('frontend.books.show', $book) }}';"
                                style="cursor: pointer;">

                                <div class="popular-book-image-wrap">

                                    <span class="popular-book-badge">{{ $tabItem['badge'] }}</span>

                                    @if($book->is_discount_active)
                                        <span class="popular-book-discount">{{ $book->discount_label }}</span>
                                    @endif

                                    <div class="popular-book-cover">
                                        <img
                                            src="{{ $bookImage ?: $fallbackCover }}"
                                            alt="{{ $book->title }}"
                                            loading="lazy"
                                        >
                                    </div>

                                    <form
                                        action="{{ route('frontend.cart.add', $book) }}"
                                        method="POST"
                                        class="add-to-cart-form"
                                    >
                                        @csrf

                                        <input type="hidden" name="quantity" value="1">

                                        <button type="submit" class="add-to-cart popular-book-cart-btn">
                                            <i class="bi bi-cart3"></i>
                                            <span>Add to Cart</span>
                                        </button>
                                    </form>

                                </div>

                                <div class="popular-book-info">

                                    <h3 title="{{ $book->title }}">{{ $book->title }}</h3>

                                    <p class="popular-book-author">
                                        {{ $book->author->name ?? 'Unknown Author' }}
                                    </p>

                                    <div class="popular-book-bottom">

                                        <div class="popular-book-price-wrap">
                                            @if($book->is_discount_active)
                                                <span class="popular-book-price popular-book-discounted-price">
                                                    ${{ number_format($discountedPrice, 2) }}
                                                </span>
                                                <span class="popular-book-old-price">
                                                    ${{ number_format($originalPrice, 2) }}
                                                </span>
                                            @else
                                                <span class="popular-book-price">
                                                    ${{ number_format($originalPrice, 2) }}
                                                </span>
                                            @endif
                                        </div>

                                        <span class="popular-book-arrow">
                                            <i class="bi bi-arrow-up-right"></i>
                                        </span>

                                    </div>

                                </div>

                            </article>

                        @empty

                            <div class="popular-books-empty">
                                <div class="popular-books-empty-icon">
                                    <i class="bi {{ $tabItem['icon'] }}"></i>
                                </div>
                                <h3>{{ $tabItem['empty_title'] }}</h3>
                                <p>{{ $tabItem['empty_text'] }}</p>
                            </div>

                        @endforelse

                    </div>
                </div>

            @endforeach

        </div>

    </div>
</section>