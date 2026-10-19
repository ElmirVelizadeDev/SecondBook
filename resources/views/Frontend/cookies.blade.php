@extends('Layout.Frontend.master')

@section('title', 'Cookie Policy | SecondBook')

@push('css')
    @vite('resources/css/pages/cookies.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="cookies-page"></div>
@endsection
