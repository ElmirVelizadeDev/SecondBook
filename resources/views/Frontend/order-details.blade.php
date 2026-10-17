@extends('Layout.Frontend.master')

@section('title', 'Order Details | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/order-details.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="order-details-page"
        data-order-id="{{ $order->id }}"
        data-orders-url="{{ route('frontend.orders') }}"
        data-tracking-url="{{ url('/frontend/order-tracking/__ORDER__') }}"
        data-review-url="{{ url('/frontend/orders/__ORDER__/review') }}"
    ></div>
@endsection
