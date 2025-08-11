@can('user-access')
@extends('layouts.Users.app')
@section('content')
<div class="container-fluid py-4 px-5">
    <div class="row mb-4 align-items-center g-3">
        <div class="col-auto">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                alt="Profile Photo" class="rounded-circle" width="64" height="64">
        </div>
        <div class="col">
            <h3 id="greeting" class="mb-0"></h3>
            <div class="text-muted">Welcome back, {{ $user->name }}!</div>
        </div>
        <!-- Notification bell moved to navigation -->
    </div>

    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body row align-items-center g-3">
                    <div class="col-auto">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                            alt="Profile Photo" class="rounded-circle" width="56" height="56">
                    </div>
                    <div class="col">
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <div class="text-muted">{{ $user->title ?? 'Member' }} |
                            {{ $user->institution ?? $user->campus ?? '' }}</div>
                        <div class="text-muted">Expertise: {{ $user->expertise ?? 'N/A' }}</div>
                    </div>
                    <div class="col-auto ms-auto">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Upload CV</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Upload Publications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">Recent Activity</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($activities as $activity)
                        <li class="list-group-item">
                            <span class="fw-bold text-primary">{{ ucfirst($activity['type']) }}</span>:
                            {{ $activity['text'] }}<br>
                            <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                        </li>
                        @empty
                        <li class="list-group-item text-muted">No recent activity</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Research Projects</span>
                    <select class="form-select form-select-sm w-auto" id="projectFilter">
                        <option value="all">All</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="card-body">
                    <div id="projectsList">
                        @forelse($projects as $project)
                        <div class="border rounded p-2 mb-2 project-item"
                            data-status="{{ strtolower($project->status ?? 'ongoing') }}">
                            <div class="fw-bold">{{ $project->title }}</div>
                            <div>Status: <span
                                    class="badge bg-{{ ($project->status ?? 'ongoing') == 'completed' ? 'success' : 'info' }}">{{ ucfirst($project->status ?? 'Ongoing') }}</span>
                            </div>
                            <div>Funding: {{ $project->funding_agency ?? 'N/A' }}</div>
                            <div><small>{{ $project->start_date }} - {{ $project->end_date }}</small></div>
                        </div>
                        @empty
                        <div class="text-muted">No projects found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Publications</span>
                    <div>
                        <a href="#" class="btn btn-outline-primary btn-sm">Upload</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Export PDF</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Co-authors</th>
                                    <th>DOI</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($publications as $pub)
                                <tr>
                                    <td>{{ $pub->title ?? 'Untitled' }}</td>
                                    <td>{{ $pub->year ?? '-' }}</td>
                                    <td>{{ $pub->co_authors ?? '-' }}</td>
                                    <td>@if(!empty($pub->doi))<a href="https://doi.org/{{ $pub->doi }}"
                                            target="_blank">{{ $pub->doi }}</a>@else - @endif</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-muted">No publications found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons CDN for bell icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script>
    // Dynamic greeting based on time of day
    function getGreeting() {
        const hour = new Date().getHours();
        if (hour < 12) return 'Good morning';
        if (hour < 18) return 'Good afternoon';
        return 'Good evening';
    }
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('greeting').textContent = getGreeting() + ', {{ $user->title ? $user->title . '
        ' : '
        ' }}{{ $user->name }}!';
        // Project filter
        document.getElementById('projectFilter').addEventListener('change', function () {
            const val = this.value;
            document.querySelectorAll('.project-item').forEach(function (item) {
                if (val === 'all' || item.dataset.status === val) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

</script>
@endsection
@endcan
