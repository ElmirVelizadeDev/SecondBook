@extends('layout.admin.master')

@section('title', 'Create Coupon')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/coupons.css') }}">
@endpush

@section('content')

<div class="coupons-edit-page">

    {{-- HEADER --}}
    <div class="coupons-edit-header">

        <div class="coupons-edit-heading">

            <div class="coupons-edit-eyebrow">
                <i class="bi bi-ticket-perforated"></i>
                Coupon Management
            </div>

            <h1>Create Coupon</h1>

            <p>Create a new discount coupon manually.</p>

        </div>

        <a href="{{ route('admin.coupons.index') }}"
           class="coupons-edit-back">

            <i class="bi bi-arrow-left"></i>
            <span>Back to Coupons</span>

        </a>

    </div>


    {{-- CARD --}}
    <div class="coupons-edit-card">

        <div class="coupons-edit-card-header">

            <div class="coupons-edit-card-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>

            <div class="coupons-edit-card-title">
                <strong>New Coupon</strong>
                <span>
                    Fill in the information associated with this coupon.
                </span>
            </div>

        </div>


        <div class="coupons-edit-card-body">

            {{-- VALIDATION ERRORS --}}
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


            <form action="{{ route('admin.coupons.store') }}"
                  method="POST">

                @csrf


                {{-- COUPON INFORMATION --}}
                <div class="coupons-edit-section">

                    <div class="coupons-edit-section-heading">
                        <i class="bi bi-ticket-perforated"></i>
                        <span>Coupon Information</span>
                    </div>


                    <div class="coupons-edit-grid">

                        {{-- CODE --}}
                        <div class="coupons-edit-field span-6">

                            <label for="code"
                                   class="coupons-edit-label">

                                Coupon Code
                                <span class="required">*</span>

                            </label>

                            <input
                                id="code"
                                type="text"
                                name="code"
                                class="coupons-edit-input @error('code') is-invalid @enderror"
                                placeholder="WELCOME10"
                                value="{{ old('code') }}"
                            >

                            @error('code')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- TYPE --}}
                        <div class="coupons-edit-field span-6">

                            <label for="type"
                                   class="coupons-edit-label">

                                Discount Type
                                <span class="required">*</span>

                            </label>

                            <select
                                id="type"
                                name="type"
                                class="coupons-edit-select @error('type') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Type
                                </option>

                                <option value="percentage"
                                    @selected(old('type') === 'percentage')>
                                    Percentage (%)
                                </option>

                                <option value="fixed"
                                    @selected(old('type') === 'fixed')>
                                    Fixed Amount ($)
                                </option>

                            </select>

                            @error('type')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- VALUE --}}
                        <div class="coupons-edit-field span-6">

                            <label for="value"
                                   class="coupons-edit-label">

                                Discount Value
                                <span class="required">*</span>

                            </label>

                            <input
                                id="value"
                                type="number"
                                name="value"
                                step="0.01"
                                min="0.01"
                                class="coupons-edit-input @error('value') is-invalid @enderror"
                                placeholder="10"
                                value="{{ old('value') }}"
                            >

                            @error('value')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- MINIMUM ORDER --}}
                        <div class="coupons-edit-field span-6">

                            <label for="minimum_order_amount"
                                   class="coupons-edit-label">

                                Minimum Order Amount

                            </label>

                            <input
                                id="minimum_order_amount"
                                type="number"
                                name="minimum_order_amount"
                                step="0.01"
                                min="0"
                                class="coupons-edit-input @error('minimum_order_amount') is-invalid @enderror"
                                placeholder="20"
                                value="{{ old('minimum_order_amount', 0) }}"
                            >

                            @error('minimum_order_amount')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- MAXIMUM DISCOUNT --}}
                        <div class="coupons-edit-field span-6">

                            <label for="maximum_discount_amount"
                                   class="coupons-edit-label">

                                Maximum Discount Amount

                            </label>

                            <input
                                id="maximum_discount_amount"
                                type="number"
                                name="maximum_discount_amount"
                                step="0.01"
                                min="0"
                                class="coupons-edit-input @error('maximum_discount_amount') is-invalid @enderror"
                                placeholder="50"
                                value="{{ old('maximum_discount_amount') }}"
                            >

                            @error('maximum_discount_amount')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- USAGE LIMIT --}}
                        <div class="coupons-edit-field span-6">

                            <label for="usage_limit"
                                   class="coupons-edit-label">

                                Usage Limit

                            </label>

                            <input
                                id="usage_limit"
                                type="number"
                                name="usage_limit"
                                min="1"
                                class="coupons-edit-input @error('usage_limit') is-invalid @enderror"
                                placeholder="100"
                                value="{{ old('usage_limit') }}"
                            >

                            @error('usage_limit')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- VALIDITY & STATUS --}}
                <div class="coupons-edit-section">

                    <div class="coupons-edit-section-heading">
                        <i class="bi bi-calendar-check"></i>
                        <span>Validity & Status</span>
                    </div>


                    <div class="coupons-edit-grid">

                        {{-- STARTS AT --}}
                        <div class="coupons-edit-field span-6">

                            <label for="starts_at"
                                   class="coupons-edit-label">

                                Starts At

                            </label>

                            <input
                                id="starts_at"
                                type="datetime-local"
                                name="starts_at"
                                class="coupons-edit-input @error('starts_at') is-invalid @enderror"
                                value="{{ old('starts_at') }}"
                            >

                            @error('starts_at')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EXPIRES AT --}}
                        <div class="coupons-edit-field span-6">

                            <label for="expires_at"
                                   class="coupons-edit-label">

                                Expires At

                            </label>

                            <input
                                id="expires_at"
                                type="datetime-local"
                                name="expires_at"
                                class="coupons-edit-input @error('expires_at') is-invalid @enderror"
                                value="{{ old('expires_at') }}"
                            >

                            @error('expires_at')
                                <div class="coupons-edit-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STATUS --}}
                        <div class="coupons-edit-field span-6">

                            <label for="status"
                                   class="coupons-edit-label">

                                Status

                            </label>

                            <select
                                id="status"
                                name="status"
                                class="coupons-edit-select"
                            >

                                <option value="1"
                                    @selected(old('status', '1') == '1')>
                                    Active
                                </option>

                                <option value="0"
                                    @selected(old('status') == '0')>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="coupons-edit-footer">

                    <a href="{{ route('admin.coupons.index') }}"
                       class="coupons-edit-cancel">

                        <i class="bi bi-x-lg"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="coupons-edit-submit">

                        <i class="bi bi-check-circle"></i>
                        Create Coupon

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

