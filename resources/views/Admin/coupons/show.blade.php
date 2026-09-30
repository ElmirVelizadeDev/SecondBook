@extends('layout.admin.master')

@section('title', 'Coupon Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/coupons.css') }}">
@endpush

@section('content')

@php
    $couponStatus = 'inactive';

    if ($coupon->status) {
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            $couponStatus = 'expired';
        } elseif ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            $couponStatus = 'scheduled';
        } else {
            $couponStatus = 'active';
        }
    }

    $statusClasses = [
        'active'    => 'coupon-status-active',
        'inactive'  => 'coupon-status-inactive',
        'expired'   => 'coupon-status-expired',
        'scheduled' => 'coupon-status-scheduled',
    ];

    $statusClass = $statusClasses[$couponStatus];

    $statusLabels = [
        'active'    => 'Active',
        'inactive'  => 'Inactive',
        'expired'   => 'Expired',
        'scheduled' => 'Scheduled',
    ];
@endphp


<div class="coupons-show-page">

    {{-- HEADER --}}
    <div class="coupons-edit-header">

        <div class="coupons-edit-heading">

            <div class="coupons-edit-eyebrow">
                <i class="bi bi-ticket-perforated"></i>
                Coupon Management
            </div>

            <h1>Coupon Details</h1>

            <p>View complete coupon information.</p>

        </div>


        <div class="coupons-show-header-actions">

            <a href="{{ route('admin.coupons.index') }}"
               class="coupons-edit-back">

                <i class="bi bi-arrow-left"></i>

                <span>Back to Coupons</span>

            </a>


            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
               class="coupons-edit-submit">

                <i class="bi bi-pencil"></i>

                <span>Edit Coupon</span>

            </a>

        </div>

    </div>


    {{-- SUMMARY --}}
    <div class="coupons-show-summary">

        <div class="coupons-show-summary-item">

            <span>Coupon Code</span>

            <strong>{{ $coupon->code }}</strong>

        </div>


        <div class="coupons-show-summary-item">

            <span>Discount</span>

            <strong>

                @if($coupon->type === 'percentage')

                    {{ number_format($coupon->value, 0) }}%

                @else

                    ${{ number_format($coupon->value, 2) }}

                @endif

            </strong>

        </div>


        <div class="coupons-show-summary-item">

            <span>Usage</span>

            <strong>

                {{ $coupon->used_count }}

                /

                {{ $coupon->usage_limit ?? 'Unlimited' }}

            </strong>

        </div>


        <div class="coupons-show-summary-item">

            <span>Status</span>

            <em class="coupon-status-pill {{ $statusClass }}">

                <i class="bi bi-circle-fill"></i>

                {{ $statusLabels[$couponStatus] }}

            </em>

        </div>

    </div>


    {{-- CARDS --}}
    <div class="coupons-show-grid">


        {{-- Coupon Information --}}
        <div class="coupons-edit-card">

            <div class="coupons-edit-card-header">

                <div class="coupons-edit-card-icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>

                <div class="coupons-edit-card-title">

                    <strong>Coupon Information</strong>

                    <span>Code, discount and usage details</span>

                </div>

            </div>


            <div class="coupons-show-body">

                <div class="coupons-show-row">

                    <span>Coupon Code</span>

                    <strong>{{ $coupon->code }}</strong>

                </div>


                <div class="coupons-show-row">

                    <span>Discount Type</span>

                    <strong>
                        {{ ucfirst($coupon->type) }}
                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Discount Value</span>

                    <strong>

                        @if($coupon->type === 'percentage')

                            {{ number_format($coupon->value, 0) }}%

                        @else

                            ${{ number_format($coupon->value, 2) }}

                        @endif

                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Minimum Order</span>

                    <strong>
                        ${{ number_format($coupon->minimum_order_amount, 2) }}
                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Maximum Discount</span>

                    <strong>

                        @if($coupon->maximum_discount_amount !== null)

                            ${{ number_format($coupon->maximum_discount_amount, 2) }}

                        @else

                            No Limit

                        @endif

                    </strong>

                </div>

            </div>

        </div>


        {{-- Usage --}}
        <div class="coupons-edit-card">

            <div class="coupons-edit-card-header">

                <div class="coupons-edit-card-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <div class="coupons-edit-card-title">

                    <strong>Usage</strong>

                    <span>Coupon usage limits and activity</span>

                </div>

            </div>


            <div class="coupons-show-body">

                <div class="coupons-show-row">

                    <span>Used Count</span>

                    <strong>
                        {{ $coupon->used_count }}
                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Usage Limit</span>

                    <strong>

                        {{ $coupon->usage_limit ?? 'Unlimited' }}

                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Remaining</span>

                    <strong>

                        @if($coupon->usage_limit !== null)

                            {{ max(0, $coupon->usage_limit - $coupon->used_count) }}

                        @else

                            Unlimited

                        @endif

                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Status</span>

                    <em class="coupon-status-pill {{ $statusClass }}">

                        <i class="bi bi-circle-fill"></i>

                        {{ $statusLabels[$couponStatus] }}

                    </em>

                </div>

            </div>

        </div>


        {{-- Schedule --}}
        <div class="coupons-edit-card">

            <div class="coupons-edit-card-header">

                <div class="coupons-edit-card-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div class="coupons-edit-card-title">

                    <strong>Schedule</strong>

                    <span>Coupon availability period</span>

                </div>

            </div>


            <div class="coupons-show-body">

                <div class="coupons-show-row">

                    <span>Starts At</span>

                    <strong>

                        {{ $coupon->starts_at
                            ? $coupon->starts_at->format('d M Y, H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Expires At</span>

                    <strong>

                        {{ $coupon->expires_at
                            ? $coupon->expires_at->format('d M Y, H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Current Status</span>

                    <em class="coupon-status-pill {{ $statusClass }}">

                        <i class="bi bi-circle-fill"></i>

                        {{ $statusLabels[$couponStatus] }}

                    </em>

                </div>

            </div>

        </div>


        {{-- Dates --}}
        <div class="coupons-edit-card">

            <div class="coupons-edit-card-header">

                <div class="coupons-edit-card-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div class="coupons-edit-card-title">

                    <strong>Record Information</strong>

                    <span>Coupon creation and update dates</span>

                </div>

            </div>


            <div class="coupons-show-body">

                <div class="coupons-show-row">

                    <span>Created</span>

                    <strong>

                        {{ $coupon->created_at
                            ? $coupon->created_at->format('d M Y, H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="coupons-show-row">

                    <span>Last Updated</span>

                    <strong>

                        {{ $coupon->updated_at
                            ? $coupon->updated_at->format('d M Y, H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection