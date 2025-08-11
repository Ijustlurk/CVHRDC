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


@isset($events)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary text-white">Add Event</div>
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
				<form method="POST" action="{{ route('admin.events.store') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="title" class="form-label">Title</label>
						<input type="text" name="title" id="title" class="form-control" required placeholder="Enter event title">
						<div class="invalid-feedback">Title is required.</div>
					</div>
					<div class="mb-3">
						<label for="description" class="form-label">Description</label>
						<textarea name="description" id="description" class="form-control" rows="4" required placeholder="Enter event description"></textarea>
						<div class="invalid-feedback">Description is required.</div>
					</div>
					<div class="mb-3">
						<label for="location" class="form-label">Location</label>
						<input type="text" name="location" id="location" class="form-control" required placeholder="Enter location">
						<div class="invalid-feedback">Location is required.</div>
					</div>
					<div class="row g-2">
						<div class="col-6 mb-3">
							<label for="start_date" class="form-label">Start Date</label>
							<input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
							<div class="invalid-feedback">Start date is required.</div>
						</div>
						<div class="col-6 mb-3">
							<label for="end_date" class="form-label">End Date</label>
							<input type="datetime-local" name="end_date" id="end_date" class="form-control">
						</div>
					</div>
					<div class="mb-3">
						<label for="organizer" class="form-label">Organizer</label>
						<input type="text" name="organizer" id="organizer" class="form-control" required placeholder="Enter organizer">
						<div class="invalid-feedback">Organizer is required.</div>
					</div>
					<div class="mb-3">
						<label for="image_url" class="form-label">Image URL</label>
						<input type="url" name="image_url" id="image_url" class="form-control" required placeholder="https://example.com/image.jpg">
						<div class="invalid-feedback">Image URL is required.</div>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1" checked>
						<label class="form-check-label" for="is_visible">Visible</label>
					</div>
					<button type="submit" class="btn btn-primary w-100">Add Event</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($events as $item)
				<div class="col">
					<div class="card h-100">
						<img src="{{ $item->image_url }}" class="card-img-top" alt="Event Image">
						<div class="card-body">
							<h5 class="card-title">{{ $item->title }}</h5>
							<p class="card-text">{{ Str::limit($item->description, 100) }}</p>
							<p class="card-text"><small class="text-muted">{{ $item->start_date ? $item->start_date->format('M d, Y H:i') : '' }}</small></p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<form method="POST" action="{{ route('admin.events.visibility', $item->id) }}" class="me-2">
								@csrf
								<input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
								<button type="submit" class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
									<i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
								</button>
							</form>
							<a href="{{ route('admin.events.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
							<form method="POST" action="{{ route('admin.events.destroy', $item->id) }}" onsubmit="return confirm('Delete this event?');">
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
			{{ $events->links() }}
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
