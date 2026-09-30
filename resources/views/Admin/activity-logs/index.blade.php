@extends('layout.admin.master')

@section('title', 'Activity Logs')

@push('css')

    <link rel="stylesheet" href="{{ asset('admin/css/activity-logs.css') }}">

@endpush

@section('content')

<div class="dashboard-section activity-logs-page">

    {{-- Header --}}
    <div class="dashboard-panel mb-4">

        <div class="activity-logs-header">

            <div class="activity-logs-heading">

                <div class="activity-logs-icon">
                    <i class="bi bi-activity"></i>
                </div>

                <div>
                    <h2>Activity Logs</h2>

                    <p>
                        Monitor important activities performed in the system.
                    </p>
                </div>

            </div>

            <div class="activity-logs-count">

                <i class="bi bi-clock-history"></i>

                {{ $logs->total() }} Activities

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="dashboard-panel activity-logs-filter-panel mb-4">

        <form
            action="{{ route('admin.activity.logs.index') }}"
            method="GET"
            class="activity-logs-filter-form"
        >

            <div class="activity-filter-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search activities..."
                >

            </div>


            <div class="activity-filter-select">

                <select name="action">

                    <option value="">All Actions</option>

                    @foreach($actions as $action)

                        <option
                            value="{{ $action }}"
                            @selected(request('action') === $action)
                        >
                            {{ ucfirst($action) }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="activity-filter-select">

                <select name="module">

                    <option value="">All Modules</option>

                    @foreach($modules as $module)

                        <option
                            value="{{ $module }}"
                            @selected(request('module') === $module)
                        >
                            {{ $module }}
                        </option>

                    @endforeach

                </select>

            </div>


            <button
                type="submit"
                class="activity-filter-btn"
            >
                <i class="bi bi-funnel"></i>
                Filter
            </button>


            @if(request()->hasAny(['search', 'action', 'module']))

                <a
                    href="{{ route('admin.activity.logs.index') }}"
                    class="activity-clear-btn"
                >
                    <i class="bi bi-x-lg"></i>
                    Clear
                </a>

            @endif

        </form>

    </div>


    {{-- Activity Logs --}}
    <div class="dashboard-panel activity-logs-panel">

        <div class="activity-logs-table-wrapper">

            <table class="activity-logs-table">

                <thead>

                    <tr>

                        <th>User</th>

                        <th>Activity</th>

                        <th>Module</th>

                        <th>IP Address</th>

                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($logs as $log)

                        <tr>

                            <td>

                                <div class="activity-user">

                                    <div class="activity-user-avatar">

                                        @if($log->user?->profile_photo)

                                            <img
                                                src="{{ asset('storage/' . $log->user->profile_photo) }}"
                                                alt="{{ $log->user->name }}"
                                            >

                                        @else

                                            {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}

                                        @endif

                                    </div>

                                    <div class="activity-user-info">

                                        <strong>
                                            {{ $log->user?->name ?? 'System' }}
                                        </strong>

                                        <span>
                                            {{ $log->user?->email ?? 'System activity' }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <td>

                                <div class="activity-description">

                                    <span class="activity-action action-{{ strtolower($log->action) }}">
                                        {{ ucfirst($log->action) }}
                                    </span>

                                    <p>
                                        {{ $log->description }}
                                    </p>

                                </div>

                            </td>


                            <td>

                                @if($log->module)

                                    <span class="activity-module">
                                        {{ $log->module }}
                                    </span>

                                @else

                                    <span class="activity-empty">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="activity-ip">
                                    {{ $log->ip_address ?? '—' }}
                                </span>

                            </td>


                            <td>

                                <div class="activity-date">

                                    <strong>
                                        {{ $log->created_at->format('d M Y') }}
                                    </strong>

                                    <span>
                                        {{ $log->created_at->format('H:i') }}
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="activity-empty-state">

                                    <div class="activity-empty-icon">
                                        <i class="bi bi-activity"></i>
                                    </div>

                                    <h3>No activity logs found</h3>

                                    <p>
                                        There are no activities matching your current filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($logs->hasPages())

            <div class="activity-logs-pagination">

                {{-- Left: Showing --}}
                <div class="activity-pagination-info">
                    Showing
                    <strong>{{ $logs->firstItem() }}</strong>
                    to
                    <strong>{{ $logs->lastItem() }}</strong>
                    of
                    <strong>{{ $logs->total() }}</strong>
                    results
                </div>


                {{-- Right: Pages --}}
                <div class="activity-pagination-pages">

                    {{-- Previous --}}
                    @if($logs->onFirstPage())

                        <span class="activity-page disabled">
                            ‹
                        </span>

                    @else

                        <a
                            href="{{ $logs->previousPageUrl() }}"
                            class="activity-page"
                        >
                            ‹
                        </a>

                    @endif


                    {{-- Page Numbers --}}
                    @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)

                        @if($page == $logs->currentPage())

                            <span class="activity-page active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $url }}"
                                class="activity-page"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach


                    {{-- Next --}}
                    @if($logs->hasMorePages())

                        <a
                            href="{{ $logs->nextPageUrl() }}"
                            class="activity-page"
                        >
                            ›
                        </a>

                    @else

                        <span class="activity-page disabled">
                            ›
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

@endsection

