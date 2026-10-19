@extends('Layout.Seller.master')

@section('title', 'Review Details')

@push('css')
    @vite('resources/css/seller/pages/reviews.css')
@endpush

@section('content')
<div class="seller-reviews-page" data-vue-page="seller-review-details-page" data-review-data-url="{{ route('seller.reviews.data', $review) }}" data-reviews-url="{{ route('seller.reviews.index') }}"></div>

@endsection
