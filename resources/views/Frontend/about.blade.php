@extends('Layout.Frontend.master')

@section('title', 'About Us | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/about.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="about-page"></div>
@endsection
