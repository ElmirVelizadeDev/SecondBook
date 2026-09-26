@extends('Layout.Frontend.master')

@section('title', 'Verify Code | SecondBook')
@section('hideNavbar', '1')
@section('hideFooter', '1')
@section('hideScripts', '1')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/auth-password-verify.css') }}">
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
                        <span class="sb-login-wave" aria-hidden="true">🔑</span>
                    </h1>

                    <p class="sb-login-subtitle">
                        Enter the 6-digit code sent to your email
                        and create a new password.
                    </p>

                </div>

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="sb-login-alert sb-verify-alert-danger">
                        <span class="sb-login-alert-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">
                            <strong>Verification unsuccessful</strong>

                            <span>
                                {{ $errors->first() }}
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Success Message --}}
                @if(session('status'))
                    <div class="sb-login-alert sb-verify-alert-success">
                        <span class="sb-login-alert-icon">
                            <i class="bi bi-check-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">
                            <strong>Code sent successfully</strong>

                            <span>
                                {{ session('status') }}
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('frontend.auth.password.verify.otp') }}"
                      method="POST"
                      class="sb-login-form">

                    @csrf

                    {{-- OTP --}}
                    <div class="sb-field sb-verify-field">

                        <label class="sb-field-label" for="otp_code">
                            Verification code
                            <span>6 digits</span>
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
                                autofocus>

                            <span class="sb-input-status">
                                <i class="bi bi-key"></i>
                            </span>

                        </div>

                    </div>

                    {{-- New Password --}}
                    <div class="sb-field sb-verify-field">

                        <label class="sb-field-label" for="password">
                            New password
                            <span>Required</span>
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
                                required>

                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleVerifyPassword"
                                aria-label="Show password">

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
                            <span>Didn't receive the code?</span>

                            <a href="{{ route('frontend.auth.password.request') }}"
                               class="sb-verify-resend-link">
                                Resend code
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>

                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="sb-signin-btn sb-verify-submit">

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">
                                <i class="bi bi-check2-circle"></i>
                            </span>

                            <span class="sb-signin-text">
                                Reset Password
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
                        <span>Remember your password?</span>

                        <a href="{{ route('frontend.auth.login') }}"
                           class="sb-register-link">
                            Sign in
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="sb-verify-register">
                        <span>New to SecondBook?</span>

                        <a href="{{ route('frontend.auth.register') }}"
                           class="sb-register-link">
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

    const toggleBtn = document.getElementById('toggleVerifyPassword');
    const passwordInput = document.getElementById('password');

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

    const otpInput = document.getElementById('otp_code');

    if (otpInput) {
        otpInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });
    }

});
</script>
@endpush