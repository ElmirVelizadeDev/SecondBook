@extends('Layout.Frontend.master')

@section('title', 'Track Order | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/order-tracking.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="order-tracking-page"
        data-order-id="{{ $order->id }}"
        data-orders-url="{{ route('frontend.orders') }}"
    ></div>
@endsection
