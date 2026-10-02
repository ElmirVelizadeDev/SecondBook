@extends('layout.admin.master')

@section('title', 'Edit Category')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/categories.css') }}">
@endpush

@section('content')

<div class="categories-page">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="categories-panel">

        <div class="categories-panel-header">

            <div class="categories-heading-content">

                <span class="eyebrow">
                    <i class="bi bi-tags"></i>
                    Category Management
                </span>

                <h5>
                    Edit Category
                </h5>

                <p>
                    Update category information, description, image and status.
                </p>

            </div>

            <div class="categories-header-action">

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="categories-add-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Categories
                </a>

            </div>

        </div>


        {{-- =========================================================
             BODY
             ========================================================= --}}

        <div class="categories-form-body">

            {{-- =====================================================
                 ERRORS
                 ===================================================== --}}

            @if($errors->any())

                <div class="alert alert-danger category-form-alert">

                    <div class="fw-bold mb-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Please fix the following errors:
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                action="{{ route('admin.categories.update', $category->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                     BASIC INFORMATION
                     ================================================= --}}

                <div class="category-form-section">

                    <div class="category-section-heading">

                        <span class="category-section-icon">
                            <i class="bi bi-tags"></i>
                        </span>

                        <span>
                            Basic Information
                        </span>

                    </div>


                    <div class="row g-3">

                        {{-- Category Name --}}
                        <div class="col-lg-8">

                            <label
                                for="name"
                                class="form-label"
                            >
                                Category Name
                                <span class="text-danger">*</span>
                            </label>

                            <div class="category-input-field">

                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $category->name) }}"
                                    placeholder="Enter category name"
                                    required
                                >

                            </div>

                            @error('name')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Slug --}}
                        <div class="col-lg-4">

                            <label
                                for="slug"
                                class="form-label"
                            >
                                Slug
                            </label>

                            <div class="category-input-field">

                                <input
                                    id="slug"
                                    type="text"
                                    name="slug"
                                    value="{{ old('slug', $category->slug) }}"
                                    placeholder="example: fiction-books"
                                >

                            </div>

                            @error('slug')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <div class="category-input-field">

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    placeholder="Write a short description for this category..."
                                >{{ old('description', $category->description) }}</textarea>

                            </div>

                            @error('description')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CATEGORY IMAGE
                     ================================================= --}}

                <div class="category-form-section">

                    <div class="category-section-heading">

                        <span class="category-section-icon">
                            <i class="bi bi-image"></i>
                        </span>

                        <span>
                            Category Image
                        </span>

                    </div>


                    <div class="row g-3 align-items-end">

                        {{-- Current Image --}}
                        <div class="col-lg-6">

                            <label class="form-label">
                                Current Image
                            </label>


                            @if($category->image)

                                @php

                                    $categoryImageUrl = filter_var(
                                        $category->image,
                                        FILTER_VALIDATE_URL
                                    )
                                        ? $category->image
                                        : asset('storage/' . $category->image);

                                @endphp


                                <div class="category-current-image">

                                    <img
                                        src="{{ $categoryImageUrl }}"
                                        alt="{{ $category->name }}"
                                        class="category-preview-image"
                                    >

                                    <div class="category-current-image-info">

                                        <span class="category-image-status">
                                            <i class="bi bi-check-circle"></i>
                                            Current image
                                        </span>

                                        <small>
                                            Upload a new image to replace it.
                                        </small>

                                    </div>

                                </div>

                            @else

                                <div class="category-no-image">

                                    <i class="bi bi-image"></i>

                                    <span>
                                        No image uploaded
                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- New Image --}}
                        <div class="col-lg-6">

                            <label
                                for="image"
                                class="form-label"
                            >
                                Replace Image
                            </label>


                            <div class="category-file-upload">

                                <input
                                    id="image"
                                    type="file"
                                    name="image"
                                    class="category-file-native"
                                    accept="image/*"
                                >

                                <label
                                    for="image"
                                    class="category-file-label"
                                >

                                    <span class="category-file-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </span>

                                    <span class="category-file-content">

                                        <span class="category-file-title">
                                            Choose Category Image
                                        </span>

                                        <span
                                            class="category-file-name"
                                            id="categoryFileName"
                                        >
                                            No file chosen
                                        </span>

                                    </span>

                                    <span class="category-file-button">
                                        Browse
                                    </span>

                                </label>

                            </div>


                            @error('image')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                     ================================================= --}}

                <div class="category-form-section">

                    <div class="category-section-heading">

                        <span class="category-section-icon">
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <span>
                            Category Status
                        </span>

                    </div>


                    <div class="row g-3">

                        <div class="col-lg-6">

                            <label
                                for="status"
                                class="form-label"
                            >
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="category-filter-select"
                                required
                            >

                                <option
                                    value="1"
                                    @selected(old('status', $category->status))
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected(!old('status', $category->status))
                                >
                                    Inactive
                                </option>

                            </select>

                            @error('status')

                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                     ================================================= --}}

                <div class="category-form-actions">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="category-clear-filter"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="categories-add-btn"
                    >
                        <i class="bi bi-check-circle"></i>
                        Update Category
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('image');
    const fileName = document.getElementById('categoryFileName');

    if (!imageInput || !fileName) {
        return;
    }

    imageInput.addEventListener('change', function () {

        if (this.files && this.files.length > 0) {

            fileName.textContent = this.files[0].name;
            fileName.classList.add('has-file');

        } else {

            fileName.textContent = 'No file chosen';
            fileName.classList.remove('has-file');

        }

    });

});
</script>

@endpush