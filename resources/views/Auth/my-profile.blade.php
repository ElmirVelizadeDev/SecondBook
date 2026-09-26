@extends('Layout.Frontend.master')

@section('title', 'My Profile | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/profile.css') }}">
@endpush

@php
    $profileEmail = $user->email ?: 'Not provided';
    $profilePhone = $user->phone ?: 'Not provided';
    $profileUsername = $user->username ?: 'Not provided';
    $profileBio = $user->bio ?: 'No biography has been added yet.';
    $profileDateOfBirth = $user->date_of_birth
        ? $user->date_of_birth->format('F j, Y')
        : 'Not provided';

    $profileGender = $user->gender
        ? ucfirst(str_replace('_', ' ', $user->gender))
        : 'Not provided';

    $profileCountry = $user->country ?: 'Not provided';
    $profileCity = $user->city ?: 'Not provided';
    $profileState = $user->state ?: 'Not provided';
    $profilePostalCode = $user->postal_code ?: 'Not provided';

    $profilePhoto = $user->profile_photo
        ? \Illuminate\Support\Facades\Storage::url($user->profile_photo)
        : null;
@endphp

@section('content')

<main class="sb-profile-page">

    <div class="container">

        {{-- =========================================================
             PROFILE HERO
        ========================================================== --}}
        <section class="sb-profile-hero">

            <div class="sb-profile-hero-main">

                <div class="sb-profile-avatar-wrap">

                    @if($profilePhoto)
                        <div class="sb-profile-avatar sb-profile-avatar-photo">
                            <img
                                src="{{ $profilePhoto }}"
                                alt="{{ $profileName }}"
                            >
                        </div>
                    @else
                        <div class="sb-profile-avatar">
                            {{ $avatarInitials }}
                        </div>
                    @endif

                    <div class="sb-profile-online {{ $profileStatus['class'] }}">
                        <span></span>
                        {{ $profileStatus['label'] }}
                    </div>

                </div>

                <div class="sb-profile-identity">

                    <div class="sb-profile-eyebrow">
                        <i class="bi bi-person-badge"></i>
                        MY ACCOUNT
                    </div>

                    <h1>
                        {{ $profileName }}
                    </h1>

                    @if($user->username)
                        <div class="sb-profile-username">
                            <i class="bi bi-at"></i>
                            {{ $user->username }}
                        </div>
                    @endif

                    <p>
                        Manage your personal information, account details
                        and SecondBook preferences from one place.
                    </p>

                    <div class="sb-profile-meta">

                        <span>
                            <i class="bi bi-envelope"></i>
                            {{ $profileEmail }}
                        </span>

                        <span>
                            <i class="bi bi-calendar3"></i>
                            Member since {{ $memberSince }}
                        </span>

                        <span class="sb-profile-role">
                            <i class="bi bi-shield-check"></i>
                            {{ $profileRole }}
                        </span>

                    </div>

                </div>

            </div>

            <div class="sb-profile-hero-actions">

                <a
                    href="{{ route('profile.edit') }}"
                    class="sb-profile-primary-btn"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit Profile
                </a>

                <a
                    href="{{ route('frontend.account.settings') }}"
                    class="sb-profile-secondary-btn"
                >
                    <i class="bi bi-gear"></i>
                    Account Settings
                </a>

            </div>

        </section>


        {{-- =========================================================
             STATISTICS
        ========================================================== --}}
        <section class="sb-profile-stats">

            <div class="sb-profile-stat">
                <div class="sb-profile-stat-icon">
                    <i class="bi bi-bag-check"></i>
                </div>

                <div class="sb-profile-stat-content">
                    <span>ORDERS</span>
                    <strong>{{ $orderCount }}</strong>
                    <small>Your purchases</small>
                </div>
            </div>


            <div class="sb-profile-stat">
                <div class="sb-profile-stat-icon">
                    <i class="bi bi-heart"></i>
                </div>

                <div class="sb-profile-stat-content">
                    <span>WISHLIST</span>
                    <strong>{{ $wishlistCount }}</strong>
                    <small>Saved books</small>
                </div>
            </div>


            <div class="sb-profile-stat">
                <div class="sb-profile-stat-icon">
                    <i class="bi bi-book"></i>
                </div>

                <div class="sb-profile-stat-content">
                    <span>BOOKS SOLD</span>
                    <strong>{{ $booksSoldCount }}</strong>
                    <small>
                        {{ $user->isSeller() ? 'Completed sales' : 'Seller accounts only' }}
                    </small>
                </div>
            </div>


            <div class="sb-profile-stat">
                <div class="sb-profile-stat-icon">
                    <i class="bi bi-star"></i>
                </div>

                <div class="sb-profile-stat-content">
                    <span>REVIEWS</span>
                    <strong>{{ $reviewsCount }}</strong>
                    <small>Your reviews</small>
                </div>
            </div>

        </section>


        {{-- =========================================================
             MAIN PROFILE GRID
        ========================================================== --}}
        <section class="sb-profile-grid">


            {{-- =====================================================
                 PERSONAL INFORMATION
            ====================================================== --}}
            <div class="sb-profile-card">

                <div class="sb-profile-card-header">

                    <div>
                        <span class="sb-card-overline">
                            PROFILE DETAILS
                        </span>

                        <h2>
                            Personal Information
                        </h2>

                        <p>
                            Your personal information and contact details.
                        </p>
                    </div>

                    <div class="sb-profile-card-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                </div>


                <div class="sb-profile-info-grid">

                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Full Name
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-person"></i>
                            <span title="{{ $profileName }}">
                                {{ $profileName }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Username
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-at"></i>
                            <span title="{{ $profileUsername }}">
                                {{ $profileUsername }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Email Address
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-envelope"></i>
                            <span title="{{ $profileEmail }}">
                                {{ $profileEmail }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Phone Number
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-telephone"></i>
                            <span title="{{ $profilePhone }}">
                                {{ $profilePhone }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Date of Birth
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-calendar-event"></i>
                            <span>
                                {{ $profileDateOfBirth }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Gender
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-person-standing"></i>
                            <span>
                                {{ $profileGender }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Country
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-globe2"></i>
                            <span title="{{ $profileCountry }}">
                                {{ $profileCountry }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            City
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-buildings"></i>
                            <span title="{{ $profileCity }}">
                                {{ $profileCity }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            State / Region
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-map"></i>
                            <span title="{{ $profileState }}">
                                {{ $profileState }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info">
                        <span class="sb-profile-info-label">
                            Postal Code
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-mailbox"></i>
                            <span title="{{ $profilePostalCode }}">
                                {{ $profilePostalCode }}
                            </span>
                        </div>
                    </div>


                    <div class="sb-profile-info sb-profile-info-wide">
                        <span class="sb-profile-info-label">
                            Address
                        </span>

                        <div class="sb-profile-info-value">
                            <i class="bi bi-geo-alt"></i>
                            <span title="{{ $profileAddress }}">
                                {{ $profileAddress }}
                            </span>
                        </div>
                    </div>

                </div>


                @if($user->bio)
                    <div class="sb-profile-bio">

                        <span class="sb-profile-info-label">
                            About You
                        </span>

                        <p>
                            {{ $user->bio }}
                        </p>

                    </div>
                @endif


                <div class="sb-profile-card-footer">

                    <span>
                        Keep your information up to date.
                    </span>

                    <a
                        href="{{ route('profile.edit') }}"
                        class="sb-profile-text-btn"
                    >
                        Edit information
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- =====================================================
                 ACCOUNT OVERVIEW
            ====================================================== --}}
            <div class="sb-profile-card sb-status-card">

                <div class="sb-profile-card-header">

                    <div>
                        <span class="sb-card-overline">
                            ACCOUNT
                        </span>

                        <h2>
                            Account Overview
                        </h2>

                        <p>
                            Your current account status and activity.
                        </p>
                    </div>

                    <div class="sb-profile-card-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                </div>


                <div class="sb-account-status {{ $profileStatus['class'] }}">

                    <div class="sb-status-visual">
                        <div class="sb-status-check">
                            <i class="bi {{ $profileStatus['icon'] }}"></i>
                        </div>
                    </div>

                    <div class="sb-status-content">

                        <span class="sb-status-label">
                            ACCOUNT STATUS
                        </span>

                        <h3>
                            {{ $profileStatus['label'] }}
                        </h3>

                        <p>
                            {{ $profileStatus['description'] }}
                        </p>

                    </div>

                </div>


                <div class="sb-account-details">

                    <div>
                        <span>Account Type</span>
                        <strong>{{ $profileRole }}</strong>
                    </div>

                    <div>
                        <span>Member Since</span>
                        <strong>{{ $joinedDate }}</strong>
                    </div>

                    <div>
                        <span>Last Login</span>
                        <strong>{{ $lastLogin }}</strong>
                    </div>

                    <div>
                        <span>Last Updated</span>
                        <strong>{{ $lastAccountUpdate }}</strong>
                    </div>

                </div>


                @if($user->isSeller() && $store)

                    <div class="sb-profile-store">

                        <div class="sb-profile-store-icon">
                            <i class="bi bi-shop"></i>
                        </div>

                        <div class="sb-profile-store-content">
                            <span>SELLER STORE</span>

                            <strong>
                                {{ $store->name ?? 'Your Store' }}
                            </strong>

                            <p>
                                Your seller account is connected to a SecondBook store.
                            </p>
                        </div>

                    </div>

                @endif


                <a
                    href="{{ route('frontend.account.settings') }}"
                    class="sb-profile-outline-btn"
                >
                    <i class="bi bi-sliders"></i>
                    Manage Account
                </a>

            </div>

        </section>


        {{-- =========================================================
             QUICK ACTIONS
        ========================================================== --}}
        <section class="sb-profile-section">

            <div class="sb-profile-section-heading">

                <div>
                    <span class="sb-section-overline">
                        ACCOUNT SHORTCUTS
                    </span>

                    <h2>
                        Quick Actions
                    </h2>

                    <p>
                        Shortcuts to the most important areas of your account.
                    </p>
                </div>

            </div>


            <div class="sb-profile-actions-grid">

                <a
                    href="{{ route('profile.edit') }}"
                    class="sb-profile-action"
                >
                    <div class="sb-profile-action-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>

                    <h3>
                        Edit Profile
                    </h3>

                    <p>
                        Update your name, phone number, address and
                        other personal information.
                    </p>

                    <span class="sb-action-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>


                <a
                    href="{{ route('frontend.account.settings') }}"
                    class="sb-profile-action"
                >
                    <div class="sb-profile-action-icon">
                        <i class="bi bi-sliders"></i>
                    </div>

                    <h3>
                        Account Settings
                    </h3>

                    <p>
                        Manage notifications, privacy and your
                        account preferences.
                    </p>

                    <span class="sb-action-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>


                <a
                    href="{{ route('frontend.account.settings') }}#security"
                    class="sb-profile-action"
                >
                    <div class="sb-profile-action-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <h3>
                        Security
                    </h3>

                    <p>
                        Change your password and keep your account protected.
                    </p>

                    <span class="sb-action-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>


                <a
                    href="{{ route('frontend.help-center') }}"
                    class="sb-profile-action"
                >
                    <div class="sb-profile-action-icon">
                        <i class="bi bi-headset"></i>
                    </div>

                    <h3>
                        Help Center
                    </h3>

                    <p>
                        Find answers, browse helpful guides and
                        contact SecondBook support.
                    </p>

                    <span class="sb-action-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </span>
                </a>

            </div>

        </section>


        {{-- =========================================================
             PROFILE FOOTER NOTE
        ========================================================== --}}
        <section class="sb-profile-bottom">

            <div class="sb-profile-bottom-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <div>
                <strong>
                    Your account, your information.
                </strong>

                <p>
                    Keep your profile details accurate so your
                    SecondBook experience stays smooth and secure.
                </p>
            </div>

            <a href="{{ route('profile.edit') }}">
                Update profile
                <i class="bi bi-arrow-right"></i>
            </a>

        </section>

    </div>

</main>

@endsection