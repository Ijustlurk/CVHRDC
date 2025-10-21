@extends('layouts.RUC.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">{{ $call->title }}</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Review Call for Proposals Details
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Call Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Research Priority Area:</strong>
                            <p>{{ $call->research_priority_area }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Funding Limit:</strong>
                            <p>₱{{ number_format($call->funding_limit, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Start Date:</strong>
                            <p>{{ $call->start_date->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>End Date:</strong>
                            <p>{{ $call->end_date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge bg-{{ $call->status === 'Open' ? 'success' : ($call->status === 'Draft' ? 'warning' : ($call->status === 'For Review' ? 'info' : 'secondary')) }}">
                                    {{ $call->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Visibility:</strong>
                            <p>
                                <span class="badge bg-{{ $call->visibility_status === 'Public' ? 'success' : 'secondary' }}">
                                    {{ $call->visibility_status }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($call->description)) !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Eligibility Criteria:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($call->eligibility)) !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Guidelines:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($call->guidelines)) !!}
                        </div>
                    </div>

                    @if($call->proposal_format_file)
                    <div class="mb-3">
                        <strong>Proposal Format File:</strong>
                        <div class="mt-2">
                            <a href="{{ route('ruc.calls.download-format', $call->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Format File
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Created by:</strong>
                        <p>{{ $call->creator->name }} on {{ $call->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>

                    @if($call->approved_by)
                    <div class="mb-3">
                        <strong>Approved by:</strong>
                        <p>{{ $call->approver->name }} on {{ $call->updated_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-primary">{{ $call->proposalSubmissions->count() }}</h3>
                        <p class="mb-0">Total Submissions</p>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success">{{ $call->proposalSubmissions->where('status', 'Accepted')->count() }}</h5>
                            <small class="text-muted">Accepted</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-danger">{{ $call->proposalSubmissions->where('status', 'Rejected')->count() }}</h5>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>

                    <div class="row text-center mt-2">
                        <div class="col-6">
                            <h5 class="text-warning">{{ $call->proposalSubmissions->where('status', 'Under Review')->count() }}</h5>
                            <small class="text-muted">Under Review</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-info">{{ $call->proposalSubmissions->where('status', 'Submitted')->count() }}</h5>
                            <small class="text-muted">Submitted</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header bg-warning bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-cogs me-2"></i>RUC Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($call->status === 'For Review' || $call->status === 'Draft')
                        <!-- Approve and Publish -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-grid">
                            @csrf
                            <input type="hidden" name="status" value="Open">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle me-2"></i>Approve & Publish Call
                            </button>
                        </form>
                        @endif

                        @if($call->status === 'Open' && $call->visibility_status === 'Public')
                        <!-- Withdraw/Close -->
                        <form method="POST" action="{{ route('ruc.calls.status', $call->id) }}" class="d-grid">
                            @csrf
                            <input type="hidden" name="status" value="Closed">
                            <input type="hidden" name="action" value="withdraw">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-times-circle me-2"></i>Withdraw & Close Call
                            </button>
                        </form>
                        @elseif($call->visibility_status === 'Public')
                        <!-- Make Private (for calls that are Public but not Open) -->
                        <form method="POST" action="{{ route('ruc.calls.visibility', $call->id) }}" class="d-grid">
                            @csrf
                            <input type="hidden" name="visibility_status" value="Private">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-eye-slash me-2"></i>Make Private
                            </button>
                        </form>
                        @endif

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>As RUC, you can control visibility but cannot edit the call content.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($call->proposalSubmissions->count() > 0)
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary bg-gradient text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>Recent Submissions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Research Title</th>
                            <th>Proponent</th>
                            <th>Email</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Budget Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($call->proposalSubmissions->take(5) as $submission)
                        <tr>
                            <td>{{ Str::limit($submission->research_title, 50) }}</td>
                            <td>{{ $submission->proponent_name }}</td>
                            <td>{{ $submission->email }}</td>
                            <td>{{ $submission->date_submitted->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $submission->status === 'Accepted' ? 'success' : ($submission->status === 'Rejected' ? 'danger' : ($submission->status === 'Under Review' ? 'warning' : 'info')) }}">
                                    {{ $submission->status }}
                                </span>
                            </td>
                            <td>₱{{ number_format($submission->budget_requested, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($call->proposalSubmissions->count() > 5)
            <div class="text-center mt-3">
                <small class="text-muted">{{ $call->proposalSubmissions->count() }} total submissions for this call</small>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

