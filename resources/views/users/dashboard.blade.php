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
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Research Management</span>
                    <a href="{{ route('users.research.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-flask me-1"></i>Manage Research
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Manage your research outputs, publications, and files in the dedicated Research Management section.
                    </p>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <span><i class="fas fa-flask text-primary me-2"></i>Research Papers</span>
                            <span class="badge bg-primary rounded-pill">Manage</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <span><i class="fas fa-file-alt text-info me-2"></i>Preprints</span>
                            <span class="badge bg-info rounded-pill">Manage</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <span><i class="fas fa-chalkboard text-success me-2"></i>Conference Papers</span>
                            <span class="badge bg-success rounded-pill">Manage</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <span><i class="fas fa-book text-warning me-2"></i>Books</span>
                            <span class="badge bg-warning rounded-pill">Manage</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <span><i class="fas fa-file-signature text-danger me-2"></i>Proposal Submissions</span>
                            <span class="badge bg-danger rounded-pill">Submit</span>
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
@endsection
@endcan
