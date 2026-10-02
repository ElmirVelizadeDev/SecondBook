@extends('layout.admin.master')

@section('title', 'Book Conditions')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/book-conditions.css') }}">
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

    {{-- Total --}}
    <div class="book-condition-stat-card">

        <div class="book-condition-stat-icon stat-blue">
            <i class="bi bi-bookmark-check"></i>
        </div>

        <div class="book-condition-stat-content">

            <div class="book-condition-stat-label">
                Total Conditions
            </div>

            <div class="book-condition-stat-value">
                {{ $totalConditions }}
            </div>

        </div>

    </div>


    {{-- Active --}}
    <div class="book-condition-stat-card">

        <div class="book-condition-stat-icon stat-green">
            <i class="bi bi-check-circle"></i>
        </div>

        <div class="book-condition-stat-content">

            <div class="book-condition-stat-label">
                Active
            </div>

            <div class="book-condition-stat-value">
                {{ $activeConditions }}
            </div>

        </div>

    </div>


    {{-- Inactive --}}
    <div class="book-condition-stat-card">

        <div class="book-condition-stat-icon stat-orange">
            <i class="bi bi-pause-circle"></i>
        </div>

        <div class="book-condition-stat-content">

            <div class="book-condition-stat-label">
                Inactive
            </div>

            <div class="book-condition-stat-value">
                {{ $inactiveConditions }}
            </div>

        </div>

    </div>


    {{-- Books --}}
    <div class="book-condition-stat-card">

        <div class="book-condition-stat-icon stat-purple">
            <i class="bi bi-book"></i>
        </div>

        <div class="book-condition-stat-content">

            <div class="book-condition-stat-label">
                Books Using Conditions
            </div>

            <div class="book-condition-stat-value">
                {{ $booksUsingConditions }}
            </div>

        </div>

    </div>

</section>


{{-- =====================================================
    MAIN PANEL
    ===================================================== --}}
<section class="dashboard-panel book-condition-panel">


    {{-- =================================================
        HEADER
        ================================================= --}}
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


    {{-- =================================================
        FILTERS
        ================================================= --}}
    <form
        method="GET"
        action="{{ route('admin.book.conditions.index') }}"
        class="book-condition-filters"
        autocomplete="off"
    >

        <div class="book-condition-filter-grid">

            {{-- Search --}}
            <div class="book-condition-filter-group">

                <label class="book-condition-filter-label">
                    Search conditions
                </label>

                <div class="book-condition-search-field">

                    <i class="bi bi-search"></i>

                    <input
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

                <label class="book-condition-filter-label">
                    Status
                </label>

                <select
                    name="status"
                    class="book-condition-select"
                >
                    <option value="">
                        All Status
                    </option>

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


    {{-- =================================================
        MESSAGES
        ================================================= --}}
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


    {{-- =================================================
        TABLE
        ================================================= --}}
    <div class="book-condition-table-wrap">

        <table class="book-condition-table">

            <thead>

                <tr>

                    <th class="book-condition-col-id">
                        #
                    </th>

                    <th class="book-condition-col-condition">
                        Condition
                    </th>

                    <th class="book-condition-col-description">
                        Description
                    </th>

                    <th class="book-condition-col-books">
                        Books
                    </th>

                    <th class="book-condition-col-status">
                        Status
                    </th>

                    <th class="book-condition-col-date">
                        Created
                    </th>

                    <th class="book-condition-col-actions">
                        Actions
                    </th>

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

                                <span class="book-condition-muted">
                                    —
                                </span>

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

                                <h5>
                                    No book conditions found
                                </h5>

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

<script>
document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('.book-condition-delete-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                if (typeof Swal === 'undefined') {
                    form.submit();
                    return;
                }

                Swal.fire({
                    title: 'Delete book condition?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-dark'
                    }
                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });

});
</script>

@endpush
