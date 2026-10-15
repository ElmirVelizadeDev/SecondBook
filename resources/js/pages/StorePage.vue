<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import catalogService from '../services/catalogService';
import cartService from '../services/cartService';
import contentService from '../services/contentService';

const props = defineProps({
    storeSlug: { type: String, required: true },
    booksUrl: { type: String, required: true },
    detailsUrl: { type: String, required: true },
    loginUrl: { type: String, required: true },
    authenticated: { type: Boolean, default: false },
});

const isAuthenticated = computed(() => props.authenticated === true || props.authenticated === 'true');
const payload = ref(null);
const loading = ref(true);
const error = ref('');
const notice = ref('');
const filters = reactive({ search: '', category: '', sort: '' });
const message = reactive({ subject: '', message: '' });
const sending = ref(false);
const bookMeta = computed(() => payload.value?.books?.meta || {});
const reviewMeta = computed(() => payload.value?.reviews?.meta || {});
const books = computed(() => payload.value?.books?.data || []);
const reviews = computed(() => payload.value?.reviews?.data || []);

function detailsUrl(id) {
    return props.detailsUrl.replace('__BOOK__', encodeURIComponent(id));
}

function imageUrl(path) {
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (/^(storage|uploads)\//.test(path)) return `/${path}`;
    return `/storage/${String(path).replace(/^\/+/, '')}`;
}

function currency(value) {
    return Number(value || 0).toFixed(2);
}

function hasDiscount(book) {
    return Number(book.discounted_price) > 0 && Number(book.discounted_price) < Number(book.price);
}

function params(page = 1, reviewsPage = 1) {
    return {
        search: filters.search || undefined,
        category: filters.category || undefined,
        sort: filters.sort || undefined,
        page,
        reviews_page: reviewsPage,
    };
}

async function loadStore(page = 1, reviewsPage = 1) {
    loading.value = true;
    error.value = '';
    try {
        payload.value = await catalogService.getStore(props.storeSlug, params(page, reviewsPage));
    } catch (exception) {
        error.value = exception.response?.data?.message || 'This store could not be loaded.';
    } finally {
        loading.value = false;
    }
}

function submitFilters() {
    loadStore(1, Number(reviewMeta.value.current_page || 1));
}

async function addToCart(book) {
    if (!isAuthenticated.value) {
        window.location.assign(props.loginUrl);
        return;
    }
    try {
        await cartService.addItem(book.id);
        notice.value = 'Book added to your cart.';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'This book could not be added to your cart.';
    }
}

async function sendMessage() {
    if (!isAuthenticated.value) {
        window.location.assign(props.loginUrl);
        return;
    }
    sending.value = true;
    error.value = '';
    try {
        const response = await contentService.sendStoreMessage(props.storeSlug, message);
        notice.value = response.message || 'Your message was sent to the store.';
        message.subject = '';
        message.message = '';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your message could not be sent.';
    } finally {
        sending.value = false;
    }
}

onMounted(() => {
    const searchParams = new URLSearchParams(window.location.search);
    filters.search = searchParams.get('search') || '';
    filters.category = searchParams.get('category') || '';
    filters.sort = searchParams.get('sort') || '';
    loadStore(Number(searchParams.get('page') || 1), Number(searchParams.get('reviews_page') || 1));
});
</script>

<template>
    <div class="store-page">
        <div class="store-container">
            <nav class="store-breadcrumb" aria-label="Breadcrumb"><a href="/"><i class="bi bi-house"></i><span>Home</span></a><i class="bi bi-chevron-right"></i><a :href="booksUrl">Books</a><i class="bi bi-chevron-right"></i><strong>{{ payload?.store?.name || 'Store' }}</strong></nav>
        </div>

        <div v-if="notice" class="store-toast is-success" role="status"><i class="bi bi-check-circle-fill"></i><span>{{ notice }}</span><button type="button" aria-label="Close" @click="notice = ''"><i class="bi bi-x-lg"></i></button></div>
        <div v-if="error" class="store-toast is-error" role="alert"><i class="bi bi-exclamation-circle-fill"></i><span>{{ error }}</span><button type="button" aria-label="Close" @click="error = ''"><i class="bi bi-x-lg"></i></button></div>

        <section class="store-hero">
            <div class="store-hero-bg" aria-hidden="true"><span class="store-hero-orb store-hero-orb-one"></span><span class="store-hero-orb store-hero-orb-two"></span></div>
            <div class="store-container">
                <div v-if="loading" class="store-hero-main" role="status">Loading store…</div>
                <div v-else-if="payload" class="store-hero-main">
                    <div class="store-logo"><img v-if="imageUrl(payload.store.logo)" :src="imageUrl(payload.store.logo)" :alt="payload.store.name"><div v-else class="store-logo-placeholder"><i class="bi bi-shop"></i></div></div>
                    <div class="store-identity">
                        <span class="store-kicker"><span></span>Official Store</span>
                        <div class="store-name-row"><h1>{{ payload.store.name }}</h1><span class="store-verified"><i class="bi bi-patch-check-fill"></i>Verified</span></div>
                        <div class="store-meta"><span class="store-meta-item"><i class="bi bi-person"></i>{{ payload.seller?.name || 'Seller' }}</span><span v-if="payload.store.address" class="store-meta-item"><i class="bi bi-geo-alt"></i>{{ payload.store.address }}</span><span :class="['store-meta-item store-status', payload.store.accept_orders ? 'is-open' : 'is-paused']"><span class="store-status-dot"></span>{{ payload.store.accept_orders ? 'Active Store' : 'Orders Paused' }}</span></div>
                        <p v-if="payload.store.description">{{ payload.store.description }}</p>
                    </div>
                    <div class="store-hero-action"><a href="#store-books" class="store-btn store-btn-light"><i class="bi bi-book"></i>Browse books</a></div>
                </div>
                <div v-if="payload" class="store-stats">
                    <div><span>BOOKS</span><strong>{{ payload.book_count }}</strong></div>
                    <div><span>RATING</span><strong>{{ payload.average_rating ?? '—' }}</strong></div>
                    <div><span>REVIEWS</span><strong>{{ payload.review_count }}</strong></div>
                </div>
            </div>
        </section>

        <section v-if="payload" class="store-about">
            <div class="store-container"><div class="store-about-card">
                <div class="store-about-text"><span class="store-eyebrow"><span></span>About This Store</span><h2>{{ payload.store.name }}</h2><p v-if="payload.store.description">{{ payload.store.description }}</p><p v-else class="store-muted">This store has not added a description yet.</p></div>
                <div class="store-about-info"><div class="store-info-item"><div class="store-info-icon"><i class="bi bi-person"></i></div><div><span>Seller</span><strong>{{ payload.seller?.name || 'Seller' }}</strong></div></div><div v-if="payload.store.address" class="store-info-item"><div class="store-info-icon"><i class="bi bi-geo-alt"></i></div><div><span>Address</span><strong>{{ payload.store.address }}</strong></div></div><div v-if="payload.store.phone" class="store-info-item"><div class="store-info-icon"><i class="bi bi-telephone"></i></div><div><span>Phone</span><strong>{{ payload.store.phone }}</strong></div></div></div>
            </div></div>
        </section>

        <section id="store-books" class="store-content">
            <div class="store-container">
                <div class="store-layout">
                    <aside class="store-sidebar">
                        <form class="store-filter-card store-filter-card--search" @submit.prevent="submitFilters">
                            <div class="store-filter-heading"><h3>Find a Book</h3><i class="bi bi-search"></i></div>
                            <div class="store-search"><i class="bi bi-search"></i><input v-model="filters.search" type="search" name="search" placeholder="Search this store..." aria-label="Search this store"></div>
                            <label class="store-filter-label" for="store-category">Category</label>
                            <select id="store-category" v-model="filters.category" aria-label="Filter by category"><option value="">All categories</option><option v-for="category in payload?.categories || []" :key="category.id" :value="category.id">{{ category.name }}</option></select>
                            <label class="store-filter-label" for="store-sort">Sort by</label>
                            <select id="store-sort" v-model="filters.sort" aria-label="Sort books"><option value="">Newest</option><option value="price_low">Price: Low to High</option><option value="price_high">Price: High to Low</option><option value="oldest">Oldest</option></select>
                            <button type="submit" class="store-btn store-btn-brand">Apply filters</button>
                        </form>
                    </aside>
                    <main class="store-main">
                <div class="store-books-heading"><div class="store-books-title"><span>THE COLLECTION</span><h2>Books from this store</h2></div><span>{{ bookMeta.total || 0 }} books</span></div>
                <div v-if="loading" class="store-empty" role="status">Loading books…</div>
                <div v-else-if="books.length" class="store-books-grid">
                    <article v-for="book in books" :key="book.id" :class="['store-book-card', { 'is-sold-out': Number(book.stock) <= 0 }]">
                        <a :href="detailsUrl(book.id)" class="store-book-cover"><img v-if="imageUrl(book.cover_url || book.cover)" :src="imageUrl(book.cover_url || book.cover)" :alt="book.title" loading="lazy"><span v-else class="store-book-cover-placeholder"><i class="bi bi-book"></i></span></a>
                        <div class="store-book-body">
                            <span v-if="book.category" class="store-book-category">{{ book.category.name }}</span>
                            <h3><a :href="detailsUrl(book.id)">{{ book.title }}</a></h3>
                            <p class="store-book-author">{{ book.author?.name || 'Unknown Author' }}</p>
                            <div class="store-book-meta"><span>{{ Number(book.stock) > 0 ? 'In stock' : 'Out of stock' }}</span><span>{{ book.condition || 'Good' }}</span></div>
                            <div class="store-book-footer"><div class="store-price"><strong>{{ currency(hasDiscount(book) ? book.discounted_price : book.price) }} <small>AZN</small></strong></div><div class="store-cart-slot"><button v-if="Number(book.stock) > 0 && payload.store.accept_orders" type="button" class="store-cart-btn" @click="addToCart(book)"><i class="bi bi-cart-plus"></i><span>Add to Cart</span></button><button v-else type="button" class="store-cart-btn" disabled><i :class="Number(book.stock) > 0 ? 'bi bi-pause-circle' : 'bi bi-slash-circle'"></i><span>{{ Number(book.stock) <= 0 ? 'Out of Stock' : 'Orders Paused' }}</span></button></div></div>
                        </div>
                    </article>
                </div>
                <div v-else-if="!loading" class="store-empty"><h3>No books found</h3><p>Try changing your search or filters.</p></div>
                <nav v-if="bookMeta.last_page > 1" class="store-pagination" aria-label="Store book pages"><button :disabled="bookMeta.current_page <= 1" @click="loadStore(bookMeta.current_page - 1)">Previous</button><span>{{ bookMeta.current_page }} / {{ bookMeta.last_page }}</span><button :disabled="bookMeta.current_page >= bookMeta.last_page" @click="loadStore(bookMeta.current_page + 1)">Next</button></nav>
                    </main>
                </div>
            </div>
        </section>

        <section v-if="payload" class="store-reviews-section">
            <div class="store-container">
                <div class="store-reviews-heading"><div><span>READER EXPERIENCES</span><h2>Store reviews</h2></div><div class="store-rating-summary"><strong>{{ payload.average_rating ?? '—' }}</strong><span>{{ payload.review_count }} reviews</span></div></div>
                <div class="store-rating-breakdown"><div v-for="(count, rating) in payload.rating_breakdown" :key="rating" class="store-rating-row"><span>{{ rating }} stars</span><div class="store-rating-progress"><span :style="{ width: payload.review_count ? `${count / payload.review_count * 100}%` : '0%' }"></span></div><strong>{{ count }}</strong></div></div>
                <div class="store-review-list">
                    <article v-for="review in reviews" :key="review.id" class="store-review-card">
                        <div class="store-review-top"><div class="store-review-user"><div class="store-review-avatar">{{ review.reviewer?.name?.charAt(0) || 'U' }}</div><strong>{{ review.reviewer?.name || 'Customer' }}</strong></div><div class="store-review-rating"><span v-for="star in 5" :key="star"><i :class="star <= review.rating ? 'bi bi-star-fill' : 'bi bi-star'"></i></span></div></div>
                        <a v-if="review.book" :href="detailsUrl(review.book.id)" class="store-review-book">{{ review.book.title }}</a><p class="store-review-comment" :class="{ 'store-muted': !review.comment }">{{ review.comment ? `“${review.comment}”` : 'No written comment was provided.' }}</p>
                    </article>
                    <p v-if="!reviews.length" class="store-muted">No reviews yet.</p>
                </div>
                <nav v-if="reviewMeta.last_page > 1" class="store-pagination" aria-label="Store review pages"><button :disabled="reviewMeta.current_page <= 1" @click="loadStore(bookMeta.current_page || 1, reviewMeta.current_page - 1)">Previous</button><span>{{ reviewMeta.current_page }} / {{ reviewMeta.last_page }}</span><button :disabled="reviewMeta.current_page >= reviewMeta.last_page" @click="loadStore(bookMeta.current_page || 1, reviewMeta.current_page + 1)">Next</button></nav>
            </div>
        </section>

        <section v-if="payload" class="store-contact-section">
            <div class="store-container store-contact-layout">
                <div class="store-contact-form-section"><div class="store-contact-section-heading"><span class="store-contact-section-icon"><i class="bi bi-chat-heart"></i></span><h2>Contact this store</h2><p>Ask a question about a book or your order.</p></div>
                    <form v-if="isAuthenticated" @submit.prevent="sendMessage">
                        <div class="store-contact-field"><label for="store-message-subject">Subject</label><input id="store-message-subject" v-model="message.subject" maxlength="255" required></div>
                        <div class="store-contact-field"><label for="store-message-body">Message</label><textarea id="store-message-body" v-model="message.message" rows="5" minlength="10" maxlength="5000" required></textarea></div>
                        <button type="submit" class="store-btn" :disabled="sending">{{ sending ? 'Sending…' : 'Send message' }}</button>
                    </form>
                    <p v-else><a :href="loginUrl">Sign in</a> to send a message to this store.</p>
                </div>
                <aside class="store-contact-info"><div class="store-contact-info-heading"><span class="store-contact-info-eyebrow">STORE DETAILS</span><h3>Here to help</h3></div><div v-if="payload.store.phone" class="store-contact-info-item"><i class="bi bi-telephone"></i><span>{{ payload.store.phone }}</span></div><div v-if="payload.store.address" class="store-contact-info-item"><i class="bi bi-geo-alt"></i><span>{{ payload.store.address }}</span></div><div class="store-contact-status"><span class="store-contact-status-dot"></span>{{ payload.store.accept_orders ? 'Accepting orders' : 'Orders paused' }}</div></aside>
            </div>
        </section>
    </div>
</template>
