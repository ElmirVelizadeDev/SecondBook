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

                if (!form) {
                    return;
                }

                if (typeof Swal === 'undefined') {
                    form.submit();
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
                        form.submit();
                    }
                });
            }

            const profileLogoutBtn = document.getElementById('profileLogoutBtn');

            if (profileLogoutBtn) {
                profileLogoutBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    confirmLogout('profileLogoutForm');
                });
            }
        });
    </script>

    @stack('js')

    {{-- =========================================================
        Global Add-to-Cart Handler
        Shared handler for frontend cart forms.
    ========================================================== --}}

    <script>
        (function () {
            'use strict';

            const isAuthenticated = @json(auth()->check());
            const loginUrl = @json(route('frontend.auth.login'));
            const registerUrl = @json(route('frontend.auth.register'));

            function showAuthPrompt() {
                if (typeof Swal === 'undefined') {
                    window.location.href = loginUrl;
                    return Promise.resolve();
                }

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
                if (typeof Swal === 'undefined') {
                    window.alert(message || 'Something went wrong. Please try again.');
                    return Promise.resolve();
                }

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
                    element.textContent = Number(count) > 99 ? '99+' : count;
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

            function showButtonSuccess(button) {
                if (!button) {
                    return;
                }

                button.classList.remove('is-adding');
                button.classList.add('is-added');
                button.innerHTML =
                    '<i class="bi bi-check-lg cart-success-icon" aria-hidden="true"></i>';
            }

            function restoreButton(button, originalHtml, originalDisabled) {
                if (!button) {
                    return;
                }

                button.innerHTML = originalHtml;
                button.classList.remove('is-adding', 'is-added');
                button.disabled = originalDisabled;
            }

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
                    button.classList.remove('is-added');
                    button.classList.add('is-adding');
                    button.innerHTML =
                        '<span class="cart-loading-spinner" aria-hidden="true"></span>';
                }

                let succeeded = false;

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
                        if (button) {
                            restoreButton(
                                button,
                                originalButtonHtml,
                                originalDisabled
                            );
                        }

                        delete form.dataset.cartSubmitting;
                        await showAuthPrompt();
                        return;
                    }

                    if (
                        !response.ok ||
                        data.success === false ||
                        data.status === 'error'
                    ) {
                        throw new Error(
                            data.message ||
                            'Could not add this book to your cart.'
                        );
                    }

                    succeeded = true;

                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }

                    // Show the success icon immediately after a successful response.
                    showButtonSuccess(button);

                    // Keep the checkmark visible while the toast is displayed.
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message || 'Book added to cart successfully!',
                            showConfirmButton: false,
                            timer: 2600,
                            timerProgressBar: true
                        });
                    }
                } catch (error) {
                    console.error('Add-to-cart error:', error);

                    if (button) {
                        restoreButton(
                            button,
                            originalButtonHtml,
                            originalDisabled
                        );
                    }

                    await showCartError(error.message);
                } finally {
                    delete form.dataset.cartSubmitting;

                    if (button) {
                        if (succeeded) {
                            // Do not restore the original icon before the checkmark appears.
                            window.setTimeout(function () {
                                restoreButton(
                                    button,
                                    originalButtonHtml,
                                    originalDisabled
                                );
                            }, 900);
                        } else {
                            button.classList.remove('is-adding', 'is-added');
                            button.disabled = originalDisabled;
                        }
                    }
                }
            }, true);

            // Guest-only cart links on the Books page.
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

                await showAuthPrompt();
            }, true);
        })();
    </script>

</body>
</html>
