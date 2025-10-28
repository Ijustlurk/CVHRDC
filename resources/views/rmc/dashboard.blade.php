@extends('layouts.RMC.app')
@section('content')
<div class="container py-4">
    <div class="mt-2">
        <h5 class="mb-4 font-medium site-logo"><i class="fa-solid fa-caret-right me-4"></i>RMC Dashboard - Research Management Committee</h5>
    </div>
    <hr>
    
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-blue">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">📋 Total Calls</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalCalls ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-green">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">✅ Active Calls</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $activeCalls ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-yellow">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">📝 Draft Calls</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $draftCalls ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-grey">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">🔒 Closed Calls</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $closedCalls ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-cyan">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">📄 Total Submissions</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalSubmissions ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-orange">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">📮 Submitted</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $submittedProposals ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-purple">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">🔍 Under Review</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $underReviewProposals ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-armygreen">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">✨ Accepted</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $acceptedProposals ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100 modern-card">
                <div class="card-body">
                    <h5 class="card-title fs-4 fw-bold">Recent Calls Created</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($recentCalls as $call)
                        <li class="list-group-item border-0">
                            <strong>{{ $call->title }}</strong>
                            <br><small class="text-muted">Status: {{ $call->status }} | {{ $call->created_at->format('M d, Y') }}</small>
                        </li>
                        @empty
                        <li class="list-group-item border-0 text-muted">No calls created yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100 modern-card">
                <div class="card-body">
                    <h5 class="card-title fs-4 fw-bold">Quick Stats</h5>
                    <div class="mt-3">
                        <p class="mb-2"><i class="fa-solid fa-calendar-days me-2"></i><strong>New Submissions (Last 7 days):</strong> {{ $recentSubmissions ?? 0 }}</p>
                        <p class="mb-2"><i class="fa-solid fa-chart-line me-2"></i><strong>Approval Rate:</strong> {{ $totalSubmissions > 0 ? round(($acceptedProposals / $totalSubmissions) * 100, 1) : 0 }}%</p>
                        <p class="mb-0"><i class="fa-solid fa-clock me-2"></i><strong>Active Calls Expiring Soon:</strong> Coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

