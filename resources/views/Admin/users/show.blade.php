@extends('Layout.Admin.master')
@section('title','User Details')
@push('css')@vite('resources/css/admin/pages/users.css')@endpush
@section('content')
<div class="dashboard-section users-page users-show-page" data-vue-page="users-admin" data-mode="show" data-index-url="{{ route('admin.users.index') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['user'=>$user->only(['id','first_name','last_name','name','username','email','phone','role','status','profile_photo','email_verified_at','created_at']),'role_name'=>$user->roles->first()?->display_name ?? ucfirst($user->role),'photo_url'=>$user->profile_photo ? asset('storage/'.$user->profile_photo) : null,'order_count'=>$user->orders_count,'order_total'=>$user->orders_sum_total_price,'edit_url'=>route('admin.users.edit',$user)]) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
