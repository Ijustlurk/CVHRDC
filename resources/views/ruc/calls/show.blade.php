@extends('layouts.RUC.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                html: '<p class="mb-0">{{ session('success') }}</p>',
                icon: 'success',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                customClass: {
                    popup: 'swal-wide',
                    confirmButton: 'btn btn-success btn-lg px-4'
                },
                buttonsStyling: false
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                html: '<p class="mb-0">{{ session('error') }}</p>',
                icon: 'error',
                confirmButtonText: '<i class="fas fa-times me-2"></i>OK',
                customClass: {
                    popup: 'swal-wide',
                    confirmButton: 'btn btn-danger btn-lg px-4'
                },
                buttonsStyling: false
            });
        });
    </script>
    @endif

    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">{{ $call->title }}</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Review Call for Proposals Details
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Call Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Research Priority Area:</strong>
                            <p>{{ $call->research_priority_area }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Funding Limit:</strong>
                            <p>₱{{ number_format($call->funding_limit, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Start Date:</strong>
                            <p>{{ $call->start_date->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>End Date:</strong>
                            <p>{{ $call->end_date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                                    {{ $call->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Visibility:</strong>
                            <p>
                                <span class="badge bg-{{ $call->visibility_status === 'Public' ? 'success' : 'secondary' }}">
                                    {{ $call->visibility_status }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($call->description)) !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Eligibility Criteria:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($call->eligibility)) !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Guidelines:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($call->guidelines)) !!}
                        </div>
                    </div>

                    @if($call->proposal_format_file)
                    <div class="mb-3">
                        <strong>Proposal Format File:</strong>
                        <div class="mt-2">
                            <a href="{{ route('ruc.calls.download-format', $call->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Format File
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Created by:</strong>
                        <p>{{ $call->creator->name }} on {{ $call->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>

                    @if($call->approved_by)
                    <div class="mb-3">
                        <strong>Approved by:</strong>
                        <p>{{ $call->approver->name }} on {{ $call->updated_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-primary">{{ $call->proposalSubmissions->count() }}</h3>
                        <p class="mb-0">Total Submissions</p>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success">{{ $call->proposalSubmissions->where('status', 'Accepted')->count() }}</h5>
                            <small class="text-muted">Accepted</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-danger">{{ $call->proposalSubmissions->where('status', 'Rejected')->count() }}</h5>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>

                    <div class="row text-center mt-2">
                        <div class="col-6">
                            <h5 class="text-warning">{{ $call->proposalSubmissions->where('status', 'Under Review')->count() }}</h5>
                            <small class="text-muted">Under Review</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-info">{{ $call->proposalSubmissions->where('status', 'Submitted')->count() }}</h5>
                            <small class="text-muted">Submitted</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header bg-warning bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-cogs me-2"></i>RUC Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($call->status === 'For Review' || $call->status === 'Draft')
                        <!-- Approve and Publish -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" onsubmit="return confirmApproveCall(event, '{{ addslashes($call->title) }}')">
                            @csrf
                            <input type="hidden" name="status" value="Open">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-2"></i>Approve & Publish Call
                            </button>
                        </form>
                        @endif

                        @if($call->status === 'Open' && $call->visibility_status === 'Public')
                        <!-- Withdraw/Close -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" onsubmit="return confirmWithdrawCall(event, '{{ addslashes($call->title) }}')">
                            @csrf
                            <input type="hidden" name="status" value="Closed">
                            <input type="hidden" name="action" value="withdraw">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-times-circle me-2"></i>Withdraw & Close Call
                            </button>
                        </form>
                        @endif

                        @if($call->status === 'Closed' && $call->visibility_status === 'Public')
                        <!-- Reopen for Review -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" onsubmit="return confirmReopenCall(event, '{{ addslashes($call->title) }}')">
                            @csrf
                            <input type="hidden" name="status" value="For Review">
                            <input type="hidden" name="action" value="reopen">
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-redo me-2"></i>Reopen for Review
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('ruc.calls.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to All Calls
                        </a>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>As RUC, you can control visibility but cannot edit the call content.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($call->proposalSubmissions->count() > 0)
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary bg-gradient text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>Recent Submissions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Research Title</th>
                            <th>Proponent</th>
                            <th>Email</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Budget Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($call->proposalSubmissions->take(5) as $submission)
                        <tr>
                            <td>{{ Str::limit($submission->research_title, 50) }}</td>
                            <td>{{ $submission->proponent_name }}</td>
                            <td>{{ $submission->email }}</td>
                            <td>{{ $submission->date_submitted->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }}">
                                    {{ $submission->status }}
                                </span>
                            </td>
                            <td>₱{{ number_format($submission->budget_requested, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($call->proposalSubmissions->count() > 5)
            <div class="text-center mt-3">
                <small class="text-muted">{{ $call->proposalSubmissions->count() }} total submissions for this call</small>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
// SweetAlert confirmation for approving a call
function confirmApproveCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Approve & Publish Call?',
        html: `<p class="mb-0">Are you sure you want to approve and publish "<strong>${callTitle}</strong>"? This will make it visible to the public and change the status to Open.</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Yes, Approve & Publish',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-success btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Approving Call...',
                html: '<p class="mb-0">Please wait while we approve and publish the call.</p>',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide'
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            event.target.submit();
        }
    });

    return false;
}

// SweetAlert confirmation for withdrawing a call
function confirmWithdrawCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Withdraw & Close Call?',
        html: `<p class="mb-0">Are you sure you want to withdraw "<strong>${callTitle}</strong>"? This will close the call but maintain its public visibility, signaling that the call has ended.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-times-circle me-2"></i>Yes, Withdraw & Close',
        cancelButtonText: '<i class="fas fa-ban me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-warning btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Withdrawing Call...',
                html: '<p class="mb-0">Please wait while we withdraw and close the call.</p>',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide'
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            event.target.submit();
        }
    });

    return false;
}

// SweetAlert confirmation for reopening a call for review
function confirmReopenCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Reopen Call for Review?',
        html: `<p class="mb-0">Are you sure you want to reopen "<strong>${callTitle}</strong>" for review? This will set the status back to "For Review" and visibility to "Private."</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-redo me-2"></i>Yes, Reopen for Review',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-info btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Reopening Call...',
                html: '<p class="mb-0">Please wait while we reopen the call for review.</p>',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide'
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            event.target.submit();
        }
    });

    return false;
}
</script>

<style>
/* Enhanced SweetAlert Styling */
.swal2-popup {
    font-size: 1rem !important;
    padding: 2rem !important;
}

.swal2-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    margin-bottom: 1rem !important;
}

.swal2-html-container {
    font-size: 1rem !important;
    color: #5a6c7d !important;
    margin: 1rem 0 !important;
}

.swal2-actions {
    margin-top: 1.5rem !important;
    gap: 0.5rem !important;
}

/* Primary Button Styling */
.swal2-popup .btn-primary {
    background-color: #007bff !important;
    border: 2px solid #007bff !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3) !important;
}

.swal2-popup .btn-primary:hover {
    background-color: #0056b3 !important;
    border-color: #004085 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.4) !important;
}

.swal2-popup .btn-primary:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5) !important;
}

/* Success Button Styling */
.swal2-popup .btn-success {
    background-color: #28a745 !important;
    border: 2px solid #28a745 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3) !important;
}

.swal2-popup .btn-success:hover {
    background-color: #218838 !important;
    border-color: #1e7e34 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(40, 167, 69, 0.4) !important;
}

.swal2-popup .btn-success:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
}

/* Warning Button Styling */
.swal2-popup .btn-warning {
    background-color: #ffc107 !important;
    border: 2px solid #ffc107 !important;
    color: #212529 !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(255, 193, 7, 0.3) !important;
}

.swal2-popup .btn-warning:hover {
    background-color: #e0a800 !important;
    border-color: #d39e00 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(255, 193, 7, 0.4) !important;
}

.swal2-popup .btn-warning:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.5) !important;
}

/* Danger Button Styling */
.swal2-popup .btn-danger {
    background-color: #dc3545 !important;
    border: 2px solid #dc3545 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3) !important;
}

.swal2-popup .btn-danger:hover {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4) !important;
}

.swal2-popup .btn-danger:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5) !important;
}

/* Secondary/Cancel Button Styling */
.swal2-popup .btn-secondary {
    background-color: #6c757d !important;
    border: 2px solid #6c757d !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(108, 117, 125, 0.3) !important;
}

.swal2-popup .btn-secondary:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(108, 117, 125, 0.4) !important;
}

.swal2-popup .btn-secondary:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
}

/* Outline Secondary Button Styling */
.swal2-popup .btn-outline-secondary {
    background-color: transparent !important;
    border: 2px solid #6c757d !important;
    color: #6c757d !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(108, 117, 125, 0.2) !important;
}

.swal2-popup .btn-outline-secondary:hover {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3) !important;
}

.swal2-popup .btn-outline-secondary:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
}

.swal-wide {
    width: 35rem !important;
    max-width: 90vw !important;
}
</style>
@endsection