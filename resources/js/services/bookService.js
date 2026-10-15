
import api from './api';

export default {
    async getBooks(params = {}) {
        const response = await api.get('/books', { params });
        return response.data;
    },
};
