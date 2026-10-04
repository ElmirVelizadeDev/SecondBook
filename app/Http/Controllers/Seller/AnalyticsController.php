<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $range = $request->input('range', '30');

        if (!in_array($range, ['7', '30', '90', '365'])) {
            $range = '30';
        }

        $startDate = Carbon::now()
            ->subDays((int) $range - 1)
            ->startOfDay();

        $endDate = Carbon::now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | Seller Orders
        |--------------------------------------------------------------------------
        */

        $baseQuery = Order::query()
            ->whereHas('book', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->whereBetween('created_at', [$startDate, $endDate]);


        /*
        |--------------------------------------------------------------------------
        | Overview
        |--------------------------------------------------------------------------
        */

        $totalOrders = (clone $baseQuery)->count();

        $deliveredOrders = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->count();

        $pendingOrders = (clone $baseQuery)
            ->where('order_status', 'pending')
            ->count();

        $cancelledOrders = (clone $baseQuery)
            ->where('order_status', 'cancelled')
            ->count();

        $totalRevenue = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->sum('total_price');

        $itemsSold = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->sum('quantity');

        $averageOrderValue = $deliveredOrders > 0
            ? $totalRevenue / $deliveredOrders
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Previous Period
        |--------------------------------------------------------------------------
        */

        $previousStartDate = Carbon::parse($startDate)
            ->subDays((int) $range)
            ->startOfDay();

        $previousEndDate = Carbon::parse($startDate)
            ->subDay()
            ->endOfDay();

        $previousRevenue = Order::query()
            ->whereHas('book', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $previousStartDate,
                $previousEndDate
            ])
            ->sum('total_price');

        $revenueGrowth = $previousRevenue > 0
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100
            : ($totalRevenue > 0 ? 100 : 0);


        /*
        |--------------------------------------------------------------------------
        | Daily Revenue Chart
        |--------------------------------------------------------------------------
        */

        $dailyRevenue = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('SUM(quantity) as items')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $chartLabels = [];
        $chartRevenue = [];
        $chartItems = [];

        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {

            $date = $cursor->format('Y-m-d');

            $day = $dailyRevenue->firstWhere('date', $date);

            $chartLabels[] = $cursor->format(
                $range <= 30 ? 'M d' : 'M'
            );

            $chartRevenue[] = $day
                ? round((float) $day->revenue, 2)
                : 0;

            $chartItems[] = $day
                ? (int) $day->items
                : 0;

            $cursor->addDay();
        }


        /*
        |--------------------------------------------------------------------------
        | Order Status Distribution
        |--------------------------------------------------------------------------
        */

        $statusData = (clone $baseQuery)
            ->select(
                'order_status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];


        /*
        |--------------------------------------------------------------------------
        | Top Selling Books
        |--------------------------------------------------------------------------
        */

        $topBooks = (clone $baseQuery)
            ->where('order_status', 'delivered')
            ->with('book')
            ->select(
                'book_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->groupBy('book_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */

        $totalBooks = Book::query()
            ->where('seller_id', $sellerId)
            ->count();

        $approvedBooks = Book::query()
            ->where('seller_id', $sellerId)
            ->where('status', 'approved')
            ->count();

        $pendingBooks = Book::query()
            ->where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->count();

        $lowStockBooks = Book::query()
            ->where('seller_id', $sellerId)
            ->where('stock', '>', 0)
            ->where('stock', '<=', 3)
            ->count();

        $outOfStockBooks = Book::query()
            ->where('seller_id', $sellerId)
            ->where('stock', '<=', 0)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = (clone $baseQuery)
            ->with(['book', 'user'])
            ->latest()
            ->limit(6)
            ->get();


        return view('seller.analytics.index', compact(
            'range',
            'startDate',
            'endDate',

            'totalOrders',
            'deliveredOrders',
            'pendingOrders',
            'cancelledOrders',

            'totalRevenue',
            'itemsSold',
            'averageOrderValue',
            'revenueGrowth',

            'chartLabels',
            'chartRevenue',
            'chartItems',

            'statusData',
            'statusLabels',

            'topBooks',

            'totalBooks',
            'approvedBooks',
            'pendingBooks',
            'lowStockBooks',
            'outOfStockBooks',

            'recentOrders'
        ));
    }
}