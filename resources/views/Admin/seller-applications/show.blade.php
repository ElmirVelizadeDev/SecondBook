@extends('Layout.Admin.master')
@section('title', 'Seller Apply')
@push('css')@vite('resources/css/admin/pages/seller-applications.css')@endpush
@section('content')
<div data-vue-page="seller-applications-admin" data-payload="{{ json_encode([
    'view' => 'show',
    'application' => [
        'id' => $application->id, 'store_name' => $application->store_name, 'description' => $application->description,
        'phone' => $application->phone, 'address' => $application->address, 'status' => $application->status,
        'rejection_reason' => $application->rejection_reason,
        'submitted' => $application->created_at?->format('M d, Y H:i'), 'reviewed' => $application->reviewed_at?->format('M d, Y H:i'),
        'user' => [
            'name' => $application->user->full_name ?: $application->user->name,
            'email' => $application->user->email, 'username' => $application->user->username,
            'phone' => $application->user->phone, 'city' => $application->user->city,
            'status' => $application->user->status,
            'photo' => $application->user->profile_photo ? asset('storage/' . $application->user->profile_photo) : null,
            'initial' => strtoupper(substr($application->user->first_name ?? $application->user->name ?? 'U', 0, 1)),
        ],
    ],
    'old' => ['rejection_reason' => old('rejection_reason', '')], 'errors' => $errors->getMessages(),
    'urls' => ['index' => route('admin.seller-applications.index'), 'approve' => route('admin.seller-applications.approve', $application), 'reject' => route('admin.seller-applications.reject', $application)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
