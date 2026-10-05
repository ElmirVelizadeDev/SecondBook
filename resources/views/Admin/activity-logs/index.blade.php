@extends('layout.admin.master')

@section('title', 'Activity Logs')

@push('css') <link rel="stylesheet" href="{{ asset('admin/css/activity-logs.css') }}">
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
            <span class="activity-total-count">
                {{ $logs->total() }}
            </span>
            Activities
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
            <input
                type="hidden"
                name="action"
                value="{{ request('action') }}"
            >

            <button
                type="button"
                class="activity-filter-dropdown-toggle"
                aria-haspopup="listbox"
                aria-expanded="false"
            >
                <span class="activity-filter-dropdown-label">
                    {{ request('action') ? ucfirst(request('action')) : 'All Actions' }}
                </span>

                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="activity-filter-dropdown-menu" role="listbox">
                <button
                    type="button"
                    class="activity-filter-dropdown-option {{ !request('action') ? 'active' : '' }}"
                    data-value=""
                >
                    All Actions
                </button>

                @foreach($actions as $action)
                    <button
                        type="button"
                        class="activity-filter-dropdown-option {{ request('action') === $action ? 'active' : '' }}"
                        data-value="{{ $action }}"
                    >
                        {{ ucfirst($action) }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="activity-filter-select">
            <input
                type="hidden"
                name="module"
                value="{{ request('module') }}"
            >

            <button
                type="button"
                class="activity-filter-dropdown-toggle"
                aria-haspopup="listbox"
                aria-expanded="false"
            >
                <span class="activity-filter-dropdown-label">
                    {{ request('module') ?: 'All Modules' }}
                </span>

                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="activity-filter-dropdown-menu" role="listbox">
                <button
                    type="button"
                    class="activity-filter-dropdown-option {{ !request('module') ? 'active' : '' }}"
                    data-value=""
                >
                    All Modules
                </button>

                @foreach($modules as $module)
                    <button
                        type="button"
                        class="activity-filter-dropdown-option {{ request('module') === $module ? 'active' : '' }}"
                        data-value="{{ $module }}"
                    >
                        {{ $module }}
                    </button>
                @endforeach
            </div>
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
                        class="activity-page activity-pagination-link"
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
                            class="activity-page activity-pagination-link"
                        >
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($logs->hasMorePages())
                    <a
                        href="{{ $logs->nextPageUrl() }}"
                        class="activity-page activity-pagination-link"
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

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const page = document.querySelector('.activity-logs-page');

    if (!page) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Close Dropdowns
    |--------------------------------------------------------------------------
    */

    function closeDropdowns(except = null) {
        document.querySelectorAll('.activity-filter-select.is-open').forEach(function (dropdown) {
            if (dropdown !== except) {
                dropdown.classList.remove('is-open');

                const toggle = dropdown.querySelector(
                    '.activity-filter-dropdown-toggle'
                );

                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Dropdown
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const toggle = event.target.closest(
            '.activity-filter-dropdown-toggle'
        );

        if (toggle) {
            const dropdown = toggle.closest(
                '.activity-filter-select'
            );

            if (!dropdown) {
                return;
            }

            const isOpen = dropdown.classList.contains('is-open');

            closeDropdowns(dropdown);

            dropdown.classList.toggle(
                'is-open',
                !isOpen
            );

            toggle.setAttribute(
                'aria-expanded',
                String(!isOpen)
            );

            return;
        }

        const option = event.target.closest(
            '.activity-filter-dropdown-option'
        );

        if (option) {
            const dropdown = option.closest(
                '.activity-filter-select'
            );

            if (!dropdown) {
                return;
            }

            const hiddenInput = dropdown.querySelector(
                'input[type="hidden"]'
            );

            const label = dropdown.querySelector(
                '.activity-filter-dropdown-label'
            );

            const options = dropdown.querySelectorAll(
                '.activity-filter-dropdown-option'
            );

            if (hiddenInput) {
                hiddenInput.value = option.dataset.value || '';
            }

            if (label) {
                label.textContent = option.textContent.trim();
            }

            options.forEach(function (item) {
                item.classList.remove('active');
            });

            option.classList.add('active');

            dropdown.classList.remove('is-open');

            const toggleButton = dropdown.querySelector(
                '.activity-filter-dropdown-toggle'
            );

            if (toggleButton) {
                toggleButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }

            return;
        }

        if (!event.target.closest('.activity-filter-select')) {
            closeDropdowns();
        }
    });

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeDropdowns();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Load Activity Logs
    |--------------------------------------------------------------------------
    */

    function loadActivityLogs(url, updateHistory = true) {

        const panel = document.querySelector(
            '.activity-logs-panel'
        );

        const filterPanel = document.querySelector(
            '.activity-logs-filter-panel'
        );

        if (!panel || !filterPanel) {
            return;
        }

        closeDropdowns();

        panel.classList.add('is-loading');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
            .then(function (response) {

                if (!response.ok) {
                    throw new Error(
                        'Failed to load activity logs.'
                    );
                }

                return response.text();
            })
            .then(function (html) {

                const parser = new DOMParser();

                const documentHtml = parser.parseFromString(
                    html,
                    'text/html'
                );

                const newPanel = documentHtml.querySelector(
                    '.activity-logs-panel'
                );

                const newFilterPanel = documentHtml.querySelector(
                    '.activity-logs-filter-panel'
                );

                const newCount = documentHtml.querySelector(
                    '.activity-total-count'
                );

                if (!newPanel || !newFilterPanel) {
                    throw new Error(
                        'Activity logs content not found.'
                    );
                }

                panel.replaceWith(newPanel);
                filterPanel.replaceWith(newFilterPanel);

                if (newCount) {

                    const currentCount = document.querySelector(
                        '.activity-total-count'
                    );

                    if (currentCount) {
                        currentCount.textContent =
                            newCount.textContent.trim();
                    }
                }

                if (updateHistory) {
                    window.history.pushState(
                        {},
                        '',
                        url
                    );
                }

                window.scrollTo({
                    top: page.offsetTop,
                    behavior: 'smooth'
                });
            })
            .catch(function () {
                window.location.href = url;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const paginationLink = event.target.closest(
            '.activity-pagination-link'
        );

        if (!paginationLink) {
            return;
        }

        event.preventDefault();

        loadActivityLogs(
            paginationLink.href,
            true
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    document.addEventListener('submit', function (event) {

        const form = event.target.closest(
            '.activity-logs-filter-form'
        );

        if (!form) {
            return;
        }

        event.preventDefault();

        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {

            if (String(value).trim() !== '') {
                params.set(
                    key,
                    String(value).trim()
                );
            }
        }

        const actionUrl = form.getAttribute('action');

        const url = actionUrl + (
            params.toString()
                ? '?' + params.toString()
                : ''
        );

        loadActivityLogs(
            url,
            true
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Clear Filters
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const clearButton = event.target.closest(
            '.activity-clear-btn'
        );

        if (!clearButton) {
            return;
        }

        event.preventDefault();

        loadActivityLogs(
            clearButton.href,
            true
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Browser Back / Forward
    |--------------------------------------------------------------------------
    */

    window.addEventListener('popstate', function () {

        loadActivityLogs(
            window.location.href,
            false
        );
    });

});
</script>

@endpush
