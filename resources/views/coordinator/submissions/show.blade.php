@extends('layouts.Coordinator.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <h4 class="mb-0 fw-bold">Submission Details</h4>
        </div>
        <a href="{{ route('coordinator.submissions.index') }}" class="btn btn-outline-secondary rounded-3 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Research Info Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-info-circle me-2"></i>Research Information</h6>
                </div>
                <div class="card-body p-4">
                    <h5 class="text-primary fw-bold mb-4">{{ $submission->research_title }}</h5>

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
                            @php
                                $statusColors = [
                                    'Submitted'    => 'info',
                                    'Under Review' => 'warning',
                                    'Accepted'     => 'success',
                                    'Rejected'     => 'danger',
                                ];
                                $sc = $statusColors[$submission->status] ?? 'secondary';
                            @endphp
                            <span class="badge rounded-pill bg-{{ $sc }} px-3 py-2">{{ $submission->status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Document Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #0dcaf0 0%, #0baac7 100%);">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-file-pdf me-2"></i>Submitted Document</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width:56px;height:56px;flex-shrink:0;">
                            <i class="fas fa-file-pdf fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold text-dark">Review the full proposal document</h6>
                            <p class="text-muted small mb-0">PDF, DOC, or DOCX format</p>
                        </div>
                        <a href="{{ route('coordinator.submissions.download', $submission->id) }}" class="btn btn-primary rounded-3 shadow-sm flex-shrink-0">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #343a40 0%, #212529 100%);">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-users-cog me-2"></i>Review Assignments</h6>
                </div>
                <div class="card-body p-3">
                    @if($submission->peerReviews->count() > 0)
                        <div class="mb-3">
                            @foreach($submission->peerReviews as $review)
                                <div class="d-flex align-items-center gap-2 p-3 rounded-3 bg-light mb-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                                        <i class="fas fa-user-edit small"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <span class="fw-bold d-block text-dark" style="font-size:0.85rem;">{{ $review->reviewer->name }}</span>
                                        @php
                                            $rc = ['Completed' => 'success', 'Under Review' => 'info', 'Pending' => 'warning', 'Cancelled' => 'secondary'];
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ $rc[$review->status] ?? 'secondary' }} mt-1" style="font-size:0.65rem;">{{ $review->status }}</span>
                                        <div class="text-muted mt-1" style="font-size:0.72rem;">Assigned {{ $review->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <form action="{{ route('coordinator.submissions.unassign', [$submission->id, $review->id]) }}" method="POST" onsubmit="return confirm('Remove this reviewer?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove Reviewer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 mb-3">
                            <div class="rounded-circle bg-secondary bg-opacity-10 text-muted d-flex align-items-center justify-content-center mx-auto mb-3" style="width:52px;height:52px;">
                                <i class="fas fa-user-slash fa-lg"></i>
                            </div>
                            <p class="text-muted small mb-0">No reviewers assigned yet.</p>
                        </div>
                    @endif

                    {{-- Pre-Assignment Checklist --}}
                    @if($submission->peerReviews->count() === 0)
                        <div class="mb-3 p-3 rounded-3 border bg-light" id="preAssignmentChecklist">
                            <h6 class="small fw-bold text-uppercase text-muted mb-3">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>Pre-Assignment Checklist
                            </h6>

                            <div class="form-check mb-2">
                                <input class="form-check-input checklist-item" type="checkbox" id="check1">
                                <label class="form-check-label small" for="check1">
                                    <span class="fw-bold d-block">1. Integrity & Anonymity</span>
                                    <span class="text-muted" style="font-size: 0.72rem;">Manuscript anonymized, metadata cleared, files intact.</span>
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input checklist-item" type="checkbox" id="check2">
                                <label class="form-check-label small" for="check2">
                                    <span class="fw-bold d-block">2. Scope & Ethics</span>
                                    <span class="text-muted" style="font-size: 0.72rem;">Topic fit, plagiarism score acceptable, ethics approvals cited.</span>
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input checklist-item" type="checkbox" id="check3">
                                <label class="form-check-label small" for="check3">
                                    <span class="fw-bold d-block">3. Structural Completeness</span>
                                    <span class="text-muted" style="font-size: 0.72rem;">Abstract, figures/tables, and references all present.</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <button class="btn btn-primary w-100 rounded-3 shadow-sm {{ $submission->peerReviews->count() === 0 ? 'disabled' : '' }}"
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
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    allChecked ? (assignBtn.classList.remove('disabled'), assignBtn.removeAttribute('disabled'))
                               : (assignBtn.classList.add('disabled'), assignBtn.setAttribute('disabled', 'disabled'));
                });
            });
        }
    });
</script>
@endpush

@include('coordinator.submissions.partials.assign-modal')
@endsection

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .card { transition: box-shadow 0.2s ease; }
</style>
@endpush
