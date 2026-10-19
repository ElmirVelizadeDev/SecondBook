@extends('Layout.Admin.master')
@section('title', 'Edit Book')
@push('css')@vite('resources/css/admin/pages/books.css')@endpush
@section('content')
<div class="dashboard-section books-page" data-vue-page="books-admin" data-mode="edit" data-index-url="{{ route('admin.books.index') }}" data-update-url="{{ route('admin.books.update',$book) }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['book'=>$book->only(['id','title','isbn','category_id','author_id','publisher_id','seller_id','description','cover','publication_year','pages','language','price','discount_type','discount_value','discount_start_at','discount_end_at','stock','condition','status']),'cover_url'=>$book->cover ? (filter_var($book->cover,FILTER_VALIDATE_URL)?$book->cover:asset('storage/'.$book->cover)) : null,'categories'=>$categories->map->only(['id','name']),'authors'=>$authors->map->only(['id','name']),'publishers'=>$publishers->map->only(['id','name']),'sellers'=>$sellers->map->only(['id','name'])]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
