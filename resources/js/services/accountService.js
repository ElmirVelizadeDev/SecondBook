import api from './api';

export default {
    async updateProfile(profile) {
        let response;

        if (typeof FormData !== 'undefined' && profile instanceof FormData) {
            if (!profile.has('_method')) {
                profile.append('_method', 'PUT');
            }

            response = await api.post('/profile', profile);
        } else {
            response = await api.put('/profile', profile);
        }

        return response.data;
    },
    async removeProfilePhoto() {
        const response = await api.delete('/profile/photo');
        return response.data;
    },
    async requestAccountDeletion() {
        const response = await api.post('/account/delete/request');
        return response.data;
    },
    async verifyAccountDeletionPassword(password) {
        const response = await api.post('/account/delete/verify-password', {
            current_password: password,
        });
        return response.data;
    },
    async resendAccountDeletionCode() {
        const response = await api.post('/account/delete/resend');
        return response.data;
    },
    async verifyAccountDeletionCode(otp) {
        const response = await api.post('/account/delete/verify-otp', { otp_code: otp });
        return response.data;
    },
    async getPreferences() {
        const response = await api.get('/account/preferences');
        return response.data;
    },
    async updatePreferences(preferences) {
        const response = await api.put('/account/preferences', preferences);
        return response.data;
    },
    async getSecurityEvents() {
        const response = await api.get('/account/security-events');
        return response.data;
    },
    async getSessions() {
        const response = await api.get('/account/sessions');
        return response.data;
    },
    async revokeSession(sessionKey) {
        const response = await api.delete(`/account/sessions/${encodeURIComponent(sessionKey)}`);
        return response.data;
    },
    async requestPasswordChange(payload) {
        const response = await api.post('/account/password/change-request', payload);
        return response.data;
    },
    async getWishlist(params = {}) {
        const response = await api.get('/wishlist', { params });
        return response.data;
    },
    async getWishlistState(bookId) {
        const response = await api.get(`/wishlist/${bookId}/state`);
        return response.data;
    },
    async addToWishlist(bookId) {
        const response = await api.post(`/wishlist/${bookId}`);
        return response.data;
    },
    async removeFromWishlist(bookId) {
        const response = await api.delete(`/wishlist/${bookId}`);
        return response.data;
    },
    async getOrders(params = {}) {
        const response = await api.get('/orders', { params });
        return response.data;
    },
    async getOrder(orderId) {
        const response = await api.get(`/orders/${orderId}`);
        return response.data;
    },
    async cancelOrder(orderId) {
        const response = await api.post(`/orders/${orderId}/cancel`);
        return response.data;
    },
    async getOrderTracking(orderId) {
        const response = await api.get(`/orders/${orderId}/tracking`);
        return response.data;
    },
    async getReviewEligibility(orderId) {
        const response = await api.get(`/orders/${orderId}/review`);
        return response.data;
    },
    async submitReview(orderId, review) {
        const response = await api.post(`/orders/${orderId}/review`, review);
        return response.data;
    },
    async getPaymentStatus(orderId) {
        const response = await api.get(`/payments/${orderId}`);
        return response.data;
    },
    async getNotifications(params = {}) {
        const response = await api.get('/notifications', { params });
        return response.data;
    },
    async markNotificationRead(notificationId) {
        const response = await api.post(`/notifications/${notificationId}/read`);
        return response.data;
    },
    async markAllNotificationsRead() {
        const response = await api.post('/notifications/read-all');
        return response.data;
    },
    async deleteNotification(notificationId) {
        const response = await api.delete(`/notifications/${notificationId}`);
        return response.data;
    },
    async getSellerApplication() {
        const response = await api.get('/seller-application');
        return response.data;
    },
    async applyToSell(application) {
        const response = await api.post('/seller-application', application);
        return response.data;
    },
};
