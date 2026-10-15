<script setup>
import { onMounted, reactive, ref } from 'vue';
import authService from '../services/authService';
import contentService from '../services/contentService';

const form = reactive({ name: '', email: '', subject: 'General Inquiry', message: '' });
const loading = ref(false);
const error = ref('');
const success = ref('');
const fieldErrors = ref({});

onMounted(async () => {
    try {
        const response = await authService.getCurrentUser();
        if (!form.name) form.name = response.data?.name || '';
        if (!form.email) form.email = response.data?.email || '';
    } catch {
        // The contact form is available to guests; an unauthenticated profile lookup is expected.
    }
});

async function submitMessage() {
    if (loading.value) return;
    loading.value = true;
    error.value = '';
    success.value = '';
    fieldErrors.value = {};
    try {
        const response = await contentService.sendContactMessage(form);
        success.value = response.message || 'Your message has been sent successfully.';
        Object.assign(form, { name: '', email: '', subject: '', message: '' });
    } catch (exception) {
        const data = exception.response?.data || {};
        error.value = data.message || 'Your message could not be sent. Please try again.';
        fieldErrors.value = data.errors || {};
    } finally {
        loading.value = false;
    }
}

</script>

<template>
    <section id="contact-page" class="contact-page">
        <div class="contact-hero contact-hero-premium">
            <div class="contact-hero-decoration contact-hero-decoration-1"></div><div class="contact-hero-decoration contact-hero-decoration-2"></div><div class="contact-hero-decoration contact-hero-decoration-3"></div><div class="contact-hero-grid"></div>
            <div class="container"><div class="contact-hero-inner">
                <div class="contact-hero-content">
                    <div class="contact-hero-eyebrow"><span class="contact-hero-eyebrow-icon"><i class="bi bi-chat-square-heart"></i></span><span>WE'RE HERE FOR YOU</span><span class="contact-hero-eyebrow-line"></span></div>
                    <h1 class="section-title contact-hero-title">Every great story <span class="contact-hero-title-accent">starts with <em>a conversation.</em></span></h1>
                    <p class="contact-hero-description">Questions, ideas, or simply a little help finding your way? We're listening. Reach out to the SecondBook team and let's make your next chapter a great one.</p>
                    <div class="contact-hero-meta">
                        <a href="mailto:support@secondbook.com" class="contact-hero-meta-item"><span class="contact-hero-meta-icon"><i class="bi bi-envelope-heart"></i></span><span class="contact-hero-meta-content"><span class="contact-hero-meta-label">SEND US AN EMAIL</span><strong>support@secondbook.com</strong></span><i class="bi bi-arrow-up-right contact-hero-meta-arrow"></i></a>
                        <div class="contact-hero-meta-item"><span class="contact-hero-meta-icon"><i class="bi bi-clock-history"></i></span><span class="contact-hero-meta-content"><span class="contact-hero-meta-label">OUR AVAILABILITY</span><strong>Mon – Fri · 09:00 – 18:00</strong></span><span class="contact-hero-status-dot"></span></div>
                    </div>
                    <div class="contact-hero-trust"><span class="contact-hero-trust-icon"><i class="bi bi-heart-fill"></i></span><span>Real people. Thoughtful answers. <strong>Every message matters.</strong></span></div>
                </div>
                <div class="contact-hero-visual contact-hero-visual-premium">
                    <div class="contact-hero-visual-glow"></div><div class="contact-hero-orbit"><span class="contact-hero-orbit-dot contact-hero-orbit-dot-1"></span><span class="contact-hero-orbit-dot contact-hero-orbit-dot-2"></span><span class="contact-hero-orbit-dot contact-hero-orbit-dot-3"></span></div><div class="contact-hero-ring contact-hero-ring-1"></div><div class="contact-hero-ring contact-hero-ring-2"></div>
                    <div class="contact-hero-card contact-hero-card-main"><div class="contact-hero-card-top"><span class="contact-hero-card-brand"><span class="contact-hero-card-brand-icon"><i class="bi bi-book-half"></i></span><span>SECOND<span>BOOK</span></span></span><span class="contact-hero-card-menu"><i class="bi bi-three-dots"></i></span></div><div class="contact-hero-card-illustration"><div class="contact-hero-card-illustration-circle"></div><div class="contact-hero-book contact-hero-book-back"><span></span></div><div class="contact-hero-book contact-hero-book-front"><span></span></div></div><div class="contact-hero-card-message"><i class="bi bi-chat-heart"></i><span>A conversation starts here.</span></div></div>
                </div>
            </div></div>
        </div>
        <section class="contact-content py-5"><div class="container"><div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <div class="contact-form-header">
                        <div class="title"><span>Send us a message</span></div>
                        <h2>How Can We Help?</h2>
                        <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>
                    <div v-if="success" class="alert alert-success contact-alert" role="status">{{ success }}</div>
                    <div v-if="error || Object.keys(fieldErrors).length" class="alert alert-danger contact-alert" role="alert">
                        <strong v-if="error">{{ error }}</strong>
                        <ul v-if="Object.keys(fieldErrors).length">
                            <li v-for="(messages, field) in fieldErrors" :key="field">{{ messages[0] }}</li>
                        </ul>
                    </div>
                    <form class="contact-form" @submit.prevent="submitMessage">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="contact-field">
                                    <label for="name">Your Name</label>
                                    <div class="contact-input-wrap"><i class="bi bi-person"></i><input id="name" v-model="form.name" type="text" name="name" autocomplete="name" placeholder="Your name" required maxlength="100"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-field">
                                    <label for="email">Email Address</label>
                                    <div class="contact-input-wrap"><i class="bi bi-envelope"></i><input id="email" v-model="form.email" type="email" name="email" autocomplete="email" placeholder="Your email address" required maxlength="255"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contact-field">
                                    <label for="subject">Subject</label>
                                    <div class="contact-input-wrap">
                                        <i class="bi bi-chat-left-text"></i>
                                        <select id="subject" v-model="form.subject" name="subject" required>
                                            <option>General Inquiry</option>
                                            <option>Order Issue</option>
                                            <option>Payment Issue</option>
                                            <option>Shipping &amp; Delivery</option>
                                            <option>Book Information</option>
                                            <option>Account Problem</option>
                                            <option>Wishlist Issue</option>
                                            <option>Cart Issue</option>
                                            <option>Return &amp; Refund</option>
                                            <option>Selling on SecondBook</option>
                                            <option>Seller Support</option>
                                            <option>Technical Issue</option>
                                            <option>Suggestion / Feedback</option>
                                            <option>Report a Problem</option>
                                            <option>Privacy &amp; Security</option>
                                            <option>Partnership / Business Inquiry</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contact-field">
                                    <label for="message">Message</label>
                                    <div class="contact-textarea-wrap"><i class="bi bi-pencil-square"></i><textarea id="message" v-model="form.message" name="message" rows="7" placeholder="Tell us how we can help..." required minlength="10" maxlength="5000"></textarea></div>
                                </div>
                            </div>
                        </div>
                        <div class="contact-form-footer">
                            <p><i class="bi bi-shield-check"></i>Your information is kept private.</p>
                            <button type="submit" class="btn btn-accent contact-submit" :disabled="loading">{{ loading ? 'Sending…' : 'Send Message' }}<i class="bi bi-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div></div></section>
    </section>
</template>
