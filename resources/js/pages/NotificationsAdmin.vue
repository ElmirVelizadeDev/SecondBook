<script setup>
import { computed, onMounted, reactive, ref } from 'vue';

const props = defineProps({ indexUrl: String, sendUrl: String, readAllUrl: String, csrf: String });
const state = reactive({ rows: [], users: [], filter: 'all', stats: { total: 0, unread: 0, read: 0, users_notified: 0 }, pagination: { links: [] } });
const loading = ref(true);
const error = ref('');
const sending = ref(false);
const formError = ref('');
const fieldErrors = ref({});
const form = reactive({ recipient: 'user', user_id: '', type: 'general', title: '', message: '' });
const userRequired = computed(() => form.recipient === 'user');
const types = ['general', 'promotion', 'order', 'payment', 'review', 'seller', 'system', 'success', 'warning'];
const icons = { general: 'bi-bell', promotion: 'bi-megaphone', order: 'bi-bag-check', payment: 'bi-credit-card', review: 'bi-star', seller: 'bi-shop', system: 'bi-gear', warning: 'bi-exclamation-triangle', success: 'bi-check-circle', store_settings_updated: 'bi-shop' };

async function request(url, options = {}) {
    const response = await fetch(url, { credentials: 'same-origin', ...options, headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }), ...(options.headers || {}) } });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) { const e = new Error(data.message || `Request failed (${response.status})`); e.data = data; throw e; }
    return data;
}

async function load(url = window.location.href, push = false) {
    loading.value = true; error.value = '';
    try {
        const data = await request(url);
        state.rows = data.notifications || []; state.users = data.users || []; state.filter = data.filter || 'all';
        state.stats = data.stats || state.stats; state.pagination = data.pagination || { links: [] };
        if (push) window.history.pushState({}, '', url);
    } catch (e) { error.value = e.message || 'Unable to load notifications.'; }
    finally { loading.value = false; }
}

function notify(text, failed = false) {
    if (window.Swal) window.Swal.fire({ toast: true, position: 'top-end', icon: failed ? 'error' : 'success', title: text, showConfirmButton: false, timer: 2600 });
    else if (failed) error.value = text;
}

async function mutate(url, method = 'POST', confirmText = '') {
    if (confirmText && window.Swal) { const result = await window.Swal.fire({ title: 'Are you sure?', text: confirmText, icon: 'warning', showCancelButton: true, confirmButtonText: 'Continue', cancelButtonText: 'Cancel' }); if (!result.isConfirmed) return; }
    try { const data = await request(url, { method, headers: { 'X-CSRF-TOKEN': props.csrf } }); if (data.total_count !== undefined) { state.stats.total = data.total_count; state.stats.unread = data.unread_count; state.stats.read = data.read_count; } notify(data.message); await load(window.location.href); }
    catch (e) { notify(e.message || 'The action could not be completed.', true); }
}

async function send() {
    sending.value = true; formError.value = ''; fieldErrors.value = {};
    try {
        const data = await request(props.sendUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': props.csrf }, body: JSON.stringify(form) });
        notify(data.message); Object.assign(form, { recipient: 'user', user_id: '', type: 'general', title: '', message: '' });
        window.bootstrap?.Modal.getOrCreateInstance(document.getElementById('sendNotificationModal'))?.hide();
        await load(window.location.href);
    } catch (e) { formError.value = e.message; fieldErrors.value = e.data?.errors || {}; }
    finally { sending.value = false; }
}

function changeFilter(filter) { const u = new URL(props.indexUrl, window.location.origin); u.searchParams.set('filter', filter); load(u.href, true); }
function iconFor(type) { return icons[type] || 'bi-bell'; }
function pretty(type) { return type.replaceAll('_', ' ').replace(/^./, c => c.toUpperCase()); }
function linkPage(link) { if (link.url) load(link.url, true); }
onMounted(() => load(props.indexUrl + window.location.search));
window.addEventListener('popstate', () => load(window.location.href));
</script>

<template>
    <div class="notifications-page-header">
        <div class="notifications-heading"><div class="notifications-heading-icon"><i class="bi bi-bell"></i></div><div><h1>Notifications</h1><p>Manage user notifications and keep your customers informed.</p></div></div>
        <div class="notifications-header-actions"><button class="btn notification-mark-all-btn" :disabled="!state.stats.unread" @click="mutate(readAllUrl)"><i class="bi bi-check2-all"></i> Mark all as read</button><button class="btn notification-send-btn" data-bs-toggle="modal" data-bs-target="#sendNotificationModal"><i class="bi bi-send"></i> Send notification</button></div>
    </div>
    <div class="notification-stats-grid">
        <div v-for="item in [{key:'total',label:'Total notifications',sub:'All user notifications',icon:'bi-bell',tone:'total'},{key:'unread',label:'Unread',sub:'Waiting to be read',icon:'bi-envelope',tone:'unread'},{key:'read',label:'Read',sub:'Already viewed',icon:'bi-envelope-open',tone:'read'},{key:'users_notified',label:'Users notified',sub:'Unique active recipients',icon:'bi-people',tone:'users'}]" :key="item.key" class="notification-stat-card"><div class="notification-stat-icon" :class="item.tone"><i class="bi" :class="item.icon"></i></div><div class="notification-stat-content"><span>{{ item.label }}</span><strong>{{ Number(state.stats[item.key] || 0).toLocaleString() }}</strong><small>{{ item.sub }}</small></div></div>
    </div>
    <div class="dashboard-panel notifications-panel">
        <div class="notifications-panel-header"><div><h2>Notification history</h2><p>View and manage notifications sent to users.</p></div><div class="notification-filter-tabs"><button v-for="f in ['all','unread','read']" :key="f" type="button" class="notification-filter-tab" :class="{active:state.filter===f}" @click="changeFilter(f)">{{ f[0].toUpperCase()+f.slice(1) }} <span>{{ state.stats[f==='all'?'total':f] }}</span></button></div></div>
        <div class="notification-list" :class="{'is-loading':loading}">
            <div v-if="error" class="notifications-empty" role="alert"><h3>Unable to load notifications</h3><p>{{ error }}</p><button class="btn notification-empty-btn" @click="load()">Try again</button></div>
            <template v-else-if="state.rows.length">
                <div v-for="n in state.rows" :key="n.id" class="notification-item" :class="n.read_at ? 'is-read':'is-unread'">
                    <div class="notification-item-icon"><i class="bi" :class="iconFor(n.type)"></i></div>
                    <div class="notification-item-content"><div class="notification-item-top"><div class="notification-item-title-wrap"><span v-if="!n.read_at" class="notification-unread-dot"></span><h3>{{ n.title }}</h3></div><span class="notification-type" :class="`notification-type--${n.type.toLowerCase()}`">{{ pretty(n.type) }}</span></div><p class="notification-message">{{ n.message }}</p><div class="notification-meta"><span><i class="bi bi-person"></i>{{ n.user?.name || 'All admins' }} <span v-if="n.user?.email" class="notification-user-email">{{ n.user.email }}</span></span><span><i class="bi bi-clock"></i>{{ n.created_human }}</span><span><i class="bi bi-calendar3"></i>{{ n.created_at }}</span></div></div>
                    <div class="notification-item-actions"><button class="notification-action-btn" :title="n.read_at?'Mark as unread':'Mark as read'" @click="mutate(`${indexUrl}/${n.id}/${n.read_at?'unread':'read'}`)"><i class="bi" :class="n.read_at?'bi-envelope':'bi-envelope-open'"></i></button><button class="notification-action-btn" title="Delete" @click="mutate(`${indexUrl}/${n.id}`, 'DELETE', 'This notification will be permanently deleted.')"><i class="bi bi-trash3"></i></button></div>
                </div>
            </template>
            <div v-else-if="!loading" class="notifications-empty"><div class="notifications-empty-icon"><i class="bi bi-bell-slash"></i></div><h3>No notifications found</h3><p>There are no notifications matching the selected filter.</p><button v-if="state.filter!=='all'" class="btn notification-empty-btn notification-filter-tab" @click="changeFilter('all')">View all notifications</button></div>
        </div>
        <div v-if="state.pagination.links?.length>3" class="notifications-pagination"><ul class="pagination"><li v-for="(link,i) in state.pagination.links" :key="i" class="page-item" :class="{active:link.active,disabled:!link.url}"><a class="page-link" href="#" @click.prevent="linkPage(link)">{{ link.label.replace('&laquo;','«').replace('&raquo;','»') }}</a></li></ul></div>
    </div>
    <div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationModalLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content notification-modal"><div class="modal-header"><div class="notification-modal-title"><div class="notification-modal-icon"><i class="bi bi-send"></i></div><div><h5 id="sendNotificationModalLabel">Send notification</h5><p>Send a message directly to your users.</p></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><form @submit.prevent="send"><div class="modal-body"><div v-if="formError" class="alert alert-danger">{{ formError }}</div><div class="notification-recipient-selector"><label class="notification-form-label">Recipient</label><div class="recipient-options"><label class="recipient-option" :class="{active:form.recipient==='user'}"><input v-model="form.recipient" type="radio" value="user"><span class="recipient-option-icon"><i class="bi bi-person"></i></span><span><strong>Specific user</strong><small>Send to one active user</small></span></label><label class="recipient-option" :class="{active:form.recipient==='all'}"><input v-model="form.recipient" type="radio" value="all"><span class="recipient-option-icon"><i class="bi bi-people"></i></span><span><strong>All active users</strong><small>Send to every active user</small></span></label></div></div><div v-if="userRequired" class="notification-user-select-wrap"><label for="notificationUser" class="notification-form-label">Select user</label><select id="notificationUser" v-model="form.user_id" class="form-select notification-form-control" required><option value="">Select an active user</option><option v-for="user in state.users" :key="user.id" :value="user.id">{{ user.name }} — {{ user.email }}</option></select><small v-if="fieldErrors.user_id" class="text-danger">{{ fieldErrors.user_id[0] }}</small></div><div class="notification-form-row"><div class="notification-form-group"><label for="notificationType" class="notification-form-label">Type</label><select id="notificationType" v-model="form.type" class="form-select notification-form-control" required><option v-for="type in types" :key="type" :value="type">{{ pretty(type) }}</option></select></div><div class="notification-form-group"><label for="notificationTitle" class="notification-form-label">Title</label><input id="notificationTitle" v-model="form.title" class="form-control notification-form-control" maxlength="255" placeholder="Enter notification title" required><small v-if="fieldErrors.title" class="text-danger">{{ fieldErrors.title[0] }}</small></div></div><div class="notification-form-group"><label for="notificationMessage" class="notification-form-label">Message</label><textarea id="notificationMessage" v-model="form.message" class="form-control notification-form-control notification-message-input" rows="5" placeholder="Write your notification message..." required></textarea><small v-if="fieldErrors.message" class="text-danger">{{ fieldErrors.message[0] }}</small></div><div class="notification-send-note"><i class="bi bi-info-circle"></i><span>Notifications will appear immediately in the recipient's notification center.</span></div></div><div class="modal-footer"><button type="button" class="btn notification-modal-cancel" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn notification-modal-submit" :disabled="sending"><i class="bi bi-send"></i><span>{{ sending?'Sending…':'Send notification' }}</span></button></div></form></div></div></div>
</template>
