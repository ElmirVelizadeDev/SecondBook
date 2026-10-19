@extends('Layout.Admin.master')
@section('title', 'Edit Banner')
@push('css')@vite('resources/css/admin/pages/banner.css')@endpush
@section('content')
<div class="dashboard-section banners-page" data-vue-page="banners-admin" data-mode="edit" data-index-url="{{ route('admin.banners.index') }}" data-update-url="{{ route('admin.banners.update', $banner) }}" data-csrf="{{ csrf_token() }}" data-banner="{{ json_encode(['id'=>$banner->id,'title'=>old('title',$banner->title),'subtitle'=>old('subtitle',$banner->subtitle),'button_text'=>old('button_text',$banner->button_text),'button_url'=>old('button_url',$banner->button_url),'position'=>old('position',$banner->position),'status'=>old('status',$banner->status),'start_date'=>old('start_date',$banner->start_date?->format('Y-m-d')),'end_date'=>old('end_date',$banner->end_date?->format('Y-m-d')),'image_url'=>$banner->image ? asset('storage/'.$banner->image) : null]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>
@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
