@extends('layouts.Reviewer.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0 fw-bold">Reviewer Dashboard</h4>
        <div class="alert alert-info mb-0 py-2 px-3 border-0 shadow-sm d-none d-md-block">
            <i class="fas fa-user-check me-2"></i>Welcome back, <strong>{{ Auth::user()->name }}</strong>. Here's an overview of your review activities.
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-clipboard-list fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Assigned</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['total'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('reviewer.reviews.index') }}" class="text-primary small text-decoration-none">
                        View All <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-envelope-open-text fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Pending Invitations</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['pending'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('reviewer.reviews.index', ['tab' => 'invitations']) }}" class="text-warning small text-decoration-none">
                        Respond <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-search fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Under Review</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['under_review'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-info bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('reviewer.reviews.index', ['tab' => 'assignments', 'status' => 'Under Review']) }}" class="text-info small text-decoration-none">
                        View Active <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Completed</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['completed'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-success bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('reviewer.reviews.index', ['tab' => 'assignments', 'status' => 'Completed']) }}" class="text-success small text-decoration-none">
                        View Completed <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Pending Invitations --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-warning bg-gradient text-white border-0 py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-envelope-open-text me-2"></i>Recent Invitations to Respond</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($recentInvitations as $review)
                    <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center mt-1" style="width:38px;height:38px;flex-shrink:0;">
                            <i class="fas fa-file-alt small"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 fw-bold text-dark text-truncate small">{{ $review->submission->research_title }}</p>
                            <p class="mb-1 text-muted" style="font-size:0.75rem;">Assigned {{ $review->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            <form action="{{ route('reviewer.reviews.accept', $review->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-success px-2 py-1" title="Accept">
                                    <i class="fas fa-check small"></i>
                                </button>
                            </form>
                            <form action="{{ route('reviewer.reviews.decline', $review->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger px-2 py-1" title="Decline">
                                    <i class="fas fa-times small"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-30 d-block"></i>
                        <p class="small mb-0">No pending invitations.</p>
                    </div>
                    @endforelse
                </div>
                @if($recentInvitations->count() > 0)
                <div class="card-footer bg-transparent border-0 py-3 text-center">
                    <a href="{{ route('reviewer.reviews.index', ['tab' => 'invitations']) }}" class="btn btn-sm btn-outline-warning px-4">
                        View All Invitations
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Active Reviews --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-info bg-gradient text-white border-0 py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-tasks me-2"></i>Active Assignments</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($recentActive as $review)
                    <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center mt-1" style="width:38px;height:38px;flex-shrink:0;">
                            <i class="fas fa-search small"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 fw-bold text-dark text-truncate small">{{ $review->submission->research_title }}</p>
                            <p class="mb-0 text-muted" style="font-size:0.75rem;">
                                Accepted {{ $review->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <span class="badge bg-info text-white rounded-pill px-3 flex-shrink-0">Active</span>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-check fa-2x mb-2 opacity-30 d-block"></i>
                        <p class="small mb-0">No active assignments.</p>
                    </div>
                    @endforelse
                </div>
                @if($recentActive->count() > 0)
                <div class="card-footer bg-transparent border-0 py-3 text-center">
                    <a href="{{ route('reviewer.reviews.index', ['tab' => 'assignments']) }}" class="btn btn-sm btn-outline-info px-4">
                        View All Assignments
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .border-left-primary { border-left: 4px solid #0d6efd; }
    .card:hover { transform: translateY(-2px); transition: transform 0.2s ease; }
</style>
@endpush
