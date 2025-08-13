@extends('layouts.Admin.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4 gap-3">
            <h4 class="mb-0">CVHRDC Research Management</h4>
            <div class="alert alert-info mb-0 py-2 px-3">
                Manage research entries, files, and visibility here.
            </div>
        </div>

        <div id="alertContainer" class="mb-4"></div>
        <!-- Research Management Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link{{ request('type', 'research') !== 'conference' ? ' active' : '' }}"
                    href="{{ route('admin.research.index', ['type' => 'research']) }}">
                    <i class="fas fa-book me-2"></i>Research
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ request('type') === 'conference' ? ' active' : '' }}"
                    href="{{ route('admin.research.index', ['type' => 'conference']) }}">
                    <i class="fas fa-file-alt me-2"></i>Conference Paper
                </a>
            </li>
        </ul>

        @if (request('type', 'research') !== 'conference')
            @include('admin.research.partials.research.research')
        @else
            @include('admin.research.partials.conference.conference')
        @endif
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
                            <label for="file" class="form-label">Research PDF <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept="application/pdf" required>
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
                                <input type="text" id="authors-input" class="form-control"
                                    placeholder="Type a name and press Enter">
                            </div>
                            <div class="invalid-feedback d-block" id="authors-error" style="display:none;"></div>
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="form-label">Publication Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="published_at" name="published_at" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="abstract" class="form-label">Abstract <small
                                    class="text-muted">(optional)</small></label>
                            <textarea class="form-control" id="abstract" name="abstract" rows="4"></textarea>
                            <div class="form-text">We'll try to auto-fill from the PDF if available.</div>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Research Category <span
                                    class="text-danger">*</span></label>
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
                                <input type="text" id="keywords-input" class="form-control"
                                    placeholder="Type a keyword and press Enter">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_public"
                                        id="visibility_public" value="1" required>
                                    <label class="form-check-label" for="visibility_public">Public</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_public"
                                        id="visibility_private" value="0" required>
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

        <!-- Create Conference Paper Modal -->
        <div class="modal fade" id="createConferenceModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Conference Paper</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createConferenceForm" enctype="multipart/form-data"
                        action="{{ \Illuminate\Support\Facades\Route::has('admin.conference-papers.store') ? route('admin.conference-papers.store') : '#' }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="conf-file" class="form-label">File (PDF) <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="conf-file" name="file"
                                    accept="application/pdf" required>
                            </div>
                            <div class="mb-3">
                                <label for="conf-title" class="form-label">Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="conf-title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Authors <span class="text-danger">*</span></label>
                                <div class="border rounded p-2" id="conf-authors-container">
                                    <div class="d-flex flex-wrap gap-2 mb-2" id="conf-authors-chips"></div>
                                    <input type="text" id="conf-authors-input" class="form-control"
                                        placeholder="Type a name and press Enter">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="conf-published_at" class="form-label">Publication Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="conf-published_at" name="published_at"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="conf-conference_name" class="form-label">Conference Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="conf-conference_name"
                                    name="conference_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="conf-conference_location" class="form-label">Conference Location <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="conf-conference_location"
                                    name="conference_location" placeholder="City, Country" required>
                            </div>
                            <div class="mb-3">
                                <label for="conf-publisher" class="form-label">Publisher <small
                                        class="text-muted">(optional)</small></label>
                                <input type="text" class="form-control" id="conf-publisher" name="publisher">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_public"
                                            id="conf-visibility_public" value="1" required>
                                        <label class="form-check-label" for="conf-visibility_public">Public</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_public"
                                            id="conf-visibility_private" value="0" required>
                                        <label class="form-check-label" for="conf-visibility_private">Private</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keywords/Tags <small
                                        class="text-muted">(optional)</small></label>
                                <div class="border rounded p-2" id="conf-keywords-container">
                                    <div class="d-flex flex-wrap gap-2 mb-2" id="conf-keywords-chips"></div>
                                    <input type="text" id="conf-keywords-input" class="form-control"
                                        placeholder="Type a keyword and press Enter">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="conf-abstract" class="form-label">Abstract <small
                                        class="text-muted">(optional)</small></label>
                                <textarea class="form-control" id="conf-abstract" name="abstract" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="conf-language" class="form-label">Language <small class="text-muted">(ISO
                                        639-1, optional)</small></label>
                                <input type="text" class="form-control" id="conf-language" name="language"
                                    placeholder="e.g., en, fr, es">
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
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Bootstrap JS (required for modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- pdf.js for reading PDF metadata and text -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#publishedTable, #deletedTable, #conferencePublishedTable, #conferenceDeletedTable').DataTable({
                    order: [
                        [3, 'desc']
                    ],
                    pageLength: 10,
                    language: {
                        search: "<i class='fas fa-search'></i> Search:",
                        lengthMenu: "_MENU_ records per page"
                    },
                    dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
                    retrieve: true
                });
            }

            // Ensure conference modal opens even if data attributes are ignored
            document.querySelectorAll('[data-bs-target="#createConferenceModal"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modalEl = document.getElementById('createConferenceModal');
                    if (window.bootstrap && modalEl) {
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();
                    }
                });
            });

            // Authors chips
            const authorsChips = document.getElementById('authors-chips');
            const authorsInput = document.getElementById('authors-input');

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
                hidden.name = 'authors[]';
                hidden.value = label;
                chip.appendChild(remove);
                chip.appendChild(hidden);
                container.appendChild(chip);
            }

            function addChipFromInput(input, container) {
                const value = input.value.trim();
                if (!value) return;
                createChip(value, container, 'authors[]');
                input.value = '';
            }
            authorsInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addChipFromInput(authorsInput, authorsChips);
                }
            });
            // Pre-add current user
            const currentUser = @json(Auth::user()?->name ?? '');
            if (currentUser) createChip(currentUser, authorsChips, 'authors[]');

            // Keywords chips
            const keywordsChips = document.getElementById('keywords-chips');
            const keywordsInput = document.getElementById('keywords-input');

            function addKeyword(input, container) {
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
            keywordsInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addKeyword(keywordsInput, keywordsChips);
                }
            });

            // PDF metadata
            if (window['pdfjsLib']) {
                pdfjsLib.GlobalWorkerOptions.workerSrc =
                    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            }
            const fileInput = document.getElementById('file');
            const titleInput = document.getElementById('title');
            const abstractTextarea = document.getElementById('abstract');
            fileInput?.addEventListener('change', async (e) => {
                const file = e.target.files?.[0];
                if (!file) return;
                const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
                if (!titleInput.value) titleInput.value = fileNameBase;
                try {
                    const url = URL.createObjectURL(file);
                    const pdf = await pdfjsLib.getDocument(url).promise;
                    const meta = await pdf.getMetadata().catch(() => null);
                    const docInfo = meta?.info || {};
                    if (docInfo.Title && (!titleInput.value || titleInput.value.toLowerCase() ===
                            fileNameBase.toLowerCase())) {
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
                        const endIdx = snippet.search(
                            /\b(introduction|background|keywords|methods|materials|methodology)\b/i);
                        const abstractText = (endIdx !== -1 ? snippet.substring(0, endIdx) : snippet)
                            .replace(/^abstract\s*[:.-]?\s*/i, '').trim();
                        if (abstractText && (!abstractTextarea.value || abstractTextarea.value.length <
                                10)) {
                            abstractTextarea.value = abstractText.slice(0, 4000);
                        }
                    }
                    URL.revokeObjectURL(url);
                } catch (err) {
                    console.warn('PDF parse error:', err);
                }
            });

            // Submit create form with FormData
            const createForm = document.getElementById('createResearchForm');
            createForm?.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = form.querySelector('button[type="submit"]');
                const spinner = submitBtn.querySelector('.spinner-border');
                submitBtn.disabled = true;
                spinner?.classList.remove('d-none');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const formData = new FormData(form);
                fetch('{{ route('admin.research.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(async r => {
                        const ct = r.headers.get('content-type') || '';
                        if (ct.includes('application/json')) {
                            const data = await r.json();
                            return { status: r.status, data };
                        }
                        return { status: r.status, data: { success: r.ok } };
                    })
                    .then(({
                        status,
                        data
                    }) => {
                        if (status === 200 || status === 201 || data.success) {
                            window.alert('Success: Research has been created successfully!');
                            window.location.reload();
                        } else if (status === 422) {
                            window.alert('Error: Please fix the highlighted fields.');
                        } else {
                            window.alert('Error: ' + (data.message || 'Failed to create research'));
                        }
                    }).catch(err => {
                        window.alert('Error: ' + err.message);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        spinner?.classList.add('d-none');
                    });
            });

            // Conference modal create
            const createConfForm = document.getElementById('createConferenceForm');
            createConfForm?.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = form.querySelector('button[type="submit"]');
                const spinner = submitBtn.querySelector('.spinner-border');
                submitBtn.disabled = true;
                spinner?.classList.remove('d-none');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const formData = new FormData(form);
                const actionUrl = form.getAttribute('action') || '#';
                fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(async r => {
                        const ct = r.headers.get('content-type') || '';
                        if (ct.includes('application/json')) {
                            const data = await r.json();
                            return { status: r.status, data };
                        }
                        return { status: r.status, data: { success: r.ok } };
                    })
                    .then(({
                        status,
                        data
                    }) => {
                        if (status === 200 || status === 201 || data.success) {
                            window.alert('Success: Conference paper created!');
                            window.location.reload();
                        } else if (status === 422) {
                            window.alert('Error: Please fix the highlighted fields.');
                        } else {
                            window.alert('Error: ' + (data.message ||
                                'Failed to create conference paper'));
                        }
                    }).catch(err => {
                        window.alert('Error: ' + err.message);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        spinner?.classList.add('d-none');
                    });
            });

            // Conference chips & PDF helpers
            const confAuthorsChips = document.getElementById('conf-authors-chips');
            const confAuthorsInput = document.getElementById('conf-authors-input');

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

            function confAddChip(input, container) {
                const value = input.value.trim();
                if (!value) return;
                confCreateChip(value, container, 'authors[]');
                input.value = '';
            }
            confAuthorsInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    confAddChip(confAuthorsInput, confAuthorsChips);
                }
            });
            const confCurrentUser = @json(Auth::user()?->name ?? '');
            if (confCurrentUser) confCreateChip(confCurrentUser, confAuthorsChips, 'authors[]');

            const confKeywordsChips = document.getElementById('conf-keywords-chips');
            const confKeywordsInput = document.getElementById('conf-keywords-input');

            function confAddKeyword(input, container) {
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
            confKeywordsInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    confAddKeyword(confKeywordsInput, confKeywordsChips);
                }
            });

            if (window['pdfjsLib']) {
                pdfjsLib.GlobalWorkerOptions.workerSrc =
                    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            }
            const confFileInput = document.getElementById('conf-file');
            const confTitleInput = document.getElementById('conf-title');
            confFileInput?.addEventListener('change', async (e) => {
                const file = e.target.files?.[0];
                if (!file) return;
                const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
                if (!confTitleInput.value) confTitleInput.value = fileNameBase;
            });
        });
    </script>
@endpush
