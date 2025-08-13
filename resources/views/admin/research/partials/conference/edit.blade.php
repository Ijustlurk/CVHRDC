@extends('layouts.Admin.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center mb-4 gap-3">
      <h4 class="mb-0">Edit Conference Paper</h4>
      <a href="{{ route('admin.research.index', ['type' => 'conference']) }}" class="btn btn-light">&larr; Back</a>
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
      <div class="card-header bg-primary text-white fw-bold">Update Conference Paper</div>
      <div class="card-body">
          <form method="POST" action="{{ route('admin.conference-papers.update', $paper->id) }}" class="needs-validation" novalidate>
              @csrf
              @method('PUT')

              <div class="row g-3">
                  <div class="col-12">
                      <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                      <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $paper->title) }}" required>
                      @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                      <label class="form-label">Authors <span class="text-danger">*</span></label>
                      <div class="border rounded p-2" id="authors-container">
                          <div class="d-flex flex-wrap gap-2 mb-2" id="authors-chips">
                              @foreach(old('authors', $paper->authors ?? []) as $author)
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
                      <label for="published_at" class="form-label">Publication Date</label>
                      <input id="published_at" name="published_at" type="date" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($paper->published_at)->format('Y-m-d')) }}">
                      @error('published_at')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="language" class="form-label">Language <small class="text-muted">(ISO 639-1)</small></label>
                      <input id="language" name="language" type="text" class="form-control @error('language') is-invalid @enderror" value="{{ old('language', $paper->language) }}" placeholder="e.g., en, fr, es">
                      @error('language')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="conference_name" class="form-label">Conference Name <span class="text-danger">*</span></label>
                      <input id="conference_name" name="conference_name" type="text" class="form-control @error('conference_name') is-invalid @enderror" value="{{ old('conference_name', $paper->conference_name) }}" required>
                      @error('conference_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="conference_location" class="form-label">Conference Location <span class="text-danger">*</span></label>
                      <input id="conference_location" name="conference_location" type="text" class="form-control @error('conference_location') is-invalid @enderror" value="{{ old('conference_location', $paper->conference_location) }}" placeholder="City, Country" required>
                      @error('conference_location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                      <label for="publisher" class="form-label">Publisher <small class="text-muted">(optional)</small></label>
                      <input id="publisher" name="publisher" type="text" class="form-control @error('publisher') is-invalid @enderror" value="{{ old('publisher', $paper->publisher) }}">
                      @error('publisher')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                      <label for="abstract" class="form-label">Abstract <small class="text-muted">(optional)</small></label>
                      <textarea id="abstract" name="abstract" rows="6" class="form-control @error('abstract') is-invalid @enderror" placeholder="Paste or edit the abstract">{{ old('abstract', $paper->abstract) }}</textarea>
                      @error('abstract')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                      <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                      <div class="border rounded p-2" id="keywords-container">
                          <div class="d-flex flex-wrap gap-2 mb-2" id="keywords-chips">
                              @foreach(old('keywords', $paper->keywords ?? []) as $kw)
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
                      @php $isPublic = (bool) old('is_public', $paper->is_public); @endphp
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
                  <a href="{{ route('admin.research.index', ['type' => 'conference']) }}" class="btn btn-secondary">Cancel</a>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
          </form>
      </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
// Simple chips helpers for authors and keywords
function createChip(label, container, hiddenName, badgeClass) {
  const chip = document.createElement('span');
  chip.className = `badge ${badgeClass} d-inline-flex align-items-center`;
  chip.style.gap = '6px';
  chip.textContent = label;
  const remove = document.createElement('button');
  remove.type = 'button'; remove.className = 'btn-close btn-close-white btn-sm ms-2';
  remove.addEventListener('click', () => chip.remove());
  const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = hiddenName; hidden.value = label;
  chip.appendChild(remove); chip.appendChild(hidden); container.appendChild(chip);
}
document.addEventListener('DOMContentLoaded', () => {
  const aChips = document.getElementById('authors-chips');
  const aInput = document.getElementById('authors-input');
  aInput?.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); const v=aInput.value.trim(); if(!v) return; createChip(v, aChips, 'authors[]', 'bg-secondary'); aInput.value=''; }});

  const kChips = document.getElementById('keywords-chips');
  const kInput = document.getElementById('keywords-input');
  kInput?.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); const v=kInput.value.trim(); if(!v) return; createChip(v, kChips, 'keywords[]', 'bg-info'); kInput.value=''; }});
});
</script>
@endpush


