<div class="row">
    <div class="col-md-6">


        <div class="card mb-3">
            <div class="card-header">Add News</div>
            <div class="card-body">


                <form id="news-form">
                    <div class="mb-3">
                        <label for="news-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="news-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="news-content" class="form-label">Content</label>
                        <textarea class="form-control" id="news-content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="news-image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="news-image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="news-published-at" class="form-label">Published At</label>
                        <input type="datetime-local" class="form-control" id="news-published-at" name="published_at">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="news-visible" name="is_visible" checked>
                        <label class="form-check-label" for="news-visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add News</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">

                   News List
                    <div class="card-body p-2">
                        <div id="news-list"></div>
                    </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchNews();
    document.getElementById('news-form').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('/admin/news', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: document.getElementById('news-title').value,
                content: document.getElementById('news-content').value,
                image: document.getElementById('news-image').value,
                published_at: document.getElementById('news-published-at').value,
                is_visible: document.getElementById('news-visible').checked ? 1 : 0
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('news-form').reset();
            fetchNews();
        });
    });
});

function fetchNews() {
    fetch('/admin/news', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        let html = '<ul class="list-group">';
        data.forEach(item => {
            html += `<li class="list-group-item bg-light"><strong>${item.title}</strong><br>${item.content}<br><small>${item.published_at ?? ''}</small></li>`;
        });
        html += '</ul>';
        document.getElementById('news-list').innerHTML = html;
    });
}
</script>
