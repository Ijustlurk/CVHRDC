@if(session('success'))
	<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
	<div class="alert alert-danger">
		<ul class="mb-0">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif


@isset($news)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary text-white">Add News</div>
			<div class="card-body">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul class="mb-0">
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<form method="POST" action="{{ route('admin.news.store') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="title" class="form-label">Title</label>
						<input type="text" name="title" id="title" class="form-control" required placeholder="Enter news title">
						<div class="invalid-feedback">Title is required.</div>
					</div>
					<div class="mb-3">
						<label for="content" class="form-label">Content</label>
						<textarea name="content" id="content" class="form-control" rows="4" required placeholder="Enter news content"></textarea>
						<div class="invalid-feedback">Content is required.</div>
					</div>
					<div class="mb-3">
						<label for="image" class="form-label">Image URL</label>
						<input type="url" name="image" id="image" class="form-control" required placeholder="https://example.com/image.jpg">
						<div class="invalid-feedback">Image URL is required.</div>
					</div>
					<div class="mb-3">
						<label for="published_at" class="form-label">Published At</label>
						<input type="date" name="published_at" id="published_at" class="form-control" required>
						<div class="invalid-feedback">Published date is required.</div>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1" checked>
						<label class="form-check-label" for="is_visible">Visible</label>
					</div>
					<button type="submit" class="btn btn-primary w-100">Add News</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($news as $item)
				<div class="col">
					<div class="card h-100">
						<img src="{{ $item->image }}" class="card-img-top" alt="News Image">
						<div class="card-body">
							<h5 class="card-title">{{ $item->title }}</h5>
							<p class="card-text">{{ Str::limit($item->content, 100) }}</p>
							<p class="card-text"><small class="text-muted">Published: {{ $item->published_at ? $item->published_at->format('M d, Y') : '' }}</small></p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<form method="POST" action="{{ route('admin.news.visibility', $item->id) }}" class="me-2">
								@csrf
								<input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
								<button type="submit" class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
									<i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
								</button>
							</form>
							<a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
							<form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Delete this news?');">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
							</form>
						</div>
					</div>
				</div>
			@endforeach
		</div>
		<div class="mt-3">
			{{ $news->links() }}
		</div>
	</div>
</div>
<script>
// Bootstrap validation
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
	form.addEventListener('submit', event => {
	  if (!form.checkValidity()) {
		event.preventDefault();
		event.stopPropagation();
	  }
	  form.classList.add('was-validated');
	}, false);
  });
})();
</script>
@endisset
