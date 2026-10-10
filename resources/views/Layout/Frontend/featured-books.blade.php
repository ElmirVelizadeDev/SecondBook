<section id="featured-books" class="featured-books-section">
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <header class="featured-books-header">

            <div class="featured-books-heading">
                <span class="featured-books-eyebrow">Some quality items</span>
                <h2 class="featured-books-title">Featured Books</h2>
            </div>

            <div class="featured-books-aside">
                <p class="featured-books-description">
                    Handpicked books selected from the SecondBook marketplace
                    for readers looking for something worth discovering.
                </p>

                <a href="{{ route('frontend.books') }}" class="featured-books-view-all">
                    <span>View All Books</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </header>

        {{-- ================= PRODUCT LIST ================= --}}
        <div class="featured-books-list">

            <div class="featured-books-grid">

                @forelse($featuredBooks as $book)

                    @php
                        $bookImage = null;

                        if (!empty($book->cover)) {
                            $bookImage = filter_var($book->cover, FILTER_VALIDATE_URL)
                                ? $book->cover
                                : asset('storage/' . $book->cover);
                        }

                        $originalPrice = (float) $book->price;
                        $discountedPrice = (float) $book->discounted_price;

                        $bookDetailsUrl = route('frontend.books.show', $book);
                    @endphp

                    <article
                        class="featured-book-card"
                        onclick="if (!event.target.closest('a, button, form')) window.location.href = '{{ $bookDetailsUrl }}';"
                        style="cursor: pointer;"
                    >

                        {{-- Cover --}}
                        <div class="featured-book-image-wrap">

                            @if($bookImage)

                                <a href="{{ $bookDetailsUrl }}" class="featured-book-image">
                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >
                                </a>

                            @else

                                <a
                                    href="{{ $bookDetailsUrl }}"
                                    class="featured-book-image featured-book-placeholder"
                                    aria-label="View {{ $book->title }} details"
                                >
                                    <i class="bi bi-book"></i>
                                </a>

                            @endif

                            @if($book->is_discount_active)
                                <span class="featured-book-discount">
                                    {{ $book->discount_label }}
                                </span>
                            @endif

                            {{-- Add to Cart — original functionality preserved --}}
                            <form
                                action="{{ route('frontend.cart.add', $book) }}"
                                method="POST"
                                class="add-to-cart-form"
                            >
                                @csrf

                                <input type="hidden" name="quantity" value="1">

                                <button type="submit" class="add-to-cart featured-book-cart-btn">
                                    <i class="bi bi-cart3"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </form>

                        </div>

                        {{-- Info --}}
                        <div class="featured-book-info">

                            <span class="featured-book-label">Featured</span>

                            <h3 class="featured-book-title" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>

                            <p class="featured-book-author">
                                {{ $book->author->name ?? 'Unknown Author' }}
                            </p>

                            <div class="featured-book-bottom">

                                <div class="featured-book-price-wrap">

                                    @if($book->is_discount_active)

                                        <span class="featured-book-price featured-book-discounted-price">
                                            ${{ number_format($discountedPrice, 2) }}
                                        </span>

                                        <span class="featured-book-old-price">
                                            ${{ number_format($originalPrice, 2) }}
                                        </span>

                                    @else

                                        <span class="featured-book-price">
                                            ${{ number_format($originalPrice, 2) }}
                                        </span>

                                    @endif

                                </div>

                                <a
                                    href="{{ $bookDetailsUrl }}"
                                    class="featured-book-arrow"
                                    aria-label="View {{ $book->title }} details"
                                >
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="featured-books-empty">
                        <div class="featured-books-empty-icon">
                            <i class="bi bi-book"></i>
                        </div>

                        <h3>No Featured Books Available</h3>

                        <p>
                            Featured books will appear here once they are added.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>
</section>
