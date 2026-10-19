@extends('Layout.Admin.master')
@section('title','Edit User')
@push('css')@vite('resources/css/admin/pages/users.css')@endpush
@section('content')
<div class="dashboard-section users-page" data-vue-page="users-admin" data-mode="edit" data-index-url="{{ route('admin.users.index') }}" data-update-url="{{ route('admin.users.update',$user) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['user'=>$user->only(['id','first_name','last_name','name','username','email','phone','role','status','profile_photo']),'role_name'=>$user->roles->first()?->name ?? $user->role,'roles'=>$roles->map->only(['name','display_name']),'photo_url'=>$user->profile_photo ? asset('storage/'.$user->profile_photo) : null,'is_current'=>auth()->id()===$user->id]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
