@extends('Layout.Admin.master')
@section('title', 'Coupon Details')
@push('css')@vite('resources/css/admin/pages/coupons.css')@endpush
@section('content')
<div data-vue-page="coupons-admin" data-payload="{{ json_encode([
    'view' => 'show', 'coupon' => ['id' => $coupon->id, 'code' => $coupon->code, 'type' => $coupon->type, 'value' => $coupon->value, 'minimum_order_amount' => $coupon->minimum_order_amount, 'maximum_discount_amount' => $coupon->maximum_discount_amount, 'usage_limit' => $coupon->usage_limit, 'used_count' => $coupon->used_count, 'status' => (bool) $coupon->status, 'starts_at' => $coupon->starts_at?->format('d M Y, H:i') ?? '—', 'expires_at' => $coupon->expires_at?->format('d M Y, H:i') ?? '—', 'created_at' => $coupon->created_at?->format('d M Y, H:i') ?? '—', 'updated_at' => $coupon->updated_at?->format('d M Y, H:i') ?? '—'],
    'urls' => ['index' => route('admin.coupons.index'), 'edit' => route('admin.coupons.edit', $coupon->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
