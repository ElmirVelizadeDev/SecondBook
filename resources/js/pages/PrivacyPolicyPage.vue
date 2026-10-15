<script setup>
const collectCards = [
        {'faq' : 'account', 'icon' : 'bi-person',          'title' : 'Account Information',
         'text' : 'Information you provide when creating or managing your account, such as your name, email address, phone number, and account details.'},
        {'faq' : 'order',   'icon' : 'bi-bag',             'title' : 'Order Information',
         'text' : 'Information required to process purchases, deliveries, payments, returns, and other marketplace transactions.'},
        {'faq' : 'support', 'icon' : 'bi-chat-left-text',  'title' : 'Communication',
         'text' : 'Information you provide when contacting support, submitting requests, sending messages, or communicating with SecondBook.'},
        {'faq' : 'privacy', 'icon' : 'bi-bar-chart',       'title' : 'Usage Information',
         'text' : 'Technical and usage information may be collected to help maintain website functionality, security, and overall service performance.'},
    ];

    const uses = [
        {'title' : 'Provide our services',
         'text'  : 'To create accounts, process orders, support marketplace features, and provide requested services.'},
        {'title' : 'Process transactions',
         'text'  : 'To manage purchases, payments, shipping, refunds, and related order activities.'},
        {'title' : 'Improve SecondBook',
         'text'  : 'To understand how our services are used and improve functionality and usability.'},
        {'title' : 'Protect our platform',
         'text'  : 'To detect suspicious activity, maintain security, and help prevent misuse.'},
    ];

    const protections = [
        {'icon' : 'bi-lock',         'title' : 'Account security',
         'text' : 'Account information is handled with security measures designed to help protect your access.'},
        {'icon' : 'bi-shield-check', 'title' : 'Limited access',
         'text' : 'Access to information should be limited to purposes connected with providing our services.'},
        {'icon' : 'bi-eye-slash',    'title' : 'Responsible handling',
         'text' : 'We aim to handle personal information in a transparent and responsible manner.'},
    ];

    const rights = [
        {'faq' : 'access',  'icon' : 'bi-person-lines-fill', 'title' : 'Access',
         'text' : 'Request information about the personal data associated with your account.'},
        {'faq' : 'account', 'icon' : 'bi-pencil-square',     'title' : 'Update',
         'text' : 'Review and update certain account information through your profile and account settings.'},
        {'faq' : 'privacy', 'icon' : 'bi-trash3',            'title' : 'Deletion',
         'text' : 'Depending on applicable requirements, you may request deletion of certain personal information.'},
        {'faq' : 'privacy', 'icon' : 'bi-envelope',          'title' : 'Questions',
         'text' : 'Contact us if you have questions about how your information is handled.'},
    ];

import { onMounted, onBeforeUnmount } from 'vue';
const cleanup = [];
onMounted(() => {
    document.querySelectorAll('.sb-privacy-card[data-faq-url]').forEach((card) => {
        const navigate = () => { const url = card.dataset.faqUrl; if (url) window.location.href = url; };
        const onKeydown = (event) => { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); navigate(); } };
        card.addEventListener('click', navigate);
        card.addEventListener('keydown', onKeydown);
        cleanup.push(() => { card.removeEventListener('click', navigate); card.removeEventListener('keydown', onKeydown); });
    });
});
onBeforeUnmount(() => cleanup.splice(0).forEach((dispose) => dispose()));
</script>

<template>





<div class="sb-privacy-progress" aria-hidden="true"></div>

<main class="sb-privacy-page">




    <section class="sb-privacy-hero">

        <div class="sb-privacy-hero-bg" aria-hidden="true">
            <i class="sb-privacy-orb sb-privacy-orb-1"></i>
            <i class="sb-privacy-orb sb-privacy-orb-2"></i>
            <i class="sb-privacy-ring sb-privacy-ring-1"></i>
            <i class="sb-privacy-ring sb-privacy-ring-2"></i>
        </div>

        <div class="container">

            <div class="sb-privacy-hero-inner">

                <div class="sb-privacy-eyebrow">
                    <span class="sb-privacy-eyebrow-line"></span>

                    <i class="bi bi-shield-check"></i>

                    <span>PRIVACY INFORMATION</span>

                    <span class="sb-privacy-eyebrow-line"></span>
                </div>

                <h1>
                    Privacy <em>Policy</em>
                </h1>

                <p>
                    Learn how SecondBook collects, uses, protects, and manages
                    your information while you use our marketplace.
                </p>

                <div class="sb-privacy-meta">

                    <span>
                        <i class="bi bi-calendar3"></i>
                        Last updated: September 2026
                    </span>

                    <span class="sb-privacy-meta-divider"></span>

                    <span>
                        <i class="bi bi-shield-check"></i>
                        Your privacy matters
                    </span>

                </div>

            </div>

        </div>

    </section>


    <div class="container">



        <section class="sb-privacy-intro sb-privacy-intro-main">

            <div class="sb-privacy-intro-icon">
                <i class="bi bi-lock"></i>
            </div>

            <div>

                <span class="sb-privacy-intro-label">
                    WELCOME TO SECONDBOOK
                </span>

                <h2>
                    Your information deserves careful handling.
                </h2>

                <p>
                    This Privacy Policy explains what information may be
                    collected when you use SecondBook, why it may be used,
                    and the choices available to you.
                </p>

            </div>

        </section>




        <section class="sb-privacy-section">

            <div class="sb-privacy-section-heading">

                <span class="sb-privacy-section-label">
                    INFORMATION WE COLLECT
                </span>

                <h2>
                    What information may<br>
                    we collect?
                </h2>

                <p>
                    The information we collect depends on how you interact
                    with SecondBook and the services you use.
                </p>

            </div>


            <div class="sb-privacy-card-grid">

                <template v-for="(card, index) in collectCards" :key="card.title">

                    <article
                        class="sb-privacy-card"
                        :data-faq-url="'/frontend/faq?search=' + card.faq + '#faqSearch'"
                        role="link"
                        tabindex="0"
                    >

                        <span class="sb-privacy-card-number">{{ String(index + 1).padStart(2, '0') }}</span>

                        <div class="sb-privacy-card-icon">
                            <i :class="['bi', card.icon]"></i>
                        </div>

                        <h3>{{ card.title }}</h3>

                        <p>{{ card.text }}</p>

                        <span class="sb-privacy-card-arrow" aria-hidden="true">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </article>

                </template>

            </div>

        </section>




        <section class="sb-privacy-feature">

            <div class="sb-privacy-feature-icon">
                <i class="bi bi-gear"></i>
            </div>

            <div class="sb-privacy-feature-content">

                <span class="sb-privacy-section-label">
                    HOW WE USE INFORMATION
                </span>

                <h2>
                    Why do we use your information?
                </h2>

                <div class="sb-privacy-feature-grid">

                    <template v-for="(use, index) in uses" :key="use.title">

                        <div class="sb-privacy-feature-item">

                            <span class="sb-privacy-feature-number">{{ String(index + 1).padStart(2, '0') }}</span>

                            <div>
                                <h3>{{ use.title }}</h3>

                                <p>{{ use.text }}</p>
                            </div>

                        </div>

                    </template>

                </div>

            </div>

        </section>




        <section class="sb-privacy-section sb-privacy-protection">

            <div class="sb-privacy-protection-heading">

                <span class="sb-privacy-section-label">
                    DATA PROTECTION
                </span>

                <h2>
                    We take reasonable steps<br>
                    to protect your data.
                </h2>

                <p>
                    We use appropriate technical and organizational
                    measures intended to protect information against
                    unauthorized access, misuse, alteration, or loss.
                </p>

            </div>


            <div class="sb-privacy-protection-list">

                <template v-for="(item, index) in protections" :key="item.title">

                    <div class="sb-privacy-protection-item">

                        <span class="sb-privacy-protection-icon">
                            <i :class="['bi', item.icon]"></i>
                        </span>

                        <div>
                            <h3>{{ item.title }}</h3>

                            <p>{{ item.text }}</p>
                        </div>

                    </div>

                </template>

            </div>

        </section>




        <section class="sb-privacy-intro sb-privacy-cookies">

            <div class="sb-privacy-intro-icon">
                <i class="bi bi-cookie"></i>
            </div>

            <div>

                <span class="sb-privacy-intro-label">
                    COOKIES & TECHNOLOGIES
                </span>

                <h2>
                    About cookies.
                </h2>

                <p>
                    SecondBook may use cookies and similar technologies
                    to remember preferences, support essential website
                    functionality, maintain sessions, and understand
                    how the website is used.
                </p>

                <p class="sb-privacy-secondary-text">
                    Depending on your browser and settings, you may be
                    able to manage or restrict cookies through your
                    browser controls.
                </p>

            </div>

        </section>




        <section class="sb-privacy-section">

            <div class="sb-privacy-section-heading">

                <span class="sb-privacy-section-label">
                    YOUR PRIVACY CHOICES
                </span>

                <h2>
                    You have control<br>
                    over your information.
                </h2>

                <p>
                    Depending on applicable law, you may have rights regarding
                    the personal information associated with your account.
                </p>

            </div>


            <div class="sb-privacy-card-grid">

                <template v-for="(right, index) in rights" :key="right.title">

                    <article
                        class="sb-privacy-card"
                        :data-faq-url="'/frontend/faq?search=' + right.faq + '#faqSearch'"
                        role="link"
                        tabindex="0"
                    >

                        <span class="sb-privacy-card-number">{{ String(index + 1).padStart(2, '0') }}</span>

                        <div class="sb-privacy-card-icon">
                            <i :class="['bi', right.icon]"></i>
                        </div>

                        <h3>{{ right.title }}</h3>

                        <p>{{ right.text }}</p>

                        <span class="sb-privacy-card-arrow" aria-hidden="true">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </article>

                </template>

            </div>

        </section>




        <section class="sb-privacy-note">

            <div class="sb-privacy-note-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>

                <span class="sb-privacy-section-label">
                    IMPORTANT
                </span>

                <h2>
                    This policy may be updated.
                </h2>

                <p>
                    As SecondBook grows and our services change, this
                    Privacy Policy may be updated from time to time.
                    Any updated version should replace the previous
                    version on this page.
                </p>

            </div>

        </section>




        <section class="sb-privacy-contact">

            <div class="sb-privacy-contact-icon">
                <i class="bi bi-headset"></i>
            </div>

            <div class="sb-privacy-contact-content">

                <span class="sb-privacy-section-label">
                    HAVE A QUESTION?
                </span>

                <h2>
                    Need more information about privacy?
                </h2>

                <p>
                    Contact the SecondBook team if you have questions
                    about this Privacy Policy or your personal information.
                </p>

            </div>

            <a
                href="/frontend/contact"
                class="sb-privacy-contact-btn"
            >
                <span>Contact Support</span>
                <i class="bi bi-arrow-right"></i>
            </a>

        </section>




        <div class="sb-privacy-bottom">

            <span>
                <i class="bi bi-shield-check"></i>
                Last Updated: September 2026
            </span>

            <span>
                support@secondbook.com
            </span>

        </div>

    </div>

</main>
</template>
