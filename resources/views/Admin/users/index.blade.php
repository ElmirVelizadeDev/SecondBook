@extends('layout.admin.master')

@section('title', 'Users')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/users.css') }}">
@endpush

@section('content')

<div class="dashboard-section users-page">

    {{-- Hero --}}
    <div class="users-hero mb-4">

        <div class="users-hero-content">

            <span class="hero-badge">
                <i class="bi bi-shield-check"></i>
                SecondBook Community
            </span>

            <h1>
                People behind every page.
            </h1>

            <p>
                Keep your reader community organised, verified and ready to grow.
            </p>

        </div>

        <div class="users-hero-mark">
            <i class="bi bi-people-fill"></i>
        </div>

    </div>


    {{-- Statistics --}}
    <div class="row g-4 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-blue">

                <div>
                    <span>Total users</span>

                    <strong>
                        {{ number_format($stats['total']) }}
                    </strong>
                </div>

                <i class="bi bi-people"></i>

            </div>
        </div>


        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-green">

                <div>
                    <span>Active users</span>

                    <strong>
                        {{ number_format($stats['active']) }}
                    </strong>
                </div>

                <i class="bi bi-person-check"></i>

            </div>
        </div>


        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-orange">

                <div>
                    <span>Inactive / banned</span>

                    <strong>
                        {{ number_format($stats['inactive']) }}
                    </strong>
                </div>

                <i class="bi bi-person-slash"></i>

            </div>
        </div>


        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-purple">

                <div>
                    <span>Admin users</span>

                    <strong>
                        {{ number_format($stats['admins']) }}
                    </strong>
                </div>

                <i class="bi bi-shield-lock"></i>

            </div>
        </div>

    </div>


    {{-- Users Panel --}}
    <div class="dashboard-panel users-panel">

        {{-- Header --}}
        <div class="panel-header users-panel-header">

            <div class="users-heading-content">

                <span class="eyebrow">
                    Community directory
                </span>

                <h5>
                    All users
                </h5>

                <p>
                    Search and review every account in your marketplace.
                </p>

            </div>


            <div class="users-header-action">

                <a
                    href="{{ route('admin.users.create') }}"
                    class="btn btn-primary users-add-btn"
                >
                    <i class="bi bi-person-plus"></i>

                    <span>
                        Add user
                    </span>
                </a>

            </div>

        </div>


        {{-- Filters --}}
        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
            class="user-filters"
        >

            {{-- Search --}}
            <div class="search-field">

                <i class="bi bi-search"></i>

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search name, email, username or phone..."
                    aria-label="Search users"
                >

            </div>


            {{-- Role --}}
            <select
                name="role"
                class="form-select role-filter"
                aria-label="Filter by role"
            >

                <option value="">
                    All roles
                </option>

                @foreach($roles as $roleOption)

                    <option
                        value="{{ $roleOption->name }}"
                        @selected($role === $roleOption->name)
                    >
                        {{ $roleOption->display_name }}
                    </option>

                @endforeach

            </select>


            {{-- Status --}}
            <select
                name="status"
                class="form-select status-filter"
                aria-label="Filter by status"
            >

                <option value="">
                    All statuses
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


            {{-- Filter --}}
            <button
                type="submit"
                class="btn btn-primary filter-button"
            >
                <i class="bi bi-funnel"></i>
                <span>Filter</span>
            </button>


            {{-- Clear --}}
            @if($search || $role || $status)

                <a
                    href="{{ route('admin.users.index') }}"
                    class="clear-filter"
                >
                    Clear
                </a>

            @endif

        </form>


        {{-- Users Table --}}
        <div class="users-table-wrap">

            <table class="table users-table align-middle">

                <thead>

                    <tr>

                        <th>
                            User
                        </th>

                        <th class="d-none d-md-table-cell">
                            Email
                        </th>

                        <th class="d-none d-lg-table-cell">
                            Phone
                        </th>

                        <th>
                            Role
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="d-none d-xl-table-cell">
                            Registered
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $user)

                        @php

                            $displayName = $user->full_name
                                ?: ($user->name ?: $user->username);

                        @endphp


                        <tr id="user-row-{{ $user->id }}">

                            {{-- User --}}
                            <td>

                                <div class="member-cell">

                                    @if($user->profile_photo)

                                        <img
                                            src="{{ asset('storage/' . $user->profile_photo) }}"
                                            alt="{{ $displayName }}"
                                            class="member-avatar member-avatar-image"
                                        >

                                    @else

                                        <div class="member-avatar">
                                            {{ strtoupper(substr($displayName, 0, 1)) }}
                                        </div>

                                    @endif


                                    <div class="member-info">

                                        <strong>
                                            {{ $displayName }}
                                        </strong>

                                        <small>
                                            {{ '@' . $user->username }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}
                            <td class="d-none d-md-table-cell">

                                <span class="user-email">
                                    {{ $user->email }}
                                </span>

                                <small class="verification-note">

                                    <i class="bi bi-{{
                                        $user->email_verified_at
                                            ? 'check-circle'
                                            : 'clock'
                                    }}"></i>

                                    {{
                                        $user->email_verified_at
                                            ? 'Verified'
                                            : 'Unverified'
                                    }}

                                </small>

                            </td>


                            {{-- Phone --}}
                            <td class="d-none d-lg-table-cell">

                                <span class="user-phone">
                                    {{ $user->phone ?: '-' }}
                                </span>

                            </td>


                            {{-- Role --}}
                            <td>
                                @php
                                    $primaryRole = $user->roles->first();
                                    $roleName = $primaryRole?->name ?? $user->role ?? 'user';
                                    $roleDisplayName = $primaryRole?->display_name ?? ucfirst($user->role ?? 'user');
                                @endphp

                                <span
                                    class="role-pill {{
                                        $roleName === 'super-admin' || $roleName === 'admin'
                                            ? 'role-admin'
                                            : 'role-member'
                                    }}"
                                >
                                    <i class="bi {{
                                        $roleName === 'super-admin'
                                            ? 'bi-shield-fill-check'
                                            : ($roleName === 'admin'
                                                ? 'bi-stars'
                                                : 'bi-person')
                                    }}"></i>

                                    {{ $roleDisplayName }}
                                </span>
                            </td>


                            {{-- Status --}}
                            <td>

                                <span class="status-pill status-{{ $user->status }}">

                                    <i class="bi bi-circle-fill"></i>

                                    {{ ucfirst($user->status) }}

                                </span>

                            </td>


                            {{-- Registered --}}
                            <td class="d-none d-xl-table-cell">

                                <span class="joined-date">
                                    {{ $user->created_at?->format('d M Y') }}
                                </span>

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="user-actions">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        class="btn btn-light btn-sm border user-action-btn"
                                        title="View"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="btn btn-warning btn-sm user-action-btn"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    @if(auth()->id() !== $user->id)

                                        {{-- Activate / Deactivate --}}
                                        <form
                                            action="{{ route('admin.users.status', $user) }}"
                                            method="POST"
                                            class="user-status-form"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="{{
                                                    $user->status === 'active'
                                                        ? 'inactive'
                                                        : 'active'
                                                }}"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-light btn-sm border user-action-btn"
                                                title="{{
                                                    $user->status === 'active'
                                                        ? 'Deactivate'
                                                        : 'Activate'
                                                }}"
                                            >

                                                <i class="bi bi-{{
                                                    $user->status === 'active'
                                                        ? 'pause'
                                                        : 'play'
                                                }}-circle"></i>

                                            </button>

                                        </form>


                                        {{-- Ban --}}
                                        @if($user->status !== 'banned')

                                            <form
                                                action="{{ route('admin.users.status', $user) }}"
                                                method="POST"
                                                class="user-status-form"
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
                                                    class="btn btn-light btn-sm border text-danger user-action-btn"
                                                    title="Ban"
                                                >
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>

                                            </form>

                                        @endif


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            class="delete-user-form"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $displayName }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm user-action-btn"
                                                title="Delete"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr id="users-empty-row">

                            <td
                                colspan="7"
                                class="empty-state"
                            >

                                <i class="bi bi-person-x"></i>

                                <strong>
                                    No users found
                                </strong>

                                <span>
                                    Try another search or clear the filters.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($users->hasPages())

            <div class="users-pagination">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>


{{-- Delete User JS --}}
@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    const deleteForms =
        document.querySelectorAll('.delete-user-form');

    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            const userId =
                form.dataset.userId;

            const userName =
                form.dataset.userName || 'this user';

            const csrfToken =
                form.querySelector(
                    'input[name="_token"]'
                )?.value;

            if (!csrfToken) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token not found.'
                });

                return;
            }


            Swal.fire({

                title: 'Delete user?',

                text:
                    `"${userName}" will be permanently deleted.`,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Delete user',

                cancelButtonText: 'Cancel',

                buttonsStyling: false,

                customClass: {
                    popup: 'user-delete-popup',
                    confirmButton: 'user-delete-confirm',
                    cancelButton: 'user-delete-cancel'
                }

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                Swal.fire({

                    title: 'Deleting...',

                    text: 'Please wait.',

                    allowOutsideClick: false,

                    allowEscapeKey: false,

                    showConfirmButton: false,

                    didOpen: function () {
                        Swal.showLoading();
                    }

                });


                fetch(form.action, {

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },

                    body: new URLSearchParams({
                        _token: csrfToken,
                        _method: 'DELETE'
                    })

                })

                .then(async function (response) {

                    const contentType =
                        response.headers.get(
                            'content-type'
                        ) || '';


                    if (!contentType.includes(
                        'application/json'
                    )) {

                        throw new Error(
                            'Server returned an invalid response.'
                        );

                    }


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Something went wrong while deleting the user.'
                        );

                    }


                    return data;

                })

                .then(function (data) {

                    const row =
                        document.getElementById(
                            'user-row-' + userId
                        );


                    if (row) {
                        row.remove();
                    }


                    const remainingRows =
                        document.querySelectorAll(
                            '.users-table tbody tr[id^="user-row-"]'
                        );


                    if (remainingRows.length === 0) {

                        const tbody =
                            document.querySelector(
                                '.users-table tbody'
                            );


                        if (tbody) {

                            const emptyRow =
                                document.createElement('tr');


                            emptyRow.id =
                                'users-empty-row';


                            emptyRow.innerHTML = `
                                <td
                                    colspan="7"
                                    class="empty-state"
                                >

                                    <i class="bi bi-person-x"></i>

                                    <strong>
                                        No users found
                                    </strong>

                                    <span>
                                        There are no users to display.
                                    </span>

                                </td>
                            `;


                            tbody.appendChild(emptyRow);

                        }

                    }


                    Swal.fire({

                        icon: 'success',

                        title: 'Deleted',

                        text:
                            data.message ||
                            'User deleted successfully.',

                        timer: 1200,

                        showConfirmButton: false

                    });

                })

                .catch(function (error) {

                    console.error(
                        'User delete error:',
                        error
                    );


                    Swal.fire({

                        icon: 'error',

                        title: 'Delete Failed',

                        text:
                            error.message ||
                            'Something went wrong while deleting the user.'

                    });

                });

            });

        });

    });



    /*
    |--------------------------------------------------------------------------
    | ACTIVATE / DEACTIVATE / BAN USER
    |--------------------------------------------------------------------------
    */

    const statusForms =
        document.querySelectorAll('.user-status-form');


    statusForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();


            const statusInput =
                form.querySelector(
                    'input[name="status"]'
                );


            const newStatus =
                statusInput?.value;


            if (!newStatus) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'User status could not be determined.'
                });

                return;
            }


            const csrfToken =
                form.querySelector(
                    'input[name="_token"]'
                )?.value;


            if (!csrfToken) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token not found.'
                });

                return;
            }


            const userRow =
                form.closest('tr');


            const userName =
                userRow
                    ?.querySelector(
                        '.member-info strong'
                    )
                    ?.textContent
                    .trim()
                || 'this user';


            let title =
                'Change user status?';

            let text =
                `"${userName}" status will be updated.`;

            let confirmText =
                'Continue';


            if (newStatus === 'active') {

                title =
                    'Activate user?';

                text =
                    `"${userName}" will be activated.`;

                confirmText =
                    'Activate';

            }


            if (newStatus === 'inactive') {

                title =
                    'Deactivate user?';

                text =
                    `"${userName}" will be deactivated.`;

                confirmText =
                    'Deactivate';

            }


            if (newStatus === 'banned') {

                title =
                    'Ban user?';

                text =
                    `"${userName}" will be banned.`;

                confirmText =
                    'Ban user';

            }


            Swal.fire({

                title: title,

                text: text,

                icon:
                    newStatus === 'banned'
                        ? 'warning'
                        : 'question',

                showCancelButton: true,

                confirmButtonText:
                    confirmText,

                cancelButtonText:
                    'Cancel',

                buttonsStyling: false,

                customClass: {
                    popup: 'user-delete-popup',
                    confirmButton: 'user-delete-confirm',
                    cancelButton: 'user-delete-cancel'
                }

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                Swal.fire({

                    title: 'Updating...',

                    text: 'Please wait.',

                    allowOutsideClick: false,

                    allowEscapeKey: false,

                    showConfirmButton: false,

                    didOpen: function () {
                        Swal.showLoading();
                    }

                });


                fetch(form.action, {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                            csrfToken,

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'

                    },

                    body: new URLSearchParams({

                        _token:
                            csrfToken,

                        _method:
                            'PATCH',

                        status:
                            newStatus

                    })

                })

                .then(async function (response) {

                    const contentType =
                        response.headers.get(
                            'content-type'
                        ) || '';


                    if (!contentType.includes(
                        'application/json'
                    )) {

                        throw new Error(
                            'Server returned an invalid response.'
                        );

                    }


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Something went wrong while updating the user.'
                        );

                    }


                    return data;

                })

                .then(function (data) {

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE STATUS BADGE
                    |--------------------------------------------------------------------------
                    */

                    const statusBadge =
                        userRow?.querySelector(
                            '.status-pill'
                        );


                    if (statusBadge) {

                        statusBadge.className =
                            'status-pill status-' +
                            newStatus;


                        statusBadge.innerHTML =
                            `
                            <i class="bi bi-circle-fill"></i>
                            ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}
                            `;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVATE / DEACTIVATE BUTTON
                    |--------------------------------------------------------------------------
                    */

                    if (
                        newStatus === 'active' ||
                        newStatus === 'inactive'
                    ) {

                        statusInput.value =
                            newStatus === 'active'
                                ? 'inactive'
                                : 'active';


                        const button =
                            form.querySelector(
                                'button'
                            );


                        if (button) {

                            if (newStatus === 'active') {

                                button.title =
                                    'Deactivate';

                                button.innerHTML =
                                    `
                                    <i class="bi bi-pause-circle"></i>
                                    `;

                            } else {

                                button.title =
                                    'Activate';

                                button.innerHTML =
                                    `
                                    <i class="bi bi-play-circle"></i>
                                    `;

                            }

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | BAN USER
                    |--------------------------------------------------------------------------
                    */

                    if (newStatus === 'banned') {

                        /*
                        | Remove current status form
                        */

                        form.remove();


                        /*
                        | Remove the other status form
                        */

                        if (userRow) {

                            const otherStatusForm =
                                userRow.querySelector(
                                    '.user-status-form'
                                );

                            if (otherStatusForm) {
                                otherStatusForm.remove();
                            }

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS MESSAGE
                    |--------------------------------------------------------------------------
                    */

                    Swal.fire({

                        icon: 'success',

                        title: 'Updated',

                        text:
                            data.message ||
                            'User status updated successfully.',

                        timer: 1200,

                        showConfirmButton: false

                    });

                })

                .catch(function (error) {

                    console.error(
                        'User status update error:',
                        error
                    );


                    Swal.fire({

                        icon: 'error',

                        title: 'Update Failed',

                        text:
                            error.message ||
                            'Something went wrong while updating the user.'

                    });

                });

            });

        });

    });

});
</script>

@endpush

@endsection