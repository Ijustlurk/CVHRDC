<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Header Settings</div>
            <div class="card-body">
                <form method="POST" action="/admin/header/update">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Website Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $settings->title ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Website Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $settings->subtitle ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo URL</label>
                        <input type="text" class="form-control" id="logo" name="logo" value="{{ old('logo', $settings->logo ?? '') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Header</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Current Header Settings</h4>
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
                <p><strong>Logo URL:</strong> {{ $settings->logo ?? '' }}</p>
            </li>
        </ul>
       
    </div>
</div>


         <div class="alert alert-info mt-3" id="system-info-alert" style="cursor: pointer;">
            <strong>System Info:</strong><br>
            <ul class="system-info-list">
                <li><b>Purpose:</b> Header settings allow you to manage the website's title, subtitle, and logo.</li>
                <li><b>User Side:</b> The header is the first thing users see and represents your site's branding.</li>
                <li><b>Admin Side:</b> Admins can update header details to keep the site fresh and relevant.</li>
            </ul>
        </div>