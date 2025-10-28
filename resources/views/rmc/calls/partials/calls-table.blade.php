<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Visibility</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Funding Limit</th>
                <th>Submissions</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($calls as $call)
            <tr>
                <td>
                    <strong>{{ $call->title }}</strong>
                    <br><small class="text-muted">by {{ $call->creator->name }}</small>
                    @if($call->research_priority_area)
                        <br><small class="text-info">{{ $call->research_priority_area }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                        {{ $call->status }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-{{ $call->visibility_status === 'Public' ? 'success' : 'secondary' }}">
                        {{ $call->visibility_status }}
                    </span>
                </td>
                <td>{{ $call->start_date->format('M d, Y') }}</td>
                <td>{{ $call->end_date->format('M d, Y') }}</td>
                <td>₱{{ number_format($call->funding_limit, 2) }}</td>
                <td>
                    <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('rmc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">View</a>
                        @if($call->created_by === Auth::id())
                            @if($call->visibility_status !== 'Public')
                                <a href="{{ route('rmc.calls.edit', $call->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('rmc.calls.destroy', $call->id) }}" class="d-inline ms-1" onsubmit="return confirmCallDeletion(event, '{{ addslashes($call->title) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @else
                                <span class="badge bg-warning text-dark">Posted Publicly</span>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No calls for proposals found matching your criteria.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2" id="callsPagination">{{ $calls->links() }}</div>
