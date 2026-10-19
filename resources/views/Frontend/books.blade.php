@extends('Layout.Frontend.master')

@section('title', 'Books | SecondBook')

@push('css')
    @vite('resources/css/pages/books.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="books-page"
        data-books-url="{{ route('frontend.books') }}"
        data-details-url="{{ url('/frontend/books/__BOOK__') }}"
        data-login-url="{{ route('frontend.auth.login') }}"
        data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    ></div>
@endsection
