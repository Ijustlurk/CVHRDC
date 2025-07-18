<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Add Event</div>
            <div class="card-body">
                <form id="events-form">
                    <div class="mb-3">
                        <label for="event-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="event-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-description" class="form-label">Description</label>
                        <textarea class="form-control" id="event-description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>
<div class="row">
    <div class="col-md-6">
        <form id="events-form">
            <div class="mb-3">
                <label for="event-title" class="form-label">Title</label>
                <input type="text" class="form-control" id="event-title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="event-description" class="form-label">Description</label>
                <textarea class="form-control" id="event-description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="event-location" class="form-label">Location</label>
                <input type="text" class="form-control" id="event-location" name="location">
            </div>
            <div class="mb-3">
                <label for="event-start-date" class="form-label">Start Date</label>
                <input type="datetime-local" class="form-control" id="event-start-date" name="start_date">
            </div>
            <div class="mb-3">
                <label for="event-end-date" class="form-label">End Date</label>
                <input type="datetime-local" class="form-control" id="event-end-date" name="end_date">
            </div>
            <div class="mb-3">
                <label for="event-organizer" class="form-label">Organizer</label>
                <input type="text" class="form-control" id="event-organizer" name="organizer">
            </div>
            <div class="mb-3">
                <label for="event-image-url" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="event-image-url" name="image_url">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="event-visible" name="is_visible" checked>
                <label class="form-check-label" for="event-visible">Visible</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card bg-light mb-3">
            <div class="card-header">Events List</div>
            <div class="card-body p-2">
                <div id="events-list"></div>
            </div>
        </div>
    </div>
</div>
            document.getElementById('events-form').reset();
            fetchEvents();
        });
    });
});
function fetchEvents() {
    fetch('/admin/events')
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="list-group">';
            data.forEach(item => {
                html += `<li class="list-group-item"><strong>${item.title}</strong><br>${item.description}</li>`;
            });
            html += '</ul>';
            document.getElementById('events-list').innerHTML = html;
        });
}
</script>
