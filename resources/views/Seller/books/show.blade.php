@extends('Layout.Seller.master')

@section('title', 'Book Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/books.css') }}">
@endpush

@section('content')

    <div class="seller-books-page">

        {{-- =====================================================
             HEADER
             ===================================================== --}}
        <div class="seller-page-heading">

            <div>
                <h1>Book Details</h1>
                <p>View the information and current status of this book.</p>
            </div>

            <a
                href="{{ route('seller.books.index') }}"
                class="seller-outline-button"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back to My Books</span>
            </a>

        </div>

        <div class="row g-4">

            {{-- =================================================
                 COVER
                 ================================================= --}}
            <div class="col-xl-4">

                <div class="seller-books-form-card seller-book-details-card">

                    <div class="seller-book-details-cover">

                        @if($book->cover)
                            <img
                                src="{{ asset('storage/' . $book->cover) }}"
                                alt="{{ $book->title }}"
                            >
                        @else
                            <div class="seller-book-details-no-cover">
                                <i class="bi bi-book"></i>
                                <span>No Cover</span>
                            </div>
                        @endif

                    </div>

                    <div class="seller-book-details-status">

                        <span class="seller-book-status {{ $book->status }}">
                            <i></i>
                            {{ ucfirst($book->status) }}
                        </span>

                        <span class="seller-condition">
                            {{ ucwords(str_replace('_', ' ', $book->condition)) }}
                        </span>

                    </div>

                </div>

            </div>

            {{-- =================================================
                 INFORMATION
                 ================================================= --}}
            <div class="col-xl-8">

                <div class="seller-books-form-card">

                    <div class="seller-form-header">

                        <div>
                            <h5>Book Information</h5>
                            <p>Detailed information about your book.</p>
                        </div>

                        <div class="seller-form-header-icon">
                            <i class="bi bi-book"></i>
                        </div>

                    </div>

                    <div class="seller-book-details-body">

                        {{-- Title --}}
                        <div class="seller-book-details-title">

                            <span>Title</span>

                            <h2>
                                {{ $book->title }}
                            </h2>

                        </div>

                        {{-- Highlights --}}
                        <div class="seller-book-highlights">

                            <div class="seller-book-highlight is-price">
                                <span>Price</span>
                                <strong>
                                    ${{ number_format($book->price, 2) }}
                                </strong>
                            </div>

                            <div class="seller-book-highlight">
                                <span>In Stock</span>
                                <strong>
                                    {{ $book->stock }}
                                </strong>
                            </div>

                            <div class="seller-book-highlight">
                                <span>Pages</span>
                                <strong>
                                    {{ $book->pages ?: '—' }}
                                </strong>
                            </div>

                        </div>

                        {{-- Details --}}
                        <div class="seller-book-details-grid">

                            <div class="seller-book-detail-item">

                                <div class="seller-book-detail-icon">
                                    <i class="bi bi-upc-scan"></i>
                                </div>

                                <div>
                                    <span>ISBN</span>
                                    <strong>
                                        {{ $book->isbn ?: '—' }}
                                    </strong>
                                </div>

                            </div>

                            <div class="seller-book-detail-item">

                                <div class="seller-book-detail-icon">
                                    <i class="bi bi-tags"></i>
                                </div>

                                <div>
                                    <span>Category</span>
                                    <strong>
                                        {{ $book->category?->name ?? '—' }}
                                    </strong>
                                </div>

                            </div>

                            <div class="seller-book-detail-item">

                                <div class="seller-book-detail-icon">
                                    <i class="bi bi-person"></i>
                                </div>

                                <div>
                                    <span>Author</span>
                                    <strong>
                                        {{ $book->author?->name ?? '—' }}
                                    </strong>
                                </div>

                            </div>

                            <div class="seller-book-detail-item">

                                <div class="seller-book-detail-icon">
                                    <i class="bi bi-building"></i>
                                </div>

                                <div>
                                    <span>Publisher</span>
                                    <strong>
                                        {{ $book->publisher?->name ?? '—' }}
                                    </strong>
                                </div>

                            </div>

                            <div class="seller-book-detail-item">

                                <div class="seller-book-detail-icon">
                                    <i class="bi bi-translate"></i>
                                </div>

                                <div>
                                    <span>Language</span>
                                    <strong>
                                        {{ $book->language ?: '—' }}
                                    </strong>
                                </div>

                            </div>

                            <div class="seller-book-detail-item">

                                <div class="seller-book-detail-icon">
                                    <i class="bi bi-calendar3"></i>
                                </div>

                                <div>
                                    <span>Publication Year</span>
                                    <strong>
                                        {{ $book->publication_year ?: '—' }}
                                    </strong>
                                </div>

                            </div>

                        </div>

                        {{-- Description --}}
                        <div class="seller-book-details-description">

                            <span>Description</span>

                            @if($book->description)
                                <p>
                                    {{ $book->description }}
                                </p>
                            @else
                                <p class="seller-book-details-muted">
                                    No description provided.
                                </p>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="seller-books-form-actions">

            <a
                href="{{ route('seller.books.index') }}"
                class="seller-cancel-button"
            >
                Back
            </a>

            <a
                href="{{ route('seller.books.edit', ['book' => $book->id]) }}"
                class="seller-save-button"
            >
                <i class="bi bi-pencil"></i>
                <span>Edit Book</span>
            </a>

        </div>

    </div>

@endsection

