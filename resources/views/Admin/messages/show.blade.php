@extends('Layout.Admin.master')

@section('title', 'Message Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/pages/messages.css') }}">
@endpush

@section('content')

<div class="messages-page">

    {{-- =========================================================
        Header
    ========================================================== --}}

    <div class="messages-header mb-4">

        <div>
            <h1 class="messages-title">
                Message Details
            </h1>

            <p class="messages-subtitle">
                View customer message details and manage its status.
            </p>
        </div>

        <a
            href="{{ route('admin.messages.index') }}"
            class="btn btn-light messages-back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            <span>Back to Messages</span>
        </a>

    </div>


    {{-- =========================================================
        Message Details Card
    ========================================================== --}}

    <div class="message-detail-card">

        {{-- =====================================================
            Top Section
        ====================================================== --}}

        <div class="message-detail-top">

            {{-- Sender --}}

            <div class="message-detail-user">

                <div class="message-avatar">
                    {{ strtoupper(substr($message->name, 0, 1)) }}
                </div>

                <div>

                    <h5>
                        {{ $message->name }}
                    </h5>

                    <a href="mailto:{{ $message->email }}">
                        {{ $message->email }}
                    </a>

                </div>

            </div>


            {{-- Status --}}

            <div class="message-detail-status">

                @if($message->status === 'unread')

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


        {{-- Divider --}}

        <div class="message-detail-divider"></div>


        {{-- =====================================================
            Subject
        ====================================================== --}}

        <div class="message-detail-section">

            <span class="message-detail-label">
                Subject
            </span>

            <h3 class="message-detail-subject">
                {{ $message->subject }}
            </h3>

        </div>


        {{-- =====================================================
            Message Meta
        ====================================================== --}}

        <div class="message-detail-meta">

            {{-- Received --}}

            <div>

                <span class="message-detail-label">
                    Received
                </span>

                <span>
                    {{ $message->created_at?->format('d M Y, H:i') ?? 'N/A' }}
                </span>

            </div>


            {{-- Status --}}

            <div>

                <span class="message-detail-label">
                    Status
                </span>

                <span>
                    {{ ucfirst($message->status) }}
                </span>

            </div>

        </div>


        {{-- =====================================================
            Message Body
        ====================================================== --}}

        <div class="message-detail-section">

            <span class="message-detail-label">
                Message
            </span>

            <div class="message-detail-body">
                {{ $message->message }}
            </div>

        </div>


        {{-- =====================================================
            Actions
        ====================================================== --}}

        <div class="message-detail-actions">


            {{-- =================================================
                Mark as Unread
            ================================================== --}}

            @if($message->status === 'read')

                <form
                    action="{{ route('admin.messages.unread', ['message' => $message->id]) }}"
                    method="POST"
                    class="mark-unread-form"
                >

                    @csrf

                    @method('PATCH')

                    <button
                        type="submit"
                        class="btn btn-outline-secondary mark-unread-btn"
                    >
                        <i class="bi bi-envelope"></i>
                        Mark as Unread
                    </button>

                </form>

            @endif


            {{-- =================================================
                Reply via Gmail
            ================================================== --}}

            <a
                href="{{ route('admin.messages.reply', $message) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-envelope"></i>
                Reply via Gmail
            </a>


            {{-- =================================================
                Reply in Site
                Available when the sender has a SecondBook account
            ================================================== --}}

            @if($messageUser)

                <a
                    href="{{ route('admin.messages.site-reply', $message) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-chat-left-text"></i>
                    Reply in Site
                </a>

            @endif


            {{-- =================================================
                Delete Message
            ================================================== --}}

            <form
                action="{{ route('admin.messages.destroy', ['message' => $message->id]) }}"
                method="POST"
                class="delete-message-form"
            >

                @csrf

                @method('DELETE')

                <button
                    type="button"
                    class="btn btn-outline-danger delete-message-btn"
                >
                    <i class="bi bi-trash"></i>
                    Delete
                </button>

            </form>

        </div>

    </div>

</div>

@endsection


{{-- =============================================================
    Page Specific JavaScript
============================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       Delete Message
    ========================================================= */

    document
        .querySelectorAll('.delete-message-btn')
        .forEach(function (button) {

            button.addEventListener('click', function (event) {

                event.preventDefault();

                const form =
                    button.closest('.delete-message-form');

                if (!form) {
                    return;
                }


                /* -------------------------------------------------
                   Fallback confirmation
                ------------------------------------------------- */

                if (typeof Swal === 'undefined') {

                    if (
                        confirm(
                            'Are you sure you want to delete this message?'
                        )
                    ) {
                        form.submit();
                    }

                    return;
                }


                /* -------------------------------------------------
                   SweetAlert confirmation
                ------------------------------------------------- */

                Swal.fire({

                    title: 'Delete Message?',

                    text: 'This message will be permanently deleted.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, Delete',

                    cancelButtonText: 'Cancel',

                    confirmButtonColor: '#dc3545',

                    cancelButtonColor: '#6c757d',

                    reverseButtons: true,

                    focusCancel: true

                }).then(function (result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });


    /* =========================================================
       Mark as Unread
    ========================================================= */

    document
        .querySelectorAll('.mark-unread-form')
        .forEach(function (form) {

            form.addEventListener('submit', function () {

                const button =
                    form.querySelector('.mark-unread-btn');

                if (!button) {
                    return;
                }

                button.disabled = true;

                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" ' +
                    'role="status" aria-hidden="true"></span>' +
                    'Marking as Unread...';

            });

        });

});
</script>

@endpush
