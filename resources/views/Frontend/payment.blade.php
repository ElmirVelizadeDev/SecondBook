@extends('Layout.Frontend.master')

@section('title', 'Payment | SecondBook')

@push('css')
    @vite('resources/css/pages/payment.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="payment-page"
        data-order-id="{{ $order->id }}"
        data-orders-url="{{ route('frontend.orders') }}"
    ></div>
@endsection
