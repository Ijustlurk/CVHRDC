<div class="table-responsive">
    <table class="table table-bordered table-hover" id="preprintPublishedTable">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Authors</th>
                <th>Repository</th>
                <th>Published At</th>
                <th>Peer-reviewed</th>
                <th>Visibility</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($preprints ?? [] as $preprint)
                @if ($preprint)
                    <tr>
                        <td>{{ $preprint->title ?? 'N/A' }}</td>
                        <td>{{ implode(', ', $preprint->authors ?? []) }}</td>
                        <td>{{ $preprint->repository_name ?? 'N/A' }}</td>
                        <td>{{ optional($preprint->published_at)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $preprint->is_peer_reviewed ?? false ? 'success' : 'warning' }}">
                                {{ $preprint->is_peer_reviewed ?? false ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $preprint->is_public ?? false ? 'success' : 'secondary' }}">{{ $preprint->is_public ?? false ? 'Public' : 'Private' }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.preprints.show', $preprint->id) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.preprints.edit', $preprint->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.preprints.visibility', $preprint->id) }}" class="ms-1">
                                    @csrf
                                    <input type="hidden" name="is_public" value="{{ $preprint->is_public ?? false ? 0 : 1 }}">
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $preprint->is_public ?? false ? 'secondary' : 'success' }}" title="{{ $preprint->is_public ?? false ? 'Make Private' : 'Make Public' }}">
                                        <i class="fa fa-eye{{ $preprint->is_public ?? false ? '-slash' : '' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.preprints.destroy', $preprint->id) }}" onsubmit="return confirm('Delete this preprint?');" class="ms-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-file-lines fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No preprints found. Try adjusting your search or filters.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2" id="preprintPagination">
    {{ isset($preprints) ? $preprints->links() : '' }}
</div>

