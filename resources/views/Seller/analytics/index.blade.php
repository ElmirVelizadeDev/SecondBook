@extends('Layout.Seller.master')

@section('title', 'Analytics')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/analytics.css') }}">
@endpush

@section('content')

<div class="seller-analytics-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="seller-page-heading">

        <div>
            <h1>Analytics</h1>
            <p>Track your store performance, sales and inventory.</p>
        </div>

        <form
            action="{{ route('seller.analytics.index') }}"
            method="GET"
            class="seller-analytics-range-form"
        >

            <i class="bi bi-calendar3"></i>

            <select name="range" onchange="this.form.submit()">

                <option value="7" {{ $range == '7' ? 'selected' : '' }}>
                    Last 7 Days
                </option>

                <option value="30" {{ $range == '30' ? 'selected' : '' }}>
                    Last 30 Days
                </option>

                <option value="90" {{ $range == '90' ? 'selected' : '' }}>
                    Last 90 Days
                </option>

                <option value="365" {{ $range == '365' ? 'selected' : '' }}>
                    Last Year
                </option>

            </select>

        </form>

    </div>


    {{-- =====================================================
         MAIN STATISTICS
    ====================================================== --}}
    <div class="seller-analytics-stats">

        {{-- Revenue --}}
        <div class="seller-analytics-stat-card">

            <div class="seller-analytics-stat-icon revenue">
                <i class="bi bi-currency-dollar"></i>
            </div>

            <div class="seller-analytics-stat-content">

                <span>Total Revenue</span>

                <h3>
                    ${{ number_format($totalRevenue, 2) }}
                </h3>

                <small class="{{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="bi {{ $revenueGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                    {{ number_format(abs($revenueGrowth), 1) }}%
                    vs previous period
                </small>

            </div>

        </div>


        {{-- Orders --}}
        <div class="seller-analytics-stat-card">

            <div class="seller-analytics-stat-icon orders">
                <i class="bi bi-bag-check"></i>
            </div>

            <div class="seller-analytics-stat-content">

                <span>Total Orders</span>

                <h3>
                    {{ $totalOrders }}
                </h3>

                <small>
                    {{ $deliveredOrders }} delivered
                </small>

            </div>

        </div>


        {{-- Items --}}
        <div class="seller-analytics-stat-card">

            <div class="seller-analytics-stat-icon items">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="seller-analytics-stat-content">

                <span>Items Sold</span>

                <h3>
                    {{ $itemsSold }}
                </h3>

                <small>
                    Completed orders
                </small>

            </div>

        </div>


        {{-- Average --}}
        <div class="seller-analytics-stat-card">

            <div class="seller-analytics-stat-icon average">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <div class="seller-analytics-stat-content">

                <span>Average Order</span>

                <h3>
                    ${{ number_format($averageOrderValue, 2) }}
                </h3>

                <small>
                    Per delivered order
                </small>

            </div>

        </div>

    </div>


    {{-- =====================================================
         REVENUE CHART
    ====================================================== --}}
    <div class="seller-analytics-panel seller-analytics-revenue-panel">

        <div class="seller-analytics-panel-header">

            <div class="seller-analytics-panel-title">

                <div class="seller-analytics-panel-icon">
                    <i class="bi bi-graph-up"></i>
                </div>

                <div>
                    <h5>Revenue Overview</h5>
                    <p>Your daily revenue and sales performance.</p>
                </div>

            </div>

            <div class="seller-analytics-period">
                Last {{ $range }} days
            </div>

        </div>


        <div class="seller-analytics-chart-area">

            <div class="seller-analytics-chart-summary">

                <span>Total Revenue</span>

                <strong>
                    ${{ number_format($totalRevenue, 2) }}
                </strong>

            </div>

            <div class="seller-analytics-chart">

                <canvas id="sellerRevenueChart"></canvas>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SECONDARY ANALYTICS
    ====================================================== --}}
    <div class="seller-analytics-grid">


        {{-- Order Status --}}
        <div class="seller-analytics-panel">

            <div class="seller-analytics-panel-header">

                <div class="seller-analytics-panel-title">

                    <div class="seller-analytics-panel-icon small">
                        <i class="bi bi-pie-chart"></i>
                    </div>

                    <div>
                        <h5>Order Status</h5>
                        <p>Current order distribution.</p>
                    </div>

                </div>

            </div>


            <div class="seller-analytics-status-content">

                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    ];
                @endphp

                @forelse($statusLabels as $status => $label)

                    @php
                        $count = (int) ($statusData[$status] ?? 0);
                        $percentage = $totalOrders > 0
                            ? round(($count / $totalOrders) * 100)
                            : 0;
                    @endphp

                    <div class="seller-analytics-status-row">

                        <div class="seller-analytics-status-label">

                            <span class="status-dot {{ $statusColors[$status] ?? 'default' }}"></span>

                            <strong>{{ $label }}</strong>

                        </div>

                        <div class="seller-analytics-status-value">

                            <span>{{ $count }}</span>
                            <small>{{ $percentage }}%</small>

                        </div>

                    </div>

                    <div class="seller-analytics-progress">
                        <span
                            class="{{ $statusColors[$status] ?? 'default' }}"
                            style="width: {{ $percentage }}%;"
                        ></span>
                    </div>

                @empty

                    <div class="seller-analytics-mini-empty">
                        No order data available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- Inventory --}}
        <div class="seller-analytics-panel">

            <div class="seller-analytics-panel-header">

                <div class="seller-analytics-panel-title">

                    <div class="seller-analytics-panel-icon small">
                        <i class="bi bi-boxes"></i>
                    </div>

                    <div>
                        <h5>Inventory Overview</h5>
                        <p>Monitor your current book inventory.</p>
                    </div>

                </div>

            </div>


            <div class="seller-analytics-inventory">

                <div class="seller-analytics-inventory-item">
                    <div class="inventory-icon books">
                        <i class="bi bi-book"></i>
                    </div>

                    <div>
                        <span>Total Books</span>
                        <strong>{{ $totalBooks }}</strong>
                    </div>
                </div>


                <div class="seller-analytics-inventory-item">
                    <div class="inventory-icon approved">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div>
                        <span>Approved</span>
                        <strong>{{ $approvedBooks }}</strong>
                    </div>
                </div>


                <div class="seller-analytics-inventory-item">
                    <div class="inventory-icon pending">
                        <i class="bi bi-clock"></i>
                    </div>

                    <div>
                        <span>Pending</span>
                        <strong>{{ $pendingBooks }}</strong>
                    </div>
                </div>


                <div class="seller-analytics-inventory-item">
                    <div class="inventory-icon low">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>

                    <div>
                        <span>Low Stock</span>
                        <strong>{{ $lowStockBooks }}</strong>
                    </div>
                </div>


                <div class="seller-analytics-inventory-item">
                    <div class="inventory-icon out">
                        <i class="bi bi-box2"></i>
                    </div>

                    <div>
                        <span>Out of Stock</span>
                        <strong>{{ $outOfStockBooks }}</strong>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TOP BOOKS
    ====================================================== --}}
    <div class="seller-analytics-panel seller-analytics-top-books">

        <div class="seller-analytics-panel-header">

            <div class="seller-analytics-panel-title">

                <div class="seller-analytics-panel-icon">
                    <i class="bi bi-trophy"></i>
                </div>

                <div>
                    <h5>Top Selling Books</h5>
                    <p>Your best performing books in this period.</p>
                </div>

            </div>

        </div>


        @if($topBooks->count())

            <div class="seller-analytics-books-list">

                @foreach($topBooks as $index => $item)

                    <div class="seller-analytics-book-row">

                        <div class="seller-analytics-book-rank">
                            {{ $index + 1 }}
                        </div>


                        <div class="seller-analytics-book-cover">

                            @if($item->book && $item->book->cover)

                                <img
                                    src="{{ asset('storage/' . $item->book->cover) }}"
                                    alt="{{ $item->book->title }}"
                                >

                            @else

                                <i class="bi bi-book"></i>

                            @endif

                        </div>


                        <div class="seller-analytics-book-info">

                            <strong>
                                {{ $item->book->title ?? 'Deleted Book' }}
                            </strong>

                            @if($item->book && $item->book->author)

                                <span>
                                    {{ $item->book->author->name }}
                                </span>

                            @endif

                        </div>


                        <div class="seller-analytics-book-sales">

                            <strong>
                                {{ $item->total_quantity }}
                            </strong>

                            <span>
                                {{ \Illuminate\Support\Str::plural('item', $item->total_quantity) }} sold
                            </span>

                        </div>


                        <div class="seller-analytics-book-revenue">

                            ${{ number_format($item->total_revenue, 2) }}

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="seller-analytics-mini-empty">
                <i class="bi bi-bar-chart"></i>
                <span>No sales data available for this period.</span>
            </div>

        @endif

    </div>


    {{-- =====================================================
         RECENT ORDERS
    ====================================================== --}}
    <div class="seller-analytics-panel seller-analytics-recent-orders">

        <div class="seller-analytics-panel-header">

            <div class="seller-analytics-panel-title">

                <div class="seller-analytics-panel-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <h5>Recent Orders</h5>
                    <p>Your latest seller orders.</p>
                </div>

            </div>

            <a
                href="{{ route('seller.orders.index') }}"
                class="seller-analytics-view-all"
            >
                View All
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        @if($recentOrders->count())

            <div class="table-responsive">

                <table class="seller-analytics-orders-table">

                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Book</th>
                            <th>Buyer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($recentOrders as $order)

                            @php
                                $status = strtolower($order->order_status ?? 'pending');

                                $statusClass = match ($status) {
                                    'delivered' => 'success',
                                    'processing', 'shipped' => 'info',
                                    'cancelled', 'canceled', 'refunded' => 'danger',
                                    default => 'warning',
                                };

                                $buyerName = $order->user->name
                                    ?? trim(
                                        ($order->user->first_name ?? '') . ' ' .
                                        ($order->user->last_name ?? '')
                                    )
                                    ?: 'Unknown Buyer';
                            @endphp

                            <tr>

                                <td>
                                    <strong class="seller-analytics-order-number">
                                        #{{ $order->order_number }}
                                    </strong>
                                </td>

                                <td>

                                    <div class="seller-analytics-order-book">

                                        <div class="seller-analytics-order-cover">

                                            @if($order->book && $order->book->cover)

                                                <img
                                                    src="{{ asset('storage/' . $order->book->cover) }}"
                                                    alt="{{ $order->book->title }}"
                                                >

                                            @else

                                                <i class="bi bi-book"></i>

                                            @endif

                                        </div>

                                        <strong>
                                            {{ $order->book->title ?? 'Deleted Book' }}
                                        </strong>

                                    </div>

                                </td>

                                <td>
                                    <span class="seller-analytics-buyer">
                                        {{ $buyerName }}
                                    </span>
                                </td>

                                <td>

                                    <span class="seller-analytics-status-pill {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>

                                </td>

                                <td>

                                    <strong class="seller-analytics-order-total">
                                        ${{ number_format($order->total_price, 2) }}
                                    </strong>

                                </td>

                                <td>

                                    <div class="seller-analytics-order-date">
                                        <strong>
                                            {{ $order->created_at->format('M d, Y') }}
                                        </strong>

                                        <span>
                                            {{ $order->created_at->format('H:i') }}
                                        </span>
                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="seller-analytics-mini-empty">
                <i class="bi bi-receipt"></i>
                <span>No recent orders found.</span>
            </div>

        @endif

    </div>

</div>

@endsection


@push('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('sellerRevenueChart');

    if (!canvas) {
        return;
    }

    const labels = @json($chartLabels);
    const revenue = @json($chartRevenue);

    const ctx = canvas.getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 320);

    gradient.addColorStop(0, 'rgba(139, 94, 60, .28)');
    gradient.addColorStop(1, 'rgba(139, 94, 60, 0)');

    new Chart(ctx, {

        type: 'line',

        data: {
            labels: labels,

            datasets: [{
                label: 'Revenue',
                data: revenue,

                borderColor: '#8b5e3c',
                backgroundColor: gradient,

                borderWidth: 3,

                pointRadius: 3,
                pointHoverRadius: 6,

                pointBackgroundColor: '#8b5e3c',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,

                fill: true,

                tension: .4
            }]
        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            plugins: {

                legend: {
                    display: false
                },

                tooltip: {
                    backgroundColor: '#131b2c',
                    titleColor: '#ffffff',
                    bodyColor: '#d5dbea',
                    padding: 12,
                    cornerRadius: 10,

                    callbacks: {
                        label: function (context) {
                            return '$' + Number(context.raw).toLocaleString(
                                undefined,
                                {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }
                            );
                        }
                    }
                }

            },

            scales: {

                x: {
                    grid: {
                        display: false
                    },

                    border: {
                        display: false
                    },

                    ticks: {
                        color: '#8b95a7',
                        font: {
                            size: 10
                        },

                        maxTicksLimit: 10
                    }
                },

                y: {

                    beginAtZero: true,

                    border: {
                        display: false
                    },

                    grid: {
                        color: 'rgba(139, 149, 167, .12)'
                    },

                    ticks: {
                        color: '#8b95a7',
                        font: {
                            size: 10
                        },

                        callback: function (value) {
                            return '$' + value;
                        }
                    }
                }

            },

            animation: {
                duration: 1100,
                easing: 'easeOutQuart'
            }

        }

    });

});
</script>

@endpush