@extends('layout.admin.master')

@section('title', 'Edit Coupon')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/coupons.css') }}">
@endpush

@section('content')

<div class="coupons-edit-page">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="coupons-edit-header">

        <div class="coupons-edit-heading">

            <div class="coupons-edit-eyebrow">
                <i class="bi bi-pencil-square"></i>
                Coupon Management
            </div>

            <h1>Edit Coupon</h1>

            <p>
                Update coupon discount, usage and availability information.
            </p>

        </div>

        <a href="{{ route('admin.coupons.index') }}"
           class="coupons-edit-back">

            <i class="bi bi-arrow-left"></i>

            <span>Back to Coupons</span>

        </a>

    </div>


    {{-- =========================================================
         MAIN CARD
         ========================================================= --}}

    <div class="coupons-edit-card">

        <div class="coupons-edit-card-header">

            <div class="coupons-edit-card-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>

            <div class="coupons-edit-card-title">

                <strong>{{ $coupon->code }}</strong>

                <span>
                    Modify the information associated with this coupon.
                </span>

            </div>

        </div>


        <div class="coupons-edit-card-body">

            {{-- =================================================
                 ERRORS
                 ================================================= --}}

            @if($errors->any())

                <div class="coupons-edit-errors">

                    <div class="coupons-edit-errors-title">

                        <i class="bi bi-exclamation-triangle"></i>

                        Please fix the following errors:

                    </div>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('admin.coupons.update', $coupon->id) }}"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                     COUPON INFORMATION
                     ================================================= --}}

                <div class="coupons-edit-section">

                    <div class="coupons-edit-section-heading">

                        <i class="bi bi-ticket-perforated"></i>

                        <span>Coupon Information</span>

                    </div>


                    <div class="coupons-edit-grid">


                        {{-- Coupon Code --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="code"
                                class="coupons-edit-label"
                            >
                                Coupon Code
                                <span class="required">*</span>
                            </label>

                            <input
                                id="code"
                                type="text"
                                name="code"
                                class="coupons-edit-input @error('code') is-invalid @enderror"
                                value="{{ old('code', $coupon->code) }}"
                            >

                            @error('code')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Discount Type --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="type"
                                class="coupons-edit-label"
                            >
                                Discount Type
                                <span class="required">*</span>
                            </label>

                            <select
                                id="type"
                                name="type"
                                class="coupons-edit-select"
                            >

                                <option
                                    value="percentage"
                                    @selected(old('type', $coupon->type) === 'percentage')
                                >
                                    Percentage (%)
                                </option>

                                <option
                                    value="fixed"
                                    @selected(old('type', $coupon->type) === 'fixed')
                                >
                                    Fixed Amount ($)
                                </option>

                            </select>

                            @error('type')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Discount Value --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="value"
                                class="coupons-edit-label"
                            >
                                Discount Value
                                <span class="required">*</span>
                            </label>

                            <input
                                id="value"
                                type="number"
                                name="value"
                                class="coupons-edit-input"
                                value="{{ old('value', $coupon->value) }}"
                                step="0.01"
                                min="0.01"
                            >

                            @error('value')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Minimum Order --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="minimum_order_amount"
                                class="coupons-edit-label"
                            >
                                Minimum Order Amount
                            </label>

                            <input
                                id="minimum_order_amount"
                                type="number"
                                name="minimum_order_amount"
                                class="coupons-edit-input"
                                value="{{ old(
                                    'minimum_order_amount',
                                    $coupon->minimum_order_amount
                                ) }}"
                                step="0.01"
                                min="0"
                            >

                            @error('minimum_order_amount')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Maximum Discount --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="maximum_discount_amount"
                                class="coupons-edit-label"
                            >
                                Maximum Discount Amount
                            </label>

                            <input
                                id="maximum_discount_amount"
                                type="number"
                                name="maximum_discount_amount"
                                class="coupons-edit-input"
                                value="{{ old(
                                    'maximum_discount_amount',
                                    $coupon->maximum_discount_amount
                                ) }}"
                                step="0.01"
                                min="0"
                            >

                            @error('maximum_discount_amount')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Usage Limit --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="usage_limit"
                                class="coupons-edit-label"
                            >
                                Usage Limit
                            </label>

                            <input
                                id="usage_limit"
                                type="number"
                                name="usage_limit"
                                class="coupons-edit-input"
                                value="{{ old(
                                    'usage_limit',
                                    $coupon->usage_limit
                                ) }}"
                                min="1"
                            >

                            @error('usage_limit')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     COUPON SCHEDULE
                     ================================================= --}}

                <div class="coupons-edit-section">

                    <div class="coupons-edit-section-heading">

                        <i class="bi bi-calendar-event"></i>

                        <span>Coupon Schedule</span>

                    </div>


                    <div class="coupons-edit-grid">


                        {{-- Starts At --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="starts_at"
                                class="coupons-edit-label"
                            >
                                Starts At
                            </label>

                            <input
                                id="starts_at"
                                type="datetime-local"
                                name="starts_at"
                                class="coupons-edit-input"
                                value="{{ old(
                                    'starts_at',
                                    $coupon->starts_at
                                        ? $coupon->starts_at->format('Y-m-d\TH:i')
                                        : ''
                                ) }}"
                            >

                            @error('starts_at')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Expires At --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="expires_at"
                                class="coupons-edit-label"
                            >
                                Expires At
                            </label>

                            <input
                                id="expires_at"
                                type="datetime-local"
                                name="expires_at"
                                class="coupons-edit-input"
                                value="{{ old(
                                    'expires_at',
                                    $coupon->expires_at
                                        ? $coupon->expires_at->format('Y-m-d\TH:i')
                                        : ''
                                ) }}"
                            >

                            @error('expires_at')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="coupons-edit-field span-6">

                            <label
                                for="status"
                                class="coupons-edit-label"
                            >
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="coupons-edit-select"
                            >

                                <option
                                    value="1"
                                    @selected(old('status', $coupon->status) == 1)
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected(old('status', $coupon->status) == 0)
                                >
                                    Inactive
                                </option>

                            </select>

                            @error('status')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CURRENT USAGE
                     ================================================= --}}

                <div class="coupons-edit-section">

                    <div class="coupons-edit-section-heading">

                        <i class="bi bi-bar-chart"></i>

                        <span>Usage Information</span>

                    </div>


                    <div class="coupons-edit-grid">

                        <div class="coupons-edit-field span-6">

                            <label class="coupons-edit-label">
                                Used Count
                            </label>

                            <div class="coupons-edit-static">

                                <i class="bi bi-people"></i>

                                {{ $coupon->used_count }}

                                uses

                            </div>

                        </div>


                        <div class="coupons-edit-field span-6">

                            <label class="coupons-edit-label">
                                Remaining Uses
                            </label>

                            <div class="coupons-edit-static">

                                <i class="bi bi-graph-down"></i>

                                @if($coupon->usage_limit !== null)

                                    {{ max(0, $coupon->usage_limit - $coupon->used_count) }}

                                    remaining

                                @else

                                    Unlimited

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                     ================================================= --}}

                <div class="coupons-edit-footer">

                    <a
                        href="{{ route('admin.coupons.index') }}"
                        class="coupons-edit-cancel"
                    >

                        <i class="bi bi-x-lg"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="coupons-edit-submit"
                    >

                        <i class="bi bi-check-circle"></i>

                        Update Coupon

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection