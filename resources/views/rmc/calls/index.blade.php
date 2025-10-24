@extends('layouts.RMC.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Calls for Proposals Management</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Create and manage calls for proposals for research funding opportunities.
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                html: '<p class="mb-0">{{ session('success') }}</p>',
                icon: 'success',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                customClass: {
                    popup: 'swal-wide',
                    confirmButton: 'btn btn-success btn-lg px-4'
                },
                buttonsStyling: false
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                html: '<p class="mb-0">{{ session('error') }}</p>',
                icon: 'error',
                confirmButtonText: '<i class="fas fa-times me-2"></i>OK',
                customClass: {
                    popup: 'swal-wide',
                    confirmButton: 'btn btn-danger btn-lg px-4'
                },
                buttonsStyling: false
            });
        });
    </script>
    @endif


    <!-- Calls Management Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link{{ request('tab', 'all-calls') === 'all-calls' ? ' active' : '' }}"
               href="javascript:void(0)" onclick="switchToAllCalls()">
                <i class="fas fa-list me-2"></i>All Calls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'my-calls' ? ' active' : '' }}"
               href="javascript:void(0)" onclick="switchToMyCalls()">
                <i class="fas fa-user me-2"></i>My Calls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'create-call' ? ' active' : '' }}"
               href="{{ route('rmc.calls.index', ['tab' => 'create-call']) }}">
                <i class="fas fa-plus me-2"></i>Create New Call
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- All Calls Tab -->
        <div class="tab-pane fade{{ request('tab', 'all-calls') === 'all-calls' ? ' show active' : '' }}" id="all-calls">
            <div class="card shadow">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-bullhorn me-2"></i>All Calls for Proposals</h6>
                    </div>
                </div>

                <!-- Search and Filter Section (embedded in tab) -->
                <div class="card-header bg-light border-bottom py-2" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterForm" method="GET" action="{{ route('rmc.calls.index') }}" class="mb-0">
                        <input type="hidden" name="tab" value="all-calls">

                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInput" name="search"
                                           placeholder="Search by title, research priority area, or description..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearch" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Status Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="statusFilter" name="status">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="For Review" {{ request('status') == 'For Review' ? 'selected' : '' }}>For Review</option>
                                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Visibility Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilter" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilter" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortBy" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                    <option value="funding_high" {{ request('sort') == 'funding_high' ? 'selected' : '' }}>Funding High</option>
                                    <option value="funding_low" {{ request('sort') == 'funding_low' ? 'selected' : '' }}>Funding Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRange" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFrom" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFrom" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateTo" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateTo" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtn">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtn">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounter" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterText">
                                        {{ $calls->firstItem() ?? 0 }} - {{ $calls->lastItem() ?? 0 }} of {{ $calls->total() }}
                                    </span>
                                    <span id="loadingIndicator" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body" id="allCallsTable">
                    @include('rmc.calls.partials.calls-table', ['calls' => $calls])
                </div>
            </div>
        </div>

        <!-- My Calls Tab -->
        <div class="tab-pane fade{{ request('tab') === 'my-calls' ? ' show active' : '' }}" id="my-calls">
            <div class="card shadow">
                <div class="card-header bg-success bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-user me-2"></i>My Calls for Proposals</h6>
                    </div>
                </div>

                <!-- Search and Filter Section (embedded in tab) -->
                <div class="card-header bg-light border-bottom py-2" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterFormMyCalls" method="GET" action="{{ route('rmc.calls.index') }}" class="mb-0">
                        <input type="hidden" name="tab" value="my-calls">

                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputMyCalls" name="search"
                                           placeholder="Search by title, research priority area, or description..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchMyCalls" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Status Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="statusFilterMyCalls" name="status">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="For Review" {{ request('status') == 'For Review' ? 'selected' : '' }}>For Review</option>
                                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Visibility Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilterMyCalls" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterMyCalls" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByMyCalls" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                    <option value="funding_high" {{ request('sort') == 'funding_high' ? 'selected' : '' }}>Funding High</option>
                                    <option value="funding_low" {{ request('sort') == 'funding_low' ? 'selected' : '' }}>Funding Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRangeMyCalls" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromMyCalls" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromMyCalls" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToMyCalls" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToMyCalls" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-success btn-sm" id="applyFiltersBtnMyCalls">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnMyCalls">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterMyCalls" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextMyCalls">
                                        {{ $calls->firstItem() ?? 0 }} - {{ $calls->lastItem() ?? 0 }} of {{ $calls->total() }}
                                    </span>
                                    <span id="loadingIndicatorMyCalls" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body" id="myCallsTable">
                    @include('rmc.calls.partials.calls-table', ['calls' => $calls])
                </div>
            </div>
        </div>

        <!-- Create Call Tab -->
        <div class="tab-pane fade{{ request('tab') === 'create-call' ? ' show active' : '' }}" id="create-call">
            @include('rmc.calls.create')
        </div>
    </div>
</div>

<script>
// SweetAlert confirmation for call deletion
function confirmCallDeletion(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Delete Call for Proposals?',
        html: `<p class="mb-0">Are you sure you want to delete "<strong>${callTitle}</strong>"? This action cannot be undone and will permanently remove all associated data.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Yes, Delete Call',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-danger btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting Call...',
                html: '<p class="mb-0">Please wait while we delete the call for proposals.</p>',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide'
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            event.target.submit();
        }
    });

    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filter functionality for both tabs
    initializeAllCallsFilters();
    initializeMyCallsFilters();
});

// All Calls Tab Search and Filter Functionality
let allCallsSearchTimeout;

function initializeAllCallsFilters() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const visibilityFilter = document.getElementById('visibilityFilter');
    const dateFilter = document.getElementById('dateFilter');
    const sortBy = document.getElementById('sortBy');
    const clearSearchBtn = document.getElementById('clearSearch');
    const customDateRange = document.getElementById('customDateRange');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const searchFilterForm = document.getElementById('searchFilterForm');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performAllCallsSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(allCallsSearchTimeout);
            allCallsSearchTimeout = setTimeout(() => {
                performAllCallsSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performAllCallsSearch();
        });
    }

    // Apply Filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performAllCallsSearch();
        });
    }

    // Reset All button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            // Clear all form inputs
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (visibilityFilter) visibilityFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            if (sortBy) sortBy.value = 'newest';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';

            // Hide custom date range
            if (customDateRange) {
                customDateRange.style.display = 'none';
            }

            // Perform search with cleared filters
            performAllCallsSearch();
        });
    }

    // Filter and sort change handlers
    [statusFilter, visibilityFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performAllCallsSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performAllCallsSearch();
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

// My Calls Tab Search and Filter Functionality
let myCallsSearchTimeout;

function initializeMyCallsFilters() {
    const searchInput = document.getElementById('searchInputMyCalls');
    const statusFilter = document.getElementById('statusFilterMyCalls');
    const visibilityFilter = document.getElementById('visibilityFilterMyCalls');
    const dateFilter = document.getElementById('dateFilterMyCalls');
    const sortBy = document.getElementById('sortByMyCalls');
    const clearSearchBtn = document.getElementById('clearSearchMyCalls');
    const customDateRange = document.getElementById('customDateRangeMyCalls');
    const dateFrom = document.getElementById('dateFromMyCalls');
    const dateTo = document.getElementById('dateToMyCalls');
    const searchFilterForm = document.getElementById('searchFilterFormMyCalls');
    const applyFiltersBtn = document.getElementById('applyFiltersBtnMyCalls');
    const resetFiltersBtn = document.getElementById('resetFiltersBtnMyCalls');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performMyCallsSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(myCallsSearchTimeout);
            myCallsSearchTimeout = setTimeout(() => {
                performMyCallsSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performMyCallsSearch();
        });
    }

    // Apply Filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performMyCallsSearch();
        });
    }

    // Reset All button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            // Clear all form inputs
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (visibilityFilter) visibilityFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            if (sortBy) sortBy.value = 'newest';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';

            // Hide custom date range
            if (customDateRange) {
                customDateRange.style.display = 'none';
            }

            // Perform search with cleared filters
            performMyCallsSearch();
        });
    }

    // Filter and sort change handlers
    [statusFilter, visibilityFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performMyCallsSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performMyCallsSearch();
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

// Perform AJAX search for All Calls tab
function performAllCallsSearch() {
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

    const searchUrl = `{{ route('rmc.calls.index') }}?${params.toString()}`;

    fetch(searchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update calls table
        const tableContainer = document.getElementById('allCallsTable');
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
        const newUrl = params.toString() ? searchUrl : '{{ route('rmc.calls.index') }}';
        window.history.pushState({}, '', newUrl);

        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('All Calls search error:', error);
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

// Perform AJAX search for My Calls tab
function performMyCallsSearch() {
    const formData = new FormData(document.getElementById('searchFilterFormMyCalls'));
    const params = new URLSearchParams(formData);

    // Remove empty parameters for cleaner URLs
    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    // Show loading indicator
    const loadingIndicator = document.getElementById('loadingIndicatorMyCalls');
    const counterText = document.getElementById('counterTextMyCalls');
    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

    const searchUrl = `{{ route('rmc.calls.index') }}?${params.toString()}`;

    fetch(searchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update calls table
        const tableContainer = document.getElementById('myCallsTable');
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
        const newUrl = params.toString() ? searchUrl : '{{ route('rmc.calls.index') }}';
        window.history.pushState({}, '', newUrl);

        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('My Calls search error:', error);
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

// Tab switching functions
function switchToAllCalls() {
    // Get current search parameters from My Calls form if active
    const currentParams = getCurrentSearchParams();

    // Update active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector('[onclick="switchToAllCalls()"]').classList.add('active');

    // Update tab pane visibility
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    document.getElementById('all-calls').classList.add('show', 'active');

    // Update URL without triggering page reload
    const newUrl = buildTabUrl('all-calls', currentParams);
    window.history.pushState({}, '', newUrl);

    // Update the All Calls form with current parameters if coming from My Calls
    if (currentParams && Object.keys(currentParams).length > 0) {
        updateAllCallsForm(currentParams);
    }
}

function switchToMyCalls() {
    // Get current search parameters from All Calls form if active
    const currentParams = getCurrentSearchParams();

    // Update active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector('[onclick="switchToMyCalls()"]').classList.add('active');

    // Update tab pane visibility
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    document.getElementById('my-calls').classList.add('show', 'active');

    // Update URL without triggering page reload
    const newUrl = buildTabUrl('my-calls', currentParams);
    window.history.pushState({}, '', newUrl);

    // Update the My Calls form with current parameters if coming from All Calls
    if (currentParams && Object.keys(currentParams).length > 0) {
        updateMyCallsForm(currentParams);
    }
}

function getCurrentSearchParams() {
    const activeTab = document.querySelector('.tab-pane.show.active').id;

    if (activeTab === 'all-calls') {
        const form = document.getElementById('searchFilterForm');
        if (form) {
            const formData = new FormData(form);
            const params = {};
            for (let [key, value] of formData.entries()) {
                if (value && value.trim() !== '') {
                    params[key] = value;
                }
            }
            return params;
        }
    } else if (activeTab === 'my-calls') {
        const form = document.getElementById('searchFilterFormMyCalls');
        if (form) {
            const formData = new FormData(form);
            const params = {};
            for (let [key, value] of formData.entries()) {
                if (value && value.trim() !== '') {
                    params[key] = value;
                }
            }
            return params;
        }
    }

    return {};
}

function buildTabUrl(tab, params) {
    const baseUrl = '{{ route('rmc.calls.index') }}';
    const urlParams = new URLSearchParams();

    urlParams.set('tab', tab);

    if (params) {
        Object.keys(params).forEach(key => {
            urlParams.set(key, params[key]);
        });
    }

    return urlParams.toString() ? `${baseUrl}?${urlParams.toString()}` : baseUrl;
}

function updateAllCallsForm(params) {
    const form = document.getElementById('searchFilterForm');
    if (form && params) {
        Object.keys(params).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = params[key];
            }
        });

        // Update custom date range visibility
        const dateFilter = form.querySelector('[name="date_filter"]');
        const customDateRange = document.getElementById('customDateRange');
        if (dateFilter && customDateRange) {
            if (params.date_filter === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        }
    }
}

function updateMyCallsForm(params) {
    const form = document.getElementById('searchFilterFormMyCalls');
    if (form && params) {
        Object.keys(params).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = params[key];
            }
        });

        // Update custom date range visibility
        const dateFilter = form.querySelector('[name="date_filter"]');
        const customDateRange = document.getElementById('customDateRangeMyCalls');
        if (dateFilter && customDateRange) {
            if (params.date_filter === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        }
    }
}

// Handle pagination clicks with AJAX for both tabs
document.addEventListener('click', function(e) {
    if (e.target.closest('#allCallsTable #callsPagination a') || e.target.closest('#myCallsTable #callsPagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;

        // Determine which tab is active and show appropriate loading indicator
        const activeTab = document.querySelector('.tab-pane.show.active').id;
        const loadingIndicator = document.getElementById(`loadingIndicator${activeTab === 'my-calls' ? 'MyCalls' : ''}`);
        const counterText = document.getElementById(`counterText${activeTab === 'my-calls' ? 'MyCalls' : ''}`);

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
            // Update calls table
            const tableContainer = document.getElementById(`${activeTab}Table`);
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

            // Scroll to top of table section
            const tableSection = document.querySelector('.tab-pane.show.active .card-body');
            if (tableSection) {
                tableSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
/* Enhanced SweetAlert Styling */
.swal2-popup {
    font-size: 1rem !important;
    padding: 2rem !important;
}

.swal2-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    margin-bottom: 1rem !important;
}

.swal2-html-container {
    font-size: 1rem !important;
    color: #5a6c7d !important;
    margin: 1rem 0 !important;
}

.swal2-actions {
    margin-top: 1.5rem !important;
    gap: 0.5rem !important;
}

/* Success Button Styling */
.swal2-popup .btn-success {
    background-color: #28a745 !important;
    border: 2px solid #28a745 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3) !important;
}

.swal2-popup .btn-success:hover {
    background-color: #218838 !important;
    border-color: #1e7e34 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(40, 167, 69, 0.4) !important;
}

.swal2-popup .btn-success:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
}

/* Danger Button Styling */
.swal2-popup .btn-danger {
    background-color: #dc3545 !important;
    border: 2px solid #dc3545 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3) !important;
}

.swal2-popup .btn-danger:hover {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4) !important;
}

.swal2-popup .btn-danger:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5) !important;
}

/* Secondary/Cancel Button Styling */
.swal2-popup .btn-secondary {
    background-color: #6c757d !important;
    border: 2px solid #6c757d !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(108, 117, 125, 0.3) !important;
}

.swal2-popup .btn-secondary:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(108, 117, 125, 0.4) !important;
}

.swal2-popup .btn-secondary:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
}

.swal-wide {
    width: 35rem !important;
    max-width: 90vw !important;
}

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

.card-header.bg-light #searchInput,
.card-header.bg-light #searchInputMyCalls {
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

.card-header.bg-light .pt-1 {
    padding-top: 0.25rem !important;
}

.card-header.bg-light .mb-1 {
    margin-bottom: 0.25rem !important;
}

/* Responsive adjustments for embedded layout */
@media (max-width: 768px) {
    .card-header.bg-light {
        padding: 0.5rem 0.75rem !important;
    }

    .card-header.bg-light .row.mb-2 {
        margin-bottom: 0.4rem !important;
    }

    .card-header.bg-light .text-md-end {
        text-align: center !important;
        margin-top: 0.4rem;
    }

    .card-header.bg-light .d-flex.gap-2 {
        justify-content: center !important;
    }

    .card-header.bg-light .col-lg-8, .card-header.bg-light .col-md-7 {
        text-align: center !important;
    }
}

@media (max-width: 576px) {
    .card-header.bg-light {
        padding: 0.4rem 0.5rem !important;
    }

    .card-header.bg-light .btn-sm {
        padding: 0.2rem 0.4rem !important;
        font-size: 0.8rem !important;
    }

    .card-header.bg-light .form-select-sm {
        font-size: 0.8rem !important;
        padding: 0.2rem 0.4rem !important;
    }

    .card-header.bg-light .input-group-text {
        padding: 0.2rem 0.4rem !important;
        font-size: 0.8rem !important;
    }

    .card-header.bg-light #searchInput,
    .card-header.bg-light #searchInputMyCalls {
        font-size: 0.8rem !important;
        padding: 0.2rem 0.4rem !important;
    }
}

/* Desktop optimization for 1366x768 and similar resolutions */
@media (min-width: 1200px) and (max-width: 1400px) {
    .col-xl-3 {
        flex: 0 0 auto;
        width: 25%;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .col-lg-6 {
        flex: 0 0 auto;
        width: 50%;
    }
}

/* Tablet landscape optimization */
@media (min-width: 768px) and (max-width: 991px) {
    .col-md-6 {
        flex: 0 0 auto;
        width: 50%;
    }
}

/* Focus states for embedded form elements */
.card-header.bg-light .form-control:focus {
    border-color: #80bdff !important;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15) !important;
}

.card-header.bg-light .form-select:focus {
    border-color: #80bdff !important;
    box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15) !important;
}

.card-header.bg-light .input-group .form-control:focus + .btn-outline-secondary {
    border-color: #80bdff !important;
}
</style>
@endsection