<div id="header-wrap">

    {{-- ===================== TOP BAR ===================== --}}
    <div class="top-content">
        <div class="container-fluid">
            <div class="top-content-inner">

                {{-- LEFT --}}
                <div class="top-left">
                    <i class="bi bi-book"></i>
                    <span>SecondBook Marketplace</span>
                </div>

                {{-- CENTER --}}
                <div class="top-center">
                    <div class="top-center-item">
                        <i class="bi bi-truck"></i>
                        <span>Fast Delivery</span>
                    </div>

                    <div class="top-center-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Secure Shopping</span>
                    </div>

                    <div class="top-center-item">
                        <i class="bi bi-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="top-right">

                    @auth

                        @php
                            $unreadCount = $unreadCount
                                ?? \App\Models\Notification::where('user_id', auth()->id())
                                    ->whereNull('read_at')
                                    ->count();

                            $headerNotifications = \App\Models\Notification::where(
                                'user_id',
                                auth()->id()
                            )
                                ->latest()
                                ->take(5)
                                ->get();

                            $cartCount = auth()->user()->cart?->items?->sum('quantity') ?? 0;
                        @endphp

                        {{-- WISHLIST --}}
                        <a href="{{ route('frontend.wishlist') }}"
                           class="header-action header-wishlist"
                           aria-label="Wishlist">

                            <i class="bi bi-heart"></i>

                            <span class="header-action-label">
                                Wishlist
                            </span>

                        </a>

                        {{-- CART --}}
                        <a href="{{ route('frontend.cart') }}"
                           class="header-action header-cart"
                           aria-label="Cart">

                            <i class="bi bi-bag"></i>

                            <span class="header-action-label">
                                Cart
                            </span>

                            @if($cartCount > 0)
                                <span class="cart-count" id="header-cart-count">
                                    {{ $cartCount }}
                                </span>
                            @endif

                        </a>

                        {{-- NOTIFICATIONS --}}
                        <div class="header-dropdown-wrapper header-notification">

                            <button type="button"
                                    class="header-action notification-trigger"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    data-bs-display="static"
                                    aria-expanded="false"
                                    aria-label="Notifications">

                                <i class="bi bi-bell"></i>

                                <span class="header-action-label">
                                    Notifications
                                </span>

                                @if($unreadCount > 0)
                                    <span class="notification-badge">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif

                            </button>

                            <div class="dropdown-menu header-dropdown notification-dropdown">

                                <div class="header-dropdown-header">

                                    <h6 class="header-dropdown-title">
                                        Notifications
                                    </h6>

                                    @if($unreadCount > 0)

                                        <form action="{{ route('frontend.notifications.read-all') }}"
                                              method="POST"
                                              class="notification-read-all-form">

                                            @csrf

                                            <button type="submit"
                                                    class="header-dropdown-link border-0 bg-transparent">

                                                Mark all as read

                                            </button>

                                        </form>

                                    @endif

                                </div>

                                <div class="notification-list">

                                    @forelse($headerNotifications as $notification)

                                        <div class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                                             data-notification-id="{{ $notification->id }}">

                                            <a href="{{ route('frontend.notifications.index') }}"
                                               class="notification-content-link">

                                                <div class="notification-item-title">
                                                    {{ $notification->title }}
                                                </div>

                                                <p class="notification-item-message">
                                                    {{ $notification->message }}
                                                </p>

                                                <span class="notification-item-time">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>

                                            </a>

                                            <button type="button"
                                                    class="notification-delete-btn"
                                                    data-id="{{ $notification->id }}"
                                                    aria-label="Delete notification">

                                                <i class="bi bi-x"></i>

                                            </button>

                                        </div>

                                    @empty

                                        <div class="notification-empty">

                                            <i class="bi bi-bell-slash"></i>

                                            <p>
                                                No notifications yet.
                                            </p>

                                        </div>

                                    @endforelse

                                </div>

                                @if($headerNotifications->count() > 0)

                                    <div class="notification-dropdown-footer">

                                        <a href="{{ route('frontend.notifications.index') }}">
                                            View all notifications
                                        </a>

                                    </div>

                                @endif

                            </div>

                        </div>

                        {{-- PROFILE --}}
                        <div class="header-dropdown-wrapper header-profile">

                            <button type="button"
                                    class="header-profile-trigger"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    data-bs-display="static"
                                    aria-expanded="false"
                                    aria-label="Profile menu">

                                <span class="header-profile-avatar">

                                    <img src="{{ Auth::user()->avatar
                                        ? asset('storage/' . Auth::user()->avatar)
                                        : asset('profile-icon.png') }}"
                                         alt="{{ Auth::user()->name }}">

                                </span>

                                <span class="header-profile-name">
                                    {{ Auth::user()->name }}
                                </span>

                                <i class="bi bi-chevron-down"></i>

                            </button>

                            <div class="dropdown-menu header-dropdown profile-dropdown">

                                <div class="profile-dropdown-user">

                                    <div class="profile-dropdown-avatar">

                                        <img src="{{ Auth::user()->avatar
                                            ? asset('storage/' . Auth::user()->avatar)
                                            : asset('profile-icon.png') }}"
                                             alt="{{ Auth::user()->name }}">

                                    </div>

                                    <div class="profile-dropdown-user-info">

                                        <p class="profile-dropdown-user-name">
                                            {{ Auth::user()->name }}
                                        </p>

                                        <p class="profile-dropdown-user-email">
                                            {{ Auth::user()->email }}
                                        </p>

                                    </div>

                                </div>

                                <div class="profile-menu">

                                    <a href="{{ route('my.profile') }}"
                                       class="profile-menu-item">

                                        <i class="bi bi-person"></i>
                                        <span>My Profile</span>

                                    </a>

                                    <a href="{{ route('frontend.orders') }}"
                                       class="profile-menu-item">

                                        <i class="bi bi-box-seam"></i>
                                        <span>My Orders</span>

                                    </a>

                                    <a href="{{ route('frontend.wishlist') }}"
                                       class="profile-menu-item">

                                        <i class="bi bi-heart"></i>
                                        <span>Wishlist</span>

                                    </a>

                                    <a href="{{ route('frontend.account.settings') }}"
                                       class="profile-menu-item">

                                        <i class="bi bi-gear"></i>
                                        <span>Account Settings</span>

                                    </a>

                                    <div class="profile-menu-divider"></div>

                                    @if(Auth::user()->role === 'admin')

                                        <a href="{{ route('admin.dashboard') }}"
                                           class="profile-menu-item profile-admin">

                                            <i class="bi bi-speedometer2"></i>
                                            <span>Admin Panel</span>

                                        </a>

                                    @elseif(Auth::user()->role === 'seller')

                                        <a href="{{ route('seller.dashboard') }}"
                                           class="profile-menu-item profile-seller">

                                            <i class="bi bi-shop"></i>
                                            <span>Seller Panel</span>

                                        </a>

                                    @else

                                        <a href="{{ route('frontend.seller-application') }}"
                                           class="profile-menu-item profile-become-seller">

                                            <i class="bi bi-shop"></i>
                                            <span>Become a Seller</span>

                                        </a>

                                    @endif

                                    <div class="profile-menu-divider"></div>

                                    <form action="{{ route('frontend.auth.logout') }}"
                                          method="POST">

                                        @csrf

                                        <button type="submit"
                                                class="profile-menu-item profile-logout">

                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Logout</span>

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @else

                        {{-- GUEST --}}
                        <a href="{{ route('frontend.auth.login') }}"
                           class="header-auth-link header-login">

                            Login

                        </a>

                        <a href="{{ route('frontend.auth.register') }}"
                           class="header-auth-link header-register">

                            Register

                        </a>

                    @endauth

                </div>

            </div>
        </div>
    </div>


    {{-- ===================== MAIN NAVBAR ===================== --}}
    <header id="header">

        <div class="container-fluid">

            <div class="main-header">

                {{-- LOGO --}}
                <div class="main-logo">

                    <a href="{{ route('frontend.home') }}">

                        <img src="{{ asset('main-logo.png') }}"
                             alt="SecondBook">

                    </a>

                </div>


                {{-- MOBILE TOGGLER --}}
                <button class="header-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#mainNav"
                        aria-controls="mainNav"
                        aria-expanded="false"
                        aria-label="Toggle navigation">

                    <i class="bi bi-list"></i>

                </button>


                {{-- NAVIGATION --}}
                <nav class="header-nav collapse"
                     id="mainNav">

                    {{-- SEARCH --}}
                    <div class="nav-search-item">

                        <form action="{{ route('frontend.books') }}"
                              method="GET"
                              class="navbar-search-form">

                            <div class="navbar-search">

                                <i class="bi bi-search"></i>

                                <input type="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search books..."
                                       autocomplete="off">

                                <button type="submit"
                                        aria-label="Search">

                                    <i class="bi bi-arrow-right"></i>

                                </button>

                            </div>

                        </form>

                    </div>


                    {{-- HORIZONTAL MENU --}}
                    <div class="nav-menu-wrapper">

                        {{-- LEFT ARROW --}}
                        <button type="button"
                                class="nav-scroll-btn nav-scroll-left"
                                aria-label="Scroll navigation left">

                            <i class="bi bi-chevron-left"></i>

                        </button>


                        {{-- SCROLL AREA --}}
                        <div class="nav-scroll-area">

                            <ul class="menu-list">

                                <li>
                                    <a href="{{ route('frontend.home') }}"
                                       class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}">

                                        Home

                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('frontend.books') }}"
                                       class="{{ request()->routeIs('frontend.books', 'frontend.books.*') ? 'active' : '' }}">

                                        Books

                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('frontend.categories') }}"
                                       class="{{ request()->routeIs('frontend.categories', 'frontend.categories.*') ? 'active' : '' }}">

                                        Categories

                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('frontend.authors') }}"
                                       class="{{ request()->routeIs('frontend.authors', 'frontend.authors.*') ? 'active' : '' }}">

                                        Authors

                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('frontend.about') }}"
                                       class="{{ request()->routeIs('frontend.about') ? 'active' : '' }}">

                                        About

                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('frontend.contact') }}"
                                       class="{{ request()->routeIs('frontend.contact') ? 'active' : '' }}">

                                        Contact

                                    </a>
                                </li>

                            </ul>

                        </div>


                        {{-- RIGHT ARROW --}}
                        <button type="button"
                                class="nav-scroll-btn nav-scroll-right"
                                aria-label="Scroll navigation right">

                            <i class="bi bi-chevron-right"></i>

                        </button>

                    </div>

                </nav>

            </div>

        </div>

    </header>

</div>


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       SELLER APPLICATION SUCCESS MESSAGE
       ========================================================= */

    @if(session('success'))

        if (typeof Swal !== 'undefined') {

            Swal.fire({
                toast: true,
                position: 'top-end',

                icon: 'success',

                title: 'Seller application submitted',

                text: @json(session('success')),

                showConfirmButton: false,

                timer: 5000,

                timerProgressBar: true,

                background: '#ffffff',

                color: '#241c17',

                customClass: {
                    popup: 'secondbook-toast'
                },

                didOpen: function (toast) {

                    toast.addEventListener(
                        'mouseenter',
                        Swal.stopTimer
                    );

                    toast.addEventListener(
                        'mouseleave',
                        Swal.resumeTimer
                    );
                }
            });

        }

    @endif


    /* =========================================================
       DELETE NOTIFICATION
       ========================================================= */

    document.querySelectorAll('.notification-delete-btn').forEach(function (button) {

        button.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            const notificationId = this.dataset.id;
            const notificationItem =
                this.closest('.notification-item');

            if (!notificationId) {
                return;
            }

            if (typeof Swal !== 'undefined') {

                Swal.fire({

                    title: 'Delete notification?',

                    text: 'This notification will be permanently removed.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Delete',

                    cancelButtonText: 'Cancel',

                    reverseButtons: true

                }).then(function (result) {

                    if (result.isConfirmed) {

                        deleteNotification(
                            notificationId,
                            notificationItem
                        );

                    }

                });

            } else {

                deleteNotification(
                    notificationId,
                    notificationItem
                );

            }

        });

    });


    /* =========================================================
       DELETE REQUEST
       ========================================================= */

    function deleteNotification(
        notificationId,
        notificationItem
    ) {

        fetch(
            "{{ url('/frontend-assets/notifications') }}/" +
            notificationId,
            {
                method: 'DELETE',

                headers: {

                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                }

            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Request failed.'
                );

            }

            return response.json();

        })

        .then(function (data) {

            if (!data.success) {

                throw new Error(
                    data.message ||
                    'Something went wrong.'
                );

            }

            if (notificationItem) {

                notificationItem.remove();

            }


            const trigger =
                document.querySelector(
                    '.notification-trigger'
                );

            if (trigger) {

                let badge =
                    trigger.querySelector(
                        '.notification-badge'
                    );

                const unreadCount =
                    Number(
                        data.unread_count || 0
                    );

                if (unreadCount > 0) {

                    if (!badge) {

                        badge =
                            document.createElement(
                                'span'
                            );

                        badge.className =
                            'notification-badge';

                        trigger.appendChild(
                            badge
                        );

                    }

                    badge.textContent =
                        unreadCount > 99
                            ? '99+'
                            : unreadCount;

                } else if (badge) {

                    badge.remove();

                }

            }


            const list =
                document.querySelector(
                    '.notification-list'
                );

            if (
                list &&
                list.querySelectorAll(
                    '.notification-item'
                ).length === 0
            ) {

                list.innerHTML =
                    '<div class="notification-empty">' +
                        '<i class="bi bi-bell-slash"></i>' +
                        '<p>No notifications yet.</p>' +
                    '</div>';

            }


            if (typeof Swal !== 'undefined') {

                Swal.fire({

                    icon: 'success',

                    title: 'Deleted',

                    text:
                        data.message ||
                        'Notification deleted successfully.',

                    timer: 1600,

                    showConfirmButton: false

                });

            }

        })

        .catch(function (error) {

            console.error(error);

            if (typeof Swal !== 'undefined') {

                Swal.fire({

                    icon: 'error',

                    title: 'Error',

                    text:
                        'Unable to delete the notification.'

                });

            }

        });

    }


    /* =========================================================
       MOBILE HORIZONTAL NAVIGATION
       ========================================================= */

    const navScrollArea =
        document.querySelector(
            '.nav-scroll-area'
        );

    const navScrollLeft =
        document.querySelector(
            '.nav-scroll-left'
        );

    const navScrollRight =
        document.querySelector(
            '.nav-scroll-right'
        );


    function updateNavigationArrows() {

        if (
            !navScrollArea ||
            !navScrollLeft ||
            !navScrollRight
        ) {

            return;

        }

        const maxScroll =
            navScrollArea.scrollWidth -
            navScrollArea.clientWidth;

        const currentScroll =
            navScrollArea.scrollLeft;

        const hasOverflow =
            maxScroll > 2;


        if (!hasOverflow) {

            navScrollLeft.classList.remove(
                'is-visible'
            );

            navScrollRight.classList.remove(
                'is-visible'
            );

            return;

        }


        if (currentScroll <= 2) {

            navScrollLeft.classList.remove(
                'is-visible'
            );

        } else {

            navScrollLeft.classList.add(
                'is-visible'
            );

        }


        if (currentScroll >= maxScroll - 2) {

            navScrollRight.classList.remove(
                'is-visible'
            );

        } else {

            navScrollRight.classList.add(
                'is-visible'
            );

        }

    }


    if (navScrollArea) {

        navScrollLeft?.addEventListener(
            'click',
            function () {

                navScrollArea.scrollBy({

                    left: -220,

                    behavior: 'smooth'

                });

            }
        );


        navScrollRight?.addEventListener(
            'click',
            function () {

                navScrollArea.scrollBy({

                    left: 220,

                    behavior: 'smooth'

                });

            }
        );


        navScrollArea.addEventListener(
            'scroll',
            updateNavigationArrows,
            {
                passive: true
            }
        );


        window.addEventListener(
            'resize',
            updateNavigationArrows
        );


        setTimeout(
            updateNavigationArrows,
            100
        );

    }

});
</script>

@endpush