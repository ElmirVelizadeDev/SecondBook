@extends('Layout.Admin.master')
@section('title','Edit Order')
@push('css')@vite('resources/css/admin/pages/orders.css')@endpush
@section('content')
<div class="orders-page" data-vue-page="orders-admin" data-mode="edit" data-index-url="{{ route('admin.orders.index') }}" data-update-url="{{ route('admin.orders.update',$order) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['order'=>$order->only(['id','order_number','user_id','book_id','book_price','quantity','payment_method','payment_status','order_status','full_name','phone','country','city','postal_code','address','note']),'users'=>$users->map->only(['id','first_name','last_name','name','email']),'books'=>$books->map->only(['id','title','price'])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
