<section id="special-offer" class="special-offer-section">
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <header class="special-offer-header">

            <div class="special-offer-heading">
                <span class="special-offer-eyebrow">Limited-time marketplace deals</span>
                <h2 class="special-offer-title">Special Offers</h2>
                <p class="special-offer-description">
                    Discover selected books at exceptional prices.
                    Limited quantities and special marketplace deals await.
                </p>
            </div>

            <div class="special-offer-badge">
                <i class="bi bi-tag"></i>
                <span>Limited Offers</span>
            </div>

        </header>

        {{-- ================= PRODUCTS ================= --}}
        <div class="special-offer-products">

            <div class="special-offer-grid">

                @forelse($specialOffers as $book)

                    @php
                        $bookImage = null;

                        if (!empty($book->cover)) {
                            $bookImage = filter_var($book->cover, FILTER_VALIDATE_URL)
                                ? $book->cover
                                : asset('storage/' . $book->cover);
                        }

                        $originalPrice = (float) $book->price;
                        $discountedPrice = (float) $book->discounted_price;
                        $savedAmount = max(0, $originalPrice - $discountedPrice);

                        $bookDetailsUrl = route('frontend.books.show', $book);
                    @endphp

                    <article
                        class="special-offer-card"
                        onclick="if (!event.target.closest('a, button, form')) window.location.href = '{{ $bookDetailsUrl }}';"
                        style="cursor: pointer;"
                    >

                        {{-- Image --}}
                        <div class="special-offer-image-wrap">

                            @if($bookImage)

                                <a href="{{ $bookDetailsUrl }}" class="special-offer-image">
                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >
                                </a>

                            @else

                                <a
                                    href="{{ $bookDetailsUrl }}"
                                    class="special-offer-image special-offer-placeholder"
                                    aria-label="View {{ $book->title }} details"
                                >
                                    <i class="bi bi-book"></i>
                                </a>

                            @endif

                            <span class="special-offer-discount">
                                {{ $book->discount_label }}
                            </span>

                            {{-- Add to Cart — original functionality preserved --}}
                            <form
                                action="{{ route('frontend.cart.add', $book) }}"
                                method="POST"
                                class="add-to-cart-form"
                            >
                                @csrf

                                <input type="hidden" name="quantity" value="1">

                                <button type="submit" class="add-to-cart special-offer-cart-btn">
                                    <i class="bi bi-cart3"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </form>

                        </div>

                        {{-- Info --}}
                        <div class="special-offer-info">

                            <div class="special-offer-condition">Special Deal</div>

                            <h3 class="special-offer-book-title" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>

                            <p class="special-offer-author">
                                {{ $book->author->name ?? 'Unknown Author' }}
                            </p>

                            <div class="special-offer-price">

                                <div class="special-offer-price-main">
                                    <span class="special-offer-current-price">
                                        ${{ number_format($discountedPrice, 2) }}
                                    </span>

                                    <span class="special-offer-old-price">
                                        ${{ number_format($originalPrice, 2) }}
                                    </span>
                                </div>

                                @if($savedAmount > 0)
                                    <span class="special-offer-save">
                                        Save ${{ number_format($savedAmount, 2) }}
                                    </span>
                                @endif

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="special-offer-empty">
                        <div class="special-offer-empty-icon">
                            <i class="bi bi-tags"></i>
                        </div>

                        <h3>No Special Offers Available</h3>

                        <p>
                            Special offers will appear here once they are added.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>
</section>
