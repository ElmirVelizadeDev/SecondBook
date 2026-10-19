@extends('Layout.Seller.master')

@section('title', 'Dashboard')

@push('css')
    @vite('resources/css/seller/pages/dashboard.css')
@endpush

@section('content')
<div class="seller-dashboard-page" data-vue-page="seller-dashboard-page" data-page-data="{{ base64_encode(json_encode((function () use ($sellerName, $greeting, $totalBooks, $pendingBooks, $totalOrders, $totalSales, $recentOrders) {
    return [
        'sellerName' => $sellerName,
        'greeting' => $greeting,
        'totalBooks' => number_format($totalBooks),
        'pendingBooks' => number_format($pendingBooks),
        'totalOrders' => number_format($totalOrders),
        'totalSales' => number_format($totalSales, 2),
        'reviewedRate' => $totalBooks > 0 ? round((max(0, $totalBooks - $pendingBooks) / $totalBooks) * 100) : 0,
        'averageOrder' => number_format($totalOrders > 0 ? $totalSales / $totalOrders : 0, 2),
        'createBookUrl' => route('seller.books.create'),
        'booksUrl' => route('seller.books.index'),
        'pendingBooksUrl' => route('seller.books.index', ['status' => 'pending']),
        'ordersUrl' => route('seller.orders.index'),
        'salesUrl' => route('seller.sales.index'),
        'messagesUrl' => route('seller.messages.index'),
        'storeUrl' => route('seller.store'),
        'recentOrders' => $recentOrders->map(function ($order) {
            $status = $order->order_status ?? 'pending';
            return [
                'id' => $order->id,
                'number' => $order->order_number,
                'url' => route('seller.orders.show', $order),
                'customer' => $order->user?->name ?? 'Unknown',
                'initial' => strtoupper(substr($order->user?->name ?: 'U', 0, 1)),
                'book' => $order->book?->title ?? 'Deleted Book',
                'quantity' => $order->quantity,
                'total' => number_format($order->total_price, 2),
                'status' => $status,
                'statusLabel' => ucwords(str_replace('_', ' ', $status)),
            ];
        })->values(),
    ];
})(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
