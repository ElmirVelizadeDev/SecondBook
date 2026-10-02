@extends('layout.admin.master')

@section('title', 'Categories')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/categories.css') }}">
@endpush

@section('content')

@php
    $totalCategories = $categories->total();

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    | These queries keep the page independent from controller-provided
    | statistics while the same categories.css can be reused everywhere.
    */
    $activeCategories = \App\Models\Category::where('status', true)->count();
    $inactiveCategories = \App\Models\Category::where('status', false)->count();
    $categoriesWithBooks = \App\Models\Category::has('books')->count();
@endphp

<div class="dashboard-section categories-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="categories-hero">

        <div class="categories-hero-content">

            <span class="categories-hero-badge">
                <i class="bi bi-tags"></i>
                Marketplace Library
            </span>

            <h1>
                Categories, beautifully organised.
            </h1>

            <p>
                Create, organise and manage the categories used across
                the SecondBook marketplace.
            </p>

        </div>

        <div class="categories-hero-mark" aria-hidden="true">
            <i class="bi bi-tags"></i>
        </div>

    </section>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <section class="categories-stats">

        {{-- Total --}}
        <div class="category-stat-card stat-blue">

            <div class="category-stat-content">
                <span>Total Categories</span>
                <strong>{{ $totalCategories }}</strong>
            </div>

            <div class="category-stat-icon">
                <i class="bi bi-tags"></i>
            </div>

        </div>


        {{-- Active --}}
        <div class="category-stat-card stat-green">

            <div class="category-stat-content">
                <span>Active</span>
                <strong>{{ $activeCategories }}</strong>
            </div>

            <div class="category-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>

        </div>


        {{-- Inactive --}}
        <div class="category-stat-card stat-orange">

            <div class="category-stat-content">
                <span>Inactive</span>
                <strong>{{ $inactiveCategories }}</strong>
            </div>

            <div class="category-stat-icon">
                <i class="bi bi-pause-circle"></i>
            </div>

        </div>


        {{-- Used by Books --}}
        <div class="category-stat-card stat-purple">

            <div class="category-stat-content">
                <span>Used by Books</span>
                <strong>{{ $categoriesWithBooks }}</strong>
            </div>

            <div class="category-stat-icon">
                <i class="bi bi-book"></i>
            </div>

        </div>

    </section>


    {{-- =========================================================
        CATEGORY PANEL
    ========================================================== --}}
    <section class="dashboard-panel categories-panel">

        {{-- =====================================================
            PANEL HEADER
        ====================================================== --}}
        <div class="categories-panel-header">

            <div class="categories-heading-content">

                <span class="eyebrow">
                    Category Directory
                </span>

                <h5>
                    All Categories
                </h5>

                <p>
                    Search, filter and manage your marketplace categories.
                </p>

            </div>


            <div class="categories-header-action">

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="categories-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Category</span>
                </a>

            </div>

        </div>


        {{-- =====================================================
            SUCCESS MESSAGE
        ====================================================== --}}
        @if(session('success'))

            <div class="category-alert category-alert-success">

                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        {{-- =====================================================
            FILTERS
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.categories.index') }}"
            class="category-filters"
        >

            {{-- Search --}}
            <div class="category-filter-search">

                <label for="category-search">
                    Search
                </label>

                <div class="category-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        id="category-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Category name, slug or description..."
                    >

                </div>

            </div>


            {{-- Status --}}
            <div class="category-filter-group">

                <label for="category-status">
                    Status
                </label>

                <select
                    id="category-status"
                    name="status"
                    class="category-filter-select"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="1"
                        @selected(request('status') === '1')
                    >
                        Active
                    </option>

                    <option
                        value="0"
                        @selected(request('status') === '0')
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Actions --}}
            <div class="category-filter-actions">

                <button
                    type="submit"
                    class="category-filter-btn"
                >
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>

                @if(request()->hasAny(['search', 'status']))

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="category-clear-filter"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Reset</span>
                    </a>

                @endif

            </div>

        </form>


        {{-- =====================================================
            TABLE HEADER
        ====================================================== --}}
        <div class="categories-list-header">

            <div class="categories-list-heading">

                <span class="eyebrow">
                    Category Management
                </span>

                <h5>
                    Category List
                </h5>

                <p>
                    {{ $totalCategories }}
                    {{ $totalCategories === 1 ? 'category' : 'categories' }}
                    available in the marketplace.
                </p>

            </div>

            <div class="categories-count-badge">
                <i class="bi bi-collection"></i>
                <span>{{ $totalCategories }}</span>
            </div>

        </div>


        {{-- =====================================================
            TABLE
        ====================================================== --}}
        <div class="categories-table-wrap">

            <table class="categories-table">

                <thead>

                    <tr>

                        <th class="categories-col-id">
                            ID
                        </th>

                        <th class="categories-col-category">
                            Category
                        </th>

                        <th class="categories-col-slug">
                            Slug
                        </th>

                        <th class="categories-col-description">
                            Description
                        </th>

                        <th class="categories-col-books">
                            Books
                        </th>

                        <th class="categories-col-status">
                            Status
                        </th>

                        <th class="categories-col-date">
                            Created
                        </th>

                        <th class="categories-col-actions">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($categories as $category)

                        <tr data-category-id="{{ $category->id }}">

                            {{-- =================================================
                                ID
                            ================================================== --}}
                            <td>

                                <span class="category-id">
                                    #{{ $category->id }}
                                </span>

                            </td>


                            {{-- =================================================
                                CATEGORY
                            ================================================== --}}
                            <td>

                                <div class="category-item-cell">

                                    @if(!empty($category->image))

                                        @php
                                            $categoryImageUrl = filter_var(
                                                $category->image,
                                                FILTER_VALIDATE_URL
                                            )
                                                ? $category->image
                                                : asset('storage/' . $category->image);
                                        @endphp

                                        <img
                                            src="{{ $categoryImageUrl }}"
                                            alt="{{ $category->name }}"
                                            class="category-image"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="category-image-placeholder">
                                            <i class="bi bi-tags"></i>
                                        </div>

                                    @endif


                                    <div class="category-item-info">

                                        <strong
                                            title="{{ $category->name }}"
                                        >
                                            {{ $category->name }}
                                        </strong>

                                        <small>
                                            Category #{{ $category->id }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                SLUG
                            ================================================== --}}
                            <td>

                                <span
                                    class="category-slug"
                                    title="{{ $category->slug }}"
                                >
                                    {{ $category->slug }}
                                </span>

                            </td>


                            {{-- =================================================
                                DESCRIPTION
                            ================================================== --}}
                            <td>

                                <div class="category-description">

                                    {{ \Illuminate\Support\Str::limit(
                                        $category->description ?? '—',
                                        65
                                    ) }}

                                </div>

                            </td>


                            {{-- =================================================
                                BOOK COUNT
                            ================================================== --}}
                            <td>

                                <span class="category-book-count">

                                    <i class="bi bi-book"></i>

                                    {{ $category->books_count ?? 0 }}

                                </span>

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}
                            <td>

                                @if($category->status)

                                    <span class="category-status-pill category-status-active">

                                        <i class="bi bi-circle-fill"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="category-status-pill category-status-inactive">

                                        <i class="bi bi-circle-fill"></i>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                CREATED
                            ================================================== --}}
                            <td>

                                <span class="category-date">
                                    {{ $category->created_at?->format('d M Y') ?? '—' }}
                                </span>

                            </td>


                            {{-- =================================================
                                ACTIONS
                            ================================================== --}}
                            <td>

                                <div class="category-actions">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.categories.show', $category->id) }}"
                                        class="category-action-btn category-action-view"
                                        title="View Category"
                                        aria-label="View Category"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Status --}}
                                    <form
                                        action="{{ route('admin.categories.status', $category->id) }}"
                                        method="POST"
                                        class="category-status-form"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="category-action-btn {{ $category->status
                                                ? 'category-action-warning'
                                                : 'category-action-success' }}"
                                            title="{{ $category->status ? 'Deactivate' : 'Activate' }}"
                                            aria-label="{{ $category->status ? 'Deactivate' : 'Activate' }}"
                                        >

                                            <i class="bi {{ $category->status
                                                ? 'bi-pause-circle'
                                                : 'bi-check-circle' }}"></i>

                                        </button>

                                    </form>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="category-action-btn category-action-edit"
                                        title="Edit Category"
                                        aria-label="Edit Category"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.categories.destroy', $category->id) }}"
                                        method="POST"
                                        class="category-delete-form"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="category-action-btn category-action-delete"
                                            title="Delete Category"
                                            aria-label="Delete Category"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="categories-empty-state">

                                    <div class="categories-empty-icon">
                                        <i class="bi bi-tags"></i>
                                    </div>

                                    <h6>
                                        No categories found
                                    </h6>

                                    <p>
                                        There are no categories matching your
                                        current filters.
                                    </p>

                                    <a
                                        href="{{ route('admin.categories.create') }}"
                                        class="categories-empty-btn"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Add Category
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
        @if($categories->hasPages())

            <div class="categories-pagination">

                <div class="categories-pagination-info">

                    Showing

                    <strong>
                        {{ $categories->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $categories->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $categories->total() }}
                    </strong>

                    categories

                </div>


                <nav
                    class="categories-pagination-pages"
                    aria-label="Categories pagination"
                >

                    {{-- Previous --}}
                    @if($categories->onFirstPage())

                        <span class="categories-pager-btn disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $categories->previousPageUrl() }}"
                            class="categories-pager-btn"
                            aria-label="Previous page"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </a>

                    @endif


                    {{-- Pages --}}
                    @foreach($categories->getUrlRange(
                        max(1, $categories->currentPage() - 2),
                        min($categories->lastPage(), $categories->currentPage() + 2)
                    ) as $page => $url)

                        @if($page == $categories->currentPage())

                            <span class="categories-pager-btn active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $url }}"
                                class="categories-pager-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach


                    {{-- Next --}}
                    @if($categories->hasMorePages())

                        <a
                            href="{{ $categories->nextPageUrl() }}"
                            class="categories-pager-btn"
                            aria-label="Next page"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @else

                        <span class="categories-pager-btn disabled">
                            <i class="bi bi-chevron-right"></i>
                        </span>

                    @endif

                </nav>

            </div>

        @endif

    </section>

</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Delete Category
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.category-delete-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            /*
            |--------------------------------------------------------------------------
            | SweetAlert is not available
            |--------------------------------------------------------------------------
            */

            if (typeof Swal === 'undefined') {
                form.submit();
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Confirmation
            |--------------------------------------------------------------------------
            */

            Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(function (result) {

                /*
                |--------------------------------------------------------------------------
                | User cancelled
                |--------------------------------------------------------------------------
                */

                if (!result.isConfirmed) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Delete request
                |--------------------------------------------------------------------------
                */

                const row = form.closest('tr');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content'),

                        'Accept': 'application/json',

                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    body: new URLSearchParams(
                        new FormData(form)
                    )
                })

                .then(function (response) {

                    if (!response.ok) {
                        throw new Error('Delete request failed.');
                    }

                    return response.json().catch(function () {
                        return {};
                    });
                })

                .then(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Remove row animation
                    |--------------------------------------------------------------------------
                    */

                    if (row) {

                        row.style.transition =
                            'opacity .25s ease, transform .25s ease';

                        row.style.opacity = '0';

                        row.style.transform = 'translateX(10px)';

                        setTimeout(function () {

                            row.remove();

                            window.location.reload();

                        }, 250);

                    } else {

                        window.location.reload();

                    }
                })

                .catch(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Delete error
                    |--------------------------------------------------------------------------
                    */

                    Swal.fire({
                        title: 'Unable to delete',
                        text: 'The category could not be deleted. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });

                });

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Status Toggle
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.category-status-form').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            const button = form.querySelector('button');

            if (!button) {
                return;
            }

            button.disabled = true;

            fetch(form.action, {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content'),

                    'Accept': 'application/json',

                    'X-Requested-With': 'XMLHttpRequest'
                },

                body: new URLSearchParams(
                    new FormData(form)
                )
            })

            .then(function (response) {

                if (!response.ok) {
                    throw new Error('Status update failed.');
                }

                return response.json().catch(function () {
                    return {};
                });

            })

            .then(function () {

                window.location.reload();

            })

            .catch(function () {

                button.disabled = false;

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        title: 'Unable to update status',
                        text: 'Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });

                } else {

                    window.location.reload();

                }

            });

        });

    });

});
</script>

@endpush