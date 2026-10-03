<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Seller Panel') | SecondBook</title>

    <link rel="icon" href="{{ asset('admin/images/logo.png') }}">

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Seller CSS --}}
    <link rel="stylesheet" href="{{ asset('seller/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('seller/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('seller/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('seller/css/dashboard.css') }}">


    @stack('css')
</head>

<body>

    @php
        $sellerUnreadMessagesCount = \App\Models\Message::where('seller_id', auth()->id())
            ->where('status', 'unread')
            ->count();
    @endphp

    <div class="seller-wrapper">

        {{-- Seller Sidebar --}}
        @include('Layout.Seller.sidebar')

        <div class="seller-main">

            {{-- Seller Header --}}
            @include('Layout.Seller.header')

            <main class="seller-content">
                @yield('content')
            </main>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    @stack('js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const toggle = document.getElementById('sellerMobileToggle');
            const sidebar = document.querySelector('.seller-sidebar');

            if (toggle && sidebar) {

                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });

            }

        });
    </script>

</body>

</html>