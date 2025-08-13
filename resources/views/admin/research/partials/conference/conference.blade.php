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
                            @foreach ($conferencePapers as $paper)
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
                                        <a href="{{ route('admin.conference-papers.edit', $paper->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('admin.conference-papers.visibility', $paper->id) }}" class="ms-1">
                                            @csrf
                                            <input type="hidden" name="is_public" value="{{ $paper->is_public ? 0 : 1 }}">
        
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $paper->is_public ? 'secondary' : 'success' }}">
                                                <i class="fa fa-eye{{ $paper->is_public ? '-slash' : '' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.conference-papers.destroy', $paper->id) }}" onsubmit="return confirm('Delete this conference paper?');" class="ms-1">
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
                <div class="mt-2">{{ $conferencePapers->links() }}</div>
            </div>

            <div class="tab-pane fade" id="conf-deleted" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="conferenceDeletedTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Deleted At</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deletedConferencePapers as $paper)
                            <tr>
                                <td>{{ $paper->title }}</td>
                                <td>{{ optional($paper->deleted_at)->format('M d, Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.conference-papers.restore', $paper->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-undo-alt"></i> Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
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
                    </div>
                    <div class="mb-3">
                        <label for="conf-title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conf-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors <span class="text-danger">*</span></label>
                        <div class="border rounded p-2" id="conf-authors-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="conf-authors-chips"></div>
                            <input type="text" id="conf-authors-input" class="form-control" placeholder="Type a name and press Enter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="conf-published_at" name="published_at" required>
                    </div>
                    <div class="mb-3">
                        <label for="conf-conference_name" class="form-label">Conference Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conf-conference_name" name="conference_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="conf-conference_location" class="form-label">Conference Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conf-conference_location" name="conference_location" placeholder="City, Country" required>
                    </div>
                    <div class="mb-3">
                        <label for="conf-publisher" class="form-label">Publisher <small class="text-muted">(optional)</small></label>
                        <input type="text" class="form-control" id="conf-publisher" name="publisher">
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
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                        <div class="border rounded p-2" id="conf-keywords-container">
                            <div class="d-flex flex-wrap gap-2 mb-2" id="conf-keywords-chips"></div>
                            <input type="text" id="conf-keywords-input" class="form-control" placeholder="Type a keyword and press Enter">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="conf-abstract" class="form-label">Abstract <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control" id="conf-abstract" name="abstract" rows="4"></textarea>
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

    // Conference authors chips
    const confAuthorsChips = document.getElementById('conf-authors-chips');
    const confAuthorsInput = document.getElementById('conf-authors-input');
    function confCreateChip(label, container, hiddenName) {
        const chip=document.createElement('span'); chip.className='badge bg-secondary d-inline-flex align-items-center'; chip.style.gap='6px'; chip.textContent=label;
        const remove=document.createElement('button'); remove.type='button'; remove.className='btn-close btn-close-white btn-sm ms-2'; remove.addEventListener('click',()=>chip.remove());
        const hidden=document.createElement('input'); hidden.type='hidden'; hidden.name=hiddenName; hidden.value=label; chip.appendChild(remove); chip.appendChild(hidden); container.appendChild(chip);
    }
    function confAddChip(input, container){ const value=input.value.trim(); if(!value) return; confCreateChip(value, container, 'authors[]'); input.value=''; }
    confAuthorsInput?.addEventListener('keydown', (e)=>{ if(e.key==='Enter' || e.key===','){ e.preventDefault(); confAddChip(confAuthorsInput, confAuthorsChips);} });
    const confCurrentUser = @json(Auth::user()?->name ?? ''); if (confCurrentUser) confCreateChip(confCurrentUser, confAuthorsChips, 'authors[]');

    // Conference keywords chips
    const confKeywordsChips = document.getElementById('conf-keywords-chips');
    const confKeywordsInput = document.getElementById('conf-keywords-input');
    function confAddKeyword(input, container){ const value=input.value.trim(); if(!value) return; const chip=document.createElement('span'); chip.className='badge bg-info d-inline-flex align-items-center'; chip.style.gap='6px'; chip.textContent=value; const remove=document.createElement('button'); remove.type='button'; remove.className='btn-close btn-close-white btn-sm ms-2'; remove.addEventListener('click',()=>chip.remove()); const hidden=document.createElement('input'); hidden.type='hidden'; hidden.name='keywords[]'; hidden.value=value; chip.appendChild(remove); chip.appendChild(hidden); container.appendChild(chip); input.value=''; }
    confKeywordsInput?.addEventListener('keydown', (e)=>{ if(e.key==='Enter' || e.key===','){ e.preventDefault(); confAddKeyword(confKeywordsInput, confKeywordsChips);} });

    // PDF filename to title
    const confFileInput = document.getElementById('conf-file');
    const confTitleInput = document.getElementById('conf-title');
    confFileInput?.addEventListener('change', async (e)=>{ const file=e.target.files?.[0]; if(!file) return; const fileNameBase=file.name.replace(/\.pdf$/i,'').replace(/[_-]+/g,' '); if(!confTitleInput.value) confTitleInput.value=fileNameBase; });

    // Submit create conference form
    const createConfForm = document.getElementById('createConferenceForm');
    createConfForm?.addEventListener('submit', function(e){
        e.preventDefault();
        const form=this; const submitBtn=form.querySelector('button[type="submit"]'); const spinner=submitBtn.querySelector('.spinner-border');
        submitBtn.disabled=true; spinner?.classList.remove('d-none');
        const csrfToken=document.querySelector('meta[name="csrf-token"]').content; const formData=new FormData(form);
        fetch(form.action, { method:'POST', headers:{ 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: formData })
        .then(r=>r.json().then(data=>({status:r.status, data})))
        .then(({status,data})=>{ if(status===200||status===201||data.success){ window.alert('Success: Conference paper created!'); window.location.reload(); } else if(status===422){ window.alert('Error: Please fix the highlighted fields.'); } else { window.alert('Error: ' + (data.message || 'Failed to create conference paper')); } })
        .catch(err=>{ window.alert('Error: ' + err.message); })
        .finally(()=>{ submitBtn.disabled=false; spinner?.classList.add('d-none'); });
    });
});
</script>
@endpush


