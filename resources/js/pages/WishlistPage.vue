<script setup>
import { computed, onMounted, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    booksUrl: { type: String, required: true },
    detailsUrl: { type: String, required: true },
});

const books = ref([]);
const meta = ref({ current_page: 1, last_page: 1, per_page: 12, total: 0 });
const loading = ref(true);
const error = ref('');
const notice = ref('');
const removing = ref(null);
const pageNumbers = computed(() => {
    const start = Math.max(1, meta.value.current_page - 2);
    const end = Math.min(meta.value.last_page, meta.value.current_page + 2);
    return Array.from({ length: end - start + 1 }, (_, index) => start + index);
});

function detailsUrl(bookId) {
    return props.detailsUrl.replace('__BOOK__', encodeURIComponent(bookId));
}

function coverUrl(book) {
    const path = book?.cover_url || book?.cover;
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (/^(storage|uploads)\//.test(path)) return `/${path}`;
    return `/storage/${String(path).replace(/^\/+/, '')}`;
}

function conditionLabel(condition) {
    return String(condition || 'good')
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (letter) => letter.toUpperCase());
}

function description(book) {
    const text = String(book.description || '').replace(/<[^>]*>/g, '').trim();
    return text.length > 100 ? `${text.slice(0, 97).trimEnd()}…` : text;
}

function currency(value) {
    return Number(value || 0).toFixed(2);
}

async function loadWishlist(page = 1) {
    loading.value = true;
    error.value = '';
    try {
        const response = await accountService.getWishlist({ page });
        books.value = (response.data || [])
            .filter((item) => item.book)
            .map((item) => ({ ...item.book, wishlist_id: item.id }));
        meta.value = response.meta || meta.value;
    } catch (exception) {
        error.value = exception.response?.data?.message
            || 'Your wishlist could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}

async function removeBook(book) {
    if (removing.value !== null) return;
    removing.value = book.id;
    error.value = '';
    try {
        await accountService.removeFromWishlist(book.id);
        notice.value = `${book.title} was removed from your wishlist.`;
        await loadWishlist(meta.value.current_page);
        if (!books.value.length && meta.value.current_page > 1) {
            await loadWishlist(meta.value.current_page - 1);
        }
    } catch (exception) {
        error.value = exception.response?.data?.message
            || 'This book could not be removed. Please try again.';
    } finally {
        removing.value = null;
    }
}

onMounted(() => loadWishlist());
</script>

<template>
    <main class="sb-wishlist-page">
        <div class="wishlist-ambient" aria-hidden="true">
            <div class="wishlist-ambient-orb wishlist-orb-one"></div>
            <div class="wishlist-ambient-orb wishlist-orb-two"></div>
            <div class="wishlist-ambient-orb wishlist-orb-three"></div>
            <div class="wishlist-ambient-grid"></div>
            <div class="wishlist-ambient-grain"></div>
        </div>

        <section class="wishlist-hero">
            <div class="container">
                <nav class="wishlist-breadcrumb" aria-label="Breadcrumb">
                    <a href="/frontend"><i class="bi bi-house"></i><span>Home</span></a>
                    <i class="bi bi-chevron-right breadcrumb-divider"></i>
                    <span class="breadcrumb-current">Wishlist</span>
                </nav>
                <div class="wishlist-hero-grid">
                    <div class="wishlist-hero-content">
                        <div class="wishlist-eyebrow">
                            <span class="wishlist-eyebrow-icon"><i class="bi bi-heart-fill"></i></span>
                            <span>YOUR PERSONAL LIBRARY</span>
                            <span class="wishlist-eyebrow-line"></span>
                        </div>
                        <h1 class="wishlist-hero-title">Some stories <span>stay with you.</span></h1>
                        <p class="wishlist-hero-description">
                            A little corner for the books that caught your eye.
                            Keep your next great discovery close, until the moment
                            feels right to turn the first page.
                        </p>
                        <div class="wishlist-hero-bottom">
                            <a :href="booksUrl" class="wishlist-hero-cta">
                                <span>Discover more books</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                            <div class="wishlist-hero-note">
                                <i class="bi bi-book-half"></i>
                                <span>A collection of possibilities</span>
                            </div>
                        </div>
                    </div>
                    <div class="wishlist-hero-art" aria-hidden="true">
                        <div class="wishlist-art-orbit wishlist-art-orbit-one"></div>
                        <div class="wishlist-art-orbit wishlist-art-orbit-two"></div>
                        <div class="wishlist-art-star wishlist-star-one">✦</div>
                        <div class="wishlist-art-star wishlist-star-two">✧</div>
                        <div class="wishlist-art-star wishlist-star-three">✦</div>
                        <div class="wishlist-art-book wishlist-art-book-back">
                            <div class="wishlist-art-book-spine"></div>
                            <div class="wishlist-art-book-lines"></div>
                        </div>
                        <div class="wishlist-art-book wishlist-art-book-front">
                            <div class="wishlist-art-book-cover">
                                <span class="wishlist-art-cover-mark">S</span>
                                <span class="wishlist-art-cover-rule"></span>
                                <span class="wishlist-art-cover-rule"></span>
                                <span class="wishlist-art-cover-caption">A WORLD<br>OF STORIES</span>
                                <span class="wishlist-art-cover-flower">✳</span>
                            </div>
                        </div>
                        <div class="wishlist-art-heart"><i class="bi bi-heart-fill"></i></div>
                        <div class="wishlist-art-caption">
                            <span class="wishlist-art-caption-line"></span>
                            <span>Made of stories</span>
                        </div>
                        <div class="wishlist-collection-chip">
                            <span class="wishlist-chip-icon"><i class="bi bi-bookmark-heart-fill"></i></span>
                            <span class="wishlist-chip-copy">
                                <small>YOUR COLLECTION</small>
                                <strong>{{ meta.total }} {{ meta.total === 1 ? 'book' : 'books' }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="wishlist-hero-footer">
                    <span>COLLECT THE STORIES THAT MOVE YOU</span>
                    <span class="wishlist-hero-footer-line"></span>
                    <span class="wishlist-hero-footer-number">SB / 01</span>
                </div>
            </div>
        </section>

        <section class="wishlist-section">
            <div class="container">
                <div v-if="notice" class="wishlist-alert wishlist-alert-success" role="status">
                    <span class="wishlist-alert-icon"><i class="bi bi-check2"></i></span>
                    <div class="wishlist-alert-copy"><strong>All good</strong><span>{{ notice }}</span></div>
                    <span class="wishlist-alert-accent"></span>
                </div>
                <div v-if="error" class="wishlist-alert wishlist-alert-error" role="alert">
                    <span class="wishlist-alert-icon"><i class="bi bi-exclamation-lg"></i></span>
                    <div class="wishlist-alert-copy"><strong>Something went wrong</strong><span>{{ error }}</span></div>
                    <span class="wishlist-alert-accent"></span>
                </div>

                <div v-if="!loading && books.length" class="wishlist-topbar">
                    <div class="wishlist-topbar-heading">
                        <div class="wishlist-section-kicker"><span class="wishlist-kicker-dot"></span>THE BOOKS YOU CHOSE</div>
                        <h2>Your <span>collection.</span></h2>
                        <p>Every saved title has a place here. Take your time finding your next read.</p>
                    </div>
                    <div class="wishlist-topbar-side">
                        <div class="wishlist-total-panel">
                            <span class="wishlist-total-label">COLLECTION SIZE</span>
                            <div class="wishlist-total-number"><strong>{{ meta.total }}</strong><span>{{ meta.total === 1 ? 'TITLE' : 'TITLES' }}</span></div>
                            <span class="wishlist-total-rule"></span>
                        </div>
                        <a :href="booksUrl" class="wishlist-browse-btn"><span>Explore the shelves</span><i class="bi bi-arrow-up-right"></i></a>
                    </div>
                </div>

                <div v-if="!loading && books.length" class="wishlist-results-line">
                    <span><i class="bi bi-grid-3x3-gap"></i> YOUR SAVED TITLES</span>
                    <span class="wishlist-results-count">{{ books.length ? ((meta.current_page - 1) * meta.per_page + 1) : 0 }}–{{ Math.min(meta.current_page * meta.per_page, meta.total) }} <span class="wishlist-results-muted">OF</span> {{ meta.total }}</span>
                </div>

                <div v-if="loading" class="wishlist-empty" role="status">Loading your saved books…</div>
                <div v-else-if="books.length" class="wishlist-grid">
                    <article v-for="(book, index) in books" :key="book.id" class="wishlist-card">
                        <div class="wishlist-card-visual">
                            <a :href="detailsUrl(book.id)" class="wishlist-cover-link" :aria-label="`View ${book.title}`">
                                <div class="wishlist-cover">
                                    <div class="wishlist-cover-light"></div>
                                    <img v-if="coverUrl(book)" :src="coverUrl(book)" :alt="book.title" loading="lazy" @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'">
                                    <div v-if="coverUrl(book)" class="wishlist-no-cover" style="display: none"><i class="bi bi-book-half"></i><span>Cover unavailable</span></div>
                                    <div v-else class="wishlist-no-cover"><i class="bi bi-book-half"></i><span>Cover unavailable</span></div>
                                    <span class="wishlist-cover-corner"></span>
                                </div>
                            </a>
                            <span class="wishlist-cover-index">SB <span>/</span> {{ String((meta.current_page - 1) * meta.per_page + index + 1).padStart(2, '0') }}</span>
                            <span class="wishlist-condition-badge"><i class="bi bi-stars"></i>{{ conditionLabel(book.condition) }}</span>
                            <form class="wishlist-heart-form" @submit.prevent="removeBook(book)">
                                <button type="submit" class="wishlist-heart-btn" :aria-label="`Remove ${book.title} from wishlist`" title="Remove from wishlist" :disabled="removing === book.id">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </form>
                            <div v-if="Number(book.stock) <= 0" class="wishlist-out-overlay">
                                <span class="wishlist-out-icon"><i class="bi bi-hourglass"></i></span>
                                <span>Currently unavailable</span>
                            </div>
                        </div>
                        <div class="wishlist-card-content">
                            <div class="wishlist-card-meta">
                                <span class="wishlist-category"><i class="bi bi-bookmark"></i>{{ book.category?.name || 'Book' }}</span>
                                <span :class="['wishlist-stock', Number(book.stock) > 0 ? 'available' : 'unavailable']">
                                    <span class="wishlist-stock-dot"></span>{{ Number(book.stock) > 0 ? 'In stock' : 'Out of stock' }}
                                </span>
                            </div>
                            <h3 class="wishlist-book-title"><a :href="detailsUrl(book.id)">{{ book.title }}</a></h3>
                            <div class="wishlist-author">
                                <span class="wishlist-author-icon"><i class="bi bi-person"></i></span>
                                <span class="wishlist-author-copy"><small>WRITTEN BY</small><strong>{{ book.author?.name || 'Unknown Author' }}</strong></span>
                            </div>
                            <p :class="['wishlist-description', { 'wishlist-description-empty': !description(book) }]">
                                {{ description(book) || 'A story waiting to be discovered.' }}
                            </p>
                            <div class="wishlist-card-divider"></div>
                            <div class="wishlist-price-row">
                                <div class="wishlist-price-info">
                                    <span class="wishlist-price-label">CURRENT PRICE</span>
                                    <strong class="wishlist-price"><span class="wishlist-currency">$</span>{{ currency(book.price) }}</strong>
                                </div>
                                <div v-if="Number(book.stock) > 0" class="wishlist-availability">
                                    <i class="bi bi-box-seam"></i><span>{{ book.stock }} {{ Number(book.stock) === 1 ? 'copy' : 'copies' }}</span>
                                </div>
                            </div>
                            <div class="wishlist-actions">
                                <a :href="detailsUrl(book.id)" class="wishlist-view-btn"><span>Explore this book</span><i class="bi bi-arrow-up-right"></i></a>
                                <form class="wishlist-delete-form" @submit.prevent="removeBook(book)">
                                    <button type="submit" class="wishlist-remove-btn" title="Remove from wishlist" :aria-label="`Remove ${book.title} from wishlist`" :disabled="removing === book.id">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
                <div v-else class="wishlist-empty">
                    <div class="wishlist-empty-art" aria-hidden="true">
                        <span class="wishlist-empty-orbit wishlist-empty-orbit-one"></span>
                        <span class="wishlist-empty-orbit wishlist-empty-orbit-two"></span>
                        <span class="wishlist-empty-star wishlist-empty-star-one">✦</span>
                        <span class="wishlist-empty-star wishlist-empty-star-two">✧</span>
                        <div class="wishlist-empty-book">
                            <div class="wishlist-empty-book-cover">
                                <span class="wishlist-empty-book-mark">S</span>
                                <span class="wishlist-empty-book-rule"></span>
                                <i class="bi bi-heart"></i>
                            </div>
                        </div>
                        <div class="wishlist-empty-heart"><i class="bi bi-heart-fill"></i></div>
                    </div>
                    <div class="wishlist-empty-kicker"><span></span>A LITTLE SPACE FOR BIG STORIES<span></span></div>
                    <h2>Your next favorite <span>is still out there.</span></h2>
                    <p>There are wonderful stories waiting to find you. Explore the shelves, follow your curiosity, and save the books you would love to return to.</p>
                    <div class="wishlist-empty-actions">
                        <a :href="booksUrl" class="wishlist-empty-btn"><span>Explore the collection</span><i class="bi bi-arrow-up-right"></i></a>
                        <a href="/frontend" class="wishlist-empty-home-btn"><i class="bi bi-house"></i><span>Return home</span></a>
                    </div>
                    <div class="wishlist-empty-footnote"><i class="bi bi-stars"></i>EVERY GREAT COLLECTION STARTS WITH ONE BOOK</div>
                </div>

                <nav v-if="!loading && meta.last_page > 1" class="wishlist-pagination" aria-label="Wishlist pages">
                    <button type="button" :disabled="meta.current_page <= 1" @click="loadWishlist(meta.current_page - 1)">Previous</button>
                    <button v-for="page in pageNumbers" :key="page" type="button" :aria-current="page === meta.current_page ? 'page' : undefined" @click="loadWishlist(page)">{{ page }}</button>
                    <button type="button" :disabled="meta.current_page >= meta.last_page" @click="loadWishlist(meta.current_page + 1)">Next</button>
                </nav>
                <div v-if="!loading && books.length" class="wishlist-bottom-note">
                    <span class="wishlist-bottom-note-icon"><i class="bi bi-book-half"></i></span>
                    <p>Good books are worth coming back to. <a :href="booksUrl">Find another story.</a></p>
                    <span class="wishlist-bottom-note-mark">SB.</span>
                </div>
            </div>
        </section>
    </main>
</template>
