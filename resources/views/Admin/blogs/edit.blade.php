@extends('layout.admin.master')

@section('title', 'Edit Blog')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/blog.css') }}">
@endpush

@section('content')

<div class="dashboard-section blogs-page">


{{-- Header --}}
<div class="dashboard-panel blogs-header-panel">

    <div class="blogs-header-content">

        <div>
            <h5>Edit Blog</h5>
            <p>Update your blog post information and publication settings</p>
        </div>

        <a href="{{ route('admin.blogs.index') }}"
           class="blogs-back-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Blogs</span>
        </a>

    </div>

</div>


{{-- Validation Errors --}}
@if($errors->any())

    <div class="blogs-validation-alert">

        <div class="blogs-validation-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
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


{{-- Form --}}
<div class="dashboard-panel blog-form-panel">

    <form action="{{ route('admin.blogs.update', $blog) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')


        {{-- Blog Information --}}
        <div class="blog-form-section">

            <div class="blog-form-section-header">

                <div class="blog-form-section-icon">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div>
                    <h6>Blog Information</h6>
                    <p>Update the basic information for this blog post.</p>
                </div>

            </div>


            <div class="blog-form-grid">

                {{-- Title --}}
                <div class="blog-form-group blog-form-full">

                    <label for="title">
                        Title <span>*</span>
                    </label>

                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $blog->title) }}"
                           class="form-control"
                           placeholder="Enter blog title..."
                           required>

                </div>


                {{-- Excerpt --}}
                <div class="blog-form-group blog-form-full">

                    <label for="excerpt">
                        Excerpt
                    </label>

                    <textarea id="excerpt"
                              name="excerpt"
                              rows="3"
                              class="form-control"
                              placeholder="Write a short summary of the blog post...">{{ old('excerpt', $blog->excerpt) }}</textarea>

                    <small>
                        A short description shown before the full article.
                    </small>

                </div>


                {{-- Content --}}
                <div class="blog-form-group blog-form-full">

                    <label for="content">
                        Content <span>*</span>
                    </label>

                    <textarea id="content"
                              name="content"
                              rows="12"
                              class="form-control blog-content-input"
                              placeholder="Write your blog content here..."
                              required>{{ old('content', $blog->content) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Publication --}}
        <div class="blog-form-section">

            <div class="blog-form-section-header">

                <div class="blog-form-section-icon">
                    <i class="bi bi-send"></i>
                </div>

                <div>
                    <h6>Publication</h6>
                    <p>Manage the publication status and date.</p>
                </div>

            </div>


            <div class="blog-form-grid">

                {{-- Status --}}
                <div class="blog-form-group">

                    <label for="status">
                        Status <span>*</span>
                    </label>

                    <select id="status"
                            name="status"
                            class="form-select"
                            required>

                        <option value="draft"
                            {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="published"
                            {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>
                            Published
                        </option>

                    </select>

                </div>


                {{-- Published At --}}
                <div class="blog-form-group">

                    <label for="published_at">
                        Published At
                    </label>

                    <input type="datetime-local"
                           id="published_at"
                           name="published_at"
                           value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}"
                           class="form-control">

                    <small>
                        Leave empty when the post is a draft.
                    </small>

                </div>

            </div>

        </div>


        {{-- Featured Image --}}
        <div class="blog-form-section">

            <div class="blog-form-section-header">

                <div class="blog-form-section-icon">
                    <i class="bi bi-image"></i>
                </div>

                <div>
                    <h6>Featured Image</h6>
                    <p>Update the image used for this blog post.</p>
                </div>

            </div>


            <div class="blog-image-upload">

                <label for="image"
                       class="blog-upload-area">

                    <i class="bi bi-cloud-arrow-up"></i>

                    <strong>Choose a new image</strong>

                    <span>
                        JPG, JPEG, PNG or WEBP · Maximum 2 MB
                    </span>

                </label>

                <input type="file"
                       id="image"
                       name="image"
                       accept=".jpg,.jpeg,.png,.webp"
                       hidden>


                {{-- Current / New Preview --}}
                <div class="blog-upload-preview show"
                     id="blog-upload-preview">

                    @if($blog->image)

                        <img src="{{ asset('storage/' . $blog->image) }}"
                             alt="{{ $blog->title }}"
                             id="blog-preview-image">

                    @else

                        <img src=""
                             alt="Preview"
                             id="blog-preview-image">

                    @endif

                </div>

            </div>

            @if($blog->image)

                <small class="blog-current-image-text">
                    Current featured image. Upload a new image to replace it.
                </small>

            @endif

        </div>


        {{-- Actions --}}
        <div class="blog-form-actions">

            <a href="{{ route('admin.blogs.index') }}"
               class="blog-cancel-btn">
                Cancel
            </a>

            <button type="submit"
                    class="blogs-add-btn">
                <i class="bi bi-check-lg"></i>
                Update Blog
            </button>

        </div>

    </form>

</div>


</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput = document.getElementById('image');
    const previewWrapper = document.getElementById('blog-upload-preview');
    const previewImage = document.getElementById('blog-preview-image');

    if (imageInput) {

        imageInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                previewImage.src = event.target.result;
                previewWrapper.classList.add('show');

            };

            reader.readAsDataURL(file);
        });

    }

});
</script>

@endpush
