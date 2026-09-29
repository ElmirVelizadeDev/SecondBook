@extends('layout.admin.master')

@section('title', 'Reports')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/reports.css') }}">
@endpush

@section('content')

<div class="dashboard-section reports-page">

    {{-- Header --}}
    <div class="dashboard-panel reports-header-panel">
        <div class="reports-header-content">
            <div>
                <h5>Reports Dashboard</h5>
                <p>Monitor sales, orders, customers and inventory</p>
            </div>

            <div class="reports-header-actions">
                <a href="{{ route('admin.reports.sales') }}"
                   class="reports-back-btn">
                    <i class="bi bi-graph-up"></i>
                    Sales Report
                </a>
            </div>
        </div>
    </div>


    {{-- Date Filter --}}
    <div class="dashboard-panel reports-filter-panel">
        <form method="GET"
              action="{{ route('admin.reports.index') }}">

            <div class="reports-filter-row">

                <div class="reports-filter-group">
                    <label for="start_date">
                        Start Date
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                    >
                </div>


                <div class="reports-filter-group">
                    <label for="end_date">
                        End Date
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                    >
                </div>


                <div class="reports-filter-actions">

                    <button type="submit"
                            class="reports-filter-btn">
                        <i class="bi bi-funnel"></i>
                        Apply
                    </button>

                    <a href="{{ route('admin.reports.index') }}"
                       class="reports-reset-btn">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </a>

                </div>

            </div>

        </form>
    </div>


    {{-- Main Statistics --}}
    <div class="reports-stats-grid">

        {{-- Revenue --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-revenue">
                <i class="bi bi-currency-dollar"></i>
            </div>

            <div class="reports-stat-content">
                <span>Total Revenue</span>

                <strong>
                    ${{ number_format($totalRevenue, 2) }}
                </strong>
            </div>
        </div>


        {{-- Orders --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-orders">
                <i class="bi bi-bag-check"></i>
            </div>

            <div class="reports-stat-content">
                <span>Total Orders</span>

                <strong>
                    {{ number_format($totalOrders) }}
                </strong>
            </div>
        </div>


        {{-- Books Sold --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-books">
                <i class="bi bi-book"></i>
            </div>

            <div class="reports-stat-content">
                <span>Books Sold</span>

                <strong>
                    {{ number_format($booksSold) }}
                </strong>
            </div>
        </div>


        {{-- Customers --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-users">
                <i class="bi bi-people"></i>
            </div>

            <div class="reports-stat-content">
                <span>New Customers</span>

                <strong>
                    {{ number_format($totalCustomers) }}
                </strong>
            </div>
        </div>

    </div>


    {{-- Order Status --}}
    <div class="reports-stats-grid">

        {{-- Pending --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div class="reports-stat-content">
                <span>Pending Orders</span>

                <strong>
                    {{ number_format($pendingOrders) }}
                </strong>
            </div>
        </div>


        {{-- Delivered --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="reports-stat-content">
                <span>Delivered Orders</span>

                <strong>
                    {{ number_format($deliveredOrders) }}
                </strong>
            </div>
        </div>


        {{-- Cancelled --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-danger">
                <i class="bi bi-x-circle"></i>
            </div>

            <div class="reports-stat-content">
                <span>Cancelled Orders</span>

                <strong>
                    {{ number_format($cancelledOrders) }}
                </strong>
            </div>
        </div>


        {{-- Average Order --}}
        <div class="reports-stat-card">
            <div class="reports-stat-icon reports-icon-books">
                <i class="bi bi-calculator"></i>
            </div>

            <div class="reports-stat-content">
                <span>Average Order</span>

                <strong>
                    ${{ number_format($averageOrderValue, 2) }}
                </strong>
            </div>
        </div>

    </div>


    {{-- Sales Chart --}}
    <div class="dashboard-panel reports-panel">

        <div class="reports-panel-header">
            <div>
                <h5>Sales Overview</h5>

                <p>
                    Delivered revenue during the selected period
                </p>
            </div>
        </div>

        <div class="reports-chart-wrapper">
            <canvas id="salesChart"></canvas>
        </div>

    </div>


    {{-- Two Columns --}}
    <div class="reports-two-column">

        {{-- Top Books --}}
        <div class="dashboard-panel reports-panel">

            <div class="reports-panel-header">
                <div>
                    <h5>Top Selling Books</h5>

                    <p>
                        Best performing books
                    </p>
                </div>

                <a href="{{ route('admin.reports.books') }}"
                   class="reports-back-btn">
                    View All
                </a>
            </div>


            <div class="reports-list">

                @forelse($topBooks as $topBook)

                    <div class="reports-list-item">

                        <div class="reports-list-info">

                            <strong>
                                {{ $topBook->book?->title ?? 'Unknown Book' }}
                            </strong>

                            <span>
                                {{ number_format($topBook->total_sold) }} books sold
                            </span>

                        </div>

                        <strong>
                            ${{ number_format($topBook->revenue, 2) }}
                        </strong>

                    </div>

                @empty

                    <div class="reports-table-empty">
                        No sales data available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- Low Stock --}}
        <div class="dashboard-panel reports-panel">

            <div class="reports-panel-header">

                <div>
                    <h5>Low Stock</h5>

                    <p>
                        Books that need attention
                    </p>
                </div>

                <a href="{{ route('admin.reports.books') }}"
                   class="reports-back-btn">
                    View All
                </a>

            </div>


            <div class="reports-list">

                @forelse($lowStockBooks as $book)

                    <div class="reports-list-item">

                        <div class="reports-list-info">

                            <strong>
                                {{ $book->title }}
                            </strong>

                            <span>
                                Stock: {{ $book->stock }}
                            </span>

                        </div>


                        @if($book->stock <= 0)

                            <span class="reports-stock-badge reports-stock-danger">
                                Out of stock
                            </span>

                        @else

                            <span class="reports-stock-badge reports-stock-warning">
                                Low stock
                            </span>

                        @endif

                    </div>

                @empty

                    <div class="reports-table-empty">
                        No low stock books.
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- Recent Orders --}}
    <div class="dashboard-panel reports-panel reports-orders-panel">

        <div class="reports-panel-header">

            <div>
                <h5>Recent Orders</h5>

                <p>
                    Latest orders during the selected period
                </p>
            </div>

            <a href="{{ route('admin.reports.sales') }}"
               class="reports-back-btn">
                View Sales
            </a>

        </div>


        <div class="reports-table-wrapper">

            <table class="reports-table">

                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Book</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($recentOrders as $order)

                        <tr>

                            <td>
                                <span class="reports-order-number">
                                    {{ $order->order_number }}
                                </span>
                            </td>


                            <td>
                                {{ $order->user?->name ?? 'Unknown' }}
                            </td>


                            <td>
                                <span class="reports-book-name">
                                    {{ $order->book?->title ?? 'Unknown Book' }}
                                </span>
                            </td>


                            <td>

                                @if($order->order_status === 'delivered')

                                    <span class="reports-stock-badge reports-stock-normal">
                                        Delivered
                                    </span>

                                @elseif($order->order_status === 'cancelled')

                                    <span class="reports-stock-badge reports-stock-danger">
                                        Cancelled
                                    </span>

                                @elseif($order->order_status === 'pending')

                                    <span class="reports-stock-badge reports-stock-warning">
                                        Pending
                                    </span>

                                @else

                                    <span class="reports-stock-badge reports-stock-normal">
                                        {{ ucfirst($order->order_status) }}
                                    </span>

                                @endif

                            </td>


                            <td>
                                <strong>
                                    ${{ number_format($order->total_price, 2) }}
                                </strong>
                            </td>


                            <td>
                                {{ $order->created_at?->format('d M Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="reports-table-empty">
                                    No orders found for this period.
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- Chart.js --}}
@push('js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const canvas = document.getElementById('salesChart');

            if (!canvas) {
                return;
            }

            const labels = @json(
                $salesByDay->pluck('date')
            );

            const values = @json(
                $salesByDay->pluck('revenue')
            );

            new Chart(canvas, {

                type: 'line',

                data: {
                    labels: labels,

                    datasets: [{
                        label: 'Revenue',
                        data: values,
                        tension: 0.35,
                        fill: true
                    }]
                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }

                }

            });

        });
    </script>

@endpush

@endsection