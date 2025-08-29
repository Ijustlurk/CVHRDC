@can('user-access')
@extends('layouts.Users.app')
@section('content')

<div class="container-fluid py-4 px-5">
    <div class="row mb-4 align-items-center g-3">
        <div class="col-auto">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                alt="Profile Photo" class="rounded-circle" width="64" height="64">
        </div>
        <div class="col">
            <h3 id="greeting" class="mb-0"></h3>
            <div class="text-muted">Welcome back, {{ $user->name }}!</div>
        </div>
        <!-- Notification bell moved to navigation -->
    </div>

    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body row align-items-center g-3">
                    <div class="col-auto">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                            alt="Profile Photo" class="rounded-circle" width="56" height="56">
                    </div>
                    <div class="col">
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <div class="text-muted">{{ $user->title ?? 'Member' }} |
                            {{ $user->institution ?? $user->campus ?? '' }}</div>
                        <div class="text-muted">Expertise: {{ $user->expertise ?? 'N/A' }}</div>
                    </div>
                    <div class="col-auto ms-auto">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Upload CV</a>
                        <!--a href="#" class="btn btn-outline-secondary btn-sm">Upload Publications</a-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">Recent Activity</div>
                <div class="card-body">
                    <div id="activityFeed">
                        <div class="text-muted text-center py-3" id="activityLoading">Loading…</div>
                        <ul class="list-group d-none" id="activityList"></ul>
                        <div class="text-muted d-none" id="activityEmpty">No recent activity</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Research notification -->
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
                        title: 'Research Uploaded'
                    });
                });

            </script>
            @endif

            <!-- Preprint notification -->
            @if (session('preprintsuccess'))
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
                        title: 'Preprint Uploaded'
                    });
                });

            </script>
            @endif

            <!-- Preprint notification -->
            @if (session('confesuccess'))
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
                        title: 'Conference Paper Uploaded'
                    });
                });

            </script>
            @endif
            <div class="card h-100">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold">Research outputs</span>
                        <small class="text-muted">Select a type to view your records. Click on any item to see full details.</small>
                    </div>
                    <div class="row g-2">
                        <div class="col-12 col-md-auto">
                          <select class="form-select form-select-sm border-secondary w-100" id="outputTypeSelect">
                            <option value="research" selected>Research</option>
                            <option value="preprints">Preprint</option>
                            <option value="conference-papers">Conference Paper</option>
                            <option value="books">Book</option>
                          </select>
                        </div>
                      
                        <div class="col-12 col-md-auto">
                          <select class="form-select form-select-sm border-primary text-primary w-100" id="uploadSelect">
                            <option selected hidden>Create new…</option>
                            <option value="PublishResearch">Publish Research</option>
                            <option value="Preprint">Preprint</option>
                            <option value="ConferencePaper">Conference Paper</option>
                            <option value="Book">Publish Book</option>
                          </select>
                        </div>
                      </div>
                      

                    <!-- Research Modal -->
                    <div class="modal fade" id="PublishResearch" tabindex="-1" aria-labelledby="publishLabel"
                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="publishLabel"><i class="fas fa-flask me-2"></i>Publish Research</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" action="{{ route('users.research.store') }}"
                                        method="POST" id="researchForm">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-6 d-flex justify-content-start">
                                                    <h5 class="modal-title fw-bold" id="publishLabel">Publish Research
                                                        Form</h5>
                                                </div>
                                                <div class="col-6 d-flex justify-content-end">
                                                    <button type="button" class="btn-close text-danger"
                                                        data-bs-dismiss="modal" aria-label="Close"
                                                        style="filter: invert(33%) sepia(96%) saturate(5000%) hue-rotate(345deg) brightness(90%) contrast(90%);">
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- File -->
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Research PDF <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="file" name="file"
                                                    accept="application/pdf" required>
                                            </div>

                                            <!-- Title -->
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Title <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="title" name="title"
                                                    required>
                                            </div>

                                            <!-- Authors -->
                                            <div class="mb-3">
                                                <label class="form-label">Authors <span class="text-danger">*</span>
                                                    <small class="text-muted">(Type a name and press ENTER)</small>
                                                </label>
                                                <div class="border rounded p-2" id="authors-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2" id="authors-chips"></div>
                                                    <input type="text" id="authors-input" class="form-control"
                                                        placeholder="Type a name and press Enter">
                                                </div>
                                            </div>

                                            <!-- Hidden authors[] inputs will be added dynamically -->
                                            <div id="authors-hidden-container"></div>

                                            <!-- Publication Date -->
                                            <div class="mb-3">
                                                <label for="published_at" class="form-label">Publication Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="published_at"
                                                    name="published_at" required>
                                            </div>

                                            <!-- Abstract -->
                                            <div class="mb-3">
                                                <label for="abstract" class="form-label">Abstract <small
                                                        class="text-muted">(optional)</small></label>
                                                <textarea class="form-control" id="abstract" name="abstract"
                                                    rows="4"></textarea>
                                            </div>

                                            <!-- Category -->
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Research Category <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="" selected disabled>Select a category</option>
                                                    <option value="Terminal">Terminal</option>
                                                    <option value="Technological">Technological</option>
                                                    <option value="Social">Social</option>
                                                </select>
                                            </div>

                                            <!-- Keywords -->
                                            <div class="mb-3">
                                                <label class="form-label">Keywords/Tags <small class="text-muted">(Type
                                                        a name and press ENTER) (optional)</small>
                                                </label>
                                                <div class="border rounded p-2" id="keywords-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2" id="keywords-chips"></div>
                                                    <input type="text" id="keywords-input" class="form-control"
                                                        placeholder="Type a keyword and press Enter">
                                                </div>
                                            </div>

                                            <!-- Hidden keywords[] inputs will be added dynamically -->
                                            <div id="keywords-hidden-container"></div>

                                            <!-- Visibility -->
                                            <div class="mb-3">
                                                <label class="form-label">File Visibility <span
                                                        class="text-danger">*</span></label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_public"
                                                            id="visibility_public" value="1" required checked>
                                                        <label class="form-check-label"
                                                            for="visibility_public">Public</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_public"
                                                            id="visibility_private" value="0" required>
                                                        <label class="form-check-label"
                                                            for="visibility_private">Private</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Publish
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Preprint -->
                    <div class="modal fade" id="Preprint" tabindex="-1" aria-labelledby="preprintLabel"
                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="preprintLabel"><i class="fas fa-file-alt me-2"></i>Preprint</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" action="{{ route('users.preprint.store')}}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-6 d-flex justify-content-start">
                                                    <h5 class="modal-title fw-bold" id="publishLabel">Preprint Form</h5>
                                                </div>
                                                <div class="col-6 d-flex justify-content-end">
                                                    <button type="button" class="btn-close text-danger"
                                                        data-bs-dismiss="modal" aria-label="Close"
                                                        style="filter: invert(33%) sepia(96%) saturate(5000%) hue-rotate(345deg) brightness(90%) contrast(90%);">
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="preprint-file" class="form-label">File (PDF) <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="preprint-file" name="file"
                                                    accept="application/pdf" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="preprint-title" class="form-label">Title <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="preprint-title" name="title"
                                                    required>
                                            </div>

                                            <!-- Authors with chips -->
                                            <div class="mb-3">
                                                <label class="form-label">Authors <span class="text-danger">*</span>
                                                    <small class="text-muted">(Type a name and press ENTER)</small>
                                                </label>
                                                <div class="border rounded p-2" id="preprint-authors-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2"
                                                        id="preprint-authors-chips"></div>
                                                    <input type="text" id="preprint-authors-input" class="form-control"
                                                        placeholder="Type a name and press Enter">
                                                    <!-- Hidden container -->
                                                    <div id="preprint-authors-hidden"></div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="preprint-published_at" class="form-label">Publication
                                                    Date</label>
                                                <input type="date" class="form-control" id="preprint-published_at"
                                                    name="published_at">
                                            </div>

                                            <div class="mb-3">
                                                <label for="preprint-repository_name" class="form-label">Repository Name
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="preprint-repository_name"
                                                    name="repository_name"
                                                    placeholder="e.g., arXiv, bioRxiv, institutional repository"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">File Visibility <span
                                                        class="text-danger">*</span></label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_public"
                                                            id="preprint-visibility_public" value="1" checked required>
                                                        <label class="form-check-label"
                                                            for="preprint-visibility_public">Public</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_public"
                                                            id="preprint-visibility_private" value="0" required>
                                                        <label class="form-check-label"
                                                            for="preprint-visibility_private">Private</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="preprint-abstract" class="form-label">Abstract <small
                                                        class="text-muted">(optional)</small></label>
                                                <textarea class="form-control" id="preprint-abstract" name="abstract"
                                                    rows="4"></textarea>
                                            </div>

                                            <!-- Keywords with chips -->
                                            <div class="mb-3">
                                                <label class="form-label">Keywords/Tags <small class="text-muted">(Type
                                                        a
                                                        keywords/Tags and press ENTER) (optional)</small></label>
                                                <div class="border rounded p-2" id="preprint-keywords-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2"
                                                        id="preprint-keywords-chips"></div>
                                                    <input type="text" id="preprint-keywords-input" class="form-control"
                                                        placeholder="Type a keyword and press Enter">
                                                    <!-- Hidden container -->
                                                    <div id="preprint-keywords-hidden"></div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Peer-reviewed? <small
                                                        class="text-muted">(optional)</small></label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="is_peer_reviewed" id="preprint-peer_reviewed_yes"
                                                            value="1">
                                                        <label class="form-check-label"
                                                            for="preprint-peer_reviewed_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="is_peer_reviewed" id="preprint-peer_reviewed_no"
                                                            value="0" checked>
                                                        <label class="form-check-label"
                                                            for="preprint-peer_reviewed_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                <i class="fas fa-save me-1"></i>Create
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conference Paper -->
                    <div class="modal fade" id="ConferencePaper" tabindex="-1" aria-labelledby="ConferenceLabel"
                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="ConferenceLabel"><i class="fas fa-chalkboard me-2"></i>Conference Paper</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="createConferenceForm" method="POST" enctype="multipart/form-data" action="{{ route('users.conferencepaper.store') }}">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="conf-file" class="form-label">File (PDF) <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="conf-file" name="file"
                                                    accept="application/pdf" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-title" class="form-label">Title <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="conf-title" name="title"
                                                    required>
                                            </div>

                                            <!-- Authors -->
                                            <div class="mb-3">
                                                <label class="form-label">Authors <span
                                                        class="text-danger">*</span></label>
                                                <div class="border rounded p-2" id="conf-authors-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2" id="conf-authors-chips">
                                                    </div>
                                                    <input type="text" id="conf-authors-input" class="form-control"
                                                        placeholder="Type a name and press Enter">
                                                </div>
                                                <div id="conf-authors-hidden"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-published_at" class="form-label">Publication Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="conf-published_at"
                                                    name="published_at" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-conference_name" class="form-label">Conference Name
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="conf-conference_name"
                                                    name="conference_name" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-conference_location" class="form-label">Conference
                                                    Location <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="conf-conference_location"
                                                    name="conference_location" placeholder="City, Country" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-publisher" class="form-label">Publisher <small
                                                        class="text-muted">(optional)</small></label>
                                                <input type="text" class="form-control" id="conf-publisher"
                                                    name="publisher">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">File Visibility <span
                                                        class="text-danger">*</span></label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_public"
                                                            id="conf-visibility_public" value="1" required>
                                                        <label class="form-check-label"
                                                            for="conf-visibility_public">Public</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_public"
                                                            id="conf-visibility_private" value="0" required>
                                                        <label class="form-check-label"
                                                            for="conf-visibility_private">Private</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Keywords -->
                                            <div class="mb-3">
                                                <label class="form-label">Keywords/Tags <small
                                                        class="text-muted">(optional)</small></label>
                                                <div class="border rounded p-2" id="conf-keywords-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2" id="conf-keywords-chips">
                                                    </div>
                                                    <input type="text" id="conf-keywords-input" class="form-control"
                                                        placeholder="Type a keyword and press Enter">
                                                </div>
                                                <div id="conf-keywords-hidden"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-abstract" class="form-label">Abstract <small
                                                        class="text-muted">(optional)</small></label>
                                                <textarea class="form-control" id="conf-abstract" name="abstract"
                                                    rows="4"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="conf-language" class="form-label">Language <small
                                                        class="text-muted">(ISO 639-1, optional)</small></label>
                                                <input type="text" class="form-control" id="conf-language"
                                                    name="language" placeholder="e.g., en, fr, es">
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <span class="spinner-border spinner-border-sm d-none"
                                                    role="status"></span>
                                                <i class="fas fa-save me-1"></i>Create
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Dynamic list -->
                <div class="card-body pt-0 mt-4">
                                        <!-- Search and Sort Controls -->
                    <div class="row g-3 mb-3" id="searchSortControls" style="display: none;">
                        <div class="col-12 col-lg-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchInput" 
                                       placeholder="Search by title, authors, or keywords..." 
                                       style="border-left: 0;">
                    </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <select class="form-select form-select-sm" id="sortSelect">
                                    <option value="title-asc">Title (A-Z)</option>
                                    <option value="title-desc">Title (Z-A)</option>
                                    <option value="date-asc">Date (Oldest First)</option>
                                    <option value="date-desc" selected>Date (Newest First)</option>
                                    <option value="authors-asc">Authors (A-Z)</option>
                                    <option value="type-asc">Type (A-Z)</option>
                                </select>
                                <button class="btn btn-outline-secondary btn-sm" id="clearFilters" type="button">
                                    <i class="fas fa-times me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Results Info -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3" id="resultsInfo" style="display: none;">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <small class="text-muted" id="resultsCount"></small>
                            <small class="text-muted" id="searchStatus"></small>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-primary btn-sm d-md-none" type="button" id="mobileViewToggle" title="Toggle view">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="outputList" class="mt-2"></div>
                </div>

                <!-- Book Modal -->
                <div class="modal fade" id="Book" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Book</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="createUserBookForm" method="POST" enctype="multipart/form-data" action="{{ route('users.book.store') }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="user-book-file" class="form-label">File (PDF) <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="user-book-file" name="file" accept="application/pdf" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="user-book-title" class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="user-book-title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Authors <span class="text-danger">*</span></label>
                                        <div class="border rounded p-2" id="user-book-authors-container">
                                            <div class="d-flex flex-wrap gap-2 mb-2" id="user-book-authors-chips"></div>
                                            <input type="text" id="user-book-authors-input" class="form-control" placeholder="Type a name and press Enter">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Editors <small class="text-muted">(optional)</small></label>
                                        <div class="border rounded p-2" id="user-book-editors-container">
                                            <div class="d-flex flex-wrap gap-2 mb-2" id="user-book-editors-chips"></div>
                                            <input type="text" id="user-book-editors-input" class="form-control" placeholder="Type a name and press Enter">
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="user-book-published_at" class="form-label">Publication Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="user-book-published_at" name="published_at" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="user-book-publisher" class="form-label">Publisher <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="user-book-publisher" name="publisher" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-0">
                                        <div class="col-md-6">
                                            <label for="user-book-isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="user-book-isbn" name="isbn" placeholder="10 or 13 digits" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="user-book-edition" class="form-label">Edition <small class="text-muted">(optional)</small></label>
                                            <input type="text" class="form-control" id="user-book-edition" name="edition" placeholder="e.g., 2nd, Revised">
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-0">
                                        <div class="col-md-6">
                                            <label for="user-book-doi" class="form-label">DOI <small class="text-muted">(optional)</small></label>
                                            <input type="text" class="form-control" id="user-book-doi" name="doi" placeholder="e.g., 10.1234/abc.5678">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">File Visibility <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_public" id="user-book-visibility_public" value="1" checked required>
                                                    <label class="form-check-label" for="user-book-visibility_public">Public</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_public" id="user-book-visibility_private" value="0" required>
                                                    <label class="form-check-label" for="user-book-visibility_private">Private</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <label class="form-label">Keywords/Tags <small class="text-muted">(optional)</small></label>
                                        <div class="border rounded p-2" id="user-book-keywords-container">
                                            <div class="d-flex flex-wrap gap-2 mb-2" id="user-book-keywords-chips"></div>
                                            <input type="text" id="user-book-keywords-input" class="form-control" placeholder="Type a keyword and press Enter">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        <i class="fas fa-save me-1"></i>Create
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Details Modal -->
                <div class="modal fade" id="outputDetailsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary text-white">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt me-3 fs-4" id="outputDetailsIcon"></i>
                                    <div>
                                        <h5 class="modal-title mb-0" id="outputDetailsTitle"></h5>
                                        <small class="opacity-75" id="outputDetailsSubtitle"></small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <!-- File Information Section -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-info-circle me-2"></i>File Information
                                                </h6>
                                                <div id="outputDetailsFileInfo"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-users me-2"></i>Authors & Contributors
                                                </h6>
                                                <div id="outputDetailsAuthors"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Publication Details Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-calendar-alt me-2"></i>Publication Details
                                                </h6>
                                                <div id="outputDetailsMeta" class="row g-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Abstract Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-align-left me-2"></i>Abstract
                                                </h6>
                                                <div id="outputDetailsAbstract" class="text-muted fst-italic"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keywords/Tags Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-tags me-2"></i>Keywords & Tags
                                                </h6>
                                                <div id="outputDetailsTags" class="d-flex flex-wrap gap-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Close
                                </button>
                                <button type="button" class="btn btn-primary" id="outputDetailsDownload">
                                    <i class="fas fa-download me-1"></i>Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
{{--  
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Publications</span>
                    <div>
                        <a href="#" class="btn btn-outline-primary btn-sm">Upload</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Export PDF</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Instruction for users -->
                    <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>Tip:</strong> Click on any research output item above to view complete details and download the PDF file.
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Co-authors</th>
                                    <th>DOI</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($publications as $pub)
                                <tr>
                                    <td>{{ $pub->title ?? 'Untitled' }}</td>
                                    <td>{{ $pub->year ?? '-' }}</td>
                                    <td>{{ $pub->co_authors ?? '-' }}</td>
                                    <td>@if(!empty($pub->doi))<a href="https://doi.org/{{ $pub->doi }}"
                                            target="_blank">{{ $pub->doi }}</a>@else - @endif</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-muted">No publications found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}
</div>



<!-- Bootstrap Icons CDN for bell icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


<script>
    // Recent Activity dynamic fetch and render
    document.addEventListener('DOMContentLoaded', function(){
        const listEl = document.getElementById('activityList');
        const loadingEl = document.getElementById('activityLoading');
        const emptyEl = document.getElementById('activityEmpty');

        if(!listEl) return;

        function escapeHtml(str){
            return String(str || '').replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[s] || s));
        }

        function formatTimestamp(iso){
            try{
                const d = new Date(iso);
                return d.toLocaleString();
            }catch(e){
                return iso;
            }
        }

        function render(items){
            loadingEl?.classList.add('d-none');
            listEl.innerHTML = '';

            if(!items || items.length === 0){
                emptyEl?.classList.remove('d-none');
                listEl.classList.add('d-none');
                return;
            }

            emptyEl?.classList.add('d-none');
            listEl.classList.remove('d-none');

            items.forEach(item => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="text-break">
                            <span class="fw-bold text-primary">${escapeHtml(item.type)}</span>
                            <span class="text-muted">${escapeHtml(item.action)}</span>:
                            ${escapeHtml(item.title || 'Untitled')}
                        </div>
                        <small class="text-muted text-nowrap">${escapeHtml(formatTimestamp(item.timestamp))}</small>
                    </div>
                `;
                listEl.appendChild(li);
            });
        }

        async function load(){
            try{
                const res = await fetch(`{{ route('users.activity.list') }}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                const data = await res.json();
                // Server already sorts desc, but ensure newest first
                data.sort((a,b) => new Date(b.timestamp) - new Date(a.timestamp));
                render(data);
            }catch(e){
                if (loadingEl) loadingEl.textContent = 'Failed to load activity.';
            }
        }

        load();
    });
 </script>
<script>
    // Conference chips now use the generic handleChips initializer below
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        function handleChips(inputId, chipsContainerId, hiddenContainerId, fieldName) {
            const input = document.getElementById(inputId);
            const chipsContainer = document.getElementById(chipsContainerId);
            const hiddenContainer = document.getElementById(hiddenContainerId);

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && input.value.trim() !== '') {
                    e.preventDefault();
                    const value = input.value.trim();

                    // Chip wrapper
                    const chip = document.createElement('span');
                    chip.className = 'badge bg-primary d-inline-flex align-items-center p-2';

                    // Text
                    const text = document.createElement('span');
                    text.textContent = value;

                    // Hidden input
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = fieldName + '[]';
                    hiddenInput.value = value;
                    hiddenContainer.appendChild(hiddenInput);

                    // Close button
                    const closeBtn = document.createElement('span');
                    closeBtn.innerHTML = '&times;';
                    closeBtn.className = 'ms-2';
                    closeBtn.style.cursor = 'pointer';
                    closeBtn.addEventListener('click', () => {
                        chip.remove();
                        hiddenInput.remove();
                    });

                    // Append
                    chip.appendChild(text);
                    chip.appendChild(closeBtn);
                    chipsContainer.appendChild(chip);

                    input.value = '';
                }
            });
        }

        // For Preprint modal
        handleChips('preprint-authors-input', 'preprint-authors-chips', 'preprint-authors-hidden', 'authors');
        handleChips('preprint-keywords-input', 'preprint-keywords-chips', 'preprint-keywords-hidden',
            'keywords');
        // For Conference Paper modal
        handleChips('conf-authors-input', 'conf-authors-chips', 'conf-authors-hidden', 'authors');
        handleChips('conf-keywords-input', 'conf-keywords-chips', 'conf-keywords-hidden', 'keywords');
    });

</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        function handleChips(inputId, chipsContainerId, hiddenContainerId, fieldName) {
            const input = document.getElementById(inputId);
            const chipsContainer = document.getElementById(chipsContainerId);
            const hiddenContainer = document.getElementById(hiddenContainerId);

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && input.value.trim() !== '') {
                    e.preventDefault();
                    const value = input.value.trim();

                    // Chip wrapper
                    const chip = document.createElement('span');
                    chip.className = 'badge bg-primary d-inline-flex align-items-center p-2';

                    // Text
                    const text = document.createElement('span');
                    text.textContent = value;

                    // Hidden input (for form submit)
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = fieldName + '[]';
                    hiddenInput.value = value;
                    hiddenContainer.appendChild(hiddenInput);

                    // Close button (×)
                    const closeBtn = document.createElement('span');
                    closeBtn.innerHTML = '&times;';
                    closeBtn.className = 'ms-2';
                    closeBtn.style.cursor = 'pointer';
                    closeBtn.addEventListener('click', () => {
                        chip.remove();
                        hiddenInput.remove();
                    });

                    // Append
                    chip.appendChild(text);
                    chip.appendChild(closeBtn);
                    chipsContainer.appendChild(chip);

                    // Clear input
                    input.value = '';
                }
            });
        }

        // Init for Authors & Keywords
        handleChips('authors-input', 'authors-chips', 'authors-hidden-container', 'authors');
        handleChips('keywords-input', 'keywords-chips', 'keywords-hidden-container', 'keywords');

        // removed duplicate modal trigger; handled in unified script below
    });

</script>
<script>
    // Dynamic greeting based on time of day
    function getGreeting() {
        const hour = new Date().getHours();
        if (hour < 12) return 'Good morning';
        if (hour < 18) return 'Good afternoon';
        return 'Good evening';
    }
    document.addEventListener('DOMContentLoaded', function () {
        const greetingName = @json((($user->title ?? null) ? ($user->title.' ') : '').($user->name ?? ''));
        const greetingEl = document.getElementById('greeting');
        if (greetingEl) {
            greetingEl.textContent = getGreeting() + ', ' + greetingName + '!';
        }
        // Project filter (guard for missing element)
        const projectFilter = document.getElementById('projectFilter');
        if (projectFilter) {
            projectFilter.addEventListener('change', function () {
                const val = this.value;
                document.querySelectorAll('.project-item').forEach(function (item) {
                    if (val === 'all' || item.dataset.status === val) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });

</script>
<script>
    // Research outputs section interactivity
    document.addEventListener('DOMContentLoaded', function(){
        const createSelect = document.getElementById('uploadSelect');
        if(createSelect){
            createSelect.addEventListener('change', function(){
                const id = this.value;
                if(!id){ return; }

                // Find target modal element
                const modalEl = document.getElementById(id);
                if(!modalEl){
                    // reset selection and gracefully notify
                    this.selectedIndex = 0;
                    console.warn('Modal element not found for id:', id);
                    return;
                }

                // Close any currently open modal to avoid stacked backdrops
                const openModalEl = document.querySelector('.modal.show');
                if(openModalEl){
                    try {
                        bootstrap.Modal.getInstance(openModalEl)?.hide();
                    } catch(e) {}
                }

                // Ensure backdrop issues are cleared before showing a new modal
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');

                // Now show the requested modal
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl, {backdrop: 'static', keyboard: false});
                modal.show();

                // reset selection
                this.selectedIndex = 0;
            });
        }

        const typeSelect = document.getElementById('outputTypeSelect');
        const list = document.getElementById('outputList');
        const searchSortControls = document.getElementById('searchSortControls');
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const clearFilters = document.getElementById('clearFilters');
        const resultsInfo = document.getElementById('resultsInfo');
        const resultsCount = document.getElementById('resultsCount');
        const searchStatus = document.getElementById('searchStatus');

        let currentData = [];
        let filteredData = [];

        async function fetchAndRender(type){
            list.innerHTML = '<div class="text-muted text-center py-4"><div class="loading-spinner mb-2"></div>Loading…</div>';
            let url = '';
            if(type === 'research') url = '{{ route('users.research.list') }}';
            if(type === 'preprints') url = '{{ route('users.preprints.list') }}';
            if(type === 'conference-papers') url = '{{ route('users.conference-papers.list') }}';
            if(type === 'books') url = '{{ route('users.books.list') }}';

            try{
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                const data = await res.json();
                currentData = data;
                filteredData = [...data];
                renderList(data);
                showSearchControls();
                updateResultsInfo();
            }catch(e){
                list.innerHTML = '<div class="text-danger text-center py-4">Failed to load records.</div>';
                hideSearchControls();
            }
        }

        function renderList(items){
            if(!items || items.length === 0){
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    list.innerHTML = `
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="fas fa-search fa-2x mb-3"></i>
                                <h5>No results found</h5>
                                <p>No records match your search for "<strong>${escapeHtml(searchTerm)}</strong>"</p>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="clearAllFilters()">
                                <i class="fas fa-times me-1"></i>Clear Search
                            </button>
                        </div>
                    `;
                } else {
                    list.innerHTML = '<div class="text-muted text-center py-4">No records found.</div>';
                }
                return;
            }
            
            // Check if we should show table view on larger screens
            const shouldShowTable = window.innerWidth >= 992 && items.length > 3;
            
            if (shouldShowTable) {
                renderTableView(items);
            } else {
                renderCardView(items);
            }
        }
        
        function renderCardView(items) {
            list.innerHTML = '';
            items.forEach(item => {
                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'w-100 text-start border rounded-1 px-3 py-2 mb-2 bg-white cursor-pointer output-item';
                row.addEventListener('click', () => openDetails(item));
                
                const authors = Array.isArray(item.authors) ? item.authors.join(', ') : (item.authors || '');
                const title = highlightSearchTerm(item.title || 'Untitled');
                const authorsText = highlightSearchTerm(authors);
                const type = highlightSearchTerm(item.type || '');
                
                row.innerHTML = `
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-2">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-break">${title}</div>
                            <div class="small text-muted">
                                <div class="d-flex flex-column flex-sm-row gap-1 gap-sm-2">
                                    <span class="text-truncate">${authorsText}</span>
                                    ${item.published_at ? `<span class="text-muted d-none d-sm-inline">•</span><span class="text-muted">${escapeHtml(item.published_at)}</span>` : ''}
                            </div>
                        </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            <span class="badge text-bg-light text-nowrap">${type}</span>
                            <small class="text-muted d-md-none">Tap to view details</small>
                        </div>
                    </div>
                `;
                list.appendChild(row);
            });
        }

        function renderTableView(items) {
            list.innerHTML = `
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-nowrap">Title</th>
                                <th scope="col" class="text-nowrap d-none d-lg-table-cell">Authors</th>
                                <th scope="col" class="text-nowrap d-none d-md-table-cell">Date</th>
                                <th scope="col" class="text-nowrap">Type</th>
                                <th scope="col" class="text-nowrap d-none d-lg-table-cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map(item => {
                                const authors = Array.isArray(item.authors) ? item.authors.join(', ') : (item.authors || '');
                                const title = highlightSearchTerm(item.title || 'Untitled');
                                const authorsText = highlightSearchTerm(authors);
                                const type = highlightSearchTerm(item.type || '');
                                
                                return `
                                    <tr class="cursor-pointer" 
                                        role="button" 
                                        tabindex="0" 
                                        aria-label="View details for ${escapeHtml(item.title || 'Untitled')}" 
                                        onclick="openDetails(${JSON.stringify(item).replace(/"/g, '&quot;')})"
                                        onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault(); openDetails(${JSON.stringify(item).replace(/"/g, '&quot;')});}">
                                        <td class="fw-semibold">${title}</td>
                                        <td class="d-none d-lg-table-cell text-truncate" style="max-width: 200px;" title="${escapeHtml(authors)}">${authorsText}</td>
                                        <td class="d-none d-md-table-cell text-muted">${escapeHtml(item.published_at || 'N/A')}</td>
                                        <td><span class="badge text-bg-light">${type}</span></td>
                                        <td class="d-none d-lg-table-cell">
                                            <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); openDetails(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                                                <i class="fas fa-eye me-1"></i>View
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Expose globally so table row/buttons can call it
        window.openDetails = function(item){
            const titleEl = document.getElementById('outputDetailsTitle');
            const subtitleEl = document.getElementById('outputDetailsSubtitle');
            const iconEl = document.getElementById('outputDetailsIcon');
            const fileInfoEl = document.getElementById('outputDetailsFileInfo');
            const authorsEl = document.getElementById('outputDetailsAuthors');
            const metaEl = document.getElementById('outputDetailsMeta');
            const absEl = document.getElementById('outputDetailsAbstract');
            const tagsEl = document.getElementById('outputDetailsTags');
            const downloadBtn = document.getElementById('outputDetailsDownload');

            // Set title and icon based on type
            titleEl.textContent = item.title || 'Untitled';
            subtitleEl.textContent = item.type || 'Research Output';
            
            // Set appropriate icon
            const iconMap = {
                'Research': 'fas fa-flask',
                'Preprint': 'fas fa-file-alt',
                'Conference Paper': 'fas fa-chalkboard',
                'Book': 'fas fa-book'
            };
            iconEl.className = iconMap[item.type] || 'fas fa-file-alt';

            // File Information
            const fileSize = item.file_size ? formatFileSize(item.file_size) : 'Unknown';
            fileInfoEl.innerHTML = `
                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted d-block">File Name</small>
                        <strong>${escapeHtml(item.file || 'N/A')}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">File Size</small>
                        <strong>${fileSize}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Type</small>
                        <span class="badge bg-primary">${escapeHtml(item.type || 'N/A')}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge bg-success">Available</span>
                    </div>
                </div>
            `;

            // Authors & Contributors
            const authors = Array.isArray(item.authors) ? item.authors : (item.authors || []);
            const editors = Array.isArray(item.editors) ? item.editors : [];
            
            let authorsHtml = '';
            if (authors.length > 0) {
                authorsHtml += '<div class="mb-2"><small class="text-muted d-block">Authors</small>';
                authors.forEach(author => {
                    authorsHtml += `<span class="badge bg-primary me-1 mb-1">${escapeHtml(author)}</span>`;
                });
                authorsHtml += '</div>';
            }
            
            if (editors.length > 0) {
                authorsHtml += '<div class="mb-2"><small class="text-muted d-block">Editors</small>';
                editors.forEach(editor => {
                    authorsHtml += `<span class="badge bg-info me-1 mb-1">${escapeHtml(editor)}</span>`;
                });
                authorsHtml += '</div>';
            }
            
            if (authors.length === 0 && editors.length === 0) {
                authorsHtml = '<span class="text-muted">No authors or editors specified</span>';
            }
            
            authorsEl.innerHTML = authorsHtml;

            // Publication Details
            let metaHtml = '';
            
            if (item.published_at) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-calendar text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Published Date</small>
                                <strong>${escapeHtml(item.published_at)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.category) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-tag text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Category</small>
                                <strong>${escapeHtml(item.category)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.repository_name) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-database text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Repository</small>
                                <strong>${escapeHtml(item.repository_name)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.conference_name) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-chalkboard text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Conference</small>
                                <strong>${escapeHtml(item.conference_name)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.conference_location) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Location</small>
                                <strong>${escapeHtml(item.conference_location)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.publisher) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-building text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">Publisher</small>
                                <strong>${escapeHtml(item.publisher)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.isbn) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-barcode text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">ISBN</small>
                                <strong>${escapeHtml(item.isbn)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            if (item.doi) {
                metaHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded border">
                            <i class="fas fa-link text-primary me-3"></i>
                            <div>
                                <small class="text-muted d-block">DOI</small>
                                <a href="https://doi.org/${escapeHtml(item.doi)}" target="_blank" class="text-decoration-none">
                                    <strong>${escapeHtml(item.doi)}</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            metaEl.innerHTML = metaHtml || '<div class="col-12"><span class="text-muted">No additional publication details available</span></div>';

            // Abstract
            absEl.textContent = item.abstract || 'No abstract provided.';
            if (!item.abstract) {
                absEl.className = 'text-muted fst-italic';
            } else {
                absEl.className = 'text-dark';
            }

            // Keywords/Tags
            tagsEl.innerHTML = '';
            const keywords = Array.isArray(item.keywords) ? item.keywords : (item.keywords || []);
            if (keywords.length > 0) {
                keywords.forEach(k => {
                    const b = document.createElement('span');
                    b.className = 'badge bg-secondary-subtle text-secondary-emphasis me-2 mb-2 px-3 py-2';
                    b.textContent = k;
                    tagsEl.appendChild(b);
                });
            } else {
                tagsEl.innerHTML = '<span class="text-muted">No keywords or tags specified</span>';
            }

            // Download button functionality
            if (item.file_path) {
                downloadBtn.style.display = 'inline-block';
                const downloadRoutes = {
                    'Research': '{{ route("users.download.research", ":id") }}',
                    'Preprint': '{{ route("users.download.preprint", ":id") }}',
                    'Conference Paper': '{{ route("users.download.conference-paper", ":id") }}',
                    'Book': '{{ route("users.download.book", ":id") }}'
                };
                
                downloadBtn.onclick = () => {
                    const route = downloadRoutes[item.type];
                    if (route) {
                        // Show loading state
                        const originalText = downloadBtn.innerHTML;
                        downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Downloading...';
                        downloadBtn.disabled = true;
                        
                        // Download the file
                        window.location.href = route.replace(':id', item.id);
                        
                        // Reset button after a short delay
                        setTimeout(() => {
                            downloadBtn.innerHTML = originalText;
                            downloadBtn.disabled = false;
                        }, 2000);
                    }
                };
            } else {
                downloadBtn.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('outputDetailsModal')).show();
        }

        function escapeHtml(str){
            return String(str).replace(/[&<>\"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;','\'':'&#39;'}[s]));
        }

        // Search and Sort Functions
        function showSearchControls() {
            searchSortControls.style.display = 'block';
            resultsInfo.style.display = 'flex';
        }

        function hideSearchControls() {
            searchSortControls.style.display = 'none';
            resultsInfo.style.display = 'none';
        }

        function updateResultsInfo() {
            const total = currentData.length;
            const filtered = filteredData.length;
            const searchTerm = searchInput.value.trim();
            
            // Mobile-friendly results display
            if (window.innerWidth <= 768) {
                resultsCount.textContent = `${filtered}/${total}`;
                if (searchTerm) {
                    searchStatus.textContent = `"${searchTerm}"`;
                } else {
                    searchStatus.textContent = '';
                }
            } else {
                resultsCount.textContent = `Showing ${filtered} of ${total} records`;
                if (searchTerm) {
                    searchStatus.textContent = `Filtered by: "${searchTerm}"`;
                } else {
                    searchStatus.textContent = '';
                }
            }
        }

        function highlightSearchTerm(text) {
            const searchTerm = searchInput.value.trim().toLowerCase();
            if (!searchTerm || !text) return escapeHtml(text);
            
            const regex = new RegExp(`(${escapeHtml(searchTerm)})`, 'gi');
            return escapeHtml(text).replace(regex, '<span class="search-highlight">$1</span>');
        }

        function performSearch() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            
            if (!searchTerm) {
                filteredData = [...currentData];
            } else {
                filteredData = currentData.filter(item => {
                    const title = (item.title || '').toLowerCase();
                    const authors = Array.isArray(item.authors) ? item.authors.join(' ').toLowerCase() : (item.authors || '').toLowerCase();
                    const keywords = Array.isArray(item.keywords) ? item.keywords.join(' ').toLowerCase() : (item.keywords || '').toLowerCase();
                    const abstract = (item.abstract || '').toLowerCase();
                    const type = (item.type || '').toLowerCase();
                    
                    return title.includes(searchTerm) || 
                           authors.includes(searchTerm) || 
                           keywords.includes(searchTerm) || 
                           abstract.includes(searchTerm) || 
                           type.includes(searchTerm);
                });
            }
            
            performSort();
            updateResultsInfo();
        }

        function performSort() {
            const sortValue = sortSelect.value;
            const [field, direction] = sortValue.split('-');
            
            filteredData.sort((a, b) => {
                let aValue, bValue;
                
                switch (field) {
                    case 'title':
                        aValue = (a.title || '').toLowerCase();
                        bValue = (b.title || '').toLowerCase();
                        break;
                    case 'date':
                        aValue = new Date(a.published_at || '1900-01-01');
                        bValue = new Date(b.published_at || '1900-01-01');
                        break;
                    case 'authors':
                        aValue = Array.isArray(a.authors) ? a.authors.join(' ').toLowerCase() : (a.authors || '').toLowerCase();
                        bValue = Array.isArray(b.authors) ? b.authors.join(' ').toLowerCase() : (b.authors || '').toLowerCase();
                        break;
                    case 'type':
                        aValue = (a.type || '').toLowerCase();
                        bValue = (b.type || '').toLowerCase();
                        break;
                    default:
                        return 0;
                }
                
                if (direction === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            renderList(filteredData);
        }

        function clearAllFilters() {
            searchInput.value = '';
            sortSelect.value = 'date-desc';
            filteredData = [...currentData];
            performSort();
            updateResultsInfo();
        }

        if(typeSelect){
            typeSelect.addEventListener('change', () => fetchAndRender(typeSelect.value));
            fetchAndRender(typeSelect.value);
        }

        // Event Listeners for Search and Sort
        searchInput.addEventListener('input', debounce(performSearch, 300));
        sortSelect.addEventListener('change', performSort);
        clearFilters.addEventListener('click', clearAllFilters);
        
        // Mobile-specific event listeners
        const mobileViewToggle = document.getElementById('mobileViewToggle');
        
        if (mobileViewToggle) {
            mobileViewToggle.addEventListener('click', () => {
                const list = document.getElementById('outputList');
                list.classList.toggle('mobile-compact-view');
                mobileViewToggle.innerHTML = list.classList.contains('mobile-compact-view') ? 
                    '<i class="fas fa-list"></i>' : '<i class="fas fa-th-large"></i>';
            });
        }

        // Debounce function for search input
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Handle window resize for responsive behavior
        window.addEventListener('resize', debounce(() => {
            if (filteredData.length > 0) {
                renderList(filteredData);
            }
        }, 250));

        // Initialize Book modal helpers
        (function initUserBookModal(){
            const fileInput = document.getElementById('user-book-file');
            const titleInput = document.getElementById('user-book-title');
            fileInput?.addEventListener('change', (e) => {
                const file = e.target.files?.[0];
                if (!file) return;
                const fileNameBase = file.name.replace(/\.pdf$/i, '').replace(/[_-]+/g, ' ');
                if (!titleInput.value) titleInput.value = fileNameBase;
            });

            const bookModal = document.getElementById('Book');
            if (bookModal) {
                bookModal.addEventListener('shown.bs.modal', function() {
                    const form = document.getElementById('createUserBookForm');
                    if (form) {
                        form.reset();
                        const aChips = document.getElementById('user-book-authors-chips');
                        const eChips = document.getElementById('user-book-editors-chips');
                        const kChips = document.getElementById('user-book-keywords-chips');
                        if (aChips) aChips.innerHTML = '';
                        if (eChips) eChips.innerHTML = '';
                        if (kChips) kChips.innerHTML = '';
                    }
                    setTimeout(() => { initChipInputs(); }, 150);
                });
            }

            const createForm = document.getElementById('createUserBookForm');
            createForm?.addEventListener('submit', function(e) {
                const authorsChips = this.querySelectorAll('#user-book-authors-chips input[name="authors[]"]');
                if (authorsChips.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one author.');
                    return false;
                }
            });

            function initChipInputs(){
                setupChipInput('user-book-authors-input','user-book-authors-chips','authors[]','bg-secondary');
                setupChipInput('user-book-editors-input','user-book-editors-chips','editors[]','bg-secondary');
                setupChipInput('user-book-keywords-input','user-book-keywords-chips','keywords[]','bg-info');
            }

            function setupChipInput(inputId, chipsId, hiddenName, badgeClass){
                const input = document.getElementById(inputId);
                const chips = document.getElementById(chipsId);
                if(!input || !chips) return;
                input.removeEventListener('keydown', input._chipHandler || (()=>{}));
                input._chipHandler = (e) => {
                    if(e.key === 'Enter' || e.key === ','){
                        e.preventDefault();
                        const value = input.value.trim();
                        if(!value) return;
                        const chip = document.createElement('span');
                        chip.className = `badge ${badgeClass} d-inline-flex align-items-center`;
                        chip.style.gap = '6px';
                        chip.textContent = value;
                        const remove = document.createElement('button');
                        remove.type = 'button';
                        remove.className = 'btn-close btn-close-white btn-sm ms-2';
                        remove.addEventListener('click', () => chip.remove());
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = hiddenName;
                        hidden.value = value;
                        chip.appendChild(remove);
                        chip.appendChild(hidden);
                        chips.appendChild(chip);
                        input.value = '';
                    }
                };
                input.addEventListener('keydown', input._chipHandler);
            }
        })();
    });
</script>
@endsection
@endcan
