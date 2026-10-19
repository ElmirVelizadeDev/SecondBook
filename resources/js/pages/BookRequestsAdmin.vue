<script setup>
import { computed, ref } from 'vue';

const props = defineProps({ payload: { type: String, required: true } });
const state = JSON.parse(props.payload);
const requests = state.requests || [];
const categories = state.categories || [];
const filters = ref({ ...(state.filters || {}) });
const book = state.book || {};
const form = ref({ ...(state.old || {}) });
const errors = state.errors || {};
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
const counts = computed(() => ({
    total: requests.length,
    pending: requests.filter((item) => item.status === 'pending').length,
    approved: requests.filter((item) => item.status === 'approved').length,
    rejected: requests.filter((item) => item.status === 'rejected').length,
}));

function statusClass(status) {
    return ({ approved: 'book-request-status-approved', rejected: 'book-request-status-rejected', changes_requested: 'book-request-status-changes' })[status] || 'book-request-status-pending';
}

function removeRequest(url) {
    const submit = () => {
        const formElement = document.createElement('form');
        formElement.method = 'POST';
        formElement.action = url;
        const token = document.createElement('input');
        token.type = 'hidden'; token.name = '_token'; token.value = csrf;
        const method = document.createElement('input');
        method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
        formElement.append(token, method);
        document.body.append(formElement);
        formElement.submit();
    };
    if (window.Swal) {
        window.Swal.fire({ title: 'Delete book request?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel', reverseButtons: true, customClass: { popup: 'swal2-dark' } }).then((result) => { if (result.isConfirmed) submit(); });
    } else if (window.confirm('Are you sure you want to delete this book request?')) submit();
}
</script>

<template>
    <div class="dashboard-section book-request-page">
        <section class="book-request-hero">
            <div class="book-request-hero-content">
                <div class="book-request-hero-text">
                    <span class="book-request-hero-badge"><i class="bi bi-inbox"></i> {{ state.view === 'edit' ? 'Seller Submission' : 'Seller Submissions' }}</span>
                    <h1>{{ state.view === 'edit' ? 'Review Book Request' : 'Book Requests, reviewed with care.' }}</h1>
                    <p>{{ state.view === 'edit' ? 'Review the seller’s book submission and respond to the request.' : 'Manage books submitted by sellers and decide which ones are ready for the SecondBook marketplace.' }}</p>
                </div>
                <div class="book-request-hero-mark"><i :class="state.view === 'edit' ? 'bi bi-eye' : 'bi bi-inbox'"></i></div>
            </div>
        </section>

        <template v-if="state.view === 'index'">
            <div v-if="state.success" class="alert alert-success">{{ state.success }}</div>
            <div v-if="state.error" class="alert alert-danger">{{ state.error }}</div>
            <section class="book-request-stats">
                <div v-for="stat in [{name:'Total Requests',value:counts.total,icon:'bi-inbox',color:'stat-blue'},{name:'Pending',value:counts.pending,icon:'bi-hourglass-split',color:'stat-orange'},{name:'Approved',value:counts.approved,icon:'bi-check-circle',color:'stat-green'},{name:'Rejected',value:counts.rejected,icon:'bi-x-circle',color:'stat-red'}]" :key="stat.name" class="book-request-stat-card">
                    <div class="book-request-stat-icon" :class="stat.color"><i class="bi" :class="stat.icon"></i></div>
                    <div class="book-request-stat-content"><div class="book-request-stat-label">{{ stat.name }}</div><div class="book-request-stat-value">{{ stat.value }}</div></div>
                </div>
            </section>
            <section class="dashboard-panel book-request-panel">
                <div class="book-request-panel-header">
                    <div class="book-request-heading-content"><h2 class="book-request-panel-title">Seller Book Requests</h2><p class="book-request-panel-description">Books waiting for admin review.</p></div>
                    <div class="book-request-header-action"><span class="book-request-count-badge"><i class="bi bi-inbox"></i> {{ requests.length }} {{ requests.length === 1 ? 'request' : 'requests' }}</span></div>
                </div>
                <form method="GET" :action="state.urls.index" class="book-request-filters" autocomplete="off">
                    <div class="book-request-filter-grid">
                        <div class="book-request-filter-group"><label class="book-request-filter-label">Search requests</label><div class="book-request-search-field"><i class="bi bi-search"></i><input v-model="filters.search" type="text" name="search" class="book-request-input" placeholder="Search book or seller..."></div></div>
                        <div class="book-request-filter-group"><label class="book-request-filter-label">Category</label><select v-model="filters.category" name="category" class="book-request-select"><option value="">All Categories</option><option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option></select></div>
                        <div class="book-request-filter-actions"><button type="submit" class="book-request-filter-btn"><i class="bi bi-funnel"></i> Filter</button><a v-if="filters.search || filters.category" :href="state.urls.index" class="book-request-clear-filter"><i class="bi bi-x-lg"></i> Reset</a></div>
                    </div>
                </form>
                <div class="book-request-table-wrap">
                    <table class="book-request-table"><thead><tr><th class="book-request-col-id">ID</th><th class="book-request-col-book">Book</th><th class="book-request-col-seller">Seller</th><th class="book-request-col-category">Category</th><th class="book-request-col-price">Price</th><th class="book-request-col-stock">Stock</th><th class="book-request-col-condition">Condition</th><th class="book-request-col-status">Status</th><th class="book-request-col-date">Date</th><th class="book-request-col-actions">Actions</th></tr></thead>
                        <tbody><tr v-for="item in requests" :key="item.id">
                            <td><span class="book-request-id">#{{ item.id }}</span></td>
                            <td><div class="book-request-item-cell"><div class="book-request-cover"><img v-if="item.cover" :src="item.cover" :alt="item.title"><i v-else class="bi bi-book"></i></div><div class="book-request-item-info"><span class="book-request-item-name" :title="item.title">{{ item.title }}</span><span v-if="item.isbn" class="book-request-item-meta">ISBN: {{ item.isbn }}</span></div></div></td>
                            <td><div v-if="item.seller" class="book-request-item-info"><span class="book-request-item-name">{{ item.seller.name }}</span><span v-if="item.seller.email" class="book-request-item-meta">{{ item.seller.email }}</span></div><span v-else class="book-request-muted">Unknown seller</span></td>
                            <td><span v-if="item.category" class="book-request-category">{{ item.category }}</span><span v-else class="book-request-muted">—</span></td>
                            <td><span class="book-request-price">₼{{ Number(item.price || 0).toFixed(2) }}</span></td>
                            <td><span class="book-request-stock"><i class="bi bi-stack"></i> {{ item.stock }}</span></td>
                            <td>{{ String(item.condition || '—').replaceAll('_', ' ') }}</td>
                            <td><span class="book-request-status-pill" :class="statusClass(item.status)">{{ String(item.status || '').replaceAll('_', ' ') }}</span></td>
                            <td>{{ item.created_at || '—' }}</td>
                            <td><div class="book-request-actions"><a :href="item.edit_url" class="book-request-action-btn book-request-review-btn" title="Review Request"><i class="bi bi-eye"></i></a><button type="button" class="book-request-action-btn book-request-delete-btn" title="Delete Request" @click="removeRequest(item.delete_url)"><i class="bi bi-trash"></i></button></div></td>
                        </tr><tr v-if="!requests.length"><td colspan="10"><div class="book-request-empty-state"><div class="book-request-empty-icon"><i class="bi bi-inbox"></i></div><h5>No Book Requests</h5><p>There are currently no books waiting for approval.</p></div></td></tr></tbody>
                    </table>
                </div>
            </section>
        </template>

        <template v-else>
            <section class="dashboard-panel book-request-panel">
                <div class="book-request-panel-header"><div class="book-request-heading-content"><h2 class="book-request-panel-title">Book Information</h2><p class="book-request-panel-description">Information submitted by the seller.</p></div><div class="book-request-header-action book-request-header-pill"><span class="book-request-status-pill" :class="statusClass(book.status)">{{ String(book.status || '').replaceAll('_', ' ') }}</span><a :href="state.urls.index" class="book-request-clear-filter"><i class="bi bi-arrow-left"></i> Back to Requests</a></div></div>
                <div class="book-request-detail-layout"><div class="book-request-detail-side"><div class="book-request-detail-cover"><img v-if="book.cover" :src="book.cover" :alt="book.title"><div v-else class="book-request-detail-placeholder"><i class="bi bi-book"></i><span>No Cover</span></div></div></div><div class="book-request-detail-main"><div class="book-request-form-grid">
                    <div v-for="field in [{label:'Title',value:book.title},{label:'ISBN',value:book.isbn},{label:'Category',value:book.category},{label:'Author',value:book.author},{label:'Publisher',value:book.publisher},{label:'Seller',value:book.seller},{label:'Publication Year',value:book.publication_year},{label:'Pages',value:book.pages},{label:'Language',value:book.language},{label:'Price',value:`₼${Number(book.price || 0).toFixed(2)}`},{label:'Stock',value:book.stock},{label:'Condition',value:String(book.condition || '').replaceAll('_',' ')}]" :key="field.label" class="book-request-form-group book-request-field-half"><label class="book-request-filter-label">{{ field.label }}</label><div class="book-request-readonly">{{ field.value || 'Not provided' }}</div></div>
                    <div class="book-request-form-group book-request-field-full"><label class="book-request-filter-label">Description</label><div class="book-request-readonly is-multiline">{{ book.description || 'No description provided.' }}</div></div>
                </div></div></div>
            </section>
            <section class="dashboard-panel book-request-panel"><div class="book-request-panel-header"><div class="book-request-heading-content"><h2 class="book-request-panel-title">Admin Response</h2><p class="book-request-panel-description">Send a response to the seller about this book request.</p></div></div>
                <form :action="state.urls.update" method="POST"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="PUT">
                    <div v-if="Object.keys(errors).length" class="book-request-form-section"><div class="book-request-alert"><i class="bi bi-exclamation-circle-fill"></i><div><strong>Please fix the following errors:</strong><ul><template v-for="(messages,key) in errors" :key="key"><li v-for="message in messages" :key="message">{{ message }}</li></template></ul></div></div></div>
                    <div class="book-request-form-section"><div class="book-request-form-grid"><div class="book-request-form-group book-request-field-half"><label for="status" class="book-request-filter-label">Request Status</label><select id="status" v-model="form.status" name="status" class="book-request-select" :class="{ 'is-invalid': errors.status }"><option value="pending">Pending</option><option value="approved">Approved</option><option value="changes_requested">Changes Requested</option><option value="rejected">Rejected</option></select><div v-for="error in errors.status" :key="error" class="book-request-field-error">{{ error }}</div></div>
                        <div class="book-request-form-group book-request-field-full"><label for="message" class="book-request-filter-label">Message to Seller</label><textarea id="message" v-model="form.message" name="message" rows="5" class="book-request-textarea" :class="{ 'is-invalid': errors.message }" placeholder="Write a message for the seller..."></textarea><div class="book-request-form-hint">The seller will receive this message as a notification.</div><div v-for="error in errors.message" :key="error" class="book-request-field-error">{{ error }}</div></div></div></div>
                    <div class="book-request-form-section"><div class="book-request-form-actions"><button type="submit" class="book-request-filter-btn"><i class="bi bi-send"></i> Send Response</button><a :href="state.urls.index" class="book-request-clear-filter">Cancel</a></div></div>
                </form>
            </section>
        </template>
    </div>
</template>
