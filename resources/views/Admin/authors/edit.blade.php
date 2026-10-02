@extends('layout.admin.master')

@section('title', 'Edit Author')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/authors.css') }}">
@endpush

@section('content')

<div class="authors-page">

    <div class="authors-panel author-form-panel">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="authors-panel-header">

            <div class="authors-heading-content">

                <span class="eyebrow">
                    <i class="bi bi-person-vcard"></i>
                    Author Management
                </span>

                <h5>Edit Author</h5>

                <p>
                    Update author information, biography, photo and publication status.
                </p>

            </div>

            <div class="authors-header-action">

                <a
                    href="{{ route('admin.authors.index') }}"
                    class="authors-add-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Authors
                </a>

            </div>

        </div>


        {{-- =====================================================
             FORM BODY
        ====================================================== --}}
        <div class="author-form-body">

            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}
            @if($errors->any())

                <div class="author-form-alert">

                    <i class="bi bi-exclamation-triangle"></i>

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
                action="{{ route('admin.authors.update', $author->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}
                <div class="authors-form-section">

                    <div class="authors-section-heading">

                        <span class="authors-section-icon">
                            <i class="bi bi-person"></i>
                        </span>

                        <span>Basic Information</span>

                    </div>


                    <div class="author-form-grid">

                        {{-- AUTHOR NAME --}}
                        <div class="author-field author-field-full">

                            <label for="name">

                                Author Name

                                <span class="author-required">*</span>

                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $author->name) }}"
                                class="author-input @error('name') is-invalid @enderror"
                                placeholder="Enter author name"
                                required
                            >

                            @error('name')
                                <span class="author-field-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BIOGRAPHY
                ================================================== --}}
                <div class="authors-form-section">

                    <div class="authors-section-heading">

                        <span class="authors-section-icon">
                            <i class="bi bi-text-paragraph"></i>
                        </span>

                        <span>Biography</span>

                    </div>


                    <div class="author-field">

                        <label for="bio">

                            Author Biography

                        </label>

                        <textarea
                            id="bio"
                            name="bio"
                            rows="6"
                            class="author-input author-textarea @error('bio') is-invalid @enderror"
                            placeholder="Write a short biography about this author..."
                        >{{ old('bio', $author->bio) }}</textarea>

                        @error('bio')
                            <span class="author-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     AUTHOR PHOTO
                ================================================== --}}
                <div class="authors-form-section">

                    <div class="authors-section-heading">

                        <span class="authors-section-icon">
                            <i class="bi bi-image"></i>
                        </span>

                        <span>Author Photo</span>

                    </div>


                    <div class="author-form-grid">

                        {{-- CURRENT PHOTO --}}
                        <div class="author-field">

                            <label class="authors-form-label">

                                Current Photo

                            </label>


                            @if(!empty($author->photo))

                                @php
                                    $photoUrl = str_starts_with($author->photo, 'http')
                                        ? $author->photo
                                        : asset('storage/' . ltrim($author->photo, '/'));
                                @endphp


                                <div class="authors-current-photo">

                                    <img
                                        src="{{ $photoUrl }}"
                                        alt="{{ $author->name }}"
                                    >

                                    <div class="authors-current-photo-info">

                                        <strong>
                                            Current author photo
                                        </strong>

                                        <span>
                                            Upload a new image to replace it.
                                        </span>

                                    </div>

                                </div>

                            @else

                                <div class="authors-no-photo">

                                    <span class="authors-no-photo-icon">
                                        <i class="bi bi-person"></i>
                                    </span>

                                    <span>
                                        No photo uploaded.
                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- NEW PHOTO --}}
                        <div class="author-field">

                            <label
                                for="photo"
                                class="authors-form-label"
                            >
                                Replace Photo
                            </label>


                            <div class="authors-file-upload">

                                <input
                                    id="photo"
                                    type="file"
                                    name="photo"
                                    class="authors-file-native"
                                    accept="image/*"
                                >


                                <label
                                    for="photo"
                                    class="authors-file-label"
                                >

                                    <span class="authors-file-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </span>


                                    <span class="authors-file-content">

                                        <span class="authors-file-title">
                                            Choose Author Photo
                                        </span>

                                        <span
                                            class="authors-file-name"
                                            id="authorFileName"
                                        >
                                            No file chosen
                                        </span>

                                    </span>


                                    <span class="authors-file-button">
                                        Browse
                                    </span>

                                </label>

                            </div>


                            @error('photo')

                                <span class="author-field-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PUBLICATION STATUS
                ================================================== --}}
                <div class="authors-form-section">

                    <div class="authors-section-heading">

                        <span class="authors-section-icon">
                            <i class="bi bi-toggle-on"></i>
                        </span>

                        <span>Publication Status</span>

                    </div>


                    <div class="authors-status-card">

                        <div class="authors-status-info">

                            <span class="authors-status-icon">
                                <i class="bi bi-check-circle"></i>
                            </span>

                            <div>

                                <strong>
                                    Active Author
                                </strong>

                                <span>
                                    Active authors are available for book assignments.
                                </span>

                            </div>

                        </div>


                        <label class="authors-switch">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                id="status"
                                {{ old('status', $author->status) ? 'checked' : '' }}
                            >

                            <span class="authors-switch-slider"></span>

                        </label>

                    </div>

                </div>


                {{-- =================================================
                     FORM ACTIONS
                ================================================== --}}
                <div class="authors-form-actions">

                    <a
                        href="{{ route('admin.authors.index') }}"
                        class="authors-cancel-btn"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="authors-add-btn"
                    >
                        <i class="bi bi-check-circle"></i>
                        Update Author
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

    const photoInput = document.getElementById('photo');
    const fileName = document.getElementById('authorFileName');

    if (photoInput && fileName) {

        photoInput.addEventListener('change', function () {

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