@extends('Layout.Seller.master')

@section('title', 'Messages')

@push('css') @vite('resources/css/seller/pages/messages.css')
@endpush

@section('content')
<div class="seller-messages-page" data-vue-page="seller-messages-index-page" data-page-data="{{ base64_encode(json_encode([
    'indexUrl' => route('seller.messages.index'),
    'unreadUrl' => route('seller.messages.index', ['status' => 'unread']),
    'readUrl' => route('seller.messages.index', ['status' => 'read']),
    'status' => request('status', ''), 'search' => request('search', ''),
    'total' => $totalMessages, 'unread' => $unreadMessages, 'read' => $readMessages,
    'stats' => [
        ['label' => 'Total Messages', 'count' => $totalMessages, 'type' => '', 'icon' => 'bi-chat-left-text'],
        ['label' => 'Unread', 'count' => $unreadMessages, 'type' => 'unread', 'icon' => 'bi-envelope'],
        ['label' => 'Read', 'count' => $readMessages, 'type' => 'read', 'icon' => 'bi-envelope-open'],
        ['label' => 'Today', 'count' => $todayMessages, 'type' => 'today', 'icon' => 'bi-calendar-day'],
    ],
    'messages' => $messages->getCollection()->map(function ($message) {
        $sender = $message->name ?: $message->user?->full_name ?: $message->user?->name ?: 'User';
        return [
            'id' => $message->id, 'url' => route('seller.messages.show', $message),
            'sender' => $sender, 'initial' => mb_strtoupper(mb_substr($sender, 0, 1)),
            'photoUrl' => $message->user?->profile_photo ? asset('storage/' . $message->user->profile_photo) : null,
            'unread' => $message->status === 'unread', 'humanDate' => $message->created_at?->diffForHumans(),
            'subject' => $message->subject ?: 'No Subject',
            'preview' => \Illuminate\Support\Str::limit($message->message ?: 'No message content.', 150),
            'email' => $message->email ?: $message->user?->email ?: '—', 'replies' => $message->replies_count,
        ];
    })->values(),
    'links' => $messages->linkCollection()->toArray(),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
