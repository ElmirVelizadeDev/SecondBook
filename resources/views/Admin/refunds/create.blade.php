@extends('layout.admin.master')

@section('title', 'Create Refund')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/refunds.css') }}">
@endpush

@section('content')

<div class="refunds-page">

    {{-- =========================================================
        HERO
    ========================================================= --}}
    <div class="refund-hero">

        <div class="refund-hero-content">

            <span class="refund-hero-badge">
                <i class="bi bi-arrow-counterclockwise"></i>
                Payments Recovery
            </span>

            <h1>Create Refund</h1>

            <p>
                Create a new refund request for an existing customer order.
            </p>

        </div>

        <div class="refund-hero-actions">

            <a
                href="{{ route('admin.refunds.index') }}"
                class="refund-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back to Refunds</span>
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
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
        MAIN PANEL
    ========================================================= --}}
    <div class="refund-panel">

        {{-- PANEL HEADER --}}
        <div class="refund-panel-header">

            <div class="refund-panel-heading">

                <span class="eyebrow">
                    Refund Details
                </span>

                <h5>
                    New Refund
                </h5>

                <p>
                    Select the order and provide the refund information.
                </p>

            </div>

        </div>


        {{-- =====================================================
            FORM
        ===================================================== --}}
        <form
            method="POST"
            action="{{ route('admin.refunds.store') }}"
            class="refund-form"
        >

            @csrf


            {{-- =================================================
                ORDER INFORMATION
            ================================================= --}}
            <div class="refund-form-section">

                <div class="refund-section-heading">

                    <div class="refund-section-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div>

                        <h6>
                            Order Information
                        </h6>

                        <p>
                            Select the order that requires a refund.
                        </p>

                    </div>

                </div>


                <div class="refund-form-grid">

                    <div class="refund-field-full">

                        <label
                            for="order_id"
                            class="refund-form-label"
                        >
                            Order
                            <span class="required">*</span>
                        </label>

                        <select
                            name="order_id"
                            id="order_id"
                            class="refund-form-control @error('order_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select an order
                            </option>

                            @foreach($orders as $order)

                                <option
                                    value="{{ $order->id }}"
                                    data-total="{{ $order->payment?->amount ?? $order->total_price }}"
                                    @selected(old('order_id') == $order->id)
                                >

                                    #{{ $order->order_number }}
                                    — {{ $order->user?->name ?? 'Unknown customer' }}
                                    — ${{ number_format(
                                        $order->payment?->amount ?? $order->total_price,
                                        2
                                    ) }}

                                </option>

                            @endforeach

                        </select>

                        @error('order_id')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


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
                            Enter the amount and reason for the refund.
                        </p>

                    </div>

                </div>


                <div class="refund-form-grid">

                    {{-- AMOUNT --}}
                    <div>

                        <label
                            for="amount"
                            class="refund-form-label"
                        >
                            Refund Amount
                            <span class="required">*</span>
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            class="refund-form-control @error('amount') is-invalid @enderror"
                            placeholder="0.00"
                            min="0.01"
                            step="0.01"
                            required
                        >

                        @error('amount')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                        <span class="refund-field-help">
                            The amount cannot exceed the refundable order amount.
                        </span>

                    </div>


                    {{-- REASON --}}
                    <div>

                        <label
                            for="reason"
                            class="refund-form-label"
                        >
                            Refund Reason
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="reason"
                            id="reason"
                            value="{{ old('reason') }}"
                            class="refund-form-control @error('reason') is-invalid @enderror"
                            placeholder="Enter refund reason"
                            maxlength="255"
                            required
                        >

                        @error('reason')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- INTERNAL NOTE --}}
                    <div class="refund-field-full">

                        <label
                            for="note"
                            class="refund-form-label"
                        >
                            Internal Note
                        </label>

                        <textarea
                            name="note"
                            id="note"
                            rows="5"
                            maxlength="2000"
                            class="refund-form-control @error('note') is-invalid @enderror"
                            placeholder="Add any additional information about this refund..."
                        >{{ old('note') }}</textarea>

                        @error('note')

                            <div class="refund-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                        <span class="refund-field-help">
                            This note is for administrative records.
                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                INFORMATION
            ================================================= --}}
            <div class="refund-info">

                <div class="refund-info-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>

                    <strong>
                        Refund Workflow
                    </strong>

                    <p>
                        New refunds are created with
                        <strong>Pending</strong> status.
                        After creation, you can review and approve,
                        reject, or process the refund from the refund
                        management page.
                    </p>

                </div>

            </div>


            {{-- =================================================
                FORM FOOTER
            ================================================= --}}
            <div class="refund-form-footer">

                <a
                    href="{{ route('admin.refunds.index') }}"
                    class="refund-cancel-btn"
                >

                    <i class="bi bi-x-lg"></i>
                    Cancel

                </a>


                <button
                    type="submit"
                    class="refund-submit-btn"
                >

                    <i class="bi bi-check-circle"></i>
                    Create Refund

                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Set maximum refundable amount
    |--------------------------------------------------------------------------
    */

    const orderSelect = document.getElementById('order_id');
    const amountInput = document.getElementById('amount');

    function updateRefundAmountLimit() {

        if (!orderSelect || !amountInput) {
            return;
        }

        const selectedOption =
            orderSelect.options[orderSelect.selectedIndex];

        const total =
            selectedOption?.dataset?.total;

        if (total) {

            amountInput.max = parseFloat(total).toFixed(2);

        } else {

            amountInput.removeAttribute('max');

        }

    }

    if (orderSelect && amountInput) {

        orderSelect.addEventListener(
            'change',
            updateRefundAmountLimit
        );

        updateRefundAmountLimit();

    }

});
</script>

@endpush