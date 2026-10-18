@extends('Layout.Seller.master')

@section('title', 'Reviews')

@push('css') <link rel="stylesheet" href="{{ asset('seller/css/pages/reviews.css') }}">
@endpush

@section('content')

<div class="seller-reviews-page" data-vue-page="seller-reviews-index-page" data-index-data-url="{{ route('seller.reviews.list-data') }}" data-reviews-url="{{ route('seller.reviews.index') }}"></div>

@endsection
