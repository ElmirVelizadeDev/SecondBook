@extends('Layout.Admin.master')
@section('title','Role Details')
@push('css')@vite('resources/css/admin/pages/roles.css')@endpush
@section('content')
<div class="dashboard-section roles-page" data-vue-page="roles-admin" data-mode="show" data-index-url="{{ route('admin.roles.index') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['role'=>$role->only(['id','name','display_name','description','is_system','created_at']),'permissions'=>$role->permissions->map->only(['id','name','display_name','group_name']),'users'=>$role->users->map->only(['id','name','email']),'edit_url'=>route('admin.roles.edit',$role),'delete_url'=>route('admin.roles.destroy',$role)]) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
