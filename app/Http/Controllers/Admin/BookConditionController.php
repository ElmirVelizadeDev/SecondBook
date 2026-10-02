<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class BookConditionController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Default book conditions.
     */
    protected function getDefaultConditions(): array
    {
        return [
            [
                'id' => 'new',
                'name' => 'New',
                'description' => 'Unused copy with no marks, folds, or shelf wear.',
                'status' => 1,
            ],
            [
                'id' => 'like_new',
                'name' => 'Like New',
                'description' => 'Barely used and looks almost brand new.',
                'status' => 1,
            ],
            [
                'id' => 'good',
                'name' => 'Good',
                'description' => 'Used with light wear but fully readable.',
                'status' => 1,
            ],
            [
                'id' => 'fair',
                'name' => 'Fair',
                'description' => 'Readable copy with visible wear and signs of use.',
                'status' => 1,
            ],
        ];
    }

    /**
     * Get all book conditions.
     */
    protected function getConditions(): array
    {
        $savedConditions = session('admin_book_conditions');

        $savedConditions = is_array($savedConditions)
            ? $savedConditions
            : [];

        $conditions = [];

        /*
         * Merge saved values with default conditions.
         */
        foreach ($this->getDefaultConditions() as $defaultCondition) {

            $savedCondition = collect($savedConditions)
                ->firstWhere('id', $defaultCondition['id']);

            $condition = $savedCondition
                ? array_merge($defaultCondition, $savedCondition)
                : $defaultCondition;

            $booksQuery = Book::query()
                ->where('condition', $condition['id']);

            $condition['books_count'] = (int) $booksQuery->count();

            $condition['created_at'] = $booksQuery
                ->orderBy('created_at')
                ->value('created_at')
                ?? ($savedCondition['created_at'] ?? now()->format('Y-m-d'));

            $conditions[] = $condition;
        }

        /*
         * Add custom conditions.
         */
        foreach ($savedConditions as $savedCondition) {

            if (!collect($conditions)->contains(
                fn ($condition) => $condition['id'] === $savedCondition['id']
            )) {

                $booksQuery = Book::query()
                    ->where('condition', $savedCondition['id']);

                $savedCondition['books_count'] = (int) $booksQuery->count();

                $savedCondition['created_at'] = $booksQuery
                    ->orderBy('created_at')
                    ->value('created_at')
                    ?? ($savedCondition['created_at'] ?? now()->format('Y-m-d'));

                $conditions[] = $savedCondition;
            }
        }

        return $conditions;
    }

    /**
     * Save conditions into session.
     */
    protected function saveConditions(array $conditions): void
    {
        session([
            'admin_book_conditions' => $conditions,
        ]);
    }

    /**
     * Display book conditions.
     */
    public function index(Request $request)
    {
        $conditions = $this->getConditions();

        /*
         * Search filter.
         */
        if ($request->filled('search')) {

            $search = strtolower(trim($request->search));

            $conditions = array_values(
                array_filter(
                    $conditions,
                    function ($condition) use ($search) {
                        return str_contains(
                            strtolower($condition['name']),
                            $search
                        );
                    }
                )
            );
        }

        /*
         * Status filter.
         */
        if ($request->filled('status')) {

            $status = $request->status;

            $conditions = array_values(
                array_filter(
                    $conditions,
                    function ($condition) use ($status) {
                        return $status === 'active'
                            ? (int) $condition['status'] === 1
                            : (int) $condition['status'] === 0;
                    }
                )
            );
        }

        return view(
            'admin.book-condition.index',
            compact('conditions')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.book-condition.create');
    }

    /**
     * Store a new book condition.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        $conditions = $this->getConditions();

        $condition = [
            'id' => 'custom-' . uniqid(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'status' => (int) $validated['status'],
            'books_count' => 0,
            'created_at' => now()->format('Y-m-d'),
        ];

        $conditions[] = $condition;

        $this->saveConditions($conditions);

        $this->activityLogService->log(
            'created',
            'Book Conditions',
            "Book condition \"{$condition['name']}\" was created."
        );

        return redirect()
            ->route('admin.book.conditions.index')
            ->with('success', 'Condition added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(string $condition)
    {
        $conditions = $this->getConditions();

        $selectedCondition = collect($conditions)
            ->firstWhere('id', $condition);

        if (!$selectedCondition) {
            abort(404);
        }

        return view(
            'admin.book-condition.edit',
            compact('selectedCondition')
        );
    }

    /**
     * Update a book condition.
     */
    public function update(Request $request, string $condition)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        $conditions = $this->getConditions();

        $index = collect($conditions)->search(
            fn ($item) => $item['id'] === $condition
        );

        if ($index === false) {
            abort(404);
        }

        $oldName = $conditions[$index]['name'];

        $conditions[$index]['name'] = $validated['name'];

        $conditions[$index]['description'] =
            $validated['description'] ?? '';

        $conditions[$index]['status'] =
            (int) $validated['status'];

        $this->saveConditions($conditions);

        $this->activityLogService->log(
            'updated',
            'Book Conditions',
            "Book condition \"{$oldName}\" was updated."
        );

        return redirect()
            ->route('admin.book.conditions.index')
            ->with('success', 'Condition updated successfully.');
    }

    /**
     * Toggle condition status.
     */
    public function status(string $condition)
    {
        $conditions = $this->getConditions();

        $index = collect($conditions)->search(
            fn ($item) => $item['id'] === $condition
        );

        if ($index === false) {
            abort(404);
        }

        $conditionName = $conditions[$index]['name'];

        $conditions[$index]['status'] =
            (int) $conditions[$index]['status'] === 1
                ? 0
                : 1;

        $newStatus =
            (int) $conditions[$index]['status'] === 1
                ? 'active'
                : 'inactive';

        $this->saveConditions($conditions);

        $this->activityLogService->log(
            'updated',
            'Book Conditions',
            "Book condition \"{$conditionName}\" status was changed to {$newStatus}."
        );

        return redirect()
            ->route('admin.book.conditions.index')
            ->with('success', 'Condition status updated successfully.');
    }

    /**
     * Delete a book condition.
     */
    public function destroy(string $condition)
    {
        $conditions = $this->getConditions();

        $selectedCondition = collect($conditions)
            ->firstWhere('id', $condition);

        if (!$selectedCondition) {
            abort(404);
        }

        $conditionName = $selectedCondition['name'];

        $conditions = array_values(
            array_filter(
                $conditions,
                fn ($item) => $item['id'] !== $condition
            )
        );

        $this->saveConditions($conditions);

        $this->activityLogService->log(
            'deleted',
            'Book Conditions',
            "Book condition \"{$conditionName}\" was deleted."
        );

        return redirect()
            ->route('admin.book.conditions.index')
            ->with('success', 'Condition deleted successfully.');
    }
}
