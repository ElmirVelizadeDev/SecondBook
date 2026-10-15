@extends('Layout.Frontend.master')

@section('title', 'Terms & Conditions | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/terms.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="terms-page"></div>
@endsection
