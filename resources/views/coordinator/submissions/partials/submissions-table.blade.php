@php
    use Illuminate\Support\Str;
@endphp

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-dark text-uppercase small" style="font-size: 0.7rem; letter-spacing: 0.05rem;">
            <tr>
                <th class="px-4 py-3 border-0">Research Details</th>
                <th class="py-3 border-0 text-center">Call/Date</th>
                <th class="py-3 border-0 text-center">Status</th>
                <th class="py-3 border-0">Reviewers</th>
                <th class="px-4 py-3 border-0 text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $submission)
            <tr>
                <td class="px-4 py-3">
                    <div class="fw-bold text-dark mb-1 h6">{{ Str::limit($submission->research_title, 80) }}</div>
                    <div class="d-flex align-items-center text-muted small">
                        <span class="me-3"><i class="fas fa-user-circle me-1 opacity-75"></i>{{ $submission->proponent_name }}</span>
                        <span><i class="fas fa-university me-1 opacity-75"></i>{{ Str::limit($submission->affiliation, 30) }}</span>
                    </div>
                </td>
                <td class="py-3 text-center">
                    <div class="text-dark small fw-bold mb-1">{{ Str::limit($submission->callForProposal->title, 35) }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        <i class="fas fa-calendar-day me-1"></i>{{ $submission->date_submitted->format('M d, Y') }}
                    </div>
                </td>
                <td class="py-3 text-center">
                    <span class="badge rounded-pill bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }} px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                        <i class="fas fa-{{ $submission->status === 'Accepted' ? 'check-circle' : ($submission->status === 'Rejected' ? 'times-circle' : ($submission->status === 'Under Review' ? 'search' : 'paper-plane')) }} me-1"></i>
                        {{ $submission->status }}
                    </span>
                </td>
                <td class="py-3">
                    @if($submission->peerReviews->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($submission->peerReviews as $review)
                                <span class="badge bg-light text-dark border d-flex align-items-center gap-1 p-1 pr-2" title="{{ $review->reviewer->name }}">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; font-size: 0.6rem;">
                                        {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 0.7rem;">{{ Str::limit($review->reviewer->name, 12) }}</span>
                                    <i class="fas fa-circle ms-1 {{ $review->status === 'Completed' ? 'text-success' : 'text-warning' }}" style="font-size: 0.4rem;" title="{{ $review->status }}"></i>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted small italic opacity-75">
                            <i class="fas fa-user-clock me-1"></i>No Assignments
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('coordinator.submissions.show', $submission->id) }}" class="btn btn-sm btn-outline-info shadow-sm" title="View Details">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="py-4">
                        <i class="fas fa-search fa-3x text-light mb-3"></i>
                        <h6 class="text-muted fw-normal">No submissions found matching your criteria.</h6>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none mt-2" onclick="document.getElementById('resetFiltersBtn').click()">
                            <i class="fas fa-redo me-1"></i>Reset all filters
                        </button>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($submissions->hasPages())
<div class="card-footer bg-white border-0 py-3 mt-auto" id="submissionsPagination">
    {{ $submissions->links() }}
</div>
@endif
