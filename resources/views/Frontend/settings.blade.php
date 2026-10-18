@extends('Layout.Frontend.master')

@section('title', 'Account Settings | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/account-settings.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="account-settings-page"
        data-name="{{ $user->name }}"
        data-email="{{ $user->email }}"
        data-profile-url="{{ route('profile.edit') }}"
        data-delete-verify-url="{{ route('frontend.auth.account.delete.verify') }}"
    ></div>
@endsection
