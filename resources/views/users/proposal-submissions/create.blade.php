@extends('layouts.Users.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Submit Research Proposal</h4>
        <a href="{{ route('users.proposal-submissions.index') }}" class="btn btn-light">&larr; Back</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($availableCalls->count() === 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>No Active Calls Available</strong><br>
            There are currently no open calls for proposals accepting submissions. Please check back later.
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('users.proposal-submissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to My Submissions
            </a>
        </div>
    @else
        <div class="card shadow">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fas fa-file-signature me-2"></i>Proposal Submission Form
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.proposal-submissions.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-3">
                        <!-- Call for Proposal Selection -->
                        <div class="col-12">
                            <label for="call_id" class="form-label">
                                Call for Proposal <span class="text-danger">*</span>
                            </label>
                            <select id="call_id" name="call_id" class="form-select @error('call_id') is-invalid @enderror" required>
                                <option value="" disabled {{ old('call_id') ? '' : 'selected' }}>Select a Call for Proposal</option>
                                @foreach($availableCalls as $call)
                                    <option value="{{ $call->id }}" {{ old('call_id') == $call->id ? 'selected' : '' }}>
                                        {{ $call->title }} (Ends: {{ $call->end_date->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('call_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Only active calls that are currently accepting submissions are shown.</div>
                        </div>

                        <!-- Research Title -->
                        <div class="col-12">
                            <label for="research_title" class="form-label">
                                Research Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="research_title" name="research_title"
                                   class="form-control @error('research_title') is-invalid @enderror"
                                   value="{{ old('research_title') }}" required>
                            @error('research_title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Proponent Name -->
                        <div class="col-md-6">
                            <label for="proponent_name" class="form-label">
                                Proponent Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="proponent_name" name="proponent_name"
                                   class="form-control @error('proponent_name') is-invalid @enderror"
                                   value="{{ old('proponent_name', Auth::user()->name) }}" required>
                            @error('proponent_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Affiliation -->
                        <div class="col-md-6">
                            <label for="affiliation" class="form-label">
                                Affiliation/Institution <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="affiliation" name="affiliation"
                                   class="form-control @error('affiliation') is-invalid @enderror"
                                   value="{{ old('affiliation') }}" placeholder="e.g., University, Organization" required>
                            @error('affiliation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">
                                Contact Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="contact_number" name="contact_number"
                                   class="form-control @error('contact_number') is-invalid @enderror"
                                   value="{{ old('contact_number') }}" placeholder="e.g., +63 912 345 6789" required>
                            @error('contact_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Budget Requested -->
                        <div class="col-md-6">
                            <label for="budget_requested" class="form-label">
                                Budget Requested (₱) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" id="budget_requested" name="budget_requested"
                                       class="form-control @error('budget_requested') is-invalid @enderror"
                                       value="{{ old('budget_requested') }}" step="0.01" min="0" required>
                            </div>
                            @error('budget_requested')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Enter the total budget amount requested for this research proposal.</div>
                        </div>

                        <!-- Proposal Document Upload -->
                        <div class="col-md-6">
                            <label for="proposal_document" class="form-label">
                                Proposal Document <span class="text-danger">*</span>
                            </label>
                            <input type="file" id="proposal_document" name="proposal_document"
                                   class="form-control @error('proposal_document') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx" required>
                            @error('proposal_document')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</div>
                        </div>

                        <!-- File Preview (when selected) -->
                        <div class="col-12" id="file-preview" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-file-upload me-2"></i>
                                <strong>Selected File:</strong> <span id="file-name"></span>
                                (<span id="file-size"></span>)
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <a href="{{ route('users.proposal-submissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Proposal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File preview functionality
    const fileInput = document.getElementById('proposal_document');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    fileInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.style.display = 'block';
        } else {
            filePreview.style.display = 'none';
        }
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form validation
    const form = document.querySelector('.needs-validation');
    form?.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endpush
