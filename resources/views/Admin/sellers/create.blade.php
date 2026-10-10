@extends('layout.admin.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/sellers.css') }}">
@endpush

@section('title', 'Add Seller')

@section('content')
<div class="dashboard-section sellers-page">
    <div class="dashboard-panel mb-4">
        <div class="panel-header mb-0">
            <div>
                <span class="eyebrow">Seller management</span>
                <h5 class="mb-1">Add seller</h5>
                <p class="text-muted mb-0 small">Create a new marketplace seller account.</p>
            </div>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left me-2"></i>Back to sellers</a>
        </div>
    </div>

    <div class="dashboard-panel seller-form-panel">
        <form action="{{ route('admin.sellers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('Admin.sellers._form')
        </form>
    </div>
</div>
@endsection
