

@isset($announcements)
<div class="row mb-4 g-4">
	<div class="col-12 col-lg-5">
		<div class="card shadow-sm h-100">
			<div class="card-header bg-primary text-white">Add Announcement</div>
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
				<form method="POST" action="{{ route('admin.announcement.update') }}" class="needs-validation" novalidate>
					@csrf
					<div class="mb-3">
						<label for="title" class="form-label">Title</label>
						<input type="text" name="title" id="title" class="form-control" required placeholder="Enter announcement title">
						<div class="invalid-feedback">Title is required.</div>
					</div>
					<div class="mb-3">
						<label for="content" class="form-label">Content</label>
						<textarea name="content" id="content" class="form-control" rows="4" required placeholder="Enter announcement content"></textarea>
						<div class="invalid-feedback">Content is required.</div>
					</div>
					<div class="row g-2">
						<div class="col-6 mb-3">
							<label for="start_date" class="form-label">Start Date</label>
							<input type="date" name="start_date" id="start_date" class="form-control">
									<div class="col-12">
						<div class="col-6 mb-3">
							<label for="end_date" class="form-label">End Date</label>
							<input type="date" name="end_date" id="end_date" class="form-control">
						</div>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="active" id="active" value="1" checked>
						<label class="form-check-label" for="active">Active</label>
					</div>
					<button type="submit" class="btn btn-primary w-100">Add Announcement</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7">
		<div class="row row-cols-1 row-cols-md-2 g-3">
			@foreach($announcements as $item)
                <div class="col-12">
                    <div class="card h-100 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ Str::limit($item->content, 100) }}</p>
                            <p class="card-text"><small class="text-muted">{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('M d, Y') : '' }}</small></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center bg-light">
							<div class="mt-3">
								{{ $announcements->links() }}
							</div>
						</div>
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
                            <a href="#" class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
                            <form method="POST" action="#" onsubmit="return confirm('Delete this announcement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
			@endforeach
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
