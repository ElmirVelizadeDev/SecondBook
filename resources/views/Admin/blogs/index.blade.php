
@extends('layout.admin.master')

@section('title', 'Blogs')

@push('css')
<link rel="stylesheet" href="{{ asset('admin/css/blog.css') }}">
@endpush

@section('content')
<div class="dashboard-section blogs-page">

    <div class="dashboard-panel blogs-header-panel">
        <div class="blogs-header-content">
            <div>
                <h5>Blogs</h5>
                <p>Manage your blog posts and publications</p>
            </div>

            <a href="{{ route('admin.blogs.create') }}" class="blogs-add-btn">
                <i class="bi bi-plus-lg"></i>
                <span>Add Blog</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="blogs-alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button"
                    class="blogs-alert-close"
                    onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif

    <div class="dashboard-panel blogs-filter-panel">
        <form method="GET"
              action="{{ route('admin.blogs.index') }}"
              id="blogs-filter-form">

            <div class="blogs-filter-row">
                <div class="blogs-search">
                    <label for="blog-search">Search</label>

                    <div class="blogs-search-group">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               id="blog-search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search blog posts...">
                    </div>
                </div>

                <div class="blogs-status-filter">
                    <label for="blog-status">Status</label>

                    <select id="blog-status" name="status">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                            Published
                        </option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>
                    </select>
                </div>

                <div class="blogs-filter-actions">
                    <button type="submit" class="blogs-search-btn">
                        <i class="bi bi-search"></i>
                        Search
                    </button>

                    <a href="{{ route('admin.blogs.index') }}"
                       class="blogs-reset-btn"
                       id="blogs-reset-btn"
                       title="Reset filters"
                       @if(!request()->filled('search') && !request()->filled('status')) hidden @endif>
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div id="blogs-results">
        <div class="dashboard-panel blogs-table-panel">
            <div class="blogs-table-wrapper">
                <table class="blogs-table">
                    <thead>
                        <tr>
                            <th class="blog-col-id">#</th>
                            <th class="blog-col-image">Image</th>
                            <th>Blog</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th class="blog-col-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($blogs as $blog)
                            <tr id="blog-row-{{ $blog->id }}">
                                <td class="blog-id">{{ $blog->id }}</td>

                                <td>
                                    @if($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}"
                                             alt="{{ $blog->title }}"
                                             class="blog-image">
                                    @else
                                        <div class="blog-image-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="blog-info">
                                        <div class="blog-name">{{ $blog->title }}</div>

                                        @if($blog->excerpt)
                                            <div class="blog-excerpt">
                                                {{ Str::limit($blog->excerpt, 75) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <span class="blog-author">
                                        {{ $blog->author?->name ?? 'Admin' }}
                                    </span>
                                </td>

                                <td>
                                    @if($blog->status === 'published')
                                        <span class="blog-status blog-status-published">
                                            <span></span>
                                            Published
                                        </span>
                                    @else
                                        <span class="blog-status blog-status-draft">
                                            <span></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($blog->published_at)
                                        <div class="blog-date">
                                            {{ $blog->published_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="blog-no-date">—</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="blog-actions">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}"
                                           class="blog-edit-btn"
                                           title="Edit Blog">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.blogs.destroy', $blog) }}"
                                              method="POST"
                                              class="blog-delete-form"
                                              data-blog-id="{{ $blog->id }}"
                                              data-blog-title="{{ $blog->title }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="blog-delete-btn"
                                                    title="Delete Blog">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="blogs-empty">
                                        <div class="blogs-empty-icon">
                                            <i class="bi bi-journal-text"></i>
                                        </div>

                                        <h6>No Blog Posts Found</h6>
                                        <p>There are no blog posts matching your search.</p>

                                        <a href="{{ route('admin.blogs.create') }}"
                                           class="blogs-add-btn">
                                            <i class="bi bi-plus-lg"></i>
                                            Add Blog
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($blogs->hasPages())
                <div class="blogs-pagination">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('blogs-filter-form');
    const results = document.getElementById('blogs-results');
    const searchInput = document.getElementById('blog-search');
    const statusSelect = document.getElementById('blog-status');
    const resetButton = document.getElementById('blogs-reset-btn');

    let requestController = null;

    function updateResetButton() {
        resetButton.hidden = !searchInput.value.trim() && !statusSelect.value;
    }

    async function loadBlogs(url, updateHistory = true) {
        if (requestController) {
            requestController.abort();
        }

        requestController = new AbortController();

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: requestController.signal
            });

            if (!response.ok) {
                throw new Error('Failed to load blog posts.');
            }

            const html = await response.text();
            const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
            const newResults = parsedDocument.getElementById('blogs-results');
            const newForm = parsedDocument.getElementById('blogs-filter-form');

            if (!newResults || !newForm) {
                throw new Error('Could not update the blog results.');
            }

            results.innerHTML = newResults.innerHTML;

            searchInput.value = newForm.querySelector('[name="search"]').value;
            statusSelect.value = newForm.querySelector('[name="status"]').value;

            updateResetButton();

            if (updateHistory) {
                history.pushState({}, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Blog posts could not be loaded. Please try again.'
                });
            }
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
        const url = filterForm.action + (query ? '?' + query : '');

        loadBlogs(url);
    });

    searchInput.addEventListener('input', updateResetButton);

    resetButton.addEventListener('click', function (event) {
        event.preventDefault();

        searchInput.value = '';
        statusSelect.value = '';

        updateResetButton();
        loadBlogs(filterForm.action);
    });

    results.addEventListener('click', function (event) {
        const pageLink = event.target.closest('.blogs-pagination a');

        if (!pageLink) {
            return;
        }

        event.preventDefault();
        loadBlogs(pageLink.href);
    });

    window.addEventListener('popstate', function () {
        loadBlogs(window.location.href, false);
    });

    results.addEventListener('submit', function (event) {
        const deleteForm = event.target.closest('.blog-delete-form');

        if (!deleteForm) {
            return;
        }

        event.preventDefault();

        const blogId = deleteForm.dataset.blogId;
        const blogTitle = deleteForm.dataset.blogTitle;
        const csrfToken = deleteForm.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            return;
        }

        Swal.fire({
            title: 'Delete Blog?',
            text: `"${blogTitle}" will be permanently deleted.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'blog-delete-popup',
                confirmButton: 'blog-delete-confirm',
                cancelButton: 'blog-delete-cancel'
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
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        _token: csrfToken,
                        _method: 'DELETE'
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to delete blog.');
                }

                await loadBlogs(window.location.href, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    text: data.message || 'Blog deleted successfully.',
                    timer: 1200,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: error.message || 'Something went wrong.'
                });
            }
        });
    });

    updateResetButton();
});
</script>
@endpush