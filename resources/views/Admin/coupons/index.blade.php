@extends('layout.admin.master')

@section('title', 'Coupons')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/coupons.css') }}">
@endpush

@section('content')
<div class="dashboard-section coupons-page">

    {{-- HERO --}}
    <section class="coupons-hero">
        <div class="coupons-hero-content">
            <span class="coupons-hero-badge">
                <i class="bi bi-ticket-perforated"></i>
                Promotion Operations
            </span>
            <h1>Every promotion, under control.</h1>
            <p>
                Manage discount coupons, promotional offers and usage
                activity from one organised workspace.
            </p>
        </div>

        <div class="coupons-hero-mark" aria-hidden="true">
            <i class="bi bi-ticket-perforated"></i>
        </div>
    </section>

    {{-- STATISTICS --}}
    <section class="coupons-stats">
        <div class="coupon-stat-card stat-blue">
            <div class="coupon-stat-content">
                <span>Total Coupons</span>
                <strong>{{ $totalCoupons }}</strong>
            </div>
            <div class="coupon-stat-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>
        </div>

        <div class="coupon-stat-card stat-green">
            <div class="coupon-stat-content">
                <span>Active</span>
                <strong>{{ $activeCoupons }}</strong>
            </div>
            <div class="coupon-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="coupon-stat-card stat-orange">
            <div class="coupon-stat-content">
                <span>Expired</span>
                <strong>{{ $expiredCoupons }}</strong>
            </div>
            <div class="coupon-stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>

        <div class="coupon-stat-card stat-purple">
            <div class="coupon-stat-content">
                <span>Total Used</span>
                <strong>{{ $totalUsed }}</strong>
            </div>
            <div class="coupon-stat-icon">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </section>

    {{-- MAIN PANEL --}}
    <section class="dashboard-panel coupons-panel">

        <div class="coupons-panel-header">
            <div class="coupons-heading-content">
                <span class="eyebrow">Coupon directory</span>
                <h5>All Coupons</h5>
                <p>Search, filter and manage every promotional coupon.</p>
            </div>

            <div class="coupons-header-action">
                <a href="{{ route('admin.coupons.create') }}" class="coupons-add-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Coupon</span>
                </a>
            </div>
        </div>

        {{-- FILTERS --}}
        <form
            method="GET"
            action="{{ route('admin.coupons.index') }}"
            class="coupon-filters"
            id="coupon-filters"
        >
            <div class="coupon-filter-group coupon-filter-search">
                <label for="coupon-search">Search</label>
                <div class="coupon-search-field">
                    <i class="bi bi-search"></i>
                    <input
                        id="coupon-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search coupon code..."
                        autocomplete="off"
                    >
                </div>
            </div>

            <div class="coupon-filter-group">
                <label for="coupon-type">Discount Type</label>
                <select id="coupon-type" name="type" class="coupon-filter-select">
                    <option value="">All Types</option>
                    <option value="percentage" @selected(request('type') === 'percentage')>
                        Percentage
                    </option>
                    <option value="fixed" @selected(request('type') === 'fixed')>
                        Fixed Amount
                    </option>
                </select>
            </div>

            <div class="coupon-filter-group">
                <label for="coupon-status">Status</label>
                <select id="coupon-status" name="status" class="coupon-filter-select">
                    <option value="">All Status</option>
                    <option value="1" @selected(request('status') === '1')>
                        Active
                    </option>
                    <option value="0" @selected(request('status') === '0')>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="coupon-filter-actions">
                <button type="submit" class="coupon-filter-btn">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>

                <a
                    href="{{ route('admin.coupons.index') }}"
                    class="coupon-clear-filter"
                    id="coupon-reset"
                >
                    <i class="bi bi-x-lg"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>

        {{-- TABLE AND PAGINATION --}}
        <div id="coupons-results">
            <div class="coupons-table-wrap">
                <table class="coupons-table">
                    <thead>
                        <tr>
                            <th class="coupons-col-id">#</th>
                            <th class="coupons-col-code">Code</th>
                            <th class="coupons-col-discount">Discount</th>
                            <th class="coupons-col-minimum">Minimum Order</th>
                            <th class="coupons-col-usage">Usage</th>
                            <th class="coupons-col-validity">Validity</th>
                            <th class="coupons-col-status">Status</th>
                            <th class="coupons-col-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($coupons as $coupon)
                            @php
                                $couponStatus = 'inactive';

                                if ($coupon->status) {
                                    if ($coupon->expires_at->isPast()) {
                                        $couponStatus = 'expired';
                                    } elseif ($coupon->starts_at->isFuture()) {
                                        $couponStatus = 'scheduled';
                                    } else {
                                        $couponStatus = 'active';
                                    }
                                }
                            @endphp

                            <tr id="coupon-row-{{ $coupon->id }}">
                                <td>
                                    <span class="coupon-id">#{{ $coupon->id }}</span>
                                </td>

                                <td>
                                    <div class="coupon-code-cell">
                                        <div class="coupon-code-icon">
                                            <i class="bi bi-ticket-perforated"></i>
                                        </div>
                                        <div class="coupon-code-info">
                                            <strong title="{{ $coupon->code }}">
                                                {{ $coupon->code }}
                                            </strong>
                                            <small>Discount coupon</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="coupon-discount">
                                        @if($coupon->type === 'percentage')
                                            {{ number_format($coupon->value, 0) }}%
                                        @else
                                            ${{ number_format($coupon->value, 2) }}
                                        @endif
                                    </span>
                                    <small class="coupon-discount-type">
                                        {{ $coupon->type === 'percentage' ? 'Percentage' : 'Fixed Amount' }}
                                    </small>
                                </td>

                                <td>
                                    <span class="coupon-money">
                                        ${{ number_format($coupon->minimum_order_amount, 2) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="coupon-usage-block">
                                        <strong>{{ $coupon->used_count }}</strong>
                                        <span>/</span>
                                        <span>{{ $coupon->usage_limit ?? '∞' }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="coupon-date-block">
                                        <span class="coupon-date">
                                            {{ $coupon->starts_at->format('d M Y') }}
                                        </span>
                                        <small>
                                            to {{ $coupon->expires_at->format('d M Y') }}
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    <span class="coupon-status-pill coupon-status-{{ $couponStatus }}">
                                        <i class="bi bi-circle-fill"></i>
                                        {{ ucfirst($couponStatus) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="coupon-actions">
                                        <a
                                            href="{{ route('admin.coupons.show', $coupon->id) }}"
                                            class="coupon-action-btn coupon-view-btn"
                                            title="View coupon"
                                            aria-label="View coupon #{{ $coupon->id }}"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                            class="coupon-action-btn coupon-edit-btn"
                                            title="Edit coupon"
                                            aria-label="Edit coupon #{{ $coupon->id }}"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.coupons.toggle-status', $coupon->id) }}"
                                            method="POST"
                                            class="coupon-toggle-form"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="coupon-action-btn coupon-toggle-btn"
                                                title="Toggle status"
                                                aria-label="Toggle status for coupon #{{ $coupon->id }}"
                                            >
                                                @if($coupon->status)
                                                    <i class="bi bi-toggle-on"></i>
                                                @else
                                                    <i class="bi bi-toggle-off"></i>
                                                @endif
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                            method="POST"
                                            class="coupon-delete-form"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="coupon-action-btn coupon-delete-btn"
                                                title="Delete coupon"
                                                aria-label="Delete coupon #{{ $coupon->id }}"
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
                                    <div class="coupons-empty-state">
                                        <div class="coupons-empty-icon">
                                            <i class="bi bi-ticket-perforated"></i>
                                        </div>
                                        <strong>No coupons found</strong>
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

            {{-- PAGINATION --}}
            @if($coupons->hasPages())
                @php
                    $current = $coupons->currentPage();
                    $last = $coupons->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);
                @endphp

                <div class="coupons-pagination">
                    <div class="coupons-pagination-info">
                        Showing
                        <strong>{{ $coupons->firstItem() }}</strong>
                        to
                        <strong>{{ $coupons->lastItem() }}</strong>
                        of
                        <strong>{{ $coupons->total() }}</strong>
                        coupons
                    </div>

                    <nav class="coupons-pagination-pages" aria-label="Coupons pagination">
                        @if($coupons->onFirstPage())
                            <span class="coupons-pager-btn disabled" aria-disabled="true">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        @else
                            <a
                                href="{{ $coupons->previousPageUrl() }}"
                                class="coupons-pager-btn"
                                aria-label="Previous page"
                            >
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif

                        @if($start > 1)
                            <a href="{{ $coupons->url(1) }}" class="coupons-pager-btn">1</a>

                            @if($start > 2)
                                <span class="coupons-pager-dots">…</span>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page === $current)
                                <span class="coupons-pager-btn active" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $coupons->url($page) }}" class="coupons-pager-btn">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="coupons-pager-dots">…</span>
                            @endif

                            <a href="{{ $coupons->url($last) }}" class="coupons-pager-btn">
                                {{ $last }}
                            </a>
                        @endif

                        @if($coupons->hasMorePages())
                            <a
                                href="{{ $coupons->nextPageUrl() }}"
                                class="coupons-pager-btn"
                                aria-label="Next page"
                            >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="coupons-pager-btn disabled" aria-disabled="true">
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
    const filterForm = document.getElementById('coupon-filters');
    const resultsContainer = document.getElementById('coupons-results');

    if (!filterForm || !resultsContainer) {
        return;
    }

    let searchTimer = null;
    let activeController = null;
    let requestNumber = 0;

    function setLoading(loading) {
        const results = document.getElementById('coupons-results');

        if (!results) return;

        results.style.opacity = loading ? '0.55' : '1';
        results.style.pointerEvents = loading ? 'none' : '';
        results.setAttribute('aria-busy', String(loading));
    }

    function buildFilterUrl() {
        const url = new URL(filterForm.action, window.location.origin);
        const formData = new FormData(filterForm);

        for (const [key, value] of formData.entries()) {
            if (String(value).trim() !== '') {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        }

        url.searchParams.delete('page');

        return url;
    }

    async function refreshCoupons(url, updateHistory = true) {
        if (activeController) {
            activeController.abort();
        }

        const controller = new AbortController();
        activeController = controller;
        const currentRequest = ++requestNumber;

        setLoading(true);

        try {
            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error('Unable to load coupons.');
            }

            const html = await response.text();

            if (currentRequest !== requestNumber) {
                return false;
            }

            const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
            const newStats = parsedDocument.querySelector('.coupons-stats');
            const newResults = parsedDocument.querySelector('#coupons-results');

            if (!newStats || !newResults) {
                throw new Error('Could not find coupon results in the server response.');
            }

            const currentStats = document.querySelector('.coupons-stats');
            const currentResults = document.getElementById('coupons-results');

            if (currentStats) {
                currentStats.replaceWith(newStats);
            }

            if (currentResults) {
                currentResults.replaceWith(newResults);
            }

            if (updateHistory) {
                window.history.pushState({}, '', url.pathname + url.search);
            }

            return true;
        } catch (error) {
            if (error.name === 'AbortError') {
                return false;
            }

            Swal.fire({
                icon: 'error',
                title: 'Request failed',
                text: error.message || 'Unable to refresh coupons.'
            });

            return false;
        } finally {
            if (currentRequest === requestNumber) {
                activeController = null;
                setLoading(false);
            }
        }
    }

    function applyFilters() {
        refreshCoupons(buildFilterUrl());
    }

    // Filter düyməsi: səhifə yenilənmir.
    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(searchTimer);
        applyFilters();
    });

    // Search yazarkən AJAX axtarışı.
    document.addEventListener('input', function (event) {
        if (!event.target.matches('#coupon-search')) {
            return;
        }

        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {
            applyFilters();
        }, 350);
    });

    // Select filtrləri AJAX ilə tətbiq olunur.
    document.addEventListener('change', function (event) {
        if (
            event.target.matches('#coupon-type') ||
            event.target.matches('#coupon-status')
        ) {
            clearTimeout(searchTimer);
            applyFilters();
        }
    });

    // Reset: bütün filtrlər sıfırlanır, səhifə yenilənmir.
    document.addEventListener('click', function (event) {
        const resetButton = event.target.closest('#coupon-reset');

        if (!resetButton) {
            return;
        }

        event.preventDefault();
        clearTimeout(searchTimer);
        filterForm.reset();

        refreshCoupons(resetButton.href);
    });

    function getCsrfToken(form) {
        return form.querySelector('[name="_token"]')?.value ||
            document.querySelector('meta[name="csrf-token"]')?.content ||
            '';
    }

    async function sendFormRequest(form, method) {
        const csrfToken = getCsrfToken(form);

        if (!csrfToken) {
            throw new Error('CSRF token is missing. Refresh the page and try again.');
        }

        const body = new URLSearchParams();
        body.set('_token', csrfToken);
        body.set('_method', method);

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
            },
            body: body.toString()
        });

        const contentType = response.headers.get('content-type') || '';
        const data = contentType.includes('application/json')
            ? await response.json()
            : {};

        if (!response.ok || data.success === false) {
            throw new Error(data.message || 'The operation could not be completed.');
        }

        return data;
    }

    // Pagination AJAX ilə işləyir.
    document.addEventListener('click', function (event) {
        const link = event.target.closest(
            '#coupons-results .coupons-pagination-pages a'
        );

        if (!link) {
            return;
        }

        event.preventDefault();
        refreshCoupons(link.href);
    });

    // Status dəyişdirilməsi.
    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.coupon-toggle-form');

        if (!form) {
            return;
        }

        event.preventDefault();

        const button = form.querySelector('button');

        if (button) button.disabled = true;

        try {
            const data = await sendFormRequest(form, 'PATCH');
            const updated = await refreshCoupons(window.location.href, false);

            if (updated) {
                Swal.fire({
                    icon: 'success',
                    title: 'Status updated',
                    text: data.message || 'Coupon status updated successfully.',
                    timer: 1600,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            if (button) button.disabled = false;

            Swal.fire({
                icon: 'error',
                title: 'Update failed',
                text: error.message || 'Unable to update coupon status.'
            });
        }
    });

    // Coupon silinməsi.
    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.coupon-delete-form');

        if (!form) {
            return;
        }

        event.preventDefault();

        const confirmation = await Swal.fire({
            title: 'Are you sure?',
            text: 'This coupon will be permanently deleted.',
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

        const button = form.querySelector('button');

        if (button) button.disabled = true;

        try {
            const data = await sendFormRequest(form, 'DELETE');
            const updated = await refreshCoupons(window.location.href, false);

            if (updated) {
                Swal.fire({
                    icon: 'success',
                    title: 'Coupon deleted',
                    text: data.message || 'Coupon deleted successfully.',
                    timer: 1600,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            if (button) button.disabled = false;

            Swal.fire({
                icon: 'error',
                title: 'Delete failed',
                text: error.message || 'Unable to delete coupon.'
            });
        }
    });

    // Brauzerin Back və Forward düymələri.
    window.addEventListener('popstate', function () {
        const url = new URL(window.location.href);

        document.getElementById('coupon-search').value =
            url.searchParams.get('search') || '';

        document.getElementById('coupon-type').value =
            url.searchParams.get('type') || '';

        document.getElementById('coupon-status').value =
            url.searchParams.get('status') || '';

        refreshCoupons(url, false);
    });
});
</script>
@endpush

