@extends('layout.admin.master')

@section('title', 'Books')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/books.css') }}">
@endpush

@section('content')

<div class="dashboard-section books-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="books-hero">

        <div class="books-hero-content">

            <span class="books-hero-badge">
                <i class="bi bi-book-half"></i>
                Marketplace Library
            </span>

            <h1>Every book, beautifully organised.</h1>

            <p>
                Manage your marketplace inventory, book details,
                sellers and publication status from one organised workspace.
            </p>

        </div>

        <div class="books-hero-mark" aria-hidden="true">
            <i class="bi bi-book"></i>
        </div>

    </section>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <section class="books-stats">

        {{-- Total --}}
        <div class="book-stat-card stat-blue">

            <div class="book-stat-content">
                <span>Total Books</span>
                <strong>{{ $books->total() }}</strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-collection"></i>
            </div>

        </div>


        {{-- Approved --}}
        <div class="book-stat-card stat-green">

            <div class="book-stat-content">
                <span>Approved</span>
                <strong>
                    {{ \App\Models\Book::where('status', 'approved')->count() }}
                </strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>

        </div>


        {{-- Pending --}}
        <div class="book-stat-card stat-orange">

            <div class="book-stat-content">
                <span>Pending</span>
                <strong>
                    {{ \App\Models\Book::where('status', 'pending')->count() }}
                </strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>

        </div>


        {{-- Out of stock --}}
        <div class="book-stat-card stat-purple">

            <div class="book-stat-content">
                <span>Out of Stock</span>
                <strong>
                    {{ \App\Models\Book::where('stock', '<=', 0)->count() }}
                </strong>
            </div>

            <div class="book-stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>

        </div>

    </section>


    {{-- =========================================================
        MAIN PANEL
    ========================================================== --}}
    <section class="dashboard-panel books-panel">

        {{-- =====================================================
            PANEL HEADER
        ====================================================== --}}
        <div class="books-panel-header">

            <div class="books-heading-content">

                <span class="eyebrow">
                    Book directory
                </span>

                <h5>
                    All Books
                </h5>

                <p>
                    Search, filter and manage every book listed on SecondBook.
                </p>

            </div>


            <div class="books-header-action">

                <a
                    href="{{ route('admin.books.create') }}"
                    class="books-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Book</span>
                </a>

            </div>

        </div>


        {{-- =====================================================
            FILTERS
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.books.index') }}"
            class="book-filters"
        >

            {{-- Search --}}
            <div class="book-filter-group book-filter-search">

                <label for="book-search">
                    Search
                </label>

                <div class="book-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        id="book-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Title, author, seller, ISBN..."
                        autocomplete="off"
                    >

                </div>

            </div>


            {{-- Status --}}
            <div class="book-filter-group">

                <label for="book-status">
                    Status
                </label>

                <select
                    id="book-status"
                    name="status"
                    class="book-filter-select"
                >

                    <option value="">
                        All Status
                    </option>

                    @foreach(['approved', 'pending', 'rejected'] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(request('status') === $status)
                        >
                            {{ ucfirst($status) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Condition --}}
            <div class="book-filter-group">

                <label for="book-condition">
                    Condition
                </label>

                <select
                    id="book-condition"
                    name="condition"
                    class="book-filter-select"
                >

                    <option value="">
                        All Conditions
                    </option>

                    @foreach([
                        'new' => 'New',
                        'like_new' => 'Like New',
                        'good' => 'Good',
                        'fair' => 'Fair'
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            @selected(request('condition') === $value)
                        >
                            {{ $label }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Category --}}
            <div class="book-filter-group">

                <label for="book-category">
                    Category
                </label>

                <select
                    id="book-category"
                    name="category"
                    class="book-filter-select"
                >

                    <option value="">
                        All Categories
                    </option>

                    @isset($categories)

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected((string) request('category') === (string) $category->id)
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    @endisset

                </select>

            </div>


            {{-- Actions --}}
            <div class="book-filter-actions">

                <button
                    type="submit"
                    class="book-filter-btn"
                >
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>


                @if(request()->hasAny([
                    'search',
                    'status',
                    'condition',
                    'category'
                ]))

                    <a
                        href="{{ route('admin.books.index') }}"
                        class="book-clear-filter"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Clear</span>
                    </a>

                @endif

            </div>

        </form>


        {{-- =====================================================
            TABLE
        ====================================================== --}}
        <div class="books-table-wrap">

            <table class="books-table">

                <thead>

                    <tr>

                        <th class="books-col-id">
                            #
                        </th>

                        <th class="books-col-book">
                            Book
                        </th>

                        <th class="books-col-category">
                            Category
                        </th>

                        <th class="books-col-author">
                            Author
                        </th>

                        <th class="books-col-seller">
                            Seller
                        </th>

                        <th class="books-col-price">
                            Price
                        </th>

                        <th class="books-col-condition">
                            Condition
                        </th>

                        <th class="books-col-status">
                            Status
                        </th>

                        <th class="books-col-actions">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($books as $key => $book)

                        @php

                            $condition = strtolower(
                                $book->condition ?? ''
                            );

                            $conditionClass = match($condition) {
                                'new' => 'condition-new',
                                'like_new' => 'condition-like-new',
                                'good' => 'condition-good',
                                'fair' => 'condition-fair',
                                default => 'condition-default',
                            };


                            $status = strtolower(
                                $book->status ?? ''
                            );

                            $statusClass = match($status) {
                                'approved' => 'book-status-approved',
                                'pending' => 'book-status-pending',
                                'rejected' => 'book-status-rejected',
                                default => 'book-status-default',
                            };


                            $sellerName = $book->seller?->name
                                ?? $book->seller?->username
                                ?? 'Unknown seller';


                            $sellerInitial = strtoupper(
                                mb_substr($sellerName, 0, 1)
                            );


                            $coverUrl = null;

                            if (!empty($book->cover)) {

                                $coverUrl = filter_var(
                                    $book->cover,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $book->cover
                                    : asset('storage/' . $book->cover);

                            }

                        @endphp


                        <tr id="book-row-{{ $book->id }}">

                            {{-- =================================================
                                ID
                            ================================================== --}}
                            <td>

                                <span class="book-id">
                                    #{{ $books->firstItem() + $key }}
                                </span>

                            </td>


                            {{-- =================================================
                                BOOK
                            ================================================== --}}
                            <td>

                                <div class="book-item-cell">

                                    @if($coverUrl)

                                        <img
                                            src="{{ $coverUrl }}"
                                            alt="{{ $book->title }}"
                                            class="book-cover-image"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="book-cover-placeholder">
                                            <i class="bi bi-book"></i>
                                        </div>

                                    @endif


                                    <div class="book-item-info">

                                        <strong
                                            title="{{ $book->title }}"
                                        >
                                            {{ $book->title }}
                                        </strong>

                                        @if(!empty($book->isbn))

                                            <small>
                                                ISBN:
                                                {{ $book->isbn }}
                                            </small>

                                        @else

                                            <small>
                                                No ISBN
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                CATEGORY
                            ================================================== --}}
                            <td>

                                <div class="book-simple-info">

                                    <strong>
                                        {{ $book->category?->name ?? '—' }}
                                    </strong>

                                </div>

                            </td>


                            {{-- =================================================
                                AUTHOR
                            ================================================== --}}
                            <td>

                                <div class="book-simple-info">

                                    <strong
                                        title="{{ $book->author?->name ?? '' }}"
                                    >
                                        {{ $book->author?->name ?? '—' }}
                                    </strong>

                                </div>

                            </td>


                            {{-- =================================================
                                SELLER
                            ================================================== --}}
                            <td>

                                <div class="book-seller-cell">

                                    <div class="book-seller-avatar">
                                        {{ $sellerInitial }}
                                    </div>

                                    <div class="book-seller-info">

                                        <strong
                                            title="{{ $sellerName }}"
                                        >
                                            {{ $sellerName }}
                                        </strong>

                                        <small>
                                            Seller
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                PRICE
                            ================================================== --}}
                            <td>

                                <span class="book-price">
                                    ${{ number_format((float) ($book->price ?? 0), 2) }}
                                </span>

                            </td>


                            {{-- =================================================
                                CONDITION
                            ================================================== --}}
                            <td>

                                <span class="book-condition-pill {{ $conditionClass }}">

                                    <i class="bi bi-circle-fill"></i>

                                    {{ $book->condition
                                        ? str_replace(
                                            '_',
                                            ' ',
                                            ucfirst($book->condition)
                                        )
                                        : 'Unknown'
                                    }}

                                </span>

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}
                            <td>

                                <span class="book-status-pill {{ $statusClass }}">

                                    <i class="bi bi-circle-fill"></i>

                                    {{ $book->status
                                        ? ucfirst($book->status)
                                        : 'Unknown'
                                    }}

                                </span>

                            </td>


                            {{-- =================================================
                                ACTIONS
                            ================================================== --}}
                            <td>

                                <div class="book-actions">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.books.show', $book->id) }}"
                                        class="book-action-btn book-view-btn"
                                        title="View book"
                                        aria-label="View book"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.books.edit', $book->id) }}"
                                        class="book-action-btn book-edit-btn"
                                        title="Edit book"
                                        aria-label="Edit book"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.books.destroy', $book->id) }}"
                                        method="POST"
                                        class="book-delete-form"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="book-action-btn book-delete-btn"
                                            title="Delete book"
                                            aria-label="Delete book"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="9">

                                <div class="books-empty-state">

                                    <div class="books-empty-icon">
                                        <i class="bi bi-book"></i>
                                    </div>

                                    <strong>
                                        No books found
                                    </strong>

                                    <span>
                                        Try changing your filters or add a new book.
                                    </span>

                                    <a
                                        href="{{ route('admin.books.create') }}"
                                        class="books-empty-btn"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Add Book
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
        @if($books->hasPages())

            @php

                $books->appends(request()->query());

                $current = $books->currentPage();
                $last    = $books->lastPage();

                $start = max(1, $current - 2);
                $end   = min($last, $current + 2);

            @endphp


            <div class="books-pagination">

                <div class="books-pagination-info">

                    Showing

                    <strong>
                        {{ $books->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $books->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $books->total() }}
                    </strong>

                    books

                </div>


                <nav
                    class="books-pagination-pages"
                    aria-label="Books pagination"
                >

                    {{-- Previous --}}
                    @if($books->onFirstPage())

                        <span
                            class="books-pager-btn disabled"
                            aria-disabled="true"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $books->previousPageUrl() }}"
                            class="books-pager-btn"
                            aria-label="Previous page"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </a>

                    @endif


                    {{-- First --}}
                    @if($start > 1)

                        <a
                            href="{{ $books->url(1) }}"
                            class="books-pager-btn"
                        >
                            1
                        </a>

                        @if($start > 2)

                            <span class="books-pager-dots">
                                …
                            </span>

                        @endif

                    @endif


                    {{-- Page window --}}
                    @for($page = $start; $page <= $end; $page++)

                        @if($page === $current)

                            <span
                                class="books-pager-btn active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $books->url($page) }}"
                                class="books-pager-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Last --}}
                    @if($end < $last)

                        @if($end < $last - 1)

                            <span class="books-pager-dots">
                                …
                            </span>

                        @endif

                        <a
                            href="{{ $books->url($last) }}"
                            class="books-pager-btn"
                        >
                            {{ $last }}
                        </a>

                    @endif


                    {{-- Next --}}
                    @if($books->hasMorePages())

                        <a
                            href="{{ $books->nextPageUrl() }}"
                            class="books-pager-btn"
                            aria-label="Next page"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @else

                        <span
                            class="books-pager-btn disabled"
                            aria-disabled="true"
                        >
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

    /* =========================================================
       DELETE BOOK — AJAX
    ========================================================= */

    document.querySelectorAll('.book-delete-form').forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const row = form.closest('tr');
            const button = form.querySelector('button');

            const result = await Swal.fire({

                title: 'Are you sure?',

                text: 'This book will be permanently deleted.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#dc3545',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Yes, delete it!',

                cancelButtonText: 'Cancel'

            });


            if (!result.isConfirmed) {
                return;
            }


            if (button) {
                button.disabled = true;
            }


            try {

                const csrfElement =
                    document.querySelector('meta[name="csrf-token"]');

                if (!csrfElement) {
                    throw new Error('CSRF token not found.');
                }

                const csrfToken =
                    csrfElement.getAttribute('content');


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
                        'Unable to delete the book.'
                    );

                }


                /* =================================================
                   REMOVE ROW
                ================================================= */

                if (row) {

                    row.style.transition =
                        'opacity 0.3s ease, transform 0.3s ease';

                    row.style.opacity = '0';

                    row.style.transform =
                        'translateX(20px)';


                    setTimeout(function () {

                        row.remove();

                    }, 300);

                }


                /* =================================================
                   SUCCESS
                ================================================= */

                Swal.fire({

                    icon: 'success',

                    title: 'Book deleted',

                    text:
                        data.message ||
                        'The book has been deleted successfully.',

                    timer: 1600,

                    showConfirmButton: false

                });


            } catch (error) {

                if (button) {
                    button.disabled = false;
                }


                Swal.fire({

                    icon: 'error',

                    title: 'Delete failed',

                    text:
                        error.message ||
                        'Unable to delete the book.',

                    confirmButtonColor: '#2563eb'

                });

            }

        });

    });

});

</script>

@endpush