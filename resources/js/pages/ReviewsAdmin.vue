<script setup>
const props = defineProps({ payload: { type: String, required: true } });
const state = JSON.parse(props.payload);
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

function submitAction(url, method) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    const token = document.createElement('input');
    token.type = 'hidden'; token.name = '_token'; token.value = csrf;
    const spoof = document.createElement('input');
    spoof.type = 'hidden'; spoof.name = '_method'; spoof.value = method;
    form.append(token, spoof); document.body.append(form); form.submit();
}

async function removeReview() {
    const confirmed = window.Swal
        ? (await window.Swal.fire({ title: 'Delete Review?', text: 'This review will be permanently deleted.', icon: 'warning', width: 430, padding: '30px', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel', buttonsStyling: false, customClass: { popup: 'review-delete-popup', confirmButton: 'review-delete-confirm', cancelButton: 'review-delete-cancel' } })).isConfirmed
        : window.confirm('This review will be permanently deleted.');
    if (confirmed) submitAction(state.urls.delete, 'DELETE');
}

async function confirmDelete(url) {
    const confirmed = window.Swal
        ? (await window.Swal.fire({ title: 'Delete Review?', text: 'This review will be permanently deleted.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel', buttonsStyling: false, customClass: { popup: 'review-delete-popup', confirmButton: 'review-delete-confirm', cancelButton: 'review-delete-cancel' } })).isConfirmed
        : window.confirm('This review will be permanently deleted.');
    if (confirmed) submitAction(url, 'DELETE');
}
</script>

<template>
    <div class="dashboard-section reviews-page">
        <template v-if="state.view === 'index'">
            <div v-if="state.success" class="alert alert-success">{{ state.success }}</div>
            <div class="dashboard-panel reviews-header-panel mb-4"><div class="reviews-page-header"><div class="reviews-title-row"><div class="reviews-title-icon"><i class="bi bi-chat-square-text"></i></div><div><h5 class="mb-1">Reviews</h5><p class="text-muted mb-0 small">Manage customer reviews and feedback on SecondBook</p></div></div><div class="reviews-header-count"><span class="reviews-header-count-label">Total</span><strong>{{ state.statistics.total }}</strong></div></div></div>

            <div class="row g-3 mb-4">
                <div v-for="stat in [{name:'Total Reviews',value:state.statistics.total,icon:'bi-chat-square-text',kind:'total',caption:'All customer reviews',tail:'bi-arrow-up-right'},{name:'Pending',value:state.statistics.pending,icon:'bi-clock-history',kind:'pending',caption:'Waiting for review',tail:'bi-hourglass-split'},{name:'Approved',value:state.statistics.approved,icon:'bi-check-circle',kind:'approved',caption:'Published reviews',tail:'bi-check2'},{name:'Rejected',value:state.statistics.rejected,icon:'bi-x-circle',kind:'rejected',caption:'Rejected reviews',tail:'bi-x-lg'}]" :key="stat.kind" class="col-xl-3 col-md-6"><div class="dashboard-panel review-stat-card h-100"><div class="review-stat-content"><div class="review-stat-icon" :class="stat.kind"><i class="bi" :class="stat.icon"></i></div><div class="review-stat-info"><span class="review-stat-label">{{ stat.name }}</span><strong>{{ stat.value }}</strong></div></div><div class="review-stat-footer"><span>{{ stat.caption }}</span><i class="bi" :class="stat.tail"></i></div></div></div>
            </div>

            <div class="dashboard-panel reviews-filter-panel mb-4"><div class="reviews-filter-header"><div><h6 class="mb-1">Filter Reviews</h6><p class="text-muted small mb-0">Search and filter customer feedback</p></div><div class="reviews-filter-icon"><i class="bi bi-funnel"></i></div></div>
                <form method="GET" :action="state.urls.index" class="reviews-filter-form">
                    <div class="reviews-search-field"><label class="form-label">Search</label><div class="reviews-search-group"><div class="reviews-search-input-wrap"><i class="bi bi-search"></i><input type="text" name="search" :value="state.filters.search" placeholder="User, email, book or review..."></div><button type="submit" class="reviews-search-button"><i class="bi bi-search"></i><span>Search</span></button></div></div>
                    <div class="reviews-filter-field"><label class="form-label">Status</label><select name="status" class="form-select"><option value="">All Status</option><option value="approved" :selected="state.filters.status === 'approved'">Approved</option><option value="pending" :selected="state.filters.status === 'pending'">Pending</option><option value="rejected" :selected="state.filters.status === 'rejected'">Rejected</option></select></div>
                    <div class="reviews-filter-field"><label class="form-label">Rating</label><select name="rating" class="form-select"><option value="">All Ratings</option><option v-for="rating in [5,4,3,2,1]" :key="rating" :value="rating" :selected="String(state.filters.rating) === String(rating)">{{ rating }} Stars</option></select></div>
                    <div class="reviews-filter-actions"><button type="submit" class="reviews-filter-button"><i class="bi bi-funnel"></i> Filter</button><a :href="state.urls.index" class="reviews-reset-button"><i class="bi bi-arrow-counterclockwise"></i> Reset</a></div>
                </form>
            </div>

            <div class="dashboard-panel reviews-list-panel"><div class="reviews-list-header"><div><div class="reviews-list-title"><i class="bi bi-chat-left-text"></i><h6 class="mb-0">Review List</h6></div><p class="text-muted small mb-0">Customer feedback submitted on SecondBook</p></div><div class="reviews-list-count">{{ state.pagination.total }} {{ state.pagination.total === 1 ? 'review' : 'reviews' }}</div></div>
                <div class="table-responsive reviews-table-wrapper"><table class="table reviews-table align-middle"><thead><tr><th class="review-number-column">#</th><th>Customer</th><th class="d-none d-md-table-cell">Book</th><th>Rating</th><th class="d-none d-lg-table-cell">Review</th><th>Status</th><th class="d-none d-xl-table-cell">Date</th><th class="text-end">Action</th></tr></thead>
                    <tbody><tr v-for="(review,index) in state.reviews" :key="review.id">
                        <td class="review-number-cell">{{ state.pagination.first_item + index }}</td>
                        <td><div class="review-customer-cell"><div class="review-avatar"><img v-if="review.customer.avatar" :src="review.customer.avatar" :alt="review.customer.name"><template v-else>{{ (review.customer.name || 'U').slice(0,1).toUpperCase() }}</template></div><div class="review-customer-info"><strong>{{ review.customer.name }}</strong><span>{{ review.customer.email }}</span></div></div></td>
                        <td class="d-none d-md-table-cell"><div class="review-book-cell"><img v-if="review.book.cover" class="review-book-cover" :src="review.book.cover" :alt="review.book.title"><div v-else class="review-book-placeholder"><i class="bi bi-book"></i></div><div class="review-book-info"><strong>{{ review.book.title }}</strong><span>{{ review.book.author || '-' }}</span></div></div></td>
                        <td class="review-rating-cell"><div class="review-stars"><i v-for="star in 5" :key="star" class="bi" :class="star <= review.rating ? 'bi-star-fill' : 'bi-star'"></i></div><span>{{ review.rating }}/5</span></td>
                        <td class="d-none d-lg-table-cell"><span class="review-comment-preview">{{ review.comment || '—' }}</span></td>
                        <td><span class="review-status-badge" :class="review.status">{{ review.status }}</span></td><td class="d-none d-xl-table-cell"><div class="review-date-cell"><strong>{{ review.date }}</strong><span>{{ review.time }}</span></div></td>
                        <td><div class="review-actions-cell"><a :href="review.show_url" class="review-action-btn view" title="View Review"><i class="bi bi-eye"></i></a><form v-if="review.status !== 'approved'" :action="review.approve_url" method="POST"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="PATCH"><button type="submit" class="review-action-btn approve" title="Approve Review"><i class="bi bi-check-lg"></i></button></form><form v-if="review.status !== 'rejected'" :action="review.reject_url" method="POST"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="PATCH"><button type="submit" class="review-action-btn reject" title="Reject Review"><i class="bi bi-x-lg"></i></button></form><button type="button" class="review-action-btn delete review-delete-btn" title="Delete Review" @click="confirmDelete(review.delete_url)"><i class="bi bi-trash3"></i></button></div></td>
                    </tr><tr v-if="!state.reviews.length"><td colspan="8" class="reviews-empty-cell"><div class="reviews-empty-state"><div class="reviews-empty-icon"><i class="bi bi-chat-square-text"></i></div><h6>No reviews found</h6><p>There are no reviews matching your current filters.</p><a :href="state.urls.index" class="reviews-reset-empty"><i class="bi bi-arrow-counterclockwise"></i> Clear Filters</a></div></td></tr></tbody>
                </table></div>
                <nav v-if="state.pagination.last_page > 1" class="reviews-pagination-links" aria-label="Reviews pages"><a v-for="(link,index) in state.pagination.links" :key="index" :href="link.url || undefined" :class="{ active:link.active, disabled:!link.url }" v-html="link.label"></a></nav>
            </div>
        </template>

        <template v-else>
            <div class="dashboard-panel review-detail-header mb-4"><div class="panel-header mb-0"><div><h5 class="mb-0">Review Details</h5><p class="text-muted mb-0 small">View customer feedback and review information</p></div><span class="review-status-badge" :class="state.review.status"><i class="bi" :class="state.review.status === 'approved' ? 'bi-check-circle-fill' : state.review.status === 'pending' ? 'bi-clock-fill' : 'bi-x-circle-fill'"></i>{{ state.review.status }}</span></div></div>
            <div class="row g-4"><div class="col-xl-8">
                <div class="dashboard-panel review-detail-card mb-4"><div class="review-detail-card-header"><div><h6 class="mb-1">Review Information</h6><p class="text-muted small mb-0">Customer and book details</p></div><div class="review-detail-id">#{{ state.review.id }}</div></div><div class="review-info-grid">
                    <div class="review-info-item"><span class="review-info-label">Customer</span><div class="review-customer"><div class="review-detail-avatar"><img v-if="state.review.customer.avatar" :src="state.review.customer.avatar" :alt="state.review.customer.name"><template v-else>{{ state.review.customer.name.slice(0,1).toUpperCase() }}</template></div><div><strong>{{ state.review.customer.name }}</strong><span>{{ state.review.customer.email }}</span></div></div></div>
                    <div class="review-info-item"><span class="review-info-label">Book</span><div class="review-book-detail"><img v-if="state.review.book.cover" :src="state.review.book.cover" :alt="state.review.book.title" class="review-detail-book-cover"><div v-else class="review-detail-book-placeholder"><i class="bi bi-book"></i></div><div><strong>{{ state.review.book.title }}</strong><span>{{ state.review.book.author }}</span></div></div></div>
                </div></div>
                <div class="dashboard-panel review-detail-card mb-4"><div class="review-detail-card-header"><div><h6 class="mb-1">Customer Rating</h6><p class="text-muted small mb-0">Rating given by the customer</p></div><div class="review-rating-number">{{ state.review.rating }}/5</div></div><div class="review-large-rating"><div class="review-large-stars"><i v-for="star in 5" :key="star" class="bi" :class="star <= state.review.rating ? 'bi-star-fill' : 'bi-star'"></i></div><span>Customer rating</span></div></div>
                <div class="dashboard-panel review-detail-card"><div class="review-detail-card-header"><div><h6 class="mb-1">Customer Review</h6><p class="text-muted small mb-0">Feedback submitted by the customer</p></div><i class="bi bi-chat-square-text review-section-icon"></i></div><div v-if="state.review.comment" class="review-full-comment">{{ state.review.comment }}</div><div v-else class="review-no-comment"><i class="bi bi-chat-square"></i><span>This customer did not leave a comment.</span></div></div>
            </div><div class="col-xl-4">
                <div class="dashboard-panel review-detail-card mb-4"><div class="review-detail-card-header"><div><h6 class="mb-1">Review Summary</h6><p class="text-muted small mb-0">Review information</p></div></div><div class="review-summary-list"><div class="review-summary-row"><span><i class="bi bi-hash"></i> Review ID</span><strong>#{{ state.review.id }}</strong></div><div class="review-summary-row"><span><i class="bi bi-person"></i> Customer</span><strong>{{ state.review.customer.name }}</strong></div><div class="review-summary-row"><span><i class="bi bi-star"></i> Rating</span><strong>{{ state.review.rating }}/5</strong></div><div class="review-summary-row"><span><i class="bi bi-calendar3"></i> Submitted</span><strong>{{ state.review.date }}</strong></div><div class="review-summary-row"><span><i class="bi bi-clock"></i> Time</span><strong>{{ state.review.time }}</strong></div><div class="review-summary-row"><span><i class="bi bi-activity"></i> Status</span><strong class="text-capitalize">{{ state.review.status }}</strong></div></div></div>
                <div class="dashboard-panel review-detail-card"><div class="review-detail-card-header"><div><h6 class="mb-1">Review Actions</h6><p class="text-muted small mb-0">Manage this customer review</p></div></div><div class="review-actions"><form v-if="state.review.status !== 'approved'" :action="state.urls.approve" method="POST"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="PATCH"><button type="submit" class="review-action-btn approve"><i class="bi bi-check-lg"></i><span>Approve Review</span></button></form><form v-if="state.review.status !== 'rejected'" :action="state.urls.reject" method="POST"><input type="hidden" name="_token" :value="csrf"><input type="hidden" name="_method" value="PATCH"><button type="submit" class="review-action-btn reject"><i class="bi bi-x-lg"></i><span>Reject Review</span></button></form><button type="button" class="review-action-btn delete" @click="removeReview"><i class="bi bi-trash3"></i><span>Delete Review</span></button><a :href="state.urls.index" class="review-action-btn back"><i class="bi bi-arrow-left"></i><span>Back to Reviews</span></a></div></div>
            </div></div>
        </template>
    </div>
</template>
