<script setup>
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue';
import authService from '../services/authService';
import accountService from '../services/accountService';

const props = defineProps({
    mode: { type: String, required: true },
    profileUrl: { type: String, required: true },
    editUrl: { type: String, required: true },
    settingsUrl: { type: String, required: true },
    ordersUrl: { type: String, required: true },
    wishlistUrl: { type: String, required: true },
    orderCount: { type: Number, default: 0 },
    wishlistCount: { type: Number, default: 0 },
    booksSoldCount: { type: Number, default: 0 },
    reviewsCount: { type: Number, default: 0 },
    memberSince: { type: String, default: '' },
    roleLabel: { type: String, default: 'Customer' },
    statusLabel: { type: String, default: 'Active' },
});

const user = ref(null);
const loading = ref(true);
const saving = ref(false);
const error = ref('');
const notice = ref('');
const photoFile = ref(null);
const currentPassword = ref('');
const photoPreview = ref('');
let objectUrl = '';
const counts = reactive({ orders: 0, wishlist: 0, sold: 0, reviews: 0 });
const fields = reactive({
    first_name: '',
    last_name: '',
    username: '',
    email: '',
    phone_country_code: '+994',
    phone: '',
    date_of_birth: '',
    gender: '',
    country: '',
    city: '',
    state: '',
    postal_code: '',
    address: '',
    bio: '',
});
const fullName = computed(() => [user.value?.first_name, user.value?.last_name].filter(Boolean).join(' ') || user.value?.name || 'User');
const avatarInitials = computed(() => fullName.value.split(/\s+/).filter(Boolean).slice(0, 2).map((word) => word.charAt(0).toUpperCase()).join('') || 'U');
const photoUrl = computed(() => photoPreview.value || (user.value?.profile_photo
    ? (/^https?:\/\//i.test(user.value.profile_photo)
        ? user.value.profile_photo
        : `/storage/${String(user.value.profile_photo).replace(/^\/+/, '')}`)
    : ''));
const phoneCodes = ['+994', '+90', '+7', '+380', '+49', '+33', '+44', '+39', '+34', '+1'];

function animateCounts() {
    const targets = {
        orders: props.orderCount,
        wishlist: props.wishlistCount,
        sold: props.booksSoldCount,
        reviews: props.reviewsCount,
    };
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        Object.assign(counts, targets);
        return;
    }
    const duration = 1300;
    const start = performance.now();
    const tick = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        Object.keys(targets).forEach((key) => {
            counts[key] = Math.round(targets[key] * eased);
        });
        if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

function populateFields(data) {
    Object.assign(fields, {
        first_name: data.first_name || '',
        last_name: data.last_name || '',
        username: data.username || '',
        email: data.email || '',
        phone_country_code: phoneCodes.find((code) => data.phone?.startsWith(code)) || '+994',
        phone: data.phone ? data.phone.replace(/^\+\d+\s*/, '') : '',
        date_of_birth: data.date_of_birth ? String(data.date_of_birth).slice(0, 10) : '',
        gender: data.gender || '',
        country: data.country || '',
        city: data.city || '',
        state: data.state || '',
        postal_code: data.postal_code || '',
        address: data.address || '',
        bio: data.bio || '',
    });
}

async function loadProfile() {
    loading.value = true;
    error.value = '';
    try {
        const response = await authService.getCurrentUser();
        user.value = response.data;
        populateFields(user.value);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your profile could not be loaded.';
    } finally {
        loading.value = false;
        if (props.mode === 'view') animateCounts();
    }
}

function onPhotoChange(event) {
    const file = event.target.files?.[0] || null;
    photoFile.value = file;
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = file ? URL.createObjectURL(file) : '';
    photoPreview.value = objectUrl;
}

async function saveProfile() {
    if (saving.value) return;
    saving.value = true;
    error.value = '';
    notice.value = '';
    try {
        const formData = new FormData();
        Object.entries(fields).forEach(([key, value]) => formData.append(key, value));
        if (fields.email.trim().toLowerCase() !== String(user.value?.email || '').toLowerCase()) {
            formData.append('current_password', currentPassword.value);
        }
        if (photoFile.value) formData.append('profile_photo', photoFile.value);
        const response = await accountService.updateProfile(formData);
        if (response.redirect) {
            window.location.assign(response.redirect);
            return;
        }
        notice.value = response.message || 'Your profile has been updated.';
        await loadProfile();
        photoFile.value = null;
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your profile could not be saved.';
    } finally {
        saving.value = false;
    }
}

async function removePhoto() {
    try {
        await accountService.removeProfilePhoto();
        photoPreview.value = '';
        photoFile.value = null;
        await loadProfile();
        notice.value = 'Your profile photo has been removed.';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your profile photo could not be removed.';
    }
}

onMounted(loadProfile);
onUnmounted(() => {
    if (objectUrl) URL.revokeObjectURL(objectUrl);
});
</script>

<template>
    <main v-if="mode === 'view'" class="sb-profile-page">
        <div class="container">
            <Transition name="sb-alert"><div v-if="error" class="alert alert-warning" role="alert">{{ error }}</div></Transition>
            <div v-if="loading" class="sb-profile-card" role="status">Loading profile…</div>
            <template v-else-if="user">
                <section class="sb-profile-hero"><div class="sb-profile-hero-main">
                    <div class="sb-profile-avatar-wrap"><div :class="['sb-profile-avatar', { 'sb-profile-avatar-photo': photoUrl }]"><img v-if="photoUrl" :src="photoUrl" :alt="fullName"><span v-else>{{ avatarInitials }}</span></div><div class="sb-profile-online is-active"><span></span>{{ statusLabel }}</div></div>
                    <div class="sb-profile-identity"><div class="sb-profile-eyebrow"><i class="bi bi-person-badge"></i>MY ACCOUNT</div><h1>{{ fullName }}</h1><div v-if="user.username" class="sb-profile-username"><i class="bi bi-at"></i>{{ user.username }}</div><p>Manage your personal information, account details and SecondBook preferences from one place.</p><div class="sb-profile-meta"><span><i class="bi bi-envelope"></i>{{ user.email || 'Not provided' }}</span><span><i class="bi bi-calendar3"></i>Member since {{ memberSince }}</span><span class="sb-profile-role"><i class="bi bi-shield-check"></i>{{ roleLabel }}</span></div></div>
                </div><div class="sb-profile-hero-actions"><a :href="editUrl" class="sb-profile-primary-btn"><i class="bi bi-pencil-square"></i>Edit Profile</a><a :href="settingsUrl" class="sb-profile-secondary-btn"><i class="bi bi-gear"></i>Account Settings</a></div></section>
                <section class="sb-profile-stats">
                    <div class="sb-profile-stat"><div class="sb-profile-stat-icon"><i class="bi bi-bag-check"></i></div><div class="sb-profile-stat-content"><span>ORDERS</span><strong>{{ counts.orders }}</strong><small>Your purchases</small></div></div>
                    <div class="sb-profile-stat"><div class="sb-profile-stat-icon"><i class="bi bi-heart"></i></div><div class="sb-profile-stat-content"><span>WISHLIST</span><strong>{{ counts.wishlist }}</strong><small>Saved books</small></div></div>
                    <div class="sb-profile-stat"><div class="sb-profile-stat-icon"><i class="bi bi-book"></i></div><div class="sb-profile-stat-content"><span>BOOKS SOLD</span><strong>{{ counts.sold }}</strong><small>Completed sales</small></div></div>
                    <div class="sb-profile-stat"><div class="sb-profile-stat-icon"><i class="bi bi-star"></i></div><div class="sb-profile-stat-content"><span>REVIEWS</span><strong>{{ counts.reviews }}</strong><small>Your reviews</small></div></div>
                </section>
                <section class="sb-profile-grid"><article class="sb-profile-card"><div class="sb-profile-card-header"><div><span class="sb-card-overline">PROFILE DETAILS</span><h2>Personal Information</h2><p>Your personal information and contact details.</p></div><div class="sb-profile-card-icon"><i class="bi bi-person-vcard"></i></div></div>
                    <div class="sb-profile-info-grid"><div class="sb-profile-info"><span class="sb-profile-info-label">Full Name</span><div class="sb-profile-info-value"><i class="bi bi-person"></i>{{ fullName }}</div></div><div class="sb-profile-info"><span class="sb-profile-info-label">Username</span><div class="sb-profile-info-value"><i class="bi bi-at"></i>{{ user.username || 'Not provided' }}</div></div><div class="sb-profile-info"><span class="sb-profile-info-label">Email Address</span><div class="sb-profile-info-value"><i class="bi bi-envelope"></i>{{ user.email || 'Not provided' }}</div></div><div class="sb-profile-info"><span class="sb-profile-info-label">Phone</span><div class="sb-profile-info-value"><i class="bi bi-telephone"></i>{{ user.phone || 'Not provided' }}</div></div><div class="sb-profile-info"><span class="sb-profile-info-label">Location</span><div class="sb-profile-info-value"><i class="bi bi-geo-alt"></i>{{ [user.city, user.country].filter(Boolean).join(', ') || 'Not provided' }}</div></div><div class="sb-profile-info"><span class="sb-profile-info-label">Biography</span><div class="sb-profile-info-value"><i class="bi bi-card-text"></i>{{ user.bio || 'No biography has been added yet.' }}</div></div></div>
                </article><aside class="sb-profile-card sb-account-status"><span class="sb-card-overline">ACCOUNT STATUS</span><h2>Account overview</h2><p>{{ statusLabel }}</p><strong>{{ roleLabel }}</strong><a :href="ordersUrl">View orders<i class="bi bi-arrow-right"></i></a><a :href="wishlistUrl">View wishlist<i class="bi bi-arrow-right"></i></a></aside></section>
            </template>
        </div>
    </main>
    <main v-else class="sb-edit-profile-page">
        <div class="container">
            <header class="sb-edit-profile-header"><div><a :href="profileUrl" class="sb-edit-profile-back"><span class="sb-edit-profile-back-icon"><i class="bi bi-arrow-left"></i></span><span>Back to profile</span></a><div class="sb-edit-profile-heading"><span class="sb-edit-profile-eyebrow"><span class="sb-edit-profile-eyebrow-dot"></span>ACCOUNT SETTINGS</span><h1>Edit your <em>profile.</em></h1><p>Keep your personal information accurate and up to date.</p></div></div><div class="sb-edit-profile-header-icon"><i class="bi bi-person-gear"></i></div></header>
            <Transition name="sb-alert"><div v-if="error" class="sb-edit-profile-alert sb-edit-profile-alert-error" role="alert"><div class="sb-edit-profile-alert-icon"><i class="bi bi-exclamation-triangle"></i></div><div class="sb-edit-profile-alert-content"><strong>Please check your information</strong><p>{{ error }}</p></div></div></Transition>
            <Transition name="sb-alert"><div v-if="notice" class="sb-edit-profile-alert sb-edit-profile-alert-success" role="status"><div class="sb-edit-profile-alert-icon"><i class="bi bi-check-lg"></i></div><div class="sb-edit-profile-alert-content"><strong>Profile updated</strong><span>{{ notice }}</span></div></div></Transition>
            <div v-if="loading" class="sb-edit-profile-card" role="status">Loading profile…</div>
            <form v-else class="sb-edit-profile-layout" @submit.prevent="saveProfile">
                <section class="sb-edit-profile-main">
                    <div class="sb-edit-profile-card"><h2>Personal Information</h2><div class="sb-edit-profile-grid">
                        <label>First name<input v-model="fields.first_name" name="first_name" required minlength="2" maxlength="50"></label>
                        <label>Last name<input v-model="fields.last_name" name="last_name" required minlength="2" maxlength="50"></label>
                        <label>Username<input v-model="fields.username" name="username" maxlength="100"></label>
                        <label>Email address<input v-model="fields.email" name="email" type="email" required maxlength="255"></label>
                        <label v-if="fields.email.trim().toLowerCase() !== String(user?.email || '').toLowerCase()">Current password for email change<input v-model="currentPassword" name="current_password" type="password" autocomplete="current-password" required></label>
                        <label>Phone country code<select v-model="fields.phone_country_code" name="phone_country_code" required><option v-for="code in phoneCodes" :key="code" :value="code">{{ code }}</option></select></label>
                        <label>Phone number<input v-model="fields.phone" name="phone" inputmode="numeric"></label>
                        <label>Date of birth<input v-model="fields.date_of_birth" name="date_of_birth" type="date"></label>
                        <label>Gender<select v-model="fields.gender" name="gender"><option value="">Prefer not to say</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option><option value="prefer_not_to_say">Prefer not to say</option></select></label>
                        <label>Country<input v-model="fields.country" name="country" maxlength="120"></label><label>City<input v-model="fields.city" name="city" maxlength="120"></label><label>State<input v-model="fields.state" name="state" maxlength="120"></label><label>Postal code<input v-model="fields.postal_code" name="postal_code" maxlength="30"></label><label class="sb-edit-profile-field-wide">Address<input v-model="fields.address" name="address" maxlength="255"></label><label class="sb-edit-profile-field-wide">Biography<textarea v-model="fields.bio" name="bio" rows="4" maxlength="1000"></textarea></label>
                    </div></div>
                    <div class="sb-edit-profile-card"><h2>Profile Photo</h2><div class="sb-edit-profile-photo"><img v-if="photoUrl" :src="photoUrl" alt="Profile preview"><span v-else>{{ avatarInitials }}</span><label>Choose photo<input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" @change="onPhotoChange"></label><button v-if="user?.profile_photo" type="button" @click="removePhoto">Remove current photo</button></div><small>JPG, JPEG, PNG or WEBP; maximum 2 MB.</small></div>
                    <button type="submit" class="sb-edit-profile-submit" :disabled="saving">{{ saving ? 'Saving…' : 'Save changes' }}</button>
                </section>
            </form>
        </div>
    </main>
</template>