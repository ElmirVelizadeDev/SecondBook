<script setup>
import { onMounted, ref } from 'vue';
import contentService from '../services/contentService';

const shipping = ref(null);
const loading = ref(true);
const error = ref('');

function currency(value) {
    return Number(value || 0).toFixed(2);
}

onMounted(async () => {
    try {
        shipping.value = await contentService.getShippingInformation();
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Shipping information could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="sb-shipping-page">
        <div class="sb-shipping-progress" aria-hidden="true"></div>
        <section class="sb-shipping-hero">
            <div class="sb-shipping-hero-bg" aria-hidden="true"><span class="sb-shipping-decoration sb-shipping-decoration-one"></span><span class="sb-shipping-decoration sb-shipping-decoration-two"></span><span class="sb-shipping-ring sb-shipping-ring-one"></span><span class="sb-shipping-ring sb-shipping-ring-two"></span></div>
            <div class="container"><div class="sb-shipping-hero-content"><div class="sb-shipping-eyebrow"><span class="sb-shipping-eyebrow-line"></span><i class="bi bi-truck" aria-hidden="true"></i><span>SECONDBOOK SHIPPING</span><span class="sb-shipping-eyebrow-line"></span></div><h1>Shipping made <em>simple.</em></h1><p>Everything you need to know about delivery, shipping times, order tracking and receiving your books from SecondBook.</p><div class="sb-shipping-meta"><span><i class="bi bi-box-seam"></i>Carefully prepared orders</span><span class="sb-shipping-meta-divider"></span><span><i class="bi bi-house-check"></i>Delivery information</span></div></div></div>
            <div class="sb-shipping-hero-bottom-line" aria-hidden="true"></div>
        </section>
        <div class="container sb-shipping-container">
            <section class="sb-shipping-intro"><div class="sb-shipping-intro-icon"><i class="bi bi-box-seam"></i></div><div class="sb-shipping-intro-content"><span class="sb-shipping-intro-label">YOUR ORDER JOURNEY</span><h2>From checkout to your doorstep.</h2><p>We keep the delivery process straightforward, so you know what to expect after placing your order. Find helpful information about preparation, delivery and order updates below.</p></div><span class="sb-shipping-intro-decoration"><i class="bi bi-book-half"></i></span></section>
            <section id="shipping-overview" class="sb-shipping-section">
                <div class="sb-shipping-section-heading"><span class="sb-shipping-section-label"><i class="bi bi-info-circle"></i>Shipping Overview</span><h2>What happens after <em>you place an order?</em></h2><p>We keep the delivery process straightforward, so you always know what to expect.</p></div>
                <div v-if="loading" class="sb-shipping-options" role="status">Loading shipping options…</div>
                <div v-else-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
                <div v-else class="sb-shipping-options">
                    <p v-if="shipping?.estimated_delivery_message" class="sb-shipping-estimate"><i class="bi bi-clock"></i>{{ shipping.estimated_delivery_message }}</p>
                    <div v-if="shipping?.enabled && shipping.options?.length" class="sb-shipping-methods">
                        <article v-for="option in shipping.options" :key="option.id" class="sb-shipping-method-card">
                            <span class="sb-shipping-method-icon"><i class="bi bi-truck"></i></span>
                            <div><h3>{{ option.name }}</h3><p v-if="option.description">{{ option.description }}</p><span v-if="option.delivery_time">{{ option.delivery_time }}</span></div>
                            <strong>{{ Number(option.price) > 0 ? `$${currency(option.price)}` : 'FREE' }}</strong>
                        </article>
                    </div>
                    <div v-else class="sb-shipping-empty">Shipping methods will be shown during checkout when available.</div>
                    <p v-if="shipping?.default_country" class="sb-shipping-country"><i class="bi bi-geo-alt"></i>Default delivery country: {{ shipping.default_country }}</p>
                    <p v-if="shipping?.free_shipping_threshold > 0" class="sb-shipping-threshold">Free shipping applies to eligible orders of ${{ currency(shipping.free_shipping_threshold) }} or more.</p>
                </div>
            </section>
            <section class="sb-shipping-steps"><div class="sb-shipping-section-heading"><span class="sb-shipping-section-label"><i class="bi bi-signpost-split"></i>THE PROCESS</span><h2>Your order's <em>journey.</em></h2></div><div class="sb-shipping-steps-grid"><article><span>01</span><i class="bi bi-bag-check"></i><h3>Order placed</h3><p>Complete checkout and receive your order confirmation.</p></article><article><span>02</span><i class="bi bi-box-seam"></i><h3>Prepared</h3><p>Your seller prepares the books for delivery.</p></article><article><span>03</span><i class="bi bi-truck"></i><h3>On the way</h3><p>Use your order tracking page to follow status updates.</p></article><article><span>04</span><i class="bi bi-house-check"></i><h3>Delivered</h3><p>Receive your order at the delivery address provided.</p></article></div></section>
        </div>
    </div>
</template>
