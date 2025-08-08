<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Project</div>
            <div class="card-body">

                <!-- Alert container -->
                <div id="project-alert-container"></div>

                <form id="project-form" action="{{ url('/admin/projects') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="project-title" class="form-label">
                            Title <span class="text-danger">*</span>
                            <small class="text-muted">(3-255 characters)</small>
                        </label>
                        <textarea class="form-control" id="project-title" name="title" rows="2" required
                            placeholder="Enter project title..." minlength="3" maxlength="255"></textarea>
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-description" class="form-label">
                            Description <span class="text-danger">*</span>
                            <small class="text-muted">(minimum 10 characters)</small>
                        </label>
                        <textarea class="form-control" id="project-description" name="description" rows="6" required
                            placeholder="Enter project description..." minlength="10"></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-image" class="form-label">
                            Image URL <span class="text-danger">*</span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="project-image" name="image" required
                            placeholder="https://drive.google.com/file/d/.../view">
                        <div class="form-text">
                            <strong>Google Drive Instructions:</strong><br>
                            1. Right-click your image in Google Drive → "Get link"<br>
                            2. Set to "Anyone with the link" can view<br>
                            3. Copy and paste the sharing link here
                        </div>
                        <div class="invalid-feedback" id="image-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-start-date" class="form-label">
                            Start Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="project-start-date" name="start_date" required>
                        <div class="invalid-feedback" id="start_date-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-end-date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="project-end-date" name="end_date">
                        <div class="form-text">
                            <small class="text-muted">Optional - leave blank if project is ongoing</small>
                        </div>
                        <div class="invalid-feedback" id="end_date-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="project-status" name="status" required>
                            <option value="">-- Select Status --</option>
                            <option value="Planning">Planning</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <div class="invalid-feedback" id="status-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-budget" class="form-label">Budget</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="project-budget"
                            name="budget" placeholder="0.00">
                        <div class="form-text">
                            <small class="text-muted">Optional - enter amount in your local currency</small>
                        </div>
                        <div class="invalid-feedback" id="budget-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-lead-researcher" class="form-label">
                            Lead Researcher <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="project-lead-researcher"
                            name="lead_researcher" required placeholder="Enter lead researcher name">
                        <div class="invalid-feedback" id="lead_researcher-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-leader" class="form-label">
                            Project Leader <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="project-leader" name="leader" required
                            placeholder="Enter project leader name">
                        <div class="invalid-feedback" id="leader-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-funding-agency" class="form-label">Funding Agency</label>
                        <input type="text" class="form-control" id="project-funding-agency" name="funding_agency"
                            placeholder="Enter funding agency name">
                        <div class="invalid-feedback" id="funding_agency-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="project-active" class="form-label">
                            Active Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="project-active" name="is_active" required>
                            <option value="">-- Select Active Status --</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="is_active-error"></div>
                    </div>

                     <div class="mb-3">
                        <label for="project-visible" class="form-label">
                            Visibility <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="project-visible" name="is_visible" required>
                            <option value="">-- Select Visibility --</option>
                            <option value="1">Visible</option>
                                    <option value="0">Hidden</option>
                                </select>
                        <div class="invalid-feedback" id="is_visible-error"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submit-project-btn">
                            <span class="spinner-border spinner-border-sm d-none" id="submit-spinner"></span>
                            <i class="fas fa-plus"></i> Add Project
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearProjectForm()">
                            <i class="fas fa-times"></i> Clear Form
                        </button>
                        <!--
                        <button type="button" class="btn btn-info btn-sm" onclick="testProjectAlerts()">
                            <i class="fas fa-flask"></i> Test Alerts
                        </button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Recent Projects</h4>
            </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-projects-btn">View All
                Projects</button>
        </div>
        <div class="text-muted align-items-right" id="projects-count-info" style="font-size:1rem;"></div>
        <div class="card-body p-2">
            <div id="projects-list"></div>
            <!-- Day.js CDN for human readable dates -->
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
            <script>
                dayjs.extend(dayjs_plugin_relativeTime);
            </script>
            <!-- Project Details Modal -->
            <div class="modal fade" id="projectDetailsModal" tabindex="-1"
                aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="projectDetailsModalLabel">Project Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="project-details-body">
                    <!-- Details will be loaded here -->
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Google Drive URL converter (Updated for 2024)
    function convertGoogleDriveUrl(url) {
        if (!url) return '';

        // Check if it's already a direct image URL or thumbnail URL
        if (url.includes('drive.google.com/thumbnail') ||
            url.includes('drive.usercontent.google.com') ||
            (!url.includes('drive.google.com'))) {
            return url;
        }

        // Extract file ID from various Google Drive URL formats
        let fileId = null;

        // Format: https://drive.google.com/file/d/FILE_ID/view
        let match = url.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
        if (match) {
            fileId = match[1];
        }

        // Format: https://drive.google.com/open?id=FILE_ID
        if (!fileId) {
            match = url.match(/[?&]id=([a-zA-Z0-9_-]+)/);
            if (match) {
                fileId = match[1];
            }
        }

        // If file ID found, use the new thumbnail method (works as of 2024)
        if (fileId) {
            const newUrl = `https://drive.google.com/thumbnail?id=${fileId}&sz=s4000`;
            console.log('Google Drive URL conversion:', url, '->', newUrl);
            return newUrl;
        }

        // If no match found, return original URL
        return url;
    }

    // Simple alert function with multiple fallbacks
    function showProjectAlert(message, type = 'info') {
        console.log('showProjectAlert called with:', message, type);

        // ALWAYS show native alert first for immediate feedback
        if (type === 'success') {
            alert('✅ SUCCESS: ' + message);
        } else if (type === 'danger') {
            alert('❌ ERROR: ' + message);
        } else {
            alert('ℹ️ INFO: ' + message);
        }

        // Also try to show Bootstrap alert
        const alertContainer = document.getElementById('project-alert-container');
        if (alertContainer) {
            // Clear any existing alerts first
            alertContainer.innerHTML = '';

            const alertClass = type === 'success' ? 'alert-success' :
                type === 'danger' ? 'alert-danger' :
                type === 'warning' ? 'alert-warning' : 'alert-info';

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <strong>${type === 'success' ? 'Success!' : type === 'danger' ? 'Error!' : 'Info:'}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            alertContainer.innerHTML = alertHtml;
            console.log('Bootstrap alert also displayed');

            // Scroll to alert
            alertContainer.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                    console.log('Bootstrap alert auto-dismissed');
                }
            }, 5000);
        } else {
            console.error('Alert container not found - only native alert shown');
        }
    }

    // Clear validation errors
    function clearProjectValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    // Clear form function
    function clearProjectForm() {
        const form = document.getElementById('project-form');
        form.reset();
        clearProjectValidationErrors();
        document.getElementById('project-alert-container').innerHTML = '';
        console.log('Project form cleared');
    }

    // Show validation errors
    function showProjectValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            const errorDiv = document.getElementById(`${field}-error`);

            if (input) {
                input.classList.add('is-invalid');
            }
            if (errorDiv) {
                errorDiv.textContent = errors[field][0];
            }
        });
    }

    // Validate form before submission
    function validateProjectForm(formData) {
        const errors = {};

        // Validate title (required)
        const title = formData.get('title')?.trim();
        if (!title) {
            errors.title = ['Title is required'];
        } else if (title.length < 3) {
            errors.title = ['Title must be at least 3 characters'];
        } else if (title.length > 255) {
            errors.title = ['Title must not exceed 255 characters'];
        }

        // Validate description (required)
        const description = formData.get('description')?.trim();
        if (!description) {
            errors.description = ['Description is required'];
        } else if (description.length < 10) {
            errors.description = ['Description must be at least 10 characters'];
        }

        // Validate image URL (required)
        const image = formData.get('image')?.trim();
        if (!image) {
            errors.image = ['Image URL is required'];
        } else {
            try {
                new URL(image);
            } catch (e) {
                errors.image = ['Please enter a valid URL'];
            }
        }

        // Validate start_date (required)
        const startDate = formData.get('start_date');
        if (!startDate || !startDate.trim()) {
            errors.start_date = ['Start date is required'];
        }

        // Validate end_date (optional but if provided, must be after start date)
        const endDate = formData.get('end_date');
        if (endDate && endDate.trim() && startDate && startDate.trim()) {
            if (new Date(endDate) < new Date(startDate)) {
                errors.end_date = ['End date must be after start date'];
            }
        }

        // Validate status (required)
        const status = formData.get('status');
        if (!status || !status.trim()) {
            errors.status = ['Status is required'];
        }

        // Validate lead_researcher (required)
        const leadResearcher = formData.get('lead_researcher')?.trim();
        if (!leadResearcher) {
            errors.lead_researcher = ['Lead researcher is required'];
        }

        // Validate is_active (required)
        const isActive = formData.get('is_active');
        if (isActive === null || isActive === '' || isActive === undefined) {
            errors.is_active = ['Active status is required'];
        }

        // Validate leader (required)
        const leader = formData.get('leader')?.trim();
        if (!leader) {
            errors.leader = ['Project leader is required'];
        }

        // Validate is_visible (required)
        const isVisible = formData.get('is_visible');
        if (isVisible === null || isVisible === '' || isVisible === undefined) {
            errors.is_visible = ['Visibility selection is required'];
        }

        // Validate budget (optional but if provided, must be positive)
        const budget = formData.get('budget');
        if (budget && budget.trim()) {
            const budgetNum = parseFloat(budget);
            if (isNaN(budgetNum) || budgetNum < 0) {
                errors.budget = ['Budget must be a positive number'];
            }
        }

        return {
            isValid: Object.keys(errors).length === 0,
            errors: errors
        };
    }

    // Test alert function
    function testProjectAlerts() {
        showProjectAlert('Testing project alerts - this should work!', 'success');
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchProjects();
        setInterval(fetchProjects, 5000);

        // Handle hide/show visibility buttons for projects (event delegation)
        document.getElementById('projects-list').addEventListener('click', function(e) {
            let btn = e.target;
            if (!btn.classList.contains('toggle-visibility-btn')) {
                btn = btn.closest('.toggle-visibility-btn');
            }
            if (btn && btn.classList.contains('toggle-visibility-btn')) {
                const id = btn.getAttribute('data-id');
                if (!id) return;
                const currentVisible = btn.getAttribute('data-visible') == '1';
                const newVisible = currentVisible ? 0 : 1;
                btn.disabled = true;
                fetch(`/admin/projects/${id}/visibility`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            is_visible: newVisible
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Update button and badge immediately
                        const badge = document.getElementById('visibility-label-' + id);
                        if (badge) {
                            badge.textContent = newVisible ? 'Visible' : 'Hidden';
                            badge.className = 'badge ' + (newVisible ? 'bg-success' :
                                'bg-secondary');
                        }
                        btn.textContent = newVisible ? 'Hide' : 'Show';
                        btn.className = 'btn btn-sm btn-outline-' + (newVisible ? 'secondary' :
                            'success') + ' ms-2 toggle-visibility-btn';
                        btn.setAttribute('data-visible', newVisible);
                        btn.disabled = false;
                    })
                    .catch(error => {
                        alert('Error updating visibility: ' + error.message);
                        btn.disabled = false;
                    });
            }
        });

        // Handle form submission
        document.getElementById('project-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-project-btn');
            const spinner = document.getElementById('submit-spinner');
            const form = this;

            // Clear previous errors and alerts
            clearProjectValidationErrors();
            document.getElementById('project-alert-container').innerHTML = '';

            const formData = new FormData(form);

            // Client-side validation
            console.log('Validating project form data...');
            const validation = validateProjectForm(formData);
            if (!validation.isValid) {
                showProjectValidationErrors(validation.errors);

                // Show validation error alert with details
                const errorCount = Object.keys(validation.errors).length;
                showProjectAlert(`Please fill in all required fields (${errorCount} errors found)`,
                    'danger');

                console.log('Validation failed:', validation.errors);

                // Scroll to first error
                const firstErrorField = document.querySelector('.is-invalid');
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstErrorField.focus();
                }

                return; // Stop form submission
            }

            console.log('Validation passed, submitting project form...');

            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('=== SERVER RESPONSE ===');
                    console.log('Full response:', data);
                    console.log('Success value:', data.success);
                    console.log('Message:', data.message);

                    // Force alert to show regardless of response
                    console.log('About to call showProjectAlert...');

                    if (data.success) {
                        console.log('SUCCESS - calling showProjectAlert');
                        showProjectAlert('Project added successfully!', 'success');

                        // Delay form clearing to ensure alert is shown
                        setTimeout(() => {
                            clearProjectForm(); // Clear form and validation errors
                            fetchProjects(); // Refresh the projects list
                        }, 2000); // Increased delay
                    } else {
                        console.log('FAILURE - showing error');
                        if (data.errors) {
                            showProjectValidationErrors(data.errors);
                            const errorCount = Object.keys(data.errors).length;
                            showProjectAlert(
                                `Found ${errorCount} validation errors. Please check the form.`,
                                'danger');
                        } else {
                            showProjectAlert(data.message ||
                                'Failed to add project. Please try again.', 'danger');
                        }
                    }

                    console.log('showProjectAlert should have been called by now');
                })
                .catch(error => {
                    console.error('=== FETCH ERROR ===');
                    console.error('Error details:', error);
                    showProjectAlert('Network error: ' + error.message, 'danger');
                })
                .finally(() => {
                    // Reset loading state
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                });
        });

        document.getElementById('view-all-projects-btn').addEventListener('click', function(e) {
            e.preventDefault();
            fetchAllProjectsModal();
        });
    });

    function fetchAllProjectsModal() {
        fetch('/admin/projects?all=1', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Backend already returns projects in descending order by id
            let html = '<h5>All Projects</h5>';
            html += '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div><strong>Description:</strong> ${item.description}</div>
                    <div><strong>Started At:</strong> <small>${item.start_date ?? ''}</small></div>
                    <div><strong>Ended At:</strong> <small>${item.end_date ?? ''}</small></div>
                    <div><strong>Status:</strong> <small>${item.status ?? ''}</small></div>
                    <div><strong>Budget:</strong> <small>${item.budget ?? ''}</small></div>
                    <div><strong>Lead Researcher:</strong> <small>${item.lead_researcher ?? ''}</small></div>
                    <div><strong>Active:</strong> <span class="badge ${item.is_active ? 'bg-success' : 'bg-secondary'}">${item.is_active ? 'Active' : 'Inactive'}</span></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank"><img src="${convertGoogleDriveUrl(item.image)}" style="max-width: 50px; max-height: 50px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}">${item.is_visible ? 'Visible' : 'Hidden'}</span></div>
                </li>`;
            });
            html += '</ul>';
            // Show in modal
            document.getElementById('project-details-body').innerHTML = html;
            var modalEl = document.getElementById('projectDetailsModal');
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
                var closeBtn = modalEl.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.onclick = function() {
                        modalEl.style.display = 'none';
                        modalEl.classList.remove('show');
                    };
                }
            }
        })
        .catch(() => {
                document.getElementById('project-details-body').innerHTML =
                    '<div class="text-danger">Error loading all projects.</div>';
            var modalEl = document.getElementById('projectDetailsModal');
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
            }
        });
    }

    function fetchProjects() {
        const projectsList = document.getElementById('projects-list');
        fetch('/admin/projects', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div class='d-none'><strong>Description:</strong> ${item.description}</div>
                    <div><strong>Started At:</strong> <small>${item.start_date ?? ''}</small></div>
                    <div><strong>Ended At:</strong> <small>${item.end_date ?? ''}</small></div>
                    <div><strong>Status:</strong> <small>${item.status ?? ''}</small></div>
                    <div class="d-none"><strong>Budget:</strong> <small>${item.budget ?? ''}</small></div>
                    <div class="d-none"><strong>Lead Researcher:</strong> <small>${item.lead_researcher ?? ''}</small></div>
                    <div><strong>Active:</strong> <span class="badge ${item.is_active ? 'bg-success' : 'bg-secondary'}">${item.is_active ? 'Active' : 'Inactive'}</span></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank"><img src="${convertGoogleDriveUrl(item.image)}" style="max-width: 50px; max-height: 50px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                        <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                        <button class="btn btn-sm btn-primary ms-2 view-more-btn"
                            data-id="${item.id}"
                            data-title="${encodeURIComponent(item.title)}"
                            data-description="${encodeURIComponent(item.description)}"
                            data-image="${encodeURIComponent(item.image ?? '')}"
                            data-started="${encodeURIComponent(item.start_date ?? '')}"
                            data-ended="${encodeURIComponent(item.end_date ?? '')}"
                            data-status="${encodeURIComponent(item.status ?? '')}"
                            data-budget="${encodeURIComponent(item.budget ?? '')}"
                            data-leadresearcher="${encodeURIComponent(item.lead_researcher ?? '')}"
                            data-leader="${encodeURIComponent(item.leader ?? '')}"
                            data-fundingagency="${encodeURIComponent(item.funding_agency ?? '')}"
                            data-active="${item.is_active}"
                            data-visible="${item.is_visible}"
                            data-addedat="${encodeURIComponent(item.created_at ?? '')}"
                            data-addedby="${encodeURIComponent(item.added_by ?? 'Unknown')}"
                        >View More</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            projectsList.innerHTML = html;

                // Update count info
                const countInfo = document.getElementById('projects-count-info');
                if (countInfo) {
                    countInfo.textContent = `Total: ${data.length}, Displayed: ${data.length}`;
                }

            // Add event listeners for view more buttons
            document.querySelectorAll('.view-more-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                        const title = decodeURIComponent(this.getAttribute('data-title'));
                        const description = decodeURIComponent(this.getAttribute(
                            'data-description'));
                        const image = decodeURIComponent(this.getAttribute('data-image'));
                        const started = decodeURIComponent(this.getAttribute('data-started'));
                        const ended = decodeURIComponent(this.getAttribute('data-ended'));
                        const status = decodeURIComponent(this.getAttribute('data-status'));
                        const budget = decodeURIComponent(this.getAttribute('data-budget'));
                        const leadResearcher = decodeURIComponent(this.getAttribute(
                            'data-leadresearcher'));
                        const leader = decodeURIComponent(this.getAttribute('data-leader') || '');
                        const fundingAgency = decodeURIComponent(this.getAttribute(
                            'data-fundingagency') || '');
                        const active = this.getAttribute('data-active') == '1';
                        const visible = this.getAttribute('data-visible') == '1';
                        const addedAtRaw = decodeURIComponent(this.getAttribute('data-addedat'));
                        const addedBy = decodeURIComponent(this.getAttribute('data-addedby'));
                        const addedAt = addedAtRaw ? dayjs(addedAtRaw).fromNow() : '';

                        let modalHtml = `<div class="mb-3"><strong>Title:</strong> ${title}</div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Description:</strong><br><div class="p-2 bg-light rounded">${description}</div></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Start Date:</strong> <span class="badge bg-primary">${started || 'Not set'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>End Date:</strong> <span class="badge bg-${ended ? 'secondary' : 'warning'}">${ended || 'Ongoing'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Status:</strong> <span class="badge bg-info">${status || 'Not set'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Budget:</strong> <span class="badge bg-success">${budget ? '$' + budget : 'Not specified'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Lead Researcher:</strong> <span class="text-primary">${leadResearcher || 'Not assigned'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Project Leader:</strong> <span class="text-secondary">${leader || 'Not assigned'}</span></div>`;
                        if (fundingAgency) {
                            modalHtml +=
                                `<div class="mb-3"><strong>Funding Agency:</strong> <span class="text-info">${fundingAgency}</span></div>`;
                        }
                        modalHtml +=
                            `<div class="mb-3"><strong>Added At:</strong> <small class="text-muted">${addedAt}</small></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Added By:</strong> <small class="text-muted">${addedBy}</small></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Image:</strong> ${image ? `<br><div class="mt-2"><a href="${image}" target="_blank"><img src="${convertGoogleDriveUrl(image)}" style="max-width: 300px; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;" class="btn btn-outline-primary btn-sm">🔗 View Original Link</span></a></div>` : '<span class="text-muted">No image</span>'}</div>`;
                        modalHtml +=
                            `<div class="mb-2"><strong>Active Status:</strong> <span class="badge ${active ? 'bg-success' : 'bg-secondary'}">${active ? 'Active' : 'Inactive'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-2"><strong>Visibility:</strong> <span class="badge ${visible ? 'bg-success' : 'bg-secondary'}">${visible ? 'Visible' : 'Hidden'}</span></div>`;

                        document.getElementById('project-details-body').innerHTML = modalHtml;

                        // Bootstrap 5 modal logic fallback
                        var modalEl = document.getElementById('projectDetailsModal');
                        if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                            modal.show();
                        } else {
                            // fallback for missing bootstrap js
                            modalEl.style.display = 'block';
                            modalEl.classList.add('show');
                            // Add close button logic for fallback
                            var closeBtn = modalEl.querySelector('.btn-close');
                            if (closeBtn) {
                                closeBtn.onclick = function() {
                                    modalEl.style.display = 'none';
                                    modalEl.classList.remove('show');
                                };
                            }
                        }
                    });
                });
                // Event delegation is now handled outside fetchProjects function
        })
        .catch(() => {
            projectsList.innerHTML = '<div class="text-danger">Error loading projects.</div>';
        });
    }
</script>
