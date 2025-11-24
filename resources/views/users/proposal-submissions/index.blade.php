@extends('layouts.Users.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">My Proposal Submissions</h4>
        <a href="{{ route('users.proposal-submissions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Submit New Proposal
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <!-- Search and Filter Section -->
            <div class="card-header bg-light border-bottom py-2 mb-3" style="background-color: #f8f9fa !important;">
                <form id="searchFilterFormSubmissions" method="GET" action="{{ route('users.proposal-submissions.index') }}" class="mb-0">
                    <!-- Search Bar -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInputSubmissions" name="search"
                                       placeholder="Search by title, proponent, affiliation, or call..."
                                       value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearchSubmissions" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Row -->
                    <div class="row g-2 mb-2">
                        <!-- Status Filter -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <select class="form-select form-select-sm" id="statusFilterSubmissions" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <select class="form-select form-select-sm" id="dateFilterSubmissions" name="date_filter">
                                <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                            <select class="form-select form-select-sm" id="sortBySubmissions" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>Title A-Z</option>
                                <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Title Z-A</option>
                                <option value="budget_high" {{ request('sort') == 'budget_high' ? 'selected' : '' }}>Budget High-Low</option>
                                <option value="budget_low" {{ request('sort') == 'budget_low' ? 'selected' : '' }}>Budget Low-High</option>
                            </select>
                        </div>
                    </div>

                    <!-- Custom Date Range (shown when Custom Range is selected) -->
                    <div class="row mb-2 g-2" id="customDateRangeSubmissions" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                        <div class="col-6">
                            <label for="dateFromSubmissions" class="form-label small text-muted mb-1">From Date</label>
                            <input type="date" class="form-control form-control-sm" id="dateFromSubmissions" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-6">
                            <label for="dateToSubmissions" class="form-label small text-muted mb-1">To Date</label>
                            <input type="date" class="form-control form-control-sm" id="dateToSubmissions" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <!-- Action Buttons and Results Counter -->
                    <div class="row align-items-center pt-1">
                        <div class="col-lg-8 col-md-7">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnSubmissions">
                                    <i class="fas fa-filter me-1"></i>Apply
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnSubmissions">
                                    <i class="fas fa-redo me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 text-md-end">
                            <div id="resultsCounterSubmissions" class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="counterTextSubmissions">
                                    {{ $submissions->firstItem() ?? 0 }} - {{ $submissions->lastItem() ?? 0 }} of {{ $totalCount ?? $submissions->total() }}
                                </span>
                                <span id="loadingIndicatorSubmissions" class="ms-2" style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div id="submissionsTableContainer">
                @include('users.proposal-submissions.submissions-table', ['submissions' => $submissions])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeSubmissionsFilters();
});

// Proposal Submissions Search and Filter Functionality
let submissionsSearchTimeout;

function initializeSubmissionsFilters() {
    const searchInput = document.getElementById('searchInputSubmissions');
    const statusFilter = document.getElementById('statusFilterSubmissions');
    const dateFilter = document.getElementById('dateFilterSubmissions');
    const sortBy = document.getElementById('sortBySubmissions');
    const clearSearchBtn = document.getElementById('clearSearchSubmissions');
    const customDateRange = document.getElementById('customDateRangeSubmissions');
    const dateFrom = document.getElementById('dateFromSubmissions');
    const dateTo = document.getElementById('dateToSubmissions');
    const searchFilterForm = document.getElementById('searchFilterFormSubmissions');
    const applyFiltersBtn = document.getElementById('applyFiltersBtnSubmissions');
    const resetFiltersBtn = document.getElementById('resetFiltersBtnSubmissions');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSubmissionsSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(submissionsSearchTimeout);
            submissionsSearchTimeout = setTimeout(() => {
                performSubmissionsSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performSubmissionsSearch();
        });
    }

    // Apply Filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performSubmissionsSearch();
        });
    }

    // Reset All button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            if (sortBy) sortBy.value = 'newest';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';

            if (customDateRange) {
                customDateRange.style.display = 'none';
            }

            performSubmissionsSearch();
        });
    }

    // Filter and sort change handlers
    [statusFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performSubmissionsSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performSubmissionsSearch();
                }
            });
        }
    });

    // Show/hide custom date range
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        });
    }
}

// Perform AJAX search for Submissions
function performSubmissionsSearch() {
    const formData = new FormData(document.getElementById('searchFilterFormSubmissions'));
    const params = new URLSearchParams(formData);

    // Remove empty parameters for cleaner URLs
    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    // Show loading indicator
    const loadingIndicator = document.getElementById('loadingIndicatorSubmissions');
    const counterText = document.getElementById('counterTextSubmissions');
    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

    const searchUrl = `{{ route('users.proposal-submissions.index') }}?${params.toString()}`;

    fetch(searchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update submissions table
        const tableContainer = document.getElementById('submissionsTableContainer');
        if (tableContainer && data.html) {
            tableContainer.innerHTML = data.html;
        }

        // Update counter
        if (counterText) {
            const from = data.from || 0;
            const to = data.to || 0;
            const total = data.totalCount || 0;
            counterText.textContent = `${from} - ${to} of ${total}`;
        }

        // Update browser URL
        const newUrl = params.toString() ? searchUrl : '{{ route('users.proposal-submissions.index') }}';
        window.history.pushState({}, '', newUrl);

        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('Submissions search error:', error);
        if (loadingIndicator) loadingIndicator.style.display = 'none';

        alert('Search failed. Please try again.');
    });
}

// Handle pagination clicks with AJAX
document.addEventListener('click', function(e) {
    if (e.target.closest('#submissionsTableContainer #submissionsPagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;

        const loadingIndicator = document.getElementById('loadingIndicatorSubmissions');
        const counterText = document.getElementById('counterTextSubmissions');

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
            // Update submissions table
            const tableContainer = document.getElementById('submissionsTableContainer');
            if (tableContainer && data.html) {
                tableContainer.innerHTML = data.html;
            }

            // Update counter
            if (counterText) {
                const from = data.from || 0;
                const to = data.to || 0;
                const total = data.totalCount || 0;
                counterText.textContent = `${from} - ${to} of ${total}`;
            }

            // Update browser URL
            window.history.pushState({}, '', url);

            // Hide loading indicator
            if (loadingIndicator) loadingIndicator.style.display = 'none';

            // Scroll to top of container
            const container = document.querySelector('.container');
            if (container) {
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        })
        .catch(error => {
            console.error('Pagination error:', error);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        });
    }
});
</script>

<style>
/* Embedded Search and Filter Layout */
.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6 !important;
    padding: 0.5rem 1rem !important;
}

.card-header.bg-light .form-select-sm {
    font-size: 0.875rem !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 0.25rem !important;
}

.card-header.bg-light .input-group-text {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
    background-color: #e9ecef !important;
    border-color: #ced4da !important;
}

.card-header.bg-light input.form-control {
    font-size: 0.875rem !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 0.25rem !important;
}

.card-header.bg-light .btn-sm {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
}

.card-header.bg-light .btn-outline-secondary {
    border-left: 0 !important;
    border-radius: 0 0.25rem 0.25rem 0 !important;
}

.card-header.bg-light .row.mb-2 {
    margin-bottom: 0.5rem !important;
}

.card-header.bg-light .row.g-2 {
    --bs-gutter-x: 0.4rem !important;
    --bs-gutter-y: 0.4rem !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-header.bg-light {
        padding: 0.5rem 0.75rem !important;
    }

    .card-header.bg-light .text-md-end {
        text-align: center !important;
        margin-top: 0.4rem;
    }
}

/* Focus states */
.card-header.bg-light .form-control:focus {
    border-color: #80bdff !important;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15) !important;
}

.card-header.bg-light .form-select:focus {
    border-color: #80bdff !important;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15) !important;
}
</style>
@endpush
