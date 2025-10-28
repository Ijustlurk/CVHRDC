@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <h4 class="mb-0">Edit Call for Proposals</h4>
            <div class="alert alert-warning mb-0 py-2 px-3">
                <i class="fas fa-edit me-2"></i>Editing: {{ $call->title }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('rmc.calls.show', $call->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Details
            </a>
            <a href="{{ route('rmc.calls.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list me-2"></i>All Calls
            </a>
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

    <div class="card shadow">
        <div class="card-header bg-warning bg-gradient text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-edit me-2"></i>Edit Call for Proposals</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rmc.calls.update', $call->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmCallUpdate(event)">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $call->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $call->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="research_priority_area" class="form-label">Research Priority Area <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('research_priority_area') is-invalid @enderror" id="research_priority_area" name="research_priority_area" value="{{ old('research_priority_area', $call->research_priority_area) }}" required>
                            @error('research_priority_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $call->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $call->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="funding_limit" class="form-label">Funding Limit (₱) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('funding_limit') is-invalid @enderror" id="funding_limit" name="funding_limit" step="0.01" min="0" value="{{ old('funding_limit', $call->funding_limit) }}" required>
                                    @error('funding_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                                            {{ $call->status }}
                                        </span>
                                        <small class="text-muted d-block">Managed by RUC during review process</small>
                                    </div>
                                    <input type="hidden" name="status" value="{{ $call->status }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="eligibility" class="form-label">Eligibility Criteria <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('eligibility') is-invalid @enderror" id="eligibility" name="eligibility" rows="3" required>{{ old('eligibility', $call->eligibility) }}</textarea>
                            @error('eligibility')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="guidelines" class="form-label">Guidelines <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('guidelines') is-invalid @enderror" id="guidelines" name="guidelines" rows="3" required>{{ old('guidelines', $call->guidelines) }}</textarea>
                            @error('guidelines')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proposal_format_file" class="form-label">Proposal Format File</label>
                            <input type="file" class="form-control @error('proposal_format_file') is-invalid @enderror" id="proposal_format_file" name="proposal_format_file" accept=".pdf,.doc,.docx">
                            <div class="form-text">
                                Current file: {{ $call->proposal_format_file ? basename($call->proposal_format_file) : 'No file uploaded' }}
                                <br>Upload a new file to replace the current one (max 10MB)
                            </div>
                            @error('proposal_format_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Visibility Status</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-{{ $call->visibility_status === 'Public' ? 'success' : 'secondary' }}">
                                    {{ $call->visibility_status }}
                                </span>
                                <small class="text-muted d-block">Managed by RUC during review process</small>
                            </div>
                            <input type="hidden" name="visibility_status" value="{{ $call->visibility_status }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Update Call for Proposals
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for start_date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').setAttribute('min', today);

    // Update end_date minimum when start_date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        document.getElementById('end_date').setAttribute('min', startDate);
        if (document.getElementById('end_date').value < startDate) {
            document.getElementById('end_date').value = startDate;
        }
    });
});

// SweetAlert confirmation for call update
function confirmCallUpdate(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Update Call for Proposals?',
        html: '<p class="mb-0">This will update the call details. The changes will be saved immediately.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Yes, Update Call',
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
                title: 'Updating Call...',
                html: '<p class="mb-0">Please wait while we update your call for proposals.</p>',
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