@extends('layout.admin.master')

@section('title', 'Settings')

@section('content')

@php
    /* =========================================================
       DATA HELPERS
    ========================================================= */

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

    $html = fn (string $s) =>
        new \Illuminate\Support\HtmlString($s);


    /* =========================================================
       UI BUILDERS
    ========================================================= */

    /* Section heading */
    $head = fn (
        string $icon,
        string $tone,
        string $title,
        string $desc
    ) => $html(
        '<div class="settings-card-header">
            <div class="settings-card-heading">
                <span class="sx-chip sx-tone-' . $tone . '">
                    <i class="bi ' . $icon . '"></i>
                </span>

                <div>
                    <h2>' . e($title) . '</h2>
                    <p>' . e($desc) . '</p>
                </div>
            </div>
        </div>'
    );


    /* Text / number / URL / email field */
    $field = function (array $f) use ($setting, $html) {
        $name = $f['name'];

        $value = old(
            $name,
            $setting($name, $f['default'] ?? '')
        );

        $hint = isset($f['hint'])
            ? ' <small>(' . e($f['hint']) . ')</small>'
            : '';

        return $html(
            '<div class="settings-field ' . ($f['class'] ?? '') . '">

                <label for="' . $name . '">
                    ' . e($f['label']) . $hint . '
                </label>

                <div class="sx-input">
                    <i class="bi ' . $f['icon'] . '"></i>

                    <input
                        id="' . $name . '"
                        type="' . ($f['type'] ?? 'text') . '"
                        name="' . $name . '"
                        value="' . e($value) . '"
                        ' . ($f['attrs'] ?? '') . '
                    >
                </div>

            </div>'
        );
    };


    /* Textarea */
    $area = function (array $f) use ($setting, $html) {
        $name = $f['name'];

        return $html(
            '<div class="settings-field ' .
                ($f['class'] ?? 'settings-field-full') .
            '">

                <label for="' . $name . '">
                    ' . e($f['label']) . '
                </label>

                <textarea
                    class="sx-area"
                    id="' . $name . '"
                    name="' . $name . '"
                    rows="' . ($f['rows'] ?? 3) . '"
                >' . e(old($name, $setting($name))) . '</textarea>

            </div>'
        );
    };


    /* File upload */
    $file = fn (array $f) => $html(
        '<div class="settings-field ' . ($f['class'] ?? '') . '">

            <label for="' . $f['name'] . '">
                ' . e($f['label']) . '
            </label>

            <div class="sx-file" data-file>

                <input
                    class="sx-file-input"
                    id="' . $f['name'] . '"
                    type="file"
                    name="' . $f['name'] . '"
                    accept="image/*"
                >

                <label
                    class="sx-file-drop"
                    for="' . $f['name'] . '"
                >

                    <span class="sx-file-thumb">
                        <i class="bi ' . $f['icon'] . '"></i>
                        <img alt="" hidden>
                    </span>

                    <span class="sx-file-text">
                        <strong>Choose an image</strong>

                        <span
                            class="sx-file-name"
                            data-file-name
                        >
                            Drop a file here or browse
                        </span>
                    </span>

                    <span class="sx-file-btn">
                        <i class="bi bi-upload"></i>
                        Browse
                    </span>

                </label>

            </div>

        </div>'
    );


    /* Toggle card */
    $toggle = function (
        string $key,
        array $t
    ) use ($enabled, $html) {

        $checked = $enabled(
            $key,
            $t['default'] ?? true
        )
            ? 'checked'
            : '';

        return $html(
            '<div class="settings-toggle-card">

                <div class="settings-toggle-info">

                    <span class="sx-chip sx-chip-sm sx-tone-' .
                        $t['tone'] .
                    '">
                        <i class="bi ' . $t['icon'] . '"></i>
                    </span>

                    <div>
                        <strong>' . e($t['label']) . '</strong>
                        <small>' . e($t['description']) . '</small>
                    </div>

                </div>

                <label class="settings-toggle">
                    <input
                        type="checkbox"
                        name="' . $key . '"
                        value="1"
                        ' . $checked . '
                    >
                    <i></i>
                </label>

            </div>'
        );
    };


    /* Side navigation rail */
    $rail = [
        ['sec-appearance', 'bi-palette',             'violet', 'Appearance'],
        ['sec-general',    'bi-globe',               'blue',   'General'],
        ['sec-store',      'bi-shop',                'green',  'Store'],
        ['sec-orders',     'bi-bag-check',           'amber',  'Orders'],
        ['sec-payments',   'bi-credit-card-2-front', 'blue',   'Payments'],
        ['sec-shipping',   'bi-truck',               'cyan',   'Shipping'],
        ['sec-seo',        'bi-search',              'violet', 'SEO'],
        ['sec-social',     'bi-share',               'rose',   'Social'],
        ['sec-security',   'bi-shield-lock',         'red',    'Security'],
        ['sec-legal',      'bi-file-earmark-text',   'slate',  'Legal'],
    ];
@endphp


<div class="dashboard-section settings-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}

    <div class="settings-hero">

        <div class="settings-hero-content">

            <div class="settings-hero-crumb">
                <i class="bi bi-sliders"></i>
                Administration
            </div>

            <h1>Settings</h1>

            <p>
                Configure your SecondBook marketplace, appearance,
                payments, shipping, security and platform preferences
                from one place.
            </p>

        </div>

        <div class="settings-hero-icon">
            <i class="bi bi-gear-fill"></i>
        </div>

    </div>


    {{-- =====================================================
         ALERTS
    ====================================================== --}}

    @if(session('success'))
        <div class="settings-alert settings-alert-success">

            <i class="bi bi-check-circle-fill"></i>

            <div class="settings-alert-body">
                <span>{{ session('success') }}</span>
            </div>

        </div>
    @endif


    @if($errors->any())
        <div class="settings-alert settings-alert-error">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div class="settings-alert-body">

                <strong>
                    Please review the settings form.
                </strong>

                <span>
                    {{ $errors->first() }}
                </span>

            </div>

        </div>
    @endif


    <div class="sx-layout">

        {{-- =================================================
             SIDE RAIL
        ================================================== --}}

        <nav
            class="sx-rail"
            aria-label="Settings sections"
        >

            <div class="sx-rail-title">
                Sections
            </div>

            @foreach($rail as [$id, $icon, $tone, $label])
                <a
                    href="#{{ $id }}"
                    class="sx-tone-{{ $tone }}"
                    data-rail="{{ $id }}"
                >
                    <i class="bi {{ $icon }}"></i>
                    <span>{{ $label }}</span>
                </a>
            @endforeach

        </nav>


        {{-- =================================================
             MAIN CONTENT
        ================================================== --}}

        <div class="sx-content">


            {{-- =================================================
                 THEME
            ================================================== --}}

            <section
                class="settings-card settings-theme-card"
                id="sec-appearance"
            >

                {{
                    $head(
                        'bi-palette',
                        'violet',
                        'Theme mode',
                        'Choose how the admin interface appears on this browser.'
                    )
                }}

                <div class="settings-theme-options">

                    {{-- LIGHT MODE --}}
                    <button
                        type="button"
                        class="settings-theme-option theme-select-btn"
                        data-theme-target="light"
                    >

                        <div class="settings-theme-preview settings-theme-preview-light">

                            <div class="tp-side"></div>

                            <div class="tp-main">
                                <div class="tp-bar"></div>
                                <div class="tp-line"></div>
                                <div class="tp-line"></div>
                                <div class="tp-line"></div>
                            </div>

                        </div>

                        <div class="settings-theme-info">

                            <div>
                                <strong>Light mode</strong>
                                <small>
                                    Classic bright interface
                                </small>
                            </div>

                            <div class="settings-theme-check">
                                <i class="bi bi-sun"></i>
                            </div>

                        </div>

                    </button>


                    {{-- DARK MODE --}}
                    <button
                        type="button"
                        class="settings-theme-option theme-select-btn"
                        data-theme-target="dark"
                    >

                        <div class="settings-theme-preview settings-theme-preview-dark">

                            <div class="tp-side"></div>

                            <div class="tp-main">
                                <div class="tp-bar"></div>
                                <div class="tp-line"></div>
                                <div class="tp-line"></div>
                                <div class="tp-line"></div>
                            </div>

                        </div>

                        <div class="settings-theme-info">

                            <div>
                                <strong>Dark mode</strong>
                                <small>
                                    Comfortable low-light interface
                                </small>
                            </div>

                            <div class="settings-theme-check">
                                <i class="bi bi-moon-stars"></i>
                            </div>

                        </div>

                    </button>

                </div>

            </section>


            {{-- =================================================
                 SETTINGS FORM
            ================================================== --}}

            <form
                id="settingsForm"
                method="POST"
                action="{{ route('admin.settings.update') }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- FORM SAVE HEADER --}}

                <div class="settings-save-header">

                    <div>
                        <h2>Global settings</h2>

                        <p>
                            Manage how SecondBook behaves across
                            the marketplace.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="settings-primary-btn"
                    >
                        <i class="bi bi-check2-circle"></i>
                        <span>Save settings</span>
                    </button>

                </div>


                {{-- =================================================
                     GENERAL
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-general"
                >

                    {{
                        $head(
                            'bi-globe',
                            'blue',
                            'Brand and location',
                            'Configure the basic identity and location of your marketplace.'
                        )
                    }}

                    <div class="settings-grid">

                        {{ $field([
                            'name' => 'site_name',
                            'label' => 'Site name',
                            'icon' => 'bi-fonts',
                            'default' => 'SecondBook',
                            'attrs' => 'required'
                        ]) }}

                        {{ $field([
                            'name' => 'support_email',
                            'label' => 'Support email',
                            'icon' => 'bi-envelope',
                            'type' => 'email'
                        ]) }}

                        {{ $area([
                            'name' => 'site_description',
                            'label' => 'Site description'
                        ]) }}

                        {{ $field([
                            'name' => 'support_phone',
                            'label' => 'Support phone',
                            'icon' => 'bi-telephone'
                        ]) }}

                        {{ $field([
                            'name' => 'address',
                            'label' => 'Address',
                            'icon' => 'bi-geo-alt'
                        ]) }}

                        {{ $field([
                            'name' => 'country',
                            'label' => 'Country',
                            'icon' => 'bi-flag'
                        ]) }}

                        {{ $field([
                            'name' => 'city',
                            'label' => 'City',
                            'icon' => 'bi-building'
                        ]) }}

                        {{ $field([
                            'name' => 'currency',
                            'label' => 'Currency',
                            'icon' => 'bi-cash-coin',
                            'default' => 'USD',
                            'attrs' => 'required'
                        ]) }}


                        {{-- TIMEZONE --}}

                        <div class="settings-field">

                            <label for="timezone">
                                Timezone
                            </label>

                            <div class="sx-select">

                                <i class="bi bi-clock"></i>

                                <select
                                    id="timezone"
                                    name="timezone"
                                >

                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option
                                            value="{{ $timezone }}"
                                            @selected(
                                                old(
                                                    'timezone',
                                                    $setting(
                                                        'timezone',
                                                        config('app.timezone')
                                                    )
                                                ) === $timezone
                                            )
                                        >
                                            {{ $timezone }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- LOGO --}}

                        {{ $file([
                            'name' => 'logo',
                            'label' => 'Logo',
                            'icon' => 'bi-image'
                        ]) }}


                        {{-- FAVICON --}}

                        {{ $file([
                            'name' => 'favicon',
                            'label' => 'Favicon',
                            'icon' => 'bi-bookmark-star'
                        ]) }}


                        {{-- MAINTENANCE MODE --}}

                        <div class="settings-field settings-field-full">

                            {{
                                $toggle(
                                    'maintenance_mode',
                                    [
                                        'label' => 'Maintenance mode',
                                        'description' => 'Temporarily disable public marketplace access.',
                                        'icon' => 'bi-tools',
                                        'tone' => 'amber',
                                        'default' => false,
                                    ]
                                )
                            }}

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     STORE
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-store"
                >

                    {{
                        $head(
                            'bi-shop',
                            'green',
                            'Marketplace behavior',
                            'Control registrations, approvals, reviews and inventory behavior.'
                        )
                    }}

                    <div class="settings-toggle-grid">

                        @foreach([
                            'marketplace_enabled' => [
                                'label' => 'Marketplace enabled',
                                'description' => 'Allow customers to browse and purchase books.',
                                'icon' => 'bi-shop-window'
                            ],
                            'user_registration_enabled' => [
                                'label' => 'User registration enabled',
                                'description' => 'Allow new customer accounts to be created.',
                                'icon' => 'bi-person-plus'
                            ],
                            'seller_registration_enabled' => [
                                'label' => 'Seller registration enabled',
                                'description' => 'Allow users to apply as marketplace sellers.',
                                'icon' => 'bi-person-badge'
                            ],
                            'seller_approval_required' => [
                                'label' => 'Seller approval required',
                                'description' => 'Require admin approval before sellers become active.',
                                'icon' => 'bi-person-check'
                            ],
                            'book_approval_required' => [
                                'label' => 'Book approval required',
                                'description' => 'Require approval before seller books become visible.',
                                'icon' => 'bi-book'
                            ],
                            'reviews_enabled' => [
                                'label' => 'Reviews enabled',
                                'description' => 'Allow customers to leave reviews for purchased books.',
                                'icon' => 'bi-star'
                            ],
                            'stock_management_enabled' => [
                                'label' => 'Stock management enabled',
                                'description' => 'Track book stock and inventory quantities.',
                                'icon' => 'bi-box-seam'
                            ],
                        ] as $key => $item)

                            {{
                                $toggle(
                                    $key,
                                    $item + [
                                        'tone' => 'green',
                                        'default' => true
                                    ]
                                )
                            }}

                        @endforeach

                    </div>

                    <div class="settings-divider"></div>

                    <div class="settings-grid settings-grid-single">

                        {{ $field([
                            'name' => 'minimum_order_amount',
                            'label' => 'Minimum order amount',
                            'icon' => 'bi-cash-stack',
                            'type' => 'number',
                            'default' => '0',
                            'attrs' => 'min="0" step="0.01"',
                            'class' => 'settings-field-half'
                        ]) }}

                    </div>

                </section>


                {{-- =================================================
                     ORDERS
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-orders"
                >

                    {{
                        $head(
                            'bi-bag-check',
                            'amber',
                            'Order behavior',
                            'Define default order statuses, numbering and cancellation rules.'
                        )
                    }}

                    <div class="settings-grid">

                        {{-- DEFAULT ORDER STATUS --}}

                        <div class="settings-field">

                            <label for="default_order_status">
                                Default order status
                            </label>

                            <div class="sx-select">

                                <i class="bi bi-circle-half"></i>

                                <select
                                    id="default_order_status"
                                    name="default_order_status"
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
                                            @selected(
                                                old(
                                                    'default_order_status',
                                                    $setting(
                                                        'default_order_status',
                                                        'pending'
                                                    )
                                                ) === $status
                                            )
                                        >
                                            {{ ucfirst($status) }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{ $field([
                            'name' => 'order_number_format',
                            'label' => 'Order number format',
                            'icon' => 'bi-hash',
                            'default' => 'SB-{YYYY}-{####}'
                        ]) }}


                        {{ $field([
                            'name' => 'cancel_order_period',
                            'label' => 'Cancel order period',
                            'hint' => 'hours',
                            'icon' => 'bi-hourglass-split',
                            'type' => 'number',
                            'default' => '24',
                            'attrs' => 'min="0"'
                        ]) }}

                    </div>

                    <div class="settings-divider"></div>

                    <div class="settings-toggle-grid">

                        @foreach([
                            'auto_cancel_pending_orders' => [
                                'label' => 'Auto cancel pending orders',
                                'description' => 'Automatically cancel orders that remain pending.',
                                'icon' => 'bi-arrow-repeat'
                            ],
                            'customer_order_notifications' => [
                                'label' => 'Customer order notifications',
                                'description' => 'Send order updates and notifications to customers.',
                                'icon' => 'bi-bell'
                            ],
                        ] as $key => $item)

                            {{
                                $toggle(
                                    $key,
                                    $item + [
                                        'tone' => 'amber',
                                        'default' => true
                                    ]
                                )
                            }}

                        @endforeach

                    </div>

                </section>


                {{-- =================================================
                     PAYMENTS
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-payments"
                >

                    {{
                        $head(
                            'bi-credit-card-2-front',
                            'blue',
                            'Payment preferences',
                            'Choose the payment methods available throughout checkout.'
                        )
                    }}

                    <div class="settings-grid">

                        {{-- DEFAULT PAYMENT METHOD --}}

                        <div class="settings-field">

                            <label for="default_payment_method">
                                Default payment method
                            </label>

                            <div class="sx-select">

                                <i class="bi bi-wallet2"></i>

                                <select
                                    id="default_payment_method"
                                    name="default_payment_method"
                                >

                                    @foreach($paymentMethods as $method)

                                        <option
                                            value="{{ $method }}"
                                            @selected(
                                                old(
                                                    'default_payment_method',
                                                    $setting(
                                                        'default_payment_method',
                                                        'cash_on_delivery'
                                                    )
                                                ) === $method
                                            )
                                        >
                                            {{ ucwords(str_replace('_', ' ', $method)) }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- PAYMENTS ENABLED --}}

                        <div class="settings-field settings-field-toggle">

                            {{
                                $toggle(
                                    'payments_enabled',
                                    [
                                        'label' => 'Payments enabled',
                                        'description' => 'Allow customers to complete payments at checkout.',
                                        'icon' => 'bi-credit-card',
                                        'tone' => 'green',
                                        'default' => true,
                                    ]
                                )
                            }}

                        </div>

                    </div>


                    {{-- AVAILABLE PAYMENT METHODS --}}

                    <div class="settings-subsection">

                        <div class="settings-subsection-heading">

                            <div>
                                <strong>
                                    Available payment methods
                                </strong>

                                <small>
                                    Select the methods customers can use.
                                </small>
                            </div>

                            <i class="bi bi-credit-card"></i>

                        </div>


                        <div class="settings-method-grid">

                            @foreach($paymentMethods as $method)

                                <label class="settings-method-option">

                                    <input
                                        type="checkbox"
                                        name="payment_methods[]"
                                        value="{{ $method }}"
                                        @checked(
                                            in_array(
                                                $method,
                                                $checkedMethods,
                                                true
                                            )
                                            ||
                                            (
                                                !$checkedMethods
                                                &&
                                                $method === 'cash_on_delivery'
                                            )
                                        )
                                    >

                                    <span class="settings-method-check">
                                        <i class="bi bi-check-lg"></i>
                                    </span>


                                    <span class="sx-chip sx-chip-sm sx-tone-blue">

                                        @if($method === 'cash_on_delivery')
                                            <i class="bi bi-cash-coin"></i>

                                        @elseif($method === 'credit_card')
                                            <i class="bi bi-credit-card"></i>

                                        @elseif($method === 'debit_card')
                                            <i class="bi bi-credit-card-2-back"></i>

                                        @elseif($method === 'paypal')
                                            <i class="bi bi-paypal"></i>

                                        @else
                                            <i class="bi bi-wallet2"></i>
                                        @endif

                                    </span>

                                    <span class="settings-method-text">
                                        {{ ucwords(str_replace('_', ' ', $method)) }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     SHIPPING
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-shipping"
                >

                    {{
                        $head(
                            'bi-truck',
                            'cyan',
                            'Delivery defaults',
                            'Configure shipping costs, thresholds and delivery messaging.'
                        )
                    }}

                    <div class="settings-grid">

                        {{ $field([
                            'name' => 'default_shipping_fee',
                            'label' => 'Default shipping fee',
                            'icon' => 'bi-box-seam',
                            'type' => 'number',
                            'default' => '0',
                            'attrs' => 'min="0" step="0.01"'
                        ]) }}

                        {{ $field([
                            'name' => 'free_shipping_threshold',
                            'label' => 'Free shipping threshold',
                            'icon' => 'bi-gift',
                            'type' => 'number',
                            'default' => '0',
                            'attrs' => 'min="0" step="0.01"'
                        ]) }}

                        {{ $field([
                            'name' => 'default_country',
                            'label' => 'Default country',
                            'icon' => 'bi-geo-alt',
                            'default' => $setting('country')
                        ]) }}

                        {{ $field([
                            'name' => 'estimated_delivery_message',
                            'label' => 'Estimated delivery message',
                            'icon' => 'bi-calendar-check',
                            'default' => 'Delivered within 3-5 business days',
                            'class' => 'settings-field-full'
                        ]) }}

                        <div class="settings-field settings-field-full">

                            {{
                                $toggle(
                                    'shipping_enabled',
                                    [
                                        'label' => 'Shipping enabled',
                                        'description' => 'Enable shipping calculations and delivery options.',
                                        'icon' => 'bi-truck',
                                        'tone' => 'cyan',
                                        'default' => true,
                                    ]
                                )
                            }}

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     SEO
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-seo"
                >

                    {{
                        $head(
                            'bi-search',
                            'violet',
                            'Search visibility',
                            'Configure metadata and search engine visibility for SecondBook.'
                        )
                    }}

                    <div class="settings-grid">

                        {{ $field([
                            'name' => 'meta_title',
                            'label' => 'Meta title',
                            'icon' => 'bi-card-heading'
                        ]) }}

                        {{ $field([
                            'name' => 'keywords',
                            'label' => 'Keywords',
                            'icon' => 'bi-tags'
                        ]) }}

                        {{ $area([
                            'name' => 'meta_description',
                            'label' => 'Meta description'
                        ]) }}

                        {{ $file([
                            'name' => 'open_graph_image',
                            'label' => 'Open Graph image',
                            'icon' => 'bi-share'
                        ]) }}


                        {{-- SEARCH ENGINE INDEXING --}}

                        <div class="settings-field">

                            <label>
                                Indexing
                            </label>

                            {{
                                $toggle(
                                    'search_engine_indexing',
                                    [
                                        'label' => 'Search engine indexing',
                                        'description' => 'Allow search engines to index the marketplace.',
                                        'icon' => 'bi-eye',
                                        'tone' => 'violet',
                                        'default' => true,
                                    ]
                                )
                            }}

                        </div>


                        {{ $field([
                            'name' => 'google_analytics_id',
                            'label' => 'Google Analytics ID',
                            'icon' => 'bi-bar-chart'
                        ]) }}

                        {{ $field([
                            'name' => 'google_search_console_verification',
                            'label' => 'Search Console verification',
                            'icon' => 'bi-google'
                        ]) }}

                    </div>

                </section>


                {{-- =================================================
                     SOCIAL
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-social"
                >

                    {{
                        $head(
                            'bi-share',
                            'rose',
                            'Social profiles',
                            'Connect the social channels displayed throughout the platform.'
                        )
                    }}

                    <div class="settings-grid">

                        {{ $field([
                            'name' => 'facebook',
                            'label' => 'Facebook',
                            'icon' => 'bi-facebook',
                            'type' => 'url'
                        ]) }}

                        {{ $field([
                            'name' => 'instagram',
                            'label' => 'Instagram',
                            'icon' => 'bi-instagram',
                            'type' => 'url'
                        ]) }}

                        {{ $field([
                            'name' => 'tiktok',
                            'label' => 'Tiktok',
                            'icon' => 'bi-tiktok',
                            'type' => 'url'
                        ]) }}

                        {{ $field([
                            'name' => 'youtube',
                            'label' => 'Youtube',
                            'icon' => 'bi-youtube',
                            'type' => 'url'
                        ]) }}

                        {{ $field([
                            'name' => 'whatsapp',
                            'label' => 'WhatsApp',
                            'icon' => 'bi-whatsapp'
                        ]) }}

                    </div>

                </section>


                {{-- =================================================
                     SECURITY
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-security"
                >

                    {{
                        $head(
                            'bi-shield-lock',
                            'red',
                            'Security policy',
                            'Define authentication limits and administrative session policies.'
                        )
                    }}

                    <div class="settings-grid">

                        {{ $field([
                            'name' => 'login_attempt_limit',
                            'label' => 'Login attempt limit',
                            'icon' => 'bi-shield-exclamation',
                            'type' => 'number',
                            'default' => '5',
                            'attrs' => 'min="1" max="20"'
                        ]) }}

                        {{ $field([
                            'name' => 'session_lifetime',
                            'label' => 'Session lifetime',
                            'hint' => 'minutes',
                            'icon' => 'bi-stopwatch',
                            'type' => 'number',
                            'default' => '120',
                            'attrs' => 'min="1"'
                        ]) }}

                        {{ $field([
                            'name' => 'minimum_password_length',
                            'label' => 'Minimum password length',
                            'icon' => 'bi-key',
                            'type' => 'number',
                            'default' => '8',
                            'attrs' => 'min="8"'
                        ]) }}

                    </div>


                    <div class="settings-divider"></div>


                    <div class="settings-toggle-grid">

                        @foreach([
                            'two_factor_authentication_enabled' => [
                                'label' => 'Two-factor authentication enabled',
                                'description' => 'Add an additional authentication layer to admin access.',
                                'icon' => 'bi-phone'
                            ],
                            'admin_session_security' => [
                                'label' => 'Admin session security',
                                'description' => 'Apply additional protection to administrator sessions.',
                                'icon' => 'bi-shield-check'
                            ],
                        ] as $key => $item)

                            {{
                                $toggle(
                                    $key,
                                    $item + [
                                        'tone' => 'red',
                                        'default' => false
                                    ]
                                )
                            }}

                        @endforeach

                    </div>

                </section>


                {{-- =================================================
                     LEGAL
                ================================================== --}}

                <section
                    class="settings-card settings-section"
                    id="sec-legal"
                >

                    {{
                        $head(
                            'bi-file-earmark-text',
                            'slate',
                            'Policy content',
                            'Manage the legal information displayed to customers and sellers.'
                        )
                    }}

                    <div class="settings-grid">

                        @foreach([
                            'privacy_policy' => 'Privacy Policy',
                            'terms_conditions' => 'Terms & Conditions',
                            'refund_policy' => 'Refund Policy',
                            'shipping_policy' => 'Shipping Policy',
                            'cookie_notice' => 'Cookie Notice',
                        ] as $key => $label)

                            {{
                                $area([
                                    'name' => $key,
                                    'label' => $label,
                                    'rows' => 5,
                                    'class' => 'settings-field-half'
                                ])
                            }}

                        @endforeach

                    </div>

                </section>


                {{-- =================================================
                     SAVE BAR
                ================================================== --}}

                <div
                    class="settings-save-bar"
                    id="settingsSaveBar"
                >

                    <div class="settings-save-info">

                        <span class="settings-save-dot"></span>

                        <div>

                            <strong id="saveStatusTitle">
                                All changes saved
                            </strong>

                            <small id="saveStatusText">
                                Changes apply after saving.
                            </small>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="settings-primary-btn"
                    >
                        <i class="bi bi-check2-circle"></i>
                        <span>Save all settings</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('settingsForm');

    if (!form) {
        return;
    }


    /* =========================================================
       THEME BUTTONS
    ========================================================= */

    const themeButtons = document.querySelectorAll('.theme-select-btn');
    const root = document.documentElement;

    const markTheme = function () {

        const current = root.getAttribute('data-theme') || 'light';

        themeButtons.forEach(function (button) {
            button.classList.toggle(
                'active',
                button.dataset.themeTarget === current
            );
        });
    };

    markTheme();

    new MutationObserver(markTheme).observe(root, {
        attributes: true,
        attributeFilter: ['data-theme']
    });

    themeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setTimeout(markTheme, 60);
        });
    });


    /* =========================================================
       FILE INPUTS
       Preview + file name + drag & drop
    ========================================================= */

    document.querySelectorAll('[data-file]').forEach(function (box) {

        const input = box.querySelector('input[type="file"]');
        const nameEl = box.querySelector('[data-file-name]');
        const img = box.querySelector('.sx-file-thumb img');
        const icon = box.querySelector('.sx-file-thumb i');

        const idle = nameEl.textContent;


        const render = function () {

            const file = input.files && input.files[0];

            box.classList.toggle('has-file', !!file);

            if (!file) {
                nameEl.textContent = idle;
                img.hidden = true;
                icon.hidden = false;
                return;
            }

            nameEl.textContent =
                file.name +
                ' — ' +
                Math.max(1, Math.round(file.size / 1024)) +
                ' KB';

            if (file.type.startsWith('image/')) {

                const reader = new FileReader();

                reader.onload = function (e) {
                    img.src = e.target.result;
                    img.hidden = false;
                    icon.hidden = true;
                };

                reader.readAsDataURL(file);
            }
        };

        input.addEventListener('change', render);

        ['dragenter', 'dragover'].forEach(function (type) {
            box.addEventListener(type, function (e) {
                e.preventDefault();
                box.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (type) {
            box.addEventListener(type, function () {
                box.classList.remove('is-dragover');
            });
        });

        box.addEventListener('drop', function (e) {

            e.preventDefault();

            if (e.dataTransfer && e.dataTransfer.files.length) {

                input.files = e.dataTransfer.files;

                input.dispatchEvent(
                    new Event('change', { bubbles: true })
                );
            }
        });

    });


    /* =========================================================
       SECTION RAIL — STICKY + SCROLL SPY
       - rail səhifə ilə birlikdə gəlir
       - bölmənin üstündən keçəndə aktiv düymə dəyişir
       - düyməyə klik hamar scroll edir
    ========================================================= */

    (function () {

        const layout = document.querySelector('.sx-layout');
        const rail = document.querySelector('.sx-rail');
        const links = Array.from(document.querySelectorAll('[data-rail]'));

        if (!layout || !rail || !links.length) {
            return;
        }

        const sections = links
            .map(function (link) {
                return document.getElementById(link.dataset.rail);
            })
            .filter(Boolean);

        if (!sections.length) {
            return;
        }

        layout.classList.add('has-js-rail');


        /* ---------- scroll container (window və ya daxili scroll) ---------- */

        let scroller = null;

        for (
            let el = layout.parentElement;
            el && el !== document.body && el !== document.documentElement;
            el = el.parentElement
        ) {
            const overflowY = getComputedStyle(el).overflowY;

            if (
                /(auto|scroll|overlay)/.test(overflowY) &&
                el.scrollHeight > el.clientHeight
            ) {
                scroller = el;
                break;
            }
        }

        const isMobile = function () {
            return window.innerWidth <= 991;
        };

        /* Fixed header varsa, rail-in dayanacağı yer (px) */
        const gap = function () {
            if (scroller) {
                return 16;
            }

            return isMobile() ? 88 : 100;
        };

        const stickyLine = function () {
            const base = scroller
                ? scroller.getBoundingClientRect().top
                : 0;

            return base + gap();
        };

        /* Mobildə rail üfüqi zolaqdır, bölmə onun altında dayanmalıdır */
        const belowRail = function () {
            return isMobile() ? rail.offsetHeight + 12 : 0;
        };


        /* ---------- active link ---------- */

        let current = null;
        let lockUntil = 0;
        let ticking = false;

        const setActive = function (id) {

            if (id === current) {
                return;
            }

            current = id;

            links.forEach(function (link) {

                const on = link.dataset.rail === id;

                link.classList.toggle('is-active', on);

                if (on) {
                    link.setAttribute('aria-current', 'true');
                } else {
                    link.removeAttribute('aria-current');
                }
            });

            /* Mobil üfüqi zolaqda aktiv düyməni görünən et */
            const activeLink = links.find(function (link) {
                return link.dataset.rail === id;
            });

            if (activeLink && rail.scrollWidth > rail.clientWidth) {
                rail.scrollTo({
                    left:
                        activeLink.offsetLeft -
                        (rail.clientWidth - activeLink.offsetWidth) / 2,
                    behavior: 'smooth'
                });
            }
        };


        /* ---------- sticky + spy ---------- */

        const update = function () {

            ticking = false;

            /* 1) Rail səhifə ilə birlikdə gəlsin */
            const layoutRect = layout.getBoundingClientRect();

            const maxShift = Math.max(
                0,
                layoutRect.height - rail.offsetHeight
            );

            const shift = Math.min(
                maxShift,
                Math.max(0, stickyLine() - layoutRect.top)
            );

            rail.style.transform = shift
                ? 'translate3d(0,' + shift + 'px,0)'
                : '';

            /* 2) Hansı bölmənin üstündəyik */
            if (Date.now() < lockUntil) {
                return;
            }

            const line = stickyLine() + belowRail() + 24;

            let active = sections[0];

            sections.forEach(function (section) {
                if (section.getBoundingClientRect().top <= line) {
                    active = section;
                }
            });

            const atBottom = scroller
                ? scroller.scrollTop + scroller.clientHeight >=
                  scroller.scrollHeight - 4
                : window.innerHeight + window.scrollY >=
                  document.documentElement.scrollHeight - 4;

            if (atBottom) {
                active = sections[sections.length - 1];
            }

            setActive(active.id);
        };

        const request = function () {

            if (ticking) {
                return;
            }

            ticking = true;

            window.requestAnimationFrame(update);
        };

        /* capture = true: window və istənilən daxili scroll-u tutur */
        document.addEventListener('scroll', request, {
            passive: true,
            capture: true
        });

        window.addEventListener('resize', request);
        window.addEventListener('load', request);

        if ('ResizeObserver' in window) {
            new ResizeObserver(request).observe(layout);
        }


        /* ---------- link-ə klik: hamar scroll ---------- */

        links.forEach(function (link) {

            link.addEventListener('click', function (event) {

                const target = document.getElementById(
                    link.dataset.rail
                );

                if (!target) {
                    return;
                }

                event.preventDefault();

                const delta =
                    target.getBoundingClientRect().top -
                    (stickyLine() + belowRail());

                if (scroller) {
                    scroller.scrollBy({ top: delta, behavior: 'smooth' });
                } else {
                    window.scrollBy({ top: delta, behavior: 'smooth' });
                }

                setActive(link.dataset.rail);

                /* smooth scroll bitənə qədər spy qarışmasın */
                lockUntil = Date.now() + 900;

                window.setTimeout(request, 950);

                if (window.history && window.history.replaceState) {
                    window.history.replaceState(
                        null,
                        '',
                        '#' + link.dataset.rail
                    );
                }
            });
        });

        update();

    })();


    /* =========================================================
       UNSAVED CHANGES INDICATOR
    ========================================================= */

    const saveBar = document.getElementById('settingsSaveBar');
    const saveTitle = document.getElementById('saveStatusTitle');
    const saveText = document.getElementById('saveStatusText');

    const setDirty = function (dirty) {

        saveBar.classList.toggle('is-dirty', dirty);

        saveTitle.textContent = dirty
            ? 'Unsaved changes'
            : 'All changes saved';

        saveText.textContent = dirty
            ? 'Save to apply your edits.'
            : 'Changes apply after saving.';
    };

    form.addEventListener('input', function () {
        setDirty(true);
    });

    form.addEventListener('change', function () {
        setDirty(true);
    });


    /* =========================================================
       AJAX SUBMIT
       Existing behavior preserved
    ========================================================= */

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        const submitButtons = form.querySelectorAll('button[type="submit"]');
        const formData = new FormData(form);

        submitButtons.forEach(function (button) {

            button.disabled = true;
            button.dataset.originalHtml = button.innerHTML;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                ></span>

                <span>Saving...</span>
            `;
        });

        try {

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const contentType = response.headers.get('content-type') || '';

            let data = {};

            if (contentType.includes('application/json')) {
                data = await response.json();
            }

            if (!response.ok) {

                let errorMessage = 'Please review the settings form.';

                if (data.errors) {

                    const messages = [];

                    Object.values(data.errors).forEach(function (errors) {
                        errors.forEach(function (message) {
                            messages.push(message);
                        });
                    });

                    if (messages.length) {
                        errorMessage = messages.join('<br>');
                    }
                }

                throw new Error(errorMessage);
            }

            setDirty(false);

            Swal.fire({
                icon: 'success',
                title: 'Settings saved',
                text: data.message || 'Settings updated successfully.',
                confirmButtonColor: '#3451d1',
                confirmButtonText: 'Done',
                customClass: {
                    popup: 'settings-swal-popup'
                }
            });

        } catch (error) {

            Swal.fire({
                icon: 'error',
                title: 'Save failed',
                html: error.message || 'Something went wrong while saving settings.',
                confirmButtonColor: '#3451d1',
                confirmButtonText: 'Try again',
                customClass: {
                    popup: 'settings-swal-popup'
                }
            });

        } finally {

            submitButtons.forEach(function (button) {

                button.disabled = false;

                if (button.dataset.originalHtml) {
                    button.innerHTML = button.dataset.originalHtml;
                }
            });
        }
    });

});
</script>

@endpush

@endsection