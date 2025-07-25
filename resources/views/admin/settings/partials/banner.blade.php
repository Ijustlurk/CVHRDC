<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-bold">Banner Settings</div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="/admin/banner/update">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $banner->title ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $banner->image ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $banner->link ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <select class="form-select" id="active" name="active" required>
                            <option value="1" {{ old('active', $banner->active ?? true) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('active', $banner->active ?? true) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Banner</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Current Banner Settings</h4>
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
                <p><strong>Title:</strong> {{ $banner->title ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Subtitle:</strong> {{ $banner->subtitle ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Image URL:</strong> {{ $banner->image ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Link:</strong> {{ $banner->link ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Status:</strong> {{ ($banner->active ?? true) ? 'Active' : 'Inactive' }}</p>
            </li>
        </ul>
       
    </div>
</div>

        <div class="alert alert-info mt-3" id="system-info-alert" style="cursor: pointer;">
            <strong>System Info:</strong><br>
             <ul class="system-info-list">
                <li><b>Purpose:</b> Banner settings allow you to manage the main banner displayed on the website, including title, subtitle, image, and link.</li>
                <li><b>User Side:</b> The banner is the first thing users see and can be used for announcements, promotions, or branding.</li>
                <li><b>Admin Side:</b> Admins can update banner details to keep the site fresh and relevant.</li>
                <li><b>Status:</b> You can activate or deactivate the banner as needed.</li>
            </ul>
        </div>