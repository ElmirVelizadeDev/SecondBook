@extends('Layout.Admin.master')
@section('title','Refund Details')
@push('css')@vite('resources/css/admin/pages/refunds.css')@endpush
@section('content')
<div class="refunds-page" data-vue-page="refunds-admin" data-mode="show" data-index-url="{{ route('admin.refunds.index') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['refund'=>$refund->only(['id','refund_number','order_id','amount','reason','note','status','requested_at','processed_at']),'order'=>$refund->order?->only(['order_number','order_status','payment_status','total_price','quantity']),'customer'=>$refund->user?->only(['name','email']),'payment'=>$refund->payment?->only(['payment_method','payment_status','amount']),'processor'=>$refund->processor?->name,'status_url'=>route('admin.refunds.status',$refund),'delete_url'=>route('admin.refunds.destroy',$refund),'edit_url'=>$refund->status!=='processed'?route('admin.refunds.edit',$refund):null]) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
