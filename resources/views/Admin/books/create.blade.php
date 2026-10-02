@extends('layout.admin.master')

@section('title', 'Add Book')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/books.css') }}">
@endpush

@section('content')

<div class="dashboard-section books-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="books-hero books-hero-compact">

        <div class="books-hero-content">

            <span class="books-hero-badge">
                <i class="bi bi-plus-circle"></i>
                New listing
            </span>

            <h1>Add a new book</h1>

            <p>
                Fill in the book details, set the price and stock,
                and upload a cover. New books are saved as pending by default.
            </p>

        </div>

        <div class="books-hero-mark" aria-hidden="true">
            <i class="bi bi-book"></i>
        </div>

    </section>


    <form
        action="{{ route('admin.books.store') }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >

        @csrf

        @if ($errors->any())

            <div class="book-form-alert" role="alert">

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


        <div class="book-form-layout">

            {{-- =====================================================
                LEFT COLUMN
            ====================================================== --}}
            <div class="book-form-main">

                {{-- ---------- Basic information ---------- --}}
                <section class="dashboard-panel books-panel book-form-panel">

                    <div class="books-panel-header">

                        <div class="books-heading-content">
                            <span class="eyebrow">Step 1</span>
                            <h5>Basic information</h5>
                            <p>Title, ISBN and who wrote and published it.</p>
                        </div>

                        <a
                            href="{{ route('admin.books.index') }}"
                            class="book-clear-filter"
                        >
                            <i class="bi bi-arrow-left"></i>
                            <span>Back to books</span>
                        </a>

                    </div>

                    <div class="book-form-body">

                        <div class="book-form-grid">

                            {{-- Title --}}
                            <div class="book-field book-field-full">

                                <label for="book-title">
                                    Book title <span class="book-required">*</span>
                                </label>

                                <input
                                    id="book-title"
                                    type="text"
                                    name="title"
                                    class="book-input @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}"
                                    placeholder="Enter book title"
                                    required
                                >

                                @error('title')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- ISBN --}}
                            <div class="book-field">

                                <label for="book-isbn">ISBN</label>

                                <input
                                    id="book-isbn"
                                    type="text"
                                    name="isbn"
                                    class="book-input @error('isbn') is-invalid @enderror"
                                    value="{{ old('isbn') }}"
                                    placeholder="978-3-16-148410-0"
                                >

                                @error('isbn')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Language --}}
                            <div class="book-field">

                                <label for="book-language">Language</label>

                                <input
                                    id="book-language"
                                    type="text"
                                    name="language"
                                    class="book-input"
                                    value="{{ old('language', 'English') }}"
                                    placeholder="English"
                                >

                            </div>


                            {{-- Author --}}
                            <div class="book-field">

                                <label for="book-author">Author</label>

                                <select
                                    id="book-author"
                                    name="author_id"
                                    class="book-input book-select @error('author_id') is-invalid @enderror"
                                >
                                    <option value="">Select author</option>

                                    @foreach($authors as $author)
                                        <option
                                            value="{{ $author->id }}"
                                            @selected(old('author_id') == $author->id)
                                        >
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('author_id')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Category --}}
                            <div class="book-field">

                                <label for="book-category">Category</label>

                                <select
                                    id="book-category"
                                    name="category_id"
                                    class="book-input book-select @error('category_id') is-invalid @enderror"
                                >
                                    <option value="">Select category</option>

                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            @selected(old('category_id') == $category->id)
                                        >
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Publisher --}}
                            <div class="book-field">

                                <label for="book-publisher">Publisher</label>

                                <select
                                    id="book-publisher"
                                    name="publisher_id"
                                    class="book-input book-select @error('publisher_id') is-invalid @enderror"
                                >
                                    <option value="">Select publisher</option>

                                    @foreach($publishers as $publisher)
                                        <option
                                            value="{{ $publisher->id }}"
                                            @selected(old('publisher_id') == $publisher->id)
                                        >
                                            {{ $publisher->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('publisher_id')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Seller --}}
                            <div class="book-field">

                                <label for="book-seller">Seller</label>

                                <select
                                    id="book-seller"
                                    name="seller_id"
                                    class="book-input book-select @error('seller_id') is-invalid @enderror"
                                >
                                    <option value="">Select seller</option>

                                    @foreach($sellers as $seller)
                                        <option
                                            value="{{ $seller->id }}"
                                            @selected(old('seller_id') == $seller->id)
                                        >
                                            {{ $seller->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('seller_id')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>

                    </div>

                </section>


                {{-- ---------- Details, price, stock ---------- --}}
                <section class="dashboard-panel books-panel book-form-panel">

                    <div class="books-panel-header">

                        <div class="books-heading-content">
                            <span class="eyebrow">Step 2</span>
                            <h5>Condition, price and stock</h5>
                            <p>How the book looks, what it costs and how many you have.</p>
                        </div>

                    </div>

                    <div class="book-form-body">

                        <div class="book-form-grid book-form-grid-3">

                            {{-- Condition --}}
                            <div class="book-field">

                                <label for="book-condition-field">Condition</label>

                                <select
                                    id="book-condition-field"
                                    name="condition"
                                    class="book-input book-select"
                                >
                                    @foreach([
                                        'new' => 'New',
                                        'like_new' => 'Like New',
                                        'good' => 'Good',
                                        'fair' => 'Fair',
                                    ] as $value => $label)
                                        <option
                                            value="{{ $value }}"
                                            @selected(old('condition', 'like_new') === $value)
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>


                            {{-- Price --}}
                            <div class="book-field">

                                <label for="book-price-field">Price ($)</label>

                                <input
                                    id="book-price-field"
                                    type="number"
                                    name="price"
                                    class="book-input @error('price') is-invalid @enderror"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('price', '0.00') }}"
                                    placeholder="0.00"
                                >

                                @error('price')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Stock --}}
                            <div class="book-field">

                                <label for="book-stock">Stock</label>

                                <input
                                    id="book-stock"
                                    type="number"
                                    name="stock"
                                    class="book-input @error('stock') is-invalid @enderror"
                                    min="0"
                                    value="{{ old('stock', 1) }}"
                                    placeholder="1"
                                >

                                @error('stock')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Publication year --}}
                            <div class="book-field">

                                <label for="book-year">Publication year</label>

                                <input
                                    id="book-year"
                                    type="number"
                                    name="publication_year"
                                    class="book-input @error('publication_year') is-invalid @enderror"
                                    min="1000"
                                    max="{{ date('Y') }}"
                                    value="{{ old('publication_year') }}"
                                    placeholder="{{ date('Y') }}"
                                >

                                @error('publication_year')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Pages --}}
                            <div class="book-field">

                                <label for="book-pages">Pages</label>

                                <input
                                    id="book-pages"
                                    type="number"
                                    name="pages"
                                    class="book-input @error('pages') is-invalid @enderror"
                                    min="1"
                                    value="{{ old('pages') }}"
                                    placeholder="Number of pages"
                                >

                                @error('pages')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>

                    </div>

                </section>


                {{-- ---------- Discount ---------- --}}
                <section class="dashboard-panel books-panel book-form-panel">

                    <div class="books-panel-header">

                        <div class="books-heading-content">
                            <span class="eyebrow">Step 3</span>
                            <h5>Discount</h5>
                            <p>
                                Add a real discount to show this book in Special Offers.
                                Leave it as “No discount” otherwise.
                            </p>
                        </div>

                    </div>

                    <div class="book-form-body">

                        <div class="book-form-grid">

                            {{-- Type --}}
                            <div class="book-field">

                                <label for="discountType">Discount type</label>

                                <select
                                    id="discountType"
                                    name="discount_type"
                                    class="book-input book-select @error('discount_type') is-invalid @enderror"
                                >
                                    @foreach([
                                        'none' => 'No discount',
                                        'percentage' => 'Percentage (%)',
                                        'fixed' => 'Fixed amount ($)',
                                    ] as $value => $label)
                                        <option
                                            value="{{ $value }}"
                                            @selected(old('discount_type', 'none') === $value)
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('discount_type')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Value --}}
                            <div class="book-field">

                                <label for="discountValue">Discount value</label>

                                <input
                                    id="discountValue"
                                    type="number"
                                    name="discount_value"
                                    class="book-input @error('discount_value') is-invalid @enderror"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('discount_value', 0) }}"
                                    placeholder="0"
                                >

                                @error('discount_value')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- Start --}}
                            <div class="book-field">

                                <label for="discountStart">Start date</label>

                                <input
                                    id="discountStart"
                                    type="datetime-local"
                                    name="discount_start_at"
                                    class="book-input @error('discount_start_at') is-invalid @enderror"
                                    value="{{ old('discount_start_at') }}"
                                >

                                @error('discount_start_at')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>


                            {{-- End --}}
                            <div class="book-field">

                                <label for="discountEnd">End date</label>

                                <input
                                    id="discountEnd"
                                    type="datetime-local"
                                    name="discount_end_at"
                                    class="book-input @error('discount_end_at') is-invalid @enderror"
                                    value="{{ old('discount_end_at') }}"
                                >

                                @error('discount_end_at')
                                    <span class="book-field-error">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>

                    </div>

                </section>


                {{-- ---------- Description ---------- --}}
                <section class="dashboard-panel books-panel book-form-panel">

                    <div class="books-panel-header">

                        <div class="books-heading-content">
                            <span class="eyebrow">Step 4</span>
                            <h5>Description</h5>
                            <p>A short summary buyers will read on the book page.</p>
                        </div>

                    </div>

                    <div class="book-form-body">

                        <div class="book-field">

                            <label for="book-description" class="sr-only-label">
                                Description
                            </label>

                            <textarea
                                id="book-description"
                                name="description"
                                class="book-input book-textarea"
                                rows="6"
                                placeholder="Write a short description about the book..."
                            >{{ old('description') }}</textarea>

                        </div>

                    </div>

                </section>

            </div>


            {{-- =====================================================
                RIGHT COLUMN
            ====================================================== --}}
            <aside class="book-form-side">

                {{-- ---------- Cover ---------- --}}
                <section class="dashboard-panel books-panel book-form-panel">

                    <div class="books-panel-header">

                        <div class="books-heading-content">
                            <span class="eyebrow">Media</span>
                            <h5>Cover image</h5>
                        </div>

                    </div>

                    <div class="book-form-body">

                        <div class="book-cover-preview" id="coverPreview">

                            <div class="book-cover-preview-empty">
                                <i class="bi bi-book"></i>
                                <span>No cover selected</span>
                            </div>

                        </div>


                        <div class="book-file-upload">

                            <input
                                type="file"
                                name="cover"
                                id="coverInput"
                                class="book-file-native"
                                accept="image/*"
                            >

                            <label for="coverInput" class="book-file-label">

                                <span class="book-file-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </span>

                                <span class="book-file-content">
                                    <span class="book-file-title">Upload cover</span>
                                    <span class="book-file-name" id="coverFileName">
                                        JPG, PNG or WEBP
                                    </span>
                                </span>

                                <span class="book-file-button">Browse</span>

                            </label>

                        </div>

                        @error('cover')
                            <span class="book-field-error">{{ $message }}</span>
                        @enderror

                    </div>

                </section>


                {{-- ---------- Status + actions ---------- --}}
                <section class="dashboard-panel books-panel book-form-panel">

                    <div class="books-panel-header">

                        <div class="books-heading-content">
                            <span class="eyebrow">Publishing</span>
                            <h5>Status</h5>
                        </div>

                    </div>

                    <div class="book-form-body">

                        <div class="book-field">

                            <label for="book-status-field">Listing status</label>

                            <select
                                id="book-status-field"
                                name="status"
                                class="book-input book-select"
                            >
                                @foreach([
                                    'pending' => 'Pending',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                ] as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected(old('status', 'pending') === $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        <div class="book-form-actions">

                            <button type="submit" class="books-add-btn book-submit-btn">
                                <i class="bi bi-check-lg"></i>
                                <span>Save book</span>
                            </button>

                            <a
                                href="{{ route('admin.books.index') }}"
                                class="book-clear-filter book-cancel-btn"
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


@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       COVER PREVIEW
    ========================================================= */

    const coverInput = document.getElementById('coverInput');
    const coverPreview = document.getElementById('coverPreview');
    const coverFileName = document.getElementById('coverFileName');

    const emptyState = `
        <div class="book-cover-preview-empty">
            <i class="bi bi-book"></i>
            <span>No cover selected</span>
        </div>
    `;

    if (coverInput && coverPreview) {

        coverInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                coverPreview.innerHTML = emptyState;
                coverFileName.textContent = 'JPG, PNG or WEBP';
                coverFileName.classList.remove('has-file');
                return;
            }

            if (!file.type.startsWith('image/')) {
                this.value = '';
                coverPreview.innerHTML = `
                    <div class="book-cover-preview-empty">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Please select an image file</span>
                    </div>
                `;
                coverFileName.textContent = 'JPG, PNG or WEBP';
                coverFileName.classList.remove('has-file');
                return;
            }

            coverFileName.textContent = file.name;
            coverFileName.classList.add('has-file');

            const reader = new FileReader();

            reader.onload = function (event) {
                coverPreview.innerHTML =
                    '<img src="' + event.target.result + '" alt="Book cover preview">';
            };

            reader.readAsDataURL(file);

        });

    }


    /* =========================================================
       DISCOUNT
    ========================================================= */

    const discountType = document.getElementById('discountType');
    const discountValue = document.getElementById('discountValue');

    if (discountType && discountValue) {

        const updateDiscountFields = function () {

            const none = discountType.value === 'none';

            if (none) {
                discountValue.value = '0';
            }

            discountValue.disabled = none;

        };

        discountType.addEventListener('change', updateDiscountFields);

        updateDiscountFields();

    }

});

</script>

@endpush