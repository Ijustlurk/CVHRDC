<div class="row">
    <div class="col-md-6">
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.settings.update', 'announcement') }}">
            @csrf
            <div class="mb-3">
                <label for="announcement" class="form-label">General Announcement</label>
                <textarea class="form-control" id="announcement" name="announcement">{{ old('announcement', $settings['announcement'] ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Announcement</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Announcement Settings</div>
            <div class="card-body">
                <strong>Announcement:</strong> {{ $settings['announcement'] ?? '' }}
            </div>
        </div>
    </div>
</div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Announcement Settings</div>
            <div class="card-body">
                <strong>Announcement:</strong> {{ $settings['announcement'] ?? '' }}
            </div>
        </div>
    </div>
</div>
