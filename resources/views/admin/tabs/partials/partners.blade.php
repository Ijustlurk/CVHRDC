@if (session('success'))
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
@endif


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
                            <textarea name="address" id="address" class="form-control" rows="2" placeholder="Enter address"></textarea>
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
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach ($partners as $item)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text"><strong>Organization:</strong> {{ $item->organization ?? '-' }}</p>
                                @if ($item->contact_email)
                                    <p class="card-text"><strong>Email:</strong> {{ $item->contact_email }}</p>
                                @endif
                                @if ($item->contact_phone)
                                    <p class="card-text"><strong>Phone:</strong> {{ $item->contact_phone }}</p>
                                @endif
                                @if ($item->address)
                                    <p class="card-text"><strong>Address:</strong> {{ Str::limit($item->address, 50) }}</p>
                                @endif
                                @if ($item->website)
                                    <p class="card-text"><strong>Website:</strong> <a href="{{ $item->website }}"
                                            target="_blank"
                                            class="text-decoration-none">{{ Str::limit($item->website, 30) }}</a></p>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-warning me-2"
                                    onclick="openEditPartnerModal({{ $item->id }})"><i class="fa fa-edit"></i>Edit</button>
                                <form method="POST" action="{{ route('admin.partners.destroy', $item->id) }}"
                                    onsubmit="return confirm('Delete this partner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
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
                            <label for="edit_name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required
                                placeholder="Enter partner name">
                            <div class="invalid-feedback">Name is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_organization" class="form-label fw-bold">Organization <span class="text-danger">*</span></label>
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
        document.getElementById('updatePartnerBtn').addEventListener('click', function() {
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
        document.getElementById('editPartnerModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('editPartnerForm');
            form.classList.remove('was-validated');
            form.reset();
        });
    </script>
@endisset
