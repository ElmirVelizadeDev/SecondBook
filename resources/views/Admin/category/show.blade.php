@extends('Layout.Admin.master')
@section('title', 'Category Details')
@push('css')@vite('resources/css/admin/pages/category.css')@endpush
@section('content')
@php $imageUrl = $category->image ? (filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : asset('storage/' . $category->image)) : null; @endphp
<div data-vue-page="categories-admin" data-payload="{{ json_encode([
    'view' => 'show', 'category' => ['id' => $category->id, 'name' => $category->name, 'slug' => $category->slug, 'description' => $category->description, 'status' => (bool) $category->status, 'image' => $imageUrl, 'books_count' => $category->books_count ?? 0, 'created' => $category->created_at?->format('d M Y') ?? '—', 'created_full' => $category->created_at?->format('d M Y H:i') ?? '—', 'updated_full' => $category->updated_at?->format('d M Y H:i') ?? '—'],
    'urls' => ['index' => route('admin.categories.index'), 'edit' => route('admin.categories.edit', $category->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
