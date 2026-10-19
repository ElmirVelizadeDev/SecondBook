@extends('Layout.Admin.master')
@section('title','Order Details')
@push('css')@vite('resources/css/admin/pages/orders.css')@endpush
@section('content')
<div class="orders-page" data-vue-page="orders-admin" data-mode="show" data-index-url="{{ route('admin.orders.index') }}" data-payload="{{ json_encode(['order'=>$order->only(['id','order_number','user_id','book_id','book_price','quantity','total_price','payment_method','payment_status','order_status','full_name','phone','country','city','postal_code','address','note','created_at']),'user'=>$order->user?->only(['name','email','phone']),'book'=>$order->book?->only(['title','isbn']),'edit_url'=>route('admin.orders.edit',$order)]) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
