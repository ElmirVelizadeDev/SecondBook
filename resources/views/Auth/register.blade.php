@extends('Layout.Frontend.master')

@section('title', 'Register | SecondBook')

@section('hideNavbar', '1')
@section('hideFooter', '1')
@section('hideScripts', '1')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/auth-register.css') }}">
@endpush

@section('content')

<div class="auth-page-bg auth-page-bg-login sb-register-page">

    <div class="container auth-wrapper py-0">

        <div class="auth-grid auth-grid-login w-100">

            <section class="auth-card auth-card-login auth-card-login-pro sb-register-premium">

                {{-- Decorative elements --}}
                <span class="sb-register-orb sb-register-orb-one"></span>
                <span class="sb-register-orb sb-register-orb-two"></span>
                <span class="sb-register-card-line"></span>

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
                        JOIN SECONDBOOK
                        <span class="sb-login-kicker-dot"></span>
                    </span>

                    <h1 class="sb-login-title">
                        Create
                        <span>your account.</span>
                        <span
                            class="sb-login-wave"
                            aria-hidden="true"
                        >✨</span>
                    </h1>

                    <p class="sb-login-subtitle">
                        Create your SecondBook account and start your reading journey.
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

                {{-- Register form --}}
                <form
                    action="{{ route('frontend.auth.register.store') }}"
                    method="POST"
                    class="sb-login-form"
                >

                    @csrf

                    {{-- First Name / Last Name --}}
                    <div class="row sb-register-name-row">

                        <div class="col-md-6 sb-field">

                            <label
                                for="firstName"
                                class="sb-field-label"
                            >
                                First Name
                            </label>

                            <div class="sb-input-group">

                                <span class="sb-input-icon">
                                    <i class="bi bi-person"></i>
                                </span>

                                <input
                                    type="text"
                                    id="firstName"
                                    name="first_name"
                                    class="form-control sb-login-input"
                                    placeholder="First name"
                                    value="{{ old('first_name') }}"
                                    autocomplete="given-name"
                                >

                            </div>

                        </div>

                        <div class="col-md-6 sb-field">

                            <label
                                for="lastName"
                                class="sb-field-label"
                            >
                                Last Name
                            </label>

                            <div class="sb-input-group">

                                <span class="sb-input-icon">
                                    <i class="bi bi-person"></i>
                                </span>

                                <input
                                    type="text"
                                    id="lastName"
                                    name="last_name"
                                    class="form-control sb-login-input"
                                    placeholder="Last name"
                                    value="{{ old('last_name') }}"
                                    autocomplete="family-name"
                                >

                            </div>

                        </div>

                    </div>

                    {{-- Username --}}
                    <div class="sb-field">

                        <label
                            for="username"
                            class="sb-field-label"
                        >
                            Username
                        </label>

                        <div class="sb-input-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-at"></i>
                            </span>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-control sb-login-input"
                                placeholder="Choose a username"
                                value="{{ old('username') }}"
                                autocomplete="username"
                            >

                        </div>

                    </div>

                    {{-- Email --}}
                    <div class="sb-field">

                        <label
                            for="registerEmail"
                            class="sb-field-label"
                        >
                            Email Address
                        </label>

                        <div class="sb-input-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                id="registerEmail"
                                name="email"
                                class="form-control sb-login-input"
                                placeholder="name@example.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                            >

                        </div>

                    </div>

                    {{-- Password --}}
                    <div class="sb-field">

                        <label
                            for="registerPassword"
                            class="sb-field-label"
                        >
                            Password
                        </label>

                        <div class="sb-input-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                id="registerPassword"
                                name="password"
                                class="form-control sb-login-input"
                                placeholder="Minimum 8 characters"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleRegisterPassword"
                                aria-label="Show password"
                            >
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    {{-- Confirm Password --}}
                    <div class="sb-field">

                        <label
                            for="registerPasswordConfirm"
                            class="sb-field-label"
                        >
                            Confirm Password
                        </label>

                        <div class="sb-input-group">

                            <span class="sb-input-icon">
                                <i class="bi bi-shield-check"></i>
                            </span>

                            <input
                                type="password"
                                id="registerPasswordConfirm"
                                name="password_confirmation"
                                class="form-control sb-login-input"
                                placeholder="Repeat password"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="sb-pass-toggle"
                                id="toggleRegisterPasswordConfirm"
                                aria-label="Show password"
                            >
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    {{-- Terms --}}
                    <div class="sb-register-options">

                        <label
                            for="termsCheck"
                            class="sb-register-terms"
                        >

                            <input
                                type="checkbox"
                                id="termsCheck"
                                name="terms"
                                value="1"
                                {{ old('terms') ? 'checked' : '' }}
                            >

                            <span class="sb-custom-checkbox">
                                <i class="bi bi-check2"></i>
                            </span>

                            <span class="sb-register-terms-text">
                                I agree to the
                                <a
                                    href="{{ route('frontend.auth.terms') }}"
                                    target="_blank"
                                >
                                    Terms and Conditions
                                </a>
                            </span>

                        </label>

                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="sb-signin-btn sb-register-submit"
                    >

                        <span class="sb-signin-content">

                            <span class="sb-signin-icon">
                                <i class="bi bi-person-plus"></i>
                            </span>

                            <span class="sb-signin-text">
                                Create Account
                            </span>

                        </span>

                        <span class="sb-signin-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </button>

                </form>

                {{-- Benefits --}}
                <div class="sb-register-benefits">

                    <div class="sb-register-benefit">

                        <span class="sb-register-benefit-icon">
                            <i class="bi bi-heart"></i>
                        </span>

                        <div>
                            <strong>Save</strong>
                            <small>Favorite books</small>
                        </div>

                    </div>

                    <div class="sb-register-benefit">

                        <span class="sb-register-benefit-icon">
                            <i class="bi bi-book"></i>
                        </span>

                        <div>
                            <strong>Discover</strong>
                            <small>New listings</small>
                        </div>

                    </div>

                    <div class="sb-register-benefit">

                        <span class="sb-register-benefit-icon">
                            <i class="bi bi-people"></i>
                        </span>

                        <div>
                            <strong>Connect</strong>
                            <small>Fellow readers</small>
                        </div>

                    </div>

                </div>

                {{-- Login --}}
                <div class="sb-register-login">

                    <span>Already have an account?</span>

                    <a
                        href="{{ route('frontend.auth.login') }}"
                        class="sb-register-link"
                    >
                        Sign In
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

                {{-- Bottom mark --}}
                <div class="sb-login-bottom-mark">

                    <span></span>

                    <i class="bi bi-book-half"></i>

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

    function setupPasswordToggle(buttonId, inputId) {

        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        if (!button || !input) {
            return;
        }

        button.addEventListener('click', function () {

            const isPassword =
                input.getAttribute('type') === 'password';

            input.setAttribute(
                'type',
                isPassword ? 'text' : 'password'
            );

            button.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';

            button.setAttribute(
                'aria-label',
                isPassword
                    ? 'Hide password'
                    : 'Show password'
            );

        });

    }

    setupPasswordToggle(
        'toggleRegisterPassword',
        'registerPassword'
    );

    setupPasswordToggle(
        'toggleRegisterPasswordConfirm',
        'registerPasswordConfirm'
    );

});
</script>

@endpush