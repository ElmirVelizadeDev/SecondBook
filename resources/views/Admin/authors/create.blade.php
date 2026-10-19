@extends('Layout.Admin.master')
@section('title', 'Add Author')
@push('css')@vite('resources/css/admin/pages/authors.css')@endpush
@section('content')
<div data-vue-page="authors-admin" data-payload="{{ json_encode([
    'view' => 'create', 'old' => ['name' => old('name', ''), 'bio' => old('bio', ''), 'status' => old('status', '1')],
    'errors' => $errors->getMessages(), 'urls' => ['index' => route('admin.authors.index'), 'store' => route('admin.authors.store')],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
