@extends('layout.admin.master')

@section('title', 'Edit Publisher')

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
                    <i class="bi bi-pencil-square"></i>
                    Publisher Management
                </span>

                <h1>Edit Publisher</h1>

                <p>
                    Update the publisher information and keep the directory
                    accurate and up to date.
                </p>

            </div>

            <div class="publishers-hero-mark">
                <i class="bi bi-building-gear"></i>
            </div>

        </div>

    </section>


    {{-- Form Panel --}}
    <section class="dashboard-panel publishers-panel">

        <div class="publishers-panel-header">

            <div class="publishers-heading-content">

                <h2 class="publishers-panel-title">
                    Edit publisher information
                </h2>

                <p class="publishers-panel-description">
                    Update the publisher's name, logo, country, website,
                    description and status.
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
            action="{{ route('admin.publishers.update', $publisher->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="publisher-form-layout">

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
                                value="{{ old('name', $publisher->name) }}"
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
                                value="{{ old('country', $publisher->country) }}"
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
                                value="{{ old('website', $publisher->website) }}"
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
                            >{{ old('description', $publisher->description) }}</textarea>

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
                            Update Publisher
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

                        <label
                            for="publisher-logo"
                            class="publisher-field-label"
                        >
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
                                        Choose new logo
                                    </span>

                                    <span class="publisher-file-hint">
                                        PNG, JPG, JPEG, WEBP
                                    </span>

                                </span>

                            </label>

                        </div>

                        @error('logo')
                            <div class="publisher-field-error">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- Logo Preview --}}
                        @php
                            $currentLogoUrl = null;

                            if (!empty($publisher->logo)) {
                                $currentLogoUrl = filter_var(
                                    $publisher->logo,
                                    FILTER_VALIDATE_URL
                                )
                                    ? $publisher->logo
                                    : asset(
                                        'storage/' . ltrim($publisher->logo, '/')
                                    );
                            }
                        @endphp

                        <div
                            id="publisher-logo-preview"
                            class="publisher-logo-preview"
                            style="{{ $currentLogoUrl ? '' : 'display: none;' }}"
                        >

                            <div class="publisher-logo-preview-header">

                                <span>
                                    {{ $currentLogoUrl ? 'Current Logo' : 'Logo Preview' }}
                                </span>

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
                                    src="{{ $currentLogoUrl ?? '' }}"
                                    alt="{{ $publisher->name }} logo"
                                    onerror="this.closest('.publisher-logo-preview').style.display='none';"
                                >

                            </div>

                        </div>

                    </div>

                    {{-- Current Logo --}}
                    @if(!empty($publisher->logo))

                        @php
                            $currentLogoUrl = filter_var(
                                $publisher->logo,
                                FILTER_VALIDATE_URL
                            )
                                ? $publisher->logo
                                : asset(
                                    'storage/' .
                                    ltrim($publisher->logo, '/')
                                );
                        @endphp

                        <div class="publisher-field">

                            <label class="publisher-field-label">
                                Current Logo
                            </label>

                            <div class="publisher-cover-preview">

                                <img
                                    src="{{ $currentLogoUrl }}"
                                    alt="{{ $publisher->name }}"
                                    onerror="this.style.display='none';"
                                >

                            </div>

                        </div>

                    @endif


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
                                {{ old('status', $publisher->status ?? 0) ? 'checked' : '' }}
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
    const logoPreview = document.getElementById('publisher-logo-preview');
    const logoPreviewImg = document.getElementById('publisher-logo-preview-img');
    const logoRemove = document.getElementById('publisher-logo-remove');

    if (!logoInput || !logoPreview || !logoPreviewImg || !logoRemove) {
        return;
    }

    const originalLogo = logoPreviewImg.getAttribute('src') || '';

    logoInput.addEventListener('change', function () {

        const file = this.files && this.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            this.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {

            logoPreviewImg.src = event.target.result;

            logoPreview.style.display = 'block';

            const previewTitle = logoPreview.querySelector(
                '.publisher-logo-preview-header span'
            );

            if (previewTitle) {
                previewTitle.textContent = 'New Logo Preview';
            }

        };

        reader.readAsDataURL(file);

        const fileTitle = document.querySelector(
            '.publisher-file-title'
        );

        if (fileTitle) {
            fileTitle.textContent = file.name;
        }

    });


    logoRemove.addEventListener('click', function () {

        logoInput.value = '';

        if (originalLogo) {

            logoPreviewImg.src = originalLogo;

            logoPreview.style.display = 'block';

            const previewTitle = logoPreview.querySelector(
                '.publisher-logo-preview-header span'
            );

            if (previewTitle) {
                previewTitle.textContent = 'Current Logo';
            }

        } else {

            logoPreview.style.display = 'none';

        }

        const fileTitle = document.querySelector(
            '.publisher-file-title'
        );

        if (fileTitle) {
            fileTitle.textContent = 'Choose new logo';
        }

    });

});
</script>
@endpush