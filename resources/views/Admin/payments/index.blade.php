@extends('layout.admin.master')

@section('title', 'Payments')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/payments.css') }}">
@endpush

@section('content')
<div class="dashboard-section payments-page">

    {{-- ================= HERO ================= --}}
    <section class="payments-hero">
        <div class="payments-hero-content">
            <span class="payments-hero-badge">
                <i class="bi bi-credit-card"></i>
                Payment Operations
            </span>
            <h1>Every payment, under control.</h1>
            <p>
                Manage transactions, payment methods and payment status
                from one organised workspace.
            </p>
        </div>
        <div class="payments-hero-mark" aria-hidden="true">
            <i class="bi bi-wallet2"></i>
        </div>
    </section>

    {{-- ================= SUCCESS / ERROR ================= --}}
    @if(session('success'))
        <div class="payments-alert payments-alert-success">
            <div class="payments-alert-content">
                <i class="bi bi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button
                type="button"
                class="payments-alert-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="payments-alert payments-alert-error">
            <div class="payments-alert-content">
                <i class="bi bi-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button
                type="button"
                class="payments-alert-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- ================= STATISTICS ================= --}}
    <section class="payments-stats">
        <div class="payment-stat-card stat-blue">
            <div class="payment-stat-content">
                <span>Total Payments</span>
                <strong>{{ $totalPayments }}</strong>
            </div>
            <div class="payment-stat-icon">
                <i class="bi bi-credit-card"></i>
            </div>
        </div>

        <div class="payment-stat-card stat-orange">
            <div class="payment-stat-content">
                <span>Pending</span>
                <strong>{{ $pendingPayments }}</strong>
            </div>
            <div class="payment-stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>

        <div class="payment-stat-card stat-green">
            <div class="payment-stat-content">
                <span>Paid</span>
                <strong>{{ $paidPayments }}</strong>
            </div>
            <div class="payment-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="payment-stat-card stat-purple">
            <div class="payment-stat-content">
                <span>Revenue</span>
                <strong>${{ number_format($totalRevenue, 2) }}</strong>
            </div>
            <div class="payment-stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </section>

    {{-- ================= MAIN PANEL ================= --}}
    <section class="dashboard-panel payments-panel">

        <div class="payments-panel-header">
            <div class="payments-heading-content">
                <span class="eyebrow">Payment directory</span>
                <h5>All Payments</h5>
                <p>
                    Search, filter and review every marketplace payment.
                </p>
            </div>

            <div class="payments-header-action">
                <a
                    href="{{ route('admin.payments.create') }}"
                    class="payments-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Payment</span>
                </a>
            </div>
        </div>

        {{-- ================= FILTERS ================= --}}
        <form
            id="payments-filter-form"
            method="GET"
            action="{{ route('admin.payments.index') }}"
            class="payment-filters"
        >
            <div class="payment-filter-group payment-filter-search">
                <label for="payment-search">Search</label>
                <div class="payment-search-field">
                    <i class="bi bi-search"></i>
                    <input
                        id="payment-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Transaction ID, order number..."
                        autocomplete="off"
                    >
                </div>
            </div>

            <div class="payment-filter-group">
                <label for="payment-status">Payment Status</label>
                <select
                    id="payment-status"
                    name="payment_status"
                    class="payment-filter-select"
                >
                    <option value="">All Status</option>
                    <option value="pending" @selected(request('payment_status') === 'pending')>
                        Pending
                    </option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>
                        Paid
                    </option>
                    <option value="failed" @selected(request('payment_status') === 'failed')>
                        Failed
                    </option>
                    <option value="refunded" @selected(request('payment_status') === 'refunded')>
                        Refunded
                    </option>
                </select>
            </div>

            <div class="payment-filter-group">
                <label for="payment-method">Payment Method</label>
                <select
                    id="payment-method"
                    name="payment_method"
                    class="payment-filter-select"
                >
                    <option value="">All Methods</option>
                    <option value="cash_on_delivery" @selected(request('payment_method') === 'cash_on_delivery')>
                        Cash On Delivery
                    </option>
                    <option value="credit_card" @selected(request('payment_method') === 'credit_card')>
                        Credit Card
                    </option>
                    <option value="debit_card" @selected(request('payment_method') === 'debit_card')>
                        Debit Card
                    </option>
                    <option value="paypal" @selected(request('payment_method') === 'paypal')>
                        PayPal
                    </option>
                </select>
            </div>

            <div class="payment-filter-group">
                <label for="payment-date">Payment Date</label>
                <div class="payment-date-field">
                    <i class="bi bi-calendar3"></i>
                    <input
                        id="payment-date"
                        type="date"
                        name="date"
                        value="{{ request('date') }}"
                    >
                </div>
            </div>

            <div class="payment-filter-actions">
                <button type="submit" class="payment-filter-btn">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>

                <a
                    id="payments-clear-filter"
                    href="{{ route('admin.payments.index') }}"
                    class="payment-clear-filter"
                    @if(!request()->hasAny(['search', 'payment_status', 'payment_method', 'date'])) hidden @endif
                >
                    <i class="bi bi-x-lg"></i>
                    <span>Clear</span>
                </a>
            </div>
        </form>

        {{-- ================= AJAX RESULTS ================= --}}
        <div id="payments-results">

            {{-- ================= PAYMENTS TABLE ================= --}}
            <div class="payments-table-wrap">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th class="payments-col-id">#</th>
                            <th class="payments-col-transaction">Transaction</th>
                            <th class="payments-col-customer">Customer</th>
                            <th class="payments-col-order">Order</th>
                            <th class="payments-col-amount">Amount</th>
                            <th class="payments-col-method">Method</th>
                            <th class="payments-col-status">Status</th>
                            <th class="payments-col-date">Date</th>
                            <th class="payments-col-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $customerName = trim(
                                    ($payment->order->user->first_name ?? '') . ' ' .
                                    ($payment->order->user->last_name ?? '')
                                );

                                $customerName = $customerName ?: (
                                    $payment->order->user->name ??
                                    $payment->order->user->username ??
                                    'Customer'
                                );

                                $customerInitial = strtoupper(
                                    mb_substr($customerName, 0, 1)
                                );

                                $paymentStatus = strtolower(
                                    $payment->payment_status ?? 'pending'
                                );

                                $knownStatuses = [
                                    'paid',
                                    'pending',
                                    'failed',
                                    'refunded'
                                ];

                                $paymentStatusClass = in_array(
                                    $paymentStatus,
                                    $knownStatuses
                                )
                                    ? 'payment-status-' . $paymentStatus
                                    : 'payment-status-default';
                            @endphp

                            <tr id="payment-row-{{ $payment->id }}">
                                <td>
                                    <span class="payment-id">
                                        #{{ $payment->id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="payment-transaction-cell">
                                        <div class="payment-transaction-icon">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        <div class="payment-transaction-info">
                                            <strong title="{{ $payment->transaction_id }}">
                                                {{ $payment->transaction_id }}
                                            </strong>
                                            <small>Transaction</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="payment-customer-cell">
                                        <div class="payment-customer-avatar">
                                            {{ $customerInitial }}
                                        </div>
                                        <div class="payment-customer-info">
                                            <strong title="{{ $customerName }}">
                                                {{ $customerName }}
                                            </strong>
                                            <small title="{{ $payment->order->user->email ?? '' }}">
                                                {{ $payment->order->user->email ?? '—' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="payment-order-cell">
                                        <div class="payment-order-icon">
                                            <i class="bi bi-cart-check"></i>
                                        </div>
                                        <div class="payment-order-info">
                                            <strong title="{{ $payment->order->order_number ?? '#' . $payment->order_id }}">
                                                {{ $payment->order->order_number ?? '#' . $payment->order_id }}
                                            </strong>
                                            <small title="{{ $payment->order->book->title ?? 'Unknown book' }}">
                                                {{ $payment->order->book->title ?? 'Unknown book' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="payment-amount">
                                        ${{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="payment-method">
                                        @switch($payment->payment_method)
                                            @case('cash_on_delivery')
                                                <i class="bi bi-cash-stack"></i>
                                                Cash On Delivery
                                                @break

                                            @case('credit_card')
                                                <i class="bi bi-credit-card"></i>
                                                Credit Card
                                                @break

                                            @case('debit_card')
                                                <i class="bi bi-credit-card-2-front"></i>
                                                Debit Card
                                                @break

                                            @case('paypal')
                                                <i class="bi bi-paypal"></i>
                                                PayPal
                                                @break

                                            @default
                                                <i class="bi bi-wallet2"></i>
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Unknown')) }}
                                        @endswitch
                                    </span>
                                </td>

                                <td>
                                    <span class="payment-status-pill {{ $paymentStatusClass }}">
                                        <i class="bi bi-circle-fill"></i>
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="payment-date-block">
                                        <span class="payment-date">
                                            {{ $payment->created_at->format('d M Y') }}
                                        </span>
                                        <small class="payment-time">
                                            {{ $payment->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    <div class="payment-actions">
                                        <a
                                            href="{{ route('admin.payments.show', $payment->id) }}"
                                            class="payment-action-btn payment-view-btn"
                                            title="View payment"
                                            aria-label="View payment #{{ $payment->id }}"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.payments.edit', $payment->id) }}"
                                            class="payment-action-btn payment-edit-btn"
                                            title="Edit payment"
                                            aria-label="Edit payment #{{ $payment->id }}"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.payments.destroy', $payment->id) }}"
                                            method="POST"
                                            class="payment-delete-form"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="payment-action-btn payment-delete-btn"
                                                title="Delete payment"
                                                aria-label="Delete payment #{{ $payment->id }}"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="payments-empty-state">
                                        <div class="payments-empty-icon">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </div>
                                        <strong>No payments found</strong>
                                        <span>
                                            Try changing your filters or search criteria.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ================= PAGINATION ================= --}}
            @if($payments->hasPages())
                @php
                    $payments->appends(request()->query());

                    $current = $payments->currentPage();
                    $last = $payments->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);
                @endphp

                <div class="payments-pagination">
                    <div class="payments-pagination-info">
                        Showing
                        <strong>{{ $payments->firstItem() }}</strong>
                        to
                        <strong>{{ $payments->lastItem() }}</strong>
                        of
                        <strong>{{ $payments->total() }}</strong>
                        payments
                    </div>

                    <nav
                        class="payments-pagination-pages"
                        aria-label="Payments pagination"
                    >
                        @if($payments->onFirstPage())
                            <span class="payments-pager-btn disabled" aria-disabled="true">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        @else
                            <a
                                href="{{ $payments->previousPageUrl() }}"
                                class="payments-pager-btn"
                                aria-label="Previous page"
                            >
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif

                        @if($start > 1)
                            <a href="{{ $payments->url(1) }}" class="payments-pager-btn">
                                1
                            </a>

                            @if($start > 2)
                                <span class="payments-pager-dots">…</span>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page === $current)
                                <span class="payments-pager-btn active" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $payments->url($page) }}" class="payments-pager-btn">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="payments-pager-dots">…</span>
                            @endif

                            <a href="{{ $payments->url($last) }}" class="payments-pager-btn">
                                {{ $last }}
                            </a>
                        @endif

                        @if($payments->hasMorePages())
                            <a
                                href="{{ $payments->nextPageUrl() }}"
                                class="payments-pager-btn"
                                aria-label="Next page"
                            >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="payments-pager-btn disabled" aria-disabled="true">
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
    const form = document.getElementById('payments-filter-form');
    const results = document.getElementById('payments-results');
    const clearButton = document.getElementById('payments-clear-filter');

    if (!form || !results || !clearButton) {
        return;
    }

    let requestController = null;

    function hasActiveFilters() {
        return Array.from(form.querySelectorAll('[name]')).some(function (input) {
            return input.value.trim() !== '';
        });
    }

    function updateClearButton() {
        clearButton.hidden = !hasActiveFilters();
    }

    function syncFiltersFromUrl(url) {
        const params = new URL(url, window.location.origin).searchParams;

        form.querySelectorAll('[name]').forEach(function (input) {
            input.value = params.get(input.name) || '';
        });

        updateClearButton();
    }

    async function loadResults(url, options = {}) {
        if (requestController) {
            requestController.abort();
        }

        const controller = new AbortController();
        requestController = controller;

        results.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error('Unable to load payments.');
            }

            const html = await response.text();
            const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
            const newResults = parsedDocument.getElementById('payments-results');

            if (!newResults) {
                throw new Error('Payments results could not be found in the response.');
            }

            results.innerHTML = newResults.innerHTML;

            if (options.updateHistory) {
                window.history.pushState({}, '', url);
            }

            if (options.syncFilters) {
                syncFiltersFromUrl(url);
            } else {
                updateClearButton();
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: error.message || 'Unable to load payments.',
                    confirmButtonColor: '#2563eb'
                });
            }
        } finally {
            if (requestController === controller) {
                requestController = null;
                results.removeAttribute('aria-busy');
            }
        }
    }

    // AJAX filter
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const url = new URL(form.action, window.location.origin);
        const params = new URLSearchParams(new FormData(form));

        url.search = '';

        params.forEach(function (value, key) {
            if (value.trim() !== '') {
                url.searchParams.set(key, value);
            }
        });

        url.searchParams.delete('page');

        loadResults(url.toString(), {
            updateHistory: true
        });
    });

    // Clear filters
    clearButton.addEventListener('click', function (event) {
        event.preventDefault();

        form.reset();

        loadResults(clearButton.href, {
            updateHistory: true,
            syncFilters: true
        });
    });

    // AJAX pagination
    results.addEventListener('click', function (event) {
        const link = event.target.closest('.payments-pagination-pages a');

        if (!link) {
            return;
        }

        event.preventDefault();

        loadResults(link.href, {
            updateHistory: true,
            syncFilters: true
        });
    });

    // AJAX delete
    results.addEventListener('submit', async function (event) {
        const deleteForm = event.target.closest('.payment-delete-form');

        if (!deleteForm) {
            return;
        }

        event.preventDefault();

        const button = deleteForm.querySelector('button');

        const confirmation = await Swal.fire({
            title: 'Are you sure?',
            text: 'This payment will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (!confirmation.isConfirmed) {
            return;
        }

        if (button) {
            button.disabled = true;
        }

        try {
            const csrfInput = deleteForm.querySelector('input[name="_token"]');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfInput?.value || csrfMeta?.content || '';

            const response = await fetch(deleteForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: new URLSearchParams({
                    _token: csrfToken,
                    _method: 'DELETE'
                })
            });

            const contentType = response.headers.get('content-type') || '';
            let data = {};

            if (contentType.includes('application/json')) {
                data = await response.json();
            }

            if (!response.ok || data.success === false) {
                throw new Error(data.message || 'Unable to delete the payment.');
            }

            await Swal.fire({
                icon: 'success',
                title: 'Payment deleted',
                text: data.message || 'The payment has been deleted successfully.',
                timer: 1600,
                showConfirmButton: false
            });

            const currentUrl = new URL(window.location.href);
            const visibleRows = results.querySelectorAll('tbody tr[id^="payment-row-"]');

            if (
                visibleRows.length <= 1 &&
                currentUrl.searchParams.has('page') &&
                Number(currentUrl.searchParams.get('page')) > 1
            ) {
                currentUrl.searchParams.set(
                    'page',
                    String(Number(currentUrl.searchParams.get('page')) - 1)
                );
            }

            await loadResults(currentUrl.toString());
        } catch (error) {
            if (button) {
                button.disabled = false;
            }

            Swal.fire({
                icon: 'error',
                title: 'Delete failed',
                text: error.message || 'Unable to delete the payment.',
                confirmButtonColor: '#2563eb'
            });
        }
    });

    // Browser back / forward
    window.addEventListener('popstate', function () {
        syncFiltersFromUrl(window.location.href);
        loadResults(window.location.href);
    });

    updateClearButton();
});
</script>
@endpush

