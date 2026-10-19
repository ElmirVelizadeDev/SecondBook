@extends('Layout.Admin.master')
@section('title','Roles and Permissions')
@push('css')@vite('resources/css/admin/pages/roles.css')@endpush
@section('content')
<div class="dashboard-section roles-page" data-vue-page="roles-admin" data-mode="index" data-index-url="{{ route('admin.roles.index') }}" data-create-url="{{ route('admin.roles.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
