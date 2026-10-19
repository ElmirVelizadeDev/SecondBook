@extends('Layout.Admin.master')
@section('title', 'Categories')
@push('css')@vite('resources/css/admin/pages/category.css')@endpush
@section('content')
<div data-vue-page="categories-admin" data-payload="{{ json_encode([
    'view' => 'index',
    'categories' => $categories->getCollection()->map(fn ($category) => [
        'id' => $category->id, 'name' => $category->name, 'slug' => $category->slug,
        'description' => $category->description, 'status' => (bool) $category->status,
        'image' => $category->image ? (filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : asset('storage/' . $category->image)) : null,
        'books_count' => $category->books_count ?? 0, 'created' => $category->created_at?->format('d M Y') ?? '—',
        'show_url' => route('admin.categories.show', $category->id), 'edit_url' => route('admin.categories.edit', $category->id),
        'status_url' => route('admin.categories.status', $category->id), 'delete_url' => route('admin.categories.destroy', $category->id),
    ])->values(),
    'stats' => ['total' => $categories->total(), 'active' => \App\Models\Category::where('status', true)->count(), 'inactive' => \App\Models\Category::where('status', false)->count(), 'with_books' => \App\Models\Category::has('books')->count()],
    'filters' => ['search' => request('search', ''), 'status' => request('status', '')],
    'pagination' => ['total' => $categories->total(), 'first' => $categories->firstItem(), 'last' => $categories->lastItem(), 'current' => $categories->currentPage(), 'last_page' => $categories->lastPage(), 'pages' => $categories->getUrlRange(1, $categories->lastPage()), 'previous' => $categories->previousPageUrl(), 'next' => $categories->nextPageUrl()],
    'urls' => ['index' => route('admin.categories.index'), 'create' => route('admin.categories.create')], 'success' => session('success'), 'error' => session('error'),
]) }}"></div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>@vite('resources/js/app.js')@endpush
