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


@isset($publications)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary text-white">Add Publication</div>
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
				<form method="POST" action="{{ route('admin.publications.store') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="title" class="form-label">Title</label>
						<input type="text" name="title" id="title" class="form-control" required placeholder="Enter publication title">
						<div class="invalid-feedback">Title is required.</div>
					</div>
					<div class="mb-3">
						<label for="description" class="form-label">Description</label>
						<textarea name="description" id="description" class="form-control" rows="4" required placeholder="Enter publication description"></textarea>
						<div class="invalid-feedback">Description is required.</div>
					</div>
					<div class="mb-3">
						<label for="file_url" class="form-label">File URL</label>
						<input type="url" name="file_url" id="file_url" class="form-control" required placeholder="https://example.com/file.pdf">
						<div class="invalid-feedback">File URL is required.</div>
					</div>
					<button type="submit" class="btn btn-primary w-100">Add Publication</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($publications as $item)
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">{{ $item->title }}</h5>
							<p class="card-text">{{ Str::limit($item->description, 100) }}</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<a href="{{ $item->file_url ?? '#' }}" class="btn btn-sm btn-info me-2" target="_blank"><i class="fa fa-file-pdf"></i></a>
							<a href="{{ route('admin.publications.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
							<form method="POST" action="{{ route('admin.publications.destroy', $item->id) }}" onsubmit="return confirm('Delete this publication?');">
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
			{{ $publications->links() }}
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
