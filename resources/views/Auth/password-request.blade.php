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

                {{-- Validation errors --}}
                @if ($errors->any())

                    <div class="sb-login-alert">

                        <span class="sb-login-alert-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">

                            <strong>Please check the following</strong>

                            <ul>

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                @endif

                {{-- Password reset form --}}
                <form
                    action="{{ route('frontend.auth.password.send.otp') }}"
                    method="POST"
                    class="sb-login-form"
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

@endsection