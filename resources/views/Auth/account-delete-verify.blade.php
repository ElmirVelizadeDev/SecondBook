@extends('Layout.Frontend.master')

@section('title', 'Delete Account | SecondBook')

@section('hideNavbar', '1')

@section('hideFooter', '1')

@section('hideScripts', '1')

@php
$passwordVerified = session(
'account_delete_password_verified',
false
);
@endphp

@push('css') <link
     rel="stylesheet"
     href="{{ asset('frontend-assets/css/auth-account-delete-verify.css') }}"
 >
@endpush

@section('content')

<div class="auth-delete-page-bg">


<div class="container auth-delete-wrapper py-0">

    <div class="auth-delete-grid w-100">

        <section class="sb-account-delete-verify-premium">

            {{-- Decorative Elements --}}
            <div class="sb-account-delete-orb sb-account-delete-orb-one"></div>
            <div class="sb-account-delete-orb sb-account-delete-orb-two"></div>
            <div class="sb-account-delete-card-line"></div>

            {{-- Brand --}}
            <div class="sb-account-delete-brand">

                <a
                    href="{{ route('frontend.home') }}"
                    class="sb-account-delete-brand-link"
                    aria-label="SecondBook home"
                >

                    <span class="sb-account-delete-logo-wrap">

                        <img
                            src="{{ asset('main-logo.png') }}"
                            alt="SecondBook logo"
                            class="sb-account-delete-logo"
                        >

                    </span>

                </a>

            </div>

            {{-- Heading --}}
            <div class="sb-account-delete-head">

                <span class="sb-account-delete-kicker">

                    <span class="sb-account-delete-kicker-line"></span>

                    ACCOUNT SECURITY

                    <span class="sb-account-delete-kicker-dot"></span>

                </span>

                <h1 class="sb-account-delete-title">

                    @if($passwordVerified)

                        Verify
                        <span>your code.</span>

                        <span
                            class="sb-account-delete-wave"
                            aria-hidden="true"
                        >
                            🔐
                        </span>

                    @else

                        Confirm
                        <span>your identity.</span>

                        <span
                            class="sb-account-delete-wave"
                            aria-hidden="true"
                        >
                            🔒
                        </span>

                    @endif

                </h1>

                <p class="sb-account-delete-subtitle">

                    @if($passwordVerified)

                        Enter the 6-digit code sent to your email
                        to permanently delete your account.

                    @else

                        Enter your current password to continue
                        with account deletion.

                    @endif

                </p>

            </div>

            {{-- AJAX Validation Alert --}}
            <div
                class="sb-login-alert sb-account-delete-alert-danger"
                id="accountDeleteVerifyAlert"
                style="display: none;"
            >

                <span class="sb-login-alert-icon">
                    <i class="bi bi-exclamation-circle"></i>
                </span>

                <div class="sb-login-alert-content">

                    <strong id="accountDeleteVerifyAlertTitle">
                        Verification unsuccessful
                    </strong>

                    <span id="accountDeleteVerifyAlertMessage"></span>

                </div>

            </div>

            {{-- Success Message --}}
            @if(session('status'))

                <div class="sb-login-alert sb-account-delete-alert-success">

                    <span class="sb-login-alert-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>

                    <div class="sb-login-alert-content">

                        <strong>
                            Verification code sent
                        </strong>

                        <span>
                            {{ session('status') }}
                        </span>

                    </div>

                </div>

            @endif

            {{-- Verification Form --}}
            <form
                action="{{ $passwordVerified
                    ? route('frontend.auth.account.delete.verify.otp')
                    : route('frontend.auth.account.delete.verify.password') }}"
                method="POST"
                class="sb-login-form"
                id="accountDeleteVerifyForm"
            >

                @csrf

                @if(!$passwordVerified)

                    {{-- Current Password --}}
                    <div class="sb-field sb-account-delete-field">

                        <label
                            class="sb-field-label"
                            for="current_password"
                        >

                            Current password

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
                                id="current_password"
                                name="current_password"
                                class="sb-login-input sb-account-delete-password-input"
                                placeholder="Enter your current password"
                                autocomplete="current-password"
                                required
                                autofocus
                            >

                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleCurrentPassword"
                                aria-label="Show password"
                            >
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                @else

                    {{-- OTP --}}
                    <div class="sb-field sb-account-delete-field">

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
                                minlength="6"
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

                    {{-- Resend Code --}}
                    <div class="sb-verify-resend">

                        <span class="sb-verify-resend-icon">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <div class="sb-verify-resend-content">

                            <span>
                                Didn't receive the code?
                            </span>

                            <a
                                href="{{ route('frontend.auth.account.delete.resend') }}"
                                class="sb-verify-resend-link"
                                id="accountDeleteResendLink"
                            >
                                Resend code
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>

                @endif

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="sb-signin-btn sb-account-delete-submit"
                    id="accountDeleteVerifySubmitButton"
                >

                    <span class="sb-signin-content">

                        <span class="sb-signin-icon">

                            <i
                                class="bi {{ $passwordVerified
                                    ? 'bi-trash3'
                                    : 'bi-arrow-right' }}"
                            ></i>

                        </span>

                        <span
                            class="sb-signin-text"
                            id="accountDeleteVerifySubmitText"
                        >

                            @if($passwordVerified)

                                Delete Account

                            @else

                                Continue

                            @endif

                        </span>

                    </span>

                    <span class="sb-signin-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>

                </button>

            </form>

            {{-- Security Note --}}
            <div class="sb-account-delete-security">

                <span class="sb-account-delete-security-icon">
                    <i class="bi bi-shield-check"></i>
                </span>

                <div class="sb-account-delete-security-content">

                    <strong>
                        Secure verification
                    </strong>

                    <span>
                        Your account can only be deleted after
                        identity verification.
                    </span>

                </div>

            </div>

            {{-- Bottom Mark --}}
            <div class="sb-account-delete-bottom-mark">

                <span></span>

                <i class="bi bi-shield-lock"></i>

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
       VERIFICATION STATE
    ========================================================= */

    const passwordVerified = @json($passwordVerified);

    /* =========================================================
       ELEMENTS
    ========================================================= */

    const verifyForm =
        document.getElementById(
            'accountDeleteVerifyForm'
        );

    const submitButton =
        document.getElementById(
            'accountDeleteVerifySubmitButton'
        );

    const submitText =
        document.getElementById(
            'accountDeleteVerifySubmitText'
        );

    const verifyAlert =
        document.getElementById(
            'accountDeleteVerifyAlert'
        );

    const verifyAlertTitle =
        document.getElementById(
            'accountDeleteVerifyAlertTitle'
        );

    const verifyAlertMessage =
        document.getElementById(
            'accountDeleteVerifyAlertMessage'
        );

    const resendLink =
        document.getElementById(
            'accountDeleteResendLink'
        );

    if (
        !verifyForm ||
        !submitButton ||
        !verifyAlert
    ) {
        return;
    }

    /* =========================================================
       CURRENT PASSWORD TOGGLE
    ========================================================= */

    const toggleCurrentPassword =
        document.getElementById(
            'toggleCurrentPassword'
        );

    const currentPasswordInput =
        document.getElementById(
            'current_password'
        );

    if (
        toggleCurrentPassword &&
        currentPasswordInput
    ) {

        toggleCurrentPassword.addEventListener(
            'click',
            function () {

                const isPassword =
                    currentPasswordInput.getAttribute(
                        'type'
                    ) === 'password';

                currentPasswordInput.setAttribute(
                    'type',
                    isPassword
                        ? 'text'
                        : 'password'
                );

                toggleCurrentPassword.innerHTML =
                    isPassword
                        ? '<i class="bi bi-eye-slash"></i>'
                        : '<i class="bi bi-eye"></i>';

                toggleCurrentPassword.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            }
        );

    }

    /* =========================================================
       OTP INPUT
    ========================================================= */

    const otpInput =
        document.getElementById('otp_code');

    if (otpInput) {

        otpInput.addEventListener(
            'input',
            function () {

                this.value =
                    this.value
                        .replace(/\D/g, '')
                        .slice(0, 6);

            }
        );

    }

    /* =========================================================
       HIDE ALERT
    ========================================================= */

    function hideVerifyAlert() {

        verifyAlert.style.display =
            'none';

        verifyAlertTitle.textContent =
            'Verification unsuccessful';

        verifyAlertMessage.textContent =
            '';

    }

    /* =========================================================
       SHOW ALERT
    ========================================================= */

    function showVerifyAlert(
        title,
        message
    ) {

        verifyAlertTitle.textContent =
            title;

        verifyAlertMessage.textContent =
            message;

        verifyAlert.style.display =
            'flex';

    }

    /* =========================================================
       SUBMIT FORM
    ========================================================= */

    verifyForm.addEventListener(
        'submit',
        async function (event) {

            event.preventDefault();

            hideVerifyAlert();

            const originalButtonContent =
                submitButton.innerHTML;

            submitButton.disabled =
                true;

            submitButton.innerHTML = `

                <span class="sb-signin-content">

                    <span class="sb-signin-icon">

                        <i class="bi bi-arrow-repeat"></i>

                    </span>

                    <span class="sb-signin-text">

                        ${
                            passwordVerified
                                ? 'Deleting...'
                                : 'Verifying...'
                        }

                    </span>

                </span>

                <span class="sb-signin-arrow">

                    <i class="bi bi-arrow-up-right"></i>

                </span>

            `;

            try {

                const csrfInput =
                    verifyForm.querySelector(
                        'input[name="_token"]'
                    );

                const formData =
                    new FormData(verifyForm);

                /*
                 * Make absolutely sure OTP field is sent
                 * during the OTP stage.
                 */
                if (passwordVerified) {

                    const otpField =
                        document.getElementById(
                            'otp_code'
                        );

                    if (!otpField) {

                        showVerifyAlert(
                            'Verification unsuccessful',
                            'Verification code is required.'
                        );

                        submitButton.disabled =
                            false;

                        submitButton.innerHTML =
                            originalButtonContent;

                        return;
                    }

                    const otpValue =
                        otpField.value.trim();

                    if (!otpValue) {

                        showVerifyAlert(
                            'Verification unsuccessful',
                            'Verification code is required.'
                        );

                        otpField.focus();

                        submitButton.disabled =
                            false;

                        submitButton.innerHTML =
                            originalButtonContent;

                        return;
                    }

                    formData.set(
                        'otp_code',
                        otpValue
                    );

                }

                const response =
                    await fetch(
                        verifyForm.action,
                        {
                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN':
                                    csrfInput
                                        ? csrfInput.value
                                        : '',

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'Accept':
                                    'application/json'

                            },

                            body:
                                formData,

                            credentials:
                                'same-origin'
                        }
                    );

                let data = {};

                try {

                    data =
                        await response.json();

                } catch (jsonError) {

                    data = {};

                }

                /* =====================================================
                   SUCCESS
                ===================================================== */

                if (
                    response.ok &&
                    data.success
                ) {

                    /*
                     * Password verified successfully.
                     * Reload page so Blade renders OTP stage.
                     */
                    if (
                        !passwordVerified &&
                        data.step === 'otp'
                    ) {

                        window.location.reload();

                        return;

                    }

                    /*
                     * Account deleted successfully.
                     */
                    if (data.redirect) {

                        window.location.href =
                            data.redirect;

                        return;

                    }

                }

                /* =====================================================
                   ERRORS
                ===================================================== */

                let message =
                    passwordVerified
                        ? 'Unable to delete your account. Please try again.'
                        : 'Unable to verify your password. Please try again.';

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

                showVerifyAlert(
                    'Verification unsuccessful',
                    message
                );

            } catch (error) {

                showVerifyAlert(
                    'Something went wrong',
                    'Unable to process your request right now. Please try again.'
                );

            } finally {

                submitButton.disabled =
                    false;

                submitButton.innerHTML =
                    originalButtonContent;

            }

        }
    );

    /* =========================================================
       RESEND OTP
    ========================================================= */

    if (resendLink) {

        resendLink.addEventListener(
            'click',
            async function (event) {

                event.preventDefault();

                hideVerifyAlert();

                const originalContent =
                    resendLink.innerHTML;

                resendLink.style.pointerEvents =
                    'none';

                resendLink.innerHTML = `
                    Sending...
                `;

                try {

                    const csrfInput =
                        verifyForm.querySelector(
                            'input[name="_token"]'
                        );

                    const response =
                        await fetch(
                            resendLink.href,
                            {
                                method: 'POST',

                                headers: {

                                    'X-CSRF-TOKEN':
                                        csrfInput
                                            ? csrfInput.value
                                            : '',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'Accept':
                                        'application/json'

                                },

                                credentials:
                                    'same-origin'
                            }
                        );

                    let data = {};

                    try {

                        data =
                            await response.json();

                    } catch (jsonError) {

                        data = {};

                    }

                    if (
                        response.ok &&
                        data.success
                    ) {

                        showVerifyAlert(
                            'Verification code sent',
                            data.message ||
                                'A new verification code has been sent to your email.'
                        );

                    } else {

                        let message =
                            'Unable to resend the verification code. Please try again.';

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

                        showVerifyAlert(
                            'Verification unsuccessful',
                            message
                        );

                    }

                } catch (error) {

                    showVerifyAlert(
                        'Something went wrong',
                        'Unable to resend the verification code right now. Please try again.'
                    );

                } finally {

                    resendLink.style.pointerEvents =
                        '';

                    resendLink.innerHTML =
                        originalContent;

                }

            }
        );

    }

});

</script>

@endpush
