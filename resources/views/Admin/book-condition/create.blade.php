@extends('layout.admin.master')

@section('title', 'Add Condition')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/book-condition.css') }}">
@endpush

@section('content')

<div class="dashboard-section book-condition-page">


{{-- =====================================================
    HERO
    ===================================================== --}}
<section class="book-condition-hero">

    <div class="book-condition-hero-content">

        <div class="book-condition-hero-text">

            <span class="book-condition-hero-badge">
                <i class="bi bi-bookmark-plus"></i>
                Book Condition Management
            </span>

            <h1>Add Book Condition</h1>

            <p>
                Create a new condition type that can be assigned
                to books throughout the SecondBook marketplace.
            </p>

        </div>

        <div class="book-condition-hero-mark">
            <i class="bi bi-bookmark-plus"></i>
        </div>

    </div>

</section>


{{-- =====================================================
    MAIN PANEL
    ===================================================== --}}
<section class="dashboard-panel book-condition-panel">

    {{-- Panel Header --}}
    <div class="book-condition-panel-header">

        <div class="book-condition-heading-content">

            <h2 class="book-condition-panel-title">
                Condition Information
            </h2>

            <p class="book-condition-panel-description">
                Enter the basic information for this book condition.
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


    {{-- Form --}}
    <form
        action="{{ route('admin.book.conditions.store') }}"
        method="POST"
    >

        @csrf

        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="book-condition-filters">

                <div class="alert alert-danger">

                    <strong class="d-block mb-2">
                        Please correct the following errors:
                    </strong>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- Form Fields --}}
        <div class="book-condition-filters">

            <div class="book-condition-filter-grid">

                {{-- Name --}}
                <div class="book-condition-filter-group">

                    <label
                        for="name"
                        class="book-condition-filter-label"
                    >
                        Condition Name *
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
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


                {{-- Status --}}
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
                            {{ old('status', 1) == 1 ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            {{ old('status', 1) == 0 ? 'selected' : '' }}
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


                {{-- Actions --}}
                <div class="book-condition-filter-actions">

                    <button
                        type="submit"
                        class="book-condition-filter-btn"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Save Condition
                    </button>

                    <a
                        href="{{ route('admin.book.conditions.index') }}"
                        class="book-condition-clear-filter"
                    >
                        Cancel
                    </a>

                </div>

            </div>


            {{-- Description --}}
            <div
                class="book-condition-filter-group"
                style="margin-top: 16px;"
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
                    class="book-condition-input @error('description') is-invalid @enderror"
                    placeholder="Describe this book condition..."
                    style="height: 140px; min-height: 140px; resize: vertical; padding: 12px 13px;"
                >{{ old('description') }}</textarea>

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
