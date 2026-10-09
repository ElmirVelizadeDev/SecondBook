
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

            <h1>People behind every page.</h1>

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
                    <strong>{{ number_format($stats['total']) }}</strong>
                </div>
                <i class="bi bi-people"></i>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-green">
                <div>
                    <span>Active users</span>
                    <strong>{{ number_format($stats['active']) }}</strong>
                </div>
                <i class="bi bi-person-check"></i>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-orange">
                <div>
                    <span>Inactive / banned</span>
                    <strong>{{ number_format($stats['inactive']) }}</strong>
                </div>
                <i class="bi bi-person-slash"></i>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="user-stat-card stat-purple">
                <div>
                    <span>Admin users</span>
                    <strong>{{ number_format($stats['admins']) }}</strong>
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
                <span class="eyebrow">Community directory</span>
                <h5>All users</h5>
                <p>Search and review every account in your marketplace.</p>
            </div>

            <div class="users-header-action">
                <a href="{{ route('admin.users.create') }}"
                   class="btn btn-primary users-add-btn">
                    <i class="bi bi-person-plus"></i>
                    <span>Add user</span>
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET"
              action="{{ route('admin.users.index') }}"
              class="user-filters"
              id="users-filter-form">

            <div class="search-field">
                <i class="bi bi-search"></i>
                <input type="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search name, email, username or phone..."
                       aria-label="Search users">
            </div>

            <select name="role"
                    class="form-select role-filter"
                    aria-label="Filter by role">
                <option value="">All roles</option>

                @foreach($roles as $roleOption)
                    <option value="{{ $roleOption->name }}"
                        @selected($role === $roleOption->name)>
                        {{ $roleOption->display_name }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                    class="form-select status-filter"
                    aria-label="Filter by status">
                <option value="">All statuses</option>

                <option value="active" @selected($status === 'active')>
                    Active
                </option>

                <option value="inactive" @selected($status === 'inactive')>
                    Inactive
                </option>

                <option value="banned" @selected($status === 'banned')>
                    Banned
                </option>
            </select>

            <button type="submit" class="btn btn-primary filter-button">
                <i class="bi bi-funnel"></i>
                <span>Filter</span>
            </button>

            <a href="{{ route('admin.users.index') }}"
               class="clear-filter"
               id="users-clear-filter"
               @if(!$search && !$role && !$status) hidden @endif>
                Clear
            </a>
        </form>

        {{-- Users Table --}}
        <div id="users-results">
            <div class="users-table-wrap">
                <table class="table users-table align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th class="d-none d-md-table-cell">Email</th>
                            <th class="d-none d-lg-table-cell">Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="d-none d-xl-table-cell">Registered</th>
                            <th class="text-end">Actions</th>
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
                                                class="member-avatar member-avatar-image">
                                        @else
                                            <div class="member-avatar">
                                                {{ strtoupper(substr($displayName, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="member-info">
                                            <strong>{{ $displayName }}</strong>
                                            <small>{{ '@' . $user->username }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="d-none d-md-table-cell">
                                    <span class="user-email">{{ $user->email }}</span>

                                    <small class="verification-note">
                                        <i class="bi bi-{{ $user->email_verified_at ? 'check-circle' : 'clock' }}"></i>
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
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
                                        $roleDisplayName = $primaryRole?->display_name
                                            ?? ucfirst($user->role ?? 'user');
                                    @endphp

                                    <span class="role-pill {{
                                        $roleName === 'super-admin' || $roleName === 'admin'
                                            ? 'role-admin'
                                            : 'role-member'
                                    }}">
                                        <i class="bi {{
                                            $roleName === 'super-admin'
                                                ? 'bi-shield-fill-check'
                                                : ($roleName === 'admin' ? 'bi-stars' : 'bi-person')
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
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="btn btn-light btn-sm border user-action-btn"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-warning btn-sm user-action-btn"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            {{-- Activate / Deactivate --}}
                                            <form action="{{ route('admin.users.status', $user) }}"
                                                  method="POST"
                                                  class="user-status-form"
                                                  data-user-id="{{ $user->id }}">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status"
                                                       value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">

                                                <button type="submit"
                                                        class="btn btn-light btn-sm border user-action-btn"
                                                        title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $user->status === 'active' ? 'pause' : 'play' }}-circle"></i>
                                                </button>
                                            </form>

                                            {{-- Ban --}}
                                            @if($user->status !== 'banned')
                                                <form action="{{ route('admin.users.status', $user) }}"
                                                      method="POST"
                                                      class="user-status-form"
                                                      data-user-id="{{ $user->id }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status" value="banned">

                                                    <button type="submit"
                                                            class="btn btn-light btn-sm border text-danger user-action-btn"
                                                            title="Ban">
                                                        <i class="bi bi-slash-circle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.users.destroy', $user) }}"
                                                  method="POST"
                                                  class="delete-user-form"
                                                  data-user-id="{{ $user->id }}"
                                                  data-user-name="{{ $displayName }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm user-action-btn"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="users-empty-row">
                                <td colspan="7" class="empty-state">
                                    <i class="bi bi-person-x"></i>
                                    <strong>No users found</strong>
                                    <span>Try another search or clear the filters.</span>
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
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function showError(message, title = 'Error') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#2563eb'
        });
    }

    function showSuccess(message, title = 'Updated') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 1200,
            showConfirmButton: false
        });
    }

    function createStatusForm(userId, targetStatus, icon, title, extraClass = '') {
        const form = document.createElement('form');

        form.action = '{{ url('admin/users') }}/' + userId + '/status';
        form.method = 'POST';
        form.className = 'user-status-form';
        form.dataset.userId = userId;

        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" value="${targetStatus}">
            <button type="submit"
                    class="btn btn-light btn-sm border user-action-btn ${extraClass}"
                    title="${title}">
                <i class="bi ${icon}"></i>
            </button>
        `;

        return form;
    }

    function updateUserStatusUI(userRow, userId, newStatus) {
        if (!userRow) {
            return;
        }

        const statusBadge = userRow.querySelector('.status-pill');

        if (statusBadge) {
            statusBadge.className = 'status-pill status-' + newStatus;
            statusBadge.innerHTML = `
                <i class="bi bi-circle-fill"></i>
                ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}
            `;
        }

        const actions = userRow.querySelector('.user-actions');

        if (!actions) {
            return;
        }

        actions.querySelectorAll('.user-status-form').forEach(function (form) {
            form.remove();
        });

        const nextStatus = newStatus === 'active' ? 'inactive' : 'active';
        const statusIcon = newStatus === 'active'
            ? 'bi-pause-circle'
            : 'bi-play-circle';
        const statusTitle = newStatus === 'active' ? 'Deactivate' : 'Activate';

        const statusForm = createStatusForm(
            userId,
            nextStatus,
            statusIcon,
            statusTitle
        );

        let banForm = null;

        if (newStatus !== 'banned') {
            banForm = createStatusForm(
                userId,
                'banned',
                'bi-slash-circle',
                'Ban',
                'text-danger'
            );
        }

        const deleteForm = actions.querySelector('.delete-user-form');

        if (deleteForm) {
            actions.insertBefore(statusForm, deleteForm);

            if (banForm) {
                actions.insertBefore(banForm, deleteForm);
            }
        } else {
            actions.appendChild(statusForm);

            if (banForm) {
                actions.appendChild(banForm);
            }
        }

        bindStatusForm(statusForm);

        if (banForm) {
            bindStatusForm(banForm);
        }
    }

    function bindDeleteForm(form) {
        if (!form || form.dataset.bound === 'true') {
            return;
        }

        form.dataset.bound = 'true';

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const userId = form.dataset.userId;
            const userName = form.dataset.userName || 'this user';
            const csrfToken = form.querySelector('input[name="_token"]')?.value;

            if (!csrfToken) {
                showError('CSRF token not found.');
                return;
            }

            Swal.fire({
                title: 'Delete user?',
                text: `"${userName}" will be permanently deleted.`,
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
                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        throw new Error('Server returned an invalid response.');
                    }

                    const data = await response.json();

                    if (!response.ok || data.success === false) {
                        throw new Error(
                            data.message || 'Something went wrong while deleting the user.'
                        );
                    }

                    return data;
                })
                .then(function (data) {
                    const row = document.getElementById('user-row-' + userId);

                    if (row) {
                        row.style.transition = 'opacity .25s ease, transform .25s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(15px)';

                        setTimeout(function () {
                            row.remove();

                            const remainingRows = document.querySelectorAll(
                                '.users-table tbody tr[id^="user-row-"]'
                            );

                            if (remainingRows.length === 0) {
                                const tbody = document.querySelector('.users-table tbody');

                                if (tbody) {
                                    const emptyRow = document.createElement('tr');
                                    emptyRow.id = 'users-empty-row';

                                    emptyRow.innerHTML = `
                                        <td colspan="7" class="empty-state">
                                            <i class="bi bi-person-x"></i>
                                            <strong>No users found</strong>
                                            <span>There are no users to display.</span>
                                        </td>
                                    `;

                                    tbody.appendChild(emptyRow);
                                }
                            }
                        }, 250);
                    }

                    showSuccess(data.message || 'User deleted successfully.', 'Deleted');
                })
                .catch(function (error) {
                    console.error('User delete error:', error);
                    Swal.close();

                    showError(
                        error.message || 'Something went wrong while deleting the user.',
                        'Delete Failed'
                    );
                });
            });
        });
    }

    function bindStatusForm(form) {
        if (!form || form.dataset.bound === 'true') {
            return;
        }

        form.dataset.bound = 'true';

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const statusInput = form.querySelector('input[name="status"]');
            const newStatus = statusInput?.value;

            if (!newStatus) {
                showError('User status could not be determined.');
                return;
            }

            const csrfToken = form.querySelector('input[name="_token"]')?.value;

            if (!csrfToken) {
                showError('CSRF token not found.');
                return;
            }

            const userRow = form.closest('tr');
            const userId = userRow?.id.replace('user-row-', '');
            const userName = userRow
                ?.querySelector('.member-info strong')
                ?.textContent.trim() || 'this user';

            let title = 'Change user status?';
            let text = `"${userName}" status will be updated.`;
            let confirmText = 'Continue';

            if (newStatus === 'active') {
                title = 'Activate user?';
                text = `"${userName}" will be activated.`;
                confirmText = 'Activate';
            }

            if (newStatus === 'inactive') {
                title = 'Deactivate user?';
                text = `"${userName}" will be deactivated.`;
                confirmText = 'Deactivate';
            }

            if (newStatus === 'banned') {
                title = 'Ban user?';
                text = `"${userName}" will be banned.`;
                confirmText = 'Ban user';
            }

            Swal.fire({
                title: title,
                text: text,
                icon: newStatus === 'banned' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: confirmText,
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
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        _token: csrfToken,
                        _method: 'PATCH',
                        status: newStatus
                    })
                })
                .then(async function (response) {
                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        throw new Error('Server returned an invalid response.');
                    }

                    const data = await response.json();

                    if (!response.ok || data.success === false) {
                        throw new Error(
                            data.message || 'Something went wrong while updating the user.'
                        );
                    }

                    return data;
                })
                .then(function (data) {
                    updateUserStatusUI(userRow, userId, data.status || newStatus);

                    showSuccess(
                        data.message || 'User status updated successfully.'
                    );
                })
                .catch(function (error) {
                    console.error('User status update error:', error);
                    Swal.close();

                    showError(
                        error.message || 'Something went wrong while updating the user.',
                        'Update Failed'
                    );
                });
            });
        });
    }

    // Initial bindings
    document.querySelectorAll('.user-status-form').forEach(bindStatusForm);
    document.querySelectorAll('.delete-user-form').forEach(bindDeleteForm);

    // Filters and pagination
    const filterForm = document.getElementById('users-filter-form');
    const usersResults = document.getElementById('users-results');
    const clearFilter = document.getElementById('users-clear-filter');

    function updateClearFilter() {
        const search = filterForm.querySelector('[name="search"]').value.trim();
        const role = filterForm.querySelector('[name="role"]').value;
        const status = filterForm.querySelector('[name="status"]').value;

        if (clearFilter) {
            clearFilter.hidden = !(search || role || status);
        }
    }

    async function loadUsers(url, updateHistory = true) {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load users.');
            }

            const html = await response.text();
            const parsed = new DOMParser().parseFromString(html, 'text/html');
            const newResults = parsed.getElementById('users-results');
            const newForm = parsed.getElementById('users-filter-form');

            if (!newResults || !newForm) {
                throw new Error('Users table could not be loaded.');
            }

            usersResults.innerHTML = newResults.innerHTML;

            ['search', 'role', 'status'].forEach(function (name) {
                const current = filterForm.querySelector(`[name="${name}"]`);
                const updated = newForm.querySelector(`[name="${name}"]`);

                if (current && updated) {
                    current.value = updated.value;
                }
            });

            usersResults.querySelectorAll('.user-status-form').forEach(bindStatusForm);
            usersResults.querySelectorAll('.delete-user-form').forEach(bindDeleteForm);

            updateClearFilter();

            if (updateHistory) {
                history.pushState({}, '', url);
            }
        } catch (error) {
            console.error('Users filter error:', error);

            showError('Unable to load users. Please try again.', 'Loading Failed');
        }
    }

    if (filterForm && usersResults) {
        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const params = new URLSearchParams(new FormData(filterForm));

            for (const [key, value] of [...params.entries()]) {
                if (!value.trim()) {
                    params.delete(key);
                }
            }

            params.delete('page');

            const query = params.toString();
            const url = query
                ? `${filterForm.action}?${query}`
                : filterForm.action;

            loadUsers(url);
        });

        filterForm.querySelectorAll('[name="search"], [name="role"], [name="status"]')
            .forEach(function (input) {
                input.addEventListener('input', updateClearFilter);
                input.addEventListener('change', updateClearFilter);
            });

        if (clearFilter) {
            clearFilter.addEventListener('click', function (event) {
                event.preventDefault();

                filterForm.querySelector('[name="search"]').value = '';
                filterForm.querySelector('[name="role"]').value = '';
                filterForm.querySelector('[name="status"]').value = '';

                updateClearFilter();
                loadUsers(filterForm.action);
            });
        }

        usersResults.addEventListener('click', function (event) {
            const link = event.target.closest('.users-pagination a');

            if (!link) {
                return;
            }

            event.preventDefault();
            loadUsers(link.href);
        });

        window.addEventListener('popstate', function () {
            loadUsers(window.location.href, false);
        });
    }
});
</script>
@endpush

