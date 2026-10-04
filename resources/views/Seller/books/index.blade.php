@extends('Layout.Seller.master')

@section('title', 'My Books')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/books.css') }}">
@endpush

@section('content')

@php
    $hasFilters =
        request()->filled('search') ||
        request()->filled('status') ||
        request()->filled('condition');
@endphp

<div class="seller-books-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="seller-page-heading">

        <div>
            <h1>My Books</h1>
            <p>Manage the books available in your store.</p>
        </div>

        <a
            href="{{ route('seller.books.create') }}"
            class="seller-primary-button"
        >
            <i class="bi bi-plus-lg"></i>
            <span>Add New Book</span>
        </a>

    </div>

    {{-- =====================================================
        STATISTICS
    ====================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="seller-book-stat-card">

                <div class="seller-book-stat-icon">
                    <i class="bi bi-book"></i>
                </div>

                <div>
                    <span>Total Books</span>

                    <h3 id="totalBooksCount">
                        {{ $totalBooks }}
                    </h3>
                </div>

            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="seller-book-stat-card">

                <div class="seller-book-stat-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <span>Approved</span>
                    <h3>{{ $approvedBooks }}</h3>
                </div>

            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="seller-book-stat-card">

                <div class="seller-book-stat-icon pending">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <span>Pending</span>
                    <h3>{{ $pendingBooks }}</h3>
                </div>

            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="seller-book-stat-card">

                <div class="seller-book-stat-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div>
                    <span>Rejected</span>
                    <h3>{{ $rejectedBooks }}</h3>
                </div>

            </div>
        </div>

    </div>

    {{-- =====================================================
        BOOKS PANEL
    ====================================================== --}}

    <div class="seller-books-panel">

        {{-- =================================================
            FILTERS
        ================================================== --}}

        <div class="seller-books-filter">

            <form
                method="GET"
                action="{{ route('seller.books.index') }}"
            >

                <div class="seller-search-group">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search books..."
                    >

                </div>

                <select
                    name="status"
                    class="seller-filter-select"
                >

                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="approved"
                        {{ request('status') === 'approved' ? 'selected' : '' }}
                    >
                        Approved
                    </option>

                    <option
                        value="pending"
                        {{ request('status') === 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="rejected"
                        {{ request('status') === 'rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                </select>

                <select
                    name="condition"
                    class="seller-filter-select"
                >

                    <option value="">
                        All Conditions
                    </option>

                    <option
                        value="new"
                        {{ request('condition') === 'new' ? 'selected' : '' }}
                    >
                        New
                    </option>

                    <option
                        value="like_new"
                        {{ request('condition') === 'like_new' ? 'selected' : '' }}
                    >
                        Like New
                    </option>

                    <option
                        value="good"
                        {{ request('condition') === 'good' ? 'selected' : '' }}
                    >
                        Good
                    </option>

                    <option
                        value="fair"
                        {{ request('condition') === 'fair' ? 'selected' : '' }}
                    >
                        Fair
                    </option>

                </select>

                <button
                    type="submit"
                    class="seller-filter-button"
                >
                    <i class="bi bi-search"></i>
                    <span>Search</span>
                </button>

                @if($hasFilters)

                    <a
                        href="{{ route('seller.books.index') }}"
                        class="seller-reset-button"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Reset</span>
                    </a>

                @endif

            </form>

        </div>

        {{-- =================================================
            BOOKS CONTENT
        ================================================== --}}

        <div id="sellerBooksContent">

            @if($books->count())

                <div class="table-responsive">

                    <table class="table seller-books-table align-middle mb-0">

                        <thead>

                            <tr>
                                <th>Book</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Condition</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($books as $book)

                                <tr id="book-row-{{ $book->id }}">

                                    {{-- Book --}}

                                    <td>

                                        <div class="seller-book-info">

                                            <div class="seller-book-cover">

                                                @if($book->cover)

                                                    <img
                                                        src="{{ asset('storage/' . $book->cover) }}"
                                                        alt="{{ $book->title }}"
                                                    >

                                                @else

                                                    <i class="bi bi-book"></i>

                                                @endif

                                            </div>

                                            <div>

                                                <strong>
                                                    {{ $book->title }}
                                                </strong>

                                                @if($book->isbn)

                                                    <span>
                                                        ISBN: {{ $book->isbn }}
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Category --}}

                                    <td>

                                        <span class="seller-category-text">
                                            {{ $book->category?->name ?? '—' }}
                                        </span>

                                    </td>

                                    {{-- Price --}}

                                    <td>

                                        <strong class="seller-price-text">
                                            ${{ number_format($book->price, 2) }}
                                        </strong>

                                    </td>

                                    {{-- Stock --}}

                                    <td>

                                        <span
                                            class="seller-stock {{ $book->stock <= 3 ? 'is-low' : '' }}"
                                        >
                                            {{ $book->stock }}
                                        </span>

                                    </td>

                                    {{-- Condition --}}

                                    <td>

                                        <span class="seller-condition">
                                            {{ ucwords(str_replace('_', ' ', $book->condition)) }}
                                        </span>

                                    </td>

                                    {{-- Status --}}

                                    <td>

                                        <span class="seller-book-status {{ $book->status }}">

                                            <i></i>

                                            {{ ucfirst($book->status) }}

                                        </span>

                                    </td>

                                    {{-- Actions --}}

                                    <td>

                                        <div class="seller-book-actions">

                                            <a
                                                href="{{ route('seller.books.show', $book) }}"
                                                class="is-view"
                                                title="View"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a
                                                href="{{ route('seller.books.edit', $book) }}"
                                                class="is-edit"
                                                title="Edit"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <button
                                                type="button"
                                                class="seller-delete-button"
                                                title="Delete"
                                                data-delete-book="{{ $book->id }}"
                                                data-book-title="{{ $book->title }}"
                                                data-delete-url="{{ route('seller.books.destroy', $book) }}"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}

                @if($books->hasPages())

                    <div class="seller-books-pagination">

                        {{ $books->links() }}

                    </div>

                @endif

            @else

                <div class="seller-books-empty">

                    <div class="seller-books-empty-icon">
                        <i class="bi bi-book"></i>
                    </div>

                    <h5>No Books Found</h5>

                    <p>
                        You don't have any books matching your search.
                    </p>

                    <a
                        href="{{ route('seller.books.create') }}"
                        class="seller-primary-button"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Add Your First Book</span>
                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- =====================================================
        EMPTY STATE TEMPLATE
    ====================================================== --}}

    <template id="booksEmptyTemplate">

        <div class="seller-books-empty">

            <div class="seller-books-empty-icon">
                <i class="bi bi-book"></i>
            </div>

            <h5>No Books Found</h5>

            <p>
                You don't have any books matching your search.
            </p>

            <a
                href="{{ route('seller.books.create') }}"
                class="seller-primary-button"
            >
                <i class="bi bi-plus-lg"></i>
                <span>Add Your First Book</span>
            </a>

        </div>

    </template>

    {{-- =====================================================
        DELETE FORM
    ====================================================== --}}

    <form
        id="deleteBookForm"
        method="POST"
        hidden
    >
        @csrf
        @method('DELETE')
    </form>

    {{-- =====================================================
        DELETE CONFIRMATION MODAL
    ====================================================== --}}

    <div
        class="seller-modal-overlay"
        id="deleteBookModal"
    >

        <div class="seller-delete-modal">

            <button
                type="button"
                class="seller-modal-close"
                id="closeDeleteModal"
                aria-label="Close"
            >
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="seller-delete-icon">
                <i class="bi bi-trash3"></i>
            </div>

            <h4>Delete Book?</h4>

            <p>
                Are you sure you want to delete
                <strong id="deleteBookTitle"></strong>?
                <br>
                This action cannot be undone.
            </p>

            <div class="seller-delete-actions">

                <button
                    type="button"
                    class="seller-modal-cancel"
                    id="cancelDelete"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="seller-modal-delete"
                    id="confirmDelete"
                >
                    <i class="bi bi-trash3"></i>
                    <span>Delete Book</span>
                </button>

            </div>

        </div>

    </div>

    {{-- =====================================================
        TOAST
    ====================================================== --}}

    <div
        class="seller-success-alert"
        id="sellerSuccessAlert"
        hidden
    >

        <div class="seller-success-icon">

            <i
                class="bi bi-check-lg"
                id="sellerAlertIcon"
            ></i>

        </div>

        <div class="seller-success-content">

            <strong id="sellerAlertTitle">
                Success
            </strong>

            <span id="sellerSuccessMessage">
                Book deleted successfully.
            </span>

        </div>

        <button
            type="button"
            class="seller-success-close"
            id="closeSuccessAlert"
            aria-label="Close"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>

</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const $ = id => document.getElementById(id);

    const modal = $('deleteBookModal');
    const deleteForm = $('deleteBookForm');
    const deleteTitle = $('deleteBookTitle');
    const confirmBtn = $('confirmDelete');
    const toast = $('sellerSuccessAlert');
    const booksContent = $('sellerBooksContent');

    let selected = null;
    let paginationLoading = false;

    /* =====================================================
       Toast
    ====================================================== */

    function showToast(message, type = 'success') {

        if (!toast) {
            return;
        }

        const isError = type === 'error';

        toast.classList.toggle('is-error', isError);

        $('sellerAlertTitle').textContent =
            isError ? 'Error' : 'Success';

        $('sellerAlertIcon').className =
            isError
                ? 'bi bi-exclamation-lg'
                : 'bi bi-check-lg';

        $('sellerSuccessMessage').textContent = message;

        toast.hidden = false;

        clearTimeout(toast.hideTimer);

        toast.hideTimer = setTimeout(() => {
            toast.hidden = true;
        }, 4000);
    }

    /* =====================================================
       Server Success Message
    ====================================================== */

    @if(session('success'))

        showToast(
            @json(session('success')),
            'success'
        );

    @endif

    /* =====================================================
       Close Toast
    ====================================================== */

    $('closeSuccessAlert').addEventListener('click', () => {

        toast.hidden = true;

    });

    /* =====================================================
       Modal
    ====================================================== */

    function hideModal() {

        modal.classList.remove('show');

        selected = null;

    }

    function openDeleteModal(button) {

        const id = button.dataset.deleteBook;

        selected = {
            id: id,
            row: document.getElementById('book-row-' + id),
            url: button.dataset.deleteUrl
        };

        deleteTitle.textContent =
            button.dataset.bookTitle;

        deleteForm.action =
            selected.url;

        modal.classList.add('show');
    }

    /* =====================================================
       Delete Button
       Event Delegation
    ====================================================== */

    document.addEventListener('click', function (event) {

        const deleteButton =
            event.target.closest('[data-delete-book]');

        if (!deleteButton) {
            return;
        }

        event.preventDefault();

        openDeleteModal(deleteButton);

    });

    $('closeDeleteModal').addEventListener(
        'click',
        hideModal
    );

    $('cancelDelete').addEventListener(
        'click',
        hideModal
    );

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            hideModal();
        }

    });

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            hideModal();
        }

    });

    /* =====================================================
       Statistics
    ====================================================== */

    function decrement(el) {

        if (!el) {
            return;
        }

        el.textContent = Math.max(
            0,
            (parseInt(el.textContent) || 0) - 1
        );
    }

    /* =====================================================
       Delete - AJAX
    ====================================================== */

    confirmBtn.addEventListener(
        'click',
        async function () {

            if (!selected) {
                return;
            }

            const row = selected.row;
            const url = selected.url;

            if (!row) {
                return;
            }

            const original =
                confirmBtn.innerHTML;

            confirmBtn.disabled = true;

            confirmBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';

            try {

                const response = await fetch(url, {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                            deleteForm.querySelector(
                                'input[name="_token"]'
                            ).value,

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'

                    },

                    body:
                        new FormData(deleteForm)

                });

                const data =
                    await response.json();

                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message ||
                        'Unable to delete the book.'
                    );

                }

                hideModal();

                const statusEl =
                    row.querySelector(
                        '.seller-book-status'
                    );

                const status = statusEl
                    ? statusEl.textContent
                        .trim()
                        .toLowerCase()
                    : null;

                row.classList.add(
                    'is-removing'
                );

                setTimeout(function () {

                    row.remove();

                    decrement(
                        $('totalBooksCount')
                    );

                    if (status) {

                        const icon =
                            document.querySelector(
                                '.seller-book-stat-icon.' +
                                status
                            );

                        if (icon) {

                            decrement(
                                icon
                                    .closest(
                                        '.seller-book-stat-card'
                                    )
                                    .querySelector('h3')
                            );

                        }

                    }

                    const tbody =
                        document.querySelector(
                            '.seller-books-table tbody'
                        );

                    if (
                        tbody &&
                        tbody.querySelectorAll('tr').length === 0
                    ) {

                        const tableWrap =
                            document.querySelector(
                                '.seller-books-page .table-responsive'
                            );

                        const pagination =
                            document.querySelector(
                                '.seller-books-pagination'
                            );

                        if (tableWrap) {
                            tableWrap.remove();
                        }

                        if (pagination) {
                            pagination.remove();
                        }

                        const panel =
                            document.querySelector(
                                '.seller-books-panel'
                            );

                        panel.appendChild(
                            $('booksEmptyTemplate')
                                .content
                                .cloneNode(true)
                        );

                    }

                }, 280);

                showToast(
                    data.message ||
                    'Book deleted successfully.',
                    'success'
                );

            } catch (error) {

                console.error(
                    'Delete book error:',
                    error
                );

                hideModal();

                showToast(
                    error.message ||
                    'Something went wrong while deleting the book.',
                    'error'
                );

            } finally {

                confirmBtn.disabled = false;

                confirmBtn.innerHTML =
                    original;

            }

        }
    );

    /* =====================================================
       AJAX Pagination
    ====================================================== */

    async function loadBooksPage(url, pushState = true) {

        if (paginationLoading) {
            return;
        }

        paginationLoading = true;

        booksContent.classList.add(
            'is-loading'
        );

        try {

            const response = await fetch(url, {

                method: 'GET',

                headers: {

                    'Accept':
                        'text/html',

                    'X-Requested-With':
                        'XMLHttpRequest'

                }

            });

            if (!response.ok) {

                throw new Error(
                    'Unable to load the selected page.'
                );

            }

            const html =
                await response.text();

            const parser =
                new DOMParser();

            const documentPage =
                parser.parseFromString(
                    html,
                    'text/html'
                );

            const newContent =
                documentPage.querySelector(
                    '#sellerBooksContent'
                );

            if (!newContent) {

                throw new Error(
                    'Books content could not be loaded.'
                );

            }

            booksContent.innerHTML =
                newContent.innerHTML;

            if (pushState) {

                window.history.pushState(
                    {
                        url: url
                    },
                    '',
                    url
                );

            }

            window.scrollTo({
                top:
                    booksContent.getBoundingClientRect().top +
                    window.scrollY -
                    120,
                behavior: 'smooth'
            });

        } catch (error) {

            console.error(
                'Pagination error:',
                error
            );

            showToast(
                error.message ||
                'Unable to load books.',
                'error'
            );

        } finally {

            paginationLoading = false;

            booksContent.classList.remove(
                'is-loading'
            );

        }

    }

    /* =====================================================
       Pagination Click
    ====================================================== */

    document.addEventListener('click', function (event) {

        const link =
            event.target.closest(
                '.seller-books-pagination a'
            );

        if (!link) {
            return;
        }

        event.preventDefault();

        const url =
            link.href;

        if (!url) {
            return;
        }

        loadBooksPage(url);

    });

    /* =====================================================
       Browser Back / Forward
    ====================================================== */

    window.addEventListener(
        'popstate',
        function () {

            loadBooksPage(
                window.location.href,
                false
            );

        }
    );

});
</script>

@endpush