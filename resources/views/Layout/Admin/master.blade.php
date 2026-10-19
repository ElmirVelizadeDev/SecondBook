<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

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

    {{-- =========================================================
         GOOGLE FONT
    ========================================================== --}}

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- =========================================================
         BOOTSTRAP
    ========================================================== --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- =========================================================
         BOOTSTRAP ICONS
    ========================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    {{-- =========================================================
         ADMIN SHELL CSS
    ========================================================== --}}

    @vite('resources/css/admin/core/style.css')

    @vite('resources/css/admin/core/header.css')

    @vite('resources/css/admin/core/sidebar.css')

    @vite('resources/css/admin/core/responsive.css')

    @stack('css')

    {{-- =========================================================
         SWEETALERT2
    ========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =========================================================
         APPLY SAVED THEME BEFORE PAGE LOAD
    ========================================================== --}}

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

    @vite('resources/css/admin/core/layout-overrides.css')

</head>


<body>

<div class="wrapper">

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    @include('Layout.Admin.sidebar')


    {{-- =========================================================
         SIDEBAR OVERLAY
    ========================================================== --}}

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
        aria-hidden="true"
    ></div>


    {{-- =========================================================
         MAIN
    ========================================================== --}}

    <div class="main">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        @include('Layout.Admin.header')


        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}

        <div class="container-fluid p-0">

            @yield('content')

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        @include('Layout.Admin.footer')

    </div>

</div>


{{-- =========================================================
     COMMON SCRIPTS
========================================================= --}}

@include('Layout.Admin.scripts')

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

                var isDark =
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