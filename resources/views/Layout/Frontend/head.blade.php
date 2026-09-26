<!DOCTYPE html>

<html lang="en">

<head>

<title>@yield('title', 'BookSaw - Home | SecondBook')</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="format-detection" content="telephone=no">

<meta name="apple-mobile-web-app-capable" content="yes">

<meta name="author" content="">

<meta name="keywords" content="">

<meta name="description" content="">

{{-- =========================================================
   FAVICON
========================================================= --}}

<link
    rel="icon"
    type="image/png"
    href="{{ asset('admin/images/logo.png') }}"
>

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
    href="{{ asset('frontend-assets/css/normalize.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/icomoon/icomoon.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/vendor.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/popular-books.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/popular-categories.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/billboard.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/subscribe.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/latest-articles.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/categories.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/special-offer.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/why-choose.blade.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/featured-books.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/quotation.css') }}"
>

{{-- =========================================================
   HEADER CSS
========================================================= --}}

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/header.css') }}"
>

{{-- =========================================================
   FOOTER CSS
========================================================= --}}

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/footer.css') }}"
>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('frontend-assets/css/footer-bottom.css') }}"
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
   PAGE-SPECIFIC CSS
========================================================= --}}

@stack('css')


</head>
