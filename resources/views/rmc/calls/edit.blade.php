@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Edit Call for Proposals</h4>
        <div class="alert alert-warning mb-0 py-2 px-3">
            <i class="fas fa-edit me-2"></i>Editing: {{ $call->title }}
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-warning bg-gradient text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-edit me-2"></i>Edit Call for Proposals</h6>
        </div>
        <div class="card-body">
            <form data-ajax="true" action="{{ route('rmc.calls.update', $call->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $call->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ $call->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="research_priority_area" class="form-label">Research Priority Area <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="research_priority_area" name="research_priority_area" value="{{ $call->research_priority_area }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $call->start_date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $call->end_date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="funding_limit" class="form-label">Funding Limit (₱) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="funding_limit" name="funding_limit" step="0.01" min="0" value="{{ $call->funding_limit }}" required>
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
                            <textarea class="form-control" id="eligibility" name="eligibility" rows="3" required>{{ $call->eligibility }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="guidelines" class="form-label">Guidelines <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="guidelines" name="guidelines" rows="3" required>{{ $call->guidelines }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="proposal_format_file" class="form-label">Proposal Format File</label>
                            <input type="file" class="form-control" id="proposal_format_file" name="proposal_format_file" accept=".pdf,.doc,.docx">
                            <div class="form-text">
                                Current file: {{ $call->proposal_format_file ? basename($call->proposal_format_file) : 'No file uploaded' }}
                                <br>Upload a new file to replace the current one (max 10MB)
                            </div>
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
                    <a href="{{ route('rmc.calls.show', $call->id) }}" class="btn btn-secondary">Cancel</a>
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
</script>

