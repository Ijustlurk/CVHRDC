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

@php
    
@endphp
            document.getElementById('events-form').reset();
            fetchEvents();
        });
    });
});
function fetchEvents() {
    const eventsList = document.getElementById('events-list');
    fetch('/admin/events', {
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
                <div><strong>Location:</strong> <small>${item.location ?? ''}</small></div>
                <div><strong>Start Date:</strong> <small>${item.start_date ?? ''}</small></div>
                <div><strong>End Date:</strong> <small>${item.end_date ?? ''}</small></div>
                <div><strong>Organizer:</strong> <small>${item.organizer ?? ''}</small></div>
                <div><strong>Added At:</strong> <small>${addedAt}</small></div>
                <div><strong>Image:</strong> ${item.image_url ? `<a href="${item.image_url}" target="_blank">View</a>` : 'No image'}</div>
                <div><strong>Visibility:</strong> <span class="badge ${item.is_visible ? 'bg-success' : 'bg-secondary'}" id="visibility-label-${item.id}">${item.is_visible ? 'Visible' : 'Hidden'}</span>
                    <button class="btn btn-sm btn-outline-${item.is_visible ? 'secondary' : 'success'} ms-2 toggle-visibility-btn" data-id="${item.id}" data-visible="${item.is_visible}">${item.is_visible ? 'Hide' : 'Show'}</button>
                    <button class="btn btn-sm btn-primary ms-2 view-more-btn"
                        data-id="${item.id}"
                        data-title="${encodeURIComponent(item.title)}"
                        data-description="${encodeURIComponent(item.description)}"
                        data-location="${encodeURIComponent(item.location ?? '')}"
                        data-started="${encodeURIComponent(item.start_date ?? '')}"
                        data-ended="${encodeURIComponent(item.end_date ?? '')}"
                        data-organizer="${encodeURIComponent(item.organizer ?? '')}"
                        data-imageurl="${encodeURIComponent(item.image_url ?? '')}"
                        data-visible="${item.is_visible}"
                        data-addedat="${encodeURIComponent(item.created_at ?? '')}"
                    >View More</button>
                </div>
            </li>`;
        });
        html += '</ul>';
        eventsList.innerHTML = html;
        // Add event listeners for view more buttons
        document.querySelectorAll('.view-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // ...modal logic for event details...
            });
        });
        // Use event delegation for toggle-visibility-btn (with closest)
        eventsList.addEventListener('click', function(e) {
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
                fetch(`/admin/events/${id}/visibility`, {
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
        eventsList.innerHTML = '<div class="text-danger">Error loading events.</div>';
    });
}
</script>
