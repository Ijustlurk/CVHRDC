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
            @forelse ($conferencePapers as $paper)
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
                        <a href="{{ route('users.conference-papers.show', $paper->id) }}" class="btn btn-sm btn-info" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('users.conference-papers.edit', $paper->id) }}" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('users.conference-papers.visibility', $paper->id) }}" class="ms-1">
                            @csrf
                            <input type="hidden" name="is_public" value="{{ $paper->is_public ? 0 : 1 }}">
                            <button type="submit" class="btn btn-sm btn-outline-{{ $paper->is_public ? 'secondary' : 'success' }}" title="{{ $paper->is_public ? 'Make Private' : 'Make Public' }}">
                                <i class="fa fa-eye{{ $paper->is_public ? '-slash' : '' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('users.conference-papers.destroy', $paper->id) }}" onsubmit="return confirm('Delete this conference paper?');" class="ms-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No conference papers found. Try adjusting your search or filters.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2" id="conferencePagination">
    {{ $conferencePapers->links() }}
</div>

