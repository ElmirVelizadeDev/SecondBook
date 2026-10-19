@extends('Layout.Admin.master')
@section('title', 'Publisher Details')
@push('css')@vite('resources/css/admin/pages/publishers.css')@endpush
@section('content')
@php $logoUrl = $publisher->logo ? (filter_var($publisher->logo, FILTER_VALIDATE_URL) ? $publisher->logo : asset('storage/' . ltrim($publisher->logo, '/'))) : null; @endphp
<div data-vue-page="publishers-admin" data-payload="{{ json_encode([
    'view' => 'show', 'publisher' => ['id' => $publisher->id, 'name' => $publisher->name, 'country' => $publisher->country, 'website' => $publisher->website, 'description' => $publisher->description, 'status' => (bool) $publisher->status, 'logo' => $logoUrl, 'created' => $publisher->created_at?->format('d M Y H:i') ?? '—', 'updated' => $publisher->updated_at?->format('d M Y H:i') ?? '—'],
    'urls' => ['index' => route('admin.publishers.index'), 'edit' => route('admin.publishers.edit', $publisher->id)],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
