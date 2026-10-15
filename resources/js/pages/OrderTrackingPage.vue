<script setup>
import { computed, onMounted, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    orderId: { type: [String, Number], required: true },
    ordersUrl: { type: String, required: true },
});

const order = ref(null);
const loading = ref(true);
const error = ref('');
const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
const currentIndex = computed(() => statusOrder.indexOf(order.value?.order_status));
const statusLabels = ['Order Placed', 'Processing', 'Shipped', 'Delivered'];
const statusDescriptions = [
    'Your order has been placed successfully.',
    'Your order is being prepared.',
    'Your order has been shipped.',
    'Your order has been delivered successfully.',
];

onMounted(async () => {
    try {
        const response = await accountService.getOrderTracking(props.orderId);
        order.value = response.data;
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Tracking information could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <main class="sb-order-tracking-page">
        <section class="tracking-hero"><div class="container">
            <div class="tracking-breadcrumb"><a href="/frontend"><i class="bi bi-house-door"></i><span>Home</span></a><i class="bi bi-chevron-right"></i><a :href="ordersUrl">My Orders</a><i class="bi bi-chevron-right"></i><span class="is-current">Track Order</span></div>
            <div class="tracking-hero-grid">
                <div class="tracking-hero-content"><span class="tracking-eyebrow"><span class="tracking-eyebrow-icon"><i class="bi bi-truck"></i></span>Order Tracking</span><h1>Track Your <span>Order</span></h1><p>Follow your order's journey from placement to delivery and stay updated every step of the way.</p><div class="tracking-hero-features"><span><i class="bi bi-shield-check"></i>Order updates</span><span><i class="bi bi-box-seam"></i>Delivery progress</span></div></div>
                <div v-if="order" class="tracking-hero-order"><div class="tracking-hero-order-icon"><i class="bi bi-receipt-cutoff"></i></div><span class="tracking-hero-order-label">ORDER REFERENCE</span><strong>#{{ order.order_number }}</strong></div>
            </div>
        </div></section>
        <section class="tracking-section"><div class="container">
            <div v-if="loading" class="tracking-card" role="status">Loading tracking information…</div>
            <div v-else-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
            <template v-else-if="order">
                <article class="tracking-order-card"><span class="tracking-section-kicker">YOUR ORDER</span><h2>{{ order.book?.title || 'Book unavailable' }}</h2><p>Order #{{ order.order_number }}</p><div class="tracking-timeline">
                    <div v-for="(label, index) in statusLabels" :key="label" :class="['tracking-step', { 'is-complete': currentIndex >= index, 'is-current': currentIndex === index }]">
                        <span class="tracking-step-icon"><i :class="index === 0 ? 'bi bi-bag-check' : index === 1 ? 'bi bi-box-seam' : index === 2 ? 'bi bi-truck' : 'bi bi-house-check'"></i></span>
                        <div><strong>{{ label }}</strong><p>{{ statusDescriptions[index] }}</p></div>
                    </div>
                </div>
                <div v-if="order.order_status === 'cancelled'" class="alert alert-warning">This order has been cancelled.</div>
                <a :href="ordersUrl" class="tracking-back-btn"><i class="bi bi-arrow-left"></i>Back to Orders</a></article>
            </template>
        </div></section>
    </main>
</template>
