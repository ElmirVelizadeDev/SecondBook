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

        $latestBook = $recentBooks->first();
        $latestUser = $recentUsers->first();
        $latestCategory = $recentCategories->first();

        $recentActivity = collect([
            $latestBook ? [
                'title' => 'New Book Added',
                'detail' => $latestBook->title,
                'time' => $latestBook->created_at?->diffForHumans(),
                'icon' => 'bi-book',
                'tone' => 'blue',
            ] : null,
            $latestUser ? [
                'title' => 'New User',
                'detail' => $latestUser->name . ' registered',
                'time' => $latestUser->created_at?->diffForHumans(),
                'icon' => 'bi-person',
                'tone' => 'green',
            ] : null,
            $latestCategory ? [
                'title' => 'Category Created',
                'detail' => $latestCategory->name,
                'time' => $latestCategory->created_at?->diffForHumans(),
                'icon' => 'bi-grid',
                'tone' => 'amber',
            ] : null,
        ])->filter()->values();

        return view('Admin.dashboard', [
            'dashboardData' => [
                'stats' => [
                    ['label' => 'Total Books', 'value' => Book::count(), 'icon' => 'bi-book', 'tone' => 'blue', 'url' => route('admin.books.index')],
                    ['label' => 'Total Users', 'value' => User::count(), 'icon' => 'bi-people', 'tone' => 'green', 'url' => route('admin.users.index')],
                    ['label' => 'Categories', 'value' => Category::count(), 'icon' => 'bi-grid', 'tone' => 'amber', 'url' => route('admin.categories.index')],
                    ['label' => 'Authors', 'value' => Author::count(), 'icon' => 'bi-pencil-square', 'tone' => 'rose', 'url' => route('admin.authors.index')],
                ],
                'recentActivity' => $recentActivity,
                'recentBooks' => $recentBooks->map(fn (Book $book) => [
                    'title' => $book->title,
                    'isbn' => $book->isbn,
                    'cover' => $book->cover
                        ? (str_starts_with($book->cover, 'http://') || str_starts_with($book->cover, 'https://')
                            ? $book->cover
                            : asset('storage/' . ltrim($book->cover, '/')))
                        : null,
                    'category' => $book->category?->name ?? 'No Category',
                    'seller' => $book->seller?->name ?? 'No Seller',
                    'price' => $book->price,
                    'status' => $book->status,
                ])->values(),
                'recentOrders' => $recentOrders->map(fn (Order $order, int $index) => [
                    'number' => $order->order_number,
                    'book' => $order->book?->title,
                    'customer' => $order->user?->name ?? $order->full_name,
                    'total' => $order->total_price,
                    'status' => $order->order_status,
                    'rowNumber' => $index + 1,
                ])->values(),
                'monthlyLabels' => $months->pluck('label')->values(),
                'monthlyOrders' => $monthlyOrders,
                'monthlyRevenue' => $monthlyRevenue,
                'glanceLabel' => $months->last()['label'] ?? 'Latest month',
                'glanceOrders' => $monthlyOrders[count($monthlyOrders) - 1] ?? 0,
                'glanceRevenue' => $monthlyRevenue[count($monthlyRevenue) - 1] ?? 0,
                'links' => [
                    'bookCreate' => route('admin.books.create'),
                    'books' => route('admin.books.index'),
                    'categoryCreate' => route('admin.categories.create'),
                    'authorCreate' => route('admin.authors.create'),
                    'users' => route('admin.users.index'),
                ],
            ],
        ]);
    }
}
