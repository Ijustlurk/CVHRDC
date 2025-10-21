@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">{{ $submission->research_title }}</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Proposal Submission Details
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Call Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Call Title:</strong>
                            <p><a href="{{ route('rmc.calls.show', $call->id) }}">{{ $call->title }}</a></p>
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                                    {{ $call->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <strong>Funding Limit:</strong>
                            <p>₱{{ number_format($call->funding_limit, 2) }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Deadline:</strong>
                            <p>{{ $call->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>Proposal Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Research Title:</strong>
                            <p>{{ $submission->research_title }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Date Submitted:</strong>
                            <p>{{ $submission->date_submitted->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Proponent Name:</strong>
                            <p>{{ $submission->proponent_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Affiliation:</strong>
                            <p>{{ $submission->affiliation }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Contact Number:</strong>
                            <p>{{ $submission->contact_number }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Budget Requested:</strong>
                        <p class="text-primary h5">₱{{ number_format($submission->budget_requested, 2) }}</p>
                    </div>

                    @if($submission->remarks)
                    <div class="mb-3">
                        <strong>Remarks:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($submission->remarks)) !!}
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Proposal Document:</strong>
                        <div class="mt-2">
                            <a href="{{ route('rmc.submissions.download', [$call->id, $submission->id]) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Proposal Document
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-warning bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-edit me-2"></i>Update Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Status:</strong>
                        <p>
                            <span class="badge bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }} fs-6">
                                {{ $submission->status }}
                            </span>
                        </p>
                    </div>

                    <form data-ajax="true" action="{{ route('rmc.submissions.status', [$call->id, $submission->id]) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="status" class="form-label">New Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Submitted" {{ $submission->status === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Under Review" {{ $submission->status === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Accepted" {{ $submission->status === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ $submission->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks/Comments</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Add any comments or feedback for this submission...">{{ $submission->remarks }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Update Status
                            </button>
                            <a href="{{ route('rmc.submissions.index', $call->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Submissions
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar me-2"></i>Budget Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-primary">₱{{ number_format($submission->budget_requested, 2) }}</h5>
                            <small class="text-muted">Requested</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success">₱{{ number_format($call->funding_limit, 2) }}</h5>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        @php
                            $percentage = $call->funding_limit > 0 ? ($submission->budget_requested / $call->funding_limit) * 100 : 0;
                        @endphp
                        <div class="progress">
                            <div class="progress-bar bg-{{ $percentage > 100 ? 'danger' : ($percentage > 75 ? 'warning' : 'success') }}"
                                 role="progressbar"
                                 style="width: {{ min($percentage, 100) }}%"
                                 aria-valuenow="{{ $percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            {{ $percentage > 100 ? 'Over budget!' : ($percentage > 75 ? 'High budget usage' : 'Within budget') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-ajax="true"]');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            submitBtn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'danger');
                    if (data.errors) {
                        Object.values(data.errors).forEach(error => {
                            showAlert(error[0], 'danger');
                        });
                    }
                }
            })
            .catch(error => {
                showAlert('An error occurred. Please try again.', 'danger');
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer') || createAlertContainer();
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}

function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alertContainer';
    container.className = 'mb-4';
    document.querySelector('.container').insertBefore(container, document.querySelector('.container').firstChild);
    return container;
}
</script>
@endsection

