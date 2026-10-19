@extends('Layout.Admin.master')
@section('title', 'Banners')
@push('css')@vite('resources/css/admin/pages/banner.css')@endpush
@section('content')
<div class="dashboard-section banners-page" data-vue-page="banners-admin" data-mode="index" data-index-url="{{ route('admin.banners.index') }}" data-create-url="{{ route('admin.banners.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
