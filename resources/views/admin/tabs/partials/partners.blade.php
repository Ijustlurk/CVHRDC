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
            title: 'Partners Uploaded'
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
            title: 'Partners Visibility Changed!'
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
            title: 'Partners Updated'
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
            title: 'Failed to update Partners visibility!'
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
            title: 'Failed to update Partners visibility!'
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
            title: 'Partners Deleted'
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
            title: 'Partners Deleted'
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


<!-- @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif -->


@isset($partners)
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary fw-bold text-white">Add Partner</div>
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
                <form method="POST" action="{{ route('admin.partners.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required
                            placeholder="Enter partner name">
                        <div class="invalid-feedback">Name is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="organization" class="form-label">Organization <span
                                class="text-danger">*</span></label>
                        <input type="text" name="organization" id="organization" class="form-control" required
                            placeholder="Enter organization name">
                        <div class="invalid-feedback">Organization is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control"
                            placeholder="Enter contact email">
                    </div>
                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="tel" name="contact_phone" id="contact_phone" class="form-control"
                            placeholder="Enter contact phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2"
                            placeholder="Enter address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" name="website" id="website" class="form-control"
                            placeholder="https://example.com">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Partner</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <!-- Search and Filter Section -->
        <div class="card shadow mb-3">
            <div class="card-header bg-info text-white fw-bold">
                <i class="fa fa-search me-2"></i>Search & Filter Partners
            </div>
            <div class="card-body">
                <form id="searchFilterFormPartners" method="GET" action="{{ route('admin.partners.index') }}">
                    <div class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInputPartners" name="search" 
                                    placeholder="Search by name, organization, email, phone, address, or website..." 
                                    value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearchPartners" title="Clear search">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-12 col-md-6">
                            <label for="statusFilterPartners" class="form-label small fw-bold">
                                <i class="fa fa-filter me-1"></i>Status
                            </label>
                            <select class="form-select form-select-sm" id="statusFilterPartners" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-12 col-md-6">
                            <label for="sortByPartners" class="form-label small fw-bold">
                                <i class="fa fa-sort me-1"></i>Sort By
                            </label>
                            <select class="form-select form-select-sm" id="sortByPartners" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnPartners">
                                    <i class="fa fa-filter me-1"></i>Apply Filters
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnPartners">
                                    <i class="fa fa-redo me-1"></i>Reset All
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Results Counter -->
                <div class="mt-3 pt-3 border-top">
                    <div id="resultsCounterPartners" class="text-muted small">
                        <i class="fa fa-info-circle me-1"></i>
                        <span id="counterTextPartners">
                            Showing {{ $partners->firstItem() ?? 0 }} to {{ $partners->lastItem() ?? 0 }} of {{ $totalCount ?? $partners->total() }} partners
                        </span>
                        <span id="loadingIndicatorPartners" class="ms-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Searching...
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partners Cards Grid -->
        <div class="row row-cols-1 row-cols-md-2 g-3" id="partnersCardsContainer">
            @include('admin.tabs.partials.partners-cards', ['partners' => $partners])
        </div>
        <div class="mt-3" id="paginationContainerPartners">
            {{ $partners->links() }}
        </div>
    </div>
</div>

<!-- Edit Partner Modal -->
<div class="modal fade" id="editPartnerModal" tabindex="-1" aria-labelledby="editPartnerModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editPartnerModalLabel">
                    <i class="fa fa-edit me-2"></i>Edit Partner
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPartnerForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_partner_id" name="partner_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-bold">Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required
                            placeholder="Enter partner name">
                        <div class="invalid-feedback">Name is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_organization" class="form-label fw-bold">Organization <span
                                class="text-danger">*</span></label>
                        <input type="text" name="organization" id="edit_organization" class="form-control" required
                            placeholder="Enter organization name">
                        <div class="invalid-feedback">Organization is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contact_email" class="form-label fw-bold">Contact Email</label>
                        <input type="email" name="contact_email" id="edit_contact_email" class="form-control"
                            placeholder="Enter contact email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_contact_phone" class="form-label fw-bold">Contact Phone</label>
                        <input type="tel" name="contact_phone" id="edit_contact_phone" class="form-control"
                            placeholder="Enter contact phone">
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label fw-bold">Address</label>
                        <textarea name="address" id="edit_address" class="form-control" rows="2"
                            placeholder="Enter address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_website" class="form-label fw-bold">Website</label>
                        <input type="url" name="website" id="edit_website" class="form-control"
                            placeholder="https://example.com">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                        <label class="form-check-label fw-bold" for="edit_is_active">Active</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="updatePartnerBtn">
                    <i class="fa fa-save me-2"></i>Update Partner
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

    // Edit Partner Modal Functions
    function openEditPartnerModal(partnerId) {
        console.log('Opening edit modal for partner ID:', partnerId);

        // Show loading state
        const updateBtn = document.getElementById('updatePartnerBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        updateBtn.disabled = true;

        // Fetch partner data
        fetch(`/admin/partners/${partnerId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Populate modal fields
                document.getElementById('edit_partner_id').value = data.id;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_organization').value = data.organization;
                document.getElementById('edit_contact_email').value = data.contact_email || '';
                document.getElementById('edit_contact_phone').value = data.contact_phone || '';
                document.getElementById('edit_address').value = data.address || '';
                document.getElementById('edit_website').value = data.website || '';
                document.getElementById('edit_is_active').checked = data.is_active == 1;

                // Show modal
                const modalElement = document.getElementById('editPartnerModal');
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
                console.error('Error fetching partner data:', error);
                alert('Error loading partner data. Please try again.');
            })
            .finally(() => {
                // Reset button state
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            });
    }

    // Handle update button click
    document.getElementById('updatePartnerBtn').addEventListener('click', function () {
        const form = document.getElementById('editPartnerForm');
        const partnerId = document.getElementById('edit_partner_id').value;
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
        hiddenForm.action = `/admin/partners/${partnerId}`;
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
        const isActiveCheckbox = document.getElementById('edit_is_active');
        const isActiveInput = document.createElement('input');
        isActiveInput.type = 'hidden';
        isActiveInput.name = 'is_active';
        isActiveInput.value = isActiveCheckbox.checked ? '1' : '0';
        hiddenForm.appendChild(isActiveInput);

        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method' && key !== 'is_active') {
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
    document.getElementById('editPartnerModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editPartnerForm');
        form.classList.remove('was-validated');
        form.reset();
    });

    // Partners Search and Filter Functionality
    let partnersSearchTimeout;
    const searchInputPartners = document.getElementById('searchInputPartners');
    const statusFilterPartners = document.getElementById('statusFilterPartners');
    const sortByPartners = document.getElementById('sortByPartners');
    const clearSearchBtnPartners = document.getElementById('clearSearchPartners');
    const searchFilterFormPartners = document.getElementById('searchFilterFormPartners');

    // Prevent form submission and use AJAX instead
    if (searchFilterFormPartners) {
        searchFilterFormPartners.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            performPartnersSearch(); // Use AJAX search instead
        });
    }

    // Real-time search as user types
    if (searchInputPartners) {
        searchInputPartners.addEventListener('input', function() {
            clearTimeout(partnersSearchTimeout);
            partnersSearchTimeout = setTimeout(() => {
                performPartnersSearch();
            }, 500); // Wait 500ms after user stops typing
        });
    }

    // Clear search button
    if (clearSearchBtnPartners) {
        clearSearchBtnPartners.addEventListener('click', function() {
            searchInputPartners.value = '';
            performPartnersSearch();
        });
    }

    // Apply Filters button
    const applyFiltersBtnPartners = document.getElementById('applyFiltersBtnPartners');
    if (applyFiltersBtnPartners) {
        applyFiltersBtnPartners.addEventListener('click', function() {
            performPartnersSearch();
        });
    }

    // Reset All button
    const resetFiltersBtnPartners = document.getElementById('resetFiltersBtnPartners');
    if (resetFiltersBtnPartners) {
        resetFiltersBtnPartners.addEventListener('click', function() {
            // Clear all form inputs
            if (searchInputPartners) searchInputPartners.value = '';
            if (statusFilterPartners) statusFilterPartners.value = '';
            if (sortByPartners) sortByPartners.value = 'newest';
            
            // Perform search with cleared filters
            performPartnersSearch();
        });
    }

    // Filter and sort change handlers
    [statusFilterPartners, sortByPartners].forEach(element => {
        if (element) {
            element.addEventListener('change', performPartnersSearch);
        }
    });

    // Perform AJAX search for Partners
    function performPartnersSearch() {
        const formData = new FormData(document.getElementById('searchFilterFormPartners'));
        const params = new URLSearchParams(formData);

        // Remove empty parameters for cleaner URLs
        for (let [key, value] of Array.from(params.entries())) {
            if (!value || value.trim() === '') {
                params.delete(key);
            }
        }

        // Show loading indicator
        const loadingIndicator = document.getElementById('loadingIndicatorPartners');
        const counterText = document.getElementById('counterTextPartners');
        if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

        const searchUrl = `{{ route('admin.partners.index') }}?${params.toString()}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update partners cards
            const partnersContainer = document.getElementById('partnersCardsContainer');
            if (partnersContainer && data.html) {
                partnersContainer.innerHTML = data.html;
            }

            // Update pagination
            const paginationContainer = document.getElementById('paginationContainerPartners');
            if (paginationContainer && data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }

            // Update counter
            if (counterText) {
                const from = data.from || 0;
                const to = data.to || 0;
                const total = data.totalCount || 0;
                counterText.textContent = `Showing ${from} to ${to} of ${total} partners`;
            }

            // Update browser URL without page reload (for bookmarking/sharing)
            const newUrl = params.toString() ? searchUrl : '{{ route('admin.partners.index') }}';
            window.history.pushState({}, '', newUrl);

            // Hide loading indicator
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        })
        .catch(error => {
            console.error('Partners search error:', error);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            
            // Show error message
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
        if (e.target.closest('#paginationContainerPartners a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            const loadingIndicator = document.getElementById('loadingIndicatorPartners');
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
                // Update partners cards
                const partnersContainer = document.getElementById('partnersCardsContainer');
                if (partnersContainer && data.html) {
                    partnersContainer.innerHTML = data.html;
                }

                // Update pagination
                const paginationContainer = document.getElementById('paginationContainerPartners');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update counter
                const counterText = document.getElementById('counterTextPartners');
                if (counterText) {
                    const from = data.from || 0;
                    const to = data.to || 0;
                    const total = data.totalCount || 0;
                    counterText.textContent = `Showing ${from} to ${to} of ${total} partners`;
                }

                // Update browser URL
                window.history.pushState({}, '', url);

                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';

                // Scroll to top of partners section
                const partnersCardsContainer = document.getElementById('partnersCardsContainer');
                if (partnersCardsContainer) {
                    partnersCardsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
