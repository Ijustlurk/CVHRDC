<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Publication</div>
            <div class="card-body">
                <!-- Alert container -->
                <div id="publication-alert-container"></div>

                <form id="publications-form">
                    <div class="mb-3">
                        <label for="publication-title" class="form-label">
                            Title <span class="text-danger">*</span>
                            <small class="text-muted">(3-255 characters)</small>
                        </label>
                        <input type="text" class="form-control" id="publication-title" name="title" required
                               placeholder="Enter publication title..." minlength="3" maxlength="255">
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="publication-description" class="form-label">
                            Description <span class="text-danger">*</span>
                            <small class="text-muted">(minimum 10 characters)</small>
                        </label>
                        <textarea class="form-control" id="publication-description" name="description" rows="4" required
                                  placeholder="Enter publication description..." minlength="10"></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="publication-author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="publication-author" name="author"
                               placeholder="Enter author name (optional)">
                        <div class="invalid-feedback" id="author-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="publication-published-at" class="form-label">Published At</label>
                        <input type="datetime-local" class="form-control" id="publication-published-at"
                               name="published_at">
                        <div class="invalid-feedback" id="published_at-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="publication-type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="publication-type" name="type"
                               placeholder="e.g., Journal, Article, Book">
                        <div class="invalid-feedback" id="type-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="publication-file-url" class="form-label">
                            File URL <span class="text-danger">*</span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="publication-file-url" name="file_url" required
                               placeholder="https://drive.google.com/file/d/FILE_ID/view">
                        <div class="form-text">
                            <strong>Google Drive Instructions:</strong><br>
                            1. Right-click your file in Google Drive → "Get link"<br>
                            2. Set to "Anyone with the link" can view<br>
                            3. Copy and paste the sharing link here
                        </div>
                        <div class="invalid-feedback" id="file_url-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="publication-tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="publication-tags" name="tags"
                               placeholder="Comma-separated tags (optional)">
                        <div class="invalid-feedback" id="tags-error"></div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="publication-visible" name="is_visible" checked>
                        <label class="form-check-label" for="publication-visible">Visible</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submit-publication-btn">
                            <span class="spinner-border spinner-border-sm d-none" id="publication-submit-spinner"></span>
                            <i class="fas fa-plus"></i> Add Publication
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearPublicationForm()">
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
                <h4 class="mb-0 me-3">Recent Publications</h4>
            </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-publications-btn">View All
                Publications</button>
        </div>
        <div class="text-muted align-items-right" id="publications-count-info" style="font-size:1rem;"></div>
        <div class="card-body p-2">
            <div id="publications-list"></div>
            <!-- Day.js CDN for human readable dates -->
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
            <script>
                dayjs.extend(dayjs_plugin_relativeTime);
            </script>
            <!-- Publications Details Modal -->
            <div class="modal fade" id="publicationsDetailsModal" tabindex="-1"
                aria-labelledby="publicationsDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="publicationsDetailsModalLabel">Publication Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="publications-details-body">
                            <!-- Details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Google Drive URL normalizer for files
    function normalizeGoogleDriveUrl(url) {
        if (!url) return '';
        try {
            const u = new URL(url);
            if (!/\.google\.com$/.test(u.hostname)) return url; // keep as is if not Google domain

            // Extract file id from common patterns
            let fileId = null;
            let m = url.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
            if (m) fileId = m[1];
            if (!fileId) {
                m = url.match(/[?&#]id=([a-zA-Z0-9_-]+)/);
                if (m) fileId = m[1];
            }

            // Prefer standard view link; fallback to original
            if (fileId) {
                return `https://drive.google.com/file/d/${fileId}/view`;
            }
            return url;
        } catch (_) {
            return url;
        }
    }

    // Publications: UI helpers (alerts, validation, clearing)
    function showPublicationAlert(message, type = 'info') {
        const alertContainer = document.getElementById('publication-alert-container');
        if (!alertContainer) {
            alert(message);
            return;
        }
        alertContainer.innerHTML = '';
        const alertClass = type === 'success' ? 'alert-success' : type === 'danger' ? 'alert-danger' : type === 'warning' ? 'alert-warning' : 'alert-info';
        const html = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <strong>${type === 'success' ? 'Success!' : type === 'danger' ? 'Error!' : 'Info:'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        alertContainer.innerHTML = html;
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        setTimeout(() => {
            const a = alertContainer.querySelector('.alert');
            if (a) a.remove();
        }, 5000);
    }

    function clearPublicationValidationErrors() {
        document.querySelectorAll('#publications-form .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('#publications-form .invalid-feedback').forEach(el => el.textContent = '');
    }

    function showPublicationValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`#publications-form [name="${field}"]`);
            const errorDiv = document.getElementById(`${field}-error`);
            if (input) input.classList.add('is-invalid');
            if (errorDiv) errorDiv.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
        });
    }

    function validatePublicationForm(formData) {
        const errors = {};
        const title = formData.get('title')?.trim();
        if (!title) errors.title = ['Title is required'];
        else if (title.length < 3) errors.title = ['Title must be at least 3 characters'];
        else if (title.length > 255) errors.title = ['Title must not exceed 255 characters'];

        const description = formData.get('description')?.trim();
        if (!description) errors.description = ['Description is required'];
        else if (description.length < 10) errors.description = ['Description must be at least 10 characters'];

        const fileUrl = formData.get('file_url')?.trim();
        if (!fileUrl) errors.file_url = ['File URL is required'];
        else {
            try { new URL(fileUrl); } catch { errors.file_url = ['Please enter a valid URL']; }
        }

        return { isValid: Object.keys(errors).length === 0, errors };
    }

    function clearPublicationForm() {
        const form = document.getElementById('publications-form');
        form.reset();
        clearPublicationValidationErrors();
        const container = document.getElementById('publication-alert-container');
        if (container) container.innerHTML = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchPublications();
        setInterval(fetchPublications, 5000);
        document.getElementById('publications-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-publication-btn');
            const spinner = document.getElementById('publication-submit-spinner');

            // Clear previous errors and alerts
            clearPublicationValidationErrors();
            const alertContainer = document.getElementById('publication-alert-container');
            if (alertContainer) alertContainer.innerHTML = '';

            // Client-side validation
            const form = this;
            const formData = new FormData(form);
            const validation = validatePublicationForm(formData);
            if (!validation.isValid) {
                showPublicationValidationErrors(validation.errors);
                const errorCount = Object.keys(validation.errors).length;
                showPublicationAlert(`Please fill in all required fields (${errorCount} errors found)`, 'danger');
                const firstErrorField = document.querySelector('#publications-form .is-invalid');
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorField.focus();
                }
                return;
            }

            // Loading state
            if (submitBtn) submitBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');

            fetch('/admin/publications', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: document.getElementById('publication-title').value,
                    description: document.getElementById('publication-description').value,
                    author: document.getElementById('publication-author').value,
                    published_at: document.getElementById('publication-published-at').value,
                    type: document.getElementById('publication-type').value,
                    file_url: document.getElementById('publication-file-url').value,
                    tags: document.getElementById('publication-tags').value,
                    is_visible: document.getElementById('publication-visible').checked ? 1 : 0
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().catch(() => ({})).then(json => {
                        if (json && json.errors) {
                            showPublicationValidationErrors(json.errors);
                            const errorCount = Object.keys(json.errors).length;
                            showPublicationAlert(`Found ${errorCount} validation errors. Please check the form.`, 'danger');
                        }
                        throw new Error(json.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(() => {
                showPublicationAlert('Publication added successfully!', 'success');
                setTimeout(() => {
                    clearPublicationForm();
                    fetchPublications();
                }, 1200);
            })
            .catch(error => {
                showPublicationAlert('Error adding publication: ' + error.message, 'danger');
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            });
        });
        // View all publications handler
        const viewAllBtn = document.getElementById('view-all-publications-btn');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('/admin/publications?all=1', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        let html = '<h5>All Publications</h5>';
                        html += '<ul class="list-group">';
                        data.forEach(item => {
                            const addedAt = item.created_at ? dayjs(item.created_at)
                                .fromNow() : '';
                            const fileUrl = item.file_url ? normalizeGoogleDriveUrl(item
                                .file_url) : '';
                            html += `<li class="list-group-item bg-light mb-2">
                            <div><strong>Title:</strong> ${item.title}</div>
                            <div><strong>Description:</strong> ${item.description ?? ''}</div>
                            <div><strong>Author:</strong> <small>${item.author ?? ''}</small></div>
                            <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                            <div><strong>Type:</strong> <small>${item.type ?? ''}</small></div>
                            <div><strong>File:</strong> ${fileUrl ? `<a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm">View File</a>` : 'No file'}</div>
                            <div><strong>Tags:</strong> <small>${item.tags ?? ''}</small></div>
                            <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}">${item.is_visible ? 'Visible' : 'Hidden'}</span></div>
                            <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                        </li>`;
                        });
                        html += '</ul>';
                        document.getElementById('publications-details-body').innerHTML = html;
                        const modalEl = document.getElementById('publicationsDetailsModal');
                        if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                            bootstrap.Modal.getOrCreateInstance(modalEl).show();
                        } else {
                            modalEl.style.display = 'block';
                            modalEl.classList.add('show');
                            const closeBtn = modalEl.querySelector('.btn-close');
                            if (closeBtn) {
                                closeBtn.onclick = function() {
                                    modalEl.style.display = 'none';
                                    modalEl.classList.remove('show');
                                };
                            }
                        }
                    })
                    .catch(() => {
                        document.getElementById('publications-details-body').innerHTML =
                            '<div class="text-danger">Error loading publications.</div>';
                        const modalEl = document.getElementById('publicationsDetailsModal');
                        if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                            bootstrap.Modal.getOrCreateInstance(modalEl).show();
                        } else {
                            modalEl.style.display = 'block';
                            modalEl.classList.add('show');
                        }
                    });
            });
        }
    });

    function fetchPublications() {
        const publicationsList = document.getElementById('publications-list');
        fetch('/admin/publications', {
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
                <div><strong>Author:</strong> <small>${item.author ?? ''}</small></div>
                <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                <div><strong>Type:</strong> <small>${item.type ?? ''}</small></div>
                <div><strong>File:</strong> ${item.file_url ? `<a href="${normalizeGoogleDriveUrl(item.file_url)}" target="_blank" class="btn btn-outline-primary btn-sm">View File</a>` : 'No file'}</div>
                <div><strong>Tags:</strong> <small>${item.tags ?? ''}</small></div>
                <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                    <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                    <button class="btn btn-sm btn-primary ms-2 view-more-btn"
                        data-id="${item.id}"
                        data-title="${encodeURIComponent(item.title)}"
                        data-description="${encodeURIComponent(item.description)}"
                        data-author="${encodeURIComponent(item.author ?? '')}"
                        data-publishedat="${encodeURIComponent(item.published_at ?? '')}"
                        data-type="${encodeURIComponent(item.type ?? '')}"
                        data-fileurl="${encodeURIComponent(item.file_url ?? '')}"
                        data-tags="${encodeURIComponent(item.tags ?? '')}"
                        data-visible="${item.is_visible}"
                        data-addedat="${encodeURIComponent(item.created_at ?? '')}"
                    >View More</button>
                </div>
            </li>`;
                });
                html += '</ul>';
                publicationsList.innerHTML = html;
                // Update count info
                const countInfo = document.getElementById('publications-count-info');
                if (countInfo) {
                    countInfo.textContent = `Total: ${data.length}, Displayed: ${data.length}`;
                }
                // Add event listeners for view more buttons
                document.querySelectorAll('.view-more-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const title = decodeURIComponent(this.getAttribute('data-title'));
                        const description = decodeURIComponent(this.getAttribute(
                            'data-description'));
                        const author = decodeURIComponent(this.getAttribute('data-author'));
                        const publishedAt = decodeURIComponent(this.getAttribute(
                            'data-publishedat'));
                        const type = decodeURIComponent(this.getAttribute('data-type'));
                        const fileUrlRaw = decodeURIComponent(this.getAttribute('data-fileurl'));
                        const tags = decodeURIComponent(this.getAttribute('data-tags'));
                        const visible = this.getAttribute('data-visible') == '1';
                        const addedAtRaw = decodeURIComponent(this.getAttribute('data-addedat'));
                        const addedAt = addedAtRaw ? dayjs(addedAtRaw).fromNow() : '';

                        const fileUrl = normalizeGoogleDriveUrl(fileUrlRaw);

                        let modalHtml = `<div class="mb-3"><strong>Title:</strong> ${title}</div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Description:</strong><br><div class="p-2 bg-light rounded">${description}</div></div>`;
                        modalHtml +=
                            `<div class="mb-2"><strong>Author:</strong> <span class="text-primary">${author || 'Unknown'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-2"><strong>Published At:</strong> <span class="badge bg-secondary">${publishedAt || 'Not set'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-2"><strong>Type:</strong> <span class="badge bg-info">${type || 'Not set'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>File:</strong> ${fileUrl ? `<a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm">🔗 View File</a>` : '<span class="text-muted">No file</span>'}</div>`;
                        if (tags) {
                            modalHtml +=
                                `<div class="mb-2"><strong>Tags:</strong> <small>${tags}</small></div>`;
                        }
                        modalHtml +=
                            `<div class="mb-2"><strong>Visibility:</strong> <span class="badge ${visible ? 'bg-success' : 'bg-secondary'}">${visible ? 'Visible' : 'Hidden'}</span></div>`;
                        modalHtml +=
                            `<div class="mb-3"><strong>Added At:</strong> <small class="text-muted">${addedAt}</small></div>`;

                        document.getElementById('publications-details-body').innerHTML = modalHtml;
                        const modalEl = document.getElementById('publicationsDetailsModal');
                        if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                            bootstrap.Modal.getOrCreateInstance(modalEl).show();
                        } else {
                            modalEl.style.display = 'block';
                            modalEl.classList.add('show');
                            const closeBtn = modalEl.querySelector('.btn-close');
                            if (closeBtn) {
                                closeBtn.onclick = function() {
                                    modalEl.style.display = 'none';
                                    modalEl.classList.remove('show');
                                };
                            }
                        }
                    });
                });
                // Use event delegation for toggle-visibility-btn (with closest)
                publicationsList.addEventListener('click', function(e) {
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
                        fetch(`/admin/publications/${id}/visibility`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    is_visible: newVisible
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.text().then(text => {
                                        throw new Error(text || 'Network response was not ok');
                                    });
                                }
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
            })
            .catch(() => {
                publicationsList.innerHTML = '<div class="text-danger">Error loading publications.</div>';
            });
    }
</script>
