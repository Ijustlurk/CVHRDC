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
            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-lines me-2"></i>Manage Preprints</h6>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPreprintModal">
                <i class="fas fa-plus"></i> Create New Preprint
            </button>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="preprintTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="preprint-published-tab" data-bs-toggle="tab"
                    data-bs-target="#preprint-published" type="button" role="tab" aria-selected="true">
                    <i class="fas fa-eye me-2"></i>Published
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="preprint-deleted-tab" data-bs-toggle="tab"
                    data-bs-target="#preprint-deleted" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-trash me-2"></i>Deleted
                </button>
            </li>
        </ul>

        <div class="tab-content" id="preprintTabContent">
            <div class="tab-pane fade show active" id="preprint-published" role="tabpanel">
                <!-- Search and Filter Section -->
                <div class="card-header bg-light border-bottom py-2 mb-3" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterFormPreprint" method="GET" action="{{ route('admin.research.index') }}" class="mb-0">
                        <input type="hidden" name="type" value="preprint">
                        
                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputPreprint" name="search"
                                           placeholder="Search by title, authors, repository, abstract, or keywords..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchPreprint" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Peer-reviewed Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="peerReviewedFilter" name="peer_reviewed">
                                    <option value="" {{ request('peer_reviewed') == '' ? 'selected' : '' }}>All Peer-reviewed Status</option>
                                    <option value="Yes" {{ request('peer_reviewed') == 'Yes' ? 'selected' : '' }}>Peer-reviewed</option>
                                    <option value="No" {{ request('peer_reviewed') == 'No' ? 'selected' : '' }}>Not Peer-reviewed</option>
                                </select>
                            </div>

                            <!-- Visibility Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilterPreprint" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterPreprint" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByPreprint" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRangePreprint" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromPreprint" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromPreprint" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToPreprint" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToPreprint" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnPreprint">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnPreprint">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterPreprint" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextPreprint">
                                        {{ $preprints->firstItem() ?? 0 }} - {{ $preprints->lastItem() ?? 0 }} of {{ $preprints->total() }}
                                    </span>
                                    <span id="loadingIndicatorPreprint" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="preprintTableContainer">
                    @include('admin.research.partials.preprint.preprint-table', ['preprints' => $preprints])
                </div>
            </div>

            <div class="tab-pane fade" id="preprint-deleted" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="preprintDeletedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Deleted At</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedPreprints ?? [] as $preprint)
                                @if ($preprint)
                                    <tr>
                                        <td>{{ $preprint->title ?? 'N/A' }}</td>
                                        <td>{{ optional($preprint->deleted_at)->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form method="POST"
                                                    action="{{ route('admin.preprints.restore', $preprint->id) }}"
                                                    class="me-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-undo-alt"></i> Restore
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ route('admin.preprints.force-delete', $preprint->id) }}"
                                                    onsubmit="return confirm('Permanently delete this preprint? This action cannot be undone!');">
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
                                        <p class="text-muted mb-0">No deleted preprints found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ isset($deletedPreprints) ? $deletedPreprints->links() : '' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Preprint Modal -->
<div class="modal fade" id="createPreprintModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Preprint</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createPreprintForm" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.preprints.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="preprint-file" class="form-label">File (PDF) <span
                                class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="preprint-file" name="file"
                            accept="application/pdf" required>
                    </div>
                    <div class="mb-3">
                        <label for="preprint-title" class="form-label">Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="preprint-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors <span class="text-danger">*</span></label>
                        <div class="border rounded p-2" id="preprint-authors-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="preprint-authors-chips"></div>
                            <input type="text" id="preprint-authors-input" class="form-control"
                                placeholder="Type a name and press Enter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="preprint-published_at" class="form-label">Publication Date</label>
                        <input type="date" class="form-control" id="preprint-published_at" name="published_at">
                    </div>
                    <div class="mb-3">
                        <label for="preprint-repository_name" class="form-label">Repository Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="preprint-repository_name"
                            name="repository_name" placeholder="e.g., arXiv, bioRxiv, institutional repository"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_public"
                                    id="preprint-visibility_public" value="1" checked required>
                                <label class="form-check-label" for="preprint-visibility_public">Public</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_public"
                                    id="preprint-visibility_private" value="0" required>
                                <label class="form-check-label" for="preprint-visibility_private">Private</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="preprint-abstract" class="form-label">Abstract <small
                                class="text-muted">(optional)</small></label>
                        <textarea class="form-control" id="preprint-abstract" name="abstract" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                        <div class="border rounded p-2" id="preprint-keywords-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="preprint-keywords-chips"></div>
                            <input type="text" id="preprint-keywords-input" class="form-control"
                                placeholder="Type a keyword and press Enter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peer-reviewed? <small class="text-muted">(optional)</small></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_peer_reviewed"
                                    id="preprint-peer_reviewed_yes" value="1">
                                <label class="form-check-label" for="preprint-peer_reviewed_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_peer_reviewed"
                                    id="preprint-peer_reviewed_no" value="0" checked>
                                <label class="form-check-label" for="preprint-peer_reviewed_no">No</label>
                            </div>
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
        // Initialize preprint functionality
        function initPreprintChips() {
            // Preprint authors chips
            const preprintAuthorsChips = document.getElementById('preprint-authors-chips');
            const preprintAuthorsInput = document.getElementById('preprint-authors-input');

            if (!preprintAuthorsChips || !preprintAuthorsInput) return;

            function preprintCreateChip(label, container, hiddenName) {
                const chip = document.createElement('span');
                chip.className = 'badge bg-secondary d-inline-flex align-items-center';
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

            function preprintAddChip(input, container) {
                const value = input.value.trim();
                if (!value) return;
                preprintCreateChip(value, container, 'authors[]');
                input.value = '';
            }

            preprintAuthorsInput.removeEventListener('keydown', preprintAuthorsInput.chipHandler);
            preprintAuthorsInput.chipHandler = (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    preprintAddChip(preprintAuthorsInput, preprintAuthorsChips);
                }
            };
            preprintAuthorsInput.addEventListener('keydown', preprintAuthorsInput.chipHandler);

            const preprintCurrentUser = @json(Auth::user()?->name ?? '');
            if (preprintCurrentUser && !preprintAuthorsChips.querySelector('input[value="' + preprintCurrentUser + '"]')) {
                preprintCreateChip(preprintCurrentUser, preprintAuthorsChips, 'authors[]');
            }

            // Preprint keywords chips
            const preprintKeywordsChips = document.getElementById('preprint-keywords-chips');
            const preprintKeywordsInput = document.getElementById('preprint-keywords-input');

            if (preprintKeywordsChips && preprintKeywordsInput) {
                function preprintAddKeyword(input, container) {
                    const value = input.value.trim();
                    if (!value) return;
                    const chip = document.createElement('span');
                    chip.className = 'badge bg-info d-inline-flex align-items-center';
                    chip.style.gap = '6px';
                    chip.textContent = value;
                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'btn-close btn-close-white btn-sm ms-2';
                    remove.addEventListener('click', () => chip.remove());
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'keywords[]';
                    hidden.value = value;
                    chip.appendChild(remove);
                    chip.appendChild(hidden);
                    container.appendChild(chip);
                    input.value = '';
                }

                preprintKeywordsInput.removeEventListener('keydown', preprintKeywordsInput.chipHandler);
                preprintKeywordsInput.chipHandler = (e) => {
                    if (e.key === 'Enter' || e.key === ',') {
                        e.preventDefault();
                        preprintAddKeyword(preprintKeywordsInput, preprintKeywordsChips);
                    }
                };
                preprintKeywordsInput.addEventListener('keydown', preprintKeywordsInput.chipHandler);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search and filter functionality
            initializePreprintFilters();

            // Initialize chips
            initPreprintChips();

            // Re-initialize chips when modal is shown
            const preprintModal = document.getElementById('createPreprintModal');
            if (preprintModal) {
                preprintModal.addEventListener('shown.bs.modal', function() {
                    const form = document.getElementById('createPreprintForm');
                    if (form) {
                        form.reset();
                        const authorsChips = document.getElementById('preprint-authors-chips');
                        const keywordsChips = document.getElementById('preprint-keywords-chips');
                        if (authorsChips) authorsChips.innerHTML = '';
                        if (keywordsChips) keywordsChips.innerHTML = '';
                    }

                    setTimeout(() => {
                        initPreprintChips();
                        const authorsChips = document.getElementById('preprint-authors-chips');
                        const currentUser = @json(Auth::user()?->name ?? '');
                        if (currentUser && authorsChips && !authorsChips.querySelector('input[value="' + currentUser + '"]')) {
                            const chip = document.createElement('span');
                            chip.className = 'badge bg-secondary d-inline-flex align-items-center';
                            chip.style.gap = '6px';
                            chip.textContent = currentUser;
                            const remove = document.createElement('button');
                            remove.type = 'button';
                            remove.className = 'btn-close btn-close-white btn-sm ms-2';
                            remove.addEventListener('click', () => chip.remove());
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'authors[]';
                            hidden.value = currentUser;
                            chip.appendChild(remove);
                            chip.appendChild(hidden);
                            authorsChips.appendChild(chip);
                        }
                    }, 200);
                });
            }

            // PDF filename to title
            const preprintFileInput = document.getElementById('preprint-file');
            const preprintTitleInput = document.getElementById('preprint-title');
            preprintFileInput?.addEventListener('change', async (e) => {
                const file = e.target.files?.[0];
                if (!file) return;
                const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
                if (!preprintTitleInput.value) preprintTitleInput.value = fileNameBase;
            });

            // Submit create preprint form
            const createPreprintForm = document.getElementById('createPreprintForm');
            createPreprintForm?.addEventListener('submit', function(e) {
                const authorsChips = this.querySelectorAll('#preprint-authors-chips input[name="authors[]"]');
                if (authorsChips.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one author.');
                    return false;
                }

                console.log('Form action:', this.action);
                console.log('CSRF token:', this.querySelector('input[name="_token"]')?.value);
                console.log('Form submitting with', authorsChips.length, 'authors');
            });
        });

        window.initPreprintChips = initPreprintChips;

        // Preprint Search and Filter Functionality
        let preprintSearchTimeout;

        function initializePreprintFilters() {
            const searchInput = document.getElementById('searchInputPreprint');
            const peerReviewedFilter = document.getElementById('peerReviewedFilter');
            const visibilityFilter = document.getElementById('visibilityFilterPreprint');
            const dateFilter = document.getElementById('dateFilterPreprint');
            const sortBy = document.getElementById('sortByPreprint');
            const clearSearchBtn = document.getElementById('clearSearchPreprint');
            const customDateRange = document.getElementById('customDateRangePreprint');
            const dateFrom = document.getElementById('dateFromPreprint');
            const dateTo = document.getElementById('dateToPreprint');
            const searchFilterForm = document.getElementById('searchFilterFormPreprint');
            const applyFiltersBtn = document.getElementById('applyFiltersBtnPreprint');
            const resetFiltersBtn = document.getElementById('resetFiltersBtnPreprint');

            if (searchFilterForm) {
                searchFilterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    performPreprintSearch();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(preprintSearchTimeout);
                    preprintSearchTimeout = setTimeout(() => {
                        performPreprintSearch();
                    }, 500);
                });
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    performPreprintSearch();
                });
            }

            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', function() {
                    performPreprintSearch();
                });
            }

            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (peerReviewedFilter) peerReviewedFilter.value = '';
                    if (visibilityFilter) visibilityFilter.value = '';
                    if (dateFilter) dateFilter.value = '';
                    if (sortBy) sortBy.value = 'newest';
                    if (dateFrom) dateFrom.value = '';
                    if (dateTo) dateTo.value = '';

                    if (customDateRange) {
                        customDateRange.style.display = 'none';
                    }

                    performPreprintSearch();
                });
            }

            [peerReviewedFilter, visibilityFilter, dateFilter, sortBy].forEach(element => {
                if (element) {
                    element.addEventListener('change', performPreprintSearch);
                }
            });

            [dateFrom, dateTo].forEach(element => {
                if (element) {
                    element.addEventListener('change', function() {
                        if (dateFilter && dateFilter.value === 'custom') {
                            performPreprintSearch();
                        }
                    });
                }
            });

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

        function performPreprintSearch() {
            const formData = new FormData(document.getElementById('searchFilterFormPreprint'));
            const params = new URLSearchParams(formData);

            for (let [key, value] of Array.from(params.entries())) {
                if (!value || value.trim() === '') {
                    params.delete(key);
                }
            }

            const loadingIndicator = document.getElementById('loadingIndicatorPreprint');
            const counterText = document.getElementById('counterTextPreprint');
            if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

            const searchUrl = `{{ route('admin.research.index') }}?${params.toString()}`;

            fetch(searchUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const tableContainer = document.getElementById('preprintTableContainer');
                if (tableContainer && data.html) {
                    tableContainer.innerHTML = data.html;
                }

                if (counterText) {
                    const from = data.from || 0;
                    const to = data.to || 0;
                    const total = data.totalCount || 0;
                    counterText.textContent = `${from} - ${to} of ${total}`;
                }

                const newUrl = params.toString() ? searchUrl : '{{ route('admin.research.index') }}?type=preprint';
                window.history.pushState({}, '', newUrl);

                if (loadingIndicator) loadingIndicator.style.display = 'none';
            })
            .catch(error => {
                console.error('Preprint search error:', error);
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                alert('Search failed. Please try again.');
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('#preprintTableContainer #preprintPagination a')) {
                e.preventDefault();
                const url = e.target.closest('a').href;

                const loadingIndicator = document.getElementById('loadingIndicatorPreprint');
                const counterText = document.getElementById('counterTextPreprint');

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
                    const tableContainer = document.getElementById('preprintTableContainer');
                    if (tableContainer && data.html) {
                        tableContainer.innerHTML = data.html;
                    }

                    if (counterText) {
                        const from = data.from || 0;
                        const to = data.to || 0;
                        const total = data.totalCount || 0;
                        counterText.textContent = `${from} - ${to} of ${total}`;
                    }

                    window.history.pushState({}, '', url);

                    if (loadingIndicator) loadingIndicator.style.display = 'none';

                    const tableSection = document.querySelector('#preprint-published');
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
    </script>

    <style>
    /* Embedded Search and Filter Layout */
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

    .card-header.bg-light #searchInputPreprint {
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
