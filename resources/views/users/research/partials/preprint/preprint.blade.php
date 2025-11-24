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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="preprintPublishedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Authors</th>
                                <th>Repository</th>
                                <th>Published At</th>
                                <th>Peer-reviewed</th>
                                <th>Visibility</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($preprints ?? [] as $preprint)
                                @if ($preprint)
                                    <tr>
                                        <td>{{ $preprint->title ?? 'N/A' }}</td>
                                        <td>{{ implode(', ', $preprint->authors ?? []) }}</td>
                                        <td>{{ $preprint->repository_name ?? 'N/A' }}</td>
                                        <td>{{ optional($preprint->published_at)->format('M d, Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $preprint->is_peer_reviewed ?? false ? 'success' : 'warning' }}">
                                                {{ $preprint->is_peer_reviewed ?? false ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $preprint->is_public ?? false ? 'success' : 'secondary' }}">{{ $preprint->is_public ?? false ? 'Public' : 'Private' }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.preprints.show', $preprint->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.preprints.edit', $preprint->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('users.preprints.visibility', $preprint->id) }}"
                                                    class="ms-1">
                                                    @csrf
                                                    <input type="hidden" name="is_public"
                                                        value="{{ $preprint->is_public ?? false ? 0 : 1 }}">

                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-{{ $preprint->is_public ?? false ? 'secondary' : 'success' }}" title="{{ $preprint->is_public ?? false ? 'Make Private' : 'Make Public' }}">
                                                        <i
                                                            class="fa fa-eye{{ $preprint->is_public ?? false ? '-slash' : '' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ route('users.preprints.destroy', $preprint->id) }}"
                                                    onsubmit="return confirm('Delete this preprint?');" class="ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-file-lines fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No preprints found. Click "Create New Preprint" to
                                            get started!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ isset($preprints) ? $preprints->links() : '' }}</div>
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
                                                    action="{{ route('users.preprints.restore', $preprint->id) }}"
                                                    class="me-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-undo-alt"></i> Restore
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ route('users.preprints.force-delete', $preprint->id) }}"
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
                action="{{ route('users.preprints.store') }}">
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

            if (!preprintAuthorsChips || !preprintAuthorsInput) return; // Exit if elements not found

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

            // Remove existing listeners to prevent duplicates
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

                // Remove existing listeners to prevent duplicates
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
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#preprintPublishedTable, #preprintDeletedTable').DataTable({
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

            // Initialize chips
            initPreprintChips();

            // Re-initialize chips when modal is shown
            const preprintModal = document.getElementById('createPreprintModal');
            if (preprintModal) {
                preprintModal.addEventListener('shown.bs.modal', function() {
                    // Reset form when modal opens
                    const form = document.getElementById('createPreprintForm');
                    if (form) {
                        form.reset();
                        // Clear existing chips
                        const authorsChips = document.getElementById('preprint-authors-chips');
                        const keywordsChips = document.getElementById('preprint-keywords-chips');
                        if (authorsChips) authorsChips.innerHTML = '';
                        if (keywordsChips) keywordsChips.innerHTML = '';
                    }

                    setTimeout(() => {
                        initPreprintChips();
                        // Also ensure current user is added if not already present
                        const authorsChips = document.getElementById('preprint-authors-chips');
                        const currentUser = @json(Auth::user()?->name ?? '');
                        if (currentUser && authorsChips && !authorsChips.querySelector(
                                'input[value="' + currentUser + '"]')) {
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
                // Check if we have authors before submitting
                const authorsChips = this.querySelectorAll(
                    '#preprint-authors-chips input[name="authors[]"]');
                if (authorsChips.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one author.');
                    return false;
                }

                // Debug: Check form action and CSRF token
                console.log('Form action:', this.action);
                console.log('CSRF token:', this.querySelector('input[name="_token"]')?.value);
                console.log('Form submitting with', authorsChips.length, 'authors');

                // Let the form submit normally (no e.preventDefault())
                // This will handle the form submission server-side and redirect properly
            });
        });

        // Make initPreprintChips available globally so it can be called from other scripts
        window.initPreprintChips = initPreprintChips;
    </script>
@endpush
