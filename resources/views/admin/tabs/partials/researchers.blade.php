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


@isset($researchers)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary text-white">Add Researcher</div>
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
				<form method="POST" action="{{ route('admin.researchers.store') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="name" class="form-label">Name</label>
						<input type="text" name="name" id="name" class="form-control" required placeholder="Enter researcher name">
						<div class="invalid-feedback">Name is required.</div>
					</div>
					<div class="mb-3">
						<label for="expertise" class="form-label">Expertise</label>
						<input type="text" name="expertise" id="expertise" class="form-control" required placeholder="Enter expertise">
						<div class="invalid-feedback">Expertise is required.</div>
					</div>
					<div class="mb-3">
						<label for="email" class="form-label">Email</label>
						<input type="email" name="email" id="email" class="form-control" required placeholder="Enter email">
						<div class="invalid-feedback">Email is required.</div>
					</div>
					<button type="submit" class="btn btn-primary w-100">Add Researcher</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($researchers as $item)
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">{{ $item->name }}</h5>
							<p class="card-text">Expertise: {{ $item->expertise ?? '-' }}</p>
							<p class="card-text">Email: {{ $item->email ?? '-' }}</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<a href="{{ route('admin.researchers.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
							<form method="POST" action="{{ route('admin.researchers.destroy', $item->id) }}" onsubmit="return confirm('Delete this researcher?');">
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
			{{ $researchers->links() }}
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
