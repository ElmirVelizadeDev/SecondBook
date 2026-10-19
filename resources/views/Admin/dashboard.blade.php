@extends('Layout.Admin.master')

@push('css')
    @vite('resources/css/admin/pages/dashboard.css')
@endpush

@section('title', 'Dashboard')

@section('content')
    <div
        data-vue-page="admin-dashboard"
        data-dashboard="{{ json_encode($dashboardData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
    >
        <div class="dashboard-section dx-page">
            <div class="dx-empty" role="status">Loading dashboard…</div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/js/app.js')
@endpush
