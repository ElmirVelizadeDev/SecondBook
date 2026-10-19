<!DOCTYPE html>
<html lang="en" class="error-503-document">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SecondBook — We'll Be Back Soon</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
          rel="stylesheet">

    @vite(['resources/css/pages/error-503.css', 'resources/js/app.js'])

</head>

<body class="error-503-page">

<div class="background">
    <div class="glow glow-one"></div>
    <div class="glow glow-two"></div>
    <div class="grid"></div>

    <div class="paper paper-one"></div>
    <div class="paper paper-two"></div>
    <div class="paper paper-three"></div>
    <div class="paper paper-four"></div>
</div>

<main class="page" data-vue-page="error-503" data-year="{{ date('Y') }}">
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
</main>

<div class="footer">
    © {{ date('Y') }} <span>SecondBook</span> · Thank you for your patience.
</div>

</body>
</html>
