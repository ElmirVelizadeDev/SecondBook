<script setup>
import { onMounted, reactive, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    orderId: { type: [String, Number], required: true },
    ordersUrl: { type: String, required: true },
});

const eligibility = ref(null);
const loading = ref(true);
const submitting = ref(false);
const error = ref('');
const success = ref('');
const review = reactive({ rating: 5, comment: '' });

async function loadReview() {
    try {
        eligibility.value = await accountService.getReviewEligibility(props.orderId);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'This order is not eligible for a review.';
    } finally {
        loading.value = false;
    }
}

async function submitReview() {
    if (submitting.value) return;
    submitting.value = true;
    error.value = '';
    try {
        const response = await accountService.submitReview(props.orderId, review);
        success.value = response.message || 'Your review has been submitted.';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your review could not be submitted.';
    } finally {
        submitting.value = false;
    }
}

onMounted(loadReview);
</script>

<template>
    <main class="review-page sb-review-page">
        <section class="review-hero"><div class="container"><nav class="review-breadcrumb"><a href="/frontend">Home</a><i class="bi bi-chevron-right"></i><a :href="ordersUrl">My Orders</a><i class="bi bi-chevron-right"></i><span>Write a Review</span></nav><span class="review-eyebrow"><i class="bi bi-star"></i>Share your experience</span><h1>Your words help <span>other readers.</span></h1><p>Tell us what you thought of your purchase.</p></div></section>
        <section class="review-section"><div class="container">
            <div v-if="loading" class="review-card" role="status">Checking review eligibility…</div>
            <div v-else class="review-card">
                <div v-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
                <div v-if="success" class="alert alert-success" role="status">{{ success }} <a :href="ordersUrl">Return to orders</a></div>
                <template v-if="eligibility && !success">
                    <h2>{{ eligibility.data?.book?.title || eligibility.book?.title || 'Your book' }}</h2>
                    <form @submit.prevent="submitReview">
                        <label for="rating">Your rating</label>
                        <select id="rating" v-model.number="review.rating" class="form-select" required>
                            <option v-for="rating in [5, 4, 3, 2, 1]" :key="rating" :value="rating">{{ rating }} {{ rating === 1 ? 'star' : 'stars' }}</option>
                        </select>
                        <label for="comment">Review <span>(optional)</span></label>
                        <textarea id="comment" v-model="review.comment" class="form-control" rows="5" maxlength="2000" placeholder="Share your thoughts about this book."></textarea>
                        <button type="submit" class="review-submit-btn" :disabled="submitting">{{ submitting ? 'Submitting…' : 'Submit Review' }}</button>
                    </form>
                </template>
            </div>
        </div></section>
    </main>
</template>
