<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Partner</div>
            <div class="card-body">
                <form id="partners-form">
                    <div class="mb-3">
                        <label for="partner-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="partner-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="partner-organization" class="form-label">Organization</label>
                        <input type="text" class="form-control" id="partner-organization" name="organization" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Partner</button>
                </form>
            </div>
        </div>
<div class="row">
    <div class="col-md-6">
        <form id="partners-form">
            <div class="mb-3">
                <label for="partner-name" class="form-label">Name</label>
                <input type="text" class="form-control" id="partner-name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="partner-organization" class="form-label">Organization</label>
                <input type="text" class="form-control" id="partner-organization" name="organization" required>
            </div>
            <div class="mb-3">
                <label for="partner-email" class="form-label">Contact Email</label>
                <input type="email" class="form-control" id="partner-email" name="contact_email">
            </div>
            <div class="mb-3">
                <label for="partner-phone" class="form-label">Contact Phone</label>
                <input type="text" class="form-control" id="partner-phone" name="contact_phone">
            </div>
            <div class="mb-3">
                <label for="partner-address" class="form-label">Address</label>
                <input type="text" class="form-control" id="partner-address" name="address">
            </div>
            <div class="mb-3">
                <label for="partner-website" class="form-label">Website</label>
                <input type="text" class="form-control" id="partner-website" name="website">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="partner-active" name="is_active" checked>
                <label class="form-check-label" for="partner-active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Partner</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">Partners List</div>
            <div class="card-body p-2">
                <div id="partners-list"></div>
            </div>
        </div>
    </div>
</div>
            document.getElementById('partners-form').reset();
            fetchPartners();
        });
    });
});
function fetchPartners() {
    fetch('/admin/partners')
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                html += `<li class="list-group-item"><strong>${item.name}</strong><br>${item.organization}</li>`;
            });
            html += '</ul>';
            document.getElementById('partners-list').innerHTML = html;
        });
}
</script>
