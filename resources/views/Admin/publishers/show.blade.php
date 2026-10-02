@extends('layout.admin.master')

@section('title', 'Publisher Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/publishers.css') }}">
@endpush

@section('content')

<div class="dashboard-section publishers-page">

    {{-- Hero --}}
    <section class="publishers-hero publishers-hero-compact">

        <div class="publishers-hero-content">

            <div class="publishers-hero-text">

                <span class="publishers-hero-badge">
                    <i class="bi bi-building"></i>
                    Publisher Details
                </span>

                <h1>{{ $publisher->name }}</h1>

                <p>
                    Review publisher information, status and publishing details.
                </p>

            </div>

            <div class="publishers-hero-mark">
                <i class="bi bi-building-check"></i>
            </div>

        </div>

    </section>


    {{-- Detail Panel --}}
    <section class="dashboard-panel publishers-panel">

        <div class="publishers-panel-header">

            <div class="publishers-heading-content">

                <h2 class="publishers-panel-title">
                    Publisher information
                </h2>

                <p class="publishers-panel-description">
                    Complete details for this publisher.
                </p>

            </div>

            <div class="publishers-header-action">

                <a
                    href="{{ route('admin.publishers.edit', $publisher->id) }}"
                    class="publisher-secondary-btn"
                >
                    <i class="bi bi-pencil"></i>
                    Edit Publisher
                </a>

            </div>

        </div>


        <div class="publisher-detail-layout">

            {{-- Sidebar --}}
            <aside class="publisher-detail-sidebar">

                <div>

                    <div class="publisher-detail-logo">

                        @if(!empty($publisher->logo))

                            @php
                                $logoUrl = filter_var(
                                    $publisher->logo,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $publisher->logo
                                    : asset(
                                        'storage/' .
                                        ltrim($publisher->logo, '/')
                                    );
                            @endphp

                            <img
                                src="{{ $logoUrl }}"
                                alt="{{ $publisher->name }}"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >

                            <div
                                class="publisher-detail-placeholder"
                                style="display: none;"
                            >
                                <i class="bi bi-building"></i>
                            </div>

                        @else

                            <div class="publisher-detail-placeholder">
                                <i class="bi bi-building"></i>
                            </div>

                        @endif

                    </div>

                    <h3 class="publisher-detail-name">
                        {{ $publisher->name }}
                    </h3>

                    <p class="publisher-detail-country">
                        {{ $publisher->country ?: '—' }}
                    </p>

                </div>

            </aside>


            {{-- Main Details --}}
            <div class="publisher-detail-main">

                <div class="publisher-detail-grid">

                    {{-- ID --}}
                    <div class="publisher-detail-item">

                        <span class="publisher-detail-label">
                            Publisher ID
                        </span>

                        <strong class="publisher-detail-value">
                            #{{ $publisher->id }}
                        </strong>

                    </div>


                    {{-- Status --}}
                    <div class="publisher-detail-item">

                        <span class="publisher-detail-label">
                            Status
                        </span>

                        <div>

                            @if($publisher->status ?? false)

                                <span class="publisher-status-pill publisher-status-active">
                                    Active
                                </span>

                            @else

                                <span class="publisher-status-pill publisher-status-inactive">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Country --}}
                    <div class="publisher-detail-item">

                        <span class="publisher-detail-label">
                            Country
                        </span>

                        <strong class="publisher-detail-value">
                            {{ $publisher->country ?: '—' }}
                        </strong>

                    </div>


                    {{-- Website --}}
                    <div class="publisher-detail-item">

                        <span class="publisher-detail-label">
                            Website
                        </span>

                        @if(!empty($publisher->website))

                            <a
                                href="{{ $publisher->website }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="publisher-detail-value"
                                style="color: var(--pub-primary);"
                            >
                                {{ $publisher->website }}
                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>

                        @else

                            <strong class="publisher-detail-value">
                                —
                            </strong>

                        @endif

                    </div>


                    {{-- Description --}}
                    <div class="publisher-detail-item publisher-detail-item-full">

                        <span class="publisher-detail-label">
                            Description
                        </span>

                        @if(!empty($publisher->description))

                            <div class="publisher-detail-description">
                                {{ $publisher->description }}
                            </div>

                        @else

                            <strong class="publisher-detail-value">
                                —
                            </strong>

                        @endif

                    </div>


                    {{-- Created --}}
                    <div class="publisher-detail-item">

                        <span class="publisher-detail-label">
                            Created At
                        </span>

                        <strong class="publisher-detail-value">
                            {{ $publisher->created_at?->format('d M Y H:i') ?? '—' }}
                        </strong>

                    </div>


                    {{-- Updated --}}
                    <div class="publisher-detail-item">

                        <span class="publisher-detail-label">
                            Updated At
                        </span>

                        <strong class="publisher-detail-value">
                            {{ $publisher->updated_at?->format('d M Y H:i') ?? '—' }}
                        </strong>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="publisher-detail-actions">

                    <a
                        href="{{ route('admin.publishers.edit', $publisher->id) }}"
                        class="publisher-form-submit"
                    >
                        <i class="bi bi-pencil"></i>
                        Edit Publisher
                    </a>

                    <a
                        href="{{ route('admin.publishers.index') }}"
                        class="publisher-secondary-btn"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Back to Publishers
                    </a>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection