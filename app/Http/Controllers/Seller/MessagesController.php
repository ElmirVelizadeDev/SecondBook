<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display seller messages.
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();
        $sixMonthsAgo = now()->subMonths(6);

        /* Messages */
        $query = Message::query()
            ->where('seller_id', $sellerId)
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->with([
                'user:id,name,email,profile_photo',
            ])
            ->withCount('replies');

        /* Search */
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        /* Status Filter */
        if (
            $request->filled('status') &&
            in_array($request->input('status'), ['unread', 'read'], true)
        ) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        /* Paginated Messages */
        $messages = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        /* Statistics */
        $baseQuery = Message::query()
            ->where('seller_id', $sellerId)
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo);

        $totalMessages = (clone $baseQuery)->count();

        $unreadMessages = (clone $baseQuery)
            ->where('status', 'unread')
            ->count();

        $readMessages = (clone $baseQuery)
            ->where('status', 'read')
            ->count();

        $todayMessages = (clone $baseQuery)
            ->whereDate('created_at', today())
            ->count();

        /* Header Unread Count */
        $unreadMessagesCount = $unreadMessages;

        return view(
            'seller.messages.index',
            compact(
                'messages',
                'totalMessages',
                'unreadMessages',
                'readMessages',
                'todayMessages',
                'unreadMessagesCount'
            )
        );
    }

    /**
     * Display a single seller conversation.
     */
    public function show(Message $message)
    {
        /* Security */
        abort_unless(
            (int) $message->seller_id === (int) auth()->id(),
            403
        );

        /* Mark Message As Read */
        if ($message->status === 'unread') {
            $message->update([
                'status' => 'read',
            ]);
        }

        /* Load Conversation */
        $message->load([
            'user:id,name,email,profile_photo',
            'replies' => function ($query) {
                $query
                    ->with('user:id,name,email,profile_photo')
                    ->oldest();
            },
        ]);

        /* Unread Count */
        $unreadMessagesCount = Message::query()
            ->where('seller_id', auth()->id())
            ->whereNull('archived_at')
            ->where('created_at', '>=', now()->subMonths(6))
            ->where('status', 'unread')
            ->count();

        return view(
            'seller.messages.show',
            compact(
                'message',
                'unreadMessagesCount'
            )
        );
    }

    /**
     * Mark message as read via AJAX.
     */
    public function markAsRead(Message $message)
    {
        /* Security */
        abort_unless(
            (int) $message->seller_id === (int) auth()->id(),
            403
        );

        /* Update Status */
        $message->update([
            'status' => 'read',
        ]);

        return response()->json([
            'success' => true,
            'status' => 'read',
            'message' => 'Message marked as read.',
        ]);
    }

    /**
     * Mark message as unread via AJAX.
     */
    public function markAsUnread(Message $message)
    {
        /* Security */
        abort_unless(
            (int) $message->seller_id === (int) auth()->id(),
            403
        );

        /* Update Status */
        $message->update([
            'status' => 'unread',
        ]);

        return response()->json([
            'success' => true,
            'status' => 'unread',
            'message' => 'Message marked as unread.',
        ]);
    }

    /**
     * Send a reply from seller to customer.
     */
    public function reply(Request $request, Message $message)
    {
        /* Security */
        abort_unless(
            (int) $message->seller_id === (int) auth()->id(),
            403
        );

        /* Validate Reply */
        $validated = $request->validate(
            [
                'reply' => [
                    'required',
                    'string',
                    'min:2',
                    'max:10000',
                ],
            ],
            [
                'reply.required' => 'Please enter your reply.',
                'reply.min' => 'Your reply must be at least 2 characters.',
                'reply.max' => 'Your reply may not exceed 10,000 characters.',
            ]
        );

        /* Create Seller Reply */
        $messageReply = $message->replies()->create([
            'user_id' => auth()->id(),
            'sender_type' => 'seller',
            'reply' => $validated['reply'],
            'read_at' => null,
        ]);

        /* Load Reply User */
        $messageReply->load(
            'user:id,name,email,profile_photo'
        );

        $sellerName =
            $messageReply->user?->full_name
            ?: $messageReply->user?->name
            ?: 'Seller';

        $customerName =
            $message->user?->full_name
            ?: $message->user?->name
            ?: 'Customer';

        /* Notify Customer */
        if ($message->user_id) {
            Notification::create([
                'user_id' => $message->user_id,
                'type' => 'seller_message_reply',
                'title' => 'New Seller Message',
                'message' =>
                    'Seller: ' . $sellerName .
                    ' | Customer: ' . $customerName .
                    ' | Subject: "' . $message->subject .
                    '" | Message: ' . $messageReply->reply,
                'read_at' => null,
            ]);
        }

        /* AJAX Response */
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'reply' => [
                    'id' => $messageReply->id,
                    'name' => 'You',
                    'reply' => $messageReply->reply,
                    'created_at' => $messageReply->created_at?->format(
                        'M d, Y · H:i'
                    ),
                    'profile_photo' => $messageReply->user?->profile_photo
                        ? asset(
                            'storage/' .
                            $messageReply->user->profile_photo
                        )
                        : null,
                    'initial' => strtoupper(
                        substr(
                            $sellerName,
                            0,
                            1
                        )
                    ),
                ],
            ]);
        }

        /* Normal Request Fallback */
        return redirect()
            ->route(
                'seller.messages.show',
                $message
            )
            ->with(
                'success',
                'Reply sent successfully.'
            );
    }

    /**
     * Delete seller reply via AJAX.
     */
    public function deleteReply(string $message, string $reply)
    {
        $messageModel = Message::query()
            ->whereKey($message)
            ->where('seller_id', auth()->id())
            ->with('user:id,name,email,profile_photo')
            ->firstOrFail();

        $messageReply = $messageModel->replies()
            ->whereKey($reply)
            ->where('user_id', auth()->id())
            ->where('sender_type', 'seller')
            ->with('user:id,name,email,profile_photo')
            ->firstOrFail();

        if ($messageModel->user_id) {
            $sellerName =
                $messageReply->user?->full_name
                ?: $messageReply->user?->name
                ?: 'Seller';

            $customerName =
                $messageModel->user?->full_name
                ?: $messageModel->user?->name
                ?: 'Customer';

            $notificationMessage =
                'Seller: ' . $sellerName .
                ' | Customer: ' . $customerName .
                ' | Subject: "' . $messageModel->subject .
                '" | Message: ' . $messageReply->reply;

            Notification::query()
                ->where('user_id', $messageModel->user_id)
                ->where('type', 'seller_message_reply')
                ->where('title', 'New Seller Message')
                ->where('message', $notificationMessage)
                ->latest('created_at')
                ->first()?->delete();
        }

        $replyId = $messageReply->id;

        $messageReply->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reply deleted successfully.',
            'reply_id' => $replyId,
        ]);
    }
}
