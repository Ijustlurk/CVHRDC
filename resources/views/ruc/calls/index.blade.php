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

    <div id="alertContainer" class="mb-4"></div>

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
                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Open">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                            @elseif($call->status === 'Open' && $call->visibility_status === 'Public')
                                            <!-- Withdraw/Close -->
                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Closed">
                                                <input type="hidden" name="action" value="withdraw">
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-times me-1"></i>Withdraw
                                                </button>
                                            </form>
                                            @elseif($call->visibility_status === 'Public')
                                            <!-- Make Private -->
                                            <form method="POST" action="{{ route('ruc.calls.visibility', $call->id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="visibility_status" value="Private">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye-slash"></i>
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

                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline">
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
                                            <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-inline">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-ajax="true"]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
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

