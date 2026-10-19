@extends('Layout.Admin.master')
@section('title', 'Add Category')
@push('css')@vite('resources/css/admin/pages/category.css')@endpush
@section('content')
<div data-vue-page="categories-admin" data-payload="{{ json_encode([
    'view' => 'create', 'urls' => ['index' => route('admin.categories.index'), 'store' => route('admin.categories.store')],
    'old' => ['name' => old('name', ''), 'slug' => old('slug', ''), 'description' => old('description', ''), 'status' => old('status', true)],
    'errors' => $errors->getMessages(), 'success' => session('success'),
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
