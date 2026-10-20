{{-- AJAX-rendered table partial for reviewer reviews --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-muted text-uppercase" style="font-size: 0.72rem;">
            <tr>
                <th class="px-4 py-3">Submission Details</th>
                <th class="py-3">Proponent</th>
                <th class="py-3">Assigned On</th>
                <th class="py-3 text-center">Status</th>
                @if($tab === 'invitations')
                    <th class="px-4 py-3 text-center">Actions</th>
                @else
                    <th class="px-4 py-3 text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td class="px-4 py-3">
                    <div class="fw-bold text-dark" style="max-width: 320px;">
                        {{ Str::limit($review->submission->research_title, 65) }}
                    </div>
                    @if($review->submission->callForProposal)
                        <div class="small text-muted mt-1">
                            <i class="fas fa-bullhorn me-1"></i>{{ Str::limit($review->submission->callForProposal->title, 50) }}
                        </div>
                    @endif
                </td>
                <td class="py-3">
                    <span class="text-dark">{{ $review->submission->proponent_name }}</span>
                </td>
                <td class="py-3">
                    <span class="text-muted">{{ $review->created_at->format('M d, Y') }}</span>
                </td>
                <td class="py-3 text-center">
                    @php
                        $statusColors = [
                            'Pending'      => 'warning',
                            'Under Review' => 'info',
                            'Completed'    => 'success',
                            'Cancelled'    => 'secondary',
                        ];
                        $color = $statusColors[$review->status] ?? 'secondary';
                    @endphp
                    <span class="badge rounded-pill bg-{{ $color }} px-3 py-2">
                        {{ $review->status }}
                    </span>
                </td>
                @if($tab === 'invitations')
                <td class="px-4 py-3 text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <form action="{{ route('reviewer.reviews.accept', $review->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success shadow-sm px-3">
                                <i class="fas fa-check me-1"></i>Accept
                            </button>
                        </form>
                        <form action="{{ route('reviewer.reviews.decline', $review->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm px-3">
                                <i class="fas fa-times me-1"></i>Decline
                            </button>
                        </form>
                    </div>
                </td>
                @else
                <td class="px-4 py-3 text-center">
                    @if($review->status === 'Under Review')
                        <a href="{{ route('reviewer.reviews.show', $review->id) }}" class="btn btn-sm btn-primary shadow-sm px-3">
                            <i class="fas fa-eye me-1"></i>View & Review
                        </a>
                    @elseif($review->status === 'Completed')
                        <a href="{{ route('reviewer.reviews.show', $review->id) }}" class="btn btn-sm btn-outline-secondary shadow-sm px-3">
                            <i class="fas fa-eye me-1"></i>View Verdict
                        </a>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $tab === 'invitations' ? 5 : 4 }}" class="text-center py-5">
                    <div class="opacity-40 mb-3">
                        <i class="fas fa-{{ $tab === 'invitations' ? 'envelope-open' : 'folder-open' }} fa-3x"></i>
                    </div>
                    <h6 class="text-muted">
                        {{ $tab === 'invitations' ? 'No pending review invitations.' : 'No review assignments found.' }}
                    </h6>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($reviews->hasPages())
<div class="d-flex justify-content-end px-3 py-3 border-top bg-light" id="reviewsPagination">
    {{ $reviews->links() }}
</div>
@endif
