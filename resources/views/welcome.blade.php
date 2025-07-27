@php
    $maintenance = \App\Models\Maintenance::first();
    $header = \App\Models\Header::first();
    $banner = \App\Models\Banner::first();
    $footer = \App\Models\Footer::first();
    $news = \App\Models\News::where('is_visible', true)->orderByDesc('published_at')->take(3)->get();
    $projects = \App\Models\Project::where('is_visible', true)->orderByDesc('start_date')->take(3)->get();
    $announcements = \App\Models\Announcement::where('active', true)->orderByDesc('start_date')->take(3)->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $header->title ?? 'CVHRDC' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        .hero-section {
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            padding: 60px 0 40px 0;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .banner-img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .section-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .card-hover:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            transition: 0.3s;
        }
        .announcement-badge {
            background: #ffb347;
            color: #fff;
            font-size: 0.9rem;
            border-radius: 8px;
            padding: 2px 10px;
        }
    </style>
</head>
<body class="bg-light">
    @if($maintenance && $maintenance->active)
        <div class="alert alert-warning text-center mb-0 rounded-0 animate__animated animate__fadeInDown">
            <strong>Maintenance Mode:</strong> {{ $maintenance->message ?? 'The site is under maintenance.' }}
        </div>
    @endif
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                @if($header && $header->logo)
                    <img src="{{ $header->logo }}" alt="Logo" height="40" class="me-2">
                @endif
                <span class="fw-bold">{{ $header->title ?? 'CVHRDC' }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#news">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="#announcements">Announcements</a></li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item"><a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a></li>
                        @else
                            <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Log in</a></li>
                            @if (Route::has('register'))
                                <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <section class="hero-section text-center animate__animated animate__fadeIn">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-3">{{ $header->title ?? 'CVHRDC' }}</h1>
                    <h4 class="mb-4 text-primary">{{ $header->subtitle ?? 'Cagayan Valley Health Research and Development Consortium' }}</h4>
                    <p class="lead">CVHRDC is dedicated to advancing health research and development in the Cagayan Valley region. We foster collaboration among institutions, promote innovation, and support the dissemination of research findings for the betterment of public health.</p>
                    @if($banner && $banner->link)
                        <a href="{{ $banner->link }}" class="btn btn-primary btn-lg mt-3 animate__animated animate__pulse animate__infinite">Learn More</a>
                    @endif
                </div>
                <div class="col-lg-6">
                    @if($banner && $banner->image)
                        <img src="{{ $banner->image }}" alt="Banner" class="banner-img animate__animated animate__zoomIn">
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section id="announcements" class="py-5 bg-white">
        <div class="container">
            <div class="section-title text-center mb-4">Latest Announcements</div>
            <div class="row g-4">
                @forelse($announcements as $announcement)
                    <div class="col-md-4">
                        <div class="card card-hover h-100 animate__animated animate__fadeInUp">
                            <div class="card-body">
                                <span class="announcement-badge mb-2 d-inline-block"><i class="fa fa-bullhorn"></i> Announcement</span>
                                <h5 class="card-title">{{ $announcement->title }}</h5>
                                <p class="card-text">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="text-muted small">{{ $announcement->start_date ? date('M d, Y', strtotime($announcement->start_date)) : '' }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">No announcements at this time.</div>
                @endforelse
            </div>
        </div>
    </section>
    <section id="news" class="py-5">
        <div class="container">
            <div class="section-title text-center mb-4">Latest News</div>
            <div class="row g-4">
                @forelse($news as $item)
                    <div class="col-md-4">
                        <div class="card card-hover h-100 animate__animated animate__fadeInUp">
                            @if($item->image)
                                <img src="{{ $item->image }}" class="card-img-top" alt="News Image">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text">{{ Str::limit($item->content, 100) }}</p>
                                <div class="text-muted small">{{ $item->published_at ? date('M d, Y', strtotime($item->published_at)) : '' }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">No news available.</div>
                @endforelse
            </div>
        </div>
    </section>
    <section id="projects" class="py-5 bg-white">
        <div class="container">
            <div class="section-title text-center mb-4">Featured Projects</div>
            <div class="row g-4">
                @forelse($projects as $project)
                    <div class="col-md-4">
                        <div class="card card-hover h-100 animate__animated animate__fadeInUp">
                            @if($project->image)
                                <img src="{{ $project->image }}" class="card-img-top" alt="Project Image">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $project->title }}</h5>
                                <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                                <div class="text-muted small">{{ $project->start_date ? date('M d, Y', strtotime($project->start_date)) : '' }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">No projects available.</div>
                @endforelse
            </div>
        </div>
    </section>
    @include('layouts.Admin.footer', ['footer' => $footer])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
