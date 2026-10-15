@extends('Layout.Frontend.master')

@section('title', 'FAQ | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/faq.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="faq-page" data-contact-url="{{ route('frontend.contact') }}"></div>
@endsection
