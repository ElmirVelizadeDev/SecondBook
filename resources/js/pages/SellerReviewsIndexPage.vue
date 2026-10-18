<script setup>
import { computed, onMounted, ref } from 'vue';
import sellerReviewService from '../services/sellerReviewService';

const props = defineProps({
    indexDataUrl: { type: String, required: true },
    reviewsUrl: { type: String, required: true },
});

const initialParams = new URLSearchParams(window.location.search);
const search = ref(initialParams.get('search') || '');
const ratingFilter = ref(initialParams.get('rating') || '');
const responseData = ref(null);
const error = ref('');
const loading = ref(true);

const reviews = computed(() => responseData.value?.reviews?.data || []);
const statistics = computed(() => responseData.value?.statistics || null);
const pagination = computed(() => responseData.value?.reviews || null);
const hasFilters = computed(() => Boolean(search.value || ratingFilter.value));
const pageLinks = computed(() => pagination.value?.links?.slice(1, -1) || []);
const reviewCountLabel = computed(() => {
    const total = pagination.value?.total || 0;
    return `${total} ${total === 1 ? 'review' : 'reviews'}`;
});

async function loadReviews() {
    loading.value = true;
    error.value = '';

    try {
        const params = Object.fromEntries(new URLSearchParams(window.location.search).entries());
        responseData.value = await sellerReviewService.getReviews(props.indexDataUrl, params);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Reviews could not be loaded.';
    } finally {
        loading.value = false;
    }
}

onMounted(loadReviews);
</script>

<template>
    <div class="seller-reviews-page">
        <div class="seller-page-heading">
            <div>
                <h1>Reviews</h1>
                <p>Monitor customer reviews and ratings for your books.</p>
            </div>
        </div>

        <div class="seller-reviews-stats">
            <div class="seller-reviews-stat-card">
                <div class="seller-reviews-stat-icon reviews"><i class="bi bi-chat-square-text"></i></div>
                <div class="seller-reviews-stat-content"><span>Total Reviews</span><h3>{{ statistics?.total_reviews ?? '—' }}</h3></div>
            </div>
            <div class="seller-reviews-stat-card">
                <div class="seller-reviews-stat-icon rating"><i class="bi bi-star-fill"></i></div>
                <div class="seller-reviews-stat-content"><span>Average Rating</span><h3>{{ statistics?.average_rating ?? '—' }}</h3></div>
            </div>
            <div class="seller-reviews-stat-card">
                <div class="seller-reviews-stat-icon five"><i class="bi bi-stars"></i></div>
                <div class="seller-reviews-stat-content"><span>5 Star Reviews</span><h3>{{ statistics?.five_star_reviews ?? '—' }}</h3></div>
            </div>
            <div class="seller-reviews-stat-card">
                <div class="seller-reviews-stat-icon one"><i class="bi bi-star"></i></div>
                <div class="seller-reviews-stat-content"><span>1 Star Reviews</span><h3>{{ statistics?.one_star_reviews ?? '—' }}</h3></div>
            </div>
        </div>

        <div class="seller-reviews-panel">
            <div class="seller-reviews-panel-header">
                <div class="seller-reviews-panel-title">
                    <div class="seller-reviews-panel-icon"><i class="bi bi-star-half"></i></div>
                    <div><h5>Customer Reviews</h5><p>See what buyers think about your books.</p></div>
                </div>
                <div class="seller-reviews-count"><i class="bi bi-chat-square-text"></i>{{ pagination ? reviewCountLabel : '—' }}</div>
            </div>

            <div class="seller-reviews-filter">
                <form :action="reviewsUrl" method="GET">
                    <div class="seller-reviews-search">
                        <div class="seller-reviews-search-input">
                            <i class="bi bi-search"></i>
                            <input v-model="search" type="text" name="search" placeholder="Search reviews...">
                        </div>
                        <div class="seller-reviews-rating-wrapper">
                            <i class="bi bi-star"></i>
                            <select v-model="ratingFilter" name="rating" class="seller-reviews-rating-select">
                                <option value="">All Ratings</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <button type="submit" class="seller-reviews-search-button"><i class="bi bi-search"></i><span>Search</span></button>
                        <a v-if="hasFilters" :href="reviewsUrl" class="seller-reviews-reset-button">
                            <i class="bi bi-arrow-counterclockwise"></i><span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>

            <div v-if="loading" class="seller-reviews-empty" role="status" aria-live="polite">
                <div class="seller-reviews-empty-icon"><i class="bi bi-star"></i></div>
                <h5>Loading Reviews</h5>
                <p>Please wait while customer reviews are loaded.</p>
            </div>
            <div v-else-if="error" class="seller-reviews-empty" role="alert">
                <div class="seller-reviews-empty-icon"><i class="bi bi-exclamation-circle"></i></div>
                <h5>Reviews Could Not Be Loaded</h5>
                <p>{{ error }}</p>
            </div>
            <template v-else-if="reviews.length">
                <div class="table-responsive">
                    <table class="seller-reviews-table">
                        <thead><tr><th>Book</th><th>Buyer</th><th>Rating</th><th>Review</th><th>Date</th><th>Action</th></tr></thead>
                        <tbody>
                            <tr v-for="review in reviews" :key="review.id">
                                <td>
                                    <div class="seller-review-book">
                                        <div class="seller-review-book-cover">
                                            <img v-if="review.book?.cover_url" :src="review.book.cover_url" :alt="review.book.title">
                                            <i v-else class="bi bi-book"></i>
                                        </div>
                                        <div class="seller-review-book-info">
                                            <strong>{{ review.book?.title || 'Deleted Book' }}</strong>
                                            <span v-if="review.book?.author">{{ review.book.author }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="seller-review-buyer">
                                        <div class="seller-review-buyer-avatar">{{ review.buyer.initial }}</div>
                                        <div class="seller-review-buyer-info"><strong>{{ review.buyer.name }}</strong><span>{{ review.buyer.email || 'No email' }}</span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="seller-review-rating">
                                        <div class="seller-review-stars" :aria-label="`${review.rating} out of 5 stars`">
                                            <i v-for="star in 5" :key="star" class="bi" :class="star <= review.rating ? 'bi-star-fill' : 'bi-star'"></i>
                                        </div>
                                        <span class="seller-review-rating-value">{{ Number(review.rating).toFixed(1) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="seller-review-comment">
                                        <p v-if="review.comment">{{ review.comment }}</p>
                                        <span v-else class="seller-review-no-comment"><i class="bi bi-chat-square"></i>No comment provided</span>
                                    </div>
                                </td>
                                <td><div class="seller-review-date"><strong>{{ review.created_date }}</strong><span>{{ review.created_time }}</span></div></td>
                                <td><a :href="review.show_url" class="seller-review-view-button" title="View Review"><i class="bi bi-eye"></i><span>View</span></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="pagination && pagination.last_page > 1" class="seller-reviews-pagination">
                    <nav class="d-flex justify-items-center justify-content-between" aria-label="Reviews pagination">
                        <div class="d-flex justify-content-between flex-fill d-sm-none">
                            <ul class="pagination">
                                <li v-if="pagination.prev_page_url" class="page-item"><a class="page-link" :href="pagination.prev_page_url" rel="prev">Previous</a></li>
                                <li v-else class="page-item disabled" aria-disabled="true"><span class="page-link">Previous</span></li>
                                <li v-if="pagination.next_page_url" class="page-item"><a class="page-link" :href="pagination.next_page_url" rel="next">Next</a></li>
                                <li v-else class="page-item disabled" aria-disabled="true"><span class="page-link">Next</span></li>
                            </ul>
                        </div>
                        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                            <div><p class="small text-muted">Showing <span class="fw-semibold">{{ pagination.from }}</span> to <span class="fw-semibold">{{ pagination.to }}</span> of <span class="fw-semibold">{{ pagination.total }}</span> results</p></div>
                            <div>
                                <ul class="pagination">
                                    <li v-if="pagination.prev_page_url" class="page-item"><a class="page-link" :href="pagination.prev_page_url" rel="prev" aria-label="Previous">&lsaquo;</a></li>
                                    <li v-else class="page-item disabled" aria-disabled="true" aria-label="Previous"><span class="page-link" aria-hidden="true">&lsaquo;</span></li>
                                    <li v-for="(link, index) in pageLinks" :key="`${link.label}-${index}`" class="page-item" :class="{ active: link.active, disabled: !link.url }" :aria-current="link.active ? 'page' : null" :aria-disabled="!link.url ? 'true' : null">
                                        <span v-if="!link.url || link.active" class="page-link">{{ link.label }}</span>
                                        <a v-else class="page-link" :href="link.url">{{ link.label }}</a>
                                    </li>
                                    <li v-if="pagination.next_page_url" class="page-item"><a class="page-link" :href="pagination.next_page_url" rel="next" aria-label="Next">&rsaquo;</a></li>
                                    <li v-else class="page-item disabled" aria-disabled="true" aria-label="Next"><span class="page-link" aria-hidden="true">&rsaquo;</span></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </template>
            <div v-else class="seller-reviews-empty">
                <div class="seller-reviews-empty-icon"><i class="bi bi-star"></i></div>
                <h5>No Reviews Found</h5>
                <p>There are no customer reviews matching your filters.</p>
                <a v-if="hasFilters" :href="reviewsUrl" class="seller-reviews-empty-button">
                    <i class="bi bi-arrow-counterclockwise"></i><span>Clear Filters</span>
                </a>
            </div>
        </div>
    </div>
</template>
