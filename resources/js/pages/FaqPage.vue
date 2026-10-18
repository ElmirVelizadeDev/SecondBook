<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import contentService from '../services/contentService';

const props = defineProps({
    contactUrl: {
        type: String,
        required: true,
    },
});

const questions = ref([]);
const categories = ref([]);
const selectedCategory = ref('');
const search = ref('');
const openFaq = ref(null);
const loading = ref(true);
const error = ref('');
const scrollProgress = ref(0);

const filtered = computed(() => {
    const phrase = search.value.trim().toLowerCase();

    return questions.value.filter((faq) => {
        const matchesCategory =
            !selectedCategory.value ||
            faq.category === selectedCategory.value;

        const matchesSearch =
            !phrase ||
            `${faq.question ?? ''} ${faq.answer ?? ''}`
                .toLowerCase()
                .includes(phrase);

        return matchesCategory && matchesSearch;
    });
});

function toggleFaq(id) {
    openFaq.value = openFaq.value === id ? null : id;
}

function selectCategory(category) {
    selectedCategory.value =
        selectedCategory.value === category ? '' : category;

    openFaq.value = null;
}

function resetFilters() {
    search.value = '';
    selectedCategory.value = '';
    openFaq.value = null;
}

function updateScrollProgress() {
    const scrollableHeight =
        document.documentElement.scrollHeight - window.innerHeight;

    scrollProgress.value =
        scrollableHeight > 0
            ? (window.scrollY / scrollableHeight) * 100
            : 0;
}

onMounted(async () => {
    window.addEventListener('scroll', updateScrollProgress, {
        passive: true,
    });

    updateScrollProgress();

    try {
        const response = await contentService.getFaqs();

        questions.value = Array.isArray(response.data)
            ? response.data
            : [];

        categories.value = Array.isArray(response.categories)
            ? response.categories
            : [
                ...new Set(
                    questions.value
                        .map((faq) => faq.category)
                        .filter(Boolean)
                ),
            ];
    } catch (exception) {
        error.value =
            exception.response?.data?.message ||
            'Frequently asked questions could not be loaded.';
    } finally {
        loading.value = false;
    }
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateScrollProgress);
});
</script>

<template>
    <div class="sb-faq-page">
        <div
            class="sb-faq-progress"
            :style="{ width: `${scrollProgress}%` }"
            aria-hidden="true"
        ></div>

        <!-- HERO -->
        <section class="sb-faq-hero">
            <div
                class="sb-faq-hero-decoration sb-faq-decoration-one"
            ></div>
            <div
                class="sb-faq-hero-decoration sb-faq-decoration-two"
            ></div>

            <div class="container">
                <div class="sb-faq-hero-content">
                    <div class="sb-faq-eyebrow">
                        <span class="sb-faq-eyebrow-icon">
                            <i class="bi bi-question-circle"></i>
                        </span>
                        <span>SecondBook Help Center</span>
                    </div>

                    <h1>
                        Everything you need to
                        <span>know.</span>
                    </h1>

                    <p>
                        Find clear answers to the most common questions
                        about buying, selling, orders, payments and your
                        SecondBook account.
                    </p>

                    <div class="sb-faq-hero-meta">
                        <div class="sb-faq-meta-item">
                            <span class="sb-faq-meta-icon">
                                <i class="bi bi-lightning-charge"></i>
                            </span>

                            <div>
                                <strong>Quick answers</strong>
                                <span>Find what you need faster</span>
                            </div>
                        </div>

                        <div class="sb-faq-meta-divider"></div>

                        <div class="sb-faq-meta-item">
                            <span class="sb-faq-meta-icon">
                                <i class="bi bi-headset"></i>
                            </span>

                            <div>
                                <strong>Need more help?</strong>
                                <span>Our support team is here</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ SECTION -->
        <section class="sb-faq-section">
            <div class="container">
                <div class="sb-faq-section-heading">
                    <div>
                        <div class="sb-faq-section-label">
                            <i class="bi bi-book-half"></i>
                            HELP &amp; SUPPORT
                        </div>

                        <h2>
                            Answers to your
                            <span>questions.</span>
                        </h2>
                    </div>

                    <p>
                        Explore the topics below or search for a specific
                        answer. We're here to make your SecondBook
                        experience simple.
                    </p>
                </div>

                <!-- SEARCH -->
                <div class="sb-faq-search-wrapper">
                    <div class="sb-faq-search">
                        <span class="sb-faq-search-icon">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            v-model="search"
                            type="search"
                            placeholder="Search questions and answers..."
                            aria-label="Search frequently asked questions"
                        />

                        <button
                            v-if="search"
                            class="sb-faq-search-clear"
                            type="button"
                            aria-label="Clear search"
                            @click="search = ''"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- CATEGORY FILTER -->
                <div
                    v-if="categories.length"
                    class="sb-faq-filter-row"
                >
                    <div class="sb-faq-filter-title">
                        <i class="bi bi-grid"></i>
                        Browse by topic
                    </div>

                    <div class="sb-faq-categories">
                        <button
                            type="button"
                            class="sb-faq-category"
                            :class="{ active: !selectedCategory }"
                            @click="selectCategory('')"
                        >
                            All topics
                        </button>

                        <button
                            v-for="category in categories"
                            :key="category"
                            type="button"
                            class="sb-faq-category"
                            :class="{
                                active: selectedCategory === category,
                            }"
                            @click="selectCategory(category)"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>

                <!-- LOADING -->
                <div
                    v-if="loading"
                    class="sb-faq-empty"
                    role="status"
                >
                    <span class="sb-faq-empty-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </span>

                    <h3>Loading answers</h3>

                    <p>
                        Please wait while we retrieve the latest
                        frequently asked questions.
                    </p>
                </div>

                <!-- ERROR -->
                <div
                    v-else-if="error"
                    class="sb-faq-empty"
                    role="alert"
                >
                    <span class="sb-faq-empty-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </span>

                    <h3>Something went wrong</h3>
                    <p>{{ error }}</p>

                    <a
                        class="sb-faq-reset-btn"
                        :href="contactUrl"
                    >
                        Contact support
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>

                <!-- FAQ CONTENT -->
                <div v-else class="sb-faq-content">
                    <div class="sb-faq-main">
                        <div
                            v-if="filtered.length"
                            class="sb-faq-list"
                        >
                            <article
                                v-for="(faq) in filtered"
                                :key="faq.id"
                                class="sb-faq-item"
                                :class="{
                                    active: openFaq === faq.id,
                                }"
                            >
                                <button
                                    type="button"
                                    class="sb-faq-question"
                                    :aria-expanded="
                                        openFaq === faq.id
                                    "
                                    @click="toggleFaq(faq.id)"
                                >
                                    <span class="sb-faq-question-left">
                                        <span class="sb-faq-icon">
                                            <i
                                                class="bi bi-chat-square-text"
                                            ></i>
                                        </span>

                                        <span
                                            class="sb-faq-question-content"
                                        >
                                            <span
                                                v-if="faq.category"
                                                class="sb-faq-question-category"
                                            >
                                                {{ faq.category }}
                                            </span>

                                            <span
                                                class="sb-faq-question-text"
                                            >
                                                {{ faq.question }}
                                            </span>
                                        </span>
                                    </span>

                                    <span class="sb-faq-toggle">
                                        <i
                                            class="bi"
                                            :class="
                                                openFaq === faq.id
                                                    ? 'bi-dash-lg'
                                                    : 'bi-plus-lg'
                                            "
                                        ></i>
                                    </span>
                                </button>

                                <div
                                    v-if="openFaq === faq.id"
                                    class="sb-faq-answer"
                                >
                                    <div
                                        class="sb-faq-answer-inner"
                                    >
                                        <div
                                            class="sb-faq-answer-line"
                                        ></div>

                                        <div
                                            class="sb-faq-answer-content"
                                        >
                                            <div
                                                class="sb-faq-answer-label"
                                            >
                                                <i
                                                    class="bi bi-check-circle"
                                                ></i>
                                                ANSWER
                                            </div>

                                            <p>{{ faq.answer }}</p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- EMPTY RESULTS -->
                        <div
                            v-else
                            class="sb-faq-empty sb-faq-no-results"
                        >
                            <span class="sb-faq-empty-icon">
                                <i class="bi bi-search"></i>
                            </span>

                            <span class="sb-faq-empty-label">
                                NO RESULTS FOUND
                            </span>

                            <h3>No matching answers</h3>

                            <p>
                                Try another search term or choose a
                                different topic to find the answer
                                you're looking for.
                            </p>

                            <button
                                type="button"
                                class="sb-faq-reset-btn"
                                @click="resetFilters"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Clear filters
                            </button>
                        </div>
                    </div>

                    <!-- SIDE CARD -->
                    <aside class="sb-faq-side-card">
                        <span class="sb-faq-side-icon">
                            <i class="bi bi-headset"></i>
                        </span>

                        <span class="sb-faq-side-label">
                            PERSONAL SUPPORT
                        </span>

                        <h3>
                            Still need
                            <br />
                            a little help?
                        </h3>

                        <p>
                            Can't find the answer you're looking for?
                            Get in touch with our team and we'll help
                            you find the right solution.
                        </p>

                        <div class="sb-faq-side-divider"></div>

                        <div class="sb-faq-side-stat">
                            <span class="sb-faq-side-stat-icon">
                                <i class="bi bi-chat-dots"></i>
                            </span>

                            <div>
                                <strong>1:1</strong>
                                <span>Personal assistance</span>
                            </div>
                        </div>

                        <a
                            class="sb-faq-side-contact"
                            :href="contactUrl"
                        >
                            Contact our team
                            <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </aside>
                </div>
            </div>
        </section>

        <!-- CONTACT CTA -->
        <section class="sb-faq-contact">
            <div class="container">
                <div class="sb-faq-contact-box">
                    <div class="sb-faq-contact-decoration"></div>

                    <span class="sb-faq-contact-icon">
                        <i class="bi bi-envelope-paper"></i>
                    </span>

                    <div class="sb-faq-contact-content">
                        <span>WE'RE HERE FOR YOU</span>

                        <h2>Let's find your answer.</h2>

                        <p>
                            Every question deserves an answer.
                            Reach out to SecondBook support for
                            assistance with your account, orders,
                            books or seller experience.
                        </p>
                    </div>

                    <a
                        class="sb-faq-contact-btn"
                        :href="contactUrl"
                    >
                        Get in touch
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </section>
    </div>
</template>
