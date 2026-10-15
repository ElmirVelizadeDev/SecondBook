import api from './api';

export default {
    async getCart() {
        const response = await api.get('/cart');
        return response.data;
    },
    async addItem(bookId, quantity = 1) {
        const response = await api.post(`/cart/items/${bookId}`, { quantity });
        return response.data;
    },
    async updateItem(bookId, quantity) {
        const response = await api.patch(`/cart/items/${bookId}`, { quantity });
        return response.data;
    },
    async removeItem(bookId) {
        const response = await api.delete(`/cart/items/${bookId}`);
        return response.data;
    },
    async clearCart() {
        const response = await api.delete('/cart');
        return response.data;
    },
    async getCheckout(shippingId) {
        const response = await api.get('/checkout', {
            params: shippingId ? { shipping_id: shippingId } : {},
        });
        return response.data;
    },
    async placeOrder(details) {
        const response = await api.post('/checkout', details);
        return response.data;
    },
};
