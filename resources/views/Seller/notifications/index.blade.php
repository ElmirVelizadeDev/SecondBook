@extends('Layout.Seller.master')

@section('title', 'Notifications')

@push('css') <link rel="stylesheet" href="{{ asset('seller/css/notifications.css') }}">
@endpush

@section('content')

@php
$hasFilters = request()->filled('status');
$currentStatus = request('status');
@endphp

<div class="seller-notifications-page">


{{-- Page Header --}}
<div class="seller-page-heading">
    <div>
        <h1>Notifications</h1>

        <p>
            Stay updated with activity and important events from your store.
        </p>
    </div>

    @if(($unreadNotifications ?? 0) > 0)
        <div class="seller-notifications-heading-pill">
            <i></i>

            {{ $unreadNotifications }}
            unread
            {{ $unreadNotifications === 1 ? 'notification' : 'notifications' }}
        </div>
    @endif
</div>

{{-- Statistics --}}
<div class="row g-4 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="seller-notification-stat-card">

            <div class="seller-notification-stat-icon">
                <i class="bi bi-bell"></i>
            </div>

            <div class="seller-notification-stat-content">
                <span>Total Notifications</span>

                <h3 class="notifications-total-count">
                    {{ $totalNotifications ?? 0 }}
                </h3>
            </div>

        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="seller-notification-stat-card">

            <div class="seller-notification-stat-icon unread">
                <i class="bi bi-bell-fill"></i>
            </div>

            <div class="seller-notification-stat-content">
                <span>Unread</span>

                <h3 class="notifications-unread-count">
                    {{ $unreadNotifications ?? 0 }}
                </h3>
            </div>

        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="seller-notification-stat-card">

            <div class="seller-notification-stat-icon read">
                <i class="bi bi-bell-slash"></i>
            </div>

            <div class="seller-notification-stat-content">
                <span>Read</span>

                <h3 class="notifications-read-count">
                    {{ $readNotifications ?? 0 }}
                </h3>
            </div>

        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="seller-notification-stat-card">

            <div class="seller-notification-stat-icon today">
                <i class="bi bi-calendar-day"></i>
            </div>

            <div class="seller-notification-stat-content">
                <span>Today</span>

                <h3>
                    {{ $todayNotifications ?? 0 }}
                </h3>
            </div>

        </div>
    </div>

</div>

{{-- Notifications Panel --}}
<div class="seller-notifications-panel">

    {{-- Filter Header --}}
    <div class="seller-notifications-filter">

        <div class="seller-notification-tabs">

            <a
                href="{{ route('seller.notifications.index') }}"
                class="seller-notification-tab {{ !$currentStatus ? 'active' : '' }}"
            >
                All
                <em>{{ $totalNotifications ?? 0 }}</em>
            </a>

            <a
                href="{{ route('seller.notifications.index', ['status' => 'unread']) }}"
                class="seller-notification-tab {{ $currentStatus === 'unread' ? 'active' : '' }}"
            >
                Unread
                <em>{{ $unreadNotifications ?? 0 }}</em>
            </a>

            <a
                href="{{ route('seller.notifications.index', ['status' => 'read']) }}"
                class="seller-notification-tab {{ $currentStatus === 'read' ? 'active' : '' }}"
            >
                Read
                <em>{{ $readNotifications ?? 0 }}</em>
            </a>

        </div>

        <div class="seller-notification-header-actions">

            @if(($unreadNotifications ?? 0) > 0)
                <form
                    action="{{ route('seller.notifications.read-all') }}"
                    method="POST"
                    class="seller-notification-read-all-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="seller-notification-read-all"
                    >
                        <i class="bi bi-check2-all"></i>
                        <span>Mark all as read</span>
                    </button>
                </form>
            @endif

            @if(($totalNotifications ?? 0) > 0)
                <form
                    action="{{ route('seller.notifications.destroy-all') }}"
                    method="POST"
                    class="seller-notification-delete-all-form"
                >
                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="seller-notification-delete-all"
                    >
                        <i class="bi bi-trash3"></i>
                        <span>Delete all</span>
                    </button>
                </form>
            @endif

        </div>
    </div>

    {{-- Notification List --}}
    @if($notifications->count())

        <div class="seller-notification-list">

            @foreach($notifications as $notification)

                @php
                    $isUnread = is_null($notification->read_at);

                    $type = $notification->type ?? 'default';

                    $icon = match ($type) {
                        'order',
                        'new_order' => 'bi-bag-check',

                        'sale' => 'bi-graph-up-arrow',

                        'review' => 'bi-star-fill',

                        'message' => 'bi-chat-left-text',

                        'book' => 'bi-book',

                        'approved',
                        'success' => 'bi-check-circle',

                        'rejected' => 'bi-x-circle',

                        'warning' => 'bi-exclamation-triangle',

                        default => 'bi-bell',
                    };

                    $notificationData = is_array($notification->data)
                        ? $notification->data
                        : [];

                    $notificationTitle =
                        $notification->title
                        ?? ($notificationData['title'] ?? 'Notification');

                    $notificationMessage =
                        $notification->message
                        ?? (
                            $notificationData['message']
                            ?? (
                                $notificationData['body']
                                ?? 'You have a new notification.'
                            )
                        );

                    $notificationUrl =
                        $notification->url
                        ?? ($notificationData['url'] ?? null);
                @endphp

                <div
                    class="seller-notification-item {{ $isUnread ? 'is-unread' : '' }}"
                    data-notification-id="{{ $notification->id }}"
                    data-status="{{ $isUnread ? 'unread' : 'read' }}"
                >

                    {{-- Icon --}}
                    <div class="seller-notification-icon">

                        <i class="bi {{ $icon }}"></i>

                        @if($isUnread)
                            <span class="seller-notification-unread-dot"></span>
                        @endif

                    </div>

                    {{-- Content --}}
                    <div class="seller-notification-content">

                        <div class="seller-notification-top">

                            <div class="seller-notification-title-wrap">

                                <strong>
                                    {{ $notificationTitle }}
                                </strong>

                                @if($isUnread)
                                    <span class="seller-notification-new-badge">
                                        New
                                    </span>
                                @endif

                            </div>

                            <time>
                                <i class="bi bi-clock"></i>
                                {{ $notification->created_at?->diffForHumans() ?? '—' }}
                            </time>

                        </div>

                        <p>
                            {{ $notificationMessage }}
                        </p>

                        <div class="seller-notification-meta">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                {{ $notification->created_at?->format('M d, Y · H\:i') ?? '—' }}
                            </span>

                            @if($notification->type)
                                <span class="seller-notification-type">
                                    <i class="bi bi-tag"></i>

                                    {{ ucwords(str_replace('_', ' ', $notification->type)) }}
                                </span>
                            @endif

                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="seller-notification-actions">

                        @if($notificationUrl)
                            <a
                                href="{{ $notificationUrl }}"
                                class="seller-notification-action view"
                                title="Open notification"
                            >
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        @endif

                        {{-- Read / Unread --}}
                        <form
                            action="{{
                                $isUnread
                                    ? route('seller.notifications.read', $notification)
                                    : route('seller.notifications.unread', $notification)
                            }}"
                            method="POST"
                            class="seller-notification-status-form"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="seller-notification-action"
                                title="{{ $isUnread ? 'Mark as read' : 'Mark as unread' }}"
                            >
                                <i class="bi {{ $isUnread ? 'bi-check2' : 'bi-envelope' }}"></i>
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form
                            action="{{ route('seller.notifications.destroy', $notification) }}"
                            method="POST"
                            class="seller-notification-delete-form"
                        >
                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="seller-notification-action delete"
                                title="Delete notification"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="seller-notifications-pagination">
                {{ $notifications->links() }}
            </div>
        @endif

    @else

        {{-- Empty State --}}
        <div class="seller-notifications-empty">

            <div class="seller-notifications-empty-icon">
                <i class="bi bi-bell-slash"></i>
            </div>

            <h5>
                No Notifications Found
            </h5>

            <p>
                You don't have any notifications matching your current filter.
            </p>

            @if($hasFilters)
                <a
                    href="{{ route('seller.notifications.index') }}"
                    class="seller-notification-empty-button"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>Clear Filter</span>
                </a>
            @endif

        </div>

    @endif

</div>


</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', () => {

    const page = document.querySelector(
        '.seller-notifications-page'
    );

    if (!page) {
        return;
    }

    const readAllUrl = @json(
        route('seller.notifications.read-all')
    );

    const deleteAllUrl = @json(
        route('seller.notifications.destroy-all')
    );

    /*
     * Get Current CSRF Token
     *
     * Always read the token from the current DOM.
     * This is important because loadPage() replaces
     * the notification page HTML dynamically.
     */
    const getCsrfToken = () => {

        const tokenInput = page.querySelector(
            'input[name="_token"]'
        );

        return tokenInput?.value || '';
    };

    /*
     * AJAX Request
     */
    const request = async (form) => {

        const tokenInput = form.querySelector(
            'input[name="_token"]'
        );

        const token = tokenInput?.value || getCsrfToken();

        if (!token) {
            throw new Error(
                'CSRF token not found.'
            );
        }

        /*
         * Make sure dynamically created forms
         * always contain the current token.
         */
        if (!tokenInput) {

            const hiddenToken =
                document.createElement('input');

            hiddenToken.type = 'hidden';
            hiddenToken.name = '_token';
            hiddenToken.value = token;

            form.prepend(hiddenToken);
        }

        const formData = new FormData(form);

        const response = await fetch(
            form.action,
            {
                method: 'POST',
                credentials: 'same-origin',

                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },

                body: formData,
            }
        );

        const responseText =
            await response.text();

        let data = null;

        try {
            data = responseText
                ? JSON.parse(responseText)
                : null;
        } catch (error) {

            console.error(
                'Invalid JSON response:',
                responseText
            );
        }

        if (!response.ok) {

            console.error(
                'Notification request failed:',
                {
                    status: response.status,
                    statusText: response.statusText,
                    response: responseText,
                }
            );

            throw new Error(
                data?.message ||
                `Request failed with status ${response.status}.`
            );
        }

        return data || {
            success: true,
        };
    };

    /*
     * Load Notifications
     */
    const loadPage = async (
        url,
        pushState = true
    ) => {

        try {

            page.classList.add(
                'is-loading'
            );

            const response = await fetch(
                url,
                {
                    method: 'GET',

                    credentials: 'same-origin',

                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Page request failed.'
                );
            }

            const html =
                await response.text();

            const parser =
                new DOMParser();

            const documentHtml =
                parser.parseFromString(
                    html,
                    'text/html'
                );

            const newPage =
                documentHtml.querySelector(
                    '.seller-notifications-page'
                );

            if (!newPage) {
                throw new Error(
                    'Notifications content not found.'
                );
            }

            page.innerHTML =
                newPage.innerHTML;

            if (pushState) {

                window.history.pushState(
                    {},
                    '',
                    url
                );
            }

        } catch (error) {

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
                text: 'Notifications could not be loaded.',
            });

        } finally {

            page.classList.remove(
                'is-loading'
            );
        }
    };

    /*
     * Filter / Pagination / Clear Filter
     */
    page.addEventListener(
        'click',
        (event) => {

            const link =
                event.target.closest(
                    '.seller-notification-tab, ' +
                    '.seller-notifications-pagination a, ' +
                    '.seller-notification-empty-button'
                );

            if (!link) {
                return;
            }

            if (
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey ||
                event.button !== 0
            ) {
                return;
            }

            event.preventDefault();

            loadPage(
                link.href
            );
        }
    );

    /*
     * Browser Back / Forward
     */
    window.addEventListener(
        'popstate',
        () => {

            loadPage(
                window.location.href,
                false
            );
        }
    );

    /*
     * Form Actions
     */
    page.addEventListener(
        'submit',
        async (event) => {

            const form =
                event.target;

            if (
                !form.matches(
                    '.seller-notification-status-form, ' +
                    '.seller-notification-delete-form, ' +
                    '.seller-notification-read-all-form, ' +
                    '.seller-notification-delete-all-form'
                )
            ) {
                return;
            }

            event.preventDefault();

            /*
             * Delete All Confirmation
             */
            if (
                form.matches(
                    '.seller-notification-delete-all-form'
                )
            ) {

                const confirmDelete =
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Delete all notifications?',
                        text: 'All notifications will be permanently deleted.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete all',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusCancel: true,
                    });

                if (!confirmDelete.isConfirmed) {
                    return;
                }
            }

            /*
             * Delete Single Confirmation
             */
            if (
                form.matches(
                    '.seller-notification-delete-form'
                )
            ) {

                const confirmDelete =
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Delete notification?',
                        text: 'This notification will be permanently deleted.',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                    });

                if (!confirmDelete.isConfirmed) {
                    return;
                }
            }

            const item =
                form.closest(
                    '.seller-notification-item'
                );

            const wasUnread =
                item?.dataset.status === 'unread';

            try {

                /*
                 * Send AJAX Request
                 */
                const result =
                    await request(form);

                console.log(
                    'Notification action success:',
                    result
                );

                /*
                 * Delete Single
                 */
                if (
                    form.matches(
                        '.seller-notification-delete-form'
                    )
                ) {

                    item?.remove();

                    updateCounts(
                        'delete',
                        wasUnread
                    );

                    showEmptyStateIfNeeded();

                    return;
                }

                /*
                 * Delete All
                 */
                if (
                    form.matches(
                        '.seller-notification-delete-all-form'
                    )
                ) {

                    page
                        .querySelectorAll(
                            '.seller-notification-item'
                        )
                        .forEach(
                            notification => {
                                notification.remove();
                            }
                        );

                    updateCounts(
                        'delete-all'
                    );

                    showEmptyStateIfNeeded();

                    await Swal.fire({
                        icon: 'success',
                        title: 'Notifications deleted',
                        text: 'All notifications have been deleted successfully.',
                        timer: 1600,
                        showConfirmButton: false,
                    });

                    return;
                }

                /*
                 * Mark Read / Unread
                 */
                if (
                    form.matches(
                        '.seller-notification-status-form'
                    )
                ) {

                    const currentStatus =
                        item?.dataset.status;

                    const isReadAction =
                        currentStatus === 'unread';

                    if (isReadAction) {

                        item?.classList.remove(
                            'is-unread'
                        );

                        if (item) {
                            item.dataset.status =
                                'read';
                        }

                        item
                            ?.querySelector(
                                '.seller-notification-new-badge'
                            )
                            ?.remove();

                        item
                            ?.querySelector(
                                '.seller-notification-unread-dot'
                            )
                            ?.remove();

                        const button =
                            form.querySelector(
                                'button'
                            );

                        const icon =
                            button?.querySelector(
                                'i'
                            );

                        if (button) {
                            button.title =
                                'Mark as unread';
                        }

                        if (icon) {
                            icon.className =
                                'bi bi-envelope';
                        }

                        if (form) {

                            form.action =
                                form.action.replace(
                                    /\/read$/,
                                    '/unread'
                                );
                        }

                        updateCounts(
                            'read'
                        );

                        if (
                            new URLSearchParams(
                                window.location.search
                            ).get('status') === 'unread'
                        ) {

                            item?.remove();

                            showEmptyStateIfNeeded();
                        }

                    } else {

                        item?.classList.add(
                            'is-unread'
                        );

                        if (item) {
                            item.dataset.status =
                                'unread';
                        }

                        /*
                         * Add Unread Dot
                         */
                        if (
                            !item?.querySelector(
                                '.seller-notification-unread-dot'
                            )
                        ) {

                            const iconWrapper =
                                item?.querySelector(
                                    '.seller-notification-icon'
                                );

                            if (iconWrapper) {

                                const dot =
                                    document.createElement(
                                        'span'
                                    );

                                dot.className =
                                    'seller-notification-unread-dot';

                                iconWrapper.appendChild(
                                    dot
                                );
                            }
                        }

                        /*
                         * Add New Badge
                         */
                        if (
                            !item?.querySelector(
                                '.seller-notification-new-badge'
                            )
                        ) {

                            const titleWrap =
                                item?.querySelector(
                                    '.seller-notification-title-wrap'
                                );

                            if (titleWrap) {

                                const badge =
                                    document.createElement(
                                        'span'
                                    );

                                badge.className =
                                    'seller-notification-new-badge';

                                badge.textContent =
                                    'New';

                                titleWrap.appendChild(
                                    badge
                                );
                            }
                        }

                        const button =
                            form.querySelector(
                                'button'
                            );

                        const icon =
                            button?.querySelector(
                                'i'
                            );

                        if (button) {
                            button.title =
                                'Mark as read';
                        }

                        if (icon) {
                            icon.className =
                                'bi bi-check2';
                        }

                        if (form) {

                            form.action =
                                form.action.replace(
                                    /\/unread$/,
                                    '/read'
                                );
                        }

                        updateCounts(
                            'unread'
                        );

                        if (
                            new URLSearchParams(
                                window.location.search
                            ).get('status') === 'read'
                        ) {

                            item?.remove();

                            showEmptyStateIfNeeded();
                        }
                    }

                    return;
                }

                /*
                 * Mark All As Read
                 */
                if (
                    form.matches(
                        '.seller-notification-read-all-form'
                    )
                ) {

                    page
                        .querySelectorAll(
                            '.seller-notification-item'
                        )
                        .forEach(
                            notification => {

                                notification.classList.remove(
                                    'is-unread'
                                );

                                notification.dataset.status =
                                    'read';

                                notification
                                    .querySelector(
                                        '.seller-notification-new-badge'
                                    )
                                    ?.remove();

                                notification
                                    .querySelector(
                                        '.seller-notification-unread-dot'
                                    )
                                    ?.remove();

                                const statusForm =
                                    notification.querySelector(
                                        '.seller-notification-status-form'
                                    );

                                if (!statusForm) {
                                    return;
                                }

                                statusForm.action =
                                    statusForm.action.replace(
                                        /\/read$/,
                                        '/unread'
                                    );

                                const button =
                                    statusForm.querySelector(
                                        'button'
                                    );

                                const icon =
                                    button?.querySelector(
                                        'i'
                                    );

                                if (button) {
                                    button.title =
                                        'Mark as unread';
                                }

                                if (icon) {
                                    icon.className =
                                        'bi bi-envelope';
                                }
                            }
                        );

                    updateCounts(
                        'read-all'
                    );

                    /*
                     * If currently viewing Unread,
                     * remove all notifications.
                     */
                    if (
                        new URLSearchParams(
                            window.location.search
                        ).get('status') === 'unread'
                    ) {

                        page
                            .querySelectorAll(
                                '.seller-notification-item'
                            )
                            .forEach(
                                notification => {
                                    notification.remove();
                                }
                            );

                        showEmptyStateIfNeeded();
                    }

                    /*
                     * Remove Mark All button immediately.
                     */
                    form.remove();

                    return;
                }

            } catch (error) {

                console.error(
                    'Notification AJAX error:',
                    error
                );

                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text:
                        error.message ||
                        'Please try again.',
                });
            }
        }
    );

    /*
     * Update Statistics
     */
    function updateCounts(
        action,
        wasUnread = false
    ) {

        const totalElement =
            page.querySelector(
                '.notifications-total-count'
            );

        const unreadElement =
            page.querySelector(
                '.notifications-unread-count'
            );

        const readElement =
            page.querySelector(
                '.notifications-read-count'
            );

        let total =
            parseInt(
                totalElement?.textContent || '0',
                10
            );

        let unread =
            parseInt(
                unreadElement?.textContent || '0',
                10
            );

        let read =
            parseInt(
                readElement?.textContent || '0',
                10
            );

        if (action === 'delete') {

            total =
                Math.max(
                    0,
                    total - 1
                );

            if (wasUnread) {

                unread =
                    Math.max(
                        0,
                        unread - 1
                    );

            } else {

                read =
                    Math.max(
                        0,
                        read - 1
                    );
            }
        }

        if (action === 'delete-all') {

            total = 0;
            unread = 0;
            read = 0;
        }

        if (action === 'read') {

            if (unread > 0) {

                unread--;
                read++;
            }
        }

        if (action === 'unread') {

            if (read > 0) {

                read--;
                unread++;
            }
        }

        if (action === 'read-all') {

            read += unread;
            unread = 0;
        }

        if (totalElement) {
            totalElement.textContent =
                total;
        }

        if (unreadElement) {
            unreadElement.textContent =
                unread;
        }

        if (readElement) {
            readElement.textContent =
                read;
        }

        updateTabs(
            total,
            unread,
            read
        );

        updateHeader(
            unread
        );

        updateHeaderActions(
            total,
            unread
        );
    }

    /*
     * Update Filter Counts
     */
    function updateTabs(
        total,
        unread,
        read
    ) {

        const tabs =
            page.querySelectorAll(
                '.seller-notification-tab'
            );

        tabs.forEach(
            tab => {

                const badge =
                    tab.querySelector(
                        'em'
                    );

                if (!badge) {
                    return;
                }

                const href =
                    tab.getAttribute(
                        'href'
                    ) || '';

                if (
                    !href.includes(
                        'status='
                    )
                ) {

                    badge.textContent =
                        total;

                } else if (
                    href.includes(
                        'status=unread'
                    )
                ) {

                    badge.textContent =
                        unread;

                } else if (
                    href.includes(
                        'status=read'
                    )
                ) {

                    badge.textContent =
                        read;
                }
            }
        );
    }

    /*
     * Update Header
     */
    function updateHeader(
        unread
    ) {

        const heading =
            page.querySelector(
                '.seller-page-heading'
            );

        if (!heading) {
            return;
        }

        let pill =
            heading.querySelector(
                '.seller-notifications-heading-pill'
            );

        if (unread <= 0) {

            pill?.remove();

            return;
        }

        if (!pill) {

            pill =
                document.createElement(
                    'div'
                );

            pill.className =
                'seller-notifications-heading-pill';

            heading.appendChild(
                pill
            );
        }

        pill.innerHTML = `
            <i></i>
            ${unread}
            unread
            ${unread === 1 ? 'notification' : 'notifications'}
        `;
    }

    /*
     * Update Header Actions
     */
    function updateHeaderActions(
        total,
        unread
    ) {

        const actions =
            page.querySelector(
                '.seller-notification-header-actions'
            );

        if (!actions) {
            return;
        }

        let readAllForm =
            actions.querySelector(
                '.seller-notification-read-all-form'
            );

        /*
         * Add Mark All As Read
         */
        if (
            unread > 0 &&
            !readAllForm
        ) {

            const token =
                getCsrfToken();

            if (!token) {

                console.error(
                    'Unable to recreate Mark All form: CSRF token not found.'
                );

                return;
            }

            readAllForm =
                document.createElement(
                    'form'
                );

            readAllForm.className =
                'seller-notification-read-all-form';

            readAllForm.method =
                'POST';

            readAllForm.action =
                readAllUrl;

            readAllForm.innerHTML = `
                <input
                    type="hidden"
                    name="_token"
                    value="${token}"
                >

                <button
                    type="submit"
                    class="seller-notification-read-all"
                >
                    <i class="bi bi-check2-all"></i>
                    <span>Mark all as read</span>
                </button>
            `;

            actions.prepend(
                readAllForm
            );
        }

        /*
         * Remove Mark All As Read
         */
        if (unread <= 0) {

            readAllForm?.remove();
        }

        /*
         * Add Delete All
         */
        if (
            total > 0 &&
            !actions.querySelector(
                '.seller-notification-delete-all-form'
            )
        ) {

            const token =
                getCsrfToken();

            if (!token) {

                console.error(
                    'Unable to recreate Delete All form: CSRF token not found.'
                );

                return;
            }

            const deleteAllForm =
                document.createElement(
                    'form'
                );

            deleteAllForm.className =
                'seller-notification-delete-all-form';

            deleteAllForm.method =
                'POST';

            deleteAllForm.action =
                deleteAllUrl;

            deleteAllForm.innerHTML = `
                <input
                    type="hidden"
                    name="_token"
                    value="${token}"
                >

                <input
                    type="hidden"
                    name="_method"
                    value="DELETE"
                >

                <button
                    type="submit"
                    class="seller-notification-delete-all"
                >
                    <i class="bi bi-trash3"></i>
                    <span>Delete all</span>
                </button>
            `;

            actions.appendChild(
                deleteAllForm
            );
        }

        /*
         * Remove Delete All
         */
        if (total <= 0) {

            actions
                .querySelector(
                    '.seller-notification-delete-all-form'
                )
                ?.remove();
        }
    }

    /*
     * Empty State
     */
    function showEmptyStateIfNeeded() {

        const list =
            page.querySelector(
                '.seller-notification-list'
            );

        if (
            !list ||
            list.children.length > 0
        ) {
            return;
        }

        const panel =
            page.querySelector(
                '.seller-notifications-panel'
            );

        if (!panel) {
            return;
        }

        list.remove();

        panel
            .querySelector(
                '.seller-notifications-pagination'
            )
            ?.remove();

        const existingEmpty =
            panel.querySelector(
                '.seller-notifications-empty'
            );

        if (existingEmpty) {
            return;
        }

        const currentStatus =
            new URLSearchParams(
                window.location.search
            ).get('status');

        const emptyState =
            document.createElement(
                'div'
            );

        emptyState.className =
            'seller-notifications-empty';

        emptyState.innerHTML = `
            <div class="seller-notifications-empty-icon">
                <i class="bi bi-bell-slash"></i>
            </div>

            <h5>
                No Notifications Found
            </h5>

            <p>
                You don't have any notifications matching your current filter.
            </p>

            ${
                currentStatus
                    ? `
                        <a
                            href="{{ route('seller.notifications.index') }}"
                            class="seller-notification-empty-button"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Clear Filter</span>
                        </a>
                    `
                    : ''
            }
        `;

        panel.appendChild(
            emptyState
        );
    }

});
</script>

@endpush
