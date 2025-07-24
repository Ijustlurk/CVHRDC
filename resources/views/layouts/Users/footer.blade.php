{{-- Dynamic Footer --}}
<footer class="bg-light text-center py-3 mt-5 border-top">
    <div class="container">
        <strong>{{ $footer->org_name ?? '' }}</strong><br>
        <span class="d-none d-md-inline">{{ $footer->description ?? '' }}</span><br>
        <span>{{ $footer->address ?? '' }}</span><br>
        <span>Email: {{ $footer->email ?? '' }}</span> |
        <span>Phone: {{ $footer->phone ?? '' }}</span> |
        <span>Website: <a href="{{ $footer->website ?? '#' }}" target="_blank">{{ $footer->website ?? '' }}</a></span>
        <div class="mt-2 small text-muted">{{ $footer->copyright ?? '' }}</div>
    </div>
</footer>
