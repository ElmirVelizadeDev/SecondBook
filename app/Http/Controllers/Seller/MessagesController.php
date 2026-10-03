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

        /*
        |--------------------------------------------------------------------------
        | Messages
        |--------------------------------------------------------------------------
        */

        $query = Message::query()
            ->where('seller_id', $sellerId)
            ->with([
                'user:id,name,email,profile_photo',
            ])
            ->withCount('replies');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            in_array($request->input('status'), ['unread', 'read'], true)
        ) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Paginated Messages
        |--------------------------------------------------------------------------
        */

        $messages = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalMessages = Message::query()
            ->where('seller_id', $sellerId)
            ->count();

        $unreadMessages = Message::query()
            ->where('seller_id', $sellerId)
            ->where('status', 'unread')
            ->count();

        $readMessages = Message::query()
            ->where('seller_id', $sellerId)
            ->where('status', 'read')
            ->count();

        $todayMessages = Message::query()
            ->where('seller_id', $sellerId)
            ->whereDate('created_at', today())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Header Unread Count
        |--------------------------------------------------------------------------
        */

        $unreadMessagesCount = $unreadMessages;

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

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
        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $message->seller_id === (int) auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Mark Message As Read
        |--------------------------------------------------------------------------
        */

        if ($message->status === 'unread') {
            $message->update([
                'status' => 'read',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Load Conversation
        |--------------------------------------------------------------------------
        */

        $message->load([
            'user:id,name,email,profile_photo',

            'replies' => function ($query) {
                $query
                    ->with('user:id,name,email,profile_photo')
                    ->oldest();
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Unread Count
        |--------------------------------------------------------------------------
        */

        $unreadMessagesCount = Message::query()
            ->where('seller_id', auth()->id())
            ->where('status', 'unread')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'seller.messages.show',
            compact(
                'message',
                'unreadMessagesCount'
            )
        );
    }


    /**
     * Send a reply from seller to customer.
     */
    public function reply(
        Request $request,
        Message $message
    ) {
        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $message->seller_id === (int) auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Reply
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Create Seller Reply
        |--------------------------------------------------------------------------
        */

        $messageReply = $message->replies()->create([
            'user_id' => auth()->id(),
            'sender_type' => 'seller',
            'reply' => $validated['reply'],
            'read_at' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notify Customer
        |--------------------------------------------------------------------------
        */

        if ($message->user_id) {
            Notification::create([
                'user_id' => $message->user_id,
                'type' => 'seller_message_reply',
                'title' => 'New Seller Message',
                'message' => 'The seller replied to "' .
                    $message->subject .
                    '": ' .
                    $messageReply->reply,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

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
}