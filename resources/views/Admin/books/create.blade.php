@extends('layout.admin.master')

@section('title', 'Add Book')

@section('content')

<div class="dashboard-section">

    {{-- Header --}}
    <div class="dashboard-panel mb-4">

        <div class="panel-header mb-0">

            <div>
                <h5 class="mb-1">Add Book</h5>

                <p class="text-muted mb-0 small">
                    Create a new book listing
                </p>
            </div>

            <a
                href="{{ route('admin.books.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Back to Books
            </a>

        </div>

    </div>


    {{-- Form --}}
    <div class="dashboard-panel">

        @if ($errors->any())

            <div class="alert alert-danger mb-4">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('admin.books.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="row g-4">

                {{-- LEFT SIDE --}}
                <div class="col-12 col-lg-8">

                    {{-- Title --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Book Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="Enter book title"
                            required
                        >

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- ISBN --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            ISBN
                        </label>

                        <input
                            type="text"
                            name="isbn"
                            class="form-control @error('isbn') is-invalid @enderror"
                            value="{{ old('isbn') }}"
                            placeholder="e.g. 978-3-16-148410-0"
                        >

                        @error('isbn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="row g-3">

                        {{-- Author --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Author
                            </label>

                            <select
                                name="author_id"
                                class="form-select @error('author_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select author
                                </option>

                                @foreach($authors as $author)

                                    <option
                                        value="{{ $author->id }}"
                                        {{ old('author_id') == $author->id ? 'selected' : '' }}
                                    >
                                        {{ $author->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('author_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Category --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Category
                            </label>

                            <select
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select category
                                </option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Publisher --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Publisher
                            </label>

                            <select
                                name="publisher_id"
                                class="form-select @error('publisher_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select publisher
                                </option>

                                @foreach($publishers as $publisher)

                                    <option
                                        value="{{ $publisher->id }}"
                                        {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}
                                    >
                                        {{ $publisher->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('publisher_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Condition --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Condition
                            </label>

                            <select
                                name="condition"
                                class="form-select"
                            >

                                <option
                                    value="new"
                                    {{ old('condition') == 'new' ? 'selected' : '' }}
                                >
                                    New
                                </option>

                                <option
                                    value="like_new"
                                    {{ old('condition', 'like_new') == 'like_new' ? 'selected' : '' }}
                                >
                                    Like New
                                </option>

                                <option
                                    value="good"
                                    {{ old('condition') == 'good' ? 'selected' : '' }}
                                >
                                    Good
                                </option>

                                <option
                                    value="fair"
                                    {{ old('condition') == 'fair' ? 'selected' : '' }}
                                >
                                    Fair
                                </option>

                            </select>

                        </div>


                        {{-- Price --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Price ($)
                            </label>

                            <input
                                type="number"
                                name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                step="0.01"
                                min="0"
                                value="{{ old('price', '0.00') }}"
                                placeholder="0.00"
                            >

                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Stock --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Stock
                            </label>

                            <input
                                type="number"
                                name="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                min="0"
                                value="{{ old('stock', 1) }}"
                                placeholder="1"
                            >

                            @error('stock')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Language --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Language
                            </label>

                            <input
                                type="text"
                                name="language"
                                class="form-control"
                                value="{{ old('language', 'English') }}"
                                placeholder="English"
                            >

                        </div>


                        {{-- =====================================================
                             DISCOUNT
                        ====================================================== --}}
                        <div class="col-12">

                            <div class="border rounded-4 p-3">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold mb-1">
                                        Discount
                                    </label>

                                    <p class="text-muted small mb-0">
                                        Add a real discount to make this book appear in Special Offers.
                                    </p>

                                </div>


                                <div class="row g-3">

                                    {{-- Discount Type --}}
                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Discount Type
                                        </label>

                                        <select
                                            name="discount_type"
                                            id="discountType"
                                            class="form-select @error('discount_type') is-invalid @enderror"
                                        >

                                            <option
                                                value="none"
                                                {{ old('discount_type', 'none') === 'none' ? 'selected' : '' }}
                                            >
                                                No Discount
                                            </option>

                                            <option
                                                value="percentage"
                                                {{ old('discount_type') === 'percentage' ? 'selected' : '' }}
                                            >
                                                Percentage (%)
                                            </option>

                                            <option
                                                value="fixed"
                                                {{ old('discount_type') === 'fixed' ? 'selected' : '' }}
                                            >
                                                Fixed Amount ($)
                                            </option>

                                        </select>

                                        @error('discount_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- Discount Value --}}
                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Discount Value
                                        </label>

                                        <input
                                            type="number"
                                            name="discount_value"
                                            id="discountValue"
                                            class="form-control @error('discount_value') is-invalid @enderror"
                                            step="0.01"
                                            min="0"
                                            value="{{ old('discount_value', 0) }}"
                                            placeholder="0"
                                        >

                                        @error('discount_value')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- Start Date --}}
                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Start Date
                                        </label>

                                        <input
                                            type="datetime-local"
                                            name="discount_start_at"
                                            class="form-control @error('discount_start_at') is-invalid @enderror"
                                            value="{{ old('discount_start_at') }}"
                                        >

                                        @error('discount_start_at')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- End Date --}}
                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            End Date
                                        </label>

                                        <input
                                            type="datetime-local"
                                            name="discount_end_at"
                                            class="form-control @error('discount_end_at') is-invalid @enderror"
                                            value="{{ old('discount_end_at') }}"
                                        >

                                        @error('discount_end_at')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                <small class="text-muted d-block mt-3">
                                    Leave Discount Type as "No Discount" if this book should not appear in Special Offers.
                                </small>

                            </div>

                        </div>


                        {{-- Publication Year --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Publication Year
                            </label>

                            <input
                                type="number"
                                name="publication_year"
                                class="form-control @error('publication_year') is-invalid @enderror"
                                min="1000"
                                max="{{ date('Y') }}"
                                value="{{ old('publication_year') }}"
                                placeholder="{{ date('Y') }}"
                            >

                            @error('publication_year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Pages --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Pages
                            </label>

                            <input
                                type="number"
                                name="pages"
                                class="form-control @error('pages') is-invalid @enderror"
                                min="1"
                                value="{{ old('pages') }}"
                                placeholder="Number of pages"
                            >

                            @error('pages')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Seller --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Seller
                            </label>

                            <select
                                name="seller_id"
                                class="form-select @error('seller_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select seller
                                </option>

                                @foreach($sellers as $seller)

                                    <option
                                        value="{{ $seller->id }}"
                                        {{ old('seller_id') == $seller->id ? 'selected' : '' }}
                                    >
                                        {{ $seller->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('seller_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="mt-3">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="5"
                            placeholder="Write a short description about the book..."
                        >{{ old('description') }}</textarea>

                    </div>

                </div>


                {{-- RIGHT SIDE --}}
                <div class="col-12 col-lg-4">

                    {{-- Cover --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Cover Image
                        </label>


                        {{-- Preview --}}
                        <div
                            class="cover-preview mb-3"
                            id="coverPreview"
                        >

                            <div class="cover-preview-placeholder">

                                <i class="bi bi-book"></i>

                                <span>
                                    No cover selected
                                </span>

                            </div>

                        </div>


                        <input
                            type="file"
                            name="cover"
                            id="coverInput"
                            class="form-control @error('cover') is-invalid @enderror"
                            accept="image/*"
                        >

                        <small class="text-muted d-block mt-2">
                            Upload a book cover image.
                        </small>

                        @error('cover')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option
                                value="pending"
                                {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="approved"
                                {{ old('status') == 'approved' ? 'selected' : '' }}
                            >
                                Approved
                            </option>

                            <option
                                value="rejected"
                                {{ old('status') == 'rejected' ? 'selected' : '' }}
                            >
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- Actions --}}
                    <div class="d-grid gap-2 mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-check-circle me-2"></i>
                            Save Book
                        </button>

                        <a
                            href="{{ route('admin.books.index') }}"
                            class="btn btn-light border"
                        >
                            Cancel
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection


@push('css')

<style>

    /* =========================================================
       FORM
    ========================================================= */

    .form-control,
    .form-select {

        border-radius: 14px;

        padding: 12px 16px;

        color: #111827 !important;

        background-color: #fff !important;

        border-color: #cbd5e1;
    }


    .form-control::placeholder {

        color: #64748b;

        opacity: 1;
    }


    .form-label {

        color: var(--bs-body-color);

        margin-bottom: 8px;

        font-weight: 600;
    }


    /* =========================================================
       COVER PREVIEW
    ========================================================= */

    .cover-preview {

        width: 100%;

        height: 300px;

        border: 1px solid #cbd5e1;

        border-radius: 14px;

        overflow: hidden;

        background: #f8fafc;

        display: flex;

        align-items: center;

        justify-content: center;
    }


    .cover-preview img {

        width: 100%;

        height: 100%;

        object-fit: contain;

        display: block;
    }


    .cover-preview-placeholder {

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        gap: 10px;

        color: #64748b;

        text-align: center;
    }


    .cover-preview-placeholder i {

        font-size: 48px;
    }


    .cover-preview-placeholder span {

        font-size: 14px;

        font-weight: 500;
    }


    /* =========================================================
       FILE INPUT
    ========================================================= */

    input[type="file"].form-control {

        padding: 6px;

        background: #f8fafc;

        color: #0f172a;

        border-color: #cbd5e1;
    }


    input[type="file"].form-control::file-selector-button,
    input[type="file"].form-control::-webkit-file-upload-button {

        margin: 0 10px 0 0;

        padding: 10px 14px;

        border: 0;

        border-right: 1px solid #cbd5e1;

        border-radius: 10px;

        background: #e2e8f0;

        color: #0f172a;

        font-weight: 600;
    }


    /* =========================================================
       DISCOUNT
    ========================================================= */

    .border.rounded-4 {

        border-color: #e2e8f0 !important;
    }


    /* =========================================================
       DARK MODE
    ========================================================= */

    :root[data-theme="dark"] .form-control,
    :root[data-theme="dark"] .form-select {

        color: #f8fafc !important;

        background-color: #0f172a !important;

        border-color: #334155;
    }


    :root[data-theme="dark"] .form-control::placeholder {

        color: #94a3b8;
    }


    :root[data-theme="dark"] input[type="file"].form-control {

        background: #0b1220 !important;

        color: #e5e7eb !important;

        border-color: #334155 !important;
    }


    :root[data-theme="dark"] input[type="file"].form-control::file-selector-button,
    :root[data-theme="dark"] input[type="file"].form-control::-webkit-file-upload-button {

        background: #1e293b;

        color: #e5e7eb;

        border-right: 1px solid #334155;
    }


    :root[data-theme="dark"] .cover-preview {

        background: #0b1220;

        border-color: #334155;
    }


    :root[data-theme="dark"] .cover-preview-placeholder {

        color: #94a3b8;
    }


    :root[data-theme="dark"] .border.rounded-4 {

        border-color: #334155 !important;

        background: #0b1220;
    }

</style>

@endpush


@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        /* =====================================================
           COVER PREVIEW
        ===================================================== */

        const input =
            document.getElementById('coverInput');

        const preview =
            document.getElementById('coverPreview');


        if (input && preview) {

            input.addEventListener('change', function () {

                const file = this.files[0];


                if (!file) {

                    preview.innerHTML = `

                        <div class="cover-preview-placeholder">

                            <i class="bi bi-book"></i>

                            <span>
                                No cover selected
                            </span>

                        </div>

                    `;

                    return;
                }


                if (!file.type.startsWith('image/')) {

                    this.value = '';

                    preview.innerHTML = `

                        <div class="cover-preview-placeholder">

                            <i class="bi bi-exclamation-circle"></i>

                            <span>
                                Please select an image
                            </span>

                        </div>

                    `;

                    return;
                }


                const reader =
                    new FileReader();


                reader.onload = function (event) {

                    preview.innerHTML = `

                        <img
                            src="${event.target.result}"
                            alt="Book Cover Preview"
                        >

                    `;
                };


                reader.readAsDataURL(file);

            });

        }


        /* =====================================================
           DISCOUNT
        ===================================================== */

        const discountType =
            document.getElementById('discountType');

        const discountValue =
            document.getElementById('discountValue');


        if (discountType && discountValue) {

            function updateDiscountValue() {

                if (discountType.value === 'none') {

                    discountValue.value = '0';

                    discountValue.disabled = true;

                } else {

                    discountValue.disabled = false;

                }

            }


            discountType.addEventListener(
                'change',
                updateDiscountValue
            );


            updateDiscountValue();

        }

    });

</script>

@endpush