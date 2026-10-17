<script setup>
import { computed, onMounted, ref } from 'vue';
import cartService from '../services/cartService';

const props = defineProps({
    booksUrl: { type: String, required: true },
    checkoutUrl: { type: String, required: true },
});

const cart = ref([]);
const subtotal = ref(0);
const shippingFee = ref(0);
const total = ref(0);
const shippingEnabled = ref(true);
const freeShippingThreshold = ref(0);
const totalItems = computed(() => cart.value.reduce(
    (sum, item) => sum + Number(item.quantity || 0),
    0
));
const loading = ref(true);
const error = ref('');
const notice = ref('');
const updating = ref(null);

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

async function loadCart() {
    loading.value = true;
    error.value = '';
    try {
        const response = await cartService.getCart();
        cart.value = response.data || [];
        subtotal.value = Number(response.subtotal || 0);
        shippingFee.value = Number(response.shipping_fee || 0);
        total.value = Number(response.total || subtotal.value + shippingFee.value);
        shippingEnabled.value = response.shipping_enabled !== false;
        freeShippingThreshold.value = Number(response.free_shipping_threshold || 0);

        const headerCartCount = document.getElementById('header-cart-count');
        if (headerCartCount) {
            headerCartCount.textContent = totalItems.value > 99
                ? '99+'
                : String(totalItems.value);
            headerCartCount.style.display = totalItems.value > 0 ? '' : 'none';
        }
    } catch (exception) {
        error.value = exception.response?.data?.message
            || 'Your cart could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}

async function updateQuantity(item, quantity) {
    if (updating.value !== null) return;
    updating.value = item.id;
    error.value = '';
    notice.value = '';
    try {
        if (quantity <= 0) {
            await cartService.removeItem(item.id);
        } else {
            await cartService.updateItem(item.id, quantity);
        }
        await loadCart();
    } catch (exception) {
        const message = exception.response?.data?.message
            || 'The cart could not be updated. Please try again.';
        await loadCart();
        error.value = message;
    } finally {
        updating.value = null;
    }
}

async function clearCart() {
    if (updating.value !== null || !cart.value.length) return;
    updating.value = 'all';
    error.value = '';
    try {
        await cartService.clearCart();
        await loadCart();
        notice.value = 'Your cart has been cleared.';
    } catch (exception) {
        error.value = exception.response?.data?.message
            || 'Your cart could not be cleared. Please try again.';
    } finally {
        updating.value = null;
    }
}

onMounted(loadCart);
</script>

<template>
    <main class="sb-cart-page">
        <div class="sb-cart-background" aria-hidden="true">
            <span class="sb-cart-orb sb-cart-orb-one"></span>
            <span class="sb-cart-orb sb-cart-orb-two"></span>
            <span class="sb-cart-grid"></span>
        </div>

        <div class="container sb-cart-container">
            <header class="sb-cart-header">
                <div class="sb-cart-header-content">
                    <nav class="sb-cart-breadcrumb" aria-label="Breadcrumb">
                        <a href="/frontend">Home</a><i class="bi bi-chevron-right"></i><span>Your Cart</span>
                    </nav>
                    <span class="sb-cart-eyebrow"><span class="sb-cart-eyebrow-icon"><i class="bi bi-bag-heart"></i></span>YOUR READING JOURNEY</span>
                    <h1>Your Books, <span>Together.</span></h1>
                    <p>Your next great read is waiting. Review your selection and get ready to turn the page.</p>
                    <div class="sb-cart-header-trust"><span><i class="bi bi-book-half"></i> Curated for readers</span><span><i class="bi bi-shield-check"></i> Secure checkout</span></div>
                </div>
                <a :href="booksUrl" class="sb-continue-shopping"><i class="bi bi-arrow-left"></i><span>Continue Shopping</span></a>
            </header>

            <div v-if="notice" class="sb-cart-alert sb-cart-alert-success" role="status">
                <span class="sb-cart-alert-icon"><i class="bi bi-check-lg"></i></span>{{ notice }}
            </div>
            <div v-if="error" class="sb-cart-alert sb-cart-alert-error" role="alert">
                <span class="sb-cart-alert-icon"><i class="bi bi-exclamation-lg"></i></span>{{ error }}
                <button type="button" class="btn btn-sm btn-outline-dark ms-auto" @click="loadCart">Try again</button>
            </div>

            <div v-if="loading" class="sb-empty-cart" role="status">Loading your cart…</div>
            <div v-else-if="cart.length" class="sb-cart-layout">
                <section class="sb-cart-main">
                    <div class="sb-cart-items-header">
                        <div class="sb-cart-items-title">
                            <div class="sb-cart-items-icon"><i class="bi bi-bag"></i></div>
                            <div><span class="sb-cart-section-label">YOUR SELECTION</span><h2>Your Cart</h2><span class="sb-cart-items-count">{{ totalItems }} {{ totalItems === 1 ? 'item' : 'items' }}</span></div>
                        </div>
                        <button type="button" class="sb-clear-cart" :disabled="updating !== null" @click="clearCart"><i class="bi bi-trash3"></i><span>Clear Cart</span></button>
                    </div>

                    <div class="sb-cart-items-list">
                        <article v-for="item in cart" :key="item.id" class="sb-cart-item">
                            <div class="sb-cart-item-cover">
                                <img v-if="coverUrl(item)" :src="coverUrl(item)" :alt="item.title" loading="lazy" decoding="async" @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'">
                                <div v-else class="sb-cart-no-cover"><i class="bi bi-book"></i></div>
                                <div v-if="coverUrl(item)" class="sb-cart-no-cover" style="display: none"><i class="bi bi-book"></i></div>
                                <span class="sb-cart-cover-shine" aria-hidden="true"></span>
                            </div>
                            <div class="sb-cart-item-content">
                                <div class="sb-cart-item-top">
                                    <div class="sb-cart-item-details">
                                        <span class="sb-cart-item-label">BOOK SELECTION</span>
                                        <h3>{{ item.title }}</h3>
                                        <div :class="['sb-cart-stock', Number(item.stock) > 0 ? 'is-available' : 'is-unavailable']">
                                            <i :class="Number(item.stock) > 0 ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill'"></i>
                                            <span>{{ Number(item.stock) > 0 ? `${item.stock} available` : 'Out of stock' }}</span>
                                        </div>
                                    </div>
                                    <div class="sb-cart-unit-price">
                                        <span class="sb-cart-price-caption">UNIT PRICE</span>
                                        <div v-if="Number(item.original_price) > Number(item.price)" class="sb-cart-price-wrap">
                                            <span class="sb-cart-old-price">${{ currency(item.original_price) }}</span>
                                            <strong class="sb-cart-discounted-price">${{ currency(item.price) }}</strong>
                                        </div>
                                        <strong v-else>${{ currency(item.price) }}</strong>
                                        <small v-if="item.discount_label" class="sb-cart-discount-label">{{ item.discount_label }}</small>
                                    </div>
                                    <button type="button" class="sb-remove-item" :aria-label="`Remove ${item.title}`" title="Remove item" :disabled="updating === item.id" @click="updateQuantity(item, 0)">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                <div class="sb-cart-item-bottom">
                                    <div class="sb-quantity-control">
                                        <span class="sb-quantity-label">Quantity</span>
                                        <button type="button" class="sb-quantity-btn" aria-label="Decrease quantity" :disabled="updating !== null || Number(item.quantity) <= 1" @click="updateQuantity(item, Number(item.quantity) - 1)"><i class="bi bi-dash"></i></button>
                                        <input :value="item.quantity" type="text" name="quantity" readonly :aria-label="`${item.title} quantity`">
                                        <button type="button" class="sb-quantity-btn" aria-label="Increase quantity" :disabled="updating !== null || Number(item.quantity) >= Number(item.stock)" @click="updateQuantity(item, Number(item.quantity) + 1)"><i class="bi bi-plus"></i></button>
                                    </div>
                                    <div class="sb-cart-item-total"><span>ITEM TOTAL</span><strong>${{ currency(Number(item.price) * Number(item.quantity)) }}</strong></div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <a :href="booksUrl" class="sb-cart-discover-link">
                        <span class="sb-cart-discover-icon"><i class="bi bi-plus-lg"></i></span>
                        <span><strong>Found another book?</strong><small>Keep exploring the collection</small></span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </section>

                <aside class="sb-cart-summary">
                    <div class="sb-summary-card">
                        <div class="sb-summary-top-decoration" aria-hidden="true"></div>
                        <div class="sb-summary-header">
                            <div class="sb-summary-icon"><i class="bi bi-receipt"></i></div>
                            <div><span>THE FINAL CHAPTER</span><h2>Order Summary</h2></div>
                        </div>
                        <div class="sb-summary-content">
                            <div class="sb-summary-row"><span>Items</span><strong>{{ totalItems }} {{ totalItems === 1 ? 'item' : 'items' }}</strong></div>
                            <div class="sb-summary-row"><span>Subtotal</span><strong>${{ currency(subtotal) }}</strong></div>
                            <div class="sb-summary-row"><span>Shipping</span><strong :class="{ 'sb-free-shipping': shippingEnabled && !shippingFee }">{{ !shippingEnabled ? 'Disabled' : shippingFee ? `$${currency(shippingFee)}` : 'Free' }}</strong></div>
                            <div v-if="shippingEnabled && freeShippingThreshold > 0" class="sb-shipping-note">
                                <span class="sb-shipping-note-icon"><i class="bi bi-truck"></i></span>
                                <span>{{ shippingFee > 0 ? `Free shipping on orders over $${currency(freeShippingThreshold)}.` : 'Free shipping is included with your order.' }}</span>
                            </div>
                            <div class="sb-summary-divider"></div>
                            <div class="sb-summary-total"><span>Total</span><strong>${{ currency(total) }}</strong></div>
                            <a :href="checkoutUrl" class="sb-checkout-btn"><span>Proceed to Checkout</span><i class="bi bi-arrow-right"></i></a>
                            <div class="sb-secure-checkout">
                                <span class="sb-secure-icon"><i class="bi bi-shield-lock"></i></span>
                                <div><strong>Secure checkout</strong><span>Protected payment experience</span></div>
                                <i class="bi bi-patch-check-fill sb-secure-check"></i>
                            </div>
                            <div class="sb-summary-payment-note"><i class="bi bi-heart"></i><span>Every book begins a new journey.</span></div>
                        </div>
                    </div>
                </aside>
            </div>
            <section v-else class="sb-empty-cart">
                <div class="sb-empty-cart-decoration sb-decoration-one" aria-hidden="true"></div>
                <div class="sb-empty-cart-decoration sb-decoration-two" aria-hidden="true"></div>
                <div class="sb-empty-cart-icon"><span class="sb-empty-cart-icon-ring"></span><i class="bi bi-bag-heart"></i></div>
                <span class="sb-empty-cart-label">YOUR NEXT CHAPTER AWAITS</span>
                <h2>Your story starts <span>with a book.</span></h2>
                <p>Your cart is taking a little break. Explore our collection and discover the books waiting to become part of your next reading journey.</p>
                <a :href="booksUrl" class="sb-empty-cart-btn"><i class="bi bi-book"></i><span>Explore the Collection</span><i class="bi bi-arrow-right"></i></a>
                <div class="sb-empty-cart-footnote"><i class="bi bi-stars"></i>A good book is always worth discovering.</div>
            </section>
        </div>
    </main>
</template>
