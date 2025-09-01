@extends('layouts.Admin.app')

@section('content')
<div class="container px-4 py-5">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">CVHRMC User Management System</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Welcome to the User Management section. You can manage users and roles here.
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer" class="mb-4"></div>

    <!-- User Management Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link{{ !request()->routeIs('admin.roles.*') ? ' active' : '' }}"
                href="{{ route('admin.users.index') }}">
                <i class="fas fa-users me-2"></i>Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->routeIs('admin.roles.*') ? ' active' : '' }}"
                href="{{ route('admin.roles.index') }}">
                <i class="fas fa-user-tag me-2"></i>Roles
            </a>
        </li>
    </ul>

    <div class="card shadow">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-user-tag me-2"></i>Manage Roles</h6>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="fas fa-plus"></i> Create New Role
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="rolesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Users Count</th>
                            <th>Created At</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ ucfirst($role->name) }}</span>
                                @if (in_array($role->name, ['admin', 'superadmin', 'user']))
                                <span class="badge bg-info ms-2">System Role</span>
                                @endif
                            </td>
                            <td>{{ $role->description ?: 'No description' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $role->users_count }} users</span>
                            </td>
                            <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-primary edit-role-btn" data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}" data-description="{{ $role->description }}"
                                        data-bs-toggle="modal" data-bs-target="#editRoleModal" title="Edit Role">
                                        Edit
                                    </button>
                                    @if (!in_array($role->name, ['admin', 'superadmin', 'user']))
                                    <button class="btn btn-sm btn-danger delete-role-btn" data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}" title="Delete Role">
                                        Delete
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--
                Editor-in-Chief – Oversees the entire publication process and editorial direction.
                Peer Review Coordinator – Manages submission and review assignments.
                Reviewer/Referee – Evaluates submitted manuscripts for quality and accuracy.
                Publishing Coordinator – Handles scheduling, documentation, and communication.
            -->

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Create New Role</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Describe the purpose and permissions of this role..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="fas fa-save me-1"></i>Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Role</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_role_id" name="role_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"
                            placeholder="Describe the purpose and permissions of this role..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="fas fa-save me-1"></i>Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Roles DOM Content Loaded');
        console.log('jQuery available:', typeof $ !== 'undefined');
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]') ? .content);

        // Initialize DataTable with custom settings
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('#rolesTable').DataTable({
                order: [
                    [3, 'desc']
                ],
                pageLength: 10,
                language: {
                    search: "<i class='fas fa-search'></i> Search:",
                    lengthMenu: "_MENU_ records per page",
                },
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
            });
            console.log('Roles DataTable initialized');
        } else {
            console.error('jQuery or DataTables not available for roles');
        }

        // Create Role Form Submission
        const createRoleForm = document.getElementById('createRoleForm');
        if (createRoleForm) {
            console.log('Create role form found, adding event listener');
            createRoleForm.addEventListener('submit', function (e) {
                e.preventDefault();
                console.log('Create role form submitted');
                const form = this;
                const submitBtn = form.querySelector('button[type="submit"]');
                const spinner = submitBtn.querySelector('.spinner-border');

                // Clear previous validation errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                if (submitBtn) submitBtn.disabled = true;
                if (spinner) spinner.classList.remove('d-none');

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                console.log('Role form data:', data);

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    window.alert('Error: CSRF token not found. Please refresh the page.');
                    return;
                }

                console.log('Sending role request to:', '{{ route('admin.roles.store') }}');

                fetch('{{ route('admin.roles.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content
                            },
                            body: JSON.stringify(data)
                        })
                    .then(response => {
                        console.log('Role response status:', response.status);
                        return response.json().then(data => ({
                            status: response.status,
                            data
                        }));
                    })
                    .then(({
                        status,
                        data
                    }) => {
                        console.log('Role response data:', data);
                        if (data.success) {
                            window.alert('Success: Role has been created successfully!');
                            window.location.reload();
                        } else {
                            if (status === 422) {
                                Object.keys(data.errors || {}).forEach(field => {
                                    const input = form.querySelector(`[name="${field}"]`);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        const feedback = input.nextElementSibling;
                                        if (feedback && feedback.classList.contains(
                                                'invalid-feedback')) {
                                            feedback.textContent = data.errors[field][0];
                                        }
                                    }
                                });
                            }
                            window.alert('Error: ' + (data.message || 'Failed to create role'));
                        }
                    })
                    .catch(error => {
                        console.error('Role fetch error:', error);
                        window.alert('Error: ' + error.message);
                    })
                    .finally(() => {
                        if (submitBtn) submitBtn.disabled = false;
                        if (spinner) spinner.classList.add('d-none');
                    });
            });
        } else {
            console.error('Create role form not found');
        }

        // Edit Role Button Click
        const editRoleButtons = document.querySelectorAll('.edit-role-btn');
        console.log('Found edit role buttons:', editRoleButtons.length);

        editRoleButtons.forEach(button => {
            button.addEventListener('click', function () {
                console.log('Edit role button clicked');
                const roleId = this.dataset.id;
                const roleName = this.dataset.name;
                const roleDescription = this.dataset.description;

                console.log('Role data:', {
                    roleId,
                    roleName,
                    roleDescription
                });

                // Clear previous validation errors
                const form = document.getElementById('editRoleForm');
                if (form) {
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                        'is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent =
                        '');

                    // Reset form fields
                    form.reset();

                    // Populate form fields
                    const roleIdField = document.getElementById('edit_role_id');
                    const nameField = document.getElementById('edit_name');
                    const descriptionField = document.getElementById('edit_description');

                    if (roleIdField) roleIdField.value = roleId;
                    if (nameField) nameField.value = roleName;
                    if (descriptionField) descriptionField.value = roleDescription || '';

                    console.log('Role form populated successfully');
                } else {
                    console.error('Edit role form not found');
                }
            });
        });

        // Edit Role Form Submission
        document.getElementById('editRoleForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const roleId = document.getElementById('edit_role_id').value;
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');

            // Clear previous validation errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            fetch(`/admin/roles/${roleId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    data
                })))
                .then(({
                    status,
                    data
                }) => {
                    if (data.success) {
                        window.alert('Success: Role has been updated successfully!');
                        window.location.reload();
                    } else {
                        if (status === 422) {
                            Object.keys(data.errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains(
                                            'invalid-feedback')) {
                                        feedback.textContent = data.errors[field][0];
                                    }
                                }
                            });
                        }
                        window.alert('Error: ' + (data.message || 'Failed to update role'));
                    }
                })
                .catch(error => {
                    window.alert('Error: ' + error.message);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                });
        });

        // Delete Role
        document.querySelectorAll('.delete-role-btn').forEach(button => {
            button.addEventListener('click', function () {
                const roleId = this.dataset.id;
                const roleName = this.dataset.name;

                if (confirm(`Are you sure you want to delete role "${roleName}"?`)) {
                    fetch(`/admin/roles/${roleId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.alert(
                                    'Success: Role has been deleted successfully!');
                                window.location.reload();
                            } else {
                                window.alert('Error: ' + (data.message ||
                                    'Failed to delete role'));
                            }
                        })
                        .catch(error => {
                            window.alert('Error: ' + error.message);
                        });
                }
            });
        });

        // Clear form validation on modal close
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                        'is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent =
                        '');
                    form.reset();
                }
            });
        });
    });

</script>
@endpush
