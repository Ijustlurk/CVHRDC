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
                                <a href="{{ route('rmc.submissions.show', [$submission->call_id, $submission->id]) }}" class="btn btn-sm btn-primary">
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
                <p class="text-muted mb-0">No submissions received yet.</p>
                <p class="text-muted">Submissions will appear here once researchers submit proposals to your calls.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

