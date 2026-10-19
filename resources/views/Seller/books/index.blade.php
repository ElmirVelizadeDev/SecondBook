@extends('Layout.Seller.master')

@section('title', 'My Books')

@push('css')
    @vite('resources/css/seller/pages/books.css')
@endpush

@section('content')
<div class="seller-books-page" data-vue-page="seller-books-index-page" data-page-data="{{ base64_encode(json_encode([
    'stats' => [
        ['label' => 'Total Books', 'value' => $totalBooks, 'type' => '', 'icon' => 'bi-book'],
        ['label' => 'Approved', 'value' => $approvedBooks, 'type' => 'approved', 'icon' => 'bi-check-circle'],
        ['label' => 'Pending', 'value' => $pendingBooks, 'type' => 'pending', 'icon' => 'bi-hourglass-split'],
        ['label' => 'Rejected', 'value' => $rejectedBooks, 'type' => 'rejected', 'icon' => 'bi-x-circle'],
    ],
    'filters' => ['search' => request('search', ''), 'status' => request('status', ''), 'condition' => request('condition', '')],
    'indexUrl' => route('seller.books.index'),
    'createUrl' => route('seller.books.create'),
    'csrfToken' => csrf_token(),
    'success' => session('success'),
    'books' => $books->getCollection()->map(function ($book) {
        $cover = $book->cover;
        $coverUrl = $cover ? (\Illuminate\Support\Str::startsWith($cover, ['http://', 'https://']) ? $cover : asset('storage/' . ltrim($cover, '/'))) : null;
        return [
            'id' => $book->id, 'title' => $book->title, 'isbn' => $book->isbn,
            'coverUrl' => $coverUrl, 'category' => $book->category?->name,
            'price' => number_format($book->price, 2), 'stock' => $book->stock,
            'condition' => ucwords(str_replace('_', ' ', $book->condition)),
            'status' => $book->status, 'statusLabel' => ucfirst($book->status),
            'showUrl' => route('seller.books.show', $book), 'editUrl' => route('seller.books.edit', $book),
            'deleteUrl' => route('seller.books.destroy', $book),
        ];
    })->values(),
    'pagination' => ['links' => $books->linkCollection()->toArray()],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
