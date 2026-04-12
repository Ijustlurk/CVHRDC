@extends('layouts.Coordinator.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0 fw-bold">Peer Review Management</h4>
        <div class="alert alert-info mb-0 py-2 px-3 border-0 shadow-sm d-none d-md-block">
            <i class="fas fa-tasks me-2"></i>Assign reviewers and monitor evaluation progress.
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Peer Review Management Tabs -->
    <ul class="nav nav-tabs mb-4 border-bottom-0 shadow-sm p-1 bg-white rounded-3" style="width: fit-content;">
        <li class="nav-item">
            <a class="nav-link{{ $type === 'proposal' ? ' active fw-bold text-primary shadow-sm bg-light' : ' text-muted' }} rounded-3" 
               href="{{ route('coordinator.submissions.index', ['type' => 'proposal']) }}">
                <i class="fas fa-file-signature me-2"></i>Project Proposal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $type === 'conference' ? ' active fw-bold text-primary shadow-sm bg-light' : ' text-muted' }} rounded-3" 
               href="{{ route('coordinator.submissions.index', ['type' => 'conference']) }}">
                <i class="fas fa-file-alt me-2"></i>Conference Paper
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $type === 'preprint' ? ' active fw-bold text-primary shadow-sm bg-light' : ' text-muted' }} rounded-3" 
               href="{{ route('coordinator.submissions.index', ['type' => 'preprint']) }}">
                <i class="fas fa-file-lines me-2"></i>Preprint
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $type === 'book' ? ' active fw-bold text-primary shadow-sm bg-light' : ' text-muted' }} rounded-3" 
               href="{{ route('coordinator.submissions.index', ['type' => 'book']) }}">
                <i class="fas fa-book-open me-2"></i>Book
            </a>
        </li>
    </ul>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header py-3 border-0" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list-ul me-2"></i>{{ ucfirst($type) }} Submissions
                </h6>
                <div class="badge bg-white text-primary px-3 py-2 rounded-3 shadow-sm border">
                    Total: {{ $totalCount }}
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <!-- Search and Filter Bar -->
            <div class="bg-light border-bottom p-4">
                <form id="searchFilterForm" method="GET" action="{{ route('coordinator.submissions.index') }}" class="mb-0">
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="row g-3">
                        <!-- Search Input -->
                        <div class="col-lg-12">
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="searchInput" name="search"
                                       placeholder="Search by title, proponent name, affiliation, or call title..."
                                       value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary bg-white border-start-0 text-muted" id="clearSearch" title="Clear search">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Status Filter Toggle -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Submission Status</label>
                            <select class="form-select shadow-sm" id="statusFilter" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Statuses</option>
                                <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Timeframe</label>
                            <select class="form-select shadow-sm" id="dateFilter" name="date_filter">
                                <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Sort Order</label>
                            <select class="form-select shadow-sm" id="sortBy" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest (Submitted)</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest (Submitted)</option>
                                <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>Title (A-Z)</option>
                                <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Title (Z-A)</option>
                            </select>
                        </div>

                        <!-- Reset Button -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary w-100 shadow-sm" id="resetFiltersBtn">
                                <i class="fas fa-undo-alt me-2"></i>Reset Filters
                            </button>
                        </div>
                    </div>

                    <!-- Custom Date Range Inputs -->
                    <div class="row mt-3 g-2 animate__animated animate__fadeIn" id="customDateRange" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                        <div class="col-6">
                            <label for="dateFrom" class="form-label small text-muted mb-1">From Date</label>
                            <input type="date" class="form-control shadow-sm" id="dateFrom" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-6">
                            <label for="dateTo" class="form-label small text-muted mb-1">To Date</label>
                            <input type="date" class="form-control shadow-sm" id="dateTo" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <!-- Results Indicator -->
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <div id="resultsCounter" class="text-muted small">
                            Showing <span class="fw-bold" id="counterRange">{{ $submissions->firstItem() ?? 0 }} - {{ $submissions->lastItem() ?? 0 }}</span> 
                            of <span class="fw-bold" id="counterTotal">{{ $totalCount }}</span> results
                        </div>
                        <div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div id="submissionsTableContainer">
                @include('coordinator.submissions.partials.submissions-table', ['submissions' => $submissions, 'type' => $type])
            </div>
        </div>
    </div>
</div>

@include('coordinator.submissions.partials.assign-modal')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initFilters();
});

let searchTimeout;

function initFilters() {
    const form = document.getElementById('searchFilterForm');
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const sortBy = document.getElementById('sortBy');
    const customDateRange = document.getElementById('customDateRange');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const resetBtn = document.getElementById('resetFiltersBtn');

    // Search input handler
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Clear search handler
    clearSearch.addEventListener('click', function() {
        if (searchInput.value) {
            searchInput.value = '';
            performSearch();
        }
    });

    // Dropdown filters handler
    [statusFilter, dateFilter, sortBy].forEach(el => {
        el.addEventListener('change', function() {
            if (this.id === 'dateFilter') {
                customDateRange.style.display = (this.value === 'custom') ? 'flex' : 'none';
                if (this.value !== 'custom') performSearch();
            } else {
                performSearch();
            }
        });
    });

    // Custom date inputs handler
    [dateFrom, dateTo].forEach(el => {
        el.addEventListener('change', performSearch);
    });

    // Reset handler
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        dateFilter.value = '';
        sortBy.value = 'newest';
        dateFrom.value = '';
        dateTo.value = '';
        customDateRange.style.display = 'none';
        performSearch();
    });

    function performSearch() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const container = document.getElementById('submissionsTableContainer');
        const rangeText = document.getElementById('counterRange');
        const totalText = document.getElementById('counterTotal');
        
        loadingIndicator.style.display = 'inline-block';
        
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        // Remove empty values
        const cleanParams = new URLSearchParams();
        params.forEach((value, key) => {
            if (value && value !== '') cleanParams.append(key, value);
        });

        const url = `${form.action}?${cleanParams.toString()}`;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            container.innerHTML = data.html;
            rangeText.textContent = `${data.from} - ${data.to}`;
            totalText.textContent = data.totalCount;
            
            // Update URL bar without reload
            window.history.pushState({}, '', url);
            
            loadingIndicator.style.display = 'none';
        })
        .catch(error => {
            console.error('Error fetching filtered results:', error);
            loadingIndicator.style.display = 'none';
        });
    }

    // Pagination link handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('#submissionsPagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            const loadingIndicator = document.getElementById('loadingIndicator');
            loadingIndicator.style.display = 'inline-block';
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('submissionsTableContainer').innerHTML = data.html;
                document.getElementById('counterRange').textContent = `${data.from} - ${data.to}`;
                document.getElementById('counterTotal').textContent = data.totalCount;
                window.history.pushState({}, '', url);
                loadingIndicator.style.display = 'none';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    });
}
</script>
@endpush

@push('styles')
<style>
    .nav-tabs .nav-link {
        border: none;
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
        background: transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .nav-tabs .nav-link:hover {
        background: rgba(13, 110, 253, 0.05);
        color: #0d6efd;
    }
    
    .nav-tabs .nav-link.active {
        background: #f8f9fa !important;
        border-bottom: 2px solid #0d6efd !important;
    }

    .form-select, .form-control {
        border-color: #e9ecef;
        padding: 0.6rem 0.8rem;
    }

    .form-select:focus, .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    .bg-gradient {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
    }

    .rounded-4 {
        border-radius: 1rem !important;
    }

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
</style>
@endpush
