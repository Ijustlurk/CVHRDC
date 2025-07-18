<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Project</div>
            <div class="card-body">
                <form id="projects-form">
                    <div class="mb-3">
                        <label for="project-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="project-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="project-description" class="form-label">Description</label>
                        <textarea class="form-control" id="project-description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Project</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Projects List</div>
            <div class="card-body">
                <div id="projects-list"></div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchProjects();
    document.getElementById('projects-form').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('/admin/projects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: document.getElementById('project-title').value,
                description: document.getElementById('project-description').value
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('projects-form').reset();
            fetchProjects();
        });
    });
});
function fetchProjects() {
    fetch('/admin/projects')
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                html += `<li class="list-group-item"><strong>${item.title}</strong><br>${item.description}</li>`;
            });
            html += '</ul>';
            document.getElementById('projects-list').innerHTML = html;
        });
}
</script>
