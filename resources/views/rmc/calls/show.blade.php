@extends('layouts.RMC.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <!-- Success Messages -->
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
            Call for Proposals Details
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
                            <a href="{{ route('rmc.calls.download-format', $call->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
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
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-cogs me-2"></i>Actions</h6>
                </div>
                <div class="card-body">
                    @if($call->created_by === Auth::id())
                        <div class="d-grid gap-2">
                            @if($call->visibility_status !== 'Public')
                                <a href="{{ route('rmc.calls.edit', $call->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Call
                                </a>

                                <form method="POST" action="{{ route('rmc.calls.destroy', $call->id) }}" onsubmit="return confirmCallDeletion(event, '{{ $call->title }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Delete Call
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>This call has been posted publicly and cannot be edited or deleted.
                                </div>
                            @endif

                            <a href="{{ route('rmc.submissions.index', $call->id) }}" class="btn btn-info">
                                <i class="fas fa-file-alt me-2"></i>View Submissions
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>You can only manage calls that you created.
                        </div>
                    @endif
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
                            <th>Actions</th>
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
                            <td>
                                <a href="{{ route('rmc.submissions.show', [$call->id, $submission->id]) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($call->proposalSubmissions->count() > 5)
            <div class="text-center mt-3">
                <a href="{{ route('rmc.submissions.index', $call->id) }}" class="btn btn-outline-primary">
                    View All Submissions ({{ $call->proposalSubmissions->count() }})
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
// SweetAlert confirmation for call deletion
function confirmCallDeletion(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Delete Call for Proposals?',
        html: `<p class="mb-0">Are you sure you want to delete "<strong>${callTitle}</strong>"? This action cannot be undone and will permanently remove all associated data.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Yes, Delete Call',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-danger btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting Call...',
                html: '<p class="mb-0">Please wait while we delete the call for proposals.</p>',
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

.swal-wide {
    width: 35rem !important;
    max-width: 90vw !important;
}
</style>
@endsection