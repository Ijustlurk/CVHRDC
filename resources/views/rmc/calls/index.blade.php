@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Calls for Proposals Management</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Create and manage calls for proposals for research funding opportunities.
        </div>
    </div>

    <div id="alertContainer" class="mb-4"></div>

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
                                                    <form method="POST" action="{{ route('rmc.calls.destroy', $call->id) }}" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to delete this call?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-warning text-dark">Posted Publicly</span>
                                                @endif
                                                <form method="POST" action="{{ route('rmc.calls.visibility', $call->id) }}" class="d-inline ms-1">
                                                    @csrf
                                                    <input type="hidden" name="visibility_status" value="{{ $call->visibility_status === 'Public' ? 'Private' : 'Public' }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $call->visibility_status === 'Public' ? 'secondary' : 'success' }}">
                                                        <i class="fa fa-eye{{ $call->visibility_status === 'Public' ? '-slash' : '' }}"></i>
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
                                                <form method="POST" action="{{ route('rmc.calls.destroy', $call->id) }}" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to delete this call?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @else
                                                <span class="badge bg-warning text-dark">Posted Publicly</span>
                                            @endif
                                            <form method="POST" action="{{ route('rmc.calls.visibility', $call->id) }}" class="d-inline ms-1">
                                                @csrf
                                                <input type="hidden" name="visibility_status" value="{{ $call->visibility_status === 'Public' ? 'Private' : 'Public' }}">
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $call->visibility_status === 'Public' ? 'secondary' : 'success' }}">
                                                    <i class="fa fa-eye{{ $call->visibility_status === 'Public' ? '-slash' : '' }}"></i>
                                                </button>
                                            </form>
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

