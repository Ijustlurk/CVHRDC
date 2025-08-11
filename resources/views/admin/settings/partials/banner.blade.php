<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-bold bg-primary text-white">Banner Settings</div>
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
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $settings->title ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $settings->subtitle ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $settings->image ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $settings->link ?? '') }}">
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
                <p><strong>Title:</strong> {{ $settings->title ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Subtitle:</strong> {{ $settings->subtitle ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Image URL:</strong> {{ $settings->image ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Link:</strong> {{ $settings->link ?? '' }}</p>
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