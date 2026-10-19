@extends('Layout.Admin.master')
@section('title','Users')
@push('css')@vite('resources/css/admin/pages/users.css')@endpush
@section('content')
<div class="dashboard-section users-page" data-vue-page="users-admin" data-mode="index" data-index-url="{{ route('admin.users.index') }}" data-create-url="{{ route('admin.users.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
