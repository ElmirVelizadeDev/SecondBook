<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display notifications.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        if (!in_array($filter, ['all', 'unread', 'read'])) {
            $filter = 'all';
        }

        $query = Notification::with('user')->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        if ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query
            ->paginate(15)
            ->withQueryString();

        $totalNotifications = Notification::count();

        $unreadNotifications = Notification::whereNull('read_at')->count();

        $readNotifications = Notification::whereNotNull('read_at')->count();

        $usersNotified = Notification::distinct('user_id')
            ->count('user_id');

        $users = User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);

        return view('admin.notifications.index', compact(
            'notifications',
            'filter',
            'totalNotifications',
            'unreadNotifications',
            'readNotifications',
            'usersNotified',
            'users'
        ));
    }

    /**
     * Send notification to one user or all active users.
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient' => [
                'required',
                'in:user,all',
            ],
            'user_id' => [
                'nullable',
                'exists:users,id',
            ],
            'type' => [
                'required',
                'in:general,order,payment,review,seller,promotion,system,success,warning',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
            ],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (
                $request->recipient === 'user' &&
                !$request->filled('user_id')
            ) {
                $validator->errors()->add(
                    'user_id',
                    'Please select a user.'
                );
            }
        });

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check the form fields.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $query = User::query()
            ->where('status', 'active');

        if ($request->recipient === 'user') {
            $query->where('id', $request->user_id);
        }

        $users = $query->get([
            'id',
        ]);

        if ($users->isEmpty()) {
            $message = 'No active users were found.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $message);
        }

        DB::transaction(function () use ($users, $request) {
            $now = now();

            $notifications = [];

            foreach ($users as $user) {
                $notifications[] = [
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'title' => $request->title,
                    'message' => $request->message,
                    'read_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Notification::insert($notifications);
        });

        $recipientText = $request->recipient === 'all'
            ? 'all active users'
            : 'the selected user';

        $this->activityLogService->log(
            'created',
            'Notifications',
            "Notification sent to {$recipientText}: \"{$request->title}\"."
        );

        $successMessage = "Notification sent successfully to {$recipientText}.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'title' => $request->title,
                'recipient' => $recipientText,
                'total_count' => Notification::count(),
                'unread_count' => Notification::whereNull('read_at')->count(),
                'read_count' => Notification::whereNotNull('read_at')->count(),
            ]);
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', $successMessage);
    }

    /**
     * Mark one notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if (is_null($notification->read_at)) {
            $notification->update([
                'read_at' => now(),
            ]);

            $this->activityLogService->log(
                'updated',
                'Notifications',
                "Notification \"{$notification->title}\" marked as read."
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
            'unread_count' => Notification::whereNull('read_at')->count(),
            'total_count' => Notification::count(),
            'read_count' => Notification::whereNotNull('read_at')->count(),
        ]);
    }

    /**
     * Mark one notification as unread.
     */
    public function markAsUnread(Notification $notification)
    {
        if (!is_null($notification->read_at)) {
            $notification->update([
                'read_at' => null,
            ]);

            $this->activityLogService->log(
                'updated',
                'Notifications',
                "Notification \"{$notification->title}\" marked as unread."
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread.',
            'unread_count' => Notification::whereNull('read_at')->count(),
            'total_count' => Notification::count(),
            'read_count' => Notification::whereNotNull('read_at')->count(),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $count = Notification::whereNull('read_at')->count();

        Notification::whereNull('read_at')->update([
            'read_at' => now(),
        ]);

        if ($count > 0) {
            $this->activityLogService->log(
                'updated',
                'Notifications',
                "All unread notifications marked as read ({$count} notifications)."
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
            'unread_count' => 0,
            'total_count' => Notification::count(),
            'read_count' => Notification::whereNotNull('read_at')->count(),
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy(Notification $notification)
    {
        $title = $notification->title;

        $this->activityLogService->log(
            'deleted',
            'Notifications',
            "Notification \"{$title}\" was deleted."
        );

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully.',
            'unread_count' => Notification::whereNull('read_at')->count(),
            'total_count' => Notification::count(),
            'read_count' => Notification::whereNotNull('read_at')->count(),
        ]);
    }
}
