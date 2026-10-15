
<template>
    <main class="catalog">
        <!-- Hero -->
        <section class="catalog-hero">
            <div class="hero-pattern"></div>
            <div class="hero-orbit hero-orbit-one"></div>
            <div class="hero-orbit hero-orbit-two"></div>

            <div class="hero-inner">
                <div class="hero-content">
                    <nav class="hero-breadcrumb">
                        <span>SecondBook</span>
                        <i class="bi bi-chevron-right"></i>
                        <span>Kitab kataloqu</span>
                    </nav>

                    <p class="hero-eyebrow">
                        <span></span>
                        SECONDBOOK MARKETPLACE
                    </p>

                    <h1>
                        Every Book Has
                        <span>Another Chapter.</span>
                    </h1>

                    <p class="hero-description">
                        Yeni hekayələr kəşf et, sevimli müəlliflərini tap
                        və növbəti kitabını SecondBook kolleksiyasından seç.
                    </p>

                    <div class="hero-actions">
                        <button class="hero-primary" @click="scrollToBooks">
                            Kitabları kəşf et
                            <i class="bi bi-arrow-down-right"></i>
                        </button>

                        <button class="hero-secondary" @click="resetFilters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Filtrləri sıfırla
                        </button>
                    </div>

                    <div class="hero-trust">
                        <div class="trust-icon">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div>
                            <strong>{{ total }} kitab</strong>
                            <span>Kəşf etməyə hazır kolleksiya</span>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="visual-label">
                        <span></span>
                        THE READING COLLECTION
                    </div>

                    <div class="visual-glow"></div>
                    <div class="visual-ring ring-one"></div>
                    <div class="visual-ring ring-two"></div>

                    <div class="main-book-frame">
                        <img
                            v-if="featuredBook?.cover_url"
                            :src="featuredBook.cover_url"
                            :alt="featuredBook.title"
                        >

                        <div v-else class="main-book-placeholder">
                            <i class="bi bi-book-half"></i>
                            <span>YOUR NEXT CHAPTER</span>
                        </div>

                        <div class="frame-caption">
                            <span>DISCOVER SOMETHING NEW</span>
                            <strong>Read. Explore.</strong>
                        </div>
                    </div>

                    <div
                        v-for="(book, index) in stackBooks"
                        :key="`stack-${book.id}`"
                        class="stack-book"
                        :class="`stack-book-${index + 1}`"
                    >
                        <img
                            v-if="book.cover_url"
                            :src="book.cover_url"
                            :alt="book.title"
                        >
                        <div v-else class="stack-placeholder">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>

                    <div class="floating-card floating-top">
                        <div class="floating-icon">
                            <i class="bi bi-bookmark-heart"></i>
                        </div>
                        <div>
                            <strong>Your next favorite</strong>
                            <span>Just one page away</span>
                        </div>
                    </div>

                    <div class="floating-card floating-bottom">
                        <div class="floating-icon">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div>
                            <strong>New discoveries</strong>
                            <span>Stories worth exploring</span>
                        </div>
                    </div>

                    <div class="visual-stamp">
                        <i class="bi bi-stars"></i>
                        <span>READ</span>
                        <span>MORE</span>
                    </div>

                    <span class="visual-number">01</span>
                </div>
            </div>

            <div class="hero-bottom-line">
                <span>TURN THE PAGE</span>
                <div></div>
                <span>FIND YOUR STORY</span>
            </div>
        </section>

        <!-- Statistics -->
        <section class="catalog-stats">
            <div class="catalog-container">
                <div class="stats-panel">
                    <article class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <strong>{{ total }}</strong>
                            <span>Kataloqdakı kitablar</span>
                        </div>
                    </article>

                    <article class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-grid"></i>
                        </div>
                        <div>
                            <strong>{{ categories.length }}</strong>
                            <span>Kateqoriyalar</span>
                        </div>
                    </article>

                    <article class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-tags"></i>
                        </div>
                        <div>
                            <strong>{{ discountedCount }}</strong>
                            <span>Endirimli kitablar</span>
                        </div>
                    </article>

                    <article class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <div>
                            <strong>{{ meta.current_page }} / {{ meta.last_page }}</strong>
                            <span>Mövcud səhifə</span>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- Book collection -->
        <section id="catalog-collection" class="catalog-section">
            <div class="catalog-container">
                <header class="section-heading">
                    <div>
                        <span class="section-label">
                            <i class="bi bi-stars"></i>
                            BOOK COLLECTION
                        </span>

                        <h2>
                            Hekayəni <em>buradan</em> başla.
                        </h2>
                    </div>

                    <p>
                        Kolleksiyaya nəzər sal, seçimlərini dəqiqləşdir
                        və sənə uyğun kitabı tap.
                    </p>
                </header>

                <!-- Filters -->
                <section class="filter-panel">
                    <div class="filter-heading">
                        <div class="filter-title">
                            <div class="filter-icon">
                                <i class="bi bi-sliders"></i>
                            </div>

                            <div>
                                <strong>Kitab axtarışı</strong>
                                <span>Kolleksiyanı öz zövqünə uyğunlaşdır</span>
                            </div>
                        </div>

                        <div class="total-badge">
                            <span>{{ total }}</span> nəticə
                        </div>
                    </div>

                    <div class="filter-form">
                        <div class="filter-field search-field">
                            <label for="catalog-search">Axtarış</label>

                            <div class="search-control">
                                <i class="bi bi-search"></i>
                                <input
                                    id="catalog-search"
                                    v-model="search"
                                    type="search"
                                    placeholder="Kitab, müəllif və ya ISBN..."
                                    @input="scheduleSearch"
                                >
                            </div>
                        </div>

                        <div class="filter-field">
                            <label for="catalog-category">Kateqoriya</label>

                            <select
                                id="catalog-category"
                                v-model="categoryId"
                                @change="applyFilters"
                            >
                                <option value="">Bütün kateqoriyalar</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="String(category.id)"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div class="filter-field">
                            <label for="catalog-condition">Kitabın vəziyyəti</label>

                            <select
                                id="catalog-condition"
                                v-model="condition"
                                @change="applyFilters"
                            >
                                <option value="">Bütün vəziyyətlər</option>
                                <option value="new">Yeni</option>
                                <option value="like_new">Yeni kimi</option>
                                <option value="good">Yaxşı</option>
                                <option value="fair">Qənaətbəxş</option>
                            </select>
                        </div>

                        <div class="filter-field">
                            <label for="catalog-sort">Sıralama</label>

                            <select
                                id="catalog-sort"
                                v-model="sort"
                                @change="applyFilters"
                            >
                                <option value="newest">Ən yenilər</option>
                                <option value="oldest">Ən köhnələr</option>
                                <option value="price_low">Əvvəlcə ucuz</option>
                                <option value="price_high">Əvvəlcə baha</option>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button
                                class="filter-submit"
                                type="button"
                                @click="applyFilters"
                            >
                                <i class="bi bi-search"></i>
                                Axtar
                            </button>

                            <button
                                class="filter-reset"
                                type="button"
                                @click="resetFilters"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Sıfırla
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Results -->
                <div class="results-heading">
                    <div>
                        <span class="results-label">
                            <span></span>
                            AVAILABLE BOOKS
                        </span>

                        <h3>Növbəti oxu seçimin</h3>
                    </div>

                    <span class="results-count">
                        <strong>{{ total }}</strong> kitab tapıldı
                    </span>
                </div>

                <div v-if="loading" class="catalog-message">
                    <div class="loading-spinner"></div>
                    <strong>Kitablar hazırlanır...</strong>
                    <span>Kolleksiya yüklənir, bir az gözlə.</span>
                </div>

                <div v-else-if="error" class="catalog-message error-message">
                    <div class="message-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <strong>Kitabları yükləmək mümkün olmadı</strong>
                    <span>{{ error }}</span>
                    <button class="empty-button" @click="loadBooks()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Yenidən yoxla
                    </button>
                </div>

                <div v-else-if="books.length === 0" class="catalog-message empty-message">
                    <div class="empty-visual">
                        <div class="empty-book empty-book-one"></div>
                        <div class="empty-book empty-book-two"></div>
                        <div class="empty-book empty-book-three"></div>
                        <i class="bi bi-search"></i>
                    </div>

                    <span class="empty-label">NO BOOKS FOUND</span>
                    <h3>Axtarış nəticəsi tapılmadı.</h3>
                    <p>
                        Başqa sözlə axtar və ya filtrləri sıfırlayaraq
                        bütün kolleksiyaya yenidən nəzər sal.
                    </p>

                    <button class="empty-button" @click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Bütün kitabları göstər
                    </button>
                </div>

                <div v-else class="books-grid">
                    <article
                        v-for="(book, index) in books"
                        :key="book.id"
                        class="book-card"
                        :style="{ '--card-index': index }"
                    >
                        <div class="book-cover-wrap">
                            <img
                                v-if="book.cover_url"
                                :src="book.cover_url"
                                :alt="book.title"
                                class="book-cover-image"
                                loading="lazy"
                            >

                            <div v-else class="book-cover-placeholder">
                                <i class="bi bi-book-half"></i>
                                <span>SECONDBOOK</span>
                            </div>

                            <span class="condition-badge">
                                <i class="bi bi-check-circle"></i>
                                {{ conditionLabel(book.condition) }}
                            </span>

                            <span
                                v-if="hasDiscount(book)"
                                class="discount-badge"
                            >
                                {{ book.discount_label || 'ENDİRİM' }}
                            </span>

                            <div class="cover-overlay">
                                <span>HƏR KİTABIN BİR HEKAYƏSİ VAR</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </div>
                        </div>

                        <div class="book-card-content">
                            <div class="book-category">
                                <span></span>
                                {{ book.category?.name || 'Kateqoriyasız' }}
                            </div>

                            <h3 :title="book.title">
                                {{ book.title }}
                            </h3>

                            <p class="book-author">
                                <i class="bi bi-pen"></i>
                                {{ book.author?.name || 'Müəllif göstərilməyib' }}
                            </p>

                            <div class="book-divider"></div>

                            <div class="book-bottom">
                                <div class="book-price-block">
                                    <span class="price-label">QİYMƏT</span>

                                    <div class="book-price-wrap">
                                        <strong
                                            :class="{
                                                'discounted-price': hasDiscount(book)
                                            }"
                                        >
                                            {{ formatPrice(book.discounted_price) }}
                                        </strong>

                                        <del v-if="hasDiscount(book)">
                                            {{ formatPrice(book.price) }}
                                        </del>
                                    </div>
                                </div>

                                <div class="book-stock">
                                    <i
                                        :class="book.stock > 0
                                            ? 'bi bi-box-seam'
                                            : 'bi bi-exclamation-circle'"
                                    ></i>
                                    <span>
                                        {{ book.stock > 0
                                            ? `${book.stock} ədəd`
                                            : 'Stokda yoxdur' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Pagination -->
                <nav
                    v-if="!loading && !error && meta.last_page > 1"
                    class="catalog-pagination"
                    aria-label="Kitab kataloqu səhifələri"
                >
                    <div class="pagination-info">
                        Səhifə
                        <strong>{{ meta.current_page }}</strong>
                        /
                        <strong>{{ meta.last_page }}</strong>
                    </div>

                    <div class="pagination-links">
                        <button
                            class="page-button page-arrow"
                            :disabled="meta.current_page <= 1"
                            aria-label="Əvvəlki səhifə"
                            @click="changePage(meta.current_page - 1)"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <button
                            v-for="page in visiblePages"
                            :key="page"
                            class="page-button"
                            :class="{ active: page === meta.current_page }"
                            :aria-current="page === meta.current_page ? 'page' : undefined"
                            @click="changePage(page)"
                        >
                            {{ page }}
                        </button>

                        <button
                            class="page-button page-arrow"
                            :disabled="meta.current_page >= meta.last_page"
                            aria-label="Növbəti səhifə"
                            @click="changePage(meta.current_page + 1)"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </nav>
            </div>
        </section>

        <!-- Sell CTA -->
        <section class="sell-section">
            <div class="catalog-container">
                <div class="sell-card">
                    <div class="sell-image">
                        <img
                            v-if="featuredBook?.cover_url"
                            :src="featuredBook.cover_url"
                            :alt="featuredBook.title"
                            loading="lazy"
                        >
                        <div v-else class="sell-image-placeholder">
                            <i class="bi bi-book-half"></i>
                        </div>
                    </div>

                    <div class="sell-content">
                        <span class="sell-eyebrow">
                            <i class="bi bi-stars"></i>
                            SHARE THE STORY
                        </span>

                        <h2>
                            Kitablarına
                            <em>yeni həyat</em>
                            ver.
                        </h2>

                        <p>
                            Oxuyub bitirdiyin kitabların başqa oxucuların
                            sevimli hekayəsinə çevrilməsinə imkan yarat.
                        </p>

                        <a href="/frontend/seller-application" class="sell-button">
                            Satıcı ol
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import bookService from '../services/bookService';

const books = ref([]);
const categories = ref([]);
const total = ref(0);
const loading = ref(false);
const error = ref('');

const search = ref('');
const categoryId = ref('');
const condition = ref('');
const sort = ref('newest');

const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 12,
    total: 0,
});

let searchTimer = null;
let requestVersion = 0;

const visiblePages = computed(() => {
    const current = meta.value.current_page;
    const last = meta.value.last_page;
    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    return Array.from(
        { length: end - start + 1 },
        (_, index) => start + index
    );
});

const featuredBook = computed(() => books.value[0] || null);

const stackBooks = computed(() => books.value.slice(1, 4));

const discountedCount = computed(() =>
    books.value.filter(book => hasDiscount(book)).length
);

async function loadBooks(page = 1) {
    const version = ++requestVersion;

    loading.value = true;
    error.value = '';

    try {
        const response = await bookService.getBooks({
            search: search.value.trim() || undefined,
            category_id: categoryId.value || undefined,
            condition: condition.value || undefined,
            sort: sort.value,
            page,
        });

        if (version !== requestVersion) return;

        books.value = response.data;
        categories.value = response.categories;
        meta.value = response.meta;
        total.value = response.meta.total;
    } catch (err) {
        if (version !== requestVersion) return;

        console.error('Kitablar yüklənmədi:', err);
        error.value = 'İnternet bağlantısını yoxla və yenidən cəhd et.';
    } finally {
        if (version === requestVersion) {
            loading.value = false;
        }
    }
}

function scheduleSearch() {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        loadBooks(1);
    }, 350);
}

function applyFilters() {
    clearTimeout(searchTimer);
    loadBooks(1);
}

function resetFilters() {
    clearTimeout(searchTimer);

    search.value = '';
    categoryId.value = '';
    condition.value = '';
    sort.value = 'newest';

    loadBooks(1);
}

function changePage(page) {
    if (page < 1 || page > meta.value.last_page) return;

    loadBooks(page);
    scrollToBooks();
}

function scrollToBooks() {
    document.getElementById('catalog-collection')?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
}

function formatPrice(value) {
    return `${Number(value ?? 0).toFixed(2)} AZN`;
}

function hasDiscount(book) {
    return Number(book.discounted_price) < Number(book.price);
}

function conditionLabel(value) {
    return {
        new: 'Yeni',
        like_new: 'Yeni kimi',
        good: 'Yaxşı',
        fair: 'Qənaətbəxş',
    }[value] || value || 'Göstərilməyib';
}

onMounted(() => loadBooks());

onUnmounted(() => {
    clearTimeout(searchTimer);
});
</script>

<style scoped>
.catalog {
    --bk-bg: #fbf8f4;
    --bk-bg-2: #f7f1ea;
    --bk-surface: #fff;
    --bk-surface-2: #fcf8f3;
    --bk-ink: #2a221c;
    --bk-text: #4a3f37;
    --bk-muted: #8f8379;
    --bk-faint: #b3a79d;
    --bk-line: #eee4da;
    --bk-line-strong: #e2d3c4;
    --bk-brown: #8b5e3c;
    --bk-brown-deep: #6b4529;
    --bk-gold: #c9a074;
    --bk-cream: #f7eee4;
    --bk-sale: #b0432d;
    --bk-ease: cubic-bezier(.22, 1, .36, 1);
    --bk-spring: cubic-bezier(.34, 1.56, .64, 1);

    overflow: hidden;
    background: var(--bk-bg);
    color: var(--bk-ink);
    font-family: "DM Sans", Arial, sans-serif;
}

.catalog *,
.catalog *::before,
.catalog *::after {
    box-sizing: border-box;
}

.catalog button,
.catalog input,
.catalog select {
    font: inherit;
}

.catalog button {
    cursor: pointer;
}

.catalog button:focus-visible,
.catalog input:focus-visible,
.catalog select:focus-visible,
.catalog a:focus-visible {
    outline: 3px solid rgba(139, 94, 60, .32);
    outline-offset: 3px;
}

.catalog-container {
    width: min(1280px, calc(100% - 48px));
    margin: 0 auto;
}

/* Hero */

.catalog-hero {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    padding: 38px 24px 88px;
    background:
        radial-gradient(ellipse at 85% 20%, rgba(185, 130, 93, .19), transparent 38%),
        linear-gradient(120deg, #211610 0%, #302016 54%, #211610 100%);
    color: #fff8f0;
}

.hero-pattern {
    position: absolute;
    z-index: -1;
    inset: 0;
    opacity: .12;
    background-image: radial-gradient(rgba(217, 177, 138, .55) .7px, transparent .7px);
    background-size: 24px 24px;
    mask-image: linear-gradient(90deg, transparent, #000 80%);
}

.hero-orbit {
    position: absolute;
    z-index: -1;
    border: 1px solid rgba(217, 177, 138, .14);
    border-radius: 50%;
    pointer-events: none;
}

.hero-orbit-one {
    top: -220px;
    right: -120px;
    width: 680px;
    height: 680px;
    animation: orbitSpin 65s linear infinite;
}

.hero-orbit-two {
    top: -130px;
    right: -35px;
    width: 510px;
    height: 510px;
    border-style: dashed;
    animation: orbitSpin 85s linear infinite reverse;
}

.hero-inner {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(380px, .88fr);
    align-items: center;
    gap: 48px;
    width: min(1280px, 100%);
    margin: 0 auto;
}

.hero-content {
    position: relative;
    z-index: 2;
    animation: fadeUp .8s var(--bk-ease) both;
}

.hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 44px;
    color: #c5b5a7;
    font-size: 11px;
}

.hero-breadcrumb i {
    color: var(--bk-gold);
    font-size: 9px;
}

.hero-breadcrumb span:last-child {
    color: #f1d6b8;
}

.hero-eyebrow {
    display: flex;
    align-items: center;
    gap: 11px;
    margin: 0 0 23px;
    color: #d9b18a;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2.7px;
}

.hero-eyebrow > span,
.visual-label > span {
    width: 27px;
    height: 1px;
    background: var(--bk-gold);
}

.hero-content h1 {
    max-width: 690px;
    margin: 0;
    color: #fff8f0;
    font-family: "Playfair Display", Georgia, serif;
    font-size: clamp(46px, 5.1vw, 72px);
    font-weight: 600;
    line-height: 1.08;
    letter-spacing: -2.3px;
}

.hero-content h1 span {
    display: block;
    margin-top: 5px;
    color: #d9b18a;
    font-style: italic;
    font-weight: 500;
}

.hero-description {
    max-width: 530px;
    margin: 25px 0 29px;
    color: #c5b5a7;
    font-size: 14px;
    line-height: 1.95;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.hero-primary,
.hero-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 51px;
    padding: 0 22px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
    transition:
        transform .35s var(--bk-ease),
        background .35s var(--bk-ease),
        box-shadow .35s var(--bk-ease);
}

.hero-primary {
    border: 1px solid #d9b18a;
    background: #d9b18a;
    color: #2b1b12;
}

.hero-primary:hover {
    transform: translateY(-3px);
    background: #efd0ac;
    box-shadow: 0 14px 30px rgba(0, 0, 0, .22);
}

.hero-primary i {
    transition: transform .35s var(--bk-ease);
}

.hero-primary:hover i {
    transform: translate(3px, 3px);
}

.hero-secondary {
    border: 1px solid rgba(255, 248, 240, .22);
    background: rgba(255, 255, 255, .035);
    color: #fff8f0;
}

.hero-secondary:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, .1);
}

.hero-trust {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-top: 38px;
}

.trust-icon {
    display: grid;
    width: 42px;
    height: 42px;
    place-items: center;
    border: 1px solid rgba(217, 177, 138, .35);
    border-radius: 50%;
    background: rgba(217, 177, 138, .1);
    color: #e4bf98;
    font-size: 17px;
}

.hero-trust strong,
.hero-trust span {
    display: block;
}

.hero-trust strong {
    color: #fff8f0;
    font-size: 13px;
}

.hero-trust span {
    margin-top: 4px;
    color: #b7a494;
    font-size: 11px;
}

/* Hero visual */

.hero-visual {
    position: relative;
    min-width: 0;
    min-height: 505px;
    animation: visualReveal .9s .12s var(--bk-ease) both;
}

.visual-label {
    position: absolute;
    z-index: 5;
    top: 3px;
    left: 10px;
    display: flex;
    align-items: center;
    gap: 9px;
    color: #d9b18a;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 2px;
}

.visual-glow {
    position: absolute;
    top: 50%;
    left: 54%;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: rgba(185, 130, 93, .13);
    filter: blur(55px);
    transform: translate(-50%, -50%);
    animation: glowPulse 5s ease-in-out infinite alternate;
}

.visual-ring {
    position: absolute;
    top: 50%;
    left: 54%;
    border: 1px solid rgba(217, 177, 138, .22);
    border-radius: 50%;
    transform: translate(-50%, -50%);
}

.ring-one {
    width: 360px;
    height: 360px;
}

.ring-two {
    width: 275px;
    height: 275px;
    border-style: dashed;
    opacity: .65;
}

.main-book-frame {
    position: absolute;
    z-index: 2;
    top: 42px;
    right: 35px;
    width: 61%;
    height: 380px;
    overflow: hidden;
    border: 1px solid rgba(255, 248, 240, .32);
    border-radius: 5px;
    background: linear-gradient(145deg, #765039, #352117);
    box-shadow: 0 35px 75px rgba(0, 0, 0, .4);
}

.main-book-frame > img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 1s var(--bk-ease);
}

.hero-visual:hover .main-book-frame > img {
    transform: scale(1.045);
}

.main-book-frame::after {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 48%, rgba(25, 15, 10, .8));
    content: "";
    pointer-events: none;
}

.main-book-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    width: 100%;
    height: 100%;
    color: #e0bd98;
}

.main-book-placeholder i {
    font-size: 70px;
}

.main-book-placeholder span {
    font-size: 9px;
    letter-spacing: 2px;
}

.frame-caption {
    position: absolute;
    z-index: 2;
    right: 20px;
    bottom: 21px;
    left: 20px;
}

.frame-caption span,
.frame-caption strong {
    display: block;
}

.frame-caption span {
    color: #e0bd98;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1.7px;
}

.frame-caption strong {
    margin-top: 8px;
    color: #fff8f0;
    font-family: "Playfair Display", Georgia, serif;
    font-size: 30px;
    font-weight: 500;
}

.stack-book {
    position: absolute;
    z-index: 4;
    bottom: 46px;
    width: 120px;
    height: 190px;
    overflow: hidden;
    border: 3px solid rgba(255, 248, 240, .9);
    border-radius: 3px;
    background: #493020;
    box-shadow: 0 20px 35px rgba(0, 0, 0, .36);
    transform-origin: bottom center;
    transition: transform .45s var(--bk-ease);
}

.stack-book img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.stack-book-1 {
    left: 12px;
    transform: rotate(-13deg);
}

.stack-book-2 {
    left: 53px;
    bottom: 61px;
    transform: rotate(0);
}

.stack-book-3 {
    left: 94px;
    transform: rotate(12deg);
}

.hero-visual:hover .stack-book-1 {
    transform: rotate(-18deg) translate(-5px, -5px);
}

.hero-visual:hover .stack-book-2 {
    transform: rotate(0) translateY(-10px);
}

.hero-visual:hover .stack-book-3 {
    transform: rotate(17deg) translate(5px, -4px);
}

.stack-placeholder {
    display: grid;
    width: 100%;
    height: 100%;
    place-items: center;
    background: linear-gradient(145deg, #765039, #352117);
    color: #d9b18a;
    font-size: 35px;
}

.floating-card {
    position: absolute;
    z-index: 6;
    display: flex;
    align-items: center;
    gap: 11px;
    max-width: calc(100% - 8px);
    padding: 12px 14px;
    border: 1px solid rgba(255, 248, 240, .16);
    border-radius: 5px;
    background: rgba(45, 30, 21, .91);
    box-shadow: 0 16px 40px rgba(0, 0, 0, .24);
    backdrop-filter: blur(14px);
    animation: floating 5s ease-in-out infinite;
}

.floating-top {
    top: 86px;
    left: -4px;
}

.floating-bottom {
    right: 0;
    bottom: 32px;
    animation-delay: .8s;
}

.floating-icon {
    display: grid;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    place-items: center;
    border-radius: 4px;
    background: rgba(217, 177, 138, .12);
    color: #d9b18a;
}

.floating-card strong,
.floating-card span {
    display: block;
    white-space: nowrap;
}

.floating-card strong {
    color: #fff8f0;
    font-size: 10px;
}

.floating-card span {
    margin-top: 4px;
    color: #c5b5a7;
    font-size: 9px;
}

.visual-stamp {
    position: absolute;
    z-index: 6;
    top: 8px;
    right: 2px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    width: 67px;
    height: 67px;
    border: 1px solid rgba(217, 177, 138, .6);
    border-radius: 50%;
    background: rgba(33, 22, 16, .82);
    color: #d9b18a;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1.3px;
    transform: rotate(10deg);
}

.visual-stamp i {
    font-size: 15px;
}

.visual-number {
    position: absolute;
    right: 0;
    bottom: 92px;
    color: rgba(217, 177, 138, .22);
    font-family: Georgia, serif;
    font-size: 64px;
    line-height: 1;
    pointer-events: none;
}

.hero-bottom-line {
    position: absolute;
    right: 24px;
    bottom: 29px;
    left: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 13px;
    color: rgba(217, 177, 138, .65);
    font-size: 8px;
    font-weight: 700;
    letter-spacing: 2px;
}

.hero-bottom-line > div {
    width: 45px;
    height: 1px;
    background: rgba(217, 177, 138, .35);
}

/* Statistics */

.catalog-stats {
    padding: 0 0 64px;
    background: linear-gradient(180deg, #f7f1ea, #fbf8f4);
}

.stats-panel {
    position: relative;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    overflow: hidden;
    border: 1px solid var(--bk-line);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(60, 40, 25, .04), 0 20px 48px rgba(60, 40, 25, .09);
    transform: translateY(-1px);
}

.stats-panel::before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--bk-brown), var(--bk-gold), var(--bk-brown));
    background-size: 200% 100%;
    content: "";
    animation: gradientMove 6s linear infinite;
}

.stat-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    min-height: 112px;
    padding: 24px;
    border-right: 1px solid var(--bk-line);
    transition: background .35s var(--bk-ease);
}

.stat-item:last-child {
    border-right: 0;
}

.stat-item:hover {
    background: var(--bk-surface-2);
}

.stat-icon {
    display: grid;
    flex: 0 0 48px;
    width: 48px;
    height: 48px;
    place-items: center;
    border-radius: 14px;
    background: linear-gradient(135deg, #f8ecdf, #f1e0cf);
    color: var(--bk-brown);
    font-size: 20px;
    transition: transform .4s var(--bk-spring);
}

.stat-item:hover .stat-icon {
    transform: rotate(-8deg) scale(1.08);
}

.stat-item strong,
.stat-item span {
    display: block;
}

.stat-item strong {
    overflow-wrap: anywhere;
    color: var(--bk-ink);
    font-size: 18px;
    font-weight: 800;
}

.stat-item span {
    margin-top: 5px;
    color: var(--bk-muted);
    font-size: 11px;
    line-height: 1.5;
}

/* Collection heading */

.catalog-section {
    scroll-margin-top: 28px;
    padding: 25px 0 100px;
    background:
        radial-gradient(760px 420px at 100% 0%, rgba(201, 160, 116, .14), transparent 60%),
        radial-gradient(640px 420px at 0% 45%, rgba(139, 94, 60, .055), transparent 60%),
        var(--bk-bg);
}

.section-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 35px;
    margin-bottom: 42px;
}

.section-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border: 1px solid var(--bk-line-strong);
    border-radius: 100px;
    background: #fff;
    color: var(--bk-brown);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.8px;
}

.section-label i {
    color: var(--bk-gold);
}

.section-heading h2 {
    margin: 16px 0 0;
    color: var(--bk-ink);
    font-family: "Playfair Display", Georgia, serif;
    font-size: clamp(31px, 3.5vw, 46px);
    font-weight: 600;
    line-height: 1.15;
    letter-spacing: -1px;
}

.section-heading h2 em {
    color: var(--bk-brown);
    font-weight: 500;
}

.section-heading > p {
    max-width: 370px;
    margin: 0;
    padding-left: 18px;
    border-left: 2px solid var(--bk-line-strong);
    color: var(--bk-muted);
    font-size: 13px;
    line-height: 1.9;
}

/* Filters */

.filter-panel {
    position: relative;
    overflow: hidden;
    margin-bottom: 64px;
    border: 1px solid var(--bk-line);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(60, 40, 25, .04), 0 20px 48px rgba(60, 40, 25, .08);
}

.filter-panel::before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--bk-brown), var(--bk-gold), var(--bk-brown));
    background-size: 200% 100%;
    content: "";
    animation: gradientMove 6s linear infinite;
}

.filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 82px;
    padding: 16px 26px;
    border-bottom: 1px solid var(--bk-line);
    background: linear-gradient(180deg, #fcf8f3, #fff);
}

.filter-title {
    display: flex;
    align-items: center;
    gap: 13px;
}

.filter-icon {
    display: grid;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    place-items: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #a06c46, #6b4529);
    color: #fff;
    font-size: 18px;
    box-shadow: 0 10px 22px rgba(107, 69, 41, .2);
    transition: transform .4s var(--bk-spring);
}

.filter-panel:hover .filter-icon {
    transform: rotate(-8deg);
}

.filter-title strong,
.filter-title span {
    display: block;
}

.filter-title strong {
    color: var(--bk-ink);
    font-size: 14px;
    font-weight: 800;
}

.filter-title span {
    margin-top: 4px;
    color: var(--bk-muted);
    font-size: 11px;
}

.total-badge {
    padding: 8px 14px;
    border: 1px solid var(--bk-line-strong);
    border-radius: 100px;
    background: var(--bk-cream);
    color: var(--bk-muted);
    font-size: 11px;
    white-space: nowrap;
}

.total-badge span {
    color: var(--bk-brown);
    font-weight: 800;
}

.filter-form {
    display: grid;
    grid-template-columns: minmax(220px, 1.5fr) repeat(3, minmax(135px, 1fr));
    align-items: end;
    gap: 16px;
    padding: 25px 26px 28px;
}

.filter-field {
    min-width: 0;
}

.filter-field label {
    display: block;
    margin: 0 0 9px 2px;
    color: var(--bk-text);
    font-size: 11px;
    font-weight: 800;
}

.search-control {
    position: relative;
    display: flex;
    align-items: center;
    height: 51px;
    border: 1px solid var(--bk-line-strong);
    border-radius: 11px;
    background: var(--bk-surface-2);
    transition: border-color .3s ease, box-shadow .3s ease, background .3s ease;
}

.search-control > i {
    flex: 0 0 auto;
    margin-left: 15px;
    color: #a99a8e;
    font-size: 15px;
}

.search-control input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0 13px 0 10px;
    border: 0;
    outline: 0;
    background: transparent;
    color: var(--bk-ink);
    font-size: 12px;
}

.search-control input::placeholder {
    color: var(--bk-faint);
}

.search-control:focus-within {
    border-color: var(--bk-brown);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(139, 94, 60, .11);
}

.filter-field select {
    display: block;
    width: 100%;
    min-width: 0;
    height: 51px;
    padding: 0 32px 0 13px;
    border: 1px solid var(--bk-line-strong);
    border-radius: 11px;
    outline: 0;
    background-color: var(--bk-surface-2);
    color: var(--bk-text);
    font-size: 12px;
    cursor: pointer;
    transition: border-color .3s ease, box-shadow .3s ease;
}

.filter-field select:focus {
    border-color: var(--bk-brown);
    box-shadow: 0 0 0 3px rgba(139, 94, 60, .11);
}

.filter-actions {
    display: flex;
    grid-column: 1 / -1;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-submit,
.filter-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 45px;
    padding: 0 21px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 800;
    transition: transform .3s var(--bk-ease), background .3s ease, box-shadow .3s ease;
}

.filter-submit {
    border: 1px solid transparent;
    background: linear-gradient(135deg, #a06c46, #8b5e3c 50%, #6b4529);
    color: #fff;
    box-shadow: 0 8px 18px rgba(107, 69, 41, .18);
}

.filter-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(107, 69, 41, .28);
}

.filter-reset {
    border: 1px solid var(--bk-line-strong);
    background: #fff;
    color: var(--bk-text);
}

.filter-reset:hover {
    transform: translateY(-2px);
    background: var(--bk-cream);
    color: var(--bk-brown);
}

/* Results heading */

.results-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 30px;
}

.results-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--bk-brown);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
}

.results-label > span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--bk-gold);
    box-shadow: 0 0 0 4px rgba(201, 160, 116, .14);
    animation: statusPulse 2s ease-out infinite;
}

.results-heading h3 {
    margin: 11px 0 0;
    color: var(--bk-ink);
    font-family: "Playfair Display", Georgia, serif;
    font-size: clamp(24px, 2.7vw, 33px);
    font-weight: 600;
}

.results-count {
    padding: 9px 15px;
    border: 1px solid var(--bk-line-strong);
    border-radius: 100px;
    background: #fff;
    color: var(--bk-muted);
    font-size: 11px;
    white-space: nowrap;
}

.results-count strong {
    color: var(--bk-brown);
    font-weight: 800;
}

/* Book cards */

.books-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 25px 22px;
}

.book-card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    height: 100%;
    overflow: hidden;
    border: 1px solid var(--bk-line);
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(60, 40, 25, .04), 0 8px 24px rgba(60, 40, 25, .055);
    animation: cardReveal .65s var(--bk-ease) both;
    animation-delay: calc(var(--card-index, 0) * 45ms);
    transition: transform .45s var(--bk-ease), box-shadow .45s var(--bk-ease), border-color .45s ease;
}

.book-card::before {
    position: absolute;
    z-index: 4;
    top: 0;
    right: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--bk-brown), var(--bk-gold), var(--bk-brown));
    content: "";
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .5s var(--bk-ease);
}

.book-card:hover {
    transform: translateY(-8px);
    border-color: var(--bk-line-strong);
    box-shadow: 0 4px 8px rgba(60, 40, 25, .05), 0 28px 52px rgba(60, 40, 25, .13);
}

.book-card:hover::before {
    transform: scaleX(1);
}

.book-cover-wrap {
    position: relative;
    flex: 0 0 auto;
    height: 315px;
    margin: 9px 9px 0;
    overflow: hidden;
    border-radius: 12px;
    background: linear-gradient(135deg, #f1e6da, #e2d2c2);
}

.book-cover-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .8s var(--bk-ease), filter .5s ease;
}

.book-card:hover .book-cover-image {
    transform: scale(1.07);
    filter: saturate(1.08);
}

.book-cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    height: 100%;
    color: #98775d;
}

.book-cover-placeholder i {
    font-size: 48px;
}

.book-cover-placeholder span {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
}

.condition-badge {
    position: absolute;
    z-index: 2;
    top: 12px;
    left: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: calc(100% - 24px);
    padding: 7px 10px;
    border: 1px solid rgba(255, 255, 255, .65);
    border-radius: 100px;
    background: rgba(255, 255, 255, .9);
    color: #6e503b;
    font-size: 9px;
    font-weight: 800;
    backdrop-filter: blur(10px);
}

.condition-badge i {
    color: var(--bk-brown);
}

.discount-badge {
    position: absolute;
    z-index: 2;
    top: 49px;
    left: 12px;
    max-width: calc(100% - 24px);
    overflow: hidden;
    padding: 7px 10px;
    border-radius: 100px;
    background: linear-gradient(135deg, #d4694f, #b0432d);
    color: #fff;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .5px;
    text-overflow: ellipsis;
    white-space: nowrap;
    box-shadow: 0 8px 18px rgba(176, 67, 45, .24);
}

.cover-overlay {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 18px 14px 13px;
    background: linear-gradient(180deg, transparent, rgba(25, 18, 13, .76));
    color: #fff;
    opacity: 0;
    transform: translateY(8px);
    transition: opacity .35s ease, transform .4s var(--bk-ease);
}

.book-card:hover .cover-overlay {
    opacity: 1;
    transform: translateY(0);
}

.cover-overlay span {
    font-size: 8px;
    font-weight: 800;
    letter-spacing: .8px;
}

.cover-overlay i {
    display: grid;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    place-items: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .2);
    font-size: 14px;
}

.book-card-content {
    display: flex;
    flex: 1;
    flex-direction: column;
    min-width: 0;
    padding: 19px 18px 18px;
}

.book-category {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow: hidden;
    margin-bottom: 10px;
    color: var(--bk-brown);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    text-overflow: ellipsis;
    text-transform: uppercase;
    white-space: nowrap;
}

.book-category > span {
    flex: 0 0 17px;
    width: 17px;
    height: 2px;
    border-radius: 2px;
    background: var(--bk-gold);
}

.book-card-content h3 {
    display: -webkit-box;
    min-height: 44px;
    overflow: hidden;
    margin: 0;
    color: var(--bk-ink);
    font-size: 15px;
    font-weight: 800;
    line-height: 1.45;
    overflow-wrap: anywhere;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    transition: color .3s ease;
}

.book-card:hover .book-card-content h3 {
    color: var(--bk-brown);
}

.book-author {
    display: flex;
    align-items: center;
    gap: 7px;
    overflow: hidden;
    margin: 10px 0 0;
    color: var(--bk-muted);
    font-size: 11px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.book-author i {
    flex: 0 0 auto;
    color: var(--bk-gold);
    font-size: 12px;
}

.book-divider {
    height: 1px;
    margin: 17px 0;
    background: linear-gradient(90deg, transparent, var(--bk-line-strong), transparent);
}

.book-bottom {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
    margin-top: auto;
}

.book-price-block {
    min-width: 0;
}

.price-label {
    display: block;
    margin-bottom: 7px;
    color: var(--bk-faint);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.book-price-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    gap: 4px 7px;
}

.book-price-wrap strong {
    color: var(--bk-brown);
    font-size: 19px;
    font-weight: 800;
    line-height: 1.25;
    overflow-wrap: anywhere;
}

.book-price-wrap strong.discounted-price {
    color: var(--bk-sale);
}

.book-price-wrap del {
    color: var(--bk-faint);
    font-size: 11px;
    white-space: nowrap;
}

.book-stock {
    display: flex;
    align-items: center;
    gap: 5px;
    max-width: 100%;
    color: var(--bk-muted);
    font-size: 9px;
}

.book-stock i {
    color: var(--bk-gold);
    font-size: 12px;
}

.book-stock span {
    overflow-wrap: anywhere;
}

/* Loading and empty states */

.catalog-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 280px;
    padding: 48px 24px;
    border: 1px solid var(--bk-line);
    border-radius: 22px;
    background:
        radial-gradient(420px 220px at 50% 0%, rgba(201, 160, 116, .13), transparent 70%),
        #fff;
    color: var(--bk-muted);
    text-align: center;
}

.catalog-message > strong {
    margin-top: 16px;
    color: var(--bk-ink);
    font-size: 17px;
}

.catalog-message > span {
    margin-top: 8px;
    font-size: 12px;
    line-height: 1.7;
}

.loading-spinner {
    width: 38px;
    height: 38px;
    border: 3px solid var(--bk-line);
    border-top-color: var(--bk-brown);
    border-radius: 50%;
    animation: spinnerRotate .8s linear infinite;
}

.message-icon {
    display: grid;
    width: 54px;
    height: 54px;
    place-items: center;
    border-radius: 50%;
    background: #fbe9e6;
    color: #b42318;
    font-size: 23px;
}

.empty-visual {
    position: relative;
    width: 150px;
    height: 95px;
    margin-bottom: 17px;
}

.empty-book {
    position: absolute;
    bottom: 5px;
    left: 50%;
    width: 38px;
    height: 62px;
    border-radius: 4px;
    background: linear-gradient(135deg, #e9dccf, #d8c5b3);
    box-shadow: 0 10px 18px rgba(60, 42, 29, .12);
}

.empty-book-one {
    transform: translateX(-57px) rotate(-14deg);
    animation: bookSwayOne 4s ease-in-out infinite;
}

.empty-book-two {
    background: linear-gradient(135deg, #cdb9a7, #b79d85);
    transform: translateX(-50%) rotate(0);
    animation: bookSwayTwo 4s ease-in-out .4s infinite;
}

.empty-book-three {
    transform: translateX(18px) rotate(14deg);
    animation: bookSwayThree 4s ease-in-out .8s infinite;
}

.empty-visual > i {
    position: absolute;
    top: 0;
    right: 7px;
    display: grid;
    width: 37px;
    height: 37px;
    place-items: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #a06c46, #6b4529);
    color: #fff;
    font-size: 15px;
    box-shadow: 0 10px 20px rgba(107, 69, 41, .24);
    animation: glowPulse 2.4s ease-in-out infinite alternate;
}

.empty-label {
    padding: 6px 11px;
    border-radius: 100px;
    background: var(--bk-cream);
    color: var(--bk-brown);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 2px;
}

.catalog-message h3 {
    margin: 13px 0 8px;
    color: var(--bk-ink);
    font-family: "Playfair Display", Georgia, serif;
    font-size: clamp(23px, 3vw, 30px);
    font-weight: 600;
}

.catalog-message p {
    max-width: 420px;
    margin: 0 auto 23px;
    color: var(--bk-muted);
    font-size: 12px;
    line-height: 1.8;
}

.empty-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 43px;
    padding: 0 19px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #a06c46, #6b4529);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    transition: transform .3s var(--bk-ease), box-shadow .3s ease;
}

.empty-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(107, 69, 41, .24);
}

/* Pagination */

.catalog-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-top: 44px;
    padding: 16px 21px;
    border: 1px solid var(--bk-line);
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 5px 18px rgba(60, 40, 25, .045);
}

.pagination-info {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--bk-muted);
    font-size: 12px;
}

.pagination-info strong {
    color: var(--bk-ink);
}

.pagination-links {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 7px;
}

.page-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 39px;
    height: 39px;
    padding: 0 10px;
    border: 1px solid var(--bk-line-strong);
    border-radius: 10px;
    background: #fff;
    color: var(--bk-text);
    font-size: 12px;
    font-weight: 700;
    transition: transform .3s var(--bk-ease), background .3s ease, color .3s ease, box-shadow .3s ease;
}

.page-button:hover:not(:disabled):not(.active) {
    transform: translateY(-2px);
    background: var(--bk-cream);
    color: var(--bk-brown);
}

.page-button.active {
    border-color: transparent;
    background: linear-gradient(135deg, #a06c46, #6b4529);
    color: #fff;
    box-shadow: 0 8px 18px rgba(107, 69, 41, .22);
}

.page-button:disabled {
    cursor: not-allowed;
    opacity: .4;
}

/* Sell section */

.sell-section {
    padding: 0 0 95px;
    background: var(--bk-bg);
}

.sell-card {
    position: relative;
    display: flex;
    min-height: 330px;
    overflow: hidden;
    border-radius: 25px;
    background:
        radial-gradient(520px 320px at 100% 0%, rgba(201, 160, 116, .24), transparent 65%),
        linear-gradient(135deg, #3d2c21 0%, #2a1e16 55%, #1d130d 100%);
    box-shadow: 0 30px 65px rgba(42, 29, 20, .2);
}

.sell-card::before {
    position: absolute;
    top: -120px;
    right: 8%;
    width: 300px;
    height: 300px;
    border: 1px dashed rgba(255, 255, 255, .13);
    border-radius: 50%;
    content: "";
    animation: orbitSpin 45s linear infinite;
    pointer-events: none;
}

.sell-image {
    position: relative;
    flex: 0 0 38%;
    min-width: 0;
    overflow: hidden;
}

.sell-image::after {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 35%, #2a1e16 100%);
    content: "";
}

.sell-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: .83;
    transition: transform 1s var(--bk-ease);
}

.sell-card:hover .sell-image img {
    transform: scale(1.06);
}

.sell-image-placeholder {
    display: grid;
    width: 100%;
    height: 100%;
    place-items: center;
    background: linear-gradient(135deg, #765039, #352117);
    color: #d9b18a;
    font-size: 68px;
}

.sell-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex: 1;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    min-width: 0;
    padding: 44px 48px 44px 24px;
}

.sell-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border: 1px solid rgba(255, 255, 255, .18);
    border-radius: 100px;
    background: rgba(255, 255, 255, .07);
    color: #e6c9a6;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.7px;
}

.sell-content h2 {
    max-width: 540px;
    margin: 18px 0 12px;
    color: #fff;
    font-family: "Playfair Display", Georgia, serif;
    font-size: clamp(30px, 3.4vw, 43px);
    font-weight: 600;
    line-height: 1.15;
}

.sell-content h2 em {
    color: #e6c9a6;
    font-weight: 500;
}

.sell-content p {
    max-width: 470px;
    margin: 0 0 24px;
    color: rgba(255, 255, 255, .73);
    font-size: 12px;
    line-height: 1.9;
}

.sell-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 11px;
    min-height: 47px;
    padding: 0 23px;
    border-radius: 10px;
    background: linear-gradient(135deg, #fff, #f6e6d3);
    color: var(--bk-brown-deep);
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    transition: transform .3s var(--bk-ease), box-shadow .3s ease;
}

.sell-button:hover {
    transform: translateY(-3px);
    color: var(--bk-brown-deep);
    box-shadow: 0 14px 28px rgba(0, 0, 0, .25);
}

.sell-button i {
    transition: transform .3s var(--bk-ease);
}

.sell-button:hover i {
    transform: translate(3px, -3px);
}

/* Animations */

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes visualReveal {
    from {
        opacity: 0;
        transform: translateX(20px) scale(.97);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}

@keyframes floating {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-8px);
    }
}

@keyframes glowPulse {
    from {
        opacity: .55;
        transform: translate(-50%, -50%) scale(.94);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.06);
    }
}

@keyframes orbitSpin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes gradientMove {
    from {
        background-position: 0% 50%;
    }
    to {
        background-position: 200% 50%;
    }
}

@keyframes statusPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(201, 160, 116, .45);
    }
    100% {
        box-shadow: 0 0 0 8px rgba(201, 160, 116, 0);
    }
}

@keyframes cardReveal {
    from {
        opacity: 0;
        transform: translateY(22px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spinnerRotate {
    to {
        transform: rotate(360deg);
    }
}

@keyframes bookSwayOne {
    0%, 100% {
        transform: translateX(-57px) rotate(-14deg);
    }
    50% {
        transform: translateX(-57px) translateY(-6px) rotate(-11deg);
    }
}

@keyframes bookSwayTwo {
    0%, 100% {
        transform: translateX(-50%) rotate(0);
    }
    50% {
        transform: translateX(-50%) translateY(-8px) rotate(0);
    }
}

@keyframes bookSwayThree {
    0%, 100% {
        transform: translateX(18px) rotate(14deg);
    }
    50% {
        transform: translateX(18px) translateY(-6px) rotate(11deg);
    }
}

/* Responsive */

@media (max-width: 1199px) {
    .hero-inner {
        grid-template-columns: minmax(0, 1fr) minmax(340px, .85fr);
        gap: 28px;
    }

    .hero-content h1 {
        font-size: clamp(43px, 5vw, 62px);
    }

    .hero-visual {
        min-height: 460px;
    }

    .main-book-frame {
        right: 22px;
        height: 350px;
    }

    .stack-book {
        transform: scale(.88);
        transform-origin: bottom left;
    }

    .hero-visual:hover .stack-book-1 {
        transform: scale(.88) rotate(-17deg) translate(-5px, -5px);
    }

    .hero-visual:hover .stack-book-2 {
        transform: scale(.88) translateY(-10px);
    }

    .hero-visual:hover .stack-book-3 {
        transform: scale(.88) rotate(17deg) translate(5px, -4px);
    }

    .filter-form {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-actions {
        grid-column: 1 / -1;
    }
}

@media (max-width: 991px) {
    .catalog-hero {
        padding-bottom: 82px;
    }

    .hero-inner {
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
    }

    .hero-content {
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
    }

    .hero-breadcrumb,
    .hero-eyebrow,
    .hero-actions,
    .hero-trust {
        justify-content: center;
    }

    .hero-breadcrumb {
        margin-bottom: 30px;
    }

    .hero-eyebrow {
        margin-bottom: 19px;
    }

    .hero-content h1,
    .hero-description {
        margin-right: auto;
        margin-left: auto;
    }

    .hero-content h1 {
        font-size: clamp(45px, 7vw, 65px);
    }

    .hero-visual {
        width: min(100%, 570px);
        min-height: 465px;
        margin: 0 auto;
    }

    .main-book-frame {
        right: 45px;
        width: 65%;
        height: 355px;
    }

    .stack-book {
        left: 20px;
        bottom: 42px;
        transform: scale(.92);
    }

    .hero-visual:hover .stack-book-1 {
        transform: scale(.92) rotate(-17deg) translate(-5px, -5px);
    }

    .hero-visual:hover .stack-book-2 {
        transform: scale(.92) translateY(-10px);
    }

    .hero-visual:hover .stack-book-3 {
        transform: scale(.92) rotate(17deg) translate(5px, -4px);
    }

    .floating-top {
        top: 58px;
        left: 5px;
    }

    .floating-bottom {
        right: 5px;
        bottom: 20px;
    }

    .visual-stamp {
        right: 20px;
    }

    .visual-number {
        right: 20px;
    }

    .stats-panel {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .stat-item:nth-child(2) {
        border-right: 0;
    }

    .stat-item:nth-child(-n + 2) {
        border-bottom: 1px solid var(--bk-line);
    }

    .books-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .book-cover-wrap {
        height: 285px;
    }

    .section-heading {
        align-items: flex-start;
        flex-direction: column;
        gap: 17px;
    }

    .section-heading > p {
        max-width: 600px;
    }

    .sell-image {
        flex-basis: 35%;
    }

    .sell-content {
        padding-right: 30px;
    }
}

@media (max-width: 767px) {
    .catalog-container {
        width: calc(100% - 32px);
    }

    .catalog-hero {
        padding: 25px 16px 77px;
    }

    .hero-breadcrumb {
        margin-bottom: 27px;
        font-size: 10px;
    }

    .hero-content h1 {
        font-size: clamp(39px, 8vw, 56px);
        letter-spacing: -1.5px;
    }

    .hero-description {
        margin-top: 20px;
        font-size: 12px;
        line-height: 1.85;
    }

    .hero-actions {
        gap: 9px;
    }

    .hero-primary,
    .hero-secondary {
        min-height: 47px;
        padding: 0 14px;
        font-size: 11px;
    }

    .hero-trust {
        margin-top: 27px;
    }

    .hero-visual {
        min-height: 405px;
        margin-top: 9px;
    }

    .main-book-frame {
        top: 40px;
        right: 25px;
        width: 65%;
        height: 300px;
    }

    .frame-caption {
        right: 12px;
        bottom: 14px;
        left: 12px;
    }

    .frame-caption strong {
        font-size: 25px;
    }

    .stack-book {
        bottom: 32px;
        left: -5px;
        transform: scale(.73);
    }

    .hero-visual:hover .stack-book-1 {
        transform: scale(.73) rotate(-17deg) translate(-5px, -5px);
    }

    .hero-visual:hover .stack-book-2 {
        transform: scale(.73) translateY(-10px);
    }

    .hero-visual:hover .stack-book-3 {
        transform: scale(.73) rotate(17deg) translate(5px, -4px);
    }

    .floating-card {
        gap: 7px;
        padding: 9px;
    }

    .floating-icon {
        flex-basis: 30px;
        width: 30px;
        height: 30px;
        font-size: 12px;
    }

    .floating-card strong {
        font-size: 8px;
    }

    .floating-card span {
        font-size: 7px;
    }

    .floating-top {
        top: 52px;
        left: -4px;
    }

    .floating-bottom {
        right: -3px;
        bottom: 12px;
    }

    .visual-stamp {
        top: 8px;
        right: 0;
        width: 53px;
        height: 53px;
        font-size: 7px;
    }

    .visual-number {
        right: 0;
        bottom: 56px;
        font-size: 45px;
    }

    .visual-ring.ring-one {
        width: 285px;
        height: 285px;
    }

    .visual-ring.ring-two {
        width: 215px;
        height: 215px;
    }

    .hero-bottom-line {
        bottom: 26px;
        gap: 8px;
        font-size: 7px;
    }

    .hero-bottom-line > div {
        width: 25px;
    }

    .catalog-stats {
        padding-bottom: 43px;
    }

    .stats-panel {
        border-radius: 17px;
    }

    .stat-item {
        gap: 10px;
        min-height: 94px;
        padding: 15px 12px;
    }

    .stat-icon {
        flex-basis: 39px;
        width: 39px;
        height: 39px;
        border-radius: 11px;
        font-size: 16px;
    }

    .stat-item strong {
        font-size: 16px;
    }

    .stat-item span {
        font-size: 9px;
    }

    .catalog-section {
        padding: 17px 0 70px;
    }

    .section-heading {
        margin-bottom: 30px;
    }

    .section-heading h2 {
        font-size: clamp(29px, 6vw, 38px);
    }

    .section-heading > p {
        font-size: 12px;
    }

    .filter-panel {
        margin-bottom: 46px;
        border-radius: 17px;
    }

    .filter-heading {
        min-height: 70px;
        padding: 13px 15px;
    }

    .filter-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .filter-title strong {
        font-size: 12px;
    }

    .filter-title span {
        font-size: 9px;
    }

    .total-badge {
        padding: 7px 9px;
        font-size: 9px;
    }

    .filter-form {
        grid-template-columns: minmax(0, 1fr);
        gap: 13px;
        padding: 19px 15px 20px;
    }

    .filter-actions {
        grid-column: auto;
    }

    .filter-submit {
        flex: 1;
    }

    .filter-reset {
        flex: 0 0 auto;
    }

    .results-heading {
        align-items: flex-start;
        flex-direction: column;
        gap: 13px;
        margin-bottom: 23px;
    }

    .results-heading h3 {
        font-size: 27px;
    }

    .books-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .book-card {
        border-radius: 13px;
    }

    .book-card:hover {
        transform: translateY(-3px);
    }

    .book-cover-wrap {
        height: clamp(155px, 43vw, 245px);
        margin: 6px 6px 0;
        border-radius: 9px;
    }

    .condition-badge {
        top: 7px;
        left: 7px;
        gap: 4px;
        padding: 5px 7px;
        font-size: 8px;
    }

    .discount-badge {
        top: 34px;
        left: 7px;
        padding: 5px 7px;
        font-size: 8px;
    }

    .book-card-content {
        padding: 13px 11px 12px;
    }

    .book-category {
        gap: 5px;
        margin-bottom: 8px;
        font-size: 8px;
        letter-spacing: .5px;
    }

    .book-category > span {
        flex-basis: 12px;
        width: 12px;
    }

    .book-card-content h3 {
        min-height: 39px;
        font-size: 13px;
        line-height: 1.4;
    }

    .book-author {
        gap: 5px;
        margin-top: 8px;
        font-size: 10px;
    }

    .book-divider {
        margin: 12px 0;
    }

    .book-bottom {
        align-items: flex-start;
        gap: 9px;
    }

    .book-price-wrap {
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }

    .book-price-wrap strong {
        font-size: clamp(14px, 3.8vw, 18px);
    }

    .book-price-wrap del {
        font-size: 10px;
    }

    .book-stock {
        font-size: 9px;
    }

    .cover-overlay {
        display: none;
    }

    .catalog-pagination {
        flex-direction: column;
        gap: 13px;
        padding: 15px 10px;
    }

    .pagination-links {
        justify-content: center;
        gap: 5px;
    }

    .page-button {
        min-width: 35px;
        height: 35px;
        padding: 0 7px;
        border-radius: 9px;
    }

    .sell-section {
        padding-bottom: 65px;
    }

    .sell-card {
        display: block;
        border-radius: 20px;
    }

    .sell-image {
        width: 100%;
        height: 185px;
    }

    .sell-image::after {
        background: linear-gradient(180deg, transparent 35%, #2a1e16 100%);
    }

    .sell-content {
        padding: 5px 22px 28px;
    }

    .sell-content h2 {
        font-size: 32px;
    }
}

@media (max-width: 420px) {
    .hero-content h1 {
        font-size: 37px;
    }

    .hero-actions {
        flex-direction: column;
        align-items: stretch;
        width: min(100%, 300px);
        margin: 0 auto;
    }

    .hero-primary,
    .hero-secondary {
        width: 100%;
    }

    .hero-visual {
        min-height: 350px;
    }

    .main-book-frame {
        right: 17px;
        width: 67%;
        height: 260px;
    }

    .stack-book {
        bottom: 22px;
        left: -15px;
        transform: scale(.61);
    }

    .hero-visual:hover .stack-book-1 {
        transform: scale(.61) rotate(-17deg) translate(-5px, -5px);
    }

    .hero-visual:hover .stack-book-2 {
        transform: scale(.61) translateY(-10px);
    }

    .hero-visual:hover .stack-book-3 {
        transform: scale(.61) rotate(17deg) translate(5px, -4px);
    }

    .floating-card {
        padding: 7px;
    }

    .floating-icon {
        flex-basis: 26px;
        width: 26px;
        height: 26px;
    }

    .floating-card strong {
        font-size: 7px;
    }

    .floating-card span {
        font-size: 6px;
    }

    .visual-stamp {
        width: 45px;
        height: 45px;
        font-size: 6px;
    }

    .visual-stamp i {
        font-size: 12px;
    }

    .stats-panel {
        grid-template-columns: minmax(0, 1fr);
    }

    .stat-item,
    .stat-item:nth-child(2),
    .stat-item:nth-child(-n + 2) {
        min-height: 75px;
        border-right: 0;
        border-bottom: 1px solid var(--bk-line);
    }

    .stat-item:last-child {
        border-bottom: 0;
    }

    .stat-item span {
        font-size: 10px;
    }

    .filter-heading {
        align-items: flex-start;
    }

    .filter-title span {
        max-width: 155px;
        line-height: 1.5;
    }

    .total-badge {
        font-size: 8px;
    }

    .books-grid {
        gap: 10px;
    }

    .book-card-content {
        padding: 11px 9px;
    }

    .book-card-content h3 {
        font-size: 12px;
    }

    .book-stock {
        font-size: 8px;
    }

    .catalog-message {
        padding: 35px 15px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .catalog *,
    .catalog *::before,
    .catalog *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}
</style>
