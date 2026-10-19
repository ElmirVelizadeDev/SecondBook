@extends('Layout.Frontend.master')

@section('title', 'Privacy Policy | SecondBook')

@push('css')
    @vite('resources/css/pages/privacy-policy.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="privacy-policy-page"></div>
@endsection
