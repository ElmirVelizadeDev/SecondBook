@extends('layout.admin.master')

@section('title', 'Create Banner')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/banner.css') }}">
@endpush

@section('content')

<div class="dashboard-section banners-page">

    {{-- Header --}}
    <div class="dashboard-panel mb-4">
        <div class="panel-header mb-0">

            <div>
                <h5 class="mb-1">Create Banner</h5>
                <p class="text-muted mb-0 small">
                    Add a new banner to your website
                </p>
            </div>

            <a href="{{ route('admin.banners.index') }}"
               class="btn btn-light banner-back-btn">
                <i class="bi bi-arrow-left"></i>
                Back to Banners
            </a>

        </div>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="fw-semibold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Form --}}
    <form action="{{ route('admin.banners.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-lg-8">

                <div class="dashboard-panel banner-form-panel">

                    <div class="banner-form-heading">
                        <h6>Banner Information</h6>
                        <p>
                            Enter the content that will be displayed on the banner.
                        </p>
                    </div>


                    {{-- Title --}}
                    <div class="mb-4">

                        <label for="title" class="form-label">
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter banner title"
                            maxlength="255"
                            required
                        >

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Subtitle --}}
                    <div class="mb-4">

                        <label for="subtitle" class="form-label">
                            Subtitle
                        </label>

                        <input
                            type="text"
                            id="subtitle"
                            name="subtitle"
                            value="{{ old('subtitle') }}"
                            class="form-control @error('subtitle') is-invalid @enderror"
                            placeholder="Enter banner subtitle"
                            maxlength="255"
                        >

                        @error('subtitle')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Button Row --}}
                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="button_text" class="form-label">
                                Button Text
                            </label>

                            <input
                                type="text"
                                id="button_text"
                                name="button_text"
                                value="{{ old('button_text') }}"
                                class="form-control @error('button_text') is-invalid @enderror"
                                placeholder="e.g. Shop Now"
                                maxlength="100"
                            >

                            @error('button_text')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label for="button_url" class="form-label">
                                Button URL
                            </label>

                            <input
                                type="text"
                                id="button_url"
                                name="button_url"
                                value="{{ old('button_url') }}"
                                class="form-control @error('button_url') is-invalid @enderror"
                                placeholder="e.g. /books"
                                maxlength="500"
                            >

                            @error('button_url')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- Settings --}}
            <div class="col-lg-4">

                <div class="dashboard-panel banner-form-panel">

                    <div class="banner-form-heading">
                        <h6>Banner Settings</h6>
                        <p>
                            Configure position and visibility.
                        </p>
                    </div>


                    {{-- Position --}}
                    <div class="mb-4">

                        <label for="position" class="form-label">
                            Position
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            id="position"
                            name="position"
                            value="{{ old('position', 0) }}"
                            min="0"
                            class="form-control @error('position') is-invalid @enderror"
                            required
                        >

                        <div class="form-text">
                            Lower numbers are displayed first.
                        </div>

                        @error('position')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="mb-4">

                        <label for="status" class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Start Date --}}
                    <div class="mb-4">

                        <label for="start_date" class="form-label">
                            Start Date
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            value="{{ old('start_date') }}"
                            class="form-control @error('start_date') is-invalid @enderror"
                        >

                        @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- End Date --}}
                    <div>

                        <label for="end_date" class="form-label">
                            End Date
                        </label>

                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            class="form-control @error('end_date') is-invalid @enderror"
                        >

                        @error('end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Image --}}
            <div class="col-12">

                <div class="dashboard-panel banner-form-panel">

                    <div class="banner-form-heading">
                        <h6>Banner Image</h6>
                        <p>
                            Upload the image that will be displayed on the website.
                        </p>
                    </div>


                    <div class="banner-upload-area">

                        <div class="banner-preview-wrapper">

                            <div class="banner-preview-placeholder"
                                 id="bannerPreviewPlaceholder">

                                <i class="bi bi-image"></i>

                                <span>
                                    Image Preview
                                </span>

                            </div>

                            <img
                                id="bannerPreview"
                                class="banner-preview-image"
                                src=""
                                alt="Banner Preview"
                            >

                        </div>


                        <div class="banner-upload-content">

                            <label for="image" class="form-label">
                                Banner Image
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="form-control @error('image') is-invalid @enderror"
                                required
                            >

                            <div class="form-text">
                                JPG, JPEG, PNG or WEBP. Maximum size: 2MB.
                            </div>

                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="banner-form-actions">

            <a href="{{ route('admin.banners.index') }}"
               class="btn btn-light banner-cancel-btn">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">
                <i class="bi bi-check-lg"></i>
                Create Banner
            </button>

        </div>

    </form>

</div>

@endsection


@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const imageInput = document.getElementById('image');
        const preview = document.getElementById('bannerPreview');
        const placeholder = document.getElementById('bannerPreviewPlaceholder');

        if (!imageInput) {
            return;
        }

        imageInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
                return;
            }

            if (!file.type.startsWith('image/')) {
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';

            };

            reader.readAsDataURL(file);
        });

    });
</script>
@endpush