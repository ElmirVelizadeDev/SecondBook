@extends('Layout.Admin.master')
@section('title', 'Book Details')
@push('css')@vite('resources/css/admin/pages/books.css')@endpush
@section('content')
<div class="dashboard-section books-page" data-vue-page="books-admin" data-mode="show" data-index-url="{{ route('admin.books.index') }}" data-payload="{{ json_encode(['book'=>$book->only(['id','title','isbn','description','cover','publication_year','pages','language','price','discount_type','discount_value','discount_start_at','discount_end_at','stock','condition','status','created_at']),'cover_url'=>$book->cover ? (filter_var($book->cover,FILTER_VALIDATE_URL)?$book->cover:asset('storage/'.$book->cover)) : null,'category'=>$book->category?->name,'author'=>$book->author?->name,'publisher'=>$book->publisher?->name,'seller'=>$book->seller?->name ?? $book->seller?->username]) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
