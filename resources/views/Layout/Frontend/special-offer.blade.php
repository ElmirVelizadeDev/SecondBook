<section id="special-offer" class="special-offer-section">

    <div class="container">

        {{-- Section Header --}}
        <div class="special-offer-header">

            <div class="special-offer-heading">

                <span class="special-offer-eyebrow">
                    Limited-time marketplace deals
                </span>

                <h2 class="special-offer-title">
                    Special Offers
                </h2>

                <div class="special-offer-divider"></div>

                <p class="special-offer-description">
                    Discover selected books at exceptional prices.
                    Limited quantities and special marketplace deals await.
                </p>

            </div>

            <div class="special-offer-badge">
                <i class="bi bi-tag"></i>
                <span>Limited Offers</span>
            </div>

        </div>


        {{-- Products --}}
        <div class="special-offer-products">

            <div class="special-offer-grid">

                @forelse($specialOffers as $book)

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


                    <article class="special-offer-card">

                        {{-- Product Image --}}
                        <div class="special-offer-image-wrap">

                            @if($bookImage)

                                <a
                                    href="#"
                                    class="special-offer-image"
                                >
                                    <img
                                        src="{{ $bookImage }}"
                                        alt="{{ $book->title }}"
                                        loading="lazy"
                                    >
                                </a>

                            @else

                                <div class="special-offer-image special-offer-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>

                            @endif


                            {{-- Discount Badge --}}
                            <span class="special-offer-discount">
                                {{ $book->discount_label }}
                            </span>


                            {{-- Cart Button --}}
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
                                    class="add-to-cart special-offer-cart-btn"
                                >
                                    <i class="bi bi-cart3"></i>
                                    <span>Add to Cart</span>
                                </button>

                            </form>

                        </div>


                        {{-- Product Info --}}
                        <div class="special-offer-info">

                            <div class="special-offer-condition">
                                Special Deal
                            </div>

                            <h3 class="special-offer-book-title">
                                {{ $book->title }}
                            </h3>

                            <p class="special-offer-author">
                                {{ $book->author->name ?? 'Unknown Author' }}
                            </p>


                            <div class="special-offer-price">

                                {{-- Original Price --}}
                                <span class="special-offer-old-price">
                                    ${{ number_format($originalPrice, 2) }}
                                </span>

                                {{-- Discounted Price --}}
                                <span class="special-offer-current-price">
                                    ${{ number_format($discountedPrice, 2) }}
                                </span>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="special-offer-empty">

                        <div class="special-offer-empty-icon">
                            <i class="bi bi-tags"></i>
                        </div>

                        <h3>
                            No Special Offers Available
                        </h3>

                        <p>
                            Special offers will appear here once they are added.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>

