@extends('Layout.Admin.master')
@section('title','Edit Role')
@push('css')@vite('resources/css/admin/pages/roles.css')@endpush
@section('content')
<div class="dashboard-section roles-page" data-vue-page="roles-admin" data-mode="edit" data-index-url="{{ route('admin.roles.index') }}" data-update-url="{{ route('admin.roles.update',$role) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['role'=>$role->only(['id','name','display_name','description','is_system']),'permission_groups'=>$permissions->map(fn($group)=>$group->map->only(['id','name','display_name','group_name'])),'selected_permissions'=>$selectedPermissions]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
