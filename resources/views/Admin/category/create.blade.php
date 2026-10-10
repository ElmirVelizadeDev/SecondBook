@extends('layout.admin.master')

@section('title', 'Add Category')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/category.css') }}">
@endpush

@section('content')

<div class="dashboard-section categories-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="categories-hero categories-hero-compact">
        <div class="categories-hero-content">

            <span class="categories-hero-badge">
                <i class="bi bi-plus-circle"></i>
                New category
            </span>

            <h1>Add a new category</h1>

            <p>
                Create a clear and organized category for your book marketplace.
                Add an image, description and choose its visibility.
            </p>

        </div>

        <div class="categories-hero-mark" aria-hidden="true">
            <i class="bi bi-tags"></i>
        </div>
    </section>


    {{-- =========================================================
        FORM
    ========================================================== --}}
    <form
        action="{{ route('admin.categories.store') }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >

        @csrf


        {{-- =====================================================
            VALIDATION ERRORS
        ====================================================== --}}
        @if ($errors->any())

            <div class="category-form-alert" role="alert">

                <i class="bi bi-exclamation-triangle"></i>

                <div>
                    <strong>Please fix the following:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        @endif


        {{-- =====================================================
            FORM LAYOUT
        ====================================================== --}}
        <div class="category-form-layout">


            {{-- =================================================
                LEFT COLUMN
            ================================================== --}}
            <div class="category-form-main">


                {{-- =================================================
                    BASIC INFORMATION
                ================================================== --}}
                <section class="dashboard-panel categories-panel category-form-panel">

                    <div class="categories-panel-header">

                        <div class="categories-heading-content">

                            <span class="eyebrow">
                                Step 1
                            </span>

                            <h5>
                                Basic information
                            </h5>

                            <p>
                                Give the category a clear name and URL-friendly slug.
                            </p>

                        </div>

                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="category-clear-filter"
                        >
                            <i class="bi bi-arrow-left"></i>
                            <span>Back to categories</span>
                        </a>

                    </div>


                    <div class="category-form-body">

                        <div class="category-form-grid">


                            {{-- CATEGORY NAME --}}
                            <div class="category-field category-field-full">

                                <label for="category-name">
                                    Category name
                                    <span class="category-required">*</span>
                                </label>

                                <input
                                    id="category-name"
                                    type="text"
                                    name="name"
                                    class="category-input @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Fiction"
                                    autocomplete="off"
                                    required
                                >

                                @error('name')
                                    <span class="category-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- SLUG --}}
                            <div class="category-field category-field-full">

                                <label for="category-slug">
                                    Slug
                                </label>

                                <input
                                    id="category-slug"
                                    type="text"
                                    name="slug"
                                    class="category-input @error('slug') is-invalid @enderror"
                                    value="{{ old('slug') }}"
                                    placeholder="fiction-books"
                                    autocomplete="off"
                                >

                                <span class="category-field-hint">
                                    Leave empty if the slug is generated automatically.
                                </span>

                                @error('slug')
                                    <span class="category-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- DESCRIPTION --}}
                            <div class="category-field category-field-full">

                                <label for="category-description">
                                    Description
                                </label>

                                <textarea
                                    id="category-description"
                                    name="description"
                                    class="category-input category-textarea @error('description') is-invalid @enderror"
                                    rows="7"
                                    placeholder="Write a short description for this category..."
                                >{{ old('description') }}</textarea>

                                <span class="category-field-hint">
                                    A short description helps keep category information clear and consistent.
                                </span>

                                @error('description')
                                    <span class="category-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                    STATUS
                ================================================== --}}
                <section class="dashboard-panel categories-panel category-form-panel">

                    <div class="categories-panel-header">

                        <div class="categories-heading-content">

                            <span class="eyebrow">
                                Step 2
                            </span>

                            <h5>
                                Publishing
                            </h5>

                            <p>
                                Control whether this category is available to users.
                            </p>

                        </div>

                    </div>


                    <div class="category-form-body">

                        <div class="category-status-setting">

                            <label class="category-switch">

                                <input
                                    type="checkbox"
                                    name="status"
                                    value="1"
                                    id="category-status"
                                    {{ old('status', 1) ? 'checked' : '' }}
                                >

                                <span class="category-switch-slider"></span>

                            </label>


                            <div class="category-status-copy">

                                <label for="category-status">
                                    Active category
                                </label>

                                <p>
                                    Active categories can be used and displayed across the marketplace.
                                </p>

                            </div>

                        </div>

                    </div>

                </section>


            </div>


            {{-- =================================================
                RIGHT COLUMN
            ================================================== --}}
            <aside class="category-form-side">


                {{-- =================================================
                    CATEGORY IMAGE
                ================================================== --}}
                <section class="dashboard-panel categories-panel category-form-panel">

                    <div class="categories-panel-header">

                        <div class="categories-heading-content">

                            <span class="eyebrow">
                                Media
                            </span>

                            <h5>
                                Category image
                            </h5>

                            <p>
                                Add a visual identity to this category.
                            </p>

                        </div>

                    </div>


                    <div class="category-form-body">

                        {{-- IMAGE PREVIEW --}}
                        <div
                            class="category-image-preview"
                            id="categoryImagePreview"
                        >

                            <div class="category-image-placeholder">

                                <i class="bi bi-image"></i>

                                <span>
                                    No image selected
                                </span>

                            </div>

                        </div>


                        {{-- FILE UPLOAD --}}
                        <div class="category-file-upload">

                            <input
                                type="file"
                                name="image"
                                id="categoryImage"
                                class="category-file-native @error('image') is-invalid @enderror"
                                accept="image/*"
                            >

                            <label
                                for="categoryImage"
                                class="category-file-label"
                            >

                                <span class="category-file-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </span>

                                <span class="category-file-content">

                                    <span class="category-file-title">
                                        Upload image
                                    </span>

                                    <span
                                        class="category-file-name"
                                        id="categoryFileName"
                                    >
                                        JPG, PNG or WEBP
                                    </span>

                                </span>

                                <span class="category-file-button">
                                    Browse
                                </span>

                            </label>

                        </div>

                        @error('image')
                            <span class="category-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                        <span class="category-field-hint category-image-hint">
                            Recommended: square image for consistent category cards.
                        </span>

                    </div>

                </section>


                {{-- =================================================
                    TIP
                ================================================== --}}
                <section class="category-tip">

                    <div class="category-tip-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>

                    <div>

                        <strong>
                            Category tip
                        </strong>

                        <p>
                            Use a simple, high-quality image that clearly represents
                            the category. Keep category names short and easy to scan.
                        </p>

                    </div>

                </section>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <section class="dashboard-panel categories-panel category-form-panel">

                    <div class="category-form-body">

                        <div class="category-form-actions">

                            <button
                                type="submit"
                                class="categories-add-btn category-submit-btn"
                            >
                                <i class="bi bi-check-lg"></i>
                                <span>Save category</span>
                            </button>

                            <a
                                href="{{ route('admin.categories.index') }}"
                                class="category-clear-filter category-cancel-btn"
                            >
                                Cancel
                            </a>

                        </div>

                    </div>

                </section>


            </aside>

        </div>

    </form>

</div>

@endsection


{{-- =============================================================
    IMAGE PREVIEW
============================================================= --}}
@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('categoryImage');
    const imagePreview = document.getElementById('categoryImagePreview');
    const fileName = document.getElementById('categoryFileName');

    if (!imageInput || !imagePreview) {
        return;
    }


    const emptyState = `
        <div class="category-image-placeholder">
            <i class="bi bi-image"></i>
            <span>No image selected</span>
        </div>
    `;


    imageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {

            imagePreview.innerHTML = emptyState;

            if (fileName) {
                fileName.textContent = 'JPG, PNG or WEBP';
                fileName.classList.remove('has-file');
            }

            return;
        }


        if (!file.type.startsWith('image/')) {

            this.value = '';

            imagePreview.innerHTML = `
                <div class="category-image-placeholder">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Please select an image file</span>
                </div>
            `;

            if (fileName) {
                fileName.textContent = 'JPG, PNG or WEBP';
                fileName.classList.remove('has-file');
            }

            return;
        }


        if (fileName) {
            fileName.textContent = file.name;
            fileName.classList.add('has-file');
        }


        const reader = new FileReader();


        reader.onload = function (event) {

            imagePreview.innerHTML = `
                <img
                    src="${event.target.result}"
                    alt="Category image preview"
                    class="category-preview-image"
                >
            `;

        };


        reader.readAsDataURL(file);

    });

});
</script>

@endpush