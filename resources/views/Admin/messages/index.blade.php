@extends('Layout.Admin.master')

@section('title', 'Messages')

@push('css')
    <link rel="stylesheet" href="{{ asset('admin/css/pages/messages.css') }}">
@endpush

@section('content')

<div class="messages-page">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    @endif


    {{-- Page Header --}}
    <div class="page-header mb-4">

        <div>
            <h1 class="page-title">
                Messages
            </h1>

            <p class="page-subtitle">
                Manage customer messages and inquiries.
            </p>
        </div>

    </div>


    {{-- Statistics --}}
    <div class="row g-4 mb-4">

        {{-- Total Messages --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="message-stat-card">

                <div class="message-stat-icon">
                    <i class="bi bi-chat-left-text"></i>
                </div>

                <div>
                    <span class="message-stat-label">
                        Total Messages
                    </span>

                    <h3>
                        {{ $totalMessages }}
                    </h3>
                </div>

            </div>

        </div>


        {{-- Unread --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="message-stat-card">

                <div class="message-stat-icon">
                    <i class="bi bi-envelope"></i>
                </div>

                <div>
                    <span class="message-stat-label">
                        Unread
                    </span>

                    <h3>
                        {{ $unreadMessages }}
                    </h3>
                </div>

            </div>

        </div>


        {{-- Read --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="message-stat-card">

                <div class="message-stat-icon">
                    <i class="bi bi-envelope-open"></i>
                </div>

                <div>
                    <span class="message-stat-label">
                        Read
                    </span>

                    <h3>
                        {{ $readMessages }}
                    </h3>
                </div>

            </div>

        </div>


        {{-- Today --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="message-stat-card">

                <div class="message-stat-icon">
                    <i class="bi bi-calendar-day"></i>
                </div>

                <div>
                    <span class="message-stat-label">
                        Today
                    </span>

                    <h3>
                        {{ $todayMessages }}
                    </h3>
                </div>

            </div>

        </div>

    </div>


    {{-- Filter Panel --}}
    <div class="message-card mb-4">

        <div class="message-card-header">

            <div>
                <h5>
                    Filter Messages
                </h5>

                <p>
                    Search and filter customer messages.
                </p>
            </div>

        </div>


        <form
            action="{{ route('admin.messages.index') }}"
            method="GET"
        >

            <div class="row g-3 align-items-end">

                {{-- Search --}}
                <div class="col-12 col-lg-7">

                    <label
                        for="search"
                        class="form-label"
                    >
                        Search
                    </label>

                    <div class="message-search-group">

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search by name, email, subject or message..."
                        >

                        <button
                            type="submit"
                            class="btn message-search-btn"
                        >
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-12 col-lg-3">

                    <label
                        for="status"
                        class="form-label"
                    >
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select"
                    >

                        <option value="">
                            All Messages
                        </option>

                        <option
                            value="unread"
                            {{ request('status') === 'unread' ? 'selected' : '' }}
                        >
                            Unread
                        </option>

                        <option
                            value="read"
                            {{ request('status') === 'read' ? 'selected' : '' }}
                        >
                            Read
                        </option>

                    </select>

                </div>


                {{-- Reset --}}
                <div class="col-12 col-lg-2">

                    <a
                        href="{{ route('admin.messages.index') }}"
                        class="btn message-reset-btn w-100"
                    >
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- Messages Table --}}
    <div class="message-card">

        <div class="message-card-header">

            <div>

                <h5>
                    Customer Messages
                </h5>

                <p>
                    {{ $messages->total() }} message(s) found.
                </p>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table message-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Sender</th>

                        <th>Subject</th>

                        <th>Message</th>

                        <th>Status</th>

                        <th>Date</th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($messages as $message)

                        <tr class="{{ $message->status === 'unread' ? 'message-unread' : '' }}">

                            {{-- ID --}}
                            <td>

                                <span class="message-id">
                                    {{ $message->id }}
                                </span>

                            </td>


                            {{-- Sender --}}
                            <td>

                                <div class="message-sender">

                                    <div class="message-avatar">
                                        {{ strtoupper(substr($message->name, 0, 1)) }}
                                    </div>

                                    <div class="message-sender-info">

                                        <strong>
                                            {{ $message->name }}
                                        </strong>

                                        <span>
                                            {{ $message->email }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- Subject --}}
                            <td>

                                <div class="message-subject">
                                    {{ $message->subject }}
                                </div>

                            </td>


                            {{-- Message Preview --}}
                            <td>

                                <div class="message-preview">
                                    {{ \Illuminate\Support\Str::limit($message->message, 70) }}
                                </div>

                            </td>


                            {{-- Status --}}
                            <td>

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

                            </td>


                            {{-- Date --}}
                            <td>

                                <div class="message-date">

                                    <strong>
                                        {{ $message->created_at->format('d M Y') }}
                                    </strong>

                                    <span>
                                        {{ $message->created_at->format('H:i') }}
                                    </span>

                                </div>

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="message-actions justify-content-end">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('admin.messages.show', $message) }}"
                                        class="message-action-btn view"
                                        title="View Message"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Mark as unread --}}
                                    @if($message->status === 'read')

                                        <form
                                            action="{{ route('admin.messages.unread', $message) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="message-action-btn unread-btn"
                                                title="Mark as Unread"
                                            >
                                                <i class="bi bi-envelope"></i>
                                            </button>

                                        </form>

                                    @endif


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.messages.destroy', $message) }}"
                                        method="POST"
                                        class="delete-message-form"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="message-action-btn delete"
                                            title="Delete Message"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="message-empty">

                                    <div class="message-empty-icon">
                                        <i class="bi bi-chat-left-text"></i>
                                    </div>

                                    <h5>
                                        No Messages Found
                                    </h5>

                                    <p>
                                        There are no customer messages to display.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($messages->hasPages())

            <div class="message-pagination">

                {{-- Pagination Info --}}
                <div class="message-pagination-info">

                    Showing
                    <strong>{{ $messages->firstItem() }}</strong>

                    to

                    <strong>{{ $messages->lastItem() }}</strong>

                    of

                    <strong>{{ $messages->total() }}</strong>

                    results

                </div>


                {{-- Pagination Links --}}
                <div class="message-pagination-links">

                    {{ $messages->onEachSide(1)->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection


@push('js')

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const deleteForms = document.querySelectorAll('.delete-message-form');

            deleteForms.forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    event.preventDefault();

                    Swal.fire({

                        title: 'Delete Message?',

                        text: 'This message will be permanently deleted.',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonText: 'Yes, Delete',

                        cancelButtonText: 'Cancel',

                        reverseButtons: true

                    }).then((result) => {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                });

            });

        });
    </script>

@endpush