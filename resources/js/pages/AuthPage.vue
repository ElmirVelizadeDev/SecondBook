<script setup>
import { computed, reactive, ref } from 'vue';
import authService from '../services/authService';
import accountService from '../services/accountService';

const props = defineProps({
    mode: { type: String, required: true },
    email: { type: String, default: '' },
    passwordVerified: { type: Boolean, default: false },
    homeUrl: { type: String, required: true },
    loginUrl: { type: String, required: true },
    registerUrl: { type: String, required: true },
    passwordRequestUrl: { type: String, required: true },
    passwordVerifyUrl: { type: String, required: true },
    emailVerifyUrl: { type: String, required: true },
    accountDeleteVerifyUrl: { type: String, required: true },
    termsUrl: { type: String, required: true },
});

const forms = reactive({
    login: { email: props.email, password: '', remember: false },
    register: { first_name: '', last_name: '', username: '', email: '', password: '', password_confirmation: '', terms: false },
    request: { email: props.email },
    verify: { otp_code: '', password: '', password_confirmation: '' },
    delete: { current_password: '', otp_code: '' },
});
const loading = ref(false);
const error = ref('');
const success = ref('');
const fieldErrors = ref({});
const passwordVerified = ref(props.passwordVerified);

const title = computed(() => ({
    login: ['Welcome', 'back.'],
    register: ['Create', 'your account.'],
    'password-request': ['Reset', 'your password.'],
    'email-verify': ['Verify', 'your email.'],
    'password-verify': ['Verify', 'your code.'],
    'account-delete-verify': passwordVerified.value ? ['Verify', 'your code.'] : ['Confirm', 'your identity.'],
}[props.mode] || ['SecondBook', '']));

async function send(action) {
    if (loading.value) return;
    loading.value = true;
    error.value = '';
    success.value = '';
    fieldErrors.value = {};
    try {
        let response;
        if (props.mode === 'login') {
            response = await authService.login(forms.login);
        } else if (props.mode === 'register') {
            response = await authService.register(forms.register);
        } else if (props.mode === 'password-request') {
            response = await authService.requestPasswordReset(forms.request.email);
        } else if (props.mode === 'email-verify') {
            response = action === 'resend'
                ? await authService.resendEmailVerification()
                : await authService.verifyEmail(forms.verify.otp_code);
        } else if (props.mode === 'password-verify') {
            response = action === 'resend'
                ? await authService.resendPasswordCode()
                : await authService.verifyPasswordCode(forms.verify);
        } else if (props.mode === 'account-delete-verify') {
            if (!passwordVerified.value) {
                response = await accountService.verifyAccountDeletionPassword(forms.delete.current_password);
                passwordVerified.value = true;
            } else if (action === 'resend') {
                response = await accountService.resendAccountDeletionCode();
            } else {
                response = await accountService.verifyAccountDeletionCode(forms.delete.otp_code);
            }
        }

        success.value = response?.message || 'Your request was completed.';
        if (props.mode === 'login' || props.mode === 'register') {
            window.location.assign(response?.redirect || (props.mode === 'login' ? props.homeUrl : props.emailVerifyUrl));
        } else if (response?.redirect) {
            window.location.assign(response.redirect);
        } else if (props.mode === 'password-request') {
            window.location.assign(props.passwordVerifyUrl);
        } else if (props.mode === 'email-verify') {
            window.location.assign(props.loginUrl);
        } else if (props.mode === 'account-delete-verify' && passwordVerified.value && forms.delete.otp_code) {
            window.location.assign(props.homeUrl);
        }
    } catch (exception) {
        const data = exception.response?.data || {};
        error.value = data.message || 'Your request could not be completed. Please try again.';
        fieldErrors.value = data.errors || {};
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div :class="['auth-page-bg', 'auth-page-bg-login', { 'sb-register-page': mode === 'register', 'sb-password-reset-page': mode === 'password-request', 'sb-password-verify-page': ['email-verify', 'password-verify'].includes(mode) }]">
        <div class="container auth-wrapper py-0">
            <div class="auth-grid auth-grid-login w-100">
                <section :class="['auth-card', 'auth-card-login', 'auth-card-login-pro', { 'sb-login-premium': mode === 'login', 'sb-register-premium': mode === 'register', 'sb-password-reset-premium': mode === 'password-request', 'sb-password-verify-premium': ['email-verify', 'password-verify'].includes(mode) }]">
                    <span v-if="mode === 'register'" class="sb-register-orb sb-register-orb-one"></span>
                    <span v-if="mode === 'password-request'" class="sb-reset-orb sb-reset-orb-one"></span>
                    <span v-if="['email-verify', 'password-verify'].includes(mode)" class="sb-verify-orb sb-verify-orb-one"></span>
                    <div class="sb-login-brand"><a :href="homeUrl" class="sb-login-brand-link" aria-label="SecondBook home"><span class="sb-login-logo-wrap"><img :src="'/main-logo.png'" alt="SecondBook logo" class="sb-login-logo"></span></a></div>
                    <div class="sb-login-head">
                        <span class="sb-login-kicker"><span class="sb-login-kicker-line"></span>{{ mode === 'login' ? 'Welcome back' : mode === 'register' ? 'JOIN SECONDBOOK' : mode === 'password-request' ? 'PASSWORD RECOVERY' : mode === 'email-verify' ? 'VERIFY YOUR EMAIL' : mode === 'account-delete-verify' ? 'ACCOUNT SECURITY' : 'VERIFY YOUR IDENTITY' }}<span class="sb-login-kicker-dot"></span></span>
                        <h1 class="sb-login-title">{{ title[0] }}<span>{{ title[1] }}</span><span class="sb-login-wave" aria-hidden="true">{{ mode === 'login' ? '👋' : mode === 'register' ? '✨' : mode === 'email-verify' ? '✉️' : '🔐' }}</span></h1>
                        <p class="sb-login-subtitle">{{ mode === 'login' ? 'Sign in to continue your reading journey and discover your next great story.' : mode === 'register' ? 'Create your SecondBook account and start your reading journey.' : mode === 'password-request' ? 'Enter your email address and we’ll send you a 6-digit verification code.' : mode === 'email-verify' ? `Enter the 6-digit verification code sent to ${email}.` : mode === 'account-delete-verify' ? (passwordVerified ? `Enter the 6-digit verification code sent to ${email}.` : 'Confirm your current password to continue account deletion.') : 'Enter the 6-digit code sent to your email and create a new password.' }}</p>
                    </div>

                    <div v-if="error" class="sb-login-alert" role="alert"><span class="sb-login-alert-icon"><i class="bi bi-exclamation-circle"></i></span><div class="sb-login-alert-content"><strong>Please check the following</strong><ul><li>{{ error }}</li><li v-for="(messages, field) in fieldErrors" :key="field">{{ messages[0] }}</li></ul></div></div>
                    <div v-if="success" class="alert alert-success" role="status">{{ success }}</div>

                    <form v-if="mode === 'login'" class="sb-login-form" @submit.prevent="send()">
                        <div class="sb-field"><label class="sb-field-label" for="loginEmail">Email address</label><div class="sb-input-group"><span class="sb-input-icon"><i class="bi bi-envelope"></i></span><input id="loginEmail" v-model="forms.login.email" type="email" name="email" autocomplete="email" required></div></div>
                        <div class="sb-field"><label class="sb-field-label" for="loginPassword">Password</label><div class="sb-input-group"><span class="sb-input-icon"><i class="bi bi-lock"></i></span><input id="loginPassword" v-model="forms.login.password" type="password" name="password" autocomplete="current-password" required></div></div>
                        <label class="sb-remember"><input v-model="forms.login.remember" type="checkbox" name="remember"><span>Remember me</span></label>
                        <button type="submit" class="sb-login-submit" :disabled="loading">{{ loading ? 'Signing in…' : 'Sign in' }}<i class="bi bi-arrow-right"></i></button>
                        <p class="sb-login-bottom">New to SecondBook? <a :href="registerUrl">Create an account</a></p>
                        <a class="sb-login-forgot" :href="passwordRequestUrl">Forgot password?</a>
                    </form>

                    <form v-else-if="mode === 'register'" class="sb-login-form" @submit.prevent="send()">
                        <div class="row sb-register-name-row"><div class="col-md-6 sb-field"><label class="sb-field-label" for="first_name">First name</label><input id="first_name" v-model="forms.register.first_name" name="first_name" required maxlength="100"></div><div class="col-md-6 sb-field"><label class="sb-field-label" for="last_name">Last name</label><input id="last_name" v-model="forms.register.last_name" name="last_name" required maxlength="100"></div></div>
                        <div class="sb-field"><label class="sb-field-label" for="username">Username</label><input id="username" v-model="forms.register.username" name="username" required maxlength="50"></div>
                        <div class="sb-field"><label class="sb-field-label" for="register_email">Email address</label><input id="register_email" v-model="forms.register.email" type="email" name="email" autocomplete="email" required></div>
                        <div class="sb-field"><label class="sb-field-label" for="register_password">Password</label><input id="register_password" v-model="forms.register.password" type="password" name="password" autocomplete="new-password" required></div>
                        <div class="sb-field"><label class="sb-field-label" for="register_password_confirmation">Confirm password</label><input id="register_password_confirmation" v-model="forms.register.password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required></div>
                        <label class="sb-terms"><input v-model="forms.register.terms" type="checkbox" name="terms" required><span>I agree to the <a :href="termsUrl">Terms and Conditions</a>.</span></label>
                        <button type="submit" class="sb-login-submit" :disabled="loading">{{ loading ? 'Creating account…' : 'Create account' }}<i class="bi bi-arrow-right"></i></button>
                        <p class="sb-login-bottom">Already have an account? <a :href="loginUrl">Sign in</a></p>
                    </form>

                    <form v-else-if="mode === 'password-request'" class="sb-login-form" @submit.prevent="send()">
                        <div class="sb-field"><label class="sb-field-label" for="reset_email">Email address</label><input id="reset_email" v-model="forms.request.email" type="email" name="email" required autocomplete="email"></div>
                        <button type="submit" class="sb-login-submit" :disabled="loading">{{ loading ? 'Sending code…' : 'Send verification code' }}<i class="bi bi-arrow-right"></i></button>
                        <p class="sb-login-bottom"><a :href="loginUrl">Return to sign in</a></p>
                    </form>

                    <form v-else-if="mode === 'email-verify'" class="sb-login-form" @submit.prevent="send()">
                        <div class="sb-field"><label class="sb-field-label" for="email_otp">Verification code</label><input id="email_otp" v-model="forms.verify.otp_code" type="text" name="otp_code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required></div>
                        <button type="submit" class="sb-login-submit" :disabled="loading">{{ loading ? 'Verifying…' : 'Verify email' }}<i class="bi bi-arrow-right"></i></button>
                        <button type="button" class="sb-login-resend" :disabled="loading" @click="send('resend')">Resend code</button>
                    </form>

                    <form v-else-if="mode === 'password-verify'" class="sb-login-form" @submit.prevent="send()">
                        <div class="sb-field"><label class="sb-field-label" for="password_otp">Verification code</label><input id="password_otp" v-model="forms.verify.otp_code" type="text" name="otp_code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required></div>
                        <div class="sb-field"><label class="sb-field-label" for="new_password">New password</label><input id="new_password" v-model="forms.verify.password" type="password" name="password" autocomplete="new-password" required></div>
                        <div class="sb-field"><label class="sb-field-label" for="new_password_confirmation">Confirm new password</label><input id="new_password_confirmation" v-model="forms.verify.password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required></div>
                        <button type="submit" class="sb-login-submit" :disabled="loading">{{ loading ? 'Verifying…' : 'Update password' }}<i class="bi bi-arrow-right"></i></button>
                        <button type="button" class="sb-login-resend" :disabled="loading" @click="send('resend')">Resend code</button>
                    </form>

                    <form v-else-if="mode === 'account-delete-verify'" class="sb-login-form" @submit.prevent="send()">
                        <div v-if="!passwordVerified" class="sb-field"><label class="sb-field-label" for="delete_current_password">Current password</label><input id="delete_current_password" v-model="forms.delete.current_password" type="password" name="current_password" autocomplete="current-password" required></div>
                        <template v-else><div class="sb-field"><label class="sb-field-label" for="delete_otp">Verification code</label><input id="delete_otp" v-model="forms.delete.otp_code" type="text" name="otp_code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required></div><p class="text-danger">Account deletion is permanent.</p></template>
                        <button type="submit" class="sb-login-submit" :disabled="loading">{{ loading ? 'Verifying…' : passwordVerified ? 'Delete account' : 'Verify password' }}<i class="bi bi-arrow-right"></i></button>
                        <button v-if="passwordVerified" type="button" class="sb-login-resend" :disabled="loading" @click="send('resend')">Resend code</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</template>
