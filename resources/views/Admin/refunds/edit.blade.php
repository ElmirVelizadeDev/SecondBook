@extends('layout.admin.master')

@section('title', 'Edit Refund')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/refunds.css') }}">
@endpush

@section('content')

<div class="refunds-page">

    {{-- =========================================================
        HEADER
    ========================================================= --}}
    <div class="refund-hero">

        <div class="refund-hero-content">

            <span class="refund-hero-badge">
                <i class="bi bi-pencil-square"></i>
                Refund Management
            </span>

            <h1>Edit Refund</h1>

            <p>
                Update the information associated with this refund.
            </p>

        </div>

        <div class="refund-hero-actions">

            <a
                href="{{ route('admin.refunds.show', $refund) }}"
                class="refund-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back to Refund Details</span>
            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================= --}}
    @if($errors->any())

        <div class="refund-alert refund-alert-danger">

            <i class="bi bi-exclamation-triangle"></i>

            <div>

                <strong>
                    Please fix the following errors:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
        MAIN PANEL
    ========================================================= --}}
    <div class="refund-panel">

        {{-- =====================================================
            PANEL HEADER
        ===================================================== --}}
        <div class="refund-panel-header">

            <div class="refund-panel-heading">

                <span class="eyebrow">
                    Refund Information
                </span>

                <h5>
                    {{ $refund->refund_number }}
                </h5>

                <p>
                    Modify the information associated with this refund.
                </p>

            </div>

        </div>


        {{-- =====================================================
            REFUND SUMMARY
        ===================================================== --}}
        <div class="refund-summary">

            {{-- Refund Number --}}
            <div class="refund-summary-item">

                <span>
                    Refund Number
                </span>

                <strong>
                    {{ $refund->refund_number }}
                </strong>

            </div>


            {{-- Requested --}}
            <div class="refund-summary-item">

                <span>
                    Requested
                </span>

                <strong>
                    {{ $refund->requested_at?->format('M d, Y H:i') ?? '—' }}
                </strong>

            </div>


            {{-- Customer --}}
            <div class="refund-summary-item">

                <span>
                    Customer
                </span>

                <strong>
                    {{ $refund->user?->name ?? '—' }}
                </strong>

            </div>


            {{-- Status --}}
            <div class="refund-summary-item">

                <span>
                    Status
                </span>

                <strong>
                    {{ ucfirst($refund->status) }}
                </strong>

            </div>

        </div>


        {{-- =====================================================
            FORM
        ===================================================== --}}
        <form
            action="{{ route('admin.refunds.update', $refund) }}"
            method="POST"
            class="refund-form"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                REFUND INFORMATION
            ================================================= --}}
            <div class="refund-form-section">

                <div class="refund-section-heading">

                    <div class="refund-section-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div>

                        <h6>
                            Refund Information
                        </h6>

                        <p>
                            Update the refund amount, reason and note.
                        </p>

                    </div>

                </div>


                <div class="refund-form-grid">

                    {{-- =================================================
                        AMOUNT
                    ================================================= --}}
                    <div>

                        <label
                            for="amount"
                            class="refund-form-label"
                        >
                            Refund Amount
                            <span class="required">*</span>
                        </label>

                        <input
                            id="amount"
                            type="number"
                            name="amount"
                            class="refund-form-control @error('amount') is-invalid @enderror"
                            value="{{ old('amount', $refund->amount) }}"
                            min="0.01"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                        @error('amount')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        REASON
                    ================================================= --}}
                    <div>

                        <label
                            for="reason"
                            class="refund-form-label"
                        >
                            Refund Reason
                            <span class="required">*</span>
                        </label>

                        <input
                            id="reason"
                            type="text"
                            name="reason"
                            class="refund-form-control @error('reason') is-invalid @enderror"
                            value="{{ old('reason', $refund->reason) }}"
                            maxlength="255"
                            placeholder="Enter refund reason"
                            required
                        >

                        @error('reason')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        DESCRIPTION
                    ================================================= --}}
                    <div class="refund-field-full">

                        <label
                            for="description"
                            class="refund-form-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="refund-form-control @error('description') is-invalid @enderror"
                            rows="5"
                            placeholder="Enter additional refund information..."
                        >{{ old('description', $refund->description) }}</textarea>

                        @error('description')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        INTERNAL NOTE
                    ================================================= --}}
                    <div class="refund-field-full">

                        <label
                            for="admin_note"
                            class="refund-form-label"
                        >
                            Internal Note
                        </label>

                        <textarea
                            id="admin_note"
                            name="admin_note"
                            class="refund-form-control @error('admin_note') is-invalid @enderror"
                            rows="5"
                            maxlength="2000"
                            placeholder="Add an internal note about this refund..."
                        >{{ old('admin_note', $refund->admin_note) }}</textarea>

                        <span class="refund-field-help">
                            This note is intended for internal administrative use.
                        </span>

                        @error('admin_note')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                RELATED ORDER
            ================================================= --}}
            <div class="refund-form-section">

                <div class="refund-section-heading">

                    <div class="refund-section-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div>

                        <h6>
                            Related Order
                        </h6>

                        <p>
                            Order connected to this refund.
                        </p>

                    </div>

                </div>


                @if($refund->order)

                    <div class="refund-order-card">

                        <div class="refund-order-main">

                            <div class="refund-order-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>


                            <div class="refund-order-content">

                                <span>
                                    Order Number
                                </span>

                                <strong>
                                    #{{ $refund->order->order_number }}
                                </strong>

                            </div>


                            <div class="refund-order-total">

                                <span>
                                    Order Total
                                </span>

                                <strong>
                                    ${{ number_format($refund->order->total_price ?? 0, 2) }}
                                </strong>

                            </div>

                        </div>

                    </div>

                @else

                    <div class="refund-warning">

                        <div class="refund-warning-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>

                        <div>

                            <strong>
                                Order information unavailable
                            </strong>

                            <p>
                                The order associated with this refund
                                could not be found.
                            </p>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                WARNING
            ================================================= --}}
            <div class="refund-warning">

                <div class="refund-warning-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>

                    <strong>
                        Before saving
                    </strong>

                    <p>
                        Make sure the refund amount and reason are correct.
                        Processed refunds cannot be modified.
                    </p>

                </div>

            </div>


            {{-- =================================================
                ACTIONS
            ================================================= --}}
            <div class="refund-form-footer">

                <a
                    href="{{ route('admin.refunds.show', $refund) }}"
                    class="refund-cancel-btn"
                >

                    <i class="bi bi-x-lg"></i>
                    Cancel

                </a>


                @if($refund->status !== 'processed')

                    <button
                        type="submit"
                        class="refund-submit-btn"
                    >

                        <i class="bi bi-check-circle"></i>
                        Update Refund

                    </button>

                @else

                    <button
                        type="button"
                        class="refund-submit-btn"
                        disabled
                    >

                        <i class="bi bi-lock"></i>
                        Refund Locked

                    </button>

                @endif

            </div>

        </form>

    </div>

</div>

@endsection