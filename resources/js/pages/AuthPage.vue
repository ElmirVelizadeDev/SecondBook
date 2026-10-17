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
    register: {
        first_name: '',
        last_name: '',
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
    },
    request: { email: props.email },
    verify: {
        otp_code: '',
        password: '',
        password_confirmation: '',
    },
    delete: { current_password: '', otp_code: '' },
});

const loading = ref(false);
const error = ref('');
const success = ref('');
const fieldErrors = ref({});
const passwordVerified = ref(props.passwordVerified);

const showLoginPassword = ref(false);
const showRegisterPassword = ref(false);
const showRegisterConfirmation = ref(false);
const showNewPassword = ref(false);
const showNewPasswordConfirmation = ref(false);
const showDeletePassword = ref(false);

const isCodeVerification = computed(() =>
    ['email-verify', 'password-verify'].includes(props.mode)
);

const title = computed(() => {
    if (props.mode === 'account-delete-verify') {
        return passwordVerified.value
            ? ['Verify', 'your code.']
            : ['Confirm', 'your identity.'];
    }

    return ({
        login: ['Welcome', 'back.'],
        register: ['Create', 'your account.'],
        'password-request': ['Reset', 'your password.'],
        'email-verify': ['Verify', 'your email.'],
        'password-verify': ['Verify', 'your code.'],
    }[props.mode] || ['SecondBook', '']);
});

const pageKicker = computed(() => ({
    login: 'WELCOME BACK',
    register: 'JOIN SECONDBOOK',
    'password-request': 'PASSWORD RECOVERY',
    'email-verify': 'VERIFY YOUR EMAIL',
    'password-verify': 'VERIFY YOUR IDENTITY',
    'account-delete-verify': 'ACCOUNT SECURITY',
}[props.mode] || 'SECONDBOOK'));

const subtitle = computed(() => {
    if (props.mode === 'login') {
        return 'Sign in to continue your reading journey and discover your next great story.';
    }

    if (props.mode === 'register') {
        return 'Create your SecondBook account and start your reading journey.';
    }

    if (props.mode === 'password-request') {
        return 'Enter your email address and we’ll send you a 6-digit verification code.';
    }

    if (props.mode === 'email-verify') {
        return `Enter the 6-digit verification code sent to ${props.email}.`;
    }

    if (props.mode === 'password-verify') {
        return 'Enter the 6-digit code sent to your email and create a new password.';
    }

    if (props.mode === 'account-delete-verify') {
        return passwordVerified.value
            ? `Enter the 6-digit verification code sent to ${props.email}.`
            : 'Confirm your current password to continue account deletion.';
    }

    return '';
});

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
            response = await authService.requestPasswordReset(
                forms.request.email
            );
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
                response = await accountService.verifyAccountDeletionPassword(
                    forms.delete.current_password
                );

                passwordVerified.value = true;
            } else if (action === 'resend') {
                response = await accountService.resendAccountDeletionCode();
            } else {
                response = await accountService.verifyAccountDeletionCode(
                    forms.delete.otp_code
                );
            }
        }

        success.value = response?.message || 'Your request was completed.';

        if (props.mode === 'login' || props.mode === 'register') {
            window.location.assign(
                response?.redirect ||
                (props.mode === 'login' ? props.homeUrl : props.emailVerifyUrl)
            );
        } else if (response?.redirect) {
            window.location.assign(response.redirect);
        } else if (props.mode === 'password-request') {
            window.location.assign(props.passwordVerifyUrl);
        } else if (props.mode === 'email-verify') {
            window.location.assign(props.loginUrl);
        } else if (
            props.mode === 'account-delete-verify' &&
            passwordVerified.value &&
            forms.delete.otp_code
        ) {
            window.location.assign(props.homeUrl);
        }
    } catch (exception) {
        const data = exception.response?.data || {};

        error.value = data.message ||
            'Your request could not be completed. Please try again.';

        fieldErrors.value = data.errors || {};
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div
        :class="[
            'auth-page-bg',
            'auth-page-bg-login',
            {
                'sb-register-page': mode === 'register',
                'sb-password-reset-page': mode === 'password-request',
                'sb-password-verify-page': isCodeVerification,
                'auth-delete-page-bg': mode === 'account-delete-verify',
            }
        ]"
    >
        <div
            :class="[
                'container',
                'py-0',
                mode === 'account-delete-verify'
                    ? 'auth-delete-wrapper'
                    : 'auth-wrapper'
            ]"
        >
            <div
                :class="[
                    'w-100',
                    mode === 'account-delete-verify'
                        ? 'auth-delete-grid'
                        : 'auth-grid auth-grid-login'
                ]"
            >
                <section
                    :class="[
                        'auth-card',
                        'auth-card-login',
                        'auth-card-login-pro',
                        {
                            'sb-login-premium': mode === 'login',
                            'sb-register-premium': mode === 'register',
                            'sb-password-reset-premium': mode === 'password-request',
                            'sb-password-verify-premium': isCodeVerification,
                            'sb-account-delete-verify-premium':
                                mode === 'account-delete-verify',
                        }
                    ]"
                >
                    <!-- Decorations -->
                    <template v-if="mode === 'register'">
                        <span class="sb-register-orb sb-register-orb-one"></span>
                        <span class="sb-register-orb sb-register-orb-two"></span>
                        <span class="sb-register-card-line"></span>
                    </template>

                    <template v-if="mode === 'password-request'">
                        <span class="sb-reset-orb sb-reset-orb-one"></span>
                        <span class="sb-reset-orb sb-reset-orb-two"></span>
                        <span class="sb-reset-card-line"></span>
                    </template>

                    <template v-if="isCodeVerification">
                        <span class="sb-verify-orb sb-verify-orb-one"></span>
                        <span class="sb-verify-orb sb-verify-orb-two"></span>
                        <span class="sb-verify-card-line"></span>
                    </template>

                    <template v-if="mode === 'account-delete-verify'">
                        <span class="sb-account-delete-orb sb-account-delete-orb-one"></span>
                        <span class="sb-account-delete-orb sb-account-delete-orb-two"></span>
                        <span class="sb-account-delete-card-line"></span>
                    </template>

                    <!-- Brand -->
                    <div
                        :class="mode === 'account-delete-verify'
                            ? 'sb-account-delete-brand'
                            : 'sb-login-brand'"
                    >
                        <a
                            :href="homeUrl"
                            :class="mode === 'account-delete-verify'
                                ? 'sb-account-delete-brand-link'
                                : 'sb-login-brand-link'"
                            aria-label="SecondBook home"
                        >
                            <span
                                :class="mode === 'account-delete-verify'
                                    ? 'sb-account-delete-logo-wrap'
                                    : 'sb-login-logo-wrap'"
                            >
                                <img
                                    :src="'/main-logo.png'"
                                    alt="SecondBook logo"
                                    :class="mode === 'account-delete-verify'
                                        ? 'sb-account-delete-logo'
                                        : 'sb-login-logo'"
                                >
                            </span>
                        </a>
                    </div>

                    <!-- Heading -->
                    <div
                        :class="mode === 'account-delete-verify'
                            ? 'sb-account-delete-head'
                            : 'sb-login-head'"
                    >
                        <span
                            :class="mode === 'account-delete-verify'
                                ? 'sb-account-delete-kicker'
                                : 'sb-login-kicker'"
                        >
                            <span
                                :class="mode === 'account-delete-verify'
                                    ? 'sb-account-delete-kicker-line'
                                    : 'sb-login-kicker-line'"
                            ></span>

                            {{ pageKicker }}

                            <span
                                :class="mode === 'account-delete-verify'
                                    ? 'sb-account-delete-kicker-dot'
                                    : 'sb-login-kicker-dot'"
                            ></span>
                        </span>

                        <h1
                            :class="mode === 'account-delete-verify'
                                ? 'sb-account-delete-title'
                                : 'sb-login-title'"
                        >
                            {{ title[0] }} <span>{{ title[1] }}</span>
                            <span
                                :class="mode === 'account-delete-verify'
                                    ? 'sb-account-delete-wave'
                                    : 'sb-login-wave'"
                                aria-hidden="true"
                            >
                                {{
                                    mode === 'login' ? '👋' :
                                    mode === 'register' ? '✨' :
                                    mode === 'email-verify' ? '✉️' :
                                    mode === 'account-delete-verify' ? '🔐' :
                                    '🔐'
                                }}
                            </span>
                        </h1>

                        <p
                            :class="mode === 'account-delete-verify'
                                ? 'sb-account-delete-subtitle'
                                : 'sb-login-subtitle'"
                        >
                            {{ subtitle }}
                        </p>
                    </div>

                    <!-- Error -->
                    <div
                        v-if="error"
                        :class="mode === 'account-delete-verify'
                            ? 'sb-account-delete-alert-danger'
                            : isCodeVerification
                                ? 'sb-login-alert sb-verify-alert-danger'
                                : 'sb-login-alert'"
                        role="alert"
                    >
                        <span class="sb-login-alert-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">
                            <strong>Please check the following</strong>
                            <span>{{ error }}</span>

                            <ul v-if="Object.keys(fieldErrors).length">
                                <li
                                    v-for="(messages, field) in fieldErrors"
                                    :key="field"
                                >
                                    {{ messages[0] }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Success -->
                    <div
                        v-if="success"
                        :class="mode === 'account-delete-verify'
                            ? 'sb-account-delete-alert-success'
                            : isCodeVerification
                                ? 'sb-login-alert sb-verify-alert-success'
                                : 'sb-login-alert'"
                        role="status"
                    >
                        <span class="sb-login-alert-icon">
                            <i class="bi bi-check-circle"></i>
                        </span>

                        <div class="sb-login-alert-content">
                            <strong>Success</strong>
                            <span>{{ success }}</span>
                        </div>
                    </div>

                    <!-- Login -->
                    <form
                        v-if="mode === 'login'"
                        class="sb-login-form"
                        @submit.prevent="send()"
                    >
                        <div class="sb-field">
                            <label class="sb-field-label" for="loginEmail">
                                Email address
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input
                                    id="loginEmail"
                                    v-model="forms.login.email"
                                    class="sb-login-input"
                                    type="email"
                                    name="email"
                                    autocomplete="email"
                                    placeholder="Enter your email"
                                    required
                                >
                            </div>
                        </div>

                        <div class="sb-field">
                            <label class="sb-field-label" for="loginPassword">
                                Password
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input
                                    id="loginPassword"
                                    v-model="forms.login.password"
                                    class="sb-login-input"
                                    :type="showLoginPassword ? 'text' : 'password'"
                                    name="password"
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="sb-pass-toggle"
                                    :aria-label="showLoginPassword ? 'Hide password' : 'Show password'"
                                    @click="showLoginPassword = !showLoginPassword"
                                >
                                    <i :class="showLoginPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sb-login-options">
                            <label class="sb-remember">
                                <input
                                    v-model="forms.login.remember"
                                    class="sb-custom-checkbox"
                                    type="checkbox"
                                    name="remember"
                                >
                                <span class="sb-remember-text">Remember me</span>
                            </label>

                            <a class="sb-forgot-link" :href="passwordRequestUrl">
                                Forgot password?
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="sb-signin-btn"
                            :disabled="loading"
                        >
                            <span class="sb-signin-content">
                                <span class="sb-signin-icon">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </span>
                                <span class="sb-signin-text">
                                    {{ loading ? 'Signing in…' : 'Sign in' }}
                                </span>
                            </span>

                            <span class="sb-signin-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>

                        <div class="sb-login-divider">
                            <span></span>
                            <small>NEW TO SECONDBOOK?</small>
                            <span></span>
                        </div>

                        <p class="sb-register-copy">
                            Start your reading journey.
                            <a class="sb-register-link" :href="registerUrl">
                                Create an account
                            </a>
                        </p>

                        <div class="sb-login-bottom-mark">
                            <span></span>
                            <i class="bi bi-book-half"></i>
                            <span></span>
                        </div>
                    </form>

                    <!-- Register -->
                    <form
                        v-else-if="mode === 'register'"
                        class="sb-login-form"
                        @submit.prevent="send()"
                    >
                        <div class="row sb-register-name-row">
                            <div class="col-md-6 sb-field">
                                <label class="sb-field-label" for="first_name">
                                    First name
                                </label>

                                <div class="sb-input-group">
                                    <span class="sb-input-icon">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input
                                        id="first_name"
                                        v-model="forms.register.first_name"
                                        class="sb-login-input"
                                        name="first_name"
                                        autocomplete="given-name"
                                        placeholder="First name"
                                        maxlength="100"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-md-6 sb-field">
                                <label class="sb-field-label" for="last_name">
                                    Last name
                                </label>

                                <div class="sb-input-group">
                                    <span class="sb-input-icon">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input
                                        id="last_name"
                                        v-model="forms.register.last_name"
                                        class="sb-login-input"
                                        name="last_name"
                                        autocomplete="family-name"
                                        placeholder="Last name"
                                        maxlength="100"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="sb-field">
                            <label class="sb-field-label" for="username">
                                Username
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input
                                    id="username"
                                    v-model="forms.register.username"
                                    class="sb-login-input"
                                    name="username"
                                    autocomplete="username"
                                    placeholder="Choose a username"
                                    maxlength="50"
                                    required
                                >
                            </div>
                        </div>

                        <div class="sb-field">
                            <label class="sb-field-label" for="register_email">
                                Email address
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    id="register_email"
                                    v-model="forms.register.email"
                                    class="sb-login-input"
                                    type="email"
                                    name="email"
                                    autocomplete="email"
                                    placeholder="Enter your email"
                                    required
                                >
                            </div>
                        </div>

                        <div class="sb-field">
                            <label class="sb-field-label" for="register_password">
                                Password
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input
                                    id="register_password"
                                    v-model="forms.register.password"
                                    class="sb-login-input"
                                    :type="showRegisterPassword ? 'text' : 'password'"
                                    name="password"
                                    autocomplete="new-password"
                                    placeholder="Create a password"
                                    required
                                >
                                <button
                                    type="button"
                                    class="sb-pass-toggle"
                                    :aria-label="showRegisterPassword ? 'Hide password' : 'Show password'"
                                    @click="showRegisterPassword = !showRegisterPassword"
                                >
                                    <i :class="showRegisterPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sb-field">
                            <label
                                class="sb-field-label"
                                for="register_password_confirmation"
                            >
                                Confirm password
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input
                                    id="register_password_confirmation"
                                    v-model="forms.register.password_confirmation"
                                    class="sb-login-input"
                                    :type="showRegisterConfirmation ? 'text' : 'password'"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    placeholder="Confirm your password"
                                    required
                                >
                                <button
                                    type="button"
                                    class="sb-pass-toggle"
                                    :aria-label="showRegisterConfirmation ? 'Hide password' : 'Show password'"
                                    @click="showRegisterConfirmation = !showRegisterConfirmation"
                                >
                                    <i :class="showRegisterConfirmation ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sb-register-options">
                            <label class="sb-register-terms">
                                <input
                                    v-model="forms.register.terms"
                                    class="sb-custom-checkbox"
                                    type="checkbox"
                                    name="terms"
                                    required
                                >
                                <span class="sb-register-terms-text">
                                    I agree to the
                                    <a :href="termsUrl">Terms and Conditions</a>.
                                </span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="sb-register-submit"
                            :disabled="loading"
                        >
                            <span class="sb-signin-content">
                                <span class="sb-signin-icon">
                                    <i class="bi bi-person-plus"></i>
                                </span>
                                <span class="sb-signin-text">
                                    {{ loading ? 'Creating account…' : 'Create account' }}
                                </span>
                            </span>

                            <span class="sb-signin-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>

                        <div class="sb-register-benefits">
                            <div class="sb-register-benefit">
                                <span class="sb-register-benefit-icon">
                                    <i class="bi bi-book"></i>
                                </span>
                                <span>Discover books</span>
                            </div>

                            <div class="sb-register-benefit">
                                <span class="sb-register-benefit-icon">
                                    <i class="bi bi-heart"></i>
                                </span>
                                <span>Save favorites</span>
                            </div>
                        </div>

                        <p class="sb-register-login">
                            Already have an account?
                            <a class="sb-register-link" :href="loginUrl">
                                Sign in
                            </a>
                        </p>

                        <div class="sb-login-bottom-mark">
                            <span></span>
                            <i class="bi bi-book-half"></i>
                            <span></span>
                        </div>
                    </form>

                    <!-- Password Reset Request -->
                    <form
                        v-else-if="mode === 'password-request'"
                        class="sb-login-form"
                        @submit.prevent="send()"
                    >
                        <div class="sb-field">
                            <label class="sb-field-label" for="reset_email">
                                Email address
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    id="reset_email"
                                    v-model="forms.request.email"
                                    class="sb-login-input"
                                    type="email"
                                    name="email"
                                    autocomplete="email"
                                    placeholder="Enter your email"
                                    required
                                >
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="sb-reset-submit"
                            :disabled="loading"
                        >
                            <span class="sb-signin-content">
                                <span class="sb-signin-icon">
                                    <i class="bi bi-send"></i>
                                </span>
                                <span class="sb-signin-text">
                                    {{ loading ? 'Sending code…' : 'Send verification code' }}
                                </span>
                            </span>

                            <span class="sb-signin-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>

                        <div class="sb-reset-navigation">
                            <a class="sb-reset-login" :href="loginUrl">
                                <i class="bi bi-arrow-left"></i>
                                Return to sign in
                            </a>
                        </div>

                        <div class="sb-login-bottom-mark">
                            <span></span>
                            <i class="bi bi-shield-lock"></i>
                            <span></span>
                        </div>
                    </form>

                    <!-- Email Verify -->
                    <form
                        v-else-if="mode === 'email-verify'"
                        class="sb-login-form"
                        @submit.prevent="send()"
                    >
                        <div class="sb-field sb-verify-field">
                            <label class="sb-field-label" for="email_otp">
                                Verification code
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <input
                                    id="email_otp"
                                    v-model="forms.verify.otp_code"
                                    class="sb-login-input sb-otp-input"
                                    type="text"
                                    name="otp_code"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    pattern="[0-9]{6}"
                                    maxlength="6"
                                    placeholder="Enter 6-digit code"
                                    required
                                >
                                <span class="sb-input-status">
                                    <i class="bi bi-key"></i>
                                </span>
                            </div>
                        </div>

                        <div class="sb-verify-resend">
                            <span class="sb-verify-resend-icon">
                                <i class="bi bi-envelope-arrow-up"></i>
                            </span>

                            <div class="sb-verify-resend-content">
                                Didn't receive the code?
                                <button
                                    type="button"
                                    class="sb-verify-resend-link"
                                    :disabled="loading"
                                    @click="send('resend')"
                                >
                                    Resend code
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="sb-signin-btn"
                            :disabled="loading"
                        >
                            <span class="sb-signin-content">
                                <span class="sb-signin-icon">
                                    <i class="bi bi-envelope-check"></i>
                                </span>
                                <span class="sb-signin-text">
                                    {{ loading ? 'Verifying…' : 'Verify email' }}
                                </span>
                            </span>

                            <span class="sb-signin-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>

                        <div class="sb-verify-navigation">
                            <a class="sb-verify-login" :href="loginUrl">
                                Return to sign in
                            </a>
                        </div>

                        <div class="sb-login-bottom-mark">
                            <span></span>
                            <i class="bi bi-shield-check"></i>
                            <span></span>
                        </div>
                    </form>

                    <!-- Password Verify -->
                    <form
                        v-else-if="mode === 'password-verify'"
                        class="sb-login-form"
                        @submit.prevent="send()"
                    >
                        <div class="sb-field sb-verify-field">
                            <label class="sb-field-label" for="password_otp">
                                Verification code
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <input
                                    id="password_otp"
                                    v-model="forms.verify.otp_code"
                                    class="sb-login-input sb-otp-input"
                                    type="text"
                                    name="otp_code"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    pattern="[0-9]{6}"
                                    maxlength="6"
                                    placeholder="Enter 6-digit code"
                                    required
                                >
                                <span class="sb-input-status">
                                    <i class="bi bi-key"></i>
                                </span>
                            </div>
                        </div>

                        <div class="sb-field sb-verify-field">
                            <label class="sb-field-label" for="new_password">
                                New password
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input
                                    id="new_password"
                                    v-model="forms.verify.password"
                                    class="sb-login-input"
                                    :type="showNewPassword ? 'text' : 'password'"
                                    name="password"
                                    autocomplete="new-password"
                                    placeholder="Create a new password"
                                    required
                                >
                                <button
                                    type="button"
                                    class="sb-pass-toggle"
                                    :aria-label="showNewPassword ? 'Hide password' : 'Show password'"
                                    @click="showNewPassword = !showNewPassword"
                                >
                                    <i :class="showNewPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sb-field sb-verify-field">
                            <label
                                class="sb-field-label"
                                for="new_password_confirmation"
                            >
                                Confirm new password
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input
                                    id="new_password_confirmation"
                                    v-model="forms.verify.password_confirmation"
                                    class="sb-login-input"
                                    :type="showNewPasswordConfirmation ? 'text' : 'password'"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    placeholder="Confirm your new password"
                                    required
                                >
                                <button
                                    type="button"
                                    class="sb-pass-toggle"
                                    :aria-label="showNewPasswordConfirmation ? 'Hide password' : 'Show password'"
                                    @click="showNewPasswordConfirmation = !showNewPasswordConfirmation"
                                >
                                    <i :class="showNewPasswordConfirmation ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sb-verify-resend">
                            <span class="sb-verify-resend-icon">
                                <i class="bi bi-envelope-arrow-up"></i>
                            </span>

                            <div class="sb-verify-resend-content">
                                Need another code?
                                <button
                                    type="button"
                                    class="sb-verify-resend-link"
                                    :disabled="loading"
                                    @click="send('resend')"
                                >
                                    Resend code
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="sb-signin-btn"
                            :disabled="loading"
                        >
                            <span class="sb-signin-content">
                                <span class="sb-signin-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <span class="sb-signin-text">
                                    {{ loading ? 'Updating…' : 'Update password' }}
                                </span>
                            </span>

                            <span class="sb-signin-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>

                        <div class="sb-verify-navigation">
                            <a class="sb-verify-login" :href="loginUrl">
                                Return to sign in
                            </a>
                        </div>

                        <div class="sb-login-bottom-mark">
                            <span></span>
                            <i class="bi bi-shield-check"></i>
                            <span></span>
                        </div>
                    </form>

                    <!-- Account Delete Verify -->
                    <form
                        v-else-if="mode === 'account-delete-verify'"
                        class="sb-login-form"
                        @submit.prevent="send()"
                    >
                        <div
                            v-if="!passwordVerified"
                            class="sb-account-delete-field"
                        >
                            <label
                                class="sb-field-label"
                                for="delete_current_password"
                            >
                                Current password
                                <span>Required</span>
                            </label>

                            <div class="sb-input-group">
                                <span class="sb-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input
                                    id="delete_current_password"
                                    v-model="forms.delete.current_password"
                                    class="sb-login-input"
                                    :type="showDeletePassword ? 'text' : 'password'"
                                    name="current_password"
                                    autocomplete="current-password"
                                    placeholder="Enter your current password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="sb-pass-toggle"
                                    :aria-label="showDeletePassword ? 'Hide password' : 'Show password'"
                                    @click="showDeletePassword = !showDeletePassword"
                                >
                                    <i :class="showDeletePassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <template v-else>
                            <div class="sb-account-delete-field">
                                <label class="sb-field-label" for="delete_otp">
                                    Verification code
                                    <span>6 digits</span>
                                </label>

                                <div class="sb-input-group sb-otp-group">
                                    <span class="sb-input-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </span>

                                    <input
                                        id="delete_otp"
                                        v-model="forms.delete.otp_code"
                                        class="sb-login-input sb-otp-input"
                                        type="text"
                                        name="otp_code"
                                        inputmode="numeric"
                                        autocomplete="one-time-code"
                                        pattern="[0-9]{6}"
                                        maxlength="6"
                                        placeholder="Enter 6-digit code"
                                        required
                                    >

                                    <span class="sb-input-status">
                                        <i class="bi bi-key"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="sb-verify-resend">
                                <span class="sb-verify-resend-icon">
                                    <i class="bi bi-envelope-arrow-up"></i>
                                </span>

                                <div class="sb-verify-resend-content">
                                    Didn't receive the code?
                                    <button
                                        type="button"
                                        class="sb-verify-resend-link"
                                        :disabled="loading"
                                        @click="send('resend')"
                                    >
                                        Resend code
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <button
                            type="submit"
                            class="sb-account-delete-submit"
                            :disabled="loading"
                        >
                            <span class="sb-signin-content">
                                <span class="sb-signin-icon">
                                    <i
                                        :class="passwordVerified
                                            ? 'bi bi-trash3'
                                            : 'bi bi-shield-lock'"
                                    ></i>
                                </span>

                                <span class="sb-signin-text">
                                    {{
                                        loading
                                            ? 'Verifying…'
                                            : passwordVerified
                                                ? 'Delete account'
                                                : 'Verify password'
                                    }}
                                </span>
                            </span>

                            <span class="sb-signin-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </button>

                        <div class="sb-account-delete-security">
                            <span class="sb-account-delete-security-icon">
                                <i class="bi bi-shield-exclamation"></i>
                            </span>

                            <div class="sb-account-delete-security-content">
                                <strong>Account deletion is permanent</strong>
                                <span>
                                    Your account and associated data may be permanently deleted.
                                    Make sure you want to continue.
                                </span>
                            </div>
                        </div>

                        <div class="sb-account-delete-bottom-mark">
                            <span></span>
                            <i class="bi bi-shield-lock"></i>
                            <span></span>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</template>
