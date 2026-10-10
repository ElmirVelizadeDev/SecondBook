@extends('layout.admin.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/users.css') }}">
@endpush

@section('title', 'Add User')

@section('content')
<div class="dashboard-section users-page">
    <div class="dashboard-panel mb-4">
        <div class="panel-header mb-0">
            <div>
                <span class="eyebrow">Community directory</span>
                <h5 class="mb-1">Add user</h5>
                <p class="text-muted mb-0 small">Create a new SecondBook account.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left me-2"></i>Back to users</a>
        </div>
    </div>

    <div class="dashboard-panel user-form-panel">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('Admin.users._form')
        </form>
    </div>
</div>
@endsection
