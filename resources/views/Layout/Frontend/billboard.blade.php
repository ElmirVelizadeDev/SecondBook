<section id="billboard" class="billboard-section">

    <div class="container">

        <div class="billboard-slider">

            {{-- =====================================================
                 SLIDE 01
                 ===================================================== --}}

            <div class="billboard-slide">

                <div class="billboard-hero">

                    {{-- IMAGE --}}
                    <div class="billboard-visual">

                        <img
                            src="{{ asset('frontend-assets/images/billboard-book-01.jpg') }}"
                            alt="SecondBook books"
                            class="billboard-image"
                        >

                        <div class="billboard-image-overlay"></div>

                        <div class="billboard-image-word">
                            BOOKS
                        </div>

                        <div class="billboard-image-label">
                            <span class="billboard-image-label-line"></span>
                            <span>READ SOMETHING NEW</span>
                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="billboard-content">

                        <div class="billboard-topline">
                            <span class="billboard-line"></span>
                            <span>SECOND BOOK</span>
                            <span class="billboard-topline-dot"></span>
                            <span>BOOK MARKETPLACE</span>
                        </div>

                        <h1 class="billboard-title">
                            Every book
                            <span>deserves another</span>
                            reader.
                        </h1>

                        <p class="billboard-description">
                            Discover remarkable second-hand books, give your
                            bookshelf a new story, and find your next favorite
                            read at a price worth loving.
                        </p>

                        <div class="billboard-actions">

                            <a
                                href="#popular-books"
                                class="billboard-btn billboard-btn-primary"
                            >
                                <span>Explore Books</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                            @if(auth()->check() && auth()->user()->role === 'seller')

                                <a
                                    href="{{ route('seller.dashboard') }}"
                                    class="billboard-btn billboard-btn-secondary"
                                >
                                    <span>Seller Panel</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            @else

                                <a
                                    href="{{ route('frontend.seller-application') }}"
                                    class="billboard-btn billboard-btn-secondary"
                                >
                                    <span>Become a Seller</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            @endif

                        </div>

                        <div class="billboard-bottom-info">

                            <div class="billboard-info-item">
                                <span class="billboard-info-number">01</span>

                                <div>
                                    <strong>Discover</strong>
                                    <small>Stories worth finding</small>
                                </div>
                            </div>

                            <span class="billboard-info-divider"></span>

                            <div class="billboard-info-item">
                                <span class="billboard-info-number">02</span>

                                <div>
                                    <strong>Read</strong>
                                    <small>Books worth keeping</small>
                                </div>
                            </div>

                            <span class="billboard-info-divider"></span>

                            <div class="billboard-info-item">
                                <span class="billboard-info-number">03</span>

                                <div>
                                    <strong>Share</strong>
                                    <small>Stories worth passing on</small>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 SLIDE 02
                 ===================================================== --}}

            <div class="billboard-slide">

                <div class="billboard-hero">

                    {{-- IMAGE --}}
                    <div class="billboard-visual">

                        <img
                            src="{{ asset('frontend-assets/images/billboard-book-02.jpg') }}"
                            alt="SecondBook reading collection"
                            class="billboard-image"
                        >

                        <div class="billboard-image-overlay"></div>

                        <div class="billboard-image-word">
                            READ
                        </div>

                        <div class="billboard-image-label">
                            <span class="billboard-image-label-line"></span>
                            <span>FIND YOUR NEXT STORY</span>
                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="billboard-content">

                        <div class="billboard-topline">
                            <span class="billboard-line"></span>
                            <span>SECOND BOOK</span>
                            <span class="billboard-topline-dot"></span>
                            <span>YOUR NEXT STORY</span>
                        </div>

                        <h1 class="billboard-title">
                            Find a story
                            <span>that stays</span>
                            with you.
                        </h1>

                        <p class="billboard-description">
                            Explore carefully selected books from different
                            categories and discover stories waiting for their
                            next reader.
                        </p>

                        <div class="billboard-actions">

                            <a
                                href="{{ route('frontend.books') }}"
                                class="billboard-btn billboard-btn-primary"
                            >
                                <span>Browse Collection</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                            <a
                                href="{{ route('frontend.categories') }}"
                                class="billboard-btn billboard-btn-secondary"
                            >
                                <span>View Categories</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                        <div class="billboard-bottom-info">

                            <div class="billboard-info-item">
                                <span class="billboard-info-number">01</span>

                                <div>
                                    <strong>Explore</strong>
                                    <small>Different worlds</small>
                                </div>
                            </div>

                            <span class="billboard-info-divider"></span>

                            <div class="billboard-info-item">
                                <span class="billboard-info-number">02</span>

                                <div>
                                    <strong>Choose</strong>
                                    <small>Books you love</small>
                                </div>
                            </div>

                            <span class="billboard-info-divider"></span>

                            <div class="billboard-info-item">
                                <span class="billboard-info-number">03</span>

                                <div>
                                    <strong>Enjoy</strong>
                                    <small>Every new chapter</small>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SLIDER CONTROLS
         ===================================================== --}}

    <button
        type="button"
        class="billboard-arrow billboard-arrow-prev"
        aria-label="Previous slide"
    >
        <i class="bi bi-arrow-left"></i>
    </button>

    <button
        type="button"
        class="billboard-arrow billboard-arrow-next"
        aria-label="Next slide"
    >
        <i class="bi bi-arrow-right"></i>
    </button>

</section>


{{-- =========================================================
     BILLBOARD SLIDER
     ========================================================= --}}

@push('js')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const billboardSlider = $('.billboard-slider');

        if (!billboardSlider.length) {
            return;
        }

        if (billboardSlider.hasClass('slick-initialized')) {
            return;
        }

        billboardSlider.slick({

            slidesToShow: 1,

            slidesToScroll: 1,

            infinite: true,

            arrows: true,

            prevArrow: $('.billboard-arrow-prev'),

            nextArrow: $('.billboard-arrow-next'),

            dots: false,

            autoplay: true,

            autoplaySpeed: 6000,

            speed: 700,

            fade: true,

            cssEase: 'ease-in-out',

            adaptiveHeight: false,

            pauseOnHover: true,

            pauseOnFocus: true,

            swipe: true,

            touchMove: true

        });

    });
</script>

@endpush

