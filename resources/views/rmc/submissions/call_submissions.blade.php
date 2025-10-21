@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Submissions for "{{ $call->title }}"</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Manage proposal submissions for this specific call for proposals.
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
                            <strong>Status:</strong>
                            <p>
                                <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                                    {{ $call->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <strong>Visibility:</strong>
                            <p>
                                <span class="badge bg-{{ $call->visibility_status === 'Public' ? 'success' : 'secondary' }}">
                                    {{ $call->visibility_status }}
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
                    <div class="mt-2">
                        <a href="{{ route('rmc.calls.show', $call->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Call Details
                        </a>
                        <a href="{{ route('rmc.submissions.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-list me-2"></i>All Submissions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alertContainer" class="mb-4"></div>

    <div class="card shadow">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>Proposal Submissions</h6>
                <span class="badge bg-light text-dark">{{ $submissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $submissions->total() : ($submissions->count() ?? 0) }} Total Submissions</span>
            </div>
        </div>
        <div class="card-body">
            @if($submissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Research Title</th>
                            <th>Proponent</th>
                            <th>Affiliation</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Budget Requested</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($submission->research_title, 60) }}</strong>
                            </td>
                            <td>{{ $submission->proponent_name }}</td>
                            <td>{{ Str::limit($submission->affiliation, 30) }}</td>
                            <td>{{ $submission->email }}</td>
                            <td>{{ $submission->contact_number }}</td>
                            <td>{{ $submission->date_submitted->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }}">
                                    {{ $submission->status }}
                                </span>
                            </td>
                            <td>₱{{ number_format($submission->budget_requested, 2) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rmc.submissions.show', [$call->id, $submission->id]) }}" class="btn btn-sm btn-primary">View</a>

                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fas fa-edit me-1"></i>Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('rmc.submissions.status', [$call->id, $submission->id]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Under Review">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-search me-2"></i>Set to Under Review
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('rmc.submissions.status', [$call->id, $submission->id]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Accepted">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check me-2"></i>Accept
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('rmc.submissions.status', [$call->id, $submission->id]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-times me-2"></i>Reject
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $submissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $submissions->links() : '' }}</div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">No submissions received for this call yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@php
    use Illuminate\Support\Str;
@endphp

<script>
// Handle status update form submission with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-ajax="true"]');

    forms.forEach(form => {
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
    });
});

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
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
</script>
@endsection

