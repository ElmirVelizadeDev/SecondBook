<script setup>
import { onMounted, reactive, ref } from 'vue';
import accountService from '../services/accountService';

const form = reactive({ store_name: '', description: '', phone: '', address: '' });
const application = ref(null);
const loading = ref(true);
const submitting = ref(false);
const error = ref('');
const success = ref('');
const fieldErrors = ref({});

async function loadApplication() {
    try {
        const response = await accountService.getSellerApplication();
        application.value = response.data || null;
        if (application.value) {
            Object.assign(form, {
                store_name: application.value.store_name || '',
                description: application.value.description || '',
                phone: application.value.phone || '',
                address: application.value.address || '',
            });
        }
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your seller application status could not be loaded.';
    } finally {
        loading.value = false;
    }
}

async function submitApplication() {
    if (submitting.value) return;
    submitting.value = true;
    error.value = '';
    success.value = '';
    fieldErrors.value = {};
    try {
        const response = await accountService.applyToSell(form);
        success.value = response.message || 'Your seller application has been submitted.';
        await loadApplication();
    } catch (exception) {
        const data = exception.response?.data || {};
        error.value = data.message || 'Your application could not be submitted.';
        fieldErrors.value = data.errors || {};
    } finally {
        submitting.value = false;
    }
}

onMounted(loadApplication);
</script>

<template>
    <main class="sb-seller-page"><div class="container">
        <section class="sb-seller-hero"><div class="sb-seller-hero-content"><span class="sb-seller-eyebrow"><span class="sb-seller-eyebrow-icon"><i class="bi bi-shop"></i></span>Seller Program</span><h1>Build your own <span>bookstore.</span></h1><p>Turn your passion for books into your own SecondBook store. Reach readers, manage your listings and grow your sales from one place.</p></div><div class="sb-seller-hero-mark"><div class="sb-seller-hero-circle"><i class="bi bi-shop-window"></i></div><span>SECOND<br>BOOK</span></div></section>
        <div v-if="error" class="sb-seller-alert sb-seller-alert-danger" role="alert"><div class="sb-seller-alert-icon"><i class="bi bi-exclamation-lg"></i></div><p>{{ error }}</p></div>
        <div v-if="success" class="sb-seller-alert sb-seller-alert-success" role="status"><div class="sb-seller-alert-icon"><i class="bi bi-check2"></i></div><p>{{ success }}</p></div>
        <div v-if="loading" class="sb-seller-form-card" role="status">Checking your application…</div>
        <section v-else-if="application && application.status !== 'rejected'" class="sb-seller-status">
            <div class="sb-seller-status-visual">
                <div class="sb-seller-status-icon"><i :class="application.status === 'pending' ? 'bi bi-hourglass-split' : 'bi bi-check-lg'"></i></div>
                <span v-if="application.status === 'pending'" class="sb-seller-status-pulse"></span>
            </div>
            <div class="sb-seller-status-content">
                <span class="sb-seller-section-label">Application status</span>
                <h2>Your application is <span>{{ application.status === 'pending' ? 'under review.' : 'approved.' }}</span></h2>
                <p v-if="application.status === 'pending'">Your seller application has been submitted successfully. Our administration team will review your information and update your application status.</p>
                <p v-else>Your seller application has been approved.</p>
                <div class="sb-seller-status-meta"><span :class="['sb-seller-status-badge', application.status]">{{ application.status }}</span></div>
            </div>
        </section>
        <template v-else>
            <section v-if="application?.status === 'rejected'" class="sb-seller-status rejected">
                <div class="sb-seller-status-visual"><div class="sb-seller-status-icon"><i class="bi bi-x-lg"></i></div></div>
                <div class="sb-seller-status-content">
                    <span class="sb-seller-section-label">Application status</span>
                    <h2>Let's try <span>again.</span></h2>
                    <p>Your previous seller application was rejected. Review the information below and submit a new application with the necessary changes.</p>
                    <div v-if="application.rejection_reason" class="sb-seller-rejection">{{ application.rejection_reason }}</div>
                </div>
            </section>
            <form class="sb-seller-form-card" @submit.prevent="submitApplication">
                <div class="sb-seller-form-heading">
                    <div><span class="sb-seller-section-label">{{ application ? 'New seller application' : 'Seller application' }}</span><h2>Tell us about <span>your store.</span></h2></div>
                    <div class="sb-seller-form-step"><span>01</span><small>{{ application ? 'Re-application' : 'Application' }}</small></div>
                </div>
                <p class="sb-seller-form-intro">Share a few details about your store so our team can review your seller application.</p>
                <div class="sb-seller-form">
                    <div class="sb-seller-field">
                        <label for="seller_store_name">Store name <span>*</span></label>
                        <div class="sb-seller-input"><i class="bi bi-shop"></i><input id="seller_store_name" v-model="form.store_name" name="store_name" placeholder="e.g. The Reading Corner" required maxlength="255"></div>
                        <small v-if="fieldErrors.store_name" class="sb-seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ fieldErrors.store_name[0] }}</small>
                    </div>
                    <div class="sb-seller-field">
                        <label for="seller_description">About your store</label>
                        <div class="sb-seller-textarea"><textarea id="seller_description" v-model="form.description" name="description" rows="5" maxlength="2000" placeholder="Tell us about the books you plan to sell."></textarea><span><i class="bi bi-pencil-square"></i></span></div>
                        <small v-if="fieldErrors.description" class="sb-seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ fieldErrors.description[0] }}</small>
                    </div>
                    <div class="sb-seller-fields-row">
                        <div class="sb-seller-field">
                            <label for="seller_phone">Phone <span>*</span></label>
                            <div class="sb-seller-input"><i class="bi bi-telephone"></i><input id="seller_phone" v-model="form.phone" name="phone" required maxlength="50"></div>
                            <small v-if="fieldErrors.phone" class="sb-seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ fieldErrors.phone[0] }}</small>
                        </div>
                        <div class="sb-seller-field">
                            <label for="seller_address">Address <span>*</span></label>
                            <div class="sb-seller-input"><i class="bi bi-geo-alt"></i><input id="seller_address" v-model="form.address" name="address" required maxlength="255"></div>
                            <small v-if="fieldErrors.address" class="sb-seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ fieldErrors.address[0] }}</small>
                        </div>
                    </div>
                    <div class="sb-seller-form-footer">
                        <div class="sb-seller-security"><span class="sb-seller-security-icon"><i class="bi bi-shield-check"></i></span><span>Your information is kept secure and used only to review your application.</span></div>
                        <button type="submit" class="sb-seller-submit" :disabled="submitting">{{ submitting ? 'Submitting…' : 'Submit application' }}<i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </template>
    </div></main>
</template>
