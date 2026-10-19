@extends('Layout.Admin.master')
@section('title','Messages')
@push('css')@vite('resources/css/admin/pages/messages.css')@endpush
@section('content')<div class="messages-page" data-vue-page="messages-admin" data-mode="index" data-index-url="{{ route('admin.messages.index') }}" data-csrf="{{ csrf_token() }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
