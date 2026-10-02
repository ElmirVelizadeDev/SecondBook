@extends('layout.admin.master')

@section('title', 'Add Publisher')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/publishers.css') }}">
@endpush

@section('content')

<div class="dashboard-section publishers-page">

    {{-- Hero --}}
    <section class="publishers-hero publishers-hero-compact">

        <div class="publishers-hero-content">

            <div class="publishers-hero-text">

                <span class="publishers-hero-badge">
                    <i class="bi bi-plus-circle"></i>
                    Publisher Management
                </span>

                <h1>Add Publisher</h1>

                <p>
                    Create a new publisher and add its information to the
                    SecondBook publishing directory.
                </p>

            </div>

            <div class="publishers-hero-mark">
                <i class="bi bi-building-add"></i>
            </div>

        </div>

    </section>


    {{-- Form Panel --}}
    <section class="dashboard-panel publishers-panel">

        <div class="publishers-panel-header">

            <div class="publishers-heading-content">

                <h2 class="publishers-panel-title">
                    Publisher information
                </h2>

                <p class="publishers-panel-description">
                    Enter the publisher's basic information, logo and website.
                </p>

            </div>

            <div class="publishers-header-action">

                <a
                    href="{{ route('admin.publishers.index') }}"
                    class="publisher-secondary-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Publishers
                </a>

            </div>

        </div>


        <form
            action="{{ route('admin.publishers.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="publisher-form-layout">

                {{-- Main Form --}}
                <div class="publisher-form-main">

                    {{-- Errors --}}
                    @if($errors->any())

                        <div class="publisher-form-alert">

                            <strong class="d-block mb-2">
                                Please correct the following errors:
                            </strong>

                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>

                    @endif


                    <div class="publisher-form-grid">

                        {{-- Name --}}
                        <div class="publisher-field publisher-field-full">

                            <label
                                for="publisher-name"
                                class="publisher-field-label"
                            >
                                Publisher Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                id="publisher-name"
                                type="text"
                                name="name"
                                class="publisher-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter publisher name"
                            >

                            @error('name')
                                <div class="publisher-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Country --}}
                        <div class="publisher-field">

                            <label
                                for="publisher-country"
                                class="publisher-field-label"
                            >
                                Country
                            </label>

                            <input
                                id="publisher-country"
                                type="text"
                                name="country"
                                class="publisher-input @error('country') is-invalid @enderror"
                                value="{{ old('country') }}"
                                placeholder="Enter country"
                            >

                            @error('country')
                                <div class="publisher-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Website --}}
                        <div class="publisher-field">

                            <label
                                for="publisher-website"
                                class="publisher-field-label"
                            >
                                Website
                            </label>

                            <input
                                id="publisher-website"
                                type="url"
                                name="website"
                                class="publisher-input @error('website') is-invalid @enderror"
                                value="{{ old('website') }}"
                                placeholder="https://example.com"
                            >

                            @error('website')
                                <div class="publisher-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="publisher-field publisher-field-full">

                            <label
                                for="publisher-description"
                                class="publisher-field-label"
                            >
                                Description
                            </label>

                            <textarea
                                id="publisher-description"
                                name="description"
                                rows="5"
                                class="publisher-textarea @error('description') is-invalid @enderror"
                                placeholder="Write a short description about this publisher..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="publisher-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="publisher-form-actions">

                        <button
                            type="submit"
                            class="publisher-form-submit"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Save Publisher
                        </button>

                        <a
                            href="{{ route('admin.publishers.index') }}"
                            class="publisher-secondary-btn"
                        >
                            Cancel
                        </a>

                    </div>

                </div>


                {{-- Sidebar --}}
                <aside class="publisher-form-side">

                    {{-- Logo --}}
                    <div class="publisher-field">

                        <label class="publisher-field-label">
                            Publisher Logo
                        </label>

                        <div class="publisher-file-upload">

                            <input
                                id="publisher-logo"
                                type="file"
                                name="logo"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                class="publisher-file-input @error('logo') is-invalid @enderror"
                            >

                            <label
                                for="publisher-logo"
                                class="publisher-file-label"
                            >

                                <span class="publisher-file-content">

                                    <i class="bi bi-cloud-arrow-up"></i>

                                    <span class="publisher-file-title">
                                        Choose logo
                                    </span>

                                    <span class="publisher-file-hint">
                                        PNG, JPG, JPEG, WEBP
                                    </span>

                                </span>

                            </label>

                        </div>


                        {{-- Logo Preview --}}
                        <div
                            id="publisher-logo-preview"
                            class="publisher-logo-preview"
                            style="display: none;"
                        >

                            <div class="publisher-logo-preview-header">
                                <span>Logo Preview</span>

                                <button
                                    type="button"
                                    id="publisher-logo-remove"
                                    class="publisher-logo-remove"
                                    aria-label="Remove selected logo"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <div class="publisher-logo-preview-image">
                                <img
                                    id="publisher-logo-preview-img"
                                    src=""
                                    alt="Publisher logo preview"
                                >
                            </div>

                        </div>


                        @error('logo')
                            <div class="publisher-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="publisher-field">

                        <label class="publisher-field-label">
                            Status
                        </label>

                        <div class="publisher-status-toggle">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                id="publisher-status"
                                {{ old('status', 1) ? 'checked' : '' }}
                            >

                            <label for="publisher-status">
                                Active publisher
                            </label>

                        </div>

                    </div>

                </aside>

            </div>

        </form>

    </section>

</div>

@endsection


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const logoInput = document.getElementById('publisher-logo');
    const preview = document.getElementById('publisher-logo-preview');
    const previewImage = document.getElementById('publisher-logo-preview-img');
    const removeButton = document.getElementById('publisher-logo-remove');
    const uploadLabel = document.querySelector('.publisher-file-label');
    const fileTitle = document.querySelector('.publisher-file-title');

    if (!logoInput || !preview || !previewImage) {
        return;
    }

    logoInput.addEventListener('change', function (event) {

        const file = event.target.files[0];

        if (!file) {
            preview.style.display = 'none';
            previewImage.removeAttribute('src');

            if (fileTitle) {
                fileTitle.textContent = 'Choose logo';
            }

            return;
        }

        /*
         * Check that the selected file is an image.
         */
        if (!file.type.startsWith('image/')) {

            logoInput.value = '';

            preview.style.display = 'none';
            previewImage.removeAttribute('src');

            if (fileTitle) {
                fileTitle.textContent = 'Choose logo';
            }

            return;
        }

        /*
         * Create temporary browser URL for preview.
         * The image is NOT uploaded at this point.
         */
        const imageUrl = URL.createObjectURL(file);

        previewImage.src = imageUrl;
        preview.style.display = 'block';

        if (fileTitle) {
            fileTitle.textContent = file.name;
        }

        /*
         * Release the temporary URL after the image is loaded.
         */
        previewImage.onload = function () {
            URL.revokeObjectURL(imageUrl);
        };
    });


    /*
     * Remove selected logo.
     */
    if (removeButton) {

        removeButton.addEventListener('click', function () {

            logoInput.value = '';

            previewImage.removeAttribute('src');
            preview.style.display = 'none';

            if (fileTitle) {
                fileTitle.textContent = 'Choose logo';
            }
        });
    }

});
</script>
@endpush