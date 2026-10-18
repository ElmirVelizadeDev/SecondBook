@extends('Layout.Admin.master')

@section('title', 'Email Settings')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/pages/email-settings.css') }}">
@endpush

@section('content')

<div class="dashboard-section email-settings-page">

    {{-- Page Header --}}
    <div class="dashboard-panel email-settings-header">
        <div class="email-settings-header-content">
            <div class="email-settings-title-wrap">
                <div class="email-settings-icon">
                    <i class="bi bi-envelope-paper"></i>
                </div>

                <div>
                    <h1>Email Settings</h1>
                    <p>
                        Configure your SMTP connection and manage system email delivery.
                    </p>
                </div>
            </div>

            <div class="email-status-badge">
                <span class="email-status-dot"></span>
                Email Configuration
            </div>
        </div>
    </div>


    {{-- Alerts --}}
    @if(session('success'))
        <div class="email-alert email-alert-success">
            <div class="email-alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="email-alert-content">
                <strong>Success</strong>
                <span>{{ session('success') }}</span>
            </div>

            <button type="button" class="email-alert-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="email-alert email-alert-error">
            <div class="email-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="email-alert-content">
                <strong>Something went wrong</strong>
                <span>{{ session('error') }}</span>
            </div>

            <button type="button" class="email-alert-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="email-alert email-alert-error">
            <div class="email-alert-icon">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>

            <div class="email-alert-content">
                <strong>Please check the form</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="email-alert-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif


    <div class="email-settings-grid">

        {{-- Main Settings --}}
        <div class="email-settings-main">

            <form
                action="{{ route('admin.email-settings.update') }}"
                method="POST"
                id="emailSettingsForm"
            >
                @csrf
                @method('PUT')

                {{-- SMTP Configuration --}}
                <div class="dashboard-panel email-card">

                    <div class="email-card-header">
                        <div class="email-card-heading">
                            <div class="email-card-icon">
                                <i class="bi bi-server"></i>
                            </div>

                            <div>
                                <h2>Mail Configuration</h2>
                                <p>
                                    Configure how SecondBook sends outgoing emails.
                                </p>
                            </div>
                        </div>

                        <label class="email-switch">
                            <input
                                type="checkbox"
                                name="mail_enabled"
                                value="1"
                                {{ old(
                                    'mail_enabled',
                                    isset($settings['mail_enabled'])
                                        ? filter_var($settings['mail_enabled']->value, FILTER_VALIDATE_BOOLEAN)
                                        : true
                                ) ? 'checked' : '' }}
                            >

                            <span class="email-switch-slider"></span>

                            <span class="email-switch-label">
                                Enabled
                            </span>
                        </label>
                    </div>


                    <div class="email-card-body">

                        <div class="email-form-grid">

                            {{-- Mailer --}}
                            <div class="email-form-group">
                                <label for="mail_mailer">
                                    Mailer
                                    <span>*</span>
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-send"></i>

                                    <select
                                        id="mail_mailer"
                                        name="mail_mailer"
                                        class="email-form-control"
                                    >
                                        <option
                                            value="smtp"
                                            {{ old('mail_mailer', $settings['mail_mailer']->value ?? 'smtp') === 'smtp' ? 'selected' : '' }}
                                        >
                                            SMTP
                                        </option>

                                        <option
                                            value="log"
                                            {{ old('mail_mailer', $settings['mail_mailer']->value ?? 'smtp') === 'log' ? 'selected' : '' }}
                                        >
                                            Log
                                        </option>
                                    </select>
                                </div>

                                <small>
                                    SMTP is recommended for real email delivery.
                                </small>
                            </div>


                            {{-- Host --}}
                            <div class="email-form-group smtp-field">
                                <label for="mail_host">
                                    SMTP Host
                                    <span>*</span>
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-hdd-network"></i>

                                    <input
                                        type="text"
                                        id="mail_host"
                                        name="mail_host"
                                        class="email-form-control"
                                        value="{{ old('mail_host', $settings['mail_host']->value ?? '') }}"
                                        placeholder="smtp.example.com"
                                    >
                                </div>
                            </div>


                            {{-- Port --}}
                            <div class="email-form-group smtp-field">
                                <label for="mail_port">
                                    SMTP Port
                                    <span>*</span>
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-ethernet"></i>

                                    <input
                                        type="number"
                                        id="mail_port"
                                        name="mail_port"
                                        class="email-form-control"
                                        value="{{ old('mail_port', $settings['mail_port']->value ?? 587) }}"
                                        min="1"
                                        max="65535"
                                        placeholder="587"
                                    >
                                </div>

                                <small>
                                    Common ports: 587 (TLS), 465 (SSL).
                                </small>
                            </div>


                            {{-- Encryption --}}
                            <div class="email-form-group smtp-field">
                                <label for="mail_encryption">
                                    Encryption
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-shield-lock"></i>

                                    <select
                                        id="mail_encryption"
                                        name="mail_encryption"
                                        class="email-form-control"
                                    >
                                        @php
                                            $encryption = old(
                                                'mail_encryption',
                                                $settings['mail_encryption']->value ?? 'tls'
                                            );
                                        @endphp

                                        <option value="tls" {{ $encryption === 'tls' ? 'selected' : '' }}>
                                            TLS
                                        </option>

                                        <option value="ssl" {{ $encryption === 'ssl' ? 'selected' : '' }}>
                                            SSL
                                        </option>

                                        <option value="none" {{ $encryption === 'none' ? 'selected' : '' }}>
                                            None
                                        </option>
                                    </select>
                                </div>
                            </div>


                            {{-- Username --}}
                            <div class="email-form-group smtp-field">
                                <label for="mail_username">
                                    SMTP Username
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-person"></i>

                                    <input
                                        type="text"
                                        id="mail_username"
                                        name="mail_username"
                                        class="email-form-control"
                                        value="{{ old('mail_username', $settings['mail_username']->value ?? '') }}"
                                        placeholder="your@email.com"
                                        autocomplete="username"
                                    >
                                </div>
                            </div>


                            {{-- Password --}}
                            <div class="email-form-group smtp-field">
                                <label for="mail_password">
                                    SMTP Password
                                </label>

                                <div class="email-input-wrap email-password-wrap">
                                    <i class="bi bi-key"></i>

                                    <input
                                        type="password"
                                        id="mail_password"
                                        name="mail_password"
                                        class="email-form-control"
                                        placeholder="Leave empty to keep current password"
                                        autocomplete="new-password"
                                    >

                                    <button
                                        type="button"
                                        class="email-password-toggle"
                                        id="togglePassword"
                                        aria-label="Show password"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                                <small>
                                    Your existing password remains unchanged if this field is empty.
                                </small>
                            </div>

                        </div>

                    </div>
                </div>


                {{-- Sender Settings --}}
                <div class="dashboard-panel email-card">

                    <div class="email-card-header">
                        <div class="email-card-heading">
                            <div class="email-card-icon">
                                <i class="bi bi-person-vcard"></i>
                            </div>

                            <div>
                                <h2>Sender Information</h2>
                                <p>
                                    Define the address and name shown on outgoing emails.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="email-card-body">

                        <div class="email-form-grid">

                            <div class="email-form-group">
                                <label for="mail_from_address">
                                    From Address
                                    <span>*</span>
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-envelope"></i>

                                    <input
                                        type="email"
                                        id="mail_from_address"
                                        name="mail_from_address"
                                        class="email-form-control"
                                        value="{{ old('mail_from_address', $settings['mail_from_address']->value ?? '') }}"
                                        placeholder="noreply@example.com"
                                    >
                                </div>
                            </div>


                            <div class="email-form-group">
                                <label for="mail_from_name">
                                    From Name
                                    <span>*</span>
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-type"></i>

                                    <input
                                        type="text"
                                        id="mail_from_name"
                                        name="mail_from_name"
                                        class="email-form-control"
                                        value="{{ old('mail_from_name', $settings['mail_from_name']->value ?? 'SecondBook') }}"
                                        placeholder="SecondBook"
                                    >
                                </div>
                            </div>


                            <div class="email-form-group">
                                <label for="mail_reply_to_address">
                                    Reply-To Address
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-reply"></i>

                                    <input
                                        type="email"
                                        id="mail_reply_to_address"
                                        name="mail_reply_to_address"
                                        class="email-form-control"
                                        value="{{ old('mail_reply_to_address', $settings['mail_reply_to_address']->value ?? '') }}"
                                        placeholder="support@example.com"
                                    >
                                </div>
                            </div>


                            <div class="email-form-group">
                                <label for="mail_reply_to_name">
                                    Reply-To Name
                                </label>

                                <div class="email-input-wrap">
                                    <i class="bi bi-person-lines-fill"></i>

                                    <input
                                        type="text"
                                        id="mail_reply_to_name"
                                        name="mail_reply_to_name"
                                        class="email-form-control"
                                        value="{{ old('mail_reply_to_name', $settings['mail_reply_to_name']->value ?? '') }}"
                                        placeholder="SecondBook Support"
                                    >
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                {{-- Actions --}}
                <div class="email-form-actions">

                    <button
                        type="submit"
                        class="email-btn email-btn-primary"
                        id="saveEmailSettings"
                    >
                        <i class="bi bi-check2-circle"></i>
                        <span>Save Changes</span>
                    </button>

                </div>

            </form>

        </div>


        {{-- Sidebar --}}
        <aside class="email-settings-sidebar">

            {{-- Test Email --}}
            <div class="dashboard-panel email-card email-test-card">

                <div class="email-card-header">
                    <div class="email-card-heading">
                        <div class="email-card-icon">
                            <i class="bi bi-send-check"></i>
                        </div>

                        <div>
                            <h2>Test Email</h2>
                            <p>
                                Verify your SMTP configuration.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="email-card-body">

                    <form
                        action="{{ route('admin.email-settings.test') }}"
                        method="POST"
                        id="testEmailForm"
                    >
                        @csrf

                        <div class="email-form-group">
                            <label for="test_email">
                                Test Email Address
                                <span>*</span>
                            </label>

                            <div class="email-input-wrap">
                                <i class="bi bi-envelope-at"></i>

                                <input
                                    type="email"
                                    id="test_email"
                                    name="test_email"
                                    class="email-form-control"
                                    value="{{ old('test_email') }}"
                                    placeholder="you@example.com"
                                    required
                                >
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="email-btn email-btn-secondary email-test-button"
                            id="testEmailButton"
                        >
                            <i class="bi bi-send"></i>
                            <span>Send Test Email</span>
                        </button>
                    </form>

                </div>

            </div>


            {{-- SMTP Guide --}}
            <div class="dashboard-panel email-card email-guide-card">

                <div class="email-card-header">
                    <div class="email-card-heading">
                        <div class="email-card-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div>
                            <h2>SMTP Guide</h2>
                            <p>
                                Common configuration values.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="email-card-body">

                    <div class="smtp-guide-list">

                        <div class="smtp-guide-item">
                            <div>
                                <strong>Gmail</strong>
                                <span>smtp.gmail.com</span>
                            </div>

                            <b>587 / TLS</b>
                        </div>

                        <div class="smtp-guide-item">
                            <div>
                                <strong>Outlook</strong>
                                <span>smtp.office365.com</span>
                            </div>

                            <b>587 / TLS</b>
                        </div>

                        <div class="smtp-guide-item">
                            <div>
                                <strong>Mailgun</strong>
                                <span>smtp.mailgun.org</span>
                            </div>

                            <b>587 / TLS</b>
                        </div>

                        <div class="smtp-guide-item">
                            <div>
                                <strong>SendGrid</strong>
                                <span>smtp.sendgrid.net</span>
                            </div>

                            <b>587 / TLS</b>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Security Notice --}}
            <div class="email-security-notice">
                <div class="email-security-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <strong>Security Notice</strong>

                    <p>
                        SMTP passwords are encrypted before being stored in the database.
                    </p>
                </div>
            </div>

        </aside>

    </div>

</div>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Email settings loaded');
});
</script>
@endpush