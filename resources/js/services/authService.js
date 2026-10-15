import api from './api';

export default {
    async login(credentials) {
        const response = await api.post('/auth/login', credentials);
        return response.data;
    },
    async register(details) {
        const response = await api.post('/auth/register', details);
        return response.data;
    },
    async logout() {
        const response = await api.post('/auth/logout');
        return response.data;
    },
    async getCurrentUser() {
        const response = await api.get('/me');
        return response.data;
    },
    async verifyEmail(otp) {
        const response = await api.post('/auth/email/verify', { otp_code: otp });
        return response.data;
    },
    async resendEmailVerification() {
        const response = await api.post('/auth/email/resend');
        return response.data;
    },
    async requestPasswordReset(email) {
        const response = await api.post('/auth/password/send-otp', { email });
        return response.data;
    },
    async resendPasswordCode(payload = {}) {
        const response = await api.post('/auth/password/resend', payload);
        return response.data;
    },
    async verifyPasswordCode(payload) {
        const response = await api.post('/auth/password/verify', payload);
        return response.data;
    },
};
