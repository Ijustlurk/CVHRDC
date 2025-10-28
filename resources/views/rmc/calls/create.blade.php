<div class="card shadow">
    <div class="card-header bg-success bg-gradient text-white py-3">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-plus me-2"></i>Create New Call for Proposals</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('rmc.calls.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmCallCreation(event)">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="research_priority_area" class="form-label">Research Priority Area <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('research_priority_area') is-invalid @enderror" id="research_priority_area" name="research_priority_area" value="{{ old('research_priority_area') }}" required>
                        @error('research_priority_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
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
                                <input type="number" class="form-control @error('funding_limit') is-invalid @enderror" id="funding_limit" name="funding_limit" step="0.01" min="0" value="{{ old('funding_limit') }}" required>
                                @error('funding_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-info">For Review</span>
                                    <small class="text-muted d-block">Automatically set when call is created</small>
                                </div>
                                <input type="hidden" name="status" value="For Review">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="eligibility" class="form-label">Eligibility Criteria <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('eligibility') is-invalid @enderror" id="eligibility" name="eligibility" rows="3" required>{{ old('eligibility') }}</textarea>
                        @error('eligibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="guidelines" class="form-label">Guidelines <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('guidelines') is-invalid @enderror" id="guidelines" name="guidelines" rows="3" required>{{ old('guidelines') }}</textarea>
                        @error('guidelines')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="proposal_format_file" class="form-label">Proposal Format File</label>
                        <input type="file" class="form-control @error('proposal_format_file') is-invalid @enderror" id="proposal_format_file" name="proposal_format_file" accept=".pdf,.doc,.docx">
                        <div class="form-text">Upload PDF, DOC, or DOCX file (max 10MB)</div>
                        @error('proposal_format_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Visibility Status</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-secondary">Private</span>
                            <small class="text-muted d-block">Automatically set when call is created</small>
                        </div>
                        <input type="hidden" name="visibility_status" value="Private">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Create Call for Proposals
                </button>
            </div>
        </form>
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

// SweetAlert confirmation for call creation
function confirmCallCreation(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Create Call for Proposals?',
        html: '<p class="mb-0">This call will be submitted for review and will be visible to the Research Utilization Committee.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Yes, Create Call',
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
                title: 'Creating Call...',
                html: '<p class="mb-0">Please wait while we create your call for proposals.</p>',
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