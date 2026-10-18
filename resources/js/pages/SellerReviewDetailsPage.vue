<script setup>
import { computed, onMounted, ref } from 'vue';
import sellerReviewService from '../services/sellerReviewService';

const props = defineProps({
    reviewDataUrl: { type: String, required: true },
    reviewsUrl: { type: String, required: true },
});

const review = ref(null);
const error = ref('');
const loading = ref(true);
const rating = computed(() => Number(review.value?.rating ?? 0));

const submittedDate = computed(() => review.value?.created_date || '—');

onMounted(async () => {
    try {
        review.value = await sellerReviewService.getReview(props.reviewDataUrl);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Review details could not be loaded.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="seller-reviews-page">
        <div class="seller-page-heading">
            <div>
                <h1>Review Details</h1>
                <p>View detailed information about this customer review.</p>
            </div>
            <a :href="reviewsUrl" class="seller-review-show-back">
                <i class="bi bi-arrow-left"></i><span>Back to Reviews</span>
            </a>
        </div>

        <div v-if="loading" class="seller-review-show-grid" role="status" aria-live="polite">
            <div class="seller-review-show-main">Loading review details…</div>
        </div>
        <div v-else-if="error" class="seller-review-show-grid" role="alert">
            <div class="seller-review-show-main">{{ error }}</div>
        </div>
        <div v-else-if="review" class="seller-review-show-grid">
            <div class="seller-review-show-main">
                <div class="seller-review-show-header">
                    <div class="seller-review-show-header-icon"><i class="bi bi-star-half"></i></div>
                    <div><h5>Customer Review</h5><p>Review submitted {{ submittedDate }}</p></div>
                </div>
                <div class="seller-review-show-rating-section">
                    <div class="seller-review-show-rating-label"><span>Rating</span><strong>{{ rating.toFixed(1) }} / 5.0</strong></div>
                    <div class="seller-review-show-stars" :aria-label="`${rating} out of 5 stars`">
                        <i v-for="star in 5" :key="star" class="bi" :class="star <= rating ? 'bi-star-fill' : 'bi-star'"></i>
                    </div>
                </div>
                <div class="seller-review-show-comment">
                    <div class="seller-review-show-section-label"><i class="bi bi-chat-square-text"></i><span>Customer Comment</span></div>
                    <div v-if="review.comment" class="seller-review-show-comment-box"><i class="bi bi-quote"></i><p>{{ review.comment }}</p></div>
                    <div v-else class="seller-review-show-empty-comment">
                        <i class="bi bi-chat-square"></i><div><strong>No comment provided</strong><span>The customer submitted a rating without written feedback.</span></div>
                    </div>
                </div>
                <div class="seller-review-show-meta">
                    <div class="seller-review-show-meta-item"><span>Review ID</span><strong>#{{ review.id }}</strong></div>
                    <div class="seller-review-show-meta-item"><span>Submitted</span><strong>{{ submittedDate }}</strong></div>
                    <div class="seller-review-show-meta-item"><span>Time</span><strong>{{ review.created_time }}</strong></div>
                </div>
            </div>

            <div class="seller-review-show-side">
                <div class="seller-review-show-card">
                    <div class="seller-review-show-card-heading"><div class="seller-review-show-card-icon"><i class="bi bi-person"></i></div><div><h5>Buyer</h5><span>Customer information</span></div></div>
                    <div class="seller-review-show-buyer">
                        <div class="seller-review-show-avatar">{{ review.buyer.initial }}</div>
                        <div class="seller-review-show-buyer-info"><strong>{{ review.buyer.name }}</strong><span>{{ review.buyer.email || 'No email available' }}</span></div>
                    </div>
                </div>

                <div class="seller-review-show-card">
                    <div class="seller-review-show-card-heading"><div class="seller-review-show-card-icon"><i class="bi bi-book"></i></div><div><h5>Book</h5><span>Reviewed book</span></div></div>
                    <div v-if="review.book" class="seller-review-show-book">
                        <div class="seller-review-show-book-cover"><img v-if="review.book.cover_url" :src="review.book.cover_url" :alt="review.book.title"><i v-else class="bi bi-book"></i></div>
                        <div class="seller-review-show-book-info"><strong>{{ review.book.title || '—' }}</strong><span v-if="review.book.author">{{ review.book.author }}</span><small v-if="review.book.isbn">ISBN: {{ review.book.isbn }}</small></div>
                    </div>
                    <div v-else class="seller-review-show-deleted-book"><i class="bi bi-book"></i><div><strong>Book unavailable</strong><span>This book is no longer available.</span></div></div>
                </div>

                <div class="seller-review-show-card">
                    <div class="seller-review-show-card-heading"><div class="seller-review-show-card-icon"><i class="bi bi-info-circle"></i></div><div><h5>Review Information</h5><span>Additional details</span></div></div>
                    <div class="seller-review-show-details">
                        <div><span>Status</span><strong class="seller-review-show-status"><i class="bi bi-check-circle-fill"></i>Published</strong></div>
                        <div><span>Rating</span><strong>{{ rating.toFixed(1) }} / 5.0</strong></div>
                        <div><span>Created</span><strong>{{ review.created_full }}</strong></div>
                        <div v-if="review.updated_full"><span>Updated</span><strong>{{ review.updated_full }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
