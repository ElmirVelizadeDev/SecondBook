@extends('Layout.Admin.master')
@section('title', 'Books')
@push('css')@vite('resources/css/admin/pages/books.css')@endpush
@section('content')
<div class="dashboard-section books-page" data-vue-page="books-admin" data-mode="index" data-index-url="{{ route('admin.books.index') }}" data-create-url="{{ route('admin.books.create') }}" data-csrf="{{ csrf_token() }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
