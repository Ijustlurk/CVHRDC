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
                        <a href="#" class="btn btn-outline-secondary btn-sm">Upload Publications</a>
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
                    <ul class="list-group">
                        @forelse($activities as $activity)
                        <li class="list-group-item">
                            <span class="fw-bold text-primary">{{ ucfirst($activity['type']) }}</span>:
                            {{ $activity['text'] }}<br>
                            <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                        </li>
                        @empty
                        <li class="list-group-item text-muted">No recent activity</li>
                        @endforelse
                    </ul>
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
                        <small class="text-muted">Select a type to view your records.</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm w-auto border-secondary" id="outputTypeSelect">
                            <option value="research" selected>Research</option>
                            <option value="preprints">Preprint</option>
                            <option value="conference-papers">Conference Paper</option>
                            <option value="books">Book</option>
                        </select>
                        <!-- Create new (kept existing behavior) -->
                        <select class="form-select form-select-sm w-auto border-primary text-primary" id="uploadSelect">
                            <option selected hidden>Create new…</option>
                            <option value="PublishResearch">Publish Research</option>
                            <option value="Preprint">Preprint</option>
                            <option value="ConferencePaper">Conference Paper</option>
                            <option value="Book">Publish Book</option>
                        </select>
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
                <div class="card-body pt-0">
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
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="outputDetailsTitle"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2" id="outputDetailsMeta"></div>
                                <div class="mb-3">
                                    <h6 class="fw-semibold mb-1">Abstract</h6>
                                    <div id="outputDetailsAbstract" class="text-muted"></div>
                                </div>
                                <div class="mb-2">
                                    <h6 class="fw-semibold mb-1">Tags</h6>
                                    <div id="outputDetailsTags" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

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
</div>
<!-- Bootstrap Icons CDN for bell icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


<script>
    document.addEventListener("DOMContentLoaded", function () {
        function setupChips(inputId, chipsId, hiddenId, inputName, badgeClass) {
            const input = document.getElementById(inputId);
            const chips = document.getElementById(chipsId);
            const hidden = document.getElementById(hiddenId);

            input.addEventListener("keydown", function (e) {
                if (e.key === "Enter" && this.value.trim() !== "") {
                    e.preventDefault();
                    const value = this.value.trim();

                    // Create chip
                    const chip = document.createElement("span");
                    chip.className = `badge ${badgeClass} me-2`;
                    chip.innerText = value;
                    chips.appendChild(chip);

                    // Create hidden input
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = inputName;
                    hiddenInput.value = value;
                    hidden.appendChild(hiddenInput);

                    this.value = "";
                }
            });
        }

        setupChips("conf-authors-input", "conf-authors-chips", "conf-authors-hidden", "authors[]",
        "bg-primary");
        setupChips("conf-keywords-input", "conf-keywords-chips", "conf-keywords-hidden", "keywords[]",
            "bg-success");
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

        async function fetchAndRender(type){
            list.innerHTML = '<div class="text-muted">Loading…</div>';
            let url = '';
            if(type === 'research') url = '{{ route('users.research.list') }}';
            if(type === 'preprints') url = '{{ route('users.preprints.list') }}';
            if(type === 'conference-papers') url = '{{ route('users.conference-papers.list') }}';
            if(type === 'books') url = '{{ route('users.books.list') }}';

            try{
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                const data = await res.json();
                renderList(data);
            }catch(e){
                list.innerHTML = '<div class="text-danger">Failed to load records.</div>';
            }
        }

        function renderList(items){
            if(!items || items.length === 0){
                list.innerHTML = '<div class="text-muted">No records found.</div>';
                return;
            }
            list.innerHTML = '';
            items.forEach(item => {
                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'w-100 text-start border rounded-1 px-3 py-2 mb-2 bg-white';
                row.addEventListener('click', () => openDetails(item));
                const authors = Array.isArray(item.authors) ? item.authors.join(', ') : (item.authors || '');
                row.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="fw-semibold">${escapeHtml(item.title || 'Untitled')}</div>
                            <div class="small text-muted">${escapeHtml(authors)}${item.published_at ? ' • ' + escapeHtml(item.published_at) : ''}</div>
                        </div>
                        <span class="badge text-bg-light">${escapeHtml(item.type || '')}</span>
                    </div>
                `;
                list.appendChild(row);
            });
        }

        function openDetails(item){
            const titleEl = document.getElementById('outputDetailsTitle');
            const metaEl = document.getElementById('outputDetailsMeta');
            const absEl = document.getElementById('outputDetailsAbstract');
            const tagsEl = document.getElementById('outputDetailsTags');

            titleEl.textContent = item.title || 'Untitled';
            const authors = Array.isArray(item.authors) ? item.authors.join(', ') : (item.authors || '');
            let metaParts = [];
            if(authors) metaParts.push('Authors: ' + authors);
            if(item.published_at) metaParts.push('Published: ' + item.published_at);
            if(item.category) metaParts.push('Category: ' + item.category);
            if(item.repository_name) metaParts.push('Repository: ' + item.repository_name);
            if(item.conference_name) metaParts.push('Conference: ' + item.conference_name);
            if(item.publisher) metaParts.push('Publisher: ' + item.publisher);
            if(item.isbn) metaParts.push('ISBN: ' + item.isbn);
            if(item.doi) metaParts.push('DOI: ' + item.doi);
            metaEl.innerHTML = metaParts.map(p => `<span class="badge text-bg-light me-2 mb-2">${escapeHtml(p)}</span>`).join('');
            absEl.textContent = item.abstract || 'No abstract provided.';
            tagsEl.innerHTML = '';
            const keywords = Array.isArray(item.keywords) ? item.keywords : (item.keywords || []);
            keywords.forEach(k => {
                const b = document.createElement('span');
                b.className = 'badge bg-secondary-subtle text-secondary-emphasis';
                b.textContent = k;
                tagsEl.appendChild(b);
            });
            new bootstrap.Modal(document.getElementById('outputDetailsModal')).show();
        }

        function escapeHtml(str){
            return String(str).replace(/[&<>\"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;','\'':'&#39;'}[s]));
        }

        if(typeSelect){
            typeSelect.addEventListener('change', () => fetchAndRender(typeSelect.value));
            fetchAndRender(typeSelect.value);
        }

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
