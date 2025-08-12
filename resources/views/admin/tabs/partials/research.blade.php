@if(session('success'))
	<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
	<div class="alert alert-danger">
		<ul class="mb-0">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow h-100">
			<div class="card-header bg-primary fw-bold text-white">Add Research</div>
			<div class="card-body">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul class="mb-0">
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<form method="POST" action="{{ \Illuminate\Support\Facades\Route::has('admin.research.store') ? route('admin.research.store') : '#' }}" class="needs-validation" enctype="multipart/form-data" novalidate>
					@csrf
					<div class="mb-3">
						<label for="file" class="form-label">Research PDF <span class="text-danger">*</span></label>
						<input type="file" name="file" id="file" class="form-control" accept="application/pdf" required>
						<div class="form-text">Upload a PDF file. We'll try to read its title and abstract.</div>
						<div class="invalid-feedback">PDF file is required.</div>
					</div>

					<div class="mb-3">
						<label for="title" class="form-label">Title <span class="text-danger">*</span></label>
						<input type="text" name="title" id="title" class="form-control" required placeholder="Enter research title">
						<div class="invalid-feedback">Title is required.</div>
					</div>

					<div class="mb-3">
						<label class="form-label">Authors <span class="text-danger">*</span></label>
						<div class="border rounded p-2" id="authors-container">
							<div class="d-flex flex-wrap gap-2 mb-2" id="authors-chips"></div>
							<input type="text" id="authors-input" class="form-control" placeholder="Type a name and press Enter">
						</div>
						<div class="form-text">Include authors in order. The logged-in user is added by default.</div>
						<div class="invalid-feedback d-block" id="authors-error" style="display:none;">At least one author is required.</div>
					</div>

					<div class="mb-3">
						<label for="published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
						<input type="date" name="published_at" id="published_at" class="form-control" required>
						<div class="invalid-feedback">Publication date is required.</div>
					</div>

					<div class="mb-3">
						<label for="abstract" class="form-label">Abstract <small class="text-muted">(optional)</small></label>
						<textarea name="abstract" id="abstract" class="form-control" rows="5" placeholder="Paste or edit the abstract"></textarea>
						<div class="form-text">We'll try to auto-fill from the PDF if available.</div>
					</div>

					<div class="mb-3">
						<label for="category" class="form-label">Research Category <span class="text-danger">*</span></label>
						<select name="category" id="category" class="form-select" required>
							<option value="" selected disabled>Select a category</option>
							<option value="Terminal">Terminal</option>
							<option value="Technological">Technological</option>
							<option value="Social">Social</option>
						</select>
						<div class="invalid-feedback">Please select a category.</div>
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
						<div class="invalid-feedback d-block" id="visibility-error" style="display:none;">Please choose visibility.</div>
					</div>

					<button type="submit" class="btn btn-primary w-100">Publish</button>
				</form>
			</div>
		</div>
	</div>

	<div class="col-12 col-lg-7">
		@isset($research)
			<div class="row row-cols-1 row-cols-md-2 g-3">
				@foreach($research as $item)
					<div class="col">
						<div class="card h-100">
							<div class="card-body">
								<h5 class="card-title">{{ $item->title }}</h5>
								<p class="card-text"><small class="text-muted">Published: {{ $item->published_at ? $item->published_at->format('M d, Y') : '' }}</small></p>
								<p class="card-text">{{ Str::limit($item->abstract, 120) }}</p>
								<p class="mb-1"><strong>Category:</strong> {{ $item->category }}</p>
								<p class="mb-1"><strong>Authors:</strong> {{ implode(', ', $item->authors ?? []) }}</p>
								<p class="mb-2"><strong>Visibility:</strong> {{ $item->is_public ? 'Public' : 'Private' }}</p>
							</div>
							<div class="card-footer d-flex justify-content-between align-items-center">
								<form method="POST" action="{{ \Illuminate\Support\Facades\Route::has('admin.research.visibility') ? route('admin.research.visibility', $item->id) : '#' }}" class="me-2">
									@csrf
									<input type="hidden" name="is_public" value="{{ $item->is_public ? 0 : 1 }}">
									<button type="submit" class="btn btn-sm btn-outline-{{ $item->is_public ? 'success' : 'secondary' }}">
										<i class="fa fa-eye{{ $item->is_public ? '' : '-slash' }}"></i>
									</button>
								</form>
								<a href="{{ \Illuminate\Support\Facades\Route::has('admin.research.edit') ? route('admin.research.edit', $item->id) : '#' }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
								<form method="POST" action="{{ \Illuminate\Support\Facades\Route::has('admin.research.destroy') ? route('admin.research.destroy', $item->id) : '#' }}" onsubmit="return confirm('Delete this research entry?');">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
								</form>
							</div>
						</div>
					</div>
				@endforeach
			</div>
			<div class="mt-3">
				{{ $research->links() }}
			</div>
		@endisset
	</div>
</div>

<!-- pdf.js for reading PDF metadata and text -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>

<script>
// Bootstrap validation
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      const authorsValid = ensureAuthorsHiddenInputs(form);
      const visibilityValid = ensureVisibilitySelected();
      if (!form.checkValidity() || !authorsValid || !visibilityValid) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();

// Authors chips logic
const authorsChips = document.getElementById('authors-chips');
const authorsInput = document.getElementById('authors-input');
const authorsError = document.getElementById('authors-error');

function createChip(label, container, hiddenName) {
  const chip = document.createElement('span');
  chip.className = 'badge bg-secondary d-inline-flex align-items-center';
  chip.style.gap = '6px';
  chip.textContent = label;

  const remove = document.createElement('button');
  remove.type = 'button';
  remove.className = 'btn-close btn-close-white btn-sm ms-2';
  remove.ariaLabel = 'Remove';
  remove.addEventListener('click', () => {
    chip.remove();
  });

  const hidden = document.createElement('input');
  hidden.type = 'hidden';
  hidden.name = hiddenName;
  hidden.value = label;

  chip.appendChild(remove);
  chip.appendChild(hidden);
  container.appendChild(chip);
}

function ensureAuthorsHiddenInputs(form) {
  const hasAuthors = authorsChips.querySelectorAll('input[name="authors[]"]').length > 0;
  authorsError.style.display = hasAuthors ? 'none' : 'block';
  return hasAuthors;
}

function ensureVisibilitySelected() {
  const selected = document.querySelector('input[name="is_public"]:checked');
  const err = document.getElementById('visibility-error');
  const ok = !!selected;
  err.style.display = ok ? 'none' : 'block';
  return ok;
}

function addChipFromInput(input, container, hiddenName) {
  const value = input.value.trim();
  if (!value) return;
  createChip(value, container, hiddenName);
  input.value = '';
}

authorsInput?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    addChipFromInput(authorsInput, authorsChips, 'authors[]');
  }
});

// Pre-add current user as author by default
document.addEventListener('DOMContentLoaded', () => {
  const currentUser = @json(Auth::user()?->name ?? '');
  if (currentUser) {
    createChip(currentUser, authorsChips, 'authors[]');
  }
});

// Keywords chips logic
const keywordsChips = document.getElementById('keywords-chips');
const keywordsInput = document.getElementById('keywords-input');

keywordsInput?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    addChipFromInput(keywordsInput, keywordsChips, 'keywords[]');
  }
});

// PDF metadata and abstract extraction
const fileInput = document.getElementById('file');
const titleInput = document.getElementById('title');
const abstractTextarea = document.getElementById('abstract');

if (window['pdfjsLib']) {
  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

fileInput?.addEventListener('change', async (e) => {
  const file = e.target.files?.[0];
  if (!file) return;
  // Set title from filename as fallback
  const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
  if (!titleInput.value) titleInput.value = fileNameBase;

  try {
    const url = URL.createObjectURL(file);
    const loadingTask = pdfjsLib.getDocument(url);
    const pdf = await loadingTask.promise;
    // Try metadata
    const meta = await pdf.getMetadata().catch(() => null);
    const docInfo = meta?.info || {};
    if (docInfo.Title && (!titleInput.value || titleInput.value.toLowerCase() === fileNameBase.toLowerCase())) {
      titleInput.value = docInfo.Title;
    }

    // Try to get first 2 pages and search for Abstract
    let text = '';
    const pagesToRead = Math.min(pdf.numPages, 3);
    for (let i = 1; i <= pagesToRead; i++) {
      const page = await pdf.getPage(i);
      const content = await page.getTextContent();
      const pageText = content.items.map(it => it.str).join(' ');
      text += '\n' + pageText;
    }
    const idx = text.search(/abstract\s*[:.-]?\s*/i);
    if (idx !== -1) {
      const snippet = text.substring(idx).replace(/\s+/g, ' ').trim();
      const endIdx = snippet.search(/\b(introduction|background|keywords|methods|materials|methodology)\b/i);
      const abstractText = (endIdx !== -1 ? snippet.substring(0, endIdx) : snippet).replace(/^abstract\s*[:.-]?\s*/i, '').trim();
      if (abstractText && (!abstractTextarea.value || abstractTextarea.value.length < 10)) {
        abstractTextarea.value = abstractText.slice(0, 4000);
      }
    }
    URL.revokeObjectURL(url);
  } catch (err) {
    console.warn('PDF parse error:', err);
  }
});
</script>


