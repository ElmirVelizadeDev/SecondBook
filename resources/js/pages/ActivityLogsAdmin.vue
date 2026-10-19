<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({ payload: { type: String, required: true } });
const initial = JSON.parse(props.payload);
const logs = ref(initial.logs || []);
const actions = ref(initial.actions || []);
const modules = ref(initial.modules || []);
const filters = ref({ ...(initial.filters || {}) });
const pagination = ref(initial.pagination || {});
const busy = ref(false);
const error = ref('');
const opened = ref('');
let controller;

function queryUrl() {
    const url = new URL(initial.urls.index, window.location.origin);
    Object.entries(filters.value).forEach(([key, value]) => {
        if (String(value || '').trim()) url.searchParams.set(key, String(value).trim());
    });
    return url;
}

async function load(url, push = true) {
    controller?.abort();
    const current = new AbortController();
    controller = current;
    busy.value = true;
    error.value = '';
    opened.value = '';
    try {
        const response = await fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: current.signal });
        if (!response.ok) throw new Error('Failed to load activity logs.');
        const data = await response.json();
        logs.value = data.logs || [];
        actions.value = data.actions || [];
        modules.value = data.modules || [];
        pagination.value = data.pagination || {};
        filters.value = { ...(data.filters || {}) };
        if (push) history.pushState({}, '', url);
    } catch (exception) {
        if (exception.name !== 'AbortError') {
            error.value = exception.message || 'Failed to load activity logs.';
            console.error('Activity log request failed:', exception);
        }
    } finally {
        if (controller === current) { controller = null; busy.value = false; }
    }
}

function selectFilter(key, value) {
    filters.value[key] = value;
    opened.value = '';
}

function handleOutsideClick(event) {
    if (!event.target.closest('.activity-filter-select')) opened.value = '';
}

function handleEscape(event) {
    if (event.key === 'Escape') opened.value = '';
}

function handlePopstate() {
    const url = new URL(location.href);
    filters.value = { search: url.searchParams.get('search') || '', action: url.searchParams.get('action') || '', module: url.searchParams.get('module') || '' };
    load(url, false);
}

onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
    document.addEventListener('keydown', handleEscape);
    window.addEventListener('popstate', handlePopstate);
});
onBeforeUnmount(() => {
    controller?.abort();
    document.removeEventListener('click', handleOutsideClick);
    document.removeEventListener('keydown', handleEscape);
    window.removeEventListener('popstate', handlePopstate);
});
</script>

<template>
    <div class="dashboard-section activity-logs-page">
        <div class="dashboard-panel mb-4"><div class="activity-logs-header"><div class="activity-logs-heading"><div class="activity-logs-icon"><i class="bi bi-activity"></i></div><div><h2>Activity Logs</h2><p>Monitor important activities performed in the system.</p></div></div><div class="activity-logs-count"><i class="bi bi-clock-history"></i><span class="activity-total-count">{{ pagination.total }}</span> Activities</div></div></div>
        <div class="dashboard-panel activity-logs-filter-panel mb-4"><form class="activity-logs-filter-form" @submit.prevent="load(queryUrl())">
            <div class="activity-filter-search"><i class="bi bi-search"></i><input v-model="filters.search" type="text" name="search" placeholder="Search activities..."></div>
            <div v-for="filter in [{key:'action',label:'All Actions',options:actions.map(x=>({value:x,label:x.charAt(0).toUpperCase()+x.slice(1)}))},{key:'module',label:'All Modules',options:modules.map(x=>({value:x,label:x}))}]" :key="filter.key" class="activity-filter-select" :class="{ 'is-open': opened === filter.key }">
                <input type="hidden" :name="filter.key" :value="filters[filter.key]">
                <button type="button" class="activity-filter-dropdown-toggle" aria-haspopup="listbox" :aria-expanded="opened === filter.key" @click="opened = opened === filter.key ? '' : filter.key"><span class="activity-filter-dropdown-label">{{ filter.options.find(item=>item.value === filters[filter.key])?.label || filter.label }}</span><i class="bi bi-chevron-down"></i></button>
                <div class="activity-filter-dropdown-menu" role="listbox"><button type="button" class="activity-filter-dropdown-option" :class="{active:!filters[filter.key]}" @click="selectFilter(filter.key,'')">{{ filter.label }}</button><button v-for="option in filter.options" :key="option.value" type="button" class="activity-filter-dropdown-option" :class="{active:filters[filter.key] === option.value}" @click="selectFilter(filter.key,option.value)">{{ option.label }}</button></div>
            </div>
            <button type="submit" class="activity-filter-btn" :disabled="busy"><i class="bi bi-funnel"></i> Filter</button><button v-if="filters.search || filters.action || filters.module" type="button" class="activity-clear-btn" @click="filters={search:'',action:'',module:''}; load(queryUrl())"><i class="bi bi-x-lg"></i> Clear</button>
        </form></div>
        <div class="dashboard-panel activity-logs-panel" :class="{'is-loading':busy}"><p v-if="error" class="alert alert-danger" role="alert">{{ error }}</p><div class="activity-logs-table-wrapper"><table class="activity-logs-table"><thead><tr><th>User</th><th>Activity</th><th>Module</th><th>IP Address</th><th>Date</th></tr></thead><tbody>
            <tr v-for="log in logs" :key="log.id"><td><div class="activity-user"><div class="activity-user-avatar"><img v-if="log.user.avatar" :src="log.user.avatar" :alt="log.user.name"><template v-else>{{ (log.user.name || 'S').slice(0,1).toUpperCase() }}</template></div><div class="activity-user-info"><strong>{{ log.user.name }}</strong><span>{{ log.user.email }}</span></div></div></td><td><div class="activity-description"><span class="activity-action" :class="`action-${String(log.action || '').toLowerCase()}`">{{ log.action ? log.action.charAt(0).toUpperCase()+log.action.slice(1) : '—' }}</span><p>{{ log.description }}</p></div></td><td><span v-if="log.module" class="activity-module">{{ log.module }}</span><span v-else class="activity-empty">—</span></td><td><span class="activity-ip">{{ log.ip_address || '—' }}</span></td><td><div class="activity-date"><strong>{{ log.date }}</strong><span>{{ log.time }}</span></div></td></tr>
            <tr v-if="!logs.length"><td colspan="5"><div class="activity-empty-state"><div class="activity-empty-icon"><i class="bi bi-activity"></i></div><h3>No activity logs found</h3><p>There are no activities matching your current filters.</p></div></td></tr>
        </tbody></table></div>
        <div v-if="pagination.last_page > 1" class="activity-logs-pagination"><div class="activity-pagination-info">Showing <strong>{{ pagination.first_item }}</strong> to <strong>{{ pagination.last_item }}</strong> of <strong>{{ pagination.total }}</strong> results</div><div class="activity-pagination-pages"><button class="activity-page" :class="{disabled:!pagination.previous}" :disabled="!pagination.previous" @click="pagination.previous && load(pagination.previous)">‹</button><button v-for="(url,page) in pagination.pages" :key="page" class="activity-page" :class="{active:Number(page) === Number(pagination.current_page)}" @click="Number(page) !== Number(pagination.current_page) && load(url)">{{ page }}</button><button class="activity-page" :class="{disabled:!pagination.next}" :disabled="!pagination.next" @click="pagination.next && load(pagination.next)">›</button></div></div>
        </div>
    </div>
</template>
