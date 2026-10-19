@extends('Layout.Admin.master')
@section('title', 'Seller Apply')
@push('css')@vite('resources/css/admin/pages/seller-applications.css')@endpush
@section('content')
<div data-vue-page="seller-applications-admin" data-payload="{{ json_encode([
    'view' => 'index',
    'applications' => $applications->getCollection()->map(fn ($application) => [
        'id' => $application->id, 'store_name' => $application->store_name, 'phone' => $application->phone,
        'address' => $application->address, 'status' => $application->status,
        'date' => $application->created_at?->format('M d, Y'), 'time' => $application->created_at?->format('H:i'),
        'applicant' => [
            'name' => $application->user->full_name ?: $application->user->name,
            'email' => $application->user->email,
            'photo' => $application->user->profile_photo ? asset('storage/' . $application->user->profile_photo) : null,
            'initial' => strtoupper(substr($application->user->first_name ?? $application->user->name ?? 'U', 0, 1)),
        ],
        'show_url' => route('admin.seller-applications.show', $application),
    ])->values(),
    'counts' => ['total' => $totalCount, 'pending' => $pendingCount, 'approved' => $approvedCount, 'rejected' => $rejectedCount],
    'filters' => ['search' => request('search', ''), 'status' => request('status', '')],
    'pagination' => ['links' => $applications->linkCollection()->toArray()],
    'urls' => ['index' => route('admin.seller-applications.index')], 'success' => session('success'), 'error' => session('error'),
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
