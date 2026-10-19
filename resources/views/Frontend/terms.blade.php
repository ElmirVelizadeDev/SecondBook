@extends('Layout.Frontend.master')

@section('title', 'Terms & Conditions | SecondBook')

@push('css')
    @vite('resources/css/pages/terms.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="terms-page"></div>
@endsection
