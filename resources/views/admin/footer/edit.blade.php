@extends('admin.app')
@section('content')
<div class="container mt-4">
    <h2>Edit Footer</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.footer.update') }}">
        @csrf
        <div class="mb-3">
            <label for="content" class="form-label">Footer Content</label>
            <textarea name="content" id="content" class="form-control" rows="4">{{ old('content', $footer->content ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Footer</button>
    </form>
</div>
@endsection
