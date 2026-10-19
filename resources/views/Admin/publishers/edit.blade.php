@extends('Layout.Admin.master')
@section('title', 'Edit Publisher')
@push('css')@vite('resources/css/admin/pages/publishers.css')@endpush
@section('content')
<div data-vue-page="publishers-admin" data-payload="{{ json_encode([
    'view' => 'edit', 'publisher' => ['id' => $publisher->id, 'name' => $publisher->name, 'country' => $publisher->country, 'website' => $publisher->website, 'description' => $publisher->description, 'status' => (bool) $publisher->status, 'logo' => $publisher->logo ? (filter_var($publisher->logo, FILTER_VALIDATE_URL) ? $publisher->logo : asset('storage/' . ltrim($publisher->logo, '/'))) : null],
    'old' => ['name' => old('name', $publisher->name), 'country' => old('country', $publisher->country), 'website' => old('website', $publisher->website), 'description' => old('description', $publisher->description), 'status' => old('status', $publisher->status)],
    'errors' => $errors->getMessages(), 'urls' => ['index' => route('admin.publishers.index'), 'update' => route('admin.publishers.update', $publisher->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
