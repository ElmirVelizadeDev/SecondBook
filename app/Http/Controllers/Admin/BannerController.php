<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display a listing of banners.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'subtitle',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        // Order by position first, then newest
        $banners = $query
            ->orderBy('position')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'banners' => $banners->getCollection()->map(fn ($banner) => [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle,
                    'image_url' => $banner->image ? asset('storage/' . $banner->image) : null,
                    'position' => $banner->position,
                    'status' => $banner->status,
                    'start_date' => $banner->start_date?->format('d M Y'),
                    'end_date' => $banner->end_date?->format('d M Y'),
                    'edit_url' => route('admin.banners.edit', $banner),
                    'delete_url' => route('admin.banners.destroy', $banner),
                ]),
                'pagination' => [
                    'first_item' => $banners->firstItem(),
                    'last_item' => $banners->lastItem(),
                    'total' => $banners->total(),
                    'current_page' => $banners->currentPage(),
                    'last_page' => $banners->lastPage(),
                    'links' => $banners->linkCollection()->toArray(),
                ],
                'search' => $request->input('search', ''),
                'status' => $request->input('status', ''),
            ]);
        }

        return view(
            'Admin.banners.index',
            compact('banners')
        );
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('Admin.banners.create');
    }

    /**
     * Store a newly created banner.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'subtitle' => 'nullable|string|max:255',

            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'button_text' => 'nullable|string|max:100',

            'button_url' => 'nullable|string|max:500',

            'position' => 'required|integer|min:0',

            'status' => 'required|in:active,inactive',

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Upload image
        $validated['image'] = $request
            ->file('image')
            ->store('banners', 'public');

        $banner = Banner::create($validated);

        $this->activityLogService->log(
            'created',
            'Banners',
            "Banner \"{$banner->title}\" was created."
        );

        return redirect()
            ->route('admin.banners.index')
            ->with(
                'success',
                'Banner created successfully.'
            );
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner)
    {
        return view(
            'Admin.banners.edit',
            compact('banner')
        );
    }

    /**
     * Update the specified banner.
     */
    public function update(
        Request $request,
        Banner $banner
    ) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'subtitle' => 'nullable|string|max:255',

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'button_text' => 'nullable|string|max:100',

            'button_url' => 'nullable|string|max:500',

            'position' => 'required|integer|min:0',

            'status' => 'required|in:active,inactive',

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Replace image if a new one was uploaded
        if ($request->hasFile('image')) {
            if (
                $banner->image &&
                Storage::disk('public')->exists($banner->image)
            ) {
                Storage::disk('public')->delete(
                    $banner->image
                );
            }

            $validated['image'] = $request
                ->file('image')
                ->store('banners', 'public');
        }

        $banner->update($validated);

        $this->activityLogService->log(
            'updated',
            'Banners',
            "Banner \"{$banner->title}\" was updated."
        );

        return redirect()
            ->route('admin.banners.index')
            ->with(
                'success',
                'Banner updated successfully.'
            );
    }

    /**
     * Remove the specified banner.
     */
    public function destroy(Banner $banner)
    {
        $bannerTitle = $banner->title;

        // Delete image from storage
        if (
            $banner->image &&
            Storage::disk('public')->exists($banner->image)
        ) {
            Storage::disk('public')->delete(
                $banner->image
            );
        }

        $this->activityLogService->log(
            'deleted',
            'Banners',
            "Banner \"{$bannerTitle}\" was deleted."
        );

        $banner->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.banners.index')
            ->with(
                'success',
                'Banner deleted successfully.'
            );
    }
}

