@extends('layout.admin.master')

@section('title', 'Orders')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/orders.css') }}">
@endpush


@section('content')

<div class="container-fluid p-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-cart3 me-2"></i>
                Orders
            </h2>

            <p class="text-muted mb-0">
                Manage customer orders, payments and delivery status.
            </p>
        </div>

        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            Add Order
        </a>

    </div>


    {{-- Statistics --}}
    <div class="row g-4 mb-4">

        {{-- Total Orders --}}
        <div class="col-xl-3 col-md-6">

            <div class="order-card">

                <div>
                    <span>Total Orders</span>
                    <h3>{{ $totalOrders }}</h3>
                </div>

                <i class="bi bi-cart-check"></i>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

            <div class="order-card">

                <div>
                    <span>Pending</span>
                    <h3>{{ $totalPending }}</h3>
                </div>

                <i class="bi bi-hourglass-split"></i>

            </div>

        </div>


        {{-- Delivered --}}
        <div class="col-xl-3 col-md-6">

            <div class="order-card">

                <div>
                    <span>Delivered</span>
                    <h3>{{ $totalDelivered }}</h3>
                </div>

                <i class="bi bi-check-circle"></i>

            </div>

        </div>


        {{-- Revenue --}}
        <div class="col-xl-3 col-md-6">

            <div class="order-card">

                <div>
                    <span>Revenue</span>
                    <h3>${{ number_format($revenue, 2) }}</h3>
                </div>

                <i class="bi bi-currency-dollar"></i>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="dashboard-panel mb-4">

        <form method="GET"
              action="{{ route('admin.orders.index') }}"
              class="row g-3 align-items-end">

            {{-- Search --}}
            <div class="col-12 col-md-6 col-lg-4">

                <label class="form-label small text-muted fw-semibold">
                    Search
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control border-start-0"
                        placeholder="Order number, customer, book..."
                    >

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Search
                    </button>

                </div>

            </div>


            {{-- Order Status --}}
            <div class="col-6 col-md-3 col-lg-2">

                <label class="form-label small text-muted fw-semibold">
                    Status
                </label>

                <select name="status" class="form-select">

                    <option value="">
                        All Status
                    </option>

                    <option value="pending"
                        @selected(request('status') === 'pending')>
                        Pending
                    </option>

                    <option value="processing"
                        @selected(request('status') === 'processing')>
                        Processing
                    </option>

                    <option value="shipped"
                        @selected(request('status') === 'shipped')>
                        Shipped
                    </option>

                    <option value="delivered"
                        @selected(request('status') === 'delivered')>
                        Delivered
                    </option>

                    <option value="cancelled"
                        @selected(request('status') === 'cancelled')>
                        Cancelled
                    </option>

                </select>

            </div>


            {{-- Payment Status --}}
            <div class="col-6 col-md-3 col-lg-2">

                <label class="form-label small text-muted fw-semibold">
                    Payment
                </label>

                <select name="payment" class="form-select">

                    <option value="">
                        All Payments
                    </option>

                    <option value="paid"
                        @selected(request('payment') === 'paid')>
                        Paid
                    </option>

                    <option value="pending"
                        @selected(request('payment') === 'pending')>
                        Pending
                    </option>

                    <option value="failed"
                        @selected(request('payment') === 'failed')>
                        Failed
                    </option>

                    <option value="refunded"
                        @selected(request('payment') === 'refunded')>
                        Refunded
                    </option>

                </select>

            </div>


            {{-- Date --}}
            <div class="col-12 col-md-6 col-lg-2">

                <label class="form-label small text-muted fw-semibold">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="form-control"
                >

            </div>


            {{-- Buttons --}}
            <div class="col-12 col-md-6 col-lg-2 d-flex gap-2 order-filter-buttons">

                <button type="submit"
                        class="btn btn-primary filter-btn">
                    <i class="bi bi-funnel me-1"></i>
                    <span>Filter</span>
                </button>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="btn btn-light reset-btn">

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Book</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($orders as $order)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $order->id }}
                                </td>


                                {{-- Customer --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $order->user->first_name }}
                                        {{ $order->user->last_name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $order->user->email }}
                                    </small>

                                </td>


                                {{-- Book --}}
                                <td>
                                    {{ $order->book->title }}
                                </td>


                                {{-- Total --}}
                                <td>
                                    ${{ number_format($order->total_price, 2) }}
                                </td>


                                {{-- Payment --}}
                                <td>

                                    <span class="badge bg-success">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($order->order_status) }}
                                    </span>

                                </td>


                                {{-- Date --}}
                                <td>
                                    {{ $order->created_at->format('d M Y') }}
                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="order-actions">

                                        {{-- Show --}}
                                        <a
                                            href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-light"
                                            title="View">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('admin.orders.edit', $order->id) }}"
                                            class="btn btn-sm btn-light"
                                            title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('admin.orders.destroy', $order->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this order?');">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-cart-x fs-1 d-block mb-2"></i>

                                        <div class="fw-semibold">
                                            No orders found
                                        </div>

                                        <small>
                                            Try changing your filters or search.
                                        </small>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            @if ($orders->hasPages())

                <div class="orders-pagination">

                    {{-- Info --}}
                    <div class="orders-pagination-info">
                        Showing
                        <strong>{{ $orders->firstItem() }}</strong>
                        to
                        <strong>{{ $orders->lastItem() }}</strong>
                        of
                        <strong>{{ $orders->total() }}</strong>
                        orders
                    </div>


                    {{-- Pagination --}}
                    <div class="orders-pagination-links">

                        <nav aria-label="Orders pagination">

                            <ul class="pagination mb-0">

                                {{-- Previous --}}
                                <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">

                                    @if ($orders->onFirstPage())

                                        <span class="page-link">
                                            <i class="bi bi-chevron-left"></i>
                                        </span>

                                    @else

                                        <a
                                            class="page-link"
                                            href="{{ $orders->previousPageUrl() }}"
                                            aria-label="Previous">

                                            <i class="bi bi-chevron-left"></i>

                                        </a>

                                    @endif

                                </li>


                                {{-- Pages --}}
                                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)

                                    <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">

                                        @if ($page == $orders->currentPage())

                                            <span class="page-link">
                                                {{ $page }}
                                            </span>

                                        @else

                                            <a
                                                class="page-link"
                                                href="{{ $url }}">

                                                {{ $page }}

                                            </a>

                                        @endif

                                    </li>

                                @endforeach


                                {{-- Next --}}
                                <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">

                                    @if ($orders->hasMorePages())

                                        <a
                                            class="page-link"
                                            href="{{ $orders->nextPageUrl() }}"
                                            aria-label="Next">

                                            <i class="bi bi-chevron-right"></i>

                                        </a>

                                    @else

                                        <span class="page-link">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>

                                    @endif

                                </li>

                            </ul>

                        </nav>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection