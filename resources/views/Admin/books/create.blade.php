@extends('Layout.Admin.master')
@section('title', 'Add Book')
@push('css')@vite('resources/css/admin/pages/books.css')@endpush
@section('content')
<div class="dashboard-section books-page" data-vue-page="books-admin" data-mode="create" data-index-url="{{ route('admin.books.index') }}" data-store-url="{{ route('admin.books.store') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['categories'=>$categories->map->only(['id','name']),'authors'=>$authors->map->only(['id','name']),'publishers'=>$publishers->map->only(['id','name']),'sellers'=>$sellers->map->only(['id','name'])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
