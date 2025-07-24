
<div class="row">
    <div class="col-md-6">
 
        <form method="POST" action="{{ route('admin.settings.update', 'banner') }}">
        @csrf
        <div class="mb-3">
            <label for="main_banner" class="form-label">Main Banner Text</label>
            <input type="text" class="form-control" id="main_banner" name="main_banner" value="{{ $settings->where('key','main_banner')->first()->value ?? '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Save Banner</button>
    </form>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Current Header Settings</div>
            <div class="card-body">
                <strong>Header Title:</strong> {{ $settings['header_title'] ?? '' }}
            </div>
        </div>
    </div>
</div>

