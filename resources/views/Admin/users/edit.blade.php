@extends('layout.admin.master')

@section('title', 'Edit User')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/users.css') }}">
@endpush

@section('content')

<div class="dashboard-section users-page">

    {{-- Page Header --}}
    <div class="users-edit-hero mb-4">

        <div class="users-edit-hero-content">

            <div class="users-edit-breadcrumb">
                <a href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    Users
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>Edit User</span>
            </div>

            <span class="hero-badge">
                <i class="bi bi-person-gear"></i>
                Account Management
            </span>

            <h1>Edit user account.</h1>

            <p>
                Update profile information, account access and security settings.
            </p>

        </div>

        <div class="users-edit-hero-mark">
            <i class="bi bi-person-gear"></i>
        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="users-edit-alert users-edit-alert-error mb-4">

            <div class="users-edit-alert-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>

                <strong>
                    Please check the following fields.
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- Edit User Form --}}
    <form
        action="{{ route('admin.users.update', $user) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="row g-4">


            {{-- Left Column --}}
            <div class="col-12 col-xl-8">


                {{-- Personal Information --}}
                <div class="dashboard-panel users-edit-panel mb-4">

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
                                Update the user's basic profile details.
                            </p>

                        </div>

                    </div>


                    <div class="users-edit-form-grid">

                        {{-- First Name --}}
                        <div class="users-edit-field">

                            <label for="first_name">
                                First name
                                <span>*</span>
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-person"></i>

                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    placeholder="Enter first name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    required
                                >

                            </div>

                            @error('first_name')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Last Name --}}
                        <div class="users-edit-field">

                            <label for="last_name">
                                Last name
                                <span>*</span>
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-person"></i>

                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    placeholder="Enter last name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    required
                                >

                            </div>

                            @error('last_name')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Username --}}
                        <div class="users-edit-field">

                            <label for="username">
                                Username
                                <span>*</span>
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-at"></i>

                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username', $user->username) }}"
                                    placeholder="Enter username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    required
                                >

                            </div>

                            @error('username')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="users-edit-field">

                            <label for="email">
                                Email address
                                <span>*</span>
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-envelope"></i>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="Enter email address"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                >

                            </div>

                            @error('email')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Phone --}}
                        <div class="users-edit-field">

                            <label for="phone">
                                Phone number
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-telephone"></i>

                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="Enter phone number"
                                    class="form-control @error('phone') is-invalid @enderror"
                                >

                            </div>

                            @error('phone')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Profile Photo --}}
                        <div class="users-edit-field">

                            <label for="profile_photo">
                                Profile photo
                            </label>

                            <div class="users-edit-file-wrap">

                                <input
                                    type="file"
                                    id="profile_photo"
                                    name="profile_photo"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    class="form-control @error('profile_photo') is-invalid @enderror"
                                >

                            </div>

                            <small class="users-edit-help">
                                JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                            </small>

                            @error('profile_photo')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Account Access --}}
                <div class="dashboard-panel users-edit-panel mb-4">

                    <div class="users-edit-panel-header">

                        <div class="users-edit-section-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>

                        <div>

                            <span class="eyebrow">
                                Access control
                            </span>

                            <h5>
                                Account access
                            </h5>

                            <p>
                                Manage this user's role and account status.
                            </p>

                        </div>

                    </div>


                    <div class="users-edit-form-grid">


                        {{-- Role --}}
                        <div class="users-edit-field">

                            <label for="role">
                                Role
                                <span>*</span>
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-person-badge"></i>

                                <select
                                    id="role"
                                    name="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    {{ auth()->id() === $user->id ? 'disabled' : '' }}
                                >

                                    @foreach($roles as $role)

                                        <option
                                            value="{{ $role->name }}"
                                            @selected(
                                                old('role', $user->roles->first()?->name ?? $user->role)
                                                === $role->name
                                            )
                                        >
                                            {{ $role->display_name ?? ucfirst($role->name) }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            @if(auth()->id() === $user->id)
                                <small class="users-edit-help">
                                    You cannot change your own role.
                                </small>
                            @endif

                            @error('role')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="users-edit-field">

                            <label for="status">
                                Status
                                <span>*</span>
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-circle-half"></i>

                                <select
                                    id="status"
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    {{ auth()->id() === $user->id ? 'disabled' : '' }}
                                >

                                    <option
                                        value="active"
                                        @selected(old('status', $user->status) === 'active')
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="inactive"
                                        @selected(old('status', $user->status) === 'inactive')
                                    >
                                        Inactive
                                    </option>

                                    <option
                                        value="banned"
                                        @selected(old('status', $user->status) === 'banned')
                                    >
                                        Banned
                                    </option>

                                </select>

                            </div>

                            @if(auth()->id() === $user->id)
                                <small class="users-edit-help">
                                    You cannot change your own account status.
                                </small>
                            @endif

                            @error('status')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Password --}}
                <div class="dashboard-panel users-edit-panel">

                    <div class="users-edit-panel-header">

                        <div class="users-edit-section-icon">
                            <i class="bi bi-key"></i>
                        </div>

                        <div>

                            <span class="eyebrow">
                                Security
                            </span>

                            <h5>
                                Change password
                            </h5>

                            <p>
                                Leave these fields empty to keep the current password.
                            </p>

                        </div>

                    </div>


                    <div class="users-edit-form-grid">


                        {{-- Password --}}
                        <div class="users-edit-field">

                            <label for="password">
                                New password
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-lock"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Enter new password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="users-password-toggle"
                                    data-target="password"
                                    aria-label="Show password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                            @error('password')
                                <small class="users-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Confirm Password --}}
                        <div class="users-edit-field">

                            <label for="password_confirmation">
                                Confirm password
                            </label>

                            <div class="users-edit-input-wrap">

                                <i class="bi bi-lock-fill"></i>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm new password"
                                    class="form-control"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="users-password-toggle"
                                    data-target="password_confirmation"
                                    aria-label="Show password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Right Column --}}
            <div class="col-12 col-xl-4">


                {{-- Current User --}}
                <div class="dashboard-panel users-edit-profile-card mb-4">

                    <div class="users-edit-profile-top">

                        @if($user->profile_photo)

                            <img
                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                alt="{{ $user->full_name ?: $user->name }}"
                                class="users-edit-profile-avatar"
                            >

                        @else

                            <div class="users-edit-profile-avatar users-edit-profile-initial">
                                {{
                                    strtoupper(
                                        substr(
                                            $user->full_name
                                                ?: ($user->name ?: $user->username),
                                            0,
                                            1
                                        )
                                    )
                                }}
                            </div>

                        @endif

                        <div class="users-edit-profile-info">

                            <h5>
                                {{
                                    $user->full_name
                                        ?: ($user->name ?: $user->username)
                                }}
                            </h5>

                            <span>
                                {{ '@' . $user->username }}
                            </span>

                        </div>

                    </div>


                    <div class="users-edit-profile-meta">

                        <div>
                            <span>Role</span>

                            <strong>
                                {{
                                    ucfirst(
                                        $user->roles->first()?->display_name
                                            ?? $user->role
                                    )
                                }}
                            </strong>
                        </div>

                        <div>
                            <span>Status</span>

                            <strong class="users-edit-status status-{{ $user->status }}">
                                {{ ucfirst($user->status) }}
                            </strong>
                        </div>

                        <div>
                            <span>Joined</span>

                            <strong>
                                {{ $user->created_at?->format('d M Y') }}
                            </strong>
                        </div>

                    </div>

                </div>


                {{-- Email Verification --}}
                <div class="dashboard-panel users-edit-info-card mb-4">

                    <div class="users-edit-info-icon">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <div>

                        <span>
                            Email verification
                        </span>

                        @if($user->email_verified_at)

                            <strong class="users-edit-verified">
                                <i class="bi bi-check-circle-fill"></i>
                                Verified
                            </strong>

                            <small>
                                {{ $user->email_verified_at->format('d M Y, H:i') }}
                            </small>

                        @else

                            <strong class="users-edit-unverified">
                                <i class="bi bi-clock"></i>
                                Unverified
                            </strong>

                            <small>
                                This email address has not been verified.
                            </small>

                        @endif

                    </div>

                </div>


                {{-- Form Actions --}}
                <div class="dashboard-panel users-edit-actions">

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="users-edit-cancel-btn"
                    >
                        <i class="bi bi-arrow-left"></i>
                        <span>Cancel</span>
                    </a>

                    <button
                        type="submit"
                        class="users-edit-save-btn"
                    >
                        <i class="bi bi-check2"></i>
                        <span>Save changes</span>
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const passwordToggles =
        document.querySelectorAll('.users-password-toggle');

    passwordToggles.forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId =
                button.dataset.target;

            const input =
                document.getElementById(targetId);

            const icon =
                button.querySelector('i');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

                button.setAttribute(
                    'aria-label',
                    'Hide password'
                );

            } else {

                input.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

                button.setAttribute(
                    'aria-label',
                    'Show password'
                );

            }

        });

    });

});
</script>
@endpush

@endsection