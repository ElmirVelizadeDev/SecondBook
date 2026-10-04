@extends('Layout.Seller.master')

@section('title', 'Message Details')

@push('css') <link rel="stylesheet" href="{{ asset('seller/css/messages.css') }}">
@endpush

@section('content')

@php
$customerName = $message->name ?: 'Unknown Customer';


$customerInitial = strtoupper(
    substr($message->name ?: 'U', 0, 1)
);

$isUnread = $message->status === 'unread';
$repliesCount = $message->replies->count();


@endphp

<div class="seller-message-show-page">


{{-- Page Header --}}
<div class="seller-page-heading">

    <div>
        <h1>Message Details</h1>

        <p>
            View the conversation and reply to your customer.
        </p>
    </div>

    <a
        href="{{ route('seller.messages.index') }}"
        class="seller-message-show-back"
    >
        <i class="bi bi-arrow-left"></i>
        <span>Back to Messages</span>
    </a>

</div>

<div class="row g-4">

    {{-- Customer / Message Info --}}
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

                    <strong>
                        {{ $customerName }}
                    </strong>

                    @if($isUnread)

                        <span class="seller-message-status unread">
                            <i></i>
                            Unread
                        </span>

                    @else

                        <span class="seller-message-status read">
                            <i></i>
                            Read
                        </span>

                    @endif

                </div>

            </div>

            {{-- Details --}}
            <div class="seller-message-info-list">

                {{-- Email --}}
                <div class="seller-message-info-item">

                    <div class="seller-message-info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

                    <div class="seller-message-info-text">

                        <span>Email</span>

                        @if($message->email)

                            <strong>
                                <a href="mailto:{{ $message->email }}">
                                    {{ $message->email }}
                                </a>
                            </strong>

                        @else

                            <strong>—</strong>

                        @endif

                    </div>

                </div>

                {{-- Subject --}}
                <div class="seller-message-info-item">

                    <div class="seller-message-info-icon">
                        <i class="bi bi-card-heading"></i>
                    </div>

                    <div class="seller-message-info-text">

                        <span>Subject</span>

                        <strong>
                            {{ $message->subject ?: '—' }}
                        </strong>

                    </div>

                </div>

                {{-- Received --}}
                <div class="seller-message-info-item">

                    <div class="seller-message-info-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div class="seller-message-info-text">

                        <span>Received</span>

                        <strong>
                            {{ $message->created_at?->format('M d, Y · H:i') ?? '—' }}
                        </strong>

                    </div>

                </div>

                {{-- Replies --}}
                <div class="seller-message-info-item">

                    <div class="seller-message-info-icon">
                        <i class="bi bi-reply"></i>
                    </div>

                    <div class="seller-message-info-text">

                        <span>Replies</span>

                        <strong>
                            {{ $repliesCount }}
                            {{ \Illuminate\Support\Str::plural('reply', $repliesCount) }}
                        </strong>

                    </div>

                </div>

            </div>

            {{-- Quick Action --}}
            <a
                href="#seller-reply-form"
                class="seller-message-quick-reply"
            >
                <i class="bi bi-send"></i>
                Reply now
            </a>

        </aside>

    </div>

    {{-- Conversation --}}
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
            <div
                class="seller-message-conversation-body"
                id="sellerConversationBody"
            >

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

                            <strong>
                                {{ $message->name ?: 'Customer' }}
                            </strong>

                            <span>
                                {{ $message->created_at?->format('M d, Y · H:i') }}
                            </span>

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
                        $isSeller = $reply->sender_type === 'seller';

                        $replyName =
                            $reply->user?->full_name
                            ?: $reply->user?->name;

                        $initial = strtoupper(
                            substr(
                                $replyName ?: ($isSeller ? 'S' : 'U'),
                                0,
                                1
                            )
                        );
                    @endphp

                    <div
                        class="seller-message-bubble {{ $isSeller ? 'seller' : 'customer' }}"
                        data-reply-id="{{ $reply->id }}"
                    >

                        <div class="seller-message-mini-avatar">

                            @if($reply->user?->profile_photo)

                                <img
                                    src="{{ asset('storage/' . $reply->user->profile_photo) }}"
                                    alt="{{ $replyName ?: ($isSeller ? 'Seller' : 'Customer') }}"
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

                                <div class="seller-message-bubble-meta">

                                    <span>
                                        {{ $reply->created_at?->format('M d, Y · H:i') }}
                                    </span>

                                    @if($isSeller)

                                        <button
                                            type="button"
                                            class="seller-message-delete-reply"
                                            data-reply-id="{{ $reply->id }}"
                                            title="Delete reply"
                                            aria-label="Delete reply"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>

                                    @endif

                                </div>

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

                        <p>
                            This conversation has not received a reply yet.
                        </p>

                    </div>

                @endforelse

            </div>

            {{-- Reply Form --}}
            <div
                class="seller-message-reply-section"
                id="seller-reply-form"
            >

                <div class="seller-message-reply-header">

                    <div class="seller-message-reply-header-icon">
                        <i class="bi bi-reply-fill"></i>
                    </div>

                    <div>

                        <h6>Reply to Customer</h6>

                        <p>
                            Send a message directly to this customer.
                        </p>

                    </div>

                </div>

                <form
                    action="{{ route('seller.messages.reply', $message) }}"
                    method="POST"
                    class="seller-message-reply-form"
                    id="sellerReplyForm"
                >

                    @csrf

                    <div
                        class="seller-message-reply-field"
                        id="sellerReplyField"
                    >

                        <textarea
                            name="reply"
                            id="sellerReplyTextarea"
                            rows="5"
                            placeholder="Write your reply..."
                            required
                            maxlength="10000"
                        >{{ old('reply') }}</textarea>

                        <div
                            class="seller-message-reply-error"
                            id="sellerReplyError"
                            hidden
                        >
                            <i class="bi bi-exclamation-circle"></i>
                            <span></span>
                        </div>

                        @error('reply')

                            <div class="seller-message-reply-error">

                                <i class="bi bi-exclamation-circle"></i>

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    <div class="seller-message-reply-actions">

                        <span class="seller-message-reply-hint">

                            <kbd>Ctrl</kbd>
                            +
                            <kbd>Enter</kbd>
                            to send

                            <em id="sellerReplyCount">0</em>
                            characters

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
                                id="sellerReplySendButton"
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

@push('js')

<script>
(function () {
    const body = document.getElementById(
        'sellerConversationBody'
    );

    const form = document.getElementById(
        'sellerReplyForm'
    );

    const textarea = document.getElementById(
        'sellerReplyTextarea'
    );

    const counter = document.getElementById(
        'sellerReplyCount'
    );

    const errorBox = document.getElementById(
        'sellerReplyError'
    );

    const errorText = errorBox?.querySelector(
        'span'
    );

    const field = document.getElementById(
        'sellerReplyField'
    );

    const button = document.getElementById(
        'sellerReplySendButton'
    );

    const buttonText = button?.querySelector(
        'span'
    );

    /*
    |--------------------------------------------------------------------------
    | Scroll
    |--------------------------------------------------------------------------
    */

    function scrollToBottom() {
        if (!body) {
            return;
        }

        body.scrollTop = body.scrollHeight;
    }

    /*
    |--------------------------------------------------------------------------
    | Textarea
    |--------------------------------------------------------------------------
    */

    function updateTextarea() {
        if (!textarea) {
            return;
        }

        if (counter) {
            counter.textContent = textarea.value.length;
        }

        textarea.style.height = 'auto';

        textarea.style.height =
            Math.min(
                textarea.scrollHeight,
                320
            ) + 'px';
    }

    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */

    function clearError() {
        if (errorBox) {
            errorBox.hidden = true;
        }

        if (errorText) {
            errorText.textContent = '';
        }

        if (field) {
            field.classList.remove('has-error');
        }
    }

    function showError(message) {
        if (errorBox) {
            errorBox.hidden = false;
        }

        if (errorText) {
            errorText.textContent = message;
        }

        if (field) {
            field.classList.add('has-error');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Empty State
    |--------------------------------------------------------------------------
    */

    function removeEmptyState() {
        if (!body) {
            return;
        }

        const emptyState = body.querySelector(
            '.seller-message-no-replies'
        );

        if (emptyState) {
            emptyState.remove();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update Message Count
    |--------------------------------------------------------------------------
    */

    function updateMessageCount() {
        const countElement =
            document.querySelector(
                '.seller-message-count'
            );

        if (!countElement || !body) {
            return;
        }

        const messages =
            body.querySelectorAll(
                '.seller-message-bubble'
            );

        const count = messages.length;

        countElement.textContent =
            count +
            ' ' +
            (
                count === 1
                    ? 'message'
                    : 'messages'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Reply
    |--------------------------------------------------------------------------
    */

    function createReplyElement(reply) {
        const bubble =
            document.createElement('div');

        bubble.className =
            'seller-message-bubble seller';

        bubble.dataset.replyId =
            reply.id;

        const avatar =
            document.createElement('div');

        avatar.className =
            'seller-message-mini-avatar';

        if (reply.profile_photo) {
            const image =
                document.createElement('img');

            image.src =
                reply.profile_photo;

            image.alt =
                reply.name || 'You';

            avatar.appendChild(image);
        } else {
            avatar.textContent =
                reply.initial || 'S';
        }

        const card =
            document.createElement('div');

        card.className =
            'seller-message-bubble-card';

        const header =
            document.createElement('div');

        header.className =
            'seller-message-bubble-header';

        const name =
            document.createElement('strong');

        name.textContent =
            'You';

        const meta =
            document.createElement('div');

        meta.className =
            'seller-message-bubble-meta';

        const date =
            document.createElement('span');

        date.textContent =
            reply.created_at || '';

        const deleteButton =
            document.createElement('button');

        deleteButton.type =
            'button';

        deleteButton.className =
            'seller-message-delete-reply';

        deleteButton.dataset.replyId =
            reply.id;

        deleteButton.title =
            'Delete reply';

        deleteButton.setAttribute(
            'aria-label',
            'Delete reply'
        );

        const deleteIcon =
            document.createElement('i');

        deleteIcon.className =
            'bi bi-trash3';

        deleteButton.appendChild(
            deleteIcon
        );

        meta.appendChild(date);
        meta.appendChild(deleteButton);

        header.appendChild(name);
        header.appendChild(meta);

        const text =
            document.createElement('div');

        text.className =
            'seller-message-bubble-text';

        /*
         * textContent is intentionally used
         * to prevent HTML injection.
         */

        text.textContent =
            reply.reply;

        card.appendChild(header);
        card.appendChild(text);

        bubble.appendChild(avatar);
        bubble.appendChild(card);

        return bubble;
    }

    /*
    |--------------------------------------------------------------------------
    | Send Reply
    |--------------------------------------------------------------------------
    */

    async function sendReply(event) {
        event.preventDefault();

        if (!form || !textarea) {
            return;
        }

        clearError();

        const replyText =
            textarea.value.trim();

        if (!replyText) {
            showError(
                'Please enter your reply.'
            );

            textarea.focus();

            return;
        }

        if (replyText.length < 2) {
            showError(
                'Your reply must be at least 2 characters.'
            );

            textarea.focus();

            return;
        }

        if (replyText.length > 10000) {
            showError(
                'Your reply may not exceed 10,000 characters.'
            );

            textarea.focus();

            return;
        }

        const formData =
            new FormData(form);

        const csrfToken =
            form.querySelector(
                'input[name="_token"]'
            )?.value || '';

        const originalButtonText =
            buttonText?.textContent ||
            'Send Reply';

        if (button) {
            button.disabled = true;

            button.classList.add(
                'is-loading'
            );
        }

        if (buttonText) {
            buttonText.textContent =
                'Sending...';
        }

        try {
            const response =
                await fetch(
                    form.action,
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                csrfToken,

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json',
                        },

                        body: formData,
                    }
                );

            let data = null;

            try {
                data =
                    await response.json();
            } catch (jsonError) {
                throw new Error(
                    'The server returned an invalid response.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validation Errors
            |--------------------------------------------------------------------------
            */

            if (response.status === 422) {
                if (
                    data.errors?.reply?.length
                ) {
                    showError(
                        data.errors.reply[0]
                    );
                } else {
                    showError(
                        data.message ||
                        'Please check your reply.'
                    );
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Other Errors
            |--------------------------------------------------------------------------
            */

            if (
                !response.ok ||
                !data.success
            ) {
                throw new Error(
                    data.message ||
                    'Unable to send reply.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Add Reply
            |--------------------------------------------------------------------------
            */

            removeEmptyState();

            const replyElement =
                createReplyElement(
                    data.reply
                );

            body.appendChild(
                replyElement
            );

            /*
            |--------------------------------------------------------------------------
            | Clear Form
            |--------------------------------------------------------------------------
            */

            textarea.value = '';

            updateTextarea();

            /*
            |--------------------------------------------------------------------------
            | Update Counter
            |--------------------------------------------------------------------------
            */

            updateMessageCount();

            /*
            |--------------------------------------------------------------------------
            | Scroll
            |--------------------------------------------------------------------------
            */

            scrollToBottom();

        } catch (error) {

            console.error(error);

            showError(
                error.message ||
                'Unable to send reply. Please try again.'
            );

        } finally {

            if (button) {
                button.disabled = false;

                button.classList.remove(
                    'is-loading'
                );
            }

            if (buttonText) {
                buttonText.textContent =
                    originalButtonText;
            }

            textarea.focus();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Reply
    |--------------------------------------------------------------------------
    */

    async function deleteReply(
        replyId,
        deleteButton
    ) {
        if (!replyId || !deleteButton) {
            return;
        }

        const bubble =
            deleteButton.closest(
                '.seller-message-bubble'
            );

        const result =
            await Swal.fire({
                title: 'Delete reply?',
                text: 'This reply and its related customer notification will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            });

        if (!result.isConfirmed) {
            return;
        }

        const csrfToken =
            form?.querySelector(
                'input[name="_token"]'
            )?.value || '';

        deleteButton.disabled = true;

        deleteButton.classList.add(
            'is-loading'
        );

        try {
            const response =
                await fetch(
                    `{{ url('/seller/messages') }}/{{ $message->id }}/replies/${replyId}`,
                    {
                        method: 'DELETE',

                        headers: {
                            'X-CSRF-TOKEN':
                                csrfToken,

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json',
                        },
                    }
                );

            let data = null;

            try {
                data =
                    await response.json();
            } catch (jsonError) {
                throw new Error(
                    'The server returned an invalid response.'
                );
            }

            if (
                !response.ok ||
                !data.success
            ) {
                throw new Error(
                    data.message ||
                    'Unable to delete reply.'
                );
            }

            if (bubble) {
                bubble.remove();
            }

            updateMessageCount();

            await Swal.fire({
                title: 'Deleted',
                text: data.message ||
                    'Reply deleted successfully.',
                icon: 'success',
                timer: 1400,
                showConfirmButton: false,
            });

        } catch (error) {

            console.error(error);

            deleteButton.disabled = false;

            deleteButton.classList.remove(
                'is-loading'
            );

            Swal.fire({
                title: 'Error',
                text:
                    error.message ||
                    'Unable to delete reply. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    if (textarea) {
        textarea.addEventListener(
            'input',
            function () {
                clearError();
                updateTextarea();
            }
        );
    }

    if (textarea && form) {
        textarea.addEventListener(
            'keydown',
            function (event) {
                if (
                    (
                        event.ctrlKey ||
                        event.metaKey
                    ) &&
                    event.key === 'Enter'
                ) {
                    event.preventDefault();

                    if (
                        textarea.value.trim() !== ''
                    ) {
                        form.requestSubmit();
                    }
                }
            }
        );
    }

    if (form) {
        form.addEventListener(
            'submit',
            sendReply
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Button Event Delegation
    |--------------------------------------------------------------------------
    */

    if (body) {
        body.addEventListener(
            'click',
            function (event) {
                const deleteButton =
                    event.target.closest(
                        '.seller-message-delete-reply'
                    );

                if (!deleteButton) {
                    return;
                }

                event.preventDefault();

                deleteReply(
                    deleteButton.dataset.replyId,
                    deleteButton
                );
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updateTextarea();

    scrollToBottom();

})();
</script>

@endpush

@endsection
