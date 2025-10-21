@extends('layouts.RMC.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">All Proposal Submissions</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Manage all proposal submissions for calls you have created.
        </div>
    </div>

    @if($calls->isEmpty())
    <div class="alert alert-warning">
        <i class="fas fa-info-circle me-2"></i>
        You haven't created any calls for proposals yet. Create a call first to start receiving submissions.
        <a href="{{ route('rmc.calls.index', ['tab' => 'create-call']) }}" class="btn btn-sm btn-primary ms-2">
            <i class="fas fa-plus me-1"></i>Create Call
        </a>
    </div>
    @endif

    <div id="alertContainer" class="mb-4"></div>

    <div class="card shadow">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>All Proposal Submissions</h6>
                <span class="badge bg-light text-dark">{{ $submissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $submissions->total() : ($submissions->count() ?? 0) }} Total Submissions</span>
            </div>
        </div>
        <div class="card-body">
            @if(($submissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $submissions->total() : $submissions->count()) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Research Title</th>
                            <th>Call Title</th>
                            <th>Proponent</th>
                            <th>Affiliation</th>
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
                                <strong>{{ Str::limit($submission->research_title, 50) }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('rmc.calls.show', $submission->call_id) }}" class="text-decoration-none">
                                    {{ Str::limit($submission->callForProposal->title, 40) }}
                                </a>
                            </td>
                            <td>{{ $submission->proponent_name }}</td>
                            <td>{{ Str::limit($submission->affiliation, 25) }}</td>
                            <td>{{ $submission->date_submitted->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }}">
                                    {{ $submission->status }}
                                </span>
                            </td>
                            <td>₱{{ number_format($submission->budget_requested, 2) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rmc.submissions.show', [$submission->call_id, $submission->id]) }}" class="btn btn-sm btn-primary">View</a>

                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fas fa-edit me-1"></i>Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('rmc.submissions.status', [$submission->call_id, $submission->id]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Under Review">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-search me-2"></i>Set to Under Review
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('rmc.submissions.status', [$submission->call_id, $submission->id]) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Accepted">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check me-2"></i>Accept
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('rmc.submissions.status', [$submission->call_id, $submission->id]) }}" class="d-inline">
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
                <p class="text-muted mb-0">No submissions received yet.</p>
                <p class="text-muted">Submissions will appear here once researchers submit proposals to your calls.</p>
            </div>
            @endif
        </div>
    </div>
</div>

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

