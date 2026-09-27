<section id="categories" class="categories-section">
    <div class="container">

        <div class="categories-header">

            <div class="categories-heading">
                <span class="categories-eyebrow">
                    Find your next read faster
                </span>

                <h2 class="categories-title">
                    Popular Categories
                </h2>

                <div class="categories-divider"></div>

                <p class="categories-description">
                    Browse popular genres and discover books that match
                    your interests, mood, and reading style.
                </p>
            </div>

            <a
                href="{{ route('frontend.books') }}"
                class="categories-view-all"
            >
                <span>Explore All Books</span>
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        <div class="categories-grid">

            @foreach($categories->take(4) as $category)

                @php
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

                    <div class="category-image-wrap">

                        @if($categoryImage)

                            <img
                                src="{{ $categoryImage }}"
                                alt="{{ $category->name }} books"
                                loading="lazy"
                            >

                        @else

                            <img
                                src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=900&q=90"
                                alt="{{ $category->name }} books"
                                loading="lazy"
                            >

                        @endif

                        <div class="category-image-overlay"></div>

                        <div class="category-card-content">

                            <span class="category-card-icon">
                                <i class="bi bi-book"></i>
                            </span>

                            <div class="category-card-text">
                                <span class="category-card-label">
                                    Explore
                                </span>

                                <h3>
                                    {{ $category->name }}
                                </h3>

                                <p>
                                    Explore {{ $category->name }} books
                                </p>
                            </div>

                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-up-right"></i>
                            </span>

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    </div>
</section>

