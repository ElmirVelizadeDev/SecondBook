<script setup>
import { computed, onMounted, ref } from 'vue';
import contentService from '../services/contentService';

const props = defineProps({ faqUrl: { type: String, required: true }, contactUrl: { type: String, required: true } });
const faqs = ref([]);
const search = ref('');
const loading = ref(true);
const error = ref('');
const results = computed(() => {
    const query = search.value.trim().toLowerCase();
    if (!query) return faqs.value.slice(0, 6);
    return faqs.value.filter((faq) => `${faq.question} ${faq.answer}`.toLowerCase().includes(query));
});

onMounted(async () => {
    try {
        const response = await contentService.getFaqs();
        faqs.value = response.data || [];
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Help content could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <main class="sb-help-page">
        <section class="sb-help-hero"><div class="sb-help-hero-decoration sb-help-decoration-one"></div><div class="sb-help-hero-decoration sb-help-decoration-two"></div><div class="sb-help-hero-decoration sb-help-decoration-three"></div><div class="container">
            <div class="sb-help-hero-content"><div class="sb-help-eyebrow"><span class="sb-help-eyebrow-icon"><i class="bi bi-life-preserver"></i></span><span>SecondBook Help Center</span></div><h1>How can we <span>help?</span></h1><p>Find helpful answers about buying books, selling books, orders, payments, shipping and your SecondBook account.</p>
                <form class="sb-help-search-wrapper" @submit.prevent><div class="sb-help-search"><span class="sb-help-search-icon"><i class="bi bi-search"></i></span><input v-model="search" type="search" placeholder="Search for help…" aria-label="Search Help Center"><button type="submit" class="sb-help-search-button"><span>Search</span><i class="bi bi-arrow-right"></i></button></div></form>
            </div>
        </div></section>
        <section class="sb-help-section"><div class="container">
            <div class="sb-help-heading"><span>QUICK ANSWERS</span><h2>Popular help topics</h2></div>
            <div v-if="loading" class="sb-help-empty" role="status">Loading help topics…</div>
            <div v-else-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
            <div v-else class="sb-help-results">
                <article v-for="faq in results" :key="faq.id" class="sb-help-card"><span class="sb-help-card-category">{{ faq.category || 'Help' }}</span><h3>{{ faq.question }}</h3><p>{{ faq.answer }}</p></article>
                <div v-if="!results.length" class="sb-help-empty"><h3>No results found</h3><p>Try a different phrase or contact our support team.</p></div>
            </div>
            <div class="sb-help-contact"><a :href="faqUrl">Browse all FAQs<i class="bi bi-arrow-right"></i></a><a :href="contactUrl">Contact support<i class="bi bi-arrow-right"></i></a></div>
        </div></section>
    </main>
</template>
