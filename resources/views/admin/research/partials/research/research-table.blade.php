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
            @forelse ($research as $item)
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
                        <a href="{{ route('admin.research.show', $item->id) }}" class="btn btn-sm btn-info" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.research.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.research.visibility', $item->id) }}" class="ms-1">
                            @csrf
                            <input type="hidden" name="is_public" value="{{ $item->is_public ? 0 : 1 }}">
                            <button type="submit" class="btn btn-sm btn-outline-{{ $item->is_public ? 'secondary' : 'success' }}" title="{{ $item->is_public ? 'Make Private' : 'Make Public' }}">
                                <i class="fa fa-eye{{ $item->is_public ? '-slash' : '' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.research.destroy', $item->id) }}" onsubmit="return confirm('Delete this research entry?');" class="ms-1">
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
                    <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No research found. Try adjusting your search or filters.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2" id="researchPagination">
    {{ $research->links() }}
</div>

