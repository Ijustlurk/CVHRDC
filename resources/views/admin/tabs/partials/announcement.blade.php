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
            title: 'Announcement Uploaded'
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
            title: 'Announcement Visibility Changed!'
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
            title: 'Announcement Updated'
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
            title: 'Failed to update Announcement visibility!'
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
            title: 'Failed to update Announcement visibility!'
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
            title: 'Announcement Deleted'
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
            title: 'Announcement Deleted'
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


@isset($announcements)
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary fw-bold text-white">Add Announcement</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.announcements.store') }}" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" required
                            placeholder="Enter announcement title" value="{{ old('title') }}">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control" rows="4" required
                            placeholder="Enter announcement content">{{ old('content') }}</textarea>
                        <div class="invalid-feedback">Content is required.</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date') }}">
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>
                        <div class="col-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date') }}">
                            <div class="invalid-feedback" id="end-date-error">End date is required.</div>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
                            {{ old('active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Announcement</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="row g-3">
            @forelse ($announcements as $item)
            <div class="col-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $item->title }}</h5>
                            <span class="badge bg-{{ $item->active ? 'success' : 'secondary' }} ms-2">
                                {{ $item->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="card-text">{{ Str::limit($item->content, 150) }}</p>
                        <div class="row text-muted small">
                            <div class="col-6">
                                @if ($item->start_date)
                                <i class="fa fa-calendar-o me-1"></i>
                                Start: {{ \Carbon\Carbon::parse($item->start_date)->format('M d, Y') }}
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($item->end_date)
                                <i class="fa fa-calendar-times-o me-1"></i>
                                End: {{ \Carbon\Carbon::parse($item->end_date)->format('M d, Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light">
                        <small class="text-muted">
                            Created: {{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}
                        </small>
                        <div class="btn-group" role="group">
                            <form method="POST" action="{{ route('admin.announcements.visibility', $item->id) }}"
                                class="d-inline">
                                @csrf
                                <input type="hidden" name="active" value="{{ $item->active ? 0 : 1 }}">
                                <button type="submit"
                                    class="btn btn-sm btn-outline-{{ $item->active ? 'success' : 'secondary' }}">
                                    <i class="fa fa-eye{{ $item->active ? '' : '-slash' }}"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-warning ms-1" title="Edit"
                                onclick="openEditAnnouncementModal({{ $item->id }})">
                                <i class="fa fa-edit"></i>
                            </button>
                            <!-- Delete Button that triggers Modal -->
                            <button type="button" class="btn btn-sm btn-danger ms-1" title="Delete"
                                data-bs-toggle="modal" data-bs-target="#deleteAnnouncementModal{{ $item->id }}">
                                <i class="fa fa-trash"></i>
                            </button>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteAnnouncementModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="deleteAnnouncementModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAnnouncementModalLabel{{ $item->id }}">
                                                Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            Are you sure you want to delete this announcement?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>

                                            <form method="POST"
                                                action="{{ route('admin.announcements.destroy', $item->id) }}"
                                                class="d-inline">
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
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fa fa-bullhorn fa-3x mb-3 opacity-25"></i>
                        <h5>No Announcements Found</h5>
                        <p class="mb-0">Create your first announcement using the form on the left.</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        @if ($announcements->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editAnnouncementModalLabel">
                    <i class="fa fa-edit me-2"></i>Edit Announcement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAnnouncementForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_announcement_id" name="announcement_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold">Title <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required
                            placeholder="Enter announcement title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_content" class="form-label fw-bold">Content <span
                                class="text-danger">*</span></label>
                        <textarea name="content" id="edit_content" class="form-control" rows="4" required
                            placeholder="Enter announcement content"></textarea>
                        <div class="invalid-feedback">Content is required.</div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="edit_start_date" class="form-label fw-bold">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control">
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>
                        <div class="col-6">
                            <label for="edit_end_date" class="form-label fw-bold">End Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control">
                            <div class="invalid-feedback" id="edit-end-date-error">End date is required.</div>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="active" id="edit_active" value="1">
                        <label class="form-check-label fw-bold" for="edit_active">Active</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="updateAnnouncementBtn">
                    <i class="fa fa-save me-2"></i>Update Announcement
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


    function validateDates() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const errorDiv = document.getElementById('end-date-error');

        const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
        const endDate = endDateInput.value ? new Date(endDateInput.value) : null;

        if (startDate && endDate && endDate < startDate) {
            endDateInput.setCustomValidity('End date cannot be before start date');
            errorDiv.textContent = 'End date cannot be before start date';
        } else {
            endDateInput.setCustomValidity('');
            errorDiv.textContent = 'Date is required.'; // fallback message
        }
    }

    document.getElementById('start_date').addEventListener('change', validateDates);
    document.getElementById('end_date').addEventListener('change', validateDates);

    // Edit Announcement Modal Functions
    function openEditAnnouncementModal(announcementId) {
        console.log('Opening edit modal for announcement ID:', announcementId);

        // Show loading state
        const updateBtn = document.getElementById('updateAnnouncementBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        updateBtn.disabled = true;

        // Fetch announcement data
        fetch(`/admin/announcements/${announcementId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Populate modal fields
                document.getElementById('edit_announcement_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_content').value = data.content;

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

                document.getElementById('edit_active').checked = data.active == 1;

                // Show modal
                const modalElement = document.getElementById('editAnnouncementModal');
                console.log('Modal element found:', modalElement);

                if (modalElement) {
                    try {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                        console.log('Modal shown successfully');
                    } catch (error) {
                        console.error('Error showing modal:', error);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching announcement data:', error);
                alert('Error loading announcement data. Please try again.');
            })
            .finally(() => {
                // Reset button state
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            });
    }

    // Handle update button click
    document.getElementById('updateAnnouncementBtn').addEventListener('click', function () {
        const form = document.getElementById('editAnnouncementForm');
        const announcementId = document.getElementById('edit_announcement_id').value;
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
        hiddenForm.action = `/admin/announcements/${announcementId}`;
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

        // Handle checkbox separately to ensure proper value
        const isActiveCheckbox = document.getElementById('edit_active');
        const isActiveInput = document.createElement('input');
        isActiveInput.type = 'hidden';
        isActiveInput.name = 'active';
        isActiveInput.value = isActiveCheckbox.checked ? '1' : '0';
        hiddenForm.appendChild(isActiveInput);

        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method' && key !== 'active') {
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

    // Reset form validation when modal is hidden
    document.getElementById('editAnnouncementModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editAnnouncementForm');
        form.classList.remove('was-validated');
        form.reset();
    });

</script>
@endisset
