@extends('layout.admin.master')

@section('title', 'Edit Condition')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/book-conditions.css') }}">
@endpush

@section('content')

<div class="dashboard-section book-condition-page">

    {{-- =====================================================
        HEADER
        ===================================================== --}}
    <section class="book-condition-hero">

        <div class="book-condition-hero-content">

            <div class="book-condition-hero-text">

                <span class="book-condition-hero-badge">
                    <i class="bi bi-pencil-square"></i>
                    Book Management
                </span>

                <h1>Edit Book Condition</h1>

                <p>
                    Update the condition details used throughout
                    the SecondBook marketplace.
                </p>

            </div>

            <div class="book-condition-hero-mark">
                <i class="bi bi-pencil-square"></i>
            </div>

        </div>

    </section>


    {{-- =====================================================
        FORM PANEL
        ===================================================== --}}
    <section class="dashboard-panel book-condition-panel">

        {{-- Panel Header --}}
        <div class="book-condition-panel-header">

            <div class="book-condition-heading-content">

                <h2 class="book-condition-panel-title">
                    Condition Details
                </h2>

                <p class="book-condition-panel-description">
                    Update the information for this book condition.
                </p>

            </div>

            <div class="book-condition-header-action">

                <a
                    href="{{ route('admin.book.conditions.index') }}"
                    class="book-condition-clear-filter"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Conditions
                </a>

            </div>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div style="padding: 20px 24px 0;">

                <div class="alert alert-danger">

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-exclamation-circle-fill"></i>

                        <div>
                            <strong>Please fix the following errors:</strong>

                            <ul class="mb-0 mt-2 ps-3">

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- Form --}}
        <form
            action="{{ route('admin.book.conditions.update', ['condition' => $selectedCondition['id']]) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="book-condition-filters">

                <div class="book-condition-filter-grid">

                    {{-- =================================================
                        CONDITION NAME
                        ================================================= --}}
                    <div class="book-condition-filter-group">

                        <label
                            for="name"
                            class="book-condition-filter-label"
                        >
                            Condition Name <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $selectedCondition['name'] ?? '') }}"
                            class="book-condition-input @error('name') is-invalid @enderror"
                            placeholder="Enter condition name"
                            required
                        >

                        @error('name')
                            <div class="text-danger mt-2 small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                        STATUS
                        ================================================= --}}
                    <div class="book-condition-filter-group">

                        <label
                            for="status"
                            class="book-condition-filter-label"
                        >
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="book-condition-select @error('status') is-invalid @enderror"
                        >

                            <option
                                value="1"
                                {{ old('status', $selectedCondition['status'] ?? 1) == 1 ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old('status', $selectedCondition['status'] ?? 1) == 0 ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="text-danger mt-2 small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                        BACK / UPDATE
                        ================================================= --}}
                    <div class="book-condition-filter-actions">

                        <button
                            type="submit"
                            class="book-condition-filter-btn"
                        >
                            <i class="bi bi-check-circle"></i>
                            Update Condition
                        </button>

                        <a
                            href="{{ route('admin.book.conditions.index') }}"
                            class="book-condition-clear-filter"
                        >
                            Cancel
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    DESCRIPTION
                    ================================================= --}}
                <div
                    class="book-condition-filter-group"
                    style="margin-top: 20px;"
                >

                    <label
                        for="description"
                        class="book-condition-filter-label"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        class="book-condition-input @error('description') is-invalid @enderror"
                        placeholder="Describe this condition..."
                        style="height: 140px; padding: 12px 13px; resize: vertical; line-height: 1.6;"
                    >{{ old('description', $selectedCondition['description'] ?? '') }}</textarea>

                    @error('description')
                        <div class="text-danger mt-2 small">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </form>

    </section>

</div>

@endsection