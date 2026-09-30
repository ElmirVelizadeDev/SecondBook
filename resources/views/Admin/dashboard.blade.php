@extends('layout.admin.master')

@section('title', 'Dashboard')

@section('content')

@php

    /* ---------- Status maps (same statuses as before) ---------- */

    $bookStatus = [
        'approved' => ['green', 'Approved'],
        'pending'  => ['amber', 'Pending'],
        'rejected' => ['red',   'Rejected'],
    ];

    $orderStatus = [
        'pending'    => ['amber', 'Pending'],
        'processing' => ['blue',  'Processing'],
        'shipped'    => ['cyan',  'Shipped'],
        'delivered'  => ['green', 'Delivered'],
        'cancelled'  => ['red',   'Cancelled'],
    ];

    /* ---------- Stat cards ---------- */

    $stats = [
        ['Total Books', $totalBooks,      'bi-book',          'blue',  'admin.books.index'],
        ['Total Users', $totalUsers,      'bi-people',        'green', 'admin.users.index'],
        ['Categories',  $totalCategories, 'bi-grid',          'amber', 'admin.categories.index'],
        ['Authors',     $totalAuthors,    'bi-pencil-square', 'rose',  'admin.authors.index'],
    ];

    /* ---------- Hero "latest month" glance ---------- */

    $glanceLabel   = collect($monthlyLabels)->last();
    $glanceOrders  = collect($monthlyOrders)->last() ?? 0;
    $glanceRevenue = collect($monthlyRevenue)->last() ?? 0;

@endphp

<div class="dashboard-section dx-page">

    {{-- =========================================
        HERO
    ========================================= --}}
    <div class="dx-hero">

        <div class="dx-hero-body">

            <div class="dx-hero-crumb">
                <i class="bi bi-speedometer2"></i>
                SecondBook Admin
            </div>

            <h1>Welcome back, Admin</h1>

            <p>Manage your books, users and categories from one place.</p>

            <div class="dx-hero-actions">

                <a href="{{ route('admin.books.create') }}" class="dx-btn dx-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Add Book
                </a>

                <a href="{{ route('admin.books.index') }}" class="dx-btn dx-btn-ghost">
                    <i class="bi bi-book"></i>
                    View Books
                </a>

            </div>

        </div>

        <div class="dx-hero-glance">

            <div class="dx-glance-title">
                <i class="bi bi-calendar3"></i>
                {{ $glanceLabel ?: 'Latest month' }}
            </div>

            <div class="dx-glance-row">
                <span>Orders</span>
                <strong>{{ number_format((float) $glanceOrders) }}</strong>
            </div>

            <div class="dx-glance-row">
                <span>Revenue</span>
                <strong>{{ number_format((float) $glanceRevenue, 2) }}<small>AZN</small></strong>
            </div>

        </div>

    </div>


    {{-- =========================================
        STATISTICS
    ========================================= --}}
    <div class="dx-stats">

        @foreach($stats as [$label, $value, $icon, $tone, $routeName])

            <div class="dx-stat">

                <div class="dx-stat-top">
                    <span class="dx-stat-label">{{ $label }}</span>

                    <span class="dx-chip dx-tone-{{ $tone }}">
                        <i class="bi {{ $icon }}"></i>
                    </span>
                </div>

                <div class="dx-stat-value">{{ number_format((float) $value) }}</div>

                <a href="{{ route($routeName) }}" class="dx-stat-link">
                    View details
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        @endforeach

    </div>


    {{-- =========================================
        CHART & ACTIVITY
    ========================================= --}}
    <div class="dx-split">

        <div class="dx-panel">

            <div class="dx-panel-head">

                <div>
                    <h2>Monthly Overview</h2>
                    <p>Orders and revenue by month</p>
                </div>

                <div class="dx-legend">
                    <span><i></i>Orders</span>
                    <span><i></i>Revenue</span>
                </div>

            </div>

            <div class="dx-chart">
                <canvas id="monthlyOverviewChart"></canvas>
            </div>

        </div>


        <div class="dx-panel">

            <div class="dx-panel-head">
                <div>
                    <h2>Recent Activity</h2>
                    <p>Latest changes on the platform</p>
                </div>
            </div>

            @if($recentBooks->isNotEmpty() || $recentUsers->isNotEmpty() || $recentCategories->isNotEmpty())

                <ul class="dx-activity">

                    @if($recentBooks->isNotEmpty())
                        <li>
                            <span class="dx-chip dx-tone-blue"><i class="bi bi-book"></i></span>
                            <div>
                                <strong>New Book Added</strong>
                                <p>{{ $recentBooks->first()->title }}</p>
                                @if($recentBooks->first()->created_at)
                                    <small>{{ $recentBooks->first()->created_at->diffForHumans() }}</small>
                                @endif
                            </div>
                        </li>
                    @endif

                    @if($recentUsers->isNotEmpty())
                        <li>
                            <span class="dx-chip dx-tone-green"><i class="bi bi-person"></i></span>
                            <div>
                                <strong>New User</strong>
                                <p>{{ $recentUsers->first()->name }} registered</p>
                                @if($recentUsers->first()->created_at)
                                    <small>{{ $recentUsers->first()->created_at->diffForHumans() }}</small>
                                @endif
                            </div>
                        </li>
                    @endif

                    @if($recentCategories->isNotEmpty())
                        <li>
                            <span class="dx-chip dx-tone-amber"><i class="bi bi-grid"></i></span>
                            <div>
                                <strong>Category Created</strong>
                                <p>{{ $recentCategories->first()->name }}</p>
                                @if($recentCategories->first()->created_at)
                                    <small>{{ $recentCategories->first()->created_at->diffForHumans() }}</small>
                                @endif
                            </div>
                        </li>
                    @endif

                </ul>

            @else

                <div class="dx-empty">
                    <i class="bi bi-inbox"></i>
                    <strong>No activity yet</strong>
                    <span>New books, users and categories will show up here.</span>
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================
        RECENT BOOKS
    ========================================= --}}
    <div class="dx-panel">

        <div class="dx-panel-head">

            <div>
                <h2>Recent Books</h2>
                <p>The latest titles added to the marketplace</p>
            </div>

            <a href="{{ route('admin.books.index') }}" class="dx-btn dx-btn-soft">
                View all
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

        <div class="dx-table-wrap">

            <table class="dx-table">

                <thead>
                    <tr>
                        <th>Book</th>
                        <th class="dx-hide-md">Category</th>
                        <th class="dx-hide-lg">Seller</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($recentBooks as $book)

                        <tr>

                            <td>
                                <div class="dx-book">

                                    <div class="dx-cover">
                                        @if($book->cover)
                                            <img
                                                src="{{ asset('storage/' . $book->cover) }}"
                                                alt="{{ $book->title }}"
                                                loading="lazy"
                                            >
                                        @else
                                            <i class="bi bi-book"></i>
                                        @endif
                                    </div>

                                    <div>
                                        <span class="dx-title">{{ $book->title }}</span>

                                        @if($book->isbn)
                                            <span class="dx-sub">ISBN: {{ $book->isbn }}</span>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            <td class="dx-hide-md">{{ $book->category->name ?? 'No Category' }}</td>

                            <td class="dx-hide-lg">{{ $book->seller->name ?? 'No Seller' }}</td>

                            <td class="dx-num">{{ number_format($book->price, 2) }} AZN</td>

                            <td>
                                @php
                                    [$tone, $text] = $bookStatus[$book->status] ?? ['slate', ucfirst($book->status)];
                                @endphp

                                <span class="dx-pill dx-pill-{{ $tone }}">{{ $text }}</span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">
                                <div class="dx-empty">
                                    <i class="bi bi-book"></i>
                                    <strong>No books found</strong>
                                    <span>Add your first book to see it here.</span>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================
        ORDERS & QUICK ACTIONS
    ========================================= --}}
    <div class="dx-split">

        <div class="dx-panel">

            <div class="dx-panel-head">
                <div>
                    <h2>Recent Orders</h2>
                    <p>Latest customer purchases</p>
                </div>
            </div>

            <div class="dx-table-wrap">

                <table class="dx-table">

                    <thead>
                        <tr>
                            <th class="dx-hide-md">#</th>
                            <th>Order</th>
                            <th class="dx-hide-md">Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($recentOrders as $order)

                            <tr>

                                <td class="dx-hide-md">{{ $loop->iteration }}</td>

                                <td>
                                    <span class="dx-title">#{{ $order->order_number }}</span>

                                    @if($order->book)
                                        <span class="dx-sub">{{ $order->book->title }}</span>
                                    @endif
                                </td>

                                <td class="dx-hide-md">
                                    {{ $order->user->name ?? $order->full_name }}
                                </td>

                                <td class="dx-num">{{ number_format($order->total_price, 2) }} AZN</td>

                                <td>
                                    @php
                                        [$tone, $text] = $orderStatus[$order->order_status] ?? ['slate', ucfirst($order->order_status)];
                                    @endphp

                                    <span class="dx-pill dx-pill-{{ $tone }}">{{ $text }}</span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5">
                                    <div class="dx-empty">
                                        <i class="bi bi-bag"></i>
                                        <strong>No orders found</strong>
                                        <span>Orders will appear here once customers check out.</span>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="dx-panel">

            <div class="dx-panel-head">
                <div>
                    <h2>Quick Actions</h2>
                    <p>Jump straight to common tasks</p>
                </div>
            </div>

            <div class="dx-actions">

                @foreach([
                    ['admin.books.create',      'Add Book',     'Publish a new title',      'bi-book',      'blue'],
                    ['admin.categories.create', 'Add Category', 'Organize your catalog',    'bi-grid',      'green'],
                    ['admin.authors.create',    'Add Author',   'Create an author profile', 'bi-pencil-square', 'amber'],
                    ['admin.users.index',       'View Users',   'Manage customer accounts', 'bi-people',    'slate'],
                ] as [$routeName, $title, $hint, $icon, $tone])

                    <a href="{{ route($routeName) }}" class="dx-action">

                        <span class="dx-chip dx-tone-{{ $tone }}">
                            <i class="bi {{ $icon }}"></i>
                        </span>

                        <span>
                            <strong>{{ $title }}</strong>
                            <small>{{ $hint }}</small>
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                @endforeach

            </div>

        </div>

    </div>

</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('monthlyOverviewChart');

    if (!canvas) {
        return;
    }

    const monthlyLabels  = @json($monthlyLabels);
    const monthlyOrders  = @json($monthlyOrders);
    const monthlyRevenue = @json($monthlyRevenue);

    const scope = canvas.closest('.dashboard-section');
    let chart = null;

    /* Read theme colors from CSS variables */
    const token = function (name) {
        return getComputedStyle(scope).getPropertyValue(name).trim();
    };

    /* Soft gradient under each line */
    const gradient = function (ctx, hex) {
        const g = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 340);
        g.addColorStop(0, hex + '33');
        g.addColorStop(1, hex + '00');
        return g;
    };

    const build = function () {

        if (chart) {
            chart.destroy();
        }

        const ctx = canvas.getContext('2d');
        const orders = token('--dx-chart-orders');
        const revenue = token('--dx-chart-revenue');
        const grid = token('--dx-chart-grid');
        const text = token('--dx-chart-text');

        Chart.defaults.font.family = '"Plus Jakarta Sans", system-ui, sans-serif';
        Chart.defaults.color = text;

        chart = new Chart(canvas, {

            type: 'line',

            data: {
                labels: monthlyLabels,

                datasets: [
                    {
                        label: 'Orders',
                        data: monthlyOrders,
                        borderColor: orders,
                        backgroundColor: gradient(ctx, orders),
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 3,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: orders,
                        yAxisID: 'orders'
                    },

                    {
                        label: 'Revenue',
                        data: monthlyRevenue,
                        borderColor: revenue,
                        backgroundColor: gradient(ctx, revenue),
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 3,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: revenue,
                        yAxisID: 'revenue'
                    }
                ]
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
                        display: false          /* custom legend in the panel header */
                    },

                    tooltip: {
                        padding: 12,
                        cornerRadius: 10,
                        boxPadding: 5,
                        usePointStyle: true,
                        backgroundColor: '#101828',
                        titleColor: '#fff',
                        bodyColor: '#d0d5dd',
                        callbacks: {
                            label: function (item) {
                                if (item.dataset.yAxisID === 'revenue') {
                                    return ' Revenue: ' +
                                        Number(item.parsed.y).toLocaleString(undefined, {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }) + ' AZN';
                                }

                                return ' Orders: ' + item.parsed.y;
                            }
                        }
                    }
                },

                scales: {

                    x: {
                        grid: { display: false },
                        border: { display: false }
                    },

                    orders: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: grid },
                        ticks: { precision: 0, padding: 8 }
                    },

                    revenue: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        border: { display: false },
                        grid: { drawOnChartArea: false },
                        ticks: { padding: 8 }
                    }

                }

            }

        });

    };

    build();

    /* Re-draw with the right colors when the theme is switched */
    new MutationObserver(build).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });

});
</script>

@endpush