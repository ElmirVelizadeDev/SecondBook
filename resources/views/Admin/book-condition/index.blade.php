@extends('layout.admin.master')

@section('title', 'Book Conditions')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/book-condition.css') }}">
@endpush

@section('content')
<div class="dashboard-section book-condition-page">

    {{-- =====================================================
        HERO
    ===================================================== --}}
    <section class="book-condition-hero">
        <div class="book-condition-hero-content">
            <div class="book-condition-hero-text">
                <span class="book-condition-hero-badge">
                    <i class="bi bi-bookmark-check"></i>
                    Book Management
                </span>

                <h1>Book Conditions, clearly organised.</h1>

                <p>
                    Manage the condition types available for books
                    throughout the SecondBook marketplace.
                </p>
            </div>

            <div class="book-condition-hero-mark">
                <i class="bi bi-bookmark-check"></i>
            </div>
        </div>
    </section>

    {{-- =====================================================
        STATS
    ===================================================== --}}
    @php
        $conditionCollection = collect($conditions);

        $totalConditions = $conditionCollection->count();

        $activeConditions = $conditionCollection
            ->filter(fn ($condition) => (int) $condition['status'] === 1)
            ->count();

        $inactiveConditions = $conditionCollection
            ->filter(fn ($condition) => (int) $condition['status'] === 0)
            ->count();

        $booksUsingConditions = $conditionCollection
            ->sum(fn ($condition) => (int) ($condition['books_count'] ?? 0));
    @endphp

    <section class="book-condition-stats">
        <div class="book-condition-stat-card">
            <div class="book-condition-stat-icon stat-blue">
                <i class="bi bi-bookmark-check"></i>
            </div>
            <div class="book-condition-stat-content">
                <div class="book-condition-stat-label">Total Conditions</div>
                <div class="book-condition-stat-value">{{ $totalConditions }}</div>
            </div>
        </div>

        <div class="book-condition-stat-card">
            <div class="book-condition-stat-icon stat-green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="book-condition-stat-content">
                <div class="book-condition-stat-label">Active</div>
                <div class="book-condition-stat-value">{{ $activeConditions }}</div>
            </div>
        </div>

        <div class="book-condition-stat-card">
            <div class="book-condition-stat-icon stat-orange">
                <i class="bi bi-pause-circle"></i>
            </div>
            <div class="book-condition-stat-content">
                <div class="book-condition-stat-label">Inactive</div>
                <div class="book-condition-stat-value">{{ $inactiveConditions }}</div>
            </div>
        </div>

        <div class="book-condition-stat-card">
            <div class="book-condition-stat-icon stat-purple">
                <i class="bi bi-book"></i>
            </div>
            <div class="book-condition-stat-content">
                <div class="book-condition-stat-label">Books Using Conditions</div>
                <div class="book-condition-stat-value">{{ $booksUsingConditions }}</div>
            </div>
        </div>
    </section>

    {{-- =====================================================
        MAIN PANEL
    ===================================================== --}}
    <section class="dashboard-panel book-condition-panel">

        {{-- HEADER --}}
        <div class="book-condition-panel-header">
            <div class="book-condition-heading-content">
                <h2 class="book-condition-panel-title">
                    Condition directory
                </h2>

                <p class="book-condition-panel-description">
                    Browse and manage all book conditions registered
                    on SecondBook.
                </p>
            </div>

            <div class="book-condition-header-action">
                <a
                    href="{{ route('admin.book.conditions.create') }}"
                    class="book-condition-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    Add Condition
                </a>
            </div>
        </div>

        {{-- FILTERS --}}
        <form
            method="GET"
            action="{{ route('admin.book.conditions.index') }}"
            class="book-condition-filters"
            autocomplete="off"
        >
            <div class="book-condition-filter-grid">

                {{-- Search --}}
                <div class="book-condition-filter-group">
                    <label
                        class="book-condition-filter-label"
                        for="book-condition-search"
                    >
                        Search conditions
                    </label>

                    <div class="book-condition-search-field">
                        <i class="bi bi-search"></i>

                        <input
                            id="book-condition-search"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="book-condition-input"
                            placeholder="Search by condition name..."
                        >
                    </div>
                </div>

                {{-- Status --}}
                <div class="book-condition-filter-group">
                    <label
                        class="book-condition-filter-label"
                        for="book-condition-status"
                    >
                        Status
                    </label>

                    <select
                        id="book-condition-status"
                        name="status"
                        class="book-condition-select"
                    >
                        <option value="">All Status</option>

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

                {{-- Actions --}}
                <div class="book-condition-filter-actions">
                    <button
                        type="submit"
                        class="book-condition-filter-btn"
                    >
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>

                    @if(request()->filled('search') || request()->filled('status'))
                        <a
                            href="{{ route('admin.book.conditions.index') }}"
                            class="book-condition-clear-filter"
                        >
                            <i class="bi bi-x-lg"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- MESSAGES --}}
        @if(session('success'))
            <div class="book-condition-message-wrap">
                <div class="alert alert-success mb-0">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="book-condition-message-wrap">
                <div class="alert alert-danger mb-0">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="book-condition-table-wrap">
            <table class="book-condition-table">
                <thead>
                    <tr>
                        <th class="book-condition-col-id">#</th>
                        <th class="book-condition-col-condition">Condition</th>
                        <th class="book-condition-col-description">Description</th>
                        <th class="book-condition-col-books">Books</th>
                        <th class="book-condition-col-status">Status</th>
                        <th class="book-condition-col-date">Created</th>
                        <th class="book-condition-col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($conditions as $condition)
                        <tr id="book-condition-row-{{ $condition['id'] }}">

                            {{-- ID --}}
                            <td>
                                <span class="book-condition-id">
                                    #{{ $condition['id'] }}
                                </span>
                            </td>

                            {{-- Condition --}}
                            <td>
                                <div class="book-condition-item-cell">
                                    <div class="book-condition-item-icon">
                                        <i class="bi bi-bookmark-check"></i>
                                    </div>

                                    <div class="book-condition-item-info">
                                        <span class="book-condition-item-name">
                                            {{ $condition['name'] }}
                                        </span>

                                        <span class="book-condition-item-meta">
                                            Condition #{{ $condition['id'] }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Description --}}
                            <td>
                                @if(!empty($condition['description']))
                                    <span
                                        class="book-condition-description"
                                        title="{{ $condition['description'] }}"
                                    >
                                        {{ $condition['description'] }}
                                    </span>
                                @else
                                    <span class="book-condition-muted">—</span>
                                @endif
                            </td>

                            {{-- Books Count --}}
                            <td>
                                <span class="book-condition-books-count">
                                    <i class="bi bi-book"></i>
                                    {{ $condition['books_count'] ?? 0 }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if((int) $condition['status'] === 1)
                                    <span class="book-condition-status-pill book-condition-status-active">
                                        Active
                                    </span>
                                @else
                                    <span class="book-condition-status-pill book-condition-status-inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Created --}}
                            <td>
                                <span class="book-condition-item-meta">
                                    {{ $condition['created_at'] ?? '—' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="book-condition-actions">

                                    {{-- Status --}}
                                    <form
                                        action="{{ route('admin.book.conditions.status', ['condition' => $condition['id']]) }}"
                                        method="POST"
                                        class="book-condition-status-form"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="book-condition-action-btn book-condition-status-btn {{ (int) $condition['status'] === 1 ? 'deactivate' : 'activate' }}"
                                            title="{{ (int) $condition['status'] === 1 ? 'Deactivate' : 'Activate' }}"
                                        >
                                            <i class="bi {{ (int) $condition['status'] === 1 ? 'bi-pause-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.book.conditions.edit', ['condition' => $condition['id']]) }}"
                                        class="book-condition-action-btn book-condition-edit-btn"
                                        title="Edit Condition"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.book.conditions.destroy', ['condition' => $condition['id']]) }}"
                                        method="POST"
                                        class="book-condition-delete-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="book-condition-action-btn book-condition-delete-btn"
                                            title="Delete Condition"
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
                                <div class="book-condition-empty-state">
                                    <div class="book-condition-empty-icon">
                                        <i class="bi bi-bookmark-check"></i>
                                    </div>

                                    <h5>No book conditions found</h5>

                                    <p>
                                        There are no book conditions matching
                                        your current filters.
                                    </p>

                                    <a
                                        href="{{ route('admin.book.conditions.create') }}"
                                        class="book-condition-add-btn mt-4"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Add Condition
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.book-condition-page');

    if (!page) {
        return;
    }

    const indexUrl = @json(route('admin.book.conditions.index'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let activeController = null;

    /**
     * Update the page sections from the server-rendered HTML.
     */
    function updatePage(html) {
        const parsedDocument = new DOMParser().parseFromString(html, 'text/html');

        const selectors = [
            '.book-condition-stats',
            '.book-condition-filters',
            '.book-condition-table-wrap'
        ];

        selectors.forEach(function (selector) {
            const currentElement = page.querySelector(selector);
            const newElement = parsedDocument.querySelector(selector);

            if (currentElement && newElement) {
                currentElement.replaceWith(newElement);
            } else if (currentElement && !newElement) {
                currentElement.remove();
            } else if (!currentElement && newElement) {
                const panel = page.querySelector('.book-condition-panel');

                if (selector === '.book-condition-stats') {
                    page.querySelector('.book-condition-hero')?.after(newElement);
                } else if (selector === '.book-condition-filters') {
                    panel?.querySelector('.book-condition-panel-header')?.after(newElement);
                } else if (selector === '.book-condition-table-wrap') {
                    panel?.append(newElement);
                }
            }
        });

        const currentMessages = page.querySelectorAll('.book-condition-message-wrap');

        currentMessages.forEach(function (message) {
            message.remove();
        });

        const newPanel = parsedDocument.querySelector('.book-condition-panel');

        if (newPanel) {
            const newMessages = newPanel.querySelectorAll('.book-condition-message-wrap');
            const filters = page.querySelector('.book-condition-filters');

            newMessages.forEach(function (message) {
                if (filters) {
                    filters.after(message.cloneNode(true));
                }
            });
        }
    }

    /**
     * Fetch and render filtered conditions.
     */
    async function loadConditions(url, options = {}) {
        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();
        page.classList.add('book-condition-ajax-loading');

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: activeController.signal
            });

            if (!response.ok) {
                throw new Error('Unable to load book conditions.');
            }

            const html = await response.text();

            updatePage(html);

            if (options.pushState) {
                window.history.pushState({}, '', url);
            }

            return true;
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Book conditions AJAX error:', error);

                if (options.fallback !== false) {
                    window.location.href = url;
                }
            }

            return false;
        } finally {
            page.classList.remove('book-condition-ajax-loading');
        }
    }

    /**
     * Build the URL from the current filter values.
     * Search runs only after form submission.
     */
    function getFilterUrl() {
        const form = page.querySelector('.book-condition-filters');
        const url = new URL(indexUrl, window.location.origin);

        if (!form) {
            return url.toString();
        }

        const search = form.querySelector('[name="search"]')?.value.trim() || '';
        const status = form.querySelector('[name="status"]')?.value || '';

        if (search) {
            url.searchParams.set('search', search);
        }

        if (status) {
            url.searchParams.set('status', status);
        }

        return url.toString();
    }

    /**
     * Filter only on button click or Enter.
     */
    document.addEventListener('submit', function (event) {
        const form = event.target;

        if (!form.matches('.book-condition-filters')) {
            return;
        }

        event.preventDefault();

        loadConditions(getFilterUrl(), {
            pushState: true
        });
    });

    /**
     * Reset filters and handle pagination.
     */
    document.addEventListener('click', function (event) {
        const resetLink = event.target.closest('.book-condition-clear-filter');

        if (resetLink) {
            event.preventDefault();

            loadConditions(resetLink.href, {
                pushState: true
            });

            return;
        }

        const paginationLink = event.target.closest(
            '.book-condition-pagination a, .book-condition-pagination-pages a'
        );

        if (paginationLink) {
            event.preventDefault();

            loadConditions(paginationLink.href, {
                pushState: true
            });
        }
    });

    /**
     * Handle status changes and deletes.
     */
    document.addEventListener('submit', async function (event) {
        const form = event.target;

        const isDeleteForm = form.matches('.book-condition-delete-form');
        const isStatusForm = form.matches('.book-condition-status-form');

        if (!isDeleteForm && !isStatusForm) {
            return;
        }

        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: isDeleteForm
                    ? 'Delete book condition?'
                    : `${button?.title || 'Change status'} condition?`,
                text: isDeleteForm
                    ? 'This action cannot be undone.'
                    : 'Do you want to change this condition’s status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: isDeleteForm ? 'Yes, delete it' : 'Continue',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-dark'
                }
            });

            if (!result.isConfirmed) {
                return;
            }
        } else if (isDeleteForm) {
            if (!confirm('This action cannot be undone. Delete condition?')) {
                return;
            }
        } else if (isStatusForm) {
            if (!confirm('Change this condition’s status?')) {
                return;
            }
        }

        if (button) {
            button.disabled = true;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            const contentType = response.headers.get('content-type') || '';
            let data = {};

            if (contentType.includes('application/json')) {
                data = await response.json();
            }

            if (!response.ok || data.success === false) {
                throw new Error(
                    data.message ||
                    (isDeleteForm
                        ? 'Unable to delete the condition.'
                        : 'Unable to change condition status.')
                );
            }

            const refreshed = await loadConditions(window.location.href, {
                pushState: false,
                fallback: false
            });

            if (!refreshed) {
                window.location.reload();
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: isDeleteForm ? 'Condition deleted' : 'Status updated',
                    text: data.message || (
                        isDeleteForm
                            ? 'The condition has been deleted successfully.'
                            : 'Condition status has been updated successfully.'
                    ),
                    timer: 1600,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            if (button) {
                button.disabled = false;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Action failed',
                    text: error.message || 'Something went wrong.',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                alert(error.message || 'Something went wrong.');
            }
        }
    });

    /**
     * Browser back/forward navigation.
     */
    window.addEventListener('popstate', function () {
        loadConditions(window.location.href, {
            pushState: false
        });
    });
});
</script>
@endpush
