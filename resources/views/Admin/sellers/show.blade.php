@extends('layout.admin.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/sellers.css') }}">
@endpush

@section('title', 'Seller Details')

@section('content')

@php
    $displayName = $seller->name
        ?: ($seller->full_name ?: $seller->username);

    $initial = strtoupper(
        substr($displayName ?: 'S', 0, 1)
    );

    $roleName = 'Seller';

    $status = $seller->status ?: 'active';

    $joinedDate = $seller->created_at?->format('d M Y');

    $verifiedDate = $seller->email_verified_at
        ? $seller->email_verified_at->format('d M Y')
        : null;
@endphp


<div class="dashboard-section seller-show-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}
    <div class="seller-show-hero mb-4">

        <div class="seller-show-hero-content">

            <div class="seller-show-breadcrumb">

                <a href="{{ route('admin.sellers.index') }}">
                    <i class="bi bi-shop"></i>
                    Sellers
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>
                    Seller #{{ $seller->id }}
                </span>

            </div>

            <div class="seller-show-title-row">

                <div>

                    <span class="seller-show-eyebrow">
                        Seller profile
                    </span>

                    <h1>
                        {{ $displayName }}
                    </h1>

                    <p>
                        View seller account details and marketplace inventory.
                    </p>

                </div>

                <div class="seller-show-hero-icon">
                    <i class="bi bi-shop"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TOP PROFILE / QUICK INFO
    ========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- PROFILE --}}
        <div class="col-12 col-xl-8">

            <div class="dashboard-panel seller-profile-panel">

                <div class="seller-profile-main">

                    {{-- AVATAR --}}
                    <div class="seller-profile-avatar-wrap">

                        @if($seller->profile_photo)

                            <img
                                src="{{ asset('storage/' . $seller->profile_photo) }}"
                                alt="{{ $displayName }}"
                                class="seller-profile-avatar seller-profile-avatar-image"
                            >

                        @else

                            <div class="seller-profile-avatar">
                                {{ $initial }}
                            </div>

                        @endif

                    </div>


                    {{-- INFO --}}
                    <div class="seller-profile-info">

                        <div class="seller-profile-name-row">

                            <div>

                                <h2>
                                    {{ $displayName }}
                                </h2>

                                <span class="seller-username">
                                    {{ '@' . $seller->username }}
                                </span>

                            </div>


                            <span
                                class="seller-show-status seller-show-status-{{ $status }}"
                            >
                                <i class="bi bi-circle-fill"></i>
                                {{ ucfirst($status) }}
                            </span>

                        </div>


                        <div class="seller-profile-meta">

                            <span>
                                <i class="bi bi-envelope"></i>
                                {{ $seller->email }}
                            </span>

                            @if($seller->phone)

                                <span>
                                    <i class="bi bi-telephone"></i>
                                    {{ $seller->phone }}
                                </span>

                            @endif

                            @if($joinedDate)

                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    Joined {{ $joinedDate }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- PROFILE FOOTER --}}
                <div class="seller-profile-footer">

                    <div>
                        <span>Account type</span>
                        <strong>
                            <i class="bi bi-shop"></i>
                            {{ $roleName }}
                        </strong>
                    </div>

                    <div>
                        <span>Seller ID</span>
                        <strong>
                            #{{ $seller->id }}
                        </strong>
                    </div>

                    <div>
                        <span>Email verification</span>

                        <strong
                            class="{{ $seller->email_verified_at ? 'text-success' : 'text-warning' }}"
                        >

                            <i
                                class="bi bi-{{ $seller->email_verified_at ? 'check-circle' : 'clock' }}"
                            ></i>

                            {{ $seller->email_verified_at ? 'Verified' : 'Not verified' }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="col-12 col-xl-4">

            <div class="dashboard-panel seller-actions-panel">

                <div class="seller-side-heading">

                    <span class="seller-show-eyebrow">
                        Account actions
                    </span>

                    <h5>
                        Manage seller
                    </h5>

                    <p>
                        Update seller information or return to the directory.
                    </p>

                </div>


                <div class="seller-action-buttons">

                    <a
                        href="{{ route('admin.sellers.edit', $seller) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-pencil-square"></i>
                        Edit seller
                    </a>


                    <a
                        href="{{ route('admin.sellers.index') }}"
                        class="btn btn-light border"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Back to sellers
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         STATS
    ========================================================== --}}
    <div class="row g-4 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="seller-detail-stat stat-blue">

                <div>

                    <span>Total books</span>

                    <strong>
                        {{ number_format($bookStats['total']) }}
                    </strong>

                </div>

                <i class="bi bi-book"></i>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="seller-detail-stat stat-green">

                <div>

                    <span>Approved</span>

                    <strong>
                        {{ number_format($bookStats['approved']) }}
                    </strong>

                </div>

                <i class="bi bi-check-circle"></i>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="seller-detail-stat stat-orange">

                <div>

                    <span>Pending</span>

                    <strong>
                        {{ number_format($bookStats['pending']) }}
                    </strong>

                </div>

                <i class="bi bi-clock-history"></i>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="seller-detail-stat stat-red">

                <div>

                    <span>Rejected</span>

                    <strong>
                        {{ number_format($bookStats['rejected']) }}
                    </strong>

                </div>

                <i class="bi bi-x-circle"></i>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ACCOUNT INFORMATION + VERIFICATION
    ========================================================== --}}
    <div class="row g-4 mb-4">

        <div class="col-12 col-xl-8">

            <div class="dashboard-panel seller-info-panel">

                <div class="seller-section-heading">

                    <div>

                        <span class="seller-show-eyebrow">
                            Personal information
                        </span>

                        <h5>
                            Account details
                        </h5>

                    </div>

                </div>


                <div class="seller-info-grid">

                    <div class="seller-info-item">

                        <span>
                            Full name
                        </span>

                        <strong>
                            {{ $displayName }}
                        </strong>

                    </div>


                    <div class="seller-info-item">

                        <span>
                            Username
                        </span>

                        <strong>
                            {{ $seller->username ?: '-' }}
                        </strong>

                    </div>


                    <div class="seller-info-item">

                        <span>
                            Email address
                        </span>

                        <strong>
                            {{ $seller->email }}
                        </strong>

                    </div>


                    <div class="seller-info-item">

                        <span>
                            Phone number
                        </span>

                        <strong>
                            {{ $seller->phone ?: '-' }}
                        </strong>

                    </div>


                    <div class="seller-info-item">

                        <span>
                            Account status
                        </span>

                        <strong>
                            {{ ucfirst($status) }}
                        </strong>

                    </div>


                    <div class="seller-info-item">

                        <span>
                            Joined
                        </span>

                        <strong>
                            {{ $joinedDate ?: '-' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-xl-4">

            <div class="dashboard-panel seller-verification-panel">

                <div class="seller-section-heading">

                    <div>

                        <span class="seller-show-eyebrow">
                            Verification
                        </span>

                        <h5>
                            Email status
                        </h5>

                    </div>

                    <i
                        class="bi bi-{{ $seller->email_verified_at ? 'shield-check' : 'shield-exclamation' }}"
                    ></i>

                </div>


                @if($seller->email_verified_at)

                    <div class="seller-verification-state verified">

                        <div class="seller-verification-icon">
                            <i class="bi bi-check-lg"></i>
                        </div>

                        <div>

                            <strong>
                                Email verified
                            </strong>

                            <span>
                                Verified on {{ $verifiedDate }}
                            </span>

                        </div>

                    </div>

                @else

                    <div class="seller-verification-state pending">

                        <div class="seller-verification-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>

                            <strong>
                                Email not verified
                            </strong>

                            <span>
                                This seller has not verified their email yet.
                            </span>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         BOOKS
    ========================================================== --}}
    <div class="dashboard-panel seller-books-panel">

        <div class="seller-books-header">

            <div>

                <span class="seller-show-eyebrow">
                    Marketplace inventory
                </span>

                <h5>
                    Seller books
                </h5>

                <p>
                    Books currently associated with this seller account.
                </p>

            </div>

            <span class="seller-books-count">
                {{ number_format($bookStats['total']) }}
                {{ $bookStats['total'] === 1 ? 'book' : 'books' }}
            </span>

        </div>


        <div class="seller-books-table-wrap">

            <table class="table seller-books-table align-middle">

                <thead>

                    <tr>

                        <th>
                            Book
                        </th>

                        <th class="d-none d-md-table-cell">
                            ISBN
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Stock
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="d-none d-lg-table-cell">
                            Added
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($books as $book)

                        <tr>

                            <td>

                                <div class="seller-book-cell">

                                    @if($book->cover)

                                        <img
                                            src="{{ asset('storage/' . $book->cover) }}"
                                            alt="{{ $book->title }}"
                                            class="seller-book-cover"
                                        >

                                    @else

                                        <div class="seller-book-cover seller-book-cover-placeholder">
                                            <i class="bi bi-book"></i>
                                        </div>

                                    @endif


                                    <div>

                                        <strong>
                                            {{ $book->title }}
                                        </strong>

                                        <small>
                                            {{ $book->author?->name ?: 'Unknown author' }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <td class="d-none d-md-table-cell">

                                <span class="seller-book-isbn">
                                    {{ $book->isbn ?: '-' }}
                                </span>

                            </td>


                            <td>

                                <span class="seller-book-price">
                                    {{ number_format((float) $book->price, 2) }}
                                </span>

                            </td>


                            <td>

                                <span class="seller-book-stock">
                                    {{ number_format((int) $book->stock) }}
                                </span>

                            </td>


                            <td>

                                @php
                                    $bookStatus = $book->status ?: 'pending';
                                @endphp

                                <span
                                    class="seller-book-status seller-book-status-{{ $bookStatus }}"
                                >

                                    <i class="bi bi-circle-fill"></i>

                                    {{ ucfirst($bookStatus) }}

                                </span>

                            </td>


                            <td class="d-none d-lg-table-cell">

                                <span class="seller-book-date">
                                    {{ $book->created_at?->format('d M Y') }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="seller-books-empty"
                            >

                                <i class="bi bi-book"></i>

                                <strong>
                                    No books found
                                </strong>

                                <span>
                                    This seller has not added any books yet.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($books->hasPages())

            <div class="seller-books-pagination">

                {{ $books->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
