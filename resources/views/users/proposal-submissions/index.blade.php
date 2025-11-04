@extends('layouts.Users.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">My Proposal Submissions</h4>
        <a href="{{ route('users.proposal-submissions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Submit New Proposal
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Research Title</th>
                                <th>Call for Proposal</th>
                                <th>Status</th>
                                <th>Submitted Date</th>
                                <th>Budget Requested</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td>{{ $submission->research_title }}</td>
                                    <td>{{ $submission->callForProposal->title ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $submission->status === 'Submitted' ? 'primary' : ($submission->status === 'Under Review' ? 'warning' : ($submission->status === 'Accepted' ? 'success' : 'danger')) }}">
                                            {{ $submission->status }}
                                        </span>
                                    </td>
                                    <td>{{ $submission->date_submitted->format('M d, Y H:i') }}</td>
                                    <td>₱{{ number_format($submission->budget_requested, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.proposal-submissions.show', $submission->id) }}"
                                               class="btn btn-sm btn-primary">View</a>
                                            <a href="{{ route('users.proposal-submissions.download', $submission->id) }}"
                                               class="btn btn-sm btn-outline-secondary ms-1"
                                               title="Download Proposal Document">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $submissions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-signature fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Proposal Submissions Yet</h5>
                    <p class="text-muted mb-4">You haven't submitted any proposals yet. Start by submitting your first research proposal!</p>
                    <a href="{{ route('users.proposal-submissions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Submit Your First Proposal
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
