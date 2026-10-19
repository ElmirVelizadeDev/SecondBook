<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    public function index(Request $request)
    {
        $query = Shipping::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shippings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalShippings = Shipping::count();
        $activeShippings = Shipping::where('status', true)->count();
        $inactiveShippings = Shipping::where('status', false)->count();
        $freeShippings = Shipping::where('price', 0)->count();

        if ($request->expectsJson()) {
            return response()->json([
                'shippings' => $shippings->getCollection()->map(fn (Shipping $shipping) => [
                    'id' => $shipping->id,
                    'name' => $shipping->name,
                    'description' => $shipping->description,
                    'price' => $shipping->price,
                    'delivery_time' => $shipping->delivery_time,
                    'status' => $shipping->status,
                ])->values(),
                'pagination' => [
                    'from' => $shippings->firstItem(),
                    'to' => $shippings->lastItem(),
                    'total' => $shippings->total(),
                    'current_page' => $shippings->currentPage(),
                    'last_page' => $shippings->lastPage(),
                    'pages' => collect($shippings->getUrlRange(
                        max(1, $shippings->currentPage() - 2),
                        min($shippings->lastPage(), $shippings->currentPage() + 2)
                    ))->map(fn ($url, $page) => ['page' => $page, 'url' => $url])->values(),
                    'previous_url' => $shippings->previousPageUrl(),
                    'next_url' => $shippings->nextPageUrl(),
                ],
                'stats' => [
                    'total' => $totalShippings,
                    'active' => $activeShippings,
                    'inactive' => $inactiveShippings,
                    'free' => $freeShippings,
                ],
            ]);
        }

        return view('Admin.shipping.index', compact(
            'shippings',
            'totalShippings',
            'activeShippings',
            'inactiveShippings',
            'freeShippings'
        ));
    }

    public function create()
    {
        return view('Admin.shipping.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'delivery_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        $shipping = Shipping::create($validated);

        $this->activityLogService->log(
            'created',
            'Shipping',
            "Shipping method \"{$shipping->name}\" was created."
        );

        return redirect()
            ->route('admin.shipping.index')
            ->with('success', 'Shipping method created successfully.');
    }

    public function edit(Shipping $shipping)
    {
        return view('Admin.shipping.edit', compact('shipping'));
    }

    public function update(Request $request, Shipping $shipping)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'delivery_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        $shipping->update($validated);

        $this->activityLogService->log(
            'updated',
            'Shipping',
            "Shipping method \"{$shipping->name}\" was updated."
        );

        return redirect()
            ->route('admin.shipping.index')
            ->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(Request $request, Shipping $shipping)
    {
        $shippingName = $shipping->name;

        $this->activityLogService->log(
            'deleted',
            'Shipping',
            "Shipping method \"{$shippingName}\" was deleted."
        );

        $shipping->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'The shipping method has been deleted successfully.',
                'id' => $shipping->id,
            ]);
        }

        return redirect()
            ->route('admin.shipping.index')
            ->with('success', 'Shipping method deleted successfully.');
    }
}

