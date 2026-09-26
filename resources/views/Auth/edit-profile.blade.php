@extends('Layout.Frontend.master')

@section('title', 'Edit Profile | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/edit-profile.css') }}">
@endpush

@php
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Full Name
    |--------------------------------------------------------------------------
    */

    $fullName = trim(
        ($user->first_name ?? '') . ' ' . ($user->last_name ?? '')
    );

    $fullName = $fullName ?: ($user->name ?? 'User');

    /*
    |--------------------------------------------------------------------------
    | Initials
    |--------------------------------------------------------------------------
    */

    $initials = collect(
        preg_split('/\s+/', trim($fullName)) ?: []
    )
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $initials = $initials ?: 'U';

    /*
    |--------------------------------------------------------------------------
    | Phone
    |--------------------------------------------------------------------------
    */

    $phone = $user->phone ?? '';

    $phoneCountryCode = '+994';
    $phoneNumber = $phone;

    $countryCodes = [
        '+994' => '🇦🇿 +994',
        '+90'  => '🇹🇷 +90',
        '+7'   => '🇷🇺 +7',
        '+380' => '🇺🇦 +380',
        '+49'  => '🇩🇪 +49',
        '+33'  => '🇫🇷 +33',
        '+44'  => '🇬🇧 +44',
        '+39'  => '🇮🇹 +39',
        '+34'  => '🇪🇸 +34',
        '+1'   => '🇺🇸 +1',
    ];

    foreach ($countryCodes as $code => $label) {
        if ($phone !== '' && str_starts_with($phone, $code)) {
            $phoneCountryCode = $code;
            $phoneNumber = substr($phone, strlen($code));
            break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Date of Birth
    |--------------------------------------------------------------------------
    */

    $dateOfBirth = '';

    if ($user->date_of_birth) {
        $dateOfBirth = $user->date_of_birth instanceof \Carbon\Carbon
            ? $user->date_of_birth->format('Y-m-d')
            : \Illuminate\Support\Carbon::parse($user->date_of_birth)->format('Y-m-d');
    }
@endphp

@section('content')

<main class="sb-edit-profile-page">

    <div class="container">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}

        <header class="sb-edit-profile-header">

            <div class="sb-edit-profile-header-content">

                <a
                    href="{{ route('my.profile') }}"
                    class="sb-edit-profile-back"
                >
                    <span class="sb-edit-profile-back-icon">
                        <i class="bi bi-arrow-left"></i>
                    </span>

                    <span>Back to profile</span>
                </a>

                <div class="sb-edit-profile-heading">

                    <span class="sb-edit-profile-eyebrow">
                        <span class="sb-edit-profile-eyebrow-dot"></span>
                        ACCOUNT SETTINGS
                    </span>

                    <h1>
                        Edit your
                        <em>profile.</em>
                    </h1>

                    <p>
                        Keep your personal information accurate and up to date.
                    </p>

                </div>

            </div>

            <div class="sb-edit-profile-header-icon">
                <i class="bi bi-person-gear"></i>
            </div>

        </header>


        {{-- =========================================================
             SUCCESS MESSAGE
        ========================================================== --}}

        @if(session('success'))

            <div class="sb-edit-profile-alert sb-edit-profile-alert-success">

                <div class="sb-edit-profile-alert-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="sb-edit-profile-alert-content">
                    <strong>Profile updated</strong>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>

                <button
                    type="button"
                    class="sb-edit-profile-alert-close"
                    onclick="this.closest('.sb-edit-profile-alert').remove()"
                    aria-label="Close"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif


        {{-- =========================================================
             VALIDATION ERRORS
        ========================================================== --}}

        @if($errors->any())

            <div class="sb-edit-profile-alert sb-edit-profile-alert-error">

                <div class="sb-edit-profile-alert-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div class="sb-edit-profile-alert-content">

                    <strong>
                        Please check your information
                    </strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

                <button
                    type="button"
                    class="sb-edit-profile-alert-close"
                    onclick="this.closest('.sb-edit-profile-alert').remove()"
                    aria-label="Close"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        @endif


        {{-- =========================================================
             MAIN LAYOUT
        ========================================================== --}}

        <div class="sb-edit-profile-layout">

            {{-- =====================================================
                 MAIN FORM
            ====================================================== --}}

            <section class="sb-edit-profile-main">

                <form
                    action="{{ route('profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="profileEditForm"
                >

                    @csrf
                    @method('PUT')


                    {{-- =================================================
                         PERSONAL INFORMATION
                    ================================================== --}}

                    <div class="sb-edit-profile-card">

                        <div class="sb-edit-profile-card-header">

                            <div>
                                <span class="sb-edit-profile-card-label">
                                    PERSONAL
                                </span>

                                <h2>Personal information</h2>

                                <p>
                                    Update the information associated with your account.
                                </p>
                            </div>

                            <div class="sb-edit-profile-card-icon">
                                <i class="bi bi-person"></i>
                            </div>

                        </div>


                        <div class="sb-edit-profile-card-body">

                            {{-- PROFILE PHOTO --}}

                            <div class="sb-edit-profile-photo-section">

                                <div class="sb-edit-profile-photo-heading">

                                    <div>
                                        <label>Profile photo</label>

                                        <span>
                                            Your profile picture helps others recognize you.
                                        </span>
                                    </div>

                                    <span class="sb-edit-profile-photo-limit">
                                        2 MB MAX
                                    </span>

                                </div>


                                <div class="sb-edit-profile-photo-row">

                                    <div class="sb-edit-profile-photo">

                                        @if($user->profile_photo)

                                            <img
                                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                                alt="{{ $fullName }}"
                                                id="profilePhotoPreview"
                                            >

                                        @else

                                            <span id="profilePhotoInitials">
                                                {{ $initials }}
                                            </span>

                                        @endif

                                        <span class="sb-edit-profile-camera">
                                            <i class="bi bi-camera-fill"></i>
                                        </span>

                                    </div>


                                    <div class="sb-edit-profile-photo-info">

                                        <strong>
                                            {{ $fullName }}
                                        </strong>

                                        <span>
                                            JPG, JPEG, PNG or WEBP
                                        </span>


                                        <div class="sb-edit-profile-photo-actions">

                                            <label
                                                for="profile_photo"
                                                class="sb-edit-profile-photo-change"
                                            >
                                                <i class="bi bi-upload"></i>
                                                Change photo
                                            </label>


                                            @if($user->profile_photo)

                                                <button
                                                    type="button"
                                                    class="sb-edit-profile-photo-delete"
                                                    id="removeProfilePhotoBtn"
                                                >
                                                    Remove
                                                </button>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                                <input
                                    type="file"
                                    name="profile_photo"
                                    id="profile_photo"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    hidden
                                >

                                @error('profile_photo')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            <div class="sb-edit-profile-divider"></div>


                            {{-- FULL NAME --}}

                            <div class="sb-edit-profile-field">

                                <label for="full_name">
                                    Full name
                                    <span>*</span>
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-person"></i>

                                    <input
                                        type="text"
                                        id="full_name"
                                        value="{{ old('full_name', $fullName) }}"
                                        placeholder="Your full name"
                                        autocomplete="name"
                                    >

                                </div>

                                <input
                                    type="hidden"
                                    name="first_name"
                                    id="first_name"
                                    value="{{ old('first_name', $user->first_name ?? '') }}"
                                >

                                <input
                                    type="hidden"
                                    name="last_name"
                                    id="last_name"
                                    value="{{ old('last_name', $user->last_name ?? '') }}"
                                >

                                @error('first_name')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                                @error('last_name')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- EMAIL --}}

                            <div class="sb-edit-profile-field">

                                <label for="email">
                                    Email address
                                    <span>*</span>
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-envelope"></i>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="you@example.com"
                                        autocomplete="email"
                                    >

                                </div>

                                @error('email')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- PHONE --}}

                            <div class="sb-edit-profile-field">

                                <label for="phone">
                                    Phone number
                                </label>

                                <div class="sb-edit-profile-phone">

                                    <div class="sb-edit-profile-country">

                                        <select
                                            name="phone_country_code"
                                            id="phone_country_code"
                                            aria-label="Country code"
                                        >

                                            @foreach($countryCodes as $code => $label)

                                                <option
                                                    value="{{ $code }}"
                                                    @selected($phoneCountryCode === $code)
                                                >
                                                    {{ $label }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="sb-edit-profile-input">

                                        <i class="bi bi-telephone"></i>

                                        <input
                                            type="tel"
                                            name="phone"
                                            id="phone"
                                            value="{{ old('phone', $phoneNumber) }}"
                                            placeholder="501234567"
                                            inputmode="numeric"
                                            autocomplete="tel-national"
                                            maxlength="15"
                                        >

                                    </div>

                                </div>

                                @error('phone')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- DATE OF BIRTH --}}

                            <div class="sb-edit-profile-field">

                                <label for="date_of_birth">
                                    Date of birth
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-calendar3"></i>

                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        id="date_of_birth"
                                        value="{{ old('date_of_birth', $dateOfBirth) }}"
                                        autocomplete="bday"
                                    >

                                </div>

                                @error('date_of_birth')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- GENDER --}}

                            <div class="sb-edit-profile-field">

                                <label for="gender">
                                    Gender
                                </label>

                                <div class="sb-edit-profile-input sb-edit-profile-select">

                                    <i class="bi bi-person-vcard"></i>

                                    <select
                                        name="gender"
                                        id="gender"
                                    >

                                        <option value="">
                                            Select gender
                                        </option>

                                        <option
                                            value="male"
                                            @selected(old('gender', $user->gender) === 'male')
                                        >
                                            Male
                                        </option>

                                        <option
                                            value="female"
                                            @selected(old('gender', $user->gender) === 'female')
                                        >
                                            Female
                                        </option>

                                        <option
                                            value="other"
                                            @selected(old('gender', $user->gender) === 'other')
                                        >
                                            Other
                                        </option>

                                    </select>

                                </div>

                                @error('gender')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         LOCATION
                    ================================================== --}}

                    <div class="sb-edit-profile-card">

                        <div class="sb-edit-profile-card-header">

                            <div>
                                <span class="sb-edit-profile-card-label">
                                    LOCATION
                                </span>

                                <h2>Where you live</h2>

                                <p>
                                    Keep your location and delivery information current.
                                </p>
                            </div>

                            <div class="sb-edit-profile-card-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>

                        </div>


                        <div class="sb-edit-profile-card-body">

                            {{-- COUNTRY --}}

                            <div class="sb-edit-profile-field">

                                <label for="country">
                                    Country
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-globe2"></i>

                                    <input
                                        type="text"
                                        name="country"
                                        id="country"
                                        value="{{ old('country', $user->country) }}"
                                        placeholder="Country"
                                        autocomplete="country-name"
                                    >

                                </div>

                                @error('country')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- CITY --}}

                            <div class="sb-edit-profile-field">

                                <label for="city">
                                    City
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-buildings"></i>

                                    <input
                                        type="text"
                                        name="city"
                                        id="city"
                                        value="{{ old('city', $user->city) }}"
                                        placeholder="City"
                                        autocomplete="address-level2"
                                    >

                                </div>

                                @error('city')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- STATE --}}

                            <div class="sb-edit-profile-field">

                                <label for="state">
                                    State / Region
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-map"></i>

                                    <input
                                        type="text"
                                        name="state"
                                        id="state"
                                        value="{{ old('state', $user->state) }}"
                                        placeholder="State or region"
                                        autocomplete="address-level1"
                                    >

                                </div>

                                @error('state')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- POSTAL CODE --}}

                            <div class="sb-edit-profile-field">

                                <label for="postal_code">
                                    Postal code
                                </label>

                                <div class="sb-edit-profile-input">

                                    <i class="bi bi-mailbox"></i>

                                    <input
                                        type="text"
                                        name="postal_code"
                                        id="postal_code"
                                        value="{{ old('postal_code', $user->postal_code) }}"
                                        placeholder="Postal code"
                                        autocomplete="postal-code"
                                    >

                                </div>

                                @error('postal_code')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- ADDRESS --}}

                            <div class="sb-edit-profile-field sb-edit-profile-field-full">

                                <label for="address">
                                    Address
                                </label>

                                <div class="sb-edit-profile-input sb-edit-profile-textarea">

                                    <i class="bi bi-house"></i>

                                    <textarea
                                        name="address"
                                        id="address"
                                        placeholder="Street, building, apartment..."
                                        autocomplete="street-address"
                                    >{{ old('address', $user->address) }}</textarea>

                                </div>

                                @error('address')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         ABOUT
                    ================================================== --}}

                    <div class="sb-edit-profile-card">

                        <div class="sb-edit-profile-card-header">

                            <div>
                                <span class="sb-edit-profile-card-label">
                                    ABOUT YOU
                                </span>

                                <h2>A little about yourself</h2>

                                <p>
                                    Add a short introduction to your profile.
                                </p>
                            </div>

                            <div class="sb-edit-profile-card-icon">
                                <i class="bi bi-chat-square-text"></i>
                            </div>

                        </div>


                        <div class="sb-edit-profile-card-body sb-edit-profile-card-body-single">

                            <div class="sb-edit-profile-field">

                                <label for="bio">
                                    Biography
                                </label>

                                <div class="sb-edit-profile-input sb-edit-profile-textarea">

                                    <i class="bi bi-pencil"></i>

                                    <textarea
                                        name="bio"
                                        id="bio"
                                        maxlength="1000"
                                        placeholder="Tell us a little about yourself..."
                                    >{{ old('bio', $user->bio) }}</textarea>

                                </div>

                                <div class="sb-edit-profile-field-footer">
                                    <span>Tell people a little about you.</span>
                                    <span>
                                        <strong id="bioCount">{{ strlen(old('bio', $user->bio ?? '')) }}</strong>/1000
                                    </span>
                                </div>

                                @error('bio')
                                    <small class="sb-edit-profile-error">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}

                    <div class="sb-edit-profile-actions">

                        <a
                            href="{{ route('my.profile') }}"
                            class="sb-edit-profile-cancel"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="sb-edit-profile-save"
                            id="profileSaveButton"
                        >
                            <i class="bi bi-check2"></i>
                            <span>Save changes</span>
                        </button>

                    </div>

                </form>

            </section>


            {{-- =====================================================
                 SIDEBAR
            ====================================================== --}}

            <aside class="sb-edit-profile-sidebar">


                {{-- PROFILE PREVIEW --}}

                <div class="sb-edit-profile-preview-card">

                    <span class="sb-edit-profile-preview-label">
                        PROFILE PREVIEW
                    </span>


                    <div class="sb-edit-profile-preview-avatar">

                        @if($user->profile_photo)

                            <img
                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                alt="{{ $fullName }}"
                                id="sidebarProfilePreview"
                            >

                        @else

                            <span id="sidebarProfileInitials">
                                {{ $initials }}
                            </span>

                        @endif

                    </div>


                    <h3>
                        {{ $fullName }}
                    </h3>

                    <p>
                        {{ $user->email }}
                    </p>


                    <div class="sb-edit-profile-role">

                        <i class="bi bi-person-check"></i>

                        <span>
                            {{ ucfirst($user->role ?? 'User') }}
                        </span>

                    </div>


                    <div class="sb-edit-profile-preview-line"></div>


                    <div class="sb-edit-profile-preview-status">

                        <span class="sb-edit-profile-status-dot"></span>

                        <span>
                            Profile information
                        </span>

                    </div>

                </div>


                {{-- SECURITY CARD --}}

                <div class="sb-edit-profile-side-card">

                    <div class="sb-edit-profile-side-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>

                        <span class="sb-edit-profile-side-label">
                            ACCOUNT SECURITY
                        </span>

                        <h3>
                            Keep your account safe
                        </h3>

                        <p>
                            Use accurate information and keep your account
                            details up to date.
                        </p>

                    </div>

                </div>


                {{-- TIPS --}}

                <div class="sb-edit-profile-tips">

                    <div class="sb-edit-profile-tips-title">

                        <i class="bi bi-stars"></i>

                        <span>
                            PROFILE TIPS
                        </span>

                    </div>


                    <div class="sb-edit-profile-tip">
                        <i class="bi bi-check2"></i>
                        <span>Use your real name</span>
                    </div>

                    <div class="sb-edit-profile-tip">
                        <i class="bi bi-check2"></i>
                        <span>Keep your email updated</span>
                    </div>

                    <div class="sb-edit-profile-tip">
                        <i class="bi bi-check2"></i>
                        <span>Add a clear profile photo</span>
                    </div>

                </div>

            </aside>

        </div>

    </div>

</main>


{{-- =========================================================
     REMOVE PROFILE PHOTO
========================================================= --}}

@if($user->profile_photo)

    <form
        action="{{ route('profile.photo.destroy') }}"
        method="POST"
        id="removeProfilePhotoForm"
        hidden
    >
        @csrf
        @method('DELETE')
    </form>

@endif


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('profileEditForm');

    const fullNameInput =
        document.getElementById('full_name');

    const firstNameInput =
        document.getElementById('first_name');

    const lastNameInput =
        document.getElementById('last_name');

    const emailInput =
        document.getElementById('email');

    const phoneInput =
        document.getElementById('phone');

    const photoInput =
        document.getElementById('profile_photo');

    const removeButton =
        document.getElementById('removeProfilePhotoBtn');

    const removeForm =
        document.getElementById('removeProfilePhotoForm');

    const saveButton =
        document.getElementById('profileSaveButton');

    const bioInput =
        document.getElementById('bio');

    const bioCount =
        document.getElementById('bioCount');


    /*
    |--------------------------------------------------------------------------
    | CSRF TOKEN
    |--------------------------------------------------------------------------
    */

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || '';


    /*
    |--------------------------------------------------------------------------
    | ALERT HELPER
    |--------------------------------------------------------------------------
    */

    function removeAjaxAlerts() {

        document
            .querySelectorAll(
                '.sb-edit-profile-ajax-alert'
            )
            .forEach(function (alert) {
                alert.remove();
            });
    }


    function showAlert(type, title, message) {

        removeAjaxAlerts();

        const alert = document.createElement('div');

        alert.className =
            'sb-edit-profile-alert ' +
            'sb-edit-profile-alert-' +
            type +
            ' sb-edit-profile-ajax-alert';

        const icon =
            type === 'success'
                ? 'bi-check-lg'
                : 'bi-exclamation-triangle';

        alert.innerHTML = `
            <div class="sb-edit-profile-alert-icon">
                <i class="bi ${icon}"></i>
            </div>

            <div class="sb-edit-profile-alert-content">
                <strong>${title}</strong>
                <span>${message}</span>
            </div>

            <button
                type="button"
                class="sb-edit-profile-alert-close"
                aria-label="Close"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        `;

        const container =
            document.querySelector('.sb-edit-profile-page .container');

        const header =
            document.querySelector('.sb-edit-profile-header');

        if (container && header) {

            header.insertAdjacentElement(
                'afterend',
                alert
            );

        }

        const closeButton =
            alert.querySelector(
                '.sb-edit-profile-alert-close'
            );

        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function () {
                    alert.remove();
                }
            );

        }

        if (type === 'success') {

            setTimeout(function () {

                if (alert.isConnected) {
                    alert.remove();
                }

            }, 5000);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FULL NAME
    |--------------------------------------------------------------------------
    */

    function prepareFullName() {

        if (
            !fullNameInput ||
            !firstNameInput ||
            !lastNameInput
        ) {
            return true;
        }

        const fullName =
            fullNameInput.value.trim();

        const parts =
            fullName
                .split(/\s+/)
                .filter(Boolean);

        if (parts.length < 2) {

            fullNameInput.setCustomValidity(
                'Please enter your first and last name.'
            );

            fullNameInput.reportValidity();

            return false;
        }

        fullNameInput.setCustomValidity('');

        firstNameInput.value =
            parts.shift();

        lastNameInput.value =
            parts.join(' ');

        return true;
    }


    if (
        fullNameInput &&
        firstNameInput &&
        lastNameInput
    ) {

        fullNameInput.addEventListener(
            'input',
            function () {

                this.setCustomValidity('');

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EMAIL
    |--------------------------------------------------------------------------
    */

    if (emailInput) {

        emailInput.addEventListener(
            'blur',
            function () {

                this.value =
                    this.value
                        .trim()
                        .toLowerCase();

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PHONE
    |--------------------------------------------------------------------------
    */

    if (phoneInput) {

        phoneInput.addEventListener(
            'input',
            function () {

                this.value =
                    this.value.replace(
                        /\D/g,
                        ''
                    );

            }
        );


        phoneInput.addEventListener(
            'paste',
            function () {

                setTimeout(() => {

                    this.value =
                        this.value.replace(
                            /\D/g,
                            ''
                        );

                }, 0);

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BIO CHARACTER COUNTER
    |--------------------------------------------------------------------------
    */

    function updateBioCount() {

        if (!bioInput || !bioCount) {
            return;
        }

        bioCount.textContent =
            bioInput.value.length;
    }


    if (bioInput) {

        updateBioCount();

        bioInput.addEventListener(
            'input',
            updateBioCount
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE PHOTO PREVIEW
    |--------------------------------------------------------------------------
    */

    if (photoInput) {

        photoInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files[0];

                if (!file) {
                    return;
                }


                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                if (!allowedTypes.includes(file.type)) {

                    showAlert(
                        'error',
                        'Invalid image',
                        'Please select a JPG, JPEG, PNG or WEBP image.'
                    );

                    this.value = '';

                    return;
                }


                if (
                    file.size >
                    2 * 1024 * 1024
                ) {

                    showAlert(
                        'error',
                        'Image too large',
                        'The profile photo must be smaller than 2 MB.'
                    );

                    this.value = '';

                    return;
                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        const imageUrl =
                            event.target.result;


                        /*
                        |--------------------------------------------------------------------------
                        | MAIN PREVIEW
                        |--------------------------------------------------------------------------
                        */

                        const currentMainPreview =
                            document.getElementById(
                                'profilePhotoPreview'
                            );

                        const currentMainInitials =
                            document.getElementById(
                                'profilePhotoInitials'
                            );


                        if (currentMainPreview) {

                            currentMainPreview.src =
                                imageUrl;

                        } else if (
                            currentMainInitials
                        ) {

                            currentMainInitials.outerHTML = `
                                <img
                                    src="${imageUrl}"
                                    alt="Profile photo"
                                    id="profilePhotoPreview"
                                >
                            `;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SIDEBAR PREVIEW
                        |--------------------------------------------------------------------------
                        */

                        const currentSidebarPreview =
                            document.getElementById(
                                'sidebarProfilePreview'
                            );

                        const currentSidebarInitials =
                            document.getElementById(
                                'sidebarProfileInitials'
                            );


                        if (currentSidebarPreview) {

                            currentSidebarPreview.src =
                                imageUrl;

                        } else if (
                            currentSidebarInitials
                        ) {

                            currentSidebarInitials.outerHTML = `
                                <img
                                    src="${imageUrl}"
                                    alt="Profile photo"
                                    id="sidebarProfilePreview"
                                >
                            `;
                        }
                    };


                reader.readAsDataURL(file);

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SIDEBAR PREVIEW
    |--------------------------------------------------------------------------
    */

    function updateSidebarPreview(data) {

        if (!data) {
            return;
        }


        const sidebarName =
            document.querySelector(
                '.sb-edit-profile-preview-card h3'
            );

        const sidebarEmail =
            document.querySelector(
                '.sb-edit-profile-preview-card p'
            );


        if (
            sidebarName &&
            data.full_name
        ) {

            sidebarName.textContent =
                data.full_name;
        }


        if (
            sidebarEmail &&
            data.email
        ) {

            sidebarEmail.textContent =
                data.email;
        }


        const sidebarRole =
            document.querySelector(
                '.sb-edit-profile-role span'
            );


        if (
            sidebarRole &&
            data.role
        ) {

            sidebarRole.textContent =
                data.role;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE AVATAR
        |--------------------------------------------------------------------------
        */

        if (data.profile_photo_url) {

            const mainInitials =
                document.getElementById(
                    'profilePhotoInitials'
                );

            const mainPreview =
                document.getElementById(
                    'profilePhotoPreview'
                );


            if (mainPreview) {

                mainPreview.src =
                    data.profile_photo_url;

            } else if (mainInitials) {

                mainInitials.outerHTML = `
                    <img
                        src="${data.profile_photo_url}"
                        alt="Profile photo"
                        id="profilePhotoPreview"
                    >
                `;
            }


            const sidebarInitials =
                document.getElementById(
                    'sidebarProfileInitials'
                );

            const sidebarPreview =
                document.getElementById(
                    'sidebarProfilePreview'
                );


            if (sidebarPreview) {

                sidebarPreview.src =
                    data.profile_photo_url;

            } else if (sidebarInitials) {

                sidebarInitials.outerHTML = `
                    <img
                        src="${data.profile_photo_url}"
                        alt="Profile photo"
                        id="sidebarProfilePreview"
                    >
                `;
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE UPDATE AJAX
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();


                /*
                |--------------------------------------------------------------------------
                | VALIDATE FULL NAME
                |--------------------------------------------------------------------------
                */

                if (!prepareFullName()) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | REMOVE OLD ALERTS
                |--------------------------------------------------------------------------
                */

                removeAjaxAlerts();


                /*
                |--------------------------------------------------------------------------
                | BUTTON LOADING
                |--------------------------------------------------------------------------
                */

                let originalButtonHtml = '';

                if (saveButton) {

                    originalButtonHtml =
                        saveButton.innerHTML;

                    saveButton.disabled =
                        true;

                    saveButton.classList.add(
                        'is-loading'
                    );

                    saveButton.innerHTML = `
                        <span class="sb-edit-profile-spinner"></span>
                        <span>Saving...</span>
                    `;
                }


                /*
                |--------------------------------------------------------------------------
                | FORM DATA
                |--------------------------------------------------------------------------
                */

                const formData =
                    new FormData(form);


                /*
                |--------------------------------------------------------------------------
                | AJAX REQUEST
                |--------------------------------------------------------------------------
                */

                try {

                    const response =
                        await fetch(
                            form.action,
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                body: formData
                            }
                        );


                    const responseText =
                        await response.text();


                    let data;


                    /*
                    |--------------------------------------------------------------------------
                    | PARSE JSON
                    |--------------------------------------------------------------------------
                    */

                    try {

                        data =
                            JSON.parse(
                                responseText
                            );

                    } catch (error) {

                        console.error(
                            'Server response:',
                            responseText
                        );

                        throw new Error(
                            'Server returned an invalid response. Check Laravel logs.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDATION / SERVER ERROR
                    |--------------------------------------------------------------------------
                    */

                    if (!response.ok) {

                        let message =
                            data.message ||
                            'Unable to update your profile.';


                        if (
                            data.errors &&
                            typeof data.errors === 'object'
                        ) {

                            const firstError =
                                Object.values(
                                    data.errors
                                )[0];

                            if (
                                Array.isArray(
                                    firstError
                                ) &&
                                firstError.length
                            ) {

                                message =
                                    firstError[0];

                            } else if (
                                typeof firstError ===
                                'string'
                            ) {

                                message =
                                    firstError;
                            }
                        }


                        throw new Error(
                            message
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS
                    |--------------------------------------------------------------------------
                    */

                    showAlert(
                        'success',
                        'Profile updated',
                        data.message ||
                            'Your profile has been updated successfully.'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE SIDEBAR
                    |--------------------------------------------------------------------------
                    */

                    updateSidebarPreview(
                        data.user || data
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE PHOTO CHANGE STATE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.profile_photo_url &&
                        !document.getElementById(
                            'removeProfilePhotoBtn'
                        )
                    ) {

                        /*
                        | The page can keep the current design.
                        | The remove button will appear after
                        | the next normal render.
                        */
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CLEAR SELECTED FILE
                    |--------------------------------------------------------------------------
                    */

                    if (photoInput) {
                        photoInput.value = '';
                    }


                } catch (error) {

                    console.error(
                        'Profile AJAX error:',
                        error
                    );


                    showAlert(
                        'error',
                        'Unable to save changes',
                        error.message ||
                            'Something went wrong while updating your profile.'
                    );


                } finally {

                    /*
                    |--------------------------------------------------------------------------
                    | RESTORE BUTTON
                    |--------------------------------------------------------------------------
                    */

                    if (saveButton) {

                        saveButton.disabled =
                            false;

                        saveButton.classList.remove(
                            'is-loading'
                        );

                        saveButton.innerHTML =
                            originalButtonHtml;
                    }
                }

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE PROFILE PHOTO AJAX
    |--------------------------------------------------------------------------
    */

    if (
        removeButton &&
        removeForm
    ) {

        removeButton.addEventListener(
            'click',
            async function () {

                const confirmed =
                    window.confirm(
                        'Are you sure you want to remove your profile photo?'
                    );


                if (!confirmed) {
                    return;
                }


                const originalButtonHtml =
                    removeButton.innerHTML;


                removeButton.disabled =
                    true;

                removeButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Removing...
                `;


                const formData =
                    new FormData(
                        removeForm
                    );


                try {

                    const response =
                        await fetch(
                            removeForm.action,
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                body: formData
                            }
                        );


                    const responseText =
                        await response.text();


                    let data;


                    try {

                        data =
                            JSON.parse(
                                responseText
                            );

                    } catch (error) {

                        console.error(
                            'Server response:',
                            responseText
                        );

                        throw new Error(
                            'Server returned an invalid response. Check Laravel logs.'
                        );
                    }


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Unable to remove your profile photo.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS ALERT
                    |--------------------------------------------------------------------------
                    */

                    showAlert(
                        'success',
                        'Photo removed',
                        data.message ||
                            'Your profile photo has been removed successfully.'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE MAIN IMAGE
                    |--------------------------------------------------------------------------
                    */

                    const mainPreview =
                        document.getElementById(
                            'profilePhotoPreview'
                        );


                    if (mainPreview) {

                        mainPreview.outerHTML = `
                            <span id="profilePhotoInitials">
                                ${data.initials || 'U'}
                            </span>
                        `;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE SIDEBAR IMAGE
                    |--------------------------------------------------------------------------
                    */

                    const sidebarPreview =
                        document.getElementById(
                            'sidebarProfilePreview'
                        );


                    if (sidebarPreview) {

                        sidebarPreview.outerHTML = `
                            <span id="sidebarProfileInitials">
                                ${data.initials || 'U'}
                            </span>
                        `;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE BUTTON
                    |--------------------------------------------------------------------------
                    */

                    removeButton.remove();


                } catch (error) {

                    console.error(
                        'Remove photo AJAX error:',
                        error
                    );


                    showAlert(
                        'error',
                        'Unable to remove photo',
                        error.message ||
                            'Something went wrong while removing your profile photo.'
                    );


                    removeButton.disabled =
                        false;

                    removeButton.innerHTML =
                        originalButtonHtml;

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | RESTORE ONLY IF BUTTON STILL EXISTS
                |--------------------------------------------------------------------------
                */

                if (removeButton.isConnected) {

                    removeButton.disabled =
                        false;

                    removeButton.innerHTML =
                        originalButtonHtml;
                }

            }
        );
    }

});
</script>

@endpush



@endsection