@extends('layouts.Users.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">{{ $research->title }}</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Research Details
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-flask me-2"></i>Research Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Title:</strong>
                            <p class="mt-2">{{ $research->title }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Authors:</strong>
                            <p class="mt-2">
                                @if($research->authors && is_array($research->authors))
                                    {{ implode(', ', $research->authors) }}
                                @else
                                    <span class="text-muted">No authors listed</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Category:</strong>
                            <p class="mt-2">
                                <span class="badge bg-{{ $research->category === 'Terminal' ? 'success' : ($research->category === 'Technological' ? 'info' : 'warning') }}">
                                    {{ $research->category }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Publication Date:</strong>
                            <p class="mt-2">{{ optional($research->published_at)->format('F d, Y') ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Date Created:</strong>
                            <p class="mt-2">{{ $research->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>

                    @if($research->abstract)
                    <div class="mb-3">
                        <strong>Abstract:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($research->abstract)) !!}
                        </div>
                    </div>
                    @endif

                    @if($research->keywords && is_array($research->keywords) && count($research->keywords) > 0)
                    <div class="mb-3">
                        <strong>Keywords/Tags:</strong>
                        <div class="mt-2">
                            @foreach($research->keywords as $keyword)
                                <span class="badge bg-info me-1 mb-1">{{ $keyword }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Research Document:</strong>
                        <div class="mt-2">
                            @if($research->file_path)
                                <a href="{{ Storage::url($research->file_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i>View PDF Document
                                </a>
                                <a href="{{ Storage::url($research->file_path) }}" class="btn btn-sm btn-outline-success ms-2" download>
                                    <i class="fas fa-download me-2"></i>Download PDF
                                </a>
                            @else
                                <span class="text-muted">No document available</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-warning bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-cog me-2"></i>Status & Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Visibility Status:</strong>
                        <p class="mt-2">
                            <span class="badge bg-{{ $research->is_public ? 'success' : 'secondary' }} fs-6">
                                {{ $research->is_public ? 'Public' : 'Private' }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mt-2">{{ $research->updated_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('users.research.edit', $research->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Research
                        </a>

                        <form method="POST" action="{{ route('users.research.visibility', $research->id) }}">
                            @csrf
                            <input type="hidden" name="is_public" value="{{ $research->is_public ? 0 : 1 }}">
                            <button type="submit" class="btn btn-outline-{{ $research->is_public ? 'secondary' : 'success' }} w-100">
                                <i class="fa fa-eye{{ $research->is_public ? '-slash' : '' }} me-2"></i>
                                {{ $research->is_public ? 'Make Private' : 'Make Public' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('users.research.destroy', $research->id) }}" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Delete Research
                            </button>
                        </form>

                        <a href="{{ route('users.research.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            @if($research->file_path)
            <div class="card shadow mt-3">
                <div class="card-header bg-info bg-gradient text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>File Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>File Name:</strong>
                        <p class="mb-0 small text-muted">{{ basename($research->file_path) }}</p>
                    </div>
                    @if(Storage::exists($research->file_path))
                    <div class="mb-2">
                        <strong>File Size:</strong>
                        <p class="mb-0 small text-muted">{{ number_format(Storage::size($research->file_path) / 1024, 2) }} KB</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Research?',
            html: '<p class="mb-0">Are you sure you want to delete "<strong>{{ addslashes($research->title) }}</strong>"? This action will move the research to the deleted items.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Yes, Delete',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'swal-wide',
                confirmButton: 'btn btn-danger btn-lg px-4 me-2',
                cancelButton: 'btn btn-secondary btn-lg px-4'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this research?')) {
            document.getElementById('deleteForm').submit();
        }
    }
}
</script>

<style>
/* Enhanced SweetAlert Styling */
.swal2-popup {
    font-size: 1rem !important;
    padding: 2rem !important;
}

.swal2-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    margin-bottom: 1rem !important;
}

.swal2-html-container {
    font-size: 1rem !important;
    color: #5a6c7d !important;
    margin: 1rem 0 !important;
}

.swal2-actions {
    margin-top: 1.5rem !important;
    gap: 0.5rem !important;
}

.swal2-popup .btn-danger {
    background-color: #dc3545 !important;
    border: 2px solid #dc3545 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3) !important;
}

.swal2-popup .btn-danger:hover {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4) !important;
}

.swal2-popup .btn-secondary {
    background-color: #6c757d !important;
    border: 2px solid #6c757d !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 2rem !important;
    border-radius: 0.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(108, 117, 125, 0.3) !important;
}

.swal2-popup .btn-secondary:hover {
    background-color: #5a6268 !important;
    border-color: #545b62 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(108, 117, 125, 0.4) !important;
}

.swal-wide {
    width: 35rem !important;
    max-width: 90vw !important;
}
</style>
@endsection

