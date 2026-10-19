@extends('Layout.Frontend.master')

@section('title', $store->name . ' | SecondBook')

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/pages/store.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="store-page"
        data-store-slug="{{ $store->slug }}"
        data-books-url="{{ route('frontend.books') }}"
        data-details-url="{{ url('/frontend/books/__BOOK__') }}"
        data-login-url="{{ route('frontend.auth.login') }}"
        data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    ></div>
@endsection
