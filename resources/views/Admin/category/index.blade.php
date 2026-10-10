
@extends('layout.admin.master')

@section('title', 'Categories')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/category.css') }}">
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
                        autocomplete="off"
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
                        <th class="categories-col-id">ID</th>
                        <th class="categories-col-category">Category</th>
                        <th class="categories-col-slug">Slug</th>
                        <th class="categories-col-description">Description</th>
                        <th class="categories-col-books">Books</th>
                        <th class="categories-col-status">Status</th>
                        <th class="categories-col-date">Created</th>
                        <th class="categories-col-actions">Actions</th>
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
                                        <strong title="{{ $category->name }}">
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
                    <strong>{{ $categories->firstItem() }}</strong>
                    to
                    <strong>{{ $categories->lastItem() }}</strong>
                    of
                    <strong>{{ $categories->total() }}</strong>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.querySelector('.category-filters');
    const searchInput = document.getElementById('category-search');
    const statusSelect = document.getElementById('category-status');

    let searchTimer = null;
    let activeController = null;
    let requestNumber = 0;

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content ||
        filterForm?.querySelector('input[name="_token"]')?.value ||
        '';

    function notify(icon, title, text = '') {
        if (typeof Swal !== 'undefined') {
            return Swal.fire({
                icon,
                title,
                text,
                confirmButtonColor: '#2563eb'
            });
        }

        window.alert(title + (text ? '\n' + text : ''));
        return Promise.resolve();
    }

    function buildFilterUrl() {
        const url = new URL(filterForm.action, window.location.origin);
        const formData = new FormData(filterForm);

        formData.forEach((value, key) => {
            const cleanValue = String(value).trim();

            if (cleanValue) {
                url.searchParams.set(key, cleanValue);
            } else {
                url.searchParams.delete(key);
            }
        });

        url.searchParams.delete('page');

        return url;
    }

    function syncFilters(url) {
        const params = new URL(url, window.location.origin).searchParams;

        if (searchInput) {
            searchInput.value = params.get('search') || '';
        }

        if (statusSelect) {
            statusSelect.value = params.get('status') || '';
        }

        updateClearButton();
    }

    function updateClearButton() {
        if (!filterForm) return;

        const hasFilters = [
            searchInput?.value,
            statusSelect?.value
        ].some(value => String(value || '').trim() !== '');

        const actions = filterForm.querySelector('.category-filter-actions');
        let clearLink = filterForm.querySelector('.category-clear-filter');

        if (hasFilters && !clearLink && actions) {
            clearLink = document.createElement('a');
            clearLink.href = filterForm.action;
            clearLink.className = 'category-clear-filter';
            clearLink.innerHTML =
                '<i class="bi bi-x-lg"></i><span>Reset</span>';

            actions.appendChild(clearLink);
        } else if (!hasFilters && clearLink) {
            clearLink.remove();
        }
    }

    function replacePagination(parsedDocument) {
        const current = document.querySelector('.categories-pagination');
        const next = parsedDocument.querySelector('.categories-pagination');

        if (current && next) {
            current.replaceWith(next.cloneNode(true));
        } else if (current) {
            current.remove();
        } else if (next) {
            document.querySelector('.categories-table-wrap')
                ?.insertAdjacentElement('afterend', next.cloneNode(true));
        }
    }

    function replacePageContent(parsedDocument) {
        const newTable = parsedDocument.querySelector('.categories-table-wrap');
        const currentTable = document.querySelector('.categories-table-wrap');

        if (!newTable || !currentTable) {
            throw new Error(
                'The categories table was not found in the server response.'
            );
        }

        const newStats = parsedDocument.querySelector('.categories-stats');
        const currentStats = document.querySelector('.categories-stats');

        if (newStats && currentStats) {
            currentStats.replaceWith(newStats.cloneNode(true));
        }

        const newListHeader = parsedDocument.querySelector('.categories-list-header');
        const currentListHeader = document.querySelector('.categories-list-header');

        if (newListHeader && currentListHeader) {
            currentListHeader.replaceWith(newListHeader.cloneNode(true));
        }

        currentTable.replaceWith(newTable.cloneNode(true));
        replacePagination(parsedDocument);
    }

    async function loadCategories(url, options = {}) {
        const targetUrl = new URL(url, window.location.origin);
        const thisRequest = ++requestNumber;

        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();
        const controller = activeController;

        document.querySelector('.categories-table-wrap')
            ?.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(targetUrl.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error('Unable to load categories. Please try again.');
            }

            const html = await response.text();
            const parsedDocument = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            if (thisRequest !== requestNumber) {
                return null;
            }

            replacePageContent(parsedDocument);
            syncFilters(targetUrl);

            if (options.updateHistory !== false) {
                window.history.pushState(
                    { categoriesAjax: true },
                    '',
                    targetUrl.toString()
                );
            }

            if (options.scrollToTable) {
                document.querySelector('.categories-panel')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            return parsedDocument;
        } catch (error) {
            if (error.name === 'AbortError') {
                return null;
            }

            notify(
                'error',
                'Unable to load categories',
                error.message || 'Please try again.'
            );

            throw error;
        } finally {
            if (thisRequest === requestNumber) {
                activeController = null;

                document.querySelector('.categories-table-wrap')
                    ?.removeAttribute('aria-busy');
            }
        }
    }

    function applyFilters() {
        updateClearButton();

        loadCategories(buildFilterUrl(), {
            updateHistory: true,
            scrollToTable: false
        }).catch(() => {});
    }

    // =========================================================
    // SEARCH — AJAX
    // =========================================================

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(function () {
                applyFilters();
            }, 350);
        });
    }

    // =========================================================
    // STATUS — DO NOT FILTER UNTIL FILTER BUTTON IS CLICKED
    // =========================================================

    if (statusSelect) {
        statusSelect.addEventListener('change', updateClearButton);
    }

    // =========================================================
    // FILTER FORM — AJAX
    // =========================================================

    if (filterForm) {
        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();
            clearTimeout(searchTimer);
            applyFilters();
        });
    }

    // =========================================================
    // RESET FILTERS — AJAX
    // =========================================================

    document.addEventListener('click', function (event) {
        const clearLink = event.target.closest('.category-clear-filter');

        if (!clearLink) return;

        event.preventDefault();
        clearTimeout(searchTimer);

        filterForm?.reset();

        const cleanUrl = new URL(
            filterForm?.action || clearLink.href,
            window.location.origin
        );

        loadCategories(cleanUrl, {
            updateHistory: true,
            scrollToTable: false
        }).catch(() => {});
    });

    // =========================================================
    // PAGINATION — AJAX
    // =========================================================

    document.addEventListener('click', function (event) {
        const pageLink = event.target.closest(
            '.categories-pagination a.categories-pager-btn'
        );

        if (!pageLink) return;

        if (
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        event.preventDefault();

        loadCategories(pageLink.href, {
            updateHistory: true,
            scrollToTable: true
        }).catch(() => {});
    });

    // =========================================================
    // DELETE CATEGORY — AJAX
    // =========================================================

    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.category-delete-form');

        if (!form) return;

        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');
        let confirmed = false;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            confirmed = result.isConfirmed;
        } else {
            confirmed = window.confirm(
                'Are you sure you want to permanently delete this category?'
            );
        }

        if (!confirmed) return;

        if (button) button.disabled = true;

        try {
            if (!csrfToken) {
                throw new Error(
                    'CSRF token not found. Refresh the page and try again.'
                );
            }

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
                }),
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type') || '';
            let data = {};

            if (contentType.includes('application/json')) {
                data = await response.json();
            }

            if (!response.ok || data.success === false) {
                throw new Error(
                    data.message || 'Unable to delete the category.'
                );
            }

            if (
                !contentType.includes('application/json') &&
                !response.redirected
            ) {
                throw new Error(
                    'The server did not confirm that the category was deleted.'
                );
            }

            const currentUrl = new URL(window.location.href);

            const parsedDocument = await loadCategories(currentUrl, {
                updateHistory: false,
                scrollToTable: false
            });

            if (parsedDocument) {
                const currentPage = Number(
                    currentUrl.searchParams.get('page') || 1
                );

                const remainingRows = document.querySelectorAll(
                    '.categories-table tbody tr[data-category-id]'
                );

                if (remainingRows.length === 0 && currentPage > 1) {
                    const previousUrl = new URL(currentUrl);
                    previousUrl.searchParams.set('page', currentPage - 1);

                    await loadCategories(previousUrl, {
                        updateHistory: true,
                        scrollToTable: false
                    });
                }
            }

            notify(
                'success',
                'Category deleted',
                data.message || 'The category has been deleted successfully.'
            );
        } catch (error) {
            notify(
                'error',
                'Delete failed',
                error.message || 'Unable to delete the category.'
            );
        } finally {
            if (button && button.isConnected) {
                button.disabled = false;
            }
        }
    });

    // =========================================================
    // STATUS TOGGLE — AJAX
    // =========================================================

    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.category-status-form');

        if (!form) return;

        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');

        if (!button) return;

        button.disabled = true;

        try {
            if (!csrfToken) {
                throw new Error(
                    'CSRF token not found. Refresh the page and try again.'
                );
            }

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
                    _method: 'PATCH'
                }),
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type') || '';
            let data = {};

            if (contentType.includes('application/json')) {
                data = await response.json();
            }

            if (!response.ok || data.success === false) {
                throw new Error(
                    data.message || 'Unable to update category status.'
                );
            }

            if (
                !contentType.includes('application/json') &&
                !response.redirected
            ) {
                throw new Error(
                    'The server did not confirm the status update.'
                );
            }

            const currentUrl = new URL(window.location.href);

            await loadCategories(currentUrl, {
                updateHistory: false,
                scrollToTable: false
            });

            notify(
                'success',
                'Status updated',
                data.message || 'Category status updated successfully.'
            );
        } catch (error) {
            notify(
                'error',
                'Unable to update status',
                error.message || 'Please try again.'
            );
        } finally {
            if (button.isConnected) {
                button.disabled = false;
            }
        }
    });

    // =========================================================
    // BROWSER BACK / FORWARD — AJAX
    // =========================================================

    window.addEventListener('popstate', function () {
        clearTimeout(searchTimer);

        loadCategories(window.location.href, {
            updateHistory: false,
            scrollToTable: false
        }).catch(() => {});
    });

    updateClearButton();
});
</script>
@endpush