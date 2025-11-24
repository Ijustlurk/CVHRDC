<div class="card shadow">
    <div class="card-header bg-primary bg-gradient text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-flask me-2"></i>Manage Research</h6>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createResearchModal">
                <i class="fas fa-plus"></i> Create New Research
            </button>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="researchTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="published-tab" data-bs-toggle="tab" data-bs-target="#published" type="button" role="tab" aria-selected="true">
                    <i class="fas fa-eye me-2"></i>Published Research
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="deleted-tab" data-bs-toggle="tab" data-bs-target="#deleted" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-trash me-2"></i>Deleted Research
                </button>
            </li>
        </ul>

        <div class="tab-content" id="researchTabContent">
            <div class="tab-pane fade show active" id="published" role="tabpanel">
                <!-- Search and Filter Section -->
                <div class="card-header bg-light border-bottom py-2 mb-3" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterForm" method="GET" action="{{ route('admin.research.index') }}" class="mb-0">
                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInput" name="search"
                                           placeholder="Search by title, authors, abstract, or keywords..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearch" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Category Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="categoryFilter" name="category">
                                    <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
                                    <option value="Terminal" {{ request('category') == 'Terminal' ? 'selected' : '' }}>Terminal</option>
                                    <option value="Technological" {{ request('category') == 'Technological' ? 'selected' : '' }}>Technological</option>
                                    <option value="Social" {{ request('category') == 'Social' ? 'selected' : '' }}>Social</option>
                                </select>
                            </div>

                            <!-- Visibility Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilter" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilter" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortBy" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRange" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFrom" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFrom" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateTo" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateTo" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtn">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtn">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounter" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterText">
                                        {{ $research->firstItem() ?? 0 }} - {{ $research->lastItem() ?? 0 }} of {{ $research->total() }}
                                    </span>
                                    <span id="loadingIndicator" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="researchTableContainer">
                    @include('admin.research.partials.research.research-table', ['research' => $research])
                </div>
            </div>

            <div class="tab-pane fade" id="deleted" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="deletedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Deleted At</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedResearch as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ optional($item->deleted_at)->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('admin.research.restore', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo-alt"></i> Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.research.force-delete', $item->id) }}" onsubmit="return confirm('Permanently delete this research entry? This action cannot be undone!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i> Delete Forever
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No deleted research found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $deletedResearch->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Research Modal -->
<div class="modal fade" id="createResearchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Research</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createResearchForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Research PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="file" name="file" accept="application/pdf" required>
                        <div class="form-text">Upload a PDF file. We'll try to read its title and abstract.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors <span class="text-danger">*</span></label>
                        <div class="border rounded p-2" id="authors-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="authors-chips"></div>
                            <input type="text" id="authors-input" class="form-control" placeholder="Type a name and press Enter">
                        </div>
                        <div class="invalid-feedback d-block" id="authors-error" style="display:none;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="published_at" name="published_at" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="abstract" class="form-label">Abstract <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control" id="abstract" name="abstract" rows="4"></textarea>
                        <div class="form-text">We'll try to auto-fill from the PDF if available.</div>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Research Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="" selected disabled>Select a category</option>
                            <option value="Terminal">Terminal</option>
                            <option value="Technological">Technological</option>
                            <option value="Social">Social</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                        <div class="border rounded p-2" id="keywords-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="keywords-chips"></div>
                            <input type="text" id="keywords-input" class="form-control" placeholder="Type a keyword and press Enter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_public" id="visibility_public" value="1" required>
                                <label class="form-check-label" for="visibility_public">Public</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_public" id="visibility_private" value="0" required>
                                <label class="form-check-label" for="visibility_private">Private</label>
                            </div>
                        </div>
                        <div class="invalid-feedback d-block" id="visibility-error" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="fas fa-save me-1"></i>Publish
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filter functionality
    initializeResearchFilters();

    // Authors chips functionality
    const authorsChips = document.getElementById('authors-chips');
    const authorsInput = document.getElementById('authors-input');
    const currentUser = @json(Auth::user()?->name ?? '');
    
    function createChip(label, container, hiddenName) {
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
    
    function addChipFromInput(input, container) {
        const value = input.value.trim();
        if (!value) return;
        
        // Check if author already exists
        const existingChips = Array.from(container.querySelectorAll('input[name="authors[]"]'));
        const existingValues = existingChips.map(input => input.value.toLowerCase());
        
        if (!existingValues.includes(value.toLowerCase())) {
            createChip(value, container, 'authors[]');
        }
        input.value = '';
    }
    
    function initializeDefaultAuthor() {
        // Clear existing chips first
        if (authorsChips) {
            authorsChips.innerHTML = '';
        }
        
        // Add current user if available
        if (currentUser && authorsChips) {
            createChip(currentUser, authorsChips, 'authors[]');
        }
    }
    
    authorsInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addChipFromInput(authorsInput, authorsChips);
        }
    });

    // Keywords chips functionality
    const keywordsChips = document.getElementById('keywords-chips');
    const keywordsInput = document.getElementById('keywords-input');
    
    function addKeyword(input, container) {
        const value = input.value.trim();
        if (!value) return;
        
        // Check if keyword already exists
        const existingChips = Array.from(container.querySelectorAll('input[name="keywords[]"]'));
        const existingValues = existingChips.map(input => input.value.toLowerCase());
        
        if (!existingValues.includes(value.toLowerCase())) {
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
        }
        input.value = '';
    }
    
    keywordsInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addKeyword(keywordsInput, keywordsChips);
        }
    });

    // PDF metadata extraction
    if (window['pdfjsLib']) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }
    
    const fileInput = document.getElementById('file');
    const titleInput = document.getElementById('title');
    const abstractTextarea = document.getElementById('abstract');
    
    fileInput?.addEventListener('change', async (e) => {
        const file = e.target.files?.[0];
        if (!file) return;
        
        const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
        if (!titleInput.value) {
            titleInput.value = fileNameBase;
        }
        
        try {
            const url = URL.createObjectURL(file);
            const pdf = await pdfjsLib.getDocument(url).promise;
            const meta = await pdf.getMetadata().catch(() => null);
            const docInfo = meta?.info || {};
            
            if (docInfo.Title && (!titleInput.value || titleInput.value.toLowerCase() === fileNameBase.toLowerCase())) {
                titleInput.value = docInfo.Title;
            }
            
            let text = '';
            const pagesToRead = Math.min(pdf.numPages, 3);
            for (let i = 1; i <= pagesToRead; i++) {
                const page = await pdf.getPage(i);
                const content = await page.getTextContent();
                text += '\n' + content.items.map(it => it.str).join(' ');
            }
            
            const idx = text.search(/abstract\s*[:.-]?\s*/i);
            if (idx !== -1) {
                const snippet = text.substring(idx).replace(/\s+/g, ' ').trim();
                const endIdx = snippet.search(/\b(introduction|background|keywords|methods|materials|methodology)\b/i);
                const abstractText = (endIdx !== -1 ? snippet.substring(0, endIdx) : snippet)
                    .replace(/^abstract\s*[:.-]?\s*/i, '')
                    .trim();
                
                if (abstractText && (!abstractTextarea.value || abstractTextarea.value.length < 10)) {
                    abstractTextarea.value = abstractText.slice(0, 4000);
                }
            }
            URL.revokeObjectURL(url);
        } catch (err) {
            console.warn('PDF parse error:', err);
        }
    });

    // Modal event handlers
    const createResearchModal = document.getElementById('createResearchModal');
    const createForm = document.getElementById('createResearchForm');
    let isSubmitting = false;
    let formSubmitted = false;
    
    // Reset form when modal is shown
    createResearchModal?.addEventListener('show.bs.modal', function() {
        if (createForm) {
            createForm.reset();
        }
        
        createForm?.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        createForm?.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        
        if (keywordsChips) {
            keywordsChips.innerHTML = '';
        }
        
        initializeDefaultAuthor();
        
        const submitBtn = createForm?.querySelector('button[type="submit"]');
        const spinner = submitBtn?.querySelector('.spinner-border');
        if (submitBtn) {
            submitBtn.disabled = false;
        }
        if (spinner) {
            spinner.classList.add('d-none');
        }
        
        isSubmitting = false;
        formSubmitted = false;
    });

    // Form submission handler
    createForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        if (isSubmitting || formSubmitted) {
            console.log('Form submission blocked - already submitting or submitted');
            return false;
        }
        
        isSubmitting = true;
        formSubmitted = true;
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn?.querySelector('.spinner-border');
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
        }
        if (spinner) {
            spinner.classList.remove('d-none');
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData(form);
        
        const submissionId = Date.now() + Math.random();
        formData.append('submission_id', submissionId);
        
        console.log('Submitting form with ID:', submissionId);
        
        fetch('{{ route('admin.research.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async (response) => {
            const contentType = response.headers.get('content-type');
            
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                return { status: response.status, data };
            } else {
                const text = await response.text();
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(({ status, data }) => {
            if ((status === 200 || status === 201) && data.success) {
                console.log('Form submitted successfully');
                
                const modal = bootstrap.Modal.getInstance(createResearchModal);
                if (modal) {
                    modal.hide();
                }
                
                setTimeout(() => {
                    alert('Success: Research has been created successfully!');
                    window.location.reload();
                }, 300);
            } else if (status === 422) {
                console.log('Validation errors:', data);
                let errorMessage = 'Validation Error: Please check the following fields:\n';
                
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `- ${field}: ${data.errors[field].join(', ')}\n`;
                    });
                } else {
                    errorMessage += '- Please ensure all required fields are filled correctly\n';
                    errorMessage += '- Make sure at least one author is added\n';
                    errorMessage += '- Ensure the file is a valid PDF\n';
                    errorMessage += '- Check that all required fields are filled';
                }
                
                alert(errorMessage);
                formSubmitted = false;
            } else if (status === 409 && data.duplicate) {
                alert('Duplicate Submission: ' + data.message);
                formSubmitted = false;
            } else {
                alert('Error: ' + (data.message || 'Failed to create research'));
                formSubmitted = false;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Error: ' + err.message);
            formSubmitted = false;
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Publish';
            }
            if (spinner) {
                spinner.classList.add('d-none');
            }
            isSubmitting = false;
        });
    });
    
    initializeDefaultAuthor();
});

// Research Search and Filter Functionality
let researchSearchTimeout;

function initializeResearchFilters() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const visibilityFilter = document.getElementById('visibilityFilter');
    const dateFilter = document.getElementById('dateFilter');
    const sortBy = document.getElementById('sortBy');
    const clearSearchBtn = document.getElementById('clearSearch');
    const customDateRange = document.getElementById('customDateRange');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const searchFilterForm = document.getElementById('searchFilterForm');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performResearchSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(researchSearchTimeout);
            researchSearchTimeout = setTimeout(() => {
                performResearchSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performResearchSearch();
        });
    }

    // Apply Filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performResearchSearch();
        });
    }

    // Reset All button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (categoryFilter) categoryFilter.value = '';
            if (visibilityFilter) visibilityFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            if (sortBy) sortBy.value = 'newest';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';

            if (customDateRange) {
                customDateRange.style.display = 'none';
            }

            performResearchSearch();
        });
    }

    // Filter and sort change handlers
    [categoryFilter, visibilityFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performResearchSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performResearchSearch();
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

// Perform AJAX search for Research
function performResearchSearch() {
    const formData = new FormData(document.getElementById('searchFilterForm'));
    const params = new URLSearchParams(formData);

    // Remove empty parameters for cleaner URLs
    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    // Show loading indicator
    const loadingIndicator = document.getElementById('loadingIndicator');
    const counterText = document.getElementById('counterText');
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
        // Update research table
        const tableContainer = document.getElementById('researchTableContainer');
        if (tableContainer && data.html) {
            tableContainer.innerHTML = data.html;
        }

        // Update counter
        if (counterText) {
            const from = data.from || 0;
            const to = data.to || 0;
            const total = data.totalCount || 0;
            counterText.textContent = `${from} - ${to} of ${total}`;
        }

        // Update browser URL
        const newUrl = params.toString() ? searchUrl : '{{ route('admin.research.index') }}';
        window.history.pushState({}, '', newUrl);

        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('Research search error:', error);
        if (loadingIndicator) loadingIndicator.style.display = 'none';

        alert('Search failed. Please try again.');
    });
}

// Handle pagination clicks with AJAX
document.addEventListener('click', function(e) {
    if (e.target.closest('#researchTableContainer #researchPagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;

        const loadingIndicator = document.getElementById('loadingIndicator');
        const counterText = document.getElementById('counterText');

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
            // Update research table
            const tableContainer = document.getElementById('researchTableContainer');
            if (tableContainer && data.html) {
                tableContainer.innerHTML = data.html;
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
            const tableSection = document.querySelector('#published');
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

.card-header.bg-light #searchInput {
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


