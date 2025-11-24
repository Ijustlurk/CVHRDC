@if($submissions->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Research Title</th>
                    <th>Call for Proposal</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                    <th>Budget Requested</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ $submission->research_title }}</td>
                        <td>{{ $submission->callForProposal->title ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $submission->status === 'Submitted' ? 'primary' : ($submission->status === 'Under Review' ? 'warning' : ($submission->status === 'Accepted' ? 'success' : 'danger')) }}">
                                {{ $submission->status }}
                            </span>
                        </td>
                        <td>{{ $submission->date_submitted->format('M d, Y H:i') }}</td>
                        <td>₱{{ number_format($submission->budget_requested, 2) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('users.proposal-submissions.show', $submission->id) }}"
                                   class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('users.proposal-submissions.download', $submission->id) }}"
                                   class="btn btn-sm btn-outline-secondary ms-1"
                                   title="Download Proposal Document">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3" id="submissionsPagination">
        {{ $submissions->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-file-signature fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No Proposal Submissions Found</h5>
        <p class="text-muted mb-4">No submissions match your search criteria. Try adjusting your filters.</p>
    </div>
@endif

