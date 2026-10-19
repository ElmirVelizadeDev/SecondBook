@extends('Layout.Seller.master')

@section('title', 'Analytics')

@push('css')
    @vite('resources/css/seller/pages/analytics.css')
@endpush

@section('content')
<div class="seller-analytics-page" data-vue-page="seller-analytics-page" data-page-data="{{ base64_encode(json_encode([
    'analyticsUrl' => route('seller.analytics.index'), 'ordersUrl' => route('seller.orders.index'),
    'range' => $range, 'totalRevenue' => number_format($totalRevenue, 2), 'totalOrders' => $totalOrders,
    'deliveredOrders' => $deliveredOrders, 'itemsSold' => $itemsSold,
    'averageOrderValue' => number_format($averageOrderValue, 2), 'revenueGrowth' => $revenueGrowth,
    'chartLabels' => $chartLabels, 'chartRevenue' => $chartRevenue,
    'statuses' => collect($statusLabels)->map(function ($label, $key) use ($statusData, $totalOrders) {
        $count = (int) ($statusData[$key] ?? 0);
        $colors = ['pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary', 'delivered' => 'success', 'cancelled' => 'danger'];
        return ['key' => $key, 'label' => $label, 'count' => $count, 'percentage' => $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0, 'color' => $colors[$key] ?? 'default'];
    })->values(),
    'inventory' => [
        ['label' => 'Total Books', 'count' => $totalBooks, 'type' => 'books', 'icon' => 'bi-book'],
        ['label' => 'Approved', 'count' => $approvedBooks, 'type' => 'approved', 'icon' => 'bi-check-circle'],
        ['label' => 'Pending', 'count' => $pendingBooks, 'type' => 'pending', 'icon' => 'bi-clock'],
        ['label' => 'Low Stock', 'count' => $lowStockBooks, 'type' => 'low', 'icon' => 'bi-exclamation-triangle'],
        ['label' => 'Out of Stock', 'count' => $outOfStockBooks, 'type' => 'out', 'icon' => 'bi-box2'],
    ],
    'topBooks' => $topBooks->map(fn ($item) => ['id' => $item->book_id, 'title' => $item->book?->title ?? 'Deleted Book', 'author' => $item->book?->author?->name, 'coverUrl' => $item->book?->cover ? asset('storage/' . $item->book->cover) : null, 'quantity' => (int) $item->total_quantity, 'revenue' => number_format($item->total_revenue, 2)])->values(),
    'recentOrders' => $recentOrders->map(function ($order) {
        $status = strtolower($order->order_status ?? 'pending');
        $class = match ($status) { 'delivered' => 'success', 'processing', 'shipped' => 'info', 'cancelled', 'canceled', 'refunded' => 'danger', default => 'warning' };
        $buyer = $order->user->name ?? trim(($order->user->first_name ?? '') . ' ' . ($order->user->last_name ?? '')) ?: 'Unknown Buyer';
        return ['id' => $order->id, 'number' => $order->order_number, 'book' => $order->book?->title ?? 'Deleted Book', 'coverUrl' => $order->book?->cover ? asset('storage/' . $order->book->cover) : null, 'buyer' => $buyer, 'statusClass' => $class, 'statusLabel' => ucfirst(str_replace('_', ' ', $status)), 'total' => number_format($order->total_price, 2), 'date' => $order->created_at->format('M d, Y'), 'time' => $order->created_at->format('H:i')];
    })->values(),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
