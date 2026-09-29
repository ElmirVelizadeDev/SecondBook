<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SecondBook Admin</title>

    @php
    $adminFavicon = \App\Models\Setting::get('favicon');
    @endphp

    <link
        rel="icon"
        type="image/png"
        href="{{ $adminFavicon
            ? asset('storage/' . $adminFavicon)
            : asset('admin/images/logo.png')
        }}"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/books.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/users.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/reviews.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/banner.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/book-requests.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/book-condition.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/analysis.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/authors.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/category.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/publishers.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/sellers.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/roles.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/settings.css') }}">

    @stack('css')

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Apply saved theme before page loads --}}
    <script>
        (function () {

            var savedTheme = localStorage.getItem('admin_theme');

            var preferredDark =
                window.matchMedia &&
                window.matchMedia('(prefers-color-scheme: dark)').matches;

            var theme =
                savedTheme ||
                (preferredDark ? 'dark' : 'light');

            document.documentElement.setAttribute(
                'data-theme',
                theme
            );

        })();
    </script>

    <style>

        :root[data-theme="dark"] body {
            background: #0f172a;
            color: #e6edf7;
        }

        :root[data-theme="dark"] .navbar,
        :root[data-theme="dark"] footer,
        :root[data-theme="dark"] .dashboard-panel,
        :root[data-theme="dark"] .card,
        :root[data-theme="dark"] .dropdown-menu {
            background: #111827 !important;
            color: #e5e7eb !important;
            border-color: #334155 !important;
        }

        :root[data-theme="dark"] .table {
            color: #e5e7eb;
        }

        :root[data-theme="dark"] h1,
        :root[data-theme="dark"] h2,
        :root[data-theme="dark"] h3,
        :root[data-theme="dark"] h4,
        :root[data-theme="dark"] h5,
        :root[data-theme="dark"] h6,
        :root[data-theme="dark"] strong,
        :root[data-theme="dark"] label,
        :root[data-theme="dark"] .form-label,
        :root[data-theme="dark"] .panel-header h5,
        :root[data-theme="dark"] .navbar .header-title,
        :root[data-theme="dark"] .navbar .fw-semibold,
        :root[data-theme="dark"] .badge,
        :root[data-theme="dark"] p,
        :root[data-theme="dark"] span,
        :root[data-theme="dark"] td,
        :root[data-theme="dark"] th,
        :root[data-theme="dark"] li,
        :root[data-theme="dark"] a:not(.btn) {
            color: #e6edf7;
        }

        :root[data-theme="dark"] .table th,
        :root[data-theme="dark"] .table td {
            border-color: #334155;
            background: transparent;
        }

        :root[data-theme="dark"] .form-control,
        :root[data-theme="dark"] .form-select,
        :root[data-theme="dark"] .input-group-text {
            background: #0b1220 !important;
            color: #e5e7eb !important;
            border-color: #334155 !important;
        }

        .dashboard-panel .form-control,
        .dashboard-panel .form-select,
        .dashboard-panel .form-label,
        .dashboard-panel .form-control::placeholder,
        .dashboard-panel .form-select option {
            color: #111827 !important;
        }

        :root[data-theme="dark"] .dashboard-panel .form-control,
        :root[data-theme="dark"] .dashboard-panel .form-select,
        :root[data-theme="dark"] .dashboard-panel .form-label,
        :root[data-theme="dark"] .dashboard-panel .form-control::placeholder,
        :root[data-theme="dark"] .dashboard-panel .form-select option {
            color: #e6edf7 !important;
        }

        :root[data-theme="dark"] .text-muted,
        :root[data-theme="dark"] small,
        :root[data-theme="dark"] .navbar small {
            color: #b6c2d3 !important;
        }

        :root[data-theme="dark"] .btn-light,
        :root[data-theme="dark"] .btn.btn-light.border {
            background: #1e293b !important;
            color: #e5e7eb !important;
            border-color: #334155 !important;
        }

        :root[data-theme="dark"] .dropdown-menu .dropdown-item {
            color: #e6edf7 !important;
        }

        :root[data-theme="dark"] .dropdown-menu .dropdown-item:hover,
        :root[data-theme="dark"] .dropdown-menu .dropdown-item:focus {
            background: #1f2937 !important;
            color: #ffffff !important;
        }

        :root[data-theme="dark"] .dropdown-menu .dropdown-item.text-danger {
            color: #fda4af !important;
        }

        :root[data-theme="dark"] .dropdown-menu .dropdown-item.text-danger:hover,
        :root[data-theme="dark"] .dropdown-menu .dropdown-item.text-danger:focus {
            background: #3f1d2a !important;
            color: #fecdd3 !important;
        }

        :root[data-theme="dark"] .dropdown-divider {
            border-color: #334155;
        }

        :root[data-theme="dark"] .main,
        :root[data-theme="dark"] .container-fluid {
            background: transparent;
        }

        :root[data-theme="dark"] .order-card {
            background: #111827 !important;
            color: #e6edf7;
        }

        :root[data-theme="dark"] .order-card span {
            color: #b6c2d3 !important;
        }

        :root[data-theme="dark"] .order-card h3 {
            color: #ffffff !important;
        }

        :root[data-theme="dark"] .order-card i {
            color: #c08457 !important;
        }

        /* SweetAlert2 Theme Fix */

        .swal2-popup {
            color: #111827 !important;
        }

        .swal2-title {
            color: #111827 !important;
        }

        .swal2-html-container {
            color: #6b7280 !important;
        }

        /* SweetAlert2 Dark Mode */

        :root[data-theme="dark"] .swal2-popup {
            background: #111827 !important;
        }

        :root[data-theme="dark"] .swal2-title {
            color: #ffffff !important;
        }

        :root[data-theme="dark"] .swal2-html-container {
            color: #cbd5e1 !important;
        }

        /* =========================================================
        GLOBAL SCROLL FIX
        ========================================================= */

        html,
        body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            overflow-x: hidden;
        }

        .wrapper {
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        .main {
            min-width: 0;
            width: 100%;
            flex: 1;
            overflow-x: hidden;
        }

        .container-fluid {
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        .sidebar-overlay {
            overflow: hidden;
        }

    </style>

</head>

<body class="sidebar-open">

<div class="wrapper">

    {{-- Sidebar --}}
    @include('layout.admin.sidebar')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main">

        {{-- Header --}}
        @include('layout.admin.header')

        <div class="container-fluid p-0">

            @yield('content')

        </div>

        {{-- Footer --}}
        @include('layout.admin.footer')

    </div>

</div>


{{-- Common Scripts --}}
@include('layout.admin.scripts')

<script src="{{ asset('admin/js/sidebar.js') }}"></script>

<script src="{{ asset('admin/js/app.js') }}"></script>


{{-- Theme System --}}
<script>

    (function () {

        function applyTheme(theme) {

            var safeTheme =
                theme === 'dark'
                    ? 'dark'
                    : 'light';

            document.documentElement.setAttribute(
                'data-theme',
                safeTheme
            );

            localStorage.setItem(
                'admin_theme',
                safeTheme
            );

            var label =
                document.getElementById('themeToggleLabel');

            var icon =
                document.getElementById('themeToggleIcon');

            if (label && icon) {

                if (safeTheme === 'dark') {

                    label.textContent =
                        'Switch to Light Mode';

                    icon.className =
                        'bi bi-sun me-2';

                } else {

                    label.textContent =
                        'Switch to Dark Mode';

                    icon.className =
                        'bi bi-moon-stars me-2';

                }

            }

        }

        window.setAdminTheme = applyTheme;

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                var currentTheme =
                    document.documentElement.getAttribute(
                        'data-theme'
                    ) || 'light';

                applyTheme(currentTheme);


                var toggleBtn =
                    document.getElementById(
                        'themeToggleBtn'
                    );

                if (toggleBtn) {

                    toggleBtn.addEventListener(
                        'click',
                        function (event) {

                            event.preventDefault();

                            var now =
                                document.documentElement.getAttribute(
                                    'data-theme'
                                ) || 'light';

                            applyTheme(
                                now === 'dark'
                                    ? 'light'
                                    : 'dark'
                            );

                        }
                    );

                }


                document
                    .querySelectorAll('[data-theme-target]')
                    .forEach(function (btn) {

                        btn.addEventListener(
                            'click',
                            function () {

                                applyTheme(
                                    btn.getAttribute(
                                        'data-theme-target'
                                    )
                                );

                            }
                        );

                    });

            }
        );

    })();

</script>


{{-- Logout Confirmation --}}
<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            function confirmLogout(formId) {

                Swal.fire({

                    title: 'Logout?',

                    text: 'Are you sure you want to sign out of your account?',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Logout',

                    cancelButtonText: 'Cancel',

                    confirmButtonColor: '#8b5e3c',

                    cancelButtonColor: '#6c757d',

                    reverseButtons: false

                }).then(function (result) {

                    if (result.isConfirmed) {

                        var form =
                            document.getElementById(formId);

                        if (form) {
                            form.submit();
                        }

                    }

                });

            }


            // Header Logout

            var logoutBtn =
                document.getElementById('logoutBtn');

            if (logoutBtn) {

                logoutBtn.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        confirmLogout('logoutForm');

                    }
                );

            }


            // Sidebar Logout

            var sidebarLogoutBtn =
                document.getElementById(
                    'sidebarLogoutBtn'
                );

            if (sidebarLogoutBtn) {

                sidebarLogoutBtn.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        confirmLogout(
                            'sidebarLogoutForm'
                        );

                    }
                );

            }

        }
    );

</script>

{{-- Session Alerts --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: @json(session('success')),
                confirmButtonColor: '#8b5e3c',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error')),
                confirmButtonColor: '#8b5e3c',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: @json(session('warning')),
                confirmButtonColor: '#8b5e3c',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('permission_denied'))
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: @json(session('permission_denied')),
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });
        @endif

    });
</script>


{{-- Page Specific Scripts --}}
@stack('scripts')


{{-- Existing JS Stack --}}
@stack('js')


</body>

</html>