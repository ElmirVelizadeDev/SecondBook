<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import cartService from '../services/cartService';

const props = defineProps({
    cartUrl: { type: String, required: true },
    ordersUrl: { type: String, required: true },
    customerName: { type: String, default: '' },
    customerPhone: { type: String, default: '' },
});

const checkout = ref(null);
const loading = ref(true);
const submitting = ref(false);
const error = ref('');
const fieldErrors = ref({});
const form = reactive({
    full_name: props.customerName,
    phone: props.customerPhone,
    country: '',
    city: '',
    postal_code: '',
    address: '',
    note: '',
    shipping_id: '',
    payment_method: '',
});

const items = computed(() => checkout.value?.cart || []);
const shippingMethods = computed(() => checkout.value?.shipping_methods || []);
const paymentMethods = computed(() => checkout.value?.payment_methods || []);

function currency(value) {
    return Number(value || 0).toFixed(2);
}

function coverUrl(item) {
    const path = item.cover_url || item.cover;
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (/^(storage|uploads)\//.test(path)) return `/${path}`;
    return `/storage/${String(path).replace(/^\/+/, '')}`;
}

function paymentLabel(method) {
    return method === 'cash_on_delivery' ? 'Cash on Delivery' : method.replaceAll('_', ' ');
}

async function loadCheckout(shippingId) {
    loading.value = true;
    error.value = '';
    try {
        checkout.value = await cartService.getCheckout(shippingId || undefined);
        if (!form.country) form.country = checkout.value.default_country || '';
        form.shipping_id = checkout.value.selected_shipping_id || '';
        if (!paymentMethods.value.includes(form.payment_method)) {
            form.payment_method = checkout.value.default_payment_method || paymentMethods.value[0] || '';
        }
    } catch (exception) {
        checkout.value = null;
        error.value = exception.response?.data?.message
            || 'Checkout details could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}

async function changeShipping() {
    await loadCheckout(form.shipping_id);
}

async function submitOrder() {
    if (submitting.value) return;
    submitting.value = true;
    error.value = '';
    fieldErrors.value = {};
    try {
        const response = await cartService.placeOrder({ ...form });
        if (response.payment_url) {
            window.location.assign(response.payment_url);
            return;
        }
        window.location.assign(props.ordersUrl);
    } catch (exception) {
        const data = exception.response?.data || {};
        error.value = data.message || 'Your order could not be placed. Please review your details and try again.';
        fieldErrors.value = data.errors || {};
        if (exception.response?.status === 401) {
            window.location.assign('/frontend/auth/login');
        }
    } finally {
        submitting.value = false;
    }
}

onMounted(() => loadCheckout());
</script>

<template>
    <main class="sb-checkout-page">
        <section class="sb-checkout-hero">
            <div class="container">
                <nav class="sb-checkout-breadcrumb" aria-label="Breadcrumb">
                    <a :href="cartUrl"><i class="bi bi-arrow-left"></i><span>Shopping Cart</span></a>
                    <i class="bi bi-chevron-right"></i><span>Checkout</span>
                </nav>
                <div class="sb-checkout-hero-grid">
                    <div class="sb-checkout-hero-content">
                        <span class="sb-checkout-eyebrow"><span class="sb-checkout-eyebrow-icon"><i class="bi bi-shield-check"></i></span>Secure Checkout</span>
                        <h1>Almost there.<span>Complete your order.</span></h1>
                        <p>Enter your delivery details, choose your shipping and payment method, and we'll take care of the rest.</p>
                    </div>
                    <div class="sb-checkout-progress">
                        <div class="sb-progress-step is-complete"><span class="sb-progress-icon"><i class="bi bi-check2"></i></span><span>Cart</span></div>
                        <span class="sb-progress-line is-active"></span>
                        <div class="sb-progress-step is-active"><span class="sb-progress-icon"><i class="bi bi-bag-check"></i></span><span>Checkout</span></div>
                        <span class="sb-progress-line"></span>
                        <div class="sb-progress-step"><span class="sb-progress-icon"><i class="bi bi-check-lg"></i></span><span>Complete</span></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="sb-checkout-section">
            <div class="container">
                <div v-if="error" class="sb-checkout-alert sb-alert-error" role="alert">
                    <span class="sb-alert-icon"><i class="bi bi-exclamation-circle-fill"></i></span>
                    <span>{{ error }}</span>
                    <a v-if="!items.length" :href="cartUrl">Return to your cart</a>
                </div>
                <div v-if="loading" class="sb-checkout-card p-4" role="status">Loading checkout…</div>
                <div v-else-if="items.length" class="sb-checkout-form">
                    <form @submit.prevent="submitOrder">
                        <div class="row g-4 g-xl-5">
                            <div class="col-lg-7">
                                <section class="sb-checkout-card">
                                    <div class="sb-card-header">
                                        <div class="sb-card-heading">
                                            <div class="sb-card-icon"><i class="bi bi-person-vcard"></i></div>
                                            <div><span class="sb-card-kicker">Delivery Details</span><h2>Customer Information</h2><p>Tell us where you'd like your order delivered.</p></div>
                                        </div>
                                        <span class="sb-required-badge"><i class="bi bi-asterisk"></i>Required</span>
                                    </div>
                                    <div class="sb-card-body">
                                        <div class="row g-3 g-md-4">
                                            <div class="col-12">
                                                <label for="full_name" class="sb-form-label">Full Name <span>*</span></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-person"></i></span><input id="full_name" v-model="form.full_name" name="full_name" class="sb-form-input" placeholder="Enter your full name" autocomplete="name" required></div>
                                                <small v-if="fieldErrors.full_name" class="sb-field-error">{{ fieldErrors.full_name[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phone" class="sb-form-label">Phone Number <span>*</span></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-telephone"></i></span><input id="phone" v-model="form.phone" name="phone" class="sb-form-input" placeholder="+994 XX XXX XX XX" autocomplete="tel" required></div>
                                                <small v-if="fieldErrors.phone" class="sb-field-error">{{ fieldErrors.phone[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="country" class="sb-form-label">Country <span>*</span></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-globe2"></i></span><input id="country" v-model="form.country" name="country" class="sb-form-input" placeholder="Enter your country" autocomplete="country-name" required></div>
                                                <small v-if="fieldErrors.country" class="sb-field-error">{{ fieldErrors.country[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="city" class="sb-form-label">City <span>*</span></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-buildings"></i></span><input id="city" v-model="form.city" name="city" class="sb-form-input" placeholder="Enter your city" autocomplete="address-level2" required></div>
                                                <small v-if="fieldErrors.city" class="sb-field-error">{{ fieldErrors.city[0] }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="postal_code" class="sb-form-label">Postal Code <small>Optional</small></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-mailbox"></i></span><input id="postal_code" v-model="form.postal_code" name="postal_code" class="sb-form-input" placeholder="Postal code" autocomplete="postal-code"></div>
                                                <small v-if="fieldErrors.postal_code" class="sb-field-error">{{ fieldErrors.postal_code[0] }}</small>
                                            </div>
                                            <div class="col-12">
                                                <label for="address" class="sb-form-label">Delivery Address <span>*</span></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-geo-alt"></i></span><textarea id="address" v-model="form.address" name="address" class="sb-form-input sb-form-textarea" rows="3" placeholder="Street, building and apartment" autocomplete="street-address" required></textarea></div>
                                                <small v-if="fieldErrors.address" class="sb-field-error">{{ fieldErrors.address[0] }}</small>
                                            </div>
                                            <div class="col-12">
                                                <label for="note" class="sb-form-label">Order Note <small>Optional</small></label>
                                                <div class="sb-input-wrap"><span class="sb-input-icon"><i class="bi bi-chat-left-text"></i></span><textarea id="note" v-model="form.note" name="note" class="sb-form-input sb-form-textarea sb-note-textarea" rows="3" placeholder="Any special instructions for your order?"></textarea></div>
                                                <small v-if="fieldErrors.note" class="sb-field-error">{{ fieldErrors.note[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section v-if="checkout.shipping_enabled && shippingMethods.length" class="sb-checkout-card sb-shipping-card">
                                    <div class="sb-card-header"><div class="sb-card-heading"><div class="sb-card-icon"><i class="bi bi-truck"></i></div><div><span class="sb-card-kicker">Delivery</span><h2>Shipping Method</h2><p>Choose how you'd like your order delivered.</p></div></div><span class="sb-required-badge"><i class="bi bi-asterisk"></i>Required</span></div>
                                    <div class="sb-card-body"><div class="sb-shipping-list">
                                        <label v-for="method in shippingMethods" :key="method.id" :class="['sb-choice-card shipping-option', { 'is-selected': Number(form.shipping_id) === Number(method.id) }]">
                                            <input v-model="form.shipping_id" type="radio" name="shipping_id" :value="method.id" required @change="changeShipping">
                                            <span class="sb-choice-radio"><span></span></span>
                                            <span class="sb-choice-icon"><i :class="String(method.name).toLowerCase().includes('express') ? 'bi bi-lightning-charge' : 'bi bi-truck'"></i></span>
                                            <span class="sb-choice-content"><strong>{{ method.name }}</strong><small v-if="method.description">{{ method.description }}</small><span v-if="method.delivery_time" class="sb-delivery-time"><i class="bi bi-clock"></i>{{ method.delivery_time }}</span></span>
                                            <span class="sb-choice-price"><strong :class="{ 'is-free': Number(method.price) <= 0 || (checkout.free_shipping_threshold > 0 && checkout.subtotal >= checkout.free_shipping_threshold) }">{{ Number(method.price) <= 0 || (checkout.free_shipping_threshold > 0 && checkout.subtotal >= checkout.free_shipping_threshold) ? 'FREE' : `$${currency(method.price)}` }}</strong></span>
                                        </label>
                                    </div><small v-if="fieldErrors.shipping_id" class="sb-field-error">{{ fieldErrors.shipping_id[0] }}</small></div>
                                </section>

                                <section v-if="checkout.payments_enabled && paymentMethods.length" class="sb-checkout-card sb-payment-card">
                                    <div class="sb-card-header"><div class="sb-card-heading"><div class="sb-card-icon"><i class="bi bi-credit-card-2-front"></i></div><div><span class="sb-card-kicker">Payment</span><h2>Payment Method</h2><p>Choose your preferred way to pay.</p></div></div><span class="sb-secure-badge"><i class="bi bi-shield-lock"></i>Secure</span></div>
                                    <div class="sb-card-body"><div class="sb-shipping-list">
                                        <label v-for="method in paymentMethods" :key="method" :class="['sb-choice-card payment-option', { 'is-selected': form.payment_method === method }]">
                                            <input v-model="form.payment_method" type="radio" name="payment_method" :value="method" required>
                                            <span class="sb-choice-radio"><span></span></span><span class="sb-choice-icon"><i class="bi bi-cash-coin"></i></span>
                                            <span class="sb-choice-content"><strong>{{ paymentLabel(method) }}</strong><small>Payment is collected when your order is delivered.</small></span>
                                        </label>
                                    </div><small v-if="fieldErrors.payment_method" class="sb-field-error">{{ fieldErrors.payment_method[0] }}</small></div>
                                </section>
                            </div>

                            <aside class="col-lg-5">
                                <div class="sb-checkout-sidebar">
                                    <section class="sb-checkout-card">
                                        <div class="sb-card-header"><div class="sb-card-heading"><div class="sb-card-icon"><i class="bi bi-receipt"></i></div><div><span class="sb-card-kicker">Your Selection</span><h2>Order Summary</h2></div></div></div>
                                        <div class="sb-card-body">
                                            <div v-for="item in items" :key="item.id" class="sb-order-item">
                                                <div class="sb-order-item-cover"><img v-if="coverUrl(item)" :src="coverUrl(item)" :alt="item.title" loading="lazy"><i v-else class="bi bi-book"></i></div>
                                                <div class="sb-order-item-info"><strong>{{ item.title }}</strong><span>Qty {{ item.quantity }}</span></div>
                                                <strong>${{ currency(Number(item.price) * Number(item.quantity)) }}</strong>
                                            </div>
                                            <div class="sb-summary-row"><span>Subtotal</span><strong>${{ currency(checkout.subtotal) }}</strong></div>
                                            <div class="sb-summary-row"><span>Shipping</span><strong>{{ Number(checkout.shipping_fee) ? `$${currency(checkout.shipping_fee)}` : 'FREE' }}</strong></div>
                                            <div class="sb-summary-total"><span>Total</span><strong>${{ currency(checkout.grand_total) }}</strong></div>
                                            <p v-if="checkout.delivery_estimate" class="sb-delivery-time"><i class="bi bi-clock"></i>{{ checkout.delivery_estimate }}</p>
                                            <div class="sb-secure-checkout"><div class="sb-secure-checkout-icon"><i class="bi bi-shield-check"></i></div><div class="sb-secure-checkout-content"><strong class="sb-secure-checkout-title">Safe and secure</strong><span class="sb-secure-checkout-text">Your order details are protected.</span></div></div>
                                            <button type="submit" class="sb-checkout-submit" :disabled="submitting || !checkout.payments_enabled">
                                                <span>{{ submitting ? 'Placing order…' : 'Place Order' }}</span><i class="bi bi-arrow-right"></i>
                                            </button>
                                        </div>
                                    </section>
                                </div>
                            </aside>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</template>
