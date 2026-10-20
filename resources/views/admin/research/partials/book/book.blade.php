{{-- Success/Error Messages --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow">
    <div class="card-header bg-primary bg-gradient text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-book me-2"></i>Manage Books</h6>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createBookModal">
                <i class="fas fa-plus"></i> Create New Book
            </button>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="bookTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="book-published-tab" data-bs-toggle="tab"
                    data-bs-target="#book-published" type="button" role="tab" aria-selected="true">
                    <i class="fas fa-eye me-2"></i>Published
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="book-deleted-tab" data-bs-toggle="tab" data-bs-target="#book-deleted"
                    type="button" role="tab" aria-selected="false">
                    <i class="fas fa-trash me-2"></i>Deleted
                </button>
            </li>
        </ul>

        <div class="tab-content" id="bookTabContent">
            <div class="tab-pane fade show active" id="book-published" role="tabpanel">
                <!-- Search and Filter Section -->
                <div class="card-header bg-light border-bottom py-2 mb-3" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterFormBook" method="GET" action="{{ route('admin.books.index') }}" class="mb-0">
                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputBook" name="search"
                                           placeholder="Search by title, authors, publisher, ISBN, DOI, or keywords..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchBook" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Visibility Filter -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilterBook" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterBook" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByBook" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRangeBook" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromBook" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromBook" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToBook" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToBook" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnBook">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnBook">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterBook" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextBook">
                                        {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} of {{ $books->total() }}
                                    </span>
                                    <span id="loadingIndicatorBook" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="bookTableContainer">
                    @include('admin.research.partials.book.book-table', ['books' => $books])
                </div>
            </div>

                <div class="tab-pane fade" id="book-deleted" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="bookDeletedTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Deleted At</th>
                                    <th width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deletedBooks ?? [] as $book)
                                    @if ($book)
                                        <tr>
                                            <td>{{ data_get($book, 'title', 'N/A') }}</td>
                                            <td>{{ optional(data_get($book, 'deleted_at'))->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form method="POST"
                                                        action="{{ route('admin.books.restore', data_get($book, 'id')) }}"
                                                        class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-undo-alt"></i> Restore
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('admin.books.force-delete', data_get($book, 'id')) }}"
                                                        onsubmit="return confirm('Permanently delete this book? This action cannot be undone!');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash-alt"></i> Delete Forever
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No deleted books found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ isset($deletedBooks) ? $deletedBooks->links() : '' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Book Modal -->
    <div class="modal fade" id="createBookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Book</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="createBookForm" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.books.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="book-file" class="form-label">File (PDF) <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="book-file" name="file"
                                accept="application/pdf" required>
                        </div>
                        <div class="mb-3">
                            <label for="book-title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="book-title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Authors <span class="text-danger">*</span></label>
                            <div class="border rounded p-2" id="book-authors-container">
                                <div class="d-flex flex-wrap gap-2 mb-2" id="book-authors-chips"></div>
                                <input type="text" id="book-authors-input" class="form-control"
                                    placeholder="Type a name and press Enter">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Editors <small class="text-muted">(optional)</small></label>
                            <div class="border rounded p-2" id="book-editors-container">
                                <div class="d-flex flex-wrap gap-2 mb-2" id="book-editors-chips"></div>
                                <input type="text" id="book-editors-input" class="form-control"
                                    placeholder="Type a name and press Enter">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="book-published_at" class="form-label">Publication Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="book-published_at" name="published_at"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="book-publisher" class="form-label">Publisher <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="book-publisher" name="publisher"
                                    required>
                            </div>
                        </div>
                        <div class="row g-3 mt-0">
                            <div class="col-md-6">
                                <label for="book-isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="book-isbn" name="isbn"
                                    placeholder="10 or 13 digits" required>
                            </div>
                            <div class="col-md-6">
                                <label for="book-edition" class="form-label">Edition <small
                                        class="text-muted">(optional)</small></label>
                                <input type="text" class="form-control" id="book-edition" name="edition"
                                    placeholder="e.g., 2nd, Revised">
                            </div>
                        </div>
                        <div class="row g-3 mt-0">
                            <div class="col-md-6">
                                <label for="book-doi" class="form-label">DOI <small
                                        class="text-muted">(optional)</small></label>
                                <input type="text" class="form-control" id="book-doi" name="doi"
                                    placeholder="e.g., 10.1234/abc.5678">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_public"
                                            id="book-visibility_public" value="1" checked required>
                                        <label class="form-check-label" for="book-visibility_public">Public</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_public"
                                            id="book-visibility_private" value="0" required>
                                        <label class="form-check-label" for="book-visibility_private">Private</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                            <div class="border rounded p-2" id="book-keywords-container">
                                <div class="d-flex flex-wrap gap-2 mb-2" id="book-keywords-chips"></div>
                                <input type="text" id="book-keywords-input" class="form-control"
                                    placeholder="Type a keyword and press Enter">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            <i class="fas fa-save me-1"></i>Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Book Search and Filter Functionality
            let bookSearchTimeout;

            function initializeBookFilters() {
                const searchInput = document.getElementById('searchInputBook');
                const visibilityFilter = document.getElementById('visibilityFilterBook');
                const dateFilter = document.getElementById('dateFilterBook');
                const sortBy = document.getElementById('sortByBook');
                const clearSearchBtn = document.getElementById('clearSearchBook');
                const customDateRange = document.getElementById('customDateRangeBook');
                const dateFrom = document.getElementById('dateFromBook');
                const dateTo = document.getElementById('dateToBook');
                const searchFilterForm = document.getElementById('searchFilterFormBook');
                const applyFiltersBtn = document.getElementById('applyFiltersBtnBook');
                const resetFiltersBtn = document.getElementById('resetFiltersBtnBook');

                // Prevent form submission and use AJAX instead
                if (searchFilterForm) {
                    searchFilterForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        performBookSearch();
                    });
                }

                // Real-time search as user types
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(bookSearchTimeout);
                        bookSearchTimeout = setTimeout(() => {
                            performBookSearch();
                        }, 500);
                    });
                }

                // Clear search button
                if (clearSearchBtn) {
                    clearSearchBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        performBookSearch();
                    });
                }

                // Apply Filters button
                if (applyFiltersBtn) {
                    applyFiltersBtn.addEventListener('click', function() {
                        performBookSearch();
                    });
                }

                // Reset All button
                if (resetFiltersBtn) {
                    resetFiltersBtn.addEventListener('click', function() {
                        if (searchInput) searchInput.value = '';
                        if (visibilityFilter) visibilityFilter.value = '';
                        if (dateFilter) dateFilter.value = '';
                        if (sortBy) sortBy.value = 'newest';
                        if (dateFrom) dateFrom.value = '';
                        if (dateTo) dateTo.value = '';

                        if (customDateRange) {
                            customDateRange.style.display = 'none';
                        }

                        performBookSearch();
                    });
                }

                // Filter and sort change handlers
                [visibilityFilter, dateFilter, sortBy].forEach(element => {
                    if (element) {
                        element.addEventListener('change', performBookSearch);
                    }
                });

                // Custom date range inputs
                [dateFrom, dateTo].forEach(element => {
                    if (element) {
                        element.addEventListener('change', function() {
                            if (dateFilter && dateFilter.value === 'custom') {
                                performBookSearch();
                            }
                        });
                    }
                });

                // Show/hide custom date range
                if (dateFilter) {
                    dateFilter.addEventListener('change', function() {
                        if (this.value === 'custom') {
                            customDateRange.style.display = 'flex';
                        } else {
                            customDateRange.style.display = 'none';
                        }
                    });
                }
            }

            // Perform AJAX search for Books
            function performBookSearch() {
                const formData = new FormData(document.getElementById('searchFilterFormBook'));
                const params = new URLSearchParams(formData);

                // Add type parameter to ensure correct routing
                params.set('type', 'book');

                // Remove empty parameters for cleaner URLs
                for (let [key, value] of Array.from(params.entries())) {
                    if (!value || value.trim() === '') {
                        params.delete(key);
                    }
                }

                // Show loading indicator
                const loadingIndicator = document.getElementById('loadingIndicatorBook');
                const counterText = document.getElementById('counterTextBook');
                if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

                const searchUrl = `{{ route('admin.books.index') }}?${params.toString()}`;

                fetch(searchUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update book table
                    const tableContainer = document.getElementById('bookTableContainer');
                    if (tableContainer && data.html) {
                        tableContainer.innerHTML = data.html;
                        // Re-initialize DataTables after AJAX update
                        if (typeof initTables === 'function') initTables();
                    }

                    // Update counter
                    if (counterText) {
                        const from = data.from || 0;
                        const to = data.to || 0;
                        const total = data.totalCount || 0;
                        counterText.textContent = `${from} - ${to} of ${total}`;
                    }

                    // Update browser URL
                    const newUrl = params.toString() ? searchUrl : '{{ route('admin.books.index') }}?type=book';
                    window.history.pushState({}, '', newUrl);

                    // Hide loading indicator
                    if (loadingIndicator) loadingIndicator.style.display = 'none';
                })
                .catch(error => {
                    console.error('Book search error:', error);
                    if (loadingIndicator) loadingIndicator.style.display = 'none';

                    alert('Search failed. Please try again.');
                });
            }

            // Handle pagination clicks with AJAX
            document.addEventListener('click', function(e) {
                if (e.target.closest('#bookTableContainer #bookPagination a')) {
                    e.preventDefault();
                    const url = e.target.closest('a').href;

                    const loadingIndicator = document.getElementById('loadingIndicatorBook');
                    const counterText = document.getElementById('counterTextBook');

                    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update book table
                        const tableContainer = document.getElementById('bookTableContainer');
                        if (tableContainer && data.html) {
                            tableContainer.innerHTML = data.html;
                            // Re-initialize DataTables after AJAX update
                            if (typeof initTables === 'function') initTables();
                        }

                        // Update counter
                        if (counterText) {
                            const from = data.from || 0;
                            const to = data.to || 0;
                            const total = data.totalCount || 0;
                            counterText.textContent = `${from} - ${to} of ${total}`;
                        }

                        // Update browser URL
                        window.history.pushState({}, '', url);

                        // Hide loading indicator
                        if (loadingIndicator) loadingIndicator.style.display = 'none';

                        // Scroll to top of table section
                        const tableSection = document.querySelector('#book-published');
                        if (tableSection) {
                            tableSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    })
                    .catch(error => {
                        console.error('Pagination error:', error);
                        if (loadingIndicator) loadingIndicator.style.display = 'none';
                    });
                }
            });

            function initBookChips() {
                const authorsChips = document.getElementById('book-authors-chips');
                const authorsInput = document.getElementById('book-authors-input');
                if (authorsChips && authorsInput) {
                    function createChip(label, container, hiddenName, badgeClass) {
                        const chip = document.createElement('span');
                        chip.className = `badge ${badgeClass} d-inline-flex align-items-center`;
                        chip.style.gap = '6px';
                        chip.textContent = label;
                        const remove = document.createElement('button');
                        remove.type = 'button';
                        remove.className = 'btn-close btn-close-white btn-sm ms-2';
                        remove.addEventListener('click', () => chip.remove());
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = hiddenName;
                        hidden.value = label;
                        chip.appendChild(remove);
                        chip.appendChild(hidden);
                        container.appendChild(chip);
                    }

                    function addChipFromInput(input, container, hiddenName, badgeClass) {
                        const value = input.value.trim();
                        if (!value) return;
                        createChip(value, container, hiddenName, badgeClass);
                        input.value = '';
                    }

                    authorsInput.removeEventListener('keydown', authorsInput.chipHandler);
                    authorsInput.chipHandler = (e) => {
                        if (e.key === 'Enter' || e.key === ',') {
                            e.preventDefault();
                            addChipFromInput(authorsInput, authorsChips, 'authors[]', 'bg-secondary');
                        }
                    };
                    authorsInput.addEventListener('keydown', authorsInput.chipHandler);

                    const currentUser = @json(Auth::user()?->name ?? '');
                    if (currentUser && !authorsChips.querySelector('input[value="' + currentUser + '"]')) {
                        createChip(currentUser, authorsChips, 'authors[]', 'bg-secondary');
                    }

                    const editorsChips = document.getElementById('book-editors-chips');
                    const editorsInput = document.getElementById('book-editors-input');
                    if (editorsChips && editorsInput) {
                        editorsInput.removeEventListener('keydown', editorsInput.chipHandler);
                        editorsInput.chipHandler = (e) => {
                            if (e.key === 'Enter' || e.key === ',') {
                                e.preventDefault();
                                addChipFromInput(editorsInput, editorsChips, 'editors[]', 'bg-secondary');
                            }
                        };
                        editorsInput.addEventListener('keydown', editorsInput.chipHandler);
                    }

                    const keywordsChips = document.getElementById('book-keywords-chips');
                    const keywordsInput = document.getElementById('book-keywords-input');
                    if (keywordsChips && keywordsInput) {
                        keywordsInput.removeEventListener('keydown', keywordsInput.chipHandler);
                        keywordsInput.chipHandler = (e) => {
                            if (e.key === 'Enter' || e.key === ',') {
                                e.preventDefault();
                                addChipFromInput(keywordsInput, keywordsChips, 'keywords[]', 'bg-info');
                            }
                        };
                        keywordsInput.addEventListener('keydown', keywordsInput.chipHandler);
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize search and filter functionality
                initializeBookFilters();
                
                initBookChips();

                const bookModal = document.getElementById('createBookModal');
                if (bookModal) {
                    bookModal.addEventListener('shown.bs.modal', function() {
                        const form = document.getElementById('createBookForm');
                        if (form) {
                            form.reset();
                            const aChips = document.getElementById('book-authors-chips');
                            const eChips = document.getElementById('book-editors-chips');
                            const kChips = document.getElementById('book-keywords-chips');
                            if (aChips) aChips.innerHTML = '';
                            if (eChips) eChips.innerHTML = '';
                            if (kChips) kChips.innerHTML = '';
                        }
                        setTimeout(() => {
                            initBookChips();
                        }, 200);
                    });
                }

                const fileInput = document.getElementById('book-file');
                const titleInput = document.getElementById('book-title');
                fileInput?.addEventListener('change', (e) => {
                    const file = e.target.files?.[0];
                    if (!file) return;
                    const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
                    if (!titleInput.value) titleInput.value = fileNameBase;
                });

                const createForm = document.getElementById('createBookForm');
                createForm?.addEventListener('submit', function(e) {
                    const authorsChips = this.querySelectorAll('#book-authors-chips input[name="authors[]"]');
                    if (authorsChips.length === 0) {
                        e.preventDefault();
                        alert('Please add at least one author.');
                        return false;
                    }
                });
            });

            window.initBookChips = initBookChips;
        </script>

        <style>
        /* Embedded Search and Filter Layout for Books */
        .card-header.bg-light {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding: 0.5rem 1rem !important;
        }

        .card-header.bg-light .form-select-sm {
            font-size: 0.875rem !important;
            padding: 0.25rem 0.5rem !important;
            border-radius: 0.25rem !important;
        }

        .card-header.bg-light .input-group-text {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
        }

        .card-header.bg-light #searchInputBook {
            font-size: 0.875rem !important;
            padding: 0.25rem 0.5rem !important;
            border-radius: 0.25rem !important;
        }

        .card-header.bg-light .btn-sm {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
        }

        .card-header.bg-light .btn-outline-secondary {
            border-left: 0 !important;
            border-radius: 0 0.25rem 0.25rem 0 !important;
        }

        .card-header.bg-light .row.mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .card-header.bg-light .row.g-2 {
            --bs-gutter-x: 0.4rem !important;
            --bs-gutter-y: 0.4rem !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-header.bg-light {
                padding: 0.5rem 0.75rem !important;
            }

            .card-header.bg-light .text-md-end {
                text-align: center !important;
                margin-top: 0.4rem;
            }
        }

        /* Focus states */
        .card-header.bg-light .form-control:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15) !important;
        }

        .card-header.bg-light .form-select:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15) !important;
        }
        </style>
    @endpush
