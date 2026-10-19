@extends('Layout.Admin.master')
@section('title','Backup')
@push('css')@vite('resources/css/admin/pages/backup.css')@endpush
@section('content')
<div class="dashboard-section backup-page" data-vue-page="backup-admin" data-index-url="{{ route('admin.backup.index') }}" data-create-url="{{ route('admin.backup.create') }}" data-csrf="{{ csrf_token() }}" data-success="{{ session('success') }}" data-error="{{ session('error') }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
