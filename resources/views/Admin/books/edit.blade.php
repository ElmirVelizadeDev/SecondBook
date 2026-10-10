/*
 * @Author: mikey.zhaopeng 
 * @Date: 2026-10-10 11:22:31 
 * @Last Modified by:   mikey.zhaopeng 
 * @Last Modified time: 2026-10-10 11:22:31 
 */
@extends('layout.admin.master')

@section('title', 'Edit Book')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/books.css') }}">
@endpush

@section('content')

<div class="books-page">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="books-panel">

        <div class="books-panel-header">

            <div class="books-heading-content">

                <span class="eyebrow">
                    <i class="bi bi-pencil-square"></i>
                    Book Management
                </span>

                <h5>
                    Edit Book
                </h5>

                <p>
                    Update book information, pricing, inventory and marketplace settings.
                </p>

            </div>

            <div class="books-header-action">

                <a
                    href="{{ route('admin.books.index') }}"
                    class="books-add-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Books
                </a>

            </div>

        </div>


        {{-- =========================================================
             BODY
             ========================================================= --}}

        <div style="padding: 26px;">

            {{-- =====================================================
                 ERRORS
                 ===================================================== --}}

            @if($errors->any())

                <div
                    class="alert alert-danger mb-4"
                    style="
                        border-radius: 10px;
                        font-size: 11px;
                        font-weight: 500;
                    "
                >

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
                action="{{ route('admin.books.update', $book->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                     BASIC INFORMATION
                     ================================================= --}}

                <div class="mb-4">

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-book"></i>
                        </span>

                        <span>Basic Information</span>

                    </div>


                    <div class="row g-3">

                        {{-- Title --}}
                        <div class="col-lg-8">

                            <label
                                for="title"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Book Title
                                <span class="text-danger">*</span>
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="title"
                                    type="text"
                                    name="title"
                                    value="{{ old('title', $book->title) }}"
                                    placeholder="Enter book title"
                                    required
                                >

                            </div>

                        </div>


                        {{-- ISBN --}}
                        <div class="col-lg-4">

                            <label
                                for="isbn"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                ISBN
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="isbn"
                                    type="text"
                                    name="isbn"
                                    value="{{ old('isbn', $book->isbn) }}"
                                    placeholder="ISBN"
                                >

                            </div>

                        </div>


                        {{-- Category --}}
                        <div class="col-lg-4">

                            <label
                                for="category_id"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Category
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                class="book-filter-select"
                                required
                            >

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $book->category_id) == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Author --}}
                        <div class="col-lg-4">

                            <label
                                for="author_id"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Author
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="author_id"
                                name="author_id"
                                class="book-filter-select"
                                required
                            >

                                @foreach($authors as $author)

                                    <option
                                        value="{{ $author->id }}"
                                        @selected(old('author_id', $book->author_id) == $author->id)
                                    >
                                        {{ $author->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Publisher --}}
                        <div class="col-lg-4">

                            <label
                                for="publisher_id"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Publisher
                            </label>

                            <select
                                id="publisher_id"
                                name="publisher_id"
                                class="book-filter-select"
                            >

                                <option value="">
                                    Select Publisher
                                </option>

                                @foreach($publishers as $publisher)

                                    <option
                                        value="{{ $publisher->id }}"
                                        @selected(old('publisher_id', $book->publisher_id) == $publisher->id)
                                    >
                                        {{ $publisher->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BOOK DETAILS
                     ================================================= --}}

                <div
                    class="mb-4 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-info-circle"></i>
                        </span>

                        <span>Book Details</span>

                    </div>


                    <div class="row g-3">

                        {{-- Publication Year --}}
                        <div class="col-lg-4">

                            <label
                                for="publication_year"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Publication Year
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="publication_year"
                                    type="number"
                                    name="publication_year"
                                    min="0"
                                    value="{{ old('publication_year', $book->publication_year) }}"
                                    placeholder="e.g. 2024"
                                >

                            </div>

                        </div>


                        {{-- Pages --}}
                        <div class="col-lg-4">

                            <label
                                for="pages"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Pages
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="pages"
                                    type="number"
                                    name="pages"
                                    min="0"
                                    value="{{ old('pages', $book->pages) }}"
                                    placeholder="Number of pages"
                                >

                            </div>

                        </div>


                        {{-- Language --}}
                        <div class="col-lg-4">

                            <label
                                for="language"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Language
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="language"
                                    type="text"
                                    name="language"
                                    value="{{ old('language', $book->language) }}"
                                    placeholder="e.g. English"
                                >

                            </div>

                        </div>


                        {{-- Condition --}}
                        <div class="col-lg-6">

                            <label
                                for="condition"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Condition
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="condition"
                                name="condition"
                                class="book-filter-select"
                                required
                            >

                                <option
                                    value="new"
                                    @selected(old('condition', $book->condition) === 'new')
                                >
                                    New
                                </option>

                                <option
                                    value="like_new"
                                    @selected(old('condition', $book->condition) === 'like_new')
                                >
                                    Like New
                                </option>

                                <option
                                    value="good"
                                    @selected(old('condition', $book->condition) === 'good')
                                >
                                    Good
                                </option>

                                <option
                                    value="fair"
                                    @selected(old('condition', $book->condition) === 'fair')
                                >
                                    Fair
                                </option>

                            </select>

                        </div>


                        {{-- Seller --}}
                        <div class="col-lg-6">

                            <label
                                for="seller_id"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Seller
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="seller_id"
                                name="seller_id"
                                class="book-filter-select"
                                required
                            >

                                @foreach($sellers as $seller)

                                    <option
                                        value="{{ $seller->id }}"
                                        @selected(old('seller_id', $book->seller_id) == $seller->id)
                                    >
                                        {{ trim(($seller->first_name ?? '') . ' ' . ($seller->last_name ?? '')) ?: ($seller->name ?? $seller->username ?? 'Seller') }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PRICING & INVENTORY
                     ================================================= --}}

                <div
                    class="mb-4 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-cash-stack"></i>
                        </span>

                        <span>Pricing & Inventory</span>

                    </div>


                    <div class="row g-3">

                        {{-- Price --}}
                        <div class="col-lg-6">

                            <label
                                for="price"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Price
                                <span class="text-danger">*</span>
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="price"
                                    type="number"
                                    name="price"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('price', $book->price) }}"
                                    required
                                >

                            </div>

                        </div>


                        {{-- Stock --}}
                        <div class="col-lg-6">

                            <label
                                for="stock"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Stock
                                <span class="text-danger">*</span>
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="stock"
                                    type="number"
                                    name="stock"
                                    min="0"
                                    value="{{ old('stock', $book->stock) }}"
                                    required
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DISCOUNT
                     ================================================= --}}

                <div
                    class="mb-4 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-percent"></i>
                        </span>

                        <span>Discount Settings</span>

                    </div>


                    <div class="row g-3">

                        {{-- Discount Type --}}
                        <div class="col-lg-4">

                            <label
                                for="discountType"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Discount Type
                            </label>

                            <select
                                id="discountType"
                                name="discount_type"
                                class="book-filter-select"
                            >

                                <option
                                    value="none"
                                    @selected(old('discount_type', $book->discount_type ?? 'none') === 'none')
                                >
                                    No Discount
                                </option>

                                <option
                                    value="percentage"
                                    @selected(old('discount_type', $book->discount_type) === 'percentage')
                                >
                                    Percentage
                                </option>

                                <option
                                    value="fixed"
                                    @selected(old('discount_type', $book->discount_type) === 'fixed')
                                >
                                    Fixed Amount
                                </option>

                            </select>

                        </div>


                        {{-- Discount Value --}}
                        <div class="col-lg-4">

                            <label
                                for="discountValue"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Discount Value
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="discountValue"
                                    type="number"
                                    name="discount_value"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('discount_value', $book->discount_value) }}"
                                >

                            </div>

                        </div>


                        {{-- Start --}}
                        <div class="col-lg-4">

                            <label
                                for="discount_start_at"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Discount Start
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="discount_start_at"
                                    type="datetime-local"
                                    name="discount_start_at"
                                    value="{{ old(
                                        'discount_start_at',
                                        $book->discount_start_at
                                            ? \Carbon\Carbon::parse($book->discount_start_at)->format('Y-m-d\TH:i')
                                            : ''
                                    ) }}"
                                >

                            </div>

                        </div>


                        {{-- End --}}
                        <div class="col-lg-4">

                            <label
                                for="discount_end_at"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Discount End
                            </label>

                            <div class="book-search-field">

                                <input
                                    id="discount_end_at"
                                    type="datetime-local"
                                    name="discount_end_at"
                                    value="{{ old(
                                        'discount_end_at',
                                        $book->discount_end_at
                                            ? \Carbon\Carbon::parse($book->discount_end_at)->format('Y-m-d\TH:i')
                                            : ''
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DESCRIPTION
                     ================================================= --}}

                <div
                    class="mb-4 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-text-paragraph"></i>
                        </span>

                        <span>Description</span>

                    </div>


                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="form-control"
                        placeholder="Write a detailed description about this book..."
                        style="
                            min-height:130px;
                            resize:vertical;
                            border:1px solid #d9e1eb;
                            border-radius:9px;
                            padding:12px 13px;
                            color:#263246;
                            font-size:11px;
                            font-weight:500;
                            outline:none;
                        "
                    >{{ old('description', $book->description) }}</textarea>

                </div>


                {{-- =================================================
                     COVER
                     ================================================= --}}

                <div
                    class="mb-4 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-image"></i>
                        </span>

                        <span>Cover Image</span>

                    </div>


                    <div class="row g-3 align-items-end">

                        {{-- Current Cover --}}
                        <div class="col-lg-6">

                            <label
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Current Cover
                            </label>

                            @if($book->cover)

                                @php
                                    $coverUrl = filter_var($book->cover, FILTER_VALIDATE_URL)
                                        ? $book->cover
                                        : asset('storage/' . $book->cover);
                                @endphp

                                <div
                                    class="d-flex align-items-center gap-3"
                                >

                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="{{ $book->title }}"
                                        style="
                                            width:55px;
                                            height:74px;
                                            object-fit:cover;
                                            border:1px solid #e1e7ef;
                                            border-radius:7px;
                                        "
                                    >

                                    <span
                                        style="
                                            color:#8a95a5;
                                            font-size:10px;
                                            font-weight:500;
                                        "
                                    >
                                        Current book cover
                                    </span>

                                </div>

                            @else

                                <div
                                    style="
                                        color:#8a95a5;
                                        font-size:10px;
                                        font-weight:500;
                                    "
                                >
                                    No cover image uploaded.
                                </div>

                            @endif

                        </div>

                        {{-- New Cover --}}
                        <div class="col-lg-6">

                            <label
                                for="cover"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Replace Cover
                            </label>

                            <div class="book-file-upload">

                                <input
                                    id="cover"
                                    type="file"
                                    name="cover"
                                    class="book-file-native"
                                    accept="image/*"
                                >

                                <label
                                    for="cover"
                                    class="book-file-label"
                                >
                                    <span class="book-file-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </span>

                                    <span class="book-file-content">
                                        <span class="book-file-title">
                                            Choose Cover Image
                                        </span>

                                        <span
                                            class="book-file-name"
                                            id="bookFileName"
                                        >
                                            No file chosen
                                        </span>
                                    </span>

                                    <span class="book-file-button">
                                        Browse
                                    </span>
                                </label>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                     ================================================= --}}

                <div
                    class="mb-4 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <div
                        class="d-flex align-items-center gap-2 mb-3"
                        style="
                            color:#344054;
                            font-size:12px;
                            font-weight:800;
                        "
                    >

                        <span
                            style="
                                width:28px;
                                height:28px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:8px;
                                color:#2563eb;
                                background:#eff6ff;
                            "
                        >
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <span>Publication Status</span>

                    </div>


                    <div class="row g-3">

                        <div class="col-lg-6">

                            <label
                                for="status"
                                class="form-label"
                                style="
                                    font-size:10px;
                                    font-weight:800;
                                    color:#667386;
                                "
                            >
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="book-filter-select"
                                required
                            >

                                <option
                                    value="pending"
                                    @selected(old('status', $book->status) === 'pending')
                                >
                                    Pending
                                </option>

                                <option
                                    value="approved"
                                    @selected(old('status', $book->status) === 'approved')
                                >
                                    Approved
                                </option>

                                <option
                                    value="rejected"
                                    @selected(old('status', $book->status) === 'rejected')
                                >
                                    Rejected
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                     ================================================= --}}

                <div
                    class="d-flex justify-content-end align-items-center gap-2 pt-4"
                    style="border-top:1px solid #e7ebf1;"
                >

                    <a
                        href="{{ route('admin.books.index') }}"
                        class="book-clear-filter"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="books-add-btn"
                        style="border:0;"
                    >
                        <i class="bi bi-check-circle"></i>
                        Update Book
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

    const discountType = document.getElementById('discountType');
    const discountValue = document.getElementById('discountValue');

    if (!discountType || !discountValue) {
        return;
    }

    function toggleDiscountValue() {

        if (discountType.value === 'none') {

            discountValue.value = '';
            discountValue.disabled = true;

        } else {

            discountValue.disabled = false;

        }
    }

    toggleDiscountValue();

    discountType.addEventListener('change', toggleDiscountValue);

});

document.addEventListener('DOMContentLoaded', function () {

    const discountType = document.getElementById('discountType');
    const discountValue = document.getElementById('discountValue');

    if (discountType && discountValue) {

        function toggleDiscountValue() {
            if (discountType.value === 'none') {
                discountValue.value = '';
                discountValue.disabled = true;
            } else {
                discountValue.disabled = false;
            }
        }

        toggleDiscountValue();

        discountType.addEventListener('change', toggleDiscountValue);
    }


    /* =========================================================
       COVER FILE NAME
    ========================================================= */

    const coverInput = document.getElementById('cover');
    const fileName = document.getElementById('bookFileName');

    if (coverInput && fileName) {

        coverInput.addEventListener('change', function () {

            if (this.files && this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileName.classList.add('has-file');
            } else {
                fileName.textContent = 'No file chosen';
                fileName.classList.remove('has-file');
            }

        });
    }

});
</script>


@endpush