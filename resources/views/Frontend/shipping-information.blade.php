@extends('Layout.Frontend.master')

@section('title', 'Shipping Information | SecondBook')

@push('css')
    @vite('resources/css/pages/shipping-information.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="shipping-information-page"></div>
@endsection
