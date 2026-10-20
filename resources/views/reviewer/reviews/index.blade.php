@extends('layouts.Reviewer.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0 fw-bold">My Reviews</h4>
        <div class="alert alert-info mb-0 py-2 px-3 border-0 shadow-sm d-none d-md-block">
            <i class="fas fa-clipboard-check me-2"></i>Manage your review invitations and active assignments.
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

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs mb-4 border-bottom-0 shadow-sm p-1 bg-white rounded-3" style="width: fit-content;">
        <li class="nav-item">
            <a class="nav-link{{ $tab === 'invitations' ? ' active fw-bold text-primary shadow-sm bg-light' : ' text-muted' }} rounded-3"
               href="{{ route('reviewer.reviews.index', ['tab' => 'invitations']) }}">
                <i class="fas fa-envelope-open-text me-2"></i>Review Invitations
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab === 'assignments' ? ' active fw-bold text-primary shadow-sm bg-light' : ' text-muted' }} rounded-3"
               href="{{ route('reviewer.reviews.index', ['tab' => 'assignments']) }}">
                <i class="fas fa-tasks me-2"></i>My Assignments
            </a>
        </li>
    </ul>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header py-3 border-0" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-{{ $tab === 'invitations' ? 'envelope-open-text' : 'tasks' }} me-2"></i>
                    {{ $tab === 'invitations' ? 'Pending Review Invitations' : 'Active & Completed Assignments' }}
                </h6>
                <div class="badge bg-white text-primary px-3 py-2 rounded-3 shadow-sm border">
                    Total: {{ $totalCount }}
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            {{-- Search & Filter Bar --}}
            <div class="bg-light border-bottom p-4">
                <form id="reviewsFilterForm" method="GET" action="{{ route('reviewer.reviews.index') }}" class="mb-0">
                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <div class="row g-3">
                        {{-- Search --}}
                        <div class="col-12">
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="searchInput" name="search"
                                       placeholder="Search by research title or proponent name..."
                                       value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-secondary bg-white border-start-0 text-muted" id="clearSearch" title="Clear Search">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>

                        @if($tab === 'assignments')
                        {{-- Status Filter (only for assignments tab) --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Review Status</label>
                            <select class="form-select shadow-sm" id="statusFilter" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Statuses</option>
                                <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        @endif

                        {{-- Date / Timeframe --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Assigned Timeframe</label>
                            <select class="form-select shadow-sm" id="dateFilter" name="date_filter">
                                <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        {{-- Sort --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <label class="form-label small fw-bold text-muted mb-1 text-uppercase">Sort Order</label>
                            <select class="form-select shadow-sm" id="sortBy" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest Assigned</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Assigned</option>
                                <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>Title (A–Z)</option>
                                <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Title (Z–A)</option>
                            </select>
                        </div>

                        {{-- Reset --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary w-100 shadow-sm" id="resetFiltersBtn">
                                <i class="fas fa-undo-alt me-2"></i>Reset Filters
                            </button>
                        </div>
                    </div>

                    {{-- Custom Date Range --}}
                    <div class="row mt-3 g-2" id="customDateRange" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                        <div class="col-6">
                            <label for="dateFrom" class="form-label small text-muted mb-1">From Date</label>
                            <input type="date" class="form-control shadow-sm" id="dateFrom" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-6">
                            <label for="dateTo" class="form-label small text-muted mb-1">To Date</label>
                            <input type="date" class="form-control shadow-sm" id="dateTo" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    {{-- Results Indicator --}}
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing <span class="fw-bold" id="counterRange">{{ $reviews->firstItem() ?? 0 }} - {{ $reviews->lastItem() ?? 0 }}</span>
                            of <span class="fw-bold" id="counterTotal">{{ $totalCount }}</span> results
                        </div>
                        <div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div id="reviewsTableContainer">
                @include('reviewer.reviews.partials.reviews-table', ['reviews' => $reviews, 'tab' => $tab])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reviewsFilterForm');
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const sortBy = document.getElementById('sortBy');
    const customDateRange = document.getElementById('customDateRange');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const resetBtn = document.getElementById('resetFiltersBtn');
    const container = document.getElementById('reviewsTableContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const counterRange = document.getElementById('counterRange');
    const counterTotal = document.getElementById('counterTotal');

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    clearSearch.addEventListener('click', function() {
        if (searchInput.value) {
            searchInput.value = '';
            performSearch();
        }
    });

    const selects = [statusFilter, dateFilter, sortBy].filter(Boolean);
    selects.forEach(el => {
        el.addEventListener('change', function() {
            if (this.id === 'dateFilter') {
                customDateRange.style.display = (this.value === 'custom') ? 'flex' : 'none';
                if (this.value !== 'custom') performSearch();
            } else {
                performSearch();
            }
        });
    });

    [dateFrom, dateTo].filter(Boolean).forEach(el => el.addEventListener('change', performSearch));

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        dateFilter.value = '';
        sortBy.value = 'newest';
        dateFrom.value = '';
        dateTo.value = '';
        customDateRange.style.display = 'none';
        performSearch();
    });

    function performSearch() {
        loadingIndicator.style.display = 'inline-block';

        const formData = new FormData(form);
        const params = new URLSearchParams();
        formData.forEach((value, key) => { if (value && value !== '') params.append(key, value); });

        const url = `${form.action}?${params.toString()}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                container.innerHTML = data.html;
                counterRange.textContent = `${data.from} - ${data.to}`;
                counterTotal.textContent = data.totalCount;
                window.history.pushState({}, '', url);
            })
            .catch(err => console.error('Filter error:', err))
            .finally(() => loadingIndicator.style.display = 'none');
    }

    // Pagination
    document.addEventListener('click', function(e) {
        if (e.target.closest('#reviewsPagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            loadingIndicator.style.display = 'inline-block';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    container.innerHTML = data.html;
                    counterRange.textContent = `${data.from} - ${data.to}`;
                    counterTotal.textContent = data.totalCount;
                    window.history.pushState({}, '', url);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                })
                .finally(() => loadingIndicator.style.display = 'none');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }

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

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
</style>
@endpush
