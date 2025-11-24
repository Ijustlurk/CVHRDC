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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="publishedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Authors</th>
                                <th>Category</th>
                                <th>Published At</th>
                                <th>Visibility</th>
                                <th width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($research as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ implode(', ', $item->authors ?? []) }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ optional($item->published_at)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_public ? 'success' : 'secondary' }}">{{ $item->is_public ? 'Public' : 'Private' }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.research.show', $item->id) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.research.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.research.visibility', $item->id) }}" class="ms-1">
                                            @csrf
                                            <input type="hidden" name="is_public" value="{{ $item->is_public ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $item->is_public ? 'secondary' : 'success' }}" title="{{ $item->is_public ? 'Make Private' : 'Make Public' }}">
                                                <i class="fa fa-eye{{ $item->is_public ? '-slash' : '' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.research.destroy', $item->id) }}" onsubmit="return confirm('Delete this research entry?');" class="ms-1">
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
                                    <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No research found. Click "Create New Research" to get started!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $research->links() }}</div>
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
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#publishedTable, #deletedTable').DataTable({
            order: [[3, 'desc']], pageLength: 10,
            language: { search: "<i class='fas fa-search'></i> Search:", lengthMenu: "_MENU_ records per page" },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            retrieve: true
        });
    }

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
    let isSubmitting = false; // Flag to prevent double submission
    let formSubmitted = false; // Additional flag to track if form was already submitted
    
    // Remove any existing event listeners to prevent duplicates
    if (createForm) {
        const newForm = createForm.cloneNode(true);
        createForm.parentNode.replaceChild(newForm, createForm);
        // Re-reference the form after cloning
        const createForm = document.getElementById('createResearchForm');
    }
    
    // Reset form when modal is shown
    createResearchModal?.addEventListener('show.bs.modal', function() {
        // Reset form
        if (createForm) {
            createForm.reset();
        }
        
        // Clear all validation states
        createForm?.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        createForm?.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        
        // Clear and reinitialize chips
        if (keywordsChips) {
            keywordsChips.innerHTML = '';
        }
        
        // Initialize default author (only once per modal open)
        initializeDefaultAuthor();
        
        // Reset submit button
        const submitBtn = createForm?.querySelector('button[type="submit"]');
        const spinner = submitBtn?.querySelector('.spinner-border');
        if (submitBtn) {
            submitBtn.disabled = false;
        }
        if (spinner) {
            spinner.classList.add('d-none');
        }
        
        // Reset submission flags
        isSubmitting = false;
        formSubmitted = false;
    });

    // Form submission handler with improved duplicate prevention
    createForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // Prevent other event listeners
        
        // Prevent double submission with multiple checks
        if (isSubmitting || formSubmitted) {
            console.log('Form submission blocked - already submitting or submitted');
            return false;
        }
        
        // Set both flags immediately
        isSubmitting = true;
        formSubmitted = true;
        
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
                // If not JSON, treat as error
                const text = await response.text();
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(({ status, data }) => {
            if ((status === 200 || status === 201) && data.success) {
                console.log('Form submitted successfully');
                
                // Close modal first
                const modal = bootstrap.Modal.getInstance(createResearchModal);
                if (modal) {
                    modal.hide();
                }
                
                // Show success message after a short delay
                setTimeout(() => {
                    alert('Success: Research has been created successfully!');
                    window.location.reload();
                }, 300);
            } else if (status === 422) {
                // Handle validation errors more specifically
                console.log('Validation errors:', data);
                let errorMessage = 'Validation Error: Please check the following fields:\n';
                
                if (data.errors) {
                    // Show specific field errors
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
                // Reset submission flag on validation error
                formSubmitted = false;
            } else if (status === 409 && data.duplicate) {
                // Handle duplicate submission
                alert('Duplicate Submission: ' + data.message);
                formSubmitted = false;
            } else {
                alert('Error: ' + (data.message || 'Failed to create research'));
                // Reset submission flag on error
                formSubmitted = false;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Error: ' + err.message);
            // Reset submission flag on error
            formSubmitted = false;
        })
        .finally(() => {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Publish';
            }
            if (spinner) {
                spinner.classList.add('d-none');
            }
            // Keep isSubmitting false but don't reset formSubmitted on success
            isSubmitting = false;
        });
    });
    
    // Initialize default author on page load (only once)
    initializeDefaultAuthor();
});
</script>
@endpush


