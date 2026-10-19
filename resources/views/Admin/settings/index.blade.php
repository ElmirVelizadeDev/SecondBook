@extends('Layout.Admin.master')
@push('css')@vite('resources/css/admin/pages/settings.css')@endpush
@section('title', 'Settings')
@section('content')
<div data-vue-page="settings-admin"
     data-update-url="{{ route('admin.settings.update') }}"
     data-csrf="{{ csrf_token() }}"
     data-storage-root="{{ asset('storage') }}"
     data-timezones="{{ json_encode(timezone_identifiers_list()) }}"
     data-settings='{{ json_encode($settings->filter(fn ($setting, $key) => in_array($key, [
         'site_name', 'site_description', 'support_email', 'support_phone', 'address', 'country', 'city', 'currency', 'timezone', 'maintenance_mode', 'logo', 'favicon',
         'marketplace_enabled', 'user_registration_enabled', 'seller_registration_enabled', 'seller_approval_required', 'book_approval_required', 'reviews_enabled', 'stock_management_enabled', 'minimum_order_amount',
         'default_order_status', 'order_number_format', 'cancel_order_period', 'auto_cancel_pending_orders', 'customer_order_notifications',
         'payments_enabled', 'default_payment_method', 'payment_methods', 'shipping_enabled', 'default_shipping_fee', 'free_shipping_threshold', 'default_country', 'estimated_delivery_message',
         'meta_title', 'meta_description', 'keywords', 'search_engine_indexing', 'google_analytics_id', 'google_search_console_verification', 'open_graph_image',
         'facebook', 'instagram', 'tiktok', 'youtube', 'whatsapp', 'login_attempt_limit', 'session_lifetime', 'minimum_password_length', 'two_factor_authentication_enabled', 'admin_session_security',
         'privacy_policy', 'terms_conditions', 'refund_policy', 'shipping_policy', 'cookie_notice'
     ], true))->map(fn ($setting) => $setting->value)->all(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}'
     data-success="{{ session('success', '') }}"
     data-error="{{ $errors->first() }}"
     data-theme="{{ session('admin_theme', 'light') }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
