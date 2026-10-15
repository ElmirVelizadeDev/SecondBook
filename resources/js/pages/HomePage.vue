<template>
    <div class="home-page">
        <section id="billboard" class="billboard-section">
            <div class="container">
                <div ref="billboardSlider" class="billboard-slider">
                    <div
                        v-for="(slide, index) in slides"
                        :key="slide.image"
                        class="billboard-slide"
                    >
                        <div class="billboard-hero">
                            <div class="billboard-visual">
                                <img
                                    :src="`/frontend-assets/images/${slide.image}`"
                                    :alt="slide.imageAlt"
                                    class="billboard-image"
                                >
                                <div class="billboard-image-overlay"></div>
                                <div class="billboard-image-word">{{ slide.imageWord }}</div>
                                <div class="billboard-image-label">
                                    <span class="billboard-image-label-line"></span>
                                    <span>{{ slide.imageLabel }}</span>
                                </div>
                            </div>

                            <div class="billboard-content">
                                <div class="billboard-topline">
                                    <span class="billboard-line"></span>
                                    <span>SECOND BOOK</span>
                                    <span class="billboard-topline-dot"></span>
                                    <span>{{ slide.topline }}</span>
                                </div>

                                <h1 v-if="index === 0" class="billboard-title">
                                    Every book
                                    <span>deserves another</span>
                                    reader.
                                </h1>
                                <h2 v-else class="billboard-title">
                                    Find a story
                                    <span>that stays</span>
                                    with you.
                                </h2>

                                <p class="billboard-description">{{ slide.description }}</p>

                                <div class="billboard-actions">
                                    <a
                                        :href="booksUrl"
                                        class="billboard-btn billboard-btn-primary"
                                    >
                                        <span>{{ slide.primaryAction }}</span>
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                    <a
                                        :href="index === 0 ? sellerCtaUrl : categoriesUrl"
                                        class="billboard-btn billboard-btn-secondary"
                                    >
                                        <span>{{ index === 0 ? sellerCtaLabel : 'View Categories' }}</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>

                                <div class="billboard-bottom-info">
                                    <template v-for="(item, itemIndex) in slide.steps" :key="item.number">
                                        <div class="billboard-info-item">
                                            <span class="billboard-info-number">{{ item.number }}</span>
                                            <div>
                                                <strong>{{ item.title }}</strong>
                                                <small>{{ item.description }}</small>
                                            </div>
                                        </div>
                                        <span
                                            v-if="itemIndex < slide.steps.length - 1"
                                            class="billboard-info-divider"
                                        ></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button
                type="button"
                class="billboard-arrow billboard-arrow-prev"
                aria-label="Previous slide"
            >
                <i class="bi bi-arrow-left"></i>
            </button>
            <button
                type="button"
                class="billboard-arrow billboard-arrow-next"
                aria-label="Next slide"
            >
                <i class="bi bi-arrow-right"></i>
            </button>
        </section>

        <div v-if="error" class="container" role="alert">
            <div class="alert alert-warning d-flex align-items-center justify-content-between">
                <span>{{ error }}</span>
                <button type="button" class="btn btn-sm btn-outline-dark" @click="loadHome">
                    Try again
                </button>
            </div>
        </div>

        <section id="popular-books" class="popular-books-section">
            <div class="container">
                <header class="popular-books-header">
                    <div class="popular-books-heading">
                        <span class="popular-books-eyebrow">Top picks from our marketplace</span>
                        <h2 class="popular-books-title">Popular Books</h2>
                    </div>
                    <div class="popular-books-aside">
                        <p class="popular-books-description">
                            Explore books readers are buying, discovering, loving,
                            and adding to their shelves across the SecondBook marketplace.
                        </p>
                        <a :href="booksUrl" class="popular-books-view-all">
                            <span>View All Books</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </header>

                <div class="popular-books-tabs-wrapper">
                    <ul class="popular-books-tabs" role="tablist">
                        <li
                            v-for="tab in tabs"
                            :key="tab.id"
                            :data-tab-target="`#${tab.id}`"
                            :class="['tab', { active: activeTab === tab.id }]"
                            role="tab"
                            :aria-selected="activeTab === tab.id"
                            tabindex="0"
                            @click="activeTab = tab.id"
                            @keydown.enter="activeTab = tab.id"
                            @keydown.space.prevent="activeTab = tab.id"
                        >
                            <i :class="`bi ${tab.icon}`"></i>
                            <span>{{ tab.label }}</span>
                        </li>
                    </ul>
                </div>

                <div class="popular-books-content">
                    <div
                        v-for="tab in tabs"
                        :id="tab.id"
                        :key="tab.id"
                        :class="{ active: activeTab === tab.id }"
                        data-tab-content
                        role="tabpanel"
                    >
                        <div class="popular-books-grid">
                            <template v-if="tab.books.length">
                                <article
                                    v-for="book in tab.books"
                                    :key="book.id"
                                    class="popular-book-card"
                                    tabindex="0"
                                    @click="openBook(book)"
                                    @keydown.enter="openBook(book)"
                                >
                                    <div class="popular-book-image-wrap">
                                        <span class="popular-book-badge">{{ tab.badge }}</span>
                                        <span v-if="hasDiscount(book)" class="popular-book-discount">
                                            {{ book.discount_label }}
                                        </span>
                                        <div class="popular-book-cover">
                                            <img
                                                v-if="bookImage(book)"
                                                :src="bookImage(book)"
                                                :alt="book.title"
                                                loading="lazy"
                                            >
                                            <div v-else class="popular-book-cover-placeholder" aria-hidden="true">
                                                <i class="bi bi-book-half"></i>
                                            </div>
                                        </div>
                                        <form class="add-to-cart-form" @click.stop>
                                            <button
                                                type="button"
                                                class="add-to-cart popular-book-cart-btn"
                                                :class="{ 'is-added': addedBookId === book.id }"
                                                :disabled="addingBookId === book.id || addedBookId === book.id"
                                                @click="addToCart(book)"
                                            >
                                                <span v-if="addingBookId === book.id" class="cart-loading-spinner"></span>
                                                <i v-else :class="`bi ${addedBookId === book.id ? 'bi-check-lg' : 'bi-cart3'}`"></i>
                                                <span>{{ cartButtonLabel(book) }}</span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="popular-book-info">
                                        <h3 :title="book.title">{{ book.title }}</h3>
                                        <p class="popular-book-author">
                                            {{ book.author?.name || 'Unknown Author' }}
                                        </p>
                                        <div class="popular-book-bottom">
                                            <div class="popular-book-price-wrap">
                                                <template v-if="hasDiscount(book)">
                                                    <span class="popular-book-price popular-book-discounted-price">
                                                        ${{ formatPrice(book.discounted_price) }}
                                                    </span>
                                                    <span class="popular-book-old-price">
                                                        ${{ formatPrice(book.price) }}
                                                    </span>
                                                </template>
                                                <span v-else class="popular-book-price">
                                                    ${{ formatPrice(book.price) }}
                                                </span>
                                            </div>
                                            <span class="popular-book-arrow">
                                                <i class="bi bi-arrow-up-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            </template>
                            <div v-else class="popular-books-empty" aria-live="polite">
                                <div class="popular-books-empty-icon">
                                    <i :class="`bi ${loading ? 'bi-arrow-repeat' : tab.icon}`"></i>
                                </div>
                                <h3>{{ loading ? 'Loading Books...' : tab.emptyTitle }}</h3>
                                <p>{{ loading ? 'Popular books are being loaded.' : tab.emptyText }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="categories" class="categories-section">
            <div class="container">
                <header class="categories-header">
                    <div class="categories-heading">
                        <span class="categories-eyebrow">Find your next read faster</span>
                        <h2 class="categories-title">Popular Categories</h2>
                    </div>
                    <div class="categories-aside">
                        <p class="categories-description">
                            Browse popular genres and discover books that match
                            your interests, mood, and reading style.
                        </p>
                        <a :href="booksUrl" class="categories-view-all">
                            <span>Explore All Books</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </header>
                <div class="categories-grid">
                    <a
                        v-for="category in home.categories.slice(0, 4)"
                        :key="category.id"
                        :href="categoryBooksUrl(category)"
                        class="category-card"
                    >
                        <div class="category-image-wrap">
                            <img
                                v-if="category.image"
                                :src="imageUrl(category.image)"
                                :alt="`${category.name} books`"
                                loading="lazy"
                            >
                            <div class="category-image-overlay"></div>
                            <div class="category-card-content">
                                <span class="category-card-icon">
                                    <i class="bi bi-book"></i>
                                </span>
                                <div class="category-card-text">
                                    <span class="category-card-label">Explore</span>
                                    <h3>{{ category.name }}</h3>
                                    <p>Explore {{ category.name }} books</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <p v-if="loading && !home.categories.length" class="categories-loading">
                        Loading categories...
                    </p>
                    <p v-else-if="!home.categories.length" class="categories-empty-state">
                        No categories are available yet.
                    </p>
                </div>
            </div>
        </section>

        <section id="featured-books" class="featured-books-section">
            <div class="container">
                <header class="featured-books-header">
                    <div class="featured-books-heading">
                        <span class="featured-books-eyebrow">Some quality items</span>
                        <h2 class="featured-books-title">Featured Books</h2>
                    </div>
                    <div class="featured-books-aside">
                        <p class="featured-books-description">
                            Handpicked books selected from the SecondBook marketplace
                            for readers looking for something worth discovering.
                        </p>
                        <a :href="booksUrl" class="featured-books-view-all">
                            <span>View All Books</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </header>
                <div class="featured-books-list">
                    <div class="featured-books-grid">
                        <article
                            v-for="book in home.featured_books"
                            :key="book.id"
                            class="featured-book-card"
                            style="cursor: pointer;"
                            @click="openBook(book)"
                        >
                            <div class="featured-book-image-wrap">
                                <a
                                    :href="bookUrl(book)"
                                    :class="['featured-book-image', { 'featured-book-placeholder': !bookImage(book) }]"
                                    :aria-label="!bookImage(book) ? `View ${book.title} details` : null"
                                    @click.stop
                                >
                                    <img
                                        v-if="bookImage(book)"
                                        :src="bookImage(book)"
                                        :alt="book.title"
                                        loading="lazy"
                                    >
                                    <i v-else class="bi bi-book"></i>
                                </a>
                                <span v-if="hasDiscount(book)" class="featured-book-discount">
                                    {{ book.discount_label }}
                                </span>
                                <form class="add-to-cart-form" @click.stop>
                                    <button
                                        type="button"
                                        class="add-to-cart featured-book-cart-btn"
                                        :class="{ 'is-added': addedBookId === book.id }"
                                        :disabled="addingBookId === book.id || addedBookId === book.id"
                                        @click="addToCart(book)"
                                    >
                                        <span v-if="addingBookId === book.id" class="cart-loading-spinner"></span>
                                        <i v-else :class="`bi ${addedBookId === book.id ? 'bi-check-lg' : 'bi-cart3'}`"></i>
                                        <span>{{ cartButtonLabel(book) }}</span>
                                    </button>
                                </form>
                            </div>
                            <div class="featured-book-info">
                                <span class="featured-book-label">Featured</span>
                                <h3 class="featured-book-title" :title="book.title">{{ book.title }}</h3>
                                <p class="featured-book-author">{{ book.author?.name || 'Unknown Author' }}</p>
                                <div class="featured-book-bottom">
                                    <div class="featured-book-price-wrap">
                                        <template v-if="hasDiscount(book)">
                                            <span class="featured-book-price featured-book-discounted-price">
                                                ${{ formatPrice(book.discounted_price) }}
                                            </span>
                                            <span class="featured-book-old-price">
                                                ${{ formatPrice(book.price) }}
                                            </span>
                                        </template>
                                        <span v-else class="featured-book-price">
                                            ${{ formatPrice(book.price) }}
                                        </span>
                                    </div>
                                    <a
                                        :href="bookUrl(book)"
                                        class="featured-book-arrow"
                                        :aria-label="`View ${book.title} details`"
                                        @click.stop
                                    >
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                        <div v-if="!home.featured_books.length" class="featured-books-empty">
                            <div class="featured-books-empty-icon">
                                <i :class="`bi ${loading ? 'bi-arrow-repeat' : 'bi-book'}`"></i>
                            </div>
                            <h3>{{ loading ? 'Loading Featured Books...' : 'No Featured Books Available' }}</h3>
                            <p>
                                {{ loading
                                    ? 'Featured books are being loaded.'
                                    : 'Featured books will appear here once they are added.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="why-choose" class="why-choose-section">
            <div class="container">
                <header class="why-choose-header">
                    <span class="why-choose-eyebrow">Built for readers and resellers</span>
                    <h2 class="why-choose-title">Why Choose SecondBook</h2>
                    <p class="why-choose-description">
                        A marketplace designed to make discovering, buying, and
                        selling books simple, reliable, and rewarding.
                    </p>
                </header>
                <div class="why-choose-grid">
                    <article v-for="feature in whyFeatures" :key="feature.title" class="why-card">
                        <div class="why-card-top">
                            <div class="why-card-icon"><i :class="`bi ${feature.icon}`"></i></div>
                        </div>
                        <div class="why-card-content">
                            <h4>{{ feature.title }}</h4>
                            <p>{{ feature.text }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="special-offer" class="special-offer-section">
            <div class="container">
                <header class="special-offer-header">
                    <div class="special-offer-heading">
                        <span class="special-offer-eyebrow">Limited-time marketplace deals</span>
                        <h2 class="special-offer-title">Special Offers</h2>
                        <p class="special-offer-description">
                            Discover selected books at exceptional prices.
                            Limited quantities and special marketplace deals await.
                        </p>
                    </div>
                    <div class="special-offer-badge">
                        <i class="bi bi-tag"></i>
                        <span>Limited Offers</span>
                    </div>
                </header>
                <div class="special-offer-products">
                    <div class="special-offer-grid">
                        <article
                            v-for="book in home.special_offers"
                            :key="book.id"
                            class="special-offer-card"
                            style="cursor: pointer;"
                            @click="openBook(book)"
                        >
                            <div class="special-offer-image-wrap">
                                <a
                                    :href="bookUrl(book)"
                                    :class="['special-offer-image', { 'special-offer-placeholder': !bookImage(book) }]"
                                    :aria-label="!bookImage(book) ? `View ${book.title} details` : null"
                                    @click.stop
                                >
                                    <img
                                        v-if="bookImage(book)"
                                        :src="bookImage(book)"
                                        :alt="book.title"
                                        loading="lazy"
                                    >
                                    <i v-else class="bi bi-book"></i>
                                </a>
                                <span class="special-offer-discount">{{ book.discount_label }}</span>
                                <form class="add-to-cart-form" @click.stop>
                                    <button
                                        type="button"
                                        class="add-to-cart special-offer-cart-btn"
                                        :class="{ 'is-added': addedBookId === book.id }"
                                        :disabled="addingBookId === book.id || addedBookId === book.id"
                                        @click="addToCart(book)"
                                    >
                                        <span v-if="addingBookId === book.id" class="cart-loading-spinner"></span>
                                        <i v-else :class="`bi ${addedBookId === book.id ? 'bi-check-lg' : 'bi-cart3'}`"></i>
                                        <span>{{ cartButtonLabel(book) }}</span>
                                    </button>
                                </form>
                            </div>
                            <div class="special-offer-info">
                                <div class="special-offer-condition">Special Deal</div>
                                <h3 class="special-offer-book-title" :title="book.title">{{ book.title }}</h3>
                                <p class="special-offer-author">{{ book.author?.name || 'Unknown Author' }}</p>
                                <div class="special-offer-price">
                                    <div class="special-offer-price-main">
                                        <span class="special-offer-current-price">
                                            ${{ formatPrice(book.discounted_price) }}
                                        </span>
                                        <span class="special-offer-old-price">
                                            ${{ formatPrice(book.price) }}
                                        </span>
                                    </div>
                                    <span v-if="savedAmount(book) > 0" class="special-offer-save">
                                        Save ${{ formatPrice(savedAmount(book)) }}
                                    </span>
                                </div>
                            </div>
                        </article>
                        <div v-if="!home.special_offers.length" class="special-offer-empty">
                            <div class="special-offer-empty-icon">
                                <i :class="`bi ${loading ? 'bi-arrow-repeat' : 'bi-tags'}`"></i>
                            </div>
                            <h3>{{ loading ? 'Loading Special Offers...' : 'No Special Offers Available' }}</h3>
                            <p>
                                {{ loading
                                    ? 'Special offers are being loaded.'
                                    : 'Special offers will appear here once they are added.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="quotation" class="quotation-section">
            <div class="container">
                <div class="quotation-inner">
                    <div class="quotation-eyebrow">A Thought Worth Reading</div>
                    <div class="quotation-mark" aria-hidden="true">“</div>
                    <h2 class="quotation-title">Quote of the Day</h2>
                    <div class="quotation-divider"></div>
                    <blockquote>
                        <q>
                            The more that you read, the more things you will know.
                            The more that you learn, the more places you’ll go.
                        </q>
                        <div class="quotation-author">
                            <span class="quotation-author-line"></span>
                            <span>Dr. Seuss</span>
                        </div>
                    </blockquote>
                </div>
            </div>
        </section>

        <section id="latest-blog" class="latest-blog-section">
            <div class="container">
                <header class="latest-blog-header">
                    <div class="latest-blog-eyebrow">From the Journal</div>
                    <div class="latest-blog-heading-row">
                        <h2 class="latest-blog-title">Latest Articles</h2>
                        <a href="#" class="latest-blog-view-all">
                            <span>View All Articles</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <p class="latest-blog-intro">
                        Discover thoughtful stories, reading inspiration, book guides,
                        and useful insights from the SecondBook community.
                    </p>
                </header>
                <div class="latest-blog-grid">
                    <article v-for="article in articles" :key="article.title" class="latest-blog-card">
                        <a href="#" class="latest-blog-image image-hvr-effect">
                            <img
                                :src="`/frontend-assets/images/${article.image}`"
                                :alt="article.alt"
                                class="post-image"
                                loading="lazy"
                            >
                            <span class="latest-blog-category">{{ article.category }}</span>
                        </a>
                        <div class="latest-blog-content">
                            <div class="latest-blog-meta">
                                <span>{{ article.date }}</span>
                                <span class="latest-blog-meta-dot"></span>
                                <span>{{ article.read }}</span>
                            </div>
                            <h3><a href="#">{{ article.title }}</a></h3>
                            <p>{{ article.excerpt }}</p>
                            <a href="#" class="latest-blog-read-more">
                                <span>Read Article</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="subscribe" class="subscribe-section">
            <div class="container">
                <div class="subscribe-wrapper">
                    <div class="subscribe-heading">
                        <span class="subscribe-eyebrow">Stay Updated</span>
                        <h2 class="subscribe-title">Get Weekly Book Deals</h2>
                        <div class="subscribe-divider"></div>
                    </div>
                    <div class="subscribe-content">
                        <p class="subscribe-description">
                            Be the first to discover newly listed books, exclusive
                            marketplace discounts, and carefully selected recommendations
                            from SecondBook.
                        </p>
                        <form id="form" class="subscribe-form">
                            <div class="subscribe-input-wrapper">
                                <i class="bi bi-envelope subscribe-input-icon"></i>
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="Enter your email address"
                                    autocomplete="email"
                                    aria-label="Email address"
                                    required
                                >
                            </div>
                            <button type="submit" class="btn-subscribe" aria-label="Subscribe to newsletter">
                                <span>Subscribe</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                        <div class="subscribe-note">
                            <i class="bi bi-shield-check"></i>
                            <span>We respect your inbox. No spam, ever.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import cartService from '../services/cartService';
import catalogService from '../services/catalogService';

const props = defineProps({
    booksUrl: { type: String, required: true },
    categoriesUrl: { type: String, required: true },
    bookDetailsUrl: { type: String, required: true },
    loginUrl: { type: String, required: true },
    registerUrl: { type: String, required: true },
    sellerCtaUrl: { type: String, required: true },
    sellerCtaLabel: { type: String, required: true },
});

const activeTab = ref('all-genre');
const loading = ref(true);
const error = ref('');
const addingBookId = ref(null);
const addedBookId = ref(null);
const billboardSlider = ref(null);
let addedTimer;

const emptyHome = () => ({
    categories: [],
    featured_books: [],
    best_sellers: [],
    trending_books: [],
    new_arrivals: [],
    editor_picks: [],
    most_loved_books: [],
    budget_deals: [],
    special_offers: [],
});

const home = ref(emptyHome());

const tabs = [
    {
        id: 'all-genre',
        label: 'Best Selling',
        badge: 'Best Selling',
        icon: 'bi-bar-chart-line',
        dataKey: 'best_sellers',
        emptyTitle: 'No Best Selling Books Available',
        emptyText: 'Best selling books will appear here once they are available.',
    },
    {
        id: 'business',
        label: 'Trending Now',
        badge: 'Trending',
        icon: 'bi-fire',
        dataKey: 'trending_books',
        emptyTitle: 'No Trending Books Available',
        emptyText: 'Trending books will appear here once they are available.',
    },
    {
        id: 'technology',
        label: 'New Arrivals',
        badge: 'New Arrival',
        icon: 'bi-stars',
        dataKey: 'new_arrivals',
        emptyTitle: 'No New Arrivals Available',
        emptyText: 'New arrivals will appear here once they are added.',
    },
    {
        id: 'romantic',
        label: 'Editor Picks',
        badge: 'Editor Pick',
        icon: 'bi-pencil-square',
        dataKey: 'editor_picks',
        emptyTitle: 'No Editor Picks Available',
        emptyText: 'Editor picks will appear here once they are selected.',
    },
    {
        id: 'adventure',
        label: 'Most Loved',
        badge: 'Most Loved',
        icon: 'bi-heart',
        dataKey: 'most_loved_books',
        emptyTitle: 'No Loved Books Available',
        emptyText: 'Loved books will appear here once readers start discovering them.',
    },
    {
        id: 'fictional',
        label: 'Budget Deals',
        badge: 'Budget Deal',
        icon: 'bi-tags',
        dataKey: 'budget_deals',
        emptyTitle: 'No Budget Deals Available',
        emptyText: 'Budget deals will appear here once they are added.',
    },
].map((tab) => ({
    ...tab,
    get books() {
        return home.value[tab.dataKey];
    },
}));

const slides = [
    {
        image: 'billboard-book-01.jpg',
        imageAlt: 'SecondBook books',
        imageWord: 'BOOKS',
        imageLabel: 'READ SOMETHING NEW',
        topline: 'BOOK MARKETPLACE',
        description: 'Discover remarkable second-hand books, give your bookshelf a new story, and find your next favorite read at a price worth loving.',
        primaryAction: 'Explore Books',
        steps: [
            { number: '01', title: 'Discover', description: 'Stories worth finding' },
            { number: '02', title: 'Read', description: 'Books worth keeping' },
            { number: '03', title: 'Share', description: 'Stories worth passing on' },
        ],
    },
    {
        image: 'billboard-book-02.jpg',
        imageAlt: 'SecondBook reading collection',
        imageWord: 'READ',
        imageLabel: 'FIND YOUR NEXT STORY',
        topline: 'YOUR NEXT STORY',
        description: 'Explore carefully selected books from different categories and discover stories waiting for their next reader.',
        primaryAction: 'Browse Collection',
        steps: [
            { number: '01', title: 'Explore', description: 'Different worlds' },
            { number: '02', title: 'Choose', description: 'Books you love' },
            { number: '03', title: 'Enjoy', description: 'Every new chapter' },
        ],
    },
];

const whyFeatures = [
    {
        icon: 'bi-collection',
        title: 'Thousands of Books',
        text: 'Explore a constantly growing catalog across different genres and interests.',
    },
    {
        icon: 'bi-tags',
        title: 'Affordable Prices',
        text: 'Find quality second-hand books at prices that make reading easier on your budget.',
    },
    {
        icon: 'bi-patch-check',
        title: 'Trusted Sellers',
        text: 'Shop with confidence through verified sellers and reliable marketplace listings.',
    },
    {
        icon: 'bi-shield-lock',
        title: 'Secure Shopping',
        text: 'Enjoy a protected shopping experience from browsing to checkout.',
    },
];

const articles = [
    {
        image: 'post-img1.jpg',
        alt: 'Reading books',
        category: 'Inspiration',
        date: 'Mar 30, 2026',
        read: '4 min read',
        title: 'Why Reading Still Makes Everyday Moments Better',
        excerpt: 'Explore how books can turn ordinary moments into meaningful experiences and lasting memories.',
    },
    {
        image: 'post-img2.jpg',
        alt: 'Books and reading',
        category: 'Book Guide',
        date: 'Mar 24, 2026',
        read: '5 min read',
        title: 'How to Choose Your Next Book Without Overthinking',
        excerpt: 'A simple approach to finding your next great read based on your interests, mood, and reading habits.',
    },
    {
        image: 'post-img3.jpg',
        alt: 'Second-hand books',
        category: 'Marketplace',
        date: 'Mar 18, 2026',
        read: '6 min read',
        title: "The Smart Reader's Guide to Buying Pre-Owned Books",
        excerpt: 'Learn what to look for when buying second-hand books and how to find quality editions at better prices.',
    },
];

async function loadHome() {
    loading.value = true;
    error.value = '';

    try {
        home.value = {
            ...emptyHome(),
            ...await catalogService.getHome(),
        };
    } catch (requestError) {
        console.error('Unable to load Home page data:', requestError);
        error.value = requestError.response?.data?.message
            || 'Home page data could not be loaded. Please try again.';
    } finally {
        loading.value = false;
    }
}

function bookImage(book) {
    if (book.cover_url) {
        return book.cover_url;
    }

    if (!book.cover) {
        return null;
    }

    return /^https?:\/\//i.test(book.cover)
        ? book.cover
        : `/storage/${book.cover}`;
}

function imageUrl(path) {
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (/^(storage|uploads)\//.test(path)) return `/${path}`;
    return `/storage/${String(path).replace(/^\/+/, '')}`;
}

function hasDiscount(book) {
    return Boolean(book.discount_label);
}

function formatPrice(value) {
    return Number(value ?? 0).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function cartButtonLabel(book) {
    if (addingBookId.value === book.id) {
        return 'Adding...';
    }

    return addedBookId.value === book.id ? 'Added' : 'Add to Cart';
}

function savedAmount(book) {
    return Math.max(0, Number(book.price) - Number(book.discounted_price));
}

function bookUrl(book) {
    return `${props.bookDetailsUrl}/${book.id}`;
}

function categoryBooksUrl(category) {
    const url = new URL(props.booksUrl, window.location.origin);
    url.searchParams.set('category', category.id);
    return `${url.pathname}${url.search}`;
}

function openBook(book) {
    window.location.href = bookUrl(book);
}

function updateHeaderCartCount(count) {
    let badge = document.getElementById('header-cart-count');

    if (!badge && count > 0) {
        const cartLink = document.querySelector('.header-cart');

        if (!cartLink) {
            return;
        }

        badge = document.createElement('span');
        badge.id = 'header-cart-count';
        badge.className = 'cart-count';
        cartLink.appendChild(badge);
    }

    if (!badge) {
        return;
    }

    badge.textContent = count > 99 ? '99+' : String(count);
    badge.style.display = count > 0 ? 'inline-flex' : 'none';
    badge.classList.remove('cart-count-bump');
    void badge.offsetWidth;
    badge.classList.add('cart-count-bump');
}

function showCartError(message) {
    if (typeof window.Swal === 'undefined') {
        window.alert(message);
        return;
    }

    window.Swal.fire({
        title: 'Unable to add book',
        text: message,
        icon: 'error',
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
            actions: 'cart-auth-actions',
            confirmButton: 'logout-confirm-btn',
        },
    });
}

function showAuthenticationPrompt() {
    if (typeof window.Swal === 'undefined') {
        window.location.href = props.loginUrl;
        return;
    }

    window.Swal.fire({
        title: 'Sign in to continue',
        text: 'Please log in or create an account to add books to your cart.',
        icon: 'info',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Log In',
        denyButtonText: 'Register',
        cancelButtonText: 'Cancel',
        buttonsStyling: false,
        customClass: {
            actions: 'cart-auth-actions',
            confirmButton: 'logout-confirm-btn',
            denyButton: 'logout-cancel-btn',
            cancelButton: 'logout-cancel-btn',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = props.loginUrl;
        } else if (result.isDenied) {
            window.location.href = props.registerUrl;
        }
    });
}

async function addToCart(book) {
    if (addingBookId.value !== null) {
        return;
    }

    addingBookId.value = book.id;

    try {
        const result = await cartService.addItem(book.id);

        if (result.success !== true) {
            throw new Error(result.message || 'Unable to add the book to cart.');
        }

        if (result.cart_count !== undefined) {
            updateHeaderCartCount(Number(result.cart_count));
        }

        addedBookId.value = book.id;
        window.clearTimeout(addedTimer);
        addedTimer = window.setTimeout(() => {
            if (addedBookId.value === book.id) {
                addedBookId.value = null;
            }
        }, 1200);

        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                icon: 'success',
                title: 'Added to Cart',
                text: result.message || 'Book added to cart successfully!',
                timer: 1600,
                showConfirmButton: false,
                customClass: {
                    icon: 'sb-cart-success-icon',
                },
            });
        }
    } catch (requestError) {
        if (requestError.response?.status === 401) {
            showAuthenticationPrompt();
        } else {
            console.error('Add to cart error:', requestError);
            showCartError(
                requestError.response?.data?.message
                    || requestError.message
                    || 'Unable to add the book to cart.'
            );
        }
    } finally {
        addingBookId.value = null;
    }
}

onMounted(async () => {
    await nextTick();

    const jquery = window.jQuery;
    const slider = billboardSlider.value;

    if (jquery && slider && typeof jquery(slider).slick === 'function') {
        jquery(slider).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
            prevArrow: jquery('.billboard-arrow-prev'),
            nextArrow: jquery('.billboard-arrow-next'),
            dots: false,
            autoplay: true,
            autoplaySpeed: 6000,
            speed: 700,
            fade: true,
            cssEase: 'ease-in-out',
            adaptiveHeight: false,
            pauseOnHover: true,
            pauseOnFocus: true,
            swipe: true,
            touchMove: true,
        });
    } else {
        console.error('The Home page billboard slider could not be initialized.');
    }

    await loadHome();
});

onBeforeUnmount(() => {
    window.clearTimeout(addedTimer);

    const jquery = window.jQuery;
    const slider = billboardSlider.value;

    if (jquery && slider && jquery(slider).hasClass('slick-initialized')) {
        jquery(slider).slick('unslick');
    }
});
</script>
