@extends('Layout.Seller.master')

@section('title', 'Messages')

@push('css') <link rel="stylesheet" href="{{ asset('seller/css/messages.css') }}">
@endpush

@section('content')

@php
$hasFilters = request()->filled('search') || request()->filled('status');
$currentStatus = request('status');
$currentSearch = request('search');
@endphp

<div class="seller-messages-page">


{{-- PAGE HEADER --}}
<div class="seller-page-heading">

    <div>
        <h1>Messages</h1>
        <p>Communicate with customers who contact your store.</p>
    </div>

    @if($unreadMessages > 0)
        <div class="seller-messages-heading-pill">
            <i></i>
            {{ $unreadMessages }} unread
            {{ $unreadMessages === 1 ? 'message' : 'messages' }}
        </div>
    @endif

</div>

{{-- STATISTICS --}}
<div class="row g-4 mb-4">

    {{-- Total --}}
    <div class="col-xl-3 col-md-6">
        <div class="seller-message-stat-card">

            <div class="seller-message-stat-icon">
                <i class="bi bi-chat-left-text"></i>
            </div>

            <div class="seller-message-stat-content">
                <span>Total Messages</span>
                <h3 class="messages-total-count">
                    {{ $totalMessages }}
                </h3>
            </div>

        </div>
    </div>

    {{-- Unread --}}
    <div class="col-xl-3 col-md-6">
        <div class="seller-message-stat-card">

            <div class="seller-message-stat-icon unread">
                <i class="bi bi-envelope"></i>
            </div>

            <div class="seller-message-stat-content">
                <span>Unread</span>
                <h3 class="messages-unread-count">
                    {{ $unreadMessages }}
                </h3>
            </div>

        </div>
    </div>

    {{-- Read --}}
    <div class="col-xl-3 col-md-6">
        <div class="seller-message-stat-card">

            <div class="seller-message-stat-icon read">
                <i class="bi bi-envelope-open"></i>
            </div>

            <div class="seller-message-stat-content">
                <span>Read</span>
                <h3 class="messages-read-count">
                    {{ $readMessages }}
                </h3>
            </div>

        </div>
    </div>

    {{-- Today --}}
    <div class="col-xl-3 col-md-6">
        <div class="seller-message-stat-card">

            <div class="seller-message-stat-icon today">
                <i class="bi bi-calendar-day"></i>
            </div>

            <div class="seller-message-stat-content">
                <span>Today</span>
                <h3 class="messages-today-count">
                    {{ $todayMessages }}
                </h3>
            </div>

        </div>
    </div>

</div>

{{-- MESSAGES PANEL --}}
<div class="seller-messages-panel">

    {{-- FILTER --}}
    <div class="seller-messages-filter">

        {{-- STATUS TABS --}}
        <div class="seller-message-tabs">

            <a
                href="{{ route('seller.messages.index') }}"
                class="seller-message-tab {{ !$currentStatus ? 'active' : '' }}"
            >
                All
                <em>{{ $totalMessages }}</em>
            </a>

            <a
                href="{{ route('seller.messages.index', ['status' => 'unread']) }}"
                class="seller-message-tab {{ $currentStatus === 'unread' ? 'active' : '' }}"
            >
                Unread
                <em>{{ $unreadMessages }}</em>
            </a>

            <a
                href="{{ route('seller.messages.index', ['status' => 'read']) }}"
                class="seller-message-tab {{ $currentStatus === 'read' ? 'active' : '' }}"
            >
                Read
                <em>{{ $readMessages }}</em>
            </a>

        </div>

        {{-- SEARCH --}}
        <form
            method="GET"
            action="{{ route('seller.messages.index') }}"
            class="seller-messages-search-form"
        >

            @if($currentStatus)
                <input
                    type="hidden"
                    name="status"
                    value="{{ $currentStatus }}"
                >
            @endif

            <div class="seller-message-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ $currentSearch }}"
                    placeholder="Search messages..."
                >

            </div>

            <button
                type="submit"
                class="seller-message-filter-button"
            >
                <i class="bi bi-search"></i>
                <span>Search</span>
            </button>

            @if($hasFilters)
                <a
                    href="{{ route('seller.messages.index') }}"
                    class="seller-message-reset-button"
                >
                    <i class="bi bi-x-lg"></i>
                    <span>Reset</span>
                </a>
            @endif

        </form>

    </div>

    {{-- MESSAGE LIST --}}
    @if($messages->count())

        <div class="seller-message-list">

            @foreach($messages as $message)

                @php
                    $senderName =
                        $message->name
                        ?: $message->user?->full_name
                        ?: $message->user?->name
                        ?: 'User';

                    $senderEmail =
                        $message->email
                        ?: $message->user?->email
                        ?: '—';

                    $senderInitial = strtoupper(
                        mb_substr($senderName, 0, 1)
                    );

                    $isUnread = $message->status === 'unread';
                @endphp

                <a
                    href="{{ route('seller.messages.show', $message) }}"
                    class="seller-message-item {{ $isUnread ? 'is-unread' : '' }}"
                >

                    {{-- Avatar --}}
                    <div class="seller-message-avatar">

                        @if($message->user?->profile_photo)

                            <img
                                src="{{ asset('storage/' . $message->user->profile_photo) }}"
                                alt="{{ $senderName }}"
                            >

                        @else

                            <span>{{ $senderInitial }}</span>

                        @endif

                        @if($isUnread)
                            <i class="seller-message-avatar-dot"></i>
                        @endif

                    </div>

                    {{-- Main Content --}}
                    <div class="seller-message-content">

                        <div class="seller-message-top">

                            <div class="seller-message-sender">

                                <strong>
                                    {{ $senderName }}
                                </strong>

                                @if($isUnread)
                                    <span class="seller-message-new-badge">
                                        New
                                    </span>
                                @endif

                            </div>

                            <time
                                datetime="{{ $message->created_at->toIso8601String() }}"
                            >
                                <i class="bi bi-clock"></i>
                                {{ $message->created_at->diffForHumans() }}
                            </time>

                        </div>

                        <div class="seller-message-subject">
                            {{ $message->subject ?: 'No Subject' }}
                        </div>

                        <p>
                            {{ \Illuminate\Support\Str::limit(
                                $message->message ?: 'No message content.',
                                150
                            ) }}
                        </p>

                        <div class="seller-message-meta">

                            <span>
                                <i class="bi bi-envelope"></i>
                                {{ $senderEmail }}
                            </span>

                            @if($message->replies_count > 0)

                                <span class="has-replies">
                                    <i class="bi bi-reply"></i>
                                    {{ $message->replies_count }}
                                    {{ $message->replies_count === 1 ? 'reply' : 'replies' }}
                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- Arrow --}}
                    <div class="seller-message-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>

                </a>

            @endforeach

        </div>

        {{-- PAGINATION --}}
        @if($messages->hasPages())

            <div class="seller-messages-pagination">
                {{ $messages->links() }}
            </div>

        @endif

    @else

        {{-- EMPTY STATE --}}
        <div class="seller-messages-empty">

            <div class="seller-messages-empty-icon">
                <i class="bi bi-chat-left-text"></i>
            </div>

            <h5>No Messages Found</h5>

            <p>
                You don't have any messages matching your current filters.
            </p>

            @if($hasFilters)

                <a
                    href="{{ route('seller.messages.index') }}"
                    class="seller-message-filter-button seller-message-clear-filter"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>Clear Filters</span>
                </a>

            @endif

        </div>

    @endif

</div>


</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', () => {

    const page = document.querySelector(
        '.seller-messages-page'
    );

    if (!page) {
        return;
    }

    /*
     * Load Messages
     */
    const loadPage = async (
        url,
        pushState = true
    ) => {

        try {

            page.classList.add('is-loading');

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            });

            if (!response.ok) {
                throw new Error(
                    'Page request failed.'
                );
            }

            const html = await response.text();

            const parser = new DOMParser();

            const documentHtml = parser.parseFromString(
                html,
                'text/html'
            );

            const newPage = documentHtml.querySelector(
                '.seller-messages-page'
            );

            if (!newPage) {
                throw new Error(
                    'Messages content not found.'
                );
            }

            page.innerHTML = newPage.innerHTML;

            if (pushState) {

                window.history.pushState(
                    {},
                    '',
                    url
                );

            }

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        } catch (error) {

            console.error(error);

            if (typeof Swal !== 'undefined') {

                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: 'Messages could not be loaded.',
                });

            } else {

                alert(
                    'Messages could not be loaded.'
                );

            }

        } finally {

            page.classList.remove('is-loading');

        }

    };

    /*
     * Status / Pagination / Reset / Clear Filters
     */
    page.addEventListener('click', (event) => {

        const link = event.target.closest(
            '.seller-message-tab, ' +
            '.seller-messages-pagination a, ' +
            '.seller-message-reset-button, ' +
            '.seller-message-clear-filter'
        );

        if (!link) {
            return;
        }

        if (
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey ||
            event.button !== 0
        ) {
            return;
        }

        event.preventDefault();

        loadPage(link.href);

    });

    /*
     * Search
     */
    page.addEventListener('submit', (event) => {

        const form = event.target;

        if (!form.matches(
            '.seller-messages-search-form'
        )) {
            return;
        }

        event.preventDefault();

        const formData = new FormData(form);

        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {

            if (
                typeof value === 'string' &&
                value.trim() !== ''
            ) {
                params.set(
                    key,
                    value.trim()
                );
            }

        }

        const queryString = params.toString();

        const url = queryString
            ? `${form.action}?${queryString}`
            : form.action;

        loadPage(url);

    });

    /*
     * Browser Back / Forward
     */
    window.addEventListener(
        'popstate',
        () => {

            loadPage(
                window.location.href,
                false
            );

        }
    );

});
</script>

@endpush
