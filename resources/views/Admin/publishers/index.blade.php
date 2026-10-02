@extends('layout.admin.master')

@section('title', 'Publishers')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/publishers.css') }}">
@endpush

@section('content')

<div class="dashboard-section publishers-page">

    {{-- Hero --}}
    <section class="publishers-hero">
        <div class="publishers-hero-content">

            <div class="publishers-hero-text">
                <span class="publishers-hero-badge">
                    <i class="bi bi-buildings"></i>
                    Publishing Directory
                </span>

                <h1>Publishers, beautifully organised.</h1>

                <p>
                    Manage publishing houses, websites, countries and
                    publisher information from one clean workspace.
                </p>
            </div>

            <div class="publishers-hero-mark">
                <i class="bi bi-building"></i>
            </div>

        </div>
    </section>


    {{-- Stats --}}
    @php
        $publisherCollection = $publishers->getCollection();

        $activePublishers = $publisherCollection
            ->where('status', true)
            ->count();

        $inactivePublishers = $publisherCollection
            ->where('status', false)
            ->count();

        $websitePublishers = $publisherCollection
            ->filter(fn ($publisher) => !empty($publisher->website))
            ->count();
    @endphp

    <section class="publishers-stats">

        <div class="publisher-stat-card">
            <div class="publisher-stat-icon stat-blue">
                <i class="bi bi-buildings"></i>
            </div>

            <div class="publisher-stat-content">
                <div class="publisher-stat-label">Total Publishers</div>
                <div class="publisher-stat-value">
                    {{ $publishers->total() }}
                </div>
            </div>
        </div>

        <div class="publisher-stat-card">
            <div class="publisher-stat-icon stat-green">
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="publisher-stat-content">
                <div class="publisher-stat-label">Active</div>
                <div class="publisher-stat-value">
                    {{ $activePublishers }}
                </div>
            </div>
        </div>

        <div class="publisher-stat-card">
            <div class="publisher-stat-icon stat-orange">
                <i class="bi bi-pause-circle"></i>
            </div>

            <div class="publisher-stat-content">
                <div class="publisher-stat-label">Inactive</div>
                <div class="publisher-stat-value">
                    {{ $inactivePublishers }}
                </div>
            </div>
        </div>

        <div class="publisher-stat-card">
            <div class="publisher-stat-icon stat-purple">
                <i class="bi bi-globe2"></i>
            </div>

            <div class="publisher-stat-content">
                <div class="publisher-stat-label">With Website</div>
                <div class="publisher-stat-value">
                    {{ $websitePublishers }}
                </div>
            </div>
        </div>

    </section>


    {{-- Main Panel --}}
    <section class="dashboard-panel publishers-panel">

        {{-- Header --}}
        <div class="publishers-panel-header">

            <div class="publishers-heading-content">
                <h2 class="publishers-panel-title">
                    Publisher directory
                </h2>

                <p class="publishers-panel-description">
                    Browse and manage all publishers registered on SecondBook.
                </p>
            </div>

            <div class="publishers-header-action">
                <a
                    href="{{ route('admin.publishers.create') }}"
                    class="publishers-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    Add Publisher
                </a>
            </div>

        </div>


        {{-- Filters --}}
        <form
            method="GET"
            action="{{ route('admin.publishers.index') }}"
            class="publisher-filters"
        >
            <div class="publisher-filter-grid">

                <div class="publisher-filter-group">

                    <label class="publisher-filter-label">
                        Search publishers
                    </label>

                    <div class="publisher-search-field">
                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="publisher-input"
                            placeholder="Search by publisher name..."
                        >
                    </div>

                </div>

                <div class="publisher-filter-actions">

                    <button
                        type="submit"
                        class="publisher-filter-btn"
                    >
                        <i class="bi bi-funnel"></i>
                        Search
                    </button>

                    @if(request()->filled('search'))
                        <a
                            href="{{ route('admin.publishers.index') }}"
                            class="publisher-clear-filter"
                        >
                            <i class="bi bi-x-lg"></i>
                            Reset
                        </a>
                    @endif

                </div>

            </div>
        </form>


        {{-- Messages --}}
        @if(session('success'))
            <div class="px-4 pt-4">
                <div class="alert alert-success mb-0">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 pt-4">
                <div class="alert alert-danger mb-0">
                    {{ session('error') }}
                </div>
            </div>
        @endif


        {{-- Table --}}
        <div class="publishers-table-wrap">

            <table class="publishers-table">

                <thead>
                    <tr>
                        <th class="publishers-col-id">#</th>
                        <th class="publishers-col-publisher">Publisher</th>
                        <th class="publishers-col-country">Country</th>
                        <th class="publishers-col-website">Website</th>
                        <th class="publishers-col-description">Description</th>
                        <th class="publishers-col-status">Status</th>
                        <th class="publishers-col-date">Created</th>
                        <th class="publishers-col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($publishers as $publisher)

                        @php
                            $logoUrl = null;

                            if (!empty($publisher->logo)) {
                                $logoUrl = filter_var(
                                    $publisher->logo,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $publisher->logo
                                    : asset(
                                        'storage/' .
                                        ltrim($publisher->logo, '/')
                                    );
                            }
                        @endphp

                        <tr id="publisher-row-{{ $publisher->id }}">

                            {{-- ID --}}
                            <td>
                                <span class="publisher-id">
                                    #{{ $publisher->id }}
                                </span>
                            </td>


                            {{-- Publisher --}}
                            <td>
                                <div class="publisher-item-cell">

                                    <div class="publisher-logo-wrapper">

                                        @if($logoUrl)

                                            <img
                                                src="{{ $logoUrl }}"
                                                alt="{{ $publisher->name }}"
                                                class="publisher-logo-thumb"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            >

                                            <div
                                                class="publisher-logo-placeholder"
                                                style="display: none;"
                                            >
                                                <i class="bi bi-building"></i>
                                            </div>

                                        @else

                                            <div class="publisher-logo-placeholder">
                                                <i class="bi bi-building"></i>
                                            </div>

                                        @endif

                                    </div>

                                    <div class="publisher-item-info">

                                        <span class="publisher-item-name">
                                            {{ $publisher->name }}
                                        </span>

                                        @if(!empty($publisher->country))
                                            <span class="publisher-item-meta">
                                                {{ $publisher->country }}
                                            </span>
                                        @endif

                                    </div>

                                </div>
                            </td>


                            {{-- Country --}}
                            <td>
                                <span class="publisher-country">
                                    {{ $publisher->country ?: '—' }}
                                </span>
                            </td>


                            {{-- Website --}}
                            <td>

                                @if(!empty($publisher->website))

                                    <a
                                        href="{{ $publisher->website }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="publisher-website"
                                        title="{{ $publisher->website }}"
                                    >
                                        <i class="bi bi-box-arrow-up-right me-1"></i>
                                        {{ $publisher->website }}
                                    </a>

                                @else

                                    <span class="text-muted">—</span>

                                @endif

                            </td>


                            {{-- Description --}}
                            <td>

                                @if(!empty($publisher->description))

                                    <span
                                        class="publisher-description"
                                        title="{{ $publisher->description }}"
                                    >
                                        {{ $publisher->description }}
                                    </span>

                                @else

                                    <span class="text-muted">—</span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($publisher->status ?? false)

                                    <span class="publisher-status-pill publisher-status-active">
                                        Active
                                    </span>

                                @else

                                    <span class="publisher-status-pill publisher-status-inactive">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Created --}}
                            <td>
                                <span class="publisher-item-meta">
                                    {{ $publisher->created_at?->format('d M Y H:i') ?? '—' }}
                                </span>
                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="publisher-actions">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.publishers.show', $publisher->id) }}"
                                        class="publisher-action-btn publisher-view-btn"
                                        title="View Publisher"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Status --}}
                                    <form
                                        action="{{ route('admin.publishers.status', $publisher->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="publisher-status-btn {{ ($publisher->status ?? false) ? 'deactivate' : 'activate' }}"
                                            title="{{ ($publisher->status ?? false) ? 'Deactivate' : 'Activate' }}"
                                        >
                                            <i class="bi {{ ($publisher->status ?? false) ? 'bi-pause-circle' : 'bi-check-circle' }}"></i>
                                        </button>

                                    </form>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.publishers.edit', $publisher->id) }}"
                                        class="publisher-action-btn publisher-edit-btn"
                                        title="Edit Publisher"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.publishers.destroy', $publisher->id) }}"
                                        method="POST"
                                        class="publisher-delete-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="publisher-action-btn publisher-delete-btn"
                                            title="Delete Publisher"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="publishers-empty-state">

                                    <div class="publishers-empty-icon">
                                        <i class="bi bi-building"></i>
                                    </div>

                                    <h5>No publishers found</h5>

                                    <p>
                                        There are no publishers matching your search.
                                    </p>

                                    <a
                                        href="{{ route('admin.publishers.create') }}"
                                        class="publishers-add-btn mt-4"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Add Publisher
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($publishers->hasPages())

            <div class="publishers-pagination">

                <div class="publishers-pagination-info">
                    Showing
                    <strong>
                        {{ $publishers->firstItem() }}
                    </strong>
                    —
                    <strong>
                        {{ $publishers->lastItem() }}
                    </strong>
                    of
                    <strong>
                        {{ $publishers->total() }}
                    </strong>
                    publishers
                </div>

                <div class="publishers-pagination-pages">

                    @if($publishers->onFirstPage())

                        <span class="publisher-pager-btn disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $publishers->previousPageUrl() }}"
                            class="publisher-pager-btn"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </a>

                    @endif


                    @php
                        $currentPage = $publishers->currentPage();
                        $lastPage = $publishers->lastPage();

                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp


                    @if($startPage > 1)

                        <a
                            href="{{ $publishers->url(1) }}"
                            class="publisher-pager-btn"
                        >
                            1
                        </a>

                        @if($startPage > 2)
                            <span class="publisher-pager-dots">...</span>
                        @endif

                    @endif


                    @for($page = $startPage; $page <= $endPage; $page++)

                        @if($page == $currentPage)

                            <span class="publisher-pager-btn active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $publishers->url($page) }}"
                                class="publisher-pager-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    @if($endPage < $lastPage)

                        @if($endPage < $lastPage - 1)
                            <span class="publisher-pager-dots">...</span>
                        @endif

                        <a
                            href="{{ $publishers->url($lastPage) }}"
                            class="publisher-pager-btn"
                        >
                            {{ $lastPage }}
                        </a>

                    @endif


                    @if($publishers->hasMorePages())

                        <a
                            href="{{ $publishers->nextPageUrl() }}"
                            class="publisher-pager-btn"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @else

                        <span class="publisher-pager-btn disabled">
                            <i class="bi bi-chevron-right"></i>
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </section>

</div>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.publisher-delete-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            if (typeof Swal === 'undefined') {
                form.submit();
                return;
            }

            Swal.fire({
                title: 'Delete publisher?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-dark'
                }
            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

    });

});
</script>
@endpush