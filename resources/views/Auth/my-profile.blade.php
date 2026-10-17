@extends('Layout.Frontend.master')

@section('title', 'My Profile | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/profile.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="profile-page"
        data-mode="view"
        data-profile-url="{{ route('my.profile') }}"
        data-edit-url="{{ route('profile.edit') }}"
        data-settings-url="{{ route('frontend.account.settings') }}"
        data-orders-url="{{ route('frontend.orders') }}"
        data-wishlist-url="{{ route('frontend.wishlist') }}"
        data-order-count="{{ $orderCount }}"
        data-wishlist-count="{{ $wishlistCount }}"
        data-books-sold-count="{{ $booksSoldCount }}"
        data-reviews-count="{{ $reviewsCount }}"
        data-member-since="{{ $memberSince }}"
        data-role-label="{{ $profileRole }}"
        data-status-label="{{ $profileStatus['label'] }}"
    ></div>
@endsection
