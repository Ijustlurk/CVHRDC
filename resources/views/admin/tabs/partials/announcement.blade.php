@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@isset($announcements)
    <div class="row mb-4 g-4">
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary fw-bold text-white">Add Announcement</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.announcement.update') }}" class="needs-validation"
                        novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                placeholder="Enter announcement title" value="{{ old('title') }}">
                            <div class="invalid-feedback">Title is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="4" required
                                placeholder="Enter announcement content">{{ old('content') }}</textarea>
                            <div class="invalid-feedback">Content is required.</div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ old('start_date') }}" required>
                                <div class="invalid-feedback">Date is required.</div>
                            </div>
                            <div class="col-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ old('end_date') }}" required>
                                <div class="invalid-feedback" id="end-date-error">Date is required.</div>
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
                    <div class="col-12">
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
                                    <form method="POST" action="{{-- route('admin.announcement.toggle', $item->id) --}}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="active" value="{{ $item->active ? 0 : 1 }}">
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-{{ $item->active ? 'success' : 'secondary' }}"
                                            title="{{ $item->active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fa fa-{{ $item->active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{-- route('admin.announcement.edit', $item->id) --}}" class="btn btn-sm btn-warning ms-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{-- route('admin.announcement.destroy', $item->id) --}}"
                                        onsubmit="return confirm('Are you sure you want to delete this announcement?');"
                                        class="d-inline ms-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
    </script>
@endisset
