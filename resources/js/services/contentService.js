import api from './api';

export default {
    async getSiteSettings() {
        const response = await api.get('/site-settings');
        return response.data;
    },
    async getFaqs() {
        const response = await api.get('/faqs');
        return response.data;
    },
    async getBanners() {
        const response = await api.get('/banners');
        return response.data;
    },
    async getPublishers() {
        const response = await api.get('/publishers');
        return response.data;
    },
    async getShippingInformation() {
        const response = await api.get('/shipping-information');
        return response.data;
    },
    async sendContactMessage(payload) {
        const response = await api.post('/contact', payload);
        return response.data;
    },
    async sendStoreMessage(storeSlug, payload) {
        const response = await api.post(
            `/stores/${encodeURIComponent(storeSlug)}/messages`,
            payload
        );
        return response.data;
    },
};
