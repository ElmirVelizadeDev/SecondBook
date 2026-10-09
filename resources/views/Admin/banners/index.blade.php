
@extends('layout.admin.master')

@section('title', 'Banners')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/banners.css') }}">
@endpush

@section('content')
<div class="dashboard-section banners-page">

    <div class="dashboard-panel mb-4">
        <div class="panel-header mb-0">
            <div>
                <h5 class="mb-1">Banners</h5>
                <p class="text-muted mb-0 small">Manage your website banners</p>
            </div>

            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Banner
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="dashboard-panel mb-4">
        <form action="{{ route('admin.banners.index') }}"
              method="GET"
              class="banner-filters"
              id="banner-filter-form">

            <div class="banner-search-group">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control"
                       placeholder="Search banner...">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                    <span>Search</span>
                </button>
            </div>

            <div class="banner-status-filter">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <a href="{{ route('admin.banners.index') }}"
               class="btn btn-light banner-reset-btn"
               id="banner-reset-btn"
               @if(!request('search') && !request('status')) hidden @endif>
                <i class="bi bi-arrow-counterclockwise"></i>
                Reset
            </a>
        </form>
    </div>

    <div id="banners-results">
        <div class="dashboard-panel">
            <div class="table-responsive">
                <table class="table banners-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th width="120">Image</th>
                            <th>Title</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th width="130" class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($banners as $banner)
                            <tr id="banner-row-{{ $banner->id }}">
                                <td>{{ $banners->firstItem() + $loop->index }}</td>

                                <td>
                                    <div class="banner-image-wrapper">
                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                             alt="{{ $banner->title }}"
                                             class="banner-image">
                                    </div>
                                </td>

                                <td>
                                    <div class="banner-title">{{ $banner->title }}</div>

                                    @if($banner->subtitle)
                                        <div class="banner-subtitle">{{ $banner->subtitle }}</div>
                                    @endif
                                </td>

                                <td>
                                    <span class="position-badge">{{ $banner->position }}</span>
                                </td>

                                <td>
                                    @if($banner->status === 'active')
                                        <span class="status-badge status-active">
                                            <span class="status-dot"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            <span class="status-dot"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($banner->start_date || $banner->end_date)
                                        <div class="banner-dates">
                                            @if($banner->start_date)
                                                <div>
                                                    <i class="bi bi-calendar-event"></i>
                                                    {{ $banner->start_date->format('d M Y') }}
                                                </div>
                                            @endif

                                            @if($banner->end_date)
                                                <div>
                                                    <i class="bi bi-calendar-check"></i>
                                                    {{ $banner->end_date->format('d M Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No date limit</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="banner-actions">
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                           class="action-btn action-edit"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.banners.destroy', $banner) }}"
                                              method="POST"
                                              class="d-inline banner-delete-form"
                                              data-banner-id="{{ $banner->id }}"
                                              data-banner-title="{{ $banner->title }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn action-delete"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-image"></i>
                                        </div>
                                        <h6>No banners found</h6>
                                        <p>Create your first banner to display it on your website.</p>

                                        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-lg"></i>
                                            Add Banner
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($banners->hasPages())
                <div class="banners-pagination">
                    <div class="banners-pagination-info">
                        Showing <strong>{{ $banners->firstItem() }}</strong>
                        to <strong>{{ $banners->lastItem() }}</strong>
                        of <strong>{{ $banners->total() }}</strong> results
                    </div>

                    <div class="banners-pagination-links">
                        @if($banners->onFirstPage())
                            <span class="banner-page-btn disabled">‹</span>
                        @else
                            <a href="{{ $banners->previousPageUrl() }}" class="banner-page-btn">‹</a>
                        @endif

                        @php
                            $current = $banners->currentPage();
                            $last = $banners->lastPage();
                        @endphp

                        @for($page = 1; $page <= $last; $page++)
                            @if($page <= 6 || $page >= $last - 1 || abs($page - $current) <= 1)
                                <a href="{{ $banners->url($page) }}"
                                   class="banner-page-btn {{ $page == $current ? 'active' : '' }}">
                                    {{ $page }}
                                </a>
                            @elseif($page == 7 || $page == $last - 2)
                                <span class="banner-page-btn disabled">...</span>
                            @endif
                        @endfor

                        @if($banners->hasMorePages())
                            <a href="{{ $banners->nextPageUrl() }}" class="banner-page-btn">›</a>
                        @else
                            <span class="banner-page-btn disabled">›</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('banner-filter-form');
    const results = document.getElementById('banners-results');
    const resetButton = document.getElementById('banner-reset-btn');
    const searchInput = filterForm.querySelector('[name="search"]');
    const statusSelect = filterForm.querySelector('[name="status"]');

    let requestController = null;

    function updateResetButton() {
        resetButton.hidden = !searchInput.value.trim() && !statusSelect.value;
    }

    async function loadBanners(url, updateHistory = true) {
        if (requestController) {
            requestController.abort();
        }

        requestController = new AbortController();

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: requestController.signal
            });

            if (!response.ok) {
                throw new Error('Failed to load banners.');
            }

            const html = await response.text();
            const documentResult = new DOMParser().parseFromString(html, 'text/html');
            const newResults = documentResult.getElementById('banners-results');
            const newForm = documentResult.getElementById('banner-filter-form');

            if (!newResults || !newForm) {
                throw new Error('Could not update the banner results.');
            }

            results.innerHTML = newResults.innerHTML;

            searchInput.value = newForm.querySelector('[name="search"]').value;
            statusSelect.value = newForm.querySelector('[name="status"]').value;

            updateResetButton();

            if (updateHistory) {
                history.pushState({}, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Banners could not be loaded. Please try again.'
                });
            }
        }
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const params = new URLSearchParams(new FormData(filterForm));

        for (const [key, value] of params.entries()) {
            if (!value.trim()) {
                params.delete(key);
            }
        }

        params.delete('page');

        const query = params.toString();
        const url = filterForm.action + (query ? '?' + query : '');

        loadBanners(url);
    });

    searchInput.addEventListener('input', updateResetButton);

    resetButton.addEventListener('click', function (event) {
        event.preventDefault();

        searchInput.value = '';
        statusSelect.value = '';

        updateResetButton();
        loadBanners(filterForm.action);
    });

    results.addEventListener('click', function (event) {
        const pageLink = event.target.closest('.banners-pagination-links a');

        if (!pageLink || pageLink.classList.contains('disabled')) {
            return;
        }

        event.preventDefault();
        loadBanners(pageLink.href);
    });

    window.addEventListener('popstate', function () {
        loadBanners(window.location.href, false);
    });

    results.addEventListener('submit', function (event) {
        const form = event.target.closest('.banner-delete-form');

        if (!form) {
            return;
        }

        event.preventDefault();

        const deleteUrl = form.action;
        const csrfToken = form.querySelector('input[name="_token"]')?.value;
        const bannerId = form.dataset.bannerId;
        const bannerTitle = form.dataset.bannerTitle;

        if (!deleteUrl || !csrfToken || !bannerId) {
            return;
        }

        Swal.fire({
            title: 'Delete Banner?',
            text: `"${bannerTitle}" will be permanently deleted.`,
            icon: 'warning',
            width: 430,
            padding: '30px',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'banner-delete-popup',
                confirmButton: 'banner-delete-confirm',
                cancelButton: 'banner-delete-cancel'
            }
        }).then(async function (result) {
            if (!result.isConfirmed) {
                return;
            }

            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function () {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        _token: csrfToken,
                        _method: 'DELETE'
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to delete banner.');
                }

                await loadBanners(window.location.href, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Banner Deleted',
                    text: data.message || 'Banner deleted successfully.',
                    timer: 1400,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: error.message || 'Something went wrong while deleting the banner.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    updateResetButton();
});
</script>
@endpush