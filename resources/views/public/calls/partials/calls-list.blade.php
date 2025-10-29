@forelse($calls as $call)
<div class="row mb-4">
    <div class="col-12">
        <div class="card call-card shadow-sm animate__animated animate__fadeInUp">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-file-alt text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h4 class="card-title mb-0 text font">
                                        {{ $call->title }}
                                    </h4>
                                    <span class="badge bg-{{ $call->getStatusBadgeColor() }} status-badge">{{ $call->getStatusLabel() }}</span>
                                </div>
                                <p class="card-text text-muted mb-3">
                                    {{ Str::limit(strip_tags($call->description), 150) }}
                                </p>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-money-bill-wave text-success me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Funding Limit</small>
                                                <strong class="text-dark">₱{{ number_format($call->funding_limit, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Application Period</small>
                                                <strong class="text-dark">
                                                    {{ $call->start_date->format('M d') }} - {{ $call->end_date->format('M d, Y') }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-users text-info me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">Eligibility</small>
                                                <span class="badge bg-info status-badge">{{ Str::limit($call->eligibility, 20) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                        <div class="d-flex gap-2 justify-content-lg-end justify-content-center">
                            <a href="{{ route('public.calls.show', $call->id) }}" class="btn btn-primary btn-md px-4">
                                <i class="fas fa-eye me-2"></i>View Full Details
                            </a>
                            @if($call->proposal_format_file && ($call->isAcceptingSubmissions() || $call->display_status === 'Closed'))
                            <a href="{{ route('public.calls.download-format', $call->id) }}" class="btn btn-outline-secondary btn-md px-4" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Format
                            </a>
                            @endif
                        </div>
                        <div class="mt-2 text-end">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $call->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="row justify-content-center">
    <div class="col-lg-6 text-center">
        <div class="card shadow-sm">
            <div class="card-body py-5">
                <i class="fas fa-search text-muted fs-1 mb-3"></i>
                <h4 class="text-muted">No Calls Found</h4>
                <p class="text-muted">No calls for proposals match your search criteria. Try adjusting your filters.</p>
            </div>
        </div>
    </div>
</div>
@endforelse

<div class="mt-4" id="callsPagination">{{ $calls->links() }}</div>
