@php $maintenance = \App\Models\Maintenance::first(); $header = \App\Models\Header::first(); $footer = \App\Models\Footer::first(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $header->title ?? 'CVHRDC' }} - {{ $call->title }}</title>
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

        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .info-card .card-title {
            color: white;
        }

        .guidelines-card {
            border-left: 4px solid #007bff;
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

    <!-- CALL DETAILS SECTION -->
    <section class="py-5 mt-5">
        <div class="container">
            <!-- Back Button -->
            <div class="row mb-4">
                <div class="col-12">
                    <a href="{{ route('public.calls.index') }}" class="btn btn-outline-secondary btn-md mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Calls
                    </a>
                </div>
            </div>

            <!-- Call Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-file-alt text-white fs-3"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h1 class="card-title mb-3 text font">{{ $call->title }}</h1>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <i class="fas fa-money-bill-wave text-success fs-4 mb-2"></i>
                                                <h6 class="mb-1">Funding Limit</h6>
                                                <strong class="text-dark">₱{{ number_format($call->funding_limit, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <i class="fas fa-calendar-alt text-primary fs-4 mb-2"></i>
                                                <h6 class="mb-1">Duration</h6>
                                                <strong class="text-dark">
                                                    {{ $call->start_date->format('M d') }} - {{ $call->end_date->format('M d, Y') }}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <i class="fas fa-users text-info fs-4 mb-2"></i>
                                                <h6 class="mb-1">Eligibility</h6>
                                                <span class="badge bg-info status-badge">{{ Str::limit($call->eligibility, 20) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <i class="fas fa-clock text-warning fs-4 mb-2"></i>
                                                <h6 class="mb-1">Posted</h6>
                                                <small class="text-muted">{{ $call->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Description Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary bg-gradient text-white">
                            <h5 class="m-0 font-weight-bold"><i class="fas fa-align-left me-2"></i>Description</h5>
                        </div>
                        <div class="card-body">
                            <div class="text">
                                {!! nl2br(e($call->description)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Guidelines Card -->
                    @if($call->guidelines)
                    <div class="card shadow-sm mb-4 guidelines-card">
                        <div class="card-header bg-light">
                            <h5 class="m-0 font-weight-bold"><i class="fas fa-clipboard-list me-2"></i>Guidelines</h5>
                        </div>
                        <div class="card-body">
                            <div class="text">
                                {!! nl2br(e($call->guidelines)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Research Priority Area Card -->
                    @if($call->research_priority_area)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info bg-gradient text-white">
                            <h5 class="m-0 font-weight-bold"><i class="fas fa-flask me-2"></i>Research Priority Area</h5>
                        </div>
                        <div class="card-body">
                            <div class="text">
                                {!! nl2br(e($call->research_priority_area)) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success bg-gradient text-white">
                            <h5 class="m-0 font-weight-bold"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            @if($call->proposal_format_file)
                            <a href="{{ route('public.calls.download-format', $call->id) }}" class="btn btn-outline-primary btn-md w-100 mb-3" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Proposal Format
                            </a>
                            @endif

                            @auth
                            <a href="{{ route('rmc.calls.create') }}" class="btn btn-primary btn-md w-100">
                                <i class="fas fa-paper-plane me-2"></i>Submit Proposal
                            </a>
                            @else
                            <p class="text-muted text-center mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Login to submit a proposal for this call.
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-md w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Submit
                            </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning bg-gradient text-dark">
                            <h5 class="m-0 font-weight-bold"><i class="fas fa-exclamation-triangle me-2"></i>Important Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Ensure all required documents are submitted before the deadline.
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Review eligibility criteria carefully before applying.
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Follow the specified proposal format guidelines.
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Contact CVHRDC for any clarifications needed.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Application Deadline Alert -->
                    @php
                        $daysUntilEnd = now()->diffInDays($call->end_date, false);
                    @endphp
                    @if($daysUntilEnd > 0 && $daysUntilEnd <= 7)
                    <div class="card shadow-sm border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-clock text-danger fs-4 mb-2"></i>
                            <h6 class="text-danger">Application Deadline Approaching!</h6>
                            <p class="mb-0 text-muted">Only {{ $daysUntilEnd }} day{{ $daysUntilEnd > 1 ? 's' : '' }} remaining</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('layouts.Admin.footer', ['footer' => $footer])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
