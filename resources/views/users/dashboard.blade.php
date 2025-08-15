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
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Research Projects</span>
                    <!-- Select Dropdown -->
                    <select class="form-select form-select-sm w-auto d-inline-block border-primary text-primary"
                        id="uploadSelect">
                        <option selected hidden>Add New</option>
                        <option value="PublishResearch">Publish Research</option>
                        <option value="Preprint">Preprint</option>
                        <option value="ConferencePaper">Conference Paper</option>
                    </select>

                    <!-- Research Modal -->
                    <div class="modal fade" id="PublishResearch" tabindex="-1" aria-labelledby="publishLabel"
                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" action="{{ route('users.research.store') }}"
                                        method="POST" id="researchForm">
                                        @csrf
                                        <div class="modal-body">
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
                                                <label class="form-label">Authors <span
                                                        class="text-danger">*</span></label>
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
                                                <label class="form-label">Keywords/Tags <small
                                                        class="text-muted">(optional)</small></label>
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
                                                            id="visibility_public" value="1" required>
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
                    <!-- <div class="modal fade" id="Preprint" tabindex="-1" aria-labelledby="publishLabel"
                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" action="{{ route('users.preprint.store')}}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <h5 class="modal-title fw-bold" id="publishLabel">Preprint Form</h5>
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
                                            <div class="mb-3">
                                                <label class="form-label">Authors <span
                                                        class="text-danger">*</span></label>
                                                <div class="border rounded p-2" id="preprint-authors-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2"
                                                        id="preprint-authors-chips"></div>
                                                    <input type="text" id="preprint-authors-input" class="form-control"
                                                        placeholder="Type a name and press Enter">
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
                                            <div class="mb-3">
                                                <label class="form-label">Keywords/Tags <small
                                                        class="text-muted">(optional)</small></label>
                                                <div class="border rounded p-2" id="preprint-keywords-container">
                                                    <div class="d-flex flex-wrap gap-2 mb-2"
                                                        id="preprint-keywords-chips"></div>
                                                    <input type="text" id="preprint-keywords-input" class="form-control"
                                                        placeholder="Type a keyword and press Enter">
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
                                                <span class="spinner-border spinner-border-sm d-none"
                                                    role="status"></span>
                                                <i class="fas fa-save me-1"></i>Create
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                <!-- ===== Script to Trigger Correct Modal ===== -->
                <script>
                    document.getElementById('uploadSelect').addEventListener('change', function () {
                        if (this.value) {
                            var modalId = this.value;
                            var myModal = new bootstrap.Modal(document.getElementById(modalId));
                            myModal.show();
                            this.selectedIndex = 0;
                        }
                    });

                </script>
                <!-- Clickable container -->
                <div class="clickable-container position-relative mb-1 mt-1">
                    <a href="javascript:void(0);" class="stretched-link text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#infoModal">
                        <div class="d-flex align-items-center border rounded-1 px-3 mx-1">
                            <!-- <div class="me-3">
                                <img src="{{ asset('assets/Image/logo.jpg') }}" class="border border-1 border-secondary"
                                    height="40" width="40" alt="" style="border-radius:50%; object-fit:cover;">
                            </div> -->
                            <div class="list-group">
                                <div class="p-1">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="mb-0 text-primary fw-bold">Sample</h6>
                                            </div>
                                            <div class="col-12">
                                                <p class="mb-0 text-muted">
                                                    Lorem ipsum dolor sit amet.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Clickable container -->
                <div class="clickable-container position-relative mb-1">
                    <a href="javascript:void(0);" class="stretched-link text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#infoModal">
                        <div class="d-flex align-items-center border rounded-1 px-3 mx-1">
                            <!-- <div class="me-3">
                                <img src="{{ asset('assets/Image/logo.jpg') }}" class="border border-1 border-secondary"
                                    height="40" width="40" alt="" style="border-radius:50%; object-fit:cover;">
                            </div> -->
                            <div class="list-group">
                                <div class="p-1">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="mb-0 text-primary fw-bold">Sample</h6>
                                            </div>
                                            <div class="col-12">
                                                <p class="mb-0 text-muted">
                                                    Lorem ipsum dolor sit amet.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true"
                    data-bs-backdrop="false">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h5 class="modal-title mb-2 fw-bold" id="infoModalLabel">Sample Details</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus suscipit
                                    libero at felis cursus, eget facilisis velit tincidunt.</p>
                            </div>
                            <div class="d-flex justify-content-end p-3">
                                <button type="button" class="btn btn-danger btn-sm"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="projectsList">
                    @forelse($projects as $project)
                    <div class="border rounded p-2 mb-2 project-item"
                        data-status="{{ strtolower($project->status ?? 'ongoing') }}">
                        <div class="fw-bold">{{ $project->title }}</div>
                        <div>Status: <span
                                class="badge bg-{{ ($project->status ?? 'ongoing') == 'completed' ? 'success' : 'info' }}">{{ ucfirst($project->status ?? 'Ongoing') }}</span>
                        </div>
                        <div>Funding: {{ $project->funding_agency ?? 'N/A' }}</div>
                        <div><small>{{ $project->start_date }} - {{ $project->end_date }}</small></div>
                    </div>
                    @empty
                    <!-- <div class="text-muted">No projects found.</div> -->
                    @endforelse
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
    document.addEventListener('DOMContentLoaded', function () {
        function handleChips(inputId, chipsContainerId, hiddenContainerId, fieldName) {
            const input = document.getElementById(inputId);
            const chipsContainer = document.getElementById(chipsContainerId);
            const hiddenContainer = document.getElementById(hiddenContainerId);

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && input.value.trim() !== '') {
                    e.preventDefault();
                    const value = input.value.trim();

                    // Create chip
                    const chip = document.createElement('span');
                    chip.className = 'badge bg-primary p-2';
                    chip.textContent = value;

                    // Remove chip on click
                    chip.addEventListener('click', () => {
                        chip.remove();
                        hiddenInput.remove();
                    });

                    chipsContainer.appendChild(chip);

                    // Create hidden input for form
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = fieldName + '[]';
                    hiddenInput.value = value;
                    hiddenContainer.appendChild(hiddenInput);

                    input.value = '';
                }
            });
        }

        handleChips('authors-input', 'authors-chips', 'authors-hidden-container', 'authors');
        handleChips('keywords-input', 'keywords-chips', 'keywords-hidden-container', 'keywords');

        // Select to modal trigger
        document.getElementById('uploadSelect').addEventListener('change', function () {
            const modalId = this.value;
            if (modalId) {
                new bootstrap.Modal(document.getElementById(modalId)).show();
            }
        });
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
        document.getElementById('greeting').textContent = getGreeting() +
            ', {{ $user->title ? $user->title . '
        ' : '
        ' }}{{ $user->name }}!';
        // Project filter
        document.getElementById('projectFilter').addEventListener('change', function () {
            const val = this.value;
            document.querySelectorAll('.project-item').forEach(function (item) {
                if (val === 'all' || item.dataset.status === val) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

</script>
@endsection
@endcan
