<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Date Range
    |--------------------------------------------------------------------------
    |
    | When no dates are selected, analytics starts from the first order
    | available in the database instead of only the current month.
    |
    */

    private function getDateRange(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $firstOrderDate = Order::min('created_at');

        $startDate = !empty($validated['start_date'])
            ? Carbon::parse($validated['start_date'])->startOfDay()
            : (
                $firstOrderDate
                    ? Carbon::parse($firstOrderDate)->startOfDay()
                    : now()->startOfMonth()
            );

        $endDate = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : now()->endOfDay();

        return [
            $startDate,
            $endDate,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Analytics Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);


        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::query()
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ]);

        $totalOrders = (clone $ordersQuery)
            ->count();

        $deliveredOrders = (clone $ordersQuery)
            ->where('order_status', 'delivered')
            ->count();

        $pendingOrders = (clone $ordersQuery)
            ->where('order_status', 'pending')
            ->count();

        $cancelledOrders = (clone $ordersQuery)
            ->where('order_status', 'cancelled')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $deliveredOrdersQuery = (clone $ordersQuery)
            ->where('order_status', 'delivered');

        $totalRevenue = (clone $deliveredOrdersQuery)
            ->sum('total_price');

        $booksSold = (clone $deliveredOrdersQuery)
            ->sum('quantity');

        $averageOrderValue = $deliveredOrders > 0
            ? $totalRevenue / $deliveredOrders
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        |
        | Users registered during the selected date range.
        |
        */

        $newUsers = User::query()
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Books
        |--------------------------------------------------------------------------
        */

        $totalBooks = Book::count();

        $lowStockBooks = Book::query()
            ->whereBetween('stock', [1, 5])
            ->count();

        $outOfStockBooks = Book::query()
            ->where('stock', '<=', 0)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Revenue By Day
        |--------------------------------------------------------------------------
        */

        $revenueByDay = Order::query()
            ->selectRaw(
                'DATE(created_at) as date, SUM(total_price) as revenue'
            )
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Orders By Status
        |--------------------------------------------------------------------------
        */

        $ordersByStatus = Order::query()
            ->selectRaw('order_status, COUNT(*) as total')
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->groupBy('order_status')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Top Selling Books
        |--------------------------------------------------------------------------
        */

        $topBooks = Order::query()
            ->selectRaw(
                'book_id, SUM(quantity) as total_sold, SUM(total_price) as revenue'
            )
            ->with('book')
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->whereNotNull('book_id')
            ->groupBy('book_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with([
                'user',
                'book',
            ])
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->latest('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Analytics Dashboard
        |--------------------------------------------------------------------------
        */

        return view('admin.analytics.index', compact(
            'startDate',
            'endDate',
            'totalOrders',
            'deliveredOrders',
            'pendingOrders',
            'cancelledOrders',
            'totalRevenue',
            'booksSold',
            'averageOrderValue',
            'newUsers',
            'totalBooks',
            'lowStockBooks',
            'outOfStockBooks',
            'revenueByDay',
            'ordersByStatus',
            'topBooks',
            'recentOrders'
        ));
    }
}