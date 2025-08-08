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
            <a class="nav-link{{ !request()->routeIs('admin.roles.*') ? ' active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users me-2"></i>Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request()->routeIs('admin.roles.*') ? ' active' : '' }}" href="{{ route('admin.roles.index') }}">
                <i class="fas fa-user-tag me-2"></i>Roles
            </a>
        </li>
    </ul>

    <div class="card shadow">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-users me-2"></i>Manage Users</h6>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-plus"></i> Create New User
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- User Status Tabs -->
            <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-users-tab" data-bs-toggle="tab" 
                            data-bs-target="#active-users" type="button" role="tab" aria-selected="true">
                        <i class="fas fa-user-check me-2"></i>Active Users
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="deleted-users-tab" data-bs-toggle="tab" 
                            data-bs-target="#deleted-users" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-user-slash me-2"></i>Deleted Users
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="userTabContent">
                <!-- Active Users Tab -->
                <div class="tab-pane fade show active" id="active-users" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="activeUsersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Created At</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary edit-user-btn" 
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-roles="{{ json_encode($user->roles->pluck('id')) }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editUserModal"
                                                    title="Edit User">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-user-btn" 
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    title="Delete User">
                                                    Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Deleted Users Tab -->
                <div class="tab-pane fade" id="deleted-users" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="deletedUsersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Deleted At</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deletedUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->deleted_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success restore-user-btn" 
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                title="Restore User">
                                            <i class="fas fa-undo-alt"></i> Restore
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Create New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Roles <span class="text-danger">*</span></label>
                        <div class="role-checkboxes border rounded p-3">
                            @foreach($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" 
                                       value="{{ $role->id }}" id="role{{ $role->id }}">
                                <label class="form-check-label" for="role{{ $role->id }}">
                                    {{ ucfirst($role->name) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="fas fa-save me-1"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                        <small class="text-muted">Leave blank to keep current password</small>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Roles <span class="text-danger">*</span></label>
                        <div class="role-checkboxes border rounded p-3">
                            @foreach($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" 
                                       value="{{ $role->id }}" id="edit_role{{ $role->id }}">
                                <label class="form-check-label" for="edit_role{{ $role->id }}">
                                    {{ ucfirst($role->name) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <i class="fas fa-save me-1"></i>Update User
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content);
    
    // Initialize DataTables with custom settings
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#activeUsersTable, #deletedUsersTable').DataTable({
            order: [[3, 'desc']],
            pageLength: 10,
            language: {
                search: "<i class='fas fa-search'></i> Search:",
                lengthMenu: "_MENU_ records per page",
            },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
        });
        console.log('DataTables initialized');
    } else {
        console.error('jQuery or DataTables not available');
    }

    // Create User Form Submission
    const createForm = document.getElementById('createUserForm');
    if (createForm) {
        console.log('Create form found, adding event listener');
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Create form submitted');
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
            
            // Handle multiple checkbox values for roles
            const roles = formData.getAll('roles[]');
            data.roles = roles;
            
            console.log('Form data:', data);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                window.alert('Error: CSRF token not found. Please refresh the page.');
                return;
            }

            console.log('Sending request to:', '{{ route("admin.users.store") }}');
            
            fetch('{{ route("admin.users.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().then(data => ({ status: response.status, data }));
            })
            .then(({ status, data }) => {
                console.log('Response data:', data);
                if (data.success) {
                    window.alert('Success: User has been created successfully!');
                    window.location.reload();
                } else {
                    if (status === 422) {
                        Object.keys(data.errors || {}).forEach(field => {
                            if (field === 'roles') {
                                const roleContainer = form.querySelector('.role-checkboxes');
                                const feedback = roleContainer?.parentElement?.querySelector('.invalid-feedback');
                                if (roleContainer && feedback) {
                                    roleContainer.classList.add('is-invalid');
                                    feedback.textContent = data.errors[field][0];
                                    feedback.style.display = 'block';
                                }
                            } else {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = data.errors[field][0];
                                    }
                                }
                            }
                        });
                    }
                    window.alert('Error: ' + (data.message || 'Failed to create user'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                window.alert('Error: ' + error.message);
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            });
        });
    } else {
        console.error('Create form not found');
    }

    // Edit User Button Click
    const editButtons = document.querySelectorAll('.edit-user-btn');
    console.log('Found edit buttons:', editButtons.length);
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Edit button clicked');
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const userEmail = this.dataset.email;
            const userRolesData = this.dataset.roles;
            
            console.log('User data:', { userId, userName, userEmail, userRolesData });
            
            let userRoles = [];
            try {
                userRoles = JSON.parse(userRolesData);
                console.log('Parsed roles:', userRoles);
            } catch (e) {
                console.error('Error parsing roles:', e);
            }

            // Clear previous validation errors
            const form = document.getElementById('editUserForm');
            if (form) {
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                // Reset form fields
                form.reset();

                // Populate form fields
                const userIdField = document.getElementById('edit_user_id');
                const nameField = document.getElementById('edit_name');
                const emailField = document.getElementById('edit_email');
                
                if (userIdField) userIdField.value = userId;
                if (nameField) nameField.value = userName;
                if (emailField) emailField.value = userEmail;

                // Reset and set role checkboxes
                form.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                    checkbox.checked = userRoles.includes(parseInt(checkbox.value));
                });
                
                console.log('Form populated successfully');
            } else {
                console.error('Edit form not found');
            }
        });
    });

    // Edit User Form Submission
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const userId = document.getElementById('edit_user_id').value;
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        // Clear previous validation errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Handle multiple checkbox values for roles
        const roles = formData.getAll('roles[]');
        data.roles = roles;

        fetch(`/admin/users/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
            if (data.success) {
                window.alert('Success: User has been updated successfully!');
                window.location.reload();
            } else {
                if (status === 422) {
                    Object.keys(data.errors).forEach(field => {
                        if (field === 'roles') {
                            const roleContainer = form.querySelector('.role-checkboxes');
                            const feedback = roleContainer.parentElement.querySelector('.invalid-feedback');
                            if (roleContainer && feedback) {
                                roleContainer.classList.add('is-invalid');
                                feedback.textContent = data.errors[field][0];
                                feedback.style.display = 'block';
                            }
                        } else {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = data.errors[field][0];
                                }
                            }
                        }
                    });
                }
                window.alert('Error: ' + (data.message || 'Failed to update user'));
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

    // Delete User
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            if (confirm(`Are you sure you want to delete user "${userName}"?`)) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.alert('Success: User has been deleted successfully!');
                        window.location.reload();
                    } else {
                        window.alert('Error: ' + (data.message || 'Failed to delete user'));
                    }
                })
                .catch(error => {
                    window.alert('Error: ' + error.message);
                });
            }
        });
    });

    // Restore User
    document.querySelectorAll('.restore-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            if (confirm(`Are you sure you want to restore user "${userName}"?`)) {
                fetch(`/admin/users/${userId}/restore`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.alert('Success: User has been restored successfully!');
                        window.location.reload();
                    } else {
                        window.alert('Error: ' + (data.message || 'Failed to restore user'));
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
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                form.reset();
            }
        });
    });
});
</script>
@endpush