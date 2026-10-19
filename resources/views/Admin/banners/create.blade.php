@extends('Layout.Admin.master')
@section('title', 'Create Banner')
@push('css')@vite('resources/css/admin/pages/banner.css')@endpush
@section('content')
<div class="dashboard-section banners-page" data-vue-page="banners-admin" data-mode="create" data-index-url="{{ route('admin.banners.index') }}" data-store-url="{{ route('admin.banners.store') }}" data-csrf="{{ csrf_token() }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
