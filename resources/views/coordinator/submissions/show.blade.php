@extends('layouts.Coordinator.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Submission Details</h4>
        <a href="{{ route('coordinator.submissions.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
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
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Research Information</h6>
                </div>
                <div class="card-body">
                    <h5 class="text-primary mb-4">{{ $submission->research_title }}</h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Proponent</label>
                            <p class="mb-0 fw-bold">{{ $submission->proponent_name }}</p>
                            <small class="text-muted">{{ $submission->affiliation }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Email & Contact</label>
                            <p class="mb-0"><i class="fas fa-envelope me-2 text-muted"></i>{{ $submission->email }}</p>
                            <p class="mb-0"><i class="fas fa-phone me-2 text-muted"></i>{{ $submission->contact_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Call for Proposal</label>
                            <p class="mb-0">{{ $submission->callForProposal->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Budget Requested</label>
                            <p class="mb-0 text-success fw-bold">₱{{ number_format($submission->budget_requested, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Date Submitted</label>
                            <p class="mb-0">{{ $submission->date_submitted->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Current Status</label>
                            <span class="badge bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }}">
                                {{ $submission->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-file-pdf me-2"></i>Submitted Document</h6>
                </div>
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-3 rounded me-3">
                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-dark fw-bold">Review the full proposal document</h6>
                            <p class="text-muted small mb-0">PDF, DOC, or DOCX format</p>
                        </div>
                        <div class="ms-auto">
                             <a href="{{ route('coordinator.submissions.download', $submission->id) }}" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Download File
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-users-cog me-2"></i>Review Assignments</h6>
                </div>
                <div class="card-body">
                    @if($submission->peerReviews->count() > 0)
                        <div class="list-group list-group-flush mb-3">
                            @foreach($submission->peerReviews as $review)
                                <div class="list-group-item px-0 border-0 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-sm bg-primary-soft text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(13, 110, 253, 0.1);">
                                            <i class="fas fa-user-edit small"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block">{{ $review->reviewer->name }}</span>
                                            <span class="badge bg-{{ $review->status === 'Completed' ? 'success' : ($review->status === 'Under Review' ? 'info' : ($review->status === 'Pending' ? 'warning' : 'secondary')) }} rounded-pill x-small">
                                                {{ $review->status }}
                                            </span>
                                        </div>
                                        <div class="ms-auto d-flex align-items-center">
                                            <form action="{{ route('coordinator.submissions.unassign', [$submission->id, $review->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this reviewer?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove Reviewer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="ps-5 small text-muted">
                                        Assigned: {{ $review->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded mb-3">
                            <i class="fas fa-user-slash text-muted mb-2 d-block"></i>
                            <p class="text-muted small mb-0">No reviewers assigned to this submission yet.</p>
                        </div>
                    @endif

                    @if($submission->peerReviews->count() === 0)
                        <div class="mb-4 bg-light p-3 rounded border" id="preAssignmentChecklist">
                            <h6 class="small fw-bold text-uppercase text-muted mb-3"><i class="fas fa-clipboard-list me-2"></i>Pre-Assignment Checklist</h6>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input checklist-item" type="checkbox" id="check1">
                                <label class="form-check-label small" for="check1">
                                    <span class="fw-bold d-block">1. Integrity & Anonymity</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Is the manuscript technically sound and properly anonymized? (Names/Affiliations removed, files OK, metadata cleared)</span>
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input checklist-item" type="checkbox" id="check2">
                                <label class="form-check-label small" for="check2">
                                    <span class="fw-bold d-block">2. Scope & Ethics</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Does this submission meet scope and ethical standards? (Topic fit, plagiarism score OK, ethics approvals cited)</span>
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input checklist-item" type="checkbox" id="check3">
                                <label class="form-check-label small" for="check3">
                                    <span class="fw-bold d-block">3. Structural Completeness</span>
                                    <span class="text-muted" style="font-size: 0.75rem;">Are all essential submission components present? (Abstract, figures/tables, and full reference list included)</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <button class="btn btn-primary w-100 {{ $submission->peerReviews->count() === 0 ? 'disabled' : '' }}" 
                        id="assignReviewerBtn" data-bs-toggle="modal" data-bs-target="#assignModal" 
                        data-submission-id="{{ $submission->id }}" data-submission-title="{{ $submission->research_title }}"
                        {{ $submission->peerReviews->count() === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-{{ $submission->peerReviews->count() > 0 ? 'user-plus' : 'plus' }} me-2"></i>
                        {{ $submission->peerReviews->count() > 0 ? 'Assign Another Reviewer' : 'Assign Reviewer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checklist-item');
        const assignBtn = document.getElementById('assignReviewerBtn');

        if (checkboxes.length > 0) {
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    if (allChecked) {
                        assignBtn.classList.remove('disabled');
                        assignBtn.removeAttribute('disabled');
                    } else {
                        assignBtn.classList.add('disabled');
                        assignBtn.setAttribute('disabled', 'disabled');
                    }
                });
            });
        }
    });
</script>
@endpush

@include('coordinator.submissions.partials.assign-modal')
@endsection
