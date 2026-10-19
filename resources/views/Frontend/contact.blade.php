@extends('Layout.Frontend.master')

@section('title', 'Contact Us | SecondBook')

@push('css')
    @vite('resources/css/pages/contact.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="contact-page"></div>
@endsection
