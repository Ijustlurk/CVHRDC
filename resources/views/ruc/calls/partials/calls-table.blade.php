<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Created By</th>
                <th>Status</th>
                <th>Visibility</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Submissions</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($calls as $call)
            <tr>
                <td>
                    <strong>{{ $call->title }}</strong>
                    <br><small class="text-muted">{{ Str::limit($call->description, 50) }}</small>
                    @if($call->research_priority_area)
                        <br><small class="text-info">{{ $call->research_priority_area }}</small>
                    @endif
                </td>
                <td>{{ $call->creator->name }}</td>
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
                <td>
                    <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('ruc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">View</a>

                        @if($call->status === 'For Review' || $call->status === 'Draft')
                        <!-- Approve and Publish -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmApproveCall(event, '{{ addslashes($call->title) }}')">
                            @csrf
                            <input type="hidden" name="status" value="Open">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check me-1"></i>Approve & Publish
                            </button>
                        </form>
                        @elseif($call->status === 'Open' && $call->visibility_status === 'Public')
                        <!-- Withdraw/Close -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmWithdrawCall(event, '{{ addslashes($call->title) }}')">
                            @csrf
                            <input type="hidden" name="status" value="Closed">
                            <input type="hidden" name="action" value="withdraw">
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="fas fa-times me-1"></i>Withdraw & Close
                            </button>
                        </form>
                        @elseif($call->status === 'Closed' && $call->visibility_status === 'Public')
                        <!-- Reopen for Review -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmReopenCall(event, '{{ addslashes($call->title) }}')">
                            @csrf
                            <input type="hidden" name="status" value="For Review">
                            <input type="hidden" name="action" value="reopen">
                            <button type="submit" class="btn btn-sm btn-info">
                                <i class="fas fa-redo me-1"></i>Reopen for Review
                            </button>
                        </form>
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

