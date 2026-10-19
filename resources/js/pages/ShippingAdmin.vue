<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({ payload: { type: String, required: true } });
const state = JSON.parse(props.payload);
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
const shippings = ref(state.shippings || []);
const pagination = ref(state.pagination || {});
const stats = state.stats || {};
const search = ref(state.search || '');
const statusFilter = ref(state.statusFilter || '');
const successMessage = ref(state.success || '');
const errorMessage = ref(state.error || '');
const busy = ref(false);
const form = ref({ ...(state.old || {}) });
const errors = state.errors || {};
let searchTimer;
let activeRequest;

function money(value) {
    return Number(value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function rowDescription(value) {
    if (!value) return '—';
    return value.length > 55 ? `${value.slice(0, 52)}...` : value;
}

function queryUrl(base = state.urls.index) {
    const url = new URL(base, window.location.origin);
    if (search.value.trim()) url.searchParams.set('search', search.value.trim());
    if (statusFilter.value !== '') url.searchParams.set('status', statusFilter.value);
    return url;
}

async function loadPage(url, pushState = true) {
    activeRequest?.abort();
    const controller = new AbortController();
    activeRequest = controller;
    busy.value = true;
    errorMessage.value = '';
    try {
        const response = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            signal: controller.signal,
        });
        if (!response.ok) throw new Error('Unable to load shipping methods.');
        const result = await response.json();
        shippings.value = result.shippings || [];
        pagination.value = result.pagination || {};
        if (pushState) window.history.pushState({}, '', url);
        const parsed = new URL(url, window.location.origin);
        search.value = parsed.searchParams.get('search') || '';
        statusFilter.value = parsed.searchParams.get('status') || '';
    } catch (error) {
        if (error.name !== 'AbortError') {
            errorMessage.value = error.message || 'Unable to load shipping methods.';
            console.error('Shipping filter error:', error);
        }
    } finally {
        if (activeRequest === controller) {
            activeRequest = null;
            busy.value = false;
        }
    }
}

function filterNow() {
    clearTimeout(searchTimer);
    loadPage(queryUrl());
}

function searchChanged() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(filterNow, 400);
}

function resetFilters() {
    clearTimeout(searchTimer);
    search.value = '';
    statusFilter.value = '';
    loadPage(state.urls.index);
}

async function deleteShipping(item, event) {
    event.preventDefault();
    if (busy.value) return;

    let confirmed = false;
    if (window.Swal) {
        const result = await window.Swal.fire({
            title: 'Delete shipping method?',
            text: 'This shipping method will be permanently deleted.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel', reverseButtons: true,
        });
        confirmed = result.isConfirmed;
    } else {
        confirmed = window.confirm('Delete this shipping method permanently?');
    }
    if (!confirmed) return;

    busy.value = true;
    errorMessage.value = '';
    try {
        const body = new URLSearchParams({ _token: csrf, _method: 'DELETE' });
        const response = await fetch(state.urls.deletePattern.replace('__id__', encodeURIComponent(item.id)), {
            method: 'POST', credentials: 'same-origin',
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body,
        });
        const result = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(result.message || 'Unable to delete the shipping method.');
        successMessage.value = result.message || 'The shipping method has been deleted successfully.';
        await loadPage(queryUrl(), false);
        if (window.Swal) await window.Swal.fire({ icon: 'success', title: 'Deleted', text: successMessage.value, confirmButtonColor: '#2563eb' });
    } catch (error) {
        errorMessage.value = error.message || 'Unable to delete the shipping method.';
        if (window.Swal) await window.Swal.fire({ icon: 'error', title: 'Delete failed', text: errorMessage.value, confirmButtonColor: '#2563eb' });
    } finally {
        busy.value = false;
    }
}

function onPopState() {
    const url = new URL(window.location.href);
    search.value = url.searchParams.get('search') || '';
    statusFilter.value = url.searchParams.get('status') || '';
    loadPage(url, false);
}

onMounted(() => window.addEventListener('popstate', onPopState));
onBeforeUnmount(() => {
    clearTimeout(searchTimer);
    activeRequest?.abort();
    window.removeEventListener('popstate', onPopState);
});
</script>

<template>
    <main v-if="state.view === 'index'" class="dashboard-section shipping-page">
        <section class="shipping-hero"><div class="shipping-hero-content"><span class="shipping-hero-badge"><i class="bi bi-truck"></i> Shipping Management</span><h1>Manage shipping methods.</h1><p>Manage delivery options, pricing, availability and shipping methods for your marketplace.</p></div><div class="shipping-hero-mark" aria-hidden="true"><i class="bi bi-truck"></i></div></section>
        <div v-if="successMessage" class="shipping-alert shipping-alert-success"><i class="bi bi-check-circle"></i><span>{{ successMessage }}</span><button type="button" class="shipping-alert-close" aria-label="Close" @click="successMessage = ''"><i class="bi bi-x"></i></button></div>
        <div v-if="errorMessage" class="shipping-alert shipping-alert-danger" role="alert"><i class="bi bi-exclamation-circle"></i><span>{{ errorMessage }}</span><button type="button" class="shipping-alert-close" aria-label="Close" @click="errorMessage = ''"><i class="bi bi-x"></i></button></div>
        <section class="shipping-stats">
            <div v-for="card in [{label:'Total Shipping',value:stats.total,icon:'bi-truck',tone:'blue'},{label:'Active',value:stats.active,icon:'bi-check-circle',tone:'green'},{label:'Inactive',value:stats.inactive,icon:'bi-pause-circle',tone:'orange'},{label:'Free Shipping',value:stats.free,icon:'bi-gift',tone:'purple'}]" :key="card.label" class="shipping-stat-card" :class="`stat-${card.tone}`"><div class="shipping-stat-content"><span>{{ card.label }}</span><h3>{{ card.value }}</h3></div><div class="shipping-stat-icon"><i class="bi" :class="card.icon"></i></div></div>
        </section>
        <section class="shipping-panel">
            <div class="shipping-panel-header"><div class="shipping-heading-content"><span class="eyebrow">Shipping configuration</span><h5>Shipping Methods</h5><p>View and manage all available shipping methods.</p></div><div class="shipping-header-action"><a :href="state.urls.create" class="shipping-add-btn"><i class="bi bi-plus-lg"></i><span>Add Shipping</span></a></div></div>
            <form method="GET" :action="state.urls.index" class="shipping-filters" id="shipping-filters" @submit.prevent="filterNow">
                <div class="shipping-filter-group shipping-filter-search"><label for="shipping-search">Search</label><div class="shipping-search-field"><i class="bi bi-search"></i><input id="shipping-search" v-model="search" type="text" name="search" placeholder="Search shipping method..." @input="searchChanged"></div></div>
                <div class="shipping-filter-group"><label for="shipping-status">Status</label><select id="shipping-status" v-model="statusFilter" name="status" class="shipping-filter-select" @change="filterNow"><option value="">All Status</option><option value="1">Active</option><option value="0">Inactive</option></select></div>
                <div class="shipping-filter-actions"><button type="submit" class="shipping-filter-btn"><i class="bi bi-funnel"></i><span>Filter</span></button><a :href="state.urls.index" class="shipping-clear-filter" id="shipping-reset" @click.prevent="resetFilters"><i class="bi bi-arrow-counterclockwise"></i> Reset</a></div>
            </form>
            <div class="shipping-table-wrap" :style="busy ? {opacity:0.55,pointerEvents:'none'} : null"><table class="shipping-table"><thead><tr><th class="shipping-col-id">#</th><th class="shipping-col-method">Method</th><th class="shipping-col-description">Description</th><th class="shipping-col-price">Price</th><th class="shipping-col-delivery">Delivery Time</th><th class="shipping-col-status">Status</th><th class="shipping-col-actions">Action</th></tr></thead>
                <tbody><tr v-for="item in shippings" :key="item.id"><td class="shipping-id">#{{ item.id }}</td><td><div class="shipping-method"><div class="shipping-method-icon"><i class="bi bi-truck"></i></div><span>{{ item.name }}</span></div></td><td><span class="shipping-description">{{ rowDescription(item.description) }}</span></td><td><span v-if="Number(item.price) === 0" class="shipping-price shipping-price-free">Free</span><span v-else class="shipping-price">{{ money(item.price) }} AZN</span></td><td><span class="shipping-delivery"><i class="bi bi-clock"></i>{{ item.delivery_time }}</span></td><td><span class="shipping-status-pill" :class="item.status ? 'shipping-status-active' : 'shipping-status-inactive'"><span></span>{{ item.status ? 'Active' : 'Inactive' }}</span></td><td><div class="shipping-actions"><a :href="state.urls.editPattern.replace('__id__', item.id)" class="shipping-action-btn shipping-edit-btn" title="Edit" aria-label="Edit shipping method"><i class="bi bi-pencil"></i></a><form :action="state.urls.deletePattern.replace('__id__', item.id)" method="POST" class="delete-shipping-form" @submit="deleteShipping(item, $event)"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="shipping-action-btn shipping-delete-btn" title="Delete" aria-label="Delete shipping method" :disabled="busy"><i class="bi bi-trash"></i></button></form></div></td></tr>
                    <tr v-if="!shippings.length"><td colspan="7"><div class="shipping-empty-state"><div class="shipping-empty-icon"><i class="bi bi-truck"></i></div><h6>No shipping methods found.</h6><p>Try changing your filters or search.</p><a :href="state.urls.create" class="shipping-empty-btn"><i class="bi bi-plus-lg"></i> Add Shipping</a></div></td></tr></tbody>
            </table></div>
            <div v-if="pagination.total > 0 && pagination.last_page > 1" class="shipping-pagination"><div class="shipping-pagination-info">Showing <strong>{{ pagination.from }}</strong> to <strong>{{ pagination.to }}</strong> of <strong>{{ pagination.total }}</strong> shipping methods</div><div class="shipping-pagination-pages"><a v-if="pagination.previous_url" :href="pagination.previous_url" class="shipping-pager-btn" @click.prevent="loadPage(pagination.previous_url)"><i class="bi bi-chevron-left"></i></a><span v-else class="shipping-pager-btn disabled"><i class="bi bi-chevron-left"></i></span><template v-for="page in pagination.pages" :key="page.page"><span v-if="page.page === pagination.current_page" class="shipping-pager-btn active">{{ page.page }}</span><a v-else :href="page.url" class="shipping-pager-btn" @click.prevent="loadPage(page.url)">{{ page.page }}</a></template><a v-if="pagination.next_url" :href="pagination.next_url" class="shipping-pager-btn" @click.prevent="loadPage(pagination.next_url)"><i class="bi bi-chevron-right"></i></a><span v-else class="shipping-pager-btn disabled"><i class="bi bi-chevron-right"></i></span></div></div>
        </section>
    </main>

    <main v-else class="shipping-page">
        <div class="shipping-panel"><div class="shipping-panel-header"><div class="shipping-heading-content"><span class="eyebrow"><i class="bi bi-truck me-1"></i> Shipping Management</span><h5>{{ state.view === 'edit' ? 'Edit Shipping' : 'Add Shipping' }}</h5><p>{{ state.view === 'edit' ? 'Update shipping method information, pricing, delivery time and availability.' : 'Create a new shipping method with pricing, delivery time and availability.' }}</p></div><div class="shipping-header-action"><a :href="state.urls.index" class="shipping-back-btn"><i class="bi bi-arrow-left"></i><span>Back to Shipping</span></a></div></div>
            <div class="shipping-form-body"><div v-if="Object.keys(errors).length" class="shipping-errors"><div class="shipping-errors-title"><i class="bi bi-exclamation-triangle"></i> Please fix the following errors:</div><ul><li v-for="(messages,key) in errors" :key="key">{{ messages[0] }}</li></ul></div>
                <form :action="state.view === 'edit' ? state.urls.update : state.urls.store" method="POST"><input type="hidden" name="_token" :value="csrf"><input v-if="state.view === 'edit'" type="hidden" name="_method" value="PUT"><div class="shipping-form-section"><div class="shipping-section-heading"><i class="bi bi-truck"></i><span>Shipping Information</span></div><div class="shipping-form-grid">
                    <div class="shipping-field"><label for="name">Shipping Method Name <span class="required">*</span></label><input id="name" v-model="form.name" type="text" name="name" :class="{'is-invalid':errors.name}" placeholder="e.g. Standard Shipping" required><div v-if="errors.name" class="shipping-field-error">{{ errors.name[0] }}</div></div>
                    <div class="shipping-field"><label for="price">Price <span class="required">*</span></label><input id="price" v-model="form.price" type="number" name="price" step="0.01" min="0" :class="{'is-invalid':errors.price}" placeholder="0.00" required><div v-if="errors.price" class="shipping-field-error">{{ errors.price[0] }}</div></div>
                    <div class="shipping-field"><label for="delivery_time">Delivery Time <span class="required">*</span></label><input id="delivery_time" v-model="form.delivery_time" type="text" name="delivery_time" :class="{'is-invalid':errors.delivery_time}" placeholder="e.g. 3-5 Business Days" required><div v-if="errors.delivery_time" class="shipping-field-error">{{ errors.delivery_time[0] }}</div></div>
                    <div class="shipping-field"><label for="status">Status</label><select id="status" v-model="form.status" name="status"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                    <div class="shipping-field shipping-field-full"><label for="description">Description</label><textarea id="description" v-model="form.description" name="description" rows="5" placeholder="Describe this shipping method..."></textarea><div v-if="errors.description" class="shipping-field-error">{{ errors.description[0] }}</div></div>
                </div></div><div class="shipping-form-footer"><a :href="state.urls.index" class="shipping-cancel-btn"><i class="bi bi-x-lg"></i> Cancel</a><button type="submit" class="shipping-submit-btn"><i class="bi bi-check-circle"></i> {{ state.view === 'edit' ? 'Update Shipping' : 'Create Shipping' }}</button></div></form>
            </div>
        </div>
    </main>
</template>
