<script setup>
import { onMounted, reactive, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    name: { type: String, default: '' },
    email: { type: String, default: '' },
    profileUrl: { type: String, required: true },
    deleteVerifyUrl: { type: String, required: true },
});

const preferences = reactive({
    email_notifications: true,
    order_updates: true,
    promotional_emails: false,
    profile_visible: true,
});
const currentPassword = ref('');
const loading = ref(true);
const savingPreferences = ref(false);
const requestingPasswordChange = ref(false);
const requestingDeletion = ref(false);
const error = ref('');
const notice = ref('');

onMounted(async () => {
    try {
        const response = await accountService.getPreferences();
        Object.assign(preferences, response.data || {});
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Account preferences could not be loaded.';
    } finally {
        loading.value = false;
    }
});

async function savePreferences() {
    savingPreferences.value = true;
    error.value = '';
    notice.value = '';
    try {
        const response = await accountService.updatePreferences(preferences);
        notice.value = response.message || 'Your account preferences have been updated.';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Your preferences could not be saved.';
    } finally {
        savingPreferences.value = false;
    }
}

async function requestPasswordChange() {
    if (requestingPasswordChange.value) return;
    requestingPasswordChange.value = true;
    error.value = '';
    notice.value = '';
    try {
        const response = await accountService.requestPasswordChange({
            current_password: currentPassword.value,
        });
        notice.value = response.message || 'A verification code has been sent to your email.';
        if (response.redirect) window.location.assign(response.redirect);
        currentPassword.value = '';
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Password verification could not be started.';
    } finally {
        requestingPasswordChange.value = false;
    }
}

async function requestDeletion() {
    if (requestingDeletion.value) return;
    if (!window.confirm('Start the account deletion verification process?')) return;
    requestingDeletion.value = true;
    error.value = '';
    try {
        const response = await accountService.requestAccountDeletion();
        window.location.assign(response.redirect || props.deleteVerifyUrl);
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Account deletion verification could not be started.';
    } finally {
        requestingDeletion.value = false;
    }
}
</script>

<template>
    <section class="account-settings-page">
        <div class="container">
            <header class="account-settings-header">
                <div><span class="account-settings-overline"><i class="bi bi-gear"></i>ACCOUNT SETTINGS</span><h1>Manage Your <em>Account.</em></h1><p>Control your security, notifications and privacy preferences from one place.</p></div>
                <a :href="profileUrl" class="account-back-profile"><i class="bi bi-arrow-left"></i>My Profile</a>
            </header>
            <div v-if="error" class="account-alert account-alert-error" role="alert"><i class="bi bi-exclamation-circle-fill"></i><span>{{ error }}</span></div>
            <div v-if="notice" class="account-alert account-alert-success" role="status"><i class="bi bi-check-circle-fill"></i><span>{{ notice }}</span></div>
            <div v-if="loading" class="account-settings-card" role="status">Loading your settings…</div>
            <div v-else class="account-settings-layout">
                <aside class="account-settings-sidebar">
                    <div class="account-mini-profile"><div class="account-avatar">{{ (name || 'U').slice(0, 1).toUpperCase() }}</div><div><strong>{{ name || 'User' }}</strong><span>{{ email }}</span></div></div>
                    <nav class="account-settings-nav"><a :href="profileUrl"><i class="bi bi-person"></i>Profile information</a><a href="#preferences"><i class="bi bi-bell"></i>Preferences</a><a href="#security"><i class="bi bi-shield-lock"></i>Security</a></nav>
                </aside>
                <div class="account-settings-content">
                    <section id="preferences" class="account-settings-card">
                        <div class="account-card-heading"><span class="account-card-icon"><i class="bi bi-bell"></i></span><div><h2>Notifications & Privacy</h2><p>Choose how SecondBook keeps you informed.</p></div></div>
                        <form @submit.prevent="savePreferences">
                            <label class="account-toggle-row"><span><strong>Email notifications</strong><small>Receive account messages by email.</small></span><input v-model="preferences.email_notifications" type="checkbox" name="email_notifications"></label>
                            <label class="account-toggle-row"><span><strong>Order updates</strong><small>Receive important updates about your orders.</small></span><input v-model="preferences.order_updates" type="checkbox" name="order_updates"></label>
                            <label class="account-toggle-row"><span><strong>Promotional emails</strong><small>Receive occasional news and offers.</small></span><input v-model="preferences.promotional_emails" type="checkbox" name="promotional_emails"></label>
                            <label class="account-toggle-row"><span><strong>Profile visibility</strong><small>Allow your public profile to be visible.</small></span><input v-model="preferences.profile_visible" type="checkbox" name="profile_visible"></label>
                            <button type="submit" class="account-save-btn" :disabled="savingPreferences">{{ savingPreferences ? 'Saving…' : 'Save preferences' }}</button>
                        </form>
                    </section>
                    <section id="security" class="account-settings-card">
                        <div class="account-card-heading"><span class="account-card-icon"><i class="bi bi-shield-lock"></i></span><div><h2>Change Password</h2><p>Verify your current password to receive a secure email code.</p></div></div>
                        <form class="account-security-form" @submit.prevent="requestPasswordChange"><label for="current_password">Current password</label><input id="current_password" v-model="currentPassword" type="password" name="current_password" autocomplete="current-password" required><button type="submit" class="account-save-btn" :disabled="requestingPasswordChange">{{ requestingPasswordChange ? 'Sending code…' : 'Continue password change' }}</button></form>
                    </section>
                    <section class="account-settings-card account-danger-zone">
                        <div class="account-card-heading"><span class="account-card-icon"><i class="bi bi-exclamation-triangle"></i></span><div><h2>Delete Account</h2><p>Account deletion requires password and email verification.</p></div></div>
                        <button type="button" class="account-delete-btn" :disabled="requestingDeletion" @click="requestDeletion">{{ requestingDeletion ? 'Starting verification…' : 'Start account deletion' }}</button>
                    </section>
                </div>
            </div>
        </div>
    </section>
</template>
