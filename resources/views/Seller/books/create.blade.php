@extends('Layout.Seller.master')

@section('title', 'Add New Book')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/books.css') }}">
@endpush

@section('content')

<div class="seller-books-page">

    {{-- =====================================================
         HEADER
         ===================================================== --}}
    <div class="seller-page-heading">

        <div>
            <h1>Add New Book</h1>
            <p>Add a new book to your store for admin approval.</p>
        </div>

        <a href="{{ route('seller.books.index') }}" class="seller-outline-button">
            <i class="bi bi-arrow-left"></i>
            <span>Back to My Books</span>
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="seller-alert seller-alert-danger">

            <div class="seller-alert-icon">
                <i class="bi bi-exclamation-lg"></i>
            </div>

            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    @endif


    <form
        action="{{ route('seller.books.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="sellerBookForm"
    >

        @csrf

        <div class="row g-4">

            {{-- =============================================
                 MAIN INFORMATION
                 ============================================= --}}
            <div class="col-xl-8">

                <div class="seller-books-form-card">

                    <div class="seller-form-header">

                        <div class="seller-form-header-main">
                            <div class="seller-form-header-icon"><i class="bi bi-book"></i></div>
                            <div>
                                <h5>Book Information</h5>
                                <p>Enter the main information about your book.</p>
                            </div>
                        </div>

                    </div>

                    <div class="seller-form-body">

                        {{-- Title --}}
                        <div class="seller-form-group @error('title') has-error @enderror">
                            <label for="title">Book Title <span>*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Enter book title" required>
                            @error('title') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="seller-form-group @error('isbn') has-error @enderror">
                                    <label for="isbn">ISBN</label>
                                    <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" placeholder="Enter ISBN">
                                    @error('isbn') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="seller-form-group @error('language') has-error @enderror">
                                    <label for="language">Language</label>
                                    <input type="text" id="language" name="language" value="{{ old('language', 'English') }}" placeholder="e.g. English">
                                    @error('language') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <div class="seller-form-group @error('category_id') has-error @enderror">
                                    <label for="category_id">Category</label>
                                    <select id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="seller-form-group @error('author_id') has-error @enderror">
                                    <label for="author_id">Author</label>
                                    <select id="author_id" name="author_id">
                                        <option value="">Select Author</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('author_id') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="seller-form-group @error('publisher_id') has-error @enderror">
                                    <label for="publisher_id">Publisher</label>
                                    <select id="publisher_id" name="publisher_id">
                                        <option value="">Select Publisher</option>
                                        @foreach($publishers as $publisher)
                                            <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('publisher_id') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>

                        {{-- Description --}}
                        <div class="seller-form-group @error('description') has-error @enderror">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="7" placeholder="Write a description about the book...">{{ old('description') }}</textarea>
                            @error('description') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                    </div>

                </div>

            </div>


            {{-- =============================================
                 SIDE INFORMATION
                 ============================================= --}}
            <div class="col-xl-4">

                {{-- Cover --}}
                <div class="seller-books-form-card mb-4">

                    <div class="seller-form-header">

                        <div class="seller-form-header-main">
                            <div class="seller-form-header-icon"><i class="bi bi-image"></i></div>
                            <div>
                                <h5>Book Cover</h5>
                                <p>Upload the cover image.</p>
                            </div>
                        </div>

                    </div>

                    <div class="seller-form-body">

                        <div class="seller-book-cover-upload @error('cover') has-error @enderror" id="coverDropzone">

                            <div class="seller-book-cover-preview">

                                <div class="seller-cover-placeholder" id="coverPlaceholder">
                                    <i class="bi bi-book"></i>
                                    <span>Preview</span>
                                </div>

                                <img id="coverPreview" src="" alt="Cover Preview" hidden>

                            </div>

                            <label for="cover" class="seller-cover-upload-button">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <span>Choose Cover</span>
                                <small id="coverHint">JPG, PNG or WEBP · Max 2MB</small>
                            </label>

                            <input type="file" id="cover" name="cover" accept=".jpg,.jpeg,.png,.webp" hidden>

                        </div>

                        @error('cover') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror

                        <div class="seller-field-error" id="coverClientError" hidden>
                            <i class="bi bi-exclamation-circle"></i> The selected file is larger than 2MB.
                        </div>

                    </div>

                </div>


                {{-- Pricing & Inventory --}}
                <div class="seller-books-form-card">

                    <div class="seller-form-header">

                        <div class="seller-form-header-main">
                            <div class="seller-form-header-icon"><i class="bi bi-box-seam"></i></div>
                            <div>
                                <h5>Pricing & Inventory</h5>
                                <p>Set price and stock information.</p>
                            </div>
                        </div>

                    </div>

                    <div class="seller-form-body">

                        <div class="seller-form-group @error('price') has-error @enderror">
                            <label for="price">Price <span>*</span></label>
                            <div class="seller-input-prefix">
                                <span>$</span>
                                <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            @error('price') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="seller-form-group @error('stock') has-error @enderror">
                            <label for="stock">Stock <span>*</span></label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', 1) }}" min="1" placeholder="Enter stock quantity" required>
                            @error('stock') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="seller-form-group @error('condition') has-error @enderror">
                            <label for="condition">Condition <span>*</span></label>
                            <select id="condition" name="condition" required>
                                <option value="">Select Condition</option>
                                <option value="new"      {{ old('condition') === 'new' ? 'selected' : '' }}>New</option>
                                <option value="like_new" {{ old('condition') === 'like_new' ? 'selected' : '' }}>Like New</option>
                                <option value="good"     {{ old('condition', 'good') === 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair"     {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                            </select>
                            @error('condition') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="seller-form-group @error('publication_year') has-error @enderror">
                            <label for="publication_year">Publication Year</label>
                            <input type="number" id="publication_year" name="publication_year" value="{{ old('publication_year') }}" min="1000" max="{{ date('Y') }}" placeholder="e.g. 2024">
                            @error('publication_year') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="seller-form-group @error('pages') has-error @enderror">
                            <label for="pages">Pages</label>
                            <input type="number" id="pages" name="pages" value="{{ old('pages') }}" min="1" placeholder="e.g. 320">
                            @error('pages') <div class="seller-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Form Actions --}}
        <div class="seller-books-form-actions">

            <a href="{{ route('seller.books.index') }}" class="seller-cancel-button">
                Cancel
            </a>

            <button type="submit" class="seller-save-button">
                <i class="bi bi-check-lg"></i>
                <span>Add Book</span>
            </button>

        </div>

    </form>

</div>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var form     = document.getElementById('sellerBookForm');
    var input    = document.getElementById('cover');
    var preview  = document.getElementById('coverPreview');
    var holder   = document.getElementById('coverPlaceholder');
    var hint     = document.getElementById('coverHint');
    var error    = document.getElementById('coverClientError');
    var dropzone = document.getElementById('coverDropzone');
    var MAX_SIZE = 2 * 1024 * 1024;

    if (input) {
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];

            if (error) error.hidden = true;
            if (!file) return;

            if (file.size > MAX_SIZE) {
                input.value = '';
                if (error) error.hidden = false;
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.hidden = false;
                if (holder) holder.hidden = true;
            };
            reader.readAsDataURL(file);

            if (hint) hint.textContent = file.name + ' · ' + Math.round(file.size / 1024) + ' KB';
        });
    }

    if (dropzone && input) {
        ['dragenter', 'dragover'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) { e.preventDefault(); dropzone.classList.add('is-dragging'); });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) { e.preventDefault(); dropzone.classList.remove('is-dragging'); });
        });
        dropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function () {
            var button = form.querySelector('.seller-save-button');
            if (button) {
                setTimeout(function () {
                    button.disabled = true;
                    button.classList.add('is-loading');
                }, 0);
            }
        });
    }

});
</script>
@endpush