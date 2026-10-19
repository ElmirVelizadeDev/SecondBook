@extends('Layout.Admin.master')
@section('title','Sellers')
@push('css')@vite('resources/css/admin/pages/sellers.css')@endpush
@section('content')<div class="dashboard-section sellers-page" data-vue-page="sellers-admin" data-mode="index" data-index-url="{{ route('admin.sellers.index') }}" data-create-url="{{ route('admin.sellers.create') }}" data-csrf="{{ csrf_token() }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
