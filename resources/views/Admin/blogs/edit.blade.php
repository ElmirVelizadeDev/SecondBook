@extends('Layout.Admin.master')
@section('title','Edit Blog')
@push('css')@vite('resources/css/admin/pages/blog.css')@endpush
@section('content')<div class="dashboard-section blogs-page" data-vue-page="blogs-admin" data-mode="edit" data-index-url="{{ route('admin.blogs.index') }}" data-update-url="{{ route('admin.blogs.update',$blog) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['blog'=>array_merge($blog->only(['id','title','excerpt','content','status','image']),['published_at'=>$blog->published_at?->format('Y-m-d\TH:i')]),'image_url'=>$blog->image ? asset('storage/'.$blog->image) : null]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
