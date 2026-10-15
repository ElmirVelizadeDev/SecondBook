import api from './api';

export default {
    async getHome() {
        const response = await api.get('/home');
        return response.data;
    },
    async getBook(bookId) {
        const response = await api.get(`/books/${bookId}`);
        return response.data;
    },
    async getCategories() {
        const response = await api.get('/categories');
        return response.data;
    },
    async getCategory(categoryId, params = {}) {
        const response = await api.get(`/categories/${categoryId}`, { params });
        return response.data;
    },
    async getAuthors(params = {}) {
        const response = await api.get('/authors', { params });
        return response.data;
    },
    async getAuthor(authorId) {
        const response = await api.get(`/authors/${authorId}`);
        return response.data;
    },
    async getStore(storeSlug, params = {}) {
        const response = await api.get(`/stores/${encodeURIComponent(storeSlug)}`, { params });
        return response.data;
    },
};
