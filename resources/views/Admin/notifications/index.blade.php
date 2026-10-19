@extends('Layout.Admin.master')

@section('title', 'Notifications')

@push('css')
@vite('resources/css/admin/pages/notifications.css')
@endpush

@section('content')
<div class="dashboard-section notifications-page" data-vue-page="notifications-admin"
     data-index-url="{{ route('admin.notifications.index') }}"
     data-send-url="{{ route('admin.notifications.send') }}"
     data-read-all-url="{{ route('admin.notifications.read-all') }}"
     data-csrf="{{ csrf_token() }}"></div>
@endsection

@push('scripts')
@vite('resources/js/app.js')
@endpush
