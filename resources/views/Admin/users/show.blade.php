@extends('layout.admin.master')

@section('title', 'User Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/pages/users.css') }}">
@endpush

@section('content')

@php
    $displayName = $user->full_name
        ?: ($user->name ?: $user->username);

    $roleName = $user->roles->first()?->display_name
        ?? ucfirst($user->role);

    $orderCount = $user->orders_count ?? 0;
    $orderTotal = $user->orders_sum_total_price ?? 0;
@endphp

<div class="dashboard-section users-page users-show-page">

    {{-- =========================================================
         HERO
         ========================================================= --}}
    <div class="users-edit-hero users-show-hero mb-4">

        <div class="users-edit-hero-content">

            <div class="users-edit-breadcrumb">

                <a href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    Users
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>User Details</span>

            </div>

            <span class="hero-badge">
                <i class="bi bi-person-vcard"></i>
                Account Overview
            </span>

            <h1>
                {{ $displayName }}
            </h1>

            <p>
                View profile information, account activity and marketplace details.
            </p>

        </div>

        <div class="users-edit-hero-mark">
            <i class="bi bi-person-vcard"></i>
        </div>

    </div>


    {{-- =========================================================
         TOP PROFILE CARD
         ========================================================= --}}
    <div class="dashboard-panel users-show-profile mb-4">

        <div class="users-show-profile-main">

            {{-- Avatar --}}
            <div class="users-show-avatar-wrap">

                @if($user->profile_photo)

                    <img
                        src="{{ asset('storage/' . $user->profile_photo) }}"
                        alt="{{ $displayName }}"
                        class="users-show-avatar"
                    >

                @else

                    <div class="users-show-avatar users-show-avatar-initial">
                        {{ strtoupper(substr($displayName, 0, 1)) }}
                    </div>

                @endif

            </div>


            {{-- Main Info --}}
            <div class="users-show-profile-info">

                <div class="users-show-name-row">

                    <h2>
                        {{ $displayName }}
                    </h2>

                    <span class="role-pill
                        {{
                            $user->role === 'admin'
                                ? 'role-admin'
                                : 'role-member'
                        }}"
                    >
                        <i class="bi {{
                            $user->role === 'admin'
                                ? 'bi-stars'
                                : 'bi-person'
                        }}"></i>

                        {{ $roleName }}
                    </span>

                </div>

                <span class="users-show-username">
                    {{ '@' . $user->username }}
                </span>

                <span class="users-show-email">
                    <i class="bi bi-envelope"></i>
                    {{ $user->email }}
                </span>

            </div>


            {{-- Status --}}
            <div class="users-show-profile-status">

                <span class="users-show-status-label">
                    Account status
                </span>

                <span class="status-pill status-{{ $user->status }}">
                    <i class="bi bi-circle-fill"></i>
                    {{ ucfirst($user->status) }}
                </span>

            </div>

        </div>


        {{-- Quick Stats --}}
        <div class="users-show-quick-stats">

            <div class="users-show-quick-stat">

                <div class="users-show-quick-icon">
                    <i class="bi bi-bag-check"></i>
                </div>

                <div>
                    <span>Orders</span>
                    <strong>{{ $orderCount }}</strong>
                </div>

            </div>


            <div class="users-show-quick-stat">

                <div class="users-show-quick-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>

                <div>
                    <span>Total spent</span>
                    <strong>
                        {{ number_format((float) $orderTotal, 2) }}
                    </strong>
                </div>

            </div>


            <div class="users-show-quick-stat">

                <div class="users-show-quick-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div>
                    <span>Joined</span>
                    <strong>
                        {{ $user->created_at?->format('d M Y') }}
                    </strong>
                </div>

            </div>


            <div class="users-show-quick-stat">

                <div class="users-show-quick-icon">
                    <i class="bi bi-envelope-check"></i>
                </div>

                <div>
                    <span>Email</span>

                    <strong>
                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN CONTENT
         ========================================================= --}}
    <div class="row g-4">


        {{-- =====================================================
             LEFT
             ===================================================== --}}
        <div class="col-12 col-xl-8">


            {{-- Personal Information --}}
            <div class="dashboard-panel users-show-panel mb-4">

                <div class="users-edit-panel-header">

                    <div class="users-edit-section-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <div>

                        <span class="eyebrow">
                            Profile information
                        </span>

                        <h5>
                            Personal information
                        </h5>

                        <p>
                            Basic information associated with this account.
                        </p>

                    </div>

                </div>


                <div class="users-show-info-grid">

                    <div class="users-show-info-item">

                        <span>
                            First name
                        </span>

                        <strong>
                            {{ $user->first_name ?: '-' }}
                        </strong>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Last name
                        </span>

                        <strong>
                            {{ $user->last_name ?: '-' }}
                        </strong>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Username
                        </span>

                        <strong>
                            {{ '@' . $user->username }}
                        </strong>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Email
                        </span>

                        <strong class="users-show-break">
                            {{ $user->email }}
                        </strong>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Phone
                        </span>

                        <strong>
                            {{ $user->phone ?: '-' }}
                        </strong>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Registered
                        </span>

                        <strong>
                            {{ $user->created_at?->format('d M Y, H:i') }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Account Information --}}
            <div class="dashboard-panel users-show-panel mb-4">

                <div class="users-edit-panel-header">

                    <div class="users-edit-section-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>

                        <span class="eyebrow">
                            Account access
                        </span>

                        <h5>
                            Account information
                        </h5>

                        <p>
                            Current role, status and verification details.
                        </p>

                    </div>

                </div>


                <div class="users-show-info-grid">

                    <div class="users-show-info-item">

                        <span>
                            Role
                        </span>

                        <div>

                            <span class="role-pill
                                {{
                                    $user->role === 'admin'
                                        ? 'role-admin'
                                        : 'role-member'
                                }}"
                            >
                                <i class="bi {{
                                    $user->role === 'admin'
                                        ? 'bi-stars'
                                        : 'bi-person'
                                }}"></i>

                                {{ $roleName }}
                            </span>

                        </div>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Status
                        </span>

                        <div>

                            <span class="status-pill status-{{ $user->status }}">
                                <i class="bi bi-circle-fill"></i>
                                {{ ucfirst($user->status) }}
                            </span>

                        </div>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Email verification
                        </span>

                        <div>

                            @if($user->email_verified_at)

                                <span class="users-show-verification verified">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Verified
                                </span>

                            @else

                                <span class="users-show-verification unverified">
                                    <i class="bi bi-clock"></i>
                                    Unverified
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="users-show-info-item">

                        <span>
                            Last verification
                        </span>

                        <strong>
                            {{
                                $user->email_verified_at
                                    ? $user->email_verified_at->format('d M Y, H:i')
                                    : '-'
                            }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Marketplace Activity --}}
            <div class="dashboard-panel users-show-panel">

                <div class="users-edit-panel-header">

                    <div class="users-edit-section-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <div>

                        <span class="eyebrow">
                            Marketplace activity
                        </span>

                        <h5>
                            User activity
                        </h5>

                        <p>
                            Overview of this account's marketplace activity.
                        </p>

                    </div>

                </div>


                <div class="users-show-activity-grid">

                    <div class="users-show-activity-card">

                        <div class="users-show-activity-icon">
                            <i class="bi bi-bag"></i>
                        </div>

                        <div>

                            <span>
                                Total orders
                            </span>

                            <strong>
                                {{ $orderCount }}
                            </strong>

                        </div>

                    </div>


                    <div class="users-show-activity-card">

                        <div class="users-show-activity-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>

                        <div>

                            <span>
                                Total order value
                            </span>

                            <strong>
                                {{ number_format((float) $orderTotal, 2) }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT
             ===================================================== --}}
        <div class="col-12 col-xl-4">


            {{-- Account Summary --}}
            <div class="dashboard-panel users-show-side-panel mb-4">

                <div class="users-show-side-header">

                    <div class="users-show-side-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                    <div>

                        <h5>
                            Account summary
                        </h5>

                        <span>
                            Current account details
                        </span>

                    </div>

                </div>


                <div class="users-show-summary-list">

                    <div>

                        <span>
                            Account ID
                        </span>

                        <strong>
                            #{{ $user->id }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Username
                        </span>

                        <strong>
                            {{ '@' . $user->username }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Role
                        </span>

                        <strong>
                            {{ $roleName }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Status
                        </span>

                        <strong class="users-show-summary-status status-{{ $user->status }}">
                            {{ ucfirst($user->status) }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Member since
                        </span>

                        <strong>
                            {{ $user->created_at?->format('d M Y') }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Email Card --}}
            <div class="dashboard-panel users-show-side-panel mb-4">

                <div class="users-show-side-header">

                    <div class="users-show-side-icon">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <div>

                        <h5>
                            Email address
                        </h5>

                        <span>
                            Verification status
                        </span>

                    </div>

                </div>


                <div class="users-show-email-card">

                    <div class="users-show-email-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

                    <div>

                        <strong>
                            {{ $user->email }}
                        </strong>

                        @if($user->email_verified_at)

                            <span class="users-show-verification verified">
                                <i class="bi bi-check-circle-fill"></i>
                                Verified
                            </span>

                        @else

                            <span class="users-show-verification unverified">
                                <i class="bi bi-clock"></i>
                                Unverified
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="dashboard-panel users-show-actions">

                <a
                    href="{{ route('admin.users.edit', $user) }}"
                    class="users-show-edit-btn"
                >
                    <i class="bi bi-pencil"></i>
                    <span>Edit user</span>
                </a>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="users-show-back-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to users</span>
                </a>

            </div>

        </div>

    </div>

</div>

@endsection