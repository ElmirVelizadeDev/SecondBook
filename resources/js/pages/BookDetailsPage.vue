<script setup>
import { computed, onMounted, ref } from 'vue';
import catalogService from '../services/catalogService';
import cartService from '../services/cartService';
import accountService from '../services/accountService';

const props = defineProps({
    bookId: { type: [String, Number], required: true },
    booksUrl: { type: String, required: true },
    detailsUrl: { type: String, required: true },
    loginUrl: { type: String, required: true },
    placeholderUrl: { type: String, required: true },
    authenticated: { type: Boolean, default: false },
});

const isAuthenticated = computed(() => props.authenticated === true || props.authenticated === 'true');
const book = ref(null);
const coverFailed = ref(false);
const relatedBooks = ref([]);
const authorBooks = ref([]);
const reviews = ref([]);
const loading = ref(true);
const error = ref('');
const quantity = ref(1);
const wishlisted = ref(false);
const notice = ref('');
const storeDetails = ref(null);
const stock = computed(() => Math.max(0, Number(book.value?.stock || 0)));
const hasDiscount = computed(() => book.value && Number(book.value.discounted_price) > 0 && Number(book.value.discounted_price) < Number(book.value.price));
const price = computed(() => Number(hasDiscount.value ? book.value.discounted_price : book.value?.price || 0));
const condition = computed(() => String(book.value?.condition || 'good').replaceAll('_', ' ').replace(/\b\w/g, (letter) => letter.toUpperCase()));
const titleWords = computed(() => (book.value?.title || '').trim().split(/\s+/));
const currency = (value) => `$${Number(value || 0).toFixed(2)}`;
const detailsUrl = (id) => props.detailsUrl.replace('__BOOK__', encodeURIComponent(id));

function imageUrl(path) {
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (/^(storage|uploads)\//.test(path)) return `/${path}`;
    return `/storage/${String(path).replace(/^\/+/, '')}`;
}
function flash(message) {
    notice.value = message;
    window.setTimeout(() => { notice.value = ''; }, 3500);
}
function changeQuantity(delta) {
    quantity.value = Math.max(1, Math.min(stock.value || 1, Number(quantity.value || 1) + delta));
}
async function addToCart() {
    if (!isAuthenticated.value) {
        window.location.href = props.loginUrl;
        return;
    }
    try {
        await cartService.addItem(book.value.id, quantity.value);
        flash('Book added to your cart.');
    } catch (exception) {
        flash(exception.response?.data?.message || 'Unable to add this book to your cart.');
    }
}
async function toggleWishlist() {
    if (!isAuthenticated.value) {
        window.location.href = props.loginUrl;
        return;
    }
    try {
        if (wishlisted.value) {
            await accountService.removeFromWishlist(book.value.id);
            wishlisted.value = false;
            flash('Book removed from your wishlist.');
        } else {
            await accountService.addToWishlist(book.value.id);
            wishlisted.value = true;
            flash('Book added to your wishlist.');
        }
    } catch (exception) {
        flash(exception.response?.data?.message || 'Unable to update your wishlist.');
    }
}
function authorBooksUrl(authorName) {
    const url = new URL(props.booksUrl, window.location.origin);
    if (authorName) url.searchParams.set('search', authorName);
    return `${url.pathname}${url.search}`;
}
function categoryBooksUrl(categoryId) {
    const url = new URL(props.booksUrl, window.location.origin);
    if (categoryId) url.searchParams.set('category', categoryId);
    return `${url.pathname}${url.search}`;
}
function ratingStars(rating) {
    return Array.from({ length: 5 }, (_, index) => index < Number(rating));
}

onMounted(async () => {
    try {
        const response = await catalogService.getBook(props.bookId);
        book.value = response.data;
        relatedBooks.value = response.related_books || [];
        authorBooks.value = response.author_books || [];
        reviews.value = response.reviews?.data || [];
        if (isAuthenticated.value) {
            try {
                const state = await accountService.getWishlistState(props.bookId);
                wishlisted.value = Boolean(state.is_wishlisted ?? state.data?.is_wishlisted ?? state.wishlisted);
            } catch (stateError) {
                if (stateError.response?.status !== 401) throw stateError;
            }
        }
        if (book.value?.store?.slug) {
            try {
                const store = await catalogService.getStore(book.value.store.slug);
                storeDetails.value = store.store || null;
            } catch (storeError) {
                if (storeError.response?.status !== 404) throw storeError;
            }
        }
    } catch (exception) {
        error.value = exception.response?.data?.message || 'This book could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <main class="book-details-page">
        <div v-if="loading" class="container bd-loading" role="status">Loading book details…</div>
        <div v-else-if="error" class="container alert alert-warning" role="alert">{{ error }} <a :href="booksUrl">Browse books</a></div>
        <template v-else-if="book">
            <section class="bd-hero">
                <div class="bd-hero-bg" aria-hidden="true"><i class="bd-orb bd-orb-1"></i><i class="bd-orb bd-orb-2"></i><i class="bd-ring bd-ring-1"></i><i class="bd-ring bd-ring-2"></i><i class="bd-sweep"></i></div>
                <div class="container"><div class="bd-topbar"><nav class="bd-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><i class="bi bi-chevron-right"></i><a :href="booksUrl">Books</a><i class="bi bi-chevron-right"></i><span>{{ book.title }}</span></nav><a :href="booksUrl" class="bd-back"><i class="bi bi-arrow-left"></i><span>Back to Books</span></a></div>
                    <div class="bd-hero-grid"><div class="bd-hero-info">
                        <div class="bd-badges"><a v-if="book.category" :href="categoryBooksUrl(book.category.id)" class="bd-badge bd-badge-category"><i class="bi bi-bookmark-star"></i>{{ book.category.name }}</a><span :class="['bd-badge bd-badge-condition', `bd-cond-${String(book.condition || 'good').replace('_', '-')}`]"><span class="bd-dot"></span>{{ condition }}</span><span :class="['bd-badge bd-badge-stock', stock > 0 ? 'is-available' : 'is-unavailable']"><span class="bd-dot"></span>{{ stock > 0 ? 'In stock' : 'Out of stock' }}</span></div>
                        <h1 class="bd-title" :aria-label="book.title"><template v-for="(word, index) in titleWords" :key="`${word}-${index}`"><span class="bd-word" :style="{ '--w': index }"><span>{{ word }}</span></span>{{ index < titleWords.length - 1 ? ' ' : '' }}</template></h1>
                        <div class="bd-author"><span class="bd-author-icon"><i class="bi bi-person"></i></span><span class="bd-author-label">Written by</span><a v-if="book.author" :href="authorBooksUrl(book.author.name)" class="bd-author-link">{{ book.author.name }}</a><strong v-else>Unknown Author</strong></div>
                        <p v-if="book.description" class="bd-excerpt">{{ book.description.slice(0, 230) }}{{ book.description.length > 230 ? '…' : '' }} <a href="#bd-about" class="bd-excerpt-more">Read more</a></p>
                        <ul class="bd-chips"><li><i class="bi bi-translate"></i>{{ book.language || 'English' }}</li><li v-if="book.pages"><i class="bi bi-file-earmark-text"></i>{{ book.pages }} pages</li><li v-if="book.publication_year"><i class="bi bi-calendar3"></i>{{ book.publication_year }}</li></ul>
                        <div class="bd-purchase" id="bd-purchase"><div class="bd-purchase-top"><div class="bd-price-block"><span class="bd-price-label">Current price</span><div v-if="hasDiscount" class="bd-price-row"><strong class="bd-price bd-price-discounted">{{ currency(price) }}</strong><span class="bd-old-price">{{ currency(book.price) }}</span></div><strong v-else class="bd-price">{{ currency(price) }}</strong><div v-if="hasDiscount" class="bd-price-tags"><span class="bd-discount-pill">{{ book.discount_label }}</span><span v-if="Number(book.price) > price" class="bd-save">You save {{ currency(Number(book.price) - price) }}</span></div></div><div :class="['bd-stock', stock > 0 ? 'is-available' : 'is-unavailable']"><span class="bd-stock-icon"><i :class="stock > 0 ? 'bi bi-check2' : 'bi bi-x'"></i></span><div><strong>{{ stock > 0 ? 'In stock' : 'Out of stock' }}</strong><span v-if="stock">{{ stock }} {{ stock === 1 ? 'copy' : 'copies' }} available</span></div></div></div>
                            <div class="bd-actions"><form v-if="stock" id="bd-cart-form" class="bd-cart-form" @submit.prevent="addToCart"><div class="bd-qty" data-qty><button type="button" class="bd-qty-btn" aria-label="Decrease quantity" @click="changeQuantity(-1)"><i class="bi bi-dash"></i></button><input v-model.number="quantity" type="number" name="quantity" min="1" :max="stock" inputmode="numeric" class="bd-qty-input" aria-label="Quantity" @change="quantity = Math.max(1, Math.min(stock, quantity || 1))"><button type="button" class="bd-qty-btn" aria-label="Increase quantity" @click="changeQuantity(1)"><i class="bi bi-plus"></i></button></div><button type="submit" class="bd-btn bd-btn-cart" data-cart-button><i class="bi bi-bag-plus"></i><span>Add to Cart</span></button></form><div v-else class="bd-cart-form"><button type="button" class="bd-btn bd-btn-cart is-disabled" disabled><i class="bi bi-bag-x"></i><span>Out of Stock</span></button></div><button type="button" :class="['bd-btn bd-btn-wishlist', { active: wishlisted }]" @click="toggleWishlist"><i :class="wishlisted ? 'bi bi-heart-fill' : 'bi bi-heart'"></i><span>{{ wishlisted ? 'Saved' : 'Add to Wishlist' }}</span></button></div>
                        </div>
                    </div>
                    <div class="bd-hero-visual"><div class="bd-stage" data-book-stage><div class="bd-halo" aria-hidden="true"></div><div class="bd-stage-ring" aria-hidden="true"></div><div class="bd-book-float"><div :class="['bd-book', { 'is-fallback': coverFailed }]" data-book-3d><div class="bd-book-front"><img :src="book.cover_url || placeholderUrl" :alt="book.title" class="bd-cover-img" data-cover-img @error="coverFailed = true"><div class="bd-book-fallback" aria-hidden="true"><i class="bi bi-book"></i><strong>{{ book.title }}</strong><span>SecondBook</span></div></div></div></div></div><div class="bd-float-card bd-float-card-a"><span class="bd-float-icon"><i class="bi bi-stars"></i></span><div><span>Condition</span><strong>{{ condition }}</strong></div></div><div class="bd-float-card bd-float-card-b"><span class="bd-float-icon"><i class="bi bi-shield-check"></i></span><div><span>SecondBook</span><strong>Quality checked</strong></div></div></div></div>
                </div>
            </section>

            <section class="bd-facts-section"><div class="container"><div class="bd-facts"><div class="bd-fact"><span class="bd-fact-icon"><i class="bi bi-stars"></i></span><div><span>Condition</span><strong>{{ condition }}</strong></div></div><div class="bd-fact"><span class="bd-fact-icon"><i class="bi bi-translate"></i></span><div><span>Language</span><strong>{{ book.language || 'English' }}</strong></div></div><div class="bd-fact"><span class="bd-fact-icon"><i class="bi bi-file-earmark-text"></i></span><div><span>Pages</span><strong>{{ book.pages || 'Not specified' }}</strong></div></div><div class="bd-fact"><span class="bd-fact-icon"><i class="bi bi-calendar3"></i></span><div><span>Published</span><strong>{{ book.publication_year || 'Not specified' }}</strong></div></div></div></div></section>
            <section v-if="book.description" class="bd-about" id="bd-about"><div class="container"><div class="bd-about-grid"><div class="bd-about-head"><span class="bd-label">THE STORY</span><h2>About this <em>book.</em></h2><div class="bd-about-mark" aria-hidden="true">”</div></div><div class="bd-about-text">{{ book.description }}</div></div></div></section>
            <section class="bd-specs" id="bd-specs"><div class="container"><div class="bd-section-head"><div><span class="bd-label">THE DETAILS</span><h2>Book <em>specifications.</em></h2></div><p>Everything you need to know about this copy, listed clearly before you decide.</p></div><div class="bd-ledger"><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-person"></i> Author</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.author?.name || 'Unknown Author' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-upc-scan"></i> ISBN</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.isbn || 'Not specified' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-building"></i> Publisher</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.publisher?.name || 'Not specified' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-bookmark-star"></i> Category</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.category?.name || 'Uncategorized' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-calendar3"></i> Published</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.publication_year || 'Not specified' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-file-earmark-text"></i> Pages</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.pages || 'Not specified' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-translate"></i> Language</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ book.language || 'English' }}</strong></div><div class="bd-spec"><span class="bd-spec-label"><i class="bi bi-stars"></i> Condition</span><span class="bd-spec-dots"></span><strong class="bd-spec-value">{{ condition }}</strong></div></div></div></section>
            <section v-if="storeDetails || book.seller" class="bd-seller-section" id="bd-seller"><div class="container"><div class="bd-seller"><div class="bd-seller-left"><div class="bd-seller-avatar"><img v-if="storeDetails?.logo" :src="imageUrl(storeDetails.logo)" :alt="storeDetails.name"><i v-else class="bi bi-shop"></i></div><div class="bd-seller-info"><span class="bd-seller-eyebrow">Sold by</span><strong>{{ storeDetails?.name || book.seller?.name }}</strong><div v-if="storeDetails" class="bd-seller-meta"><span class="bd-verified"><i class="bi bi-patch-check-fill"></i>Verified Store</span><span v-if="storeDetails.address" class="bd-seller-dot"></span><span v-if="storeDetails.address"><i class="bi bi-geo-alt"></i>{{ storeDetails.address }}</span></div></div></div><a v-if="storeDetails?.slug" :href="`/store/${encodeURIComponent(storeDetails.slug)}`" class="bd-btn bd-btn-store"><span>View Store</span><i class="bi bi-arrow-right"></i></a><span v-else class="bd-verified bd-verified-pill"><i class="bi bi-patch-check-fill"></i>Verified Seller</span></div></div></section>
            <section v-if="relatedBooks.length" class="bd-related" id="bd-related"><div class="container"><div class="bd-section-head"><div><span class="bd-label">KEEP READING</span><h2>You may also <em>like.</em></h2></div><a :href="booksUrl" class="bd-link-all"><span>Browse all books</span><i class="bi bi-arrow-right"></i></a></div><div class="bd-related-grid"><a v-for="(related, index) in relatedBooks" :key="related.id" :href="detailsUrl(related.id)" class="bd-rel-card" :style="{ '--i': index }"><div class="bd-rel-cover"><img v-if="related.cover_url" :src="related.cover_url" :alt="related.title" loading="lazy"><div v-else class="bd-rel-placeholder"><i class="bi bi-book"></i></div><span v-if="related.discount_label && Number(related.discounted_price) < Number(related.price)" class="bd-rel-discount">{{ related.discount_label }}</span></div><div class="bd-rel-body"><h3>{{ related.title }}</h3><p>{{ related.author?.name || 'Unknown Author' }}</p><div class="bd-rel-bottom"><strong :class="{ 'is-sale': Number(related.discounted_price) < Number(related.price) }">{{ currency(Number(related.discounted_price) < Number(related.price) ? related.discounted_price : related.price) }}</strong><s v-if="Number(related.discounted_price) < Number(related.price)">{{ currency(related.price) }}</s><span class="bd-rel-arrow"><i class="bi bi-arrow-up-right"></i></span></div></div></a></div></div></section>
            <section v-if="reviews.length" class="bd-reviews" id="bd-reviews"><div class="container"><div class="bd-section-head"><div><span class="bd-label">READER NOTES</span><h2>What readers <em>say.</em></h2></div></div><div class="bd-reviews-grid"><article v-for="review in reviews" :key="review.id" class="bd-review"><div class="bd-review-stars"><i v-for="(filled, index) in ratingStars(review.rating)" :key="index" :class="filled ? 'bi bi-star-fill' : 'bi bi-star'"></i></div><p>{{ review.comment || 'No written comment was provided.' }}</p><strong>{{ review.reviewer?.name || 'Reader' }}</strong></article></div></div></section>
            <div v-if="stock" class="bd-sticky" id="bd-sticky"><div class="bd-sticky-inner"><div class="bd-sticky-book"><img v-if="book.cover_url" :src="book.cover_url" alt=""><div><strong>{{ book.title }}</strong><span>{{ currency(price) }}</span></div></div><button type="button" class="bd-btn bd-btn-cart bd-btn-sticky" @click="addToCart"><i class="bi bi-bag-plus"></i><span>Add to Cart</span></button></div></div>
        </template>
        <div v-if="notice" class="bd-toast" role="status"><span class="bd-toast-icon"><i class="bi bi-check-lg"></i></span>{{ notice }}</div>
    </main>
</template>
