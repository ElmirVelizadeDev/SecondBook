@extends('Layout.Frontend.master')

@section('title', 'Help Center | SecondBook')

@push('css')
    @vite('resources/css/pages/help-center.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div
        data-vue-page="help-center-page"
        data-faq-url="{{ route('frontend.faq') }}"
        data-contact-url="{{ route('frontend.contact') }}"
    ></div>
@endsection
