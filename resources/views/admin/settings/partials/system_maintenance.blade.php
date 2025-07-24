<div class="row">
    <div class="col-md-6">
<div class="row">
    <div class="col-md-6">
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.settings.update', 'system_maintenance') }}">
            @csrf
            <div class="mb-3">
                <label for="maintenance_mode" class="form-label">Enable Maintenance Mode</label>
                <select class="form-control" id="maintenance_mode" name="maintenance_mode">
                    <option value="0" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? 0) == 0) ? 'selected' : '' }}>Disabled</option>
                    <option value="1" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? 0) == 1) ? 'selected' : '' }}>Enabled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Maintenance Setting</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Maintenance Settings</div>
            <div class="card-body">
                <strong>Maintenance Mode:</strong> {{ ($settings['maintenance_mode'] ?? 0) ? 'Enabled' : 'Disabled' }}
            </div>
        </div>
    </div>
</div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Maintenance Settings</div>
            <div class="card-body">
                <strong>Maintenance Mode:</strong> {{ ($settings['maintenance_mode'] ?? 0) ? 'Enabled' : 'Disabled' }}
            </div>
        </div>
    </div>
</div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Maintenance Settings</div>
            <div class="card-body">
                <strong>Maintenance Mode:</strong> {{ ($settings['maintenance_mode'] ?? 0) ? 'Enabled' : 'Disabled' }}
            </div>
        </div>
    </div>
</div>
