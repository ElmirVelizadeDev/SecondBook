@extends('Layout.Frontend.master')

@section('title', 'Categories | SecondBook')

@push('css')
    @vite('resources/css/pages/categories.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="categories-page"
        data-books-url="{{ route('frontend.books') }}"
        data-storage-url="{{ asset('storage') }}"
    ></div>
@endsection
