@extends('Layout.Frontend.master')

@section('title', 'Payment | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/payment.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="payment-page"
        data-order-id="{{ $order->id }}"
        data-orders-url="{{ route('frontend.orders') }}"
    ></div>
@endsection
