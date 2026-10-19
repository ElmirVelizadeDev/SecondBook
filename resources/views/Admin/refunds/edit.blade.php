@extends('Layout.Admin.master')
@section('title','Edit Refund')
@push('css')@vite('resources/css/admin/pages/refunds.css')@endpush
@section('content')
<div class="refunds-page" data-vue-page="refunds-admin" data-mode="edit" data-index-url="{{ route('admin.refunds.index') }}" data-update-url="{{ route('admin.refunds.update',$refund) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['refund'=>$refund->only(['id','refund_number','order_id','amount','reason','note','status','requested_at','processed_at'])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
