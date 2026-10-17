@extends('layout.admin.master')

@section('title', 'Edit FAQ')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/pages/faq.css') }}">
@endpush

@section('content')

<div class="dashboard-section faq-page">

{{-- Header --}}
<div class="dashboard-panel faq-header-panel">

    <div class="faq-header-content">

        <div>
            <h5>Edit FAQ</h5>
            <p>Update the question, answer and settings for this FAQ</p>
        </div>

        <a href="{{ route('admin.faq.index') }}"
           class="faq-back-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Back to FAQ</span>
        </a>

    </div>

</div>

{{-- Validation Errors --}}
@if($errors->any())

    <div class="faq-validation-alert">

        <div class="faq-validation-icon">
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
<div class="dashboard-panel faq-form-panel">

    <form action="{{ route('admin.faq.update', $faq->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- FAQ Information --}}
        <div class="faq-form-section">

            <div class="faq-form-section-header">

                <div class="faq-form-section-icon">
                    <i class="bi bi-question-circle"></i>
                </div>

                <div>
                    <h6>FAQ Information</h6>
                    <p>Update the question, answer and category for this FAQ.</p>
                </div>

            </div>

            <div class="faq-form-grid">

                {{-- Question --}}
                <div class="faq-form-group faq-form-full">

                    <label for="question">
                        Question <span>*</span>
                    </label>

                    <input type="text"
                           id="question"
                           name="question"
                           value="{{ old('question', $faq->question) }}"
                           class="form-control"
                           placeholder="Enter frequently asked question..."
                           required>

                </div>

                {{-- Answer --}}
                <div class="faq-form-group faq-form-full">

                    <label for="answer">
                        Answer <span>*</span>
                    </label>

                    <textarea id="answer"
                              name="answer"
                              rows="8"
                              class="form-control faq-answer-input"
                              placeholder="Write the answer to this question..."
                              required>{{ old('answer', $faq->answer) }}</textarea>

                    <small>
                        Provide a clear and helpful answer for your customers.
                    </small>

                </div>

            </div>

        </div>

        {{-- Organization --}}
        <div class="faq-form-section">

            <div class="faq-form-section-header">

                <div class="faq-form-section-icon">
                    <i class="bi bi-sliders"></i>
                </div>

                <div>
                    <h6>Organization</h6>
                    <p>Manage the category, display order and visibility of this FAQ.</p>
                </div>

            </div>

            <div class="faq-form-grid">

                {{-- Category --}}
                <div class="faq-form-group">

                    <label for="category">
                        Category <span>*</span>
                    </label>

                    <input type="text"
                           id="category"
                           name="category"
                           value="{{ old('category', $faq->category) }}"
                           class="form-control"
                           placeholder="e.g. Orders, Payments, Sellers..."
                           required>

                </div>

                {{-- Sort Order --}}
                <div class="faq-form-group">

                    <label for="sort_order">
                        Sort Order
                    </label>

                    <input type="number"
                           id="sort_order"
                           name="sort_order"
                           value="{{ old('sort_order', $faq->sort_order ?? 0) }}"
                           class="form-control"
                           min="0"
                           placeholder="0">

                    <small>
                        Lower numbers appear first.
                    </small>

                </div>

                {{-- Status --}}
                <div class="faq-form-group">

                    <label for="is_active">
                        Status <span>*</span>
                    </label>

                    <select id="is_active"
                            name="is_active"
                            class="form-select"
                            required>

                        <option value="1"
                            {{ old('is_active', $faq->is_active) == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('is_active', $faq->is_active) == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    <small>
                        Only active FAQs are displayed on the website.
                    </small>

                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="faq-form-actions">

            <a href="{{ route('admin.faq.index') }}"
               class="faq-cancel-btn">
                Cancel
            </a>

            <button type="submit"
                    class="faq-save-btn">
                <i class="bi bi-check-lg"></i>
                Update FAQ
            </button>

        </div>

    </form>

</div>

</div>

@endsection
