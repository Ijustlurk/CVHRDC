@extends('layouts.Users.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center mb-4 gap-3">
      <h4 class="mb-0">Edit Book</h4>
      <a href="{{ route('users.research.index', ['type' => 'book']) }}" class="btn btn-light">&larr; Back</a>
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
      <div class="card-header bg-primary text-white fw-bold">Update Book</div>
      <div class="card-body">
          <form method="POST" action="{{ route('users.books.update', $book->id) }}" class="needs-validation" novalidate>
              @csrf
              @method('PUT')

              <div class="row g-3">
                  <div class="col-12">
                      <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                      <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required>
                      @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                      <label class="form-label">Authors <span class="text-danger">*</span></label>
                      <div class="border rounded p-2" id="authors-container">
                          <div class="d-flex flex-wrap gap-2 mb-2" id="authors-chips">
                              @foreach(old('authors', $book->authors ?? []) as $author)
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

                  <div class="col-12">
                      <label class="form-label">Editors <small class="text-muted">(optional)</small></label>
                      <div class="border rounded p-2" id="editors-container">
                          <div class="d-flex flex-wrap gap-2 mb-2" id="editors-chips">
                              @foreach(old('editors', $book->editors ?? []) as $editor)
                                  <span class="badge bg-secondary d-inline-flex align-items-center" style="gap:6px;">
                                      {{ $editor }}
                                      <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Remove" onclick="this.closest('span').remove()"></button>
                                      <input type="hidden" name="editors[]" value="{{ $editor }}">
                                  </span>
                              @endforeach
                          </div>
                          <input type="text" id="editors-input" class="form-control" placeholder="Type a name and press Enter">
                      </div>
                  </div>

                  <div class="col-md-6">
                      <label for="published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
                      <input id="published_at" name="published_at" type="date" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($book->published_at)->format('Y-m-d')) }}" required>
                      @error('published_at')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="publisher" class="form-label">Publisher <span class="text-danger">*</span></label>
                      <input id="publisher" name="publisher" type="text" class="form-control @error('publisher') is-invalid @enderror" value="{{ old('publisher', $book->publisher) }}" required>
                      @error('publisher')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                      <input id="isbn" name="isbn" type="text" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $book->isbn) }}" placeholder="10 or 13 digits" required>
                      @error('isbn')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="edition" class="form-label">Edition <small class="text-muted">(optional)</small></label>
                      <input id="edition" name="edition" type="text" class="form-control @error('edition') is-invalid @enderror" value="{{ old('edition', $book->edition) }}" placeholder="e.g., 2nd, Revised">
                      @error('edition')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-md-6">
                      <label for="doi" class="form-label">DOI <small class="text-muted">(optional)</small></label>
                      <input id="doi" name="doi" type="text" class="form-control @error('doi') is-invalid @enderror" value="{{ old('doi', $book->doi) }}" placeholder="e.g., 10.1234/abc.5678">
                      @error('doi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                      <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                      @php $isPublic = (bool) old('is_public', $book->is_public); @endphp
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

                  <div class="col-12">
                      <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                      <div class="border rounded p-2" id="keywords-container">
                          <div class="d-flex flex-wrap gap-2 mb-2" id="keywords-chips">
                              @foreach(old('keywords', $book->keywords ?? []) as $kw)
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
              </div>

              <div class="d-flex justify-content-end mt-4 gap-2">
                  <a href="{{ route('users.research.index', ['type' => 'book']) }}" class="btn btn-secondary">Cancel</a>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
          </form>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
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

  const eChips = document.getElementById('editors-chips');
  const eInput = document.getElementById('editors-input');
  eInput?.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); const v=eInput.value.trim(); if(!v) return; createChip(v, eChips, 'editors[]', 'bg-secondary'); eInput.value=''; }});

  const kChips = document.getElementById('keywords-chips');
  const kInput = document.getElementById('keywords-input');
  kInput?.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); const v=kInput.value.trim(); if(!v) return; createChip(v, kChips, 'keywords[]', 'bg-info'); kInput.value=''; }});
});
</script>
@endpush


