@include('Layout.Frontend.head')

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

    @hasSection('hideNavbar')
    @else
        @include('Layout.Frontend.header-wrap')
    @endif

    <main id="frontend-content">
        @yield('content')
    </main>

    @hasSection('hideFooter')
    @else
        @include('Layout.Frontend.footer')
        @include('Layout.Frontend.footer-bottom')
    @endif

    @hasSection('hideScripts')
    @else
        @include('Layout.Frontend.scripts')
    @endif

    {{-- =========================================================
        Logout Confirmation
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function confirmLogout(formId) {
                const form = document.getElementById(formId);

                if (typeof Swal === 'undefined') {
                    form?.submit();
                    return;
                }

                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to sign out of your account?',
                    icon: 'warning',
                    width: 420,
                    padding: '1.5rem',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'logout-confirm-btn',
                        cancelButton: 'logout-cancel-btn'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form?.submit();
                    }
                });
            }

            const profileLogoutBtn = document.getElementById('profileLogoutBtn');

            if (profileLogoutBtn) {
                profileLogoutBtn.addEventListener('click', function () {
                    confirmLogout('profileLogoutForm');
                });
            }
        });
    </script>

    @stack('js')

    {{-- =========================================================
        Global Add-to-Cart Handler
        Works across frontend Blade pages and shared components.
    ========================================================== --}}
    <script>
        (function () {
            'use strict';

            const isAuthenticated = @json(auth()->check());
            const loginUrl = @json(route('frontend.auth.login'));
            const registerUrl = @json(route('frontend.auth.register'));

            function showAuthPrompt() {
                return Swal.fire({
                    title: 'Sign in to continue',
                    text: 'Please log in or create an account to add books to your cart.',
                    icon: 'info',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Log In',
                    denyButtonText: 'Register',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    customClass: {
                        actions: 'cart-auth-actions',
                        confirmButton: 'logout-confirm-btn',
                        denyButton: 'logout-cancel-btn',
                        cancelButton: 'logout-cancel-btn'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        window.location.href = loginUrl;
                    } else if (result.isDenied) {
                        window.location.href = registerUrl;
                    }
                });
            }

            function showCartError(message) {
                return Swal.fire({
                    title: 'Unable to add book',
                    text: message || 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        actions: 'cart-auth-actions',
                        confirmButton: 'logout-confirm-btn'
                    }
                });
            }

            function updateCartCount(count) {
                document.querySelectorAll(
                    '#header-cart-count, [data-cart-count]'
                ).forEach(function (element) {
                    element.textContent = count;
                });
            }

            function getCartAddUrl(form) {
                try {
                    const url = new URL(form.action, window.location.href);

                    return /\/cart\/add\/[^/]+\/?$/.test(url.pathname);
                } catch (error) {
                    return false;
                }
            }

            // Intercept cart forms globally before page-specific handlers.
            document.addEventListener('submit', async function (event) {
                const form = event.target;

                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (!getCartAddUrl(form)) {
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();

                if (form.dataset.cartSubmitting === 'true') {
                    return;
                }

                if (typeof Swal === 'undefined') {
                    if (!isAuthenticated) {
                        window.location.href = loginUrl;
                        return;
                    }

                    form.submit();
                    return;
                }

                if (!isAuthenticated) {
                    await showAuthPrompt();
                    return;
                }

                const button = form.querySelector(
                    'button[type="submit"], button:not([type])'
                );

                const originalButtonHtml = button ? button.innerHTML : '';
                const originalDisabled = button ? button.disabled : false;

                form.dataset.cartSubmitting = 'true';

                if (button) {
                    button.disabled = true;
                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
                }

                try {
                    const token =
                        form.querySelector('input[name="_token"]')?.value ||
                        document.querySelector('meta[name="csrf-token"]')?.content;

                    const headers = {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    };

                    if (token) {
                        headers['X-CSRF-TOKEN'] = token;
                    }

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: headers,
                        body: new FormData(form),
                        credentials: 'same-origin'
                    });

                    const data = await response.json().catch(function () {
                        return {};
                    });

                    if (
                        response.status === 401 ||
                        data.message === 'Unauthenticated.'
                    ) {
                        await showAuthPrompt();
                        return;
                    }

                    if (!response.ok || data.success === false) {
                        throw new Error(
                            data.message ||
                            'Could not add this book to your cart.'
                        );
                    }

                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }

                    await Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Book added to cart successfully!',
                        showConfirmButton: false,
                        timer: 2600,
                        timerProgressBar: true
                    });
                } catch (error) {
                    await showCartError(error.message);
                } finally {
                    delete form.dataset.cartSubmitting;

                    if (button) {
                        button.disabled = originalDisabled;
                        button.innerHTML = originalButtonHtml;
                    }
                }
            }, true);

            // Handle guest-only cart links on the Books page.
            document.addEventListener('click', async function (event) {
                const target = event.target;

                if (!(target instanceof Element)) {
                    return;
                }

                const link = target.closest(
                    'a[title="Login to add to cart"], ' +
                    'a[aria-label*="add this book to cart"]'
                );

                if (!link || isAuthenticated) {
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();

                if (typeof Swal !== 'undefined') {
                    await showAuthPrompt();
                } else {
                    window.location.href = loginUrl;
                }
            }, true);
        })();
    </script>

</body>
</html>
