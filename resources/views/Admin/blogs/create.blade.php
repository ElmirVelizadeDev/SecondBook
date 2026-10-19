@extends('Layout.Admin.master')
@section('title','Create Blog')
@push('css')@vite('resources/css/admin/pages/blog.css')@endpush
@section('content')<div class="dashboard-section blogs-page" data-vue-page="blogs-admin" data-mode="create" data-index-url="{{ route('admin.blogs.index') }}" data-store-url="{{ route('admin.blogs.store') }}" data-csrf="{{ csrf_token() }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
