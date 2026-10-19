<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display FAQ list.
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'question',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'answer',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'category',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === '1'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FAQ List
        |--------------------------------------------------------------------------
        */

        $faqs = $query
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Faq::query()
            ->whereNotNull('category')
            ->where(
                'category',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        if ($request->expectsJson()) {
            return response()->json([
                'faqs' => $faqs->getCollection()->map(fn (Faq $faq) => [
                    'id' => $faq->id,
                    'category' => $faq->category,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'is_active' => $faq->is_active,
                    'sort_order' => $faq->sort_order,
                    'created_at' => $faq->created_at?->format('d M Y'),
                ])->values(),
                'categories' => $categories,
                'pagination' => [
                    'from' => $faqs->firstItem(),
                    'to' => $faqs->lastItem(),
                    'total' => $faqs->total(),
                    'current_page' => $faqs->currentPage(),
                    'last_page' => $faqs->lastPage(),
                    'links' => $faqs->getUrlRange(1, $faqs->lastPage()),
                ],
            ]);
        }

        return view(
            'Admin.faq.index',
            compact(
                'faqs',
                'categories'
            )
        );
    }

    /**
     * Show create FAQ form.
     */
    public function create()
    {
        return view('Admin.faq.create');
    }

    /**
     * Store new FAQ.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'question' => [
                'required',
                'string',
                'max:255',
            ],

            'answer' => [
                'required',
                'string',
                'max:5000',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $faq = Faq::create([
            'category' => $validated['category'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->activityLogService->log(
            'created',
            'FAQs',
            "FAQ \"{$faq->question}\" was created."
        );

        return redirect()
            ->route('admin.faq.index')
            ->with(
                'success',
                'FAQ created successfully.'
            );
    }

    /**
     * Show edit FAQ form.
     */
    public function edit(Faq $faq)
    {
        return view(
            'Admin.faq.edit',
            compact('faq')
        );
    }

    /**
     * Update FAQ.
     */
    public function update(
        Request $request,
        Faq $faq
    ) {
        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'question' => [
                'required',
                'string',
                'max:255',
            ],

            'answer' => [
                'required',
                'string',
                'max:5000',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $faq->update([
            'category' => $validated['category'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->activityLogService->log(
            'updated',
            'FAQs',
            "FAQ \"{$faq->question}\" was updated."
        );

        return redirect()
            ->route('admin.faq.index')
            ->with(
                'success',
                'FAQ updated successfully.'
            );
    }

    /**
     * Delete FAQ.
     */
    public function destroy(Faq $faq)
    {
        $faqQuestion = $faq->question;

        $this->activityLogService->log(
            'deleted',
            'FAQs',
            "FAQ \"{$faqQuestion}\" was deleted."
        );

        $faq->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.faq.index')
            ->with(
                'success',
                'FAQ deleted successfully.'
            );
    }
}

