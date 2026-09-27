@extends('Layout.Frontend.master')

@section('title', 'Verify Email | SecondBook')

@section('hideNavbar', '1')
@section('hideFooter', '1')
@section('hideScripts', '1')

@push('css')
    <link rel="stylesheet"
          href="{{ asset('frontend-assets/css/auth-email-verify.css') }}">
@endpush

@section('content')

<div class="auth-page-bg auth-page-bg-login sb-password-verify-page">

    <div class="container auth-wrapper py-0">

        <div class="auth-grid auth-grid-login w-100">

            <section class="auth-card auth-card-login auth-card-login-pro sb-password-verify-premium">

                <div class="sb-verify-orb sb-verify-orb-one"></div>
                <div class="sb-verify-orb sb-verify-orb-two"></div>
                <div class="sb-verify-card-line"></div>

                {{-- Brand --}}
                <div class="sb-login-brand">
                    <a href="{{ route('frontend.home') }}"
                       class="sb-login-brand-link"
                       aria-label="SecondBook home">

                        <span class="sb-login-logo-wrap">
                            <img src="{{ asset('main-logo.png') }}"
                                 alt="SecondBook logo"
                                 class="sb-login-logo">
                        </span>

                    </a>
                </div>

                {{-- Heading --}}
                <div class="sb-login-head">

                    <span class="sb-login-kicker">
                        <span class="sb-login-kicker-line"></span>
                        VERIFY YOUR EMAIL
                        <span class="sb-login-kicker-dot"></span>
                    </span>

                    <h1 class="sb-login-title">
                        Verify
                        <span>your email.</span>
                        <span class="sb-login-wave" aria-hidden="true">
                            ✉️
                        </span>
                    </h1>

                    <p class="sb-login-subtitle">
                        Enter the 6-digit verification code
                        to activate your SecondBook account.
                    </p>

                </div>

                {{-- Alert --}}
                <div
                    class="sb-login-alert sb-verify-alert-danger"
                    id="emailVerifyAlert"
                    style="display: none;"
                >

                    <span class="sb-login-alert-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </span>

                    <div class="sb-login-alert-content">

                        <strong id="emailVerifyAlertTitle">
                            Verification unsuccessful
                        </strong>

                        <span id="emailVerifyAlertMessage"></span>

                    </div>

                </div>

                {{-- Email --}}
                <div class="sb-verify-resend">
                    <span class="sb-verify-resend-icon">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <div class="sb-verify-resend-content">

                        <span>
                            Verification code for
                        </span>

                        <strong>
                            {{ $email }}
                        </strong>

                    </div>
                </div>

                {{-- Form --}}
                <form
                    action="{{ route('frontend.auth.email.verify.otp') }}"
                    method="POST"
                    class="sb-login-form"
                    id="emailVerifyForm"
                >

                    @csrf

                    {{-- OTP --}}
                    <div class="sb-field sb-verify-field">

                        <label
                            class="sb-field-label"
                            for="otp_code"
                        >
                            Verification code

                            <span>
                                6 digits
                            </span>
                        </label>

                        <div class="sb-input-group sb-otp-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-shield-lock"></i>
                            </span>

                            <input
                                type="text"
                                id="otp_code"
                                name="otp_code"
                                class="sb-login-input sb-otp-input"
                                placeholder="Enter 6-digit code"
                                maxlength="6"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                required
                                autofocus
                            >

                            <span class="sb-input-status">
                                <i class="bi bi-key"></i>
                            </span>

                        </div>

                    </div>

                    {{-- Resend --}}
                    <div class="sb-verify-resend">

                        <span class="sb-verify-resend-icon">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <div class="sb-verify-resend-content">

                            <span>
                                Didn't receive the code?
                            </span>

                            <a
                                href="{{ route('frontend.auth.email.verify.resend') }}"
                                class="sb-verify-resend-link"
                                id="emailVerifyResendLink"
                            >
                                Resend code
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="sb-signin-btn sb-verify-submit"
                        id="emailVerifySubmitButton"
                    >

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">
                                <i class="bi bi-check2-circle"></i>
                            </span>

                            <span class="sb-signin-text">
                                Verify Email
                            </span>

                        </span>

                        <span class="sb-signin-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </button>

                </form>

                {{-- Navigation --}}
                <div class="sb-verify-navigation">

                    <div class="sb-verify-login">

                        <span>
                            Already verified?
                        </span>

                        <a
                            href="{{ route('frontend.auth.login') }}"
                            class="sb-register-link"
                        >
                            Sign in
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

                {{-- Bottom Mark --}}
                <div class="sb-login-bottom-mark">

                    <span></span>

                    <i class="bi bi-shield-check"></i>

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

    const otpInput =
        document.getElementById('otp_code');

    if (otpInput) {
        otpInput.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 6);

        });
    }

    const verifyForm =
        document.getElementById('emailVerifyForm');

    const submitButton =
        document.getElementById('emailVerifySubmitButton');

    const alertBox =
        document.getElementById('emailVerifyAlert');

    const alertTitle =
        document.getElementById('emailVerifyAlertTitle');

    const alertMessage =
        document.getElementById('emailVerifyAlertMessage');

    if (!verifyForm || !submitButton || !alertBox) {
        return;
    }

    function hideAlert() {
        alertBox.style.display = 'none';
        alertTitle.textContent =
            'Verification unsuccessful';
        alertMessage.textContent = '';
    }

    function showAlert(title, message) {

        alertTitle.textContent = title;
        alertMessage.textContent = message;
        alertBox.style.display = 'flex';

    }

    verifyForm.addEventListener('submit', async function (event) {

        event.preventDefault();

        hideAlert();

        const originalContent =
            submitButton.innerHTML;

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <span class="sb-signin-content">
                <span class="sb-signin-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </span>

                <span class="sb-signin-text">
                    Verifying...
                </span>
            </span>

            <span class="sb-signin-arrow">
                <i class="bi bi-arrow-up-right"></i>
            </span>
        `;

        try {

            const response = await fetch(
                verifyForm.action,
                {
                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'input[name="_token"]'
                            ).value,

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'
                    },

                    body: new FormData(verifyForm),

                    credentials: 'same-origin'
                }
            );

            const data =
                await response.json();

            if (
                response.ok &&
                data.success
            ) {

                if (data.redirect) {
                    window.location.href =
                        data.redirect;
                    return;
                }

            }

            let message =
                'Unable to verify your email. Please try again.';

            if (data.errors) {

                const firstError =
                    Object.values(data.errors)
                        .flat()
                        .find(Boolean);

                if (firstError) {
                    message = firstError;
                }

            } else if (data.message) {

                message = data.message;

            }

            showAlert(
                'Verification unsuccessful',
                message
            );

        } catch (error) {

            showAlert(
                'Something went wrong',
                'Unable to process your request right now. Please try again.'
            );

        } finally {

            submitButton.disabled = false;

            submitButton.innerHTML =
                originalContent;

        }

    });

});
</script>
@endpush