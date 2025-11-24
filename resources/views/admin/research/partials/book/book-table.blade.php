<div class="table-responsive">
    <table class="table table-bordered table-hover" id="bookPublishedTable">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Authors/Editors</th>
                <th>Publisher</th>
                <th>Publication Date</th>
                <th>ISBN</th>
                <th>Visibility</th>
                <th width="220">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books ?? [] as $book)
                @if ($book)
                    <tr>
                        <td>{{ data_get($book, 'title', 'N/A') }}</td>
                        <td>
                            @php
                                $authors = implode(', ', data_get($book, 'authors', []));
                                $editors = implode(', ', data_get($book, 'editors', []));
                            @endphp
                            {{ $authors ?: '—' }}@if ($editors)
                                <small class="text-muted"> | Eds: {{ $editors }}</small>
                            @endif
                        </td>
                        <td>{{ data_get($book, 'publisher', 'N/A') }}</td>
                        <td>{{ optional(data_get($book, 'published_at'))->format('M d, Y') }}</td>
                        <td>{{ data_get($book, 'isbn', '—') }}</td>
                        <td>
                            @php $bookIsPublic = (bool) data_get($book, 'is_public', false); @endphp
                            <span class="badge bg-{{ $bookIsPublic ? 'success' : 'secondary' }}">{{ $bookIsPublic ? 'Public' : 'Private' }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.books.show', data_get($book, 'id')) }}"
                                    class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.books.edit', data_get($book, 'id')) }}"
                                    class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('admin.books.visibility', data_get($book, 'id')) }}"
                                    class="ms-1">
                                    @csrf
                                    <input type="hidden" name="is_public"
                                        value="{{ $bookIsPublic ? 0 : 1 }}">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-{{ $bookIsPublic ? 'secondary' : 'success' }}"
                                        title="{{ $bookIsPublic ? 'Make Private' : 'Make Public' }}">
                                        <i class="fa fa-eye{{ $bookIsPublic ? '-slash' : '' }}"></i>
                                    </button>
                                </form>
                                <form method="POST"
                                    action="{{ route('admin.books.destroy', data_get($book, 'id')) }}"
                                    onsubmit="return confirm('Delete this book?');" class="ms-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No books found. Try adjusting your search or filters.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-2" id="bookPagination">
    {{ $books->links() }}
</div>

