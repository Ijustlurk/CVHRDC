@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@isset($news)
    <div class="row mb-4 g-4">
        <div class="col-12 col-lg-5">
            <div class="card shadow h-100">
                <div class="card-header bg-primary fw-bold text-white">Add News</div>
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
                    <form method="POST" action="{{ route('admin.news.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                placeholder="Enter news title">
                            <div class="invalid-feedback">Title is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="4" required placeholder="Enter news content"></textarea>
                            <div class="invalid-feedback">Content is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="news-image" class="form-label">
                                Image URL <span class="text-danger">*</span>
                                <small class="text-muted">(Google Drive link)</small>
                            </label>
                            <input type="url" class="form-control" id="news-image" name="image" required
                                placeholder="https://drive.google.com/file/d/.../view">
                            <div class="form-text">
                                <strong>Google Drive Instructions:</strong><br>
                                1. Right-click your image in Google Drive → "Get link"<br>
                                2. Set to "Anyone with the link" can view<br>
                                3. Copy and paste the sharing link here<br>
                                <small class="text-muted">The system will automatically convert it to display
                                    format.</small>
                            </div>
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="form-label">Published At</label>
                            <input type="date" name="published_at" id="published_at" class="form-control" required>
                            <div class="invalid-feedback">Published date is required.</div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1"
                                checked>
                            <label class="form-check-label" for="is_visible">Visible</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add News</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach ($news as $item)
                    <div class="col">
                        <div class="card h-100">
                            @php
                                $imageUrl = $item->image;
                                // Convert Google Drive URL to direct image URL
                                if (strpos($imageUrl, 'drive.google.com') !== false) {
                                    // Extract file ID from Google Drive URL
                                    preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $imageUrl, $matches);
                                    if (!empty($matches[1])) {
                                        $fileId = $matches[1];
                                        $imageUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
                                    } else {
                                        // Try alternative format: ?id=FILE_ID
                                        preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $imageUrl, $matches);
                                        if (!empty($matches[1])) {
                                            $fileId = $matches[1];
                                            $imageUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
                                        }
                                    }
                                }
                            @endphp
                            <img src="{{ $imageUrl }}" class="card-img-top" alt="News Image" 
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text">{{ Str::limit($item->content, 100) }}</p>
                                <p class="card-text"><small class="text-muted">Published:
                                        {{ $item->published_at ? $item->published_at->format('M d, Y') : '' }}</small></p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <form method="POST" action="{{ route('admin.news.visibility', $item->id) }}"
                                    class="me-2">
                                    @csrf
                                    <input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
                                        <i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i
                                        class="fa fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}"
                                    onsubmit="return confirm('Delete this news?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                {{ $news->links() }}
            </div>
        </div>
    </div>
    <script>
        // Bootstrap validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endisset