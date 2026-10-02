@extends('layout.admin.master')

@section('title', 'Category Details')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/categories.css') }}">
@endpush

@section('content')

@php
$status = (int) ($category->status ?? 0);


$statusClass = $status
    ? 'category-status-active'
    : 'category-status-inactive';

$statusLabel = $status ? 'Active' : 'Inactive';

$categoryName = $category->name ?: 'Untitled category';

$categoryInitial = strtoupper(
    mb_substr($categoryName, 0, 1)
);

$imageUrl = null;

if (!empty($category->image)) {
    $imageUrl = filter_var($category->image, FILTER_VALIDATE_URL)
        ? $category->image
        : asset('storage/' . $category->image);
}

$bookCount = $category->books_count ?? 0;


@endphp

<div class="categories-page">


{{-- =========================================================
    HERO
========================================================== --}}

<section class="categories-hero">

    <div class="categories-hero-content">

        <span class="categories-hero-badge">
            <i class="bi bi-tags"></i>
            Category details
        </span>

        <h1>
            {{ $categoryName }}
        </h1>

        <p>
            Full information about this category, including its
            description, book count, status and category image.
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

    <div class="category-stat-card stat-blue">

        <div class="category-stat-content">
            <span>Category ID</span>
            <strong>#{{ $category->id }}</strong>
        </div>

        <div class="category-stat-icon">
            <i class="bi bi-hash"></i>
        </div>

    </div>


    <div class="category-stat-card stat-green">

        <div class="category-stat-content">
            <span>Books</span>
            <strong>{{ $bookCount }}</strong>
        </div>

        <div class="category-stat-icon">
            <i class="bi bi-book"></i>
        </div>

    </div>


    <div class="category-stat-card stat-orange">

        <div class="category-stat-content">
            <span>Status</span>
            <strong>{{ $statusLabel }}</strong>
        </div>

        <div class="category-stat-icon">
            <i class="bi bi-toggle-on"></i>
        </div>

    </div>


    <div class="category-stat-card stat-purple">

        <div class="category-stat-content">
            <span>Created</span>
            <strong>
                {{ $category->created_at?->format('d M Y') ?? '—' }}
            </strong>
        </div>

        <div class="category-stat-icon">
            <i class="bi bi-calendar3"></i>
        </div>

    </div>

</section>


{{-- =========================================================
    MAIN PANEL
========================================================== --}}

<section class="categories-panel">

    {{-- =====================================================
        PANEL HEADER
    ====================================================== --}}

    <div class="categories-panel-header">

        <div class="categories-heading-content">

            <span class="eyebrow">
                Category information
            </span>

            <h5>
                {{ $categoryName }}
            </h5>

            <p>
                Everything SecondBook knows about this category.
            </p>

        </div>


        <div class="categories-header-action">

            <div class="category-actions">

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="category-clear-filter"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to categories</span>
                </a>

                <a
                    href="{{ route('admin.categories.edit', $category->id) }}"
                    class="categories-add-btn"
                >
                    <i class="bi bi-pencil"></i>
                    <span>Edit Category</span>
                </a>

            </div>

        </div>

    </div>


    {{-- =====================================================
        OVERVIEW TABLE
    ====================================================== --}}

    <div class="categories-table-wrap">

        <table class="categories-table">

            <thead>
                <tr>
                    <th class="categories-col-image">Image</th>
                    <th class="categories-col-category">Category</th>
                    <th class="categories-col-slug">Slug</th>
                    <th class="categories-col-books">Books</th>
                    <th class="categories-col-status">Status</th>
                    <th class="categories-col-date">Created</th>
                </tr>
            </thead>


            <tbody>

                <tr>

                    {{-- Image --}}

                    <td>

                        <div class="category-item-cell">

                            @if($imageUrl)

                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $categoryName }}"
                                    class="category-image"
                                    loading="lazy"
                                >

                            @else

                                <div class="category-image-placeholder">
                                    <i class="bi bi-tags"></i>
                                </div>

                            @endif

                        </div>

                    </td>


                    {{-- Category --}}

                    <td>

                        <div class="category-item-info">

                            <strong title="{{ $categoryName }}">
                                {{ $categoryName }}
                            </strong>

                            <small>
                                Category #{{ $category->id }}
                            </small>

                        </div>

                    </td>


                    {{-- Slug --}}

                    <td>

                        @if($category->slug)

                            <span
                                class="category-slug"
                                title="{{ $category->slug }}"
                            >
                                {{ $category->slug }}
                            </span>

                        @else

                            <span class="category-slug">
                                —
                            </span>

                        @endif

                    </td>


                    {{-- Books --}}

                    <td>

                        <span class="category-book-count">

                            <i class="bi bi-book"></i>

                            {{ $bookCount }}

                        </span>

                    </td>


                    {{-- Status --}}

                    <td>

                        <span class="category-status-pill {{ $statusClass }}">

                            <i class="bi bi-circle-fill"></i>

                            {{ $statusLabel }}

                        </span>

                    </td>


                    {{-- Created --}}

                    <td>

                        <span class="category-date">
                            {{ $category->created_at?->format('d M Y') ?? '—' }}
                        </span>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- =====================================================
        CATEGORY DETAILS
    ====================================================== --}}

    <div class="categories-panel-header">

        <div class="categories-heading-content">

            <span class="eyebrow">
                Category details
            </span>

            <h5>
                Basic information
            </h5>

            <p>
                Core information and metadata for this category.
            </p>

        </div>

    </div>


    <div class="categories-form-body">

        <div class="category-form-grid">

            {{-- ID --}}

            <div class="category-field">

                <label>
                    Category ID
                </label>

                <div class="category-input">
                    #{{ $category->id }}
                </div>

            </div>


            {{-- Name --}}

            <div class="category-field">

                <label>
                    Category Name
                </label>

                <div class="category-input">
                    {{ $category->name ?: '—' }}
                </div>

            </div>


            {{-- Slug --}}

            <div class="category-field">

                <label>
                    Slug
                </label>

                <div class="category-input">
                    {{ $category->slug ?: '—' }}
                </div>

            </div>


            {{-- Book Count --}}

            <div class="category-field">

                <label>
                    Book Count
                </label>

                <div class="category-input">
                    {{ $bookCount }}
                </div>

            </div>


            {{-- Status --}}

            <div class="category-field">

                <label>
                    Status
                </label>

                <div>
                    <span class="category-status-pill {{ $statusClass }}">
                        <i class="bi bi-circle-fill"></i>
                        {{ $statusLabel }}
                    </span>
                </div>

            </div>


            {{-- Created --}}

            <div class="category-field">

                <label>
                    Created Date
                </label>

                <div class="category-input">
                    {{ $category->created_at?->format('d M Y H:i') ?? '—' }}
                </div>

            </div>


            {{-- Updated --}}

            <div class="category-field">

                <label>
                    Updated Date
                </label>

                <div class="category-input">
                    {{ $category->updated_at?->format('d M Y H:i') ?? '—' }}
                </div>

            </div>


            {{-- Image --}}

            <div class="category-field">

                <label>
                    Image
                </label>

                <div class="category-input">

                    @if($imageUrl)
                        Available
                    @else
                        —
                    @endif

                </div>

            </div>


            {{-- Description --}}

            <div class="category-field category-field-full">

                <label>
                    Description
                </label>

                <div class="category-input category-textarea">

                    {{ $category->description ?: '—' }}

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        CATEGORY IMAGE
    ====================================================== --}}

    <div class="categories-panel-header">

        <div class="categories-heading-content">

            <span class="eyebrow">
                Category image
            </span>

            <h5>
                Image preview
            </h5>

            <p>
                The image currently associated with this category.
            </p>

        </div>

    </div>


    <div class="categories-form-body">

        @if($imageUrl)

            <div class="category-image-preview category-detail-image-preview">

                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $categoryName }}"
                    class="category-preview-image"
                    loading="lazy"
                >

            </div>

        @else

            <div class="category-no-image category-detail-no-image">

                <i class="bi bi-image"></i>

                <span>
                    No image uploaded
                </span>

            </div>

        @endif

    </div>


    {{-- =====================================================
        FOOTER
    ====================================================== --}}

    <div class="categories-pagination">

        <div class="categories-pagination-info">

            Category
            <strong>#{{ $category->id }}</strong>

        </div>


        <nav
            class="categories-pagination-pages"
            aria-label="Category actions"
        >

            <a
                href="{{ route('admin.categories.index') }}"
                class="categories-pager-btn"
                title="Back to categories"
                aria-label="Back to categories"
            >
                <i class="bi bi-arrow-left"></i>
            </a>


            <a
                href="{{ route('admin.categories.edit', $category->id) }}"
                class="categories-pager-btn active"
                title="Edit category"
                aria-label="Edit category"
            >
                <i class="bi bi-pencil"></i>
            </a>

        </nav>

    </div>

</section>


</div>

@endsection
