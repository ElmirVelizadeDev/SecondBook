@extends('Layout.Admin.master')
@section('title', 'Reports')
@push('css')@vite('resources/css/admin/pages/reports.css')@endpush
@section('content')
<div data-vue-page="reports-admin" data-mode="index" data-index-url="{{ route('admin.reports.index') }}" data-sales-url="{{ route('admin.reports.sales') }}" data-books-url="{{ route('admin.reports.books') }}"></div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>@vite('resources/js/app.js')@endpush
