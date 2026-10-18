<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $categories = Category::query()
            ->withCount('books')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when(request()->filled('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('Admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('Admin.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $baseSlug = !empty($validated['slug'])
            ? $validated['slug']
            : $validated['name'];

        $slug = $this->generateUniqueSlug($baseSlug);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $request->boolean('status'),
        ]);

        $this->activityLogService->log(
            'created',
            'Categories',
            "Category \"{$category->name}\" was created."
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category added successfully.');
    }

    public function edit(Category $category)
    {
        return view('Admin.category.edit', compact('category'));
    }

    public function show(Category $category)
    {
        $category->loadCount('books');

        return view('Admin.category.show', compact('category'));
    }

    public function toggleStatus(Category $category)
    {
        $oldStatus = $category->status;

        $category->update([
            'status' => !$category->status,
        ]);

        $this->activityLogService->log(
            'updated',
            'Categories',
            "Category \"{$category->name}\" status changed from \"" .
            ($oldStatus ? 'Active' : 'Inactive') .
            "\" to \"" .
            ($category->status ? 'Active' : 'Inactive') .
            "\"."
        );

        return back()
            ->with('success', 'Category status updated successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $baseSlug = !empty($validated['slug'])
            ? $validated['slug']
            : $validated['name'];

        $slug = $this->generateUniqueSlug(
            $baseSlug,
            $category->id
        );

        $imagePath = $category->image;

        if ($request->hasFile('image')) {
            if (
                !empty($category->image) &&
                Storage::disk('public')->exists($category->image)
            ) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store(
                'categories',
                'public'
            );
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $request->boolean('status'),
        ]);

        $this->activityLogService->log(
            'updated',
            'Categories',
            "Category \"{$category->name}\" was updated."
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $categoryName = $category->name;

        if (
            !empty($category->image) &&
            Storage::disk('public')->exists($category->image)
        ) {
            Storage::disk('public')->delete($category->image);
        }

        $this->activityLogService->log(
            'deleted',
            'Categories',
            "Category \"{$categoryName}\" was deleted."
        );

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function generateUniqueSlug(
        string $value,
        ?int $exceptId = null
    ): string {
        $slug = Str::slug($value);

        $slug = $slug !== ''
            ? $slug
            : 'category';

        $originalSlug = $slug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when(
                    $exceptId,
                    fn ($query) => $query->where('id', '!=', $exceptId)
                )
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
