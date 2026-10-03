<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Store;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Contact Us Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('Frontend.contact');
    }

    /*
    |--------------------------------------------------------------------------
    | Contact Us Form
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'subject' => [
                'required',
                'string',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ], [
            'name.required' => 'Please enter your name.',
            'name.max' => 'Your name may not exceed 100 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Your email address may not exceed 255 characters.',
            'subject.required' => 'Please enter a subject.',
            'subject.max' => 'The subject may not exceed 255 characters.',
            'message.required' => 'Please enter your message.',
            'message.min' => 'Your message must be at least 10 characters.',
            'message.max' => 'Your message may not exceed 5000 characters.',
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        return redirect()
            ->route('frontend.contact')
            ->with(
                'success',
                'Your message has been sent successfully. We will get back to you as soon as possible.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Send Message To Store
    |--------------------------------------------------------------------------
    */

    public function sendStoreMessage(Request $request, Store $store)
    {
        abort_unless($store->isActive(), 404);

        $validated = $request->validate([
            'subject' => [
                'required',
                'string',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ]);

        $user = auth()->user();

        Message::create([
            'user_id' => $user->id,
            'seller_id' => $store->seller_id,
            'name' => $user->full_name ?: $user->name,
            'email' => $user->email,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        return redirect()
            ->route('store.show', $store->slug)
            ->with(
                'success',
                'Your message has been sent to the store successfully.'
            );
    }
}