@extends('Layout.Admin.master')
@section('title', 'Edit FAQ')
@push('css')@vite('resources/css/admin/pages/faq.css')@endpush
@section('content')
    <div data-vue-page="faq-admin" data-payload="{{ json_encode([
        'view' => 'edit', 'old' => ['question' => old('question', $faq->question), 'answer' => old('answer', $faq->answer), 'category' => old('category', $faq->category), 'sort_order' => old('sort_order', $faq->sort_order ?? 0), 'is_active' => old('is_active', $faq->is_active ? '1' : '0')],
        'errors' => $errors->toArray(), 'urls' => ['index' => route('admin.faq.index'), 'update' => route('admin.faq.update', $faq->id)],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"><div class="dashboard-section faq-page">Loading form…</div></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
