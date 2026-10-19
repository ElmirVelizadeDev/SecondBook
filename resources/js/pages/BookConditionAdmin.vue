<script setup>
import { computed, ref } from 'vue';

const props = defineProps({ payload: { type: String, required: true } });
const state = JSON.parse(props.payload);
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
const query = ref(state.search || '');
const statusFilter = ref(state.statusFilter || '');
const condition = state.condition || {};
const old = state.old || {};
const errors = state.errors || {};
const conditions = state.conditions || [];
const filtered = computed(() => conditions.filter((item) => {
    const matchesName = !query.value || item.name.toLowerCase().includes(query.value.toLowerCase());
    const matchesStatus = !statusFilter.value || (statusFilter.value === 'active' ? Number(item.status) === 1 : Number(item.status) === 0);
    return matchesName && matchesStatus;
}));
const stats = computed(() => ({
    total: conditions.length,
    active: conditions.filter((item) => Number(item.status) === 1).length,
    inactive: conditions.filter((item) => Number(item.status) === 0).length,
    books: conditions.reduce((sum, item) => sum + Number(item.books_count || 0), 0),
}));
const replaceId = (pattern, id) => pattern.replace('__condition__', encodeURIComponent(id));

function confirmAction(event, action) {
    const message = action === 'delete' ? 'This action cannot be undone. Delete condition?' : 'Change this condition’s status?';
    if (window.Swal) {
        event.preventDefault();
        const form = event.currentTarget;
        window.Swal.fire({
            title: action === 'delete' ? 'Delete condition?' : 'Change status?',
            text: message,
            icon: action === 'delete' ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: action === 'delete' ? 'Delete' : 'Continue',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => { if (result.isConfirmed) form.submit(); });
        return;
    }
    if (!window.confirm(message)) event.preventDefault();
}
</script>

<template>
    <main v-if="state.view === 'index'" class="dashboard-section book-condition-page">
        <section class="book-condition-hero"><div class="book-condition-hero-content"><div class="book-condition-hero-text">
            <span class="book-condition-hero-badge"><i class="bi bi-bookmark-check"></i> Book Management</span>
            <h1>Book Conditions, clearly organised.</h1><p>Manage the condition types available for books throughout the SecondBook marketplace.</p>
        </div><div class="book-condition-hero-mark"><i class="bi bi-bookmark-check"></i></div></div></section>

        <section class="book-condition-stats">
            <div v-for="card in [{label:'Total Conditions',value:stats.total,icon:'bi-bookmark-check',tone:'blue'},{label:'Active',value:stats.active,icon:'bi-check-circle',tone:'green'},{label:'Inactive',value:stats.inactive,icon:'bi-pause-circle',tone:'orange'},{label:'Books Using Conditions',value:stats.books,icon:'bi-book',tone:'purple'}]" :key="card.label" class="book-condition-stat-card">
                <div class="book-condition-stat-icon" :class="`stat-${card.tone}`"><i class="bi" :class="card.icon"></i></div><div class="book-condition-stat-content"><div class="book-condition-stat-label">{{ card.label }}</div><div class="book-condition-stat-value">{{ card.value }}</div></div>
            </div>
        </section>

        <section class="dashboard-panel book-condition-panel">
            <div class="book-condition-panel-header"><div class="book-condition-heading-content"><h2 class="book-condition-panel-title">Condition directory</h2><p class="book-condition-panel-description">Browse and manage all book conditions registered on SecondBook.</p></div><div class="book-condition-header-action"><a :href="state.urls.create" class="book-condition-add-btn"><i class="bi bi-plus-lg"></i> Add Condition</a></div></div>
            <form method="GET" :action="state.urls.index" class="book-condition-filters" autocomplete="off">
                <div class="book-condition-filter-grid">
                    <div class="book-condition-filter-group"><label class="book-condition-filter-label" for="book-condition-search">Search conditions</label><div class="book-condition-search-field"><i class="bi bi-search"></i><input id="book-condition-search" v-model="query" type="text" name="search" class="book-condition-input" placeholder="Search by condition name..."></div></div>
                    <div class="book-condition-filter-group"><label class="book-condition-filter-label" for="book-condition-status">Status</label><select id="book-condition-status" v-model="statusFilter" name="status" class="book-condition-select"><option value="">All Status</option><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                    <div class="book-condition-filter-actions"><button type="submit" class="book-condition-filter-btn"><i class="bi bi-funnel"></i> Filter</button><a v-if="query || statusFilter" :href="state.urls.index" class="book-condition-clear-filter"><i class="bi bi-x-lg"></i> Reset</a></div>
                </div>
            </form>
            <div v-if="state.success" class="book-condition-message-wrap"><div class="alert alert-success mb-0">{{ state.success }}</div></div><div v-if="state.error" class="book-condition-message-wrap"><div class="alert alert-danger mb-0">{{ state.error }}</div></div>
            <div class="book-condition-table-wrap"><table class="book-condition-table"><thead><tr><th class="book-condition-col-id">#</th><th class="book-condition-col-condition">Condition</th><th class="book-condition-col-description">Description</th><th class="book-condition-col-books">Books</th><th class="book-condition-col-status">Status</th><th class="book-condition-col-date">Created</th><th class="book-condition-col-actions">Actions</th></tr></thead>
                <tbody><tr v-for="item in filtered" :key="item.id">
                    <td><span class="book-condition-id">#{{ item.id }}</span></td><td><div class="book-condition-item-cell"><div class="book-condition-item-icon"><i class="bi bi-bookmark-check"></i></div><div class="book-condition-item-info"><span class="book-condition-item-name">{{ item.name }}</span><span class="book-condition-item-meta">Condition #{{ item.id }}</span></div></div></td>
                    <td><span v-if="item.description" class="book-condition-description" :title="item.description">{{ item.description }}</span><span v-else class="book-condition-muted">—</span></td><td><span class="book-condition-books-count"><i class="bi bi-book"></i> {{ item.books_count || 0 }}</span></td>
                    <td><span class="book-condition-status-pill" :class="Number(item.status) === 1 ? 'book-condition-status-active' : 'book-condition-status-inactive'">{{ Number(item.status) === 1 ? 'Active' : 'Inactive' }}</span></td><td><span class="book-condition-item-meta">{{ item.created_at || '—' }}</span></td>
                    <td><div class="book-condition-actions"><form :action="replaceId(state.urls.statusPattern, item.id)" method="POST" class="book-condition-status-form" @submit="confirmAction($event, 'status')"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="PATCH"><button type="submit" class="book-condition-action-btn book-condition-status-btn" :class="Number(item.status) === 1 ? 'deactivate' : 'activate'" :title="Number(item.status) === 1 ? 'Deactivate' : 'Activate'"><i class="bi" :class="Number(item.status) === 1 ? 'bi-pause-circle' : 'bi-check-circle'"></i></button></form>
                        <a :href="replaceId(state.urls.editPattern, item.id)" class="book-condition-action-btn book-condition-edit-btn" title="Edit Condition"><i class="bi bi-pencil"></i></a>
                        <form :action="replaceId(state.urls.deletePattern, item.id)" method="POST" class="book-condition-delete-form" @submit="confirmAction($event, 'delete')"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="book-condition-action-btn book-condition-delete-btn" title="Delete Condition"><i class="bi bi-trash"></i></button></form></div></td>
                </tr>
                <tr v-if="!filtered.length"><td colspan="7"><div class="book-condition-empty-state"><i class="bi bi-inbox"></i><h3>No conditions found</h3><p>Try changing the filters or add a book condition.</p><a :href="state.urls.create" class="book-condition-add-btn"><i class="bi bi-plus-lg"></i> Add Condition</a></div></td></tr></tbody>
            </table></div>
        </section>
    </main>

    <main v-else class="dashboard-section book-condition-page">
        <section class="book-condition-hero"><div class="book-condition-hero-content"><div class="book-condition-hero-text"><span class="book-condition-hero-badge"><i class="bi" :class="state.view === 'edit' ? 'bi-pencil-square' : 'bi-bookmark-plus'"></i> Book Condition Management</span><h1>{{ state.view === 'edit' ? 'Edit Book Condition' : 'Add Book Condition' }}</h1><p>{{ state.view === 'edit' ? 'Update the condition details used throughout the SecondBook marketplace.' : 'Create a new condition type that can be assigned to books throughout the SecondBook marketplace.' }}</p></div><div class="book-condition-hero-mark"><i class="bi" :class="state.view === 'edit' ? 'bi-pencil-square' : 'bi-bookmark-plus'"></i></div></div></section>
        <section class="dashboard-panel book-condition-panel"><div class="book-condition-panel-header"><div class="book-condition-heading-content"><h2 class="book-condition-panel-title">{{ state.view === 'edit' ? 'Condition Details' : 'Condition Information' }}</h2><p class="book-condition-panel-description">Enter the basic information for this book condition.</p></div><div class="book-condition-header-action"><a :href="state.urls.index" class="book-condition-clear-filter"><i class="bi bi-arrow-left"></i> Back to Conditions</a></div></div>
            <form :action="state.view === 'edit' ? state.urls.update : state.urls.store" method="POST"><input type="hidden" name="_token" :value="csrf"><input v-if="state.view === 'edit'" type="hidden" name="_method" value="PUT">
                <div v-if="Object.keys(errors).length" class="book-condition-filters"><div class="alert alert-danger"><strong class="d-block mb-2">Please correct the following errors:</strong><ul class="mb-0 ps-3"><li v-for="(messages, key) in errors" :key="key">{{ messages[0] }}</li></ul></div></div>
                <div class="book-condition-filters"><div class="book-condition-filter-grid">
                    <div class="book-condition-filter-group"><label for="condition-name" class="book-condition-filter-label">Condition Name <span>*</span></label><input id="condition-name" name="name" :value="old.name" type="text" class="book-condition-input" :class="{'is-invalid': errors.name}" placeholder="Enter condition name" required><div v-if="errors.name" class="text-danger mt-2 small">{{ errors.name[0] }}</div></div>
                    <div class="book-condition-filter-group"><label for="condition-status" class="book-condition-filter-label">Status</label><select id="condition-status" name="status" :value="old.status" class="book-condition-select" :class="{'is-invalid': errors.status}"><option value="1">Active</option><option value="0">Inactive</option></select><div v-if="errors.status" class="text-danger mt-2 small">{{ errors.status[0] }}</div></div>
                    <div class="book-condition-filter-actions"><button type="submit" class="book-condition-filter-btn"><i class="bi bi-check2-circle"></i> {{ state.view === 'edit' ? 'Update Condition' : 'Save Condition' }}</button><a :href="state.urls.index" class="book-condition-clear-filter">Cancel</a></div>
                </div><div class="book-condition-filter-group" style="margin-top: 16px"><label for="condition-description" class="book-condition-filter-label">Description</label><textarea id="condition-description" name="description" :value="old.description" class="book-condition-input" :class="{'is-invalid': errors.description}" placeholder="Describe this book condition..." style="height: 140px; min-height: 140px; resize: vertical; padding: 12px 13px"></textarea><div v-if="errors.description" class="text-danger mt-2 small">{{ errors.description[0] }}</div></div></div>
            </form>
        </section>
    </main>
</template>
