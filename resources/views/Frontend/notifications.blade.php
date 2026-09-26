@extends('Layout.Frontend.master')

@section('title', 'Notifications | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/notifications.css') }}">
@endpush

@section('content')

<main class="sb-notifications-page">

    <div class="container">

        {{-- =====================================================
             HERO
        ====================================================== --}}
        <section class="sb-notifications-hero">

            <div class="sb-notifications-hero-content">

                <span class="sb-notifications-eyebrow">
                    <span class="sb-notifications-eyebrow-icon">
                        <i class="bi bi-bell"></i>
                    </span>

                    Notification Center
                </span>

                <h1>
                    Stay in the loop
                    <span>with SecondBook.</span>
                </h1>

                <p>
                    Keep track of your latest orders, book requests,
                    payments and important account updates.
                </p>

            </div>


            {{-- =================================================
                 HERO STATUS
            ================================================== --}}
            <div class="sb-notifications-status">

                <div class="sb-notifications-status-top">

                    <span>
                        Notification status
                    </span>

                    <i class="bi bi-activity"></i>

                </div>

                @if($unreadCount > 0)

                    <div class="sb-notifications-status-number">
                        {{ $unreadCount }}
                    </div>

                    <p class="sb-notifications-status-message">
                        unread
                        {{ $unreadCount === 1 ? 'notification' : 'notifications' }}
                    </p>

                @else

                    <div class="sb-notifications-status-clean">
                        <i class="bi bi-check2"></i>
                        <span>All caught up</span>
                    </div>

                    <p class="sb-notifications-status-message">
                        Nothing needs your attention
                    </p>

                @endif

            </div>

        </section>


        {{-- =====================================================
             TOOLBAR
        ====================================================== --}}
        <section class="sb-notifications-toolbar">

            <div class="sb-notifications-toolbar-left">

                <span class="sb-notifications-toolbar-kicker">
                    Account activity
                </span>

                <div class="sb-notifications-toolbar-title">

                    <h2>
                        Your notifications
                    </h2>

                    <span class="sb-notifications-count">
                        {{ $notifications->total() }}
                    </span>

                </div>

            </div>


            @if($unreadCount > 0)

                <form
                    action="{{ route('frontend.notifications.read-all') }}"
                    method="POST"
                    class="sb-notifications-read-all-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="sb-notifications-read-all"
                    >
                        <span class="sb-notifications-read-all-icon">
                            <i class="bi bi-check2-all"></i>
                        </span>

                        <span>
                            Mark all as read
                        </span>

                        <i class="bi bi-arrow-up-right"></i>
                    </button>

                </form>

            @endif

        </section>


        {{-- =====================================================
             NOTIFICATION LIST
        ====================================================== --}}
        <section class="sb-notifications-list">

            @forelse($notifications as $notification)

                @php
                    if ($notification->type === 'book_request') {
                        $notificationTypeLabel = 'Book Request';
                        $notificationIcon = 'bi-book';
                        $notificationTypeClass = 'type-book';
                    } elseif ($notification->type === 'order') {
                        $notificationTypeLabel = 'Order';
                        $notificationIcon = 'bi-bag-check';
                        $notificationTypeClass = 'type-order';
                    } elseif ($notification->type === 'payment') {
                        $notificationTypeLabel = 'Payment';
                        $notificationIcon = 'bi-credit-card';
                        $notificationTypeClass = 'type-payment';
                    } else {
                        $notificationTypeLabel = 'General';
                        $notificationIcon = 'bi-bell';
                        $notificationTypeClass = 'type-general';
                    }
                @endphp


                <article
                    class="sb-notification {{ is_null($notification->read_at) ? 'is-unread' : 'is-read' }}"
                >

                    {{-- =================================================
                         INDEX
                    ================================================== --}}
                    <div class="sb-notification-index">

                        <span>
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>


                    {{-- =================================================
                         ICON
                    ================================================== --}}
                    <div class="sb-notification-icon-column">

                        <div class="sb-notification-icon {{ $notificationTypeClass }}">
                            <i class="bi {{ $notificationIcon }}"></i>
                        </div>

                        @if(!$loop->last)
                            <span class="sb-notification-line"></span>
                        @endif

                    </div>


                    {{-- =================================================
                         CONTENT
                    ================================================== --}}
                    <div class="sb-notification-body">

                        <div class="sb-notification-header">

                            <div class="sb-notification-heading">

                                <div class="sb-notification-meta">

                                    <span class="sb-notification-type">
                                        {{ $notificationTypeLabel }}
                                    </span>

                                    @if(is_null($notification->read_at))

                                        <span class="sb-notification-new">
                                            New
                                        </span>

                                    @endif

                                </div>

                                <h3>
                                    {{ $notification->title }}
                                </h3>

                            </div>


                            <time
                                class="sb-notification-time"
                                datetime="{{ $notification->created_at->toIso8601String() }}"
                            >
                                <i class="bi bi-clock"></i>

                                {{ $notification->created_at->diffForHumans() }}
                            </time>

                        </div>


                        <p class="sb-notification-message">
                            {{ $notification->message }}
                        </p>


                        <div class="sb-notification-footer">

                            @if(is_null($notification->read_at))

                                <span class="sb-notification-state unread">
                                    <span></span>
                                    Unread
                                </span>

                                <form
                                    action="{{ route('frontend.notifications.read', $notification) }}"
                                    method="POST"
                                    class="sb-notification-read-form"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="sb-notification-read-button"
                                    >
                                        Mark as read

                                        <i class="bi bi-arrow-right"></i>
                                    </button>

                                </form>

                            @else

                                <span class="sb-notification-state read">
                                    <i class="bi bi-check2"></i>
                                    Read
                                </span>

                            @endif

                        </div>

                    </div>

                </article>

            @empty

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}
                <section class="sb-notifications-empty">

                    <div class="sb-notifications-empty-art">

                        <span class="empty-ring empty-ring-one"></span>
                        <span class="empty-ring empty-ring-two"></span>

                        <div class="empty-bell">
                            <i class="bi bi-bell-slash"></i>
                        </div>

                    </div>


                    <div class="sb-notifications-empty-content">

                        <span class="sb-notifications-empty-kicker">
                            You're all caught up
                        </span>

                        <h2>
                            Nothing new
                            <span>for now.</span>
                        </h2>

                        <p>
                            Your notification center is quiet. New updates
                            about your account, books, orders and payments
                            will appear here.
                        </p>

                        <a
                            href="{{ route('frontend.home') }}"
                            class="sb-notifications-empty-button"
                        >
                            <span>
                                Continue browsing
                            </span>

                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </section>

            @endforelse

        </section>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($notifications->hasPages())

            <div class="sb-notifications-pagination">
                {{ $notifications->links() }}
            </div>

        @endif

    </div>

</main>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Mark single notification as read
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.sb-notification-read-form').forEach(function (form) {

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const notification = form.closest('.sb-notification');
            const button = form.querySelector('.sb-notification-read-button');

            if (!notification || !button) {
                return;
            }

            const token = form.querySelector('input[name="_token"]');

            button.disabled = true;

            try {

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token.value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                });

                if (!response.ok) {
                    throw new Error('Failed to mark notification as read.');
                }

                /*
                |--------------------------------------------------------------------------
                | Change notification visual state
                |--------------------------------------------------------------------------
                */

                notification.classList.remove('is-unread');
                notification.classList.add('is-read');


                /*
                |--------------------------------------------------------------------------
                | Remove "New" badge
                |--------------------------------------------------------------------------
                */

                const newBadge = notification.querySelector(
                    '.sb-notification-new'
                );

                if (newBadge) {
                    newBadge.remove();
                }


                /*
                |--------------------------------------------------------------------------
                | Change unread state to read
                |--------------------------------------------------------------------------
                */

                const state = notification.querySelector(
                    '.sb-notification-state'
                );

                if (state) {
                    state.classList.remove('unread');
                    state.classList.add('read');

                    state.innerHTML = `
                        <i class="bi bi-check2"></i>
                        Read
                    `;
                }


                /*
                |--------------------------------------------------------------------------
                | Remove old button without touching its CSS
                |--------------------------------------------------------------------------
                */

                form.remove();


                /*
                |--------------------------------------------------------------------------
                | Update unread counter
                |--------------------------------------------------------------------------
                */

                updateUnreadCounter();

            } catch (error) {

                console.error(error);

                button.disabled = false;
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Mark all notifications as read
    |--------------------------------------------------------------------------
    */

    const markAllForm = document.querySelector(
        '.sb-notifications-read-all-form'
    );

    if (markAllForm) {

        markAllForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const button = markAllForm.querySelector(
                '.sb-notifications-read-all'
            );

            const token = markAllForm.querySelector(
                'input[name="_token"]'
            );

            if (!button || !token) {
                return;
            }

            button.disabled = true;

            try {

                const response = await fetch(markAllForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token.value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(markAllForm)
                });

                if (!response.ok) {
                    throw new Error('Failed to mark all notifications as read.');
                }


                /*
                |--------------------------------------------------------------------------
                | Update every unread notification
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('.sb-notification.is-unread')
                    .forEach(function (notification) {

                        notification.classList.remove('is-unread');
                        notification.classList.add('is-read');


                        const newBadge = notification.querySelector(
                            '.sb-notification-new'
                        );

                        if (newBadge) {
                            newBadge.remove();
                        }


                        const state = notification.querySelector(
                            '.sb-notification-state'
                        );

                        if (state) {

                            state.classList.remove('unread');
                            state.classList.add('read');

                            state.innerHTML = `
                                <i class="bi bi-check2"></i>
                                Read
                            `;
                        }


                        const readForm = notification.querySelector(
                            '.sb-notification-read-form'
                        );

                        if (readForm) {
                            readForm.remove();
                        }

                    });


                /*
                |--------------------------------------------------------------------------
                | Remove "Mark all as read" button
                |--------------------------------------------------------------------------
                */

                markAllForm.remove();


                /*
                |--------------------------------------------------------------------------
                | Update hero status
                |--------------------------------------------------------------------------
                */

                updateUnreadCounter();

            } catch (error) {

                console.error(error);

                button.disabled = false;
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Update unread counter
    |--------------------------------------------------------------------------
    */

    function updateUnreadCounter() {

        const unreadNotifications = document.querySelectorAll(
            '.sb-notification.is-unread'
        );

        const unreadCount = unreadNotifications.length;


        /*
        |--------------------------------------------------------------------------
        | Hero status
        |--------------------------------------------------------------------------
        */

        const statusNumber = document.querySelector(
            '.sb-notifications-status-number'
        );

        const statusMessage = document.querySelector(
            '.sb-notifications-status-message'
        );

        const status = document.querySelector(
            '.sb-notifications-status'
        );


        if (status && unreadCount === 0) {

            if (statusNumber) {
                statusNumber.remove();
            }

            const cleanStatus =
                status.querySelector('.sb-notifications-status-clean');

            if (!cleanStatus) {

                const top = status.querySelector(
                    '.sb-notifications-status-top'
                );

                const message = status.querySelector(
                    '.sb-notifications-status-message'
                );

                if (top) {
                    top.insertAdjacentHTML(
                        'afterend',
                        `
                        <div class="sb-notifications-status-clean">
                            <i class="bi bi-check2"></i>
                            <span>All caught up</span>
                        </div>
                        `
                    );
                }

                if (message) {
                    message.textContent =
                        'Nothing needs your attention';
                }
            }
        }

    }

});
</script>
@endpush