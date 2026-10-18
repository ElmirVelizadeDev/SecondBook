@extends('Layout.Seller.master')

@section('title', 'Message Details')

@push('css') <link rel="stylesheet" href="{{ asset('seller/css/pages/messages.css') }}">
@endpush

@section('content')
<div class="seller-message-show-page" data-vue-page="seller-message-details-page" data-page-data="{{ base64_encode(json_encode([
    'indexUrl' => route('seller.messages.index'), 'replyUrl' => route('seller.messages.reply', $message),
    'csrfToken' => csrf_token(), 'oldReply' => old('reply', ''), 'replyError' => $errors->first('reply'),
    'message' => [
        'name' => $message->name ?: 'Unknown Customer', 'initial' => strtoupper(substr($message->name ?: 'U', 0, 1)),
        'photoUrl' => $message->user?->profile_photo ? asset('storage/' . $message->user->profile_photo) : null,
        'unread' => $message->status === 'unread', 'email' => $message->email,
        'subject' => $message->subject ?: 'No Subject', 'body' => $message->message,
        'createdAt' => $message->created_at?->format('M d, Y · H:i') ?? '—',
    ],
    'info' => [
        ['label' => 'Email', 'value' => $message->email, 'icon' => 'bi-envelope'],
        ['label' => 'Subject', 'value' => $message->subject ?: '—', 'icon' => 'bi-card-heading'],
        ['label' => 'Received', 'value' => $message->created_at?->format('M d, Y · H:i') ?? '—', 'icon' => 'bi-calendar3'],
    ],
    'replies' => $message->replies->map(function ($item) use ($message) {
        $seller = $item->sender_type === 'seller';
        $name = $item->user?->full_name ?: $item->user?->name;
        $initial = strtoupper(substr($name ?: ($seller ? 'S' : 'U'), 0, 1));
        return [
            'id' => $item->id, 'seller' => $seller, 'name' => $name ?: ($seller ? 'Seller' : 'Customer'),
            'initial' => $initial, 'body' => $item->reply, 'createdAt' => $item->created_at?->format('M d, Y · H:i'),
            'deleteUrl' => $seller ? route('seller.messages.delete-reply', ['messageId' => $message->id, 'replyId' => $item->id]) : null,
        ];
    })->values(),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
