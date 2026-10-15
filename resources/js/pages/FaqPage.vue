<script setup>
import { computed, onMounted, ref } from 'vue';
import contentService from '../services/contentService';

const props = defineProps({ contactUrl: { type: String, required: true } });
const questions = ref([]);
const categories = ref([]);
const selectedCategory = ref('');
const search = ref('');
const loading = ref(true);
const error = ref('');
const filtered = computed(() => questions.value.filter((faq) => {
    const matchesCategory = !selectedCategory.value || faq.category === selectedCategory.value;
    const phrase = search.value.trim().toLowerCase();
    const matchesSearch = !phrase || `${faq.question} ${faq.answer}`.toLowerCase().includes(phrase);
    return matchesCategory && matchesSearch;
}));

onMounted(async () => {
    try {
        const response = await contentService.getFaqs();
        questions.value = response.data || [];
        categories.value = response.categories || [];
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Frequently asked questions could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="sb-faq-page">
        <div class="sb-faq-progress" aria-hidden="true"></div>
        <section class="sb-faq-hero"><div class="sb-faq-hero-decoration sb-faq-decoration-one"></div><div class="sb-faq-hero-decoration sb-faq-decoration-two"></div><div class="container"><div class="sb-faq-hero-content">
            <div class="sb-faq-eyebrow"><span class="sb-faq-eyebrow-icon"><i class="bi bi-question-circle"></i></span><span>SecondBook Help Center</span></div>
            <h1>Everything you need to <span>know.</span></h1>
            <p>Find clear answers to the most common questions about buying, selling, orders, payments and your SecondBook account.</p>
            <div class="sb-faq-hero-meta"><div class="sb-faq-meta-item"><span class="sb-faq-meta-icon"><i class="bi bi-lightning-charge"></i></span><div><strong>Quick answers</strong><span>Find what you need faster</span></div></div><div class="sb-faq-meta-divider"></div><div class="sb-faq-meta-item"><span class="sb-faq-meta-icon"><i class="bi bi-headset"></i></span><div><strong>Need more help?</strong><span>Our support team is here</span></div></div></div>
        </div></div></section>
        <section class="sb-faq-section"><div class="container">
            <div class="sb-faq-tools"><input v-model="search" type="search" placeholder="Search questions…" aria-label="Search frequently asked questions"><select v-model="selectedCategory" aria-label="Filter by category"><option value="">All topics</option><option v-for="category in categories" :key="category" :value="category">{{ category }}</option></select></div>
            <div v-if="loading" class="sb-faq-empty" role="status">Loading answers…</div>
            <div v-else-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
            <div v-else-if="filtered.length" class="sb-faq-list">
                <details v-for="faq in filtered" :key="faq.id" class="sb-faq-item"><summary><span>{{ faq.question }}</span><i class="bi bi-plus-lg"></i></summary><div class="sb-faq-answer">{{ faq.answer }}</div></details>
            </div>
            <div v-else class="sb-faq-empty"><h2>No matching answers</h2><p>Try another search term or contact our team.</p><a :href="contactUrl">Contact us</a></div>
        </div></section>
    </div>
</template>
