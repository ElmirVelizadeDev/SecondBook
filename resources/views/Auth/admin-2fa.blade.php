@extends('Layout.Frontend.master')

@section('title', 'Admin Verification | SecondBook')

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

                {{-- DECORATION --}}
                <div class="sb-login-card-glow sb-login-card-glow-one"></div>
                <div class="sb-login-card-glow sb-login-card-glow-two"></div>
                <div class="sb-login-card-line"></div>

                {{-- BRAND --}}
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

                {{-- HEADING --}}
                <div class="sb-login-head">

                    <span class="sb-login-kicker">

                        <span class="sb-login-kicker-line"></span>

                        Security verification

                        <span class="sb-login-kicker-dot"></span>

                    </span>

                    <h1 class="sb-login-title">

                        Verify your
                        <span>account.</span>

                        <span
                            class="sb-login-wave"
                            aria-hidden="true"
                        >
                            🔐
                        </span>

                    </h1>

                    <p class="sb-login-subtitle">
                        Enter the 6-digit verification code sent
                        to your email address to continue to the
                        admin panel.
                    </p>

                </div>

                {{-- ALERT --}}
                @if(session('error'))

                    <div class="sb-login-alert">

                        <span class="sb-login-alert-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">

                            <strong>
                                Verification unsuccessful
                            </strong>

                            <span>
                                {{ session('error') }}
                            </span>

                        </div>

                    </div>

                @endif

                @if($errors->any())

                    <div class="sb-login-alert">

                        <span class="sb-login-alert-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">

                            <strong>
                                Verification unsuccessful
                            </strong>

                            <span>
                                {{ $errors->first() }}
                            </span>

                        </div>

                    </div>

                @endif

                {{-- 2FA FORM --}}
                <form
                    action="{{ route('frontend.auth.admin.2fa.verify') }}"
                    method="POST"
                    class="sb-login-form"
                >

                    @csrf

                    <div class="sb-field">

                        <label
                            class="sb-field-label"
                            for="otp"
                        >
                            Verification code

                            <span>
                                6 digits
                            </span>

                        </label>

                        <div class="sb-input-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-shield-lock"></i>
                            </span>

                            <input
                                type="text"
                                id="otp_code"
                                name="otp_code"
                                class="sb-login-input"
                                placeholder="Enter 6-digit code"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                maxlength="6"
                                pattern="[0-9]{6}"
                                required
                                autofocus
                            >

                            <span class="sb-input-status">
                                <i class="bi bi-key"></i>
                            </span>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="sb-signin-btn"
                    >

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">
                                <i class="bi bi-check2-circle"></i>
                            </span>

                            <span class="sb-signin-text">
                                Verify & Continue
                            </span>

                        </span>

                        <span class="sb-signin-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </button>

                </form>

                {{-- SECURITY INFO --}}
                <div class="sb-login-benefits">

                    <div class="sb-benefit">

                        <span class="sb-benefit-icon">
                            <i class="bi bi-clock"></i>
                        </span>

                        <span>
                            Code expires in 10 minutes
                        </span>

                    </div>

                    <div class="sb-benefit">

                        <span class="sb-benefit-icon">
                            <i class="bi bi-shield-check"></i>
                        </span>

                        <span>
                            Secure admin access
                        </span>

                    </div>

                </div>

                {{-- BACK --}}
                <div class="sb-register-copy">

                    <span>
                        Not you?
                    </span>

                    <a
                        href="{{ route('frontend.auth.login') }}"
                        class="sb-register-link"
                    >
                        Return to sign in
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

                {{-- BOTTOM --}}
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