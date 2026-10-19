<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $query = Blog::with('author');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'excerpt',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $blogs = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'blogs' => $blogs->getCollection()->map(fn ($blog) => [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'excerpt' => $blog->excerpt,
                    'slug' => $blog->slug,
                    'status' => $blog->status,
                    'image_url' => $blog->image ? asset('storage/' . $blog->image) : null,
                    'author' => $blog->author?->name,
                    'published_at' => $blog->published_at?->format('d M Y'),
                    'edit_url' => route('admin.blogs.edit', $blog),
                    'delete_url' => route('admin.blogs.destroy', $blog),
                ]),
                'pagination' => [
                    'first_item' => $blogs->firstItem(),
                    'last_item' => $blogs->lastItem(),
                    'total' => $blogs->total(),
                    'links' => $blogs->linkCollection()->toArray(),
                ],
                'filters' => $request->only(['search', 'status']),
            ]);
        }

        return view(
            'Admin.blogs.index',
            compact('blogs')
        );
    }

    public function create()
    {
        return view('Admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'excerpt' => 'nullable|string|max:1000',

            'content' => 'required|string',

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => 'required|in:draft,published',

            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug(
            $validated['title']
        );

        $validated['author_id'] = Auth::id();

        if (
            $validated['status'] === 'published' &&
            empty($validated['published_at'])
        ) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('blogs', 'public');
        }

        $blog = Blog::create($validated);

        $this->activityLogService->log(
            'created',
            'Blogs',
            "Blog post \"{$blog->title}\" was created."
        );

        return redirect()
            ->route('admin.blogs.index')
            ->with(
                'success',
                'Blog post created successfully.'
            );
    }

    public function edit(Blog $blog)
    {
        return view(
            'Admin.blogs.edit',
            compact('blog')
        );
    }

    public function update(
        Request $request,
        Blog $blog
    ) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'excerpt' => 'nullable|string|max:1000',

            'content' => 'required|string',

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => 'required|in:draft,published',

            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug(
            $validated['title']
        );

        if (
            $validated['status'] === 'published' &&
            empty($validated['published_at'])
        ) {
            $validated['published_at'] =
                $blog->published_at ?? now();
        }

        if ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            if (
                $blog->image &&
                Storage::disk('public')->exists($blog->image)
            ) {
                Storage::disk('public')->delete(
                    $blog->image
                );
            }

            $validated['image'] = $request
                ->file('image')
                ->store('blogs', 'public');
        }

        $blog->update($validated);

        $this->activityLogService->log(
            'updated',
            'Blogs',
            "Blog post \"{$blog->title}\" was updated."
        );

        return redirect()
            ->route('admin.blogs.index')
            ->with(
                'success',
                'Blog post updated successfully.'
            );
    }

    public function destroy(Blog $blog)
    {
        $blogTitle = $blog->title;

        // Delete image from storage if it exists.
        if (
            $blog->image &&
            Storage::disk('public')->exists($blog->image)
        ) {
            Storage::disk('public')->delete(
                $blog->image
            );
        }

        $this->activityLogService->log(
            'deleted',
            'Blogs',
            "Blog post \"{$blogTitle}\" was deleted."
        );

        $blog->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.blogs.index')
            ->with(
                'success',
                'Blog deleted successfully.'
            );
    }
}

