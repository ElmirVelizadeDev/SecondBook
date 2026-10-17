@extends('Layout.Frontend.master')

@section('title', 'Cookie Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/cookies.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="cookies-page"></div>
@endsection
