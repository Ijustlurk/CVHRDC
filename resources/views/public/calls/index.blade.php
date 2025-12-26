@php $maintenance = \App\Models\Maintenance::first(); $header = \App\Models\Header::first(); $footer = \App\Models\Footer::first(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $header->title ?? 'CVHRDC' }} - Calls for Proposals</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />

    <style>
        .body {
            font-family: "Nunito";
        }

        .text {
            color: #112245;
        }

        .font {
            font-family: "Outfit", sans-serif;
        }

        .text-2 {
            color: #175ddc;
        }

        .hero-section {
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            border-radius: 0 0 40px 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            position: relative;
            z-index: 1;
        }

        .text-gradient {
            background: linear-gradient(90deg, #666af2 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .card-hover:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            transition: 0.3s;
        }

        .call-card {
            transition: background-color 0.3s ease;
        }

        .call-card:hover {
            background-color: #cbe7ff;
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 4px 12px;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-light body">
    @if($maintenance && $maintenance->active)
    <div class="alert alert-warning text-center mb-0 rounded-0 animate__animated animate__fadeInDown">
        <strong>Maintenance Mode:</strong>
        {{ $maintenance->message ?? 'The site is under maintenance.' }}
    </div>
    @endif

    <!-- NAVIGATION -->
    <div class="container fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow rounded-pill py-2 px-4 mb-4 mt-3 animate__animated animate__fadeInDown">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-2" href="{{ route('welcome') }}">
                    @if($header && $header->logo)
                    <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo" height="44" class="me-2 rounded-circle shadow-sm" style="background: #f3f6fa" />
                    @endif
                    {{ $header->title ?? 'CVHRDC' }}
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-2 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold px-3 rounded-pill transition-nav" href="{{ route('welcome') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold px-3 rounded-pill transition-nav active text-primary" href="{{ route('public.calls.index') }}">Calls for Proposals</a>
                        </li>
                        @if (Route::has('login')) @auth
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Dashboard</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Log in</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Register</a>
                        </li>
                        @endif @endauth @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- CALLS SECTION -->
    <section class="py-5 mt-5">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h1 class="section-title font text">
                        <i class="fas fa-bullhorn me-3 text-primary"></i>Calls for Proposals
                    </h1>
                    <p class="lead text-muted">
                        Explore research opportunities and funding calls currently available for researchers and institutions.
                    </p>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card call-card shadow-sm">
                        <div class="card-header bg-light border-bottom py-3" style="background-color: #f8f9fa !important;">
                            <form id="searchFilterForm" method="GET" action="{{ route('public.calls.index') }}" class="mb-0">
                                <!-- Search Bar -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="searchInput" class="form-label small fw-semibold text-muted mb-2">Search Calls</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            <input type="text" class="form-control" id="searchInput" name="search"
                                                   placeholder="Search by title, research priority area, description, or eligibility..."
                                                   value="{{ request('search') }}">
                                            <button type="button" class="btn btn-outline-secondary" id="clearSearch" title="Clear search">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filters Row -->
                                <div class="row g-3 mb-3">
                                    <!-- Status Filter -->
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <label for="statusFilter" class="form-label small fw-semibold text-muted mb-1">Filter by Status</label>
                                        <select class="form-select form-select-sm" id="statusFilter" name="status">
                                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        </select>
                                    </div>

                                    <!-- Date Filter -->
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <label for="dateFilter" class="form-label small fw-semibold text-muted mb-1">Filter by Date</label>
                                        <select class="form-select form-select-sm" id="dateFilter" name="date_filter">
                                            <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                            <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                            <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                            <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                        </select>
                                    </div>

                                    <!-- Sort By -->
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <label for="sortBy" class="form-label small fw-semibold text-muted mb-1">Sort By</label>
                                        <select class="form-select form-select-sm" id="sortBy" name="sort">
                                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                            <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                            <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                            <option value="funding_high" {{ request('sort') == 'funding_high' ? 'selected' : '' }}>Funding High</option>
                                            <option value="funding_low" {{ request('sort') == 'funding_low' ? 'selected' : '' }}>Funding Low</option>
                                            <option value="deadline_soon" {{ request('sort') == 'deadline_soon' ? 'selected' : '' }}>Deadline Soon</option>
                                        </select>
                                    </div>

                                    <!-- Apply/Reset Actions -->
                                    <div class="col-xl-3 col-lg-12 col-md-6 col-12 d-flex align-items-end">
                                        <div class="d-flex gap-2 w-100">
                                            <button type="button" class="btn btn-primary btn-sm flex-fill" id="applyFiltersBtn">
                                                <i class="fas fa-filter me-1"></i>Apply
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" id="resetFiltersBtn">
                                                <i class="fas fa-redo me-1"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Date Range (shown when Custom Range is selected) -->
                                <div class="row mb-3 g-3" id="customDateRange" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                                    <div class="col-md-6 col-12">
                                        <label for="dateFrom" class="form-label small fw-semibold text-muted mb-1">From Date</label>
                                        <input type="date" class="form-control form-control-sm" id="dateFrom" name="date_from" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="dateTo" class="form-label small fw-semibold text-muted mb-1">To Date</label>
                                        <input type="date" class="form-control form-control-sm" id="dateTo" name="date_to" value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <!-- Results Counter -->
                                <div class="row align-items-center">
                                    <div class="col-12 text-end">
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
                    </div>
                </div>
            </div>

            <!-- Calls List Container -->
            <div id="callsContainer">
                @include('public.calls.partials.calls-list', ['calls' => $calls])
            </div>
        </div>
    </section>

    @include('layouts.Admin.footer', ['footer' => $footer])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Search and Filter Functionality
    let searchTimeout;

    function initializeFilters() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
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
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function() {
                performSearch();
            });
        }

        // Reset All button
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function() {
                // Clear all form inputs
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                if (dateFilter) dateFilter.value = '';
                if (sortBy) sortBy.value = 'newest';
                if (dateFrom) dateFrom.value = '';
                if (dateTo) dateTo.value = '';

                // Hide custom date range
                if (customDateRange) {
                    customDateRange.style.display = 'none';
                }

                // Perform search with cleared filters
                performSearch();
            });
        }

        // Filter and sort change handlers
        [statusFilter, dateFilter, sortBy].forEach(element => {
            if (element) {
                element.addEventListener('change', performSearch);
            }
        });

        // Custom date range inputs
        [dateFrom, dateTo].forEach(element => {
            if (element) {
                element.addEventListener('change', function() {
                    if (dateFilter && dateFilter.value === 'custom') {
                        performSearch();
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

        const searchUrl = `{{ route('public.calls.index') }}?${params.toString()}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update calls container
            const callsContainer = document.getElementById('callsContainer');
            if (callsContainer && data.html) {
                callsContainer.innerHTML = data.html;
            }

            // Update counter
            if (counterText) {
                const from = data.from || 0;
                const to = data.to || 0;
                const total = data.totalCount || 0;
                counterText.textContent = `${from} - ${to} of ${total}`;
            }

            // Update browser URL
            const newUrl = params.toString() ? searchUrl : '{{ route('public.calls.index') }}';
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
        if (e.target.closest('#callsPagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;

            // Show loading indicator
            const loadingIndicator = document.getElementById('loadingIndicator');
            const counterText = document.getElementById('counterText');
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
                // Update calls container
                const callsContainer = document.getElementById('callsContainer');
                if (callsContainer && data.html) {
                    callsContainer.innerHTML = data.html;
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

                // Scroll to top of results
                const callsSection = document.querySelector('#callsContainer');
                if (callsSection) {
                    callsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Pagination error:', error);
                if (loadingIndicator) loadingIndicator.style.display = 'none';
            });
        }
    });

    // Initialize filters when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
    });
    </script>

    <!-- Real-time updates for calls -->
    <script>
        let lastUpdateTimestamp = '{{ now()->toISOString() }}';

        function checkForUpdates() {
            fetch('{{ route("api.public-calls") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Check if data has changed
                if (data.timestamp !== lastUpdateTimestamp || data.count !== {{ $calls->total() ?? $calls->count() }}) {
                    // Update the page title and show notification
                    lastUpdateTimestamp = data.timestamp;
                    showUpdateNotification();

                    // Reload the page to get fresh data
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.log('Error checking for updates:', error);
            });
        }

        function showUpdateNotification() {
            // Create a subtle notification
            let notification = document.createElement('div');
            notification.className = 'position-fixed top-0 end-0 m-3 alert alert-info alert-dismissible fade show';
            notification.style.zIndex = '9999';
            notification.innerHTML = `
                <i class="fas fa-sync-alt me-2"></i>
                New calls for proposals available! Page will refresh shortly.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Check for updates every 5 minutes (300,000 milliseconds)
        setInterval(checkForUpdates, 300000);

        // Also check when the page becomes visible (in case user switched tabs)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                checkForUpdates();
            }
        });
    </script>
</body>

</html>
