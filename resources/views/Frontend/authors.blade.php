@extends('Layout.Frontend.master')

@section('title', 'Authors | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/authors.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="authors-page"
        data-authors-url="{{ route('frontend.authors') }}"
        data-books-url="{{ route('frontend.books') }}"
        data-storage-url="{{ asset('storage') }}"
    ></div>
@endsection
