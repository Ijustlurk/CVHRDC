<div class="row">
    <div class="col-md-6">

                <div class="card mb-3">
                    <div class="card-header">Add News</div>
                    <div class="card-body">

                       <form action="{{ url('/admin/news') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="news-title" class="form-label">Title</label>
                                <textarea class="form-control" id="news-title" name="title" rows="2" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="news-content" class="form-label">Content</label>
                                <textarea class="form-control" id="news-content" name="content" rows="6" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="news-image" class="form-label">Image URL (Google Drive link)</label>
                                <input type="text" class="form-control" id="news-image" name="image">
                            </div>

                            <div class="mb-3">
                                <label for="news-published-at" class="form-label">Published At</label>
                                <input type="datetime-local" class="form-control" id="news-published-at" name="published_at">
                            </div>

                            <div class="mb-3">
                                <label for="news-visible" class="form-label">Visibility</label>
                                <select class="form-select" id="news-visible" name="is_visible">
                                    <option value="1" selected>Visible</option>
                                    <option value="0">Hidden</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Add News</button>
                        </form>


                    </div>
                </div>


</div>
<div class="col-md-6">


        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Recent News</h4>
            </div>
            <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-news-btn">View All News</button>

        </div>
        <div class="text-muted align-items-right" id="news-count-info" style="font-size:1rem;"></div>

            <div class="card-body p-2">
                <div id="news-list"></div>
                    <!-- Day.js CDN for human readable dates -->
                    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>

                    <script>dayjs.extend(dayjs_plugin_relativeTime);</script>

                    <!-- News Details Modal -->
                    <div class="modal fade" id="newsDetailsModal" tabindex="-1" aria-labelledby="newsDetailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newsDetailsModalLabel">News Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="news-details-body">
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
        fetchNews();
        setInterval(fetchNews, 5000);

            document.getElementById('view-all-news-btn').addEventListener('click', function(e) {
                e.preventDefault();
                fetchAllNewsModal();
            });
    });


    function fetchAllNewsModal() {
        // view all news button here
        fetch('/admin/news?all=1', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Backend already returns news in descending order by id
            let html = '<h5>All News</h5>';
            html += '<ul class="list-group">';
            data.forEach(item => {
                const addedAt = item.created_at ? dayjs(item.created_at).fromNow() : '';
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div><strong>Content:</strong> ${item.content}</div>
                    <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank">View</a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}">${item.is_visible ? 'Visible' : 'Hidden'}</span></div>
                </li>`;
            });
            html += '</ul>';
            // Show in modal
            document.getElementById('news-details-body').innerHTML = html;
            var modalEl = document.getElementById('newsDetailsModal');
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
            document.getElementById('news-details-body').innerHTML = '<div class="text-danger">Error loading all news.</div>';
            var modalEl = document.getElementById('newsDetailsModal');
            if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
            }
        });
    }





    function fetchNews() {
        const newsList = document.getElementById('news-list');
        fetch('/admin/news', {
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
                html += `<li class="list-group-item bg-light mb-2">
                    <div><strong>Title:</strong> ${item.title}</div>
                    <div class='d-none'><strong>Content:</strong> ${item.content}</div>
                    <div><strong>Published At:</strong> <small>${item.published_at ?? ''}</small></div>
                    <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                    <div><strong>Added By:</strong> <small>${item.added_by ?? 'Unknown'}</small></div>
                    <div><strong>Image:</strong> ${item.image ? `<a href="${item.image}" target="_blank">View</a>` : 'No image'}</div>
                    <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                        <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                        <button class="btn btn-sm btn-primary ms-2 view-more-btn" data-id="${item.id}" data-title="${encodeURIComponent(item.title)}" data-content="${encodeURIComponent(item.content)}" data-image="${encodeURIComponent(item.image ?? '')}" data-published="${encodeURIComponent(item.published_at ?? '')}" data-visible="${item.is_visible}" data-addedat="${encodeURIComponent(item.created_at ?? '')}" data-addedby="${encodeURIComponent(item.added_by ?? 'Unknown')}">View More</button>
                    </div>
                </li>`;
            });
            html += '</ul>';
            newsList.innerHTML = html;
            // Update count info
            const countInfo = document.getElementById('news-count-info');
            if (countInfo) {
                countInfo.textContent = `Total: ${data.length}, Displayed: ${data.length}`;
            }


            // ...existing code for event listeners...
            document.querySelectorAll('.view-more-btn').forEach(function(btn) {

                btn.addEventListener('click', function() {
                    const title = decodeURIComponent(this.getAttribute('data-title'));
                    const content = decodeURIComponent(this.getAttribute('data-content'));
                    const image = decodeURIComponent(this.getAttribute('data-image'));
                    const published = decodeURIComponent(this.getAttribute('data-published'));
                    const visible = this.getAttribute('data-visible') == '1';
                    const addedAtRaw = decodeURIComponent(this.getAttribute('data-addedat'));
                    const addedBy = decodeURIComponent(this.getAttribute('data-addedby'));
                    const addedAt = addedAtRaw ? dayjs(addedAtRaw).fromNow() : '';
                    let modalHtml = `<div class="mb-2"><strong>Title:</strong> ${title}</div>`;
                    modalHtml += `<div class="mb-2"><strong>Content:</strong><br><div>${content}</div></div>`;
                    modalHtml += `<div class="mb-2"><strong>Published At:</strong> <small>${published}</small></div>`;
                    modalHtml += `<div class="mb-2"><strong>Added At:</strong> <small>${addedAt}</small></div>`;
                    modalHtml += `<div class="mb-2"><strong>Added By:</strong> <small>${addedBy}</small></div>`;
                    modalHtml += `<div class="mb-2"><strong>Image:</strong> ${image ? `<a href="${image}" target="_blank">View</a>` : 'No image'}</div>`;
                    modalHtml += `<div class="mb-2"><strong>Visibility:</strong> <span class="badge ${visible ? 'bg-success' : 'bg-secondary'}">${visible ? 'Visible' : 'Hidden'}</span></div>`;
                    document.getElementById('news-details-body').innerHTML = modalHtml;
                    // Bootstrap 5 modal logic fallback
                    var modalEl = document.getElementById('newsDetailsModal');
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
            newsList.addEventListener('click', function(e) {
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
                    fetch(`/admin/news/${id}/visibility`, {
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
            newsList.innerHTML = '<div class="text-danger">Error loading news.</div>';
        });
    }
</script>
