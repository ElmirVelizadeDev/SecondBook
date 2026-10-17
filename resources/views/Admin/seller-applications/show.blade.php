@extends('layout.admin.master')

@section('title', 'Seller Apply')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/pages/seller-applications.css') }}">
@endpush

@section('content')

<div class="dashboard-section seller-application-details-page">


{{-- =========================================================
     HEADER
========================================================== --}}
<div class="dashboard-panel mb-4">

    <div class="panel-header mb-0">

        <div>
            <h5 class="mb-1">Seller Application</h5>

            <p class="text-muted mb-0 small">
                Review the seller application details.
            </p>
        </div>

        <a
            href="{{ route('admin.seller-applications.index') }}"
            class="seller-application-back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Applications
        </a>

    </div>

</div>


<div class="row g-4">

    {{-- =====================================================
         APPLICANT INFORMATION
    ====================================================== --}}
    <div class="col-lg-4">

        <div class="dashboard-panel seller-detail-card">

            <div class="seller-detail-card-header">

                <div class="seller-detail-icon">
                    <i class="bi bi-person"></i>
                </div>

                <div>
                    <h5>Applicant</h5>
                    <span>User Information</span>
                </div>

            </div>


            <div class="seller-profile">

                <div class="seller-profile-avatar">

                    @if($application->user->profile_photo)

                        <img
                            src="{{ asset('storage/' . $application->user->profile_photo) }}"
                            alt="{{ $application->user->full_name }}"
                        >

                    @else

                        <span>
                            {{ strtoupper(substr($application->user->first_name ?? $application->user->name ?? 'U', 0, 1)) }}
                        </span>

                    @endif

                </div>

                <h4>
                    {{ $application->user->full_name ?: $application->user->name }}
                </h4>

                <p>
                    {{ $application->user->email }}
                </p>

            </div>


            <div class="seller-detail-list">

                <div class="seller-detail-item">

                    <span>
                        <i class="bi bi-person-badge"></i>
                        Username
                    </span>

                    <strong>
                        {{ $application->user->username ?? '—' }}
                    </strong>

                </div>


                <div class="seller-detail-item">

                    <span>
                        <i class="bi bi-telephone"></i>
                        Phone
                    </span>

                    <strong>
                        {{ $application->user->phone ?? '—' }}
                    </strong>

                </div>


                <div class="seller-detail-item">

                    <span>
                        <i class="bi bi-geo-alt"></i>
                        City
                    </span>

                    <strong>
                        {{ $application->user->city ?? '—' }}
                    </strong>

                </div>


                <div class="seller-detail-item">

                    <span>
                        <i class="bi bi-shield-check"></i>
                        Account Status
                    </span>

                    <strong class="seller-account-status {{ $application->user->status }}">
                        {{ ucfirst($application->user->status) }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         APPLICATION INFORMATION
    ====================================================== --}}
    <div class="col-lg-8">

        <div class="dashboard-panel seller-detail-card">

            <div class="seller-detail-card-header">

                <div class="seller-detail-icon store">
                    <i class="bi bi-shop"></i>
                </div>

                <div>
                    <h5>Store Application</h5>
                    <span>Submitted seller information</span>
                </div>

            </div>


            {{-- Status --}}
            <div class="seller-application-detail-status">

                <div>

                    <span class="seller-detail-label">
                        Application Status
                    </span>

                    @if($application->status === 'pending')

                        <span class="seller-application-badge pending">
                            <i class="bi bi-clock me-1"></i>
                            Pending
                        </span>

                    @elseif($application->status === 'approved')

                        <span class="seller-application-badge approved">
                            <i class="bi bi-check-circle me-1"></i>
                            Approved
                        </span>

                    @else

                        <span class="seller-application-badge rejected">
                            <i class="bi bi-x-circle me-1"></i>
                            Rejected
                        </span>

                    @endif

                </div>

                <div class="seller-submitted-date">

                    <span>Submitted</span>

                    <strong>
                        {{ $application->created_at->format('M d, Y H:i') }}
                    </strong>

                </div>

            </div>


            {{-- Store Name --}}
            <div class="seller-detail-field">

                <span class="seller-detail-label">
                    Store Name
                </span>

                <div class="seller-detail-value">
                    <i class="bi bi-shop"></i>
                    {{ $application->store_name }}
                </div>

            </div>


            {{-- Description --}}
            <div class="seller-detail-field">

                <span class="seller-detail-label">
                    Store Description
                </span>

                <div class="seller-description-box">

                    @if($application->description)

                        {{ $application->description }}

                    @else

                        <span class="text-muted">
                            No description provided.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Contact Information --}}
            <div class="row g-3">

                <div class="col-md-6">

                    <div class="seller-detail-field">

                        <span class="seller-detail-label">
                            Phone Number
                        </span>

                        <div class="seller-detail-value">

                            <i class="bi bi-telephone"></i>

                            {{ $application->phone ?: 'Not provided' }}

                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="seller-detail-field">

                        <span class="seller-detail-label">
                            Address
                        </span>

                        <div class="seller-detail-value">

                            <i class="bi bi-geo-alt"></i>

                            {{ $application->address ?: 'Not provided' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Rejection Reason --}}
            @if($application->status === 'rejected' && $application->rejection_reason)

                <div class="seller-rejection-box">

                    <div class="seller-rejection-title">

                        <i class="bi bi-exclamation-circle"></i>

                        Rejection Reason

                    </div>

                    <p>
                        {{ $application->rejection_reason }}
                    </p>

                </div>

            @endif


            {{-- Reviewed --}}
            @if($application->reviewed_at)

                <div class="seller-reviewed-info">

                    <i class="bi bi-check2-circle"></i>

                    <span>
                        Reviewed on
                        <strong>
                            {{ $application->reviewed_at->format('M d, Y H:i') }}
                        </strong>
                    </span>

                </div>

            @endif


            {{-- =================================================
                 ACTIONS
            ================================================== --}}
            @if($application->status === 'pending')

                <div class="seller-application-actions">

                    {{-- Approve --}}
                    <form
                        action="{{ route('admin.seller-applications.approve', $application) }}"
                        method="POST"
                        class="seller-approve-form"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="seller-approve-btn"
                        >
                            <i class="bi bi-check-circle me-1"></i>
                            Approve Application
                        </button>

                    </form>


                    {{-- Reject --}}
                    <button
                        type="button"
                        class="seller-reject-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectSellerModal"
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        Reject Application
                    </button>

                </div>

            @endif

        </div>

    </div>

</div>


</div>

{{-- =============================================================
REJECT MODAL
============================================================== --}}
@if($application->status === 'pending')


<div
    class="modal fade seller-reject-modal"
    id="rejectSellerModal"
    tabindex="-1"
    aria-labelledby="rejectSellerModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title"
                        id="rejectSellerModalLabel"
                    >
                        Reject Seller Application
                    </h5>

                    <p class="mb-0">
                        Please provide a reason for rejection.
                    </p>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <form
                action="{{ route('admin.seller-applications.reject', $application) }}"
                method="POST"
            >

                @csrf
                @method('PATCH')

                <div class="modal-body">

                    <label
                        for="rejection_reason"
                        class="form-label"
                    >
                        Rejection Reason
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        id="rejection_reason"
                        name="rejection_reason"
                        class="form-control"
                        rows="5"
                        placeholder="Explain why this seller application is being rejected..."
                        required
                    >{{ old('rejection_reason') }}</textarea>

                    @error('rejection_reason')
                        <small class="text-danger d-block mt-2">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="seller-modal-cancel-btn"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="seller-modal-reject-btn"
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        Reject Application
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@endif

@endsection
