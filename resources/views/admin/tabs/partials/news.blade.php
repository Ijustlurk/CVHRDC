@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'News Uploaded'
        });
    });

</script>
@endif

<!-- Success Delete -->
@if (session('successdelete'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-success-delete',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'News Deleted'
        });
    });

</script>
@endif

<!-- Success Update -->
@if (session('successupdate'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-success-update',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'News Updated'
        });
    });

</script>
@endif

<!-- Success Visibility -->
@if (session('successvisibility'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast-success-visibility',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'success',
            title: 'News Visibility Changed!'
        });
    });

</script>
@endif


@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({
            icon: 'error',
            title: 'Validation Error!'
        });
    });

</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Validation Errors',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
            background: '#f8d7da', // light red background
            color: '#842029' // dark red text
        });
    });

</script>
@endif

@isset($news)
<div class="row mb-4 g-4">
    <div class="col-12 col-lg-5">
        <div class="card shadow h-100">
            <div class="card-header bg-primary fw-bold text-white">Add News</div>
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
                <!-- Success/Error Messages -->
                <div id="editMessageContainer" class="mb-3" style="display: none;">
                    <div id="editMessage" class="alert" role="alert"></div>
                </div>
                <form method="POST" action="{{ route('admin.news.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required
                            placeholder="Enter news title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="4" required
                            placeholder="Enter news content"></textarea>
                        <div class="invalid-feedback">Content is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="news-image" class="form-label">
                            Image URL <span class="text-danger">*</span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="news-image" name="image" required
                            placeholder="https://drive.google.com/file/d/.../view">
                        <div class="form-text">
                            <strong>Google Drive Instructions:</strong><br>
                            1. Right-click your image in Google Drive → "Get link"<br>
                            2. Set to "Anyone with the link" can view<br>
                            3. Copy and paste the sharing link here<br>
                            <small class="text-muted">The system will automatically convert it to display
                                format.</small>
                        </div>
                        <div class="invalid-feedback" id="image-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="published_at" class="form-label">Published At</label>
                        <input type="date" name="published_at" id="published_at" class="form-control" required>
                        <div class="invalid-feedback">Published date is required.</div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1"
                            checked>
                        <label class="form-check-label" for="is_visible">Visible</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add News</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="row row-cols-1 row-cols-md-2 g-3">
            @foreach ($news as $item)
            <div class="col">
                <div class="card h-100">
                    @php
                    $imageUrl = $item->image;
                    // Convert Google Drive URL to direct image URL
                    if (strpos($imageUrl, 'drive.google.com') !== false) {
                    // Extract file ID from Google Drive URL
                    preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $imageUrl, $matches);
                    if (!empty($matches[1])) {
                    $fileId = $matches[1];
                    $imageUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
                    } else {
                    // Try alternative format: ?id=FILE_ID
                    preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $imageUrl, $matches);
                    if (!empty($matches[1])) {
                    $fileId = $matches[1];
                    $imageUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=s4000";
                    }
                    }
                    }
                    @endphp
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="News Image"
                        onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text">{{ Str::limit($item->content, 100) }}</p>

                        <!-- Published date pushed to bottom -->
                        <p class="card-text mt-auto">
                            <small class="text-muted">
                                Published: {{ $item->published_at ? $item->published_at->format('M d, Y') : '' }}
                            </small>
                        </p>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <form method="POST" action="{{ route('admin.news.visibility', $item->id) }}"
                                    class="me-2">
                                    @csrf
                                    <input type="hidden" name="is_visible" value="{{ $item->is_visible ? 0 : 1 }}">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-{{ $item->is_visible ? 'success' : 'secondary' }}">
                                        <i class="fa fa-eye{{ $item->is_visible ? '' : '-slash' }}"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-warning me-2"
                                    onclick="openEditModal({{ $item->id }})"><i class="fa fa-edit fa-lg"></i></button>

                                <!-- Delete Button -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $item->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                                            <i class="fa fa-exclamation-triangle me-2"></i> Confirm Deletion
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        Are you sure you want to delete this news item? <br>
                                        <strong>{{ $item->title }}</strong>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>

                                        <!-- Actual Delete Form -->
                                        <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}">
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
            @endforeach
        </div>
        <div class="mt-3">
            {{ $news->links() }}
        </div>
    </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editNewsModalLabel">
                    <i class="fa fa-edit me-2"></i>Edit News
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form id="editNewsForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_news_id" name="news_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold">Title <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required
                            placeholder="Enter news title">
                        <div class="invalid-feedback">Title is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_content" class="form-label fw-bold">Content <span
                                class="text-danger">*</span></label>
                        <textarea name="content" id="edit_content" class="form-control" rows="4" required
                            placeholder="Enter news content"></textarea>
                        <div class="invalid-feedback">Content is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">
                            <span class="fw-bold">Image URL <span class="text-danger">*</span></span>
                            <small class="text-muted">(Google Drive link)</small>
                        </label>
                        <input type="url" class="form-control" id="edit_image" name="image" required
                            placeholder="https://drive.google.com/file/d/.../view">
                        <div class="form-text">
                            <strong>Google Drive Instructions:</strong><br>
                            1. Right-click your image in Google Drive → "Get link"<br>
                            2. Set to "Anyone with the link" can view<br>
                            3. Copy and paste the sharing link here<br>
                            <small class="text-muted">The system will automatically convert it to display
                                format.</small>
                        </div>
                        <div class="invalid-feedback" id="edit-image-error"></div>

                        <!-- Current Image Preview -->
                        <div class="mt-2" id="currentImagePreview" style="display: none;">
                            <label class="form-label fw-bold">Current Image:</label>
                            <div class="border rounded p-2">
                                <img id="previewImage" src="" alt="Current Image" class="img-fluid"
                                    style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_published_at" class="form-label fw-bold">Published At <span
                                class="text-danger">*</span></label>
                        <input type="date" name="published_at" id="edit_published_at" class="form-control" required>
                        <div class="invalid-feedback">Published date is required.</div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="edit_is_visible"
                            value="1">
                        <label class="form-check-label fw-bold" for="edit_is_visible">Visible</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="updateNewsBtn">
                    <i class="fa fa-save me-2"></i>Update News
                </button>
            </div>
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

    // Edit News Modal Functions
    function openEditModal(newsId) {
        console.log('Opening edit modal for news ID:', newsId);

        // Hide any previous messages
        hideEditMessage();

        // Show loading state
        const updateBtn = document.getElementById('updateNewsBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        updateBtn.disabled = true;

        // Fetch news data
        fetch(`/admin/news/${newsId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Populate modal fields
                document.getElementById('edit_news_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_content').value = data.content;
                document.getElementById('edit_image').value = data.image;

                // Format date for input field (YYYY-MM-DD)
                const publishedDate = new Date(data.published_at);
                const formattedDate = publishedDate.toISOString().split('T')[0];
                document.getElementById('edit_published_at').value = formattedDate;

                document.getElementById('edit_is_visible').checked = data.is_visible == 1;

                // Show current image preview
                showCurrentImagePreview(data.image);

                // Show modal
                const modalElement = document.getElementById('editNewsModal');
                console.log('Modal element found:', modalElement);

                if (modalElement) {
                    try {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                        console.log('Modal shown successfully');
                    } catch (error) {
                        console.error('Error showing modal:', error);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching news data:', error);
                showEditMessage('Error loading news data. Please try again.', 'danger');
            })
            .finally(() => {
                // Reset button state
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            });
    }

    // Function to show messages in the edit modal
    function showEditMessage(message, type = 'info') {
        const messageContainer = document.getElementById('editMessageContainer');
        const messageElement = document.getElementById('editMessage');

        messageElement.className = `alert alert-${type}`;
        messageElement.textContent = message;
        messageContainer.style.display = 'block';

        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => hideEditMessage(), 3000);
        }
    }

    // Function to hide messages in the edit modal
    function hideEditMessage() {
        const messageContainer = document.getElementById('editMessageContainer');
        messageContainer.style.display = 'none';
    }


    // Check if modal exists when page loads
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('editNewsModal');
        console.log('Modal element on page load:', modalElement);
        if (modalElement) {
            console.log('Modal HTML on page load:', modalElement.outerHTML);
        }

        // Check Bootstrap availability
        console.log('Bootstrap object:', typeof bootstrap !== 'undefined' ? bootstrap : 'NOT AVAILABLE');
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap version:', bootstrap.VERSION || 'Unknown version');
            console.log('Bootstrap Modal:', typeof bootstrap.Modal);
        }

        // Check jQuery availability
        console.log('jQuery object:', typeof $ !== 'undefined' ? $ : 'NOT AVAILABLE');
    });

    // Function to show current image preview
    function showCurrentImagePreview(imageUrl) {
        const previewContainer = document.getElementById('currentImagePreview');
        const previewImage = document.getElementById('previewImage');

        if (imageUrl) {
            // Convert Google Drive URL to direct image URL for preview
            let directImageUrl = imageUrl;
            if (imageUrl.includes('drive.google.com')) {
                const fileIdMatch = imageUrl.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
                if (fileIdMatch) {
                    directImageUrl = `https://drive.google.com/thumbnail?id=${fileIdMatch[1]}&sz=s4000`;
                }
            }

            previewImage.src = directImageUrl;
            previewImage.onerror = function () {
                this.src =
                    'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';
            };
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    }

    // Handle update button click
    document.getElementById('updateNewsBtn').addEventListener('click', function () {
        const form = document.getElementById('editNewsForm');
        const newsId = document.getElementById('edit_news_id').value;
        const updateBtn = this;

        // Validate form
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Show loading state
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';
        updateBtn.disabled = true;

        // Create a hidden form to submit the data as a regular form submission
        const hiddenForm = document.createElement('form');
        hiddenForm.method = 'POST';
        hiddenForm.action = `/admin/news/${newsId}`;
        hiddenForm.style.display = 'none';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        hiddenForm.appendChild(csrfInput);

        // Add method spoofing for PUT
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        hiddenForm.appendChild(methodInput);

        // Add all form fields
        const formData = new FormData(form);

        // Handle checkbox separately to ensure proper value
        const isVisibleCheckbox = document.getElementById('edit_is_visible');
        const isVisibleInput = document.createElement('input');
        isVisibleInput.type = 'hidden';
        isVisibleInput.name = 'is_visible';
        isVisibleInput.value = isVisibleCheckbox.checked ? '1' : '0';
        hiddenForm.appendChild(isVisibleInput);

        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method' && key !== 'is_visible') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                hiddenForm.appendChild(input);
            }
        }

        // Append form to body and submit
        document.body.appendChild(hiddenForm);
        hiddenForm.submit();
    });

    // Reset form validation when modal is hidden
    document.getElementById('editNewsModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('editNewsForm');
        form.classList.remove('was-validated');
        form.reset();
        hideEditMessage();

        // Hide image preview
        document.getElementById('currentImagePreview').style.display = 'none';
    });

</script>
@endisset
