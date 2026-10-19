@extends('Layout.Admin.master')
@section('title', 'Book Conditions')
@push('css')@vite('resources/css/admin/pages/book-condition.css')@endpush
@section('content')
    <div data-vue-page="book-condition-admin" data-payload="{{ json_encode([
        'view' => 'index', 'conditions' => $conditions,
        'search' => request('search', ''), 'statusFilter' => request('status', ''),
        'success' => session('success'), 'error' => session('error'),
        'urls' => [
            'index' => route('admin.book.conditions.index'),
            'create' => route('admin.book.conditions.create'),
            'editPattern' => route('admin.book.conditions.edit', ['condition' => '__condition__']),
            'statusPattern' => route('admin.book.conditions.status', ['condition' => '__condition__']),
            'deletePattern' => route('admin.book.conditions.destroy', ['condition' => '__condition__']),
        ],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="dashboard-section book-condition-page"><div class="book-condition-panel">Loading conditions…</div></div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
@endpush
