@extends('Layout.Seller.master')

@section('title', 'Reviews')

@push('css') @vite('resources/css/seller/pages/reviews.css')
@endpush

@section('content')

<div class="seller-reviews-page" data-vue-page="seller-reviews-index-page" data-index-data-url="{{ route('seller.reviews.list-data') }}" data-reviews-url="{{ route('seller.reviews.index') }}"></div>

@endsection
