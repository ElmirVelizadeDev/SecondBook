<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reports Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = !empty($validated['start_date'])
            ? Carbon::parse($validated['start_date'])->startOfDay()
            : now()->startOfMonth();

        $endDate = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | Base Orders Query
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::query()
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ]);


        /*
        |--------------------------------------------------------------------------
        | Order Statistics
        |--------------------------------------------------------------------------
        */

        $totalOrders = (clone $ordersQuery)->count();

        $pendingOrders = (clone $ordersQuery)
            ->where('order_status', 'pending')
            ->count();

        $deliveredOrders = (clone $ordersQuery)
            ->where('order_status', 'delivered')
            ->count();

        $cancelledOrders = (clone $ordersQuery)
            ->where('order_status', 'cancelled')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Delivered Orders
        |--------------------------------------------------------------------------
        */

        $deliveredOrdersQuery = (clone $ordersQuery)
            ->where('order_status', 'delivered');


        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $totalRevenue = (clone $deliveredOrdersQuery)
            ->sum('total_price');


        /*
        |--------------------------------------------------------------------------
        | Books Sold
        |--------------------------------------------------------------------------
        */

        $booksSold = (clone $deliveredOrdersQuery)
            ->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | Average Order Value
        |--------------------------------------------------------------------------
        */

        $averageOrderValue = $deliveredOrders > 0
            ? $totalRevenue / $deliveredOrders
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        |
        | Burada seçilən tarix aralığında sifariş etmiş
        | unikal istifadəçilər hesablanır.
        |
        */

        $totalCustomers = (clone $ordersQuery)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');


        /*
        |--------------------------------------------------------------------------
        | Top Selling Books
        |--------------------------------------------------------------------------
        */

        $topBooks = Order::query()
            ->selectRaw('
                book_id,
                SUM(quantity) as total_sold,
                SUM(total_price) as revenue
            ')
            ->with('book')
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ])
            ->whereNotNull('book_id')
            ->groupBy('book_id')
            ->orderByDesc('total_sold')
            ->limit(5)
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
                $endDate
            ])
            ->latest('created_at')
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Low Stock Books
        |--------------------------------------------------------------------------
        */

        $lowStockBooks = Book::query()
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Sales Chart
        |--------------------------------------------------------------------------
        */

        $salesByDay = Order::query()
            ->selectRaw('
                DATE(created_at) as date,
                SUM(total_price) as revenue
            ')
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();


        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalRevenue',
            'booksSold',
            'totalCustomers',
            'averageOrderValue',
            'topBooks',
            'recentOrders',
            'lowStockBooks',
            'salesByDay'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Sales Report
    |--------------------------------------------------------------------------
    */

    public function sales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = !empty($validated['start_date'])
            ? Carbon::parse($validated['start_date'])->startOfDay()
            : now()->startOfMonth();

        $endDate = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | Delivered Orders
        |--------------------------------------------------------------------------
        */

        $deliveredOrdersQuery = Order::query()
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ]);


        /*
        |--------------------------------------------------------------------------
        | Orders List
        |--------------------------------------------------------------------------
        */

        $orders = Order::with([
                'user',
                'book',
            ])
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ])
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Sales Statistics
        |--------------------------------------------------------------------------
        */

        $totalOrders = (clone $deliveredOrdersQuery)
            ->count();

        $totalRevenue = (clone $deliveredOrdersQuery)
            ->sum('total_price');

        $booksSold = (clone $deliveredOrdersQuery)
            ->sum('quantity');

        $averageOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;


        return view('admin.reports.sales', compact(
            'startDate',
            'endDate',
            'orders',
            'totalOrders',
            'totalRevenue',
            'booksSold',
            'averageOrderValue'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Books Report
    |--------------------------------------------------------------------------
    */

    public function books(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = !empty($validated['start_date'])
            ? Carbon::parse($validated['start_date'])->startOfDay()
            : now()->startOfMonth();

        $endDate = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | Books
        |--------------------------------------------------------------------------
        */

        $books = Book::with([
                'category',
                'author',
                'publisher',
                'seller',
            ])
            ->withSum([
                'orders as sold_count' => function ($query) use (
                    $startDate,
                    $endDate
                ) {
                    $query
                        ->where('order_status', 'delivered')
                        ->whereBetween('created_at', [
                            $startDate,
                            $endDate
                        ]);
                },
            ], 'quantity')
            ->withSum([
                'orders as revenue' => function ($query) use (
                    $startDate,
                    $endDate
                ) {
                    $query
                        ->where('order_status', 'delivered')
                        ->whereBetween('created_at', [
                            $startDate,
                            $endDate
                        ]);
                },
            ], 'total_price')
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Book Statistics
        |--------------------------------------------------------------------------
        */

        $totalBooks = Book::count();

        $activeBooks = Book::where(
            'status',
            'approved'
        )->count();

        $outOfStock = Book::where(
            'stock',
            '<=',
            0
        )->count();

        $lowStock = Book::whereBetween(
            'stock',
            [1, 5]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Sales Statistics
        |--------------------------------------------------------------------------
        */

        $deliveredOrdersQuery = Order::query()
            ->where('order_status', 'delivered')
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ]);

        $totalSold = (clone $deliveredOrdersQuery)
            ->sum('quantity');

        $totalRevenue = (clone $deliveredOrdersQuery)
            ->sum('total_price');


        return view('admin.reports.books', compact(
            'books',
            'startDate',
            'endDate',
            'totalBooks',
            'activeBooks',
            'outOfStock',
            'lowStock',
            'totalSold',
            'totalRevenue'
        ));
    }
}