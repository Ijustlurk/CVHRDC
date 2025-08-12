@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center mb-4 gap-3">
    <h4 class="mb-0">Edit Research</h4>
    <a href="{{ route('admin.tabs.index', ['tab' => 'research']) }}" class="btn btn-light">&larr; Back to Research</a>
  </div>

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

  <div class="card shadow">
    <div class="card-header bg-primary text-white fw-bold">Update Research Details</div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.research.update', $research->id) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-12">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $research->title) }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <label class="form-label">Authors <span class="text-danger">*</span></label>
            <div class="border rounded p-2" id="authors-container">
              <div class="d-flex flex-wrap gap-2 mb-2" id="authors-chips">
                @foreach(old('authors', $research->authors ?? []) as $author)
                  <span class="badge bg-secondary d-inline-flex align-items-center" style="gap:6px;">
                    {{ $author }}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Remove" onclick="this.closest('span').remove()"></button>
                    <input type="hidden" name="authors[]" value="{{ $author }}">
                  </span>
                @endforeach
              </div>
              <input type="text" id="authors-input" class="form-control" placeholder="Type a name and press Enter">
            </div>
            <div class="invalid-feedback d-block" id="authors-error" style="display:none;">At least one author is required.</div>
            @error('authors')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label for="published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
            <input type="date" id="published_at" name="published_at" value="{{ old('published_at', optional($research->published_at)->format('Y-m-d')) }}" class="form-control @error('published_at') is-invalid @enderror" required>
            @error('published_at')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label for="category" class="form-label">Research Category <span class="text-danger">*</span></label>
            <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
              @php $selectedCategory = old('category', $research->category); @endphp
              <option value="" disabled {{ $selectedCategory ? '' : 'selected' }}>Select a category</option>
              <option value="Terminal" {{ $selectedCategory === 'Terminal' ? 'selected' : '' }}>Terminal</option>
              <option value="Technological" {{ $selectedCategory === 'Technological' ? 'selected' : '' }}>Technological</option>
              <option value="Social" {{ $selectedCategory === 'Social' ? 'selected' : '' }}>Social</option>
            </select>
            @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <label for="abstract" class="form-label">Abstract <small class="text-muted">(optional)</small></label>
            <textarea id="abstract" name="abstract" rows="6" class="form-control @error('abstract') is-invalid @enderror" placeholder="Paste or edit the abstract">{{ old('abstract', $research->abstract) }}</textarea>
            @error('abstract')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
            <div class="border rounded p-2" id="keywords-container">
              <div class="d-flex flex-wrap gap-2 mb-2" id="keywords-chips">
                @foreach(old('keywords', $research->keywords ?? []) as $kw)
                  <span class="badge bg-info d-inline-flex align-items-center" style="gap:6px;">
                    {{ $kw }}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Remove" onclick="this.closest('span').remove()"></button>
                    <input type="hidden" name="keywords[]" value="{{ $kw }}">
                  </span>
                @endforeach
              </div>
              <input type="text" id="keywords-input" class="form-control" placeholder="Type a keyword and press Enter">
            </div>
            @error('keywords')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="col-12">
            <label class="form-label">File Visibility <span class="text-danger">*</span></label>
            @php $isPublic = (bool) old('is_public', $research->is_public); @endphp
            <div class="d-flex gap-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_public" id="visibility_public" value="1" {{ $isPublic ? 'checked' : '' }} required>
                <label class="form-check-label" for="visibility_public">Public</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_public" id="visibility_private" value="0" {{ !$isPublic ? 'checked' : '' }} required>
                <label class="form-check-label" for="visibility_private">Private</label>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
          <a href="{{ route('admin.tabs.index', ['tab' => 'research']) }}" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Bootstrap validation
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      const authorsValid = ensureAuthorsHiddenInputs();
      if (!form.checkValidity() || !authorsValid) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();

// Chips helpers
function createChip(label, container, hiddenName, badgeClass) {
  const chip = document.createElement('span');
  chip.className = `badge ${badgeClass} d-inline-flex align-items-center`;
  chip.style.gap = '6px';
  chip.textContent = label;

  const remove = document.createElement('button');
  remove.type = 'button';
  remove.className = 'btn-close btn-close-white btn-sm ms-2';
  remove.ariaLabel = 'Remove';
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

const authorsChips = document.getElementById('authors-chips');
const authorsInput = document.getElementById('authors-input');
const authorsError = document.getElementById('authors-error');
authorsInput?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    addChipFromInput(authorsInput, authorsChips, 'authors[]', 'bg-secondary');
  }
});

const keywordsChips = document.getElementById('keywords-chips');
const keywordsInput = document.getElementById('keywords-input');
keywordsInput?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    addChipFromInput(keywordsInput, keywordsChips, 'keywords[]', 'bg-info');
  }
});

function ensureAuthorsHiddenInputs() {
  const hasAuthors = authorsChips.querySelectorAll('input[name="authors[]"]').length > 0;
  authorsError.style.display = hasAuthors ? 'none' : 'block';
  return hasAuthors;
}
</script>
@endpush


