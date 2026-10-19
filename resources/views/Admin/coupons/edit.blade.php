@extends('Layout.Admin.master')
@section('title', 'Edit Coupon')
@push('css')@vite('resources/css/admin/pages/coupons.css')@endpush
@section('content')
<div data-vue-page="coupons-admin" data-payload="{{ json_encode([
    'view' => 'edit', 'coupon' => ['id' => $coupon->id, 'code' => $coupon->code, 'type' => $coupon->type, 'value' => $coupon->value, 'minimum_order_amount' => $coupon->minimum_order_amount, 'maximum_discount_amount' => $coupon->maximum_discount_amount, 'usage_limit' => $coupon->usage_limit, 'used_count' => $coupon->used_count, 'status' => (bool) $coupon->status, 'starts_at' => $coupon->starts_at?->format('Y-m-d\TH:i'), 'expires_at' => $coupon->expires_at?->format('Y-m-d\TH:i')],
    'old' => ['code' => old('code', $coupon->code), 'type' => old('type', $coupon->type), 'value' => old('value', $coupon->value), 'minimum_order_amount' => old('minimum_order_amount', $coupon->minimum_order_amount), 'maximum_discount_amount' => old('maximum_discount_amount', $coupon->maximum_discount_amount), 'usage_limit' => old('usage_limit', $coupon->usage_limit), 'starts_at' => old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')), 'expires_at' => old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')), 'status' => old('status', $coupon->status)],
    'errors' => $errors->getMessages(), 'urls' => ['index' => route('admin.coupons.index'), 'update' => route('admin.coupons.update', $coupon->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
