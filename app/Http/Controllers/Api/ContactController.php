<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Store;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        Message::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.',
        ], 201);
    }

    public function storeMessage(Request $request, Store $store)
    {
        abort_unless($store->isActive(), 404);

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $user = $request->user();
        $message = Message::create([
            'user_id' => $user->id,
            'seller_id' => $store->seller_id,
            'name' => $user->full_name ?: $user->name,
            'email' => $user->email,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        Notification::create([
            'user_id' => $store->seller_id,
            'type' => 'new_message',
            'title' => 'New Customer Message',
            'message' => ($user->full_name ?: $user->name)
                . ' sent you a new message: "' . $message->subject . '"',
            'read_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent to the store.',
        ], 201);
    }
}
