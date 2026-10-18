@extends('Layout.Seller.master')

@section('title', 'Sales')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/sales.css') }}">
@endpush

@section('content')
<div class="seller-sales-page" data-vue-page="seller-sales-page" data-page-data="{{ base64_encode(json_encode([
    'totalSales' => $totalSales,
    'totalItemsSold' => $totalItemsSold,
    'totalRevenue' => number_format($totalRevenue, 2),
    'averageSale' => number_format($averageSale, 2),
    'search' => request('search', ''),
    'indexUrl' => route('seller.sales.index'),
    'sales' => $sales->getCollection()->map(function ($sale) {
        $buyerName = $sale->user->name
            ?? trim(($sale->user->first_name ?? '') . ' ' . ($sale->user->last_name ?? ''))
            ?: 'Unknown Buyer';
        return [
            'id' => $sale->id,
            'orderNumber' => $sale->order_number,
            'bookTitle' => $sale->book->title ?? 'Deleted Book',
            'coverUrl' => ($sale->book && $sale->book->cover) ? asset('storage/' . $sale->book->cover) : null,
            'author' => $sale->book?->author?->name,
            'buyerName' => $buyerName,
            'buyerInitial' => strtoupper(substr($sale->user->name ?? $sale->user->first_name ?? 'U', 0, 1)),
            'buyerEmail' => $sale->user->email ?? 'No email',
            'quantity' => $sale->quantity,
            'bookPrice' => number_format($sale->book_price, 2),
            'totalPrice' => number_format($sale->total_price, 2),
            'date' => $sale->created_at->format('M d, Y'),
            'time' => $sale->created_at->format('H:i'),
        ];
    })->values(),
    'pagination' => [
        'total' => $sales->total(),
        'links' => $sales->linkCollection()->toArray(),
    ],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
