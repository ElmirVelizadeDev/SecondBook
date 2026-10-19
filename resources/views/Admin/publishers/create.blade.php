@extends('Layout.Admin.master')
@section('title', 'Add Publisher')
@push('css')@vite('resources/css/admin/pages/publishers.css')@endpush
@section('content')
<div data-vue-page="publishers-admin" data-payload="{{ json_encode([
    'view' => 'create', 'old' => ['name' => old('name', ''), 'country' => old('country', ''), 'website' => old('website', ''), 'description' => old('description', ''), 'status' => old('status', '1')],
    'errors' => $errors->getMessages(), 'urls' => ['index' => route('admin.publishers.index'), 'store' => route('admin.publishers.store')],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
