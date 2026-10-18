@extends('Layout.Seller.master')

@section('title', 'Store Settings')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/settings.css') }}">
@endpush

@section('content')
<div class="seller-settings-page" data-vue-page="seller-settings-page" data-page-data="{{ base64_encode(json_encode([
    'store' => [
        'accept_orders' => old('accept_orders', $store->accept_orders),
        'auto_approve_orders' => old('auto_approve_orders', $store->auto_approve_orders),
        'processing_time' => old('processing_time', $store->processing_time),
        'minimum_order_amount' => old('minimum_order_amount', $store->minimum_order_amount),
        'order_note' => old('order_note', $store->order_note),
    ],
    'errors' => $errors->messages(),
    'success' => session('success'),
    'csrfToken' => csrf_token(),
    'updateUrl' => route('seller.settings.update'),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
