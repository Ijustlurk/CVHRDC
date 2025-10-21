<div class="card shadow">
    <div class="card-header bg-success bg-gradient text-white py-3">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-plus me-2"></i>Create New Call for Proposals</h6>
    </div>
    <div class="card-body">
        <form data-ajax="true" action="{{ route('rmc.calls.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="research_priority_area" class="form-label">Research Priority Area <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="research_priority_area" name="research_priority_area" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="funding_limit" class="form-label">Funding Limit (₱) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="funding_limit" name="funding_limit" step="0.01" min="0" required>
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
                        <textarea class="form-control" id="eligibility" name="eligibility" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="guidelines" class="form-label">Guidelines <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="guidelines" name="guidelines" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="proposal_format_file" class="form-label">Proposal Format File</label>
                        <input type="file" class="form-control" id="proposal_format_file" name="proposal_format_file" accept=".pdf,.doc,.docx">
                        <div class="form-text">Upload PDF, DOC, or DOCX file (max 10MB)</div>
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
</script>

