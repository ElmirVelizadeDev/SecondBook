@php

    $hxUser = auth()->user();

    $hxAvatar = $hxUser?->avatar
        ? asset('storage/' . $hxUser->avatar)
        : asset('profile-icon.png');

    $hxRole = $hxUser?->roles->first()?->display_name
        ?? 'Administrator';

@endphp

<header class="hx-header" id="hxHeader">

    <div class="hx-inner">

        {{-- ================= LEFT ================= --}}

        <div class="hx-left">

            {{-- Sidebar toggle --}}
            <button
                class="hx-toggle is-active"
                id="toggleSidebar"
                type="button"
                aria-label="Open / close menu"
                aria-expanded="true"
            >
                <span class="hx-burger" aria-hidden="true">
                    <i></i>
                    <i></i>
                    <i></i>
                </span>
            </button>


            {{-- Page title --}}
            <div class="hx-titles">

                <div
                    class="hx-title"
                    role="heading"
                    aria-level="1"
                >
                    @yield('title', 'Dashboard')
                </div>

                <p class="hx-sub">
                    Welcome back, {{ auth()->user()->name }}
                    <span class="hx-wave">👋</span>
                </p>

            </div>

        </div>


        {{-- ================= RIGHT ================= --}}

        <div class="hx-right">

            {{-- Notifications --}}
            <a
                href="{{ route('admin.notifications.index') }}"
                class="hx-icon-btn {{ $unreadNotificationsCount > 0 ? 'has-unread' : '' }}"
                aria-label="Notifications"
            >
                <i class="bi bi-bell"></i>

                @if($unreadNotificationsCount > 0)

                    <span class="hx-badge notification-badge">
                        {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                    </span>

                @endif
            </a>


            {{-- Messages --}}
            <a
                href="{{ route('admin.messages.index') }}"
                class="hx-icon-btn hx-chat header-message"
                aria-label="Messages"
            >
                <i class="bi bi-chat-dots"></i>

                @if($unreadMessagesCount > 0)

                    <span class="hx-badge notification-badge">
                        {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                    </span>

                @endif
            </a>


            {{-- Separator --}}
            <span
                class="hx-sep"
                aria-hidden="true"
            ></span>


            {{-- Profile --}}
            <div class="hx-profile-wrap">

                @auth

                    {{-- Profile trigger --}}
                    <a
                        href="#"
                        class="hx-profile"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >

                        <span class="hx-avatar">

                            <img
                                src="{{ $hxAvatar }}"
                                alt="{{ auth()->user()->name }}"
                            >

                        </span>


                        <span class="hx-who">

                            <strong>
                                {{ auth()->user()->name }}
                            </strong>

                            <small>
                                {{ $hxRole }}
                            </small>

                        </span>


                        <i class="bi bi-chevron-down hx-caret"></i>

                    </a>


                    {{-- Profile dropdown --}}
                    <ul class="dropdown-menu dropdown-menu-end hx-menu">

                        {{-- Identity card --}}
                        <li>

                            <div class="hx-menu-head">

                                <img
                                    src="{{ $hxAvatar }}"
                                    alt="{{ auth()->user()->name }}"
                                >

                                <div>

                                    <strong>
                                        {{ auth()->user()->name }}
                                    </strong>

                                    @if(auth()->user()->email)

                                        <span>
                                            {{ auth()->user()->email }}
                                        </span>

                                    @endif

                                    <em>
                                        {{ $hxRole }}
                                    </em>

                                </div>

                            </div>

                        </li>


                        {{-- Profile --}}
                        <li>

                            <a
                                class="dropdown-item hx-item"
                                href="{{ route('my.profile') }}"
                            >

                                <span class="hx-ic hx-tone-blue">
                                    <i class="bi bi-person"></i>
                                </span>

                                Profile

                                <i class="bi bi-chevron-right hx-go"></i>

                            </a>

                        </li>


                        {{-- Settings --}}
                        <li>

                            <a
                                class="dropdown-item hx-item"
                                href="{{ route('admin.settings.index') }}"
                            >

                                <span class="hx-ic hx-tone-violet">
                                    <i class="bi bi-gear"></i>
                                </span>

                                Settings

                                <i class="bi bi-chevron-right hx-go"></i>

                            </a>

                        </li>


                        {{-- Dark / Light mode --}}
                        <li>

                            <a
                                class="dropdown-item hx-item hx-theme-item"
                                href="#"
                                id="themeToggleBtn"
                                role="button"
                            >

                                <span class="hx-ic hx-tone-amber">

                                    <i
                                        class="bi bi-moon-stars"
                                        id="themeToggleIcon"
                                    ></i>

                                </span>

                                <span id="themeToggleLabel">
                                    Switch to Dark Mode
                                </span>

                                <span
                                    class="hx-switch"
                                    aria-hidden="true"
                                ></span>

                            </a>

                        </li>


                        {{-- Divider --}}
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
                                    class="dropdown-item hx-item hx-danger"
                                    id="logoutBtn"
                                >

                                    <span class="hx-ic hx-tone-red">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </span>

                                    Logout

                                </button>

                            </form>

                        </li>

                    </ul>

                @endauth

            </div>

        </div>

    </div>


    {{-- Scroll progress line --}}
    <span
        class="hx-progress"
        aria-hidden="true"
    ></span>

</header>


<script>

(function () {

    'use strict';


    const header =
        document.getElementById('hxHeader');

    if (!header) {
        return;
    }


    /* =========================================================
       SIDEBAR ARIA STATE
    ========================================================= */

    const toggle =
        document.getElementById('toggleSidebar');

    if (toggle) {

        const sync = function () {

            toggle.setAttribute(
                'aria-expanded',
                toggle.classList.contains('is-active')
                    ? 'true'
                    : 'false'
            );

        };

        sync();

        new MutationObserver(sync).observe(
            toggle,
            {
                attributes: true,
                attributeFilter: ['class']
            }
        );

    }


    /* =========================================================
       SCROLL STATE + PROGRESS
    ========================================================= */

    let frame = null;
    let target = null;


    const paint = function () {

        frame = null;

        if (!target) {
            return;
        }

        const top =
            target.scrollTop;

        const max =
            target.scrollHeight -
            target.clientHeight;


        header.classList.toggle(
            'is-scrolled',
            top > 6
        );


        header.style.setProperty(
            '--hx-p',
            max > 0
                ? Math.min(
                    1,
                    top / max
                ).toFixed(3)
                : 0
        );

    };


    document.addEventListener(
        'scroll',
        function (event) {

            let el = event.target;


            if (
                el === document ||
                el === document.body ||
                el === document.documentElement
            ) {

                el =
                    document.scrollingElement ||
                    document.documentElement;

            }


            if (
                !el ||
                header.contains(el) ||
                (
                    el !== document.scrollingElement &&
                    el.clientHeight <
                    window.innerHeight * 0.5
                )
            ) {

                return;

            }


            target = el;


            if (!frame) {

                frame =
                    requestAnimationFrame(paint);

            }

        },
        {
            capture: true,
            passive: true
        }
    );


    /* =========================================================
       THEME TOGGLE
       Dropdown remains open
    ========================================================= */

    const themeBtn =
        document.getElementById('themeToggleBtn');

    const themeIcon =
        document.getElementById('themeToggleIcon');

    const themeLabel =
        document.getElementById('themeToggleLabel');


    function updateThemeUI(theme) {

        const isDark =
            theme === 'dark';


        if (themeIcon) {

            themeIcon.className =
                isDark
                    ? 'bi bi-sun'
                    : 'bi bi-moon-stars';

        }


        if (themeLabel) {

            themeLabel.textContent =
                isDark
                    ? 'Switch to Light Mode'
                    : 'Switch to Dark Mode';

        }

    }


    function applyTheme(theme) {

        const safeTheme =
            theme === 'dark'
                ? 'dark'
                : 'light';


        if (
            typeof window.setAdminTheme ===
            'function'
        ) {

            window.setAdminTheme(
                safeTheme
            );

        } else {

            document.documentElement.setAttribute(
                'data-theme',
                safeTheme
            );

            localStorage.setItem(
                'admin_theme',
                safeTheme
            );

        }


        updateThemeUI(
            safeTheme
        );

    }


    if (themeBtn) {

        themeBtn.addEventListener(
            'click',
            function (event) {

                /*
                 * Very important:
                 * prevents "#" navigation
                 * and stops Bootstrap from closing
                 * the profile dropdown.
                 */

                event.preventDefault();
                event.stopPropagation();


                const currentTheme =
                    document.documentElement
                        .getAttribute('data-theme')
                    || 'light';


                const nextTheme =
                    currentTheme === 'dark'
                        ? 'light'
                        : 'dark';


                applyTheme(
                    nextTheme
                );

            },
            true
        );

    }


    /* =========================================================
       INITIAL THEME UI
    ========================================================= */

    const initialTheme =
        document.documentElement
            .getAttribute('data-theme')
        || 'light';


    updateThemeUI(
        initialTheme
    );

})();

</script>