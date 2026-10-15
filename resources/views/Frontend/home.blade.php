@extends('Layout.Frontend.master')



@section('content')
    @php
        $sellerCtaUrl = route('frontend.auth.login');
        $sellerCtaLabel = 'Become a Seller';

        if (auth()->check()) {
            $user = auth()->user();
            $userRole = $user->role ?? null;

            if (!$userRole && method_exists($user, 'roles')) {
                $userRole = $user->roles()->value('name');
            }

            if (is_object($userRole)) {
                $userRole = $userRole->name ?? null;
            }

            $userRole = strtolower((string) $userRole);

            if ($userRole === 'seller') {
                $sellerCtaUrl = route('seller.dashboard');
                $sellerCtaLabel = 'Seller Panel';
            } elseif (in_array($userRole, [
                'super-admin',
                'admin',
                'manager',
                'editor',
                'moderator',
                'support',
                'accountant',
            ], true)) {
                $sellerCtaUrl = route('admin.dashboard');
                $sellerCtaLabel = 'Admin Panel';
            } else {
                $sellerCtaUrl = route('frontend.seller-application');
            }
        }
    @endphp

    @vite('resources/js/app.js')

    <div
        data-vue-page="home"
        data-books-url="{{ route('frontend.books') }}"
        data-categories-url="{{ route('frontend.categories') }}"
        data-book-details-url="{{ url('/frontend/books') }}"
        data-login-url="{{ route('frontend.auth.login') }}"
        data-register-url="{{ route('frontend.auth.register') }}"
        data-seller-cta-url="{{ $sellerCtaUrl }}"
        data-seller-cta-label="{{ $sellerCtaLabel }}"
    ></div>

@endsection
