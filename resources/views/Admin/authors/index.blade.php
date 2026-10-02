@extends('layout.admin.master')

@section('title', 'Authors')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/authors.css') }}">
@endpush

@section('content')

<div class="dashboard-section authors-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}

    <section class="authors-hero">

        <div class="authors-hero-content">

            <span class="authors-hero-badge">
                <i class="bi bi-person-lines-fill"></i>
                Author Directory
            </span>

            <h1>Every author, beautifully organised.</h1>

            <p>
                Manage your marketplace authors, book relationships
                and author information from one organised workspace.
            </p>

        </div>

        <div class="authors-hero-mark" aria-hidden="true">
            <i class="bi bi-person-badge"></i>
        </div>

    </section>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <section class="authors-stats">

        {{-- Total Authors --}}
        <div class="author-stat-card stat-blue">

            <div class="author-stat-content">

                <span>Total Authors</span>

                <strong>
                    {{ $authors->total() }}
                </strong>

            </div>

            <div class="author-stat-icon">
                <i class="bi bi-people"></i>
            </div>

        </div>


        {{-- Authors With Books --}}
        <div class="author-stat-card stat-green">

            <div class="author-stat-content">

                <span>With Books</span>

                <strong>
                    {{ \App\Models\Author::has('books')->count() }}
                </strong>

            </div>

            <div class="author-stat-icon">
                <i class="bi bi-book"></i>
            </div>

        </div>


        {{-- Empty Authors --}}
        <div class="author-stat-card stat-orange">

            <div class="author-stat-content">

                <span>No Books</span>

                <strong>
                    {{ \App\Models\Author::doesntHave('books')->count() }}
                </strong>

            </div>

            <div class="author-stat-icon">
                <i class="bi bi-bookmark-x"></i>
            </div>

        </div>


        {{-- Total Books --}}
        <div class="author-stat-card stat-purple">

            <div class="author-stat-content">

                <span>Total Books</span>

                <strong>
                    {{ \App\Models\Book::count() }}
                </strong>

            </div>

            <div class="author-stat-icon">
                <i class="bi bi-collection"></i>
            </div>

        </div>

    </section>


    {{-- =========================================================
        MAIN PANEL
    ========================================================== --}}

    <section class="dashboard-panel authors-panel">

        {{-- =====================================================
            PANEL HEADER
        ====================================================== --}}

        <div class="authors-panel-header">

            <div class="authors-heading-content">

                <span class="eyebrow">
                    Author directory
                </span>

                <h5>
                    All Authors
                </h5>

                <p>
                    Search and manage every author registered on SecondBook.
                </p>

            </div>


            <div class="authors-header-action">

                <a
                    href="{{ route('admin.authors.create') }}"
                    class="authors-add-btn"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Author</span>
                </a>

            </div>

        </div>


        {{-- =====================================================
            FILTERS
        ====================================================== --}}

        <form
            method="GET"
            action="{{ route('admin.authors.index') }}"
            class="author-filters"
        >

            {{-- Search --}}

            <div class="author-filter-group author-filter-search">

                <label for="author-search">
                    Search
                </label>

                <div class="author-search-field">

                    <i class="bi bi-search"></i>

                    <input
                        id="author-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Author name..."
                        autocomplete="off"
                    >

                </div>

            </div>


            {{-- Actions --}}

            <div class="author-filter-actions">

                <button
                    type="submit"
                    class="author-filter-btn"
                >
                    <i class="bi bi-search"></i>
                    <span>Search</span>
                </button>


                @if(request()->has('search'))

                    <a
                        href="{{ route('admin.authors.index') }}"
                        class="author-clear-filter"
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

        <div class="authors-table-wrap">

            <table class="authors-table">

                <thead>

                    <tr>

                        <th class="authors-col-id">
                            #
                        </th>

                        <th class="authors-col-author">
                            Author
                        </th>

                        <th class="authors-col-books">
                            Books
                        </th>

                        <th class="authors-col-created">
                            Created
                        </th>

                        <th class="authors-col-updated">
                            Updated
                        </th>

                        <th class="authors-col-actions">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($authors as $key => $author)

                        @php

                            $booksCount = $author->books_count
                                ?? $author->books()->count();

                            $authorName = $author->name ?? 'Unknown author';

                            $authorInitial = strtoupper(
                                mb_substr($authorName, 0, 1)
                            );

                        @endphp


                        <tr id="author-row-{{ $author->id }}">

                            {{-- ID --}}

                            <td>

                                <span class="author-id">
                                    #{{ $authors->firstItem() + $key }}
                                </span>

                            </td>


                            {{-- AUTHOR --}}

                            <td>

                                <div class="author-item-cell">

                                    <div class="author-avatar">
                                        {{ $authorInitial }}
                                    </div>

                                    <div class="author-item-info">

                                        <strong
                                            title="{{ $authorName }}"
                                        >
                                            {{ $authorName }}
                                        </strong>

                                        <small>
                                            Author #{{ $author->id }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- BOOKS --}}

                            <td>

                                @if($booksCount > 0)

                                    <span class="author-books-pill has-books">

                                        <i class="bi bi-book"></i>

                                        {{ $booksCount }}

                                        {{ $booksCount === 1 ? 'Book' : 'Books' }}

                                    </span>

                                @else

                                    <span class="author-books-pill no-books">

                                        <i class="bi bi-bookmark-x"></i>

                                        No Books

                                    </span>

                                @endif

                            </td>


                            {{-- CREATED --}}

                            <td>

                                <div class="author-date-info">

                                    <strong>
                                        {{ $author->created_at?->format('M d, Y') ?? '—' }}
                                    </strong>

                                    <small>
                                        {{ $author->created_at?->format('H:i') ?? '—' }}
                                    </small>

                                </div>

                            </td>


                            {{-- UPDATED --}}

                            <td>

                                <div class="author-date-info">

                                    <strong>
                                        {{ $author->updated_at?->format('M d, Y') ?? '—' }}
                                    </strong>

                                    <small>
                                        {{ $author->updated_at?->format('H:i') ?? '—' }}
                                    </small>

                                </div>

                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="author-actions">

                                    {{-- View --}}

                                    <a
                                        href="{{ route('admin.authors.show', $author->id) }}"
                                        class="author-action-btn author-view-btn"
                                        title="View author"
                                        aria-label="View author"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Edit --}}

                                    <a
                                        href="{{ route('admin.authors.edit', $author->id) }}"
                                        class="author-action-btn author-edit-btn"
                                        title="Edit author"
                                        aria-label="Edit author"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Delete --}}

                                    <form
                                        action="{{ route('admin.authors.destroy', $author->id) }}"
                                        method="POST"
                                        class="author-delete-form"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="author-action-btn author-delete-btn"
                                            title="Delete author"
                                            aria-label="Delete author"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="authors-empty-state">

                                    <div class="authors-empty-icon">
                                        <i class="bi bi-person-x"></i>
                                    </div>

                                    <strong>
                                        No authors found
                                    </strong>

                                    <span>
                                        Try changing your search or add a new author.
                                    </span>

                                    <a
                                        href="{{ route('admin.authors.create') }}"
                                        class="authors-empty-btn"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Add Author
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

        @if($authors->hasPages())

            @php

                $authors->appends(request()->query());

                $current = $authors->currentPage();

                $last = $authors->lastPage();

                $start = max(1, $current - 2);

                $end = min($last, $current + 2);

            @endphp


            <div class="authors-pagination">

                <div class="authors-pagination-info">

                    Showing

                    <strong>
                        {{ $authors->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $authors->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $authors->total() }}
                    </strong>

                    authors

                </div>


                <nav
                    class="authors-pagination-pages"
                    aria-label="Authors pagination"
                >

                    {{-- Previous --}}

                    @if($authors->onFirstPage())

                        <span
                            class="authors-pager-btn disabled"
                            aria-disabled="true"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $authors->previousPageUrl() }}"
                            class="authors-pager-btn"
                            aria-label="Previous page"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </a>

                    @endif


                    {{-- First --}}

                    @if($start > 1)

                        <a
                            href="{{ $authors->url(1) }}"
                            class="authors-pager-btn"
                        >
                            1
                        </a>

                        @if($start > 2)

                            <span class="authors-pager-dots">
                                …
                            </span>

                        @endif

                    @endif


                    {{-- Page Window --}}

                    @for($page = $start; $page <= $end; $page++)

                        @if($page === $current)

                            <span
                                class="authors-pager-btn active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $authors->url($page) }}"
                                class="authors-pager-btn"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Last --}}

                    @if($end < $last)

                        @if($end < $last - 1)

                            <span class="authors-pager-dots">
                                …
                            </span>

                        @endif

                        <a
                            href="{{ $authors->url($last) }}"
                            class="authors-pager-btn"
                        >
                            {{ $last }}
                        </a>

                    @endif


                    {{-- Next --}}

                    @if($authors->hasMorePages())

                        <a
                            href="{{ $authors->nextPageUrl() }}"
                            class="authors-pager-btn"
                            aria-label="Next page"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    @else

                        <span
                            class="authors-pager-btn disabled"
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
       DELETE AUTHOR — AJAX
    ========================================================== */

    document.querySelectorAll('.author-delete-form').forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const row = form.closest('tr');

            const button = form.querySelector('button');


            const result = await Swal.fire({

                title: 'Are you sure?',

                text: 'This author will be permanently deleted.',

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
                        'Unable to delete the author.'
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

                    title: 'Author deleted',

                    text:
                        data.message ||
                        'The author has been deleted successfully.',

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
                        'Unable to delete the author.',

                    confirmButtonColor: '#2563eb'

                });

            }

        });

    });

});

</script>

@endpush