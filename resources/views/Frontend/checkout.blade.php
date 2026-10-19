@extends('Layout.Frontend.master')

@section('title', 'Checkout | SecondBook')

@push('css')
    @vite('resources/css/pages/checkout.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="checkout-page"
        data-cart-url="{{ route('frontend.cart') }}"
        data-orders-url="{{ route('frontend.orders') }}"
        data-customer-name="{{ auth()->user()->name }}"
        data-customer-phone="{{ auth()->user()->phone }}"
    ></div>
@endsection
