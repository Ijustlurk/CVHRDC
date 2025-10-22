@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Calls for Proposals Management</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Create and manage calls for proposals for research funding opportunities.
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

    <!-- Calls Management Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link{{ request('tab', 'all-calls') === 'all-calls' ? ' active' : '' }}"
               href="{{ route('rmc.calls.index', ['tab' => 'all-calls']) }}">
                <i class="fas fa-list me-2"></i>All Calls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'my-calls' ? ' active' : '' }}"
               href="{{ route('rmc.calls.index', ['tab' => 'my-calls']) }}">
                <i class="fas fa-user me-2"></i>My Calls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'create-call' ? ' active' : '' }}"
               href="{{ route('rmc.calls.index', ['tab' => 'create-call']) }}">
                <i class="fas fa-plus me-2"></i>Create New Call
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Visibility</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Funding Limit</th>
                                    <th>Submissions</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($calls as $call)
                                <tr>
                                    <td>
                                        <strong>{{ $call->title }}</strong>
                                        <br><small class="text-muted">by {{ $call->creator->name }}</small>
                                    </td>
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
                                    <td>₱{{ number_format($call->funding_limit, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('rmc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">View</a>
                                            @if($call->created_by === Auth::id())
                                                @if($call->visibility_status !== 'Public')
                                                    <a href="{{ route('rmc.calls.edit', $call->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form method="POST" action="{{ route('rmc.calls.destroy', $call->id) }}" class="d-inline ms-1" onsubmit="return confirmCallDeletion(event, '{{ addslashes($call->title) }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-warning text-dark">Posted Publicly</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No calls for proposals found. Create your first call to get started!</p>
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

        <!-- My Calls Tab -->
        <div class="tab-pane fade{{ request('tab') === 'my-calls' ? ' show active' : '' }}" id="my-calls">
            <div class="card shadow">
                <div class="card-header bg-success bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-user me-2"></i>My Calls for Proposals</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Visibility</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Funding Limit</th>
                                    <th>Submissions</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($calls as $call)
                                <tr>
                                    <td>
                                        <strong>{{ $call->title }}</strong>
                                    </td>
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
                                    <td>₱{{ number_format($call->funding_limit, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $call->proposalSubmissions->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('rmc.calls.show', $call->id) }}" class="btn btn-sm btn-primary">View</a>
                                            @if($call->visibility_status !== 'Public')
                                                <a href="{{ route('rmc.calls.edit', $call->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form method="POST" action="{{ route('rmc.calls.destroy', $call->id) }}" class="d-inline ms-1" onsubmit="return confirmCallDeletion(event, '{{ addslashes($call->title) }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @else
                                                <span class="badge bg-warning text-dark">Posted Publicly</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-user fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">You haven't created any calls yet. Create your first call to get started!</p>
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

        <!-- Create Call Tab -->
        <div class="tab-pane fade{{ request('tab') === 'create-call' ? ' show active' : '' }}" id="create-call">
            @include('rmc.calls.create')
        </div>
    </div>
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