@extends('Layout.Seller.master')

@section('title', 'Add New Book')

@push('css')
    @vite('resources/css/seller/pages/books.css')
@endpush

@section('content')
<div class="seller-books-page" data-vue-page="seller-book-form-page" data-page-data="{{ base64_encode(json_encode([
    'edit' => false,
    'action' => route('seller.books.store'),
    'backUrl' => route('seller.books.index'),
    'cancelUrl' => route('seller.books.index'),
    'csrfToken' => csrf_token(),
    'errors' => $errors->messages(),
    'currentYear' => date('Y'),
    'fields' => [
        'title' => old('title', ''), 'isbn' => old('isbn', ''), 'language' => old('language', 'English'),
        'category_id' => old('category_id', ''), 'author_id' => old('author_id', ''), 'publisher_id' => old('publisher_id', ''),
        'description' => old('description', ''), 'price' => old('price', ''), 'stock' => old('stock', 1),
        'condition' => old('condition', 'good'), 'publication_year' => old('publication_year', ''), 'pages' => old('pages', ''),
    ],
    'selectLists' => [
        ['name' => 'category_id', 'label' => 'Category', 'placeholder' => 'Select Category', 'options' => $categories->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])],
        ['name' => 'author_id', 'label' => 'Author', 'placeholder' => 'Select Author', 'options' => $authors->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])],
        ['name' => 'publisher_id', 'label' => 'Publisher', 'placeholder' => 'Select Publisher', 'options' => $publishers->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])],
    ],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}"></div>

@endsection
