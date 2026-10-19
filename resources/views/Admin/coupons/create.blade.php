@extends('Layout.Admin.master')
@section('title', 'Create Coupon')
@push('css')@vite('resources/css/admin/pages/coupons.css')@endpush
@section('content')
<div data-vue-page="coupons-admin" data-payload="{{ json_encode([
    'view' => 'create',
    'old' => ['code' => old('code', ''), 'type' => old('type', ''), 'value' => old('value', ''), 'minimum_order_amount' => old('minimum_order_amount', 0), 'maximum_discount_amount' => old('maximum_discount_amount', ''), 'usage_limit' => old('usage_limit', ''), 'starts_at' => old('starts_at', ''), 'expires_at' => old('expires_at', ''), 'status' => old('status', '1')],
    'errors' => $errors->getMessages(), 'urls' => ['index' => route('admin.coupons.index'), 'store' => route('admin.coupons.store')],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
