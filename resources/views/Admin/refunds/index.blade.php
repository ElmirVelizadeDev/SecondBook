@extends('layout.admin.master')

@section('title', 'Refunds')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/refunds.css') }}">
@endpush

@section('content')

<div class="refunds-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}
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


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}
    <section class="refund-stats">

        <div class="refund-stat stat-blue">

            <div class="refund-stat-content">
                <span>Total Refunds</span>
                <strong>
                    {{ number_format($stats['total']) }}
                </strong>
            </div>

            <div class="refund-stat-icon">
                <i class="bi bi-receipt"></i>
            </div>

        </div>


        <div class="refund-stat stat-orange">

            <div class="refund-stat-content">
                <span>Pending</span>
                <strong>
                    {{ number_format($stats['pending']) }}
                </strong>
            </div>

            <div class="refund-stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>

        </div>


        <div class="refund-stat stat-green">

            <div class="refund-stat-content">
                <span>Processed</span>
                <strong>
                    {{ number_format($stats['processed']) }}
                </strong>
            </div>

            <div class="refund-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>

        </div>


        <div class="refund-stat stat-purple">

            <div class="refund-stat-content">
                <span>Refunded Amount</span>
                <strong>
                    ${{ number_format($stats['amount'], 2) }}
                </strong>
            </div>

            <div class="refund-stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>

        </div>

    </section>


    {{-- =========================================================
         MAIN PANEL
    ========================================================== --}}
    <section class="refund-panel">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="refund-panel-header">

            <div class="refund-panel-heading">

                <span class="eyebrow">
                    Refund Directory
                </span>

                <h5>
                    All Refunds
                </h5>

                <p>
                    Search, filter and review every customer refund request.
                </p>

            </div>


            <div class="refund-hero-actions">

                <a
                    href="{{ route('admin.refunds.create') }}"
                    class="refund-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Refund</span>
                </a>

            </div>

        </div>


        {{-- =====================================================
             FILTERS
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.refunds.index') }}"
            class="refund-filters"
        >

            {{-- Search --}}
            <div class="refund-search">

                <i class="bi bi-search"></i>

                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Refund number, order, customer..."
                    autocomplete="off"
                    aria-label="Search refunds"
                >

            </div>


            {{-- Status --}}
            <select
                name="status"
                class="refund-select"
                aria-label="Refund status"
            >

                <option value="">
                    All Status
                </option>

                @foreach([
                    'pending',
                    'approved',
                    'rejected',
                    'processed',
                    'cancelled'
                ] as $item)

                    <option
                        value="{{ $item }}"
                        @selected(request('status') === $item)
                    >
                        {{ ucfirst($item) }}
                    </option>

                @endforeach

            </select>


            {{-- Sort --}}
            <select
                name="sort"
                class="refund-select"
                aria-label="Sort refunds"
            >

                <option
                    value="newest"
                    @selected(request('sort', 'newest') === 'newest')
                >
                    Newest
                </option>

                <option
                    value="oldest"
                    @selected(request('sort') === 'oldest')
                >
                    Oldest
                </option>

                <option
                    value="highest"
                    @selected(request('sort') === 'highest')
                >
                    Highest Amount
                </option>

                <option
                    value="lowest"
                    @selected(request('sort') === 'lowest')
                >
                    Lowest Amount
                </option>

            </select>


            {{-- Filter Actions --}}
            <div class="refund-filter-actions">

                <button
                    type="submit"
                    class="refund-filter-btn"
                >
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>


                @if(
                    request('search') ||
                    request('status') ||
                    request('sort', 'newest') !== 'newest'
                )

                    <a
                        href="{{ route('admin.refunds.index') }}"
                        class="refund-reset-btn"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Clear</span>
                    </a>

                @endif

            </div>

        </form>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="refund-table-wrap">

            <table class="refund-table">

                <thead>

                    <tr>

                        <th class="refund-col-number">
                            Refund
                        </th>

                        <th class="refund-col-order">
                            Order
                        </th>

                        <th class="refund-col-customer">
                            Customer
                        </th>

                        <th class="refund-col-amount">
                            Amount
                        </th>

                        <th class="refund-col-reason">
                            Reason
                        </th>

                        <th class="refund-col-status">
                            Status
                        </th>

                        <th class="refund-col-date">
                            Date
                        </th>

                        <th class="refund-col-actions">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($refunds as $refund)

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

                            $customerInitial = strtoupper(
                                mb_substr($customerName, 0, 1)
                            );

                        @endphp


                        <tr>

                            {{-- Refund --}}
                            <td>

                                <div class="refund-number-cell">

                                    <div class="refund-number-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $refund->refund_number }}
                                        </strong>

                                        <small>
                                            Refund Request
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Order --}}
                            <td>

                                <span class="refund-order-number">

                                    #{{ $refund->order?->order_number ?? '-' }}

                                </span>

                            </td>


                            {{-- Customer --}}
                            <td>

                                <div class="refund-customer-cell">

                                    <div class="refund-customer-avatar">
                                        {{ $customerInitial }}
                                    </div>

                                    <div class="refund-customer-info">

                                        <strong
                                            title="{{ $customerName }}"
                                        >
                                            {{ $customerName }}
                                        </strong>

                                        <small
                                            title="{{ $refund->user?->email ?? '' }}"
                                        >
                                            {{ $refund->user?->email ?? '—' }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Amount --}}
                            <td>

                                <strong class="refund-amount">
                                    ${{ number_format($refund->amount, 2) }}
                                </strong>

                            </td>


                            {{-- Reason --}}
                            <td>

                                <span
                                    class="refund-reason"
                                    title="{{ $refund->reason }}"
                                >
                                    {{ $refund->reason }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                <span
                                    class="refund-status status-{{ $refund->status }}"
                                >

                                    <i class="bi bi-circle-fill"></i>

                                    {{ ucfirst($refund->status) }}

                                </span>

                            </td>


                            {{-- Date --}}
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


                            {{-- Actions --}}
                            <td class="refund-row">

                                <div class="refund-actions">


                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.refunds.show', $refund) }}"
                                        class="refund-action action-view"
                                        title="View refund"
                                        aria-label="View refund"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Edit --}}
                                    @if($refund->status !== 'processed')

                                        <a
                                            href="{{ route('admin.refunds.edit', $refund) }}"
                                            class="refund-action action-edit"
                                            title="Edit refund"
                                            aria-label="Edit refund"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                    @endif


                                    {{-- Pending Actions --}}
                                    @if($refund->status === 'pending')

                                        {{-- Approve --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.status', $refund) }}"
                                            class="refund-status-form"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="approved"
                                            >

                                            <button
                                                type="submit"
                                                class="refund-action action-approve"
                                                title="Approve refund"
                                                aria-label="Approve refund"
                                            >
                                                <i class="bi bi-check-lg"></i>
                                            </button>

                                        </form>


                                        {{-- Reject --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.status', $refund) }}"
                                            class="refund-status-form"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="rejected"
                                            >

                                            <button
                                                type="submit"
                                                class="refund-action action-reject"
                                                title="Reject refund"
                                                aria-label="Reject refund"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>

                                        </form>

                                    @elseif($refund->status === 'approved')

                                        {{-- Process --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.refunds.status', $refund) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="processed"
                                            >

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


                                    {{-- Delete --}}
                                    @if($refund->status !== 'processed')

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

                                    <strong>
                                        No refunds found
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


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($refunds->hasPages())

            @php

                $refunds->appends(request()->query());

                $current = $refunds->currentPage();
                $last    = $refunds->lastPage();

                $start = max(1, $current - 2);
                $end   = min($last, $current + 2);

            @endphp


            <div class="refund-pagination">

                <div class="refund-pagination-info">

                    Showing
                    <strong>{{ $refunds->firstItem() }}</strong>
                    to
                    <strong>{{ $refunds->lastItem() }}</strong>
                    of
                    <strong>{{ $refunds->total() }}</strong>
                    refunds

                </div>


                <nav
                    class="refund-pagination-pages"
                    aria-label="Refund pagination"
                >

                    {{-- Previous --}}
                    @if($refunds->onFirstPage())

                        <span
                            class="refund-pager-btn disabled"
                            aria-disabled="true"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $refunds->previousPageUrl() }}"
                            class="refund-pager-btn"
                            aria-label="Previous page"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </a>

                    @endif


                    {{-- First Page --}}
                    @if($start > 1)

                        <a
                            href="{{ $refunds->url(1) }}"
                            class="refund-pager-btn"
                        >
                            1
                        </a>

                        @if($start > 2)

                            <span class="refund-pager-dots">
                                …
                            </span>

                        @endif

                    @endif


                    {{-- Page Window --}}
                    @for($page = $start; $page <= $end; $page++)

                        @if($page === $current)

                            <span
                                class="refund-pager-btn active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $refunds->url($page) }}"
                                class="refund-pager-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Last Page --}}
                    @if($end < $last)

                        @if($end < $last - 1)

                            <span class="refund-pager-dots">
                                …
                            </span>

                        @endif

                        <a
                            href="{{ $refunds->url($last) }}"
                            class="refund-pager-btn"
                        >
                            {{ $last }}
                        </a>

                    @endif


                    {{-- Next --}}
                    @if($refunds->hasMorePages())

                        <a
                            href="{{ $refunds->nextPageUrl() }}"
                            class="refund-pager-btn"
                            aria-label="Next page"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @else

                        <span
                            class="refund-pager-btn disabled"
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | APPROVE / REJECT / PROCESS REFUND
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.refund-status-form').forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const button = form.querySelector('button');
            const row = form.closest('tr');
            const status = form.querySelector('input[name="status"]')?.value;

            let title = '';
            let text = '';
            let confirmButtonText = '';
            let icon = 'warning';
            let confirmButtonColor = '#2563eb';

            if (status === 'approved') {

                title = 'Approve refund?';
                text = 'Are you sure you want to approve this refund?';
                confirmButtonText = 'Approve refund';
                icon = 'question';
                confirmButtonColor = '#16a34a';

            } else if (status === 'rejected') {

                title = 'Reject refund?';
                text = 'Are you sure you want to reject this refund?';
                confirmButtonText = 'Reject refund';
                icon = 'warning';
                confirmButtonColor = '#dc2626';

            } else if (status === 'processed') {

                title = 'Process refund?';
                text = 'Are you sure you want to process this refund?';
                confirmButtonText = 'Process refund';
                icon = 'question';
                confirmButtonColor = '#2563eb';

            } else {

                title = 'Change refund status?';
                text = 'Are you sure you want to change this refund status?';
                confirmButtonText = 'Confirm';
            }

            const result = await Swal.fire({

                title: title,
                text: text,
                icon: icon,

                showCancelButton: true,

                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel',

                confirmButtonColor: confirmButtonColor,

                reverseButtons: true

            });

            if (!result.isConfirmed) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent double click
            |--------------------------------------------------------------------------
            */

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

                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'

                    },

                    body: new URLSearchParams({

                        _token: csrfToken,

                        _method: 'PATCH',

                        status: status

                    })

                });

                const data = await response.json();

                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message || 'Unable to update the refund status.'
                    );

                }

                /*
                |--------------------------------------------------------------------------
                | Update status badge without page refresh
                |--------------------------------------------------------------------------
                */

                if (row) {

                    const statusBadge = row.querySelector('.refund-status');

                    if (statusBadge) {

                        statusBadge.className =
                            'refund-status status-' + status;

                        statusBadge.innerHTML =
                            '<i class="bi bi-circle-fill"></i> ' +
                            status.charAt(0).toUpperCase() +
                            status.slice(1);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Update action buttons
                    |--------------------------------------------------------------------------
                    */

                    const actions = row.querySelector('.refund-actions');

                    if (actions) {

                        /*
                        | Remove old approve/reject/process forms
                        */

                        actions
                            .querySelectorAll(
                                'form:not(.delete-refund-form)'
                            )
                            .forEach(function (oldForm) {
                                oldForm.remove();
                            });

                        /*
                        | Remove edit button because approved/rejected
                        | status can still be edited according to your logic,
                        | so we keep it.
                        */

                        if (status === 'approved') {

                            const processForm = document.createElement('form');

                            processForm.method = 'POST';
                            processForm.action = form.action;

                            processForm.className = 'refund-status-form';

                            processForm.innerHTML = `
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="status" value="processed">

                                <button
                                    type="submit"
                                    class="refund-action action-process"
                                    title="Process refund"
                                    aria-label="Process refund"
                                >
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            `;

                            /*
                            | Insert before delete form
                            */

                            const deleteForm = actions.querySelector(
                                '.delete-refund-form'
                            );

                            if (deleteForm) {
                                actions.insertBefore(processForm, deleteForm);
                            } else {
                                actions.appendChild(processForm);
                            }

                            /*
                            | Bind newly created form
                            */

                            bindStatusForm(processForm);
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Success message
                |--------------------------------------------------------------------------
                */

                let successTitle = 'Refund updated';

                if (status === 'approved') {
                    successTitle = 'Refund approved';
                }

                if (status === 'rejected') {
                    successTitle = 'Refund rejected';
                }

                if (status === 'processed') {
                    successTitle = 'Refund processed';
                }

                Swal.fire({

                    icon: 'success',

                    title: successTitle,

                    text:
                        data.message ||
                        'The refund status has been updated successfully.',

                    timer: 1800,

                    showConfirmButton: false

                });

            } catch (error) {

                console.error('Refund status error:', error);

                if (button) {
                    button.disabled = false;
                }

                Swal.fire({

                    icon: 'error',

                    title: 'Update failed',

                    text:
                        error.message ||
                        'Unable to update the refund status.',

                    confirmButtonColor: '#2563eb'

                });
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | STATUS FORM BIND FUNCTION
    |--------------------------------------------------------------------------
    */

    function bindStatusForm(form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const button = form.querySelector('button');

            const status =
                form.querySelector('input[name="status"]')?.value;

            let title = 'Change refund status?';
            let text = 'Are you sure you want to change this refund status?';
            let confirmButtonText = 'Confirm';
            let icon = 'question';
            let confirmButtonColor = '#2563eb';

            if (status === 'approved') {

                title = 'Approve refund?';
                text = 'Are you sure you want to approve this refund?';
                confirmButtonText = 'Approve refund';
                confirmButtonColor = '#16a34a';

            } else if (status === 'rejected') {

                title = 'Reject refund?';
                text = 'Are you sure you want to reject this refund?';
                confirmButtonText = 'Reject refund';
                icon = 'warning';
                confirmButtonColor = '#dc2626';

            } else if (status === 'processed') {

                title = 'Process refund?';
                text = 'Are you sure you want to process this refund?';
                confirmButtonText = 'Process refund';
                confirmButtonColor = '#2563eb';
            }

            const result = await Swal.fire({

                title: title,
                text: text,
                icon: icon,

                showCancelButton: true,

                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel',

                confirmButtonColor: confirmButtonColor,

                reverseButtons: true
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

                        _method: 'PATCH',

                        status: status
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message ||
                        'Unable to update the refund status.'
                    );
                }

                const row = form.closest('tr');

                if (row) {

                    const statusBadge =
                        row.querySelector('.refund-status');

                    if (statusBadge) {

                        statusBadge.className =
                            'refund-status status-' + status;

                        statusBadge.innerHTML =
                            '<i class="bi bi-circle-fill"></i> ' +
                            status.charAt(0).toUpperCase() +
                            status.slice(1);
                    }
                }

                Swal.fire({

                    icon: 'success',

                    title: 'Refund updated',

                    text:
                        data.message ||
                        'The refund status has been updated successfully.',

                    timer: 1800,

                    showConfirmButton: false
                });

            } catch (error) {

                console.error('Refund status error:', error);

                if (button) {
                    button.disabled = false;
                }

                Swal.fire({

                    icon: 'error',

                    title: 'Update failed',

                    text:
                        error.message ||
                        'Unable to update the refund status.',

                    confirmButtonColor: '#2563eb'
                });
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE REFUND
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.delete-refund-form').forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const row = form.closest('tr');

            const refundNumber =
                form.dataset.refundNumber || 'this refund';

            const result = await Swal.fire({

                title: 'Delete refund?',

                text:
                    `Are you sure you want to permanently delete ${refundNumber}?`,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Delete refund',

                cancelButtonText: 'Cancel',

                confirmButtonColor: '#bd3d53',

                reverseButtons: true
            });

            if (!result.isConfirmed) {
                return;
            }

            const button = form.querySelector('button');

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

                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    body: new URLSearchParams({

                        _token: csrfToken,

                        _method: 'DELETE'
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message ||
                        'Unable to delete the refund.'
                    );
                }

                if (row) {

                    row.style.transition =
                        'opacity 0.3s ease, transform 0.3s ease';

                    row.style.opacity = '0';

                    row.style.transform = 'translateX(20px)';

                    setTimeout(function () {
                        row.remove();
                    }, 300);
                }

                Swal.fire({

                    icon: 'success',

                    title: 'Refund deleted',

                    text:
                        data.message ||
                        'The refund has been deleted successfully.',

                    timer: 1800,

                    showConfirmButton: false
                });

            } catch (error) {

                console.error('Refund delete error:', error);

                if (button) {
                    button.disabled = false;
                }

                Swal.fire({

                    icon: 'error',

                    title: 'Delete failed',

                    text:
                        error.message ||
                        'Unable to delete the refund.',

                    confirmButtonColor: '#2563eb'
                });
            }

        });

    });

});
</script>

@endpush