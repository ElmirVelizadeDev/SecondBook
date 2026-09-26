@extends('Layout.Frontend.master')

@section('title', 'Account Settings | SecondBook')

@push('css')
<link rel="stylesheet" href="{{ asset('frontend-assets/css/account-settings.css') }}">
@endpush

@section('content')

<section class="account-settings-page">

    <div class="container">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="account-settings-header">

            <div>

                <span class="account-settings-overline">
                    <i class="bi bi-gear"></i>
                    ACCOUNT SETTINGS
                </span>

                <h1>
                    Manage Your
                    <em>Account.</em>
                </h1>

                <p>
                    Control your security, notifications and privacy
                    preferences from one place.
                </p>

            </div>

            <a
                href="{{ route('my.profile') }}"
                class="account-back-profile"
            >
                <i class="bi bi-arrow-left"></i>
                My Profile
            </a>

        </div>


        {{-- =====================================================
             SUCCESS MESSAGE
        ====================================================== --}}

        @if(session('success'))

            <div class="account-alert account-alert-success">
                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>
            </div>

        @endif


        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}

        @if($errors->any())

            <div class="account-alert account-alert-error">

                <i class="bi bi-exclamation-circle-fill"></i>

                <div>

                    <strong>
                        Please check the following:
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- =====================================================
             MAIN LAYOUT
        ====================================================== --}}

        <div class="account-settings-layout">


            {{-- =================================================
                 SIDEBAR
            ================================================== --}}

            <aside class="account-settings-sidebar">

                {{-- Mini Profile --}}

                <div class="account-mini-profile">

                    <div class="account-avatar">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div>

                        <strong>
                            {{ $user->name ?? 'User' }}
                        </strong>

                        <span>
                            {{ $user->email ?? '' }}
                        </span>

                    </div>

                </div>


                {{-- Navigation --}}

                <nav
                    class="account-settings-nav"
                    aria-label="Account Settings Navigation"
                >

                    <a
                        href="#account"
                        class="settings-nav-link active"
                    >
                        <i class="bi bi-person"></i>
                        <span>Account</span>
                    </a>

                    <a
                        href="#security"
                        class="settings-nav-link"
                    >
                        <i class="bi bi-shield-lock"></i>
                        <span>Security</span>
                    </a>

                    <a
                        href="#notifications"
                        class="settings-nav-link"
                    >
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                    </a>

                    <a
                        href="#privacy"
                        class="settings-nav-link"
                    >
                        <i class="bi bi-eye"></i>
                        <span>Privacy</span>
                    </a>

                    <a
                        href="#danger"
                        class="settings-nav-link"
                    >
                        <i class="bi bi-trash3"></i>
                        <span>Danger Zone</span>
                    </a>

                </nav>

            </aside>


            {{-- =================================================
                 MAIN CONTENT
            ================================================== --}}

            <div class="account-settings-content">


                {{-- =================================================
                     ACCOUNT
                ================================================== --}}

                <section
                    id="account"
                    class="settings-card"
                >

                    <div class="settings-card-header">

                        <div class="settings-card-icon">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>

                            <h2>
                                Account Information
                            </h2>

                            <p>
                                Basic information connected to your account.
                            </p>

                        </div>

                    </div>


                    <div class="settings-info-grid">

                        {{-- Full Name --}}

                        <div class="settings-info-item">

                            <span>
                                FULL NAME
                            </span>

                            <strong>
                                {{ $user->name ?? '—' }}
                            </strong>

                        </div>


                        {{-- Email --}}

                        <div class="settings-info-item">

                            <span>
                                EMAIL ADDRESS
                            </span>

                            <strong>
                                {{ $user->email ?? '—' }}
                            </strong>

                        </div>


                        {{-- Member Since --}}

                        <div class="settings-info-item">

                            <span>
                                MEMBER SINCE
                            </span>

                            <strong>
                                {{ $user->created_at?->format('F Y') ?? '—' }}
                            </strong>

                        </div>


                        {{-- Account Status --}}

                        <div class="settings-info-item">

                            <span>
                                ACCOUNT STATUS
                            </span>

                            <strong class="settings-status">

                                <i class="bi bi-check-circle-fill"></i>

                                Active

                            </strong>

                        </div>

                    </div>


                    <div class="settings-card-footer">

                        <span>
                            Need to change your profile information?
                        </span>

                        <a
                            href="{{ route('profile.edit') }}"
                            class="settings-outline-btn"
                        >
                            Edit Profile
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </section>


                {{-- =================================================
                     SECURITY
                ================================================== --}}

                <section
                    id="security"
                    class="settings-card"
                >

                    <div class="settings-card-header">

                        <div class="settings-card-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>

                        <div>

                            <h2>
                                Password & Security
                            </h2>

                            <p>
                                Keep your SecondBook account protected.
                            </p>

                        </div>

                    </div>


                    <form
                        action="{{ route('frontend.account.settings.password') }}"
                        method="POST"
                        class="settings-form"
                        id="passwordSettingsForm"
                    >

                        @csrf

                        @method('PUT')


                        {{-- Current Password --}}

                        <div class="settings-form-group">

                            <label for="current_password">
                                Current Password
                            </label>

                            <div class="settings-input">

                                <i class="bi bi-lock"></i>

                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="Enter your current password"
                                    autocomplete="current-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="current_password"
                                    aria-label="Show password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        {{-- New Password + Confirm Password --}}

                        <div class="settings-form-row">


                            {{-- New Password --}}

                            <div class="settings-form-group">

                                <label for="password">
                                    New Password
                                </label>

                                <div class="settings-input">

                                    <i class="bi bi-key"></i>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Minimum 8 characters"
                                        autocomplete="new-password"
                                        minlength="8"
                                        maxlength="128"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        data-target="password"
                                        aria-label="Show password"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                            </div>


                            {{-- Confirm Password --}}

                            <div class="settings-form-group">

                                <label for="password_confirmation">
                                    Confirm New Password
                                </label>

                                <div class="settings-input">

                                    <i class="bi bi-key-fill"></i>

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Repeat your new password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        maxlength="128"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        data-target="password_confirmation"
                                        aria-label="Show password"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                            </div>

                        </div>


                        {{-- Password Requirements --}}

                        <div
                            class="password-requirements"
                            id="passwordRequirements"
                            style="display: none;"
                        >

                            <div
                                class="password-requirement"
                                id="passwordLengthRequirement"
                            >
                                <i class="bi bi-circle"></i>
                                <span>At least 8 characters</span>
                            </div>

                            <div
                                class="password-requirement"
                                id="passwordLowercaseRequirement"
                            >
                                <i class="bi bi-circle"></i>
                                <span>At least one lowercase letter</span>
                            </div>

                            <div
                                class="password-requirement"
                                id="passwordNumberRequirement"
                            >
                                <i class="bi bi-circle"></i>
                                <span>At least one number</span>
                            </div>

                        </div>


                        {{-- Form Footer --}}

                        <div class="settings-form-footer">

                            <span>
                                <i class="bi bi-info-circle"></i>
                                Use a strong password you don't use elsewhere.
                            </span>

                            <button
                                type="submit"
                                class="settings-primary-btn"
                            >
                                Update Password
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </form>

                </section>


                {{-- =================================================
                     NOTIFICATIONS
                ================================================== --}}

                <section
                    id="notifications"
                    class="settings-card"
                >

                    <div class="settings-card-header">

                        <div class="settings-card-icon">
                            <i class="bi bi-bell"></i>
                        </div>

                        <div>

                            <h2>
                                Notifications
                            </h2>

                            <p>
                                Choose which updates you'd like to receive.
                            </p>

                        </div>

                    </div>


                    <form
                        action="{{ route('frontend.account.settings.preferences') }}"
                        method="POST"
                        class="settings-preferences-form"
                    >

                        @csrf

                        @method('PUT')


                        {{-- Email Notifications --}}

                        <label class="settings-toggle-row">

                            <div class="settings-toggle-text">

                                <div class="settings-toggle-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>

                                <div>

                                    <strong>
                                        Email Notifications
                                    </strong>

                                    <span>
                                        Receive important account emails.
                                    </span>

                                </div>

                            </div>

                            <input
                                type="checkbox"
                                name="email_notifications"
                                value="1"
                                {{ ($settings->email_notifications ?? false) ? 'checked' : '' }}
                            >

                            <span class="settings-switch"></span>

                        </label>


                        {{-- Order Updates --}}

                        <label class="settings-toggle-row">

                            <div class="settings-toggle-text">

                                <div class="settings-toggle-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>

                                <div>

                                    <strong>
                                        Order Updates
                                    </strong>

                                    <span>
                                        Get notified about your orders.
                                    </span>

                                </div>

                            </div>

                            <input
                                type="checkbox"
                                name="order_updates"
                                value="1"
                                {{ ($settings->order_updates ?? false) ? 'checked' : '' }}
                            >

                            <span class="settings-switch"></span>

                        </label>


                        {{-- Promotional Emails --}}

                        <label class="settings-toggle-row">

                            <div class="settings-toggle-text">

                                <div class="settings-toggle-icon">
                                    <i class="bi bi-megaphone"></i>
                                </div>

                                <div>

                                    <strong>
                                        Promotional Emails
                                    </strong>

                                    <span>
                                        Receive offers, news and special deals.
                                    </span>

                                </div>

                            </div>

                            <input
                                type="checkbox"
                                name="promotional_emails"
                                value="1"
                                {{ ($settings->promotional_emails ?? false) ? 'checked' : '' }}
                            >

                            <span class="settings-switch"></span>

                        </label>


                        {{-- Footer --}}

                        <div class="settings-preferences-footer">

                            <button
                                type="submit"
                                class="settings-primary-btn"
                            >
                                Save Preferences
                                <i class="bi bi-check2"></i>
                            </button>

                        </div>

                    </form>

                </section>


                {{-- =================================================
                     PRIVACY
                ================================================== --}}

                <section
                    id="privacy"
                    class="settings-card"
                >

                    <div class="settings-card-header">

                        <div class="settings-card-icon">
                            <i class="bi bi-eye"></i>
                        </div>

                        <div>

                            <h2>
                                Privacy
                            </h2>

                            <p>
                                Control how your profile appears to others.
                            </p>

                        </div>

                    </div>


                    <form
                        action="{{ route('frontend.account.settings.preferences') }}"
                        method="POST"
                        class="privacy-form"
                    >

                        @csrf

                        @method('PUT')


                        <label class="settings-toggle-row">

                            <div class="settings-toggle-text">

                                <div class="settings-toggle-icon">
                                    <i class="bi bi-person-check"></i>
                                </div>

                                <div>

                                    <strong>
                                        Visible Profile
                                    </strong>

                                    <span>
                                        Allow other users to see your public profile.
                                    </span>

                                </div>

                            </div>

                            <input
                                type="checkbox"
                                name="profile_visible"
                                value="1"
                                {{ ($settings->profile_visible ?? false) ? 'checked' : '' }}
                            >

                            <span class="settings-switch"></span>

                        </label>


                        <div class="settings-preferences-footer">

                            <button
                                type="submit"
                                class="settings-primary-btn"
                            >
                                Save Privacy
                                <i class="bi bi-check2"></i>
                            </button>

                        </div>

                    </form>

                </section>


                {{-- =================================================
                     DANGER ZONE
                ================================================== --}}

                <section
                    id="danger"
                    class="settings-card settings-danger-card"
                >

                    <div class="settings-card-header">

                        <div class="settings-card-icon danger">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                        <div>

                            <h2>
                                Danger Zone
                            </h2>

                            <p>
                                Actions here can permanently affect your account.
                            </p>

                        </div>

                    </div>


                    <div class="danger-action">

                        <div>

                            <strong>
                                Delete Account
                            </strong>

                            <span>
                                Permanently delete your SecondBook account
                                and associated information.
                            </span>

                        </div>


                        <form
                            action="{{ route('profile.destroy') }}"
                            method="POST"
                            class="danger-delete-form"
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="danger-delete-btn"
                            >
                                <i class="bi bi-trash3"></i>
                                Delete Account
                            </button>

                        </form>

                    </div>

                </section>

            </div>

        </div>

    </div>

</section>

@endsection


{{-- =============================================================
     JAVASCRIPT
============================================================== --}}

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* =========================================================
       ELEMENTS
    ========================================================= */

    const navLinks = Array.from(
        document.querySelectorAll('.settings-nav-link')
    );

    const sections = Array.from(
        document.querySelectorAll(
            '.account-settings-content .settings-card'
        )
    );


    /* =========================================================
       ACTIVE LINK
    ========================================================= */

    function setActiveLink(sectionId) {
        navLinks.forEach(function (link) {
            const href = link.getAttribute('href');

            link.classList.toggle(
                'active',
                href === '#' + sectionId
            );
        });
    }


    /* =========================================================
       PASSWORD TOGGLE
    ========================================================= */

    const passwordButtons =
        document.querySelectorAll('.password-toggle');

    passwordButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId =
                this.getAttribute('data-target');

            const input =
                document.getElementById(targetId);

            const icon =
                this.querySelector('i');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

                this.setAttribute(
                    'aria-label',
                    'Hide password'
                );

            } else {

                input.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

                this.setAttribute(
                    'aria-label',
                    'Show password'
                );
            }
        });
    });


    /* =========================================================
       NAVIGATION CLICK
    ========================================================= */

    navLinks.forEach(function (link) {

        link.addEventListener('click', function (event) {

            event.preventDefault();

            const href =
                this.getAttribute('href');

            if (!href || href === '#') {
                return;
            }

            const targetId =
                href.substring(1);

            const targetSection =
                document.getElementById(targetId);

            if (!targetSection) {
                return;
            }

            setActiveLink(targetId);

            const navbar =
                document.querySelector(
                    '.navbar, header, .site-header'
                );

            let offset = 100;

            if (navbar) {

                const navbarHeight =
                    navbar.getBoundingClientRect().height;

                if (navbarHeight > 0) {
                    offset = navbarHeight + 25;
                }
            }

            const targetTop =
                targetSection.getBoundingClientRect().top +
                window.pageYOffset -
                offset;

            window.scrollTo({
                top: Math.max(0, targetTop),
                behavior: 'smooth'
            });
        });
    });


    /* =========================================================
       DETECT CURRENT SECTION
    ========================================================= */

    function updateActiveSection() {

        if (!sections.length) {
            return;
        }

        const detectionPoint = 180;

        let currentSection = sections[0];

        sections.forEach(function (section) {

            const rect =
                section.getBoundingClientRect();

            if (rect.top <= detectionPoint) {
                currentSection = section;
            }
        });

        if (currentSection) {

            setActiveLink(
                currentSection.id
            );
        }
    }


    /* =========================================================
       SCROLL HANDLER
    ========================================================= */

    let scrollTicking = false;

    function handleScroll() {

        if (scrollTicking) {
            return;
        }

        window.requestAnimationFrame(function () {

            updateActiveSection();

            scrollTicking = false;
        });

        scrollTicking = true;
    }

    window.addEventListener(
        'scroll',
        handleScroll,
        {
            passive: true,
            capture: true
        }
    );


    /* =========================================================
       RESIZE
    ========================================================= */

    window.addEventListener(
        'resize',
        updateActiveSection
    );


    /* =========================================================
       INTERSECTION OBSERVER
    ========================================================= */

    if ('IntersectionObserver' in window) {

        const observer =
            new IntersectionObserver(
                function (entries) {

                    const visibleSections =
                        entries
                            .filter(function (entry) {
                                return entry.isIntersecting;
                            })
                            .sort(function (a, b) {

                                return (
                                    a.boundingClientRect.top -
                                    b.boundingClientRect.top
                                );
                            });

                    if (visibleSections.length) {

                        setActiveLink(
                            visibleSections[0]
                                .target
                                .id
                        );
                    }
                },
                {
                    root: null,
                    rootMargin: '-15% 0px -65% 0px',
                    threshold: 0
                }
            );

        sections.forEach(function (section) {
            observer.observe(section);
        });
    }


    /* =========================================================
       INITIAL STATE
    ========================================================= */

    updateActiveSection();


    /* =========================================================
       PASSWORD ELEMENTS
    ========================================================= */

    const password =
        document.getElementById('password');

    const passwordConfirmation =
        document.getElementById(
            'password_confirmation'
        );

    const passwordRequirements =
        document.getElementById(
            'passwordRequirements'
        );

    const passwordLengthRequirement =
        document.getElementById(
            'passwordLengthRequirement'
        );

    const passwordLowercaseRequirement =
        document.getElementById(
            'passwordLowercaseRequirement'
        );

    const passwordNumberRequirement =
        document.getElementById(
            'passwordNumberRequirement'
        );


    /* =========================================================
       PASSWORD REQUIREMENT UI
    ========================================================= */

    function updateRequirement(
        element,
        passed
    ) {

        if (!element) {
            return;
        }

        const icon =
            element.querySelector('i');

        if (!icon) {
            return;
        }

        if (passed) {

            element.classList.add('valid');

            icon.classList.remove(
                'bi-circle'
            );

            icon.classList.add(
                'bi-check-circle-fill'
            );

        } else {

            element.classList.remove('valid');

            icon.classList.remove(
                'bi-check-circle-fill'
            );

            icon.classList.add(
                'bi-circle'
            );
        }
    }


    /* =========================================================
       PASSWORD VALIDATION
    ========================================================= */

    function validatePassword() {

        if (!password) {
            return false;
        }

        const value =
            password.value;

        const hasMinimumLength =
            value.length >= 8;

        const hasLowercase =
            /[a-z]/.test(value);

        const hasNumber =
            /[0-9]/.test(value);

        updateRequirement(
            passwordLengthRequirement,
            hasMinimumLength
        );

        updateRequirement(
            passwordLowercaseRequirement,
            hasLowercase
        );

        updateRequirement(
            passwordNumberRequirement,
            hasNumber
        );

        return (
            hasMinimumLength &&
            hasLowercase &&
            hasNumber
        );
    }


    /* =========================================================
       PASSWORD MATCH
    ========================================================= */

    function checkPasswordMatch() {

        if (
            passwordConfirmation &&
            passwordConfirmation.value
        ) {

            if (
                password.value !==
                passwordConfirmation.value
            ) {

                passwordConfirmation.setCustomValidity(
                    'Passwords do not match.'
                );

            } else {

                passwordConfirmation.setCustomValidity(
                    ''
                );
            }

        } else if (passwordConfirmation) {

            passwordConfirmation.setCustomValidity('');
        }
    }


    /* =========================================================
       PASSWORD INPUT EVENTS
    ========================================================= */

    if (password) {

        password.addEventListener(
            'input',
            function () {

                if (
                    passwordRequirements &&
                    password.value.length > 0
                ) {

                    passwordRequirements.style.display =
                        'block';

                } else if (passwordRequirements) {

                    passwordRequirements.style.display =
                        'none';
                }

                validatePassword();

                checkPasswordMatch();
            }
        );
    }


    if (passwordConfirmation) {

        passwordConfirmation.addEventListener(
            'input',
            checkPasswordMatch
        );
    }


    /* =========================================================
       AJAX MESSAGE HELPERS
    ========================================================= */

    function removeAjaxMessages(form) {

        if (!form || !form.parentNode) {
            return;
        }

        const successMessage =
            form.parentNode.querySelector(
                '.settings-ajax-success'
            );

        const errorMessage =
            form.parentNode.querySelector(
                '.settings-ajax-error'
            );

        if (successMessage) {
            successMessage.remove();
        }

        if (errorMessage) {
            errorMessage.remove();
        }
    }


    function showAjaxMessage(
        form,
        type,
        message
    ) {

        if (!form || !form.parentNode) {
            return;
        }

        removeAjaxMessages(form);

        const messageElement =
            document.createElement('div');

        if (type === 'success') {

            messageElement.className =
                'account-alert account-alert-success settings-ajax-success';

            messageElement.innerHTML = `
                <i class="bi bi-check-circle-fill"></i>
                <span>${message}</span>
            `;

        } else {

            messageElement.className =
                'account-alert account-alert-error settings-ajax-error';

            messageElement.innerHTML = `
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>${message}</span>
            `;
        }

        form.parentNode.insertBefore(
            messageElement,
            form
        );

        messageElement.style.display =
            'flex';

        messageElement.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        setTimeout(function () {

            messageElement.style.display =
                'none';

        }, type === 'success' ? 4000 : 5000);
    }


    /* =========================================================
       GET CSRF TOKEN
    ========================================================= */

    function getCsrfToken() {

        const csrfMeta =
            document.querySelector(
                'meta[name="csrf-token"]'
            );

        if (!csrfMeta) {
            return '';
        }

        return (
            csrfMeta.getAttribute('content') || ''
        );
    }


    /* =========================================================
       PASSWORD — AJAX UPDATE
    ========================================================= */

    const passwordForm =
        document.getElementById(
            'passwordSettingsForm'
        );

    if (passwordForm) {

        passwordForm.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();

                checkPasswordMatch();

                /*
                 * Browser native validation
                 */
                if (!passwordForm.checkValidity()) {

                    passwordForm.reportValidity();

                    return;
                }


                /*
                 * Password requirements
                 */
                if (!validatePassword()) {

                    showAjaxMessage(
                        passwordForm,
                        'error',
                        'Password must contain at least 8 characters, one lowercase letter and one number.'
                    );

                    return;
                }


                /*
                 * New password must be different
                 */
                const currentPasswordInput =
                    passwordForm.querySelector(
                        '#current_password'
                    );

                const passwordInput =
                    passwordForm.querySelector(
                        '#password'
                    );

                if (
                    currentPasswordInput &&
                    passwordInput &&
                    currentPasswordInput.value ===
                    passwordInput.value
                ) {

                    showAjaxMessage(
                        passwordForm,
                        'error',
                        'New password must be different from your current password.'
                    );

                    return;
                }


                /*
                 * Submit button
                 */
                const submitButton =
                    passwordForm.querySelector(
                        'button[type="submit"]'
                    );

                if (!submitButton) {
                    return;
                }

                const originalButtonHtml =
                    submitButton.innerHTML;

                submitButton.disabled = true;

                submitButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Updating...
                `;


                /*
                 * Form data
                 */
                const formData =
                    new FormData(passwordForm);


                try {

                    const response =
                        await fetch(
                            passwordForm.action,
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        getCsrfToken(),

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                body: formData
                            }
                        );


                    /*
                     * Read response
                     */
                    const responseText =
                        await response.text();

                    let data;


                    /*
                     * Parse JSON
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
                     * Validation / Laravel error
                     */
                    if (
                        response.status === 422 ||
                        data.success === false
                    ) {

                        let message =
                            data.message ||
                            'Please check the entered information.';


                        /*
                         * Laravel validation errors
                         */
                        if (
                            data.errors &&
                            typeof data.errors === 'object'
                        ) {

                            const firstField =
                                Object.keys(
                                    data.errors
                                )[0];

                            if (
                                firstField &&
                                Array.isArray(
                                    data.errors[firstField]
                                ) &&
                                data.errors[firstField].length
                            ) {

                                message =
                                    data.errors[firstField][0];
                            }
                        }


                        showAjaxMessage(
                            passwordForm,
                            'error',
                            message
                        );

                        return;
                    }


                    /*
                     * Other HTTP errors
                     */
                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Unable to update your password.'
                        );
                    }


                    /*
                     * SUCCESS
                     */
                    showAjaxMessage(
                        passwordForm,
                        'success',
                        data.message ||
                        'Password updated successfully.'
                    );


                    /*
                     * Clear current password
                     */
                    if (currentPasswordInput) {
                        currentPasswordInput.value = '';
                    }


                    /*
                     * Clear new password
                     */
                    if (passwordInput) {
                        passwordInput.value = '';
                    }


                    /*
                     * Clear confirmation
                     */
                    const passwordConfirmationInput =
                        passwordForm.querySelector(
                            '#password_confirmation'
                        );

                    if (passwordConfirmationInput) {
                        passwordConfirmationInput.value = '';

                        passwordConfirmationInput.setCustomValidity('');
                    }


                    /*
                     * Hide requirements
                     */
                    if (passwordRequirements) {

                        passwordRequirements.style.display =
                            'none';
                    }


                    /*
                     * Reset requirement icons
                     */
                    updateRequirement(
                        passwordLengthRequirement,
                        false
                    );

                    updateRequirement(
                        passwordLowercaseRequirement,
                        false
                    );

                    updateRequirement(
                        passwordNumberRequirement,
                        false
                    );


                } catch (error) {

                    console.error(
                        'Password AJAX error:',
                        error
                    );

                    showAjaxMessage(
                        passwordForm,
                        'error',
                        error.message ||
                        'Unable to update your password.'
                    );

                } finally {

                    submitButton.disabled =
                        false;

                    submitButton.innerHTML =
                        originalButtonHtml;
                }
            }
        );
    }


    /* =========================================================
       NOTIFICATIONS + PRIVACY — AJAX SAVE
    ========================================================= */

    const settingsForms =
        document.querySelectorAll(
            '.settings-preferences-form, .privacy-form'
        );


    settingsForms.forEach(function (form) {

        form.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();


                const submitButton =
                    form.querySelector(
                        'button[type="submit"]'
                    );

                if (!submitButton) {
                    return;
                }


                const originalButtonHtml =
                    submitButton.innerHTML;

                submitButton.disabled = true;

                submitButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Saving...
                `;


                const formData =
                    new FormData(form);


                try {

                    const response =
                        await fetch(
                            form.action,
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        getCsrfToken(),

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


                    /*
                     * Validation / backend error
                     */
                    if (
                        response.status === 422 ||
                        data.success === false
                    ) {

                        let message =
                            data.message ||
                            'Please check the entered information.';


                        if (
                            data.errors &&
                            typeof data.errors === 'object'
                        ) {

                            const firstField =
                                Object.keys(
                                    data.errors
                                )[0];

                            if (
                                firstField &&
                                Array.isArray(
                                    data.errors[firstField]
                                ) &&
                                data.errors[firstField].length
                            ) {

                                message =
                                    data.errors[firstField][0];
                            }
                        }


                        showAjaxMessage(
                            form,
                            'error',
                            message
                        );

                        return;
                    }


                    /*
                     * Other HTTP errors
                     */
                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Unable to save your settings.'
                        );
                    }


                    /*
                     * SUCCESS
                     */
                    showAjaxMessage(
                        form,
                        'success',
                        data.message ||
                        'Your settings have been updated successfully.'
                    );


                } catch (error) {

                    console.error(
                        'Settings AJAX error:',
                        error
                    );

                    showAjaxMessage(
                        form,
                        'error',
                        error.message ||
                        'Unable to update your settings.'
                    );

                } finally {

                    submitButton.disabled =
                        false;

                    submitButton.innerHTML =
                        originalButtonHtml;
                }
            }
        );
    });


    /* =========================================================
       DELETE ACCOUNT CONFIRMATION
    ========================================================= */

    const deleteForm =
        document.querySelector(
            '.danger-delete-form'
        );


    if (deleteForm) {

        deleteForm.addEventListener(
            'submit',
            function (event) {

                const confirmed =
                    window.confirm(
                        'Are you sure you want to permanently delete your account? This action cannot be undone.'
                    );

                if (!confirmed) {
                    event.preventDefault();
                }
            }
        );
    }


    /* =========================================================
       DEBUG
    ========================================================= */

    console.log(
        'SecondBook Account Settings JS loaded successfully.'
    );

});
</script>

@endpush