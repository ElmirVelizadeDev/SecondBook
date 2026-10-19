@extends('Layout.Admin.master')
@section('title', 'Shipping')
@push('css')@vite('resources/css/admin/pages/shipping.css')@endpush
@section('content')
    <div data-vue-page="shipping-admin" data-payload="{{ json_encode([
        'view' => 'index',
        'shippings' => $shippings->getCollection()->map(fn ($shipping) => ['id' => $shipping->id, 'name' => $shipping->name, 'description' => $shipping->description, 'price' => $shipping->price, 'delivery_time' => $shipping->delivery_time, 'status' => $shipping->status])->values(),
        'pagination' => ['from' => $shippings->firstItem(), 'to' => $shippings->lastItem(), 'total' => $shippings->total(), 'current_page' => $shippings->currentPage(), 'last_page' => $shippings->lastPage(), 'pages' => collect($shippings->getUrlRange(max(1, $shippings->currentPage() - 2), min($shippings->lastPage(), $shippings->currentPage() + 2)))->map(fn ($url, $page) => ['page' => $page, 'url' => $url])->values(), 'previous_url' => $shippings->previousPageUrl(), 'next_url' => $shippings->nextPageUrl()],
        'stats' => ['total' => $totalShippings, 'active' => $activeShippings, 'inactive' => $inactiveShippings, 'free' => $freeShippings],
        'search' => request('search', ''), 'statusFilter' => request('status', ''),
        'success' => session('success'), 'error' => session('error'),
        'urls' => ['index' => route('admin.shipping.index'), 'create' => route('admin.shipping.create'), 'editPattern' => route('admin.shipping.edit', ['shipping' => '__id__']), 'deletePattern' => route('admin.shipping.destroy', ['shipping' => '__id__'])],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="shipping-page"><div class="shipping-panel">Loading shipping methods…</div></div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
@endpush
