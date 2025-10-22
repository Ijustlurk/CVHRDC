@extends('layouts.RUC.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Review Calls for Proposals</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Review and manage the visibility of calls for proposals created by RMC.
        </div>
    </div>

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

    <!-- Review Calls Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link{{ request('tab', 'all-calls') === 'all-calls' ? ' active' : '' }}"
               href="{{ route('ruc.calls.index', ['tab' => 'all-calls']) }}">
                <i class="fas fa-list me-2"></i>All Calls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'for-review' ? ' active' : '' }}"
               href="{{ route('ruc.calls.index', ['tab' => 'for-review']) }}">
                <i class="fas fa-search me-2"></i>For Review
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'published' ? ' active' : '' }}"
               href="{{ route('ruc.calls.index', ['tab' => 'published']) }}">
                <i class="fas fa-eye me-2"></i>Published
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- All Calls Tab -->
        <div class="tab-pane fade{{ request('tab', 'all-calls') === 'all-calls' ? ' show active' : '' }}" id="all-calls">
            <div class="card shadow">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-bullhorn me-2"></i>All Calls for Proposals</h6>
                        <span class="badge bg-light text-dark">{{ $calls->total() }} Total Calls</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Visibility</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Submissions</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($calls as $call)
                                <tr>
                                    <td>
                                        <strong>{{ $call->title }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($call->description, 50) }}</small>
                                    </td>
                                    <td>{{ $call->creator->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                                            {{ $call->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $call->visibility_status === 'Public' ? 'success' : 'secondary' }}">
                                            {{ $call->visibility_status }}
                                        </span>
                                    </td>
                                    <td>{{ $call->start_date->format('M d, Y') }}</td>
                                    <td>{{ $call->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('ruc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">View</a>

                                            @if($call->status === 'For Review' || $call->status === 'Draft')
                                            <!-- Approve and Publish -->
                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmApproveCall(event, '{{ addslashes($call->title) }}')">
                                                @csrf
                                                <input type="hidden" name="status" value="Open">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                            @elseif($call->status === 'Open' && $call->visibility_status === 'Public')
                                            <!-- Withdraw/Close -->
                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmWithdrawCall(event, '{{ addslashes($call->title) }}')">
                                                @csrf
                                                <input type="hidden" name="status" value="Closed">
                                                <input type="hidden" name="action" value="withdraw">
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-times me-1"></i>Withdraw
                                                </button>
                                            </form>
                                            @elseif($call->visibility_status === 'Public')
                                            <!-- Make Private -->
                                            <form method="POST" action="{{ route('ruc.calls.visibility', $call->id) }}" class="d-inline" onsubmit="return confirmUnpostCall(event, '{{ addslashes($call->title) }}')">
                                                @csrf
                                                <input type="hidden" name="visibility_status" value="Private">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye-slash"></i> Unpost
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No calls for proposals found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ $calls->links() }}</div>
                </div>
            </div>
        </div>

        <!-- For Review Tab -->
        <div class="tab-pane fade{{ request('tab') === 'for-review' ? ' show active' : '' }}" id="for-review">
            <div class="card shadow">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-search me-2"></i>Calls Pending Review</h6>
                        <span class="badge bg-light text-dark">{{ $calls->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Submissions</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($calls as $call)
                                <tr>
                                    <td>
                                        <strong>{{ $call->title }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($call->description, 50) }}</small>
                                    </td>
                                    <td>{{ $call->creator->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $call->status === 'For Review' ? 'info' : 'warning' }}">
                                            {{ $call->status }}
                                        </span>
                                    </td>
                                    <td>{{ $call->start_date->format('M d, Y') }}</td>
                                    <td>{{ $call->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('ruc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">Review</a>

                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmApproveCall(event, '{{ addslashes($call->title) }}')">
                                                @csrf
                                                <input type="hidden" name="status" value="Open">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No calls pending review at the moment.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ $calls->links() }}</div>
                </div>
            </div>
        </div>

        <!-- Published Tab -->
        <div class="tab-pane fade{{ request('tab') === 'published' ? ' show active' : '' }}" id="published">
            <div class="card shadow">
                <div class="card-header bg-success bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-eye me-2"></i>Published Calls</h6>
                        <span class="badge bg-light text-dark">{{ $calls->total() }} Published</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Published Date</th>
                                    <th>End Date</th>
                                    <th>Submissions</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($calls as $call)
                                <tr>
                                    <td>
                                        <strong>{{ $call->title }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($call->description, 50) }}</small>
                                    </td>
                                    <td>{{ $call->creator->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $call->status === 'Open' ? 'success' : 'warning' }}">
                                            {{ $call->status }}
                                        </span>
                                    </td>
                                    <td>{{ $call->updated_at->format('M d, Y') }}</td>
                                    <td>{{ $call->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('ruc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">View</a>
                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline" onsubmit="return confirmWithdrawCall(event, '{{ addslashes($call->title) }}')">
                                                @csrf
                                                <input type="hidden" name="status" value="Closed">
                                                <input type="hidden" name="action" value="withdraw">
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-times me-1"></i>Withdraw
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-eye fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No published calls at the moment.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">{{ $calls->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// SweetAlert confirmation for approving a call
function confirmApproveCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Approve & Publish Call?',
        html: `<p class="mb-0">Are you sure you want to approve and publish "<strong>${callTitle}</strong>"? This will make it visible to the public.</p>`,
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
        html: `<p class="mb-0">Are you sure you want to withdraw "<strong>${callTitle}</strong>"? This will close the call and remove it from public view.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-times-circle me-2"></i>Yes, Withdraw Call',
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

// SweetAlert confirmation for unposting a call
function confirmUnpostCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Unpost Call from Public View?',
        html: `<p class="mb-0">Are you sure you want to unpost "<strong>${callTitle}</strong>"? This will remove it from public view but keep it active internally.</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-eye-slash me-2"></i>Yes, Unpost Call',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-secondary btn-lg px-4 me-2',
            cancelButton: 'btn btn-outline-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Unposting Call...',
                html: '<p class="mb-0">Please wait while we remove the call from public view.</p>',
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