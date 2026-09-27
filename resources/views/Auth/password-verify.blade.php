@extends('Layout.Frontend.master')

@section('title', 'Verify Code | SecondBook')

@section('hideNavbar', '1')

@section('hideFooter', '1')

@section('hideScripts', '1')

@php

    $sensitiveAction = session('sensitive_action');

    $verifyPurpose = $sensitiveAction === 'password_change'
        ? 'password_change'
        : 'password_reset';

@endphp

@push('css')

    <link rel="stylesheet"
          href="{{ asset('frontend-assets/css/auth-password-verify.css') }}">

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

                        VERIFY YOUR IDENTITY

                        <span class="sb-login-kicker-dot"></span>

                    </span>


                    <h1 class="sb-login-title">

                        Verify

                        <span>your code.</span>

                        <span class="sb-login-wave"
                              aria-hidden="true">
                            🔑
                        </span>

                    </h1>


                    <p class="sb-login-subtitle">

                        @if($verifyPurpose === 'password_change')

                            Enter the 6-digit code sent to your email
                            and create a new password.

                        @else

                            Enter the 6-digit code sent to your email
                            and create a new password.

                        @endif

                    </p>

                </div>


                {{-- AJAX Validation Alert --}}

                <div
                    class="sb-login-alert sb-verify-alert-danger"
                    id="passwordVerifyAlert"
                    style="display: none;"
                >

                    <span class="sb-login-alert-icon">

                        <i class="bi bi-exclamation-circle"></i>

                    </span>


                    <div class="sb-login-alert-content">

                        <strong id="passwordVerifyAlertTitle">
                            Verification unsuccessful
                        </strong>

                        <span id="passwordVerifyAlertMessage"></span>

                    </div>

                </div>


                {{-- Success Message --}}

                @if(session('status'))

                    <div class="sb-login-alert sb-verify-alert-success">

                        <span class="sb-login-alert-icon">

                            <i class="bi bi-check-circle"></i>

                        </span>


                        <div class="sb-login-alert-content">

                            <strong>
                                Code sent successfully
                            </strong>

                            <span>
                                {{ session('status') }}
                            </span>

                        </div>

                    </div>

                @endif


                {{-- Form --}}

                <form
                    action="{{ route('frontend.auth.password.verify.otp') }}"
                    method="POST"
                    class="sb-login-form"
                    id="passwordVerifyForm"
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


                    {{-- New Password --}}

                    <div class="sb-field sb-verify-field">

                        <label
                            class="sb-field-label"
                            for="password"
                        >

                            New password

                            <span>
                                Required
                            </span>

                        </label>


                        <div class="sb-input-group">

                            <span class="sb-input-icon">

                                <i class="bi bi-lock"></i>

                            </span>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="sb-login-input sb-password-input"
                                placeholder="Create your new password"
                                autocomplete="new-password"
                                required
                            >


                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleVerifyPassword"
                                aria-label="Show password"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>


                    {{-- Confirm Password --}}

                    <div class="sb-field sb-verify-field">

                        <label
                            class="sb-field-label"
                            for="password_confirmation"
                        >

                            Confirm password

                            <span>
                                Required
                            </span>

                        </label>


                        <div class="sb-input-group">

                            <span class="sb-input-icon">

                                <i class="bi bi-lock-fill"></i>

                            </span>


                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="sb-login-input sb-password-input"
                                placeholder="Confirm your new password"
                                autocomplete="new-password"
                                required
                            >


                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleVerifyPasswordConfirmation"
                                aria-label="Show password"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

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
                                href="{{ route('frontend.auth.password.resend') }}"
                                class="sb-verify-resend-link"
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
                        id="passwordVerifySubmitButton"
                    >

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">

                                <i class="bi bi-check2-circle"></i>

                            </span>


                            <span class="sb-signin-text">

                                @if($verifyPurpose === 'password_change')

                                    Update Password

                                @else

                                    Reset Password

                                @endif

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
                            Remember your password?
                        </span>


                        <a
                            href="{{ route('frontend.auth.login') }}"
                            class="sb-register-link"
                        >

                            Sign in

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>


                    <div class="sb-verify-register">

                        <span>
                            New to SecondBook?
                        </span>


                        <a
                            href="{{ route('frontend.auth.register') }}"
                            class="sb-register-link"
                        >

                            Create an Account

                            <i class="bi bi-arrow-up-right"></i>

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

    /*
    |--------------------------------------------------------------------------
    | Verify Purpose
    |--------------------------------------------------------------------------
    */

    const verifyPurpose = @json($verifyPurpose);


    /*
    |--------------------------------------------------------------------------
    | Password Toggle
    |--------------------------------------------------------------------------
    */

    const toggleBtn =
        document.getElementById('toggleVerifyPassword');

    const passwordInput =
        document.getElementById('password');


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


    /*
    |--------------------------------------------------------------------------
    | Confirm Password Toggle
    |--------------------------------------------------------------------------
    */

    const toggleConfirmationBtn =
        document.getElementById(
            'toggleVerifyPasswordConfirmation'
        );


    const passwordConfirmationInput =
        document.getElementById(
            'password_confirmation'
        );


    if (
        toggleConfirmationBtn &&
        passwordConfirmationInput
    ) {

        toggleConfirmationBtn.addEventListener(
            'click',
            function () {

                const isPassword =
                    passwordConfirmationInput.getAttribute(
                        'type'
                    ) === 'password';


                passwordConfirmationInput.setAttribute(
                    'type',
                    isPassword ? 'text' : 'password'
                );


                toggleConfirmationBtn.innerHTML = isPassword
                    ? '<i class="bi bi-eye-slash"></i>'
                    : '<i class="bi bi-eye"></i>';


                toggleConfirmationBtn.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | OTP Input
    |--------------------------------------------------------------------------
    */

    const otpInput =
        document.getElementById('otp_code');


    if (otpInput) {

        otpInput.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 6);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | AJAX Verification
    |--------------------------------------------------------------------------
    */

    const verifyForm =
        document.getElementById('passwordVerifyForm');


    const verifySubmitButton =
        document.getElementById(
            'passwordVerifySubmitButton'
        );


    const verifyAlert =
        document.getElementById(
            'passwordVerifyAlert'
        );


    const verifyAlertTitle =
        document.getElementById(
            'passwordVerifyAlertTitle'
        );


    const verifyAlertMessage =
        document.getElementById(
            'passwordVerifyAlertMessage'
        );


    if (
        !verifyForm ||
        !verifySubmitButton ||
        !verifyAlert
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Hide Alert
    |--------------------------------------------------------------------------
    */

    function hideVerifyAlert() {

        verifyAlert.style.display = 'none';

        verifyAlertTitle.textContent =
            'Verification unsuccessful';

        verifyAlertMessage.textContent = '';

    }


    /*
    |--------------------------------------------------------------------------
    | Show Alert
    |--------------------------------------------------------------------------
    */

    function showVerifyAlert(title, message) {

        verifyAlertTitle.textContent =
            title;

        verifyAlertMessage.textContent =
            message;

        verifyAlert.style.display =
            'flex';

    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    verifyForm.addEventListener(
        'submit',
        async function (event) {

            event.preventDefault();

            hideVerifyAlert();


            const originalButtonContent =
                verifySubmitButton.innerHTML;


            verifySubmitButton.disabled = true;


            verifySubmitButton.innerHTML = `

                <span class="sb-signin-content">

                    <span class="sb-signin-icon">

                        <i class="bi bi-arrow-repeat"></i>

                    </span>


                    <span class="sb-signin-text">

                        ${
                            verifyPurpose === 'password_change'
                                ? 'Updating...'
                                : 'Resetting...'
                        }

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

                        body:
                            new FormData(
                                verifyForm
                            ),

                        credentials:
                            'same-origin'

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Read JSON
                |--------------------------------------------------------------------------
                */

                const data =
                    await response.json();


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                if (
                    response.ok &&
                    data.success
                ) {

                    if (data.redirect) {

                        window.location.href =
                            data.redirect;

                        return;

                    }


                    window.location.href =
                        @json(
                            route(
                                'frontend.auth.login'
                            )
                        );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Validation Errors
                |--------------------------------------------------------------------------
                */

                let message =
                    verifyPurpose === 'password_change'
                        ? 'Unable to update your password. Please try again.'
                        : 'Unable to reset your password. Please try again.';


                if (data.errors) {

                    const firstError =
                        Object.values(data.errors)
                            .flat()
                            .find(Boolean);


                    if (firstError) {

                        message =
                            firstError;

                    }

                } else if (data.message) {

                    message =
                        data.message;

                }


                /*
                |--------------------------------------------------------------------------
                | Show Error Without Refresh
                |--------------------------------------------------------------------------
                */

                showVerifyAlert(
                    'Verification unsuccessful',
                    message
                );

            } catch (error) {

                /*
                |--------------------------------------------------------------------------
                | Request Error
                |--------------------------------------------------------------------------
                */

                showVerifyAlert(
                    'Something went wrong',
                    'Unable to process your request right now. Please try again.'
                );

            } finally {

                /*
                |--------------------------------------------------------------------------
                | Restore Button
                |--------------------------------------------------------------------------
                */

                verifySubmitButton.disabled =
                    false;


                verifySubmitButton.innerHTML =
                    originalButtonContent;

            }

        }
    );

});

</script>

@endpush