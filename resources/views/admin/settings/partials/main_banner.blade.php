<div class="row">
    <div class="col-md-6">
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.settings.update', 'banner') }}">
            @csrf
            <div class="mb-3">
                <label for="banner_text" class="form-label">Main Banner Text</label>
                <input type="text" class="form-control" id="banner_text" name="banner_text" value="{{ old('banner_text', $settings['banner_text'] ?? '') }}">
            </div>
            <button type="submit" class="btn btn-primary">Save Banner</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Banner Settings</div>
            <div class="card-body">
                <strong>Main Banner Text:</strong> {{ $settings['banner_text'] ?? '' }}
            </div>
        </div>
    </div>
</div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Banner Settings</div>
            <div class="card-body">
                <strong>Main Banner Text:</strong> {{ $settings['banner_text'] ?? '' }}
            </div>
        </div>
    </div>
</div>
