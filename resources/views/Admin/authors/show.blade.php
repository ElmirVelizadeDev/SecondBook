@extends('Layout.Admin.master')
@section('title', 'Author Details')
@push('css')@vite('resources/css/admin/pages/authors.css')@endpush
@section('content')
<div data-vue-page="authors-admin" data-payload="{{ json_encode([
    'view' => 'show', 'author' => ['id' => $author->id, 'name' => $author->name ?: 'Unnamed Author', 'bio' => $author->bio, 'status' => (bool) $author->status, 'photo' => $author->photo ? (str_starts_with($author->photo, 'http') ? $author->photo : asset('storage/' . ltrim($author->photo, '/'))) : null, 'created' => $author->created_at?->format('d M Y H:i') ?? '—', 'updated' => $author->updated_at?->format('d M Y H:i') ?? '—'],
    'urls' => ['index' => route('admin.authors.index'), 'edit' => route('admin.authors.edit', $author->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
