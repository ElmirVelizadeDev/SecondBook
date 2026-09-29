@extends('layout.admin.master')

@section('title', 'Banners')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/banners.css') }}">
@endpush

@section('content')

<div class="dashboard-section banners-page">

    {{-- Header --}}
    <div class="dashboard-panel mb-4">
        <div class="panel-header mb-0">

            <div>
                <h5 class="mb-1">Banners</h5>

                <p class="text-muted mb-0 small">
                    Manage your website banners
                </p>
            </div>

            <a
                href="{{ route('admin.banners.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg"></i>
                Add Banner
            </a>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show mb-4"
            role="alert"
        >
            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- Filters --}}
    <div class="dashboard-panel mb-4">

        <form
            action="{{ route('admin.banners.index') }}"
            method="GET"
            class="banner-filters"
        >

            <div class="banner-search-group">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search banner..."
                >

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-search"></i>
                    Search
                </button>

            </div>


            <div class="banner-status-filter">

                <select
                    name="status"
                    class="form-select"
                >
                    <option value="">All Statuses</option>

                    <option
                        value="active"
                        {{ request('status') === 'active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        {{ request('status') === 'inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

            </div>


            @if(request('search') || request('status'))

                <a
                    href="{{ route('admin.banners.index') }}"
                    class="btn btn-light banner-reset-btn"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>

            @endif

        </form>

    </div>


    {{-- Banners Table --}}
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
                        <th width="130" class="text-end">
                            Actions
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($banners as $banner)

                        <tr id="banner-row-{{ $banner->id }}">

                            {{-- ID --}}
                            <td>
                                {{ $banners->firstItem() + $loop->index }}
                            </td>


                            {{-- Image --}}
                            <td>

                                <div class="banner-image-wrapper">

                                    <img
                                        src="{{ asset('storage/' . $banner->image) }}"
                                        alt="{{ $banner->title }}"
                                        class="banner-image"
                                    >

                                </div>

                            </td>


                            {{-- Title --}}
                            <td>

                                <div class="banner-title">
                                    {{ $banner->title }}
                                </div>

                                @if($banner->subtitle)

                                    <div class="banner-subtitle">
                                        {{ $banner->subtitle }}
                                    </div>

                                @endif

                            </td>


                            {{-- Position --}}
                            <td>

                                <span class="position-badge">
                                    {{ $banner->position }}
                                </span>

                            </td>


                            {{-- Status --}}
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


                            {{-- Dates --}}
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

                                    <span class="text-muted">
                                        No date limit
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="banner-actions">

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.banners.edit', $banner) }}"
                                        class="action-btn action-edit"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.banners.destroy', $banner) }}"
                                        method="POST"
                                        class="d-inline banner-delete-form"
                                        data-banner-id="{{ $banner->id }}"
                                        data-banner-title="{{ $banner->title }}"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            title="Delete"
                                        >
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

                                    <h6>
                                        No banners found
                                    </h6>

                                    <p>
                                        Create your first banner to display it on your website.
                                    </p>

                                    <a
                                        href="{{ route('admin.banners.create') }}"
                                        class="btn btn-primary"
                                    >
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


        {{-- Pagination --}}
        @if($banners->hasPages())
            <div class="banners-pagination">

                <div class="banners-pagination-info">
                    Showing
                    <strong>{{ $banners->firstItem() }}</strong>
                    to
                    <strong>{{ $banners->lastItem() }}</strong>
                    of
                    <strong>{{ $banners->total() }}</strong>
                    results
                </div>

                <div class="banners-pagination-links">

                    {{-- Previous --}}
                    @if($banners->onFirstPage())
                        <span class="banner-page-btn disabled">‹</span>
                    @else
                        <a
                            href="{{ $banners->previousPageUrl() }}"
                            class="banner-page-btn"
                        >‹</a>
                    @endif

                    @php
                        $current = $banners->currentPage();
                        $last = $banners->lastPage();
                    @endphp

                    @for($page = 1; $page <= $last; $page++)

                        @if(
                            $page <= 6 ||
                            $page >= $last - 1 ||
                            abs($page - $current) <= 1
                        )

                            <a
                                href="{{ $banners->url($page) }}"
                                class="banner-page-btn {{ $page == $current ? 'active' : '' }}"
                            >
                                {{ $page }}
                            </a>

                        @elseif($page == 7 || $page == $last - 2)

                            <span class="banner-page-btn disabled">
                                ...
                            </span>

                        @endif

                    @endfor

                    {{-- Next --}}
                    @if($banners->hasMorePages())
                        <a
                            href="{{ $banners->nextPageUrl() }}"
                            class="banner-page-btn"
                        >›</a>
                    @else
                        <span class="banner-page-btn disabled">›</span>
                    @endif

                </div>

            </div>
        @endif

    </div>

</div>

@endsection


@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForms = document.querySelectorAll(
        '.banner-delete-form'
    );


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();


            const deleteUrl = form.action;

            const csrfToken = form.querySelector(
                'input[name="_token"]'
            )?.value;

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

            }).then(function (result) {

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


                fetch(deleteUrl, {

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

                })

                .then(async function (response) {

                    const data = await response.json();


                    if (!response.ok || !data.success) {

                        throw new Error(
                            data.message ||
                            'Failed to delete banner.'
                        );

                    }


                    return data;

                })

                .then(function (data) {

                    const row = document.getElementById(
                        'banner-row-' + bannerId
                    );


                    if (row) {
                        row.remove();
                    }


                    Swal.fire({

                        icon: 'success',

                        title: 'Banner Deleted',

                        text: data.message ||
                            'Banner deleted successfully.',

                        timer: 1400,

                        showConfirmButton: false

                    });

                })

                .catch(function (error) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Delete Failed',

                        text: error.message ||
                            'Something went wrong while deleting the banner.',

                        confirmButtonText: 'OK'

                    });

                });

            });

        });

    });

});

</script>

@endpush