@extends('Layout.Admin.master')
@section('title','Orders')
@push('css')@vite('resources/css/admin/pages/orders.css')@endpush
@section('content')
<div class="dashboard-section orders-page" data-vue-page="orders-admin" data-mode="index" data-index-url="{{ route('admin.orders.index') }}" data-create-url="{{ route('admin.orders.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
