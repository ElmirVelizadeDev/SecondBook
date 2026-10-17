<!DOCTYPE html>

<html lang="en">

<head>

@php
    $siteName = \App\Models\Setting::get(
        'site_name',
        'SecondBook'
    );

    $metaTitle = \App\Models\Setting::get(
        'meta_title',
        $siteName
    );

    $metaDescription = \App\Models\Setting::get(
        'meta_description',
        \App\Models\Setting::get('site_description', '')
    );

    $keywords = \App\Models\Setting::get(
        'keywords',
        ''
    );

    $openGraphImage = \App\Models\Setting::get(
        'open_graph_image'
    );

    $searchEngineIndexing = (bool) \App\Models\Setting::get(
        'search_engine_indexing',
        true
    );

    $googleAnalyticsId = \App\Models\Setting::get(
        'google_analytics_id'
    );

    $googleSearchConsoleVerification = \App\Models\Setting::get(
        'google_search_console_verification'
    );

    $favicon = \App\Models\Setting::get(
        'favicon'
    );

    $currentUrl = url()->current();
@endphp

<title>
    @yield('title', $metaTitle)
</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="format-detection"
    content="telephone=no"
>

<meta
    name="apple-mobile-web-app-capable"
    content="yes"
>

<meta
    name="author"
    content="{{ $siteName }}"
>

@if($keywords)
    <meta
        name="keywords"
        content="{{ $keywords }}"
    >
@endif

@if($metaDescription)
    <meta
        name="description"
        content="{{ $metaDescription }}"
    >
@endif

@if(!$searchEngineIndexing)
    <meta
        name="robots"
        content="noindex, nofollow"
    >
@else
    <meta
        name="robots"
        content="index, follow"
    >
@endif


{{-- =========================================================
   OPEN GRAPH
========================================================= --}}

<meta
    property="og:type"
    content="website"
>

<meta
    property="og:title"
    content="@yield('title', $metaTitle)"
>

@if($metaDescription)
    <meta
        property="og:description"
        content="{{ $metaDescription }}"
    >
@endif

<meta
    property="og:url"
    content="{{ $currentUrl }}"
>

<meta
    property="og:site_name"
    content="{{ $siteName }}"
>

@if($openGraphImage)
    <meta
        property="og:image"
        content="{{ asset('storage/' . $openGraphImage) }}"
    >
@endif


{{-- =========================================================
   TWITTER / X CARD
========================================================= --}}

<meta
    name="twitter:card"
    content="summary_large_image"
>

<meta
    name="twitter:title"
    content="@yield('title', $metaTitle)"
>

@if($metaDescription)
    <meta
        name="twitter:description"
        content="{{ $metaDescription }}"
    >
@endif

@if($openGraphImage)
    <meta
        name="twitter:image"
        content="{{ asset('storage/' . $openGraphImage) }}"
    >
@endif


{{-- =========================================================
   GOOGLE SEARCH CONSOLE
========================================================= --}}

@if($googleSearchConsoleVerification)
    <meta
        name="google-site-verification"
        content="{{ $googleSearchConsoleVerification }}"
    >
@endif


{{-- =========================================================
   FAVICON
========================================================= --}}

@if($favicon)
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('storage/' . $favicon) }}"
    >
@else
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('admin/images/logo.png') }}"
    >
@endif


{{-- =========================================================
   BOOTSTRAP
========================================================= --}}

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
    crossorigin="anonymous"
>


{{-- =========================================================
   BOOTSTRAP ICONS
========================================================= --}}

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
>


{{-- =========================================================
   FRONTEND CSS
========================================================= --}}

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/core/normalize.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/icomoon/icomoon.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/core/vendor.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/popular-books.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/popular-categories.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/billboard.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/subscribe.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/latest-articles.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/pages/categories.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/special-offer.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/why-choose.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/featured-books.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/quotation.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/home/cart-alert.css') }}"
>

{{-- =========================================================
   HEADER CSS
========================================================= --}}

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/core/header.css') }}"
>


{{-- =========================================================
   FOOTER CSS
========================================================= --}}

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/core/footer.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/core/footer-bottom.css') }}"
>


{{-- =========================================================
   SWEETALERT2
========================================================= --}}

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- =========================================================
   GOOGLE ANALYTICS
========================================================= --}}

@if($googleAnalyticsId)
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"
    ></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $googleAnalyticsId }}');
    </script>
@endif


{{-- =========================================================
   PAGE-SPECIFIC CSS
========================================================= --}}

@stack('css')

</head>
