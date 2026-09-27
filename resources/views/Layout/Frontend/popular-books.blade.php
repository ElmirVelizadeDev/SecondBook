<section id="popular-books" class="popular-books-section">
    <div class="container">

        {{-- =====================================================
            SECTION HEADER
        ====================================================== --}}

        <div class="popular-books-header">

            <div class="popular-books-heading">

                <span class="popular-books-eyebrow">
                    Top picks from our marketplace
                </span>

                <h2 class="popular-books-title">
                    Popular Books
                </h2>

                <div class="popular-books-divider"></div>

                <p class="popular-books-description">
                    Explore books readers are buying, discovering, loving,
                    and adding to their shelves across the SecondBook marketplace.
                </p>

            </div>

            <a
                href="{{ route('frontend.books') }}"
                class="popular-books-view-all"
            >
                <span>View All Books</span>
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        {{-- =====================================================
            CATEGORY TABS
        ====================================================== --}}

        <div class="popular-books-tabs-wrapper">

            <ul class="popular-books-tabs">

                <li
                    data-tab-target="#all-genre"
                    class="active tab"
                >
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Best Selling</span>
                </li>

                <li
                    data-tab-target="#business"
                    class="tab"
                >
                    <i class="bi bi-fire"></i>
                    <span>Trending Now</span>
                </li>

                <li
                    data-tab-target="#technology"
                    class="tab"
                >
                    <i class="bi bi-stars"></i>
                    <span>New Arrivals</span>
                </li>

                <li
                    data-tab-target="#romantic"
                    class="tab"
                >
                    <i class="bi bi-pencil-square"></i>
                    <span>Editor Picks</span>
                </li>

                <li
                    data-tab-target="#adventure"
                    class="tab"
                >
                    <i class="bi bi-heart"></i>
                    <span>Most Loved</span>
                </li>

                <li
                    data-tab-target="#fictional"
                    class="tab"
                >
                    <i class="bi bi-tags"></i>
                    <span>Budget Deals</span>
                </li>

            </ul>

        </div>


        {{-- =====================================================
            TAB CONTENT
        ====================================================== --}}

        <div class="popular-books-content">


            {{-- =================================================
                BEST SELLING
            ================================================== --}}

            <div
                id="all-genre"
                data-tab-content
                class="active"
            >

                <div class="popular-books-grid">

                    @forelse($bestSellingBooks as $book)

                        @php
                            $bookImage = null;

                            if (!empty($book->cover)) {
                                $bookImage = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);
                            }

                            $originalPrice = (float) $book->price;
                            $discountedPrice = (float) $book->discounted_price;
                        @endphp

                        <article class="popular-book-card">

                            <div class="popular-book-image-wrap">

                                @if($bookImage)

                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @endif

                                <span class="popular-book-badge">
                                    Best Selling
                                </span>

                                @if($book->is_discount_active)

                                    <span class="popular-book-discount">
                                        {{ $book->discount_label }}
                                    </span>

                                @endif

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="add-to-cart-form"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="add-to-cart popular-book-cart-btn"
                                    >
                                        <i class="bi bi-cart3"></i>
                                        <span>Add to Cart</span>
                                    </button>

                                </form>

                            </div>

                            <div class="popular-book-info">

                                <h3>
                                    {{ $book->title }}
                                </h3>

                                <p class="popular-book-author">
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="popular-book-bottom">

                                    <div class="popular-book-price-wrap">

                                        @if($book->is_discount_active)

                                            <span class="popular-book-old-price">
                                                ${{ number_format($originalPrice, 2) }}
                                            </span>

                                            <span class="popular-book-price popular-book-discounted-price">
                                                ${{ number_format($discountedPrice, 2) }}
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
                                <i class="bi bi-book"></i>
                            </div>

                            <h3>
                                No Best Selling Books Available
                            </h3>

                            <p>
                                Best selling books will appear here once they are available.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                TRENDING NOW
            ================================================== --}}

            <div
                id="business"
                data-tab-content
            >

                <div class="popular-books-grid">

                    @forelse($trendingBooks as $book)

                        @php
                            $bookImage = null;

                            if (!empty($book->cover)) {
                                $bookImage = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);
                            }

                            $originalPrice = (float) $book->price;
                            $discountedPrice = (float) $book->discounted_price;
                        @endphp

                        <article class="popular-book-card">

                            <div class="popular-book-image-wrap">

                                @if($bookImage)

                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @endif

                                <span class="popular-book-badge">
                                    Trending
                                </span>

                                @if($book->is_discount_active)

                                    <span class="popular-book-discount">
                                        {{ $book->discount_label }}
                                    </span>

                                @endif

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="add-to-cart-form"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="add-to-cart popular-book-cart-btn"
                                    >
                                        <i class="bi bi-cart3"></i>
                                        <span>Add to Cart</span>
                                    </button>

                                </form>

                            </div>

                            <div class="popular-book-info">

                                <h3>
                                    {{ $book->title }}
                                </h3>

                                <p class="popular-book-author">
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="popular-book-bottom">

                                    <div class="popular-book-price-wrap">

                                        @if($book->is_discount_active)

                                            <span class="popular-book-old-price">
                                                ${{ number_format($originalPrice, 2) }}
                                            </span>

                                            <span class="popular-book-price popular-book-discounted-price">
                                                ${{ number_format($discountedPrice, 2) }}
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
                                <i class="bi bi-fire"></i>
                            </div>

                            <h3>
                                No Trending Books Available
                            </h3>

                            <p>
                                Trending books will appear here once they are available.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                NEW ARRIVALS
            ================================================== --}}

            <div
                id="technology"
                data-tab-content
            >

                <div class="popular-books-grid">

                    @forelse($newArrivals as $book)

                        @php
                            $bookImage = null;

                            if (!empty($book->cover)) {
                                $bookImage = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);
                            }

                            $originalPrice = (float) $book->price;
                            $discountedPrice = (float) $book->discounted_price;
                        @endphp

                        <article class="popular-book-card">

                            <div class="popular-book-image-wrap">

                                @if($bookImage)

                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @endif

                                <span class="popular-book-badge">
                                    New Arrival
                                </span>

                                @if($book->is_discount_active)

                                    <span class="popular-book-discount">
                                        {{ $book->discount_label }}
                                    </span>

                                @endif

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="add-to-cart-form"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="add-to-cart popular-book-cart-btn"
                                    >
                                        <i class="bi bi-cart3"></i>
                                        <span>Add to Cart</span>
                                    </button>

                                </form>

                            </div>

                            <div class="popular-book-info">

                                <h3>
                                    {{ $book->title }}
                                </h3>

                                <p class="popular-book-author">
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="popular-book-bottom">

                                    <div class="popular-book-price-wrap">

                                        @if($book->is_discount_active)

                                            <span class="popular-book-old-price">
                                                ${{ number_format($originalPrice, 2) }}
                                            </span>

                                            <span class="popular-book-price popular-book-discounted-price">
                                                ${{ number_format($discountedPrice, 2) }}
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
                                <i class="bi bi-stars"></i>
                            </div>

                            <h3>
                                No New Arrivals Available
                            </h3>

                            <p>
                                New arrivals will appear here once they are added.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                EDITOR PICKS
            ================================================== --}}

            <div
                id="romantic"
                data-tab-content
            >

                <div class="popular-books-grid">

                    @forelse($editorPicks as $book)

                        @php
                            $bookImage = null;

                            if (!empty($book->cover)) {
                                $bookImage = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);
                            }

                            $originalPrice = (float) $book->price;
                            $discountedPrice = (float) $book->discounted_price;
                        @endphp

                        <article class="popular-book-card">

                            <div class="popular-book-image-wrap">

                                @if($bookImage)

                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @endif

                                <span class="popular-book-badge">
                                    Editor Pick
                                </span>

                                @if($book->is_discount_active)

                                    <span class="popular-book-discount">
                                        {{ $book->discount_label }}
                                    </span>

                                @endif

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="add-to-cart-form"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="add-to-cart popular-book-cart-btn"
                                    >
                                        <i class="bi bi-cart3"></i>
                                        <span>Add to Cart</span>
                                    </button>

                                </form>

                            </div>

                            <div class="popular-book-info">

                                <h3>
                                    {{ $book->title }}
                                </h3>

                                <p class="popular-book-author">
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="popular-book-bottom">

                                    <div class="popular-book-price-wrap">

                                        @if($book->is_discount_active)

                                            <span class="popular-book-old-price">
                                                ${{ number_format($originalPrice, 2) }}
                                            </span>

                                            <span class="popular-book-price popular-book-discounted-price">
                                                ${{ number_format($discountedPrice, 2) }}
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
                                <i class="bi bi-pencil-square"></i>
                            </div>

                            <h3>
                                No Editor Picks Available
                            </h3>

                            <p>
                                Editor picks will appear here once they are selected.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                MOST LOVED
            ================================================== --}}

            <div
                id="adventure"
                data-tab-content
            >

                <div class="popular-books-grid">

                    @forelse($mostLovedBooks as $book)

                        @php
                            $bookImage = null;

                            if (!empty($book->cover)) {
                                $bookImage = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);
                            }

                            $originalPrice = (float) $book->price;
                            $discountedPrice = (float) $book->discounted_price;
                        @endphp

                        <article class="popular-book-card">

                            <div class="popular-book-image-wrap">

                                @if($bookImage)

                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @endif

                                <span class="popular-book-badge">
                                    Most Loved
                                </span>

                                @if($book->is_discount_active)

                                    <span class="popular-book-discount">
                                        {{ $book->discount_label }}
                                    </span>

                                @endif

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="add-to-cart-form"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="add-to-cart popular-book-cart-btn"
                                    >
                                        <i class="bi bi-cart3"></i>
                                        <span>Add to Cart</span>
                                    </button>

                                </form>

                            </div>

                            <div class="popular-book-info">

                                <h3>
                                    {{ $book->title }}
                                </h3>

                                <p class="popular-book-author">
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="popular-book-bottom">

                                    <div class="popular-book-price-wrap">

                                        @if($book->is_discount_active)

                                            <span class="popular-book-old-price">
                                                ${{ number_format($originalPrice, 2) }}
                                            </span>

                                            <span class="popular-book-price popular-book-discounted-price">
                                                ${{ number_format($discountedPrice, 2) }}
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
                                <i class="bi bi-heart"></i>
                            </div>

                            <h3>
                                No Loved Books Available
                            </h3>

                            <p>
                                Loved books will appear here once readers start discovering them.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                BUDGET DEALS
            ================================================== --}}

            <div
                id="fictional"
                data-tab-content
            >

                <div class="popular-books-grid">

                    @forelse($budgetDeals as $book)

                        @php
                            $bookImage = null;

                            if (!empty($book->cover)) {
                                $bookImage = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);
                            }

                            $originalPrice = (float) $book->price;
                            $discountedPrice = (float) $book->discounted_price;
                        @endphp

                        <article class="popular-book-card">

                            <div class="popular-book-image-wrap">

                                @if($bookImage)

                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >

                                @endif

                                <span class="popular-book-badge">
                                    Budget Deal
                                </span>

                                @if($book->is_discount_active)

                                    <span class="popular-book-discount">
                                        {{ $book->discount_label }}
                                    </span>

                                @endif

                                <form
                                    action="{{ route('frontend.cart.add', $book) }}"
                                    method="POST"
                                    class="add-to-cart-form"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="add-to-cart popular-book-cart-btn"
                                    >
                                        <i class="bi bi-cart3"></i>
                                        <span>Add to Cart</span>
                                    </button>

                                </form>

                            </div>

                            <div class="popular-book-info">

                                <h3>
                                    {{ $book->title }}
                                </h3>

                                <p class="popular-book-author">
                                    {{ $book->author->name ?? 'Unknown Author' }}
                                </p>

                                <div class="popular-book-bottom">

                                    <div class="popular-book-price-wrap">

                                        @if($book->is_discount_active)

                                            <span class="popular-book-old-price">
                                                ${{ number_format($originalPrice, 2) }}
                                            </span>

                                            <span class="popular-book-price popular-book-discounted-price">
                                                ${{ number_format($discountedPrice, 2) }}
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
                                <i class="bi bi-tags"></i>
                            </div>

                            <h3>
                                No Budget Deals Available
                            </h3>

                            <p>
                                Budget deals will appear here once they are added.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>
</section>

