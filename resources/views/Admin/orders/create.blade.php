@extends('Layout.Admin.master')
@section('title','Create Order')
@push('css')@vite('resources/css/admin/pages/orders.css')@endpush
@section('content')
<div class="orders-page" data-vue-page="orders-admin" data-mode="create" data-index-url="{{ route('admin.orders.index') }}" data-store-url="{{ route('admin.orders.store') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['users'=>$users->map->only(['id','first_name','last_name','name','email']),'books'=>$books->map->only(['id','title','price'])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
