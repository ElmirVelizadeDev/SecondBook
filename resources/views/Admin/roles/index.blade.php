@extends('layout.admin.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/roles.css') }}">
@endpush

@section('title', 'Roles')

@section('content')

<div class="dashboard-section roles-page">


<div class="roles-hero mb-4">
    <div class="roles-hero-content">
        <span class="hero-badge">
            <i class="bi bi-shield-lock"></i>
            Access control
        </span>

        <h1>Roles & Permissions</h1>

        <p>Manage roles and access permissions across SecondBook.</p>
    </div>

    <div class="roles-hero-mark">
        <i class="bi bi-diagram-3"></i>
    </div>
</div>

<div class="row g-4 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-stat-card stat-blue">
            <div>
                <span>Total roles</span>
                <strong>{{ number_format($stats['roles']) }}</strong>
            </div>

            <i class="bi bi-person-badge"></i>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-stat-card stat-green">
            <div>
                <span>Total permissions</span>
                <strong>{{ number_format($stats['permissions']) }}</strong>
            </div>

            <i class="bi bi-key"></i>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-stat-card stat-orange">
            <div>
                <span>System roles</span>
                <strong>{{ number_format($stats['system']) }}</strong>
            </div>

            <i class="bi bi-shield-check"></i>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-stat-card stat-purple">
            <div>
                <span>Custom roles</span>
                <strong>{{ number_format($stats['custom']) }}</strong>
            </div>

            <i class="bi bi-sliders"></i>
        </div>
    </div>

</div>

<div class="dashboard-panel roles-panel">

    <div class="panel-header roles-panel-header">

        <div>
            <span class="eyebrow">Access directory</span>

            <h5>All roles</h5>

            <p>Review who can access each part of the admin panel.</p>
        </div>

        @can('roles.create')
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Create role
            </a>
        @endcan

    </div>

    <div class="table-responsive roles-table-wrap">

        <table class="table roles-table align-middle">

            <thead>
                <tr>
                    <th>Role</th>
                    <th>Description</th>
                    <th>Users</th>
                    <th>Permissions</th>
                    <th class="d-none d-lg-table-cell">Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($roles as $role)

                    <tr>

                        <td>
                            <div class="role-name-cell">

                                <div class="role-icon {{ $role->is_system ? 'system-role-icon' : '' }}">
                                    <i class="bi {{ $role->name === 'super-admin' ? 'bi-stars' : 'bi-person-badge' }}"></i>
                                </div>

                                <div>
                                    <strong>{{ $role->display_name }}</strong>

                                    <small>
                                        {{ $role->name }}

                                        @if($role->is_system)
                                            <span class="system-badge">System</span>
                                        @endif
                                    </small>
                                </div>

                            </div>
                        </td>

                        <td>
                            <span class="role-description">
                                {{ $role->description ?: 'No description' }}
                            </span>
                        </td>

                        <td>
                            <span class="role-count">
                                <i class="bi bi-people"></i>
                                {{ number_format($role->users_count) }}
                            </span>
                        </td>

                        <td>
                            <span class="role-count">
                                <i class="bi bi-key"></i>
                                {{ number_format($role->permissions_count) }}
                            </span>
                        </td>

                        <td class="d-none d-lg-table-cell">
                            <span class="role-created">
                                {{ $role->created_at?->format('d M Y') }}
                            </span>
                        </td>

                        <td>

                            <div class="role-actions">

                                <a
                                    href="{{ route('admin.roles.show', $role) }}"
                                    class="btn btn-light btn-sm border"
                                    title="View"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>

                                @can('roles.edit')
                                    <a
                                        href="{{ route('admin.roles.edit', $role) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan

                                @if(!$role->is_system)

                                    @can('roles.delete')
                                        <form
                                            action="{{ route('admin.roles.destroy', $role) }}"
                                            method="POST"
                                            class="delete-role-form"
                                            data-role-name="{{ $role->display_name }}"
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
                                    @endcan

                                @else

                                    <span
                                        class="system-lock"
                                        title="System role cannot be deleted"
                                    >
                                        <i class="bi bi-lock"></i>
                                    </span>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="role-empty-state">
                            <i class="bi bi-shield-x"></i>

                            <strong>No roles found</strong>

                            <span>
                                Run the role permission seeder to create system roles.
                            </span>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($roles->hasPages())
        <div class="roles-pagination">
            {{ $roles->links() }}
        </div>
    @endif

</div>


</div>

@push('js')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.delete-role-form').forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({
                    title: 'Delete role?',
                    text: 'This will permanently remove ' + (form.dataset.roleName || 'this role') + '.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete role',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true
                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });

    });
</script>

@endpush

@endsection
