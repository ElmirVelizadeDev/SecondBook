@extends('layout.admin.master')

@section('title', 'Reply in Site')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/pages/messages.css') }}">
@endpush

@section('content')

<div class="messages-page">

    {{-- PAGE HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Reply in Site</h4>
            <p class="mb-0 text-muted">
                Send a reply to this user's contact message.
            </p>
        </div>

        <a href="{{ route('admin.messages.show', $message) }}"
           class="btn messages-back-btn">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>
    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">
                Please fix the following errors:
            </div>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="message-detail-card">

        {{-- USER / MESSAGE HEADER --}}
        <div class="message-detail-top">

            <div class="message-detail-user">

                <div class="message-avatar">
                    {{ strtoupper(substr($messageUser->name ?? $message->name, 0, 1)) }}
                </div>

                <div>
                    <h5>
                        {{ $messageUser->name ?? $message->name }}
                    </h5>

                    <a href="mailto:{{ $messageUser->email ?? $message->email }}">
                        {{ $messageUser->email ?? $message->email }}
                    </a>
                </div>

            </div>

            <div class="message-detail-status">

                @if ($message->status === 'unread')
                    <span class="message-status unread">
                        <i class="bi bi-envelope"></i>
                        Unread
                    </span>
                @else
                    <span class="message-status read">
                        <i class="bi bi-envelope-open"></i>
                        Read
                    </span>
                @endif

            </div>

        </div>


        {{-- DIVIDER --}}
        <div class="message-detail-divider"></div>


        {{-- ORIGINAL MESSAGE --}}
        <div class="message-detail-section">

            <span class="message-detail-label">
                Original Subject
            </span>

            <h2 class="message-detail-subject">
                {{ $message->subject }}
            </h2>

        </div>


        {{-- MESSAGE META --}}
        <div class="message-detail-meta">

            <div>
                <span class="message-detail-label">
                    Sender
                </span>

                <span>
                    {{ $message->name }}
                </span>
            </div>

            <div>
                <span class="message-detail-label">
                    Email
                </span>

                <span>
                    {{ $message->email }}
                </span>
            </div>

            <div>
                <span class="message-detail-label">
                    Received
                </span>

                <span>
                    {{ $message->created_at?->format('M d, Y H:i') }}
                </span>
            </div>

        </div>


        {{-- ORIGINAL MESSAGE BODY --}}
        <div class="message-detail-section">

            <span class="message-detail-label">
                Original Message
            </span>

            <div class="message-detail-body">
                {{ $message->message }}
            </div>

        </div>


        {{-- REPLY FORM --}}
        <form
            action="{{ route('admin.messages.send-site-reply', $message) }}"
            method="POST"
        >

            @csrf

            <div class="message-detail-divider"></div>

            <div class="message-detail-section mb-0">

                <span class="message-detail-label">
                    Your Reply
                </span>

                <textarea
                    name="reply"
                    class="form-control message-site-reply-textarea"
                    rows="8"
                    placeholder="Write your reply here..."
                    required
                >{{ old('reply') }}</textarea>

                @error('reply')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ACTIONS --}}
            <div class="message-detail-actions">

                <a
                    href="{{ route('admin.messages.show', $message) }}"
                    class="btn messages-back-btn"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-send me-1"></i>
                    Send Reply
                </button>

            </div>

        </form>

    </div>

</div>

@endsection