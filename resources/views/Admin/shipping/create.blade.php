@extends('Layout.Admin.master')
@section('title', 'Add Shipping')
@push('css')@vite('resources/css/admin/pages/shipping.css')@endpush
@section('content')
    <div data-vue-page="shipping-admin" data-payload="{{ json_encode([
        'view' => 'create', 'old' => ['name' => old('name', ''), 'price' => old('price', '0'), 'delivery_time' => old('delivery_time', ''), 'status' => old('status', '1'), 'description' => old('description', '')],
        'errors' => $errors->toArray(), 'urls' => ['index' => route('admin.shipping.index'), 'store' => route('admin.shipping.store')],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="shipping-page"><div class="shipping-panel">Loading form…</div></div>
    </div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
