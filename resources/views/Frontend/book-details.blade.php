@extends('Layout.Frontend.master')

@section('title', $book->title . ' | SecondBook')

@push('css')
    @vite('resources/css/pages/book-details.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="book-details-page"
        data-book-id="{{ $book->id }}"
        data-books-url="{{ route('frontend.books') }}"
        data-details-url="{{ url('/frontend/books/__BOOK__') }}"
        data-login-url="{{ route('frontend.auth.login') }}"
        data-placeholder-url="{{ asset('frontend-assets/images/book-placeholder.svg') }}"
        data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    ></div>
@endsection
