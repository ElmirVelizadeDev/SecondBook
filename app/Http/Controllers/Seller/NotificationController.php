<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display seller notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $sixMonthsAgo = now()->subMonths(6);

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $notificationQuery = $user->notifications()
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo);

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            in_array($request->input('status'), ['unread', 'read'], true)
        ) {
            if ($request->input('status') === 'unread') {
                $notificationQuery->whereNull('read_at');
            }

            if ($request->input('status') === 'read') {
                $notificationQuery->whereNotNull('read_at');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $notifications = $notificationQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $baseQuery = $user->notifications()
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo);

        $totalNotifications = (clone $baseQuery)->count();

        $unreadNotifications = (clone $baseQuery)
            ->whereNull('read_at')
            ->count();

        $readNotifications = (clone $baseQuery)
            ->whereNotNull('read_at')
            ->count();

        $todayNotifications = (clone $baseQuery)
            ->whereDate('created_at', today())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'seller.notifications.index',
            compact(
                'notifications',
                'totalNotifications',
                'unreadNotifications',
                'readNotifications',
                'todayNotifications'
            )
        );
    }

    /**
     * Mark one notification as read.
     */
    public function markAsRead(Request $request, string $notification)
    {
        $user = Auth::user();

        $notificationModel = $user->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notificationModel->update([
            'read_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }

    /**
     * Mark one notification as unread.
     */
    public function markAsUnread(Request $request, string $notification)
    {
        $user = Auth::user();

        $notificationModel = $user->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notificationModel->update([
            'read_at' => null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread.',
            ]);
        }

        return back()->with(
            'success',
            'Notification marked as unread.'
        );
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $updated = Auth::user()
            ->notifications()
            ->whereNull('archived_at')
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'updated' => $updated,
            ]);
        }

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }

    /**
     * Delete one notification.
     */
    public function destroy(Request $request, string $notification)
    {
        $user = Auth::user();

        $notificationModel = $user->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notificationModel->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted.',
            ]);
        }

        return back()->with(
            'success',
            'Notification deleted.'
        );
    }

    /**
     * Delete all active notifications.
     */
    public function destroyAll(Request $request)
    {
        $user = Auth::user();

        $deleted = $user->notifications()
            ->whereNull('archived_at')
            ->where('created_at', '>=', now()->subMonths(6))
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications deleted successfully.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()
            ->route('seller.notifications.index')
            ->with(
                'success',
                'All notifications deleted successfully.'
            );
    }
}