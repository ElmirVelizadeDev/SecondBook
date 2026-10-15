<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import catalogService from '../services/catalogService';

const props = defineProps({
    authorsUrl: { type: String, required: true },
    booksUrl: { type: String, required: true },
    storageUrl: { type: String, required: true },
});
const authors = ref([]);
const meta = ref({ current_page: 1, last_page: 1, per_page: 12, total: 0 });
const search = ref('');
const loading = ref(true);
const error = ref('');
const pages = computed(() => {
    const first = Math.max(1, meta.value.current_page - 2);
    const last = Math.min(meta.value.last_page, meta.value.current_page + 2);
    return Array.from({ length: Math.max(0, last - first + 1) }, (_, index) => first + index);
});
const spotlight = computed(() => authors.value[0] || null);

function authorImage(photo) {
    if (!photo) return '';
    if (/^https?:\/\//i.test(photo)) return photo;
    return `${props.storageUrl}/${String(photo).replace(/^\/+/, '')}`;
}
function booksFor(author) {
    const url = new URL(props.booksUrl, window.location.origin);
    if (author?.name) url.searchParams.set('search', author.name);
    return `${url.pathname}${url.search}`;
}
function urlFor(page = 1, query = search.value) {
    const url = new URL(props.authorsUrl, window.location.origin);
    if (query) url.searchParams.set('search', query);
    if (page > 1) url.searchParams.set('page', page);
    return url;
}
async function loadAuthors(page = 1, query = search.value, updateHistory = true) {
    loading.value = true;
    error.value = '';
    try {
        const response = await catalogService.getAuthors({ search: query || undefined, page });
        authors.value = response.data || [];
        meta.value = response.meta || meta.value;
        if (updateHistory) {
            const url = urlFor(meta.value.current_page, query);
            window.history.pushState({}, '', `${url.pathname}${url.search}`);
        }
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Authors could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}
function submitSearch() {
    loadAuthors(1, search.value);
    document.querySelector('#authors-list')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
function navigate(page) {
    loadAuthors(page);
    document.querySelector('#authors-list')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
function handlePopState() {
    const params = new URLSearchParams(window.location.search);
    search.value = params.get('search') || '';
    loadAuthors(Number(params.get('page') || 1), search.value, false);
}
onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    search.value = params.get('search') || '';
    loadAuthors(Number(params.get('page') || 1), search.value, false);
    window.addEventListener('popstate', handlePopState);
});
onUnmounted(() => window.removeEventListener('popstate', handlePopState));
</script>

<template>
    <div class="authors-page">
        <section class="authors-hero"><div class="authors-hero-noise"></div><div class="authors-hero-pattern"></div><div class="authors-hero-orbit authors-hero-orbit-one"></div><div class="authors-hero-orbit authors-hero-orbit-two"></div><div class="container">
            <div class="authors-breadcrumb"><a href="/">Home</a><span>/</span><span>Authors</span></div><div class="row align-items-center g-5">
                <div class="col-lg-6"><div class="authors-hero-content"><span class="authors-eyebrow"><span class="authors-eyebrow-line"></span><i class="bi bi-pen"></i>Meet the Authors</span><h1>Stories begin with <span>great authors.</span></h1><p>Discover the writers behind the books available on SecondBook. Explore their ideas, stories and unforgettable worlds.</p><div class="authors-hero-actions"><a :href="booksUrl" class="authors-primary-btn"><span>Explore Books</span><i class="bi bi-arrow-up-right"></i></a><a href="#authors-list" class="authors-secondary-btn"><span>Browse Authors</span><i class="bi bi-arrow-down"></i></a></div><div class="authors-hero-meta"><div class="authors-hero-meta-item"><strong>{{ meta.total }}</strong><span>Authors</span></div><div class="authors-hero-meta-divider"></div><div class="authors-hero-meta-item"><strong>01</strong><span>Shared purpose</span></div></div></div></div>
                <div class="col-lg-6"><div class="authors-hero-visual"><div class="authors-art-label"><span>THE VOICES</span><i class="bi bi-arrow-down-right"></i></div><div class="authors-main-image"><img src="https://images.pexels.com/photos/9572569/pexels-photo-9572569.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="Author reading a book"><div class="authors-main-image-overlay"></div></div><div class="authors-floating-card authors-floating-card-top"><div class="floating-icon"><i class="bi bi-people"></i></div><div><strong>{{ meta.total }}</strong><span>Authors</span></div></div><div class="authors-floating-card authors-floating-card-bottom"><div class="floating-avatar"><i class="bi bi-bookmark-heart"></i></div><div><strong>Unique Stories</strong><span>One reader at a time</span></div></div><div class="authors-circle circle-one"></div><div class="authors-circle circle-two"></div><div class="authors-hero-stamp"><span>READ</span><span>DISCOVER</span><span>REPEAT</span></div><div class="authors-hero-number">01</div></div></div>
            </div><div class="authors-hero-scroll"><span>SCROLL TO DISCOVER</span><div></div></div>
        </div></section>

        <section v-if="authors.length" class="authors-marquee" aria-label="Author names"><div class="authors-marquee-viewport"><div class="authors-marquee-track"><div v-for="copy in 2" :key="copy" class="authors-marquee-group" :aria-hidden="copy === 2 ? 'true' : undefined"><template v-for="repeat in 6" :key="repeat"><span v-for="author in authors" :key="`${copy}-${repeat}-${author.id}`" class="authors-marquee-item">{{ author.name }}</span></template></div></div></div></section>
        <section class="authors-intro"><div class="container"><div class="authors-intro-grid"><div class="authors-section-index"><span>02</span><div></div><small>THE AUTHORS</small></div><div class="authors-section-heading"><span class="authors-small-label">DISCOVER THEIR WORK</span><h2>Behind every <em>great book</em> is a voice.</h2><p>Browse our growing community of authors and discover the people behind the stories waiting to be found.</p></div></div></div></section>

        <section v-if="spotlight" class="authors-spotlight"><div class="container"><div class="authors-spotlight-header"><div><span class="authors-small-label">AUTHOR SPOTLIGHT</span><h2>Meet a voice <em>worth discovering.</em></h2></div><span class="authors-section-number">03 / FEATURED</span></div><article class="authors-spotlight-card"><div class="authors-spotlight-image"><img v-if="spotlight.photo" :src="authorImage(spotlight.photo)" :alt="spotlight.name" loading="lazy"><div v-else class="authors-spotlight-placeholder"><i class="bi bi-person"></i></div><div class="authors-spotlight-image-overlay"></div><span class="authors-spotlight-image-label">FEATURED AUTHOR</span></div><div class="authors-spotlight-content"><span class="authors-spotlight-eyebrow">THE VOICE BEHIND THE STORY</span><h3>{{ spotlight.name }}</h3><p>{{ spotlight.bio ? `${spotlight.bio.slice(0, 257)}${spotlight.bio.length > 260 ? '…' : ''}` : `Discover books and stories from ${spotlight.name}.` }}</p><div class="authors-spotlight-rule"></div><div class="authors-spotlight-footer"><div class="authors-spotlight-mark"><span>SB</span></div><div><span>SECOND BOOK</span><small>Stories deserve another reader.</small></div><a :href="booksFor(spotlight)" class="authors-spotlight-btn"><span>Explore Author</span><i class="bi bi-arrow-up-right"></i></a></div></div></article></div></section>

        <section class="authors-list-section" id="authors-list"><div class="container"><div class="authors-list-header"><div><span class="authors-small-label">THE COLLECTION</span><h2>Authors worth <em>knowing.</em></h2></div><p>Explore writers, ideas and stories from the SecondBook collection.</p></div>
            <div class="authors-search-wrapper"><div class="authors-search-heading"><div><span>FIND AN AUTHOR</span><strong>Search the collection.</strong></div><i class="bi bi-search"></i></div><form class="authors-search-form" id="authors-search-form" @submit.prevent="submitSearch"><div class="authors-search-box"><i class="bi bi-search"></i><input v-model="search" type="text" name="search" placeholder="Search by author name..." autocomplete="off" aria-label="Search authors"><button v-if="search" type="button" class="authors-search-clear" aria-label="Clear search" @click="search = ''; submitSearch()"><i class="bi bi-x"></i></button></div><button type="submit" class="authors-search-btn"><span>Search Authors</span><i class="bi bi-arrow-up-right"></i></button></form></div>
            <div id="authors-results" class="authors-results"><div v-if="error" class="alert alert-warning" role="alert">{{ error }}</div><div v-else-if="loading" class="authors-loading" role="status">Loading authors…</div>
                <template v-else-if="authors.length"><div class="authors-results-meta"><div><span class="authors-results-label">AUTHORS</span><strong>{{ meta.total }}</strong></div><div class="authors-active-search">{{ search ? 'Results for' : 'Discover the collection' }} <strong v-if="search">"{{ search }}"</strong></div></div>
                    <div class="authors-grid" id="authors-grid"><article v-for="(author, index) in authors" :key="author.id" class="author-card" :style="{ '--i': index }"><div class="author-card-image"><img v-if="author.photo" :src="authorImage(author.photo)" :alt="author.name" loading="lazy"><div v-else class="author-card-placeholder"><i class="bi bi-person"></i></div><div class="author-image-overlay"></div><span class="author-number">{{ String((meta.current_page - 1) * meta.per_page + index + 1).padStart(2, '0') }}</span><a :href="booksFor(author)" class="author-card-view" :aria-label="`Explore books by ${author.name}`"><i class="bi bi-arrow-up-right"></i></a></div><div class="author-card-content"><div class="author-card-top"><span class="author-label">AUTHOR</span><span class="author-card-line"></span></div><h3>{{ author.name }}</h3><p>{{ author.bio ? `${author.bio.slice(0, 150)}${author.bio.length > 150 ? '…' : ''}` : 'Discover the books and stories behind this author.' }}</p></div></article></div>
                    <div v-if="meta.last_page > 1" class="authors-pagination-wrapper"><div class="authors-pagination"><div class="authors-pagination-info"><span>SHOWING</span><strong>{{ (meta.current_page - 1) * meta.per_page + 1 }}</strong><span>—</span><strong>{{ Math.min(meta.current_page * meta.per_page, meta.total) }}</strong><span>OF</span><strong>{{ meta.total }}</strong><span>AUTHORS</span></div><div class="authors-pagination-links"><button type="button" class="authors-page-link" :class="{ disabled: meta.current_page <= 1 }" :disabled="meta.current_page <= 1" aria-label="Previous page" @click="navigate(meta.current_page - 1)"><i class="bi bi-arrow-left"></i></button><button v-if="pages[0] > 1" type="button" class="authors-page-link" @click="navigate(1)">1</button><span v-if="pages[0] > 2" class="authors-page-ellipsis">...</span><template v-for="page in pages" :key="page"><span v-if="page === meta.current_page" class="authors-page-link active" aria-current="page">{{ page }}</span><button v-else type="button" class="authors-page-link" @click="navigate(page)">{{ page }}</button></template><span v-if="pages[pages.length - 1] < meta.last_page - 1" class="authors-page-ellipsis">...</span><button v-if="pages[pages.length - 1] < meta.last_page" type="button" class="authors-page-link" @click="navigate(meta.last_page)">{{ meta.last_page }}</button><button type="button" class="authors-page-link" :class="{ disabled: meta.current_page >= meta.last_page }" :disabled="meta.current_page >= meta.last_page" aria-label="Next page" @click="navigate(meta.current_page + 1)"><i class="bi bi-arrow-right"></i></button></div></div></div>
                </template>
                <div v-else class="authors-empty"><div class="authors-empty-number">00</div><div class="authors-empty-icon"><i class="bi bi-person-lines-fill"></i></div><span class="authors-small-label">{{ search ? 'SEARCH RESULTS' : 'THE COLLECTION' }}</span><h3>{{ search ? 'No authors found.' : 'No authors available yet.' }}</h3><p v-if="search">We couldn't find any authors matching "<strong>{{ search }}</strong>". Try another search.</p><p v-else>Our author collection is growing. Check back soon for new writers and stories.</p><a :href="search ? authorsUrl : booksUrl" class="authors-primary-btn" @click="search && (search = '')"><span>{{ search ? 'View All Authors' : 'Browse Books' }}</span><i class="bi bi-arrow-up-right"></i></a></div>
            </div>
        </div></section>

        <section class="authors-discovery"><div class="container"><div class="authors-discovery-box"><div class="discovery-decoration discovery-decoration-one"></div><div class="discovery-decoration discovery-decoration-two"></div><div class="row align-items-center g-5"><div class="col-lg-7"><span class="discovery-eyebrow"><i class="bi bi-stars"></i>Keep Exploring</span><h2>Every author has a <span>story to tell.</span></h2><p>From timeless classics to modern discoveries, find your next favourite book and explore the minds behind the pages.</p><a :href="booksUrl" class="discovery-btn"><span>Explore the Collection</span><i class="bi bi-arrow-up-right"></i></a></div><div class="col-lg-5"><div class="discovery-books"><div class="discovery-book book-one"><span>STORIES</span></div><div class="discovery-book book-two"><span>IDEAS</span></div><div class="discovery-book book-three"><span>WORDS</span></div></div></div></div></div></div></section>
        <section class="authors-cta"><div class="authors-cta-orbit authors-cta-orbit-one"></div><div class="authors-cta-orbit authors-cta-orbit-two"></div><div class="container"><div class="authors-cta-content"><div class="authors-cta-copy"><span><i class="bi bi-bookmark-heart"></i>FIND YOUR NEXT READ</span><h2>A new story is <strong>waiting for you.</strong></h2></div><a :href="booksUrl" class="authors-cta-btn"><span>Browse Books</span><i class="bi bi-arrow-up-right"></i></a></div></div></section>
    </div>
</template>
