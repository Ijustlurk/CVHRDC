@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<div class="container py-4">
    <div class="mt-2">
        <h5 class="mb-4 font-medium site-logo"><i class="fa-solid fa-caret-right me-4"></i>Analytics Dashboard</h5>
    </div>
    <hr>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-blue">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">📚 Publications</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalPublications ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-green">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">👩‍🔬 Researchers</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalResearchers ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-cyan">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">🤝 Collaborations</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalCollaborations ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-grey">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">👥 Users</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalUsers ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-yellow">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">📰 News</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalNews ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-orange">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">💬 Feedbacks</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $totalFeedbacks ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-purple">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">🆕 New Users (7d)</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $newUsers ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-animated card-armygreen">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-2">🪖 New Feedbacks (7d)</h6>
                    <p class="display-6 fw-bold mb-0">
                        {{ $newFeedbacks ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100 modern-card">
                <div class="card-body">
                    <h5 class="card-title fs-4 fw-bold">Popular Articles</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($popularArticles as $article)
                        <li class="list-group-item border-0">
                            <strong>{{ $article->title }}</strong>
                            <br><small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 modern-card">
                <div class="card-body">
                    <h5 class="card-title fs-4 fw-bold">User Engagement</h5>
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
                data: [
                    {{ $engagement['views'] ?? 0 }},
                    {{ $engagement['downloads'] ?? 0 }}
                ],
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
