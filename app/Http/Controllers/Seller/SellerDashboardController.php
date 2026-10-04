<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $sellerId = auth()->id();

        $sixMonthsAgo = now()->subMonths(6);

        $totalBooks = Book::where('seller_id', $sellerId)
            ->count();

        $pendingBooks = Book::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Orders
        |--------------------------------------------------------------------------
        | Active orders remain visible regardless of age.
        | Delivered orders are limited to the last 6 months.
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::whereHas('book', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
        ->where(function ($query) use ($sixMonthsAgo) {
            $query->where('order_status', '!=', 'delivered')
                ->orWhere(function ($query) use ($sixMonthsAgo) {
                    $query->where('order_status', 'delivered')
                        ->whereNull('archived_at')
                        ->where('created_at', '>=', $sixMonthsAgo);
                });
        })
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Sales
        |--------------------------------------------------------------------------
        */

        $totalSales = Order::whereHas('book', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
        ->where('order_status', 'delivered')
        ->whereNull('archived_at')
        ->where('created_at', '>=', $sixMonthsAgo)
        ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with([
            'user',
            'book',
        ])
            ->whereHas('book', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'totalBooks',
            'pendingBooks',
            'totalOrders',
            'totalSales',
            'recentOrders'
        ));
    }
}