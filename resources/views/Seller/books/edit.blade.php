@extends('Layout.Seller.master')

@section('title', 'Edit Book')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/pages/books.css') }}">
@endpush

@section('content')
<div class="seller-books-page" data-vue-page="seller-book-form-page" data-page-data="{{ base64_encode(json_encode([
    'edit' => true,
    'action' => route('seller.books.update', $book),
    'backUrl' => route('seller.books.show', $book),
    'cancelUrl' => route('seller.books.show', $book),
    'csrfToken' => csrf_token(),
    'errors' => $errors->messages(),
    'currentYear' => date('Y'),
    'coverUrl' => $book->cover ? asset('storage/' . ltrim($book->cover, '/')) : null,
    'fields' => [
        'title' => old('title', $book->title), 'isbn' => old('isbn', $book->isbn), 'language' => old('language', $book->language),
        'category_id' => old('category_id', $book->category_id), 'author_id' => old('author_id', $book->author_id), 'publisher_id' => old('publisher_id', $book->publisher_id),
        'description' => old('description', $book->description), 'price' => old('price', $book->price), 'stock' => old('stock', $book->stock),
        'condition' => old('condition', $book->condition), 'publication_year' => old('publication_year', $book->publication_year), 'pages' => old('pages', $book->pages),
    ],
    'conditions' => [['value' => 'new', 'label' => 'New'], ['value' => 'like_new', 'label' => 'Like New'], ['value' => 'good', 'label' => 'Good'], ['value' => 'fair', 'label' => 'Fair']],
    'selectLists' => [
        ['name' => 'category_id', 'label' => 'Category', 'placeholder' => 'Select category', 'options' => $categories->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])],
        ['name' => 'author_id', 'label' => 'Author', 'placeholder' => 'Select author', 'options' => $authors->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])],
        ['name' => 'publisher_id', 'label' => 'Publisher', 'placeholder' => 'Select publisher', 'options' => $publishers->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])],
    ],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
