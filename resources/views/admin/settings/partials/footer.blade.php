
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.settings.update', 'footer') }}">
            @csrf
            <div class="mb-3">
                <label for="footer_text" class="form-label">Footer Text</label>
                <textarea class="form-control" id="footer_text" name="footer_text">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Footer</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Footer Settings</div>
            <div class="card-body">
                <strong>Footer Text:</strong> {{ $settings['footer_text'] ?? '' }}
            </div>
        </div>
    </div>
</div>
