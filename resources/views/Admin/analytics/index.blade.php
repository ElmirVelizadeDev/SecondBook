@extends('Layout.Admin.master')
@section('title', 'Analytics')
@push('css')@vite('resources/css/admin/pages/analytics.css')@endpush
@section('content')
<div data-vue-page="analytics-admin" data-index-url="{{ route('admin.analytics.index') }}"></div>
@endsection
@push('js')<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>@vite('resources/js/app.js')@endpush
