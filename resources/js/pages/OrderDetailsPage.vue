<script setup>
import { computed, onMounted, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    orderId: { type: [String, Number], required: true },
    ordersUrl: { type: String, required: true },
    trackingUrl: { type: String, required: true },
    reviewUrl: { type: String, required: true },
});

const order = ref(null);
const loading = ref(true);
const error = ref('');
const notice = ref('');
const cancelling = ref(false);
const canCancel = computed(() => {
    const cancelDays = Number(order.value?.cancel_order_period || 0);
    const status = order.value?.order_status;
    const created = new Date(order.value?.created_at || 0);
    return cancelDays > 0
        && ['pending', 'processing'].includes(status)
        && Date.now() <= created.getTime() + cancelDays * 86400000;
});

function currency(value) {
    return Number(value || 0).toFixed(2);
}

async function loadOrder() {
    loading.value = true;
    error.value = '';
    try {
        const response = await accountService.getOrder(props.orderId);
        order.value = response.data;
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Order details could not be loaded.';
    } finally {
        loading.value = false;
    }
}

async function cancelOrder() {
    if (cancelling.value || !canCancel.value) return;
    cancelling.value = true;
    error.value = '';
    try {
        const response = await accountService.cancelOrder(props.orderId);
        notice.value = response.message || 'Order cancelled successfully.';
        await loadOrder();
    } catch (exception) {
        error.value = exception.response?.data?.message || 'This order could not be cancelled.';
    } finally {
        cancelling.value = false;
    }
}

onMounted(loadOrder);
</script>

<template>
    <main class="sb-order-details-page">
        <section class="order-details-hero"><div class="container">
            <div class="order-details-breadcrumb"><a href="/frontend"><i class="bi bi-house-door"></i><span>Home</span></a><i class="bi bi-chevron-right"></i><a :href="ordersUrl">My Orders</a><i class="bi bi-chevron-right"></i><span class="is-current">Order Details</span></div>
            <div class="order-details-hero-grid"><div class="order-details-hero-content"><span class="order-details-eyebrow"><span class="order-details-eyebrow-icon"><i class="bi bi-receipt"></i></span>Order Information</span><h1>Your order,<span>{{ order ? `#${order.order_number}` : '' }}</span></h1><p>Review your purchase, delivery information, payment status, and order summary in one place.</p></div><a :href="ordersUrl" class="order-back-btn"><i class="bi bi-arrow-left"></i><span>Back to Orders</span></a></div>
        </div></section>
        <section class="order-details-section"><div class="container">
            <div v-if="notice" class="order-alert order-alert-success" role="status"><i class="bi bi-check-lg"></i><span>{{ notice }}</span></div>
            <div v-if="error" class="order-alert order-alert-error" role="alert"><i class="bi bi-exclamation-circle"></i><span>{{ error }}</span><button type="button" @click="loadOrder">Retry</button></div>
            <div v-if="loading" class="order-details-card order-note-card" role="status">Loading order details…</div>
            <template v-else-if="order">
                <div class="order-details-grid">
                    <article class="order-details-card order-note-card">
                        <span class="order-details-section-kicker">PURCHASE SUMMARY</span>
                        <h2>{{ order.book?.title || 'Book unavailable' }}</h2>
                        <p>Order #{{ order.order_number }}</p>
                        <dl class="order-details-list">
                            <div><dt>Order status</dt><dd>{{ order.order_status }}</dd></div>
                            <div><dt>Payment status</dt><dd>{{ order.payment_status }}</dd></div>
                            <div><dt>Payment method</dt><dd>{{ order.payment_method || 'Not selected' }}</dd></div>
                            <div><dt>Quantity</dt><dd>{{ order.quantity }}</dd></div>
                            <div><dt>Book price</dt><dd>${{ currency(order.book_price) }}</dd></div>
                            <div><dt>Shipping</dt><dd>${{ currency(order.shipping_fee) }}</dd></div>
                            <div><dt>Total</dt><dd>${{ currency(order.total_price) }}</dd></div>
                        </dl>
                    </article>
                    <article class="order-details-card order-note-card">
                        <span class="order-details-section-kicker">DELIVERY DETAILS</span>
                        <h2>{{ order.full_name }}</h2>
                        <p>{{ order.address }}</p>
                        <dl class="order-details-list">
                            <div><dt>Phone</dt><dd>{{ order.phone }}</dd></div>
                            <div><dt>City</dt><dd>{{ order.city }}</dd></div>
                            <div><dt>Country</dt><dd>{{ order.country }}</dd></div>
                            <div><dt>Postal code</dt><dd>{{ order.postal_code || '—' }}</dd></div>
                            <div><dt>Shipping method</dt><dd>{{ order.shipping?.name || '—' }}</dd></div>
                            <div><dt>Estimated delivery</dt><dd>{{ order.delivery_estimate || order.shipping?.delivery_time || '—' }}</dd></div>
                        </dl>
                    </article>
                </div>
                <div class="order-details-actions">
                    <a :href="trackingUrl.replace('__ORDER__', encodeURIComponent(order.id))" class="order-action-btn order-action"><i class="bi bi-truck"></i>Track order</a>
                    <a v-if="order.order_status === 'delivered' && !order.reviewed" :href="reviewUrl.replace('__ORDER__', encodeURIComponent(order.id))" class="order-action-btn order-action"><i class="bi bi-star"></i>Write a review</a>
                    <button v-if="canCancel" type="button" class="order-cancel-btn" :disabled="cancelling" @click="cancelOrder">{{ cancelling ? 'Cancelling…' : 'Cancel order' }}</button>
                </div>
            </template>
        </div></section>
    </main>
</template>
