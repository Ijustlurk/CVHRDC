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


@isset($partners)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary fw-bold text-white">Add Partner</div>
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
				<form method="POST" action="{{ route('admin.partners.store') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="name" class="form-label">Name</label>
						<input type="text" name="name" id="name" class="form-control" required placeholder="Enter partner name">
						<div class="invalid-feedback">Name is required.</div>
					</div>
					<div class="mb-3">
						<label for="type" class="form-label">Type</label>
						<input type="text" name="type" id="type" class="form-control" required placeholder="Enter partner type">
						<div class="invalid-feedback">Type is required.</div>
					</div>
					<div class="mb-3">
						<label for="contact" class="form-label">Contact</label>
						<input type="text" name="contact" id="contact" class="form-control" placeholder="Enter contact info">
					</div>
					<button type="submit" class="btn btn-primary w-100">Add Partner</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($partners as $item)
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">{{ $item->name }}</h5>
							<p class="card-text">Type: {{ $item->type ?? '-' }}</p>
							<p class="card-text">Contact: {{ $item->contact ?? '-' }}</p>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<a href="{{ route('admin.partners.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
							<form method="POST" action="{{ route('admin.partners.destroy', $item->id) }}" onsubmit="return confirm('Delete this partner?');">
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
			{{ $partners->links() }}
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
