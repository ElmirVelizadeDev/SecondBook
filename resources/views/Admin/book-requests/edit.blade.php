@extends('Layout.Admin.master')

@section('title', 'Book Request')

@push('css')
    @vite('resources/css/admin/pages/book-requests.css')
@endpush

@section('content')
    <div
        data-vue-page="book-requests-admin"
        data-payload="{{ json_encode([
            'view' => 'edit',
            'book' => [
                'id' => $bookRequest->id,
                'title' => $bookRequest->title,
                'isbn' => $bookRequest->isbn,
                'cover' => $bookRequest->cover ? asset('storage/' . $bookRequest->cover) : null,
                'category' => $bookRequest->category?->name,
                'author' => $bookRequest->author?->name,
                'publisher' => $bookRequest->publisher?->name,
                'seller' => $bookRequest->seller?->name,
                'publication_year' => $bookRequest->publication_year,
                'pages' => $bookRequest->pages,
                'language' => $bookRequest->language,
                'price' => $bookRequest->price,
                'stock' => $bookRequest->stock,
                'condition' => $bookRequest->condition,
                'description' => $bookRequest->description,
                'status' => $bookRequest->status,
            ],
            'urls' => [
                'index' => route('admin.book.requests.index'),
                'update' => route('admin.book.requests.update', ['book' => $bookRequest->id]),
            ],
            'old' => [
                'status' => old('status', $bookRequest->status),
                'message' => old('message', ''),
            ],
            'errors' => $errors->getMessages(),
        ]) }}"
    ></div>
@endsection

@push('js')
    @vite('resources/js/app.js')
@endpush
