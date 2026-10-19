@extends('Layout.Admin.master')
@section('title','Create Payment')
@push('css')@vite('resources/css/admin/pages/orders.css')@vite('resources/css/admin/pages/payments.css')@endpush
@section('content')
<div class="orders-edit-page" data-vue-page="payments-admin" data-mode="create" data-index-url="{{ route('admin.payments.index') }}" data-store-url="{{ route('admin.payments.store') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['orders'=>$orders->map(fn($order)=>['id'=>$order->id,'order_number'=>$order->order_number,'customer'=>$order->full_name ?? $order->user?->name,'total_price'=>$order->total_price])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
