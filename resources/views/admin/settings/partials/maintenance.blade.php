<form method="POST" action="{{ route('admin.settings.update', 'maintenance') }}">
    @csrf
    <div class="mb-3">
        <label for="maintenance_mode" class="form-label">System Maintenance Mode</label>
        <select class="form-select" id="maintenance_mode" name="maintenance_mode">
            <option value="off" {{ ($settings->where('key','maintenance_mode')->first()->value ?? '') == 'off' ? 'selected' : '' }}>Off</option>
            <option value="on" {{ ($settings->where('key','maintenance_mode')->first()->value ?? '') == 'on' ? 'selected' : '' }}>On</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Maintenance</button>
</form>
