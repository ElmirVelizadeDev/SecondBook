@extends('Layout.Admin.master')
@section('title','Create Refund')
@push('css')@vite('resources/css/admin/pages/refunds.css')@endpush
@section('content')
<div class="refunds-page" data-vue-page="refunds-admin" data-mode="create" data-index-url="{{ route('admin.refunds.index') }}" data-store-url="{{ route('admin.refunds.store') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['orders'=>$orders->map(fn($order)=>['id'=>$order->id,'order_number'=>$order->order_number,'customer'=>$order->user?->name ?? 'Unknown customer','amount'=>$order->payment?->amount ?? $order->total_price])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
