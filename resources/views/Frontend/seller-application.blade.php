@extends('Layout.Frontend.master')

@section('title', 'Become a Seller | SecondBook')

@push('css')
    @vite('resources/css/pages/seller-application.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="seller-application-page"></div>
@endsection
