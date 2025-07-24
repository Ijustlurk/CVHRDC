<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.settings.update', 'header') }}">
            @csrf
            <div class="mb-3">
                <label for="header_title" class="form-label">Header Title</label>
                <input type="text" class="form-control" id="header_title" name="header_title" value="{{ old('header_title', $settings['header_title'] ?? '') }}">
            </div>
            <button type="submit" class="btn btn-primary">Save Header</button>
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
