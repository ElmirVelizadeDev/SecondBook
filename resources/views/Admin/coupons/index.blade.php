@extends('layout.admin.master')

@section('title', 'Coupons')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/coupons.css') }}">
@endpush

@section('content')

<div class="dashboard-section coupons-page">

    {{-- ================= HERO ================= --}}
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


    {{-- ================= STATISTICS ================= --}}
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


    {{-- ================= MAIN PANEL ================= --}}
    <section class="dashboard-panel coupons-panel">

        {{-- Header --}}
        <div class="coupons-panel-header">

            <div class="coupons-heading-content">

                <span class="eyebrow">
                    Coupon directory
                </span>

                <h5>
                    All Coupons
                </h5>

                <p>
                    Search, filter and manage every promotional coupon.
                </p>

            </div>


            <div class="coupons-header-action">

                <a
                    href="{{ route('admin.coupons.create') }}"
                    class="coupons-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Coupon</span>
                </a>

            </div>

        </div>


        {{-- ================= FILTERS ================= --}}
        <form
            method="GET"
            action="{{ route('admin.coupons.index') }}"
            class="coupon-filters"
        >

            {{-- Search --}}
            <div class="coupon-filter-group coupon-filter-search">

                <label for="coupon-search">
                    Search
                </label>

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


            {{-- Type --}}
            <div class="coupon-filter-group">

                <label for="coupon-type">
                    Discount Type
                </label>

                <select
                    id="coupon-type"
                    name="type"
                    class="coupon-filter-select"
                >

                    <option value="">
                        All Types
                    </option>

                    <option
                        value="percentage"
                        @selected(request('type') === 'percentage')
                    >
                        Percentage
                    </option>

                    <option
                        value="fixed"
                        @selected(request('type') === 'fixed')
                    >
                        Fixed Amount
                    </option>

                </select>

            </div>


            {{-- Status --}}
            <div class="coupon-filter-group">

                <label for="coupon-status">
                    Status
                </label>

                <select
                    id="coupon-status"
                    name="status"
                    class="coupon-filter-select"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="1"
                        @selected(request('status') === '1')
                    >
                        Active
                    </option>

                    <option
                        value="0"
                        @selected(request('status') === '0')
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Actions --}}
            <div class="coupon-filter-actions">

                <button
                    type="submit"
                    class="coupon-filter-btn"
                >
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>


                @if(request()->hasAny(['search', 'type', 'status']))

                    <a
                        href="{{ route('admin.coupons.index') }}"
                        class="coupon-clear-filter"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Clear</span>
                    </a>

                @endif

            </div>

        </form>


        {{-- ================= TABLE ================= --}}
        <div class="coupons-table-wrap">

            <table class="coupons-table">

                <thead>

                    <tr>

                        <th class="coupons-col-id">
                            #
                        </th>

                        <th class="coupons-col-code">
                            Code
                        </th>

                        <th class="coupons-col-discount">
                            Discount
                        </th>

                        <th class="coupons-col-minimum">
                            Minimum Order
                        </th>

                        <th class="coupons-col-usage">
                            Usage
                        </th>

                        <th class="coupons-col-validity">
                            Validity
                        </th>

                        <th class="coupons-col-status">
                            Status
                        </th>

                        <th class="coupons-col-actions">
                            Actions
                        </th>

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

                            {{-- ID --}}
                            <td>

                                <span class="coupon-id">
                                    #{{ $coupon->id }}
                                </span>

                            </td>


                            {{-- Code --}}
                            <td>

                                <div class="coupon-code-cell">

                                    <div class="coupon-code-icon">
                                        <i class="bi bi-ticket-perforated"></i>
                                    </div>

                                    <div class="coupon-code-info">

                                        <strong title="{{ $coupon->code }}">
                                            {{ $coupon->code }}
                                        </strong>

                                        <small>
                                            Discount coupon
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Discount --}}
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


                            {{-- Minimum Order --}}
                            <td>

                                <span class="coupon-money">
                                    ${{ number_format($coupon->minimum_order_amount, 2) }}
                                </span>

                            </td>


                            {{-- Usage --}}
                            <td>

                                <div class="coupon-usage-block">

                                    <strong>
                                        {{ $coupon->used_count }}
                                    </strong>

                                    <span>/</span>

                                    <span>
                                        {{ $coupon->usage_limit ?? '∞' }}
                                    </span>

                                </div>

                            </td>


                            {{-- Validity --}}
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


                            {{-- Status --}}
                            <td>

                                <span class="coupon-status-pill coupon-status-{{ $couponStatus }}">

                                    <i class="bi bi-circle-fill"></i>

                                    {{ ucfirst($couponStatus) }}

                                </span>

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="coupon-actions">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.coupons.show', $coupon->id) }}"
                                        class="coupon-action-btn coupon-view-btn"
                                        title="View coupon"
                                        aria-label="View coupon #{{ $coupon->id }}"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                        class="coupon-action-btn coupon-edit-btn"
                                        title="Edit coupon"
                                        aria-label="Edit coupon #{{ $coupon->id }}"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Toggle Status --}}
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


                                    {{-- Delete --}}
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

                                    <strong>
                                        No coupons found
                                    </strong>

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
        @if($coupons->hasPages())

            @php

                $coupons->appends(request()->query());

                $current = $coupons->currentPage();
                $last    = $coupons->lastPage();

                $start = max(1, $current - 2);
                $end   = min($last, $current + 2);

            @endphp


            <div class="coupons-pagination">

                <div class="coupons-pagination-info">

                    Showing

                    <strong>
                        {{ $coupons->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $coupons->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $coupons->total() }}
                    </strong>

                    coupons

                </div>


                <nav
                    class="coupons-pagination-pages"
                    aria-label="Coupons pagination"
                >

                    {{-- Previous --}}
                    @if($coupons->onFirstPage())

                        <span
                            class="coupons-pager-btn disabled"
                            aria-disabled="true"
                        >
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


                    {{-- First --}}
                    @if($start > 1)

                        <a
                            href="{{ $coupons->url(1) }}"
                            class="coupons-pager-btn"
                        >
                            1
                        </a>

                        @if($start > 2)
                            <span class="coupons-pager-dots">
                                …
                            </span>
                        @endif

                    @endif


                    {{-- Window --}}
                    @for($page = $start; $page <= $end; $page++)

                        @if($page === $current)

                            <span
                                class="coupons-pager-btn active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $coupons->url($page) }}"
                                class="coupons-pager-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Last --}}
                    @if($end < $last)

                        @if($end < $last - 1)

                            <span class="coupons-pager-dots">
                                …
                            </span>

                        @endif

                        <a
                            href="{{ $coupons->url($last) }}"
                            class="coupons-pager-btn"
                        >
                            {{ $last }}
                        </a>

                    @endif


                    {{-- Next --}}
                    @if($coupons->hasMorePages())

                        <a
                            href="{{ $coupons->nextPageUrl() }}"
                            class="coupons-pager-btn"
                            aria-label="Next page"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @else

                        <span
                            class="coupons-pager-btn disabled"
                            aria-disabled="true"
                        >
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       DELETE PAYMENT — AJAX
    ========================================================= */

    document.querySelectorAll('.payment-delete-form').forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const row = form.closest('tr');
            const button = form.querySelector('button');

            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'This payment will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) {
                return;
            }

            if (button) {
                button.disabled = true;
            }

            try {

                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content');

                const response = await fetch(form.action, {

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type':
                            'application/x-www-form-urlencoded; charset=UTF-8'
                    },

                    body: new URLSearchParams({
                        _token: csrfToken,
                        _method: 'DELETE'
                    })
                });

                const contentType =
                    response.headers.get('content-type') || '';

                let data = {};

                if (contentType.includes('application/json')) {
                    data = await response.json();
                }

                if (!response.ok) {
                    throw new Error(
                        data.message ||
                        'Unable to delete the payment.'
                    );
                }

                /* =================================================
                   REMOVE ROW WITHOUT PAGE REFRESH
                ================================================= */

                if (row) {

                    row.style.transition =
                        'opacity 0.3s ease, transform 0.3s ease';

                    row.style.opacity = '0';

                    row.style.transform =
                        'translateX(20px)';

                    setTimeout(function () {
                        row.remove();
                    }, 300);
                }

                /* =================================================
                   SUCCESS ALERT
                ================================================= */

                Swal.fire({
                    icon: 'success',
                    title: 'Payment deleted',
                    text: data.message ||
                        'The payment has been deleted successfully.',
                    timer: 1600,
                    showConfirmButton: false
                });

            } catch (error) {

                if (button) {
                    button.disabled = false;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Delete failed',
                    text: error.message ||
                        'Unable to delete the payment.',
                    confirmButtonColor: '#2563eb'
                });
            }

        });

    });

});
</script>

@endpush