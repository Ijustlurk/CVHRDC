<!-- @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif -->

<!-- @if (session('error'))
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
            title: 'Event Uploaded'
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
            title: 'Event Deleted'
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
            title: 'Failed to delete event'
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
            title: 'Event Updated'
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
            title: 'News Visibility Changed!'
        });
    });

</script>
@endif

<!-- Error Visibility -->
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
            title: 'Failed to update visibility!'
        });
    });

</script>
@endif


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


@isset($events)
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary fw-bold text-white">Add Event</div>
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
                <form method="POST" action="{{ route('admin.events.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required
                            placeholder="Enter event title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required
                            placeholder="Enter event description"></textarea>
                        <div class="invalid-feedback">Description is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" required
                            placeholder="Enter location">
                        <div class="invalid-feedback">Location is required.</div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                required>
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="organizer" class="form-label">Organizer</label>
                        <input type="text" name="organizer" id="organizer" class="form-control" required
                            placeholder="Enter organizer">
                        <div class="invalid-feedback">Organizer is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="news-image" class="form-label">
                            Image URL <span class="text-danger">*</span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="news-image" name="image_url" required
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
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1"
                            checked>
                        <label class="form-check-label" for="is_visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Event</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <!-- Search and Filter Section -->
        <div class="card shadow mb-3">
            <div class="card-header bg-info text-white fw-bold">
                <i class="fa fa-search me-2"></i>Search & Filter Events
            </div>
            <div class="card-body">
                <form id="searchFilterForm" method="GET" action="{{ route('admin.events.index') }}">
                    <div class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" name="search" 
                                    placeholder="Search by title, description, location, or organizer..." 
                                    value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearch" title="Clear search">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Time-based Quick Filters -->
                        <div class="col-12 col-md-4">
                            <label for="timeFilter" class="form-label small fw-bold">
                                <i class="fa fa-clock me-1"></i>Time Period
                            </label>
                            <select class="form-select form-select-sm" id="timeFilter" name="time_filter">
                                <option value="upcoming" {{ request('time_filter', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming Events</option>
                                <option value="past" {{ request('time_filter') == 'past' ? 'selected' : '' }}>Past Events</option>
                                <option value="all" {{ request('time_filter') == 'all' ? 'selected' : '' }}>All Events</option>
                            </select>
                        </div>

                        <!-- Visibility Filter -->
                        <div class="col-12 col-md-4">
                            <label for="visibilityFilter" class="form-label small fw-bold">
                                <i class="fa fa-filter me-1"></i>Visibility
                            </label>
                            <select class="form-select form-select-sm" id="visibilityFilter" name="visibility">
                                <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Status</option>
                                <option value="visible" {{ request('visibility') == 'visible' ? 'selected' : '' }}>Public</option>
                                <option value="hidden" {{ request('visibility') == 'hidden' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-12 col-md-4">
                            <label for="sortBy" class="form-label small fw-bold">
                                <i class="fa fa-sort me-1"></i>Sort By
                            </label>
                            <select class="form-select form-select-sm" id="sortBy" name="sort">
                                <option value="auto" {{ request('sort', 'auto') == 'auto' ? 'selected' : '' }}>Auto (Smart Sort)</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date A-Z</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date Z-A</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z-A</option>
                            </select>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="col-12">
                            <label class="form-label small fw-bold">
                                <i class="fa fa-calendar-alt me-1"></i>Custom Date Range
                                <small class="text-muted">(overrides time period filter)</small>
                            </label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="date" class="form-control form-control-sm" id="dateFrom" name="date_from" 
                                        placeholder="From date" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control form-control-sm" id="dateTo" name="date_to" 
                                        placeholder="To date" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtn">
                                    <i class="fa fa-filter me-1"></i>Apply Filters
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtn">
                                    <i class="fa fa-redo me-1"></i>Reset All
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Results Counter -->
                <div class="mt-3 pt-3 border-top">
                    <div id="resultsCounter" class="text-muted small">
                        <i class="fa fa-info-circle me-1"></i>
                        <span id="counterText">
                            Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $totalCount ?? $events->total() }} events
                        </span>
                        <span id="loadingIndicator" class="ms-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Searching...
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Cards Grid -->
        <div class="row row-cols-1 row-cols-md-2 g-3" id="eventsCardsContainer">
            @include('admin.tabs.partials.event-cards', ['events' => $events])
        </div>
        <div class="mt-3" id="paginationContainer">
            {{ $events->links() }}
        </div>
    </div>
</div>

<!-- Keep the original structure for backward compatibility -->
<div style="display: none;">
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-7">
        <div class="row row-cols-1 row-cols-md-2 g-3">
            @foreach ($events as $item)
            <div class="col">
                <div class="card h-100">
                    @php
                    $imageUrl = $item->image_url;
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
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="Event Image"
                        onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                        
                        <!-- Event Details -->
                        <div class="mb-2">
                            <small class="text-muted d-block">
                                <i class="fa fa-map-marker-alt me-1"></i><strong>Location:</strong> {{ $item->location }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fa fa-user me-1"></i><strong>Organizer:</strong> {{ $item->organizer }}
                            </small>
                            @if($item->end_date)
                            <small class="text-muted d-block">
                                <i class="fa fa-clock me-1"></i><strong>Duration:</strong> {{ $item->start_date->format('M d') }} - {{ $item->end_date->format('M d, Y') }}
                            </small>
                            @endif
                        </div>

                        <!-- Date pushed to bottom -->
                        <p class="card-text mt-auto">
                            <small class="text-muted">
                                <i class="fa fa-calendar me-1"></i>{{ $item->start_date ? $item->start_date->format('M d, Y H:i') : '' }}
                            </small>
                        </p>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <form method="POST" action="{{ route('admin.events.visibility', $item->id) }}"
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
                                    onclick="openEditEventModal({{ $item->id }})"><i class="fa fa-edit"></i></button>
                                <!-- Delete Button that triggers Modal -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteEventModal{{ $item->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteEventModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="deleteEventModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteEventModalLabel{{ $item->id }}">
                                                    <i class="fa fa-exclamation-triangle me-2"></i> Confirm Deletion
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                Are you sure you want to delete this event? <br>
                                                <strong>{{ $item->title }}</strong>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>

                                                <!-- Actual Delete Form -->
                                                <form method="POST"
                                                    action="{{ route('admin.events.destroy', $item->id) }}">
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
            {{ $events->links() }}
        </div>
    </div>
</div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editEventModalLabel">
                    <i class="fa fa-edit me-2"></i>Edit Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Success/Error Messages -->
                <div id="editEventMessageContainer" class="mb-3" style="display: none;">
                    <div id="editEventMessage" class="alert" role="alert"></div>
                </div>

                <form id="editEventForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_event_id" name="event_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold">Title <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required
                            placeholder="Enter event title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-bold">Description <span
                                class="text-danger">*</span></label>
                        <textarea name="description" id="edit_description" class="form-control" rows="4" required
                            placeholder="Enter event description"></textarea>
                        <div class="invalid-feedback">Description is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_location" class="form-label fw-bold">Location <span
                                class="text-danger">*</span></label>
                        <input type="text" name="location" id="edit_location" class="form-control" required
                            placeholder="Enter location">
                        <div class="invalid-feedback">Location is required.</div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label for="edit_start_date" class="form-label fw-bold">Start Date <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_date" id="edit_start_date" class="form-control"
                                required>
                            <div class="invalid-feedback">Start date is required.</div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="edit_end_date" class="form-label fw-bold">End Date</label>
                            <input type="datetime-local" name="end_date" id="edit_end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_organizer" class="form-label fw-bold">Organizer <span
                                class="text-danger">*</span></label>
                        <input type="text" name="organizer" id="edit_organizer" class="form-control" required
                            placeholder="Enter organizer">
                        <div class="invalid-feedback">Organizer is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image_url" class="form-label">
                            <span class="fw-bold">Image URL <span class="text-danger">*</span></span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="edit_image_url" name="image_url" required
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
                        <div class="mt-2" id="currentEventImagePreview" style="display: none;">
                            <label class="form-label fw-bold">Current Image:</label>
                            <div class="border rounded p-2">
                                <img id="previewEventImage" src="" alt="Current Event Image" class="img-fluid"
                                    style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
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
                <button type="button" class="btn btn-warning" id="updateEventBtn">
                    <i class="fa fa-save me-2"></i>Update Event
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Events Search and Filter JavaScript -->
<script>
    // Search and Filter Functionality for Events
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const timeFilter = document.getElementById('timeFilter');
    const visibilityFilter = document.getElementById('visibilityFilter');
    const sortBy = document.getElementById('sortBy');
    const clearSearchBtn = document.getElementById('clearSearch');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const searchFilterForm = document.getElementById('searchFilterForm');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performSearch();
        });
    }

    // Apply Filters button
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performSearch();
        });
    }

    // Reset All button
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            // Clear all form inputs
            if (searchInput) searchInput.value = '';
            if (timeFilter) timeFilter.value = 'upcoming';
            if (visibilityFilter) visibilityFilter.value = '';
            if (sortBy) sortBy.value = 'auto';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';
            
            // Perform search with cleared filters
            performSearch();
        });
    }

    // Filter and sort change handlers
    [timeFilter, visibilityFilter, sortBy, dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', performSearch);
        }
    });

    // Global function to clean up modal backdrops
    function cleanupModalBackdrops() {
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
        document.documentElement.style.removeProperty('overflow');
    }

    // Add global error handler for uncaught modal issues
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('modal')) {
            console.warn('Modal error detected, cleaning up backdrops');
            cleanupModalBackdrops();
        }
    });

    // Perform AJAX search
    function performSearch() {
        const formData = new FormData(searchFilterForm);
        const params = new URLSearchParams(formData);

        // Remove empty parameters for cleaner URLs
        for (let [key, value] of Array.from(params.entries())) {
            if (!value || value.trim() === '') {
                params.delete(key);
            }
        }

        // Show loading indicator
        const loadingIndicator = document.getElementById('loadingIndicator');
        const counterText = document.getElementById('counterText');
        if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

        const searchUrl = `{{ route('admin.events.index') }}?${params.toString()}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Update events cards
            const eventsContainer = document.getElementById('eventsCardsContainer');
            if (eventsContainer && data.html) {
                eventsContainer.innerHTML = data.html;
            }

            // Update pagination
            const paginationContainer = document.getElementById('paginationContainer');
            if (paginationContainer && data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }

            // Update counter
            if (counterText) {
                const from = data.from || 0;
                const to = data.to || 0;
                const total = data.totalCount || 0;
                counterText.textContent = `Showing ${from} to ${to} of ${total} events`;
            }

            // Update browser URL without page reload (for bookmarking/sharing)
            const newUrl = params.toString() ? searchUrl : '{{ route('admin.events.index') }}';
            window.history.pushState({}, '', newUrl);

            // Hide loading indicator
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        })
        .catch(error => {
            console.error('Search error:', error);
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
        if (e.target.closest('#paginationContainer a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            const loadingIndicator = document.getElementById('loadingIndicator');
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
                // Update events cards
                const eventsContainer = document.getElementById('eventsCardsContainer');
                if (eventsContainer && data.html) {
                    eventsContainer.innerHTML = data.html;
                }

                // Update pagination
                const paginationContainer = document.getElementById('paginationContainer');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update counter
                const counterText = document.getElementById('counterText');
                if (counterText) {
                    const from = data.from || 0;
                    const to = data.to || 0;
                    const total = data.totalCount || 0;
                    counterText.textContent = `Showing ${from} to ${to} of ${total} events`;
                }

                // Update browser URL
                window.history.pushState({}, '', url);

                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';

                // Scroll to top of events section
                const eventsCardsContainer = document.getElementById('eventsCardsContainer');
                if (eventsCardsContainer) {
                    eventsCardsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
                if (loadingIndicator) loadingIndicator.style.display = 'none';
            });
        }
    });

    // Emergency cleanup on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            cleanupModalBackdrops();
        }, 500);
    });

    // Add cleanup when leaving the page
    window.addEventListener('beforeunload', function() {
        cleanupModalBackdrops();
    });

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

    // Edit Event Modal Functions
    function openEditEventModal(eventId) {
        console.log('Opening edit event modal for event ID:', eventId);

        // Hide any previous messages
        hideEditEventMessage();

        // Show loading state
        const updateBtn = document.getElementById('updateEventBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        updateBtn.disabled = true;

        // Fetch event data
        fetch(`/admin/events/${eventId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Event data received:', data);

                // Populate modal fields
                document.getElementById('edit_event_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_location').value = data.location;

                // Format dates for input fields (YYYY-MM-DDTHH:MM)
                if (data.start_date) {
                    const startDate = new Date(data.start_date);
                    const formattedStartDate = startDate.toISOString().slice(0, 16);
                    document.getElementById('edit_start_date').value = formattedStartDate;
                }

                if (data.end_date) {
                    const endDate = new Date(data.end_date);
                    const formattedEndDate = endDate.toISOString().slice(0, 16);
                    document.getElementById('edit_end_date').value = formattedEndDate;
                }

                document.getElementById('edit_organizer').value = data.organizer;
                document.getElementById('edit_image_url').value = data.image_url;
                document.getElementById('edit_is_visible').checked = data.is_visible == 1;

                // Show current image preview
                showCurrentEventImagePreview(data.image_url);

                // Show modal
                const modalElement = document.getElementById('editEventModal');
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
                console.error('Error fetching event data:', error);
                showEditEventMessage('Error loading event data. Please try again.', 'danger');
            })
            .finally(() => {
                // Reset button state
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            });
    }

    // Function to show messages in the edit event modal
    function showEditEventMessage(message, type = 'info') {
        const messageContainer = document.getElementById('editEventMessageContainer');
        const messageElement = document.getElementById('editEventMessage');

        messageElement.className = `alert alert-${type}`;
        messageElement.textContent = message;
        messageContainer.style.display = 'block';

        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => hideEditEventMessage(), 3000);
        }
    }

    // Function to hide messages in the edit event modal
    function hideEditEventMessage() {
        const messageContainer = document.getElementById('editEventMessageContainer');
        messageContainer.style.display = 'none';
    }

    // Function to show current event image preview
    function showCurrentEventImagePreview(imageUrl) {
        const previewContainer = document.getElementById('currentEventImagePreview');
        const previewImage = document.getElementById('previewEventImage');

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

    // Check if event modal exists when page loads
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('editEventModal');
        console.log('Event modal element on page load:', modalElement);
        if (modalElement) {
            console.log('Event modal HTML on page load:', modalElement.outerHTML);
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

    // Handle update event button click
    document.getElementById('updateEventBtn').addEventListener('click', function () {
        const form = document.getElementById('editEventForm');
        const eventId = document.getElementById('edit_event_id').value;
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
        hiddenForm.action = `/admin/events/${eventId}`;
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
        const isVisibleCheckbox = document.getElementById('edit_is_visible');
        const isVisibleInput = document.createElement('input');
        isVisibleInput.type = 'hidden';
        isVisibleInput.name = 'is_visible';
        isVisibleInput.value = isVisibleCheckbox.checked ? '1' : '0';
        hiddenForm.appendChild(isVisibleInput);

        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method' && key !== 'is_visible') {
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

    // Reset form validation when event modal is hidden
    document.getElementById('editEventModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editEventForm');
        form.classList.remove('was-validated');
        form.reset();
        hideEditEventMessage();

        // Hide image preview
        document.getElementById('currentEventImagePreview').style.display = 'none';
    });

</script>
@endisset
