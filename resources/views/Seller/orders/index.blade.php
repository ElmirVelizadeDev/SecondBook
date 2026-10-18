@extends('Layout.Seller.master')

@section('title', 'Orders')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/orders.css') }}">
@endpush

@section('content')
<div class="seller-orders-page" data-vue-page="seller-orders-index-page" data-page-data="{{ base64_encode(json_encode([
    'indexUrl' => route('seller.orders.index'), 'csrfToken' => csrf_token(), 'success' => session('success'),
    'filters' => ['search' => request('search', ''), 'order_status' => request('order_status', ''), 'payment_status' => request('payment_status', '')],
    'orderStatuses' => ['pending', 'processing', 'shipped', 'delivered', 'cancelled'],
    'paymentStatuses' => ['pending', 'paid', 'failed', 'refunded'],
    'stats' => [
        ['label' => 'Total Orders', 'value' => $totalOrders, 'type' => '', 'icon' => 'bi-bag-check'],
        ['label' => 'Pending', 'value' => $pendingOrders, 'type' => 'pending', 'icon' => 'bi-clock-history'],
        ['label' => 'Processing', 'value' => $processingOrders, 'type' => 'processing', 'icon' => 'bi-arrow-repeat'],
        ['label' => 'Delivered', 'value' => $deliveredOrders, 'type' => 'delivered', 'icon' => 'bi-check-circle'],
    ],
    'orders' => $orders->getCollection()->map(function ($order) {
        $status = $order->order_status ?? 'pending';
        $payment = $order->payment_status ?? 'pending';
        $statusClass = in_array($status, ['processing', 'shipped', 'delivered', 'cancelled'], true) ? $status : 'pending';
        $paymentClass = in_array($payment, ['paid', 'failed', 'refunded'], true) ? $payment : 'pending';
        return [
            'id' => $order->id, 'number' => $order->order_number,
            'bookTitle' => \Illuminate\Support\Str::limit($order->book?->title ?? 'Deleted Book', 30),
            'coverUrl' => $order->book?->cover ? asset('storage/' . $order->book->cover) : null,
            'isbn' => $order->book?->isbn, 'customer' => $order->user?->name ?? 'Unknown Customer',
            'initial' => strtoupper(substr($order->user?->name ?? 'U', 0, 1)),
            'photoUrl' => $order->user?->profile_photo ? asset('storage/' . $order->user->profile_photo) : null,
            'email' => $order->user?->email, 'quantity' => $order->quantity,
            'total' => number_format($order->total_price, 2), 'paymentClass' => $paymentClass,
            'paymentLabel' => ucfirst($payment), 'statusClass' => $statusClass,
            'statusLabel' => ucfirst($status), 'date' => $order->created_at->format('M d, Y'),
            'time' => $order->created_at->format('H:i'), 'showUrl' => route('seller.orders.show', $order),
            'canDelete' => in_array($status, ['pending', 'cancelled'], true),
            'deleteUrl' => route('seller.orders.destroy', $order),
        ];
    })->values(),
    'pagination' => ['total' => $orders->total(), 'links' => $orders->linkCollection()->toArray()],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
