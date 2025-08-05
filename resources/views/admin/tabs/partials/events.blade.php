<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Event</div>
            <div class="card-body">
                <!-- Alert container -->
                <div id="event-alert-container"></div>

                <form id="events-form" action="{{ url('/admin/events') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="event-title" class="form-label">
                            Title <span class="text-danger">*</span>
                            <small class="text-muted">(3-255 characters)</small>
                        </label>
                        <input type="text" class="form-control" id="event-title" name="title" required
                            placeholder="Enter event title..." minlength="3" maxlength="255">
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-description" class="form-label">
                            Description <span class="text-danger">*</span>
                            <small class="text-muted">(minimum 10 characters)</small>
                        </label>
                        <textarea class="form-control" id="event-description" name="description" rows="4" required
                            placeholder="Enter event description..." minlength="10"></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-location" class="form-label">
                            Location <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="event-location" name="location" required
                            placeholder="Enter event location">
                        <div class="invalid-feedback" id="location-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-start-date" class="form-label">
                            Start Date <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control" id="event-start-date" name="start_date" required>
                        <div class="invalid-feedback" id="start_date-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-end-date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="event-end-date" name="end_date">
                        <div class="form-text">
                            <small class="text-muted">Optional - leave blank if event duration is not set</small>
                        </div>
                        <div class="invalid-feedback" id="end_date-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-organizer" class="form-label">
                            Organizer <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="event-organizer" name="organizer" required
                            placeholder="Enter organizer name">
                        <div class="invalid-feedback" id="organizer-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-image-url" class="form-label">
                            Image URL <span class="text-danger">*</span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="event-image-url" name="image_url" required
                            placeholder="https://drive.google.com/file/d/.../view">
                        <div class="form-text">
                            <strong>Google Drive Instructions:</strong><br>
                            1. Right-click your image in Google Drive → "Get link"<br>
                            2. Set to "Anyone with the link" can view<br>
                            3. Copy and paste the sharing link here
                        </div>
                        <div class="invalid-feedback" id="image_url-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="event-visible" class="form-label">
                            Visibility <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="event-visible" name="is_visible" required>
                            <option value="">-- Select Visibility --</option>
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                        <div class="invalid-feedback" id="is_visible-error"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submit-event-btn">
                            <span class="spinner-border spinner-border-sm d-none" id="submit-spinner"></span>
                            <i class="fas fa-plus"></i> Add Event
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearEventForm()">
                            <i class="fas fa-times"></i> Clear Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-left">
                    <h4 class="mb-0 me-3">Recent Events</h4>
                </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-events-btn">View All Events</button>

            </div>
            <div class="text-muted align-items-right" id="events-count-info" style="font-size:1rem;"></div>

            <div class="card-body p-2">
                <div id="events-list"></div>

                <!-- Day.js CDN for human readable dates -->
                <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
                <script>dayjs.extend(dayjs_plugin_relativeTime);</script>
                <!-- Event Details Modal -->
                <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="event-details-body">
                                <!-- Details will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    fetchEvents();
    setInterval(fetchEvents, 5000);

    // Handle hide/show visibility buttons for events (event delegation)
    document.getElementById('events-list').addEventListener('click', function(e) {
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
            fetch(`/admin/events/${id}/visibility`, {
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
                    badge.className = 'badge ' + (newVisible ? 'bg-success' : 'bg-secondary');
                }
                btn.textContent = newVisible ? 'Hide' : 'Show';
                btn.className = 'btn btn-sm btn-outline-' + (newVisible ? 'secondary' : 'success') + ' ms-2 toggle-visibility-btn';
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
    document.getElementById('events-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-event-btn');
        const spinner = document.getElementById('submit-spinner');
        const form = this;

        // Clear previous errors and alerts
        clearEventValidationErrors();
        document.getElementById('event-alert-container').innerHTML = '';

        const formData = new FormData(form);

        // Client-side validation
        console.log('Validating event form data...');
        const validation = validateEventForm(formData);
        if (!validation.isValid) {
            showEventValidationErrors(validation.errors);

            // Show validation error alert with details
            const errorCount = Object.keys(validation.errors).length;
            showEventAlert(`Please fill in all required fields (${errorCount} errors found)`,
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

        console.log('Validation passed, submitting event form...');

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
                console.log('About to call showEventAlert...');

                if (data.success) {
                    console.log('SUCCESS - calling showEventAlert');
                    showEventAlert('Event added successfully!', 'success');

                    // Delay form clearing to ensure alert is shown
                    setTimeout(() => {
                        clearEventForm(); // Clear form and validation errors
                        fetchEvents(); // Refresh the events list
                    }, 2000); // Increased delay
                } else {
                    console.log('FAILURE - showing error');
                    if (data.errors) {
                        showEventValidationErrors(data.errors);
                        const errorCount = Object.keys(data.errors).length;
                        showEventAlert(
                            `Found ${errorCount} validation errors. Please check the form.`,
                            'danger');
                    } else {
                        showEventAlert(data.message ||
                            'Failed to add event. Please try again.', 'danger');
                    }
                }

                console.log('showEventAlert should have been called by now');
            })
            .catch(error => {
                console.error('=== FETCH ERROR ===');
                console.error('Error details:', error);
                showEventAlert('Network error: ' + error.message, 'danger');
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
    });

    document.getElementById('view-all-events-btn').addEventListener('click', function(e) {
        e.preventDefault();
        fetchAllEventsModal();
    });
});







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
function showEventAlert(message, type = 'info') {
    console.log('showEventAlert called with:', message, type);

    // ALWAYS show native alert first for immediate feedback
    if (type === 'success') {
        alert('✅ SUCCESS: ' + message);
    } else if (type === 'danger') {
        alert('❌ ERROR: ' + message);
    } else {
        alert('ℹ️ INFO: ' + message);
    }

    // Also try to show Bootstrap alert
    const alertContainer = document.getElementById('event-alert-container');
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
function clearEventValidationErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}

// Clear form function
function clearEventForm() {
    const form = document.getElementById('events-form');
    form.reset();
    clearEventValidationErrors();
    document.getElementById('event-alert-container').innerHTML = '';
    console.log('Event form cleared');
}

// Show validation errors
function showEventValidationErrors(errors) {
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
function validateEventForm(formData) {
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

    // Validate location (required)
    const location = formData.get('location')?.trim();
    if (!location) {
        errors.location = ['Location is required'];
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

    // Validate organizer (required)
    const organizer = formData.get('organizer')?.trim();
    if (!organizer) {
        errors.organizer = ['Organizer is required'];
    }

    // Validate image_url (required)
    const imageUrl = formData.get('image_url')?.trim();
    if (!imageUrl) {
        errors.image_url = ['Image URL is required'];
    } else {
        try {
            new URL(imageUrl);
        } catch (e) {
            errors.image_url = ['Please enter a valid URL'];
        }
    }

    // Validate is_visible (required)
    const isVisible = formData.get('is_visible');
    if (isVisible === null || isVisible === '' || isVisible === undefined) {
        errors.is_visible = ['Visibility selection is required'];
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors: errors
    };
}

function fetchAllEventsModal() {
    fetch('/admin/events?all=1', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Backend already returns events in descending order by id
            let html = '<h5>All Events</h5>';
            html += '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div><strong>Description:</strong> ${item.description}</div>
                    <div><strong>Location:</strong> <small>${item.location ?? ''}</small></div>
                    <div><strong>Start Date:</strong> <small>${item.start_date ?? ''}</small></div>
                    <div><strong>End Date:</strong> <small>${item.end_date ?? ''}</small></div>
                    <div><strong>Organizer:</strong> <small>${item.organizer ?? ''}</small></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image_url ? `<a href="${item.image_url}" target="_blank"><img src="${convertGoogleDriveUrl(item.image_url)}" style="max-width: 50px; max-height: 50px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}">${item.is_visible ? 'Visible' : 'Hidden'}</span></div>
                </li>`;
            });
            html += '</ul>';
            // Show in modal
            document.getElementById('event-details-body').innerHTML = html;
            var modalEl = document.getElementById('eventDetailsModal');
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
            document.getElementById('event-details-body').innerHTML =
                '<div class="text-danger">Error loading all events.</div>';
            var modalEl = document.getElementById('eventDetailsModal');
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
        });
}

function fetchEvents() {
    const eventsList = document.getElementById('events-list');
    fetch('/admin/events', {
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
                <div><strong>Location:</strong> <small>${item.location ?? ''}</small></div>
                <div><strong>Start Date:</strong> <small>${item.start_date ?? ''}</small></div>
                <div><strong>End Date:</strong> <small>${item.end_date ?? ''}</small></div>
                <div><strong>Organizer:</strong> <small>${item.organizer ?? ''}</small></div>
                <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                <div><strong>Image:</strong> ${item.image_url ? `<a href="${item.image_url}" target="_blank"><img src="${convertGoogleDriveUrl(item.image_url)}" style="max-width: 50px; max-height: 50px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>
                <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                    <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                    <button class="btn btn-sm btn-primary ms-2 view-more-btn"
                        data-id="${item.id}"
                        data-title="${encodeURIComponent(item.title)}"
                        data-description="${encodeURIComponent(item.description)}"
                        data-location="${encodeURIComponent(item.location ?? '')}"
                        data-started="${encodeURIComponent(item.start_date ?? '')}"
                        data-ended="${encodeURIComponent(item.end_date ?? '')}"
                        data-organizer="${encodeURIComponent(item.organizer ?? '')}"
                        data-imageurl="${encodeURIComponent(item.image_url ?? '')}"
                        data-visible="${item.is_visible}"
                        data-addedat="${encodeURIComponent(item.created_at ?? '')}"
                        data-addedby="${encodeURIComponent(item.added_by ?? 'Unknown')}"
                    >View More</button>
                </div>
            </li>`;
        });
        html += '</ul>';
        eventsList.innerHTML = html;

        // Update count info
        const countInfo = document.getElementById('events-count-info');
        if (countInfo) {
            countInfo.textContent = `Total Events: ${data.length}`;
        }

        // Add event listeners for view more buttons
        document.querySelectorAll('.view-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const title = decodeURIComponent(this.getAttribute('data-title'));
                const description = decodeURIComponent(this.getAttribute('data-description'));
                const location = decodeURIComponent(this.getAttribute('data-location'));
                const started = decodeURIComponent(this.getAttribute('data-started'));
                const ended = decodeURIComponent(this.getAttribute('data-ended'));
                const organizer = decodeURIComponent(this.getAttribute('data-organizer'));
                const imageUrl = decodeURIComponent(this.getAttribute('data-imageurl'));
                const visible = this.getAttribute('data-visible') == '1';
                const addedAtRaw = decodeURIComponent(this.getAttribute('data-addedat'));
                const addedBy = decodeURIComponent(this.getAttribute('data-addedby'));
                const addedAt = addedAtRaw ? dayjs(addedAtRaw).fromNow() : '';

                let modalHtml = `<div class="mb-3"><strong>Title:</strong> ${title}</div>`;
                modalHtml += `<div class="mb-3"><strong>Description:</strong><br><div class="p-2 bg-light rounded">${description}</div></div>`;
                modalHtml += `<div class="mb-3"><strong>Location:</strong> <span class="badge bg-info">${location || 'Not set'}</span></div>`;
                modalHtml += `<div class="mb-3"><strong>Start Date:</strong> <span class="badge bg-primary">${started || 'Not set'}</span></div>`;
                modalHtml += `<div class="mb-3"><strong>End Date:</strong> <span class="badge bg-${ended ? 'secondary' : 'warning'}">${ended || 'Not set'}</span></div>`;
                modalHtml += `<div class="mb-3"><strong>Organizer:</strong> <span class="text-primary">${organizer || 'Not assigned'}</span></div>`;
                modalHtml += `<div class="mb-3"><strong>Added At:</strong> <small class="text-muted">${addedAt}</small></div>`;
                modalHtml += `<div class="mb-3"><strong>Added By:</strong> <small class="text-muted">${addedBy}</small></div>`;
                modalHtml += `<div class="mb-3"><strong>Image:</strong> ${imageUrl ? `<br><div class="mt-2"><a href="${imageUrl}" target="_blank"><img src="${convertGoogleDriveUrl(imageUrl)}" style="max-width: 300px; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;" class="btn btn-outline-primary btn-sm">🔗 View Original Link</span></a></div>` : '<span class="text-muted">No image</span>'}</div>`;
                modalHtml += `<div class="mb-2"><strong>Visibility:</strong> <span class="badge ${visible ? 'bg-success' : 'bg-secondary'}">${visible ? 'Visible' : 'Hidden'}</span></div>`;

                document.getElementById('event-details-body').innerHTML = modalHtml;

                // Bootstrap 5 modal logic fallback
                var modalEl = document.getElementById('eventDetailsModal');
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
    })
    .catch(() => {
        eventsList.innerHTML = '<div class="text-danger">Error loading events.</div>';
    });
}
</script>
