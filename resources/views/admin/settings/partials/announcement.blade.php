<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Announcement Settings</div>
            <div class="card-body">
                <form method="POST" action="/admin/announcement/update">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $announcement->title ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" required>{{ old('content', $announcement->content ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $announcement->start_date ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $announcement->end_date ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <select class="form-select" id="active" name="active" required>
                            <option value="1" {{ ($announcement->active ?? true) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ ($announcement->active ?? true) ? '' : 'selected' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Announcement</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Current Announcement Settings</h4>
            </div>
        </div>
        <style>
            .list-group-item.bg-light {
                transition: background 0.2s, box-shadow 0.2s;
            }
            .list-group-item.bg-light:hover {
                background: #e9ecef;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                cursor: pointer;
            }
        </style>
        <ul class="list-group">
            <li class="list-group-item bg-light mb-2">
                <p><strong>Title:</strong> {{ $announcement->title ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Content:</strong> {{ $announcement->content ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Start Date:</strong> {{ $announcement->start_date ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>End Date:</strong> {{ $announcement->end_date ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Status:</strong> {{ ($announcement->active ?? true) ? 'Active' : 'Inactive' }}</p>
            </li>
        </ul>
        <div class="alert alert-info mt-3">
            <strong>System Info:</strong><br>
            <ul>
                <li><b>Purpose:</b> Announcement settings allow you to manage important messages displayed to users, including scheduling and content.</li>
                <li><b>User Side:</b> Announcements inform users about updates, events, or important notices.</li>
                <li><b>Admin Side:</b> Admins can update, schedule, and activate/deactivate announcements as needed.</li>
                <li><b>Status:</b> You can activate or deactivate announcements for visibility control.</li>
            </ul>
        </div>
    </div>
</div>
