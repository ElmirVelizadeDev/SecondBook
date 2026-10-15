<script setup>
import { onMounted, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    orderId: { type: [String, Number], required: true },
    ordersUrl: { type: String, required: true },
});

const payment = ref(null);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
    try {
        const response = await accountService.getPaymentStatus(props.orderId);
        payment.value = response.data;
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Payment information could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <main class="sb-payment-page">
        <section class="sb-payment-hero"><div class="container">
            <div class="sb-payment-breadcrumb"><a href="/frontend/cart"><i class="bi bi-cart3"></i><span>Shopping Cart</span></a><i class="bi bi-chevron-right"></i><a :href="ordersUrl">Orders</a><i class="bi bi-chevron-right"></i><span class="is-current">Payment</span></div>
            <div class="sb-payment-hero-grid">
                <div class="sb-payment-hero-content"><span class="sb-payment-eyebrow"><span class="sb-payment-eyebrow-icon"><i class="bi bi-shield-check"></i></span>Secure Checkout</span><h1>Complete Your <span>Payment</span></h1><p>Review your order's payment status. Payment remains pending until a supported payment provider is configured.</p><div class="sb-payment-hero-features"><span><i class="bi bi-shield-lock"></i>Protected checkout</span><span><i class="bi bi-book-half"></i>Your next chapter awaits</span></div></div>
                <div v-if="payment" class="sb-payment-security"><div class="sb-payment-security-decoration"></div><div class="sb-payment-security-icon"><i class="bi bi-lock-fill"></i></div><div class="sb-payment-security-content"><span>CHECKOUT PROTECTION</span><strong>Secure Checkout</strong><p>Payment processing is unavailable because no payment provider is configured.</p></div><div class="sb-payment-security-footer"><i class="bi bi-check-circle-fill"></i><span>Order #{{ payment.order_number }}</span></div></div>
            </div>
        </div></section>
        <section class="sb-payment-section"><div class="container">
            <div v-if="loading" class="sb-payment-card" role="status">Loading payment status…</div>
            <div v-else-if="error" class="sb-payment-card alert alert-warning" role="alert">{{ error }}</div>
            <div v-else-if="payment" class="sb-payment-card">
                <h2>Payment status: {{ payment.payment_status }}</h2>
                <dl><div><dt>Order</dt><dd>#{{ payment.order_number }}</dd></div><div><dt>Amount</dt><dd>${{ Number(payment.amount || 0).toFixed(2) }}</dd></div><div><dt>Method</dt><dd>{{ payment.payment_method }}</dd></div></dl>
                <p v-if="payment.payment_status !== 'paid'" class="alert alert-warning">No payment provider is configured. No payment was taken and the order remains unpaid.</p>
                <a :href="ordersUrl" class="sb-payment-back-btn">Back to Orders</a>
            </div>
        </div></section>
    </main>
</template>
