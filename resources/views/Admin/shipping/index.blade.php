@extends('layout.admin.master')

@section('title', 'Shipping')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/shipping.css') }}">
@endpush

@section('content')

<div class="dashboard-section shipping-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}
    <section class="shipping-hero">

        <div class="shipping-hero-content">

            <span class="shipping-hero-badge">
                <i class="bi bi-truck"></i>
                Shipping Management
            </span>

            <h1>Manage shipping methods.</h1>

            <p>
                Manage delivery options, pricing, availability
                and shipping methods for your marketplace.
            </p>

        </div>

        <div class="shipping-hero-mark" aria-hidden="true">
            <i class="bi bi-truck"></i>
        </div>

    </section>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}
    @if(session('success'))
        <div class="shipping-alert shipping-alert-success">
            <i class="bi bi-check-circle"></i>

            <span>{{ session('success') }}</span>

            <button type="button"
                    class="shipping-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="shipping-alert shipping-alert-danger">
            <i class="bi bi-exclamation-circle"></i>

            <span>{{ session('error') }}</span>

            <button type="button"
                    class="shipping-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}
    <section class="shipping-stats">

        {{-- Total --}}
        <div class="shipping-stat-card stat-blue">

            <div class="shipping-stat-content">
                <span>Total Shipping</span>
                <h3>{{ $totalShippings }}</h3>
            </div>

            <div class="shipping-stat-icon">
                <i class="bi bi-truck"></i>
            </div>

        </div>


        {{-- Active --}}
        <div class="shipping-stat-card stat-green">

            <div class="shipping-stat-content">
                <span>Active</span>
                <h3>{{ $activeShippings }}</h3>
            </div>

            <div class="shipping-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>

        </div>


        {{-- Inactive --}}
        <div class="shipping-stat-card stat-orange">

            <div class="shipping-stat-content">
                <span>Inactive</span>
                <h3>{{ $inactiveShippings }}</h3>
            </div>

            <div class="shipping-stat-icon">
                <i class="bi bi-pause-circle"></i>
            </div>

        </div>


        {{-- Free Shipping --}}
        <div class="shipping-stat-card stat-purple">

            <div class="shipping-stat-content">
                <span>Free Shipping</span>
                <h3>{{ $freeShippings }}</h3>
            </div>

            <div class="shipping-stat-icon">
                <i class="bi bi-gift"></i>
            </div>

        </div>

    </section>


    {{-- =========================================================
         MAIN PANEL
    ========================================================== --}}
    <section class="shipping-panel">

        {{-- Panel Header --}}
        <div class="shipping-panel-header">

            <div class="shipping-heading-content">

                <span class="eyebrow">
                    Shipping configuration
                </span>

                <h5>Shipping Methods</h5>

                <p>
                    View and manage all available shipping methods.
                </p>

            </div>

            <div class="shipping-header-action">

                <a href="{{ route('admin.shipping.create') }}"
                   class="shipping-add-btn">

                    <i class="bi bi-plus-lg"></i>

                    <span>Add Shipping</span>

                </a>

            </div>

        </div>


        {{-- =====================================================
             FILTERS
        ====================================================== --}}
        <form method="GET"
              action="{{ route('admin.shipping.index') }}"
              class="shipping-filters">

            {{-- Search --}}
            <div class="shipping-filter-group shipping-filter-search">

                <label for="shipping-search">
                    Search
                </label>

                <div class="shipping-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        id="shipping-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search shipping method..."
                    >

                </div>

            </div>


            {{-- Status --}}
            <div class="shipping-filter-group">

                <label for="shipping-status">
                    Status
                </label>

                <select id="shipping-status"
                        name="status"
                        class="shipping-filter-select">

                    <option value="">
                        All Status
                    </option>

                    <option value="1"
                        @selected(request('status') === '1')>
                        Active
                    </option>

                    <option value="0"
                        @selected(request('status') === '0')>
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Actions --}}
            <div class="shipping-filter-actions">

                <button type="submit"
                        class="shipping-filter-btn">

                    <i class="bi bi-funnel"></i>

                    <span>Filter</span>

                </button>

                <a href="{{ route('admin.shipping.index') }}"
                   class="shipping-clear-filter">

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Reset

                </a>

            </div>

        </form>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="shipping-table-wrap">

            <table class="shipping-table">

                <thead>

                    <tr>

                        <th class="shipping-col-id">
                            #
                        </th>

                        <th class="shipping-col-method">
                            Method
                        </th>

                        <th class="shipping-col-description">
                            Description
                        </th>

                        <th class="shipping-col-price">
                            Price
                        </th>

                        <th class="shipping-col-delivery">
                            Delivery Time
                        </th>

                        <th class="shipping-col-status">
                            Status
                        </th>

                        <th class="shipping-col-actions">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($shippings as $shipping)

                        <tr>

                            {{-- ID --}}
                            <td class="shipping-id">
                                #{{ $shipping->id }}
                            </td>


                            {{-- Method --}}
                            <td>

                                <div class="shipping-method">

                                    <div class="shipping-method-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>

                                    <span>
                                        {{ $shipping->name }}
                                    </span>

                                </div>

                            </td>


                            {{-- Description --}}
                            <td>

                                <span class="shipping-description">

                                    {{ Str::limit($shipping->description, 55) ?: '—' }}

                                </span>

                            </td>


                            {{-- Price --}}
                            <td>

                                @if($shipping->price == 0)

                                    <span class="shipping-price shipping-price-free">
                                        Free
                                    </span>

                                @else

                                    <span class="shipping-price">
                                        {{ number_format($shipping->price, 2) }} AZN
                                    </span>

                                @endif

                            </td>


                            {{-- Delivery --}}
                            <td>

                                <span class="shipping-delivery">
                                    <i class="bi bi-clock"></i>
                                    {{ $shipping->delivery_time }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($shipping->status)

                                    <span class="shipping-status-pill shipping-status-active">
                                        <span></span>
                                        Active
                                    </span>

                                @else

                                    <span class="shipping-status-pill shipping-status-inactive">
                                        <span></span>
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="shipping-actions">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.shipping.edit', $shipping->id) }}"
                                       class="shipping-action-btn shipping-edit-btn"
                                       title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- Delete --}}
                                    <form action="{{ route('admin.shipping.destroy', $shipping->id) }}"
                                          method="POST"
                                          class="delete-shipping-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="shipping-action-btn shipping-delete-btn"
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

                                <div class="shipping-empty-state">

                                    <div class="shipping-empty-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>

                                    <h6>No shipping methods found.</h6>

                                    <p>
                                        Try changing your filters or search.
                                    </p>

                                    <a href="{{ route('admin.shipping.create') }}"
                                       class="shipping-empty-btn">

                                        <i class="bi bi-plus-lg"></i>
                                        Add Shipping

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($shippings->hasPages())

            <div class="shipping-pagination">

                <div class="shipping-pagination-info">

                    Showing
                    <strong>{{ $shippings->firstItem() }}</strong>
                    to
                    <strong>{{ $shippings->lastItem() }}</strong>
                    of
                    <strong>{{ $shippings->total() }}</strong>
                    shipping methods

                </div>

                <div class="shipping-pagination-pages">

                    @if($shippings->onFirstPage())

                        <span class="shipping-pager-btn disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a href="{{ $shippings->previousPageUrl() }}"
                           class="shipping-pager-btn">

                            <i class="bi bi-chevron-left"></i>

                        </a>

                    @endif


                    @foreach($shippings->getUrlRange(
                        max(1, $shippings->currentPage() - 2),
                        min($shippings->lastPage(), $shippings->currentPage() + 2)
                    ) as $page => $url)

                        @if($page == $shippings->currentPage())

                            <span class="shipping-pager-btn active">
                                {{ $page }}
                            </span>

                        @else

                            <a href="{{ $url }}"
                               class="shipping-pager-btn">

                                {{ $page }}

                            </a>

                        @endif

                    @endforeach


                    @if($shippings->hasMorePages())

                        <a href="{{ $shippings->nextPageUrl() }}"
                           class="shipping-pager-btn">

                            <i class="bi bi-chevron-right"></i>

                        </a>

                    @else

                        <span class="shipping-pager-btn disabled">
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

    document.querySelectorAll('.delete-shipping-form').forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const row = form.closest('tr');
            const button = form.querySelector('button');

            const result = await Swal.fire({

                title: 'Delete shipping method?',

                text: 'This shipping method will be permanently deleted.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#dc3545',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Yes, delete it!',

                cancelButtonText: 'Cancel',

                reverseButtons: true

            });

            if (!result.isConfirmed) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent double click
            |--------------------------------------------------------------------------
            */

            if (button) {
                button.disabled = true;
            }

            try {

                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content');

                const response = await fetch(form.action, {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN': csrfToken,

                        'Accept': 'application/json',

                        'X-Requested-With': 'XMLHttpRequest',

                        'Content-Type':
                            'application/x-www-form-urlencoded; charset=UTF-8'

                    },

                    body: new URLSearchParams({

                        _token: csrfToken,

                        _method: 'DELETE'

                    })

                });

                const contentType =
                    response.headers.get('content-type') || '';

                let data = {};

                if (contentType.includes('application/json')) {
                    data = await response.json();
                }

                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Unable to delete the shipping method.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Remove row without refreshing page
                |--------------------------------------------------------------------------
                */

                if (row) {

                    row.style.transition =
                        'opacity 0.3s ease, transform 0.3s ease';

                    row.style.opacity = '0';

                    row.style.transform = 'translateX(20px)';

                    setTimeout(function () {

                        row.remove();

                    }, 300);
                }

                /*
                |--------------------------------------------------------------------------
                | Success alert
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    icon: 'success',

                    title: 'Shipping deleted',

                    text:
                        data.message ||
                        'The shipping method has been deleted successfully.',

                    timer: 1800,

                    showConfirmButton: false

                });

            } catch (error) {

                console.error('Shipping delete error:', error);

                if (button) {
                    button.disabled = false;
                }

                Swal.fire({

                    icon: 'error',

                    title: 'Delete failed',

                    text:
                        error.message ||
                        'Unable to delete the shipping method.',

                    confirmButtonColor: '#2563eb'

                });

            }

        });

    });

});
</script>

@endpush