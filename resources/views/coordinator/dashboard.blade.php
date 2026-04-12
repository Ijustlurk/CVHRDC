@extends('layouts.Coordinator.app')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0 fw-bold">Coordinator Dashboard</h4>
        <div class="alert alert-info mb-0 py-2 px-3 border-0 shadow-sm d-none d-md-block">
            <i class="fas fa-tasks me-2"></i>Welcome back, <strong>{{ Auth::user()->name }}</strong>. Monitor and manage the peer review lifecycle here.
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Submissions</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['total_submissions'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('coordinator.submissions.index') }}" class="text-primary small text-decoration-none">
                        View All <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Pending Assignments</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['pending_assignments'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('coordinator.submissions.index', ['status' => 'Submitted']) }}" class="text-warning small text-decoration-none">
                        View <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Active Reviews</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['active_reviews'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-info bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('coordinator.submissions.index', ['status' => 'Under Review']) }}" class="text-info small text-decoration-none">
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
                        <div class="text-muted small text-uppercase fw-bold mb-1">Completed Reviews</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $stats['completed_reviews'] }}</div>
                    </div>
                </div>
                <div class="card-footer bg-success bg-opacity-10 border-0 py-2 px-4">
                    <a href="{{ route('coordinator.submissions.index', ['status' => 'Accepted']) }}" class="text-success small text-decoration-none">
                        View Completed <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
            <h6 class="m-0 fw-bold text-white"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('coordinator.submissions.index') }}" class="btn btn-primary w-100 p-3 d-flex flex-column align-items-center gap-2 rounded-3 shadow-sm">
                        <i class="fas fa-users fa-xl"></i>
                        <span class="fw-bold">Assign Reviewers</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('coordinator.submissions.index', ['status' => 'Under Review']) }}" class="btn btn-outline-info w-100 p-3 d-flex flex-column align-items-center gap-2 rounded-3 shadow-sm">
                        <i class="fas fa-search fa-xl"></i>
                        <span class="fw-bold">Monitor Reviews</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('coordinator.submissions.index', ['status' => 'Submitted']) }}" class="btn btn-outline-warning w-100 p-3 d-flex flex-column align-items-center gap-2 rounded-3 shadow-sm">
                        <i class="fas fa-clock fa-xl"></i>
                        <span class="fw-bold">Pending Submissions</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.1) !important; }
</style>
@endpush
