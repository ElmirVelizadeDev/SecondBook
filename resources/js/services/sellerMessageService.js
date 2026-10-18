import api from './api';
export default {
    async deleteReply(url) {
        const response = await api.delete(url, {
            baseURL: window.location.origin,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        });
        return response.data;
    },
};
