@extends('Layout.Seller.master')

@section('title', 'Notifications')

@push('css') @vite('resources/css/seller/pages/notifications.css')
@endpush

@section('content')
<div class="seller-notifications-page" data-vue-page="seller-notifications-page" data-page-data="{{ base64_encode(json_encode([
    'indexUrl' => route('seller.notifications.index'),
    'unreadUrl' => route('seller.notifications.index', ['status' => 'unread']),
    'readUrl' => route('seller.notifications.index', ['status' => 'read']),
    'readAllUrl' => route('seller.notifications.read-all'),
    'deleteAllUrl' => route('seller.notifications.destroy-all'),
    'status' => request('status', ''), 'csrfToken' => csrf_token(), 'success' => session('success'),
    'total' => $totalNotifications ?? 0, 'unread' => $unreadNotifications ?? 0,
    'read' => $readNotifications ?? 0, 'today' => $todayNotifications ?? 0,
    'stats' => [
        ['label' => 'Total Notifications', 'count' => $totalNotifications ?? 0, 'type' => '', 'icon' => 'bi-bell'],
        ['label' => 'Unread', 'count' => $unreadNotifications ?? 0, 'type' => 'unread', 'icon' => 'bi-bell-fill'],
        ['label' => 'Read', 'count' => $readNotifications ?? 0, 'type' => 'read', 'icon' => 'bi-bell-slash'],
        ['label' => 'Today', 'count' => $todayNotifications ?? 0, 'type' => 'today', 'icon' => 'bi-calendar-day'],
    ],
    'notifications' => $notifications->getCollection()->map(function ($notification) {
        $data = is_array($notification->data) ? $notification->data : [];
        $type = $notification->type ?? 'default';
        $icon = match ($type) {
            'order', 'new_order' => 'bi-bag-check', 'sale' => 'bi-graph-up-arrow',
            'review' => 'bi-star-fill', 'message' => 'bi-chat-left-text', 'book' => 'bi-book',
            'approved', 'success' => 'bi-check-circle', 'rejected' => 'bi-x-circle',
            'warning' => 'bi-exclamation-triangle', default => 'bi-bell',
        };
        $unread = is_null($notification->read_at);
        return [
            'id' => $notification->id, 'unread' => $unread, 'icon' => $icon,
            'title' => $notification->title ?? ($data['title'] ?? 'Notification'),
            'message' => $notification->message ?? ($data['message'] ?? ($data['body'] ?? 'You have a new notification.')),
            'url' => $notification->url ?? ($data['url'] ?? null), 'type' => $type,
            'typeLabel' => ucwords(str_replace('_', ' ', $type)),
            'relativeDate' => $notification->created_at?->diffForHumans() ?? '—',
            'date' => $notification->created_at?->format('M d, Y · H:i') ?? '—',
            'statusUrl' => $unread ? route('seller.notifications.read', $notification) : route('seller.notifications.unread', $notification),
            'deleteUrl' => route('seller.notifications.destroy', $notification),
        ];
    })->values(),
    'links' => $notifications->linkCollection()->toArray(),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
