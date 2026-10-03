@extends('Layout.Seller.master')

@section('title', 'Message Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/message-show.css') }}">
@endpush

@section('content')

@php
    $customerName    = $message->name ?: 'Unknown Customer';
    $customerInitial = strtoupper(substr($message->name ?: 'U', 0, 1));
    $isUnread        = $message->status === 'unread';
    $repliesCount    = $message->replies->count();
@endphp

<div class="seller-message-show-page">

    {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}
    <div class="seller-page-heading">

        <div>
            <h1>Message Details</h1>
            <p>View the conversation and reply to your customer.</p>
        </div>

        <a href="{{ route('seller.messages.index') }}"
           class="seller-message-show-back">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Messages</span>
        </a>

    </div>


    <div class="row g-4">

        {{-- =================================================
             CUSTOMER / MESSAGE INFO
             ================================================= --}}
        <div class="col-xl-4">

            <aside class="seller-message-info-card">

                {{-- Cover --}}
                <div class="seller-message-info-cover"></div>

                {{-- Customer --}}
                <div class="seller-message-customer">

                    <div class="seller-message-customer-avatar">

                        @if($message->user?->profile_photo)
                            <img
                                src="{{ asset('storage/' . $message->user->profile_photo) }}"
                                alt="{{ $customerName }}"
                            >
                        @else
                            {{ $customerInitial }}
                        @endif

                    </div>

                    <div class="seller-message-customer-content">

                        <span>Customer</span>

                        <strong>{{ $customerName }}</strong>

                        @if($isUnread)
                            <span class="seller-message-status unread">
                                <i></i> Unread
                            </span>
                        @else
                            <span class="seller-message-status read">
                                <i></i> Read
                            </span>
                        @endif

                    </div>

                </div>


                {{-- Details --}}
                <div class="seller-message-info-list">

                    <div class="seller-message-info-item">
                        <div class="seller-message-info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="seller-message-info-text">
                            <span>Email</span>
                            @if($message->email)
                                <strong>
                                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                </strong>
                            @else
                                <strong>—</strong>
                            @endif
                        </div>
                    </div>

                    <div class="seller-message-info-item">
                        <div class="seller-message-info-icon">
                            <i class="bi bi-card-heading"></i>
                        </div>
                        <div class="seller-message-info-text">
                            <span>Subject</span>
                            <strong>{{ $message->subject ?: '—' }}</strong>
                        </div>
                    </div>

                    <div class="seller-message-info-item">
                        <div class="seller-message-info-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="seller-message-info-text">
                            <span>Received</span>
                            <strong>{{ $message->created_at?->format('M d, Y · H:i') ?? '—' }}</strong>
                        </div>
                    </div>

                    <div class="seller-message-info-item">
                        <div class="seller-message-info-icon">
                            <i class="bi bi-reply"></i>
                        </div>
                        <div class="seller-message-info-text">
                            <span>Replies</span>
                            <strong>{{ $repliesCount }} {{ \Illuminate\Support\Str::plural('reply', $repliesCount) }}</strong>
                        </div>
                    </div>

                </div>


                {{-- Quick action --}}
                <a href="#seller-reply-form" class="seller-message-quick-reply">
                    <i class="bi bi-send"></i>
                    Reply now
                </a>

            </aside>

        </div>


        {{-- =================================================
             CONVERSATION
             ================================================= --}}
        <div class="col-xl-8">

            <div class="seller-message-conversation-card">

                {{-- Conversation Header --}}
                <div class="seller-message-conversation-header">

                    <div class="seller-message-conversation-title">

                        <div class="seller-message-conversation-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </div>

                        <div>
                            <h5>Conversation</h5>
                            <p>
                                Communication with
                                {{ $message->name ?: 'customer' }}
                            </p>
                        </div>

                    </div>

                    <span class="seller-message-count">
                        {{ $repliesCount + 1 }}
                        {{ \Illuminate\Support\Str::plural('message', $repliesCount + 1) }}
                    </span>

                </div>


                {{-- Conversation Body --}}
                <div class="seller-message-conversation-body" id="sellerConversationBody">

                    {{-- Original Message --}}
                    <div class="seller-message-bubble customer">

                        <div class="seller-message-mini-avatar">

                            @if($message->user?->profile_photo)
                                <img
                                    src="{{ asset('storage/' . $message->user->profile_photo) }}"
                                    alt="{{ $customerName }}"
                                >
                            @else
                                {{ $customerInitial }}
                            @endif

                        </div>

                        <div class="seller-message-bubble-card">

                            <div class="seller-message-bubble-header">

                                <strong>{{ $message->name ?: 'Customer' }}</strong>

                                <span>{{ $message->created_at?->format('M d, Y · H:i') }}</span>

                            </div>

                            <div class="seller-message-bubble-subject">
                                <i class="bi bi-tag"></i>
                                {{ $message->subject ?: 'No Subject' }}
                            </div>

                            <div class="seller-message-bubble-text">
                                {!! nl2br(e($message->message)) !!}
                            </div>

                        </div>

                    </div>


                    {{-- Replies --}}
                    @forelse($message->replies as $reply)

                        @php
                            $isSeller  = $reply->sender_type === 'seller';
                            $replyName = $reply->user?->full_name ?: $reply->user?->name;
                            $initial   = strtoupper(substr($replyName ?: ($isSeller ? 'S' : 'U'), 0, 1));
                        @endphp

                        <div class="seller-message-bubble {{ $isSeller ? 'seller' : 'customer' }}">

                            <div class="seller-message-mini-avatar">

                                @if($reply->user?->profile_photo)
                                    <img
                                        src="{{ asset('storage/' . $reply->user->profile_photo) }}"
                                        alt="{{ $replyName }}"
                                    >
                                @else
                                    {{ $initial }}
                                @endif

                            </div>

                            <div class="seller-message-bubble-card">

                                <div class="seller-message-bubble-header">

                                    <strong>
                                        {{ $isSeller ? 'You' : ($replyName ?: 'Customer') }}
                                    </strong>

                                    <span>{{ $reply->created_at?->format('M d, Y · H:i') }}</span>

                                </div>

                                <div class="seller-message-bubble-text">
                                    {!! nl2br(e($reply->reply)) !!}
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="seller-message-no-replies">

                            <div class="seller-message-no-replies-icon">
                                <i class="bi bi-chat-dots"></i>
                            </div>

                            <h6>No Replies Yet</h6>

                            <p>This conversation has not received a reply yet.</p>

                        </div>

                    @endforelse

                </div>


                {{-- =================================================
                     REPLY FORM
                     ================================================= --}}
                <div class="seller-message-reply-section" id="seller-reply-form">

                    <div class="seller-message-reply-header">

                        <div class="seller-message-reply-header-icon">
                            <i class="bi bi-reply-fill"></i>
                        </div>

                        <div>
                            <h6>Reply to Customer</h6>
                            <p>Send a message directly to this customer.</p>
                        </div>

                    </div>


                    <form
                        action="{{ route('seller.messages.reply', $message) }}"
                        method="POST"
                        class="seller-message-reply-form"
                        id="sellerReplyForm"
                    >

                        @csrf

                        <div class="seller-message-reply-field @error('reply') has-error @enderror">

                            <textarea
                                name="reply"
                                id="sellerReplyTextarea"
                                rows="5"
                                placeholder="Write your reply..."
                                required
                            >{{ old('reply') }}</textarea>

                            @error('reply')
                                <div class="seller-message-reply-error">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="seller-message-reply-actions">

                            <span class="seller-message-reply-hint">
                                <kbd>Ctrl</kbd> + <kbd>Enter</kbd> to send
                                <em id="sellerReplyCount">0</em> characters
                            </span>

                            <div class="seller-message-reply-buttons">

                                <a
                                    href="{{ route('seller.messages.index') }}"
                                    class="seller-message-cancel-button"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="seller-message-send-button"
                                >
                                    <i class="bi bi-send"></i>
                                    <span>Send Reply</span>
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    (function () {
        var body     = document.getElementById('sellerConversationBody');
        var form     = document.getElementById('sellerReplyForm');
        var textarea = document.getElementById('sellerReplyTextarea');
        var counter  = document.getElementById('sellerReplyCount');

        // Scroll to the latest message
        if (body) {
            body.scrollTop = body.scrollHeight;
        }

        if (!form || !textarea) {
            return;
        }

        function update() {
            if (counter) {
                counter.textContent = textarea.value.length;
            }
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 320) + 'px';
        }

        textarea.addEventListener('input', update);
        update();

        // Ctrl / Cmd + Enter to send
        textarea.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                if (textarea.value.trim() !== '') {
                    form.requestSubmit();
                }
            }
        });

        // Prevent double submit
        form.addEventListener('submit', function () {
            var button = form.querySelector('.seller-message-send-button');
            if (button) {
                setTimeout(function () {
                    button.disabled = true;
                    button.classList.add('is-loading');
                }, 0);
            }
        });
    })();
</script>

@endsection