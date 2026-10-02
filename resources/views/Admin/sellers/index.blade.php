@extends('layout.admin.master')

@section('title', 'Sellers')

@section('content')

<div class="dashboard-section sellers-page">


{{-- =========================================================
     HERO
========================================================== --}}

<div class="sellers-hero mb-4">
    <div class="sellers-hero-content">

        <span class="hero-badge">
            <i class="bi bi-shop-window"></i>
            Seller management
        </span>

        <h1>Sellers</h1>

        <p>
            Manage the people powering SecondBook's marketplace.
        </p>

    </div>

    <div class="sellers-hero-mark">
        <i class="bi bi-shop"></i>
    </div>
</div>


{{-- =========================================================
     STATS
========================================================== --}}

<div class="row g-4 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="seller-stat-card stat-blue">
            <div>
                <span>Total sellers</span>
                <strong>
                    {{ number_format($stats['total']) }}
                </strong>
            </div>

            <i class="bi bi-shop"></i>
        </div>
    </div>


    <div class="col-12 col-sm-6 col-xl-3">
        <div class="seller-stat-card stat-green">
            <div>
                <span>Active sellers</span>
                <strong>
                    {{ number_format($stats['active']) }}
                </strong>
            </div>

            <i class="bi bi-person-check"></i>
        </div>
    </div>


    <div class="col-12 col-sm-6 col-xl-3">
        <div class="seller-stat-card stat-orange">
            <div>
                <span>Inactive sellers</span>
                <strong>
                    {{ number_format($stats['inactive']) }}
                </strong>
            </div>

            <i class="bi bi-pause-circle"></i>
        </div>
    </div>


    <div class="col-12 col-sm-6 col-xl-3">
        <div class="seller-stat-card stat-red">
            <div>
                <span>Banned sellers</span>
                <strong>
                    {{ number_format($stats['banned']) }}
                </strong>
            </div>

            <i class="bi bi-slash-circle"></i>
        </div>
    </div>

</div>


{{-- =========================================================
     MAIN PANEL
========================================================== --}}

<div class="dashboard-panel sellers-panel">

    {{-- PANEL HEADER --}}

    <div class="panel-header sellers-panel-header">

        <div>
            <span class="eyebrow">
                Marketplace directory
            </span>

            <h5>
                All sellers
            </h5>

            <p>
                Review seller accounts and their listed inventory.
            </p>
        </div>

        <a
            href="{{ route('admin.sellers.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-2"></i>
            Add seller
        </a>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <form
        method="GET"
        action="{{ route('admin.sellers.index') }}"
        class="seller-filters"
    >

        <div class="seller-search-field">

            <i class="bi bi-search"></i>

            <input
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Search sellers..."
                aria-label="Search sellers"
            >

        </div>


        <select
            name="status"
            class="form-select seller-status-filter"
            aria-label="Filter by status"
        >

            <option value="">
                All status
            </option>

            <option
                value="active"
                @selected($status === 'active')
            >
                Active
            </option>

            <option
                value="inactive"
                @selected($status === 'inactive')
            >
                Inactive
            </option>

            <option
                value="banned"
                @selected($status === 'banned')
            >
                Banned
            </option>

        </select>


        <select
            name="sort"
            class="form-select seller-sort-filter"
            aria-label="Sort sellers"
        >

            <option
                value="newest"
                @selected($sort === 'newest')
            >
                Newest
            </option>

            <option
                value="oldest"
                @selected($sort === 'oldest')
            >
                Oldest
            </option>

            <option
                value="name_asc"
                @selected($sort === 'name_asc')
            >
                Name A-Z
            </option>

            <option
                value="name_desc"
                @selected($sort === 'name_desc')
            >
                Name Z-A
            </option>

        </select>


        <button
            type="submit"
            class="btn btn-primary seller-filter-button"
        >
            <i class="bi bi-funnel me-2"></i>
            Filter
        </button>


        @if($search || $status || $sort !== 'newest')

            <a
                href="{{ route('admin.sellers.index') }}"
                class="seller-clear-filter"
            >
                Reset
            </a>

        @endif

    </form>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="sellers-table-wrap">

        <table class="table sellers-table align-middle">

            <thead>
                <tr>

                    <th>
                        Seller
                    </th>

                    <th class="d-none d-md-table-cell">
                        Email
                    </th>

                    <th class="d-none d-lg-table-cell">
                        Phone
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Books
                    </th>

                    <th class="d-none d-xl-table-cell">
                        Joined
                    </th>

                    <th class="text-end">
                        Actions
                    </th>

                </tr>
            </thead>


            <tbody>

                @forelse($sellers as $seller)

                    @php
                        $displayName =
                            $seller->name
                            ?: ($seller->full_name
                            ?: $seller->username);
                    @endphp


                    <tr id="seller-row-{{ $seller->id }}">

                        {{-- SELLER --}}

                        <td>

                            <div class="seller-member-cell">

                                @if($seller->profile_photo)

                                    <img
                                        src="{{ asset('storage/' . $seller->profile_photo) }}"
                                        alt="{{ $displayName }}"
                                        class="seller-avatar seller-avatar-image"
                                    >

                                @else

                                    <div class="seller-avatar">
                                        {{ strtoupper(substr($displayName, 0, 1)) }}
                                    </div>

                                @endif


                                <div>

                                    <strong>
                                        {{ $displayName }}
                                    </strong>

                                    <small>
                                        Seller #{{ $seller->id }}
                                    </small>

                                </div>

                            </div>

                        </td>


                        {{-- EMAIL --}}

                        <td class="d-none d-md-table-cell">

                            <span class="seller-email">
                                {{ $seller->email }}
                            </span>

                        </td>


                        {{-- PHONE --}}

                        <td class="d-none d-lg-table-cell">

                            <span class="seller-phone">
                                {{ $seller->phone ?: '-' }}
                            </span>

                        </td>


                        {{-- STATUS --}}

                        <td>

                            <span
                                class="seller-status-pill seller-status-{{ $seller->status }}"
                                data-status-pill="{{ $seller->id }}"
                            >

                                <i class="bi bi-circle-fill"></i>

                                {{ ucfirst($seller->status) }}

                            </span>

                        </td>


                        {{-- BOOKS --}}

                        <td>

                            <span class="book-count">

                                <i class="bi bi-book"></i>

                                {{ number_format($seller->books_count) }}

                            </span>

                        </td>


                        {{-- JOINED --}}

                        <td class="d-none d-xl-table-cell">

                            <span class="seller-joined">
                                {{ $seller->created_at?->format('d M Y') }}
                            </span>

                        </td>


                        {{-- ACTIONS --}}

                        <td>

                            <div class="seller-actions">

                                {{-- VIEW --}}

                                <a
                                    href="{{ route('admin.sellers.show', $seller) }}"
                                    class="btn btn-light btn-sm border"
                                    title="View"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>


                                {{-- EDIT --}}

                                <a
                                    href="{{ route('admin.sellers.edit', $seller) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Edit"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>


                                @if(auth()->id() !== $seller->id)

                                    {{-- ACTIVATE / DEACTIVATE --}}

                                    <form
                                        action="{{ route('admin.sellers.status', $seller) }}"
                                        method="POST"
                                        class="seller-status-form"
                                        data-seller-id="{{ $seller->id }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="{{ $seller->status === 'active' ? 'inactive' : 'active' }}"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-light btn-sm border"
                                            title="{{ $seller->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                        >

                                            <i class="bi bi-{{ $seller->status === 'active' ? 'pause' : 'play' }}-circle"></i>

                                        </button>

                                    </form>


                                    {{-- BAN --}}

                                    @if($seller->status !== 'banned')

                                        <form
                                            action="{{ route('admin.sellers.status', $seller) }}"
                                            method="POST"
                                            class="seller-status-form"
                                            data-seller-id="{{ $seller->id }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="banned"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-light btn-sm border text-danger"
                                                title="Ban"
                                            >
                                                <i class="bi bi-slash-circle"></i>
                                            </button>

                                        </form>

                                    @endif


                                    {{-- DELETE --}}

                                    @if($seller->books_count === 0)

                                        <form
                                            action="{{ route('admin.sellers.destroy', $seller) }}"
                                            method="POST"
                                            class="delete-seller-form"
                                            data-seller-id="{{ $seller->id }}"
                                            data-seller-name="{{ $displayName }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Delete"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-light btn-sm border text-muted"
                                            title="Reassign books before deleting"
                                            disabled
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    @endif

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="seller-empty-state"
                        >

                            <i class="bi bi-shop"></i>

                            <strong>
                                No sellers found
                            </strong>

                            <span>
                                Try another search or add your first seller.
                            </span>

                            <a
                                href="{{ route('admin.sellers.create') }}"
                                class="btn btn-primary mt-3"
                            >
                                <i class="bi bi-plus-circle me-2"></i>
                                Add seller
                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if($sellers->hasPages())

        <div class="sellers-pagination">
            {{ $sellers->links() }}
        </div>

    @endif

</div>


</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SWEETALERT HELPERS
    |--------------------------------------------------------------------------
    */

    function swalSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            confirmButtonColor: '#2563eb',
            timer: 1700,
            timerProgressBar: true
        });
    }


    function swalError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message || 'Something went wrong.',
            confirmButtonColor: '#dc3545'
        });
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE SELLER
    |--------------------------------------------------------------------------
    */

    function bindDeleteForm(form) {

        if (!form || form.dataset.bound === 'true') {
            return;
        }

        form.dataset.bound = 'true';


        form.addEventListener('submit', function (event) {

            event.preventDefault();


            const sellerId = form.dataset.sellerId;

            const sellerName =
                form.dataset.sellerName || 'this seller';


            Swal.fire({

                title: 'Delete seller?',

                text:
                    'This will permanently remove ' +
                    sellerName +
                    '.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText:
                    'Delete seller',

                cancelButtonText:
                    'Cancel',

                confirmButtonColor:
                    '#dc3545',

                reverseButtons:
                    true

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                const button =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (button) {

                    button.disabled = true;

                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm"></span>';

                }


                const formData =
                    new FormData(form);


                fetch(form.action, {

                    method: 'POST',

                    headers: {
                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    },

                    body: formData

                })

                .then(async function (response) {

                    const contentType =
                        response.headers.get('content-type') || '';

                    let data = {};

                    if (
                        contentType.includes(
                            'application/json'
                        )
                    ) {
                        data = await response.json();
                    }


                    if (
                        !response.ok ||
                        data.success !== true
                    ) {

                        throw new Error(
                            data.message ||
                            'Seller could not be deleted.'
                        );

                    }


                    return data;

                })

                .then(function (data) {

                    const row =
                        document.getElementById(
                            'seller-row-' + sellerId
                        );


                    if (row) {

                        row.style.transition =
                            'opacity .25s ease, transform .25s ease';

                        row.style.opacity = '0';

                        row.style.transform =
                            'translateX(15px)';


                        setTimeout(function () {

                            row.remove();


                            const tbody =
                                document.querySelector(
                                    '.sellers-table tbody'
                                );


                            if (!tbody) {
                                return;
                            }


                            const rows =
                                tbody.querySelectorAll(
                                    'tr[id^="seller-row-"]'
                                );


                            if (rows.length === 0) {

                                tbody.innerHTML = `
                                    <tr>
                                        <td
                                            colspan="7"
                                            class="seller-empty-state"
                                        >

                                            <i class="bi bi-shop"></i>

                                            <strong>
                                                No sellers found
                                            </strong>

                                            <span>
                                                Try another search or add
                                                your first seller.
                                            </span>

                                            <a
                                                href="{{ route('admin.sellers.create') }}"
                                                class="btn btn-primary mt-3"
                                            >
                                                <i class="bi bi-plus-circle me-2"></i>
                                                Add seller
                                            </a>

                                        </td>
                                    </tr>
                                `;

                            }

                        }, 250);

                    }


                    swalSuccess(
                        data.message ||
                        'Seller deleted successfully.'
                    );

                })

                .catch(function (error) {

                    if (button) {

                        button.disabled = false;

                        button.innerHTML =
                            '<i class="bi bi-trash"></i>';

                    }


                    swalError(
                        error.message
                    );

                });

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS FORM
    |--------------------------------------------------------------------------
    */

    function bindStatusForm(form) {

        if (!form || form.dataset.bound === 'true') {
            return;
        }

        form.dataset.bound = 'true';


        form.addEventListener('submit', function (event) {

            event.preventDefault();


            const sellerId =
                form.dataset.sellerId;


            const statusInput =
                form.querySelector(
                    'input[name="status"]'
                );


            if (!statusInput) {

                swalError(
                    'Seller status information is missing.'
                );

                return;
            }


            const newStatus =
                statusInput.value;


            let title =
                'Update seller status?';

            let text =
                'Are you sure you want to update this seller status?';

            let confirmText =
                'Update';

            let confirmColor =
                '#2563eb';


            if (newStatus === 'active') {

                title =
                    'Activate seller?';

                text =
                    'This seller will be activated.';

                confirmText =
                    'Activate';
            }


            if (newStatus === 'inactive') {

                title =
                    'Deactivate seller?';

                text =
                    'This seller will be deactivated.';

                confirmText =
                    'Deactivate';
            }


            if (newStatus === 'banned') {

                title =
                    'Ban seller?';

                text =
                    'This seller will be banned.';

                confirmText =
                    'Ban';

                confirmColor =
                    '#dc3545';
            }


            Swal.fire({

                title: title,

                text: text,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText:
                    confirmText,

                cancelButtonText:
                    'Cancel',

                confirmButtonColor:
                    confirmColor,

                reverseButtons:
                    true

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                const button =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (button) {

                    button.disabled = true;

                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm"></span>';

                }


                const formData =
                    new FormData(form);


                fetch(form.action, {

                    method: 'POST',

                    headers: {

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'

                    },

                    body: formData

                })

                .then(async function (response) {

                    const contentType =
                        response.headers.get('content-type') || '';

                    let data = {};

                    if (
                        contentType.includes(
                            'application/json'
                        )
                    ) {

                        data =
                            await response.json();

                    }


                    if (
                        !response.ok ||
                        data.success !== true
                    ) {

                        throw new Error(
                            data.message ||
                            'Seller status could not be updated.'
                        );

                    }


                    return data;

                })

                .then(function (data) {

                    updateSellerStatusUI(
                        sellerId,
                        data.status || newStatus
                    );


                    swalSuccess(
                        data.message ||
                        'Seller status updated successfully.'
                    );

                })

                .catch(function (error) {

                    if (button) {

                        button.disabled = false;

                        restoreStatusButton(
                            button,
                            newStatus
                        );

                    }


                    swalError(
                        error.message
                    );

                });

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CREATE STATUS FORM
    |--------------------------------------------------------------------------
    */

    function createStatusForm(
        sellerId,
        targetStatus,
        icon,
        title,
        extraClass = ''
    ) {

        const form =
            document.createElement('form');


        form.action =
            '{{ url('admin/sellers') }}/' +
            sellerId +
            '/status';


        form.method =
            'POST';


        form.className =
            'seller-status-form';


        form.dataset.sellerId =
            sellerId;


        form.innerHTML = `

            <input
                type="hidden"
                name="_token"
                value="{{ csrf_token() }}"
            >

            <input
                type="hidden"
                name="_method"
                value="PATCH"
            >

            <input
                type="hidden"
                name="status"
                value="${targetStatus}"
            >

            <button
                type="submit"
                class="btn btn-light btn-sm border ${extraClass}"
                title="${title}"
            >

                <i class="bi ${icon}"></i>

            </button>

        `;


        return form;
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SELLER STATUS UI
    |--------------------------------------------------------------------------
    */

    function updateSellerStatusUI(
        sellerId,
        status
    ) {

        const row =
            document.getElementById(
                'seller-row-' + sellerId
            );


        if (!row) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS PILL
        |--------------------------------------------------------------------------
        */

        const statusPill =
            row.querySelector(
                '[data-status-pill="' +
                sellerId +
                '"]'
            );


        if (statusPill) {

            statusPill.className =
                'seller-status-pill seller-status-' +
                status;


            statusPill.innerHTML = `
                <i class="bi bi-circle-fill"></i>
                ${
                    status.charAt(0).toUpperCase() +
                    status.slice(1)
                }
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | ACTION AREA
        |--------------------------------------------------------------------------
        */

        const actions =
            row.querySelector(
                '.seller-actions'
            );


        if (!actions) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE OLD STATUS FORMS
        |--------------------------------------------------------------------------
        */

        actions
            .querySelectorAll(
                '.seller-status-form'
            )
            .forEach(function (oldForm) {

                oldForm.remove();

            });


        /*
        |--------------------------------------------------------------------------
        | CURRENT ADMIN ACCOUNT
        |--------------------------------------------------------------------------
        */

        const currentUserId =
            '{{ auth()->id() }}';


        if (
            String(sellerId) ===
            String(currentUserId)
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BUTTON
        |--------------------------------------------------------------------------
        */

        const deleteForm =
            actions.querySelector(
                '.delete-seller-form'
            );


        /*
        |--------------------------------------------------------------------------
        | STATUS TOGGLE BUTTON
        |--------------------------------------------------------------------------
        */

        let nextStatus;
        let statusIcon;
        let statusTitle;


        if (status === 'active') {

            nextStatus =
                'inactive';

            statusIcon =
                'bi-pause-circle';

            statusTitle =
                'Deactivate';

        } else {

            nextStatus =
                'active';

            statusIcon =
                'bi-play-circle';

            statusTitle =
                'Activate';

        }


        const statusForm =
            createStatusForm(
                sellerId,
                nextStatus,
                statusIcon,
                statusTitle
            );


        /*
        |--------------------------------------------------------------------------
        | BAN BUTTON
        |--------------------------------------------------------------------------
        |
        | Ban button MUST exist for both active and inactive sellers.
        | It disappears only when the current status is already banned.
        |
        */

        let banForm = null;


        if (status !== 'banned') {

            banForm =
                createStatusForm(
                    sellerId,
                    'banned',
                    'bi-slash-circle',
                    'Ban',
                    'text-danger'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT STATUS BUTTON
        |--------------------------------------------------------------------------
        */

        if (deleteForm) {

            actions.insertBefore(
                statusForm,
                deleteForm
            );

        } else {

            actions.appendChild(
                statusForm
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT BAN BUTTON
        |--------------------------------------------------------------------------
        */

        if (banForm) {

            if (deleteForm) {

                actions.insertBefore(
                    banForm,
                    deleteForm
                );

            } else {

                actions.appendChild(
                    banForm
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | BIND NEW STATUS BUTTON
        |--------------------------------------------------------------------------
        */

        bindStatusForm(
            statusForm
        );


        /*
        |--------------------------------------------------------------------------
        | BIND NEW BAN BUTTON
        |--------------------------------------------------------------------------
        */

        if (banForm) {

            bindStatusForm(
                banForm
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE BUTTON AFTER ERROR
    |--------------------------------------------------------------------------
    */

    function restoreStatusButton(
        button,
        targetStatus
    ) {

        if (!button) {
            return;
        }


        if (targetStatus === 'active') {

            button.innerHTML =
                '<i class="bi bi-pause-circle"></i>';

            button.title =
                'Deactivate';

        } else if (targetStatus === 'inactive') {

            button.innerHTML =
                '<i class="bi bi-play-circle"></i>';

            button.title =
                'Activate';

        } else {

            button.innerHTML =
                '<i class="bi bi-slash-circle"></i>';

            button.title =
                'Ban';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL BINDINGS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.seller-status-form'
        )
        .forEach(function (form) {

            bindStatusForm(form);

        });


    document
        .querySelectorAll(
            '.delete-seller-form'
        )
        .forEach(function (form) {

            bindDeleteForm(form);

        });

});
</script>

@endpush