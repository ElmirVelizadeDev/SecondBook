@extends('layout.admin.master')

@section('title', 'Edit Seller')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/sellers.css') }}">
@endpush

@section('content')

<div class="dashboard-section sellers-page seller-edit-page">

    {{-- Header --}}
    <div class="dashboard-panel mb-4">
        <div class="seller-edit-header">

            <div class="seller-edit-heading">

                <div class="seller-edit-breadcrumb">
                    <a href="{{ route('admin.sellers.index') }}">
                        <i class="bi bi-shop"></i>
                        Sellers
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>Edit Seller</span>
                </div>

                <h4>Edit Seller</h4>

                <p>
                    Update seller account information and account settings.
                </p>

            </div>

            <a
                href="{{ route('admin.sellers.index', $seller) }}"
                class="btn btn-light seller-edit-back"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back to Seller</span>
            </a>

        </div>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger seller-edit-alert mb-4">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>
                    <strong>Please check the following:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    <form
        action="{{ route('admin.sellers.update', $seller) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-xl-8">

                <div class="dashboard-panel seller-edit-panel">

                    <div class="seller-edit-panel-header">
                        <div>
                            <span class="seller-edit-eyebrow">
                                Account
                            </span>

                            <h5>Seller Information</h5>

                            <p>
                                Update the seller's basic account details.
                            </p>
                        </div>

                        <div class="seller-edit-header-icon">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                    </div>


                    <div class="seller-edit-form">

                        {{-- Name --}}
                        <div class="seller-form-group">
                            <label for="name">
                                Full Name
                                <span>*</span>
                            </label>

                            <div class="seller-input-wrap">
                                <i class="bi bi-person"></i>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $seller->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter seller name"
                                    required
                                >
                            </div>

                            @error('name')
                                <small class="seller-field-error">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>


                        <div class="row g-3">

                            {{-- Email --}}
                            <div class="col-md-6">

                                <div class="seller-form-group">
                                    <label for="email">
                                        Email Address
                                        <span>*</span>
                                    </label>

                                    <div class="seller-input-wrap">
                                        <i class="bi bi-envelope"></i>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $seller->email) }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="seller@example.com"
                                            required
                                        >
                                    </div>

                                    @error('email')
                                        <small class="seller-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                            </div>


                            {{-- Phone --}}
                            <div class="col-md-6">

                                <div class="seller-form-group">
                                    <label for="phone">
                                        Phone Number
                                    </label>

                                    <div class="seller-input-wrap">
                                        <i class="bi bi-telephone"></i>

                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $seller->phone) }}"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="+994 XX XXX XX XX"
                                        >
                                    </div>

                                    @error('phone')
                                        <small class="seller-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                            </div>

                        </div>


                        {{-- Password --}}
                        <div class="seller-form-group">

                            <label for="password">
                                New Password
                            </label>

                            <div class="seller-input-wrap seller-password-wrap">
                                <i class="bi bi-lock"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Leave blank to keep current password"
                                >

                                <button
                                    type="button"
                                    class="seller-password-toggle"
                                    data-target="password"
                                    aria-label="Show password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <small class="seller-field-help">
                                Leave this field empty if you do not want to change the password.
                            </small>

                            @error('password')
                                <small class="seller-field-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Confirm Password --}}
                        <div class="seller-form-group">

                            <label for="password_confirmation">
                                Confirm New Password
                            </label>

                            <div class="seller-input-wrap seller-password-wrap">
                                <i class="bi bi-shield-lock"></i>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Repeat the new password"
                                >

                                <button
                                    type="button"
                                    class="seller-password-toggle"
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


            {{-- Sidebar --}}
            <div class="col-xl-4">

                <div class="dashboard-panel seller-edit-panel">

                    <div class="seller-edit-panel-header seller-edit-panel-header-small">

                        <div>
                            <span class="seller-edit-eyebrow">
                                Account
                            </span>

                            <h5>Seller Settings</h5>

                            <p>
                                Manage account status and profile photo.
                            </p>
                        </div>

                        <div class="seller-edit-header-icon">
                            <i class="bi bi-sliders"></i>
                        </div>

                    </div>


                    <div class="seller-edit-sidebar">

                        {{-- Current Profile --}}
                        <div class="seller-edit-profile">

                            <div class="seller-edit-avatar" id="sellerAvatarPreview">

                                @if($seller->profile_photo)
                                    <img
                                        src="{{ asset('storage/' . $seller->profile_photo) }}"
                                        alt="{{ $seller->name }}"
                                        id="sellerPhotoPreview"
                                    >
                                @else
                                    <span id="sellerInitialPreview">
                                        {{ strtoupper(substr($seller->name ?: 'S', 0, 1)) }}
                                    </span>
                                @endif

                            </div>

                            <div>
                                <strong>
                                    {{ $seller->name }}
                                </strong>

                                <span>
                                    {{ $seller->email }}
                                </span>
                            </div>

                        </div>


                        {{-- Profile Photo --}}
                        <div class="seller-form-group">

                            <label for="profile_photo">
                                Profile Photo
                            </label>

                            <input
                                type="file"
                                id="profile_photo"
                                name="profile_photo"
                                class="form-control seller-file-input @error('profile_photo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <small class="seller-field-help">
                                JPG, JPEG, PNG or WEBP. Maximum 2MB.
                            </small>

                            @error('profile_photo')
                                <small class="seller-field-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="seller-form-group">

                            <label for="status">
                                Account Status
                                <span>*</span>
                            </label>

                            <div class="seller-input-wrap">
                                <i class="bi bi-circle-half"></i>

                                <select
                                    id="status"
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option
                                        value="active"
                                        @selected(old('status', $seller->status) === 'active')
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="inactive"
                                        @selected(old('status', $seller->status) === 'inactive')
                                    >
                                        Inactive
                                    </option>

                                    <option
                                        value="banned"
                                        @selected(old('status', $seller->status) === 'banned')
                                    >
                                        Banned
                                    </option>
                                </select>
                            </div>

                            @error('status')
                                <small class="seller-field-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- Account Info --}}
                        <div class="seller-edit-account-info">

                            <div>
                                <span>Seller ID</span>
                                <strong>#{{ $seller->id }}</strong>
                            </div>

                            <div>
                                <span>Joined</span>
                                <strong>
                                    {{ $seller->created_at?->format('d M Y') ?? '—' }}
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Bottom Actions --}}
            <div class="col-12">

                <div class="seller-edit-actions">

                    <a
                        href="{{ route('admin.sellers.show', $seller) }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check2"></i>
                        Update Seller
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Password visibility
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.seller-password-toggle').forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

                this.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

                this.setAttribute('aria-label', 'Show password');
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Profile photo preview
    |--------------------------------------------------------------------------
    */

    const photoInput = document.getElementById('profile_photo');
    const avatarPreview = document.getElementById('sellerAvatarPreview');

    if (photoInput && avatarPreview) {

        photoInput.addEventListener('change', function () {

            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                avatarPreview.innerHTML = '';

                const image = document.createElement('img');

                image.src = event.target.result;
                image.alt = 'Profile preview';
                image.className = 'seller-edit-avatar-image';

                avatarPreview.appendChild(image);
            };

            reader.readAsDataURL(file);

        });

    }

});
</script>
@endpush