<header class="seller-header">

    {{-- LEFT --}}
    <div class="seller-header-left">

        <button type="button"
                class="seller-mobile-toggle"
                id="sellerMobileToggle"
                aria-label="Open menu">

            <i class="bi bi-list"></i>

        </button>

        <div class="seller-header-title">
            <h6>Seller Panel</h6>
            <span>Manage your store and sales</span>
        </div>

    </div>


    {{-- RIGHT --}}
    <div class="seller-header-right">

        {{-- Messages --}}
        <a href="{{ route('seller.messages.index') }}"
           class="seller-header-action"
           aria-label="Messages"
           title="Messages">

            <i class="bi bi-chat-left-text"></i>

            @if(($sellerUnreadMessagesCount ?? 0) > 0)
                <span class="seller-header-message-badge">
                    {{ $sellerUnreadMessagesCount > 99 ? '99+' : $sellerUnreadMessagesCount }}
                </span>
            @endif

        </a>

        <span class="seller-header-divider" aria-hidden="true"></span>

        {{-- Seller User --}}
        <div class="seller-user">

            <div class="seller-user-avatar-wrap">

                <div class="seller-user-avatar">

                    @if(auth()->user()->profile_photo)

                        <img
                            src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            alt="{{ auth()->user()->full_name ?: auth()->user()->name }}"
                        >

                    @else

                        <i class="bi bi-person"></i>

                    @endif

                </div>

                <span class="seller-user-online"></span>

            </div>

            <div class="seller-user-info">

                <strong>
                    {{ auth()->user()->full_name ?: auth()->user()->name }}
                </strong>

                <span>Seller</span>

            </div>

        </div>

    </div>

</header>