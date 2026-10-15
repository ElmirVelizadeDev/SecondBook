<script setup>
import { computed, onMounted, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    detailsUrl: { type: String, required: true },
    booksUrl: { type: String, required: true },
});

const orders = ref([]);
const meta = ref({ current_page: 1, last_page: 1, total: 0 });
const loading = ref(true);
const error = ref('');
const pageNumbers = computed(() => Array.from(
    { length: Math.max(0, Math.min(meta.value.last_page, meta.value.current_page + 2) - Math.max(1, meta.value.current_page - 2) + 1) },
    (_, index) => Math.max(1, meta.value.current_page - 2) + index
));

function currency(value) {
    return Number(value || 0).toFixed(2);
}

function dateLabel(value) {
    return value
        ? new Date(value).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' })
        : '';
}

function statusClass(status) {
    return `status-${['pending', 'processing', 'shipped', 'delivered', 'cancelled'].includes(status) ? status : 'pending'}`;
}

function statusLabel(value) {
    return String(value || 'pending').replace(/\b\w/g, (letter) => letter.toUpperCase());
}

function detailsUrl(id) {
    return props.detailsUrl.replace('__ORDER__', encodeURIComponent(id));
}

function imageUrl(path) {
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (/^(storage|uploads)\//.test(path)) return `/${path}`;
    return `/storage/${String(path).replace(/^\/+/, '')}`;
}

async function loadOrders(page = 1) {
    loading.value = true;
    error.value = '';
    try {
        const response = await accountService.getOrders({ page });
        orders.value = response.data || [];
        meta.value = response.meta || meta.value;
    } catch (exception) {
        error.value = exception.response?.data?.message
            || 'Your orders could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}

onMounted(() => loadOrders());
</script>

<template>
    <main class="sb-orders-page">
        <section class="sb-orders-hero">
            <div class="container">
                <div class="sb-orders-breadcrumb"><a href="/frontend"><i class="bi bi-house-door"></i><span>Home</span></a><i class="bi bi-chevron-right"></i><span class="is-current">Orders</span></div>
                <div class="sb-orders-hero-grid">
                    <div class="sb-orders-hero-content"><span class="sb-orders-eyebrow"><span class="sb-orders-eyebrow-icon"><i class="bi bi-receipt"></i></span>Purchase History</span><h1>Your orders, <span>all in one place.</span></h1><p>Keep track of your purchases, delivery progress, payment details, and everything you've ordered.</p></div>
                    <div class="sb-orders-hero-card"><div class="sb-orders-hero-card-icon"><i class="bi bi-bag-heart"></i></div><div class="sb-orders-hero-card-content"><span>SecondBook</span><strong>My Purchases</strong></div><i class="bi bi-arrow-up-right sb-orders-hero-card-arrow"></i></div>
                </div>
            </div>
        </section>
        <section class="sb-orders-section">
            <div class="container">
                <div v-if="error" class="sb-orders-alert sb-alert-error" role="alert"><span class="sb-alert-icon"><i class="bi bi-exclamation-circle"></i></span><div class="sb-alert-content"><strong>Something went wrong</strong><span>{{ error }}</span></div><button type="button" @click="loadOrders()">Retry</button></div>
                <div v-if="!loading && orders.length" class="sb-orders-heading-row">
                    <div class="sb-orders-heading"><span class="sb-orders-section-kicker"><i class="bi bi-box-seam"></i>Your Purchases</span><h2>Your Orders</h2><p>Review your recent purchases and track every order.</p></div>
                    <a :href="booksUrl" class="sb-orders-continue-btn"><i class="bi bi-book"></i><span>Continue Shopping</span><i class="bi bi-arrow-right"></i></a>
                </div>
                <div v-if="loading" class="sb-orders-loading" role="status">Loading your orders…</div>
                <div v-else-if="orders.length" class="sb-orders-list">
                    <article v-for="order in orders" :key="order.id" class="sb-order-card">
                        <div class="sb-order-card-header">
                            <div class="sb-order-card-heading">
                                <div class="sb-order-card-icon"><i class="bi bi-receipt"></i></div>
                                <div><span class="sb-order-card-kicker">Order Number</span><h2>#{{ order.order_number }}</h2></div>
                            </div>
                            <div class="sb-order-date">
                                <span class="sb-order-date-icon"><i class="bi bi-calendar3"></i></span>
                                <div><span>Placed on</span><strong>{{ dateLabel(order.created_at) }}</strong></div>
                            </div>
                        </div>
                        <div class="sb-order-card-body">
                            <div class="sb-order-book">
                                <div class="sb-order-book-image">
                                    <img v-if="order.book?.cover" :src="imageUrl(order.book.cover)" :alt="order.book.title" loading="lazy" @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'">
                                    <div v-if="order.book?.cover" class="sb-order-book-placeholder" style="display: none"><i class="bi bi-book"></i></div>
                                    <div v-else class="sb-order-book-placeholder"><i class="bi bi-book"></i></div>
                                </div>
                                <div class="sb-order-book-content">
                                    <span class="sb-order-book-kicker">Book</span>
                                    <h3>{{ order.book?.title || 'Book unavailable' }}</h3>
                                    <div class="sb-order-book-meta">
                                        <span><i class="bi bi-box-seam"></i>{{ order.quantity }} {{ Number(order.quantity) === 1 ? 'copy' : 'copies' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="sb-order-info-card"><span>Total</span><strong>${{ currency(order.total_price) }}</strong></div>
                            <div class="sb-order-info-card sb-order-status-info">
                                <span>Order Status</span>
                                <span :class="['sb-order-status-badge', statusClass(order.order_status)]"><span class="sb-order-status-dot"></span>{{ statusLabel(order.order_status) }}</span>
                            </div>
                        </div>
                        <div class="sb-order-card-footer">
                            <div class="sb-order-payment-status">
                                <div class="sb-order-payment-icon"><i class="bi bi-credit-card-2-front"></i></div>
                                <div class="sb-order-payment-content"><span>Payment Status</span><strong>{{ statusLabel(order.payment_status || 'pending') }}</strong></div>
                            </div>
                            <div class="sb-order-actions">
                                <a :href="detailsUrl(order.id)" class="sb-order-details-btn"><span>View Details</span><i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                </div>
                <div v-else class="sb-orders-empty">
                    <div class="sb-orders-empty-icon"><i class="bi bi-bag-x"></i></div>
                    <span class="sb-orders-empty-eyebrow">Your shopping journey starts here</span>
                    <h2>No Orders Yet</h2>
                    <p>You haven't placed any orders yet. Explore our collection and find your next book.</p>
                    <a :href="booksUrl" class="sb-orders-shop-btn"><i class="bi bi-book"></i><span>Browse Books</span><i class="bi bi-arrow-right"></i></a>
                </div>
                <nav v-if="!loading && meta.last_page > 1" class="sb-orders-pagination" aria-label="Order pages">
                    <button type="button" :disabled="meta.current_page <= 1" @click="loadOrders(meta.current_page - 1)">Previous</button>
                    <button v-for="page in pageNumbers" :key="page" type="button" :aria-current="page === meta.current_page ? 'page' : undefined" @click="loadOrders(page)">{{ page }}</button>
                    <button type="button" :disabled="meta.current_page >= meta.last_page" @click="loadOrders(meta.current_page + 1)">Next</button>
                </nav>
            </div>
        </section>
    </main>
</template>
