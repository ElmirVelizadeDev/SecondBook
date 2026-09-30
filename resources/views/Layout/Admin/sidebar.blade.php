@php

    /* =========================================================
       MENU DEFINITION
       Permissions, routes and active patterns are preserved.
    ========================================================= */

    $pending = $pendingSellerApplicationsCount ?? 0;

    $groups = [

        [
            'id'     => 'bookMenu',
            'label'  => 'Book Management',
            'icon'   => 'bi-book',
            'tone'   => 'blue',
            'perms'  => [
                'books.view',
                'book_conditions.view',
                'book_requests.view',
                'categories.view',
                'authors.view',
                'publishers.view',
            ],
            'routes' => [
                'admin.books.*',
                'admin.categories.*',
                'admin.authors.*',
                'admin.publishers.*',
                'admin.book.conditions.*',
                'admin.book.requests.*',
            ],
            'items' => [
                ['Books',           'books.view',           'admin.books.index',           'admin.books.*'],
                ['Categories',      'categories.view',      'admin.categories.index',      'admin.categories.*'],
                ['Authors',         'authors.view',         'admin.authors.index',         'admin.authors.*'],
                ['Publishers',      'publishers.view',      'admin.publishers.index',      'admin.publishers.*'],
                ['Book Conditions', 'book_conditions.view', 'admin.book.conditions.index', 'admin.book.conditions.*'],
                ['Book Requests',   'book_requests.view',   'admin.book.requests.index',   'admin.book.requests.*'],
            ],
        ],

        [
            'id'     => 'salesMenu',
            'label'  => 'Sales Management',
            'icon'   => 'bi-cart3',
            'tone'   => 'green',
            'perms'  => [
                'orders.view',
                'payments.view',
                'coupons.view',
                'shipping.view',
                'refunds.view',
            ],
            'routes' => [
                'admin.orders.*',
                'admin.payments.*',
                'admin.coupons.*',
                'admin.shipping.*',
                'admin.refunds.*',
            ],
            'items' => [
                ['Orders',   'orders.view',   'admin.orders.index',   'admin.orders.*'],
                ['Payments', 'payments.view', 'admin.payments.index', 'admin.payments.*'],
                ['Coupons',  'coupons.view',  'admin.coupons.index',  'admin.coupons.*'],
                ['Shipping', 'shipping.view', 'admin.shipping.index', 'admin.shipping.*'],
                ['Refunds',  'refunds.view',  'admin.refunds.index',  'admin.refunds.*'],
            ],
        ],

        [
            'id'     => 'userMenu',
            'label'  => 'User Management',
            'icon'   => 'bi-people',
            'tone'   => 'violet',
            'dot'    => $pending,
            'perms'  => [
                'users.view',
                'sellers.view',
                'seller_applications.view',
                'roles.view',
            ],
            'routes' => [
                'admin.users.*',
                'admin.sellers.*',
                'admin.seller-applications.*',
                'admin.roles.*',
            ],
            'items' => [
                ['Users',               'users.view',              'admin.users.index',               'admin.users.*'],
                ['Sellers',             'sellers.view',            'admin.sellers.index',             'admin.sellers.*'],
                ['Seller Requests',     'seller_applications.view','admin.seller-applications.index', 'admin.seller-applications.*', 'badge' => $pending],
                ['Roles & Permissions', 'roles.view',              'admin.roles.index',               'admin.roles.*'],
            ],
        ],

        [
            'id'     => 'contentMenu',
            'label'  => 'Content Management',
            'icon'   => 'bi-file-earmark-text',
            'tone'   => 'amber',
            'perms'  => [
                'reviews.view',
                'banners.view',
                'blogs.view',
                'faq.view',
            ],
            'routes' => [
                'admin.reviews.*',
                'admin.banners.*',
                'admin.blogs.*',
                'admin.faq.*',
            ],
            'items' => [
                ['Reviews', 'reviews.view', 'admin.reviews.index', 'admin.reviews.*'],
                ['Banners', 'banners.view', 'admin.banners.index', 'admin.banners.*'],
                ['Blog',    'blogs.view',   'admin.blogs.index',   'admin.blogs.*'],
                ['FAQ',     'faq.view',     'admin.faq.index',     'admin.faq.*'],
            ],
        ],

        [
            'id'     => 'analyticsMenu',
            'label'  => 'Analytics',
            'icon'   => 'bi-bar-chart-line',
            'tone'   => 'cyan',
            'perms'  => [
                'reports.view',
                'analytics.view',
            ],
            'routes' => [
                'admin.reports.*',
                'admin.analytics.*',
            ],
            'items' => [
                ['Reports',   'reports.view',   'admin.reports.index',   'admin.reports.*'],
                ['Analytics', 'analytics.view', 'admin.analytics.index', 'admin.analytics.*'],
            ],
        ],

        [
            'id'     => 'systemMenu',
            'label'  => 'System',
            'icon'   => 'bi-gear',
            'tone'   => 'slate',
            'perms'  => [
                'settings.view',
                'email_settings.view',
                'notifications.view',
                'activity_logs.view',
                'backup.view',
            ],
            'routes' => [
                'admin.settings.*',
                'admin.email-settings.*',
                'admin.notifications.*',
                'admin.activity.logs.*',
                'admin.backup.*',
            ],
            'items' => [
                ['Settings',       'settings.view',       'admin.settings.index',       'admin.settings.*'],
                ['Email Settings', 'email_settings.view', 'admin.email-settings.index', 'admin.email-settings.*'],
                ['Notifications',  'notifications.view',  'admin.notifications.index',  'admin.notifications.*'],
                ['Activity Logs',  'activity_logs.view',  'admin.activity.logs.index',  'admin.activity.logs.*'],
                ['Backup',         'backup.view',         'admin.backup.index',         'admin.backup.*'],
            ],
        ],

    ];

    $index = 0;

@endphp


<div
    class="sidebar sb show"
    id="adminSidebar"
    aria-label="Admin sidebar"
>

    {{-- =====================================================
         BRAND
    ====================================================== --}}
    <div class="sb-brand">

        <div class="sb-logo">
            <img
                src="{{ asset('admin/images/logo2.png') }}"
                alt="SecondBook Logo"
            >
        </div>

        <div class="sb-brand-text">
            <strong>SecondBook</strong>
            <small>Admin Panel</small>
        </div>

        <button
            class="sidebar-close sb-close"
            id="closeSidebar"
            type="button"
            aria-label="Close menu"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>


    {{-- =====================================================
         NAVIGATION
    ====================================================== --}}
    <nav
        class="sb-scroll"
        aria-label="Admin navigation"
    >

        {{-- Dashboard --}}
        @can('dashboard.view')

            <a
                href="{{ route('admin.dashboard') }}"
                class="sb-dash {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                style="--i: {{ $index++ }}"
                @if(request()->routeIs('admin.dashboard'))
                    aria-current="page"
                @endif
            >
                <span class="sb-ic sb-tone-blue">
                    <i class="bi bi-grid"></i>
                </span>

                <span>Dashboard</span>
            </a>

        @endcan


        {{-- Section label --}}
        <span
            class="sb-label"
            style="--i: {{ $index++ }}"
        >
            Control panel
        </span>


        {{-- Groups --}}
        @foreach($groups as $group)

            @canany($group['perms'])

                @php
                    $open = request()->routeIs(...$group['routes']);
                @endphp

                <div
                    class="sb-group {{ $open ? 'is-current' : '' }}"
                    style="--i: {{ $index++ }}"
                >

                    <a
                        class="sb-toggle sb-tone-{{ $group['tone'] }}"
                        data-bs-toggle="collapse"
                        href="#{{ $group['id'] }}"
                        role="button"
                        aria-expanded="{{ $open ? 'true' : 'false' }}"
                        aria-controls="{{ $group['id'] }}"
                    >

                        <span class="sb-ic">
                            <i class="bi {{ $group['icon'] }}"></i>
                        </span>

                        <span class="sb-name">
                            {{ $group['label'] }}
                        </span>

                        @if(($group['dot'] ?? 0) > 0)

                            <span
                                class="sb-dot"
                                title="Pending requests"
                                aria-label="Pending requests"
                            ></span>

                        @endif

                        <i class="bi bi-chevron-down sb-caret"></i>

                    </a>


                    <div
                        class="collapse {{ $open ? 'show' : '' }}"
                        id="{{ $group['id'] }}"
                    >

                        <div class="sb-sub">

                            @foreach($group['items'] as $item)

                                @can($item[1])

                                    @php
                                        $current = request()->routeIs($item[3]);
                                        $badge   = $item['badge'] ?? 0;
                                    @endphp

                                    <a
                                        href="{{ route($item[2]) }}"
                                        class="sb-sublink {{ $current ? 'active' : '' }}"
                                        @if($current)
                                            aria-current="page"
                                        @endif
                                    >

                                        <span>
                                            {{ $item[0] }}
                                        </span>

                                        @if($badge > 0)

                                            <span class="sb-badge">
                                                {{ $badge > 99 ? '99+' : $badge }}
                                            </span>

                                        @endif

                                    </a>

                                @endcan

                            @endforeach

                        </div>

                    </div>

                </div>

            @endcanany

        @endforeach

    </nav>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}
    <div class="sb-footer">

        <div class="sb-foot-grid">

            {{-- Home --}}
            <a
                href="{{ route('frontend.home') }}"
                class="sb-foot-link"
            >
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>


            {{-- Settings --}}
            @can('settings.view')

                <a
                    href="{{ route('admin.settings.index') }}"
                    class="sb-foot-link"
                >
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>

            @endcan

        </div>


        {{-- Logout --}}
        <form
            action="{{ route('frontend.auth.logout') }}"
            method="POST"
            id="sidebarLogoutForm"
        >

            @csrf

            <button
                type="button"
                class="sb-logout"
                id="sidebarLogoutBtn"
            >
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>

        </form>

    </div>

</div>