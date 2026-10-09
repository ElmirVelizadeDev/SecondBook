@extends('layout.admin.master')

@section('title', 'Orders')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/orders.css') }}">
@endpush

@section('content')
<div class="dashboard-section orders-page">

    {{-- HERO --}}
    <section class="orders-hero">
        <div class="orders-hero-content">
            <span class="orders-hero-badge">
                <i class="bi bi-bag-check"></i>
                Marketplace Operations
            </span>
            <h1>Every order, under control.</h1>
            <p>
                Manage customer orders, payments and delivery progress
                from one organised workspace.
            </p>
        </div>

        <div class="orders-hero-mark" aria-hidden="true">
            <i class="bi bi-cart3"></i>
        </div>
    </section>

    {{-- STATISTICS --}}
    <section class="orders-stats">
        <div class="order-stat-card stat-blue">
            <div class="order-stat-content">
                <span>Total Orders</span>
                <strong>{{ $totalOrders }}</strong>
            </div>
            <div class="order-stat-icon">
                <i class="bi bi-cart-check"></i>
            </div>
        </div>

        <div class="order-stat-card stat-orange">
            <div class="order-stat-content">
                <span>Pending</span>
                <strong>{{ $totalPending }}</strong>
            </div>
            <div class="order-stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>

        <div class="order-stat-card stat-green">
            <div class="order-stat-content">
                <span>Delivered</span>
                <strong>{{ $totalDelivered }}</strong>
            </div>
            <div class="order-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="order-stat-card stat-purple">
            <div class="order-stat-content">
                <span>Revenue</span>
                <strong>${{ number_format($revenue, 2) }}</strong>
            </div>
            <div class="order-stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </section>

    {{-- MAIN PANEL --}}
    <section class="dashboard-panel orders-panel">

        <div class="orders-panel-header">
            <div class="orders-heading-content">
                <span class="eyebrow">Order directory</span>
                <h5>All Orders</h5>
                <p>Search, filter and review every marketplace order.</p>
            </div>

            <div class="orders-header-action">
                <a href="{{ route('admin.orders.create') }}" class="orders-add-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Order</span>
                </a>
            </div>
        </div>

        {{-- FILTERS --}}
        <form
            id="orders-filter-form"
            method="GET"
            action="{{ route('admin.orders.index') }}"
            class="order-filters"
        >
            <div class="order-filter-group order-filter-search">
                <label for="order-search">Search</label>
                <div class="order-search-field">
                    <i class="bi bi-search"></i>
                    <input
                        id="order-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Order number, customer, book..."
                        autocomplete="off"
                    >
                </div>
            </div>

            <div class="order-filter-group">
                <label for="order-status">Order Status</label>
                <select id="order-status" name="status" class="order-filter-select">
                    <option value="">All Status</option>
                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="order-filter-group">
                <label for="order-payment">Payment</label>
                <select id="order-payment" name="payment" class="order-filter-select">
                    <option value="">All Payments</option>
                    @foreach(['paid', 'pending', 'failed', 'refunded'] as $p)
                        <option value="{{ $p }}" @selected(request('payment') === $p)>
                            {{ ucfirst($p) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="order-filter-group">
                <label for="order-date">Order Date</label>
                <div class="order-date-field">
                    <i class="bi bi-calendar3"></i>
                    <input
                        id="order-date"
                        type="date"
                        name="date"
                        value="{{ request('date') }}"
                    >
                </div>
            </div>

            <div class="order-filter-actions">
                <button type="submit" class="order-filter-btn">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="order-clear-filter"
                    id="orders-clear-filter"
                    @unless(request()->hasAny(['search', 'status', 'payment', 'date']))
                        hidden
                    @endunless
                >
                    <i class="bi bi-x-lg"></i>
                    <span>Clear</span>
                </a>
            </div>
        </form>

        {{-- AJAX RESULTS --}}
        <div id="orders-results">

            {{-- TABLE --}}
            <div class="orders-table-wrap">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th class="orders-col-id">#</th>
                            <th class="orders-col-customer">Customer</th>
                            <th class="orders-col-book">Book</th>
                            <th class="orders-col-total">Total</th>
                            <th class="orders-col-payment">Payment</th>
                            <th class="orders-col-status">Status</th>
                            <th class="orders-col-date">Date</th>
                            <th class="orders-col-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $customerName = trim(
                                    ($order->user->first_name ?? '') . ' ' .
                                    ($order->user->last_name ?? '')
                                );

                                $customerName = $customerName ?: (
                                    $order->user->name ??
                                    $order->user->username ??
                                    'Customer'
                                );

                                $customerInitial = strtoupper(mb_substr($customerName, 0, 1));
                                $paymentStatus = strtolower($order->payment_status ?? 'pending');
                                $orderStatus = strtolower($order->order_status ?? 'pending');

                                $knownPayments = ['paid', 'pending', 'failed', 'refunded'];
                                $knownStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

                                $paymentClass = in_array($paymentStatus, $knownPayments)
                                    ? 'payment-' . $paymentStatus
                                    : 'payment-default';

                                $statusClass = in_array($orderStatus, $knownStatuses)
                                    ? 'order-status-' . $orderStatus
                                    : 'order-status-default';
                            @endphp

                            <tr id="order-row-{{ $order->id }}">
                                <td>
                                    <span class="order-id">#{{ $order->id }}</span>
                                </td>

                                <td>
                                    <div class="order-customer-cell">
                                        <div class="order-customer-avatar">
                                            {{ $customerInitial }}
                                        </div>
                                        <div class="order-customer-info">
                                            <strong title="{{ $customerName }}">
                                                {{ $customerName }}
                                            </strong>
                                            <small title="{{ $order->user->email ?? '' }}">
                                                {{ $order->user->email ?? '—' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="order-book-cell">
                                        <div class="order-book-icon">
                                            <i class="bi bi-book"></i>
                                        </div>
                                        <div class="order-book-info">
                                            <strong title="{{ $order->book->title ?? 'Unknown book' }}">
                                                {{ $order->book->title ?? 'Unknown book' }}
                                            </strong>
                                            <small>Book item</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="order-total">
                                        ${{ number_format($order->total_price, 2) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="order-status-pill {{ $paymentClass }}">
                                        <i class="bi bi-circle-fill"></i>
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="order-status-pill {{ $statusClass }}">
                                        <i class="bi bi-circle-fill"></i>
                                        {{ ucfirst($orderStatus) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="order-date-block">
                                        <span class="order-date">
                                            {{ $order->created_at->format('d M Y') }}
                                        </span>
                                        <small class="order-time">
                                            {{ $order->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    <div class="order-actions">
                                        <a
                                            href="{{ route('admin.orders.show', $order->id) }}"
                                            class="order-action-btn order-view-btn"
                                            title="View order"
                                            aria-label="View order #{{ $order->id }}"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.orders.edit', $order->id) }}"
                                            class="order-action-btn order-edit-btn"
                                            title="Edit order"
                                            aria-label="Edit order #{{ $order->id }}"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.orders.destroy', $order->id) }}"
                                            method="POST"
                                            class="order-delete-form"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="order-action-btn order-delete-btn"
                                                title="Delete order"
                                                aria-label="Delete order #{{ $order->id }}"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="orders-empty-state">
                                        <div class="orders-empty-icon">
                                            <i class="bi bi-cart-x"></i>
                                        </div>
                                        <strong>No orders found</strong>
                                        <span>Try changing your filters or search criteria.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($orders->hasPages())
                @php
                    $orders->appends(request()->query());

                    $current = $orders->currentPage();
                    $last = $orders->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);
                @endphp

                <div class="orders-pagination">
                    <div class="orders-pagination-info">
                        Showing
                        <strong>{{ $orders->firstItem() }}</strong>
                        to
                        <strong>{{ $orders->lastItem() }}</strong>
                        of
                        <strong>{{ $orders->total() }}</strong>
                        orders
                    </div>

                    <nav class="orders-pagination-pages" aria-label="Orders pagination">
                        @if($orders->onFirstPage())
                            <span class="orders-pager-btn disabled" aria-disabled="true">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        @else
                            <a
                                href="{{ $orders->previousPageUrl() }}"
                                class="orders-pager-btn"
                                aria-label="Previous page"
                            >
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif

                        @if($start > 1)
                            <a href="{{ $orders->url(1) }}" class="orders-pager-btn">1</a>

                            @if($start > 2)
                                <span class="orders-pager-dots">…</span>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page === $current)
                                <span class="orders-pager-btn active" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $orders->url($page) }}" class="orders-pager-btn">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="orders-pager-dots">…</span>
                            @endif

                            <a href="{{ $orders->url($last) }}" class="orders-pager-btn">
                                {{ $last }}
                            </a>
                        @endif

                        @if($orders->hasMorePages())
                            <a
                                href="{{ $orders->nextPageUrl() }}"
                                class="orders-pager-btn"
                                aria-label="Next page"
                            >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="orders-pager-btn disabled" aria-disabled="true">
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif

        </div>
    </section>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('orders-filter-form');
    const results = document.getElementById('orders-results');
    const clearButton = document.getElementById('orders-clear-filter');

    if (!filterForm || !results) return;

    const filterFields = ['search', 'status', 'payment', 'date'];
    let requestNumber = 0;

    function updateClearButton() {
        const hasFilters = filterFields.some(function (name) {
            const field = filterForm.elements.namedItem(name);
            return field && field.value.trim() !== '';
        });

        if (clearButton) {
            clearButton.hidden = !hasFilters;
        }
    }

    function buildUrl() {
        const url = new URL(filterForm.action, window.location.origin);

        new FormData(filterForm).forEach(function (value, key) {
            if (String(value).trim() !== '') {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        url.searchParams.delete('page');

        return url;
    }

    async function loadOrders(url, pushState = true) {
        const currentRequest = ++requestNumber;
        results.style.opacity = '0.55';

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error('Orders could not be loaded.');
            }

            const html = await response.text();

            if (currentRequest !== requestNumber) return;

            const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
            const newResults = parsedDocument.getElementById('orders-results');
            const newForm = parsedDocument.getElementById('orders-filter-form');

            if (!newResults || !newForm) {
                window.location.href = url.href;
                return;
            }

            results.innerHTML = newResults.innerHTML;

            filterFields.forEach(function (name) {
                const currentField = filterForm.elements.namedItem(name);
                const newField = newForm.elements.namedItem(name);

                if (currentField && newField) {
                    currentField.value = newField.value;
                }
            });

            if (pushState) {
                window.history.pushState({}, '', url.href);
            }

            updateClearButton();
        } catch (error) {
            if (currentRequest === requestNumber) {
                window.location.href = url.href;
            }
        } finally {
            if (currentRequest === requestNumber) {
                results.style.opacity = '1';
            }
        }
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        loadOrders(buildUrl());
    });

    filterForm.addEventListener('input', updateClearButton);
    filterForm.addEventListener('change', updateClearButton);

    if (clearButton) {
        clearButton.addEventListener('click', function (event) {
            event.preventDefault();
            filterForm.reset();
            updateClearButton();

            loadOrders(new URL(filterForm.action, window.location.origin));
        });
    }

    results.addEventListener('click', function (event) {
        const link = event.target.closest('.orders-pagination-pages a');

        if (!link) return;

        event.preventDefault();
        loadOrders(new URL(link.href, window.location.origin));
    });

    window.addEventListener('popstate', function () {
        loadOrders(new URL(window.location.href), false);
    });

    results.addEventListener('submit', async function (event) {
        const form = event.target.closest('.order-delete-form');

        if (!form) return;

        event.preventDefault();

        const row = form.closest('tr');
        const button = form.querySelector('button');

        const result = await Swal.fire({
            title: 'Are you sure?',
            text: 'This order will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (!result.isConfirmed) return;

        if (button) button.disabled = true;

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfInput = form.querySelector('input[name="_token"]');
            const csrfToken = csrfMeta?.getAttribute('content') || csrfInput?.value;

            if (!csrfToken) {
                throw new Error('CSRF token not found.');
            }

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: new URLSearchParams({
                    _token: csrfToken,
                    _method: 'DELETE'
                })
            });

            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json')
                ? await response.json()
                : {};

            if (!response.ok || data.success !== true) {
                throw new Error(data.message || 'This order cannot be deleted.');
            }

            if (row) {
                row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';

                setTimeout(function () {
                    row.remove();

                    const remainingRows = results.querySelectorAll(
                        '.orders-table tbody tr[id^="order-row-"]'
                    );

                    if (remainingRows.length === 0) {
                        loadOrders(new URL(window.location.href), false);
                    }
                }, 300);
            }

            await Swal.fire({
                icon: 'success',
                title: 'Order deleted',
                text: data.message || 'The order has been deleted successfully.',
                timer: 1600,
                showConfirmButton: false
            });
        } catch (error) {
            if (button) button.disabled = false;

            await Swal.fire({
                icon: 'error',
                title: 'Delete failed',
                text: error.message || 'Unable to delete the order.',
                confirmButtonColor: '#2563eb'
            });
        }
    });

    updateClearButton();
});
</script>
@endpush

