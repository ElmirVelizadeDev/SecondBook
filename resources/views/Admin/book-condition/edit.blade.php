@extends('Layout.Admin.master')
@section('title', 'Edit Condition')
@push('css')@vite('resources/css/admin/pages/book-condition.css')@endpush
@section('content')
    <div data-vue-page="book-condition-admin" data-payload="{{ json_encode([
        'view' => 'edit', 'condition' => $selectedCondition,
        'old' => ['name' => old('name', $selectedCondition['name']), 'description' => old('description', $selectedCondition['description'] ?? ''), 'status' => old('status', $selectedCondition['status'] ?? 1)],
        'errors' => $errors->toArray(), 'urls' => ['index' => route('admin.book.conditions.index'), 'update' => route('admin.book.conditions.update', ['condition' => $selectedCondition['id']])],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="dashboard-section book-condition-page"><div class="book-condition-panel">Loading form…</div></div>
    </div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
