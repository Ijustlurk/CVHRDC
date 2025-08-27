@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Analytics Dashboard</h2>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Publications</h5>
                    <p class="card-text display-6">{{ $totalPublications ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Researchers</h5>
                    <p class="card-text display-6">{{ $totalResearchers ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Collaborations</h5>
                    <p class="card-text display-6">{{ $totalCollaborations ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text display-6">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">News</h5>
                    <p class="card-text display-6">{{ $totalNews ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title">Feedbacks</h5>
                    <p class="card-text display-6">{{ $totalFeedbacks ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <h5 class="card-title">New Users (7d)</h5>
                    <p class="card-text display-6">{{ $newUsers ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <h5 class="card-title">New Feedbacks (7d)</h5>
                    <p class="card-text display-6">{{ $newFeedbacks ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Popular Articles</h5>
                    <ul class="list-group">
                        @foreach($popularArticles as $article)
                        <li class="list-group-item">
                            <strong>{{ $article->title }}</strong>
                            <br><small>{{ $article->created_at->format('M d, Y') }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">User Engagement</h5>
                    <canvas id="engagementChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('engagementChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Views', 'Downloads'],
            datasets: [{
                label: 'User Engagement',
                data: [{
                    {
                        $engagement['views'] ? ? 0
                    }
                }, {
                    {
                        $engagement['downloads'] ? ? 0
                    }
                }],
                backgroundColor: ['#0d6efd', '#198754']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

</script>
@endsection
@endcan
