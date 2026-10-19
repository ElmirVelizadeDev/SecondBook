@extends('Layout.Frontend.master')

@section('title', 'Edit Profile | SecondBook')

@push('css')
    @vite('resources/css/pages/edit-profile.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="profile-page"
        data-mode="edit"
        data-profile-url="{{ route('my.profile') }}"
        data-edit-url="{{ route('profile.edit') }}"
        data-settings-url="{{ route('frontend.account.settings') }}"
        data-orders-url="{{ route('frontend.orders') }}"
        data-wishlist-url="{{ route('frontend.wishlist') }}"
    ></div>
@endsection
