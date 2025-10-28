@foreach ($projects as $item)
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
        <img src="{{ $imageUrl }}" class="card-img-top" alt="Project Image"
            onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $item->title }}</h5>
            <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
            
            <!-- Project Details -->
            <div class="mb-2">
                <small class="text-muted d-block">
                    <i class="fa fa-user me-1"></i><strong>Lead:</strong> {{ $item->lead_researcher }}
                </small>
                <small class="text-muted d-block">
                    <i class="fa fa-flag me-1"></i><strong>Status:</strong> {{ $item->status }}
                </small>
                @if($item->budget)
                <small class="text-muted d-block">
                    <i class="fa fa-money-bill me-1"></i><strong>Budget:</strong> ${{ number_format($item->budget) }}
                </small>
                @endif
            </div>

            <!-- Date pushed to bottom -->
            <p class="card-text mt-auto">
                <small class="text-muted">
                    Start: {{ $item->start_date ? $item->start_date->format('M d, Y') : '' }}
                </small>
            </p>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-6 d-flex justify-content-start">
                    <form method="POST" action="{{ route('admin.projects.visibility', $item->id) }}"
                        class="me-2">
                        @csrf
                        <input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
                        <button type="submit"
                            class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
                            <i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
                        </button>
                    </form>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-warning me-2"
                        onclick="openEditProjectModal({{ $item->id }})"><i class="fa fa-edit"></i></button>

                    <!-- Delete Button -->
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $item->id }}">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                                <i class="fa fa-exclamation-triangle me-2"></i> Confirm Deletion
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            Are you sure you want to delete this project? <br>
                            <strong>{{ $item->title }}</strong>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>

                            <!-- Actual Delete Form -->
                            <form method="POST" action="{{ route('admin.projects.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@if ($projects->isEmpty())
<div class="col-12">
    <div class="alert alert-info text-center">
        <i class="fa fa-info-circle me-2"></i>No projects found matching your criteria.
    </div>
</div>
@endif
