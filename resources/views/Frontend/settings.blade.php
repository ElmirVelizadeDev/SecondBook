@extends('Layout.Frontend.master')

@section('title', 'Account Settings | SecondBook')

@push('css')
    @vite('resources/css/pages/account-settings.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="account-settings-page"
        data-name="{{ $user->name }}"
        data-email="{{ $user->email }}"
        data-email-verified="{{ $user->email_verified_at ? 'true' : 'false' }}"
        data-profile-url="{{ route('profile.edit') }}"
        data-delete-verify-url="{{ route('frontend.auth.account.delete.verify') }}"
        data-profile-completion="{{ $profileCompletion }}"
        data-missing-profile-fields="{{ implode('|', $missingProfileFields->all()) }}"
        data-export-url="{{ url('/api/account/export') }}"
        data-account-deletion-blocked="{{ $accountDeletionBlocked ? 'true' : 'false' }}"
    ></div>
@endsection
