@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Review Assignments</h4>
        <div class="alert alert-primary mb-0 py-2 px-3 border-0 shadow-sm">
            <i class="fas fa-clipboard-check me-2"></i>Manage and respond to your active peer review requests.
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-list-ul me-2"></i>Assigned Submissions</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted text-uppercase small" style="font-size: 0.75rem;">
                                <tr>
                                    <th class="px-4 py-3">Submission Details</th>
                                    <th class="py-3">Type</th>
                                    <th class="py-3 text-center">Your Status</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-dark">{{ $assignment->submission->research_title }}</div>
                                        <div class="small text-muted">
                                            Assigned on: {{ $assignment->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-primary border">Proposal</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($assignment->status === 'Pending')
                                            <span class="badge rounded-pill bg-warning text-dark px-3 mt-1">
                                                <i class="fas fa-clock me-1"></i>Awaiting Response
                                            </span>
                                        @elseif($assignment->status === 'Under Review')
                                            <span class="badge rounded-pill bg-info text-white px-3 mt-1">
                                                <i class="fas fa-search me-1"></i>Active Review
                                            </span>
                                        @elseif($assignment->status === 'Completed')
                                            <span class="badge rounded-pill bg-success px-3 mt-1">
                                                <i class="fas fa-check-circle me-1"></i>Completed
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary px-3 mt-1">
                                                {{ $assignment->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($assignment->status === 'Pending')
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('reviewer.reviews.accept', $assignment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success shadow-sm">
                                                        <i class="fas fa-check me-1"></i>Agree to Review
                                                    </button>
                                                </form>
                                                <form action="{{ route('reviewer.reviews.decline', $assignment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                                                        <i class="fas fa-times me-1"></i>Decline
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small italic">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="opacity-50 mb-3">
                                            <i class="fas fa-folder-open fa-3x"></i>
                                        </div>
                                        <h6 class="text-muted">No review assignments found.</h6>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
