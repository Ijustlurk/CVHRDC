<div class="card shadow">
    <div class="card-header bg-primary bg-gradient text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>Manage Conference Papers</h6>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createConferenceModal">
                <i class="fas fa-plus"></i> Create New Conference Paper
            </button>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="conferenceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="conf-published-tab" data-bs-toggle="tab" data-bs-target="#conf-published" type="button" role="tab" aria-selected="true">
                    <i class="fas fa-eye me-2"></i>Published
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="conf-deleted-tab" data-bs-toggle="tab" data-bs-target="#conf-deleted" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-trash me-2"></i>Deleted
                </button>
            </li>
        </ul>

        <div class="tab-content" id="conferenceTabContent">
            <div class="tab-pane fade show active" id="conf-published" role="tabpanel">
                <!-- Search and Filter Section -->
                <div class="card-header bg-light border-bottom py-2 mb-3" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterFormConference" method="GET" action="{{ route('admin.conference-papers.index') }}" class="mb-0">
                        <input type="hidden" name="type" value="conference">
                        
                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputConference" name="search"
                                           placeholder="Search by title, authors, conference, location, publisher, abstract, or keywords..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchConference" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Visibility Filter -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilterConference" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterConference" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByConference" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRangeConference" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromConference" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromConference" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToConference" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToConference" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnConference">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnConference">
                                        <i class="fas fa-redo me-1"></i>Reset
                                            </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterConference" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextConference">
                                        {{ $conferencePapers->firstItem() ?? 0 }} - {{ $conferencePapers->lastItem() ?? 0 }} of {{ $conferencePapers->total() }}
                                    </span>
                                    <span id="loadingIndicatorConference" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                                        </form>
                                    </div>

                <div id="conferenceTableContainer">
                    @include('admin.research.partials.conference.conference-table', ['conferencePapers' => $conferencePapers])
                </div>
            </div>

            <div class="tab-pane fade" id="conf-deleted" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="conferenceDeletedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Deleted At</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedConferencePapers as $paper)
                            <tr>
                                <td>{{ $paper->title }}</td>
                                <td>{{ optional($paper->deleted_at)->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('admin.conference-papers.restore', $paper->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo-alt"></i> Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.conference-papers.force-delete', $paper->id) }}" onsubmit="return confirm('Permanently delete this conference paper? This action cannot be undone!');">
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
                                    <p class="text-muted mb-0">No deleted conference papers found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $deletedConferencePapers->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Conference Paper Modal -->
<div class="modal fade" id="createConferenceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Conference Paper</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createConferenceForm" enctype="multipart/form-data" action="{{ route('admin.conference-papers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="conf-file" class="form-label">File (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="conf-file" name="file" accept="application/pdf" required>
                        <div class="form-text">Upload a PDF file. We'll try to read its title and abstract.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conf-title" name="title" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors <span class="text-danger">*</span></label>
                        <div class="border rounded p-2" id="conf-authors-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="conf-authors-chips"></div>
                            <input type="text" id="conf-authors-input" class="form-control" placeholder="Type a name and press Enter">
                        </div>
                        <div class="invalid-feedback d-block" id="conf-authors-error" style="display:none;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="conf-published_at" name="published_at" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-conference_name" class="form-label">Conference Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conf-conference_name" name="conference_name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-conference_location" class="form-label">Conference Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conf-conference_location" name="conference_location" placeholder="City, Country" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-publisher" class="form-label">Publisher <small class="text-muted">(optional)</small></label>
                        <input type="text" class="form-control" id="conf-publisher" name="publisher">
                    </div>
                    <div class="mb-3">
                        <label for="conf-abstract" class="form-label">Abstract <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control" id="conf-abstract" name="abstract" rows="4"></textarea>
                        <div class="form-text">We'll try to auto-fill from the PDF if available.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                        <div class="border rounded p-2" id="conf-keywords-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="conf-keywords-chips"></div>
                            <input type="text" id="conf-keywords-input" class="form-control" placeholder="Type a keyword and press Enter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_public" id="conf-visibility_public" value="1" required>
                                <label class="form-check-label" for="conf-visibility_public">Public</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_public" id="conf-visibility_private" value="0" required>
                                <label class="form-check-label" for="conf-visibility_private">Private</label>
                            </div>
                        </div>
                        <div class="invalid-feedback d-block" id="conf-visibility-error" style="display:none;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-language" class="form-label">Language <small class="text-muted">(ISO 639-1, optional)</small></label>
                        <input type="text" class="form-control" id="conf-language" name="language" placeholder="e.g., en, fr, es">
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
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#conferencePublishedTable, #conferenceDeletedTable').DataTable({
            order: [[3, 'desc']], pageLength: 10,
            language: { search: "<i class='fas fa-search'></i> Search:", lengthMenu: "_MENU_ records per page" },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            retrieve: true
        });
    }

    // Conference authors chips functionality
    const confAuthorsChips = document.getElementById('conf-authors-chips');
    const confAuthorsInput = document.getElementById('conf-authors-input');
    const confCurrentUser = @json(Auth::user()?->name ?? '');
    
    function confCreateChip(label, container, hiddenName) {
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
    
    function confAddChipFromInput(input, container) {
        const value = input.value.trim();
        if (!value) return;
        
        // Check if author already exists
        const existingChips = Array.from(container.querySelectorAll('input[name="authors[]"]'));
        const existingValues = existingChips.map(input => input.value.toLowerCase());
        
        if (!existingValues.includes(value.toLowerCase())) {
            confCreateChip(value, container, 'authors[]');
        }
        input.value = '';
    }
    
    function confInitializeDefaultAuthor() {
        // Clear existing chips first
        if (confAuthorsChips) {
            confAuthorsChips.innerHTML = '';
        }
        
        // Add current user if available
        if (confCurrentUser && confAuthorsChips) {
            confCreateChip(confCurrentUser, confAuthorsChips, 'authors[]');
        }
    }
    
    confAuthorsInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            confAddChipFromInput(confAuthorsInput, confAuthorsChips);
        }
    });

    // Conference keywords chips functionality
    const confKeywordsChips = document.getElementById('conf-keywords-chips');
    const confKeywordsInput = document.getElementById('conf-keywords-input');
    
    function confAddKeyword(input, container) {
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
    
    confKeywordsInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            confAddKeyword(confKeywordsInput, confKeywordsChips);
        }
    });

    // PDF metadata extraction
    if (window['pdfjsLib']) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }
    
    const confFileInput = document.getElementById('conf-file');
    const confTitleInput = document.getElementById('conf-title');
    const confAbstractTextarea = document.getElementById('conf-abstract');
    
    confFileInput?.addEventListener('change', async (e) => {
        const file = e.target.files?.[0];
        if (!file) return;
        
        const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
        if (!confTitleInput.value) {
            confTitleInput.value = fileNameBase;
        }
        
        try {
            const url = URL.createObjectURL(file);
            const pdf = await pdfjsLib.getDocument(url).promise;
            const meta = await pdf.getMetadata().catch(() => null);
            const docInfo = meta?.info || {};
            
            if (docInfo.Title && (!confTitleInput.value || confTitleInput.value.toLowerCase() === fileNameBase.toLowerCase())) {
                confTitleInput.value = docInfo.Title;
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
                
                if (abstractText && (!confAbstractTextarea.value || confAbstractTextarea.value.length < 10)) {
                    confAbstractTextarea.value = abstractText.slice(0, 4000);
                }
            }
            URL.revokeObjectURL(url);
        } catch (err) {
            console.warn('PDF parse error:', err);
        }
    });

    // Modal event handlers
    const createConferenceModal = document.getElementById('createConferenceModal');
    const createConferenceForm = document.getElementById('createConferenceForm');
    let confIsSubmitting = false; // Flag to prevent double submission
    let confFormSubmitted = false; // Additional flag to track if form was already submitted
    
    // Remove any existing event listeners to prevent duplicates
    if (createConferenceForm) {
        const newForm = createConferenceForm.cloneNode(true);
        createConferenceForm.parentNode.replaceChild(newForm, createConferenceForm);
        // Re-reference the form after cloning
        const createConferenceForm = document.getElementById('createConferenceForm');
    }
    
    // Reset form when modal is shown
    createConferenceModal?.addEventListener('show.bs.modal', function() {
        // Reset form
        if (createConferenceForm) {
            createConferenceForm.reset();
        }
        
        // Clear all validation states
        createConferenceForm?.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        createConferenceForm?.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        
        // Clear and reinitialize chips
        if (confKeywordsChips) {
            confKeywordsChips.innerHTML = '';
        }
        
        // Initialize default author (only once per modal open)
        confInitializeDefaultAuthor();
        
        // Reset submit button
        const submitBtn = createConferenceForm?.querySelector('button[type="submit"]');
        const spinner = submitBtn?.querySelector('.spinner-border');
        if (submitBtn) {
            submitBtn.disabled = false;
        }
        if (spinner) {
            spinner.classList.add('d-none');
        }
        
        // Reset submission flags
        confIsSubmitting = false;
        confFormSubmitted = false;
    });

    // Form submission handler with improved duplicate prevention
    createConferenceForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // Prevent other event listeners
        
        // Clear previous validation state
        const clearInvalids = () => {
            createConferenceForm?.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            createConferenceForm?.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            const authorsErr = document.getElementById('conf-authors-error');
            if (authorsErr) authorsErr.textContent = '';
            const visibilityErr = document.getElementById('conf-visibility-error');
            if (visibilityErr) visibilityErr.textContent = '';
        };
        clearInvalids();
        
        // Prevent double submission with multiple checks
        if (confIsSubmitting || confFormSubmitted) {
            console.log('Conference form submission blocked - already submitting or submitted');
            return false;
        }
        
        // Set both flags immediately
        confIsSubmitting = true;
        confFormSubmitted = true;
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn?.querySelector('.spinner-border');
        
        // Disable submit button and show spinner
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
        }
        if (spinner) {
            spinner.classList.remove('d-none');
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData(form);
        
        // Add a unique submission ID to prevent duplicates
        const submissionId = Date.now() + Math.random();
        formData.append('submission_id', submissionId);
        
        console.log('Submitting conference form with ID:', submissionId);
        
        fetch('{{ route('admin.conference-papers.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async (response) => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                console.log('Response data:', data);
                return { status: response.status, data };
            } else {
                // If not JSON, treat as error
                const text = await response.text();
                console.log('Non-JSON response text:', text);
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(({ status, data }) => {
            if ((status === 200 || status === 201) && data.success) {
                console.log('Conference form submitted successfully');
                
                // Close modal first
                const modal = bootstrap.Modal.getInstance(createConferenceModal);
                if (modal) {
                    modal.hide();
                }
                
                // Show success message after a short delay
                setTimeout(() => {
                    alert('Success: Conference paper has been created successfully!');
                    window.location.reload();
                }, 300);
            } else if (status === 422) {
                // Handle validation errors more specifically
                console.log('Conference validation errors:', data);
                
                // Map of field names to element selectors
                const fieldMap = {
                    file: '#conf-file',
                    title: '#conf-title',
                    'authors': '#conf-authors-container',
                    published_at: '#conf-published_at',
                    conference_name: '#conf-conference_name',
                    conference_location: '#conf-conference_location',
                    publisher: '#conf-publisher',
                    abstract: '#conf-abstract',
                    language: '#conf-language',
                    is_public: '#conf-visibility_public'
                };
                
                let errorMessage = 'Validation Error: Please check the following fields:\n';
                let hasErrors = false;
                
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const messages = data.errors[field];
                        const selector = fieldMap[field] || `[name="${field}"]`;
                        const inputEl = createConferenceForm.querySelector(selector);
                        
                        if (inputEl) {
                            hasErrors = true;
                            // For container (authors), highlight its input
                            if (selector === '#conf-authors-container') {
                                const authorsInput = document.getElementById('conf-authors-input');
                                if (authorsInput) authorsInput.classList.add('is-invalid');
                                const authorsErr = document.getElementById('conf-authors-error');
                                if (authorsErr) authorsErr.textContent = messages.join(' ');
                            } else if (selector === '#conf-visibility_public') {
                                const visibilityErr = document.getElementById('conf-visibility-error');
                                if (visibilityErr) visibilityErr.textContent = messages.join(' ');
                            } else {
                                inputEl.classList.add('is-invalid');
                                const feedback = inputEl.parentElement?.querySelector('.invalid-feedback');
                                if (feedback) feedback.textContent = messages.join(' ');
                            }
                            
                            // Add to error message
                            errorMessage += `- ${field}: ${messages.join(', ')}\n`;
                        }
                    });
                }
                
                if (!hasErrors) {
                    errorMessage += '- Please ensure all required fields are filled correctly\n';
                    errorMessage += '- Make sure at least one author is added\n';
                    errorMessage += '- Ensure the file is a valid PDF\n';
                    errorMessage += '- Check that all required fields are filled';
                }
                
                alert(errorMessage);
                // Reset submission flag on validation error
                confFormSubmitted = false;
            } else if (status === 409 && data.duplicate) {
                // Handle duplicate submission
                alert('Duplicate Submission: ' + data.message);
                confFormSubmitted = false;
            } else {
                alert('Error: ' + (data.message || 'Failed to create conference paper'));
                // Reset submission flag on error
                confFormSubmitted = false;
            }
        })
        .catch(err => {
            console.error('Conference fetch error:', err);
            alert('Error: ' + err.message);
            // Reset submission flag on error
            confFormSubmitted = false;
        })
        .finally(() => {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Create';
            }
            if (spinner) {
                spinner.classList.add('d-none');
            }
            // Keep confIsSubmitting false but don't reset confFormSubmitted on success
            confIsSubmitting = false;
        });
    });
    
});

// Conference Search and Filter Functionality
let conferenceSearchTimeout;

function initializeConferenceFilters() {
    const searchInput = document.getElementById('searchInputConference');
    const visibilityFilter = document.getElementById('visibilityFilterConference');
    const dateFilter = document.getElementById('dateFilterConference');
    const sortBy = document.getElementById('sortByConference');
    const clearSearchBtn = document.getElementById('clearSearchConference');
    const customDateRange = document.getElementById('customDateRangeConference');
    const dateFrom = document.getElementById('dateFromConference');
    const dateTo = document.getElementById('dateToConference');
    const searchFilterForm = document.getElementById('searchFilterFormConference');
    const applyFiltersBtn = document.getElementById('applyFiltersBtnConference');
    const resetFiltersBtn = document.getElementById('resetFiltersBtnConference');

    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performConferenceSearch();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(conferenceSearchTimeout);
            conferenceSearchTimeout = setTimeout(() => {
                performConferenceSearch();
            }, 500);
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performConferenceSearch();
        });
    }

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performConferenceSearch();
        });
    }

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

            performConferenceSearch();
        });
    }

    [visibilityFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performConferenceSearch);
        }
    });

    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performConferenceSearch();
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

function performConferenceSearch() {
    const formData = new FormData(document.getElementById('searchFilterFormConference'));
    const params = new URLSearchParams(formData);

    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    const loadingIndicator = document.getElementById('loadingIndicatorConference');
    const counterText = document.getElementById('counterTextConference');
    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

    const searchUrl = `{{ route('admin.conference-papers.index') }}?${params.toString()}`;

    fetch(searchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tableContainer = document.getElementById('conferenceTableContainer');
        if (tableContainer && data.html) {
            tableContainer.innerHTML = data.html;
        }

        if (counterText) {
            const from = data.from || 0;
            const to = data.to || 0;
            const total = data.totalCount || 0;
            counterText.textContent = `${from} - ${to} of ${total}`;
        }

        const newUrl = params.toString() ? `{{ route('admin.research.index') }}?${params.toString()}` : '{{ route('admin.research.index') }}?type=conference';
        window.history.pushState({}, '', newUrl);

        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('Conference search error:', error);
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        alert('Search failed. Please try again.');
    });
}

document.addEventListener('click', function(e) {
    if (e.target.closest('#conferenceTableContainer #conferencePagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;

        const loadingIndicator = document.getElementById('loadingIndicatorConference');
        const counterText = document.getElementById('counterTextConference');

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
            const tableContainer = document.getElementById('conferenceTableContainer');
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

            const tableSection = document.querySelector('#conf-published');
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

// Initialize conference filters on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeConferenceFilters();
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

.card-header.bg-light #searchInputConference {
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