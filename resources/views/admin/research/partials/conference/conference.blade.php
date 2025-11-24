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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="conferencePublishedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Authors</th>
                                <th>Conference</th>
                                <th>Published At</th>
                                <th>Visibility</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($conferencePapers as $paper)
                            <tr>
                                <td>{{ $paper->title }}</td>
                                <td>{{ implode(', ', $paper->authors ?? []) }}</td>
                                <td>{{ $paper->conference_name }}</td>
                                <td>{{ optional($paper->published_at)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $paper->is_public ? 'success' : 'secondary' }}">{{ $paper->is_public ? 'Public' : 'Private' }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.conference-papers.show', $paper->id) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.conference-papers.edit', $paper->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.conference-papers.visibility', $paper->id) }}" class="ms-1">
                                            @csrf
                                            <input type="hidden" name="is_public" value="{{ $paper->is_public ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $paper->is_public ? 'secondary' : 'success' }}" title="{{ $paper->is_public ? 'Make Private' : 'Make Public' }}">
                                                <i class="fa fa-eye{{ $paper->is_public ? '-slash' : '' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.conference-papers.destroy', $paper->id) }}" onsubmit="return confirm('Delete this conference paper?');" class="ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No conference papers found. Click "Create New Conference Paper" to get started!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $conferencePapers->links() }}</div>
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
</script>
@endpush