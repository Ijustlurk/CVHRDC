<form method="POST" action="{{ route('admin.settings.update', 'announcement') }}">
    @csrf
    <div class="mb-3">
        <label for="general_announcement" class="form-label">General Announcement</label>
        <textarea class="form-control" id="general_announcement" name="general_announcement">{{ $settings->where('key','general_announcement')->first()->value ?? '' }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Announcement</button>
</form>
