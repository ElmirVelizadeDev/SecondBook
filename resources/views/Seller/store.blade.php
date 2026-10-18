@extends('Layout.Seller.master')

@section('title', 'My Store')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/store.css') }}">
@endpush

@section('content')
<div class="seller-store-page" data-vue-page="seller-store-page" data-page-data="{{ base64_encode(json_encode([
    'store' => [
        'name' => old('name', $store->name),
        'slug' => $store->slug,
        'description' => old('description', $store->description),
        'phone' => old('phone', $store->phone),
        'address' => old('address', $store->address),
        'logoUrl' => $store->logo ? asset('storage/' . $store->logo) : null,
        'status' => ucfirst($store->status),
        'active' => $store->isActive(),
        'createdAt' => $store->created_at?->format('M d, Y'),
    ],
    'errors' => $errors->messages(),
    'success' => session('success'),
    'csrfToken' => csrf_token(),
    'updateUrl' => route('seller.store.update'),
    'dashboardUrl' => route('seller.dashboard'),
    'viewStoreUrl' => route('frontend.home'),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection

