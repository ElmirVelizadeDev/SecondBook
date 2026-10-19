@extends('Layout.Admin.master')
@section('title','Create User')
@push('css')@vite('resources/css/admin/pages/users.css')@endpush
@section('content')
<div class="dashboard-section users-page" data-vue-page="users-admin" data-mode="create" data-index-url="{{ route('admin.users.index') }}" data-store-url="{{ route('admin.users.store') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['roles'=>$roles->map->only(['name','display_name'])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
