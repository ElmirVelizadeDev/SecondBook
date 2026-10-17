@extends('Layout.Frontend.master')

@section('title', 'My Wishlist | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/wishlist.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="wishlist-page"
        data-books-url="{{ route('frontend.books') }}"
        data-details-url="{{ url('/frontend/books/__BOOK__') }}"
    ></div>
@endsection
