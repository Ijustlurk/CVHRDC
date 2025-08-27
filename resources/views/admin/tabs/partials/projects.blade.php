@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'Project Uploaded'
        });
    });

</script>
@endif

<!-- Success Visibility -->
@if (session('successvisibility'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-success-visibility',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'Project Visibility Changed!'
        });
    });

</script>
@endif

<!-- Success Update -->
@if (session('successupdate'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-success-update',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'Project Updated'
        });
    });

</script>
@endif

<!-- Update Error -->
@if (session('errorvisibility'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-error-update',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'error',
            title: 'Failed to update project visibility!'
        });
    });

</script>
@endif


<!-- Visibility Error -->
@if (session('errorvisibility'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-error-visibility',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'error',
            title: 'Failed to update project visibility!'
        });
    });

</script>
@endif



<!-- Success Delete -->
@if (session('successdelete'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-success-delete',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'Project Deleted'
        });
    });

</script>
@endif

<!-- Error Delete -->
@if (session('errordelete'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-error-delete',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'News Deleted'
        });
    });

</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Validation Errors',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
            background: '#f8d7da', // light red background
            color: '#842029' // dark red text
        });
    });

</script>
@endif

<!-- Error -->
@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'error',
            title: 'Validation Error!'
        });
    });

</script>
@endif


@isset($projects)
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary fw-bold text-white">Add Project</div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{ route('admin.projects.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required
                            placeholder="Enter project title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required
                            placeholder="Enter project description"></textarea>
                        <div class="invalid-feedback">Description is required.</div>
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
                            <small class="text-muted">The system will automatically convert it to display
                                format.</small>
                        </div>
                        <div class="invalid-feedback" id="image-error"></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" name="status" id="status" class="form-control" required
                            placeholder="Enter status">
                        <div class="invalid-feedback">Status is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="budget" class="form-label">Budget</label>
                        <input type="number" name="budget" id="budget" class="form-control" placeholder="Enter budget">
                    </div>
                    <div class="mb-3">
                        <label for="lead_researcher" class="form-label">Lead Researcher</label>
                        <input type="text" name="lead_researcher" id="lead_researcher" class="form-control" required
                            placeholder="Enter lead researcher">
                        <div class="invalid-feedback">Lead researcher is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="leader" class="form-label">Leader</label>
                        <input type="text" name="leader" id="leader" class="form-control" required
                            placeholder="Enter leader">
                        <div class="invalid-feedback">Leader is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="funding_agency" class="form-label">Funding Agency</label>
                        <input type="text" name="funding_agency" id="funding_agency" class="form-control"
                            placeholder="Enter funding agency">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1"
                            checked>
                        <label class="form-check-label" for="is_visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Project</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="row row-cols-1 row-cols-md-2 g-3">
            @foreach ($projects as $item)
            <div class="col">
                <div class="card h-100">
                    @php
                    $imageUrl = $item->image;
                    // Convert Google Drive URL to direct image URL
                    if (strpos($imageUrl, 'drive.google.com') !== false) {
                    // Extract file ID from Google Drive URL
                    preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $imageUrl, $matches);
                    if (!empty($matches[1])) {
                    $fileId = $matches[1];
                    $imageUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
                    } else {
                    // Try alternative format: ?id=FILE_ID
                    preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $imageUrl, $matches);
                    if (!empty($matches[1])) {
                    $fileId = $matches[1];
                    $imageUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
                    }
                    }
                    }
                    @endphp
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="News Image"
                        onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ Str::limit($item->description, 100) }}</p>

                            <!-- Spacer pushes date to bottom -->
                            <div class="mt-auto">
                                <p class="card-text">
                                    <small class="text-muted">
                                        Start: {{ $item->start_date ? $item->start_date->format('M d, Y') : '' }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <form method="POST" action="{{ route('admin.projects.visibility', $item->id) }}"
                                    class="me-2">
                                    @csrf
                                    <input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
                                        <i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-warning me-2"
                                    onclick="openEditProjectModal({{ $item->id }})"><i class="fa fa-edit"></i></button>
                                <!-- Delete Button that triggers Modal -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $item->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                Are you sure you want to delete this project?
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>

                                                <form method="POST"
                                                    action="{{ route('admin.projects.destroy', $item->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3">
            {{ $projects->links() }}
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editProjectModalLabel">
                    <i class="fa fa-edit me-2"></i>Edit Project
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Success/Error Messages -->
                <div id="editProjectMessageContainer" class="mb-3" style="display: none;">
                    <div id="editProjectMessage" class="alert" role="alert"></div>
                </div>

                <form id="editProjectForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_project_id" name="project_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold">Title <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required
                            placeholder="Enter project title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-bold">Description <span
                                class="text-danger">*</span></label>
                        <textarea name="description" id="edit_description" class="form-control" rows="4" required
                            placeholder="Enter project description"></textarea>
                        <div class="invalid-feedback">Description is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">
                            <span class="fw-bold">Image URL <span class="text-danger">*</span></span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="edit_image" name="image" required
                            placeholder="https://drive.google.com/file/d/.../view">
                        <div class="form-text">
                            <strong>Google Drive Instructions:</strong><br>
                            1. Right-click your image in Google Drive → "Get link"<br>
                            2. Set to "Anyone with the link" can view<br>
                            3. Copy and paste the sharing link here<br>
                            <small class="text-muted">The system will automatically convert it to display
                                format.</small>
                        </div>
                        <div class="invalid-feedback" id="edit-image-error"></div>

                        <!-- Current Image Preview -->
                        <div class="mt-2" id="currentProjectImagePreview" style="display: none;">
                            <label class="form-label fw-bold">Current Image:</label>
                            <div class="border rounded p-2">
                                <img id="previewProjectImage" src="" alt="Current Project Image" class="img-fluid"
                                    style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label for="edit_start_date" class="form-label fw-bold">Start Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="edit_end_date" class="form-label fw-bold">End Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label fw-bold">Status <span
                                class="text-danger">*</span></label>
                        <input type="text" name="status" id="edit_status" class="form-control" required
                            placeholder="Enter status">
                        <div class="invalid-feedback">Status is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_budget" class="form-label fw-bold">Budget</label>
                        <input type="number" name="budget" id="edit_budget" class="form-control"
                            placeholder="Enter budget">
                    </div>
                    <div class="mb-3">
                        <label for="edit_lead_researcher" class="form-label fw-bold">Lead Researcher <span
                                class="text-danger">*</span></label>
                        <input type="text" name="lead_researcher" id="edit_lead_researcher" class="form-control"
                            required placeholder="Enter lead researcher">
                        <div class="invalid-feedback">Lead researcher is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_leader" class="form-label fw-bold">Leader <span
                                class="text-danger">*</span></label>
                        <input type="text" name="leader" id="edit_leader" class="form-control" required
                            placeholder="Enter leader">
                        <div class="invalid-feedback">Leader is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_funding_agency" class="form-label fw-bold">Funding Agency</label>
                        <input type="text" name="funding_agency" id="edit_funding_agency" class="form-control"
                            placeholder="Enter funding agency">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                        <label class="form-check-label fw-bold" for="edit_is_active">Active</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="edit_is_visible"
                            value="1">
                        <label class="form-check-label fw-bold" for="edit_is_visible">Visible</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="updateProjectBtn">
                    <i class="fa fa-save me-2"></i>Update Project
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Edit Project Modal Functions
    function openEditProjectModal(projectId) {
        console.log('Opening edit project modal for project ID:', projectId);

        // Hide any previous messages
        hideEditProjectMessage();

        // Show loading state
        const updateBtn = document.getElementById('updateProjectBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        updateBtn.disabled = true;

        // Fetch project data
        fetch(`/admin/projects/${projectId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Project data received:', data);

                // Populate modal fields
                document.getElementById('edit_project_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_image').value = data.image;

                // Format dates for input fields (YYYY-MM-DD)
                if (data.start_date) {
                    const startDate = new Date(data.start_date);
                    const formattedStartDate = startDate.toISOString().split('T')[0];
                    document.getElementById('edit_start_date').value = formattedStartDate;
                }

                if (data.end_date) {
                    const endDate = new Date(data.end_date);
                    const formattedEndDate = endDate.toISOString().split('T')[0];
                    document.getElementById('edit_end_date').value = formattedEndDate;
                }

                document.getElementById('edit_status').value = data.status;
                document.getElementById('edit_budget').value = data.budget || '';
                document.getElementById('edit_lead_researcher').value = data.lead_researcher;
                document.getElementById('edit_leader').value = data.leader;
                document.getElementById('edit_funding_agency').value = data.funding_agency || '';
                document.getElementById('edit_is_active').checked = data.is_active == 1;
                document.getElementById('edit_is_visible').checked = data.is_visible == 1;

                // Show current image preview
                showCurrentProjectImagePreview(data.image);

                // Show modal
                const modalElement = document.getElementById('editProjectModal');
                console.log('Modal element found:', modalElement);

                if (modalElement) {
                    try {
                        const modal = new bootstrap.Modal(modalElement);
                        console.log('Bootstrap modal instance created:', modal);
                        modal.show();
                    } catch (error) {
                        console.error('Error showing modal:', error);
                    }
                } else {
                    console.error('Modal element not found!');
                }
            })
            .catch(error => {
                console.error('Error fetching project data:', error);
                showEditProjectMessage('Error loading project data. Please try again.', 'danger');
            })
            .finally(() => {
                // Reset button state
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            });
    }

    // Function to show messages in the edit project modal
    function showEditProjectMessage(message, type = 'info') {
        const messageContainer = document.getElementById('editProjectMessageContainer');
        const messageElement = document.getElementById('editProjectMessage');

        messageElement.className = `alert alert-${type}`;
        messageElement.textContent = message;
        messageContainer.style.display = 'block';

        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => hideEditProjectMessage(), 3000);
        }
    }

    // Function to hide messages in the edit project modal
    function hideEditProjectMessage() {
        const messageContainer = document.getElementById('editProjectMessageContainer');
        messageContainer.style.display = 'none';
    }

    // Function to show current project image preview
    function showCurrentProjectImagePreview(imageUrl) {
        const previewContainer = document.getElementById('currentProjectImagePreview');
        const previewImage = document.getElementById('previewProjectImage');

        if (imageUrl) {
            // Convert Google Drive URL to direct image URL for preview
            let directImageUrl = imageUrl;
            if (imageUrl.includes('drive.google.com')) {
                const fileIdMatch = imageUrl.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
                if (fileIdMatch) {
                    directImageUrl = `https://drive.google.com/thumbnail?id=${fileIdMatch[1]}&sz=s4000`;
                }
            }

            previewImage.src = directImageUrl;
            previewImage.onerror = function () {
                this.src =
                    'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';
            };
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    }



    // Check if project modal exists when page loads
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('editProjectModal');
        console.log('Project modal element on page load:', modalElement);
        if (modalElement) {
            console.log('Project modal HTML on page load:', modalElement.outerHTML);
        }

        // Check Bootstrap availability
        console.log('Bootstrap object:', typeof bootstrap !== 'undefined' ? bootstrap : 'NOT AVAILABLE');
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap version:', bootstrap.VERSION || 'Unknown version');
            console.log('Bootstrap Modal:', typeof bootstrap.Modal);
        }

        // Check jQuery availability
        console.log('jQuery object:', typeof $ !== 'undefined' ? $ : 'NOT AVAILABLE');
    });

    // Handle update project button click
    document.getElementById('updateProjectBtn').addEventListener('click', function () {
        const form = document.getElementById('editProjectForm');
        const projectId = document.getElementById('edit_project_id').value;
        const updateBtn = this;

        // Validate form
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Show loading state
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';
        updateBtn.disabled = true;

        // Create a hidden form to submit the data as a regular form submission
        const hiddenForm = document.createElement('form');
        hiddenForm.method = 'POST';
        hiddenForm.action = `/admin/projects/${projectId}`;
        hiddenForm.style.display = 'none';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        hiddenForm.appendChild(csrfInput);

        // Add method spoofing for PUT
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        hiddenForm.appendChild(methodInput);

        // Add all form fields
        const formData = new FormData(form);

        // Handle checkboxes separately to ensure proper values
        const isActiveCheckbox = document.getElementById('edit_is_active');
        const isActiveInput = document.createElement('input');
        isActiveInput.type = 'hidden';
        isActiveInput.name = 'is_active';
        isActiveInput.value = isActiveCheckbox.checked ? '1' : '0';
        hiddenForm.appendChild(isActiveInput);

        const isVisibleCheckbox = document.getElementById('edit_is_visible');
        const isVisibleInput = document.createElement('input');
        isVisibleInput.type = 'hidden';
        isVisibleInput.name = 'is_visible';
        isVisibleInput.value = isVisibleCheckbox.checked ? '1' : '0';
        hiddenForm.appendChild(isVisibleInput);

        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method' && key !== 'is_active' && key !== 'is_visible') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                hiddenForm.appendChild(input);
            }
        }

        // Append form to body and submit
        document.body.appendChild(hiddenForm);
        hiddenForm.submit();
    });

    // Reset form validation when project modal is hidden
    document.getElementById('editProjectModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editProjectForm');
        form.classList.remove('was-validated');
        form.reset();
        hideEditProjectMessage();

        // Hide image preview
        document.getElementById('currentProjectImagePreview').style.display = 'none';
    });

</script>
@endisset
