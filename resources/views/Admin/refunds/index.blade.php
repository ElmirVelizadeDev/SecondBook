@extends('Layout.Admin.master')
@section('title','Refunds')
@push('css')@vite('resources/css/admin/pages/refunds.css')@endpush
@section('content')
<div class="refunds-page" data-vue-page="refunds-admin" data-mode="index" data-index-url="{{ route('admin.refunds.index') }}" data-create-url="{{ route('admin.refunds.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
