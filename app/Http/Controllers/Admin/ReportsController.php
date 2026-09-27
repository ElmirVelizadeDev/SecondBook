<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
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
        | Order Statistics
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::whereBetween(
            'created_at',
            [$startDate, $endDate]
        );

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
        | Revenue
        |--------------------------------------------------------------------------
        */

        $totalRevenue = (clone $ordersQuery)
            ->where('order_status', 'delivered')
            ->sum('total_price');

        $booksSold = (clone $ordersQuery)
            ->where('order_status', 'delivered')
            ->sum('quantity');

        $averageOrderValue = $deliveredOrders > 0
            ? $totalRevenue / $deliveredOrders
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $totalCustomers = User::whereBetween(
            'created_at',
            [$startDate, $endDate]
        )->count();

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
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
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
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->latest()
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
            ->selectRaw(
                'DATE(created_at) as date, SUM(total_price) as revenue'
            )
            ->where('order_status', 'delivered')
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
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

        $orders = Order::with([
                'user',
                'book',
            ])
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->where('order_status', 'delivered')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Sales Statistics
        |--------------------------------------------------------------------------
        */

        $deliveredOrdersQuery = Order::where(
                'order_status',
                'delivered'
            )
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            );

        $totalOrders = (clone $deliveredOrdersQuery)->count();

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

            /*
            |--------------------------------------------------------------------------
            | Total Quantity Sold
            |--------------------------------------------------------------------------
            |
            | withCount() yalnızca order sətrinin sayını hesablayırdı.
            | Burada SUM(quantity) istifadə edirik.
            |
            */

            ->withSum([
                'orders as sold_count' => function ($query) use (
                    $startDate,
                    $endDate
                ) {
                    $query
                        ->where(
                            'order_status',
                            'delivered'
                        )
                        ->whereBetween(
                            'created_at',
                            [$startDate, $endDate]
                        );
                },
            ], 'quantity')

            /*
            |--------------------------------------------------------------------------
            | Revenue
            |--------------------------------------------------------------------------
            */

            ->withSum([
                'orders as revenue' => function ($query) use (
                    $startDate,
                    $endDate
                ) {
                    $query
                        ->where(
                            'order_status',
                            'delivered'
                        )
                        ->whereBetween(
                            'created_at',
                            [$startDate, $endDate]
                        );
                },
            ], 'total_price')

            ->latest()
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
        | Total Books Sold
        |--------------------------------------------------------------------------
        */

        $deliveredOrdersQuery = Order::where(
                'order_status',
                'delivered'
            )
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            );

        $totalSold = (clone $deliveredOrdersQuery)
            ->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | Total Revenue
        |--------------------------------------------------------------------------
        */

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

