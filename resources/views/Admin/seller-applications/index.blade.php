@extends('layout.admin.master')

@section('title', 'Seller Apply')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/seller-applications.css') }}">
@endpush

@section('content')

<div class="dashboard-section seller-applications-page">

    {{-- PAGE HEADER --}}
    <div class="dashboard-panel mb-4">
        <div class="panel-header mb-0">
            <div>
                <h5 class="mb-1">Seller Applications</h5>
                <p class="text-muted mb-0 small">
                    Review and manage users who want to become sellers.
                </p>
            </div>
        </div>
    </div>

    {{-- STATISTICS --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="seller-application-stat-card">
                <div class="seller-application-stat-icon total">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="seller-application-stat-content">
                    <span>Total Applications</span>
                    <h3>{{ $totalCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="seller-application-stat-card">
                <div class="seller-application-stat-icon pending">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="seller-application-stat-content">
                    <span>Pending</span>
                    <h3>{{ $pendingCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="seller-application-stat-card">
                <div class="seller-application-stat-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="seller-application-stat-content">
                    <span>Approved</span>
                    <h3>{{ $approvedCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="seller-application-stat-card">
                <div class="seller-application-stat-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="seller-application-stat-content">
                    <span>Rejected</span>
                    <h3>{{ $rejectedCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="dashboard-panel mb-4">
        <form
            id="seller-application-filter-form"
            method="GET"
            action="{{ route('admin.seller-applications.index') }}"
        >
            <div class="seller-application-filters">

                <div class="seller-application-search">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search store, name or email..."
                    >
                </div>

                <select
                    name="status"
                    class="seller-application-status-filter"
                >
                    <option value="">All Statuses</option>
                    <option
                        value="pending"
                        @selected(request('status') === 'pending')
                    >
                        Pending
                    </option>
                    <option
                        value="approved"
                        @selected(request('status') === 'approved')
                    >
                        Approved
                    </option>
                    <option
                        value="rejected"
                        @selected(request('status') === 'rejected')
                    >
                        Rejected
                    </option>
                </select>

                <button
                    type="submit"
                    class="seller-application-filter-btn"
                >
                    <i class="bi bi-search me-1"></i>
                    Search
                </button>

                <a
                    id="seller-application-reset"
                    href="{{ route('admin.seller-applications.index') }}"
                    class="seller-application-reset-btn"
                    @if(!request('search') && !request('status')) hidden @endif
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>

            </div>
        </form>
    </div>

    {{-- APPLICATIONS --}}
    <div class="dashboard-panel">

        <div class="panel-header">
            <div>
                <h5 class="mb-1">Applications</h5>
                <p class="text-muted mb-0 small">
                    Review submitted seller applications.
                </p>
            </div>
        </div>

        <div id="seller-application-results">

            @if($applications->count())

                <div class="table-responsive seller-application-table-wrapper">
                    <table class="table align-middle seller-application-table">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Store</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    {{-- APPLICANT --}}
                                    <td>
                                        <div class="seller-applicant">
                                            <div class="seller-applicant-avatar">
                                                @if($application->user->profile_photo)
                                                    <img
                                                        src="{{ asset('storage/' . $application->user->profile_photo) }}"
                                                        alt="{{ $application->user->full_name }}"
                                                    >
                                                @else
                                                    <span>
                                                        {{ strtoupper(substr($application->user->first_name ?? $application->user->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="seller-applicant-info">
                                                <strong>
                                                    {{ $application->user->full_name ?: $application->user->name }}
                                                </strong>
                                                <span>
                                                    {{ $application->user->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- STORE --}}
                                    <td>
                                        <div class="seller-store-info">
                                            <i class="bi bi-shop"></i>
                                            <span>{{ $application->store_name }}</span>
                                        </div>
                                    </td>

                                    {{-- CONTACT --}}
                                    <td>
                                        <div class="seller-contact-info">
                                            @if($application->phone)
                                                <span>
                                                    <i class="bi bi-telephone me-1"></i>
                                                    {{ $application->phone }}
                                                </span>
                                            @else
                                                <span class="text-muted">No phone</span>
                                            @endif

                                            @if($application->address)
                                                <span>
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ \Illuminate\Support\Str::limit($application->address, 30) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($application->status === 'pending')
                                            <span class="seller-application-badge pending">
                                                <i class="bi bi-clock me-1"></i>
                                                Pending
                                            </span>
                                        @elseif($application->status === 'approved')
                                            <span class="seller-application-badge approved">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Approved
                                            </span>
                                        @else
                                            <span class="seller-application-badge rejected">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>

                                    {{-- DATE --}}
                                    <td>
                                        <div class="seller-application-date">
                                            <strong>
                                                {{ $application->created_at->format('M d, Y') }}
                                            </strong>
                                            <span>
                                                {{ $application->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="text-end">
                                        <a
                                            href="{{ route('admin.seller-applications.show', $application) }}"
                                            class="seller-application-view-btn"
                                        >
                                            <i class="bi bi-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($applications->hasPages())
                    <div class="seller-application-pagination">
                        {{ $applications->links() }}
                    </div>
                @endif

            @else

                <div class="seller-application-empty">
                    <div class="seller-application-empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>

                    <h5>No Applications Found</h5>

                    <p>
                        There are no seller applications matching your current filters.
                    </p>

                    <a
                        href="{{ route('admin.seller-applications.index') }}"
                        class="seller-application-reset-btn"
                        id="seller-application-empty-reset"
                        @if(!request('search') && !request('status')) hidden @endif
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Clear Filters
                    </a>
                </div>

            @endif

        </div>
    </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('seller-application-filter-form');
    const results = document.getElementById('seller-application-results');
    const resetButton = document.getElementById('seller-application-reset');

    if (!filterForm || !results || !resetButton) {
        return;
    }

    const searchInput = filterForm.querySelector('[name="search"]');
    const statusSelect = filterForm.querySelector('[name="status"]');

    function updateResetButton() {
        const hasFilters = Boolean(
            searchInput.value.trim() || statusSelect.value
        );

        resetButton.hidden = !hasFilters;

        const emptyReset = document.getElementById(
            'seller-application-empty-reset'
        );

        if (emptyReset) {
            emptyReset.hidden = !hasFilters;
        }
    }

    async function loadApplications(url, updateHistory = true) {
        results.style.opacity = '0.55';

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Applications could not be loaded.');
            }

            const html = await response.text();
            const parser = new DOMParser();
            const documentPage = parser.parseFromString(html, 'text/html');

            const newResults = documentPage.querySelector(
                '#seller-application-results'
            );

            const newForm = documentPage.querySelector(
                '#seller-application-filter-form'
            );

            if (!newResults || !newForm) {
                window.location.href = url;
                return;
            }

            results.innerHTML = newResults.innerHTML;

            searchInput.value =
                newForm.querySelector('[name="search"]')?.value || '';

            statusSelect.value =
                newForm.querySelector('[name="status"]')?.value || '';

            updateResetButton();

            if (updateHistory) {
                window.history.pushState({}, '', url);
            }
        } catch (error) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            } else {
                alert(error.message);
            }
        } finally {
            results.style.opacity = '1';
        }
    }

    function buildFilterUrl() {
        const url = new URL(filterForm.action, window.location.origin);
        const params = new URLSearchParams(new FormData(filterForm));

        params.delete('page');

        for (const [key, value] of [...params.entries()]) {
            if (!String(value).trim()) {
                params.delete(key);
            }
        }

        url.search = params.toString();

        return url.toString();
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        loadApplications(buildFilterUrl());
    });

    searchInput.addEventListener('input', updateResetButton);
    statusSelect.addEventListener('change', updateResetButton);

    resetButton.addEventListener('click', function (event) {
        event.preventDefault();

        searchInput.value = '';
        statusSelect.value = '';

        updateResetButton();
        loadApplications(filterForm.action);
    });

    results.addEventListener('click', function (event) {
        const link = event.target.closest(
            '.seller-application-pagination a'
        );

        if (!link) {
            return;
        }

        event.preventDefault();
        loadApplications(link.href);
    });

    window.addEventListener('popstate', function () {
        loadApplications(window.location.href, false);
    });

    updateResetButton();
});
</script>
@endpush