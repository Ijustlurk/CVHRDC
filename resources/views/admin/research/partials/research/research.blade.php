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
                            @foreach ($research as $item)
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
                                        <a href="{{ route('admin.research.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('admin.research.visibility', $item->id) }}" class="ms-1">
                                            @csrf
                                            <input type="hidden" name="is_public" value="{{ $item->is_public ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $item->is_public ? 'secondary' : 'success' }}">
                                                <i class="fa fa-eye{{ $item->is_public ? '-slash' : '' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.research.destroy', $item->id) }}" onsubmit="return confirm('Delete this research entry?');" class="ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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
                            @foreach ($deletedResearch as $item)
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
                            @endforeach
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

    // Authors chips
    const authorsChips = document.getElementById('authors-chips');
    const authorsInput = document.getElementById('authors-input');
    function createChip(label, container, hiddenName) {
        const chip = document.createElement('span'); chip.className = 'badge bg-secondary d-inline-flex align-items-center'; chip.style.gap='6px'; chip.textContent = label;
        const remove = document.createElement('button'); remove.type='button'; remove.className='btn-close btn-close-white btn-sm ms-2'; remove.addEventListener('click',()=>chip.remove());
        const hidden = document.createElement('input'); hidden.type='hidden'; hidden.name=hiddenName; hidden.value=label;
        chip.appendChild(remove); chip.appendChild(hidden); container.appendChild(chip);
    }
    function addChipFromInput(input, container) { const value = input.value.trim(); if (!value) return; createChip(value, container, 'authors[]'); input.value=''; }
    authorsInput?.addEventListener('keydown', (e)=>{ if (e.key==='Enter' || e.key===','){ e.preventDefault(); addChipFromInput(authorsInput, authorsChips); }});
    // Pre-add current user
    const currentUser = @json(Auth::user()?->name ?? ''); if (currentUser) createChip(currentUser, authorsChips, 'authors[]');

    // Keywords chips
    const keywordsChips = document.getElementById('keywords-chips');
    const keywordsInput = document.getElementById('keywords-input');
    function addKeyword(input, container) { const value=input.value.trim(); if(!value) return; const chip=document.createElement('span'); chip.className='badge bg-info d-inline-flex align-items-center'; chip.style.gap='6px'; chip.textContent=value; const remove=document.createElement('button'); remove.type='button'; remove.className='btn-close btn-close-white btn-sm ms-2'; remove.addEventListener('click',()=>chip.remove()); const hidden=document.createElement('input'); hidden.type='hidden'; hidden.name='keywords[]'; hidden.value=value; chip.appendChild(remove); chip.appendChild(hidden); container.appendChild(chip); input.value=''; }
    keywordsInput?.addEventListener('keydown', (e)=>{ if (e.key==='Enter' || e.key===','){ e.preventDefault(); addKeyword(keywordsInput, keywordsChips);} });

    // PDF metadata
    if (window['pdfjsLib']) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }
    const fileInput = document.getElementById('file');
    const titleInput = document.getElementById('title');
    const abstractTextarea = document.getElementById('abstract');
    fileInput?.addEventListener('change', async (e) => {
        const file = e.target.files?.[0]; if (!file) return;
        const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
        if (!titleInput.value) titleInput.value = fileNameBase;
        try {
            const url = URL.createObjectURL(file);
            const pdf = await pdfjsLib.getDocument(url).promise;
            const meta = await pdf.getMetadata().catch(() => null);
            const docInfo = meta?.info || {};
            if (docInfo.Title && (!titleInput.value || titleInput.value.toLowerCase() === fileNameBase.toLowerCase())) { titleInput.value = docInfo.Title; }
            let text=''; const pagesToRead=Math.min(pdf.numPages,3);
            for (let i=1;i<=pagesToRead;i++){ const page=await pdf.getPage(i); const content=await page.getTextContent(); text += '\n' + content.items.map(it=>it.str).join(' ');}        
            const idx = text.search(/abstract\s*[:.-]?\s*/i);
            if (idx !== -1) {
                const snippet = text.substring(idx).replace(/\s+/g, ' ').trim();
                const endIdx = snippet.search(/\b(introduction|background|keywords|methods|materials|methodology)\b/i);
                const abstractText = (endIdx !== -1 ? snippet.substring(0, endIdx) : snippet).replace(/^abstract\s*[:.-]?\s*/i, '').trim();
                if (abstractText && (!abstractTextarea.value || abstractTextarea.value.length < 10)) { abstractTextarea.value = abstractText.slice(0, 4000); }
            }
            URL.revokeObjectURL(url);
        } catch (err) { console.warn('PDF parse error:', err); }
    });

    // Submit create form
    const createForm = document.getElementById('createResearchForm');
    createForm?.addEventListener('submit', function(e){
        e.preventDefault();
        const form=this; const submitBtn=form.querySelector('button[type="submit"]'); const spinner=submitBtn.querySelector('.spinner-border');
        submitBtn.disabled=true; spinner?.classList.remove('d-none');
        const csrfToken=document.querySelector('meta[name="csrf-token"]').content;
        const formData=new FormData(form);
        fetch('{{ route('admin.research.store') }}', { method:'POST', headers:{ 'X-CSRF-TOKEN': csrfToken }, body: formData })
        .then(r=>r.json().then(data=>({status:r.status, data})))
        .then(({status,data})=>{ if (status===200||status===201||data.success){ window.alert('Success: Research has been created successfully!'); window.location.reload(); } else if (status===422){ window.alert('Error: Please fix the highlighted fields.'); } else { window.alert('Error: ' + (data.message || 'Failed to create research')); } })
        .catch(err=>{ window.alert('Error: ' + err.message); })
        .finally(()=>{ submitBtn.disabled=false; spinner?.classList.add('d-none'); });
    });
});
</script>
@endpush


