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


@isset($projects)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary text-white">Add Project</div>
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
				<form method="POST" action="{{ route('admin.projects.store') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="title" class="form-label">Title</label>
						<input type="text" name="title" id="title" class="form-control" required placeholder="Enter project title">
						<div class="invalid-feedback">Title is required.</div>
					</div>
					<div class="mb-3">
						<label for="description" class="form-label">Description</label>
						<textarea name="description" id="description" class="form-control" rows="4" required placeholder="Enter project description"></textarea>
						<div class="invalid-feedback">Description is required.</div>
					</div>
					<div class="mb-3">
						<label for="image" class="form-label">Image URL</label>
						<input type="url" name="image" id="image" class="form-control" required placeholder="https://example.com/image.jpg">
						<div class="invalid-feedback">Image URL is required.</div>
					</div>
					<div class="row g-2">
						<div class="col-6 mb-3">
							<label for="start_date" class="form-label">Start Date</label>
							<input type="date" name="start_date" id="start_date" class="form-control" required>
							<div class="invalid-feedback">Start date is required.</div>
						</div>
						<div class="col-6 mb-3">
							<label for="end_date" class="form-label">End Date</label>
							<input type="date" name="end_date" id="end_date" class="form-control">
						</div>
					</div>
					<div class="mb-3">
						<label for="status" class="form-label">Status</label>
						<input type="text" name="status" id="status" class="form-control" required placeholder="Enter status">
						<div class="invalid-feedback">Status is required.</div>
					</div>
					<div class="mb-3">
						<label for="budget" class="form-label">Budget</label>
						<input type="number" name="budget" id="budget" class="form-control" placeholder="Enter budget">
					</div>
					<div class="mb-3">
						<label for="lead_researcher" class="form-label">Lead Researcher</label>
						<input type="text" name="lead_researcher" id="lead_researcher" class="form-control" required placeholder="Enter lead researcher">
						<div class="invalid-feedback">Lead researcher is required.</div>
					</div>
					<div class="mb-3">
						<label for="leader" class="form-label">Leader</label>
						<input type="text" name="leader" id="leader" class="form-control" required placeholder="Enter leader">
						<div class="invalid-feedback">Leader is required.</div>
					</div>
					<div class="mb-3">
						<label for="funding_agency" class="form-label">Funding Agency</label>
						<input type="text" name="funding_agency" id="funding_agency" class="form-control" placeholder="Enter funding agency">
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
						<label class="form-check-label" for="is_active">Active</label>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1" checked>
						<label class="form-check-label" for="is_visible">Visible</label>
					</div>
					<button type="submit" class="btn btn-primary w-100">Add Project</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($projects as $item)
				<div class="col">
					<div class="card h-100">
						<img src="{{ $item->image }}" class="card-img-top" alt="Project Image">
						<div class="card-body">
							<h5 class="card-title">{{ $item->title }}</h5>
							<p class="card-text">{{ Str::limit($item->description, 100) }}</p>
							<p class="card-text"><small class="text-muted">Start: {{ $item->start_date ? $item->start_date->format('M d, Y') : '' }}</small></p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<form method="POST" action="{{ route('admin.projects.visibility', $item->id) }}" class="me-2">
								@csrf
								<input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
								<button type="submit" class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
									<i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
								</button>
							</form>
							<a href="{{ route('admin.projects.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
							<form method="POST" action="{{ route('admin.projects.destroy', $item->id) }}" onsubmit="return confirm('Delete this project?');">
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
			{{ $projects->links() }}
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
