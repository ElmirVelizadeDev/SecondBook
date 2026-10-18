@extends('Layout.Seller.master')

@section('title', 'Book Details')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/books.css') }}">
@endpush

@section('content')
<div class="seller-books-page" data-vue-page="seller-book-details-page" data-page-data="{{ base64_encode(json_encode([
    'book' => [
        'title' => $book->title,
        'status' => $book->status,
        'statusLabel' => ucfirst($book->status),
        'conditionLabel' => ucwords(str_replace('_', ' ', $book->condition)),
        'coverUrl' => $book->cover ? asset('storage/' . $book->cover) : null,
        'price' => number_format($book->price, 2),
        'stock' => $book->stock,
        'pages' => $book->pages,
        'isbn' => $book->isbn,
        'category' => $book->category?->name,
        'author' => $book->author?->name,
        'publisher' => $book->publisher?->name,
        'language' => $book->language,
        'publicationYear' => $book->publication_year,
        'description' => $book->description,
        'details' => [
            ['label' => 'ISBN', 'value' => $book->isbn, 'icon' => 'bi-upc-scan'],
            ['label' => 'Category', 'value' => $book->category?->name, 'icon' => 'bi-tags'],
            ['label' => 'Author', 'value' => $book->author?->name, 'icon' => 'bi-person'],
            ['label' => 'Publisher', 'value' => $book->publisher?->name, 'icon' => 'bi-building'],
            ['label' => 'Language', 'value' => $book->language, 'icon' => 'bi-translate'],
            ['label' => 'Publication Year', 'value' => $book->publication_year, 'icon' => 'bi-calendar3'],
        ],
    ],
    'indexUrl' => route('seller.books.index'),
    'editUrl' => route('seller.books.edit', ['book' => $book->id]),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection

