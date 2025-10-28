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
            title: 'News Uploaded'
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
            title: 'News Deleted'
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
            title: 'News Updated'
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

@isset($news)
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-5">
        <div class="card shadow h-100">
            <div class="card-header bg-primary fw-bold text-white">Add News</div>
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
                <!-- Success/Error Messages -->
                <div id="editMessageContainer" class="mb-3" style="display: none;">
                    <div id="editMessage" class="alert" role="alert"></div>
                </div>
                <form method="POST" action="{{ route('admin.news.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required
                            placeholder="Enter news title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="4" required
                            placeholder="Enter news content"></textarea>
                        <div class="invalid-feedback">Content is required.</div>
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
                    <div class="mb-3">
                        <label for="published_at" class="form-label">Published At</label>
                        <input type="date" name="published_at" id="published_at" class="form-control" required>
                        <div class="invalid-feedback">Published date is required.</div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1"
                            checked>
                        <label class="form-check-label" for="is_visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add News</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <!-- Search and Filter Section -->
        <div class="card shadow mb-3">
            <div class="card-header bg-info text-white fw-bold">
                <i class="fa fa-search me-2"></i>Search & Filter News
            </div>
            <div class="card-body">
                <form id="searchFilterForm" method="GET" action="{{ route('admin.news.index') }}">
                    <div class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" name="search" 
                                    placeholder="Search by title or content..." 
                                    value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearch" title="Clear search">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Date Filter -->
                        <div class="col-12 col-md-4">
                            <label for="dateFilter" class="form-label small fw-bold">
                                <i class="fa fa-calendar me-1"></i>Date Published
                            </label>
                            <select class="form-select form-select-sm" id="dateFilter" name="date_filter">
                                <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-12 col-md-4">
                            <label for="statusFilter" class="form-label small fw-bold">
                                <i class="fa fa-filter me-1"></i>Status
                            </label>
                            <select class="form-select form-select-sm" id="statusFilter" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                <option value="public" {{ request('status') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ request('status') == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-12 col-md-4">
                            <label for="sortBy" class="form-label small fw-bold">
                                <i class="fa fa-sort me-1"></i>Sort By
                            </label>
                            <select class="form-select form-select-sm" id="sortBy" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="col-12" id="customDateRange" style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="dateFrom" class="form-label small">From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="dateFrom" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="dateTo" class="form-label small">To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="dateTo" name="date_to" value="{{ request('date_to') }}">
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
                            Showing {{ $news->firstItem() ?? 0 }} to {{ $news->lastItem() ?? 0 }} of {{ $totalCount ?? $news->total() }} news items
                        </span>
                        <span id="loadingIndicator" class="ms-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Searching...
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Cards Grid -->
        <div class="row row-cols-1 row-cols-md-2 g-3" id="newsCardsContainer">
            @include('admin.tabs.partials.news-cards', ['news' => $news])
        </div>
        <div class="mt-3" id="paginationContainer">
            {{ $news->links() }}
        </div>
    </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editNewsModalLabel">
                    <i class="fa fa-edit me-2"></i>Edit News
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form id="editNewsForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_news_id" name="news_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold">Title <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required
                            placeholder="Enter news title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_content" class="form-label fw-bold">Content <span
                                class="text-danger">*</span></label>
                        <textarea name="content" id="edit_content" class="form-control" rows="4" required
                            placeholder="Enter news content"></textarea>
                        <div class="invalid-feedback">Content is required.</div>
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
                        <div class="mt-2" id="currentImagePreview" style="display: none;">
                            <label class="form-label fw-bold">Current Image:</label>
                            <div class="border rounded p-2">
                                <img id="previewImage" src="" alt="Current Image" class="img-fluid"
                                    style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_published_at" class="form-label fw-bold">Published At <span
                                class="text-danger">*</span></label>
                        <input type="date" name="published_at" id="edit_published_at" class="form-control" required>
                        <div class="invalid-feedback">Published date is required.</div>
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
                <button type="button" class="btn btn-warning" id="updateNewsBtn">
                    <i class="fa fa-save me-2"></i>Update News
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

    // Search and Filter Functionality
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    const clearSearchBtn = document.getElementById('clearSearch');
    const customDateRange = document.getElementById('customDateRange');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const searchFilterForm = document.getElementById('searchFilterForm');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            performSearch(); // Use AJAX search instead
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500); // Wait 500ms after user stops typing
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
            if (dateFilter) dateFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            if (sortBy) sortBy.value = 'newest';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';
            if (customDateRange) customDateRange.style.display = 'none';
            
            // Perform search with cleared filters
            performSearch();
        });
    }

    // Filter and sort change handlers
    [dateFilter, statusFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter.value === 'custom') {
                    performSearch();
                }
            });
        }
    });

    // Show/hide custom date range
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.style.display = 'block';
            } else {
                customDateRange.style.display = 'none';
            }
        });
    }

    // Perform AJAX search
    function performSearch() {
        const formData = new FormData(document.getElementById('searchFilterForm'));
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

        const searchUrl = `{{ route('admin.news.index') }}?${params.toString()}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update news cards
            const newsContainer = document.getElementById('newsCardsContainer');
            if (newsContainer && data.html) {
                newsContainer.innerHTML = data.html;
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
                counterText.textContent = `Showing ${from} to ${to} of ${total} news items`;
            }

            // Update browser URL without page reload (for bookmarking/sharing)
            const newUrl = params.toString() ? searchUrl : '{{ route('admin.news.index') }}';
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
                // Update news cards
                const newsContainer = document.getElementById('newsCardsContainer');
                if (newsContainer && data.html) {
                    newsContainer.innerHTML = data.html;
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
                    counterText.textContent = `Showing ${from} to ${to} of ${total} news items`;
                }

                // Update browser URL
                window.history.pushState({}, '', url);

                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';

                // Scroll to top of news section
                const newsCardsContainer = document.getElementById('newsCardsContainer');
                if (newsCardsContainer) {
                    newsCardsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
                if (loadingIndicator) loadingIndicator.style.display = 'none';
            });
        }
    });

    // Edit News Modal Functions
    function openEditModal(newsId) {
        console.log('Opening edit modal for news ID:', newsId);

        // Hide any previous messages
        hideEditMessage();

        // Show loading state
        const updateBtn = document.getElementById('updateNewsBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        updateBtn.disabled = true;

        // Fetch news data
        fetch(`/admin/news/${newsId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Populate modal fields
                document.getElementById('edit_news_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_content').value = data.content;
                document.getElementById('edit_image').value = data.image;

                // Format date for input field (YYYY-MM-DD)
                const publishedDate = new Date(data.published_at);
                const formattedDate = publishedDate.toISOString().split('T')[0];
                document.getElementById('edit_published_at').value = formattedDate;

                document.getElementById('edit_is_visible').checked = data.is_visible == 1;

                // Show current image preview
                showCurrentImagePreview(data.image);

                // Show modal
                const modalElement = document.getElementById('editNewsModal');
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
                console.error('Error fetching news data:', error);
                showEditMessage('Error loading news data. Please try again.', 'danger');
            })
            .finally(() => {
                // Reset button state
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            });
    }

    // Function to show messages in the edit modal
    function showEditMessage(message, type = 'info') {
        const messageContainer = document.getElementById('editMessageContainer');
        const messageElement = document.getElementById('editMessage');

        messageElement.className = `alert alert-${type}`;
        messageElement.textContent = message;
        messageContainer.style.display = 'block';

        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => hideEditMessage(), 3000);
        }
    }

    // Function to hide messages in the edit modal
    function hideEditMessage() {
        const messageContainer = document.getElementById('editMessageContainer');
        messageContainer.style.display = 'none';
    }


    // Check if modal exists when page loads
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('editNewsModal');
        console.log('Modal element on page load:', modalElement);
        if (modalElement) {
            console.log('Modal HTML on page load:', modalElement.outerHTML);
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

    // Function to show current image preview
    function showCurrentImagePreview(imageUrl) {
        const previewContainer = document.getElementById('currentImagePreview');
        const previewImage = document.getElementById('previewImage');

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

    // Handle update button click
    document.getElementById('updateNewsBtn').addEventListener('click', function () {
        const form = document.getElementById('editNewsForm');
        const newsId = document.getElementById('edit_news_id').value;
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
        hiddenForm.action = `/admin/news/${newsId}`;
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

    // Reset form validation when modal is hidden
    document.getElementById('editNewsModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editNewsForm');
        form.classList.remove('was-validated');
        form.reset();
        hideEditMessage();

        // Hide image preview
        document.getElementById('currentImagePreview').style.display = 'none';
    });

</script>
@endisset
