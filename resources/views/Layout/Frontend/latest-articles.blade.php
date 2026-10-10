{{-- =========================================================
    LATEST ARTICLES — SecondBook
========================================================== --}}

@php
    $latestArticles = [
        [
            'image'    => 'frontend-assets/images/post-img1.jpg',
            'alt'      => 'Reading books',
            'category' => 'Inspiration',
            'date'     => 'Mar 30, 2026',
            'read'     => '4 min read',
            'title'    => 'Why Reading Still Makes Everyday Moments Better',
            'excerpt'  => 'Explore how books can turn ordinary moments into meaningful experiences and lasting memories.',
            'url'      => '#',
        ],
        [
            'image'    => 'frontend-assets/images/post-img2.jpg',
            'alt'      => 'Books and reading',
            'category' => 'Book Guide',
            'date'     => 'Mar 24, 2026',
            'read'     => '5 min read',
            'title'    => 'How to Choose Your Next Book Without Overthinking',
            'excerpt'  => 'A simple approach to finding your next great read based on your interests, mood, and reading habits.',
            'url'      => '#',
        ],
        [
            'image'    => 'frontend-assets/images/post-img3.jpg',
            'alt'      => 'Second-hand books',
            'category' => 'Marketplace',
            'date'     => 'Mar 18, 2026',
            'read'     => '6 min read',
            'title'    => "The Smart Reader's Guide to Buying Pre-Owned Books",
            'excerpt'  => 'Learn what to look for when buying second-hand books and how to find quality editions at better prices.',
            'url'      => '#',
        ],
    ];
@endphp

<section id="latest-blog" class="latest-blog-section">
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <header class="latest-blog-header">

            <div class="latest-blog-eyebrow">From the Journal</div>

            <div class="latest-blog-heading-row">

                <h2 class="latest-blog-title">Latest Articles</h2>

                <a href="#" class="latest-blog-view-all">
                    <span>View All Articles</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

            <p class="latest-blog-intro">
                Discover thoughtful stories, reading inspiration, book guides,
                and useful insights from the SecondBook community.
            </p>

        </header>


        {{-- ================= ARTICLES ================= --}}
        <div class="latest-blog-grid">

            @foreach($latestArticles as $article)

                <article class="latest-blog-card">

                    <a href="{{ $article['url'] }}" class="latest-blog-image image-hvr-effect">

                        <img
                            src="{{ asset($article['image']) }}"
                            alt="{{ $article['alt'] }}"
                            class="post-image"
                            loading="lazy"
                        >

                        <span class="latest-blog-category">{{ $article['category'] }}</span>

                    </a>

                    <div class="latest-blog-content">

                        <div class="latest-blog-meta">
                            <span>{{ $article['date'] }}</span>
                            <span class="latest-blog-meta-dot"></span>
                            <span>{{ $article['read'] }}</span>
                        </div>

                        <h3>
                            <a href="{{ $article['url'] }}">{{ $article['title'] }}</a>
                        </h3>

                        <p>{{ $article['excerpt'] }}</p>

                        <a href="{{ $article['url'] }}" class="latest-blog-read-more">
                            <span>Read Article</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    </div>
</section>