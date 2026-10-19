<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({ payload: { type: String, required: true } });
const state = JSON.parse(props.payload);
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
const faqs = ref(state.faqs || []);
const categories = ref(state.categories || []);
const pagination = ref(state.pagination || {});
const filters = ref({ ...(state.filters || {}) });
const success = ref(state.success || '');
const error = ref('');
const busy = ref(false);
const form = ref({ ...(state.old || {}) });
const errors = state.errors || {};
const pageLinks = computed(() => Object.entries(pagination.value.links || {}));
let debounceTimer;
let activeRequest;

function filterUrl(base = state.urls.index) {
    const url = new URL(base, window.location.origin);
    for (const key of ['search', 'category', 'status']) {
        const value = String(filters.value[key] || '').trim();
        if (value) url.searchParams.set(key, value);
    }
    return url;
}

async function loadFaqs(url, updateHistory = true) {
    activeRequest?.abort();
    const controller = new AbortController();
    activeRequest = controller;
    busy.value = true;
    error.value = '';
    try {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            signal: controller.signal,
        });
        if (!response.ok) throw new Error('Failed to load FAQs.');
        const result = await response.json();
        faqs.value = result.faqs || [];
        categories.value = result.categories || [];
        pagination.value = result.pagination || {};
        for (const key of ['search', 'category', 'status']) filters.value[key] = new URL(url, location.origin).searchParams.get(key) || '';
        if (updateHistory) history.pushState({}, '', url);
    } catch (exception) {
        if (exception.name !== 'AbortError') {
            error.value = exception.message || 'Failed to load FAQs.';
            console.error('FAQ loading error:', exception);
        }
    } finally {
        if (activeRequest === controller) {
            activeRequest = null;
            busy.value = false;
        }
    }
}

function filterChanged() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadFaqs(filterUrl()), 350);
}

function resetFilters() {
    clearTimeout(debounceTimer);
    filters.value = { search: '', category: '', status: '' };
    loadFaqs(state.urls.index);
}

async function removeFaq(faq, event) {
    event.preventDefault();
    if (busy.value) return;
    let confirmed;
    if (window.Swal) {
        const result = await window.Swal.fire({
            title: 'Delete FAQ?', text: `Delete “${faq.question}”?`, icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel', reverseButtons: true,
        });
        confirmed = result.isConfirmed;
    } else confirmed = confirm('Delete this FAQ?');
    if (!confirmed) return;

    busy.value = true;
    error.value = '';
    try {
        const response = await fetch(state.urls.deletePattern.replace('__id__', encodeURIComponent(faq.id)), {
            method: 'POST', credentials: 'same-origin',
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: new URLSearchParams({ _token: csrf, _method: 'DELETE' }),
        });
        const result = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(result.message || 'Unable to delete FAQ.');
        success.value = result.message || 'FAQ deleted successfully.';
        await loadFaqs(filterUrl(), false);
    } catch (exception) {
        error.value = exception.message || 'Unable to delete FAQ.';
        if (window.Swal) await window.Swal.fire({ icon: 'error', title: 'Delete failed', text: error.value });
    } finally {
        busy.value = false;
    }
}

function onPopState() {
    const url = new URL(location.href);
    filters.value = { search: url.searchParams.get('search') || '', category: url.searchParams.get('category') || '', status: url.searchParams.get('status') || '' };
    loadFaqs(url, false);
}

onMounted(() => window.addEventListener('popstate', onPopState));
onBeforeUnmount(() => {
    clearTimeout(debounceTimer);
    activeRequest?.abort();
    window.removeEventListener('popstate', onPopState);
});
</script>

<template>
    <main v-if="state.view === 'index'" class="dashboard-section faq-page">
        <div class="dashboard-panel faq-header-panel"><div class="faq-header-content"><div><h5>FAQ</h5><p>Manage frequently asked questions on SecondBook</p></div><a :href="state.urls.create" class="faq-add-btn"><i class="bi bi-plus-lg"></i><span>Add FAQ</span></a></div></div>
        <div v-if="success" class="faq-alert"><i class="bi bi-check-circle-fill"></i><span>{{ success }}</span><button type="button" class="faq-alert-close" @click="success = ''"><i class="bi bi-x"></i></button></div>
        <div v-if="error" class="faq-alert" role="alert"><i class="bi bi-exclamation-triangle"></i><span>{{ error }}</span><button type="button" class="faq-alert-close" @click="error = ''"><i class="bi bi-x"></i></button></div>
        <div class="dashboard-panel faq-filter-panel"><form id="faq-filter-form" :action="state.urls.index" method="GET" @submit.prevent="loadFaqs(filterUrl())"><div class="faq-filter-row">
            <div class="faq-search"><label for="faq-search">Search</label><div class="faq-search-group"><i class="bi bi-search"></i><input id="faq-search" v-model="filters.search" type="text" name="search" placeholder="Question, answer or category..." @input="filterChanged"></div></div>
            <div class="faq-category-filter"><label for="faq-category">Category</label><select id="faq-category" v-model="filters.category" name="category" @change="loadFaqs(filterUrl())"><option value="">All Categories</option><option v-for="category in categories" :key="category" :value="category">{{ category }}</option></select></div>
            <div class="faq-status-filter"><label for="faq-status">Status</label><select id="faq-status" v-model="filters.status" name="status" @change="loadFaqs(filterUrl())"><option value="">All Status</option><option value="1">Active</option><option value="0">Inactive</option></select></div>
            <div class="faq-filter-actions"><button type="submit" class="faq-search-btn"><i class="bi bi-funnel"></i> Filter</button><a v-if="filters.search || filters.category || filters.status" :href="state.urls.index" class="faq-reset-btn" title="Reset filters" @click.prevent="resetFilters"><i class="bi bi-arrow-counterclockwise"></i></a></div>
        </div></form></div>
        <div id="faq-results" class="dashboard-panel faq-table-panel">
            <div class="faq-table-header"><div><h5>FAQ List</h5><p>All frequently asked questions</p></div><span class="faq-count">{{ pagination.total || 0 }} FAQs</span></div>
            <div class="faq-table-wrapper" :style="busy ? {opacity:0.55,pointerEvents:'none'} : null"><table class="faq-table"><thead><tr><th class="faq-col-id">#</th><th>Question</th><th class="faq-col-category">Category</th><th class="faq-col-answer">Answer</th><th class="faq-col-sort">Sort</th><th class="faq-col-status">Status</th><th class="faq-col-date">Created</th><th class="faq-col-actions">Actions</th></tr></thead>
                <tbody><tr v-for="faq in faqs" :key="faq.id"><td class="faq-id">{{ faq.id }}</td><td><div class="faq-question-info"><div class="faq-question-title">{{ faq.question }}</div><div class="faq-mobile-category">{{ faq.category }}</div></div></td><td><span class="faq-category">{{ faq.category }}</span></td><td><div class="faq-answer">{{ faq.answer }}</div></td><td><span class="faq-sort">{{ faq.sort_order }}</span></td><td><span class="faq-status" :class="faq.is_active ? 'faq-status-active' : 'faq-status-inactive'"><span></span>{{ faq.is_active ? 'Active' : 'Inactive' }}</span></td><td><span class="faq-date">{{ faq.created_at }}</span></td><td><div class="faq-actions"><a :href="state.urls.editPattern.replace('__id__', faq.id)" class="faq-edit-btn" title="Edit FAQ"><i class="bi bi-pencil"></i></a><form :action="state.urls.deletePattern.replace('__id__', faq.id)" method="POST" class="faq-delete-form" @submit="removeFaq(faq, $event)"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="faq-delete-btn" title="Delete FAQ" :disabled="busy"><i class="bi bi-trash"></i></button></form></div></td></tr>
                    <tr v-if="!faqs.length"><td colspan="8"><div class="faq-empty"><div class="faq-empty-icon"><i class="bi bi-question-circle"></i></div><h6>No FAQs Found</h6><p>Create your first FAQ to get started.</p><a :href="state.urls.create" class="faq-add-btn"><i class="bi bi-plus-lg"></i> Add FAQ</a></div></td></tr></tbody>
            </table></div>
            <div v-if="pagination.last_page > 1" class="faq-pagination"><nav aria-label="FAQ pagination"><ul class="pagination"><li v-for="entry in pageLinks" :key="entry[0]" class="page-item" :class="{active:Number(entry[0])===pagination.current_page}"><a class="page-link" :href="entry[1]" @click.prevent="loadFaqs(entry[1])">{{ entry[0] }}</a></li></ul></nav></div>
        </div>
    </main>
    <main v-else class="dashboard-section faq-page">
        <div class="dashboard-panel faq-header-panel"><div class="faq-header-content"><div><h5>{{ state.view === 'edit' ? 'Edit FAQ' : 'Create FAQ' }}</h5><p>{{ state.view === 'edit' ? 'Update the question, answer and settings for this FAQ' : 'Add a frequently asked question to SecondBook' }}</p></div><a :href="state.urls.index" class="faq-back-btn"><i class="bi bi-arrow-left"></i><span>Back to FAQ</span></a></div></div>
        <div v-if="Object.keys(errors).length" class="faq-validation-alert"><div class="faq-validation-icon"><i class="bi bi-exclamation-triangle-fill"></i></div><div><strong>Please fix the following errors:</strong><ul><li v-for="(messages,key) in errors" :key="key">{{ messages[0] }}</li></ul></div></div>
        <div class="dashboard-panel faq-form-panel"><form :action="state.view === 'edit' ? state.urls.update : state.urls.store" method="POST"><input type="hidden" name="_token" :value="csrf"><input v-if="state.view === 'edit'" type="hidden" name="_method" value="PUT">
            <div class="faq-form-section"><div class="faq-form-section-header"><div class="faq-form-section-icon"><i class="bi bi-question-circle"></i></div><div><h6>FAQ Information</h6><p>Enter the question, answer and category.</p></div></div><div class="faq-form-grid">
                <div class="faq-form-group faq-form-full"><label for="question">Question <span>*</span></label><input id="question" v-model="form.question" type="text" name="question" class="form-control" placeholder="Enter frequently asked question..." maxlength="255" required><div v-if="errors.question" class="invalid-feedback d-block">{{ errors.question[0] }}</div></div>
                <div class="faq-form-group faq-form-full"><label for="answer">Answer <span>*</span></label><textarea id="answer" v-model="form.answer" name="answer" rows="8" class="form-control faq-answer-input" placeholder="Write the answer to this question..." maxlength="5000" required></textarea><small>Provide a clear and helpful answer for your customers.</small><div v-if="errors.answer" class="invalid-feedback d-block">{{ errors.answer[0] }}</div></div>
            </div></div>
            <div class="faq-form-section"><div class="faq-form-section-header"><div class="faq-form-section-icon"><i class="bi bi-sliders"></i></div><div><h6>Organization</h6><p>Set the category, display order and visibility of this FAQ.</p></div></div><div class="faq-form-grid">
                <div class="faq-form-group"><label for="category">Category <span>*</span></label><input id="category" v-model="form.category" type="text" name="category" class="form-control" placeholder="e.g. Orders, Payments, Sellers..." maxlength="100" required><div v-if="errors.category" class="invalid-feedback d-block">{{ errors.category[0] }}</div></div>
                <div class="faq-form-group"><label for="sort_order">Sort Order</label><input id="sort_order" v-model="form.sort_order" type="number" name="sort_order" class="form-control" min="0" placeholder="0"><small>Lower numbers appear first.</small><div v-if="errors.sort_order" class="invalid-feedback d-block">{{ errors.sort_order[0] }}</div></div>
                <div class="faq-form-group"><label for="is_active">Status <span>*</span></label><select id="is_active" v-model="form.is_active" name="is_active" class="form-select" required><option value="1">Active</option><option value="0">Inactive</option></select><small>Only active FAQs are displayed on the website.</small></div>
            </div></div>
            <div class="faq-form-actions"><a :href="state.urls.index" class="faq-cancel-btn">Cancel</a><button type="submit" class="faq-save-btn"><i class="bi bi-check-lg"></i> {{ state.view === 'edit' ? 'Update FAQ' : 'Create FAQ' }}</button></div>
        </form></div>
    </main>
</template>
