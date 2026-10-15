@extends('Layout.Frontend.master')

@section('title', 'Contact Us | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/contact.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="contact-page"></div>
@endsection
