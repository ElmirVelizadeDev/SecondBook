@extends('Layout.Seller.master')

@section('title', 'Sales')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/sales.css') }}">
@endpush

@section('content')

<div class="seller-sales-page">

    {{-- =====================================================
         HEADER
         ===================================================== --}}
    <div class="seller-page-heading">

        <div>
            <h1>Sales</h1>
            <p>Track your completed sales and revenue.</p>
        </div>

    </div>


    {{-- =====================================================
         STATISTICS
         ===================================================== --}}
    <div class="seller-sales-stats">

        {{-- Total Sales --}}
        <div class="seller-sales-stat-card">
            <div class="seller-sales-stat-icon sales">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="seller-sales-stat-content">
                <span>Total Sales</span>
                <h3>{{ $totalSales }}</h3>
            </div>
        </div>

        {{-- Items Sold --}}
        <div class="seller-sales-stat-card">
            <div class="seller-sales-stat-icon items">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="seller-sales-stat-content">
                <span>Items Sold</span>
                <h3>{{ $totalItemsSold }}</h3>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="seller-sales-stat-card">
            <div class="seller-sales-stat-icon revenue">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="seller-sales-stat-content">
                <span>Total Revenue</span>
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>

        {{-- Average Sale --}}
        <div class="seller-sales-stat-card">
            <div class="seller-sales-stat-icon average">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="seller-sales-stat-content">
                <span>Average Sale</span>
                <h3>${{ number_format($averageSale, 2) }}</h3>
            </div>
        </div>

    </div>


    {{-- =====================================================
         SALES PANEL
         ===================================================== --}}
    <div class="seller-sales-panel">

        {{-- Panel Header --}}
        <div class="seller-sales-panel-header">

            <div class="seller-sales-panel-title">

                <div class="seller-sales-panel-icon">
                    <i class="bi bi-graph-up"></i>
                </div>

                <div>
                    <h5>Sales History</h5>
                    <p>View your completed orders and earnings.</p>
                </div>

            </div>

            <div class="seller-sales-count">
                <i class="bi bi-receipt"></i>
                {{ $sales->total() }} {{ \Illuminate\Support\Str::plural('sale', $sales->total()) }}
            </div>

        </div>


        {{-- Search --}}
        <div class="seller-sales-filter">

            <form action="{{ route('seller.sales.index') }}" method="GET">

                <div class="seller-sales-search">

                    <div class="seller-sales-search-input">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search sales..."
                        >
                    </div>

                    <button type="submit" class="seller-sales-search-button">
                        <i class="bi bi-search"></i>
                        <span>Search</span>
                    </button>

                    @if(request()->filled('search'))
                        <a href="{{ route('seller.sales.index') }}" class="seller-sales-reset-button">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Reset</span>
                        </a>
                    @endif

                </div>

            </form>

        </div>


        {{-- Sales Table --}}
        @if($sales->count())

            <div class="table-responsive">

                <table class="seller-sales-table">

                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Book</th>
                            <th>Buyer</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($sales as $sale)

                            @php
                                $buyerName = $sale->user->name
                                    ?? trim(($sale->user->first_name ?? '') . ' ' . ($sale->user->last_name ?? ''))
                                    ?: 'Unknown Buyer';

                                $buyerInitial = strtoupper(substr(
                                    $sale->user->name ?? $sale->user->first_name ?? 'U',
                                    0,
                                    1
                                ));
                            @endphp

                            <tr>

                                {{-- Order --}}
                                <td>
                                    <div class="seller-sale-order">
                                        <strong>#{{ $sale->order_number }}</strong>
                                        <span><i></i>Delivered</span>
                                    </div>
                                </td>

                                {{-- Book --}}
                                <td>
                                    <div class="seller-sale-book">

                                        <div class="seller-sale-book-cover">
                                            @if($sale->book && $sale->book->cover)
                                                <img src="{{ asset('storage/' . $sale->book->cover) }}" alt="{{ $sale->book->title }}">
                                            @else
                                                <i class="bi bi-book"></i>
                                            @endif
                                        </div>

                                        <div class="seller-sale-book-info">
                                            <strong>{{ $sale->book->title ?? 'Deleted Book' }}</strong>
                                            @if($sale->book && $sale->book->author)
                                                <span>{{ $sale->book->author->name }}</span>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- Buyer --}}
                                <td>
                                    <div class="seller-sale-buyer">

                                        <div class="seller-sale-buyer-avatar">
                                            {{ $buyerInitial }}
                                        </div>

                                        <div>
                                            <strong>{{ $buyerName }}</strong>
                                            <span>{{ $sale->user->email ?? 'No email' }}</span>
                                        </div>

                                    </div>
                                </td>

                                {{-- Quantity --}}
                                <td>
                                    <span class="seller-sale-quantity">{{ $sale->quantity }}</span>
                                </td>

                                {{-- Price --}}
                                <td>
                                    <span class="seller-sale-price">
                                        ${{ number_format($sale->book_price, 2) }}
                                    </span>
                                </td>

                                {{-- Total --}}
                                <td>
                                    <strong class="seller-sale-total">
                                        ${{ number_format($sale->total_price, 2) }}
                                    </strong>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <div class="seller-sale-date">
                                        <strong>{{ $sale->created_at->format('M d, Y') }}</strong>
                                        <span>{{ $sale->created_at->format('H:i') }}</span>
                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @if($sales->hasPages())
                <div class="seller-sales-pagination">
                    {{ $sales->links() }}
                </div>
            @endif

        @else

            {{-- Empty State --}}
            <div class="seller-sales-empty">

                <div class="seller-sales-empty-icon">
                    <i class="bi bi-graph-up"></i>
                </div>

                <h5>No Sales Found</h5>

                <p>You don't have any completed sales matching your search.</p>

                @if(request()->filled('search'))
                    <a href="{{ route('seller.sales.index') }}" class="seller-sales-empty-button">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Clear Search</span>
                    </a>
                @endif

            </div>

        @endif

    </div>

</div>

@endsection