<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PublishersController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $publishers = Publisher::query()
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('country', 'like', '%' . $search . '%')
                        ->orWhere('website', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('Admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('Admin.publishers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'country' => 'nullable|string|max:120',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:2000',
            'status' => 'nullable|boolean',
        ]);

        $hasLogoColumn = Schema::hasColumn('publishers', 'logo');
        $hasCountryColumn = Schema::hasColumn('publishers', 'country');
        $hasWebsiteColumn = Schema::hasColumn('publishers', 'website');
        $hasDescriptionColumn = Schema::hasColumn('publishers', 'description');
        $hasStatusColumn = Schema::hasColumn('publishers', 'status');

        $logoPath = null;

        if ($hasLogoColumn && $request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store(
                'publishers',
                'public'
            );
        }

        $payload = [
            'name' => $validated['name'],
        ];

        if ($hasLogoColumn) {
            $payload['logo'] = $logoPath;
        }

        if ($hasCountryColumn) {
            $payload['country'] = $validated['country'] ?? null;
        }

        if ($hasWebsiteColumn) {
            $payload['website'] = $validated['website'] ?? null;
        }

        if ($hasDescriptionColumn) {
            $payload['description'] = $validated['description'] ?? null;
        }

        if ($hasStatusColumn) {
            $payload['status'] = $request->boolean('status', true);
        }

        $publisher = Publisher::create($payload);

        $this->activityLogService->log(
            'created',
            'Publishers',
            "Publisher \"{$publisher->name}\" was created."
        );

        return redirect()
            ->route('admin.publishers.index')
            ->with('success', 'Publisher added successfully.');
    }

    public function show(Publisher $publisher)
    {
        return view('Admin.publishers.show', compact('publisher'));
    }

    public function toggleStatus(Publisher $publisher)
    {
        if (!Schema::hasColumn('publishers', 'status')) {
            return back()->with(
                'error',
                'Status column is missing in publishers table.'
            );
        }

        $oldStatus = $publisher->status;

        $publisher->update([
            'status' => !$publisher->status,
        ]);

        $this->activityLogService->log(
            'updated',
            'Publishers',
            "Publisher \"{$publisher->name}\" status changed from \"" .
            ($oldStatus ? 'Active' : 'Inactive') .
            "\" to \"" .
            ($publisher->status ? 'Active' : 'Inactive') .
            "\"."
        );

        return back()
            ->with('success', 'Publisher status updated successfully.');
    }

    public function edit(Publisher $publisher)
    {
        return view('Admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'country' => 'nullable|string|max:120',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:2000',
            'status' => 'nullable|boolean',
        ]);

        $hasLogoColumn = Schema::hasColumn('publishers', 'logo');
        $hasCountryColumn = Schema::hasColumn('publishers', 'country');
        $hasWebsiteColumn = Schema::hasColumn('publishers', 'website');
        $hasDescriptionColumn = Schema::hasColumn('publishers', 'description');
        $hasStatusColumn = Schema::hasColumn('publishers', 'status');

        $logoPath = $hasLogoColumn
            ? $publisher->logo
            : null;

        if ($hasLogoColumn && $request->hasFile('logo')) {
            if (
                !empty($publisher->logo) &&
                Storage::disk('public')->exists($publisher->logo)
            ) {
                Storage::disk('public')->delete($publisher->logo);
            }

            $logoPath = $request->file('logo')->store(
                'publishers',
                'public'
            );
        }

        $payload = [
            'name' => $validated['name'],
        ];

        if ($hasLogoColumn) {
            $payload['logo'] = $logoPath;
        }

        if ($hasCountryColumn) {
            $payload['country'] = $validated['country'] ?? null;
        }

        if ($hasWebsiteColumn) {
            $payload['website'] = $validated['website'] ?? null;
        }

        if ($hasDescriptionColumn) {
            $payload['description'] = $validated['description'] ?? null;
        }

        if ($hasStatusColumn) {
            $payload['status'] = $request->boolean('status', false);
        }

        $publisher->update($payload);

        $this->activityLogService->log(
            'updated',
            'Publishers',
            "Publisher \"{$publisher->name}\" was updated."
        );

        return redirect()
            ->route('admin.publishers.index')
            ->with('success', 'Publisher updated successfully.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisherName = $publisher->name;

        if (
            Schema::hasColumn('publishers', 'logo') &&
            !empty($publisher->logo) &&
            Storage::disk('public')->exists($publisher->logo)
        ) {
            Storage::disk('public')->delete($publisher->logo);
        }

        $this->activityLogService->log(
            'deleted',
            'Publishers',
            "Publisher \"{$publisherName}\" was deleted."
        );

        $publisher->delete();

        return redirect()
            ->route('admin.publishers.index')
            ->with('success', 'Publisher deleted successfully.');
    }
}

