@extends('Layout.Admin.master')
@section('title', 'Coupons')
@push('css')@vite('resources/css/admin/pages/coupons.css')@endpush
@section('content')
<div data-vue-page="coupons-admin" data-payload="{{ json_encode([
    'view' => 'index',
    'coupons' => $coupons->getCollection()->map(fn ($coupon) => [
        'id' => $coupon->id, 'code' => $coupon->code, 'type' => $coupon->type, 'value' => $coupon->value,
        'minimum_order_amount' => $coupon->minimum_order_amount, 'maximum_discount_amount' => $coupon->maximum_discount_amount,
        'usage_limit' => $coupon->usage_limit, 'used_count' => $coupon->used_count, 'status' => (bool) $coupon->status,
        'starts_at' => $coupon->starts_at?->toIso8601String(), 'expires_at' => $coupon->expires_at?->toIso8601String(),
        'starts_label' => $coupon->starts_at?->format('d M Y'), 'expires_label' => $coupon->expires_at?->format('d M Y'),
        'show_url' => route('admin.coupons.show', $coupon->id), 'edit_url' => route('admin.coupons.edit', $coupon->id),
        'toggle_url' => route('admin.coupons.toggle-status', $coupon->id), 'delete_url' => route('admin.coupons.destroy', $coupon->id),
    ])->values(),
    'stats' => ['total' => $totalCoupons, 'active' => $activeCoupons, 'expired' => $expiredCoupons, 'used' => $totalUsed],
    'filters' => ['search' => request('search', ''), 'type' => request('type', ''), 'status' => request('status', '')],
    'pagination' => ['total' => $coupons->total(), 'first' => $coupons->firstItem(), 'last' => $coupons->lastItem(), 'current' => $coupons->currentPage(), 'last_page' => $coupons->lastPage(), 'pages' => $coupons->getUrlRange(1, $coupons->lastPage()), 'previous' => $coupons->previousPageUrl(), 'next' => $coupons->nextPageUrl()],
    'urls' => ['index' => route('admin.coupons.index'), 'create' => route('admin.coupons.create')], 'success' => session('success'),
]) }}"></div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>@vite('resources/js/app.js')@endpush
