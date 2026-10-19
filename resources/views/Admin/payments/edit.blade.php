@extends('Layout.Admin.master')
@section('title','Edit Payment')
@push('css')@vite('resources/css/admin/pages/orders.css')@vite('resources/css/admin/pages/payments.css')@endpush
@section('content')
<div class="orders-edit-page" data-vue-page="payments-admin" data-mode="edit" data-index-url="{{ route('admin.payments.index') }}" data-update-url="{{ route('admin.payments.update',$payment) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['payment'=>$payment->only(['id','transaction_id','order_id','amount','payment_method','payment_status','paid_at','note']),'orders'=>$orders->map(fn($order)=>['id'=>$order->id,'order_number'=>$order->order_number,'customer'=>$order->full_name ?? $order->user?->name,'total_price'=>$order->total_price])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
