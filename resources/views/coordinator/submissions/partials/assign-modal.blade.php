<!-- Assign Reviewer Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('coordinator.submissions.assign') }}" method="POST" id="assignReviewerForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="assignModalLabel">
                        <i class="fas fa-users-cog me-2"></i>Assign Reviewers
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="submission_id" id="modal_submission_id">
                    
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-1">Research Title</label>
                        <p id="modal_submission_title" class="fw-bold text-dark mb-0"></p>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-3 position-relative">
                        <label for="reviewer_search_input" class="form-label fw-bold">Select Reviewers</label>
                        
                        <!-- Selected Chips Area -->
                        <div id="selected_reviewers_container" class="d-flex flex-wrap gap-2 mb-3 p-2 border rounded bg-light min-vh-10" style="min-height: 45px;">
                            <span class="text-muted small italic my-auto px-1" id="no_selection_text">No reviewers selected yet. Search and click results to add them.</span>
                        </div>
                        
                        <!-- Hidden inputs for form submission will be generated here -->
                        <div id="hidden_inputs_container"></div>

                        <!-- Multi-Search Input -->
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" id="reviewer_search_input" class="form-control border-start-0 ps-0" placeholder="Type name, email, or campus to find reviewers...">
                        </div>
                        
                        <!-- Floating Results Dropdown -->
                        <div id="reviewer_results_dropdown" class="position-absolute w-100 bg-white shadow rounded-bottom mt-1 overflow-auto border" style="display: none; max-height: 250px; z-index: 1050; left: 0; right: 0;">
                            <div class="list-group list-group-flush" id="reviewer_results_list">
                                <!-- Results will be populated here -->
                            </div>
                        </div>

                        <div class="form-text mt-3">
                            <i class="fas fa-info-circle me-1"></i>
                            The system will automatically prevent duplicate assignments.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="submit_assign_btn" disabled>
                        <i class="fas fa-check-circle me-2"></i>Apply Assignments
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $reviewerList = $reviewers->map(function($r) {
                return [
                    'id' => $r->id,
                    'name' => $r->name,
                    'email' => $r->email,
                    'campus' => $r->campus ?? 'N/A'
                ];
            });
        @endphp

        const reviewersData = @json($reviewerList);

        const assignModal = document.getElementById('assignModal');
        const searchInput = document.getElementById('reviewer_search_input');
        const resultsDropdown = document.getElementById('reviewer_results_dropdown');
        const resultsList = document.getElementById('reviewer_results_list');
        const chipsContainer = document.getElementById('selected_reviewers_container');
        const hiddenInputsContainer = document.getElementById('hidden_inputs_container');
        const submitBtn = document.getElementById('submit_assign_btn');
        const noSelectionText = document.getElementById('no_selection_text');
        
        let selectedReviewers = new Set();

        if (assignModal) {
            assignModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const submissionId = button.getAttribute('data-submission-id');
                const submissionTitle = button.getAttribute('data-submission-title');
                
                document.getElementById('modal_submission_id').value = submissionId;
                document.getElementById('modal_submission_title').textContent = submissionTitle;
                
                // Reset everything on open
                clearSelections();
                searchInput.value = '';
                resultsDropdown.style.display = 'none';
            });
        }

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            if (query.length < 1) {
                resultsDropdown.style.display = 'none';
                return;
            }

            const filtered = reviewersData.filter(r => 
                (r.name.toLowerCase().includes(query) || 
                 r.email.toLowerCase().includes(query) || 
                 r.campus.toLowerCase().includes(query)) && 
                !selectedReviewers.has(r.id)
            );

            renderResults(filtered);
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !resultsDropdown.contains(event.target)) {
                resultsDropdown.style.display = 'none';
            }
        });

        function renderResults(reviewers) {
            if (reviewers.length === 0) {
                resultsList.innerHTML = '<div class="list-group-item text-muted small py-3">No matching reviewers found.</div>';
            } else {
                resultsList.innerHTML = reviewers.map(r => `
                    <button type="button" class="list-group-item list-group-item-action py-2 reviewer-option" data-id="${r.id}" data-name="${r.name}" data-campus="${r.campus}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 small fw-bold">${r.name}</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.7rem;">${r.campus} | ${r.email}</p>
                            </div>
                            <span class="badge bg-primary-soft text-primary"><i class="fas fa-plus small"></i></span>
                        </div>
                    </button>
                `).join('');

                const options = resultsList.querySelectorAll('.reviewer-option');
                options.forEach(opt => {
                    opt.addEventListener('click', function() {
                        addReviewer(parseInt(this.dataset.id), this.dataset.name, this.dataset.campus);
                        searchInput.value = '';
                        resultsDropdown.style.display = 'none';
                        searchInput.focus();
                    });
                });
            }
            resultsDropdown.style.display = 'block';
        }

        function addReviewer(id, name, campus) {
            if (selectedReviewers.has(id)) return;

            selectedReviewers.add(id);
            
            // Generate Chip
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary d-flex align-items-center gap-2 p-2 shadow-sm animate__animated animate__fadeIn';
            chip.style.fontSize = '0.8rem';
            chip.dataset.id = id;
            chip.innerHTML = `
                <i class="fas fa-user-check small"></i>
                <span class="text-truncate" style="max-width: 150px;">${name}</span>
                <button type="button" class="btn-close btn-close-white small remove-chip" style="transform: scale(0.6);" data-id="${id}"></button>
            `;
            
            chipsContainer.appendChild(chip);
            
            // Add Hidden Input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'reviewer_ids[]';
            input.value = id;
            input.id = `input_reviewer_${id}`;
            hiddenInputsContainer.appendChild(input);

            updateUIState();
        }

        chipsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-chip')) {
                const id = parseInt(e.target.dataset.id);
                removeReviewer(id);
            }
        });

        function removeReviewer(id) {
            selectedReviewers.delete(id);
            const chip = chipsContainer.querySelector(`span[data-id="${id}"]`);
            if (chip) chip.remove();
            
            const input = document.getElementById(`input_reviewer_${id}`);
            if (input) input.remove();
            
            updateUIState();
        }

        function clearSelections() {
            selectedReviewers.clear();
            chipsContainer.innerHTML = '';
            chipsContainer.appendChild(noSelectionText);
            hiddenInputsContainer.innerHTML = '';
            updateUIState();
        }

        function updateUIState() {
            const hasSelections = selectedReviewers.size > 0;
            noSelectionText.style.display = hasSelections ? 'none' : 'block';
            submitBtn.disabled = !hasSelections;
            
            // To maintain accessibility
            if (hasSelections) {
                submitBtn.classList.remove('disabled');
            } else {
                submitBtn.classList.add('disabled');
            }
        }
    });
</script>

<style>
    .bg-primary-soft {
        background: rgba(13, 110, 253, 0.1);
    }
    
    .min-vh-10 {
        min-height: 45px;
    }

    #reviewer_results_dropdown::-webkit-scrollbar {
        width: 6px;
    }
    
    #reviewer_results_dropdown::-webkit-scrollbar-thumb {
        background-color: #dee2e6;
        border-radius: 6px;
    }
</style>
@endpush
