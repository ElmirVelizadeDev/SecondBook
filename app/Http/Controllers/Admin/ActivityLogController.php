<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $logs = $query
            ->paginate(15)
            ->withQueryString();

        $actions = ActivityLog::query()
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $modules = ActivityLog::query()
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        if ($request->expectsJson()) {
            return response()->json([
                'logs' => $logs->getCollection()->map(fn (ActivityLog $log) => [
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
                        'avatar' => $log->user?->profile_photo
                            ? asset('storage/' . $log->user->profile_photo)
                            : null,
                    ],
                ])->values(),
                'actions' => $actions,
                'modules' => $modules,
                'filters' => [
                    'search' => $request->input('search', ''),
                    'action' => $request->input('action', ''),
                    'module' => $request->input('module', ''),
                ],
                'pagination' => [
                    'total' => $logs->total(),
                    'first_item' => $logs->firstItem(),
                    'last_item' => $logs->lastItem(),
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'pages' => $logs->getUrlRange(1, $logs->lastPage()),
                    'previous' => $logs->previousPageUrl(),
                    'next' => $logs->nextPageUrl(),
                ],
            ]);
        }

        return view('Admin.activity-logs.index', compact(
            'logs',
            'actions',
            'modules'
        ));
    }
}

