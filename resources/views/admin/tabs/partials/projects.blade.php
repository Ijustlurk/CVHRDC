
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Project</div>
            <div class="card-body">
                <form id="projects-form">
                    <div class="mb-3">
                        <label for="project-title" class="form-label">Title</label>
                        <textarea class="form-control" id="project-title" name="title" rows="6" required></textarea>
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
                        <label for="project-started-at" class="form-label">Started At</label>
                        <input type="datetime-local" class="form-control" id="project-started-at" name="started_at">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="project-visible" name="is_visible" checked>
                        <label class="form-check-label" for="project-visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Project</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        Projects List
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

<!-- TinyMCE CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#project-title',
    menubar: false,
    toolbar: 'undo redo | bold italic underline | fontsizeselect',
    height: 250
});
tinymce.init({
    selector: '#project-description',
    menubar: false,
    toolbar: 'undo redo | bold italic underline | bullist numlist | link image | fontsizeselect',
    height: 250
});
document.addEventListener('DOMContentLoaded', function() {
    fetchProjects();
    setInterval(fetchProjects, 5000);
    document.getElementById('projects-form').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('/admin/projects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: tinymce.get('project-title').getContent({format: 'text'}),
                description: tinymce.get('project-description').getContent(),
                image: document.getElementById('project-image').value,
                started_at: document.getElementById('project-started-at').value,
                is_visible: document.getElementById('project-visible').checked ? 1 : 0
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('projects-form').reset();
            fetchProjects();
        })
        .catch(error => {
            alert('Error adding project: ' + error.message);
        });
    });
});

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
            // Assume item.created_at and item.added_by are available from backend
            const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
            html += `<li class="list-group-item bg-light">
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
                const title = decodeURIComponent(this.getAttribute('data-title'));
                const description = decodeURIComponent(this.getAttribute('data-description'));
                const image = decodeURIComponent(this.getAttribute('data-image'));
                const started = decodeURIComponent(this.getAttribute('data-started'));
                const ended = decodeURIComponent(this.getAttribute('data-ended'));
                const status = decodeURIComponent(this.getAttribute('data-status'));
                const budget = decodeURIComponent(this.getAttribute('data-budget'));
                const leadResearcher = decodeURIComponent(this.getAttribute('data-leadresearcher'));
                const isActive = this.getAttribute('data-active') == '1';
                const visible = this.getAttribute('data-visible') == '1';
                const addedAtRaw = decodeURIComponent(this.getAttribute('data-addedat'));
                const addedBy = decodeURIComponent(this.getAttribute('data-addedby'));
                const addedAt = addedAtRaw ? dayjs(addedAtRaw).fromNow() : '';
                let modalHtml = `<div class="mb-2"><strong>Title:</strong> ${title}</div>`;
                modalHtml += `<div class="mb-2 d-none"><strong>Description:</strong><br><div>${description}</div></div>`;
                modalHtml += `<div class="mb-2"><strong>Started At:</strong> <small>${started}</small></div>`;
                modalHtml += `<div class="mb-2"><strong>Ended At:</strong> <small>${ended}</small></div>`;
                modalHtml += `<div class="mb-2"><strong>Status:</strong> <small>${status}</small></div>`;
                modalHtml += `<div class="mb-2 d-none"><strong>Budget:</strong> <small>${budget}</small></div>`;
                modalHtml += `<div class="mb-2 d-none"><strong>Lead Researcher:</strong> <small>${leadResearcher}</small></div>`;
                modalHtml += `<div class="mb-2"><strong>Active:</strong> <span class="badge ${isActive ? 'bg-success' : 'bg-secondary'}">${isActive ? 'Active' : 'Inactive'}</span></div>`;
                modalHtml += `<div class="mb-2"><strong>Visibility:</strong> <span class="badge ${visible ? 'bg-success' : 'bg-secondary'}">${visible ? 'Visible' : 'Hidden'}</span></div>`;
                modalHtml += `<div class="mb-2"><strong>Added At:</strong> <small>${addedAt}</small></div>`;
                modalHtml += `<div class="mb-2"><strong>Added By:</strong> <small>${addedBy}</small></div>`;
                modalHtml += `<div class="mb-2"><strong>Image:</strong> ${image ? `<a href="${image}" target="_blank">View</a>` : 'No image'}</div>`;
                document.getElementById('project-details-body').innerHTML = modalHtml;
                // Bootstrap 5 modal logic fallback
                var modalEl = document.getElementById('projectDetailsModal');
                if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                } else {
                    // fallback for missing bootstrap js
                    modalEl.style.display = 'block';
                    modalEl.classList.add('show');
                    // Add close button logic for fallback
                    var closeBtn = modalEl.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.onclick = function() {
                            modalEl.style.display = 'none';
                            modalEl.classList.remove('show');
                        };
                    }
                }
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
