@extends('Layout.Frontend.master')

@section('title', 'FAQ | SecondBook')

@push('css')
    @vite('resources/css/pages/faq.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="faq-page" data-contact-url="{{ route('frontend.contact') }}"></div>
@endsection
