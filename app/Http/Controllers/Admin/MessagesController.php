<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MessageReplyMail;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Messages List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Message::query()
            ->with(['user'])
            ->withCount('replies');
            

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (
            $request->filled('status') &&
            in_array($request->status, ['unread', 'read'], true)
        ) {
            $query->where('status', $request->status);
        }

        $messages = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalMessages = Message::count();

        $unreadMessages = Message::where(
            'status',
            'unread'
        )->count();

        $readMessages = Message::where(
            'status',
            'read'
        )->count();

        $todayMessages = Message::whereDate(
            'created_at',
            today()
        )->count();

        return view(
            'admin.messages.index',
            compact(
                'messages',
                'totalMessages',
                'unreadMessages',
                'readMessages',
                'todayMessages'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(Message $message)
    {
        /*
        |--------------------------------------------------------------------------
        | Opening an unread message marks it as read
        |--------------------------------------------------------------------------
        */

        if ($message->status === 'unread') {
            $message->status = 'read';
            $message->save();

            $this->activityLogService->log(
                'updated',
                'Messages',
                "Message \"{$message->subject}\" was marked as read."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Find the user connected to this message
        |--------------------------------------------------------------------------
        |
        | New messages should already have user_id.
        | Older messages may have user_id = NULL,
        | so try to find the user by email.
        |
        */

        $messageUser = $message->user;

        if (!$messageUser) {
            $messageUser = User::where(
                'email',
                $message->email
            )->first();
        }

        return view(
            'admin.messages.show',
            compact(
                'message',
                'messageUser'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mark As Unread
    |--------------------------------------------------------------------------
    */

    public function markAsUnread(Message $message)
    {
        $message->status = 'unread';
        $message->save();

        $this->activityLogService->log(
            'updated',
            'Messages',
            "Message \"{$message->subject}\" was marked as unread."
        );

        return redirect()
            ->route('admin.messages.index')
            ->with(
                'success',
                'Message marked as unread successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Reply via Gmail
    |--------------------------------------------------------------------------
    */

    public function reply(Message $message)
    {
        return view(
            'admin.messages.reply',
            compact('message')
        );
    }

    public function sendReply(
        Request $request,
        Message $message
    ) {
        $validated = $request->validate([
            'subject' => [
                'required',
                'string',
                'max:255',
            ],
            'reply' => [
                'required',
                'string',
                'max:10000',
            ],
        ]);

        Mail::to($message->email)->send(
            new MessageReplyMail(
                $validated['reply'],
                $validated['subject']
            )
        );

        $this->activityLogService->log(
            'updated',
            'Messages',
            "Email reply was sent for message \"{$message->subject}\" to {$message->email}."
        );

        return redirect()
            ->route('admin.messages.show', $message)
            ->with(
                'success',
                'Reply sent successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Message $message)
    {
        $messageSubject = $message->subject;

        $this->activityLogService->log(
            'deleted',
            'Messages',
            "Message \"{$messageSubject}\" was deleted."
        );

        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with(
                'success',
                'Message deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Site Reply
    |--------------------------------------------------------------------------
    */

    public function siteReply(Message $message)
    {
        /*
        |--------------------------------------------------------------------------
        | Find the recipient user
        |--------------------------------------------------------------------------
        */

        $messageUser = $message->user;

        if (!$messageUser) {
            $messageUser = User::where(
                'email',
                $message->email
            )->first();
        }

        /*
        |--------------------------------------------------------------------------
        | User must have a SecondBook account
        |--------------------------------------------------------------------------
        */

        abort_unless($messageUser, 403);

        return view(
            'admin.messages.site-reply',
            compact(
                'message',
                'messageUser'
            )
        );
    }

    public function sendSiteReply(
        Request $request,
        Message $message
    ) {
        /*
        |--------------------------------------------------------------------------
        | Find the recipient user
        |--------------------------------------------------------------------------
        */

        $messageUser = $message->user;

        if (!$messageUser) {
            $messageUser = User::where(
                'email',
                $message->email
            )->first();
        }

        /*
        |--------------------------------------------------------------------------
        | User must have a SecondBook account
        |--------------------------------------------------------------------------
        */

        abort_unless($messageUser, 403);

        /*
        |--------------------------------------------------------------------------
        | Validate reply
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'reply' => [
                'required',
                'string',
                'min:2',
                'max:10000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save site reply
        |--------------------------------------------------------------------------
        */

        $messageReply = $message->replies()->create([
            'user_id' => auth()->id(),
            'sender_type' => 'admin',
            'reply' => $validated['reply'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send notification to message owner
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => $messageUser->id,
            'type' => 'contact_reply',
            'title' => 'Contact Message Reply',
            'message' => 'Reply to "' .
                $message->subject .
                '": ' .
                $messageReply->reply,
        ]);

        $this->activityLogService->log(
            'updated',
            'Messages',
            "Site reply was sent for message \"{$message->subject}\" to {$messageUser->name}."
        );

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.messages.show', $message)
            ->with(
                'success',
                'Reply sent successfully in site.'
            );
    }
}