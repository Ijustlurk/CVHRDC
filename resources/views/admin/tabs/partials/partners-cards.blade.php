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
            <p class="card-text"><strong>Website:</strong> <a href="{{ $item->website }}" target="_blank"
                    class="text-decoration-none">{{ Str::limit($item->website, 30) }}</a></p>
            @endif
            <div class="mt-2">
                <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6 d-flex justify-content-start">
                    <form method="POST" action="{{ route('admin.partners.visibility', $item->id) }}"
                        class="me-2">
                        @csrf
                        <input type="hidden" name="is_active" value="{{ $item->is_active ? 0 : 1 }}">
                        <button type="submit"
                            class="btn btn-sm btn-outline-{{ $item->is_active ? 'success' : 'secondary' }}"
                            title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fa fa-eye mt-1{{ $item->is_active ? '' : '-slash' }}"></i>
                        </button>
                    </form>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-warning me-2"
                        onclick="openEditPartnerModal({{ $item->id }})"><i class="fa fa-edit"></i></button>
                    <!-- Delete Button that triggers Modal -->
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deletePartnerModal{{ $item->id }}">
                        <i class="fa fa-trash mt-1"></i>
                    </button>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deletePartnerModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="deletePartnerModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="deletePartnerModalLabel{{ $item->id }}">
                                        Confirm
                                        Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    Are you sure you want to delete this partner?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>

                                    <form method="POST"
                                        action="{{ route('admin.partners.destroy', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@if ($partners->isEmpty())
<div class="col-12">
    <div class="alert alert-info text-center">
        <i class="fa fa-info-circle me-2"></i>No partners found matching your criteria.
    </div>
</div>
@endif

