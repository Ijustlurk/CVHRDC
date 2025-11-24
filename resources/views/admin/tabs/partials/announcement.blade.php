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
        <!-- Search and Filter Section -->
        <div class="card shadow mb-3">
            <div class="card-header bg-info text-white fw-bold">
                <i class="fa fa-search me-2"></i>Search & Filter Announcements
            </div>
            <div class="card-body">
                <form id="searchFilterFormAnnouncements" method="GET" action="{{ route('admin.announcements.index') }}">
                    <div class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInputAnnouncements" name="search" 
                                    placeholder="Search by title or content..." 
                                    value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearchAnnouncements" title="Clear search">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Date Filter -->
                        <div class="col-12 col-md-4">
                            <label for="dateFilterAnnouncements" class="form-label small fw-bold">
                                <i class="fa fa-calendar me-1"></i>Date Created
                            </label>
                            <select class="form-select form-select-sm" id="dateFilterAnnouncements" name="date_filter">
                                <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-12 col-md-4">
                            <label for="statusFilterAnnouncements" class="form-label small fw-bold">
                                <i class="fa fa-filter me-1"></i>Status
                            </label>
                            <select class="form-select form-select-sm" id="statusFilterAnnouncements" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-12 col-md-4">
                            <label for="sortByAnnouncements" class="form-label small fw-bold">
                                <i class="fa fa-sort me-1"></i>Sort By
                            </label>
                            <select class="form-select form-select-sm" id="sortByAnnouncements" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="col-12" id="customDateRangeAnnouncements" style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="dateFromAnnouncements" class="form-label small">From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="dateFromAnnouncements" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="dateToAnnouncements" class="form-label small">To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="dateToAnnouncements" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnAnnouncements">
                                    <i class="fa fa-filter me-1"></i>Apply Filters
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnAnnouncements">
                                    <i class="fa fa-redo me-1"></i>Reset All
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Results Counter -->
                <div class="mt-3 pt-3 border-top">
                    <div id="resultsCounterAnnouncements" class="text-muted small">
                        <i class="fa fa-info-circle me-1"></i>
                        <span id="counterTextAnnouncements">
                            Showing {{ $announcements->firstItem() ?? 0 }} to {{ $announcements->lastItem() ?? 0 }} of {{ $totalCount ?? $announcements->total() }} announcements
                        </span>
                        <span id="loadingIndicatorAnnouncements" class="ms-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Searching...
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements Cards Grid -->
        <div class="row g-3" id="announcementsCardsContainer">
            @include('admin.tabs.partials.announcement-cards', ['announcements' => $announcements])
        </div>
        <div class="mt-3" id="paginationContainerAnnouncements">
            {{ $announcements->links() }}
        </div>
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

    // Announcements Search and Filter Functionality
    let announcementsSearchTimeout;
    const searchInputAnnouncements = document.getElementById('searchInputAnnouncements');
    const dateFilterAnnouncements = document.getElementById('dateFilterAnnouncements');
    const statusFilterAnnouncements = document.getElementById('statusFilterAnnouncements');
    const sortByAnnouncements = document.getElementById('sortByAnnouncements');
    const clearSearchBtnAnnouncements = document.getElementById('clearSearchAnnouncements');
    const customDateRangeAnnouncements = document.getElementById('customDateRangeAnnouncements');
    const dateFromAnnouncements = document.getElementById('dateFromAnnouncements');
    const dateToAnnouncements = document.getElementById('dateToAnnouncements');
    const searchFilterFormAnnouncements = document.getElementById('searchFilterFormAnnouncements');

    // Prevent form submission and use AJAX instead
    if (searchFilterFormAnnouncements) {
        searchFilterFormAnnouncements.addEventListener('submit', function(e) {
            e.preventDefault();
            performAnnouncementsSearch();
        });
    }

    // Real-time search as user types
    if (searchInputAnnouncements) {
        searchInputAnnouncements.addEventListener('input', function() {
            clearTimeout(announcementsSearchTimeout);
            announcementsSearchTimeout = setTimeout(() => {
                performAnnouncementsSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtnAnnouncements) {
        clearSearchBtnAnnouncements.addEventListener('click', function() {
            searchInputAnnouncements.value = '';
            performAnnouncementsSearch();
        });
    }

    // Apply Filters button
    const applyFiltersBtnAnnouncements = document.getElementById('applyFiltersBtnAnnouncements');
    if (applyFiltersBtnAnnouncements) {
        applyFiltersBtnAnnouncements.addEventListener('click', function() {
            performAnnouncementsSearch();
        });
    }

    // Reset All button
    const resetFiltersBtnAnnouncements = document.getElementById('resetFiltersBtnAnnouncements');
    if (resetFiltersBtnAnnouncements) {
        resetFiltersBtnAnnouncements.addEventListener('click', function() {
            if (searchInputAnnouncements) searchInputAnnouncements.value = '';
            if (dateFilterAnnouncements) dateFilterAnnouncements.value = '';
            if (statusFilterAnnouncements) statusFilterAnnouncements.value = '';
            if (sortByAnnouncements) sortByAnnouncements.value = 'newest';
            if (dateFromAnnouncements) dateFromAnnouncements.value = '';
            if (dateToAnnouncements) dateToAnnouncements.value = '';
            if (customDateRangeAnnouncements) customDateRangeAnnouncements.style.display = 'none';
            
            performAnnouncementsSearch();
        });
    }

    // Filter and sort change handlers
    [dateFilterAnnouncements, statusFilterAnnouncements, sortByAnnouncements].forEach(element => {
        if (element) {
            element.addEventListener('change', performAnnouncementsSearch);
        }
    });

    // Custom date range inputs
    [dateFromAnnouncements, dateToAnnouncements].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilterAnnouncements.value === 'custom') {
                    performAnnouncementsSearch();
                }
            });
        }
    });

    // Show/hide custom date range
    if (dateFilterAnnouncements) {
        dateFilterAnnouncements.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRangeAnnouncements.style.display = 'block';
            } else {
                customDateRangeAnnouncements.style.display = 'none';
            }
        });
    }

    // Perform AJAX search for Announcements
    function performAnnouncementsSearch() {
        const formData = new FormData(document.getElementById('searchFilterFormAnnouncements'));
        const params = new URLSearchParams(formData);

        // Remove empty parameters for cleaner URLs
        for (let [key, value] of Array.from(params.entries())) {
            if (!value || value.trim() === '') {
                params.delete(key);
            }
        }

        // Show loading indicator
        const loadingIndicator = document.getElementById('loadingIndicatorAnnouncements');
        const counterText = document.getElementById('counterTextAnnouncements');
        if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

        const searchUrl = `{{ route('admin.announcements.index') }}?${params.toString()}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update announcements cards
            const announcementsContainer = document.getElementById('announcementsCardsContainer');
            if (announcementsContainer && data.html) {
                announcementsContainer.innerHTML = data.html;
            }

            // Update pagination
            const paginationContainer = document.getElementById('paginationContainerAnnouncements');
            if (paginationContainer && data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }

            // Update counter
            if (counterText) {
                const from = data.from || 0;
                const to = data.to || 0;
                const total = data.totalCount || 0;
                counterText.textContent = `Showing ${from} to ${to} of ${total} announcements`;
            }

            // Update browser URL without page reload
            const newUrl = params.toString() ? searchUrl : '{{ route('admin.announcements.index') }}';
            window.history.pushState({}, '', newUrl);

            // Hide loading indicator
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        })
        .catch(error => {
            console.error('Announcements search error:', error);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            
            if (typeof Swal !== 'undefined') {
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
                    title: 'Search failed. Please try again.'
                });
            } else {
                alert('Search failed. Please try again.');
            }
        });
    }

    // Handle pagination clicks with AJAX
    document.addEventListener('click', function(e) {
        if (e.target.closest('#paginationContainerAnnouncements a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            const loadingIndicator = document.getElementById('loadingIndicatorAnnouncements');
            if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const announcementsContainer = document.getElementById('announcementsCardsContainer');
                if (announcementsContainer && data.html) {
                    announcementsContainer.innerHTML = data.html;
                }

                const paginationContainer = document.getElementById('paginationContainerAnnouncements');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                const counterText = document.getElementById('counterTextAnnouncements');
                if (counterText) {
                    const from = data.from || 0;
                    const to = data.to || 0;
                    const total = data.totalCount || 0;
                    counterText.textContent = `Showing ${from} to ${to} of ${total} announcements`;
                }

                window.history.pushState({}, '', url);

                if (loadingIndicator) loadingIndicator.style.display = 'none';

                const announcementsCardsContainer = document.getElementById('announcementsCardsContainer');
                if (announcementsCardsContainer) {
                    announcementsCardsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
                if (loadingIndicator) loadingIndicator.style.display = 'none';
            });
        }
    });

</script>
@endisset
