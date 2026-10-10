@php
    $whyFeatures = [
        [
            'icon'  => 'bi-collection',
            'title' => 'Thousands of Books',
            'text'  => 'Explore a constantly growing catalog across different genres and interests.',
        ],
        [
            'icon'  => 'bi-tags',
            'title' => 'Affordable Prices',
            'text'  => 'Find quality second-hand books at prices that make reading easier on your budget.',
        ],
        [
            'icon'  => 'bi-patch-check',
            'title' => 'Trusted Sellers',
            'text'  => 'Shop with confidence through verified sellers and reliable marketplace listings.',
        ],
        [
            'icon'  => 'bi-shield-lock',
            'title' => 'Secure Shopping',
            'text'  => 'Enjoy a protected shopping experience from browsing to checkout.',
        ],
    ];
@endphp

<section id="why-choose" class="why-choose-section">
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <header class="why-choose-header">

            <span class="why-choose-eyebrow">Built for readers and resellers</span>

            <h2 class="why-choose-title">Why Choose SecondBook</h2>

            <p class="why-choose-description">
                A marketplace designed to make discovering, buying, and
                selling books simple, reliable, and rewarding.
            </p>

        </header>


        {{-- ================= FEATURES ================= --}}
        <div class="why-choose-grid">

            @foreach($whyFeatures as $feature)

                <article class="why-card">

                    <div class="why-card-top">
                        <div class="why-card-icon">
                            <i class="bi {{ $feature['icon'] }}"></i>
                        </div>

                    </div>

                    <div class="why-card-content">
                        <h4>{{ $feature['title'] }}</h4>
                        <p>{{ $feature['text'] }}</p>
                    </div>

                </article>

            @endforeach

        </div>

    </div>
</section>