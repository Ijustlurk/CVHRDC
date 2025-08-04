<div class="row">
    <div class="col-md-6">

                <div class="card mb-3">
                    <div class="card-header">Add News</div>
                    <div class="card-body">
                        
                        <!-- Alert container -->
                        <div id="news-alert-container"></div>

                       <form id="news-form" action="{{ url('/admin/news') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="news-title" class="form-label">
                                    Title <span class="text-danger">*</span>
                                    <small class="text-muted">(3-255 characters)</small>
                                </label>
                                <textarea class="form-control" id="news-title" name="title" rows="2" required 
                                          placeholder="Enter news title..." minlength="3" maxlength="255"></textarea>
                                <div class="invalid-feedback" id="title-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="news-content" class="form-label">
                                    Content <span class="text-danger">*</span>
                                    <small class="text-muted">(minimum 10 characters)</small>
                                </label>
                                <textarea class="form-control" id="news-content" name="content" rows="6" required 
                                          placeholder="Enter news content..." minlength="10"></textarea>
                                <div class="invalid-feedback" id="content-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="news-image" class="form-label">
                                    Image URL <span class="text-danger">*</span>
                                    <small class="text-muted">(Google Drive link)</small>
                                </label>
                                <input type="url" class="form-control" id="news-image" name="image" required
                                       placeholder="https://drive.google.com/file/d/.../view">
                                <div class="form-text">
                                    <strong>Google Drive Instructions:</strong><br>
                                    1. Right-click your image in Google Drive → "Get link"<br>
                                    2. Set to "Anyone with the link" can view<br>
                                    3. Copy and paste the sharing link here<br>
                                    <small class="text-muted">The system will automatically convert it to display format.</small>
                                </div>
                                <div class="invalid-feedback" id="image-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="news-published-at" class="form-label">
                                    Published At <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" class="form-control" id="news-published-at" name="published_at" required>
                                <div class="form-text">
                                    <small class="text-muted">Select the date and time when this news should be published</small>
                                </div>
                                <div class="invalid-feedback" id="published_at-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="news-visible" class="form-label">
                                    Visibility <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="news-visible" name="is_visible" required>
                                    <option value="">-- Select Visibility --</option>
                                    <option value="1">Visible</option>
                                    <option value="0">Hidden</option>
                                </select>
                                <div class="invalid-feedback" id="is_visible-error"></div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submit-news-btn">
                                    <span class="spinner-border spinner-border-sm d-none" id="submit-spinner"></span>
                                    <i class="fas fa-plus"></i> Add News
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                    <i class="fas fa-times"></i> Clear Form
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="testAlerts()">
                                    <i class="fas fa-flask"></i> Test Alerts
                                </button>
                            </div>
                        </form>


                    </div>
                </div>


</div>
<div class="col-md-6">


        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Recent News</h4>
            </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-news-btn">View All News</button>

        </div>
        <div class="text-muted align-items-right" id="news-count-info" style="font-size:1rem;"></div>

            <div class="card-body p-2">
                <div id="news-list"></div>
                    <!-- Day.js CDN for human readable dates -->
                    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>

                    <script>dayjs.extend(dayjs_plugin_relativeTime);</script>

                    <!-- News Details Modal -->
                    <div class="modal fade" id="newsDetailsModal" tabindex="-1" aria-labelledby="newsDetailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newsDetailsModalLabel">News Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="news-details-body">
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
    function showAlert(message, type = 'info') {
        console.log('showAlert called with:', message, type);
        
        // ALWAYS show native alert first for immediate feedback
        if (type === 'success') {
            alert('✅ SUCCESS: ' + message);
        } else if (type === 'danger') {
            alert('❌ ERROR: ' + message);
        } else {
            alert('ℹ️ INFO: ' + message);
        }
        
        // Also try to show Bootstrap alert
        const alertContainer = document.getElementById('news-alert-container');
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
            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
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
    function clearValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    // Clear form function
    function clearForm() {
        const form = document.getElementById('news-form');
        form.reset();
        clearValidationErrors();
        document.getElementById('news-alert-container').innerHTML = '';
        setDefaultDateTime(); // Set default date/time after clearing
        console.log('Form cleared');
    }

    // Set default published date to current date/time
    function setDefaultDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const dateTimeString = `${year}-${month}-${day}T${hours}:${minutes}`;
        const publishedAtField = document.getElementById('news-published-at');
        
        if (publishedAtField && !publishedAtField.value) {
            publishedAtField.value = dateTimeString;
            console.log('Default date/time set:', dateTimeString);
        }
    }

    // Test alert function
    function testAlerts() {
        showAlert('Testing alerts - this should work!', 'success');
    }

    // Validate form before submission
    function validateForm(formData) {
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
        
        // Validate content (required)
        const content = formData.get('content')?.trim();
        if (!content) {
            errors.content = ['Content is required'];
        } else if (content.length < 10) {
            errors.content = ['Content must be at least 10 characters'];
        }
        
        // Validate visibility (required)
        const isVisible = formData.get('is_visible');
        if (isVisible === null || isVisible === '' || isVisible === undefined) {
            errors.is_visible = ['Visibility selection is required'];
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
        
        // Validate published_at (required)
        const publishedAt = formData.get('published_at');
        if (!publishedAt || !publishedAt.trim()) {
            errors.published_at = ['Published date is required'];
        } else {
            const date = new Date(publishedAt);
            if (isNaN(date.getTime())) {
                errors.published_at = ['Please enter a valid date and time'];
            }
        }
        
        return {
            isValid: Object.keys(errors).length === 0,
            errors: errors
        };
    }

    // Show validation errors
    function showValidationErrors(errors) {
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

    document.addEventListener('DOMContentLoaded', function() {
        fetchNews();
        setInterval(fetchNews, 5000);
        
        // Set default date/time on page load
        setDefaultDateTime();

        // Handle form submission
        document.getElementById('news-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-news-btn');
            const spinner = document.getElementById('submit-spinner');
            const form = this;
            
            // Clear previous errors and alerts
            clearValidationErrors();
            document.getElementById('news-alert-container').innerHTML = '';
            
            const formData = new FormData(form);
            
            // Client-side validation
            console.log('Validating form data...');
            const validation = validateForm(formData);
            if (!validation.isValid) {
                showValidationErrors(validation.errors);
                
                // Show validation error alert with details
                const errorCount = Object.keys(validation.errors).length;
                showAlert(`Please fill in all required fields (${errorCount} errors found)`, 'danger');
                
                console.log('Validation failed:', validation.errors);
                
                // Scroll to first error
                const firstErrorField = document.querySelector('.is-invalid');
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorField.focus();
                }
                
                return; // Stop form submission
            }
            
            console.log('Validation passed, submitting form...');
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                console.log('About to call showAlert...');
                
                if (data.success) {
                    console.log('SUCCESS - calling showAlert');
                    showAlert('News added successfully!', 'success');
                    
                    // Delay form clearing to ensure alert is shown
                    setTimeout(() => {
                        clearForm(); // Clear form and validation errors
                        fetchNews(); // Refresh the news list
                    }, 2000); // Increased delay
                } else {
                    console.log('FAILURE - showing error');
                    if (data.errors) {
                        showValidationErrors(data.errors);
                        const errorCount = Object.keys(data.errors).length;
                        showAlert(`Found ${errorCount} validation errors. Please check the form.`, 'danger');
                    } else {
                        showAlert(data.message || 'Failed to add news. Please try again.', 'danger');
                    }
                }
                
                console.log('showAlert should have been called by now');
            })
            .catch(error => {
                console.error('=== FETCH ERROR ===');
                console.error('Error details:', error);
                showAlert('Network error: ' + error.message, 'danger');
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });

        document.getElementById('view-all-news-btn').addEventListener('click', function(e) {
            e.preventDefault();
            fetchAllNewsModal();
        });
    });


    function fetchAllNewsModal() {
        // view all news button here
        fetch('/admin/news?all=1', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Backend already returns news in descending order by id
            let html = '<h5>All News</h5>';
            html += '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div><strong>Content:</strong> ${item.content}</div>
                    <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank"><img src="${convertGoogleDriveUrl(item.image)}" style="max-width: 50px; max-height: 50px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}">${item.is_visible ? 'Visible' : 'Hidden'}</span></div>
                </li>`;
            });
            html += '</ul>';
            // Show in modal
            document.getElementById('news-details-body').innerHTML = html;
            var modalEl = document.getElementById('newsDetailsModal');
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
            document.getElementById('news-details-body').innerHTML = '<div class="text-danger">Error loading all news.</div>';
            var modalEl = document.getElementById('newsDetailsModal');
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
            }
        });
    }





    function fetchNews() {
        const newsList = document.getElementById('news-list');
        fetch('/admin/news', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                // Assume item.created_at and item.added_by are available from backend
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div class='d-none'><strong>Content:</strong> ${item.content}</div>
                    <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank"><img src="${convertGoogleDriveUrl(item.image)}" style="max-width: 50px; max-height: 50px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                        <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                        <button class="btn btn-sm btn-primary ms-2 view-more-btn" data-id="${item.id}" data-title="${encodeURIComponent(item.title)}" data-content="${encodeURIComponent(item.content)}" data-image="${encodeURIComponent(item.image ?? '')}" data-published="${encodeURIComponent(item.published_at ?? '')}" data-visible="${item.is_visible}" data-addedat="${encodeURIComponent(item.created_at ?? '')}" data-addedby="${encodeURIComponent(item.added_by ?? 'Unknown')}">View More</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            newsList.innerHTML = html;
            // Update count info
            const countInfo = document.getElementById('news-count-info');
            if (countInfo) {
                countInfo.textContent = `Total: ${data.length}, Displayed: ${data.length}`;
            }


            // ...existing code for event listeners...
            document.querySelectorAll('.view-more-btn').forEach(function(btn) {

                btn.addEventListener('click', function() {
                    const title = decodeURIComponent(this.getAttribute('data-title'));
                    const content = decodeURIComponent(this.getAttribute('data-content'));
                    const image = decodeURIComponent(this.getAttribute('data-image'));
                    const published = decodeURIComponent(this.getAttribute('data-published'));
                    const visible = this.getAttribute('data-visible') == '1';
                    const addedAtRaw = decodeURIComponent(this.getAttribute('data-addedat'));
                    const addedBy = decodeURIComponent(this.getAttribute('data-addedby'));
                    const addedAt = addedAtRaw ? dayjs(addedAtRaw).fromNow() : '';
                    let modalHtml = `<div class="mb-2"><strong>Title:</strong> ${title}</div>`;
                    modalHtml += `<div class="mb-2"><strong>Content:</strong><br><div>${content}</div></div>`;
                    modalHtml += `<div class="mb-2"><strong>Published At:</strong> <small>${published}</small></div>`;
                    modalHtml += `<div class="mb-2"><strong>Added At:</strong> <small>${addedAt}</small></div>`;
                    modalHtml += `<div class="mb-2"><strong>Added By:</strong> <small>${addedBy}</small></div>`;
                    modalHtml += `<div class="mb-2"><strong>Image:</strong> ${image ? `<a href="${image}" target="_blank"><img src="${convertGoogleDriveUrl(image)}" style="max-width: 200px; max-height: 200px;" onerror="this.style.display='none'; this.nextSibling.style.display='inline';" /> <span style="display:none;">View Link</span></a>` : 'No image'}</div>`;
                    modalHtml += `<div class="mb-2"><strong>Visibility:</strong> <span class="badge ${visible ? 'bg-success' : 'bg-secondary'}">${visible ? 'Visible' : 'Hidden'}</span></div>`;
                    document.getElementById('news-details-body').innerHTML = modalHtml;
                    // Bootstrap 5 modal logic fallback
                    var modalEl = document.getElementById('newsDetailsModal');
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



            // Use event delegation for toggle-visibility-btn (with closest)
            newsList.addEventListener('click', function(e) {
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
                    fetch(`/admin/news/${id}/visibility`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ is_visible: newVisible })
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
        })
        .catch(() => {
            newsList.innerHTML = '<div class="text-danger">Error loading news.</div>';
        });
    }
</script>
