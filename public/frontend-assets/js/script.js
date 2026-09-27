(function ($) {

    "use strict";

    $(document).ready(function () {

        /* =========================================================
           TABS
        ========================================================= */

        const popularBooksSection =
            document.getElementById('popular-books');

        if (popularBooksSection) {

            const tabs =
                popularBooksSection.querySelectorAll(
                    '[data-tab-target]'
                );

            const tabContents =
                popularBooksSection.querySelectorAll(
                    '[data-tab-content]'
                );

            tabs.forEach(function (tab) {

                tab.addEventListener('click', function () {

                    const targetSelector =
                        this.dataset.tabTarget;

                    const target =
                        popularBooksSection.querySelector(
                            targetSelector
                        );

                    if (!target) {
                        return;
                    }

                    /* Remove active state */

                    tabs.forEach(function (item) {

                        item.classList.remove('active');

                    });

                    tabContents.forEach(function (content) {

                        content.classList.remove('active');
                        content.classList.remove('tab-animate');

                    });

                    /* Activate selected tab */

                    this.classList.add('active');

                    target.classList.add('active');

                    /* Restart animation */

                    void target.offsetWidth;

                    target.classList.add('tab-animate');

                });

            });

            /* Initial animation */

            const initialContent =
                popularBooksSection.querySelector(
                    '[data-tab-content].active'
                );

            if (initialContent) {

                initialContent.classList.add(
                    'tab-animate'
                );

            }

        }


        /* =========================================================
           RESPONSIVE NAVIGATION
        ========================================================= */

        const hamburger =
            document.querySelector('.hamburger');

        const navMenu =
            document.querySelector('.menu-list');


        function closeMenu() {

            if (!hamburger || !navMenu) {
                return;
            }

            hamburger.classList.remove('active');

            navMenu.classList.remove('responsive');

        }


        function mobileMenu() {

            if (!hamburger || !navMenu) {
                return;
            }

            hamburger.classList.toggle('active');

            navMenu.classList.toggle('responsive');

        }


        if (hamburger && navMenu) {

            hamburger.addEventListener(
                'click',
                mobileMenu
            );

        }


        const navLinks =
            document.querySelectorAll('.nav-link');


        navLinks.forEach(function (link) {

            link.addEventListener(
                'click',
                closeMenu
            );

        });


        /* =========================================================
           SCROLL NAVIGATION
        ========================================================= */

        function initScrollNav() {

            const scroll =
                $(window).scrollTop();

            if (scroll >= 200) {

                $('#header').addClass('fixed-top');

            } else {

                $('#header').removeClass('fixed-top');

            }

        }


        initScrollNav();


        $(window).on('scroll', function () {

            initScrollNav();

        });


        /* =========================================================
           CHOCOLAT
        ========================================================= */

        if (
            typeof Chocolat !== 'undefined' &&
            document.querySelectorAll('.image-link').length
        ) {

            Chocolat(
                document.querySelectorAll('.image-link'),
                {
                    imageSize: 'contain',
                    loop: true
                }
            );

        }


        /* =========================================================
           SEARCH
        ========================================================= */

        $('#header-wrap').on(
            'click',
            '.search-toggle',
            function (e) {

                e.preventDefault();

                e.stopPropagation();

                const $wrap =
                    $('#header-wrap');

                const $toggle =
                    $(this);

                const isOpen =
                    $wrap.hasClass('show');


                if (isOpen) {

                    $wrap.removeClass('show');

                    $toggle.removeClass('active');

                    return;

                }


                $wrap.addClass('show');

                $toggle.addClass('active');

                $wrap
                    .find('.search-input')
                    .trigger('focus');

            }
        );


        $(document).on(
            'click touchstart',
            function (e) {

                const $target =
                    $(e.target);


                if (
                    $target.closest('.search-bar').length ||
                    $target.closest('.search-toggle').length ||
                    $target.closest('.search-box').length
                ) {

                    return;

                }


                $('.search-toggle')
                    .removeClass('active');

                $('#header-wrap')
                    .removeClass('show');

            }
        );


        $(document).on(
            'keydown',
            function (e) {

                if (e.key === 'Escape') {

                    $('.search-toggle')
                        .removeClass('active');

                    $('#header-wrap')
                        .removeClass('show');

                }

            }
        );


        /* =========================================================
           MAIN SLIDER
        ========================================================= */

        if (
            $.fn.slick &&
            $('.main-slider').length
        ) {

            $('.main-slider').slick({

                autoplay: false,

                autoplaySpeed: 4000,

                fade: true,

                dots: true,

                prevArrow: $('.prev'),

                nextArrow: $('.next')

            });

        }


        /* =========================================================
           PRODUCT GRID
        ========================================================= */

        if (
            $.fn.slick &&
            $('.product-grid').length
        ) {

            $('.product-grid').slick({

                slidesToShow: 4,

                slidesToScroll: 1,

                autoplay: false,

                autoplaySpeed: 2000,

                dots: true,

                arrows: false,

                responsive: [

                    {
                        breakpoint: 1400,

                        settings: {

                            slidesToShow: 3,

                            slidesToScroll: 1

                        }
                    },

                    {
                        breakpoint: 999,

                        settings: {

                            slidesToShow: 2,

                            slidesToScroll: 1

                        }
                    },

                    {
                        breakpoint: 660,

                        settings: {

                            slidesToShow: 1,

                            slidesToScroll: 1

                        }
                    }

                ]

            });

        }


        /* =========================================================
           CATEGORIES ANIMATION
        ========================================================= */

        const categoriesSection =
            document.getElementById('categories');


        if (categoriesSection) {

            const observer =
                new IntersectionObserver(
                    function (entries) {

                        if (!entries[0].isIntersecting) {
                            return;
                        }


                        const header =
                            categoriesSection.querySelector(
                                '.categories-header'
                            );


                        const cards =
                            categoriesSection.querySelectorAll(
                                '.category-card'
                            );


                        if (header) {

                            header.classList.add(
                                'is-visible'
                            );

                        }


                        cards.forEach(function (card) {

                            card.classList.add(
                                'is-visible'
                            );

                        });


                        observer.disconnect();

                    },
                    {
                        threshold: 0.15
                    }
                );


            observer.observe(categoriesSection);

        }


        /* =========================================================
           FEATURED BOOKS ANIMATION
        ========================================================= */

        const featuredBooksSection =
            document.getElementById('featured-books');


        if (featuredBooksSection) {

            const featuredBooksList =
                featuredBooksSection.querySelector(
                    '.featured-books-list'
                );


            if (featuredBooksList) {

                /*
                 * Start visible immediately if the section
                 * is already inside the viewport.
                 */

                const featuredRect =
                    featuredBooksSection.getBoundingClientRect();


                const viewportHeight =
                    window.innerHeight ||
                    document.documentElement.clientHeight;


                const alreadyVisible =
                    featuredRect.top <
                        viewportHeight * 0.85 &&
                    featuredRect.bottom > 0;


                if (alreadyVisible) {

                    featuredBooksList.classList.add(
                        'is-visible'
                    );

                } else {

                    const featuredObserver =
                        new IntersectionObserver(
                            function (entries) {

                                const entry =
                                    entries[0];


                                if (
                                    !entry.isIntersecting
                                ) {

                                    return;

                                }


                                featuredBooksList.classList.add(
                                    'is-visible'
                                );


                                featuredObserver.disconnect();

                            },
                            {
                                threshold: 0.15,

                                rootMargin:
                                    '0px 0px -5% 0px'
                            }
                        );


                    featuredObserver.observe(
                        featuredBooksSection
                    );

                }

            }

        }

        /* =========================================================
        WHY CHOOSE ANIMATION
        ========================================================= */

        const whyChooseSection =
            document.getElementById('why-choose');

        if (whyChooseSection) {

            const whyChooseRect =
                whyChooseSection.getBoundingClientRect();

            const viewportHeight =
                window.innerHeight ||
                document.documentElement.clientHeight;

            const alreadyVisible =
                whyChooseRect.top <
                    viewportHeight * 0.85 &&
                whyChooseRect.bottom > 0;


            if (alreadyVisible) {

                whyChooseSection.classList.add(
                    'is-visible'
                );

            } else {

                const whyChooseObserver =
                    new IntersectionObserver(
                        function (entries) {

                            const entry =
                                entries[0];

                            if (!entry.isIntersecting) {
                                return;
                            }

                            whyChooseSection.classList.add(
                                'is-visible'
                            );

                            whyChooseObserver.disconnect();

                        },
                        {
                            threshold: 0.15,
                            rootMargin: '0px 0px -5% 0px'
                        }
                    );


                whyChooseObserver.observe(
                    whyChooseSection
                );

            }

        }

        /* =========================================================
        SPECIAL OFFER ANIMATION
        ========================================================= */

        const specialOfferSection =
            document.getElementById('special-offer');

        if (specialOfferSection) {

            const specialOfferRect =
                specialOfferSection.getBoundingClientRect();

            const viewportHeight =
                window.innerHeight ||
                document.documentElement.clientHeight;

            const alreadyVisible =
                specialOfferRect.top <
                    viewportHeight * 0.85 &&
                specialOfferRect.bottom > 0;


            if (alreadyVisible) {

                specialOfferSection.classList.add(
                    'is-visible'
                );

            } else {

                const specialOfferObserver =
                    new IntersectionObserver(
                        function (entries) {

                            const entry =
                                entries[0];

                            if (!entry.isIntersecting) {
                                return;
                            }

                            specialOfferSection.classList.add(
                                'is-visible'
                            );

                            specialOfferObserver.disconnect();

                        },
                        {
                            threshold: 0.15,
                            rootMargin: '0px 0px -5% 0px'
                        }
                    );


                specialOfferObserver.observe(
                    specialOfferSection
                );

            }

        }

        /* =========================================================
        QUOTATION ANIMATION
        ========================================================= */

        const quotationSection =
            document.getElementById('quotation');

        if (quotationSection) {

            const quotationRect =
                quotationSection.getBoundingClientRect();

            const viewportHeight =
                window.innerHeight ||
                document.documentElement.clientHeight;

            const alreadyVisible =
                quotationRect.top <
                    viewportHeight * 0.85 &&
                quotationRect.bottom > 0;


            if (alreadyVisible) {

                quotationSection.classList.add(
                    'is-visible'
                );

            } else {

                const quotationObserver =
                    new IntersectionObserver(
                        function (entries) {

                            const entry =
                                entries[0];

                            if (!entry.isIntersecting) {
                                return;
                            }

                            quotationSection.classList.add(
                                'is-visible'
                            );

                            quotationObserver.disconnect();

                        },
                        {
                            threshold: 0.15,
                            rootMargin: '0px 0px -5% 0px'
                        }
                    );


                quotationObserver.observe(
                    quotationSection
                );

            }

        }

        /* =========================================================
        LATEST ARTICLES ANIMATION
        ========================================================= */

        const latestBlogSection =
            document.getElementById('latest-blog');

        if (latestBlogSection) {

            const latestBlogObserver =
                new IntersectionObserver(
                    function (entries) {

                        if (!entries[0].isIntersecting) {
                            return;
                        }

                        latestBlogSection.classList.add('is-visible');

                        latestBlogObserver.disconnect();

                    },
                    {
                        threshold: 0.15
                    }
                );

            latestBlogObserver.observe(latestBlogSection);
        }

        /* =========================================================
        SUBSCRIBE ANIMATION
        ========================================================= */

        const subscribeSection =
            document.getElementById('subscribe');

        if (subscribeSection) {

            const subscribeObserver =
                new IntersectionObserver(
                    function (entries) {

                        if (!entries[0].isIntersecting) {
                            return;
                        }

                        subscribeSection.classList.add('is-visible');

                        subscribeObserver.disconnect();

                    },
                    {
                        threshold: 0.15
                    }
                );

            subscribeObserver.observe(subscribeSection);
        }

        /* =========================================================
           AOS
        ========================================================= */

        /*
         * AOS is intentionally initialized after the
         * custom Featured Books animation.
         *
         * Featured Books no longer depends on AOS.
         */

        if (typeof AOS !== 'undefined') {

            AOS.init({

                duration: 1200,

                once: true

            });

        }


        /* =========================================================
           ADD TO CART
        ========================================================= */

        $(document).on(
            'submit',
            '.add-to-cart-form',
            async function (e) {

                e.preventDefault();


                const form = this;


                const button =
                    form.querySelector(
                        '.add-to-cart'
                    );


                if (
                    !button ||
                    button.disabled
                ) {

                    return;

                }


                const originalHtml =
                    button.innerHTML;


                const csrfInput =
                    form.querySelector(
                        'input[name="_token"]'
                    );


                const csrfToken =
                    csrfInput
                        ? csrfInput.value
                        : document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            ?.getAttribute(
                                'content'
                            );


                if (!csrfToken) {

                    console.error(
                        'CSRF token not found.'
                    );

                    return;

                }


                try {

                    button.disabled = true;


                    button.innerHTML = `
                        <span class="cart-loading-spinner"></span>
                        <span>Adding...</span>
                    `;


                    const response =
                        await fetch(
                            form.action,
                            {
                                method: 'POST',

                                headers: {

                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'Accept':
                                        'application/json'

                                },

                                body:
                                    new FormData(form)

                            }
                        );


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        data.success !== true
                    ) {

                        throw new Error(
                            data.message ||
                            'Unable to add the book to cart.'
                        );

                    }


                    /* UPDATE HEADER CART COUNT */

                    const cartCount =
                        document.getElementById(
                            'header-cart-count'
                        );


                    if (
                        cartCount &&
                        data.cart_count !== undefined
                    ) {

                        const count =
                            Number(
                                data.cart_count
                            );


                        cartCount.textContent =
                            count > 99
                                ? '99+'
                                : count;


                        cartCount.style.display =
                            count > 0
                                ? 'inline-flex'
                                : 'none';


                        cartCount.classList.remove(
                            'cart-count-bump'
                        );


                        void cartCount.offsetWidth;


                        cartCount.classList.add(
                            'cart-count-bump'
                        );

                    }


                    /* SUCCESS */

                    button.innerHTML = `
                        <i class="bi bi-check-lg"></i>
                        <span>Added</span>
                    `;


                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: 'success',

                            title: 'Added to Cart',

                            text:
                                data.message ||
                                'Book added to cart successfully!',

                            timer: 1600,

                            showConfirmButton: false

                        });

                    }


                    setTimeout(
                        function () {

                            button.innerHTML =
                                originalHtml;

                            button.disabled =
                                false;

                        },
                        1200
                    );


                } catch (error) {

                    console.error(
                        'Add to cart error:',
                        error
                    );


                    button.innerHTML =
                        originalHtml;


                    button.disabled =
                        false;


                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: 'error',

                            title:
                                'Something went wrong',

                            text:
                                error.message ||
                                'Unable to add the book to cart.',

                            confirmButtonText:
                                'OK'

                        });

                    } else {

                        alert(
                            error.message ||
                            'Unable to add the book to cart.'
                        );

                    }

                }

            }
        );


        /* =========================================================
           STELLARNAV
        ========================================================= */

        if (
            $.fn.stellarNav &&
            $('.stellarnav').length
        ) {

            $('.stellarnav').stellarNav({

                theme: 'plain',

                closingDelay: 250

            });

        }

    });

})(jQuery);