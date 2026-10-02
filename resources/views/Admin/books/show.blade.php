@extends('layout.admin.master')

@section('title', 'Book Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/books.css') }}">
@endpush

@section('content')

@php
    $status = strtolower($book->status ?? '');

    $statusClass = match ($status) {
        'approved' => 'book-status-approved',
        'pending'  => 'book-status-pending',
        'rejected' => 'book-status-rejected',
        default    => 'book-status-default',
    };

    $condition = strtolower($book->condition ?? '');

    $conditionClass = match ($condition) {
        'new'      => 'condition-new',
        'like_new' => 'condition-like-new',
        'good'     => 'condition-good',
        'fair'     => 'condition-fair',
        default    => 'condition-default',
    };

    $sellerName = $book->seller?->name
        ?? $book->seller?->username
        ?? 'Unknown seller';

    $sellerInitial = strtoupper(mb_substr($sellerName, 0, 1));

    $coverUrl = null;

    if (!empty($book->cover)) {
        $coverUrl = filter_var($book->cover, FILTER_VALIDATE_URL)
            ? $book->cover
            : asset('storage/' . $book->cover);
    }
@endphp

<div class="dashboard-section books-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="books-hero">

        <div class="books-hero-content">

            <span class="books-hero-badge">
                <i class="bi bi-book-half"></i>
                Book details
            </span>

            <h1>{{ $book->title ?: 'Untitled book' }}</h1>

            <p>
                Full information about this listing: inventory, pricing,
                publishing details and the seller behind it.
            </p>

        </div>

        <div class="books-hero-mark" aria-hidden="true">
            <i class="bi bi-book"></i>
        </div>

    </section>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <section class="books-stats">

        <div class="book-stat-card stat-blue">

            <div class="book-stat-content">
                <span>Price</span>
                <strong>${{ number_format((float) ($book->price ?? 0), 2) }}</strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-tag"></i>
            </div>

        </div>


        <div class="book-stat-card stat-green">

            <div class="book-stat-content">
                <span>In stock</span>
                <strong>{{ $book->stock ?? 0 }}</strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>

        </div>


        <div class="book-stat-card stat-orange">

            <div class="book-stat-content">
                <span>Pages</span>
                <strong>{{ $book->pages ?: '—' }}</strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>

        </div>


        <div class="book-stat-card stat-purple">

            <div class="book-stat-content">
                <span>Published</span>
                <strong>{{ $book->publication_year ?: '—' }}</strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-calendar3"></i>
            </div>

        </div>

    </section>


    {{-- =========================================================
        MAIN PANEL
    ========================================================== --}}
    <section class="dashboard-panel books-panel">

        {{-- =====================================================
            PANEL HEADER
        ====================================================== --}}
        <div class="books-panel-header">

            <div class="books-heading-content">

                <span class="eyebrow">
                    Book information
                </span>

                <h5>
                    {{ $book->title ?: 'Untitled book' }}
                </h5>

                <p>
                    Everything SecondBook knows about this listing.
                </p>

            </div>


            <div class="books-header-action book-actions">

                <a
                    href="{{ route('admin.books.index') }}"
                    class="book-clear-filter"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to books</span>
                </a>

                <a
                    href="{{ route('admin.books.edit', $book->id) }}"
                    class="books-add-btn"
                >
                    <i class="bi bi-pencil"></i>
                    <span>Edit Book</span>
                </a>

            </div>

        </div>


        {{-- =====================================================
            OVERVIEW TABLE
        ====================================================== --}}
        <div class="books-table-wrap">

            <table class="books-table">

                <thead>

                    <tr>
                        <th class="books-col-book">Book</th>
                        <th class="books-col-category">Category</th>
                        <th class="books-col-author">Author</th>
                        <th class="books-col-seller">Seller</th>
                        <th class="books-col-price">Price</th>
                        <th class="books-col-condition">Condition</th>
                        <th class="books-col-status">Status</th>
                    </tr>

                </thead>


                <tbody>

                    <tr>

                        {{-- Book --}}
                        <td>

                            <div class="book-item-cell">

                                @if($coverUrl)

                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="{{ $book->title }}"
                                        class="book-cover-image"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="book-cover-placeholder">
                                        <i class="bi bi-book"></i>
                                    </div>

                                @endif

                                <div class="book-item-info">

                                    <strong title="{{ $book->title }}">
                                        {{ $book->title ?: 'Untitled book' }}
                                    </strong>

                                    <small>
                                        {{ $book->isbn ? 'ISBN: ' . $book->isbn : 'No ISBN' }}
                                    </small>

                                </div>

                            </div>

                        </td>


                        {{-- Category --}}
                        <td>

                            <div class="book-simple-info">
                                <strong>{{ $book->category?->name ?? '—' }}</strong>
                            </div>

                        </td>


                        {{-- Author --}}
                        <td>

                            <div class="book-simple-info">
                                <strong title="{{ $book->author?->name ?? '' }}">
                                    {{ $book->author?->name ?? '—' }}
                                </strong>
                            </div>

                        </td>


                        {{-- Seller --}}
                        <td>

                            <div class="book-seller-cell">

                                <div class="book-seller-avatar">
                                    {{ $sellerInitial }}
                                </div>

                                <div class="book-seller-info">

                                    <strong title="{{ $sellerName }}">
                                        {{ $sellerName }}
                                    </strong>

                                    <small>Seller</small>

                                </div>

                            </div>

                        </td>


                        {{-- Price --}}
                        <td>

                            <span class="book-price">
                                ${{ number_format((float) ($book->price ?? 0), 2) }}
                            </span>

                        </td>


                        {{-- Condition --}}
                        <td>

                            <span class="book-condition-pill {{ $conditionClass }}">

                                <i class="bi bi-circle-fill"></i>

                                {{ $book->condition
                                    ? str_replace('_', ' ', ucfirst($book->condition))
                                    : 'Unknown'
                                }}

                            </span>

                        </td>


                        {{-- Status --}}
                        <td>

                            <span class="book-status-pill {{ $statusClass }}">

                                <i class="bi bi-circle-fill"></i>

                                {{ $book->status ? ucfirst($book->status) : 'Unknown' }}

                            </span>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PUBLISHING DETAILS
        ====================================================== --}}
        <div class="books-panel-header">

            <div class="books-heading-content">

                <span class="eyebrow">
                    Publishing
                </span>

                <h5>
                    Publishing details
                </h5>

            </div>

        </div>


        <div class="books-table-wrap">

            <table class="books-table">

                <thead>

                    <tr>
                        <th>Book ID</th>
                        <th>Publisher</th>
                        <th>Language</th>
                        <th>ISBN</th>
                        <th>Added</th>
                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>
                            <span class="book-id">#{{ $book->id }}</span>
                        </td>

                        <td>
                            <div class="book-simple-info">
                                <strong>{{ $book->publisher?->name ?? '—' }}</strong>
                            </div>
                        </td>

                        <td>
                            <div class="book-simple-info">
                                <strong>{{ $book->language ?: '—' }}</strong>
                            </div>
                        </td>

                        <td>
                            <div class="book-simple-info">
                                <strong>{{ $book->isbn ?: '—' }}</strong>
                            </div>
                        </td>

                        <td>
                            <div class="book-simple-info">
                                <strong>{{ $book->created_at?->format('d M Y') ?? '—' }}</strong>
                            </div>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            DESCRIPTION
        ====================================================== --}}
        <div class="books-panel-header">

            <div class="books-heading-content">

                <span class="eyebrow">
                    Description
                </span>

                <h5>
                    About this book
                </h5>

                <p>
                    {{ $book->description ?: 'No description has been added for this book.' }}
                </p>

            </div>

        </div>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}
        <div class="books-pagination">

            <div class="books-pagination-info">
                Book <strong>#{{ $book->id }}</strong>
            </div>

            <nav class="books-pagination-pages" aria-label="Book actions">

                <a
                    href="{{ route('admin.books.index') }}"
                    class="books-pager-btn"
                    title="Back to books"
                    aria-label="Back to books"
                >
                    <i class="bi bi-arrow-left"></i>
                </a>

                <a
                    href="{{ route('admin.books.edit', $book->id) }}"
                    class="books-pager-btn active"
                    title="Edit book"
                    aria-label="Edit book"
                >
                    <i class="bi bi-pencil"></i>
                </a>

            </nav>

        </div>

    </section>

</div>

@endsection