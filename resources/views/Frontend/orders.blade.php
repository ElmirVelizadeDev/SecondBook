@extends('Layout.Frontend.master')

@section('title', 'My Orders | SecondBook')

@push('css')
    @vite('resources/css/pages/orders.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="orders-page"
        data-details-url="{{ url('/frontend/orders/__ORDER__') }}"
        data-books-url="{{ route('frontend.books') }}"
    ></div>
@endsection
