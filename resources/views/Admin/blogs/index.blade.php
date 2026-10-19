@extends('Layout.Admin.master')
@section('title','Blogs')
@push('css')@vite('resources/css/admin/pages/blog.css')@endpush
@section('content')<div class="dashboard-section blogs-page" data-vue-page="blogs-admin" data-mode="index" data-index-url="{{ route('admin.blogs.index') }}" data-create-url="{{ route('admin.blogs.create') }}" data-csrf="{{ csrf_token() }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
