@extends('Layout.Admin.master')
@section('title', 'Create FAQ')
@push('css')@vite('resources/css/admin/pages/faq.css')@endpush
@section('content')
    <div data-vue-page="faq-admin" data-payload="{{ json_encode([
        'view' => 'create', 'old' => ['question' => old('question', ''), 'answer' => old('answer', ''), 'category' => old('category', ''), 'sort_order' => old('sort_order', 0), 'is_active' => old('is_active', '1')],
        'errors' => $errors->toArray(), 'urls' => ['index' => route('admin.faq.index'), 'store' => route('admin.faq.store')],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"><div class="dashboard-section faq-page">Loading form…</div></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
