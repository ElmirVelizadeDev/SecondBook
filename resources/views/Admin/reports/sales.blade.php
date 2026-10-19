@extends('Layout.Admin.master')
@section('title', 'Sales Report')
@push('css')@vite('resources/css/admin/pages/reports.css')@endpush
@section('content')
<div data-vue-page="reports-admin" data-mode="sales" data-index-url="{{ route('admin.reports.index') }}" data-sales-url="{{ route('admin.reports.sales') }}" data-books-url="{{ route('admin.reports.books') }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
