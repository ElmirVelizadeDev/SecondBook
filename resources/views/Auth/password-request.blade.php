@extends('Layout.Frontend.master')

@section('title', 'Password Reset | SecondBook')
@section('hideNavbar', '1')
@section('hideFooter', '1')
@section('hideScripts', '1')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/auth-password-reset.css') }}">
@endpush

@section('content')

<div class="auth-page-bg auth-page-bg-login sb-password-reset-page">

    <div class="container auth-wrapper py-0">

        <div class="auth-grid auth-grid-login w-100">

            <section class="auth-card auth-card-login auth-card-login-pro sb-password-reset-premium">

                {{-- Decorative elements --}}
                <span class="sb-reset-orb sb-reset-orb-one"></span>
                <span class="sb-reset-orb sb-reset-orb-two"></span>
                <span class="sb-reset-card-line"></span>

                {{-- Brand --}}
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

                {{-- Heading --}}
                <div class="sb-login-head">

                    <span class="sb-login-kicker">
                        <span class="sb-login-kicker-line"></span>
                        PASSWORD RECOVERY
                        <span class="sb-login-kicker-dot"></span>
                    </span>

                    <h1 class="sb-login-title">
                        Reset
                        <span>your password.</span>
                        <span
                            class="sb-login-wave"
                            aria-hidden="true"
                        >🔐</span>
                    </h1>

                    <p class="sb-login-subtitle">
                        Enter your email address and we'll send you a 6-digit verification code.
                    </p>

                </div>

                {{-- AJAX Alert --}}
                <div
                    class="sb-login-alert sb-password-reset-ajax-alert"
                    id="passwordResetAlert"
                    style="display: none;"
                >

                    <span class="sb-login-alert-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </span>

                    <div class="sb-login-alert-content">

                        <strong id="passwordResetAlertTitle">
                            Please check the following
                        </strong>

                        <ul id="passwordResetAlertList"></ul>

                    </div>

                </div>

                {{-- Password reset form --}}
                <form
                    action="{{ route('frontend.auth.password.send.otp') }}"
                    method="POST"
                    class="sb-login-form"
                    id="passwordResetForm"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="_redirect_to"
                        value="{{ route('frontend.auth.password.verify') }}"
                    >

                    <div class="sb-field">

                        <label
                            class="sb-field-label"
                            for="email"
                        >
                            Email address

                            <span>Required</span>
                        </label>

                        <div class="sb-input-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="sb-login-input"
                                placeholder="name@example.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus
                            >

                            <span class="sb-input-status">
                                <i class="bi bi-at"></i>
                            </span>

                        </div>

                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="sb-signin-btn sb-reset-submit"
                        id="passwordResetSubmitButton"
                    >

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">
                                <i class="bi bi-send"></i>
                            </span>

                            <span class="sb-signin-text">
                                Send Verification Code
                            </span>

                        </span>

                        <span class="sb-signin-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </button>

                </form>

                {{-- Bottom navigation --}}
                <div class="sb-reset-navigation">

                    <div class="sb-reset-login">

                        <span>Remember your password?</span>

                        <a
                            href="{{ route('frontend.auth.login') }}"
                            class="sb-register-link"
                        >
                            Sign in
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                    <div class="sb-reset-register">

                        <span>New to SecondBook?</span>

                        <a
                            href="{{ route('frontend.auth.register') }}"
                            class="sb-register-link"
                        >
                            Create an Account
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>

                {{-- Bottom mark --}}
                <div class="sb-login-bottom-mark">

                    <span></span>

                    <i class="bi bi-shield-lock"></i>

                    <span></span>

                </div>

            </section>

        </div>

    </div>

</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('passwordResetForm');
    const submitButton = document.getElementById('passwordResetSubmitButton');

    const alertBox = document.getElementById('passwordResetAlert');
    const alertTitle = document.getElementById('passwordResetAlertTitle');
    const alertList = document.getElementById('passwordResetAlertList');

    if (!form || !submitButton || !alertBox) {
        return;
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    function removeAlert() {
        alertBox.style.display = 'none';
        alertTitle.textContent = 'Please check the following';
        alertList.innerHTML = '';
    }

    function showAlert(title, messages) {

        removeAlert();

        alertTitle.textContent = title;

        const uniqueMessages = [...new Set(
            messages.filter(Boolean)
        )];

        uniqueMessages.forEach(function (message) {

            const li = document.createElement('li');

            li.innerHTML = escapeHtml(message);

            alertList.appendChild(li);
        });

        alertBox.style.display = 'flex';
    }

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        removeAlert();

        const originalButtonContent = submitButton.innerHTML;

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <span class="sb-signin-content">

                <span class="sb-signin-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </span>

                <span class="sb-signin-text">
                    Sending...
                </span>

            </span>

            <span class="sb-signin-arrow">
                <i class="bi bi-arrow-up-right"></i>
            </span>
        `;

        try {

            const response = await fetch(
                form.action,
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('input[name="_token"]')
                            .value,

                        'X-Requested-With': 'XMLHttpRequest',

                        'Accept': 'application/json'
                    },
                    body: new FormData(form),
                    credentials: 'same-origin'
                }
            );

            const data = await response.json();

            if (response.ok && data.success !== false) {

                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                window.location.href = @json(
                    route('frontend.auth.password.verify')
                );

                return;
            }

            let messages = [];

            if (data.errors) {

                Object.values(data.errors).forEach(function (errors) {

                    if (Array.isArray(errors)) {
                        messages.push(...errors);
                    } else {
                        messages.push(errors);
                    }

                });
            }

            if (messages.length === 0 && data.message) {
                messages.push(data.message);
            }

            if (messages.length === 0) {
                messages.push(
                    'Unable to send the verification code. Please try again.'
                );
            }

            showAlert(
                data.message
                    ? 'Unable to continue'
                    : 'Please check the following',
                messages
            );

        } catch (error) {

            showAlert(
                'Something went wrong',
                [
                    'Unable to process your request right now. Please try again.'
                ]
            );

        } finally {

            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonContent;

        }

    });

});
</script>
@endpush

@endsection