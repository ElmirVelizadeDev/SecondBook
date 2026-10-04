@extends('Layout.Seller.master')

@section('title', 'Store Settings')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/settings.css') }}">
@endpush

@section('content')

<div class="seller-settings-page">

    {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}
    <div class="seller-page-heading">

        <div>
            <h1>Store Settings</h1>
            <p>
                Manage how your store handles orders and sales.
            </p>
        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
         ===================================================== --}}
    @if(session('success'))

        <div class="seller-settings-alert seller-settings-alert-success">

            <div class="seller-settings-alert-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="seller-settings-alert-content">
                <strong>Changes saved</strong>
                <span>{{ session('success') }}</span>
            </div>

        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERRORS
         ===================================================== --}}
    @if($errors->any())

        <div class="seller-settings-alert seller-settings-alert-danger">

            <div class="seller-settings-alert-icon">
                <i class="bi bi-exclamation-lg"></i>
            </div>

            <div class="seller-settings-alert-content">

                <strong>Please check the following:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         SETTINGS FORM
         ===================================================== --}}
    <form
        action="{{ route('seller.settings.update') }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        {{-- =================================================
             ORDER SETTINGS
             ================================================= --}}
        <div class="seller-settings-card">

            {{-- Card Header --}}
            <div class="seller-settings-card-header">

                <div class="seller-settings-card-title">

                    <div class="seller-settings-card-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div>
                        <h5>Order Settings</h5>

                        <p>
                            Control how your store receives and processes customer orders.
                        </p>
                    </div>

                </div>

                <span class="seller-settings-section-badge">
                    <i class="bi bi-sliders"></i>
                    Store
                </span>

            </div>


            {{-- Card Body --}}
            <div class="seller-settings-card-body">


                {{-- =========================================
                     ACCEPT ORDERS
                     ========================================= --}}
                <div class="seller-setting-row">

                    <div class="seller-setting-info">

                        <div class="seller-setting-label">
                            <div class="seller-setting-small-icon">
                                <i class="bi bi-cart-check"></i>
                            </div>

                            <div>
                                <h6>Accept Orders</h6>

                                <p>
                                    Allow customers to place new orders from your store.
                                </p>
                            </div>
                        </div>

                    </div>

                    <label class="seller-settings-toggle">

                        <input
                            type="checkbox"
                            name="accept_orders"
                            value="1"
                            id="acceptOrders"
                            {{ old('accept_orders', $store->accept_orders) ? 'checked' : '' }}
                        >

                        <span class="seller-settings-toggle-slider"></span>

                    </label>

                </div>


                {{-- =========================================
                     AUTO APPROVE
                     ========================================= --}}
                <div class="seller-setting-row">

                    <div class="seller-setting-info">

                        <div class="seller-setting-label">

                            <div class="seller-setting-small-icon">
                                <i class="bi bi-lightning-charge"></i>
                            </div>

                            <div>
                                <h6>Auto Approve Orders</h6>

                                <p>
                                    Automatically approve new orders without manual confirmation.
                                </p>
                            </div>

                        </div>

                    </div>

                    <label class="seller-settings-toggle">

                        <input
                            type="checkbox"
                            name="auto_approve_orders"
                            value="1"
                            id="autoApproveOrders"
                            {{ old('auto_approve_orders', $store->auto_approve_orders) ? 'checked' : '' }}
                        >

                        <span class="seller-settings-toggle-slider"></span>

                    </label>

                </div>


                {{-- =========================================
                     PROCESSING TIME
                     ========================================= --}}
                <div class="seller-setting-field">

                    <div class="seller-setting-field-header">

                        <div>
                            <label for="processingTime">
                                Processing Time
                            </label>

                            <small>
                                Preparation time for new orders.
                            </small>
                        </div>

                        <span class="seller-settings-field-icon">
                            <i class="bi bi-clock-history"></i>
                        </span>

                    </div>

                    <div class="seller-input-group">

                        <input
                            type="number"
                            name="processing_time"
                            id="processingTime"
                            class="seller-settings-input"
                            min="1"
                            max="30"
                            value="{{ old('processing_time', $store->processing_time) }}"
                        >

                        <span>days</span>

                    </div>

                    <p class="seller-setting-help">
                        How many days you usually need to prepare an order.
                    </p>

                </div>


                {{-- =========================================
                     MINIMUM ORDER
                     ========================================= --}}
                <div class="seller-setting-field">

                    <div class="seller-setting-field-header">

                        <div>
                            <label for="minimumOrderAmount">
                                Minimum Order Amount
                            </label>

                            <small>
                                Set the minimum purchase amount required.
                            </small>
                        </div>

                        <span class="seller-settings-field-icon">
                            <i class="bi bi-cash-stack"></i>
                        </span>

                    </div>

                    <div class="seller-input-group">

                        <span>₼</span>

                        <input
                            type="number"
                            name="minimum_order_amount"
                            id="minimumOrderAmount"
                            class="seller-settings-input"
                            min="0"
                            step="0.01"
                            value="{{ old('minimum_order_amount', $store->minimum_order_amount) }}"
                        >

                    </div>

                    <p class="seller-setting-help">
                        Use <strong>0</strong> to disable the minimum order requirement.
                    </p>

                </div>


                {{-- =========================================
                     ORDER NOTE
                     ========================================= --}}
                <div class="seller-setting-field seller-setting-field-last">

                    <div class="seller-setting-field-header">

                        <div>
                            <label for="orderNote">
                                Order Note
                            </label>

                            <small>
                                Add useful instructions related to customer orders.
                            </small>
                        </div>

                        <span class="seller-settings-field-icon">
                            <i class="bi bi-card-text"></i>
                        </span>

                    </div>

                    <textarea
                        name="order_note"
                        id="orderNote"
                        class="seller-settings-textarea"
                        rows="5"
                        maxlength="2000"
                        placeholder="Add instructions or information related to customer orders..."
                    >{{ old('order_note', $store->order_note) }}</textarea>

                    <div class="seller-settings-textarea-footer">
                        <p class="seller-setting-help">
                            This note can be used for internal order-related instructions.
                        </p>

                        <span class="seller-settings-character-limit">
                            Max 2000 characters
                        </span>
                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             SAVE ACTION
             ================================================= --}}
        <div class="seller-settings-actions">

            <div class="seller-settings-actions-info">

                <div class="seller-settings-actions-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <strong>Keep your store preferences up to date</strong>
                    <span>
                        Changes will apply to your store after saving.
                    </span>
                </div>

            </div>

            <button
                type="submit"
                class="seller-save-button"
            >
                <i class="bi bi-check2-circle"></i>
                <span>Save Changes</span>
            </button>

        </div>

    </form>

</div>

@endsection