<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom px-3 px-md-4 py-3">

    <div class="container-fluid p-0">

        {{-- Left Side --}}
        <div class="d-flex align-items-center">

            {{-- Sidebar Toggle --}}
            <button
                class="header-toggle me-2 me-md-3 is-active"
                id="toggleSidebar"
                type="button"
                aria-label="Open / close menu"
                aria-expanded="true"
            >
                <span class="hamburger-box" aria-hidden="true">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </span>
            </button>


            {{-- Page Title --}}
            <div>
                <h4 class="mb-0 fw-bold header-title">
                    @yield('title', 'Dashboard')
                </h4>

                <small class="text-muted d-none d-sm-inline">
                    Welcome back, {{ auth()->user()->name }} 👋
                </small>
            </div>

        </div>


        {{-- Right Side --}}
        <div class="d-flex align-items-center">

            {{-- Notifications --}}
            <a
                href="{{ route('admin.notifications.index') }}"
                class="header-icon me-2 me-md-3 text-decoration-none position-relative"
                aria-label="Notifications"
            >
                <i class="bi bi-bell"></i>

                @if($unreadNotificationsCount > 0)
                    <span class="notification-badge">
                        {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                    </span>
                @endif
            </a>


            {{-- Messages --}}
            <a
                href="{{ route('admin.messages.index') }}"
                class="header-icon header-message me-2 me-md-3 me-lg-4 text-decoration-none position-relative"
                aria-label="Messages"
            >
                <i class="bi bi-chat-dots"></i>

                @if($unreadMessagesCount > 0)
                    <span class="notification-badge">
                        {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                    </span>
                @endif
            </a>


            {{-- Profile --}}
            <div class="dropdown">

                @auth

                    <a
                        href="#"
                        class="d-flex align-items-center text-decoration-none"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        {{-- Profile Image --}}
                        @if(auth()->user()->avatar)

                            <img
                                src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                class="profile-image"
                                alt="{{ auth()->user()->name }}"
                            >

                        @else

                            <img
                                src="{{ asset('profile-icon.png') }}"
                                class="profile-image"
                                alt="{{ auth()->user()->name }}"
                            >

                        @endif


                        {{-- User Information --}}
                        <div class="ms-3 d-none d-sm-block">

                            <h6 class="mb-0 fw-semibold">
                                {{ auth()->user()->name }}
                            </h6>

                            <small class="text-muted">
                                {{ auth()->user()->roles->first()?->display_name ?? 'Administrator' }}
                            </small>

                        </div>


                        {{-- Dropdown Icon --}}
                        <i class="bi bi-chevron-down ms-2 ms-md-3 text-secondary d-none d-sm-inline"></i>

                    </a>


                    {{-- Profile Dropdown --}}
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">

                        {{-- Profile --}}
                        <li>
                            <a
                                class="dropdown-item py-2"
                                href="{{ route('my.profile') }}"
                            >
                                <i class="bi bi-person me-2"></i>
                                Profile
                            </a>
                        </li>


                        {{-- Settings --}}
                        <li>
                            <a
                                class="dropdown-item py-2"
                                href="{{ route('admin.settings.index') }}"
                            >
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </a>
                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        {{-- Logout --}}
                        <li>

                            <form
                                action="{{ route('frontend.auth.logout') }}"
                                method="POST"
                                id="logoutForm"
                            >

                                @csrf

                                <button
                                    type="button"
                                    class="dropdown-item text-danger py-2"
                                    id="logoutBtn"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>

                            </form>

                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        {{-- Dark Mode --}}
                        <li>

                            <a
                                class="dropdown-item py-2"
                                href="#"
                                id="themeToggleBtn"
                            >

                                <i
                                    class="bi bi-moon-stars me-2"
                                    id="themeToggleIcon"
                                ></i>

                                <span id="themeToggleLabel">
                                    Switch to Dark Mode
                                </span>

                            </a>

                        </li>

                    </ul>

                @endauth

            </div>

        </div>

    </div>

</nav>
