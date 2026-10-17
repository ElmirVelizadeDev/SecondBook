@extends('Layout.Frontend.master')

@section('title', 'Notifications | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/pages/notifications.css') }}">
@endpush

@section('content')
    @vite('resources/js/app.js')
    <div data-vue-page="notifications-page"></div>
@endsection
