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
    <main class="page" data-vue-page="error-503" data-year="{{ now()->year }}"></main>
</body>
</html>
