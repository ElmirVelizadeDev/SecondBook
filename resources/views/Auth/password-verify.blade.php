@extends('Layout.Frontend.master')

@section('title', 'Verify Code | SecondBook')
@section('hideNavbar', '1')
@section('hideFooter', '1')
@section('hideScripts', '1')

@push('css')
    @vite('resources/css/auth/auth-pages.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="auth-page"
        data-mode="password-verify"
        data-email="{{ session('sensitive_action_email', session('reset_email', '')) }}"
        data-home-url="{{ route('frontend.home') }}"
        data-login-url="{{ route('frontend.auth.login') }}"
        data-register-url="{{ route('frontend.auth.register') }}"
        data-password-request-url="{{ route('frontend.auth.password.request') }}"
        data-password-verify-url="{{ route('frontend.auth.password.verify') }}"
        data-email-verify-url="{{ route('frontend.auth.email.verify') }}"
        data-account-delete-verify-url="{{ route('frontend.auth.account.delete.verify') }}"
        data-terms-url="{{ route('frontend.auth.terms') }}"
    ></div>
@endsection
