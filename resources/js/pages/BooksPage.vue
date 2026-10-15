
<script setup>
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue';

import bookService from '../services/bookService';
import cartService from '../services/cartService';
import accountService from '../services/accountService';

const props = defineProps({
    booksUrl: {
        type: String,
        required: true,
    },
    detailsUrl: {
        type: String,
        required: true,
    },
    loginUrl: {
        type: String,
        required: true,
    },
    authenticated: {
        type: Boolean,
        default: false,
    },
});

const isAuthenticated = computed(
    () => props.authenticated === true || props.authenticated === 'true'
);

const books = ref([]);
const categories = ref([]);

const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 12,
    total: 0,
});

const loading = ref(true);
const error = ref('');
const notice = ref('');

const filters = reactive({
    search: '',
    category: '',
    condition: '',
    sort: '',
});

let noticeTimer;

// Pagination calculations
const firstItem = computed(() => {
    return meta.value.total
        ? (meta.value.current_page - 1) * meta.value.per_page + 1
        : 0;
});

const lastItem = computed(() => {
    return Math.min(
        meta.value.current_page * meta.value.per_page,
        meta.value.total
    );
});

const pageNumbers = computed(() => {
    const start = Math.max(1, meta.value.current_page - 2);
    const end = Math.min(meta.value.last_page, meta.value.current_page + 2);
    const pages = [];

    for (let page = start; page <= end; page += 1) {
        pages.push(page);
    }

    return pages;
});

const heroBooks = computed(() => books.value.slice(0, 3));

// Read filters from the current URL
function currentParams() {
    const params = new URLSearchParams(window.location.search);

    return {
        search: params.get('search') || '',
        category: params.get('category') || '',
        condition: params.get('condition') || '',
        sort: params.get('sort') || '',
        page: Number(params.get('page') || 1),
    };
}

// Prepare API parameters
function apiParams(page = currentParams().page) {
    const params = {};

    for (const key of ['search', 'category', 'condition', 'sort']) {
        if (filters[key]) {
            params[key] = filters[key];
        }
    }

    params.page = page;

    return params;
}

// Load books from the API
async function loadBooks(page = currentParams().page) {
    loading.value = true;
    error.value = '';

    try {
        const response = await bookService.getBooks(apiParams(page));

        books.value = response.data || [];
        categories.value = response.categories || [];
        meta.value = response.meta || meta.value;

        if (isAuthenticated.value && books.value.length) {
            const wishlist = await accountService.getWishlist({
                per_page: 100,
            });

            const savedIds = new Set(
                (wishlist.data || []).map((item) =>
                    Number(item.book?.id ?? item.book_id ?? item.id)
                )
            );

            books.value = books.value.map((book) => ({
                ...book,
                is_wishlisted: savedIds.has(Number(book.id)),
            }));
        }
    } catch (exception) {
        error.value =
            exception.response?.data?.message ||
            'Books could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}

// Build the URL while preserving active filters
function urlFor(params = {}) {
    const url = new URL(props.booksUrl, window.location.origin);
    const values = { ...filters, ...params };

    Object.entries(values).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== '') {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
    });

    return url;
}

// Navigate between filtered results and pages
async function navigate(params = {}) {
    const url = urlFor(params);

    window.history.pushState({}, '', `${url.pathname}${url.search}`);

    await loadBooks(Number(url.searchParams.get('page') || 1));

    document.querySelector('#books-collection')?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
}

function submitFilters() {
    navigate({ page: 1 });
}

function clearFilters() {
    Object.assign(filters, {
        search: '',
        category: '',
        condition: '',
        sort: '',
    });

    navigate({
        search: '',
        category: '',
        condition: '',
        sort: '',
        page: 1,
    });
}

// Book helpers
function detailUrl(id) {
    return props.detailsUrl.replace('__BOOK__', encodeURIComponent(id));
}

function imageUrl(book) {
    return book.cover_url || null;
}

function conditionLabel(condition) {
    return (condition || 'good')
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (letter) => letter.toUpperCase());
}

function hasDiscount(book) {
    return (
        Number(book.discounted_price) > 0 &&
        Number(book.discounted_price) < Number(book.price)
    );
}

// Display a temporary notification
function flash(message) {
    notice.value = message;

    window.clearTimeout(noticeTimer);

    noticeTimer = window.setTimeout(() => {
        notice.value = '';
    }, 3500);
}

// Add or remove a book from the wishlist
async function toggleWishlist(book) {
    if (!isAuthenticated.value) {
        window.location.href = props.loginUrl;
        return;
    }

    try {
        if (book.is_wishlisted) {
            await accountService.removeFromWishlist(book.id);

            book.is_wishlisted = false;
            flash('Book removed from your wishlist.');
        } else {
            await accountService.addToWishlist(book.id);

            book.is_wishlisted = true;
            flash('Book added to your wishlist.');
        }
    } catch (exception) {
        flash(
            exception.response?.data?.message ||
            'Unable to update your wishlist.'
        );
    }
}

// Add a book to the cart


async function addToCart(book, event) {
    if (!isAuthenticated.value) {
        window.location.href = props.loginUrl;
        return;
    }

    const button = event.currentTarget;

    if (button.disabled || button.dataset.cartProcessing === 'true') {
        return;
    }

    button.dataset.cartProcessing = 'true';

    const originalHtml = '<i class="bi bi-bag-plus" aria-hidden="true"></i>';
    let succeeded = false;

    try {
        button.disabled = true;
        button.classList.add('is-adding', 'cart-ajax-processing');
        button.setAttribute('aria-busy', 'true');

        button.innerHTML =
            '<span class="cart-loading-spinner" aria-hidden="true"></span>';

        const response = await cartService.addItem(book.id, 1);

        succeeded = true;

        button.innerHTML =
            '<span class="cart-success-icon" aria-hidden="true">' +
                '<i class="bi bi-check-lg"></i>' +
            '</span>';

        const cartCount = document.getElementById('header-cart-count');

        if (cartCount && response.cart_count !== undefined) {
            const count = Number(response.cart_count);

            cartCount.textContent = count > 99 ? '99+' : String(count);
            cartCount.style.display = count > 0 ? 'inline-flex' : 'none';

            cartCount.classList.remove('cart-count-bump');
            void cartCount.offsetWidth;
            cartCount.classList.add('cart-count-bump');
        }

        flash(response.message || 'Book added to your cart.');
    } catch (exception) {
        console.error('Add to cart error:', exception);

        flash(
            exception.response?.data?.message ||
            'Unable to add this book to your cart.'
        );
    } finally {
        window.setTimeout(() => {
            button.innerHTML = originalHtml;

            button.classList.remove(
                'is-adding',
                'cart-ajax-processing'
            );

            button.removeAttribute('aria-busy');
            button.disabled = false;

            delete button.dataset.cartProcessing;
        }, succeeded ? 900 : 0);
    }
}



// Keep filters in sync with browser navigation
function handlePopState() {
    Object.assign(filters, currentParams());
    loadBooks();
}

// Component lifecycle
onMounted(() => {
    Object.assign(filters, currentParams());

    loadBooks();

    window.addEventListener('popstate', handlePopState);
});

onUnmounted(() => {
    window.removeEventListener('popstate', handlePopState);
    window.clearTimeout(noticeTimer);
});
</script>

<template>
    <div class="books-page">
        <!-- Hero -->
        <section class="books-hero">
            <div class="books-hero-bg"></div>
            <div class="books-hero-noise"></div>
            <div class="books-hero-pattern"></div>

            <div class="books-hero-orbit books-hero-orbit-one"></div>
            <div class="books-hero-orbit books-hero-orbit-two"></div>
            <div class="books-hero-orbit books-hero-orbit-three"></div>

            <div class="container">
                <div class="books-hero-breadcrumb">
                    <a href="/">Home</a>
                    <span>/</span>
                    <span>Books</span>
                </div>

                <div class="books-hero-grid">
                    <div class="books-hero-content">
                        <div class="books-hero-eyebrow">
                            <span class="books-hero-eyebrow-line"></span>
                            <i class="bi bi-book-half"></i>
                            <span>SECOND BOOK COLLECTION</span>
                        </div>

                        <h1 class="books-hero-title">
                            Every Book
                            <span class="books-hero-title-accent">Has Another</span>
                            <span class="books-hero-title-last">Chapter.</span>
                        </h1>

                        <p class="books-hero-description">
                            Discover pre-loved books, uncover hidden gems and
                            give unforgettable stories another chance to be read.
                            Your next favourite book is waiting to be found.
                        </p>

                        <div class="books-hero-actions">
                            <a href="#books-collection" class="books-hero-btn">
                                <span>Explore Collection</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                            <a
                                href="#available-books"
                                class="books-hero-secondary-btn"
                            >
                                <span>Discover Books</span>
                                <i class="bi bi-arrow-down"></i>
                            </a>
                        </div>

                        <div class="books-hero-footer">
                            <div class="books-hero-trust-icons">
                                <span><i class="bi bi-book"></i></span>
                                <span><i class="bi bi-heart"></i></span>
                                <span><i class="bi bi-arrow-repeat"></i></span>
                            </div>

                            <div class="books-hero-trust-copy">
                                <strong>Stories worth sharing</strong>
                                <span>One book. Another beginning.</span>
                            </div>

                            <div class="books-hero-footer-divider"></div>

                            <div class="books-hero-count">
                                <strong>{{ meta.total }}+</strong>
                                <span>Books to discover</span>
                            </div>
                        </div>
                    </div>

                    <div class="books-hero-visual">
                        <div class="books-hero-visual-glow"></div>

                        <div class="books-hero-visual-label">
                            <span>THE ART OF READING</span>
                            <i class="bi bi-arrow-down-right"></i>
                        </div>

                        <div class="books-hero-image-frame">
                            <img
                                src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=85"
                                alt="A beautiful collection of books"
                            >

                            <div class="books-hero-image-overlay"></div>

                            <div class="books-hero-image-caption">
                                <span>CHAPTER NO.</span>
                                <strong>01</strong>
                            </div>
                        </div>

                        <div class="books-hero-ring books-hero-ring-one"></div>
                        <div class="books-hero-ring books-hero-ring-two"></div>

                        <div class="books-hero-floating-card books-hero-floating-top">
                            <div class="books-hero-floating-icon">
                                <i class="bi bi-stars"></i>
                            </div>

                            <div>
                                <strong>Find Your Next Read</strong>
                                <span>Something worth discovering</span>
                            </div>
                        </div>

                        <div class="books-hero-book-stack">
                            <div
                                v-for="(book, index) in heroBooks"
                                :key="book.id"
                                :class="`books-hero-stack-book books-hero-stack-book-${index + 1}`"
                            >
                                <img
                                    v-if="imageUrl(book)"
                                    :src="imageUrl(book)"
                                    :alt="book.title"
                                    loading="lazy"
                                >

                                <div v-else class="books-hero-stack-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>
                            </div>
                        </div>

                        <div class="books-hero-floating-card books-hero-floating-bottom">
                            <div class="books-hero-bottom-icon">
                                <i class="bi bi-bookmark-heart"></i>
                            </div>

                            <div>
                                <strong>Give Books New Life</strong>
                                <span>Pass the story forward</span>
                            </div>
                        </div>

                        <div class="books-hero-stamp">
                            <span>READ</span>
                            <span>LOVE</span>
                            <span>REPEAT</span>
                        </div>

                        <div class="books-hero-number">01</div>

                        <div class="books-hero-scroll">
                            <span>SCROLL TO DISCOVER</span>
                            <div class="books-hero-scroll-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics -->
        <section class="books-stats-section">
            <div class="container">
                <div class="books-stats">
                    <div class="books-stat-item">
                        <div class="books-stat-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <strong>{{ meta.total }}+</strong>
                            <span>Books Available</span>
                        </div>
                    </div>

                    <div class="books-stat-item">
                        <div class="books-stat-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <strong>Pre-Loved</strong>
                            <span>Ready for New Readers</span>
                        </div>
                    </div>

                    <div class="books-stat-item">
                        <div class="books-stat-icon">
                            <i class="bi bi-tags"></i>
                        </div>
                        <div>
                            <strong>Great Value</strong>
                            <span>Affordable Books</span>
                        </div>
                    </div>

                    <div class="books-stat-item">
                        <div class="books-stat-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <div>
                            <strong>One More</strong>
                            <span>Chapter to Every Book</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Book collection -->
        <section class="books-section" id="books-collection">
            <div class="container">
                <div class="books-section-heading">
                    <div>
                        <span class="books-results-label">EXPLORE OUR COLLECTION</span>
                        <h2>Find Your Next <em>Favorite Book.</em></h2>
                    </div>

                    <p>
                        Search through our growing collection of pre-loved
                        books and discover something special.
                    </p>
                </div>

                <!-- Filters -->
                <div class="books-toolbar">
                    <div class="books-toolbar-header">
                        <div class="books-toolbar-title">
                            <div class="toolbar-icon">
                                <i class="bi bi-sliders2"></i>
                            </div>

                            <div>
                                <strong>Find a Book</strong>
                                <span>Search and filter our collection</span>
                            </div>
                        </div>

                        <div class="books-total">
                            <span>{{ meta.total }}</span> books
                        </div>
                    </div>

                    <form
                        class="books-filter-form"
                        id="books-filter-form"
                        @submit.prevent="submitFilters"
                    >
                        <div class="books-filter-column books-search-column">
                            <label for="book-search">Search</label>

                            <div class="books-search">
                                <i class="bi bi-search"></i>
                                <input
                                    id="book-search"
                                    v-model="filters.search"
                                    type="text"
                                    name="search"
                                    placeholder="Search books by title..."
                                >
                            </div>
                        </div>

                        <div class="books-filter-column">
                            <label for="book-category">Category</label>

                            <select
                                id="book-category"
                                v-model="filters.category"
                                name="category"
                                class="books-select"
                            >
                                <option value="">All Categories</option>

                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div class="books-filter-column">
                            <label for="book-condition">Condition</label>

                            <select
                                id="book-condition"
                                v-model="filters.condition"
                                name="condition"
                                class="books-select"
                            >
                                <option value="">All Conditions</option>
                                <option value="new">New</option>
                                <option value="like_new">Like New</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                            </select>
                        </div>

                        <div class="books-filter-column">
                            <label for="book-sort">Sort By</label>

                            <select
                                id="book-sort"
                                v-model="filters.sort"
                                name="sort"
                                class="books-select"
                            >
                                <option value="">Default</option>
                                <option value="newest">Newest</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </div>

                        <div class="books-filter-actions">
                            <button
                                type="submit"
                                class="books-filter-btn"
                                id="books-search-btn"
                            >
                                <i class="bi bi-search"></i>
                                <span>Search</span>
                            </button>

                            <button
                                v-if="filters.search || filters.condition || filters.sort || filters.category"
                                type="button"
                                class="books-clear-filter"
                                aria-label="Clear filters"
                                title="Clear filters"
                                @click="clearFilters"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Clear</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results heading -->
                <div class="books-results-header" id="available-books">
                    <div>
                        <span class="books-results-label">OUR COLLECTION</span>
                        <h2>Available <em>Books</em></h2>
                    </div>

                    <div class="books-results-meta">
                        Showing
                        <strong>{{ firstItem }}</strong> –
                        <strong>{{ lastItem }}</strong>
                        of
                        <strong>{{ meta.total }}</strong>
                    </div>
                </div>

                <!-- Loading and error states -->
                <div v-if="error" class="alert alert-warning" role="alert">
                    {{ error }}

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-dark"
                        @click="loadBooks()"
                    >
                        Try again
                    </button>
                </div>

                <div v-if="loading" class="books-loading" role="status">
                    Loading books…
                </div>

                <!-- Book cards -->
                <div v-else-if="books.length" class="row g-4">
                    <div
                        v-for="book in books"
                        :key="book.id"
                        class="col-6 col-sm-6 col-lg-4 col-xl-3"
                    >
                        <article class="book-card">
                            <div class="book-cover">
                                <a
                                    :href="detailUrl(book.id)"
                                    class="book-cover-link"
                                >
                                    <img
                                        v-if="imageUrl(book)"
                                        :src="imageUrl(book)"
                                        :alt="book.title"
                                        loading="lazy"
                                    >

                                    <div
                                        v-else
                                        class="book-cover-placeholder"
                                    >
                                        <i class="bi bi-book"></i>
                                        <span>No Cover</span>
                                    </div>
                                </a>

                                <span class="book-condition">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ conditionLabel(book.condition) }}
                                </span>

                                <span
                                    v-if="hasDiscount(book)"
                                    class="book-discount"
                                >
                                    {{ book.discount_label }}
                                </span>

                                <button
                                    v-if="isAuthenticated"
                                    type="button"
                                    :class="[
                                        'book-wishlist',
                                        { active: book.is_wishlisted },
                                    ]"
                                    :aria-label="
                                        book.is_wishlisted
                                            ? 'Remove from wishlist'
                                            : 'Add to wishlist'
                                    "
                                    :title="
                                        book.is_wishlisted
                                            ? 'Remove from wishlist'
                                            : 'Add to wishlist'
                                    "
                                    @click="toggleWishlist(book)"
                                >
                                    <i
                                        :class="
                                            book.is_wishlisted
                                                ? 'bi bi-heart-fill'
                                                : 'bi bi-heart'
                                        "
                                    ></i>
                                </button>

                                <a
                                    v-else
                                    :href="loginUrl"
                                    class="book-wishlist"
                                    aria-label="Login to add to wishlist"
                                    title="Login to add to wishlist"
                                >
                                    <i class="bi bi-heart"></i>
                                </a>

                                <div class="book-cover-hover">
                                    <span>Discover this book</span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </div>
                            </div>

                            <div class="book-content">
                                <span class="book-mini-label">SECONDBOOK</span>

                                <h3>{{ book.title }}</h3>

                                <p class="book-author">
                                    <i class="bi bi-person"></i>
                                    {{ book.author?.name || 'Unknown Author' }}
                                </p>

                                <div class="book-card-divider"></div>

                                <div class="book-bottom">
                                    <div>
                                        <span class="book-price-label">PRICE</span>

                                        <div
                                            v-if="hasDiscount(book)"
                                            class="book-price-wrap"
                                        >
                                            <span class="book-old-price">
                                                ${{ Number(book.price).toFixed(2) }}
                                            </span>

                                            <strong
                                                class="book-price book-discounted-price"
                                            >
                                                ${{ Number(book.discounted_price).toFixed(2) }}
                                            </strong>
                                        </div>

                                        <strong
                                            v-else
                                            class="book-price"
                                        >
                                            ${{ Number(book.price).toFixed(2) }}
                                        </strong>
                                    </div>

                                    <div class="book-card-actions">
                                        <a
                                            :href="detailUrl(book.id)"
                                            class="book-view-btn"
                                        >
                                            View
                                            <i class="bi bi-arrow-right"></i>
                                        </a>

                                        <button
                                            v-if="isAuthenticated && Number(book.stock) > 0"
                                            type="button"
                                            class="book-cart-btn"
                                            title="Add to cart"
                                            aria-label="Add to cart"
                                            @click="addToCart(book, $event)"
                                        >
                                            <i class="bi bi-bag-plus"></i>
                                        </button>

                                        <a
                                            v-else-if="!isAuthenticated"
                                            :href="loginUrl"
                                            class="book-cart-btn"
                                            title="Login to add to cart"
                                            aria-label="Login to add this book to cart"
                                        >
                                            <i class="bi bi-bag-plus"></i>
                                        </a>

                                        <button
                                            v-else
                                            type="button"
                                            class="book-cart-btn book-cart-btn-disabled"
                                            disabled
                                            aria-label="Out of stock"
                                        >
                                            <i class="bi bi-bag-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- Empty state -->
                <div
                    v-else-if="!loading"
                    class="books-empty"
                >
                    <div class="books-empty-visual">
                        <div class="empty-book empty-book-one"></div>
                        <div class="empty-book empty-book-two"></div>
                        <div class="empty-book empty-book-three"></div>
                        <i class="bi bi-search"></i>
                    </div>

                    <span>NOTHING FOUND</span>
                    <h3>No Books Found</h3>

                    <p>
                        We couldn't find any books matching your current filters.
                    </p>

                    <button
                        type="button"
                        class="books-clear-btn"
                        @click="clearFilters"
                    >
                        <i class="bi bi-arrow-counterclockwise"></i>
                        View All Books
                    </button>
                </div>

                <!-- Pagination -->
                <div
                    v-if="!loading && meta.last_page > 1"
                    class="books-pagination"
                >
                    <div class="books-pagination-info">
                        Showing
                        <strong>{{ firstItem }}</strong> to
                        <strong>{{ lastItem }}</strong> of
                        <strong>{{ meta.total }}</strong> results
                    </div>

                    <div class="books-pagination-links">
                        <button
                            type="button"
                            :class="[
                                'books-page-link',
                                { disabled: meta.current_page <= 1 },
                            ]"
                            aria-label="Previous page"
                            :disabled="meta.current_page <= 1"
                            @click="navigate({ page: meta.current_page - 1 })"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <button
                            v-if="meta.current_page > 3"
                            type="button"
                            class="books-page-link"
                            @click="navigate({ page: 1 })"
                        >
                            1
                        </button>

                        <span
                            v-if="meta.current_page > 4"
                            class="books-page-ellipsis"
                        >
                            ...
                        </span>

                        <template
                            v-for="page in pageNumbers"
                            :key="page"
                        >
                            <span
                                v-if="page === meta.current_page"
                                class="books-page-link active"
                            >
                                {{ page }}
                            </span>

                            <button
                                v-else
                                type="button"
                                class="books-page-link"
                                @click="navigate({ page })"
                            >
                                {{ page }}
                            </button>
                        </template>

                        <span
                            v-if="meta.current_page < meta.last_page - 3"
                            class="books-page-ellipsis"
                        >
                            ...
                        </span>

                        <button
                            v-if="meta.current_page < meta.last_page - 2"
                            type="button"
                            class="books-page-link"
                            @click="navigate({ page: meta.last_page })"
                        >
                            {{ meta.last_page }}
                        </button>

                        <button
                            type="button"
                            :class="[
                                'books-page-link',
                                { disabled: meta.current_page >= meta.last_page },
                            ]"
                            aria-label="Next page"
                            :disabled="meta.current_page >= meta.last_page"
                            @click="navigate({ page: meta.current_page + 1 })"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seller call to action -->
        <section class="books-sell-section">
            <div class="container">
                <div class="books-sell-card">
                    <div class="books-sell-image">
                        <img
                            src="https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=900&q=85"
                            alt="Books"
                            loading="lazy"
                        >
                    </div>

                    <div class="books-sell-content">
                        <span>GIVE YOUR BOOK ANOTHER CHAPTER</span>

                        <h2>
                            Have Books Sitting <em>Unused?</em>
                        </h2>

                        <p>
                            Sell your books on SecondBook and let someone else
                            discover the story you once loved.
                        </p>

                        <a href="/seller" class="books-sell-btn">
                            Sell Your Books
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Notification -->
        <div
            v-if="notice"
            class="cart-success-alert is-visible"
            role="status"
        >
            <div class="cart-alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="cart-alert-content">
                <strong>SecondBook</strong>
                <span>{{ notice }}</span>
            </div>

            <button
                type="button"
                class="cart-alert-close"
                aria-label="Close"
                @click="notice = ''"
            >
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
</template>
