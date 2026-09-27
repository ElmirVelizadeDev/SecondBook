@extends('Layout.Frontend.master')

@section('title', 'Account Settings | SecondBook')

@push('css')
    <link
        rel="stylesheet"
        href="{{ asset('frontend-assets/css/account-settings.css') }}"
    >
@endpush

@section('content')

<section class="account-settings-page">

    <div class="container">

        {{-- Header --}}
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
                href="{{ route('profile.edit') }}"
                class="account-back-profile"
            >
                <i class="bi bi-arrow-left"></i>
                My Profile
            </a>
        </div>


        {{-- Success Alert --}}
        @if(session('success'))
            <div class="account-alert account-alert-success">
                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>
            </div>
        @endif


        {{-- Error Alert --}}
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


        {{-- Main Layout --}}
        <div class="account-settings-layout">


            {{-- Sidebar --}}
            <aside class="account-settings-sidebar">

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


            {{-- Content --}}
            <div class="account-settings-content">


                {{-- Account --}}
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

                        <div class="settings-info-item">
                            <span>
                                FULL NAME
                            </span>

                            <strong>
                                {{ $user->name ?? '—' }}
                            </strong>
                        </div>


                        <div class="settings-info-item">
                            <span>
                                EMAIL ADDRESS
                            </span>

                            <strong>
                                {{ $user->email ?? '—' }}
                            </strong>
                        </div>


                        <div class="settings-info-item">
                            <span>
                                MEMBER SINCE
                            </span>

                            <strong>
                                {{ $user->created_at?->format('F Y') ?? '—' }}
                            </strong>
                        </div>


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


                {{-- Security --}}
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


                        {{-- Footer --}}
                        <div class="settings-form-footer">

                            <span>
                                <i class="bi bi-shield-check"></i>
                                A verification code will be sent to your email.
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


                {{-- Notifications --}}
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


                {{-- Privacy --}}
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


                {{-- Danger Zone --}}
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


@push('js')

<script>
    console.log('ACCOUNT SETTINGS JS LOADED');

    document.addEventListener('DOMContentLoaded', function () {

        'use strict';


        /*
        |--------------------------------------------------------------------------
        | Navigation
        |--------------------------------------------------------------------------
        */

        const navLinks = Array.from(
            document.querySelectorAll('.settings-nav-link')
        );

        const sections = Array.from(
            document.querySelectorAll(
                '.account-settings-content .settings-card'
            )
        );


        function setActiveLink(sectionId) {

            navLinks.forEach(function (link) {

                link.classList.toggle(
                    'active',
                    link.getAttribute('href') === '#' + sectionId
                );

            });

        }


        navLinks.forEach(function (link) {

            link.addEventListener('click', function (event) {

                event.preventDefault();

                const href = this.getAttribute('href');

                if (!href || href === '#') {
                    return;
                }


                const targetSection =
                    document.getElementById(
                        href.substring(1)
                    );

                if (!targetSection) {
                    return;
                }


                setActiveLink(targetSection.id);


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


        function updateActiveSection() {

            if (!sections.length) {
                return;
            }


            const detectionPoint = 180;

            let currentSection = sections[0];


            sections.forEach(function (section) {

                if (
                    section.getBoundingClientRect().top <=
                    detectionPoint
                ) {

                    currentSection = section;

                }

            });


            setActiveLink(currentSection.id);

        }


        let scrollTicking = false;


        window.addEventListener(
            'scroll',
            function () {

                if (scrollTicking) {
                    return;
                }


                window.requestAnimationFrame(function () {

                    updateActiveSection();

                    scrollTicking = false;

                });


                scrollTicking = true;

            },
            {
                passive: true
            }
        );


        window.addEventListener(
            'resize',
            updateActiveSection
        );


        /*
        |--------------------------------------------------------------------------
        | Password Toggle
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.password-toggle')
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const input =
                            document.getElementById(
                                this.getAttribute('data-target')
                            );

                        const icon =
                            this.querySelector('i');


                        if (!input || !icon) {
                            return;
                        }


                        const isPassword =
                            input.type === 'password';


                        input.type =
                            isPassword
                                ? 'text'
                                : 'password';


                        icon.classList.toggle(
                            'bi-eye',
                            !isPassword
                        );

                        icon.classList.toggle(
                            'bi-eye-slash',
                            isPassword
                        );


                        this.setAttribute(
                            'aria-label',
                            isPassword
                                ? 'Hide password'
                                : 'Show password'
                        );

                    }
                );

            });


        /*
        |--------------------------------------------------------------------------
        | AJAX Messages
        |--------------------------------------------------------------------------
        */

        function removeAjaxMessages(form) {

            if (!form || !form.parentNode) {
                return;
            }


            form.parentNode
                .querySelectorAll(
                    '.settings-ajax-success, .settings-ajax-error'
                )
                .forEach(function (element) {

                    element.remove();

                });

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


            messageElement.className =
                type === 'success'
                    ? 'account-alert account-alert-success settings-ajax-success'
                    : 'account-alert account-alert-error settings-ajax-error';


            messageElement.innerHTML = `
                <i class="${
                    type === 'success'
                        ? 'bi bi-check-circle-fill'
                        : 'bi bi-exclamation-circle-fill'
                }"></i>

                <span>${message}</span>
            `;


            form.parentNode.insertBefore(
                messageElement,
                form
            );


            messageElement.style.display = 'flex';


            messageElement.scrollIntoView({

                behavior: 'smooth',

                block: 'center'

            });


            setTimeout(function () {

                messageElement.style.display = 'none';

            }, type === 'success' ? 4000 : 5000);

        }


        function getCsrfToken() {

            const csrfMeta =
                document.querySelector(
                    'meta[name="csrf-token"]'
                );


            return csrfMeta
                ? csrfMeta.getAttribute('content') || ''
                : '';

        }


        /*
        |--------------------------------------------------------------------------
        | Password Change → Send OTP
        |--------------------------------------------------------------------------
        */

        const passwordForm =
            document.getElementById(
                'passwordSettingsForm'
            );


        if (passwordForm) {

            passwordForm.addEventListener(
                'submit',
                async function (event) {

                    event.preventDefault();


                    if (!passwordForm.checkValidity()) {

                        passwordForm.reportValidity();

                        return;

                    }


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

                        Sending Code...
                    `;


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

                                    body:
                                        new FormData(
                                            passwordForm
                                        ),

                                    credentials:
                                        'same-origin'
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

                            throw new Error(
                                'Server returned an invalid response. Check Laravel logs.'
                            );

                        }


                        if (
                            response.status === 422 ||
                            data.success === false
                        ) {

                            let message =
                                data.message ||
                                'Please check your current password.';


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


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Unable to send the verification code.'
                            );

                        }


                        if (data.redirect) {

                            window.location.href =
                                data.redirect;

                            return;

                        }


                        window.location.href =
                            @json(
                                route(
                                    'frontend.auth.password.verify'
                                )
                            );

                    } catch (error) {

                        console.error(
                            'Password verification request failed:',
                            error
                        );


                        showAjaxMessage(
                            passwordForm,
                            'error',
                            error.message ||
                            'Unable to send the verification code.'
                        );

                    } finally {

                        submitButton.disabled = false;

                        submitButton.innerHTML =
                            originalButtonHtml;

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Notifications / Privacy
        |--------------------------------------------------------------------------
        */

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

                                    body:
                                        new FormData(form),

                                    credentials:
                                        'same-origin'
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

                            throw new Error(
                                'Server returned an invalid response. Check Laravel logs.'
                            );

                        }


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


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Unable to save your settings.'
                            );

                        }


                        showAjaxMessage(
                            form,
                            'success',
                            data.message ||
                            'Your settings have been updated successfully.'
                        );

                    } catch (error) {

                        console.error(
                            'Settings request failed:',
                            error
                        );


                        showAjaxMessage(
                            form,
                            'error',
                            error.message ||
                            'Unable to update your settings.'
                        );

                    } finally {

                        submitButton.disabled = false;

                        submitButton.innerHTML =
                            originalButtonHtml;

                    }

                }
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Account Deletion → Confirmation → Send OTP
        |--------------------------------------------------------------------------
        */

        const deleteForm =
            document.querySelector(
                '.danger-delete-form'
            );


        if (deleteForm) {

            deleteForm.addEventListener(
                'submit',
                async function (event) {

                    event.preventDefault();


                    const submitButton =
                        deleteForm.querySelector(
                            'button[type="submit"]'
                        );


                    if (!submitButton) {
                        return;
                    }


                    const result =
                        await Swal.fire({

                            icon: 'warning',

                            title: 'Delete Account?',

                            html: `
                                <p style="margin-bottom: 8px;">
                                    Are you sure you want to permanently delete your account?
                                </p>

                                <p
                                    style="
                                        margin-bottom: 0;
                                        color: #dc3545;
                                        font-size: 14px;
                                    "
                                >
                                    This action cannot be undone.
                                </p>
                            `,

                            showCancelButton: true,

                            confirmButtonText:
                                'Yes, Continue',

                            cancelButtonText:
                                'Cancel',

                            reverseButtons: true,

                            focusCancel: true,

                            customClass: {
                                confirmButton:
                                    'btn btn-danger',

                                cancelButton:
                                    'btn btn-secondary delete-cancel-btn'
                            },

                            buttonsStyling: false

                        });


                    if (!result.isConfirmed) {
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

                        Sending Code...
                    `;


                    try {

                        const response =
                            await fetch(
                                deleteForm.action,
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

                                    body:
                                        new FormData(
                                            deleteForm
                                        ),

                                    credentials:
                                        'same-origin'
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

                            throw new Error(
                                'Server returned an invalid response. Check Laravel logs.'
                            );

                        }


                        if (
                            response.status === 422 ||
                            data.success === false
                        ) {

                            showAjaxMessage(
                                deleteForm,
                                'error',
                                data.message ||
                                'Unable to start account deletion.'
                            );


                            return;

                        }


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Unable to send the verification code.'
                            );

                        }


                        if (data.redirect) {

                            window.location.href =
                                data.redirect;

                            return;

                        }


                        /*
                         * Dedicated account deletion
                         * verification page.
                         */
                        window.location.href =
                            @json(
                                route(
                                    'frontend.auth.account.delete.verify'
                                )
                            );

                    } catch (error) {

                        console.error(
                            'Account deletion request failed:',
                            error
                        );


                        showAjaxMessage(
                            deleteForm,
                            'error',
                            error.message ||
                            'Unable to send the verification code.'
                        );

                    } finally {

                        submitButton.disabled = false;

                        submitButton.innerHTML =
                            originalButtonHtml;

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Initial Active Section
        |--------------------------------------------------------------------------
        */

        updateActiveSection();

    });
</script>

@endpush

