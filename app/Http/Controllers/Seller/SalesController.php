<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();
        $sixMonthsAgo = now()->subMonths(6);

        $query = Order::with([
            'user',
            'book',
        ])
            ->whereHas('book', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->where('order_status', 'delivered')
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where(
                    'order_number',
                    'like',
                    "%{$search}%"
                )
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                    })
                    ->orWhereHas('book', function ($query) use ($search) {
                        $query->where(
                            'title',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        $sales = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $baseQuery = Order::whereHas('book', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
            ->where('order_status', 'delivered')
            ->whereNull('archived_at')
            ->where('created_at', '>=', $sixMonthsAgo);

        $totalSales = (clone $baseQuery)->count();

        $totalItemsSold = (clone $baseQuery)
            ->sum('quantity');

        $totalRevenue = (clone $baseQuery)
            ->sum('total_price');

        $averageSale = $totalSales > 0
            ? $totalRevenue / $totalSales
            : 0;

        return view('seller.sales.index', compact(
            'sales',
            'totalSales',
            'totalItemsSold',
            'totalRevenue',
            'averageSale'
        ));
    }
}