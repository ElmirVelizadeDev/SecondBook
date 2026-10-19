@extends('Layout.Admin.master')
@section('title', 'Add Condition')
@push('css')@vite('resources/css/admin/pages/book-condition.css')@endpush
@section('content')
    <div data-vue-page="book-condition-admin" data-payload="{{ json_encode([
        'view' => 'create', 'old' => ['name' => old('name', ''), 'description' => old('description', ''), 'status' => old('status', '1')],
        'errors' => $errors->toArray(), 'urls' => ['index' => route('admin.book.conditions.index'), 'store' => route('admin.book.conditions.store')],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="dashboard-section book-condition-page"><div class="book-condition-panel">Loading form…</div></div>
    </div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
