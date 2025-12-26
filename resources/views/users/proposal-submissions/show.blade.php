@extends('layouts.Users.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Proposal Submission Details</h4>
        <div class="btn-group">
            <a href="{{ route('users.proposal-submissions.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Back to Submissions
            </a>
            <a href="{{ route('users.proposal-submissions.download', $proposalSubmission->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Download Document
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-file-signature me-2"></i>Proposal Information
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">{{ $proposalSubmission->research_title }}</h5>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Proponent Name</label>
                            <p class="mb-0">{{ $proposalSubmission->proponent_name }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Affiliation</label>
                            <p class="mb-0">{{ $proposalSubmission->affiliation }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0">{{ $proposalSubmission->email }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contact Number</label>
                            <p class="mb-0">{{ $proposalSubmission->contact_number }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Budget Requested</label>
                            <p class="mb-0 text-success fw-bold">₱{{ number_format($proposalSubmission->budget_requested, 2) }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Submission Date</label>
                            <p class="mb-0">{{ $proposalSubmission->date_submitted->format('M d, Y \a\t H:i') }}</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Call for Proposal</label>
                            <p class="mb-0">
                                <a href="{{ route('public.calls.show', $proposalSubmission->callForProposal->id) }}" target="_blank" class="text-decoration-none">
                                    {{ $proposalSubmission->callForProposal->title }}
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Information -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white fw-bold">
                    <i class="fas fa-file-upload me-2"></i>Submitted Document
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">{{ basename($proposalSubmission->proposal_document) }}</h6>
                            <small class="text-muted">
                                @php
                                    $filePath = storage_path('app/public/' . $proposalSubmission->proposal_document);
                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                @endphp
                                Size: {{ $fileSize > 0 ? round($fileSize / 1024, 2) . ' KB' : 'Unknown' }}
                            </small>
                        </div>
                        <a href="{{ route('users.proposal-submissions.download', $proposalSubmission->id) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Remarks Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-{{ $proposalSubmission->status === 'Submitted' ? 'primary' : ($proposalSubmission->status === 'Under Review' ? 'warning' : ($proposalSubmission->status === 'Accepted' ? 'success' : 'danger')) }} text-white fw-bold">
                    <i class="fas fa-info-circle me-2"></i>Submission Status
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge fs-6 px-3 py-2 bg-{{ $proposalSubmission->status === 'Submitted' ? 'primary' : ($proposalSubmission->status === 'Under Review' ? 'warning' : ($proposalSubmission->status === 'Accepted' ? 'success' : 'danger')) }}">
                            {{ $proposalSubmission->status }}
                        </span>
                    </div>

                    @if($proposalSubmission->status === 'Submitted')
                        <p class="text-muted small mb-0">
                            <i class="fas fa-clock me-1"></i>
                            Your proposal is being reviewed by the committee.
                        </p>
                    @elseif($proposalSubmission->status === 'Under Review')
                        <p class="text-warning small mb-0">
                            <i class="fas fa-search me-1"></i>
                            Currently under review by evaluators.
                        </p>
                    @elseif($proposalSubmission->status === 'Accepted')
                        <p class="text-success small mb-0">
                            <i class="fas fa-check-circle me-1"></i>
                            Congratulations! Your proposal has been accepted.
                        </p>
                    @elseif($proposalSubmission->status === 'Rejected')
                        <p class="text-danger small mb-0">
                            <i class="fas fa-times-circle me-1"></i>
                            Your proposal was not selected for funding.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Remarks Card (if any) -->
            @if($proposalSubmission->remarks)
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white fw-bold">
                        <i class="fas fa-comment me-2"></i>Remarks/Feedback
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($proposalSubmission->remarks)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.proposal-submissions.download', $proposalSubmission->id) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download Document
                        </a>
                        <a href="{{ route('users.proposal-submissions.create') }}"
                           class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Submit Another Proposal
                        </a>
                        <a href="{{ route('users.proposal-submissions.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>View All Submissions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
