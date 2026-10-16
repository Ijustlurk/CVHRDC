@extends('layouts.RUC.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">Review Calls for Proposals</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Review and manage the visibility of calls for proposals created by RMC.
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

    <!-- Review Calls Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link{{ request('tab', 'all-calls') === 'all-calls' ? ' active' : '' }}"
               href="javascript:void(0)" onclick="switchToAllCalls()">
                <i class="fas fa-list me-2"></i>All Calls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'for-review' ? ' active' : '' }}"
               href="javascript:void(0)" onclick="switchToForReview()">
                <i class="fas fa-search me-2"></i>For Review
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ request('tab') === 'published' ? ' active' : '' }}"
               href="javascript:void(0)" onclick="switchToPublished()">
                <i class="fas fa-eye me-2"></i>Published
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
                    <form id="searchFilterFormAllCalls" method="GET" action="{{ route('ruc.calls.index') }}" class="mb-0">
                        <input type="hidden" name="tab" value="all-calls">

                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputAllCalls" name="search"
                                           placeholder="Search by title, research priority area, or description..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchAllCalls" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Status Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="statusFilterAllCalls" name="status">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="For Review" {{ request('status') == 'For Review' ? 'selected' : '' }}>For Review</option>
                                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Visibility Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilterAllCalls" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterAllCalls" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByAllCalls" name="sort">
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
                        <div class="row mb-2 g-2" id="customDateRangeAllCalls" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromAllCalls" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromAllCalls" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToAllCalls" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToAllCalls" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm" id="applyFiltersBtnAllCalls">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnAllCalls">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterAllCalls" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextAllCalls">
                                        {{ $calls->firstItem() ?? 0 }} - {{ $calls->lastItem() ?? 0 }} of {{ $calls->total() }}
                                    </span>
                                    <span id="loadingIndicatorAllCalls" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body" id="allCallsTable">
                    @include('ruc.calls.partials.calls-table', ['calls' => $calls])
                </div>
            </div>
        </div>

        <!-- For Review Tab -->
        <div class="tab-pane fade{{ request('tab') === 'for-review' ? ' show active' : '' }}" id="for-review">
            <div class="card shadow">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-search me-2"></i>Calls Pending Review</h6>
                    </div>
                </div>

                <!-- Search and Filter Section (embedded in tab) -->
                <div class="card-header bg-light border-bottom py-2" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterFormForReview" method="GET" action="{{ route('ruc.calls.index') }}" class="mb-0">
                        <input type="hidden" name="tab" value="for-review">

                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputForReview" name="search"
                                           placeholder="Search by title, research priority area, or description..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchForReview" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Status Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="statusFilterForReview" name="status">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="For Review" {{ request('status') == 'For Review' ? 'selected' : '' }}>For Review</option>
                                </select>
                            </div>

                            <!-- Visibility Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="visibilityFilterForReview" name="visibility">
                                    <option value="" {{ request('visibility') == '' ? 'selected' : '' }}>All Visibility</option>
                                    <option value="Private" {{ request('visibility') == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Public" {{ request('visibility') == 'Public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterForReview" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByForReview" name="sort">
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
                        <div class="row mb-2 g-2" id="customDateRangeForReview" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromForReview" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromForReview" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToForReview" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToForReview" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-info btn-sm" id="applyFiltersBtnForReview">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnForReview">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterForReview" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextForReview">
                                        {{ $calls->firstItem() ?? 0 }} - {{ $calls->lastItem() ?? 0 }} of {{ $calls->total() }}
                                    </span>
                                    <span id="loadingIndicatorForReview" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body" id="forReviewTable">
                    @include('ruc.calls.partials.calls-table', ['calls' => $calls])
                </div>
            </div>
        </div>

        <!-- Published Tab -->
        <div class="tab-pane fade{{ request('tab') === 'published' ? ' show active' : '' }}" id="published">
            <div class="card shadow">
                <div class="card-header bg-success bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-eye me-2"></i>Published Calls</h6>
                    </div>
                </div>

                <!-- Search and Filter Section (embedded in tab) -->
                <div class="card-header bg-light border-bottom py-2" style="background-color: #f8f9fa !important;">
                    <form id="searchFilterFormPublished" method="GET" action="{{ route('ruc.calls.index') }}" class="mb-0">
                        <input type="hidden" name="tab" value="published">

                        <!-- Search Bar -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInputPublished" name="search"
                                           placeholder="Search by title, research priority area, or description..."
                                           value="{{ request('search') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearchPublished" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Row -->
                        <div class="row g-2 mb-2">
                            <!-- Status Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="statusFilterPublished" name="status">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="dateFilterPublished" name="date_filter">
                                    <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                                    <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="last_6_months" {{ request('date_filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                    <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <select class="form-select form-select-sm" id="sortByPublished" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="a_z" {{ request('sort') == 'a_z' ? 'selected' : '' }}>A-Z</option>
                                    <option value="z_a" {{ request('sort') == 'z_a' ? 'selected' : '' }}>Z-A</option>
                                    <option value="funding_high" {{ request('sort') == 'funding_high' ? 'selected' : '' }}>Funding High</option>
                                    <option value="funding_low" {{ request('sort') == 'funding_low' ? 'selected' : '' }}>Funding Low</option>
                                </select>
                            </div>

                            <!-- Empty column for spacing -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6"></div>
                        </div>

                        <!-- Custom Date Range (shown when Custom Range is selected) -->
                        <div class="row mb-2 g-2" id="customDateRangePublished" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                            <div class="col-6">
                                <label for="dateFromPublished" class="form-label small text-muted mb-1">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateFromPublished" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-6">
                                <label for="dateToPublished" class="form-label small text-muted mb-1">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="dateToPublished" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Action Buttons and Results Counter -->
                        <div class="row align-items-center pt-1">
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-success btn-sm" id="applyFiltersBtnPublished">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFiltersBtnPublished">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 text-md-end">
                                <div id="resultsCounterPublished" class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="counterTextPublished">
                                        {{ $calls->firstItem() ?? 0 }} - {{ $calls->lastItem() ?? 0 }} of {{ $calls->total() }}
                                    </span>
                                    <span id="loadingIndicatorPublished" class="ms-2" style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body" id="publishedTable">
                    @include('ruc.calls.partials.calls-table', ['calls' => $calls])
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// SweetAlert confirmation for approving a call
function confirmApproveCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Approve & Publish Call?',
        html: `<p class="mb-0">Are you sure you want to approve and publish "<strong>${callTitle}</strong>"? This will make it visible to the public.</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Yes, Approve & Publish',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-success btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Approving Call...',
                html: '<p class="mb-0">Please wait while we approve and publish the call.</p>',
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

// SweetAlert confirmation for withdrawing a call
function confirmWithdrawCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Withdraw & Close Call?',
        html: `<p class="mb-0">Are you sure you want to withdraw "<strong>${callTitle}</strong>"? This will close the call but maintain its public visibility, signaling that the call has ended.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-times-circle me-2"></i>Yes, Withdraw & Close',
        cancelButtonText: '<i class="fas fa-ban me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-warning btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Withdrawing Call...',
                html: '<p class="mb-0">Please wait while we withdraw and close the call.</p>',
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

// SweetAlert confirmation for reopening a call for review
function confirmReopenCall(event, callTitle) {
    event.preventDefault();

    Swal.fire({
        title: 'Reopen Call for Review?',
        html: `<p class="mb-0">Are you sure you want to reopen "<strong>${callTitle}</strong>" for review? This will set the status back to "For Review" and visibility to "Private."</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-redo me-2"></i>Yes, Reopen for Review',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-info btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Reopening Call...',
                html: '<p class="mb-0">Please wait while we reopen the call for review.</p>',
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

// SweetAlert confirmation for toggling visibility
function confirmToggleVisibility(event, callTitle, newVisibility) {
    event.preventDefault();

    let actionText = newVisibility === 'Public' ? 'make public' : 'make private';
    let iconType = newVisibility === 'Public' ? 'info' : 'warning';
    
    Swal.fire({
        title: 'Change Visibility?',
        html: `<p class="mb-0">Are you sure you want to ${actionText} "<strong>${callTitle}</strong>"?</p>`,
        icon: iconType,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check me-2"></i>Yes, Change it',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-primary btn-lg px-4 me-2',
            cancelButton: 'btn btn-secondary btn-lg px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Updating Visibility...',
                html: '<p class="mb-0">Please wait.</p>',
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

            event.target.submit();
        }
    });

    return false;
}
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

/* Info Button Styling */
.swal2-popup .btn-info {
    background-color: #17a2b8 !important;
    border: 2px solid #17a2b8 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(23, 162, 184, 0.3) !important;
}

.swal2-popup .btn-info:hover {
    background-color: #138496 !important;
    border-color: #117a8b !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(23, 162, 184, 0.4) !important;
}

.swal2-popup .btn-info:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.5) !important;
}

/* Warning Button Styling */
.swal2-popup .btn-warning {
    background-color: #ffc107 !important;
    border: 2px solid #ffc107 !important;
    color: #212529 !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(255, 193, 7, 0.3) !important;
}

.swal2-popup .btn-warning:hover {
    background-color: #e0a800 !important;
    border-color: #d39e00 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(255, 193, 7, 0.4) !important;
}

.swal2-popup .btn-warning:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.5) !important;
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

/* Outline Secondary Button Styling */
.swal2-popup .btn-outline-secondary {
    background-color: transparent !important;
    border: 2px solid #6c757d !important;
    color: #6c757d !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(108, 117, 125, 0.2) !important;
}

.swal2-popup .btn-outline-secondary:hover {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3) !important;
}

.swal2-popup .btn-outline-secondary:focus {
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

.card-header.bg-light #searchInputAllCalls,
.card-header.bg-light #searchInputForReview,
.card-header.bg-light #searchInputPublished {
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

    .card-header.bg-light #searchInputAllCalls,
    .card-header.bg-light #searchInputForReview,
    .card-header.bg-light #searchInputPublished {
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

/* Tab Navigation Enhancement */
.nav-tabs {
    border-bottom: 2px solid #dee2e6 !important;
}

.nav-tabs .nav-link {
    border: none !important;
    border-bottom: 2px solid transparent !important;
    background: none !important;
    color: #6c757d !important;
    font-weight: 500 !important;
    padding: 0.75rem 1rem !important;
    transition: all 0.3s ease !important;
}

.nav-tabs .nav-link.active {
    color: #007bff !important;
    background: none !important;
    border: none !important;
    border-bottom: 2px solid #007bff !important;
}

.nav-tabs .nav-link:hover {
    color: #0056b3 !important;
    background: rgba(0, 123, 255, 0.1) !important;
    border: none !important;
    border-bottom: 2px solid #0056b3 !important;
}

/* Card Header Styling for different tabs */
.card-header.bg-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.card-header.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
}

/* Loading Animation */
.spinner-border-sm {
    animation: spinner-border 0.75s linear infinite !important;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

/* Results Counter Styling */
.text-muted small {
    color: #6c757d !important;
}

.text-muted small i {
    color: #adb5bd !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filter functionality for all tabs
    initializeAllCallsFilters();
    initializeForReviewFilters();
    initializePublishedFilters();
});

// All Calls Tab Search and Filter Functionality
let allCallsSearchTimeout;

function initializeAllCallsFilters() {
    const searchInput = document.getElementById('searchInputAllCalls');
    const statusFilter = document.getElementById('statusFilterAllCalls');
    const visibilityFilter = document.getElementById('visibilityFilterAllCalls');
    const dateFilter = document.getElementById('dateFilterAllCalls');
    const sortBy = document.getElementById('sortByAllCalls');
    const clearSearchBtn = document.getElementById('clearSearchAllCalls');
    const customDateRange = document.getElementById('customDateRangeAllCalls');
    const dateFrom = document.getElementById('dateFromAllCalls');
    const dateTo = document.getElementById('dateToAllCalls');
    const searchFilterForm = document.getElementById('searchFilterFormAllCalls');
    const applyFiltersBtn = document.getElementById('applyFiltersBtnAllCalls');
    const resetFiltersBtn = document.getElementById('resetFiltersBtnAllCalls');

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

// For Review Tab Search and Filter Functionality
let forReviewSearchTimeout;

function initializeForReviewFilters() {
    const searchInput = document.getElementById('searchInputForReview');
    const statusFilter = document.getElementById('statusFilterForReview');
    const visibilityFilter = document.getElementById('visibilityFilterForReview');
    const dateFilter = document.getElementById('dateFilterForReview');
    const sortBy = document.getElementById('sortByForReview');
    const clearSearchBtn = document.getElementById('clearSearchForReview');
    const customDateRange = document.getElementById('customDateRangeForReview');
    const dateFrom = document.getElementById('dateFromForReview');
    const dateTo = document.getElementById('dateToForReview');
    const searchFilterForm = document.getElementById('searchFilterFormForReview');
    const applyFiltersBtn = document.getElementById('applyFiltersBtnForReview');
    const resetFiltersBtn = document.getElementById('resetFiltersBtnForReview');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performForReviewSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(forReviewSearchTimeout);
            forReviewSearchTimeout = setTimeout(() => {
                performForReviewSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performForReviewSearch();
        });
    }

    // Apply Filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performForReviewSearch();
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
            performForReviewSearch();
        });
    }

    // Filter and sort change handlers
    [statusFilter, visibilityFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performForReviewSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performForReviewSearch();
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

// Published Tab Search and Filter Functionality
let publishedSearchTimeout;

function initializePublishedFilters() {
    const searchInput = document.getElementById('searchInputPublished');
    const statusFilter = document.getElementById('statusFilterPublished');
    const dateFilter = document.getElementById('dateFilterPublished');
    const sortBy = document.getElementById('sortByPublished');
    const clearSearchBtn = document.getElementById('clearSearchPublished');
    const customDateRange = document.getElementById('customDateRangePublished');
    const dateFrom = document.getElementById('dateFromPublished');
    const dateTo = document.getElementById('dateToPublished');
    const searchFilterForm = document.getElementById('searchFilterFormPublished');
    const applyFiltersBtn = document.getElementById('applyFiltersBtnPublished');
    const resetFiltersBtn = document.getElementById('resetFiltersBtnPublished');

    // Prevent form submission and use AJAX instead
    if (searchFilterForm) {
        searchFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performPublishedSearch();
        });
    }

    // Real-time search as user types
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(publishedSearchTimeout);
            publishedSearchTimeout = setTimeout(() => {
                performPublishedSearch();
            }, 500);
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            performPublishedSearch();
        });
    }

    // Apply Filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            performPublishedSearch();
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
            performPublishedSearch();
        });
    }

    // Filter and sort change handlers
    [statusFilter, dateFilter, sortBy].forEach(element => {
        if (element) {
            element.addEventListener('change', performPublishedSearch);
        }
    });

    // Custom date range inputs
    [dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                if (dateFilter && dateFilter.value === 'custom') {
                    performPublishedSearch();
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
    const formData = new FormData(document.getElementById('searchFilterFormAllCalls'));
    const params = new URLSearchParams(formData);

    // Remove empty parameters for cleaner URLs
    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    // Show loading indicator
    const loadingIndicator = document.getElementById('loadingIndicatorAllCalls');
    const counterText = document.getElementById('counterTextAllCalls');
    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

    const searchUrl = `{{ route('ruc.calls.index') }}?${params.toString()}`;

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
        const newUrl = params.toString() ? searchUrl : '{{ route('ruc.calls.index') }}';
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

// Perform AJAX search for For Review tab
function performForReviewSearch() {
    const formData = new FormData(document.getElementById('searchFilterFormForReview'));
    const params = new URLSearchParams(formData);

    // Remove empty parameters for cleaner URLs
    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    // Show loading indicator
    const loadingIndicator = document.getElementById('loadingIndicatorForReview');
    const counterText = document.getElementById('counterTextForReview');
    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

    const searchUrl = `{{ route('ruc.calls.index') }}?${params.toString()}`;

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
        const tableContainer = document.getElementById('forReviewTable');
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
        const newUrl = params.toString() ? searchUrl : '{{ route('ruc.calls.index') }}';
        window.history.pushState({}, '', newUrl);

        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('For Review search error:', error);
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

// Perform AJAX search for Published tab
function performPublishedSearch() {
    const formData = new FormData(document.getElementById('searchFilterFormPublished'));
    const params = new URLSearchParams(formData);

    // Remove empty parameters for cleaner URLs
    for (let [key, value] of Array.from(params.entries())) {
        if (!value || value.trim() === '') {
            params.delete(key);
        }
    }

    // Show loading indicator
    const loadingIndicator = document.getElementById('loadingIndicatorPublished');
    const counterText = document.getElementById('counterTextPublished');
    if (loadingIndicator) loadingIndicator.style.display = 'inline-block';

    const searchUrl = `{{ route('ruc.calls.index') }}?${params.toString()}`;

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
        const tableContainer = document.getElementById('publishedTable');
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
        const newUrl = params.toString() ? searchUrl : '{{ route('ruc.calls.index') }}';
        window.history.pushState({}, '', newUrl);

        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    })
    .catch(error => {
        console.error('Published search error:', error);
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
    // Get current search parameters from active tab
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

    // Update the All Calls form with current parameters if coming from other tabs
    if (currentParams && Object.keys(currentParams).length > 0) {
        updateAllCallsForm(currentParams);
    }
}

function switchToForReview() {
    // Get current search parameters from active tab
    const currentParams = getCurrentSearchParams();

    // Update active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector('[onclick="switchToForReview()"]').classList.add('active');

    // Update tab pane visibility
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    document.getElementById('for-review').classList.add('show', 'active');

    // Update URL without triggering page reload
    const newUrl = buildTabUrl('for-review', currentParams);
    window.history.pushState({}, '', newUrl);

    // Update the For Review form with current parameters if coming from other tabs
    if (currentParams && Object.keys(currentParams).length > 0) {
        updateForReviewForm(currentParams);
    }
}

function switchToPublished() {
    // Get current search parameters from active tab
    const currentParams = getCurrentSearchParams();

    // Update active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector('[onclick="switchToPublished()"]').classList.add('active');

    // Update tab pane visibility
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    document.getElementById('published').classList.add('show', 'active');

    // Update URL without triggering page reload
    const newUrl = buildTabUrl('published', currentParams);
    window.history.pushState({}, '', newUrl);

    // Update the Published form with current parameters if coming from other tabs
    if (currentParams && Object.keys(currentParams).length > 0) {
        updatePublishedForm(currentParams);
    }
}

function getCurrentSearchParams() {
    const activeTab = document.querySelector('.tab-pane.show.active').id;

    if (activeTab === 'all-calls') {
        const form = document.getElementById('searchFilterFormAllCalls');
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
    } else if (activeTab === 'for-review') {
        const form = document.getElementById('searchFilterFormForReview');
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
    } else if (activeTab === 'published') {
        const form = document.getElementById('searchFilterFormPublished');
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
    const baseUrl = '{{ route('ruc.calls.index') }}';
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
    const form = document.getElementById('searchFilterFormAllCalls');
    if (form && params) {
        Object.keys(params).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = params[key];
            }
        });

        // Update custom date range visibility
        const dateFilter = form.querySelector('[name="date_filter"]');
        const customDateRange = document.getElementById('customDateRangeAllCalls');
        if (dateFilter && customDateRange) {
            if (params.date_filter === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        }
    }
}

function updateForReviewForm(params) {
    const form = document.getElementById('searchFilterFormForReview');
    if (form && params) {
        Object.keys(params).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = params[key];
            }
        });

        // Update custom date range visibility
        const dateFilter = form.querySelector('[name="date_filter"]');
        const customDateRange = document.getElementById('customDateRangeForReview');
        if (dateFilter && customDateRange) {
            if (params.date_filter === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        }
    }
}

function updatePublishedForm(params) {
    const form = document.getElementById('searchFilterFormPublished');
    if (form && params) {
        Object.keys(params).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = params[key];
            }
        });

        // Update custom date range visibility
        const dateFilter = form.querySelector('[name="date_filter"]');
        const customDateRange = document.getElementById('customDateRangePublished');
        if (dateFilter && customDateRange) {
            if (params.date_filter === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        }
    }
}

// Handle pagination clicks with AJAX for all tabs
document.addEventListener('click', function(e) {
    if (e.target.closest('#allCallsTable #callsPagination a') ||
        e.target.closest('#forReviewTable #callsPagination a') ||
        e.target.closest('#publishedTable #callsPagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;

        // Determine which tab is active and show appropriate loading indicator
        const activeTab = document.querySelector('.tab-pane.show.active').id;
        const loadingIndicator = document.getElementById(`loadingIndicator${activeTab === 'all-calls' ? 'AllCalls' : (activeTab === 'for-review' ? 'ForReview' : 'Published')}`);
        const counterText = document.getElementById(`counterText${activeTab === 'all-calls' ? 'AllCalls' : (activeTab === 'for-review' ? 'ForReview' : 'Published')}`);

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
@endsection