@extends('Layout.Frontend.master')

@section('title', 'About Us | SecondBook')

@push('css')
    @vite('resources/css/pages/about.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="about-page"></div>
@endsection
