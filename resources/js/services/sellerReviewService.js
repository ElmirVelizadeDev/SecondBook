import api from './api';

export default {
    async getReviews(url, params = {}) {
        // Seller web routes live outside the shared client's /api base path.
        const response = await api.get(url, {
            baseURL: window.location.origin,
            params,
        });
        return response.data;
    },
    async getReview(url) {
        // This is a same-origin web route, while the shared Axios client defaults to /api.
        const response = await api.get(url, { baseURL: window.location.origin });
        return response.data;
    },
};
