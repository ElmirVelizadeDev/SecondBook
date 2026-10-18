<script setup>
import { computed, ref } from 'vue';
import { decodeSellerPageData } from '../utils/sellerPageData';
const props = defineProps({ pageData: { type: String, default: '' } });
const d = decodeSellerPageData(props.pageData);
const search = ref(d.filters?.search || '');
const status = ref(d.filters?.status || '');
const condition = ref(d.filters?.condition || '');
const selected = ref(null);
const showDelete = ref(false);
const deleting = ref(false);
const hasFilters = computed(() => Boolean(search.value || status.value || condition.value));
function confirmDelete(book) { selected.value = book; showDelete.value = true; }
function closeDelete() { showDelete.value = false; selected.value = null; }
async function deleteBook() {
    if (!selected.value || deleting.value) return;

    deleting.value = true;
    try {
        const response = await fetch(selected.value.deleteUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': d.csrfToken,
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new URLSearchParams({ _method: 'DELETE' }),
            credentials: 'same-origin',
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Unable to delete the book.');
        }

        closeDelete();
        if (window.Swal) {
            await window.Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: result.message, showConfirmButton: false, timer: 1800 });
        } else {
            window.alert(result.message);
        }
        window.location.assign(window.location.href);
    } catch (error) {
        if (window.Swal) {
            window.Swal.fire({ icon: 'error', title: 'Unable to delete book', text: error.message });
        } else {
            window.alert(error.message);
        }
    } finally {
        deleting.value = false;
    }
}
</script>

<template>
    <div class="seller-books-page">
        <div class="seller-page-heading"><div><h1>My Books</h1><p>Manage the books available in your store.</p></div><a :href="d.createUrl" class="seller-primary-button"><i class="bi bi-plus-lg"></i><span>Add New Book</span></a></div>
        <div v-if="d.success" class="seller-success-alert"><div class="seller-success-icon"><i class="bi bi-check-lg"></i></div><div class="seller-success-content"><strong>Success</strong><span>{{ d.success }}</span></div></div>
        <div class="row g-4 mb-4"><div v-for="stat in d.stats" :key="stat.label" class="col-xl-3 col-md-6"><div class="seller-book-stat-card"><div class="seller-book-stat-icon" :class="stat.type"><i class="bi" :class="stat.icon"></i></div><div><span>{{ stat.label }}</span><h3>{{ stat.value }}</h3></div></div></div></div>
        <div class="seller-books-panel">
            <div class="seller-books-filter"><form :action="d.indexUrl" method="GET"><div class="seller-search-group"><i class="bi bi-search"></i><input v-model="search" type="text" name="search" placeholder="Search books..."></div><select v-model="status" name="status" class="seller-filter-select"><option value="">All Statuses</option><option value="approved">Approved</option><option value="pending">Pending</option><option value="rejected">Rejected</option></select><select v-model="condition" name="condition" class="seller-filter-select"><option value="">All Conditions</option><option value="new">New</option><option value="like_new">Like New</option><option value="good">Good</option><option value="fair">Fair</option></select><button type="submit" class="seller-filter-button"><i class="bi bi-search"></i><span>Search</span></button><a v-if="hasFilters" :href="d.indexUrl" class="seller-reset-button"><i class="bi bi-x-lg"></i><span>Reset</span></a></form></div>
            <div v-if="d.books.length" class="table-responsive"><table class="table seller-books-table align-middle mb-0"><thead><tr><th>Book</th><th>Category</th><th>Price</th><th>Stock</th><th>Condition</th><th>Status</th><th>Actions</th></tr></thead><tbody><tr v-for="book in d.books" :key="book.id"><td><div class="seller-book-info"><div class="seller-book-cover"><img v-if="book.coverUrl" :src="book.coverUrl" :alt="book.title" loading="lazy" @error="book.coverError = true" :hidden="book.coverError"><i v-if="!book.coverUrl || book.coverError" class="bi bi-book"></i></div><div><strong>{{ book.title }}</strong><span v-if="book.isbn">ISBN: {{ book.isbn }}</span></div></div></td><td><span class="seller-category-text">{{ book.category || '—' }}</span></td><td><strong class="seller-price-text">${{ book.price }}</strong></td><td><span class="seller-stock" :class="{ 'is-low': book.stock <= 3 }">{{ book.stock }}</span></td><td><span class="seller-condition">{{ book.condition }}</span></td><td><span class="seller-book-status" :class="book.status"><i></i>{{ book.statusLabel }}</span></td><td><div class="seller-book-actions"><a :href="book.showUrl" class="is-view" title="View"><i class="bi bi-eye"></i></a><a :href="book.editUrl" class="is-edit" title="Edit"><i class="bi bi-pencil"></i></a><button type="button" class="seller-delete-button" title="Delete" @click="confirmDelete(book)"><i class="bi bi-trash"></i></button></div></td></tr></tbody></table></div>
            <div v-if="d.books.length && d.pagination.links.length > 3" class="seller-books-pagination"><nav aria-label="Pagination"><a v-for="(link, index) in d.pagination.links" :key="index" :href="link.url || undefined" :class="{ active: link.active, disabled: !link.url }" v-html="link.label"></a></nav></div>
            <div v-if="!d.books.length" class="seller-books-empty"><div class="seller-books-empty-icon"><i class="bi bi-book"></i></div><h5>No Books Found</h5><p>You don't have any books matching your search.</p><a :href="d.createUrl" class="seller-primary-button"><i class="bi bi-plus-lg"></i><span>Add Your First Book</span></a></div>
        </div>
        <div class="seller-modal-overlay" :class="{ show: showDelete }" role="presentation" @click.self="closeDelete"><div class="seller-delete-modal" role="dialog" aria-modal="true" aria-labelledby="deleteBookTitle"><button type="button" class="seller-modal-close" aria-label="Close" @click="closeDelete"><i class="bi bi-x-lg"></i></button><div class="seller-delete-icon"><i class="bi bi-trash3"></i></div><h4 id="deleteBookTitle">Delete Book?</h4><p>Are you sure you want to delete <strong>{{ selected?.title }}</strong>?<br>This action cannot be undone.</p><div class="seller-delete-actions"><button type="button" class="seller-modal-cancel" @click="closeDelete">Cancel</button><button type="button" class="seller-modal-delete" :disabled="deleting" @click="deleteBook"><i class="bi bi-trash3"></i><span>{{ deleting ? 'Deleting...' : 'Delete Book' }}</span></button></div></div></div>
    </div>
</template>
