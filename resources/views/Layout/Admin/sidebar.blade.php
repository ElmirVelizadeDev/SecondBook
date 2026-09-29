<div class="sidebar show">

    {{-- Logo --}}
    <div class="logo">

        <div class="logo-brand">

            <div class="logo-icon">
                <img
                    src="{{ asset('admin/images/logo2.png') }}"
                    alt="SecondBook Logo"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; display: block;"
                >
            </div>

            <div class="logo-text">
                <h4>SecondBook</h4>
                <small>Admin Panel</small>
            </div>

        </div>

        <button
            class="sidebar-close"
            id="closeSidebar"
            type="button"
            aria-label="Close menu"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>


    {{-- Scrollable Menu --}}
    <div class="sidebar-menu">

        {{-- Dashboard --}}
        @can('dashboard.view')

            <ul class="menu dashboard-menu">

                <li class="sidebar-item dashboard-item">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >

                        <div>
                            <i class="bi bi-grid"></i>
                            <span>Dashboard</span>
                        </div>

                    </a>

                </li>

            </ul>

        @endcan


        <span class="menu-title">CONTROL PANEL</span>


        {{-- =========================================================
             BOOK MANAGEMENT
        ========================================================== --}}
        @canany([
            'books.view',
            'book_conditions.view',
            'book_requests.view',
            'categories.view',
            'authors.view',
            'publishers.view'
        ])

            <ul class="menu">

                <li>

                    <a
                        data-bs-toggle="collapse"
                        href="#bookMenu"
                        role="button"
                        aria-expanded="{{ request()->routeIs(
                            'admin.books.*',
                            'admin.categories.*',
                            'admin.authors.*',
                            'admin.publishers.*',
                            'admin.book.conditions.*',
                            'admin.book.requests.*'
                        ) ? 'true' : 'false' }}"
                    >

                        <div>
                            <i class="bi bi-book"></i>
                            <span>Book Management</span>
                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </a>


                    <div
                        class="collapse {{ request()->routeIs(
                            'admin.books.*',
                            'admin.categories.*',
                            'admin.authors.*',
                            'admin.publishers.*',
                            'admin.book.conditions.*',
                            'admin.book.requests.*'
                        ) ? 'show' : '' }}"
                        id="bookMenu"
                    >

                        <ul class="menu">

                            {{-- Books --}}
                            @can('books.view')
                                <li>
                                    <a
                                        href="{{ route('admin.books.index') }}"
                                        class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}"
                                    >
                                        Books
                                    </a>
                                </li>
                            @endcan


                            {{-- Categories --}}
                            @can('categories.view')
                                <li>
                                    <a
                                        href="{{ route('admin.categories.index') }}"
                                        class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                    >
                                        Categories
                                    </a>
                                </li>
                            @endcan


                            {{-- Authors --}}
                            @can('authors.view')
                                <li>
                                    <a
                                        href="{{ route('admin.authors.index') }}"
                                        class="{{ request()->routeIs('admin.authors.*') ? 'active' : '' }}"
                                    >
                                        Authors
                                    </a>
                                </li>
                            @endcan


                            {{-- Publishers --}}
                            @can('publishers.view')
                                <li>
                                    <a
                                        href="{{ route('admin.publishers.index') }}"
                                        class="{{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}"
                                    >
                                        Publishers
                                    </a>
                                </li>
                            @endcan


                            {{-- Book Conditions --}}
                            @can('book_conditions.view')
                                <li>
                                    <a
                                        href="{{ route('admin.book.conditions.index') }}"
                                        class="{{ request()->routeIs('admin.book.conditions.*') ? 'active' : '' }}"
                                    >
                                        Book Conditions
                                    </a>
                                </li>
                            @endcan


                            {{-- Book Requests --}}
                            @can('book_requests.view')
                                <li>
                                    <a
                                        href="{{ route('admin.book.requests.index') }}"
                                        class="{{ request()->routeIs('admin.book.requests.*') ? 'active' : '' }}"
                                    >
                                        Book Requests
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </div>

                </li>

            </ul>

        @endcanany


        {{-- =========================================================
             SALES MANAGEMENT
        ========================================================== --}}
        @canany([
            'orders.view',
            'payments.view',
            'coupons.view',
            'shipping.view',
            'refunds.view'
        ])

            <ul class="menu">

                <li>

                    <a
                        data-bs-toggle="collapse"
                        href="#salesMenu"
                        role="button"
                        aria-expanded="{{ request()->routeIs(
                            'admin.orders.*',
                            'admin.payments.*',
                            'admin.coupons.*',
                            'admin.shipping.*',
                            'admin.refunds.*'
                        ) ? 'true' : 'false' }}"
                    >

                        <div>
                            <i class="bi bi-cart3"></i>
                            <span>Sales Management</span>
                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </a>


                    <div
                        id="salesMenu"
                        class="collapse {{ request()->routeIs(
                            'admin.orders.*',
                            'admin.payments.*',
                            'admin.coupons.*',
                            'admin.shipping.*',
                            'admin.refunds.*'
                        ) ? 'show' : '' }}"
                    >

                        <ul class="menu">

                            {{-- Orders --}}
                            @can('orders.view')
                                <li>
                                    <a
                                        href="{{ route('admin.orders.index') }}"
                                        class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                    >
                                        Orders
                                    </a>
                                </li>
                            @endcan


                            {{-- Payments --}}
                            @can('payments.view')
                                <li>
                                    <a
                                        href="{{ route('admin.payments.index') }}"
                                        class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                                    >
                                        Payments
                                    </a>
                                </li>
                            @endcan


                            {{-- Coupons --}}
                            @can('coupons.view')
                                <li>
                                    <a
                                        href="{{ route('admin.coupons.index') }}"
                                        class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
                                    >
                                        Coupons
                                    </a>
                                </li>
                            @endcan


                            {{-- Shipping --}}
                            @can('shipping.view')
                                <li>
                                    <a
                                        href="{{ route('admin.shipping.index') }}"
                                        class="{{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}"
                                    >
                                        Shipping
                                    </a>
                                </li>
                            @endcan


                            {{-- Refunds --}}
                            @can('refunds.view')
                                <li>
                                    <a
                                        href="{{ route('admin.refunds.index') }}"
                                        class="{{ request()->routeIs('admin.refunds.*') ? 'active' : '' }}"
                                    >
                                        Refunds
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </div>

                </li>

            </ul>

        @endcanany


        {{-- =========================================================
             USER MANAGEMENT
        ========================================================== --}}
        @canany([
            'users.view',
            'sellers.view',
            'seller_applications.view',
            'roles.view'
        ])

            <ul class="menu">

                <li>

                    <a
                        data-bs-toggle="collapse"
                        href="#userMenu"
                        role="button"
                        aria-expanded="{{ request()->routeIs(
                            'admin.users.*',
                            'admin.sellers.*',
                            'admin.seller-applications.*',
                            'admin.roles.*'
                        ) ? 'true' : 'false' }}"
                    >

                        <div>
                            <i class="bi bi-people"></i>
                            <span>User Management</span>
                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </a>


                    <div
                        id="userMenu"
                        class="collapse {{ request()->routeIs(
                            'admin.users.*',
                            'admin.sellers.*',
                            'admin.seller-applications.*',
                            'admin.roles.*'
                        ) ? 'show' : '' }}"
                    >

                        <ul class="menu">

                            {{-- Users --}}
                            @can('users.view')
                                <li>
                                    <a
                                        href="{{ route('admin.users.index') }}"
                                        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                    >
                                        Users
                                    </a>
                                </li>
                            @endcan


                            {{-- Sellers --}}
                            @can('sellers.view')
                                <li>
                                    <a
                                        href="{{ route('admin.sellers.index') }}"
                                        class="{{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}"
                                    >
                                        Sellers
                                    </a>
                                </li>
                            @endcan


                            {{-- Seller Requests --}}
                            @can('seller_applications.view')
                                <li>
                                    <a
                                        href="{{ route('admin.seller-applications.index') }}"
                                        class="d-flex align-items-center justify-content-between {{ request()->routeIs('admin.seller-applications.*') ? 'active' : '' }}"
                                    >

                                        <span>Seller Requests</span>

                                        @if(isset($pendingSellerApplicationsCount) && $pendingSellerApplicationsCount > 0)

                                            <span class="sidebar-badge">
                                                {{ $pendingSellerApplicationsCount > 99
                                                    ? '99+'
                                                    : $pendingSellerApplicationsCount }}
                                            </span>

                                        @endif

                                    </a>
                                </li>
                            @endcan


                            {{-- Roles & Permissions --}}
                            @can('roles.view')
                                <li>
                                    <a
                                        href="{{ route('admin.roles.index') }}"
                                        class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                                    >
                                        Roles & Permissions
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </div>

                </li>

            </ul>

        @endcanany


        {{-- =========================================================
             CONTENT MANAGEMENT
        ========================================================== --}}
        @canany([
            'reviews.view',
            'banners.view',
            'blogs.view',
            'faq.view'
        ])

            <ul class="menu">

                <li>

                    <a
                        data-bs-toggle="collapse"
                        href="#contentMenu"
                        role="button"
                        aria-expanded="{{ request()->routeIs(
                            'admin.reviews.*',
                            'admin.banners.*',
                            'admin.blogs.*',
                            'admin.faq.*'
                        ) ? 'true' : 'false' }}"
                    >

                        <div>
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Content Management</span>
                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </a>


                    <div
                        id="contentMenu"
                        class="collapse {{ request()->routeIs(
                            'admin.reviews.*',
                            'admin.banners.*',
                            'admin.blogs.*',
                            'admin.faq.*'
                        ) ? 'show' : '' }}"
                    >

                        <ul class="menu">

                            {{-- Reviews --}}
                            @can('reviews.view')
                                <li>
                                    <a
                                        href="{{ route('admin.reviews.index') }}"
                                        class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                                    >
                                        Reviews
                                    </a>
                                </li>
                            @endcan


                            {{-- Banners --}}
                            @can('banners.view')
                                <li>
                                    <a
                                        href="{{ route('admin.banners.index') }}"
                                        class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                                    >
                                        Banners
                                    </a>
                                </li>
                            @endcan


                            {{-- Blogs --}}
                            @can('blogs.view')
                                <li>
                                    <a
                                        href="{{ route('admin.blogs.index') }}"
                                        class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                                    >
                                        Blog
                                    </a>
                                </li>
                            @endcan


                            {{-- FAQ --}}
                            @can('faq.view')
                                <li>
                                    <a
                                        href="{{ route('admin.faq.index') }}"
                                        class="{{ request()->routeIs('admin.faq.*') ? 'active' : '' }}"
                                    >
                                        FAQ
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </div>

                </li>

            </ul>

        @endcanany


        {{-- =========================================================
             ANALYTICS
        ========================================================== --}}
        @canany([
            'reports.view',
            'analytics.view'
        ])

            <ul class="menu">

                <li>

                    <a
                        data-bs-toggle="collapse"
                        href="#analyticsMenu"
                        role="button"
                        aria-expanded="{{ request()->routeIs(
                            'admin.reports.*',
                            'admin.analytics.*'
                        ) ? 'true' : 'false' }}"
                    >

                        <div>
                            <i class="bi bi-bar-chart-line"></i>
                            <span>Analytics</span>
                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </a>


                    <div
                        id="analyticsMenu"
                        class="collapse {{ request()->routeIs(
                            'admin.reports.*',
                            'admin.analytics.*'
                        ) ? 'show' : '' }}"
                    >

                        <ul class="menu">

                            {{-- Reports --}}
                            @can('reports.view')
                                <li>
                                    <a
                                        href="{{ route('admin.reports.index') }}"
                                        class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                                    >
                                        Reports
                                    </a>
                                </li>
                            @endcan


                            {{-- Analytics --}}
                            @can('analytics.view')
                                <li>
                                    <a
                                        href="{{ route('admin.analytics.index') }}"
                                        class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"
                                    >
                                        Analytics
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </div>

                </li>

            </ul>

        @endcanany


        {{-- =========================================================
             SYSTEM
        ========================================================== --}}
        @canany([
            'settings.view',
            'email_settings.view',
            'notifications.view',
            'activity_logs.view',
            'backup.view'
        ])

            <ul class="menu">

                <li>

                    <a
                        data-bs-toggle="collapse"
                        href="#systemMenu"
                        role="button"
                        aria-expanded="{{ request()->routeIs(
                            'admin.settings.*',
                            'admin.email-settings.*',
                            'admin.notifications.*',
                            'admin.activity.logs.*',
                            'admin.backup.*'
                        ) ? 'true' : 'false' }}"
                    >

                        <div>
                            <i class="bi bi-gear"></i>
                            <span>System</span>
                        </div>

                        <i class="bi bi-chevron-down"></i>

                    </a>


                    <div
                        id="systemMenu"
                        class="collapse {{ request()->routeIs(
                            'admin.settings.*',
                            'admin.email-settings.*',
                            'admin.notifications.*',
                            'admin.activity.logs.*',
                            'admin.backup.*'
                        ) ? 'show' : '' }}"
                    >

                        <ul class="menu">

                            {{-- Settings --}}
                            @can('settings.view')
                                <li>
                                    <a
                                        href="{{ route('admin.settings.index') }}"
                                        class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                                    >
                                        Settings
                                    </a>
                                </li>
                            @endcan


                            {{-- Email Settings --}}
                            @can('email_settings.view')
                                <li>
                                    <a
                                        href="{{ route('admin.email-settings.index') }}"
                                        class="{{ request()->routeIs('admin.email-settings.*') ? 'active' : '' }}"
                                    >
                                        Email Settings
                                    </a>
                                </li>
                            @endcan


                            {{-- Notifications --}}
                            @can('notifications.view')
                                <li>
                                    <a
                                        href="{{ route('admin.notifications.index') }}"
                                        class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
                                    >
                                        Notifications
                                    </a>
                                </li>
                            @endcan


                            {{-- Activity Logs --}}
                            @can('activity_logs.view')
                                <li>
                                    <a
                                        href="{{ route('admin.activity.logs.index') }}"
                                        class="{{ request()->routeIs('admin.activity.logs.*') ? 'active' : '' }}"
                                    >
                                        Activity Logs
                                    </a>
                                </li>
                            @endcan


                            {{-- Backup --}}
                            @can('backup.view')
                                <li>
                                    <a
                                        href="{{ route('admin.backup.index') }}"
                                        class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}"
                                    >
                                        Backup
                                    </a>
                                </li>
                            @endcan

                        </ul>

                    </div>

                </li>

            </ul>

        @endcanany

    </div>


    {{-- =============================================================
         SIDEBAR FOOTER
    ============================================================== --}}
    <div class="sidebar-footer">

        {{-- Home --}}
        <a
            href="{{ route('frontend.home') }}"
            class="sidebar-link"
        >
            <i class="bi bi-house-door"></i>
            <span>Home</span>
        </a>


        {{-- Settings --}}
        @can('settings.view')
            <a
                href="{{ route('admin.settings.index') }}"
                class="sidebar-link"
            >
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        @endcan


        {{-- Logout --}}
        <form
            action="{{ route('frontend.auth.logout') }}"
            method="POST"
            id="sidebarLogoutForm"
        >

            @csrf

            <button
                type="button"
                class="sidebar-link sidebar-btn"
                id="sidebarLogoutBtn"
            >

                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>

            </button>

        </form>

    </div>

</div>

