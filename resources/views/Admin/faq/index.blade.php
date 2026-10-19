@extends('Layout.Admin.master')
@section('title', 'FAQ')
@push('css')@vite('resources/css/admin/pages/faq.css')@endpush
@section('content')
    <div data-vue-page="faq-admin" data-payload="{{ json_encode([
        'view' => 'index',
        'faqs' => $faqs->getCollection()->map(fn ($faq) => ['id' => $faq->id, 'category' => $faq->category, 'question' => $faq->question, 'answer' => $faq->answer, 'is_active' => $faq->is_active, 'sort_order' => $faq->sort_order, 'created_at' => $faq->created_at?->format('d M Y')])->values(),
        'categories' => $categories,
        'pagination' => ['from' => $faqs->firstItem(), 'to' => $faqs->lastItem(), 'total' => $faqs->total(), 'current_page' => $faqs->currentPage(), 'last_page' => $faqs->lastPage(), 'links' => $faqs->getUrlRange(1, $faqs->lastPage())],
        'filters' => ['search' => request('search', ''), 'category' => request('category', ''), 'status' => request('status', '')],
        'success' => session('success'), 'urls' => ['index' => route('admin.faq.index'), 'create' => route('admin.faq.create'), 'editPattern' => route('admin.faq.edit', ['faq' => '__id__']), 'deletePattern' => route('admin.faq.destroy', ['faq' => '__id__'])],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
        <div class="dashboard-section faq-page"><div class="dashboard-panel faq-table-panel">Loading FAQs…</div></div>
    </div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>@vite('resources/js/app.js')@endpush
