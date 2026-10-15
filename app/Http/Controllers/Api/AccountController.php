<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\SellerApplication;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function user(Request $request)
    {
        return response()->json([
            'data' => $request->user()->only([
                'id',
                'name',
                'first_name',
                'last_name',
                'username',
                'email',
                'role',
                'phone',
                'profile_photo',
                'date_of_birth',
                'gender',
                'country',
                'city',
                'state',
                'postal_code',
                'address',
                'bio',
                'email_verified_at',
                'created_at',
            ]),
        ]);
    }

    public function notifications(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->whereNull('archived_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $notifications->getCollection()->transform(fn (Notification $notification) => $notification->only([
            'id',
            'type',
            'title',
            'message',
            'read_at',
            'created_at',
        ]));

        return response()->json([
            'notifications' => BookController::pagination($notifications),
            'unread_count' => Notification::where('user_id', $request->user()->id)
                ->whereNull('read_at')
                ->whereNull('archived_at')
                ->count(),
        ]);
    }

    public function markNotificationRead(Notification $notification, Request $request)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['read_at' => $notification->read_at ?? now()]);

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function deleteNotification(Notification $notification, Request $request)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function preferences(Request $request)
    {
        $settings = UserSetting::firstOrNew(
            ['user_id' => $request->user()->id],
            [
                'email_notifications' => true,
                'order_updates' => true,
                'promotional_emails' => false,
                'profile_visible' => true,
            ]
        );

        return response()->json([
            'data' => $settings->only([
                'email_notifications',
                'order_updates',
                'promotional_emails',
                'profile_visible',
            ]),
        ]);
    }

    public function sellerApplication(Request $request)
    {
        $application = SellerApplication::where('user_id', $request->user()->id)
            ->latest()
            ->first();

        return response()->json([
            'data' => $application?->only([
                'id',
                'store_name',
                'description',
                'phone',
                'address',
                'status',
                'rejection_reason',
                'reviewed_at',
                'created_at',
                'updated_at',
            ]),
        ]);
    }
}
