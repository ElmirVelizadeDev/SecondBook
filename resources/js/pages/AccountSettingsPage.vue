<script setup>
import { onMounted, reactive, ref } from 'vue';
import accountService from '../services/accountService';

const props = defineProps({
    name: { type: String, default: '' },
    email: { type: String, default: '' },
    emailVerified: { type: Boolean, default: false },
    profileUrl: { type: String, required: true },
    deleteVerifyUrl: { type: String, required: true },
    profileCompletion: { type: [String, Number], default: 0 },
    missingProfileFields: { type: String, default: '' },
    exportUrl: { type: String, required: true },
    accountDeletionBlocked: { type: Boolean, default: false },
});

const preferences = reactive({
    email_notifications: true,
    order_updates: true,
    message_updates: true,
    promotional_emails: false,
    profile_visible: true,
});
const currentPassword = ref('');
const loading = ref(true);
const preferencesLoaded = ref(false);
const savingPreferences = ref(false);
const requestingPasswordChange = ref(false);
const requestingDeletion = ref(false);
const error = ref('');
const notice = ref('');
const validationErrors = ref([]);
const sessions = ref({ supported: false, data: [] });
const securityEvents = ref([]);
const revokingSession = ref('');
const sessionsError = ref('');
const eventsError = ref('');
const loadingSecurityData = ref(true);
const completionWidth = ref(0);
const completionValue = ref(0);
const missingFields = (props.missingProfileFields || '').split('|').filter(Boolean);

function clearFeedback() {
    error.value = '';
    notice.value = '';
    validationErrors.value = [];
}

function showRequestError(exception, fallback) {
    error.value = exception.response?.data?.message || fallback;
    const errors = exception.response?.data?.errors;
    validationErrors.value = errors && typeof errors === 'object'
        ? Object.values(errors).flat().filter(Boolean)
        : [];
}

function animateCompletion() {
    const target = Math.max(0, Math.min(100, Number(props.profileCompletion) || 0));
    completionWidth.value = target;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        completionValue.value = target;
        return;
    }
    const duration = 1400;
    const start = performance.now();
    const tick = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        completionValue.value = Math.round(target * (1 - Math.pow(1 - progress, 3)));
        if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

onMounted(async () => {
    const [preferencesResult, sessionsResult, eventsResult] = await Promise.allSettled([
        accountService.getPreferences(),
        accountService.getSessions(),
        accountService.getSecurityEvents(),
    ]);

    if (preferencesResult.status === 'fulfilled') {
        Object.assign(preferences, preferencesResult.value.data || {});
        preferencesLoaded.value = true;
    } else {
        showRequestError(preferencesResult.reason, 'Account preferences could not be loaded.');
    }
    if (sessionsResult.status === 'fulfilled') sessions.value = sessionsResult.value;
    else sessionsError.value = 'Active sessions could not be loaded.';
    if (eventsResult.status === 'fulfilled') securityEvents.value = eventsResult.value.data || [];
    else eventsError.value = 'Security history could not be loaded.';

    loading.value = false;
    loadingSecurityData.value = false;
    setTimeout(animateCompletion, 350);
});

async function savePreferences() {
    if (savingPreferences.value || !preferencesLoaded.value) return;
    savingPreferences.value = true;
    clearFeedback();
    try {
        const response = await accountService.updatePreferences(preferences);
        notice.value = response.message || 'Your account preferences have been updated.';
    } catch (exception) {
        showRequestError(exception, 'Your preferences could not be saved.');
    } finally {
        savingPreferences.value = false;
    }
}

async function requestPasswordChange() {
    if (requestingPasswordChange.value) return;
    requestingPasswordChange.value = true;
    clearFeedback();
    try {
        const response = await accountService.requestPasswordChange({
            current_password: currentPassword.value,
        });
        notice.value = response.message || 'A verification code has been sent to your email.';
        if (response.redirect) window.location.assign(response.redirect);
        currentPassword.value = '';
    } catch (exception) {
        showRequestError(exception, 'Password verification could not be started.');
    } finally {
        requestingPasswordChange.value = false;
    }
}

async function requestDeletion() {
    if (requestingDeletion.value) return;
    if (props.accountDeletionBlocked) return;
    if (!window.confirm('Deleting your account is permanent. Your profile and associated records will be removed according to SecondBook’s data-retention rules. Continue to password and email verification?')) return;
    requestingDeletion.value = true;
    clearFeedback();
    try {
        const response = await accountService.requestAccountDeletion();
        window.location.assign(response.redirect || props.deleteVerifyUrl);
    } catch (exception) {
        showRequestError(exception, 'Account deletion verification could not be started.');
    } finally {
        requestingDeletion.value = false;
    }
}

async function signOutSession(session) {
    if (session.current || revokingSession.value) return;
    if (!window.confirm('Sign out this session?')) return;
    revokingSession.value = session.key;
    clearFeedback();
    try {
        const response = await accountService.revokeSession(session.key);
        sessions.value.data = sessions.value.data.filter((item) => item.key !== session.key);
        notice.value = response.message || 'The session was signed out.';
    } catch (exception) {
        showRequestError(exception, 'This session could not be signed out.');
    } finally {
        revokingSession.value = '';
    }
}

function formatDate(value) {
    return new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value));
}

function reloadPreferences() {
    window.location.reload();
}

function eventLabel(event) {
    return ({
        password_changed: 'Password changed',
        email_updated: 'Email address updated',
    })[event] || 'Security activity';
}
</script>

<template>
    <section class="account-settings-page">
        <div class="container">
            <header class="account-settings-header">
                <div><span class="account-settings-overline"><i class="bi bi-gear"></i>ACCOUNT SETTINGS</span><h1>Manage Your <em>Account.</em></h1><p>Control your security, notifications and privacy preferences from one place.</p></div>
                <a :href="profileUrl" class="account-back-profile"><i class="bi bi-arrow-left"></i>My Profile</a>
            </header>
            <Transition name="sb-alert"><div v-if="error" class="account-alert account-alert-error" role="alert"><i class="bi bi-exclamation-circle-fill"></i><span>{{ error }}</span></div></Transition>
            <Transition name="sb-alert"><div v-if="validationErrors.length" class="account-alert account-alert-error" role="alert"><i class="bi bi-exclamation-circle-fill"></i><ul><li v-for="(message, index) in validationErrors" :key="index">{{ message }}</li></ul></div></Transition>
            <Transition name="sb-alert"><div v-if="notice" class="account-alert account-alert-success" role="status"><i class="bi bi-check-circle-fill"></i><span>{{ notice }}</span></div></Transition>
            <div v-if="loading" class="account-settings-card settings-card" role="status">Loading your settings…</div>
            <div v-else class="account-settings-layout">
                <aside class="account-settings-sidebar">
                    <div class="account-mini-profile"><div class="account-avatar">{{ (name || 'U').slice(0, 1).toUpperCase() }}</div><div><strong>{{ name || 'User' }}</strong><span>{{ email }}</span><small class="account-email-state" :class="emailVerified ? 'is-verified' : 'is-unverified'"><i :class="emailVerified ? 'bi bi-patch-check-fill' : 'bi bi-exclamation-circle'"></i>{{ emailVerified ? 'Verified' : 'Verification required' }}</small></div></div>
                    <nav class="account-settings-nav"><a :href="profileUrl"><i class="bi bi-person"></i>Profile information</a><a href="#preferences"><i class="bi bi-bell"></i>Preferences</a><a href="#security"><i class="bi bi-shield-lock"></i>Security</a><a href="#sessions"><i class="bi bi-pc-display-horizontal"></i>Sessions</a><a href="#security-history"><i class="bi bi-clock-history"></i>History</a><a href="#account-management"><i class="bi bi-person-x"></i>Account management</a></nav>
                </aside>
                <div class="account-settings-content">
                    <section class="account-settings-card settings-card account-completion-card">
                        <div class="account-completion-heading"><div><span>PROFILE COMPLETION</span><strong>{{ completionValue }}%</strong></div><a :href="profileUrl">Complete your profile</a></div>
                        <div class="account-completion-track" role="progressbar" :aria-valuenow="profileCompletion" aria-valuemin="0" aria-valuemax="100"><span :style="{ width: `${completionWidth}%` }"></span></div>
                        <p v-if="missingFields.length">Missing: {{ missingFields.join(', ') }}. <a :href="profileUrl">Update profile details</a></p>
                        <p v-else>Your profile has all recommended details.</p>
                    </section>
                    <section id="preferences" class="account-settings-card settings-card">
                        <div class="account-card-heading settings-card-header"><span class="account-card-icon settings-card-icon"><i class="bi bi-bell"></i></span><div><h2>Notifications & Privacy</h2><p>Choose how SecondBook keeps you informed.</p></div></div>
                        <form class="settings-preferences-form" @submit.prevent="savePreferences">
                            <p v-if="!preferencesLoaded && !loading" class="account-preferences-unavailable">Saved preferences could not be loaded, so changes are disabled. <button type="button" @click="reloadPreferences">Try again</button></p>
                            <fieldset class="account-preferences-fields" :disabled="savingPreferences || !preferencesLoaded">
                            <label class="account-toggle-row settings-toggle-row"><span class="settings-toggle-text"><strong>Email notifications</strong><small>Receive account messages by email.</small></span><input v-model="preferences.email_notifications" type="checkbox" name="email_notifications"><span class="settings-switch" aria-hidden="true"></span></label>
                            <label class="account-toggle-row settings-toggle-row"><span class="settings-toggle-text"><strong>Order updates</strong><small>Receive important updates about your orders.</small></span><input v-model="preferences.order_updates" type="checkbox" name="order_updates"><span class="settings-switch" aria-hidden="true"></span></label>
                            <label class="account-toggle-row settings-toggle-row"><span class="settings-toggle-text"><strong>Message updates</strong><small>Show in-app alerts when a seller replies to your messages.</small></span><input v-model="preferences.message_updates" type="checkbox" name="message_updates"><span class="settings-switch" aria-hidden="true"></span></label>
                            <label class="account-toggle-row settings-toggle-row"><span class="settings-toggle-text"><strong>Promotional emails</strong><small>Receive occasional news and offers.</small></span><input v-model="preferences.promotional_emails" type="checkbox" name="promotional_emails"><span class="settings-switch" aria-hidden="true"></span></label>
                            <label class="account-toggle-row settings-toggle-row"><span class="settings-toggle-text"><strong>Profile visibility</strong><small>Allow your public profile to be visible.</small></span><input v-model="preferences.profile_visible" type="checkbox" name="profile_visible"><span class="settings-switch" aria-hidden="true"></span></label>
                            <button type="submit" class="account-save-btn settings-primary-btn" :disabled="savingPreferences">{{ savingPreferences ? 'Saving…' : 'Save preferences' }}</button>
                            </fieldset>
                        </form>
                    </section>
                    <section id="security" class="account-settings-card settings-card">
                        <div class="account-card-heading settings-card-header"><span class="account-card-icon settings-card-icon"><i class="bi bi-shield-lock"></i></span><div><h2>Change Password</h2><p>Verify your current password to receive a secure email code.</p></div></div>
                        <form class="account-security-form" @submit.prevent="requestPasswordChange"><label for="current_password">Current password</label><input id="current_password" v-model="currentPassword" type="password" name="current_password" autocomplete="current-password" required><button type="submit" class="account-save-btn settings-primary-btn" :disabled="requestingPasswordChange">{{ requestingPasswordChange ? 'Sending code…' : 'Continue password change' }}</button></form>
                    </section>
                    <section id="sessions" class="account-settings-card settings-card">
                        <div class="account-card-heading settings-card-header"><span class="account-card-icon settings-card-icon"><i class="bi bi-pc-display-horizontal"></i></span><div><h2>Active Sessions</h2><p>Review sign-ins recorded by this installation.</p></div></div>
                        <div class="account-security-list" aria-live="polite">
                            <p v-if="loadingSecurityData" class="account-security-empty">Loading security information…</p>
                            <p v-else-if="sessionsError" class="account-security-empty">{{ sessionsError }}</p>
                            <p v-else-if="!sessions.supported" class="account-security-empty">{{ sessions.message || 'Session management is not supported by this session driver.' }}</p>
                            <p v-else-if="!sessions.data.length" class="account-security-empty">No active sessions were found.</p>
                            <TransitionGroup name="sb-alert">
                                <article v-for="session in sessions.data" :key="session.key" class="account-security-row"><div><strong>{{ session.current ? 'This device' : 'Other session' }}</strong><span>{{ session.ip_address || 'IP unavailable' }} · {{ formatDate(session.last_activity) }}</span><small>{{ session.user_agent || 'Device details unavailable' }}</small></div><span v-if="session.current" class="account-current-session">Current</span><button v-else type="button" class="account-session-revoke" :disabled="!!revokingSession" @click="signOutSession(session)">{{ revokingSession === session.key ? 'Signing out…' : 'Sign out' }}</button></article>
                            </TransitionGroup>
                        </div>
                    </section>
                    <section id="security-history" class="account-settings-card settings-card">
                        <div class="account-card-heading settings-card-header"><span class="account-card-icon settings-card-icon"><i class="bi bi-clock-history"></i></span><div><h2>Security History</h2><p>Recent password and email security changes.</p></div></div>
                        <div class="account-security-list" aria-live="polite">
                            <p v-if="loadingSecurityData" class="account-security-empty">Loading security history…</p>
                            <p v-else-if="eventsError" class="account-security-empty">{{ eventsError }}</p>
                            <p v-else-if="!securityEvents.length" class="account-security-empty">No security changes have been recorded yet.</p>
                            <article v-for="event in securityEvents" :key="event.id" class="account-security-row"><div><strong>{{ eventLabel(event.event) }}</strong><span>{{ formatDate(event.created_at) }}</span><small>{{ event.ip_address || 'IP unavailable' }}</small></div></article>
                        </div>
                    </section>
                    <section class="account-settings-card settings-card account-export-card">
                        <div class="account-card-heading settings-card-header"><span class="account-card-icon settings-card-icon"><i class="bi bi-download"></i></span><div><h2>Export Your Information</h2><p>Download a JSON copy of your profile, preferences, orders and reviews.</p></div></div>
                        <a class="account-save-btn settings-primary-btn" :href="exportUrl">Download my data</a>
                    </section>
                    <section id="account-management" class="account-settings-card settings-card account-danger-zone settings-danger-card">
                        <div class="account-card-heading settings-card-header"><span class="account-card-icon settings-card-icon danger"><i class="bi bi-exclamation-triangle"></i></span><div><h2>Delete Account</h2><p>This permanently deletes the account and cascade-linked data, including orders, reviews, store and application records. Refund-linked accounts must be reviewed by support.</p></div></div>
                        <p v-if="accountDeletionBlocked" class="account-delete-blocked">Account deletion is unavailable while refund records are linked to this account or its orders. Contact support to review those records.</p>
                        <button type="button" class="account-delete-btn danger-delete-btn" :disabled="requestingDeletion || accountDeletionBlocked" @click="requestDeletion">{{ requestingDeletion ? 'Starting verification…' : 'Start account deletion' }}</button>
                    </section>
                </div>
            </div>
        </div>
    </section>
</template>