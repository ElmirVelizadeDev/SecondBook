@extends('Layout.Frontend.master')

@section('title', 'Login | SecondBook')

@section('hideNavbar', '1')

@section('hideFooter', '1')

@section('hideScripts', '1')

@push('css')
    <link
        rel="stylesheet"
        href="{{ asset('frontend-assets/css/auth-login.css') }}"
    >
@endpush

@section('content')

<div class="auth-page-bg auth-page-bg-login">

    <div class="container auth-wrapper py-0">

        <div class="auth-grid auth-grid-login w-100">

            <section class="auth-card auth-card-login auth-card-login-pro sb-login-premium">

                {{-- =====================================================
                     PREMIUM DECORATION
                ====================================================== --}}

                <div class="sb-login-card-glow sb-login-card-glow-one"></div>

                <div class="sb-login-card-glow sb-login-card-glow-two"></div>

                <div class="sb-login-card-line"></div>


                {{-- =====================================================
                     BRAND
                ====================================================== --}}

                <div class="sb-login-brand">

                    <a
                        href="{{ route('frontend.home') }}"
                        class="sb-login-brand-link"
                        aria-label="SecondBook home"
                    >

                        <span class="sb-login-logo-wrap">

                            <img
                                src="{{ asset('main-logo.png') }}"
                                alt="SecondBook logo"
                                class="sb-login-logo"
                            >

                        </span>

                    </a>

                </div>


                {{-- =====================================================
                     HEADING
                ====================================================== --}}

                <div class="sb-login-head">

                    <span class="sb-login-kicker">

                        <span class="sb-login-kicker-line"></span>

                        Welcome back

                        <span class="sb-login-kicker-dot"></span>

                    </span>


                    <h1 class="sb-login-title">

                        Welcome

                        <span>back.</span>

                        <span
                            class="sb-login-wave"
                            aria-hidden="true"
                        >
                            👋
                        </span>

                    </h1>


                    <p class="sb-login-subtitle">
                        Sign in to continue your reading journey
                        and discover your next great story.
                    </p>

                </div>


                {{-- =====================================================
                     LOGIN FORM
                ====================================================== --}}

                <form
                    action="{{ route('frontend.auth.login.store') }}"
                    method="POST"
                    class="sb-login-form"
                    id="loginForm"
                >

                    @csrf


                    {{-- =================================================
                         EMAIL
                    ================================================== --}}

                    <div class="sb-field">

                        <label
                            class="sb-field-label"
                            for="loginEmail"
                        >

                            Email address

                            <span>
                                Required
                            </span>

                        </label>


                        <div class="sb-input-group">

                            <span class="sb-input-icon">

                                <i class="bi bi-envelope"></i>

                            </span>


                            <input
                                type="email"
                                id="loginEmail"
                                name="email"
                                class="sb-login-input"
                                placeholder="name@example.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                maxlength="254"
                            >


                            <span class="sb-input-status">

                                <i class="bi bi-at"></i>

                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                         PASSWORD
                    ================================================== --}}

                    <div class="sb-field">

                        <div class="sb-field-label-row">

                            <label
                                class="sb-field-label mb-0"
                                for="loginPassword"
                            >

                                Password

                                <span>
                                    Required
                                </span>

                            </label>

                        </div>


                        <div class="sb-input-group">

                            <span class="sb-input-icon">

                                <i class="bi bi-lock"></i>

                            </span>


                            <input
                                type="password"
                                id="loginPassword"
                                name="password"
                                class="sb-login-input sb-password-input"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                maxlength="128"
                            >


                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleLoginPassword"
                                aria-label="Show password"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>


                    {{-- =================================================
                         OPTIONS
                    ================================================== --}}

                    <div class="sb-login-options">

                        <label
                            class="sb-remember"
                            for="rememberCheck"
                        >

                            <input
                                type="checkbox"
                                id="rememberCheck"
                                name="remember"
                                value="1"
                                {{ old('remember') ? 'checked' : '' }}
                            >


                            <span class="sb-custom-checkbox">

                                <i class="bi bi-check2"></i>

                            </span>


                            <span class="sb-remember-text">
                                Remember me
                            </span>

                        </label>


                        <a
                            href="{{ route('frontend.auth.password.request') }}"
                            class="sb-forgot-link"
                        >

                            Forgot password?

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>


                    {{-- =================================================
                         SIGN IN
                    ================================================== --}}

                    <button
                        type="submit"
                        class="sb-signin-btn"
                        id="loginSubmitButton"
                    >

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">

                                <i class="bi bi-arrow-right"></i>

                            </span>


                            <span class="sb-signin-text">
                                Sign In
                            </span>

                        </span>


                        <span class="sb-signin-arrow">

                            <i class="bi bi-arrow-up-right"></i>

                        </span>

                    </button>


                    {{-- =================================================
                         DIVIDER
                    ================================================== --}}

                    <div class="sb-login-divider">

                        <span></span>

                        <em>
                            or continue with
                        </em>

                        <span></span>

                    </div>


                    {{-- =================================================
                         GOOGLE
                    ================================================== --}}

                    <a
                        href="{{ route('frontend.auth.google.redirect') }}"
                        class="sb-google-btn"
                    >

                        <span class="sb-google-icon">

                            <i class="bi bi-google"></i>

                        </span>


                        <span>
                            Continue with Google
                        </span>


                        <i class="bi bi-arrow-up-right sb-google-arrow"></i>

                    </a>

                </form>


                {{-- =====================================================
                     BENEFITS
                ====================================================== --}}

                <div class="sb-login-benefits">

                    <div class="sb-benefit">

                        <span class="sb-benefit-icon">

                            <i class="bi bi-book"></i>

                        </span>

                        <span>
                            Thousands of books
                        </span>

                    </div>


                    <div class="sb-benefit">

                        <span class="sb-benefit-icon">

                            <i class="bi bi-shield-check"></i>

                        </span>

                        <span>
                            Trusted community
                        </span>

                    </div>


                    <div class="sb-benefit">

                        <span class="sb-benefit-icon">

                            <i class="bi bi-shop"></i>

                        </span>

                        <span>
                            Buy &amp; sell
                        </span>

                    </div>

                </div>


                {{-- =====================================================
                     REGISTER
                ====================================================== --}}

                <div class="sb-register-copy">

                    <span>
                        Don't have an account?
                    </span>


                    <a
                        href="{{ route('frontend.auth.register') }}"
                        class="sb-register-link"
                    >

                        Create account

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>


                {{-- =====================================================
                     BOTTOM DECORATIVE MARK
                ====================================================== --}}

                <div class="sb-login-bottom-mark">

                    <span></span>

                    <i class="bi bi-stars"></i>

                    <span></span>

                </div>

            </section>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================== */

    const loginForm =
        document.getElementById('loginForm');

    const toggleBtn =
        document.getElementById('toggleLoginPassword');

    const passwordInput =
        document.getElementById('loginPassword');

    const submitButton =
        document.getElementById('loginSubmitButton');


    /* =========================================================
       PASSWORD SHOW / HIDE
    ========================================================== */

    if (toggleBtn && passwordInput) {

        toggleBtn.addEventListener('click', function () {

            const isPassword =
                passwordInput.getAttribute('type') === 'password';


            passwordInput.setAttribute(
                'type',
                isPassword ? 'text' : 'password'
            );


            toggleBtn.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';


            toggleBtn.setAttribute(
                'aria-label',
                isPassword
                    ? 'Hide password'
                    : 'Show password'
            );

        });

    }


    /* =========================================================
       AJAX LOGIN
    ========================================================== */

    if (!loginForm) {
        return;
    }


    loginForm.addEventListener('submit', async function (event) {

        event.preventDefault();


        /* -----------------------------------------------------
           Remove ALL previous AJAX alerts
        ------------------------------------------------------ */

        document
            .querySelectorAll('.sb-login-ajax-alert')
            .forEach(function (alert) {
                alert.remove();
            });


        /* -----------------------------------------------------
           Disable submit button
        ------------------------------------------------------ */

        if (submitButton) {

            submitButton.disabled = true;

            submitButton.classList.add('is-loading');

        }


        const submitText =
            submitButton
                ? submitButton.querySelector('.sb-signin-text')
                : null;


        if (submitText) {

            submitText.textContent =
                'Signing in...';

        }


        /* -----------------------------------------------------
           Form data
        ------------------------------------------------------ */

        const formData =
            new FormData(loginForm);


        try {

            const response =
                await fetch(
                    loginForm.action,
                    {
                        method: 'POST',

                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },

                        body: formData,

                        credentials: 'same-origin'
                    }
                );


            /* -------------------------------------------------
               Read response safely
            -------------------------------------------------- */

            const contentType =
                response.headers.get('content-type') || '';


            if (!contentType.includes('application/json')) {

                throw new Error(
                    'Server returned an unexpected response.'
                );

            }


            const data =
                await response.json();


            /* -------------------------------------------------
               SUCCESS
            -------------------------------------------------- */

            if (response.ok && data.success) {

                window.location.href =
                    data.redirect;

                return;

            }


            /* -------------------------------------------------
               ERROR MESSAGE
            -------------------------------------------------- */

            let message =
                data.message ||
                'Invalid email or password.';


            /* -------------------------------------------------
               VALIDATION ERRORS
            -------------------------------------------------- */

            if (
                data.errors &&
                typeof data.errors === 'object'
            ) {

                const firstError =
                    Object.values(data.errors)
                        .flat()
                        .find(function (error) {
                            return error;
                        });


                if (firstError) {

                    message =
                        firstError;

                }

            }


            /* -------------------------------------------------
               CREATE ONE ALERT
            -------------------------------------------------- */

            showLoginAlert(
                'Sign in unsuccessful',
                message
            );


            /* -------------------------------------------------
               FOCUS FIELD
            -------------------------------------------------- */

            if (response.status === 422) {

                const emailInput =
                    document.getElementById('loginEmail');


                const currentPasswordInput =
                    document.getElementById('loginPassword');


                if (
                    emailInput &&
                    !emailInput.value.trim()
                ) {

                    emailInput.focus();

                } else if (
                    currentPasswordInput &&
                    !currentPasswordInput.value.trim()
                ) {

                    currentPasswordInput.focus();

                }

            }


        } catch (error) {

            /* -------------------------------------------------
               NETWORK / SERVER ERROR
            -------------------------------------------------- */

            showLoginAlert(
                'Something went wrong',
                'Please try again.'
            );

        } finally {

            /* -------------------------------------------------
               Restore submit button
            -------------------------------------------------- */

            if (submitButton) {

                submitButton.disabled =
                    false;

                submitButton.classList.remove(
                    'is-loading'
                );

            }


            if (submitText) {

                submitText.textContent =
                    'Sign In';

            }

        }

    });


    /* =========================================================
       LOGIN ALERT
    ========================================================== */

    function showLoginAlert(title, message) {

        /* -----------------------------------------------------
           Remove any existing AJAX alert first
        ------------------------------------------------------ */

        document
            .querySelectorAll('.sb-login-ajax-alert')
            .forEach(function (alert) {
                alert.remove();
            });


        /* -----------------------------------------------------
           Create alert
        ------------------------------------------------------ */

        const alert =
            document.createElement('div');


        alert.className =
            'sb-login-alert sb-login-ajax-alert';


        alert.innerHTML = `

            <span class="sb-login-alert-icon">

                <i class="bi bi-exclamation-circle"></i>

            </span>


            <div class="sb-login-alert-content">

                <strong>
                    ${escapeHtml(title)}
                </strong>


                <span>
                    ${escapeHtml(message)}
                </span>

            </div>

        `;


        /* -----------------------------------------------------
           Insert before form
        ------------------------------------------------------ */

        loginForm.parentNode.insertBefore(
            alert,
            loginForm
        );

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================== */

    function escapeHtml(value) {

        const div =
            document.createElement('div');


        div.textContent =
            value ?? '';


        return div.innerHTML;

    }

});
</script>

@endpush