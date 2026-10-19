@extends('Layout.Admin.master')

@section('title', 'Activity Logs')

@push('css')
    @vite('resources/css/admin/pages/activity-logs.css')
@endpush

@section('content')
    <div
        data-vue-page="activity-logs-admin"
        data-payload="{{ json_encode([
            'logs' => $logs->getCollection()->map(fn ($log) => [
                'id' => $log->id,
                'action' => $log->action,
                'module' => $log->module,
                'description' => $log->description,
                'ip_address' => $log->ip_address,
                'date' => $log->created_at?->format('d M Y'),
                'time' => $log->created_at?->format('H:i'),
                'user' => [
                    'name' => $log->user?->name ?? 'System',
                    'email' => $log->user?->email ?? 'System activity',
                    'avatar' => $log->user?->profile_photo ? asset('storage/' . $log->user->profile_photo) : null,
                ],
            ])->values(),
            'actions' => $actions,
            'modules' => $modules,
            'filters' => ['search' => request('search', ''), 'action' => request('action', ''), 'module' => request('module', '')],
            'pagination' => [
                'total' => $logs->total(), 'first_item' => $logs->firstItem(), 'last_item' => $logs->lastItem(),
                'current_page' => $logs->currentPage(), 'last_page' => $logs->lastPage(),
                'pages' => $logs->getUrlRange(1, $logs->lastPage()),
                'previous' => $logs->previousPageUrl(), 'next' => $logs->nextPageUrl(),
            ],
            'urls' => ['index' => route('admin.activity.logs.index')],
        ]) }}"
    ></div>
@endsection

@push('js')
    @vite('resources/js/app.js')
@endpush
