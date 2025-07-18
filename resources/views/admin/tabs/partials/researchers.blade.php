<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Researcher</div>
            <div class="card-body">
                <form id="researchers-form">
                    <div class="mb-3">
                        <label for="researcher-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="researcher-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="researcher-field" class="form-label">Field</label>
                        <input type="text" class="form-control" id="researcher-field" name="field" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Researcher</button>
                </form>
            </div>
        </div>
<div class="row">
    <div class="col-md-6">
        <form id="researchers-form">
            <div class="mb-3">
                <label for="researcher-name" class="form-label">Name</label>
                <input type="text" class="form-control" id="researcher-name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="researcher-field" class="form-label">Field</label>
                <input type="text" class="form-control" id="researcher-field" name="field" required>
            </div>
            <div class="mb-3">
                <label for="researcher-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="researcher-email" name="email">
            </div>
            <div class="mb-3">
                <label for="researcher-phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="researcher-phone" name="phone">
            </div>
            <div class="mb-3">
                <label for="researcher-affiliation" class="form-label">Affiliation</label>
                <input type="text" class="form-control" id="researcher-affiliation" name="affiliation">
            </div>
            <div class="mb-3">
                <label for="researcher-bio" class="form-label">Bio</label>
                <textarea class="form-control" id="researcher-bio" name="bio" rows="2"></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="researcher-active" name="is_active" checked>
                <label class="form-check-label" for="researcher-active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Researcher</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">Researchers List</div>
            <div class="card-body p-2">
                <div id="researchers-list"></div>
            </div>
        </div>
    </div>
</div>
            document.getElementById('researchers-form').reset();
            fetchResearchers();
        });
    });
});
function fetchResearchers() {
    fetch('/admin/researchers')
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                html += `<li class="list-group-item"><strong>${item.name}</strong><br>${item.field}</li>`;
            });
            html += '</ul>';
            document.getElementById('researchers-list').innerHTML = html;
        });
}
</script>
