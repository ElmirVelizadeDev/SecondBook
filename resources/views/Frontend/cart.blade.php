@extends('Layout.Frontend.master')

@section('title', 'Your Books | SecondBook')

@push('css')
    @vite('resources/css/pages/cart.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="cart-page"
        data-books-url="{{ route('frontend.books') }}"
        data-checkout-url="{{ route('frontend.checkout') }}"
    ></div>
@endsection
