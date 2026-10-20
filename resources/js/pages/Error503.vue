<script setup>
defineProps({
    year: {
        type: [String, Number],
        required: true,
    },
});
</script>

<template>
    <div class="background">
        <div class="glow glow-one"></div>
        <div class="glow glow-two"></div>
        <div class="grid"></div>

        <div class="paper paper-one"></div>
        <div class="paper paper-two"></div>
        <div class="paper paper-three"></div>
        <div class="paper paper-four"></div>
    </div>

    <div class="container error-503-fallback">
        <section class="content" role="status" aria-live="polite">
            <div class="brand">
                <div class="brand-mark">📖</div>
                <div class="brand-name">SecondBook</div>
            </div>
            <div class="eyebrow">
                <span class="eyebrow-line"></span>
                Temporarily offline
            </div>
            <h1>A new chapter<br>is <span>loading.</span></h1>
            <p class="description">
                We're taking a little time to improve SecondBook. Our marketplace will be back shortly.
            </p>
            <div class="status">
                <span class="status-dot"></span>
                <span class="status-text">We're working on it</span>
            </div>
        </section>
    </div>

    <div class="footer">
        © {{ year }} <span>SecondBook</span> · Thank you for your patience.
    </div>
</template>
