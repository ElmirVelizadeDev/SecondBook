@extends('Layout.Admin.master')
@section('title', 'Edit Shipping')
@push('css')@vite('resources/css/admin/pages/shipping.css')@endpush
@section('content')
    <div data-vue-page="shipping-admin" data-payload="{{ json_encode([
        'view' => 'edit', 'old' => ['name' => old('name', $shipping->name), 'price' => old('price', $shipping->price), 'delivery_time' => old('delivery_time', $shipping->delivery_time), 'status' => old('status', $shipping->status ? '1' : '0'), 'description' => old('description', $shipping->description ?? '')],
        'errors' => $errors->toArray(), 'urls' => ['index' => route('admin.shipping.index'), 'update' => route('admin.shipping.update', $shipping->id)],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="shipping-page"><div class="shipping-panel">Loading form…</div>
    </div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
