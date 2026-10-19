@extends('Layout.Admin.master')
@section('title','Payment Details')
@push('css')@vite('resources/css/admin/pages/orders.css')@vite('resources/css/admin/pages/payments.css')@endpush
@section('content')
<div class="orders-edit-page" data-vue-page="payments-admin" data-mode="show" data-index-url="{{ route('admin.payments.index') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['payment'=>['id'=>$payment->id,'transaction_id'=>$payment->transaction_id,'amount'=>$payment->amount,'payment_method'=>$payment->payment_method,'payment_status'=>$payment->payment_status,'paid_at'=>$payment->paid_at,'note'=>$payment->note,'created_at'=>$payment->created_at?->format('d M Y H:i'),'order_number'=>$payment->order?->order_number,'order_status'=>$payment->order?->order_status,'order_total'=>$payment->order?->total_price,'customer'=>$payment->order?->full_name ?? $payment->order?->user?->name,'email'=>$payment->order?->user?->email,'phone'=>$payment->order?->phone,'book'=>$payment->order?->book?->title,'author'=>$payment->order?->book?->author?->name],'edit_url'=>route('admin.payments.edit',$payment)]) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
