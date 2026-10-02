@extends('layout.admin.master')

@section('title', 'Add Author')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/authors.css') }}">
@endpush

@section('content')

<div class="dashboard-section authors-page">


{{-- =========================================================
    HERO
========================================================== --}}
<section class="authors-hero authors-hero-compact">

    <div class="authors-hero-content">

        <span class="authors-hero-badge">
            <i class="bi bi-person-plus"></i>
            New author
        </span>

        <h1>Add a new author</h1>

        <p>
            Create an author profile, add a short biography,
            upload a photo and set the author's status.
        </p>

    </div>

    <div class="authors-hero-mark" aria-hidden="true">
        <i class="bi bi-person"></i>
    </div>

</section>


{{-- =========================================================
    FORM
========================================================== --}}
<form
    action="{{ route('admin.authors.store') }}"
    method="POST"
    enctype="multipart/form-data"
    novalidate
>

    @csrf

    {{-- =====================================================
        VALIDATION ERRORS
    ====================================================== --}}
    @if ($errors->any())

        <div class="author-form-alert" role="alert">

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


    <div class="author-form-layout">

        {{-- =================================================
            LEFT COLUMN
        ================================================== --}}
        <div class="author-form-main">


            {{-- =================================================
                BASIC INFORMATION
            ================================================== --}}
            <section class="dashboard-panel authors-panel author-form-panel">

                <div class="authors-panel-header">

                    <div class="authors-heading-content">

                        <span class="eyebrow">
                            Step 1
                        </span>

                        <h5>
                            Author information
                        </h5>

                        <p>
                            Add the author's name and biography.
                        </p>

                    </div>

                    <a
                        href="{{ route('admin.authors.index') }}"
                        class="author-clear-filter"
                    >
                        <i class="bi bi-arrow-left"></i>
                        <span>Back to authors</span>
                    </a>

                </div>


                <div class="author-form-body">

                    <div class="author-form-grid">


                        {{-- =================================================
                            NAME
                        ================================================== --}}
                        <div class="author-field author-field-full">

                            <label for="author-name">
                                Author name
                                <span class="author-required">*</span>
                            </label>

                            <input
                                id="author-name"
                                type="text"
                                name="name"
                                class="author-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter author name"
                                required
                                autofocus
                            >

                            @error('name')
                                <span class="author-field-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        {{-- =================================================
                            BIO
                        ================================================== --}}
                        <div class="author-field author-field-full">

                            <label for="author-bio">
                                Biography
                            </label>

                            <textarea
                                id="author-bio"
                                name="bio"
                                class="author-input author-textarea @error('bio') is-invalid @enderror"
                                rows="8"
                                placeholder="Write a short biography about the author..."
                            >{{ old('bio') }}</textarea>

                            @error('bio')
                                <span class="author-field-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                AUTHOR STATUS
            ================================================== --}}
            <section class="dashboard-panel authors-panel author-form-panel">

                <div class="authors-panel-header">

                    <div class="authors-heading-content">

                        <span class="eyebrow">
                            Step 2
                        </span>

                        <h5>
                            Visibility
                        </h5>

                        <p>
                            Choose whether this author should be visible
                            and available in the administration.
                        </p>

                    </div>

                </div>


                <div class="author-form-body">

                    <div class="author-form-grid">

                        <div class="author-field">

                            <label for="author-status">
                                Author status
                            </label>

                            <select
                                id="author-status"
                                name="status"
                                class="author-input author-select @error('status') is-invalid @enderror"
                            >

                                <option
                                    value="1"
                                    @selected((string) old('status', '1') === '1')
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected((string) old('status') === '0')
                                >
                                    Inactive
                                </option>

                            </select>

                            @error('status')
                                <span class="author-field-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>

                </div>

            </section>

        </div>


        {{-- =================================================
            RIGHT COLUMN
        ================================================== --}}
        <aside class="author-form-side">


            {{-- =================================================
                PHOTO
            ================================================== --}}
            <section class="dashboard-panel authors-panel author-form-panel">

                <div class="authors-panel-header">

                    <div class="authors-heading-content">

                        <span class="eyebrow">
                            Media
                        </span>

                        <h5>
                            Author photo
                        </h5>

                        <p>
                            Upload a profile photo for the author.
                        </p>

                    </div>

                </div>


                <div class="author-form-body">


                    {{-- =================================================
                        PHOTO PREVIEW
                    ================================================== --}}
                    <div
                        class="author-photo-preview"
                        id="authorPhotoPreview"
                    >

                        <div class="author-photo-preview-empty">

                            <i class="bi bi-person"></i>

                            <span>
                                No photo selected
                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                        FILE UPLOAD
                    ================================================== --}}
                    <div class="author-file-upload">

                        <input
                            type="file"
                            name="photo"
                            id="authorPhotoInput"
                            class="author-file-native @error('photo') is-invalid @enderror"
                            accept="image/*"
                        >

                        <label
                            for="authorPhotoInput"
                            class="author-file-label"
                        >

                            <span class="author-file-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </span>

                            <span class="author-file-content">

                                <span class="author-file-title">
                                    Upload photo
                                </span>

                                <span
                                    class="author-file-name"
                                    id="authorPhotoFileName"
                                >
                                    JPG, PNG or WEBP
                                </span>

                            </span>

                            <span class="author-file-button">
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

            </section>


            {{-- =================================================
                STATUS + ACTIONS
            ================================================== --}}
            <section class="dashboard-panel authors-panel author-form-panel">

                <div class="authors-panel-header">

                    <div class="authors-heading-content">

                        <span class="eyebrow">
                            Publishing
                        </span>

                        <h5>
                            Status
                        </h5>

                    </div>

                </div>


                <div class="author-form-body">

                    <div class="author-form-actions">

                        <button
                            type="submit"
                            class="authors-add-btn author-submit-btn"
                        >
                            <i class="bi bi-check-lg"></i>

                            <span>
                                Save author
                            </span>
                        </button>

                        <a
                            href="{{ route('admin.authors.index') }}"
                            class="author-clear-filter author-cancel-btn"
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
PHOTO PREVIEW
============================================================= --}}

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const photoInput = document.getElementById('authorPhotoInput');
    const photoPreview = document.getElementById('authorPhotoPreview');
    const photoFileName = document.getElementById('authorPhotoFileName');

    const emptyState = `
        <div class="author-photo-preview-empty">
            <i class="bi bi-person"></i>
            <span>No photo selected</span>
        </div>
    `;

    if (!photoInput || !photoPreview || !photoFileName) {
        return;
    }

    photoInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {

            photoPreview.innerHTML = emptyState;

            photoFileName.textContent = 'JPG, PNG or WEBP';
            photoFileName.classList.remove('has-file');

            return;
        }


        if (!file.type.startsWith('image/')) {

            this.value = '';

            photoPreview.innerHTML = `
                <div class="author-photo-preview-empty">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Please select an image file</span>
                </div>
            `;

            photoFileName.textContent = 'JPG, PNG or WEBP';
            photoFileName.classList.remove('has-file');

            return;
        }


        photoFileName.textContent = file.name;
        photoFileName.classList.add('has-file');


        const reader = new FileReader();

        reader.onload = function (event) {

            photoPreview.innerHTML = `
                <img
                    src="${event.target.result}"
                    alt="Author photo preview"
                >
            `;

        };

        reader.readAsDataURL(file);

    });

});
</script>

@endpush
