@forelse ($announcements as $item)
<div class="col-6">
    <div class="card h-100 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0">{{ $item->title }}</h5>
                <span class="badge bg-{{ $item->active ? 'success' : 'secondary' }} ms-2">
                    {{ $item->active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <p class="card-text">{{ Str::limit($item->content, 150) }}</p>
            <div class="row text-muted small">
                <div class="col-6">
                    @if ($item->start_date)
                    <i class="fa fa-calendar-o me-1"></i>
                    Start: {{ \Carbon\Carbon::parse($item->start_date)->format('M d, Y') }}
                    @endif
                </div>
                <div class="col-6">
                    @if ($item->end_date)
                    <i class="fa fa-calendar-times-o me-1"></i>
                    End: {{ \Carbon\Carbon::parse($item->end_date)->format('M d, Y') }}
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center bg-light">
            <small class="text-muted">
                Created: {{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}
            </small>
            <div class="btn-group" role="group">
                <form method="POST" action="{{ route('admin.announcements.visibility', $item->id) }}"
                    class="d-inline">
                    @csrf
                    <input type="hidden" name="active" value="{{ $item->active ? 0 : 1 }}">
                    <button type="submit"
                        class="btn btn-sm btn-outline-{{ $item->active ? 'success' : 'secondary' }}">
                        <i class="fa fa-eye{{ $item->active ? '' : '-slash' }}"></i>
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-warning ms-1" title="Edit"
                    onclick="openEditAnnouncementModal({{ $item->id }})">
                    <i class="fa fa-edit"></i>
                </button>
                <!-- Delete Button that triggers Modal -->
                <button type="button" class="btn btn-sm btn-danger ms-1" title="Delete"
                    data-bs-toggle="modal" data-bs-target="#deleteAnnouncementModal{{ $item->id }}">
                    <i class="fa fa-trash"></i>
                </button>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteAnnouncementModal{{ $item->id }}" tabindex="-1"
                    aria-labelledby="deleteAnnouncementModalLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteAnnouncementModalLabel{{ $item->id }}">
                                    Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                Are you sure you want to delete this announcement?
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>

                                <form method="POST"
                                    action="{{ route('admin.announcements.destroy', $item->id) }}"
                                    class="d-inline">
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
</div>
@empty
<div class="col-12">
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="fa fa-bullhorn fa-3x mb-3 opacity-25"></i>
            <h5>No Announcements Found</h5>
            <p class="mb-0">No announcements match your search criteria.</p>
        </div>
    </div>
</div>
@endforelse

