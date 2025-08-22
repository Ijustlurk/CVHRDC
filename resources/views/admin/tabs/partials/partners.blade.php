@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
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
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="Enter partner name">
                            <div class="invalid-feedback">Name is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="organization" class="form-label">Organization <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="organization" id="organization" class="form-control" required
                                placeholder="Enter organization name">
                            <div class="invalid-feedback">Organization is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" id="contact_email" class="form-control"
                                placeholder="Enter contact email">
                        </div>
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" name="contact_phone" id="contact_phone" class="form-control"
                                placeholder="Enter contact phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" name="website" id="website" class="form-control"
                                placeholder="https://example.com">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Partner</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach ($partners as $item)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text"><strong>Organization:</strong> {{ $item->organization ?? '-' }}</p>
                                @if ($item->contact_email)
                                    <p class="card-text"><strong>Email:</strong> {{ $item->contact_email }}</p>
                                @endif
                                @if ($item->contact_phone)
                                    <p class="card-text"><strong>Phone:</strong> {{ $item->contact_phone }}</p>
                                @endif
                                @if ($item->address)
                                    <p class="card-text"><strong>Address:</strong> {{ Str::limit($item->address, 50) }}</p>
                                @endif
                                @if ($item->website)
                                    <p class="card-text"><strong>Website:</strong> <a href="{{ $item->website }}"
                                            target="_blank"
                                            class="text-decoration-none">{{ Str::limit($item->website, 30) }}</a></p>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.partners.edit', $item->id) }}"
                                    class="btn btn-sm btn-warning me-2"><i class="fa fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.partners.destroy', $item->id) }}"
                                    onsubmit="return confirm('Delete this partner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i></button>
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
