<aside class="seller-sidebar">

    {{-- Brand --}}
    <div class="seller-brand">

        <div class="seller-brand-icon">
            <i class="bi bi-book-half"></i>
        </div>

        <div class="seller-brand-text">
            <h5>SecondBook</h5>
            <span>Seller Panel</span>
        </div>

    </div>


    {{-- Navigation --}}
    <nav class="seller-nav">

        {{-- Main --}}
        <div class="seller-nav-label">
            MAIN
        </div>

        <a href="{{ route('seller.dashboard') }}"
           class="seller-nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2"></i>

            <span>Dashboard</span>

        </a>


        {{-- Store --}}
        <div class="seller-nav-label">
            STORE
        </div>

        <a href="{{ route('seller.store') }}"
           class="seller-nav-link {{ request()->routeIs('seller.store') ? 'active' : '' }}">

            <i class="bi bi-shop"></i>

            <span>My Store</span>

        </a>

        <a href="{{ route('seller.books.index') }}"
           class="seller-nav-link {{ request()->routeIs('seller.books.*') ? 'active' : '' }}">

            <i class="bi bi-book"></i>

            <span>My Books</span>

        </a>


        {{-- Sales --}}
        <div class="seller-nav-label">
            SALES
        </div>

        <a href="{{ route('seller.orders.index') }}"
           class="seller-nav-link {{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">

            <i class="bi bi-bag"></i>

            <span>Orders</span>

        </a>

        <a href="{{ route('seller.sales.index') }}"
           class="seller-nav-link {{ request()->routeIs('seller.sales.*') ? 'active' : '' }}">

            <i class="bi bi-graph-up-arrow"></i>

            <span>Sales</span>

        </a>

        <a href="{{ route('seller.reviews.index') }}"
           class="seller-nav-link {{ request()->routeIs('seller.reviews.*') ? 'active' : '' }}">

            <i class="bi bi-star"></i>

            <span>Reviews</span>

        </a>


        {{-- Communication --}}
        <div class="seller-nav-label">
            COMMUNICATION
        </div>

        <a href="{{ route('seller.messages.index') }}"
           class="seller-nav-link {{ request()->routeIs('seller.messages.*') ? 'active' : '' }}">

            <i class="bi bi-chat-left-text"></i>

            <span>Messages</span>

            @if($sellerUnreadMessagesCount > 0)
                <span class="seller-message-badge">
                    {{ $sellerUnreadMessagesCount > 99 ? '99+' : $sellerUnreadMessagesCount }}
                </span>
            @endif

        </a>


        {{-- Account --}}
        <div class="seller-nav-label">
            ACCOUNT
        </div>

        <a href="{{ route('my.profile') }}"
           class="seller-nav-link">

            <i class="bi bi-person"></i>

            <span>Profile</span>

        </a>

        <a href="{{ route('frontend.account.settings') }}"
           class="seller-nav-link">

            <i class="bi bi-person-gear"></i>

            <span>Account Settings</span>

        </a>

        <a href="{{ route('seller.settings') }}"
           class="seller-nav-link {{ request()->routeIs('seller.settings') ? 'active' : '' }}">

            <i class="bi bi-shop"></i>

            <span>Store Settings</span>

        </a>

    </nav>


    {{-- Bottom --}}
    <div class="seller-sidebar-bottom">

        <a href="{{ route('frontend.home') }}"
           class="seller-nav-link">

            <i class="bi bi-arrow-left"></i>

            <span>Back to Store</span>

        </a>

    </div>

</aside>