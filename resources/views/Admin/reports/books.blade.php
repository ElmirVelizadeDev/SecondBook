@extends('layout.admin.master')

@section('title', 'Books Report')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/reports.css') }}">
@endpush

@section('content')

<div class="dashboard-section reports-page books-report-page">


{{-- Header --}}
<div class="dashboard-panel reports-header-panel">

    <div class="reports-header-content">

        <div>

            <h5>Books Report</h5>

            <p>
                Monitor books, inventory and sales performance.
            </p>

        </div>

        <div class="reports-header-actions">

            <a
                href="{{ route('admin.reports.index') }}"
                class="reports-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Reports Dashboard
            </a>

        </div>

    </div>

</div>


{{-- Date Filter --}}
<div class="dashboard-panel reports-filter-panel">

    <form
        method="GET"
        action="{{ route('admin.reports.books') }}"
        class="books-filter-form"
    >

        <div class="reports-filter-row">

            <div class="reports-filter-group">

                <label for="start_date">
                    Start Date
                </label>

                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                >

            </div>


            <div class="reports-filter-group">

                <label for="end_date">
                    End Date
                </label>

                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                >

            </div>


            <div class="reports-filter-actions">

                <button
                    type="submit"
                    class="reports-filter-btn"
                >
                    <i class="bi bi-funnel"></i>
                    Apply
                </button>

                <a
                    href="{{ route('admin.reports.books') }}"
                    class="reports-reset-btn"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>


{{-- Statistics --}}
<div class="reports-stats-grid">

    {{-- Total Books --}}
    <div class="reports-stat-card">

        <div class="reports-stat-icon reports-icon-books">
            <i class="bi bi-book"></i>
        </div>

        <div class="reports-stat-content">

            <span>Total Books</span>

            <strong>
                {{ number_format($totalBooks) }}
            </strong>

        </div>

    </div>


    {{-- Active Books --}}
    <div class="reports-stat-card">

        <div class="reports-stat-icon reports-icon-success">
            <i class="bi bi-check-circle"></i>
        </div>

        <div class="reports-stat-content">

            <span>Active Books</span>

            <strong>
                {{ number_format($activeBooks) }}
            </strong>

        </div>

    </div>


    {{-- Low Stock --}}
    <div class="reports-stat-card">

        <div class="reports-stat-icon reports-icon-warning">
            <i class="bi bi-exclamation-triangle"></i>
        </div>

        <div class="reports-stat-content">

            <span>Low Stock</span>

            <strong>
                {{ number_format($lowStock) }}
            </strong>

        </div>

    </div>


    {{-- Out Of Stock --}}
    <div class="reports-stat-card">

        <div class="reports-stat-icon reports-icon-danger">
            <i class="bi bi-box-seam"></i>
        </div>

        <div class="reports-stat-content">

            <span>Out of Stock</span>

            <strong>
                {{ number_format($outOfStock) }}
            </strong>

        </div>

    </div>

</div>


{{-- Sales Summary --}}
<div class="reports-stats-grid">

    <div class="reports-stat-card">

        <div class="reports-stat-icon reports-icon-orders">
            <i class="bi bi-bag-check"></i>
        </div>

        <div class="reports-stat-content">

            <span>Books Sold</span>

            <strong>
                {{ number_format($totalSold) }}
            </strong>

        </div>

    </div>


    <div class="reports-stat-card">

        <div class="reports-stat-icon reports-icon-revenue">
            <i class="bi bi-currency-dollar"></i>
        </div>

        <div class="reports-stat-content">

            <span>Total Revenue</span>

            <strong>
                ${{ number_format($totalRevenue, 2) }}
            </strong>

        </div>

    </div>

</div>


{{-- Books Table --}}
<div class="dashboard-panel reports-panel books-report-panel">

    <div class="reports-panel-header">

        <div>

            <h5>All Books</h5>

            <p>
                Book inventory and sales performance for the selected period.
            </p>

        </div>

    </div>


    <div class="reports-table-wrapper books-report-table-wrapper">

        <table class="reports-table books-report-table">

            <thead>

                <tr>
                    <th>Book</th>
                    <th>Category</th>
                    <th>Seller</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Sold</th>
                    <th>Revenue</th>
                    <th>Status</th>
                </tr>

            </thead>


            <tbody>

                @forelse($books as $book)

                    <tr>

                        <td>

                            <div class="books-report-book-cell">

                                <div class="books-report-cover">

                                    @if($book->cover)

                                        <img
                                            src="{{ asset('storage/' . $book->cover) }}"
                                            alt="{{ $book->title }}"
                                        >

                                    @else

                                        <div class="books-report-cover-placeholder">
                                            <i class="bi bi-book"></i>
                                        </div>

                                    @endif

                                </div>


                                <div class="books-report-book-info">

                                    <strong>
                                        {{ $book->title }}
                                    </strong>

                                    <span>
                                        {{ $book->isbn ?: 'No ISBN' }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        <td>

                            <span class="books-report-secondary-text">
                                {{ $book->category?->name ?? 'Uncategorized' }}
                            </span>

                        </td>


                        <td>

                            <span class="books-report-secondary-text">
                                {{ $book->seller?->name ?? 'Platform' }}
                            </span>

                        </td>


                        <td>

                            <strong class="books-report-price">
                                ${{ number_format($book->price, 2) }}
                            </strong>

                        </td>


                        <td>

                            @if($book->stock <= 0)

                                <span class="reports-stock-badge reports-stock-danger">
                                    Out of stock
                                </span>

                            @elseif($book->stock <= 5)

                                <span class="reports-stock-badge reports-stock-warning">
                                    {{ number_format($book->stock) }} left
                                </span>

                            @else

                                <span class="reports-stock-badge reports-stock-normal">
                                    {{ number_format($book->stock) }}
                                </span>

                            @endif

                        </td>


                        <td>

                            <strong class="books-report-sold">
                                {{ number_format($book->sold_count ?? 0) }}
                            </strong>

                        </td>


                        <td>

                            <strong class="books-report-revenue">
                                ${{ number_format($book->revenue ?? 0, 2) }}
                            </strong>

                        </td>


                        <td>

                            @if($book->status === 'approved')

                                <span class="reports-stock-badge reports-stock-normal">
                                    Approved
                                </span>

                            @elseif($book->status === 'pending')

                                <span class="reports-stock-badge reports-stock-warning">
                                    Pending
                                </span>

                            @elseif($book->status === 'rejected')

                                <span class="reports-stock-badge reports-stock-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="reports-stock-badge reports-stock-normal">
                                    {{ ucfirst($book->status) }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">

                            <div class="reports-table-empty">
                                No books found.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($books->hasPages())

        <div class="reports-pagination">

            {{ $books->links() }}

        </div>

    @endif

</div>


</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const page = document.querySelector('.books-report-page');

    if (!page) {
        return;
    }

    function loadBooksReport(url, pushState = true) {

        page.classList.add('is-loading');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Request failed');
            }

            return response.text();
        })
        .then(html => {

            const parser = new DOMParser();
            const documentHtml = parser.parseFromString(html, 'text/html');
            const newPage = documentHtml.querySelector('.books-report-page');

            if (!newPage) {
                window.location.href = url;
                return;
            }

            page.innerHTML = newPage.innerHTML;

            if (pushState) {
                window.history.pushState({}, '', url);
            }

            page.classList.remove('is-loading');

        })
        .catch(() => {

            page.classList.remove('is-loading');
            window.location.href = url;

        });

    }


    document.addEventListener('submit', function (event) {

        const form = event.target.closest('.books-filter-form');

        if (!form) {
            return;
        }

        event.preventDefault();

        const formData = new FormData(form);
        const params = new URLSearchParams();

        formData.forEach((value, key) => {

            if (value !== '') {
                params.append(key, value);
            }

        });

        const url = form.action + (
            params.toString()
                ? '?' + params.toString()
                : ''
        );

        loadBooksReport(url);

    });


    document.addEventListener('click', function (event) {

        const resetButton = event.target.closest('.reports-reset-btn');

        if (resetButton) {

            event.preventDefault();

            loadBooksReport(resetButton.href);

            return;
        }


        const paginationLink = event.target.closest(
            '.reports-pagination a'
        );

        if (paginationLink) {

            event.preventDefault();

            loadBooksReport(paginationLink.href);

        }

    });


    window.addEventListener('popstate', function () {

        loadBooksReport(window.location.href, false);

    });

});
</script>

@endpush
