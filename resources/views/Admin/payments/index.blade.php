@extends('Layout.Admin.master')
@section('title','Payments')
@push('css')@vite('resources/css/admin/pages/payments.css')@endpush
@section('content')
<div class="dashboard-section payments-page" data-vue-page="payments-admin" data-mode="index" data-index-url="{{ route('admin.payments.index') }}" data-create-url="{{ route('admin.payments.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
