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

    {{-- Google Font --}}
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
    <link rel="stylesheet" href="{{ asset('admin/css/dashboard-premium.css') }}">
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

            var savedTheme =
                localStorage.getItem('admin_theme');

            var preferredDark =
                window.matchMedia &&
                window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches;

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

        /* =========================================================
           DARK MODE
        ========================================================= */

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


        /* =========================================================
           SWEETALERT2 GLOBAL THEME
        ========================================================= */

        .swal2-popup {
            color: #111827 !important;
        }

        .swal2-title {
            color: #111827 !important;
        }

        .swal2-html-container {
            color: #6b7280 !important;
        }

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
           ADMIN LOGOUT ALERT
        ========================================================= */

        .admin-logout-popup {
            overflow: hidden !important;
            border: 1px solid #e4e7ec !important;
            border-radius: 22px !important;

            background:
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #fbfcfe 100%
                ) !important;

            box-shadow:
                0 30px 80px -25px rgba(16, 24, 40, .30),
                0 12px 35px -15px rgba(16, 24, 40, .18);

            font-family:
                "Plus Jakarta Sans",
                system-ui,
                -apple-system,
                "Segoe UI",
                sans-serif !important;
        }


        /* =========================================================
           LOGOUT TITLE
        ========================================================= */

        .admin-logout-title {
            margin: 0 !important;
            padding: 28px 28px 4px !important;

            color: #101828 !important;
            font-size: 21px !important;
            font-weight: 800 !important;

            letter-spacing: -.025em;
            line-height: 1.3 !important;
        }


        /* =========================================================
           LOGOUT BODY
        ========================================================= */

        .admin-logout-body {
            margin: 0 !important;
            padding: 0 28px !important;

            color: #667085 !important;
            font-size: 13px !important;
            line-height: 1.65 !important;
        }

        .admin-logout-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }


        /* =========================================================
           LOGOUT ICON
        ========================================================= */

        .admin-logout-icon {
            width: 64px;
            height: 64px;
            margin: 2px auto 16px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            background:
                linear-gradient(
                    145deg,
                    #fff1f0,
                    #fef3f2
                );

            color: #d92d20;

            box-shadow:
                inset 0 0 0 1px
                rgba(217, 45, 32, .10),

                0 10px 25px -16px
                rgba(217, 45, 32, .45);

            font-size: 25px;
        }

        .admin-logout-icon i {
            line-height: 1;
        }


        /* =========================================================
           LOGOUT TEXT
        ========================================================= */

        .admin-logout-content p {
            max-width: 300px;
            margin: 0 auto !important;

            color: #667085 !important;
            font-size: 13px !important;
            font-weight: 500;
            line-height: 1.7;
        }


        /* =========================================================
           LOGOUT ACTIONS
        ========================================================= */

        .admin-logout-actions {
            width: 100%;
            margin: 0 !important;
            padding: 24px 28px 28px !important;

            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }


        /* =========================================================
           BUTTON BASE
        ========================================================= */

        .admin-logout-confirm,
        .admin-logout-cancel {
            height: 46px !important;
            margin: 0 !important;
            padding: 0 16px !important;

            display: inline-flex !important;
            align-items: center;
            justify-content: center;

            border-radius: 12px !important;

            font-family:
                "Plus Jakarta Sans",
                system-ui,
                sans-serif !important;

            font-size: 13px !important;
            font-weight: 700 !important;
            line-height: 1 !important;

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                background .2s ease,
                border-color .2s ease !important;
        }


        /* =========================================================
           BUTTON CONTENT
        ========================================================= */

        .admin-logout-btn,
        .admin-cancel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .admin-logout-btn i,
        .admin-cancel-btn i {
            font-size: 14px;
        }


        /* =========================================================
           CANCEL BUTTON
        ========================================================= */

        .admin-logout-cancel {
            border:
                1px solid #dfe3ea !important;

            background:
                #ffffff !important;

            color:
                #475467 !important;

            box-shadow:
                0 2px 5px
                rgba(16, 24, 40, .04);
        }

        .admin-logout-cancel:hover {
            transform:
                translateY(-1px);

            border-color:
                #cfd4dc !important;

            background:
                #f8fafc !important;

            color:
                #101828 !important;

            box-shadow:
                0 8px 20px -14px
                rgba(16, 24, 40, .35);
        }


        /* =========================================================
           LOGOUT BUTTON
        ========================================================= */

        .admin-logout-confirm {
            border:
                1px solid #d92d20 !important;

            background:
                linear-gradient(
                    135deg,
                    #e5483d,
                    #d92d20
                ) !important;

            color:
                #ffffff !important;

            box-shadow:
                0 10px 24px -13px
                rgba(217, 45, 32, .65);
        }

        .admin-logout-confirm:hover {
            transform:
                translateY(-1px);

            border-color:
                #c5261b !important;

            background:
                linear-gradient(
                    135deg,
                    #dc4035,
                    #c9251b
                ) !important;

            color:
                #ffffff !important;

            box-shadow:
                0 14px 28px -14px
                rgba(217, 45, 32, .75);
        }

        .admin-logout-confirm:active,
        .admin-logout-cancel:active {
            transform:
                translateY(0);
        }


        /* =========================================================
           DARK MODE - LOGOUT
        ========================================================= */

        :root[data-theme="dark"] .admin-logout-popup {
            border-color:
                #26334d !important;

            background:
                linear-gradient(
                    180deg,
                    #111827 0%,
                    #0f172a 100%
                ) !important;

            box-shadow:
                0 35px 90px -25px
                rgba(0, 0, 0, .75),

                0 15px 40px -18px
                rgba(0, 0, 0, .65);
        }

        :root[data-theme="dark"] .admin-logout-title {
            color:
                #f2f4f7 !important;
        }

        :root[data-theme="dark"] .admin-logout-content p {
            color:
                #98a7bd !important;
        }

        :root[data-theme="dark"] .admin-logout-icon {
            background:
                linear-gradient(
                    145deg,
                    rgba(239, 68, 68, .16),
                    rgba(127, 29, 29, .20)
                );

            color:
                #ff8178;

            box-shadow:
                inset 0 0 0 1px
                rgba(248, 113, 113, .12),

                0 12px 28px -16px
                rgba(239, 68, 68, .35);
        }

        :root[data-theme="dark"] .admin-logout-cancel {
            border-color:
                #2d3a52 !important;

            background:
                #172033 !important;

            color:
                #b8c3d6 !important;

            box-shadow:
                none;
        }

        :root[data-theme="dark"] .admin-logout-cancel:hover {
            border-color:
                #3b4a66 !important;

            background:
                #1c2940 !important;

            color:
                #f2f4f7 !important;
        }


        /* =========================================================
           LOGOUT ANIMATION
        ========================================================= */

        @keyframes adminLogoutShow {

            from {
                opacity: 0;

                transform:
                    translateY(12px)
                    scale(.97);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }

        }

        @keyframes adminLogoutHide {

            from {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }

            to {
                opacity: 0;

                transform:
                    translateY(8px)
                    scale(.98);
            }

        }

        .admin-logout-show {
            animation:
                adminLogoutShow .22s
                cubic-bezier(.2, .8, .2, 1) both;
        }

        .admin-logout-hide {
            animation:
                adminLogoutHide .16s ease both;
        }


        /* =========================================================
           MOBILE LOGOUT
        ========================================================= */

        @media (max-width: 480px) {

            .admin-logout-popup {
                width:
                    calc(100% - 28px) !important;

                border-radius:
                    19px !important;
            }

            .admin-logout-title {
                padding:
                    24px 22px 4px !important;

                font-size:
                    19px !important;
            }

            .admin-logout-body {
                padding:
                    0 22px !important;
            }

            .admin-logout-actions {
                padding:
                    20px 22px 22px !important;
            }

        }


        /* =========================================================
           GLOBAL SCROLL / STICKY FIX
           
           IMPORTANT:
           Do NOT use overflow-x:hidden on ancestors of
           position:sticky elements.
        ========================================================= */

        html,
        body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        html {
            overflow-x: clip;
        }

        body {
            overflow-x: clip;
        }

        /*
         * Main wrapper must not become a scrolling container.
         * clip prevents horizontal overflow without creating
         * the same scrolling context as overflow:hidden.
         */
        /* =========================================================
        SETTINGS STICKY SUPPORT
        ========================================================= */

        @media (min-width: 992px) {

            .settings-page {
                position: relative;
                width: 100%;
                min-width: 0;
            }

            .settings-page .sx-layout {
                display: grid;
                grid-template-columns: 232px minmax(0, 1fr);
                gap: 24px;
                align-items: start;

                width: 100%;
                min-width: 0;

                overflow: visible !important;
            }

            .settings-page .sx-rail {
                position: -webkit-sticky;
                position: sticky;

                top: 100px;

                align-self: start;

                width: 232px;
                height: fit-content;

                max-height: none;

                overflow: visible;

                z-index: 20;
            }

            .settings-page .sx-content {
                min-width: 0;
                width: 100%;
            }
        }

        .container-fluid {
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        /*
         * Settings sticky ancestors
         */
        .main .settings-page,
        .main .settings-page .sx-layout {
            overflow: visible !important;
        }

        /*
         * Sidebar overlay should not affect normal page scrolling.
         */
        .sidebar-overlay {
            overflow: hidden;
        }

    </style>

</head>


<body class="sidebar-open">

<div class="wrapper">

    {{-- =========================================================
         SIDEBAR
    ========================================================= --}}

    @include('layout.admin.sidebar')


    {{-- =========================================================
         SIDEBAR OVERLAY
    ========================================================= --}}

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    {{-- =========================================================
         MAIN
    ========================================================= --}}

    <div class="main">

        {{-- Header --}}
        @include('layout.admin.header')


        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}

        <div class="container-fluid p-0">

            @yield('content')

        </div>


        {{-- Footer --}}
        @include('layout.admin.footer')

    </div>

</div>


{{-- =========================================================
     COMMON SCRIPTS
========================================================= --}}

@include('layout.admin.scripts')

<script src="{{ asset('admin/js/sidebar.js') }}"></script>

<script src="{{ asset('admin/js/app.js') }}"></script>


{{-- =========================================================
     THEME SYSTEM
========================================================= --}}

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
                document.getElementById(
                    'themeToggleLabel'
                );

            var icon =
                document.getElementById(
                    'themeToggleIcon'
                );


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


        window.setAdminTheme =
            applyTheme;


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
                    .querySelectorAll(
                        '[data-theme-target]'
                    )
                    .forEach(
                        function (btn) {

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

                        }
                    );

            }
        );

    })();

</script>


{{-- =========================================================
     LOGOUT CONFIRMATION
========================================================= --}}

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            function confirmLogout(formId) {

                const isDark =
                    document.documentElement.getAttribute(
                        'data-theme'
                    ) === 'dark';


                Swal.fire({

                    title: 'Sign out?',


                    html: `

                        <div class="admin-logout-content">

                            <div class="admin-logout-icon">

                                <i class="bi bi-box-arrow-right"></i>

                            </div>

                            <p>

                                Are you sure you want to sign out
                                of your admin account?

                            </p>

                        </div>

                    `,


                    showCancelButton: true,


                    confirmButtonText: `

                        <span class="admin-logout-btn">

                            <i class="bi bi-box-arrow-right"></i>

                            Logout

                        </span>

                    `,


                    cancelButtonText: `

                        <span class="admin-cancel-btn">

                            <i class="bi bi-x-lg"></i>

                            Cancel

                        </span>

                    `,


                    reverseButtons: true,

                    focusCancel: true,

                    allowOutsideClick: true,

                    allowEscapeKey: true,


                    width: '420px',

                    padding: '0',


                    background:
                        isDark
                            ? '#111827'
                            : '#ffffff',


                    color:
                        isDark
                            ? '#f2f4f7'
                            : '#101828',


                    backdrop:
                        isDark
                            ? 'rgba(2, 6, 23, .76)'
                            : 'rgba(15, 23, 42, .45)',


                    customClass: {

                        popup:
                            'admin-logout-popup',

                        title:
                            'admin-logout-title',

                        htmlContainer:
                            'admin-logout-body',

                        actions:
                            'admin-logout-actions',

                        confirmButton:
                            'admin-logout-confirm',

                        cancelButton:
                            'admin-logout-cancel'

                    },


                    showClass: {

                        popup:
                            'admin-logout-show'

                    },


                    hideClass: {

                        popup:
                            'admin-logout-hide'

                    }

                }).then(
                    function (result) {

                        if (result.isConfirmed) {

                            var form =
                                document.getElementById(
                                    formId
                                );


                            if (form) {

                                form.submit();

                            }

                        }

                    }
                );

            }


            /* =====================================================
               HEADER LOGOUT
            ===================================================== */

            var logoutBtn =
                document.getElementById(
                    'logoutBtn'
                );


            if (logoutBtn) {

                logoutBtn.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        confirmLogout(
                            'logoutForm'
                        );

                    }
                );

            }


            /* =====================================================
               SIDEBAR LOGOUT
            ===================================================== */

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


{{-- =========================================================
     SESSION ALERTS
========================================================= --}}

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            @if(session('success'))

                Swal.fire({

                    icon: 'success',

                    title: 'Success',

                    text:
                        @json(session('success')),

                    confirmButtonColor:
                        '#8b5e3c',

                    confirmButtonText:
                        'OK'

                });

            @endif


            @if(session('error'))

                Swal.fire({

                    icon: 'error',

                    title: 'Error',

                    text:
                        @json(session('error')),

                    confirmButtonColor:
                        '#8b5e3c',

                    confirmButtonText:
                        'OK'

                });

            @endif


            @if(session('warning'))

                Swal.fire({

                    icon: 'warning',

                    title: 'Warning',

                    text:
                        @json(session('warning')),

                    confirmButtonColor:
                        '#8b5e3c',

                    confirmButtonText:
                        'OK'

                });

            @endif


            @if(session('permission_denied'))

                Swal.fire({

                    icon: 'warning',

                    title: 'Access Denied',

                    text:
                        @json(session('permission_denied')),

                    confirmButtonColor:
                        '#2563eb',

                    confirmButtonText:
                        'OK'

                });

            @endif

        }
    );

</script>


{{-- =========================================================
     PAGE SPECIFIC SCRIPTS
========================================================= --}}

@stack('scripts')


{{-- =========================================================
     EXISTING JS STACK
========================================================= --}}

@stack('js')


</body>

</html>