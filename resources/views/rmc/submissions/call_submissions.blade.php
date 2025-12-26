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
                                <a href="{{ route('rmc.submissions.show', [$call->id, $submission->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
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

@endsection

