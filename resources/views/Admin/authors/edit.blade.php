@extends('Layout.Admin.master')
@section('title', 'Edit Author')
@push('css')@vite('resources/css/admin/pages/authors.css')@endpush
@section('content')
<div data-vue-page="authors-admin" data-payload="{{ json_encode([
    'view' => 'edit', 'author' => ['id' => $author->id, 'name' => $author->name, 'bio' => $author->bio, 'status' => (bool) $author->status, 'photo' => $author->photo ? (str_starts_with($author->photo, 'http') ? $author->photo : asset('storage/' . ltrim($author->photo, '/'))) : null],
    'old' => ['name' => old('name', $author->name), 'bio' => old('bio', $author->bio), 'status' => old('status', $author->status)],
    'errors' => $errors->getMessages(), 'urls' => ['index' => route('admin.authors.index'), 'update' => route('admin.authors.update', $author->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
