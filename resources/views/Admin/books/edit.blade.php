@extends('layout.admin.master')

@section('title', 'Edit Book')

@section('content')

<div class="dashboard-section">

```
{{-- Header --}}
<div class="dashboard-panel mb-4">

    <div class="panel-header mb-0">

        <div>
            <h5 class="mb-1">Edit Book</h5>

            <p class="text-muted mb-0 small">
                Update the book listing details
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


<div class="dashboard-panel">

    {{-- Validation Errors --}}
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
        action="{{ route('admin.books.update', $book->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="row g-4">

            {{-- Main Information --}}
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
                        value="{{ old('title', $book->title) }}"
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
                        value="{{ old('isbn', $book->isbn) }}"
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
                                    {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}
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
                                    {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}
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
                                    {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}
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
                                {{ old('condition', $book->condition) == 'new' ? 'selected' : '' }}
                            >
                                New
                            </option>

                            <option
                                value="like_new"
                                {{ old('condition', $book->condition) == 'like_new' ? 'selected' : '' }}
                            >
                                Like New
                            </option>

                            <option
                                value="good"
                                {{ old('condition', $book->condition) == 'good' ? 'selected' : '' }}
                            >
                                Good
                            </option>

                            <option
                                value="fair"
                                {{ old('condition', $book->condition) == 'fair' ? 'selected' : '' }}
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
                            value="{{ old('price', $book->price) }}"
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
                            value="{{ old('stock', $book->stock) }}"
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
                            value="{{ old('language', $book->language ?? 'English') }}"
                            placeholder="English"
                        >

                    </div>


                    {{-- Discount --}}
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
                                            {{ old('discount_type', $book->discount_type ?? 'none') === 'none' ? 'selected' : '' }}
                                        >
                                            No Discount
                                        </option>

                                        <option
                                            value="percentage"
                                            {{ old('discount_type', $book->discount_type ?? 'none') === 'percentage' ? 'selected' : '' }}
                                        >
                                            Percentage (%)
                                        </option>

                                        <option
                                            value="fixed"
                                            {{ old('discount_type', $book->discount_type ?? 'none') === 'fixed' ? 'selected' : '' }}
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
                                        value="{{ old('discount_value', $book->discount_value ?? 0) }}"
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
                                        value="{{ old('discount_start_at', $book->discount_start_at ? \Carbon\Carbon::parse($book->discount_start_at)->format('Y-m-d\TH:i') : '') }}"
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
                                        value="{{ old('discount_end_at', $book->discount_end_at ? \Carbon\Carbon::parse($book->discount_end_at)->format('Y-m-d\TH:i') : '') }}"
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
                            value="{{ old('publication_year', $book->publication_year) }}"
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
                            value="{{ old('pages', $book->pages) }}"
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
                                    {{ old('seller_id', $book->seller_id) == $seller->id ? 'selected' : '' }}
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
                    >{{ old('description', $book->description) }}</textarea>

                </div>

            </div>


            {{-- Right Side --}}
            <div class="col-12 col-lg-4">

                {{-- Cover --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Cover Image
                    </label>


                    @if(!empty($book->cover))

                        @php
                            $coverUrl = filter_var(
                                $book->cover,
                                FILTER_VALIDATE_URL
                            )
                                ? $book->cover
                                : asset('storage/' . $book->cover);
                        @endphp

                        <img
                            src="{{ $coverUrl }}"
                            alt="{{ $book->title }}"
                            class="img-fluid rounded border mb-3"
                            style="max-height: 240px; width: 100%; object-fit: cover;"
                            loading="lazy"
                        >

                    @else

                        <div
                            class="chart-placeholder mb-3"
                            style="height: 240px;"
                        >
                            <i class="bi bi-book"></i>

                            <h6>
                                No cover uploaded
                            </h6>
                        </div>

                    @endif


                    {{-- New Cover Upload --}}
                    <input
                        type="file"
                        name="cover"
                        class="form-control @error('cover') is-invalid @enderror"
                        accept="image/*"
                    >

                    <small class="text-muted">
                        Leave empty to keep the current cover.
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
                            {{ old('status', $book->status) == 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="approved"
                            {{ old('status', $book->status) == 'approved' ? 'selected' : '' }}
                        >
                            Approved
                        </option>

                        <option
                            value="rejected"
                            {{ old('status', $book->status) == 'rejected' ? 'selected' : '' }}
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
                        Update Book
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
```

</div>

@endsection

@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

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
