@extends('layout.admin.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/roles.css') }}">
@endpush

@section('title', 'Role Details')

@section('content')

<div class="dashboard-section roles-page role-show-page">


{{-- Role Hero --}}
<div class="dashboard-panel role-detail-panel mb-4">

    <div class="role-detail-hero">

        <div class="role-detail-main">

            <span class="eyebrow">
                <i class="bi bi-shield-lock me-1"></i>
                Access control
            </span>

            <div class="role-detail-title">

                <div class="role-detail-icon {{ $role->is_system ? 'system-role-detail-icon' : '' }}">
                    <i class="bi {{ $role->name === 'super-admin' ? 'bi-stars' : 'bi-person-badge' }}"></i>
                </div>

                <div>
                    <h2>{{ $role->display_name }}</h2>

                    <div class="role-detail-meta">
                        <span>{{ $role->name }}</span>

                        @if($role->is_system)
                            <span class="system-badge large">
                                <i class="bi bi-shield-check"></i>
                                System role
                            </span>
                        @else
                            <span class="custom-role-badge">
                                <i class="bi bi-sliders"></i>
                                Custom role
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            <p class="role-detail-description">
                {{ $role->description ?: 'No description has been added for this role.' }}
            </p>

        </div>

        <div class="role-detail-actions">

            <a
                href="{{ route('admin.roles.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Back to roles
            </a>

            @can('roles.edit')
                <a
                    href="{{ route('admin.roles.edit', $role) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-2"></i>
                    Edit role
                </a>
            @endcan

        </div>

    </div>

</div>


{{-- Statistics --}}
<div class="row g-4 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-detail-stat stat-blue">

            <div>
                <span>Total users</span>
                <strong>{{ number_format($role->users_count ?? $role->users->count()) }}</strong>
            </div>

            <i class="bi bi-people"></i>

        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-detail-stat stat-green">

            <div>
                <span>Permissions</span>
                <strong>{{ number_format($role->permissions_count ?? $role->permissions->count()) }}</strong>
            </div>

            <i class="bi bi-key"></i>

        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-detail-stat stat-purple">

            <div>
                <span>Role type</span>

                <strong class="role-type-value">
                    {{ $role->is_system ? 'System' : 'Custom' }}
                </strong>
            </div>

            <i class="bi bi-shield-check"></i>

        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="role-detail-stat stat-orange">

            <div>
                <span>Created</span>

                <strong class="role-date-value">
                    {{ $role->created_at?->format('d M Y') }}
                </strong>
            </div>

            <i class="bi bi-calendar3"></i>

        </div>
    </div>

</div>


{{-- Permissions --}}
<div class="dashboard-panel permission-summary-panel mb-4">

    <div class="panel-header role-show-panel-header">

        <div>
            <span class="eyebrow">Access matrix</span>

            <h5>Assigned permissions</h5>

            <p>
                Permissions currently assigned to this role.
            </p>
        </div>

        <span class="permission-total-badge">
            <i class="bi bi-key me-1"></i>
            {{ $role->permissions->count() }} permissions
        </span>

    </div>


    @if($role->permissions->count())

        @php
            $groupedPermissions = $role->permissions->groupBy(function ($permission) {
                return $permission->group_name ?? 'General';
            });
        @endphp

        <div class="permission-summary-grid">

            @foreach($groupedPermissions as $module => $permissions)

                <div class="permission-summary-group">

                    <div class="permission-summary-group-header">

                        <div>
                            <h6>{{ ucfirst($module) }}</h6>

                            <span>
                                {{ $permissions->count() }}
                                {{ Str::plural('permission', $permissions->count()) }}
                            </span>
                        </div>

                        <i class="bi bi-folder2-open"></i>

                    </div>

                    <div class="permission-badges">

                        @foreach($permissions as $permission)

                            <span class="permission-badge">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ $permission->display_name ?? $permission->name }}
                            </span>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="role-show-empty-state">
            <i class="bi bi-key"></i>

            <strong>No permissions assigned</strong>

            <span>
                This role currently does not have any access permissions.
            </span>
        </div>

    @endif

</div>


{{-- Users --}}
<div class="dashboard-panel role-users-panel">

    <div class="panel-header role-show-panel-header">

        <div>
            <span class="eyebrow">Role members</span>

            <h5>Users with this role</h5>

            <p>
                Accounts currently assigned to {{ $role->display_name }}.
            </p>
        </div>

        <span class="permission-total-badge">
            <i class="bi bi-people me-1"></i>
            {{ $role->users->count() }} users
        </span>

    </div>


    @if($role->users->count())

        <div class="role-users-list">

            @foreach($role->users as $user)

                <div class="role-user-row">

                    <div class="role-user-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div class="role-user-info">

                        <strong>{{ $user->name }}</strong>

                        <small>{{ $user->email }}</small>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="role-show-empty-state role-users-empty">
            <i class="bi bi-people"></i>

            <strong>No users assigned</strong>

            <span>
                No users are currently assigned to this role.
            </span>
        </div>

    @endif

</div>


</div>

@endsection
