<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AuthorsController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $authors = Author::query()
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('bio', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('Admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('Admin.authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
            'bio' => 'nullable|string|max:3000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $hasBioColumn = Schema::hasColumn('authors', 'bio');
        $hasPhotoColumn = Schema::hasColumn('authors', 'photo');
        $hasStatusColumn = Schema::hasColumn('authors', 'status');

        $photoPath = null;

        if ($hasPhotoColumn && $request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store(
                'authors',
                'public'
            );
        }

        $payload = [
            'name' => $validated['name'],
        ];

        if ($hasBioColumn) {
            $payload['bio'] = $validated['bio'] ?? null;
        }

        if ($hasPhotoColumn) {
            $payload['photo'] = $photoPath;
        }

        if ($hasStatusColumn) {
            $payload['status'] = $request->boolean('status', true);
        }

        $author = Author::create($payload);

        $this->activityLogService->log(
            'created',
            'Authors',
            "Author \"{$author->name}\" was created."
        );

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author added successfully.');
    }

    public function show(Author $author)
    {
        return view('Admin.authors.show', compact('author'));
    }

    public function toggleStatus(Author $author)
    {
        if (!Schema::hasColumn('authors', 'status')) {
            return back()->with(
                'error',
                'Status column is missing in authors table.'
            );
        }

        $oldStatus = $author->status;

        $author->update([
            'status' => !$author->status,
        ]);

        $this->activityLogService->log(
            'updated',
            'Authors',
            "Author \"{$author->name}\" status changed from \"" .
            ($oldStatus ? 'Active' : 'Inactive') .
            "\" to \"" .
            ($author->status ? 'Active' : 'Inactive') .
            "\"."
        );

        return back()
            ->with('success', 'Author status updated successfully.');
    }

    public function edit(Author $author)
    {
        return view('Admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name,' . $author->id,
            'bio' => 'nullable|string|max:3000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $hasBioColumn = Schema::hasColumn('authors', 'bio');
        $hasPhotoColumn = Schema::hasColumn('authors', 'photo');
        $hasStatusColumn = Schema::hasColumn('authors', 'status');

        $photoPath = $hasPhotoColumn
            ? $author->photo
            : null;

        if ($hasPhotoColumn && $request->hasFile('photo')) {
            if (
                !empty($author->photo) &&
                Storage::disk('public')->exists($author->photo)
            ) {
                Storage::disk('public')->delete($author->photo);
            }

            $photoPath = $request->file('photo')->store(
                'authors',
                'public'
            );
        }

        $payload = [
            'name' => $validated['name'],
        ];

        if ($hasBioColumn) {
            $payload['bio'] = $validated['bio'] ?? null;
        }

        if ($hasPhotoColumn) {
            $payload['photo'] = $photoPath;
        }

        if ($hasStatusColumn) {
            $payload['status'] = $request->boolean('status', false);
        }

        $author->update($payload);

        $this->activityLogService->log(
            'updated',
            'Authors',
            "Author \"{$author->name}\" was updated."
        );

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author updated successfully.');
    }

    public function destroy(Author $author)
    {
        $authorName = $author->name;

        if (
            Schema::hasColumn('authors', 'photo') &&
            !empty($author->photo) &&
            Storage::disk('public')->exists($author->photo)
        ) {
            Storage::disk('public')->delete($author->photo);
        }

        $this->activityLogService->log(
            'deleted',
            'Authors',
            "Author \"{$authorName}\" was deleted."
        );

        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}

