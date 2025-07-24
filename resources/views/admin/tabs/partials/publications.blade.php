<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Publication</div>
            <div class="card-body">
                <form id="publications-form">
                    <div class="mb-3">
                        <label for="publication-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="publication-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="publication-description" class="form-label">Description</label>
                        <textarea class="form-control" id="publication-description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="publication-author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="publication-author" name="author">
                    </div>
                    <div class="mb-3">
                        <label for="publication-published-at" class="form-label">Published At</label>
                        <input type="datetime-local" class="form-control" id="publication-published-at" name="published_at">
                    </div>
                    <div class="mb-3">
                        <label for="publication-type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="publication-type" name="type">
                    </div>
                    <div class="mb-3">
                        <label for="publication-file-url" class="form-label">File URL</label>
                        <input type="text" class="form-control" id="publication-file-url" name="file_url">
                    </div>
                    <div class="mb-3">
                        <label for="publication-tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="publication-tags" name="tags">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="publication-visible" name="is_visible" checked>
                        <label class="form-check-label" for="publication-visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Publication</button>
                </form>
            </div>
        </div>

    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Recent Publications</h4>
            </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-publications-btn">View All Publications</button>
        </div>
        <div class="text-muted align-items-right" id="publications-count-info" style="font-size:1rem;"></div>
        <div class="card-body p-2">
            <div id="publications-list"></div>
            <!-- Day.js CDN for human readable dates -->
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
            <script>dayjs.extend(dayjs_plugin_relativeTime);</script>
            <!-- Publications Details Modal -->
            <div class="modal fade" id="publicationsDetailsModal" tabindex="-1" aria-labelledby="publicationsDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="publicationsDetailsModalLabel">Publication Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="publications-details-body">
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
    fetchPublications();
    setInterval(fetchPublications, 5000);
    document.getElementById('publications-form').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('/admin/publications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: document.getElementById('publication-title').value,
                description: document.getElementById('publication-description').value,
                author: document.getElementById('publication-author').value,
                published_at: document.getElementById('publication-published-at').value,
                type: document.getElementById('publication-type').value,
                file_url: document.getElementById('publication-file-url').value,
                tags: document.getElementById('publication-tags').value,
                is_visible: document.getElementById('publication-visible').checked ? 1 : 0
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('publications-form').reset();
            fetchPublications();
        })
        .catch(error => {
            alert('Error adding publication: ' + error.message);
        });
    });
});

function fetchPublications() {
    const publicationsList = document.getElementById('publications-list');
    fetch('/admin/publications', {
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
                <div><strong>Author:</strong> <small>${item.author ?? ''}</small></div>
                <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                <div><strong>Type:</strong> <small>${item.type ?? ''}</small></div>
                <div><strong>File URL:</strong> ${item.file_url ? `<a href="${item.file_url}" target="_blank">View</a>` : 'No file'}</div>
                <div><strong>Tags:</strong> <small>${item.tags ?? ''}</small></div>
                <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                    <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                    <button class="btn btn-sm btn-primary ms-2 view-more-btn"
                        data-id="${item.id}"
                        data-title="${encodeURIComponent(item.title)}"
                        data-description="${encodeURIComponent(item.description)}"
                        data-author="${encodeURIComponent(item.author ?? '')}"
                        data-publishedat="${encodeURIComponent(item.published_at ?? '')}"
                        data-type="${encodeURIComponent(item.type ?? '')}"
                        data-fileurl="${encodeURIComponent(item.file_url ?? '')}"
                        data-tags="${encodeURIComponent(item.tags ?? '')}"
                        data-visible="${item.is_visible}"
                        data-addedat="${encodeURIComponent(item.created_at ?? '')}"
                    >View More</button>
                </div>
            </li>`;
        });
        html += '</ul>';
        publicationsList.innerHTML = html;
        // Add event listeners for view more buttons
        document.querySelectorAll('.view-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // ...modal logic for publication details...
            });
        });
        // Use event delegation for toggle-visibility-btn (with closest)
        publicationsList.addEventListener('click', function(e) {
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
                fetch(`/admin/publications/${id}/visibility`, {
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
        publicationsList.innerHTML = '<div class="text-danger">Error loading publications.</div>';
    });
}
</script>
