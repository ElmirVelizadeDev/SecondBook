<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Recent data
        $recentBooks = Book::with(['category', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(1)
            ->get();

        $recentCategories = Category::latest()
            ->take(1)
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly Overview
        $months = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $months->push([
                'label' => $date->format('M'),
                'month' => $date->month,
                'year' => $date->year,
            ]);
        }

        $monthlyOrders = [];
        $monthlyRevenue = [];

        foreach ($months as $month) {

            $orders = Order::whereYear(
                    'created_at',
                    $month['year']
                )
                ->whereMonth(
                    'created_at',
                    $month['month']
                )
                ->count();

            $revenue = Order::whereYear(
                    'created_at',
                    $month['year']
                )
                ->whereMonth(
                    'created_at',
                    $month['month']
                )
                ->where('order_status', 'delivered')
                ->sum('total_price');

            $monthlyOrders[] = $orders;
            $monthlyRevenue[] = $revenue;
        }

        return view('Admin.dashboard', [

            // Statistics
            'totalBooks' => Book::count(),
            'totalUsers' => User::count(),
            'totalCategories' => Category::count(),
            'totalAuthors' => Author::count(),

            // Recent Activity
            'recentBooks' => $recentBooks,
            'recentUsers' => $recentUsers,
            'recentCategories' => $recentCategories,

            // Recent Orders
            'recentOrders' => $recentOrders,

            // Monthly Overview
            'monthlyLabels' => $months->pluck('label')->toArray(),
            'monthlyOrders' => $monthlyOrders,
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
}