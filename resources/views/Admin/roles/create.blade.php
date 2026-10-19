@extends('Layout.Admin.master')
@section('title','Create Role')
@push('css')@vite('resources/css/admin/pages/roles.css')@endpush
@section('content')
<div class="dashboard-section roles-page" data-vue-page="roles-admin" data-mode="create" data-index-url="{{ route('admin.roles.index') }}" data-store-url="{{ route('admin.roles.store') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['permission_groups'=>$permissions->map(fn($group)=>$group->map->only(['id','name','display_name','group_name']))]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
