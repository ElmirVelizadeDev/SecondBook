@extends('layout.admin.master')

@section('title', 'Author Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/authors.css') }}">
@endpush

@section('content')

@php
    $authorName = $author->name ?: 'Unnamed Author';

    $photoUrl = null;

    if (!empty($author->photo)) {
        $photoUrl = str_starts_with($author->photo, 'http')
            ? $author->photo
            : asset('storage/' . ltrim($author->photo, '/'));
    }
@endphp

<div class="dashboard-section authors-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="authors-hero authors-hero-compact">

        <div class="authors-hero-content">

            <span class="authors-hero-badge">
                <i class="bi bi-person-vcard"></i>
                Author details
            </span>

            <h1>{{ $authorName }}</h1>

            <p>
                Review author information, biography, status and
                account activity.
            </p>

        </div>

        <div class="authors-hero-mark" aria-hidden="true">
            <i class="bi bi-person"></i>
        </div>

    </section>


    {{-- =========================================================
        MAIN PANEL
    ========================================================== --}}
    <section class="authors-panel author-show-panel">

        {{-- =====================================================
            PANEL HEADER
        ====================================================== --}}
        <div class="authors-panel-header">

            <div class="authors-heading-content">

                <span class="eyebrow">
                    Author information
                </span>

                <h5>
                    {{ $authorName }}
                </h5>

                <p>
                    Complete information about this author.
                </p>

            </div>

            <div class="authors-header-action">

                <a
                    href="{{ route('admin.authors.index') }}"
                    class="author-cancel-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to authors</span>
                </a>

                <a
                    href="{{ route('admin.authors.edit', $author->id) }}"
                    class="authors-add-btn"
                >
                    <i class="bi bi-pencil"></i>
                    <span>Edit Author</span>
                </a>

            </div>

        </div>


        {{-- =====================================================
            AUTHOR OVERVIEW
        ====================================================== --}}
        <div class="author-show-body">

            {{-- Photo --}}
            <div class="author-show-photo-section">

                <div class="author-show-section-label">
                    Author photo
                </div>

                @if($photoUrl)

                    <div class="author-show-photo-wrapper">

                        <img
                            src="{{ $photoUrl }}"
                            alt="{{ $authorName }}"
                            class="author-show-photo"
                            loading="lazy"
                        >

                    </div>

                @else

                    <div class="author-show-no-photo">

                        <i class="bi bi-person"></i>

                        <span>
                            No photo
                        </span>

                    </div>

                @endif

            </div>


            {{-- Information --}}
            <div class="author-show-information">

                <div class="author-show-info-card">

                    <span class="author-show-info-label">
                        Author ID
                    </span>

                    <strong class="author-show-info-value author-show-id">
                        #{{ $author->id }}
                    </strong>

                </div>


                <div class="author-show-info-card">

                    <span class="author-show-info-label">
                        Name
                    </span>

                    <strong class="author-show-info-value">
                        {{ $authorName }}
                    </strong>

                </div>


                <div class="author-show-info-card">

                    <span class="author-show-info-label">
                        Status
                    </span>

                    <div class="author-show-info-value">

                        @if($author->status)

                            <span class="author-show-status active">
                                <span class="status-dot"></span>
                                Active
                            </span>

                        @else

                            <span class="author-show-status inactive">
                                <span class="status-dot"></span>
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>


                <div class="author-show-info-card">

                    <span class="author-show-info-label">
                        Created
                    </span>

                    <strong class="author-show-info-value">
                        {{ $author->created_at?->format('d M Y H:i') ?? '—' }}
                    </strong>

                </div>


                <div class="author-show-info-card">

                    <span class="author-show-info-label">
                        Last updated
                    </span>

                    <strong class="author-show-info-value">
                        {{ $author->updated_at?->format('d M Y H:i') ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =====================================================
            BIOGRAPHY
        ====================================================== --}}
        <div class="author-show-description">

            <div class="author-show-description-header">

                <div>

                    <span class="eyebrow">
                        Biography
                    </span>

                    <h5>
                        About the author
                    </h5>

                </div>

                <i class="bi bi-journal-text"></i>

            </div>


            <div class="author-show-description-content">

                {{ $author->bio ?: 'No biography has been added for this author.' }}

            </div>

        </div>


        {{-- =====================================================
            FOOTER ACTIONS
        ====================================================== --}}
        <div class="author-show-footer">

            <div class="author-show-footer-info">
                Author <strong>#{{ $author->id }}</strong>
            </div>

            <div class="author-show-footer-actions">

                <a
                    href="{{ route('admin.authors.index') }}"
                    class="author-show-footer-btn"
                    title="Back to authors"
                    aria-label="Back to authors"
                >
                    <i class="bi bi-arrow-left"></i>
                </a>

                <a
                    href="{{ route('admin.authors.edit', $author->id) }}"
                    class="author-show-footer-btn active"
                    title="Edit author"
                    aria-label="Edit author"
                >
                    <i class="bi bi-pencil"></i>
                </a>

            </div>

        </div>

    </section>

</div>

@endsection
