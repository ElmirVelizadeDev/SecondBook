(function ($) {

    "use strict";

    $(document).ready(function () {

        /* =========================================================
           TABS
        ========================================================= */

        const tabs = document.querySelectorAll('[data-tab-target]');
        const tabContents = document.querySelectorAll('[data-tab-content]');

        tabs.forEach(tab => {

            tab.addEventListener('click', function () {

                const targetSelector = tab.dataset.tabTarget;
                const target = document.querySelector(targetSelector);

                if (!target) {
                    return;
                }

                tabContents.forEach(tabContent => {
                    tabContent.classList.remove('active');
                });

                tabs.forEach(item => {
                    item.classList.remove('active');
                });

                tab.classList.add('active');
                target.classList.add('active');
            });

        });


        /* =========================================================
           RESPONSIVE NAVIGATION
        ========================================================= */

        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.menu-list');

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
            hamburger.addEventListener('click', mobileMenu);
        }

        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {

            link.addEventListener('click', closeMenu);

        });


        /* =========================================================
           SCROLL NAVIGATION
        ========================================================= */

        function initScrollNav() {

            const scroll = $(window).scrollTop();

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

                const $wrap = $('#header-wrap');
                const $toggle = $(this);
                const isOpen = $wrap.hasClass('show');

                if (isOpen) {

                    $wrap.removeClass('show');
                    $toggle.removeClass('active');

                    return;
                }

                $wrap.addClass('show');
                $toggle.addClass('active');

                $wrap.find('.search-input').trigger('focus');

            }
        );


        $(document).on('click touchstart', function (e) {

            const $target = $(e.target);

            if (
                $target.closest('.search-bar').length ||
                $target.closest('.search-toggle').length ||
                $target.closest('.search-box').length
            ) {
                return;
            }

            $('.search-toggle').removeClass('active');
            $('#header-wrap').removeClass('show');

        });


        $(document).on('keydown', function (e) {

            if (e.key === 'Escape') {

                $('.search-toggle').removeClass('active');
                $('#header-wrap').removeClass('show');

            }

        });


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
           AOS
        ========================================================= */

        if (typeof AOS !== 'undefined') {

            AOS.init({
                duration: 1200,
                once: true
            });

        }

        /* =========================================================
        ADD TO CART
        ========================================================= */

        $(document).on('submit', '.add-to-cart-form', async function (e) {
            e.preventDefault();

            const form = this;
            const button = form.querySelector('.add-to-cart');

            if (!button || button.disabled) {
                return;
            }

            const originalHtml = button.innerHTML;

            const csrfInput = form.querySelector('input[name="_token"]');
            const csrfToken = csrfInput
                ? csrfInput.value
                : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                console.error('CSRF token not found.');
                return;
            }

            try {
                button.disabled = true;

                button.innerHTML = `
                    <span class="cart-loading-spinner"></span>
                    <span>Adding...</span>
                `;

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (!response.ok || data.success !== true) {
                    throw new Error(
                        data.message || 'Unable to add the book to cart.'
                    );
                }

                /* UPDATE HEADER CART COUNT */

                const cartCount = document.getElementById('header-cart-count');

                if (cartCount && data.cart_count !== undefined) {
                    const count = Number(data.cart_count);

                    cartCount.textContent = count > 99 ? '99+' : count;

                    cartCount.style.display =
                        count > 0 ? 'inline-flex' : 'none';

                    cartCount.classList.remove('cart-count-bump');

                    void cartCount.offsetWidth;

                    cartCount.classList.add('cart-count-bump');
                }

                /* SUCCESS */

                button.innerHTML = `
                    <i class="bi bi-check-lg"></i>
                    <span>Added</span>
                `;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: data.message || 'Book added to cart successfully!',
                        timer: 1600,
                        showConfirmButton: false
                    });
                }

                setTimeout(function () {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }, 1200);

            } catch (error) {
                console.error('Add to cart error:', error);

                button.innerHTML = originalHtml;
                button.disabled = false;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: error.message || 'Unable to add the book to cart.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert(error.message || 'Unable to add the book to cart.');
                }
            }
        });


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