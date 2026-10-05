@extends('layout.admin.master')

@section('title', 'Notifications')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/notifications.css') }}">
@endpush

@section('content')

<div class="dashboard-section notifications-page">


{{-- Page Header --}}
<div class="notifications-page-header">
    <div class="notifications-heading">
        <div class="notifications-heading-icon">
            <i class="bi bi-bell"></i>
        </div>

        <div>
            <h1>Notifications</h1>
            <p>
                Manage user notifications and keep your customers informed.
            </p>
        </div>
    </div>

    <div class="notifications-header-actions">
        <button
            type="button"
            class="btn notification-mark-all-btn"
            id="markAllReadBtn"
            {{ $unreadNotifications === 0 ? 'disabled' : '' }}
        >
            <i class="bi bi-check2-all"></i>
            Mark all as read
        </button>

        <button
            type="button"
            class="btn notification-send-btn"
            data-bs-toggle="modal"
            data-bs-target="#sendNotificationModal"
        >
            <i class="bi bi-send"></i>
            Send notification
        </button>
    </div>
</div>

{{-- Statistics --}}
<div class="notification-stats-grid">

    <div class="notification-stat-card">
        <div class="notification-stat-icon total">
            <i class="bi bi-bell"></i>
        </div>

        <div class="notification-stat-content">
            <span>Total notifications</span>

            <strong data-total-notifications>
                {{ number_format($totalNotifications) }}
            </strong>

            <small>All user notifications</small>
        </div>
    </div>

    <div class="notification-stat-card">
        <div class="notification-stat-icon unread">
            <i class="bi bi-envelope"></i>
        </div>

        <div class="notification-stat-content">
            <span>Unread</span>

            <strong data-unread-count>
                {{ number_format($unreadNotifications) }}
            </strong>

            <small>Waiting to be read</small>
        </div>
    </div>

    <div class="notification-stat-card">
        <div class="notification-stat-icon read">
            <i class="bi bi-envelope-open"></i>
        </div>

        <div class="notification-stat-content">
            <span>Read</span>

            <strong data-read-count>
                {{ number_format($readNotifications) }}
            </strong>

            <small>Already viewed</small>
        </div>
    </div>

    <div class="notification-stat-card">
        <div class="notification-stat-icon users">
            <i class="bi bi-people"></i>
        </div>

        <div class="notification-stat-content">
            <span>Users notified</span>

            <strong data-users-notified>
                {{ number_format($usersNotified) }}
            </strong>

            <small>Unique active recipients</small>
        </div>
    </div>

</div>

{{-- Notifications Panel --}}
<div class="dashboard-panel notifications-panel">

    {{-- Panel Header --}}
    <div class="notifications-panel-header">

        <div>
            <h2>Notification history</h2>

            <p>
                View and manage notifications sent to users.
            </p>
        </div>

        <div class="notification-filter-tabs">

            <a
                href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
                class="notification-filter-tab {{ $filter === 'all' ? 'active' : '' }}"
                data-filter="all"
            >
                All

                <span data-all-tab-count>
                    {{ $totalNotifications }}
                </span>
            </a>

            <a
                href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
                class="notification-filter-tab {{ $filter === 'unread' ? 'active' : '' }}"
                data-filter="unread"
            >
                Unread

                <span data-unread-tab-count>
                    {{ $unreadNotifications }}
                </span>
            </a>

            <a
                href="{{ route('admin.notifications.index', ['filter' => 'read']) }}"
                class="notification-filter-tab {{ $filter === 'read' ? 'active' : '' }}"
                data-filter="read"
            >
                Read

                <span data-read-tab-count>
                    {{ $readNotifications }}
                </span>
            </a>

        </div>
    </div>

    {{-- Notification List --}}
    <div class="notification-list">

        @forelse($notifications as $notification)

            <div
                class="notification-item {{ is_null($notification->read_at) ? 'is-unread' : 'is-read' }}"
                data-notification-id="{{ $notification->id }}"
            >

                <div class="notification-item-icon">

                    @php
                        $icon = match($notification->type) {
                            'general' => 'bi-bell',
                            'promotion' => 'bi-megaphone',
                            'order' => 'bi-bag-check',
                            'payment' => 'bi-credit-card',
                            'review' => 'bi-star',
                            'seller' => 'bi-shop',
                            'system' => 'bi-gear',
                            'warning' => 'bi-exclamation-triangle',
                            'success' => 'bi-check-circle',
                            'store_settings_updated' => 'bi-shop',
                            default => 'bi-bell',
                        };
                    @endphp

                    <i class="bi {{ $icon }}"></i>
                </div>

                <div class="notification-item-content">

                    <div class="notification-item-top">

                        <div class="notification-item-title-wrap">

                            @if(is_null($notification->read_at))
                                <span class="notification-unread-dot"></span>
                            @endif

                            <h3>
                                {{ $notification->title }}
                            </h3>

                        </div>

                        <span
                            class="notification-type notification-type--{{ strtolower($notification->type) }}"
                        >
                            {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                        </span>

                    </div>

                    <p class="notification-message">
                        {{ $notification->message }}
                    </p>

                    <div class="notification-meta">

                        <span>
                            <i class="bi bi-person"></i>

                            @if($notification->user)
                                {{ $notification->user->name }}

                                @if($notification->user->email)
                                    <span class="notification-user-email">
                                        {{ $notification->user->email }}
                                    </span>
                                @endif
                            @else
                                All admins
                            @endif
                        </span>

                        <span>
                            <i class="bi bi-clock"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </span>

                        <span>
                            <i class="bi bi-calendar3"></i>
                            {{ $notification->created_at->format('d M Y, H:i') }}
                        </span>

                    </div>

                </div>

                <div class="notification-item-actions">

                    @if(is_null($notification->read_at))

                        <button
                            type="button"
                            class="notification-action-btn mark-read-btn"
                            data-id="{{ $notification->id }}"
                            title="Mark as read"
                        >
                            <i class="bi bi-envelope-open"></i>
                        </button>

                    @else

                        <button
                            type="button"
                            class="notification-action-btn mark-unread-btn"
                            data-id="{{ $notification->id }}"
                            title="Mark as unread"
                        >
                            <i class="bi bi-envelope"></i>
                        </button>

                    @endif

                    <button
                        type="button"
                        class="notification-action-btn delete-notification-btn"
                        data-id="{{ $notification->id }}"
                        data-url="{{ route('admin.notifications.destroy', $notification) }}"
                        title="Delete"
                    >
                        <i class="bi bi-trash3"></i>
                    </button>

                </div>

            </div>

        @empty

            <div class="notifications-empty">

                <div class="notifications-empty-icon">
                    <i class="bi bi-bell-slash"></i>
                </div>

                <h3>No notifications found</h3>

                <p>
                    There are no notifications matching the selected filter.
                </p>

                @if($filter !== 'all')

                    <a
                        href="{{ route('admin.notifications.index') }}"
                        class="btn notification-empty-btn notification-filter-tab"
                        data-filter="all"
                    >
                        View all notifications
                    </a>

                @endif

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())

        <div class="notifications-pagination">
            {{ $notifications->links() }}
        </div>

    @endif

</div>


</div>

{{-- Send Notification Modal --}}

<div
    class="modal fade"
    id="sendNotificationModal"
    tabindex="-1"
    aria-labelledby="sendNotificationModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">


    <div class="modal-content notification-modal">

        {{-- Modal Header --}}
        <div class="modal-header">

            <div class="notification-modal-title">

                <div class="notification-modal-icon">
                    <i class="bi bi-send"></i>
                </div>

                <div>
                    <h5 id="sendNotificationModalLabel">
                        Send notification
                    </h5>

                    <p>
                        Send a message directly to your users.
                    </p>
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>

        </div>

        {{-- Form --}}
        <form
            action="{{ route('admin.notifications.send') }}"
            method="POST"
            id="sendNotificationForm"
        >

            @csrf

            <div class="modal-body">

                {{-- Recipient --}}
                <div class="notification-recipient-selector">

                    <label class="notification-form-label">
                        Recipient
                    </label>

                    <div class="recipient-options">

                        <label class="recipient-option active">

                            <input
                                type="radio"
                                name="recipient"
                                value="user"
                                checked
                            >

                            <span class="recipient-option-icon">
                                <i class="bi bi-person"></i>
                            </span>

                            <span>
                                <strong>Specific user</strong>
                                <small>Send to one active user</small>
                            </span>

                        </label>

                        <label class="recipient-option">

                            <input
                                type="radio"
                                name="recipient"
                                value="all"
                            >

                            <span class="recipient-option-icon">
                                <i class="bi bi-people"></i>
                            </span>

                            <span>
                                <strong>All active users</strong>
                                <small>Send to every active user</small>
                            </span>

                        </label>

                    </div>

                </div>

                {{-- User --}}
                <div
                    class="notification-user-select-wrap"
                    id="notificationUserSelectWrap"
                >

                    <label
                        for="notificationUser"
                        class="notification-form-label"
                    >
                        Select user
                    </label>

                    <select
                        name="user_id"
                        id="notificationUser"
                        class="form-select notification-form-control"
                        required
                    >

                        <option value="">
                            Select an active user
                        </option>

                        @foreach($users as $user)

                            <option value="{{ $user->id }}">
                                {{ $user->name }} — {{ $user->email }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Type + Title --}}
                <div class="notification-form-row">

                    <div class="notification-form-group">

                        <label
                            for="notificationType"
                            class="notification-form-label"
                        >
                            Type
                        </label>

                        <select
                            name="type"
                            id="notificationType"
                            class="form-select notification-form-control"
                            required
                        >
                            <option value="general">General</option>
                            <option value="promotion">Promotion</option>
                            <option value="order">Order</option>
                            <option value="payment">Payment</option>
                            <option value="review">Review</option>
                            <option value="seller">Seller</option>
                            <option value="system">System</option>
                            <option value="success">Success</option>
                            <option value="warning">Warning</option>
                        </select>

                    </div>

                    <div class="notification-form-group">

                        <label
                            for="notificationTitle"
                            class="notification-form-label"
                        >
                            Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="notificationTitle"
                            class="form-control notification-form-control"
                            placeholder="Enter notification title"
                            maxlength="255"
                            required
                        >

                    </div>

                </div>

                {{-- Message --}}
                <div class="notification-form-group">

                    <label
                        for="notificationMessage"
                        class="notification-form-label"
                    >
                        Message
                    </label>

                    <textarea
                        name="message"
                        id="notificationMessage"
                        class="form-control notification-form-control notification-message-input"
                        rows="5"
                        placeholder="Write your notification message..."
                        required
                    ></textarea>

                </div>

                {{-- Info --}}
                <div class="notification-send-note">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Notifications will appear immediately in the recipient's notification center.
                    </span>

                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn notification-modal-cancel"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn notification-modal-submit"
                    id="sendNotificationSubmit"
                >
                    <i class="bi bi-send"></i>
                    <span>Send notification</span>
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    const sendModalElement = document.getElementById(
        'sendNotificationModal'
    );

    const sendForm = document.getElementById(
        'sendNotificationForm'
    );

    const sendSubmit = document.getElementById(
        'sendNotificationSubmit'
    );

    const userSelectWrap = document.getElementById(
        'notificationUserSelectWrap'
    );

    const notificationUser = document.getElementById(
        'notificationUser'
    );

    const recipientOptions = document.querySelectorAll(
        '.recipient-option'
    );

    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            timer: 1600,
            showConfirmButton: false
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }

    function cleanupModal() {
        document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
            backdrop.remove();
        });

        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');

        if (sendModalElement) {
            sendModalElement.classList.remove('show');
            sendModalElement.style.display = 'none';
            sendModalElement.setAttribute('aria-hidden', 'true');
            sendModalElement.removeAttribute('aria-modal');
        }
    }

    function closeSendModal() {
        if (sendModalElement && typeof bootstrap !== 'undefined') {
            const modalInstance =
                bootstrap.Modal.getInstance(sendModalElement);

            if (modalInstance) {
                modalInstance.hide();
            }
        }

        setTimeout(function () {
            cleanupModal();
        }, 150);
    }

    function resetSendForm() {
        if (!sendForm) {
            return;
        }

        sendForm.reset();

        const userRadio = sendForm.querySelector(
            'input[name="recipient"][value="user"]'
        );

        if (userRadio) {
            userRadio.checked = true;
        }

        recipientOptions.forEach(function (option) {
            option.classList.remove('active');
        });

        const activeOption = sendForm.querySelector(
            '.recipient-option input[value="user"]'
        );

        if (activeOption) {
            activeOption.closest('.recipient-option')
                ?.classList.add('active');
        }

        if (userSelectWrap) {
            userSelectWrap.style.display = '';
        }

        if (notificationUser) {
            notificationUser.required = true;
            notificationUser.value = '';
        }

        if (sendSubmit) {
            sendSubmit.disabled = false;

            sendSubmit.innerHTML =
                '<i class="bi bi-send"></i>' +
                '<span>Send notification</span>';
        }
    }

    function updateMarkAllButton(unreadCount = null) {
        const button = document.getElementById('markAllReadBtn');

        if (!button) {
            return;
        }

        if (unreadCount !== null) {
            button.disabled = Number(unreadCount) === 0;
            return;
        }

        const unreadElement = document.querySelector(
            '[data-unread-count]'
        );

        const count = unreadElement
            ? parseInt(
                unreadElement.textContent.replace(/,/g, ''),
                10
            ) || 0
            : 0;

        button.disabled = count === 0;
    }

    function updateMutationCounters(data) {
        const unreadCount = Number(data.unread_count ?? 0);
        const totalCount = Number(data.total_count ?? 0);
        const readCount = Number(data.read_count ?? 0);

        document
            .querySelectorAll('[data-unread-count]')
            .forEach(function (element) {
                element.textContent =
                    unreadCount.toLocaleString();
            });

        document
            .querySelectorAll('[data-unread-tab-count]')
            .forEach(function (element) {
                element.textContent = unreadCount;
            });

        document
            .querySelectorAll('[data-total-notifications]')
            .forEach(function (element) {
                element.textContent =
                    totalCount.toLocaleString();
            });

        document
            .querySelectorAll('[data-all-tab-count]')
            .forEach(function (element) {
                element.textContent = totalCount;
            });

        document
            .querySelectorAll('[data-read-count]')
            .forEach(function (element) {
                element.textContent =
                    readCount.toLocaleString();
            });

        document
            .querySelectorAll('[data-read-tab-count]')
            .forEach(function (element) {
                element.textContent = readCount;
            });

        updateMarkAllButton(unreadCount);
    }

    async function getJson(response) {
        const contentType =
            response.headers.get('content-type') || '';

        if (!contentType.includes('application/json')) {
            throw new Error(
                `Unexpected server response. (${response.status})`
            );
        }

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || 'Request failed.'
            );
        }

        return data;
    }

    async function loadNotifications(
        url,
        updateHistory = true
    ) {
        const notificationList =
            document.querySelector('.notification-list');

        if (!notificationList) {
            return;
        }

        try {
            notificationList.classList.add('is-loading');

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(
                    `Unable to load notifications. (${response.status})`
                );
            }

            const html = await response.text();

            const parser = new DOMParser();

            const documentHtml =
                parser.parseFromString(
                    html,
                    'text/html'
                );

            const newList =
                documentHtml.querySelector(
                    '.notification-list'
                );

            if (!newList) {
                throw new Error(
                    'Unable to load notifications.'
                );
            }

            notificationList.innerHTML =
                newList.innerHTML;

            const currentPagination =
                document.querySelector(
                    '.notifications-pagination'
                );

            const newPagination =
                documentHtml.querySelector(
                    '.notifications-pagination'
                );

            if (currentPagination) {

                if (newPagination) {
                    currentPagination.innerHTML =
                        newPagination.innerHTML;

                    currentPagination.style.display = '';
                } else {
                    currentPagination.innerHTML = '';
                    currentPagination.style.display = 'none';
                }

            } else if (newPagination) {

                const panel =
                    document.querySelector(
                        '.notifications-panel'
                    );

                if (panel) {

                    const paginationWrapper =
                        document.createElement('div');

                    paginationWrapper.className =
                        'notifications-pagination';

                    paginationWrapper.innerHTML =
                        newPagination.innerHTML;

                    panel.appendChild(
                        paginationWrapper
                    );
                }
            }

            const counterSelectors = [
                '[data-total-notifications]',
                '[data-unread-count]',
                '[data-read-count]',
                '[data-users-notified]',
                '[data-all-tab-count]',
                '[data-unread-tab-count]',
                '[data-read-tab-count]'
            ];

            counterSelectors.forEach(function (selector) {

                const currentElements =
                    document.querySelectorAll(selector);

                const newElements =
                    documentHtml.querySelectorAll(selector);

                currentElements.forEach(
                    function (element, index) {

                        if (newElements[index]) {
                            element.textContent =
                                newElements[index].textContent;
                        }

                    }
                );

            });

            const newActiveTab =
                documentHtml.querySelector(
                    '.notification-filter-tab.active'
                );

            const activeFilter =
                newActiveTab?.dataset.filter || 'all';

            document
                .querySelectorAll(
                    '.notification-filter-tab'
                )
                .forEach(function (tab) {

                    tab.classList.toggle(
                        'active',
                        tab.dataset.filter === activeFilter
                    );

                });

            updateMarkAllButton();

            if (updateHistory) {
                window.history.pushState(
                    {
                        notificationFilter:
                            activeFilter
                    },
                    '',
                    url
                );
            }

        } catch (error) {

            console.error(
                'Notifications AJAX error:',
                error
            );

            showError(
                error.message ||
                'Unable to load notifications.'
            );

        } finally {

            notificationList.classList.remove(
                'is-loading'
            );
        }
    }

    async function reloadNotifications() {
        await loadNotifications(
            window.location.href,
            false
        );
    }

    recipientOptions.forEach(function (option) {

        const radio =
            option.querySelector(
                'input[type="radio"]'
            );

        if (!radio) {
            return;
        }

        option.addEventListener(
            'click',
            function () {

                radio.checked = true;

                recipientOptions.forEach(
                    function (item) {
                        item.classList.remove('active');
                    }
                );

                option.classList.add('active');

                if (radio.value === 'user') {

                    userSelectWrap.style.display = '';

                    notificationUser.required = true;

                } else {

                    userSelectWrap.style.display = 'none';

                    notificationUser.required = false;

                    notificationUser.value = '';
                }
            }
        );
    });

    if (sendForm) {

        sendForm.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();

                if (sendSubmit) {
                    sendSubmit.disabled = true;

                    sendSubmit.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>' +
                        '<span>Sending...</span>';
                }

                try {

                    const formData =
                        new FormData(sendForm);

                    const response =
                        await fetch(
                            sendForm.action,
                            {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },
                                credentials: 'same-origin'
                            }
                        );

                    const data =
                        await getJson(response);

                    closeSendModal();
                    resetSendForm();

                    await reloadNotifications();

                    showSuccess(
                        data.message ||
                        'Notification sent successfully.'
                    );

                } catch (error) {

                    if (sendSubmit) {
                        sendSubmit.disabled = false;

                        sendSubmit.innerHTML =
                            '<i class="bi bi-send"></i>' +
                            '<span>Send notification</span>';
                    }

                    showError(
                        error.message ||
                        'Unable to send notification.'
                    );
                }
            }
        );
    }

    document.addEventListener(
        'click',
        async function (event) {

            const button =
                event.target.closest(
                    '.mark-read-btn'
                );

            if (!button) {
                return;
            }

            const id = button.dataset.id;

            const url =
                "{{ url('/admin/notifications') }}/" +
                id +
                "/read";

            button.disabled = true;

            try {

                const response =
                    await fetch(
                        url,
                        {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        }
                    );

                const data =
                    await getJson(response);

                await reloadNotifications();

                showSuccess(data.message);

            } catch (error) {

                button.disabled = false;

                showError(
                    error.message ||
                    'Unable to mark notification as read.'
                );
            }
        }
    );

    document.addEventListener(
        'click',
        async function (event) {

            const button =
                event.target.closest(
                    '.mark-unread-btn'
                );

            if (!button) {
                return;
            }

            const id = button.dataset.id;

            const url =
                "{{ url('/admin/notifications') }}/" +
                id +
                "/unread";

            button.disabled = true;

            try {

                const response =
                    await fetch(
                        url,
                        {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        }
                    );

                const data =
                    await getJson(response);

                await reloadNotifications();

                showSuccess(data.message);

            } catch (error) {

                button.disabled = false;

                showError(
                    error.message ||
                    'Unable to mark notification as unread.'
                );
            }
        }
    );

    const markAllReadBtn =
        document.getElementById(
            'markAllReadBtn'
        );

    if (markAllReadBtn) {

        markAllReadBtn.addEventListener(
            'click',
            async function () {

                const button = this;

                const result =
                    await Swal.fire({
                        icon: 'question',
                        title: 'Mark all as read?',
                        text:
                            'All unread notifications will be marked as read.',
                        showCancelButton: true,
                        confirmButtonText:
                            'Yes, mark all',
                        cancelButtonText:
                            'Cancel',
                        reverseButtons: true
                    });

                if (!result.isConfirmed) {
                    return;
                }

                button.disabled = true;

                try {

                    const response =
                        await fetch(
                            "{{ route('admin.notifications.read-all') }}",
                            {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN':
                                        csrfToken,
                                    'Accept':
                                        'application/json',
                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },
                                credentials:
                                    'same-origin'
                            }
                        );

                    const data =
                        await getJson(response);

                    await reloadNotifications();

                    showSuccess(data.message);

                } catch (error) {

                    updateMarkAllButton();

                    showError(
                        error.message ||
                        'Unable to mark all notifications as read.'
                    );
                }
            }
        );
    }

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.delete-notification-btn'
                );

            if (!button) {
                return;
            }

            const url = button.dataset.url;

            if (!url) {
                showError('Delete URL is missing.');
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Delete notification?',
                text:
                    'This notification will be permanently deleted.',
                showCancelButton: true,
                confirmButtonText:
                    'Yes, delete it',
                cancelButtonText:
                    'Cancel',
                reverseButtons: true
            }).then(
                async function (result) {

                    if (!result.isConfirmed) {
                        return;
                    }

                    button.disabled = true;

                    try {

                        const response =
                            await fetch(
                                url,
                                {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN':
                                            csrfToken,
                                        'Accept':
                                            'application/json',
                                        'X-Requested-With':
                                            'XMLHttpRequest'
                                    },
                                    credentials:
                                        'same-origin'
                                }
                            );

                        const data =
                            await getJson(response);

                        await reloadNotifications();

                        showSuccess(data.message);

                    } catch (error) {

                        button.disabled = false;

                        showError(
                            error.message ||
                            'Unable to delete notification.'
                        );
                    }
                }
            );
        }
    );

    document.addEventListener(
        'click',
        function (event) {

            const filterTab =
                event.target.closest(
                    '.notification-filter-tab'
                );

            if (!filterTab) {
                return;
            }

            event.preventDefault();

            const url =
                filterTab.getAttribute('href');

            if (!url) {
                return;
            }

            loadNotifications(url);
        }
    );

    document.addEventListener(
        'click',
        function (event) {

            const paginationLink =
                event.target.closest(
                    '.notifications-pagination a'
                );

            if (!paginationLink) {
                return;
            }

            event.preventDefault();

            const url =
                paginationLink.getAttribute('href');

            if (!url) {
                return;
            }

            loadNotifications(url);
        }
    );

    window.addEventListener(
        'popstate',
        function () {

            loadNotifications(
                window.location.href,
                false
            );
        }
    );

    if (sendModalElement) {

        sendModalElement.addEventListener(
            'hidden.bs.modal',
            function () {
                cleanupModal();
                resetSendForm();
            }
        );
    }

    updateMarkAllButton();

});
</script>

@endpush
