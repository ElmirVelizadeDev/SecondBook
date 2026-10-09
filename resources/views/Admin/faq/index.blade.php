
@extends('layout.admin.master')

@section('title', 'FAQ')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/faq.css') }}">
@endpush

@section('content')
<div class="dashboard-section faq-page">

    <div class="dashboard-panel faq-header-panel">
        <div class="faq-header-content">
            <div>
                <h5>FAQ</h5>
                <p>Manage frequently asked questions on SecondBook</p>
            </div>

            <a href="{{ route('admin.faq.create') }}" class="faq-add-btn">
                <i class="bi bi-plus-lg"></i>
                <span>Add FAQ</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="faq-alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="faq-alert-close"
                    onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    <div class="dashboard-panel faq-filter-panel">
        <form method="GET" action="{{ route('admin.faq.index') }}"
              id="faq-filter-form">
            <div class="faq-filter-row">

                <div class="faq-search">
                    <label for="faq-search">Search</label>
                    <div class="faq-search-group">
                        <i class="bi bi-search"></i>
                        <input type="text" id="faq-search" name="search"
                               value="{{ request('search') }}"
                               placeholder="Question, answer or category...">
                    </div>
                </div>

                <div class="faq-category-filter">
                    <label for="faq-category">Category</label>
                    <select id="faq-category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}"
                                @selected(request('category') === $category)>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="faq-status-filter">
                    <label for="faq-status">Status</label>
                    <select id="faq-status" name="status">
                        <option value="">All Status</option>
                        <option value="1"
                            @selected(request('status') === '1')>
                            Active
                        </option>
                        <option value="0"
                            @selected(request('status') === '0')>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="faq-filter-actions">
                    <button type="submit" class="faq-search-btn">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.faq.index') }}"
                       id="faq-reset-btn"
                       class="faq-reset-btn"
                       title="Reset filters"
                       @if(
                           !request()->filled('search') &&
                           !request()->filled('category') &&
                           !request()->filled('status')
                       ) hidden @endif>
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    <div class="dashboard-panel faq-table-panel" id="faq-results">
        <div class="faq-table-header">
            <div>
                <h5>FAQ List</h5>
                <p>All frequently asked questions</p>
            </div>
            <span class="faq-count">{{ $faqs->total() }} FAQs</span>
        </div>

        <div class="faq-table-wrapper">
            <table class="faq-table">
                <thead>
                    <tr>
                        <th class="faq-col-id">#</th>
                        <th>Question</th>
                        <th class="faq-col-category">Category</th>
                        <th class="faq-col-answer">Answer</th>
                        <th class="faq-col-sort">Sort</th>
                        <th class="faq-col-status">Status</th>
                        <th class="faq-col-date">Created</th>
                        <th class="faq-col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($faqs as $faq)
                        <tr id="faq-row-{{ $faq->id }}">
                            <td class="faq-id">{{ $faq->id }}</td>

                            <td>
                                <div class="faq-question-info">
                                    <div class="faq-question-title">
                                        {{ $faq->question }}
                                    </div>
                                    <div class="faq-mobile-category">
                                        {{ $faq->category }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="faq-category">
                                    {{ $faq->category }}
                                </span>
                            </td>

                            <td>
                                <div class="faq-answer">{{ $faq->answer }}</div>
                            </td>

                            <td>
                                <span class="faq-sort">{{ $faq->sort_order }}</span>
                            </td>

                            <td>
                                @if($faq->is_active)
                                    <span class="faq-status faq-status-active">
                                        <span></span>
                                        Active
                                    </span>
                                @else
                                    <span class="faq-status faq-status-inactive">
                                        <span></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="faq-date">
                                    {{ $faq->created_at?->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <div class="faq-actions">
                                    <a href="{{ route('admin.faq.edit', $faq->id) }}"
                                       class="faq-edit-btn" title="Edit FAQ">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.faq.destroy', $faq->id) }}"
                                          method="POST"
                                          class="faq-delete-form"
                                          data-faq-id="{{ $faq->id }}"
                                          data-faq-question="{{ $faq->question }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="faq-delete-btn"
                                                title="Delete FAQ">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="faq-empty">
                                    <div class="faq-empty-icon">
                                        <i class="bi bi-question-circle"></i>
                                    </div>
                                    <h6>No FAQs Found</h6>
                                    <p>Create your first FAQ to get started.</p>
                                    <a href="{{ route('admin.faq.create') }}"
                                       class="faq-add-btn">
                                        <i class="bi bi-plus-lg"></i>
                                        Add FAQ
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($faqs->hasPages())
            <div class="faq-pagination">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('faq-filter-form');
    const results = document.getElementById('faq-results');
    const resetButton = document.getElementById('faq-reset-btn');

    function updateResetButton() {
        const search = filterForm.querySelector('[name="search"]').value.trim();
        const category = filterForm.querySelector('[name="category"]').value;
        const status = filterForm.querySelector('[name="status"]').value;

        resetButton.hidden = !(search || category || status);
    }

    async function loadFaqs(url, updateHistory = true) {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load FAQs.');
            }

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newResults = doc.getElementById('faq-results');
            const newForm = doc.getElementById('faq-filter-form');

            if (!newResults || !newForm) {
                throw new Error('FAQ results could not be found.');
            }

            results.innerHTML = newResults.innerHTML;

            ['search', 'category', 'status'].forEach(function (name) {
                filterForm.querySelector(`[name="${name}"]`).value =
                    newForm.querySelector(`[name="${name}"]`).value;
            });

            updateResetButton();

            if (updateHistory) {
                history.pushState({}, '', url);
            }
        } catch (error) {
            console.error('FAQ loading error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Loading Failed',
                text: 'Unable to load FAQs. Please try again.'
            });
        }
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const params = new URLSearchParams(new FormData(filterForm));

        for (const [key, value] of [...params.entries()]) {
            if (!value.trim()) {
                params.delete(key);
            }
        }

        params.delete('page');

        const query = params.toString();
        const url = query
            ? `${filterForm.action}?${query}`
            : filterForm.action;

        loadFaqs(url);
    });

    filterForm.querySelector('[name="search"]').addEventListener(
        'input',
        updateResetButton
    );

    filterForm.querySelector('[name="category"]').addEventListener(
        'change',
        updateResetButton
    );

    filterForm.querySelector('[name="status"]').addEventListener(
        'change',
        updateResetButton
    );

    resetButton.addEventListener('click', function (event) {
        event.preventDefault();

        filterForm.querySelector('[name="search"]').value = '';
        filterForm.querySelector('[name="category"]').value = '';
        filterForm.querySelector('[name="status"]').value = '';

        updateResetButton();
        loadFaqs(filterForm.action);
    });

    results.addEventListener('click', function (event) {
        const link = event.target.closest('.faq-pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();
        loadFaqs(link.href);
    });

    results.addEventListener('submit', function (event) {
        const deleteForm = event.target.closest('.faq-delete-form');

        if (!deleteForm) {
            return;
        }

        event.preventDefault();

        const faqId = deleteForm.dataset.faqId;
        const question = deleteForm.dataset.faqQuestion;
        const csrfToken = deleteForm.querySelector('[name="_token"]')?.value;

        if (!csrfToken) {
            Swal.fire('Error', 'CSRF token not found.', 'error');
            return;
        }

        Swal.fire({
            title: 'Delete FAQ?',
            text: `"${question}" will be permanently deleted.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'faq-delete-popup',
                confirmButton: 'faq-delete-confirm',
                cancelButton: 'faq-delete-cancel'
            }
        }).then(async function (result) {
            if (!result.isConfirmed) {
                return;
            }

            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function () {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch(deleteForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body: new URLSearchParams({
                        _token: csrfToken,
                        _method: 'DELETE'
                    })
                });

                const contentType = response.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {
                    throw new Error('Server returned an invalid response.');
                }

                const data = await response.json();

                if (!response.ok || data.success === false) {
                    throw new Error(
                        data.message || 'Something went wrong while deleting the FAQ.'
                    );
                }

                const row = document.getElementById(`faq-row-${faqId}`);

                if (row) {
                    row.remove();
                }

                await loadFaqs(window.location.href, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    text: data.message || 'FAQ deleted successfully.',
                    timer: 1200,
                    showConfirmButton: false
                });
            } catch (error) {
                console.error('FAQ delete error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: error.message || 'Unable to delete FAQ.'
                });
            }
        });
    });

    window.addEventListener('popstate', function () {
        loadFaqs(window.location.href, false);
    });
});
</script>
@endpush