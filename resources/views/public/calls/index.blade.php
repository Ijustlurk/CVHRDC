@php $maintenance = \App\Models\Maintenance::first(); $header = \App\Models\Header::first(); $footer = \App\Models\Footer::first(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $header->title ?? 'CVHRDC' }} - Calls for Proposals</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet" />

    <style>
        .body {
            font-family: "Nunito";
        }

        .text {
            color: #112245;
        }

        .font {
            font-family: "Outfit", sans-serif;
        }

        .text-2 {
            color: #175ddc;
        }

        .hero-section {
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            border-radius: 0 0 40px 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            position: relative;
            z-index: 1;
        }

        .text-gradient {
            background: linear-gradient(90deg, #666af2 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .card-hover:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            transition: 0.3s;
        }

        .call-card {
            transition: background-color 0.3s ease;
        }

        .call-card:hover {
            background-color: #cbe7ff;
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 4px 12px;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-light body">
    @if($maintenance && $maintenance->active)
    <div class="alert alert-warning text-center mb-0 rounded-0 animate__animated animate__fadeInDown">
        <strong>Maintenance Mode:</strong>
        {{ $maintenance->message ?? 'The site is under maintenance.' }}
    </div>
    @endif

    <!-- NAVIGATION -->
    <div class="container fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow rounded-pill py-2 px-4 mb-4 mt-3 animate__animated animate__fadeInDown">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-2" href="{{ route('welcome') }}">
                    @if($header && $header->logo)
                    <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo" height="44" class="me-2 rounded-circle shadow-sm" style="background: #f3f6fa" />
                    @endif
                    {{ $header->title ?? 'CVHRDC' }}
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-2 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold px-3 rounded-pill transition-nav" href="{{ route('welcome') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold px-3 rounded-pill transition-nav active text-primary" href="{{ route('public.calls.index') }}">Calls for Proposals</a>
                        </li>
                        @if (Route::has('login')) @auth
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Dashboard</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Log in</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Register</a>
                        </li>
                        @endif @endauth @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- CALLS SECTION -->
    <section class="py-5 mt-5">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h1 class="section-title font text">
                        <i class="fas fa-bullhorn me-3 text-primary"></i>Calls for Proposals
                    </h1>
                    <p class="lead text-muted">
                        Explore research opportunities and funding calls currently available for researchers and institutions.
                    </p>
                </div>
            </div>

            @forelse($calls as $call)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card call-card shadow-sm animate__animated animate__fadeInUp">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-file-alt text-white fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="card-title mb-2 text font">
                                                {{ $call->title }}
                                            </h4>
                                            <p class="card-text text-muted mb-3">
                                                {{ Str::limit(strip_tags($call->description), 150) }}
                                            </p>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Funding Limit</small>
                                                            <strong class="text-dark">₱{{ number_format($call->funding_limit, 2) }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Application Period</small>
                                                            <strong class="text-dark">
                                                                {{ $call->start_date->format('M d') }} - {{ $call->end_date->format('M d, Y') }}
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-users text-info me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Eligibility</small>
                                                            <span class="badge bg-info status-badge">{{ Str::limit($call->eligibility, 20) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                                    <div class="d-flex gap-2 justify-content-lg-end justify-content-center">
                                        <a href="{{ route('public.calls.show', $call->id) }}" class="btn btn-primary btn-md px-4">
                                            <i class="fas fa-eye me-2"></i>View Full Details
                                        </a>
                                        @if($call->proposal_format_file)
                                        <a href="{{ route('public.calls.download-format', $call->id) }}" class="btn btn-outline-secondary btn-md px-4" target="_blank">
                                            <i class="fas fa-download me-2"></i>Download Format
                                        </a>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-end">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $call->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="card shadow-sm">
                        <div class="card-body py-5">
                            <i class="fas fa-search text-muted fs-1 mb-3"></i>
                            <h4 class="text-muted">No Open Calls Available</h4>
                            <p class="text-muted">There are currently no public calls for proposals available. Please check back later for new opportunities.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($calls->hasPages())
            <div class="row justify-content-center mt-4">
                <div class="col-auto">
                    {{ $calls->links() }}
                </div>
            </div>
            @endif
        </div>
    </section>

    @include('layouts.Admin.footer', ['footer' => $footer])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Real-time updates for calls -->
    <script>
        let lastUpdateTimestamp = '{{ now()->toISOString() }}';

        function checkForUpdates() {
            fetch('{{ route("api.public-calls") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Check if data has changed
                if (data.timestamp !== lastUpdateTimestamp || data.count !== {{ $calls->total() ?? $calls->count() }}) {
                    // Update the page title and show notification
                    lastUpdateTimestamp = data.timestamp;
                    showUpdateNotification();

                    // Reload the page to get fresh data
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.log('Error checking for updates:', error);
            });
        }

        function showUpdateNotification() {
            // Create a subtle notification
            let notification = document.createElement('div');
            notification.className = 'position-fixed top-0 end-0 m-3 alert alert-info alert-dismissible fade show';
            notification.style.zIndex = '9999';
            notification.innerHTML = `
                <i class="fas fa-sync-alt me-2"></i>
                New calls for proposals available! Page will refresh shortly.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Check for updates every 5 minutes (300,000 milliseconds)
        setInterval(checkForUpdates, 300000);

        // Also check when the page becomes visible (in case user switched tabs)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                checkForUpdates();
            }
        });
    </script>
</body>

</html>
