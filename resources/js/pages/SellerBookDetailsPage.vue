<script setup>
import { computed } from 'vue';
import { decodeSellerPageData } from '../utils/sellerPageData';
const props = defineProps({ pageData: { type: String, default: '' } });
const data = decodeSellerPageData(props.pageData);
const book = computed(() => data.book || {});
</script>

<template>
    <div class="seller-books-page">
        <div class="seller-page-heading"><div><h1>Book Details</h1><p>View the information and current status of this book.</p></div><a :href="data.indexUrl" class="seller-outline-button"><i class="bi bi-arrow-left"></i><span>Back to My Books</span></a></div>
        <div class="row g-4">
            <div class="col-xl-4"><div class="seller-books-form-card seller-book-details-card"><div class="seller-book-details-cover"><img v-if="book.coverUrl" :src="book.coverUrl" :alt="book.title"><div v-else class="seller-book-details-no-cover"><i class="bi bi-book"></i><span>No Cover</span></div></div><div class="seller-book-details-status"><span class="seller-book-status" :class="book.status"><i></i>{{ book.statusLabel }}</span><span class="seller-condition">{{ book.conditionLabel }}</span></div></div></div>
            <div class="col-xl-8"><div class="seller-books-form-card"><div class="seller-form-header"><div><h5>Book Information</h5><p>Detailed information about your book.</p></div><div class="seller-form-header-icon"><i class="bi bi-book"></i></div></div><div class="seller-book-details-body">
                <div class="seller-book-details-title"><span>Title</span><h2>{{ book.title }}</h2></div>
                <div class="seller-book-highlights"><div class="seller-book-highlight is-price"><span>Price</span><strong>${{ book.price }}</strong></div><div class="seller-book-highlight"><span>In Stock</span><strong>{{ book.stock }}</strong></div><div class="seller-book-highlight"><span>Pages</span><strong>{{ book.pages || '—' }}</strong></div></div>
                <div class="seller-book-details-grid"><div v-for="item in book.details" :key="item.label" class="seller-book-detail-item"><div class="seller-book-detail-icon"><i class="bi" :class="item.icon"></i></div><div><span>{{ item.label }}</span><strong>{{ item.value || '—' }}</strong></div></div></div>
                <div class="seller-book-details-description"><span>Description</span><p v-if="book.description">{{ book.description }}</p><p v-else class="seller-book-details-muted">No description provided.</p></div>
            </div></div></div>
        </div>
        <div class="seller-books-form-actions"><a :href="data.indexUrl" class="seller-cancel-button">Back</a><a :href="data.editUrl" class="seller-save-button"><i class="bi bi-pencil"></i><span>Edit Book</span></a></div>
    </div>
</template>
