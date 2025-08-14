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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bookPublishedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Authors/Editors</th>
                                <th>Publisher</th>
                                <th>Publication Date</th>
                                <th>ISBN</th>
                                <th>Visibility</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($books ?? [] as $book)
                                @if ($book)
                                    <tr>
                                        <td>{{ data_get($book, 'title', 'N/A') }}</td>
                                        <td>
                                            @php
                                                $authors = implode(', ', data_get($book, 'authors', []));
                                                $editors = implode(', ', data_get($book, 'editors', []));
                                            @endphp
                                            {{ $authors ?: '—' }}@if ($editors)
                                                <small class="text-muted"> | Eds: {{ $editors }}</small>
                                            @endif
                                        </td>
                                        <td>{{ data_get($book, 'publisher', 'N/A') }}</td>
                                        <td>{{ optional(data_get($book, 'published_at'))->format('M d, Y') }}</td>
                                        <td>{{ data_get($book, 'isbn', '—') }}</td>
                                        <td>
                                            @php $bookIsPublic = (bool) data_get($book, 'is_public', false); @endphp
                                            <span
                                                class="badge bg-{{ $bookIsPublic ? 'success' : 'secondary' }}">{{ $bookIsPublic ? 'Public' : 'Private' }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.books.edit', data_get($book, 'id')) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                <form method="POST"
                                                    action="{{ route('admin.books.visibility', data_get($book, 'id')) }}"
                                                    class="ms-1">
                                                    @csrf
                                                    <input type="hidden" name="is_public"
                                                        value="{{ $bookIsPublic ? 0 : 1 }}">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-{{ $bookIsPublic ? 'secondary' : 'success' }}">
                                                        <i class="fa fa-eye{{ $bookIsPublic ? '-slash' : '' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ route('admin.books.destroy', data_get($book, 'id')) }}"
                                                    onsubmit="return confirm('Delete this book?');" class="ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No books found. Click "Create New Book" to get
                                                started!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ isset($books) ? $books->links() : '' }}</div>
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
    @endpush
