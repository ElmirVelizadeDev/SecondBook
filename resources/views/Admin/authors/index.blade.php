@extends('Layout.Admin.master')
@section('title', 'Authors')
@push('css')@vite('resources/css/admin/pages/authors.css')@endpush
@section('content')
<div data-vue-page="authors-admin" data-payload="{{ json_encode([
    'view' => 'index',
    'authors' => $authors->getCollection()->map(fn ($author) => [
        'id' => $author->id, 'name' => $author->name ?? 'Unknown author', 'books_count' => $author->books()->count(),
        'created' => $author->created_at?->format('M d, Y') ?? '—', 'created_time' => $author->created_at?->format('H:i') ?? '—',
        'updated' => $author->updated_at?->format('M d, Y') ?? '—', 'updated_time' => $author->updated_at?->format('H:i') ?? '—',
        'show_url' => route('admin.authors.show', $author->id), 'edit_url' => route('admin.authors.edit', $author->id), 'delete_url' => route('admin.authors.destroy', $author->id),
    ])->values(),
    'stats' => ['total' => $authors->total(), 'with_books' => \App\Models\Author::has('books')->count(), 'without_books' => \App\Models\Author::doesntHave('books')->count(), 'books' => \App\Models\Book::count()],
    'search' => request('search', ''),
    'pagination' => ['total' => $authors->total(), 'first' => $authors->firstItem(), 'last' => $authors->lastItem(), 'current' => $authors->currentPage(), 'last_page' => $authors->lastPage(), 'pages' => $authors->getUrlRange(1, $authors->lastPage()), 'previous' => $authors->previousPageUrl(), 'next' => $authors->nextPageUrl()],
    'urls' => ['index' => route('admin.authors.index'), 'create' => route('admin.authors.create')], 'success' => session('success'),
]) }}"></div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>@vite('resources/js/app.js')@endpush
