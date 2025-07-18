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
                    <button type="submit" class="btn btn-primary">Add Publication</button>
                </form>
            </div>
        </div>
<div class="row">
    <div class="col-md-6">
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
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">Publications List</div>
            <div class="card-body p-2">
                <div id="publications-list"></div>
            </div>
        </div>
    </div>
</div>
            document.getElementById('publications-form').reset();
            fetchPublications();
        });
    });
});
function fetchPublications() {
    fetch('/admin/publications')
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                html += `<li class="list-group-item"><strong>${item.title}</strong><br>${item.description}</li>`;
            });
            html += '</ul>';
            document.getElementById('publications-list').innerHTML = html;
        });
}
</script>
