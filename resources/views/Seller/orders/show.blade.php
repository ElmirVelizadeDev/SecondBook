@extends('Layout.Seller.master')

@section('title', 'Order Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/orders.css') }}">
@endpush

@section('content')
<div class="seller-orders-page seller-order-details-page" data-vue-page="seller-order-details-page" data-page-data="{{ base64_encode(json_encode((function () use ($order) {
    $statuses = ['pending' => ['Pending', 'bi-clock-history'], 'processing' => ['Processing', 'bi-arrow-repeat'], 'shipped' => ['Shipped', 'bi-truck'], 'delivered' => ['Delivered', 'bi-check2-circle']];
    $statusKeys = array_keys($statuses);
    $status = $order->order_status ?? 'pending';
    $statusClass = match ($status) {
        'processing', 'shipped', 'delivered', 'cancelled' => $status,
        default => 'pending',
    };
    $payment = $order->payment_status ?? 'pending';
    $paymentClass = match ($payment) {
        'paid', 'failed', 'refunded' => $payment,
        default => 'pending',
    };
    $current = array_search($status, $statusKeys);
    $current = $current === false ? 0 : $current;
    return [
        'csrfToken' => csrf_token(), 'indexUrl' => route('seller.orders.index'),
        'updateUrl' => route('seller.orders.update-status', $order), 'success' => session('success'),
        'statusError' => session('errors')?->first('order_status'),
        'statusOptions' => array_map(fn ($key, $val) => ['value' => $key, 'label' => $val[0]], array_keys($statuses), array_values($statuses)),
        'steps' => array_map(fn ($key, $val) => ['key' => $key, 'label' => $val[0], 'icon' => $val[1]], array_keys($statuses), array_values($statuses)),
        'currentIndex' => $current,
        'progress' => ($order->order_status ?? '') === 'cancelled' ? 0 : round(($current / (count($statuses) - 1)) * 100),
        'order' => [
            'number' => $order->order_number, 'status' => $order->order_status ?? 'pending',
            'statusClass' => $statusClass, 'statusLabel' => ucfirst($order->order_status ?? 'pending'),
            'paymentClass' => $paymentClass, 'paymentLabel' => ucfirst($payment),
            'bookTitle' => $order->book?->title ?? 'Deleted Book', 'coverUrl' => $order->book?->cover ? asset('storage/' . $order->book->cover) : null,
            'isbn' => $order->book?->isbn, 'bookPrice' => number_format($order->book_price, 2),
            'quantity' => $order->quantity, 'total' => number_format($order->total_price, 2),
            'customer' => $order->user?->name ?? 'Unknown Customer', 'initial' => strtoupper(substr($order->user?->name ?? 'U', 0, 1)),
            'photoUrl' => $order->user?->profile_photo ? asset('storage/' . $order->user->profile_photo) : null,
            'email' => $order->user?->email, 'userPhone' => $order->user?->phone,
            'address' => [
                ['label' => 'Full Name', 'value' => $order->full_name ?? 'Not provided'],
                ['label' => 'Phone', 'value' => $order->phone ?? 'Not provided'],
                ['label' => 'Country', 'value' => $order->country ?? 'Not provided'],
                ['label' => 'City', 'value' => $order->city ?? 'Not provided'],
                ['label' => 'Postal Code', 'value' => $order->postal_code ?? 'Not provided'],
                ['label' => 'Address', 'value' => $order->address ?? 'Not provided', 'full' => true],
            ],
            'note' => $order->note, 'orderNote' => $order->order_note,
            'paymentMethod' => ucwords(str_replace('_', ' ', $order->payment_method ?? 'Not selected')),
            'transactionId' => $order->payment?->transaction_id,
            'paidAt' => $order->payment?->paid_at?->format('M d, Y H:i'),
            'createdAt' => $order->created_at?->format('M d, Y H:i'),
        ],
    ];
})(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
