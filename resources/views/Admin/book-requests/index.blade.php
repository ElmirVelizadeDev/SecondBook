@extends('layout.admin.master')

@section('title', 'Book Requests')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/book-requests.css') }}">
@endpush

@section('content')
<div class="dashboard-section book-request-page">

    {{-- =====================================================
        HERO
    ===================================================== --}}
    <section class="book-request-hero">
        <div class="book-request-hero-content">
            <div class="book-request-hero-text">
                <span class="book-request-hero-badge">
                    <i class="bi bi-inbox"></i>
                    Seller Submissions
                </span>

                <h1>Book Requests, reviewed with care.</h1>

                <p>
                    Manage books submitted by sellers and decide
                    which ones are ready for the SecondBook marketplace.
                </p>
            </div>

            <div class="book-request-hero-mark">
                <i class="bi bi-inbox"></i>
            </div>
        </div>
    </section>

    {{-- =====================================================
        STATS
    ===================================================== --}}
    @php
        $requestCollection = collect($bookRequests);
        $totalRequests = $requestCollection->count();

        $approvedRequests = $requestCollection
            ->filter(fn ($item) => $item->status === 'approved')
            ->count();

        $rejectedRequests = $requestCollection
            ->filter(fn ($item) => $item->status === 'rejected')
            ->count();

        $pendingRequests = $totalRequests - $approvedRequests - $rejectedRequests;
    @endphp

    <section class="book-request-stats">
        {{-- Total --}}
        <div class="book-request-stat-card">
            <div class="book-request-stat-icon stat-blue">
                <i class="bi bi-inbox"></i>
            </div>

            <div class="book-request-stat-content">
                <div class="book-request-stat-label">Total Requests</div>
                <div class="book-request-stat-value">{{ $totalRequests }}</div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="book-request-stat-card">
            <div class="book-request-stat-icon stat-orange">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div class="book-request-stat-content">
                <div class="book-request-stat-label">Pending</div>
                <div class="book-request-stat-value">{{ $pendingRequests }}</div>
            </div>
        </div>

        {{-- Approved --}}
        <div class="book-request-stat-card">
            <div class="book-request-stat-icon stat-green">
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="book-request-stat-content">
                <div class="book-request-stat-label">Approved</div>
                <div class="book-request-stat-value">{{ $approvedRequests }}</div>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="book-request-stat-card">
            <div class="book-request-stat-icon stat-red">
                <i class="bi bi-x-circle"></i>
            </div>

            <div class="book-request-stat-content">
                <div class="book-request-stat-label">Rejected</div>
                <div class="book-request-stat-value">{{ $rejectedRequests }}</div>
            </div>
        </div>
    </section>

    {{-- =====================================================
        MAIN PANEL
    ===================================================== --}}
    <section class="dashboard-panel book-request-panel">

        {{-- HEADER --}}
        <div class="book-request-panel-header">
            <div class="book-request-heading-content">
                <h2 class="book-request-panel-title">
                    Seller Book Requests
                </h2>

                <p class="book-request-panel-description">
                    Books waiting for admin review.
                </p>
            </div>

            <div class="book-request-header-action">
                <span class="book-request-count-badge">
                    <i class="bi bi-inbox"></i>
                    {{ $bookRequests->count() }}
                    {{ $bookRequests->count() === 1 ? 'request' : 'requests' }}
                </span>
            </div>
        </div>

        {{-- FILTERS --}}
        <form
            method="GET"
            action="{{ route('admin.book.requests.index') }}"
            class="book-request-filters"
            autocomplete="off"
        >
            <div class="book-request-filter-grid">

                {{-- Search --}}
                <div class="book-request-filter-group">
                    <label class="book-request-filter-label">
                        Search requests
                    </label>

                    <div class="book-request-search-field">
                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="book-request-input"
                            placeholder="Search book or seller..."
                        >
                    </div>
                </div>

                {{-- Category --}}
                <div class="book-request-filter-group">
                    <label class="book-request-filter-label">
                        Category
                    </label>

                    <select name="category" class="book-request-select">
                        <option value="">All Categories</option>

                        @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected((string) request('category') === (string) $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="book-request-filter-actions">
                    <button type="submit" class="book-request-filter-btn">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>

                    @if(request()->filled('search') || request()->filled('category'))
                        <a
                            href="{{ route('admin.book.requests.index') }}"
                            class="book-request-clear-filter"
                        >
                            <i class="bi bi-x-lg"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="book-request-table-wrap">
            <table class="book-request-table">
                <thead>
                    <tr>
                        <th class="book-request-col-id">ID</th>
                        <th class="book-request-col-book">Book</th>
                        <th class="book-request-col-seller">Seller</th>
                        <th class="book-request-col-category">Category</th>
                        <th class="book-request-col-price">Price</th>
                        <th class="book-request-col-stock">Stock</th>
                        <th class="book-request-col-condition">Condition</th>
                        <th class="book-request-col-status">Status</th>
                        <th class="book-request-col-date">Date</th>
                        <th class="book-request-col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookRequests as $book)
                        <tr>
                            {{-- ID --}}
                            <td>
                                <span class="book-request-id">
                                    #{{ $book->id }}
                                </span>
                            </td>

                            {{-- BOOK --}}
                            <td>
                                <div class="book-request-item-cell">
                                    <div class="book-request-cover">
                                        @if($book->cover)
                                            <img
                                                src="{{ asset('storage/' . $book->cover) }}"
                                                alt="{{ $book->title }}"
                                            >
                                        @else
                                            <i class="bi bi-book"></i>
                                        @endif
                                    </div>

                                    <div class="book-request-item-info">
                                        <span
                                            class="book-request-item-name"
                                            title="{{ $book->title }}"
                                        >
                                            {{ $book->title }}
                                        </span>

                                        @if($book->isbn)
                                            <span class="book-request-item-meta">
                                                ISBN: {{ $book->isbn }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- SELLER --}}
                            <td>
                                @if($book->seller)
                                    <div class="book-request-item-info">
                                        <span class="book-request-item-name">
                                            {{ $book->seller->name }}
                                        </span>

                                        @if($book->seller->email)
                                            <span class="book-request-item-meta">
                                                {{ $book->seller->email }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="book-request-muted">
                                        Unknown seller
                                    </span>
                                @endif
                            </td>

                            {{-- CATEGORY --}}
                            <td>
                                @if($book->category?->name)
                                    <span class="book-request-category">
                                        {{ $book->category->name }}
                                    </span>
                                @else
                                    <span class="book-request-muted">—</span>
                                @endif
                            </td>

                            {{-- PRICE --}}
                            <td>
                                <span class="book-request-price">
                                    ₼{{ number_format((float) $book->price, 2) }}
                                </span>
                            </td>

                            {{-- STOCK --}}
                            <td>
                                <span class="book-request-stock">
                                    <i class="bi bi-stack"></i>
                                    {{ $book->stock }}
                                </span>
                            </td>

                            {{-- CONDITION --}}
                            <td>
                                @php
                                    $condition = match($book->condition) {
                                        'new' => 'New',
                                        'like_new' => 'Like New',
                                        'good' => 'Good',
                                        'fair' => 'Fair',
                                        default => ucfirst(
                                            str_replace('_', ' ', $book->condition ?? '')
                                        ),
                                    };
                                @endphp

                                <span class="book-request-condition">
                                    {{ $condition }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @php
                                    $statusClass = match($book->status) {
                                        'approved' => 'book-request-status-approved',
                                        'rejected' => 'book-request-status-rejected',
                                        default => 'book-request-status-pending',
                                    };
                                @endphp

                                <span class="book-request-status-pill {{ $statusClass }}">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </td>

                            {{-- DATE --}}
                            <td>
                                <span class="book-request-item-meta">
                                    {{ $book->created_at?->format('d.m.Y') }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td>
                                <div class="book-request-actions">

                                    {{-- REVIEW --}}
                                    <a
                                        href="{{ route('admin.book.requests.edit', $book->id) }}"
                                        class="book-request-action-btn book-request-review-btn"
                                        title="Review Request"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('admin.book.requests.destroy', $book->id) }}"
                                        method="POST"
                                        class="book-request-delete-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="book-request-action-btn book-request-delete-btn"
                                            title="Delete Request"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="book-request-empty-state">
                                    <div class="book-request-empty-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>

                                    <h5>No Book Requests</h5>

                                    <p>
                                        There are currently no books waiting for approval.
                                    </p>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', async function (event) {
        const form = event.target;

        if (!form.matches('.book-request-delete-form')) {
            return;
        }

        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');

        if (typeof Swal === 'undefined') {
            if (confirm('Are you sure you want to delete this book request?')) {
                form.submit();
            }

            return;
        }

        const result = await Swal.fire({
            title: 'Delete book request?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-dark'
            }
        });

        if (!result.isConfirmed) {
            return;
        }

        if (button) {
            button.disabled = true;
        }

        form.submit();
    });
});
</script>
@endpush