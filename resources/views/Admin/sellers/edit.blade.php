@extends('Layout.Admin.master')
@section('title','Edit Seller')
@push('css')@vite('resources/css/admin/pages/sellers.css')@endpush
@section('content')<div class="dashboard-section sellers-page seller-edit-page" data-vue-page="sellers-admin" data-mode="edit" data-index-url="{{ route('admin.sellers.index') }}" data-update-url="{{ route('admin.sellers.update',$seller) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['seller'=>$seller->only(['id','name','email','phone','status','profile_photo']),'photo_url'=>$seller->profile_photo ? asset('storage/'.$seller->profile_photo) : null]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
