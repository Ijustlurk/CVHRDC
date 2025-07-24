
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Project</div>
            <div class="card-body">

                <form action="{{ url('/admin/projects') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="project-title" class="form-label">Title</label>
                        <textarea class="form-control" id="project-title" name="title" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="project-description" class="form-label">Description</label>
                        <textarea class="form-control" id="project-description" name="description" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="project-image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="project-image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="project-start-date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="project-start-date" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="project-end-date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="project-end-date" name="end_date">
                    </div>
                    <div class="mb-3">
                        <label for="project-status" class="form-label">Status</label>
                        <select class="form-select" id="project-status" name="status">
                            <option value="On-going">On-going</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="project-budget" class="form-label">Budget</label>
                        <input type="number" step="0.01" class="form-control" id="project-budget" name="budget">
                    </div>
                    <div class="mb-3">
                        <label for="project-lead-researcher" class="form-label">Lead Researcher</label>
                        <input type="text" class="form-control" id="project-lead-researcher" name="lead_researcher">
                    </div>
                    <div class="mb-3">
                        <label for="project-leader" class="form-label">Leader</label>
                        <input type="text" class="form-control" id="project-leader" name="leader">
                    </div>
                    <div class="mb-3">
                        <label for="project-funding-agency" class="form-label">Funding Agency</label>
                        <input type="text" class="form-control" id="project-funding-agency" name="funding_agency">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="project-active" name="is_active" checked>
                        <label class="form-check-label" for="project-active">Active</label>
                    </div>
                     <div class="mb-3">
                                <label for="news-visible" class="form-label">Visibility</label>
                                <select class="form-select" id="news-visible" name="is_visible">
                                    <option value="1" selected>Visible</option>
                                    <option value="0">Hidden</option>
                                </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Project</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Recent Projects</h4>
            </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-projects-btn">View All Projects</button>
        </div>
        <div class="text-muted align-items-right" id="projects-count-info" style="font-size:1rem;"></div>
        <div class="card-body p-2">
            <div id="projects-list"></div>
            <!-- Day.js CDN for human readable dates -->
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
            <script>dayjs.extend(dayjs_plugin_relativeTime);</script>
            <!-- Project Details Modal -->
            <div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="projectDetailsModalLabel">Project Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="project-details-body">
                    <!-- Details will be loaded here -->
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchProjects();
        setInterval(fetchProjects, 5000);

        document.getElementById('view-all-projects-btn').addEventListener('click', function(e) {
            e.preventDefault();
            fetchAllProjectsModal();
        });
    });

    function fetchAllProjectsModal() {
        fetch('/admin/projects?all=1', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Backend already returns projects in descending order by id
            let html = '<h5>All Projects</h5>';
            html += '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div><strong>Description:</strong> ${item.description}</div>
                    <div><strong>Started At:</strong> <small>${item.start_date ?? ''}</small></div>
                    <div><strong>Ended At:</strong> <small>${item.end_date ?? ''}</small></div>
                    <div><strong>Status:</strong> <small>${item.status ?? ''}</small></div>
                    <div><strong>Budget:</strong> <small>${item.budget ?? ''}</small></div>
                    <div><strong>Lead Researcher:</strong> <small>${item.lead_researcher ?? ''}</small></div>
                    <div><strong>Active:</strong> <span class="badge ${item.is_active ? 'bg-success' : 'bg-secondary'}">${item.is_active ? 'Active' : 'Inactive'}</span></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank">View</a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}">${item.is_visible ? 'Visible' : 'Hidden'}</span></div>
                </li>`;
            });
            html += '</ul>';
            // Show in modal
            document.getElementById('project-details-body').innerHTML = html;
            var modalEl = document.getElementById('projectDetailsModal');
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
                var closeBtn = modalEl.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.onclick = function() {
                        modalEl.style.display = 'none';
                        modalEl.classList.remove('show');
                    };
                }
            }
        })
        .catch(() => {
            document.getElementById('project-details-body').innerHTML = '<div class="text-danger">Error loading all projects.</div>';
            var modalEl = document.getElementById('projectDetailsModal');
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
            }
        });
    }

    function fetchProjects() {
        const projectsList = document.getElementById('projects-list');
        fetch('/admin/projects', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div class='d-none'><strong>Description:</strong> ${item.description}</div>
                    <div><strong>Started At:</strong> <small>${item.start_date ?? ''}</small></div>
                    <div><strong>Ended At:</strong> <small>${item.end_date ?? ''}</small></div>
                    <div><strong>Status:</strong> <small>${item.status ?? ''}</small></div>
                    <div class="d-none"><strong>Budget:</strong> <small>${item.budget ?? ''}</small></div>
                    <div class="d-none"><strong>Lead Researcher:</strong> <small>${item.lead_researcher ?? ''}</small></div>
                    <div><strong>Active:</strong> <span class="badge ${item.is_active ? 'bg-success' : 'bg-secondary'}">${item.is_active ? 'Active' : 'Inactive'}</span></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank">View</a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                        <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                        <button class="btn btn-sm btn-primary ms-2 view-more-btn"
                            data-id="${item.id}"
                            data-title="${encodeURIComponent(item.title)}"
                            data-description="${encodeURIComponent(item.description)}"
                            data-image="${encodeURIComponent(item.image ?? '')}"
                            data-started="${encodeURIComponent(item.start_date ?? '')}"
                            data-ended="${encodeURIComponent(item.end_date ?? '')}"
                            data-status="${encodeURIComponent(item.status ?? '')}"
                            data-budget="${encodeURIComponent(item.budget ?? '')}"
                            data-leadresearcher="${encodeURIComponent(item.lead_researcher ?? '')}"
                            data-active="${item.is_active}"
                            data-visible="${item.is_visible}"
                            data-addedat="${encodeURIComponent(item.created_at ?? '')}"
                            data-addedby="${encodeURIComponent(item.added_by ?? 'Unknown')}"
                        >View More</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            projectsList.innerHTML = html;
            // Add event listeners for view more buttons
            document.querySelectorAll('.view-more-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // ...existing code for modal...
                });
            });
            // Use event delegation for toggle-visibility-btn (with closest)
            projectsList.addEventListener('click', function(e) {
                let btn = e.target;
                if (!btn.classList.contains('toggle-visibility-btn')) {
                    btn = btn.closest('.toggle-visibility-btn');
                }
                if (btn && btn.classList.contains('toggle-visibility-btn')) {
                    const id = btn.getAttribute('data-id');
                    if (!id) return;
                    const currentVisible = btn.getAttribute('data-visible') == '1';
                    const newVisible = currentVisible ? 0 : 1;
                    btn.disabled = true;
                    fetch(`/admin/projects/${id}/visibility`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ is_visible: newVisible })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Update button and badge immediately
                        const badge = document.getElementById('visibility-label-' + id);
                        if (badge) {
                            badge.textContent = newVisible ? 'Visible' : 'Hidden';
                            badge.className = 'badge ' + (newVisible ? 'bg-success' : 'bg-secondary');
                        }
                        btn.textContent = newVisible ? 'Hide' : 'Show';
                        btn.className = 'btn btn-sm btn-outline-' + (newVisible ? 'secondary' : 'success') + ' ms-2 toggle-visibility-btn';
                        btn.setAttribute('data-visible', newVisible);
                        btn.disabled = false;
                    })
                    .catch(error => {
                        alert('Error updating visibility: ' + error.message);
                        btn.disabled = false;
                    });
                }
            });
        })
        .catch(() => {
            projectsList.innerHTML = '<div class="text-danger">Error loading projects.</div>';
        });
    }
</script>
