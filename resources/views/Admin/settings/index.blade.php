@extends('layout.admin.master')

@section('title', 'Settings')

@section('content')

@php
    $setting = fn (string $key, mixed $default = '') =>
        $settings[$key]->value ?? $default;

    $enabled = fn (string $key, bool $default = false) =>
        filter_var(
            $setting($key, $default ? '1' : '0'),
            FILTER_VALIDATE_BOOLEAN
        );

    $checkedMethods = json_decode(
        $setting('payment_methods', '[]'),
        true
    ) ?: [];
@endphp

<div class="dashboard-section settings-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="dashboard-panel mb-4">

        <div class="panel-header mb-0">

            <div>
                <h5 class="mb-1">
                    Profile Settings
                </h5>

                <p class="text-muted mb-0 small">
                    Personalize your admin interface theme
                </p>
            </div>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>
                Please review the settings form.
            </strong>
        </div>
    @endif


    {{-- Existing theme controls intentionally kept unchanged. --}}

    {{-- =========================================================
         THEME MODE
    ========================================================== --}}

    <div class="dashboard-panel mb-4">

        <div class="panel-header">
            <h5>
                Theme Mode
            </h5>
        </div>

        <p class="text-muted mb-3">
            Choose your preferred mode. Your choice is saved on this browser.
        </p>

        <div class="row g-3">

            <div class="col-12 col-md-6">

                <button
                    type="button"
                    class="btn btn-light border w-100 text-start p-3 theme-select-btn"
                    data-theme-target="light"
                >

                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <strong class="d-block">
                                Light Mode
                            </strong>

                            <small class="text-muted">
                                Classic bright interface
                            </small>
                        </div>

                        <i class="bi bi-sun fs-5"></i>

                    </div>

                </button>

            </div>


            <div class="col-12 col-md-6">

                <button
                    type="button"
                    class="btn btn-light border w-100 text-start p-3 theme-select-btn"
                    data-theme-target="dark"
                >

                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <strong class="d-block">
                                Dark Mode
                            </strong>

                            <small class="text-muted">
                                Low-light interface
                            </small>
                        </div>

                        <i class="bi bi-moon-stars fs-5"></i>

                    </div>

                </button>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SETTINGS FORM
    ========================================================== --}}

    <form
        id="settingsForm"
        method="POST"
        action="{{ route('admin.settings.update') }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        {{-- =====================================================
             FORM HEADER
        ====================================================== --}}

        <div class="settings-form-header dashboard-panel mb-4">

            <div>

                <span class="settings-eyebrow">
                    Platform configuration
                </span>

                <h5>
                    Global settings
                </h5>

                <p class="text-muted mb-0">
                    Manage how SecondBook behaves across the marketplace.
                </p>

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check-circle me-2"></i>
                Save settings
            </button>

        </div>


        {{-- =====================================================
             GENERAL
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        General
                    </span>

                    <h5>
                        Brand and location
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Site name
                    </label>

                    <input
                        name="site_name"
                        class="form-control"
                        value="{{ old('site_name', $setting('site_name', 'SecondBook')) }}"
                        required
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Support email
                    </label>

                    <input
                        type="email"
                        name="support_email"
                        class="form-control"
                        value="{{ old('support_email', $setting('support_email')) }}"
                    >

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Site description
                    </label>

                    <textarea
                        name="site_description"
                        class="form-control"
                        rows="2"
                    >{{ old('site_description', $setting('site_description')) }}</textarea>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Support phone
                    </label>

                    <input
                        name="support_phone"
                        class="form-control"
                        value="{{ old('support_phone', $setting('support_phone')) }}"
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Address
                    </label>

                    <input
                        name="address"
                        class="form-control"
                        value="{{ old('address', $setting('address')) }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Country
                    </label>

                    <input
                        name="country"
                        class="form-control"
                        value="{{ old('country', $setting('country')) }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        City
                    </label>

                    <input
                        name="city"
                        class="form-control"
                        value="{{ old('city', $setting('city')) }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Currency
                    </label>

                    <input
                        name="currency"
                        class="form-control"
                        value="{{ old('currency', $setting('currency', 'USD')) }}"
                        required
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Timezone
                    </label>

                    <select
                        name="timezone"
                        class="form-select"
                    >

                        @foreach(timezone_identifiers_list() as $timezone)

                            <option
                                value="{{ $timezone }}"
                                @selected(
                                    old(
                                        'timezone',
                                        $setting('timezone', config('app.timezone'))
                                    ) === $timezone
                                )
                            >
                                {{ $timezone }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Logo
                    </label>

                    <input
                        type="file"
                        name="logo"
                        class="form-control"
                        accept="image/*"
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Favicon
                    </label>

                    <input
                        type="file"
                        name="favicon"
                        class="form-control"
                        accept="image/*"
                    >

                </div>


                <div class="col-12 col-md-6 settings-switches">

                    <label class="settings-switch">

                        <input
                            type="checkbox"
                            name="maintenance_mode"
                            value="1"
                            @checked($enabled('maintenance_mode'))
                        >

                        <span>
                            Maintenance mode
                        </span>

                    </label>

                </div>

            </div>

        </section>


        {{-- =====================================================
             STORE
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Store
                    </span>

                    <h5>
                        Marketplace behavior
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                @foreach([
                    'marketplace_enabled' => 'Marketplace enabled',
                    'user_registration_enabled' => 'User registration enabled',
                    'seller_registration_enabled' => 'Seller registration enabled',
                    'seller_approval_required' => 'Seller approval required',
                    'book_approval_required' => 'Book approval required',
                    'reviews_enabled' => 'Reviews enabled',
                    'stock_management_enabled' => 'Stock management enabled',
                ] as $key => $label)

                    <div class="col-12 col-md-6 col-xl-4">

                        <label class="settings-switch">

                            <input
                                type="checkbox"
                                name="{{ $key }}"
                                value="1"
                                @checked($enabled($key, true))
                            >

                            <span>
                                {{ $label }}
                            </span>

                        </label>

                    </div>

                @endforeach


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Minimum order amount
                    </label>

                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        name="minimum_order_amount"
                        class="form-control"
                        value="{{ old('minimum_order_amount', $setting('minimum_order_amount', '0')) }}"
                    >

                </div>

            </div>

        </section>


        {{-- =====================================================
             ORDERS
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Orders
                    </span>

                    <h5>
                        Order behavior
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Default order status
                    </label>

                    <select
                        name="default_order_status"
                        class="form-select"
                    >

                        @foreach([
                            'pending',
                            'processing',
                            'shipped',
                            'delivered',
                            'cancelled'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected($setting('default_order_status', 'pending') === $status)
                            >
                                {{ ucfirst($status) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Order number format
                    </label>

                    <input
                        name="order_number_format"
                        class="form-control"
                        value="{{ old('order_number_format', $setting('order_number_format', 'SB-{YYYY}-{####}')) }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Cancel order period (hours)
                    </label>

                    <input
                        type="number"
                        min="0"
                        name="cancel_order_period"
                        class="form-control"
                        value="{{ old('cancel_order_period', $setting('cancel_order_period', '24')) }}"
                    >

                </div>


                @foreach([
                    'auto_cancel_pending_orders' => 'Auto cancel pending orders',
                    'customer_order_notifications' => 'Customer order notifications',
                ] as $key => $label)

                    <div class="col-12 col-md-6">

                        <label class="settings-switch">

                            <input
                                type="checkbox"
                                name="{{ $key }}"
                                value="1"
                                @checked($enabled($key, true))
                            >

                            <span>
                                {{ $label }}
                            </span>

                        </label>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- =====================================================
             PAYMENTS
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Payments
                    </span>

                    <h5>
                        Payment preferences
                    </h5>

                    <small class="text-muted">
                        Uses the payment methods already defined by the project.
                    </small>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Default payment method
                    </label>

                    <select
                        name="default_payment_method"
                        class="form-select"
                    >

                        @foreach($paymentMethods as $method)

                            <option
                                value="{{ $method }}"
                                @selected(
                                    $setting('default_payment_method', 'cash_on_delivery') === $method
                                )
                            >
                                {{ ucwords(str_replace('_', ' ', $method)) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-12 col-md-6">

                    <label class="settings-switch">

                        <input
                            type="checkbox"
                            name="payments_enabled"
                            value="1"
                            @checked($enabled('payments_enabled', true))
                        >

                        <span>
                            Payments enabled
                        </span>

                    </label>

                </div>


                <div class="col-12">

                    <div class="settings-methods">

                        @foreach($paymentMethods as $method)

                            <label class="settings-switch">

                                <input
                                    type="checkbox"
                                    name="payment_methods[]"
                                    value="{{ $method }}"
                                    @checked(
                                        in_array($method, $checkedMethods, true)
                                        || (
                                            !$checkedMethods
                                            && $method === 'cash_on_delivery'
                                        )
                                    )
                                >

                                <span>
                                    {{ ucwords(str_replace('_', ' ', $method)) }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             SHIPPING
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Shipping
                    </span>

                    <h5>
                        Delivery defaults
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Default shipping fee
                    </label>

                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        name="default_shipping_fee"
                        class="form-control"
                        value="{{ $setting('default_shipping_fee', '0') }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Free shipping threshold
                    </label>

                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        name="free_shipping_threshold"
                        class="form-control"
                        value="{{ $setting('free_shipping_threshold', '0') }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Default country
                    </label>

                    <input
                        name="default_country"
                        class="form-control"
                        value="{{ $setting('default_country', $setting('country')) }}"
                    >

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Estimated delivery message
                    </label>

                    <input
                        name="estimated_delivery_message"
                        class="form-control"
                        value="{{ $setting('estimated_delivery_message', 'Delivered within 3-5 business days') }}"
                    >

                </div>


                <div class="col-12">

                    <label class="settings-switch">

                        <input
                            type="checkbox"
                            name="shipping_enabled"
                            value="1"
                            @checked($enabled('shipping_enabled', true))
                        >

                        <span>
                            Shipping enabled
                        </span>

                    </label>

                </div>

            </div>

        </section>


        {{-- =====================================================
             SEO
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        SEO
                    </span>

                    <h5>
                        Search visibility
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Meta title
                    </label>

                    <input
                        name="meta_title"
                        class="form-control"
                        value="{{ $setting('meta_title') }}"
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Keywords
                    </label>

                    <input
                        name="keywords"
                        class="form-control"
                        value="{{ $setting('keywords') }}"
                    >

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Meta description
                    </label>

                    <textarea
                        name="meta_description"
                        rows="2"
                        class="form-control"
                    >{{ $setting('meta_description') }}</textarea>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Open Graph image
                    </label>

                    <input
                        type="file"
                        name="open_graph_image"
                        class="form-control"
                        accept="image/*"
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="settings-switch">

                        <input
                            type="checkbox"
                            name="search_engine_indexing"
                            value="1"
                            @checked($enabled('search_engine_indexing', true))
                        >

                        <span>
                            Allow search engine indexing
                        </span>

                    </label>

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Google Analytics ID
                    </label>

                    <input
                        name="google_analytics_id"
                        class="form-control"
                        value="{{ $setting('google_analytics_id') }}"
                    >

                </div>


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        Search Console verification
                    </label>

                    <input
                        name="google_search_console_verification"
                        class="form-control"
                        value="{{ $setting('google_search_console_verification') }}"
                    >

                </div>

            </div>

        </section>


        {{-- =====================================================
             SOCIAL
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Social
                    </span>

                    <h5>
                        Social profiles
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                @foreach([
                    'facebook',
                    'instagram',
                    'tiktok',
                    'youtube',
                ] as $network)

                    <div class="col-12 col-md-6">

                        <label class="form-label">
                            {{ ucfirst($network) }}
                        </label>

                        <input
                            type="url"
                            name="{{ $network }}"
                            class="form-control"
                            value="{{ $setting($network) }}"
                        >

                    </div>

                @endforeach


                <div class="col-12 col-md-6">

                    <label class="form-label">
                        WhatsApp
                    </label>

                    <input
                        name="whatsapp"
                        class="form-control"
                        value="{{ $setting('whatsapp') }}"
                    >

                </div>

            </div>

        </section>


        {{-- =====================================================
             SECURITY
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Security
                    </span>

                    <h5>
                        Security policy
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Login attempt limit
                    </label>

                    <input
                        type="number"
                        min="1"
                        max="20"
                        name="login_attempt_limit"
                        class="form-control"
                        value="{{ $setting('login_attempt_limit', '5') }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Session lifetime (minutes)
                    </label>

                    <input
                        type="number"
                        min="1"
                        name="session_lifetime"
                        class="form-control"
                        value="{{ $setting('session_lifetime', '120') }}"
                    >

                </div>


                <div class="col-12 col-md-4">

                    <label class="form-label">
                        Minimum password length
                    </label>

                    <input
                        type="number"
                        min="8"
                        name="minimum_password_length"
                        class="form-control"
                        value="{{ $setting('minimum_password_length', '8') }}"
                    >

                </div>


                @foreach([
                    'two_factor_authentication_enabled' => 'Two-factor authentication enabled',
                    'admin_session_security' => 'Admin session security',
                ] as $key => $label)

                    <div class="col-12 col-md-6">

                        <label class="settings-switch">

                            <input
                                type="checkbox"
                                name="{{ $key }}"
                                value="1"
                                @checked($enabled($key))
                            >

                            <span>
                                {{ $label }}
                            </span>

                        </label>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- =====================================================
             LEGAL
        ====================================================== --}}

        <section class="dashboard-panel settings-card mb-4">

            <div class="settings-section-heading">

                <div>

                    <span class="settings-eyebrow">
                        Legal
                    </span>

                    <h5>
                        Policy content
                    </h5>

                </div>

            </div>


            <div class="row g-3">

                @foreach([
                    'privacy_policy' => 'Privacy Policy',
                    'terms_conditions' => 'Terms & Conditions',
                    'refund_policy' => 'Refund Policy',
                    'shipping_policy' => 'Shipping Policy',
                    'cookie_notice' => 'Cookie Notice',
                ] as $key => $label)

                    <div class="col-12 col-md-6">

                        <label class="form-label">
                            {{ $label }}
                        </label>

                        <textarea
                            name="{{ $key }}"
                            rows="4"
                            class="form-control"
                        >{{ $setting($key) }}</textarea>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- =====================================================
             SAVE BAR
        ====================================================== --}}

        <div class="settings-save-bar dashboard-panel">

            <span class="text-muted small">
                Changes apply after saving.
            </span>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check-circle me-2"></i>
                Save all settings
            </button>

        </div>

    </form>

</div>


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('settingsForm');

    if (!form) {
        return;
    }

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        const submitButtons = form.querySelectorAll(
            'button[type="submit"]'
        );

        const formData = new FormData(form);

        submitButtons.forEach(function (button) {
            button.disabled = true;

            button.dataset.originalHtml =
                button.innerHTML;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                ></span>
                Saving...
            `;
        });

        try {

            const response = await fetch(
                form.action,
                {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            );

            const data = await response.json();

            if (!response.ok) {

                let errorMessage =
                    'Please review the settings form.';

                if (data.errors) {

                    const messages = [];

                    Object.values(data.errors).forEach(
                        function (errors) {

                            errors.forEach(
                                function (message) {
                                    messages.push(message);
                                }
                            );

                        }
                    );

                    if (messages.length) {
                        errorMessage = messages.join('<br>');
                    }
                }

                throw new Error(errorMessage);
            }

            Swal.fire({
                icon: 'success',
                title: 'Settings Saved',
                text: data.message ||
                    'Settings updated successfully.',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });

        } catch (error) {

            Swal.fire({
                icon: 'error',
                title: 'Save Failed',
                html: error.message ||
                    'Something went wrong while saving settings.',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });

        } finally {

            submitButtons.forEach(function (button) {

                button.disabled = false;

                if (button.dataset.originalHtml) {
                    button.innerHTML =
                        button.dataset.originalHtml;
                }

            });

        }

    });

});
</script>
@endpush

@endsection

