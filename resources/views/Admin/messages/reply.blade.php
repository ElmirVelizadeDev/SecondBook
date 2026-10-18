@extends('Layout.Admin.master')

@section('title', 'Reply to Message')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/pages/messages.css') }}">
@endpush

@section('content')

<div class="messages-page">

    {{-- Header --}}
    <div class="messages-header mb-4">

        <div>
            <h1 class="messages-title">
                Reply to Message
            </h1>

            <p class="messages-subtitle">
                Send a reply to the customer via email.
            </p>
        </div>

        <a
            href="{{ route('admin.messages.show', $message) }}"
            class="btn btn-light messages-back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            <span>Back to Message</span>
        </a>

    </div>


    {{-- Reply Card --}}
    <div class="message-detail-card">

        <form
            action="{{ route('admin.messages.sendReply', $message) }}"
            method="POST"
        >

            @csrf


            {{-- Recipient --}}
            <div class="message-detail-section">

                <label
                    for="email"
                    class="message-detail-label"
                >
                    To
                </label>

                <input
                    type="email"
                    id="email"
                    class="form-control"
                    value="{{ $message->email }}"
                    readonly
                >

            </div>


            {{-- Subject --}}
            <div class="message-detail-section">

                <label
                    for="subject"
                    class="message-detail-label"
                >
                    Subject
                </label>

                <input
                    type="text"
                    id="subject"
                    name="subject"
                    class="form-control"
                    value="Re: {{ $message->subject }}"
                    required
                >

            </div>


            {{-- Original Message --}}
            <div class="message-detail-section">

                <span class="message-detail-label">
                    Original Message
                </span>

                <div class="message-detail-body">
                    {{ $message->message }}
                </div>

            </div>


            {{-- Reply --}}
            <div class="message-detail-section">

                <label
                    for="reply"
                    class="message-detail-label"
                >
                    Your Reply
                </label>

                <textarea
                    id="reply"
                    name="reply"
                    class="form-control"
                    rows="8"
                    placeholder="Write your reply..."
                    required
                ></textarea>

                @error('reply')
                    <div class="text-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Actions --}}
            <div class="message-detail-actions">

                <a
                    href="{{ route('admin.messages.show', $message) }}"
                    class="btn btn-light"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-send"></i>
                    Send Reply
                </button>

            </div>

        </form>

    </div>

</div>

@endsection