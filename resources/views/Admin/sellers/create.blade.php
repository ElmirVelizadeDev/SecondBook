@extends('Layout.Admin.master')
@section('title','Add Seller')
@push('css')@vite('resources/css/admin/pages/sellers.css')@endpush
@section('content')<div class="dashboard-section sellers-page" data-vue-page="sellers-admin" data-mode="create" data-index-url="{{ route('admin.sellers.index') }}" data-store-url="{{ route('admin.sellers.store') }}" data-csrf="{{ csrf_token() }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
