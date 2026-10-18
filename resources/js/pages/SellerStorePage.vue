<script setup>
import { computed, onBeforeUnmount, reactive, ref } from 'vue';
import { decodeSellerPageData } from '../utils/sellerPageData';

const props = defineProps({ pageData: { type: String, default: '' } });
const data = decodeSellerPageData(props.pageData);
const store = reactive({ ...data.store });
const name = ref(store.name || '');
const description = ref(store.description || '');
const preview = ref(store.logoUrl || '');
const selectedFile = ref(null);
const clientError = ref('');
const errors = computed(() => Object.values(data.errors || {}).flat());
const descriptionLength = computed(() => description.value.length);
const aboutText = computed(() => description.value.length > 140 ? `${description.value.slice(0, 137)}...` : description.value);
let objectUrl = null;

function chooseLogo(event) {
    const file = event.target.files?.[0];
    if (!file) return;
    clientError.value = '';
    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
        clientError.value = 'Choose a JPG, JPEG, PNG or WEBP image.';
        event.target.value = '';
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        clientError.value = 'The selected file is larger than 2MB.';
        event.target.value = '';
        return;
    }
    selectedFile.value = file;
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = URL.createObjectURL(file);
    preview.value = objectUrl;
}

function dropLogo(event) {
    const file = event.dataTransfer?.files?.[0];
    if (!file) return;
    const input = document.getElementById('logo');
    const transfer = new DataTransfer();
    transfer.items.add(file);
    input.files = transfer.files;
    chooseLogo({ target: input });
}

function dismissAlert(event) {
    event.currentTarget.closest('[data-alert]')?.remove();
}

onBeforeUnmount(() => { if (objectUrl) URL.revokeObjectURL(objectUrl); });
</script>

<template>
    <div class="seller-store-page">
        <div class="seller-page-heading"><div><h1>My Store</h1><p>Manage your store information and profile.</p></div><a :href="data.viewStoreUrl" class="seller-outline-button"><i class="bi bi-shop"></i><span>View Store</span></a></div>
        <div v-if="data.success" class="seller-alert seller-alert-success" data-alert><div class="seller-alert-icon"><i class="bi bi-check-lg"></i></div><div class="seller-alert-text"><strong>Success</strong><span>{{ data.success }}</span></div><button type="button" class="seller-alert-close" data-alert-close aria-label="Close" @click="dismissAlert"><i class="bi bi-x-lg"></i></button></div>
        <div v-if="errors.length" class="seller-alert seller-alert-danger" data-alert><div class="seller-alert-icon"><i class="bi bi-exclamation-lg"></i></div><div class="seller-alert-text"><strong>Please fix the following</strong><span v-for="(error, index) in errors" :key="index">{{ error }}</span></div><button type="button" class="seller-alert-close" data-alert-close aria-label="Close" @click="dismissAlert"><i class="bi bi-x-lg"></i></button></div>

        <form :action="data.updateUrl" method="POST" enctype="multipart/form-data" id="sellerStoreForm">
            <input type="hidden" name="_token" :value="data.csrfToken"><input type="hidden" name="_method" value="PUT">
            <div class="row g-4">
                <div class="col-xl-4"><aside class="seller-store-card"><div class="seller-store-cover"></div><div class="seller-store-profile"><div class="seller-store-logo" id="storeCardLogo"><img v-if="preview" :src="preview" :alt="name"><i v-else class="bi bi-shop"></i></div><div class="seller-store-info"><h4 id="storeCardName">{{ name || 'My Store' }}</h4><span class="seller-store-slug"><i class="bi bi-link-45deg"></i>/{{ store.slug }}</span></div><p v-if="description" class="seller-store-about">{{ aboutText }}</p></div><div class="seller-store-meta"><div><span>Status</span><strong :class="store.active ? 'active' : 'inactive'"><i class="bi bi-circle-fill"></i>{{ store.status }}</strong></div><div><span>Created</span><strong>{{ store.createdAt }}</strong></div></div><div v-if="store.phone || store.address" class="seller-store-contact"><div v-if="store.phone" class="seller-store-contact-item"><div class="seller-store-contact-icon"><i class="bi bi-telephone"></i></div><div><span>Phone</span><strong>{{ store.phone }}</strong></div></div><div v-if="store.address" class="seller-store-contact-item"><div class="seller-store-contact-icon"><i class="bi bi-geo-alt"></i></div><div><span>Address</span><strong>{{ store.address }}</strong></div></div></div></aside></div>
                <div class="col-xl-8"><div class="seller-store-form-card"><div class="seller-form-header"><div class="seller-form-header-icon"><i class="bi bi-sliders"></i></div><div><h5>Store Information</h5><p>Update your public store information.</p></div></div><div class="seller-form-body">
                    <div class="seller-form-group" :class="{ 'has-error': data.errors?.name?.length }"><label for="name">Store Name</label><div class="seller-input-wrap"><i class="bi bi-shop"></i><input type="text" id="name" name="name" v-model="name" placeholder="Enter store name" required></div><div v-for="error in data.errors?.name || []" :key="error" class="seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ error }}</div></div>
                    <div class="seller-form-group" :class="{ 'has-error': data.errors?.description?.length }"><label for="description">Description <em id="descriptionCount">{{ descriptionLength }}</em></label><textarea id="description" name="description" rows="5" v-model="description" placeholder="Tell customers about your store..."></textarea><div v-for="error in data.errors?.description || []" :key="error" class="seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ error }}</div></div>
                    <div class="row g-3"><div class="col-md-6"><div class="seller-form-group" :class="{ 'has-error': data.errors?.phone?.length }"><label for="phone">Phone</label><div class="seller-input-wrap"><i class="bi bi-telephone"></i><input type="text" id="phone" name="phone" v-model="store.phone" placeholder="Enter phone number"></div><div v-for="error in data.errors?.phone || []" :key="error" class="seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ error }}</div></div></div><div class="col-md-6"><div class="seller-form-group" :class="{ 'has-error': data.errors?.address?.length }"><label for="address">Address</label><div class="seller-input-wrap"><i class="bi bi-geo-alt"></i><input type="text" id="address" name="address" v-model="store.address" placeholder="Enter store address"></div><div v-for="error in data.errors?.address || []" :key="error" class="seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ error }}</div></div></div></div>
                    <div class="seller-form-group" :class="{ 'has-error': data.errors?.logo?.length }"><label for="logo">Store Logo</label><div class="seller-logo-upload" id="logoDropzone" @dragover.prevent @drop.prevent="dropLogo"><div class="seller-logo-preview" id="logoPreview"><img v-if="preview" :src="preview" alt="Store Logo"><i v-else class="bi bi-image"></i></div><div class="seller-logo-content"><label for="logo" class="seller-upload-button"><i class="bi bi-upload"></i> Choose Logo</label><input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp" @change="chooseLogo"><p id="logoHint">JPG, JPEG, PNG or WEBP. Maximum 2MB.</p></div></div><div v-for="error in data.errors?.logo || []" :key="error" class="seller-field-error"><i class="bi bi-exclamation-circle"></i>{{ error }}</div><div v-if="clientError" class="seller-field-error" id="logoClientError"><i class="bi bi-exclamation-circle"></i><span>{{ clientError }}</span></div></div>
                    <div class="seller-form-actions"><a :href="data.dashboardUrl" class="seller-cancel-button">Cancel</a><button type="submit" class="seller-save-button"><i class="bi bi-check2"></i><span>Save Changes</span></button></div>
                </div></div></div>
            </div>
        </form>
    </div>
</template>
