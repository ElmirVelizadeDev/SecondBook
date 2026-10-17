@extends('Layout.Frontend.master')

@section('title', 'Return Policy | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/return-policy.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="return-policy-page"></div>
@endsection
