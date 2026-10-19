@extends('Layout.Admin.master')
@section('title', 'Edit Category')
@push('css')@vite('resources/css/admin/pages/category.css')@endpush
@section('content')
<div data-vue-page="categories-admin" data-payload="{{ json_encode([
    'view' => 'edit', 'category' => ['id' => $category->id, 'name' => $category->name, 'slug' => $category->slug, 'description' => $category->description, 'status' => (bool) $category->status, 'image' => $category->image ? (filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : asset('storage/' . $category->image)) : null],
    'urls' => ['index' => route('admin.categories.index'), 'update' => route('admin.categories.update', $category->id)],
    'old' => ['name' => old('name', $category->name), 'slug' => old('slug', $category->slug), 'description' => old('description', $category->description), 'status' => old('status', $category->status)],
    'errors' => $errors->getMessages(),
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
