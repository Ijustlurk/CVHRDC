@extends('layouts.Reviewer.app')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Review Details</h4>
            <p class="text-muted small mb-0">Double-blind peer review — author identity is not disclosed</p>
        </div>
        <a href="{{ route('reviewer.reviews.index', ['tab' => 'assignments']) }}" class="btn btn-outline-secondary rounded-3 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Left: Submission Info (Double-Blind) --}}
        <div class="col-lg-5">

            {{-- Submission Info Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <h6 class="m-0 fw-bold text-white">
                        <i class="fas fa-shield-alt me-2"></i>Submission Information
                        <span class="badge bg-white text-primary ms-2 rounded-pill px-2 py-1" style="font-size:0.65rem;">Double-Blind</span>
                    </h6>
                </div>
                <div class="card-body p-4">
                    {{-- Only blind-safe fields --}}
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Research Title</label>
                        <h6 class="fw-bold text-dark mb-0">{{ $review->submission->research_title }}</h6>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Date Submitted</label>
                        <p class="mb-0"><i class="fas fa-calendar-alt me-2 text-muted"></i>{{ $review->submission->date_submitted->format('F d, Y') }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Assignment Status</label>
                        @php
                            $statusColors = ['Under Review' => 'info', 'Completed' => 'success', 'Pending' => 'warning', 'Cancelled' => 'secondary'];
                        @endphp
                        <span class="badge rounded-pill bg-{{ $statusColors[$review->status] ?? 'secondary' }} px-3 py-2">
                            {{ $review->status }}
                        </span>
                    </div>
                    <div>
                        <label class="text-muted small text-uppercase fw-bold mb-1 d-block">Assigned To You</label>
                        <p class="mb-0 text-muted"><i class="fas fa-user-clock me-2"></i>{{ $review->created_at->format('F d, Y') }}</p>
                    </div>

                    <div class="alert alert-warning border-0 rounded-3 mt-4 mb-0 p-3" style="font-size:0.80rem;">
                        <i class="fas fa-eye-slash me-2"></i>
                        <strong>Double-Blind Policy:</strong> Author details, affiliation, and contact information are intentionally hidden from reviewers.
                    </div>
                </div>
            </div>

            {{-- Download Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #0dcaf0 0%, #0baac7 100%);">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-file-pdf me-2"></i>Proposal Document</h6>
                </div>
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <i class="fas fa-file-pdf fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold text-dark">Download Proposal</h6>
                        <p class="text-muted small mb-0">File name is anonymized for double-blind integrity.</p>
                    </div>
                    <a href="{{ route('reviewer.reviews.download', $review->id) }}" class="btn btn-primary rounded-3 shadow-sm flex-shrink-0">
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                </div>
            </div>
        </div>

        {{-- Right: Verdict Form / Completed State --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                @if($review->status === 'Completed')
                    {{-- Read-only completed verdict --}}
                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                        <h6 class="m-0 fw-bold text-white"><i class="fas fa-check-double me-2"></i>Submitted Verdict</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Final Verdict</label>
                            @php
                                $verdictColors = [
                                    'Accept Submission'          => 'success',
                                    'Revisions Required (Minor)' => 'info',
                                    'Resubmit for Review'        => 'warning',
                                    'Resubmit Elsewhere'         => 'secondary',
                                    'Decline Submission'         => 'danger',
                                ];
                                $vc = $verdictColors[$review->recommendation] ?? 'secondary';
                            @endphp
                            <span class="badge rounded-pill bg-{{ $vc }} px-4 py-2" style="font-size:0.9rem;">
                                <i class="fas fa-gavel me-2"></i>{{ $review->recommendation }}
                            </span>
                        </div>

                        <div>
                            <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Review Comments</label>
                            <div class="bg-light rounded-3 p-3 border" style="white-space: pre-wrap; font-size: 0.9rem; line-height: 1.7;">{{ $review->comments }}</div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-clock me-1"></i>Verdict submitted on {{ $review->updated_at->format('F d, Y \a\t h:i A') }}.
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Active verdict submission form --}}
                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #343a40 0%, #212529 100%);">
                        <h6 class="m-0 fw-bold text-white"><i class="fas fa-gavel me-2"></i>Submit Your Verdict</h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="alert alert-info border-0 rounded-3 mb-4 p-3" style="font-size: 0.82rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            Your verdict and comments will be saved to the system and forwarded to the coordinator. This action <strong>cannot be undone</strong>.
                        </div>

                        <form action="{{ route('reviewer.reviews.verdict', $review->id) }}" method="POST" id="verdictForm">
                            @csrf

                            {{-- Verdict Selection --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Final Verdict <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-3">Select the outcome that best reflects the quality and suitability of this submission.</p>

                                @php
                                    $verdictIcons = [
                                        'Accept Submission'          => ['icon' => 'fa-check-circle', 'color' => 'success', 'desc' => 'The submission meets all standards and is ready for publication.'],
                                        'Revisions Required (Minor)' => ['icon' => 'fa-edit', 'color' => 'info', 'desc' => 'The submission needs minor corrections before it can be accepted.'],
                                        'Resubmit for Review'        => ['icon' => 'fa-redo', 'color' => 'warning', 'desc' => 'Major revisions needed and the revised paper should undergo another round of review.'],
                                        'Resubmit Elsewhere'         => ['icon' => 'fa-share-square', 'color' => 'secondary', 'desc' => 'The submission is outside the scope of this journal/conference. Authors should seek another venue.'],
                                        'Decline Submission'         => ['icon' => 'fa-times-circle', 'color' => 'danger', 'desc' => 'The submission does not meet the minimum standards. Recommend rejection.'],
                                    ];
                                @endphp

                                <div class="d-flex flex-column gap-2" id="verdictOptions">
                                    @foreach($verdicts as $verdict)
                                    @php $meta = $verdictIcons[$verdict] ?? ['icon' => 'fa-circle', 'color' => 'secondary', 'desc' => '']; @endphp
                                    <label class="verdict-option d-flex align-items-start gap-3 p-3 rounded-3 border cursor-pointer {{ old('recommendation') === $verdict ? 'selected border-'.$meta['color'].' bg-'.$meta['color'].'-subtle' : 'bg-light' }}"
                                           data-color="{{ $meta['color'] }}"
                                           style="cursor:pointer; transition: all 0.2s ease;">
                                        <input type="radio" name="recommendation" value="{{ $verdict }}" class="d-none verdict-radio"
                                               {{ old('recommendation') === $verdict ? 'checked' : '' }}>
                                        <div class="rounded-circle bg-{{ $meta['color'] }} bg-opacity-10 text-{{ $meta['color'] }} d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:36px;height:36px;">
                                            <i class="fas {{ $meta['icon'] }} small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $verdict }}</div>
                                            <div class="text-muted small">{{ $meta['desc'] }}</div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>

                                @error('recommendation')
                                    <div class="text-danger small mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Comments --}}
                            <div class="mb-4">
                                <label for="comments" class="form-label fw-bold">
                                    Review Comments <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted small mb-2">Provide a thorough evaluation. Minimum 20 characters. Your comments will guide the author's revisions.</p>
                                <textarea name="comments" id="comments" rows="8"
                                    class="form-control rounded-3 shadow-sm @error('comments') is-invalid @enderror"
                                    placeholder="Provide a detailed assessment of the manuscript's strengths, weaknesses, methodology, originality, and specific recommendations for improvement..."
                                    style="resize: vertical;">{{ old('comments') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    @error('comments')
                                        <div class="text-danger small"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @else
                                        <div class="text-muted small">Min. 20 characters required.</div>
                                    @enderror
                                    <div class="text-muted small" id="charCount">0 / 5000</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('reviewer.reviews.index', ['tab' => 'assignments']) }}" class="btn btn-outline-secondary rounded-3 px-4">
                                    Cancel
                                </a>
                                <button type="button" class="btn btn-dark rounded-3 px-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmVerdictModal" id="submitVerdictBtn" disabled>
                                    <i class="fas fa-gavel me-2"></i>Submit Verdict
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmVerdictModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 py-3" style="background: linear-gradient(135deg, #343a40 0%, #212529 100%);">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-gavel me-2"></i>Confirm Verdict Submission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2">You are about to submit your final verdict:</p>
                <div class="bg-light rounded-3 p-3 mb-3 border fw-bold text-center" id="selectedVerdictPreview">—</div>
                <div class="alert alert-warning border-0 rounded-3 mb-0 p-3" style="font-size:0.82rem;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>This action is permanent.</strong> Once submitted, your verdict and comments cannot be modified.
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Go Back</button>
                <button type="button" class="btn btn-dark rounded-3 px-5 shadow-sm" id="confirmSubmitBtn">
                    <i class="fas fa-check me-2"></i>Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const options      = document.querySelectorAll('.verdict-option');
    const submitBtn    = document.getElementById('submitVerdictBtn');
    const preview      = document.getElementById('selectedVerdictPreview');
    const confirmBtn   = document.getElementById('confirmSubmitBtn');
    const form         = document.getElementById('verdictForm');
    const textarea     = document.getElementById('comments');
    const charCount    = document.getElementById('charCount');

    if (!form) return; // Already completed — no form rendered

    function updateSubmitState() {
        const anyChecked = document.querySelector('.verdict-radio:checked');
        const hasComments = textarea && textarea.value.trim().length >= 20;
        submitBtn.disabled = !(anyChecked && hasComments);
    }

    // Card-style verdict selection
    options.forEach(label => {
        label.addEventListener('click', function() {
            const radio = this.querySelector('.verdict-radio');
            radio.checked = true;
            const color = this.dataset.color;

            options.forEach(l => {
                l.classList.remove('border-success', 'border-info', 'border-warning', 'border-secondary', 'border-danger',
                                   'bg-success-subtle', 'bg-info-subtle', 'bg-warning-subtle', 'bg-secondary-subtle', 'bg-danger-subtle');
                l.classList.add('bg-light');
            });

            this.classList.remove('bg-light');
            this.classList.add(`border-${color}`, `bg-${color}-subtle`);

            if (preview) preview.textContent = this.querySelector('.fw-bold').textContent;
            updateSubmitState();
        });
    });

    if (textarea) {
        textarea.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = `${len} / 5000`;
            charCount.className = len > 4500 ? 'text-danger small' : 'text-muted small';
            updateSubmitState();
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            form.submit();
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .verdict-option { transition: all 0.2s ease; }
    .verdict-option:hover { transform: translateX(3px); }
    .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(13,110,253,.1); border-color: #86b7fe; }
    .bg-success-subtle { background-color: rgba(25,135,84,.08) !important; }
    .bg-info-subtle    { background-color: rgba(13,202,240,.08) !important; }
    .bg-warning-subtle { background-color: rgba(255,193,7,.08) !important; }
    .bg-secondary-subtle { background-color: rgba(108,117,125,.08) !important; }
    .bg-danger-subtle  { background-color: rgba(220,53,69,.08) !important; }
</style>
@endpush
