@extends('layout.admin.master')

@section('title', 'Book Request')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/book-requests.css') }}">
@endpush

@section('content')

@php
    $statusClass = match($bookRequest->status) {
        'approved' => 'book-request-status-approved',
        'rejected' => 'book-request-status-rejected',
        'changes_requested' => 'book-request-status-changes',
        default => 'book-request-status-pending',
    };
@endphp

<div class="dashboard-section book-request-page">

    {{-- =====================================================
        HERO
        ===================================================== --}}
    <section class="book-request-hero">

        <div class="book-request-hero-content">

            <div class="book-request-hero-text">

                <span class="book-request-hero-badge">
                    <i class="bi bi-eye"></i>
                    Seller Submission
                </span>

                <h1>Review Book Request</h1>

                <p>
                    Review the seller's book submission and respond
                    to the request.
                </p>

            </div>

            <div class="book-request-hero-mark">
                <i class="bi bi-eye"></i>
            </div>

        </div>

    </section>


    {{-- =====================================================
        BOOK INFORMATION
        ===================================================== --}}
    <section class="dashboard-panel book-request-panel">

        <div class="book-request-panel-header">

            <div class="book-request-heading-content">

                <h2 class="book-request-panel-title">
                    Book Information
                </h2>

                <p class="book-request-panel-description">
                    Information submitted by the seller.
                </p>

            </div>

            <div class="book-request-header-action book-request-header-pill">

                <span class="book-request-status-pill {{ $statusClass }}">
                    {{ ucwords(str_replace('_', ' ', $bookRequest->status)) }}
                </span>

                <a
                    href="{{ route('admin.book.requests.index') }}"
                    class="book-request-clear-filter"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Requests
                </a>

            </div>

        </div>


        <div class="book-request-detail-layout">

            {{-- COVER --}}
            <div class="book-request-detail-side">

                <div class="book-request-detail-cover">

                    @if($bookRequest->cover)

                        <img
                            src="{{ asset('storage/' . $bookRequest->cover) }}"
                            alt="{{ $bookRequest->title }}"
                        >

                    @else

                        <div class="book-request-detail-placeholder">
                            <i class="bi bi-book"></i>
                            <span>No Cover</span>
                        </div>

                    @endif

                </div>

            </div>


            {{-- DETAILS --}}
            <div class="book-request-detail-main">

                <div class="book-request-form-grid">

                    <div class="book-request-form-group book-request-field-half">
                        <label class="book-request-filter-label">Title</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->title }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-half">
                        <label class="book-request-filter-label">ISBN</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->isbn ?: 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-half">
                        <label class="book-request-filter-label">Category</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->category?->name ?? 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-half">
                        <label class="book-request-filter-label">Author</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->author?->name ?? 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-half">
                        <label class="book-request-filter-label">Publisher</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->publisher?->name ?? 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-half">
                        <label class="book-request-filter-label">Seller</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->seller?->name ?? 'Unknown seller' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-third">
                        <label class="book-request-filter-label">Publication Year</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->publication_year ?? 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-third">
                        <label class="book-request-filter-label">Pages</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->pages ?? 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-third">
                        <label class="book-request-filter-label">Language</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->language ?? 'Not provided' }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-third">
                        <label class="book-request-filter-label">Price</label>
                        <div class="book-request-readonly">
                            ₼{{ number_format($bookRequest->price, 2) }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-third">
                        <label class="book-request-filter-label">Stock</label>
                        <div class="book-request-readonly">
                            {{ $bookRequest->stock }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-third">
                        <label class="book-request-filter-label">Condition</label>
                        <div class="book-request-readonly">
                            {{ ucwords(str_replace('_', ' ', $bookRequest->condition)) }}
                        </div>
                    </div>


                    <div class="book-request-form-group book-request-field-full">
                        <label class="book-request-filter-label">Description</label>
                        <div class="book-request-readonly is-multiline">
                            {{ $bookRequest->description ?: 'No description provided.' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        ADMIN RESPONSE
        ===================================================== --}}
    <section class="dashboard-panel book-request-panel">

        <div class="book-request-panel-header">

            <div class="book-request-heading-content">

                <h2 class="book-request-panel-title">
                    Admin Response
                </h2>

                <p class="book-request-panel-description">
                    Send a response to the seller about this book request.
                </p>

            </div>

        </div>


        <form
            action="{{ route('admin.book.requests.update', ['book' => $bookRequest->id]) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="book-request-form-section">

                    <div class="book-request-alert">

                        <i class="bi bi-exclamation-circle-fill"></i>

                        <div>
                            <strong>Please fix the following errors:</strong>

                            <ul>

                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>
                        </div>

                    </div>

                </div>

            @endif


            <div class="book-request-form-section">

                <div class="book-request-form-grid">

                    {{-- STATUS --}}
                    <div class="book-request-form-group book-request-field-half">

                        <label
                            for="status"
                            class="book-request-filter-label"
                        >
                            Request Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="book-request-select @error('status') is-invalid @enderror"
                        >

                            <option
                                value="pending"
                                @selected(old('status', $bookRequest->status) === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="approved"
                                @selected(old('status', $bookRequest->status) === 'approved')
                            >
                                Approved
                            </option>

                            <option
                                value="changes_requested"
                                @selected(old('status', $bookRequest->status) === 'changes_requested')
                            >
                                Changes Requested
                            </option>

                            <option
                                value="rejected"
                                @selected(old('status', $bookRequest->status) === 'rejected')
                            >
                                Rejected
                            </option>

                        </select>

                        @error('status')
                            <div class="book-request-field-error">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- MESSAGE --}}
                    <div class="book-request-form-group book-request-field-full">

                        <label
                            for="message"
                            class="book-request-filter-label"
                        >
                            Message to Seller
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            class="book-request-textarea @error('message') is-invalid @enderror"
                            placeholder="Write a message for the seller..."
                        >{{ old('message') }}</textarea>

                        <div class="book-request-form-hint">
                            The seller will receive this message as a notification.
                        </div>

                        @error('message')
                            <div class="book-request-field-error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="book-request-form-section">

                <div class="book-request-form-actions">

                    <button
                        type="submit"
                        class="book-request-filter-btn"
                    >
                        <i class="bi bi-send"></i>
                        Send Response
                    </button>

                    <a
                        href="{{ route('admin.book.requests.index') }}"
                        class="book-request-clear-filter"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </form>

    </section>

</div>

@endsection