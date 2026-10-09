
@extends('layout.admin.master')

@section('title', 'Refunds')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/refunds.css') }}">
@endpush

@section('content')
<div class="refunds-page">

    <section class="refund-hero">
        <div class="refund-hero-content">
            <span class="refund-hero-badge">
                <i class="bi bi-arrow-counterclockwise"></i>
                Payments Recovery
            </span>

            <h1>Every refund, under control.</h1>

            <p>
                Manage customer refund requests, review decisions and
                track returned payments from one organised workspace.
            </p>
        </div>

        <div class="refund-hero-mark" aria-hidden="true">
            <i class="bi bi-receipt-cutoff"></i>
        </div>
    </section>

    <section class="refund-stats">
        <div class="refund-stat stat-blue">
            <div class="refund-stat-content">
                <span>Total Refunds</span>
                <strong>{{ number_format($stats['total']) }}</strong>
            </div>
            <div class="refund-stat-icon">
                <i class="bi bi-receipt"></i>
            </div>
        </div>

        <div class="refund-stat stat-orange">
            <div class="refund-stat-content">
                <span>Pending</span>
                <strong>{{ number_format($stats['pending']) }}</strong>
            </div>
            <div class="refund-stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>

        <div class="refund-stat stat-green">
            <div class="refund-stat-content">
                <span>Processed</span>
                <strong>{{ number_format($stats['processed']) }}</strong>
            </div>
            <div class="refund-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="refund-stat stat-purple">
            <div class="refund-stat-content">
                <span>Refunded Amount</span>
                <strong>${{ number_format($stats['amount'], 2) }}</strong>
            </div>
            <div class="refund-stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </section>

    <section class="refund-panel">
        <div class="refund-panel-header">
            <div class="refund-panel-heading">
                <span class="eyebrow">Refund Directory</span>
                <h5>All Refunds</h5>
                <p>Search, filter and review every customer refund request.</p>
            </div>

            <div class="refund-hero-actions">
                <a href="{{ route('admin.refunds.create') }}" class="refund-add-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Refund</span>
                </a>
            </div>
        </div>

        <form
            method="GET"
            action="{{ route('admin.refunds.index') }}"
            class="refund-filters"
            id="refund-filters"
        >
            <div class="refund-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    name="search"
                    id="refund-search"
                    value="{{ request('search') }}"
                    placeholder="Refund number, order, customer..."
                    autocomplete="off"
                    aria-label="Search refunds"
                >
            </div>

            <select
                name="status"
                id="refund-status-filter"
                class="refund-select"
                aria-label="Refund status"
            >
                <option value="">All Status</option>
                @foreach (['pending', 'approved', 'rejected', 'processed', 'cancelled'] as $item)
                    <option value="{{ $item }}" @selected(request('status') === $item)>
                        {{ ucfirst($item) }}
                    </option>
                @endforeach
            </select>

            <select
                name="sort"
                id="refund-sort-filter"
                class="refund-select"
                aria-label="Sort refunds"
            >
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>
                    Newest
                </option>
                <option value="oldest" @selected(request('sort') === 'oldest')>
                    Oldest
                </option>
                <option value="highest" @selected(request('sort') === 'highest')>
                    Highest Amount
                </option>
                <option value="lowest" @selected(request('sort') === 'lowest')>
                    Lowest Amount
                </option>
            </select>

            <div class="refund-filter-actions">
                <button type="submit" class="refund-filter-btn">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>

                <button
                    type="button"
                    class="refund-reset-btn"
                    id="refund-reset"
                    @if (!request('search') && !request('status') && request('sort', 'newest') === 'newest') hidden @endif
                >
                    <i class="bi bi-x-lg"></i>
                    <span>Clear</span>
                </button>
            </div>
        </form>

        <div id="refund-feedback" role="status" aria-live="polite"></div>

        <div class="refund-table-wrap" id="refund-table-wrap">
            <table class="refund-table">
                <thead>
                    <tr>
                        <th class="refund-col-number">Refund</th>
                        <th class="refund-col-order">Order</th>
                        <th class="refund-col-customer">Customer</th>
                        <th class="refund-col-amount">Amount</th>
                        <th class="refund-col-reason">Reason</th>
                        <th class="refund-col-status">Status</th>
                        <th class="refund-col-date">Date</th>
                        <th class="refund-col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($refunds as $refund)
                        @php
                            $customerName = trim(
                                ($refund->user->first_name ?? '') . ' ' .
                                ($refund->user->last_name ?? '')
                            );

                            $customerName = $customerName ?: (
                                $refund->user->name ??
                                $refund->user->username ??
                                'Customer'
                            );

                            $customerInitial = strtoupper(mb_substr($customerName, 0, 1));
                        @endphp

                        <tr data-refund-row="{{ $refund->id }}">
                            <td>
                                <div class="refund-number-cell">
                                    <div class="refund-number-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $refund->refund_number }}</strong>
                                        <small>Refund Request</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="refund-order-number">
                                    #{{ $refund->order?->order_number ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="refund-customer-cell">
                                    <div class="refund-customer-avatar">
                                        {{ $customerInitial }}
                                    </div>
                                    <div class="refund-customer-info">
                                        <strong title="{{ $customerName }}">
                                            {{ $customerName }}
                                        </strong>
                                        <small title="{{ $refund->user?->email ?? '' }}">
                                            {{ $refund->user?->email ?? '—' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <strong class="refund-amount">
                                    ${{ number_format($refund->amount, 2) }}
                                </strong>
                            </td>

                            <td>
                                <span class="refund-reason" title="{{ $refund->reason }}">
                                    {{ $refund->reason }}
                                </span>
                            </td>

                            <td>
                                <span class="refund-status status-{{ $refund->status }}">
                                    <i class="bi bi-circle-fill"></i>
                                    {{ ucfirst($refund->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="refund-date-block">
                                    <span class="refund-date">
                                        {{ $refund->requested_at?->format('d M Y') ?? '—' }}
                                    </span>
                                    <small>
                                        {{ $refund->requested_at?->format('H:i') ?? '' }}
                                    </small>
                                </div>
                            </td>

                            <td class="refund-row">
                                <div class="refund-actions">
                                    <a
                                        href="{{ route('admin.refunds.show', $refund) }}"
                                        class="refund-action action-view"
                                        title="View refund"
                                        aria-label="View refund"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if ($refund->status !== 'processed')
                                        <a
                                            href="{{ route('admin.refunds.edit', $refund) }}"
                                            class="refund-action action-edit"
                                            title="Edit refund"
                                            aria-label="Edit refund"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($refund->status === 'pending')
                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.status', $refund) }}"
                                            class="refund-status-form"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">

                                            <button
                                                type="submit"
                                                class="refund-action action-approve"
                                                title="Approve refund"
                                                aria-label="Approve refund"
                                            >
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.status', $refund) }}"
                                            class="refund-status-form"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">

                                            <button
                                                type="submit"
                                                class="refund-action action-reject"
                                                title="Reject refund"
                                                aria-label="Reject refund"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @elseif ($refund->status === 'approved')
                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.status', $refund) }}"
                                            class="refund-status-form"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="processed">

                                            <button
                                                type="submit"
                                                class="refund-action action-process"
                                                title="Process refund"
                                                aria-label="Process refund"
                                            >
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($refund->status !== 'processed')
                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.destroy', $refund) }}"
                                            class="delete-refund-form"
                                            data-refund-number="{{ $refund->refund_number }}"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="refund-action action-delete"
                                                title="Delete refund"
                                                aria-label="Delete refund"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="refund-empty">
                                    <div class="refund-empty-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <strong>No refunds found</strong>
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

        @if ($refunds->hasPages())
            @php
                $refunds->appends(request()->query());
                $current = $refunds->currentPage();
                $last = $refunds->lastPage();
                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
            @endphp

            <div class="refund-pagination" id="refund-pagination">
                <div class="refund-pagination-info">
                    Showing <strong>{{ $refunds->firstItem() }}</strong>
                    to <strong>{{ $refunds->lastItem() }}</strong>
                    of <strong>{{ $refunds->total() }}</strong> refunds
                </div>

                <nav class="refund-pagination-pages" aria-label="Refund pagination">
                    @if ($refunds->onFirstPage())
                        <span class="refund-pager-btn disabled" aria-disabled="true">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $refunds->previousPageUrl() }}" class="refund-pager-btn" aria-label="Previous page">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    @if ($start > 1)
                        <a href="{{ $refunds->url(1) }}" class="refund-pager-btn">1</a>
                        @if ($start > 2)
                            <span class="refund-pager-dots">…</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page === $current)
                            <span class="refund-pager-btn active" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $refunds->url($page) }}" class="refund-pager-btn">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="refund-pager-dots">…</span>
                        @endif
                        <a href="{{ $refunds->url($last) }}" class="refund-pager-btn">
                            {{ $last }}
                        </a>
                    @endif

                    @if ($refunds->hasMorePages())
                        <a href="{{ $refunds->nextPageUrl() }}" class="refund-pager-btn" aria-label="Next page">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @else
                        <span class="refund-pager-btn disabled" aria-disabled="true">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    @endif
                </nav>
            </div>
        @endif
    </section>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const filterForm = document.getElementById('refund-filters');
    const searchInput = document.getElementById('refund-search');
    const statusFilter = document.getElementById('refund-status-filter');
    const sortFilter = document.getElementById('refund-sort-filter');
    const resetButton = document.getElementById('refund-reset');
    const feedback = document.getElementById('refund-feedback');

    if (!filterForm) return;

    let activeController = null;
    let requestSequence = 0;
    let searchTimer = null;

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content ||
        filterForm.querySelector('input[name="_token"]')?.value ||
        '';

    function notify(options) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire(options);
        }

        if (options.showCancelButton) {
            return Promise.resolve({
                isConfirmed: confirm(
                    (options.title || '') + '\n\n' + (options.text || '')
                )
            });
        }

        alert(options.text || options.title || '');
        return Promise.resolve({ isConfirmed: true });
    }

    function showFeedback(message, type = 'success') {
        if (!feedback) return;

        feedback.textContent = message;
        feedback.className = 'alert alert-' + type;
        feedback.hidden = false;

        clearTimeout(showFeedback.timer);
        showFeedback.timer = setTimeout(function () {
            feedback.hidden = true;
        }, 4000);
    }

    function buildUrl(pageUrl = null) {
        const url = new URL(
            pageUrl || filterForm.action,
            window.location.origin
        );

        const page = pageUrl
            ? new URL(pageUrl, window.location.origin).searchParams.get('page')
            : null;

        url.search = '';

        new FormData(filterForm).forEach(function (value, key) {
            if (String(value).trim() !== '') {
                url.searchParams.set(key, value);
            }
        });

        if (page) url.searchParams.set('page', page);

        return url;
    }

    function syncFilters(doc) {
        const form = doc.getElementById('refund-filters');
        if (!form) return;

        searchInput.value = form.querySelector('[name="search"]')?.value || '';
        statusFilter.value = form.querySelector('[name="status"]')?.value || '';
        sortFilter.value = form.querySelector('[name="sort"]')?.value || 'newest';

        const reset = doc.getElementById('refund-reset');
        resetButton.hidden = !reset || reset.hidden;
    }

    async function loadRefunds(pageUrl = null, options = {}) {
        const {
            updateHistory = true,
            preserveFilters = false,
            successMessage = null
        } = options;

        activeController?.abort();

        activeController = new AbortController();

        const controller = activeController;
        const sequence = ++requestSequence;
        const url = buildUrl(pageUrl);

        const table = document.getElementById('refund-table-wrap');
        if (table) {
            table.setAttribute('aria-busy', 'true');
            table.style.opacity = '0.55';
            table.style.pointerEvents = 'none';
        }

        try {
            const response = await fetch(url.toString(), {
                method: 'GET',
                credentials: 'same-origin',
                signal: controller.signal,
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Unable to load refunds. HTTP ' + response.status);
            }

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const newTable = doc.getElementById('refund-table-wrap');
            const newStats = doc.querySelector('.refund-stats');
            const newPagination = doc.getElementById('refund-pagination');

            if (!newTable || !newStats) {
                console.error('Unexpected refund list response:', html);
                throw new Error(
                    'The server returned an unexpected page. Check the Laravel logs.'
                );
            }

            if (sequence !== requestSequence) return;

            document.getElementById('refund-table-wrap')?.replaceWith(newTable);
            document.querySelector('.refund-stats')?.replaceWith(newStats);

            const oldPagination = document.getElementById('refund-pagination');

            if (newPagination) {
                if (oldPagination) {
                    oldPagination.replaceWith(newPagination);
                } else {
                    document.querySelector('.refund-panel')?.appendChild(newPagination);
                }
            } else {
                oldPagination?.remove();
            }

            if (!preserveFilters) syncFilters(doc);

            if (updateHistory) {
                const finalUrl = new URL(url.toString());
                history.pushState({}, '', finalUrl.pathname + finalUrl.search);
            }

            if (successMessage) {
                showFeedback(successMessage);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Refund list error:', error);
                showFeedback(error.message || 'Unable to load refunds.', 'danger');
            }
        } finally {
            if (sequence === requestSequence) {
                const currentTable = document.getElementById('refund-table-wrap');

                if (currentTable) {
                    currentTable.removeAttribute('aria-busy');
                    currentTable.style.opacity = '';
                    currentTable.style.pointerEvents = '';
                }
            }
        }
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const url = buildUrl();
        url.searchParams.delete('page');

        loadRefunds(url.toString());
    });

    // Search updates automatically without a full page reload.
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {
            const url = buildUrl();
            url.searchParams.delete('page');
            loadRefunds(url.toString());
        }, 400);
    });

    statusFilter.addEventListener('change', function () {
        const url = buildUrl();
        url.searchParams.delete('page');
        loadRefunds(url.toString());
    });

    sortFilter.addEventListener('change', function () {
        const url = buildUrl();
        url.searchParams.delete('page');
        loadRefunds(url.toString());
    });

    resetButton.addEventListener('click', function () {
        searchInput.value = '';
        statusFilter.value = '';
        sortFilter.value = 'newest';

        loadRefunds(filterForm.action);
    });

    window.addEventListener('popstate', function () {
        const url = new URL(window.location.href);

        searchInput.value = url.searchParams.get('search') || '';
        statusFilter.value = url.searchParams.get('status') || '';
        sortFilter.value = url.searchParams.get('sort') || 'newest';

        loadRefunds(url.toString(), {
            updateHistory: false,
            preserveFilters: true
        });
    });

    // AJAX pagination.
    document.addEventListener('click', function (event) {
        const link = event.target.closest('#refund-pagination a[href]');

        if (!link) return;

        const url = new URL(link.href, window.location.origin);

        if (url.origin !== window.location.origin) return;

        event.preventDefault();
        loadRefunds(link.href);
    });

    async function sendAction(form, method, actionName) {
        const body = new URLSearchParams();

        body.set('_token', csrfToken);
        body.set('_method', method);

        form.querySelectorAll('input[name="status"]').forEach(function (input) {
            body.set('status', input.value);
        });

        const response = await fetch(form.action, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: body.toString()
        });

        const contentType = response.headers.get('content-type') || '';

        if (!contentType.includes('application/json')) {
            const responseText = await response.text();

            console.error(actionName + ' response:', responseText);

            throw new Error(
                response.redirected
                    ? 'The server redirected this request instead of returning JSON. Check the controller.'
                    : 'The server returned HTML instead of JSON. Check the controller and Laravel logs.'
            );
        }

        const data = await response.json();

        if (!response.ok || data.success !== true) {
            throw new Error(data.message || actionName + ' failed.');
        }

        return data;
    }

    // Event delegation means actions continue working after the table is replaced.
    document.addEventListener('submit', async function (event) {
        const statusForm = event.target.closest('.refund-status-form');
        const deleteForm = event.target.closest('.delete-refund-form');
        const form = statusForm || deleteForm;

        if (!form) return;

        event.preventDefault();

        if (form.dataset.submitting === 'true') return;

        const button = form.querySelector('button[type="submit"]');
        const originalHtml = button?.innerHTML || '';
        const isDelete = Boolean(deleteForm);

        if (isDelete) {
            const refundNumber = form.dataset.refundNumber || 'this refund';

            const confirmation = await notify({
                title: 'Delete refund?',
                text: 'Are you sure you want to permanently delete ' + refundNumber + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete refund',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#bd3d53',
                reverseButtons: true
            });

            if (!confirmation.isConfirmed) return;
        } else {
            const status = form.querySelector('input[name="status"]')?.value;

            const config = {
                approved: {
                    title: 'Approve refund?',
                    text: 'Are you sure you want to approve this refund?',
                    button: 'Approve refund',
                    color: '#16a34a'
                },
                rejected: {
                    title: 'Reject refund?',
                    text: 'Are you sure you want to reject this refund?',
                    button: 'Reject refund',
                    color: '#dc2626'
                },
                processed: {
                    title: 'Process refund?',
                    text: 'Are you sure you want to process this refund?',
                    button: 'Process refund',
                    color: '#2563eb'
                }
            }[status] || {
                title: 'Update refund?',
                text: 'Confirm this refund status change.',
                button: 'Confirm',
                color: '#2563eb'
            };

            const confirmation = await notify({
                title: config.title,
                text: config.text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: config.button,
                cancelButtonText: 'Cancel',
                confirmButtonColor: config.color,
                reverseButtons: true
            });

            if (!confirmation.isConfirmed) return;
        }

        form.dataset.submitting = 'true';

        if (button) {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>';
        }

        try {
            const result = await sendAction(
                form,
                isDelete ? 'DELETE' : 'PATCH',
                isDelete ? 'Delete refund' : 'Update refund status'
            );

            const message = result.message || (
                isDelete
                    ? 'Refund deleted successfully.'
                    : 'Refund status updated successfully.'
            );

            await loadRefunds(window.location.href, {
                updateHistory: false,
                successMessage: message
            });

            if (typeof Swal !== 'undefined') {
                await Swal.fire({
                    icon: 'success',
                    title: isDelete ? 'Refund deleted' : 'Refund updated',
                    text: message,
                    timer: 1300,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            console.error('Refund action error:', error);

            await notify({
                icon: 'error',
                title: isDelete ? 'Delete failed' : 'Update failed',
                text: error.message || 'Something went wrong.',
                confirmButtonColor: '#2563eb'
            });
        } finally {
            delete form.dataset.submitting;

            if (button && button.isConnected) {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        }
    });
});
</script>
@endpush