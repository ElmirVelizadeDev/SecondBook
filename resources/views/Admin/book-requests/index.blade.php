@extends('Layout.Admin.master')

@section('title', 'Book Requests')

@push('css')
    @vite('resources/css/admin/pages/book-requests.css')
@endpush

@section('content')
    <div
        data-vue-page="book-requests-admin"
        data-payload="{{ json_encode([
            'view' => 'index',
            'requests' => $bookRequests->map(fn ($book) => [
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn,
                'cover' => $book->cover ? asset('storage/' . $book->cover) : null,
                'seller' => $book->seller ? ['name' => $book->seller->name, 'email' => $book->seller->email] : null,
                'category' => $book->category?->name,
                'price' => $book->price,
                'stock' => $book->stock,
                'condition' => $book->condition,
                'status' => $book->status,
                'created_at' => $book->created_at?->format('d M Y'),
                'edit_url' => route('admin.book.requests.edit', $book->id),
                'delete_url' => route('admin.book.requests.destroy', $book->id),
            ])->values(),
            'categories' => \App\Models\Category::orderBy('name')->get(['id', 'name']),
            'filters' => ['search' => request('search', ''), 'category' => request('category', '')],
            'urls' => ['index' => route('admin.book.requests.index')],
            'success' => session('success'),
            'error' => session('error'),
        ]) }}"
    ></div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
@endpush
