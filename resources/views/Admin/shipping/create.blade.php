@extends('layout.admin.master')

@section('title', 'Add Shipping')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/shipping.css') }}">
@endpush

@section('content')

<div class="shipping-page">

    {{-- HEADER --}}
    <div class="shipping-panel">

        <div class="shipping-panel-header">

            <div class="shipping-heading-content">

                <span class="eyebrow">
                    <i class="bi bi-truck me-1"></i>
                    Shipping Management
                </span>

                <h5>
                    Add Shipping
                </h5>

                <p>
                    Create a new shipping method with pricing,
                    delivery time and availability.
                </p>

            </div>

            <div class="shipping-header-action">

                <a
                    href="{{ route('admin.shipping.index') }}"
                    class="shipping-back-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Shipping</span>
                </a>

            </div>

        </div>


        {{-- FORM BODY --}}
        <div class="shipping-form-body">

            {{-- ERRORS --}}
            @if($errors->any())

                <div class="shipping-errors">

                    <div class="shipping-errors-title">
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
                action="{{ route('admin.shipping.store') }}"
                method="POST"
            >

                @csrf


                {{-- SHIPPING INFORMATION --}}
                <div class="shipping-form-section">

                    <div class="shipping-section-heading">
                        <i class="bi bi-truck"></i>
                        <span>Shipping Information</span>
                    </div>


                    <div class="shipping-form-grid">

                        {{-- NAME --}}
                        <div class="shipping-field">

                            <label for="name">
                                Shipping Method Name
                                <span class="required">*</span>
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="@error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="e.g. Standard Shipping"
                            >

                            @error('name')
                                <div class="shipping-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- PRICE --}}
                        <div class="shipping-field">

                            <label for="price">
                                Price
                                <span class="required">*</span>
                            </label>

                            <input
                                id="price"
                                type="number"
                                name="price"
                                step="0.01"
                                min="0"
                                class="@error('price') is-invalid @enderror"
                                value="{{ old('price', 0) }}"
                                placeholder="0.00"
                            >

                            @error('price')
                                <div class="shipping-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- DELIVERY TIME --}}
                        <div class="shipping-field">

                            <label for="delivery_time">
                                Delivery Time
                                <span class="required">*</span>
                            </label>

                            <input
                                id="delivery_time"
                                type="text"
                                name="delivery_time"
                                class="@error('delivery_time') is-invalid @enderror"
                                value="{{ old('delivery_time') }}"
                                placeholder="e.g. 3-5 Business Days"
                            >

                            @error('delivery_time')
                                <div class="shipping-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STATUS --}}
                        <div class="shipping-field">

                            <label for="status">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                            >

                                <option
                                    value="1"
                                    @selected(old('status', '1') == '1')
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected(old('status') == '0')
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="shipping-field shipping-field-full">

                            <label for="description">
                                Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Describe this shipping method..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="shipping-field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="shipping-form-footer">

                    <a
                        href="{{ route('admin.shipping.index') }}"
                        class="shipping-cancel-btn"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="shipping-submit-btn"
                    >
                        <i class="bi bi-check-circle"></i>
                        Create Shipping
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection