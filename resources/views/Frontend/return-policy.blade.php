@extends('Layout.Frontend.master')

@section('title', 'Return Policy | SecondBook')

@push('css')
    @vite('resources/css/pages/return-policy.css')
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="return-policy-page"></div>
@endsection
