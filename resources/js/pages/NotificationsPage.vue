<script setup>
import { onMounted, ref } from 'vue';
import accountService from '../services/accountService';

const notifications = ref([]);
const loading = ref(true);
const error = ref('');
const notice = ref('');
const unread = ref(0);
const meta = ref({ current_page: 1, last_page: 1 });

async function loadNotifications() {
    await loadPage(1);
}

async function loadPage(page) {
    loading.value = true;
    error.value = '';
    try {
        const response = await accountService.getNotifications({ page });
        notifications.value = response.notifications?.data || [];
        meta.value = response.notifications?.meta || meta.value;
        unread.value = Number(response.unread_count || 0);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Notifications could not be loaded.';
    } finally {
        loading.value = false;
    }
}

async function markRead(notification) {
    try {
        await accountService.markNotificationRead(notification.id);
        notification.read_at = notification.read_at || new Date().toISOString();
        unread.value = Math.max(0, unread.value - 1);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'This notification could not be marked as read.';
    }
}

async function markAllRead() {
    try {
        await accountService.markAllNotificationsRead();
        notifications.value.forEach((notification) => {
            notification.read_at = notification.read_at || new Date().toISOString();
        });
        unread.value = 0;
        notice.value = 'All notifications have been marked as read.';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Notifications could not be updated.';
    }
}

function notificationPresentation(notification) {
    const types = {
        book_request: { label: 'Book Request', icon: 'bi-book', className: 'type-book' },
        order: { label: 'Order', icon: 'bi-bag-check', className: 'type-order' },
        payment: { label: 'Payment', icon: 'bi-credit-card', className: 'type-payment' },
    };

    return types[notification.type] || {
        label: 'General',
        icon: 'bi-bell',
        className: 'type-general',
    };
}

function relativeDate(value) {
    if (!value) return '';
    const seconds = Math.round((new Date(value).getTime() - Date.now()) / 1000);
    const units = [
        ['year', 31536000],
        ['month', 2592000],
        ['week', 604800],
        ['day', 86400],
        ['hour', 3600],
        ['minute', 60],
    ];
    const [unit, length] = units.find(([, size]) => Math.abs(seconds) >= size) || ['second', 1];

    return new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' })
        .format(Math.round(seconds / length), unit);
}

onMounted(loadNotifications);
</script>

<template>
    <main class="sb-notifications-page">
        <div class="container">
            <section class="sb-notifications-hero">
                <div class="sb-notifications-hero-content">
                    <span class="sb-notifications-eyebrow">
                        <span class="sb-notifications-eyebrow-icon"><i class="bi bi-bell"></i></span>
                        Notification Center
                    </span>
                    <h1>Stay in the loop <span>with SecondBook.</span></h1>
                    <p>Keep track of your latest orders, book requests, payments and important account updates.</p>
                </div>
                <div class="sb-notifications-status">
                    <div class="sb-notifications-status-top"><span>Notification status</span><i class="bi bi-activity"></i></div>
                    <div v-if="unread" class="sb-notifications-status-number">{{ unread }}</div>
                    <div v-else class="sb-notifications-status-clean"><i class="bi bi-check2"></i><span>All caught up</span></div>
                    <p class="sb-notifications-status-message">
                        {{ unread ? `unread ${unread === 1 ? 'notification' : 'notifications'}` : 'Nothing needs your attention' }}
                    </p>
                </div>
            </section>

            <div v-if="error" class="sb-notifications-alert sb-alert-error" role="alert">
                <span class="sb-alert-icon"><i class="bi bi-exclamation-circle"></i></span>
                <div class="sb-alert-content"><strong>Something went wrong</strong><span>{{ error }}</span></div>
                <button type="button" @click="loadNotifications">Retry</button>
            </div>
            <div v-if="notice" class="sb-notifications-alert sb-alert-success" role="status">
                <span class="sb-alert-icon"><i class="bi bi-check-circle"></i></span>
                <div class="sb-alert-content"><strong>All caught up</strong><span>{{ notice }}</span></div>
            </div>

            <section class="sb-notifications-toolbar">
                <div class="sb-notifications-toolbar-left">
                    <span class="sb-notifications-toolbar-kicker">Account activity</span>
                    <div class="sb-notifications-toolbar-title">
                        <h2>Your notifications</h2>
                        <span class="sb-notifications-count">{{ meta.total || 0 }}</span>
                    </div>
                </div>
                <form v-if="unread" class="sb-notifications-read-all-form" @submit.prevent="markAllRead">
                    <button type="submit" class="sb-notifications-read-all">
                        <span class="sb-notifications-read-all-icon"><i class="bi bi-check2-all"></i></span>
                        <span>Mark all as read</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </button>
                </form>
            </section>

            <p v-if="loading" class="sb-notifications-loading" role="status">Loading notifications…</p>
            <section v-else-if="notifications.length" class="sb-notifications-list">
                <article v-for="(notification, index) in notifications" :key="notification.id" :class="['sb-notification', notification.read_at ? 'is-read' : 'is-unread']">
                    <div class="sb-notification-index"><span>{{ String((meta.current_page - 1) * (meta.per_page || 10) + index + 1).padStart(2, '0') }}</span></div>
                    <div class="sb-notification-icon-column">
                        <div :class="['sb-notification-icon', notificationPresentation(notification).className]">
                            <i :class="`bi ${notificationPresentation(notification).icon}`"></i>
                        </div>
                        <span v-if="index < notifications.length - 1" class="sb-notification-line"></span>
                    </div>
                    <div class="sb-notification-body">
                        <div class="sb-notification-header">
                            <div class="sb-notification-heading">
                                <div class="sb-notification-meta">
                                    <span class="sb-notification-type">{{ notificationPresentation(notification).label }}</span>
                                    <span v-if="!notification.read_at" class="sb-notification-new">New</span>
                                </div>
                                <h3>{{ notification.title }}</h3>
                            </div>
                            <time v-if="notification.created_at" class="sb-notification-time" :datetime="notification.created_at">
                                <i class="bi bi-clock"></i>{{ relativeDate(notification.created_at) }}
                            </time>
                        </div>
                        <p class="sb-notification-message">{{ notification.message }}</p>
                        <div class="sb-notification-footer">
                            <span v-if="!notification.read_at" class="sb-notification-state unread"><span></span>Unread</span>
                            <span v-else class="sb-notification-state read"><i class="bi bi-check2"></i>Read</span>
                            <form v-if="!notification.read_at" class="sb-notification-read-form" @submit.prevent="markRead(notification)">
                                <button type="submit" class="sb-notification-read-button">Mark as read<i class="bi bi-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </article>
            </section>
            <section v-else class="sb-notifications-empty">
                <div class="sb-notifications-empty-art">
                    <span class="empty-ring empty-ring-one"></span><span class="empty-ring empty-ring-two"></span>
                    <div class="empty-bell"><i class="bi bi-bell-slash"></i></div>
                </div>
                <div class="sb-notifications-empty-content">
                    <span class="sb-notifications-empty-kicker">You're all caught up</span>
                    <h2>Nothing new <span>for now.</span></h2>
                    <p>Your notification center is quiet. New updates about your account, books, orders and payments will appear here.</p>
                    <a href="/frontend" class="sb-notifications-empty-button"><span>Continue browsing</span><i class="bi bi-arrow-up-right"></i></a>
                </div>
            </section>

            <nav v-if="!loading && meta.last_page > 1" class="sb-notifications-pagination" aria-label="Notification pages">
                <ul class="pagination">
                    <li :class="['page-item', { disabled: meta.current_page <= 1 }]">
                        <button type="button" class="page-link" :disabled="meta.current_page <= 1" @click="loadPage(meta.current_page - 1)">Previous</button>
                    </li>
                    <li class="page-item disabled"><span class="page-link">{{ meta.current_page }} / {{ meta.last_page }}</span></li>
                    <li :class="['page-item', { disabled: meta.current_page >= meta.last_page }]">
                        <button type="button" class="page-link" :disabled="meta.current_page >= meta.last_page" @click="loadPage(meta.current_page + 1)">Next</button>
                    </li>
                </ul>
            </nav>
        </div>
    </main>
</template>
