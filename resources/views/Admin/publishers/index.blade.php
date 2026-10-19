@extends('Layout.Admin.master')
@section('title', 'Publishers')
@push('css')@vite('resources/css/admin/pages/publishers.css')@endpush
@section('content')
<div data-vue-page="publishers-admin" data-payload="{{ json_encode([
    'view' => 'index',
    'publishers' => $publishers->getCollection()->map(fn ($publisher) => [
        'id' => $publisher->id, 'name' => $publisher->name, 'country' => $publisher->country, 'website' => $publisher->website, 'created_at' => $publisher->created_at?->format('d M Y') ?? '—',
        'description' => $publisher->description, 'status' => (bool) $publisher->status,
        'logo' => $publisher->logo ? (filter_var($publisher->logo, FILTER_VALIDATE_URL) ? $publisher->logo : asset('storage/' . ltrim($publisher->logo, '/'))) : null,
        'show_url' => route('admin.publishers.show', $publisher->id), 'edit_url' => route('admin.publishers.edit', $publisher->id),
        'status_url' => route('admin.publishers.status', $publisher->id), 'delete_url' => route('admin.publishers.destroy', $publisher->id),
    ])->values(),
    'stats' => ['total' => $publishers->total(), 'active' => $publishers->getCollection()->where('status', true)->count(), 'inactive' => $publishers->getCollection()->where('status', false)->count(), 'website' => $publishers->getCollection()->filter(fn ($publisher) => !empty($publisher->website))->count()],
    'search' => request('search', ''),
    'pagination' => ['total' => $publishers->total(), 'first' => $publishers->firstItem(), 'last' => $publishers->lastItem(), 'current' => $publishers->currentPage(), 'last_page' => $publishers->lastPage(), 'pages' => $publishers->getUrlRange(1, $publishers->lastPage()), 'previous' => $publishers->previousPageUrl(), 'next' => $publishers->nextPageUrl()],
    'urls' => ['index' => route('admin.publishers.index'), 'create' => route('admin.publishers.create')], 'success' => session('success'), 'error' => session('error'),
]) }}"></div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>@vite('resources/js/app.js')@endpush
