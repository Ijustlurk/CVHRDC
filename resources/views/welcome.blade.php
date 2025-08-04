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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">
    <style>
        .body {
            font-family: 'Nunito';
        }

        .hero-section {
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            border-radius: 0 0 40px 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            position: relative;
            z-index: 1;
        }

        .text-gradient {
            background: linear-gradient(90deg, #6366f1 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-img-wrapper {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
            padding: 1.5rem;
            display: inline-block;
        }

        @media (max-width: 991.98px) {
            .hero-section {
                border-radius: 0 0 24px 24px;
                padding: 40px 0 20px 0;
            }

            .hero-img-wrapper {
                padding: 1rem;
            }
        }

        .banner-img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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

        .announcement-badge {
            background: #ffb347;
            color: #fff;
            font-size: 0.9rem;
            border-radius: 8px;
            padding: 2px 10px;
        }

    </style>
</head>

<body class="bg-light body">
    @if($maintenance && $maintenance->active)
    <div class="alert alert-warning text-center mb-0 rounded-0 animate__animated animate__fadeInDown">
        <strong>Maintenance Mode:</strong> {{ $maintenance->message ?? 'The site is under maintenance.' }}
    </div>
    @endif
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow rounded-pill py-3 px-4 mb-4 animate__animated animate__fadeInDown"
        style="margin-top: 20px;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-primary" href="#">
                @if($header && $header->logo)
                <img src="{{ asset('assets/Image/Llogo.jpg') }}" alt="Logo" height="44"
                    class="me-2 rounded-circle shadow-sm" style="background:#f3f6fa;">
                @endif
                {{ $header->title ?? 'CVHRDC' }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-2 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill transition-nav active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill transition-nav" href="#news">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill transition-nav" href="#projects">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill transition-nav"
                            href="#announcements">Announcements</a>
                    </li>
                    @if (Route::has('login'))
                    @auth
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}"
                            class="nav-link fw-semibold px-3 rounded-pill transition-nav">Dashboard</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link fw-semibold px-3 rounded-pill transition-nav">Log
                            in</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a href="{{ route('register') }}"
                            class="nav-link fw-semibold px-3 rounded-pill transition-nav">Register</a>
                    </li>
                    @endif
                    @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <section class="hero-section text-center animate__animated animate__fadeIn position-relative overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 mb-4 mb-lg-0 text-lg-start text-center">
                    <h1 class="display-4 fw-bold mb-3 text-gradient">{{ $header->title ?? 'CVHRDC' }}</h1>
                    <h4 class="mb-4 text-primary fw-semibold">
                        {{ $header->subtitle ?? 'Cagayan Valley Health Research and Development Consortium' }}</h4>
                    <p class="lead mb-4 text-secondary">
                        CVHRDC is dedicated to advancing health research and development in the Cagayan Valley region.
                        We foster collaboration among institutions, promote innovation, and support the dissemination of
                        research findings for the betterment of public health.
                    </p>
                    @if($banner && $banner->link)
                    <a href="{{ $banner->link }}"
                        class="btn btn-primary btn-sm px-5 shadow animate__animated animate__pulse animate__infinite">Learn
                        More</a>
                    @endif
                </div>
                <div class="col-lg-6 text-center">
                    @if($banner && $banner->image)
                    <div
                        class="hero-img-wrapper d-inline-block p-3 bg-white rounded-4 shadow-lg animate__animated animate__zoomIn">
                        <img src="{{ asset('assets/Image/Llogo.jpg') }}" alt="Banner" class="banner-img rounded-4"
                            style="max-width: 350px; width: 100%; height: auto;">
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Decorative shapes -->
        <div class="position-absolute top-0 start-0 translate-middle-x"
            style="width:180px; height:180px; background:rgba(99,102,241,0.08); border-radius:50%; z-index:0;"></div>
        <div class="position-absolute bottom-0 end-0 translate-middle-x"
            style="width:120px; height:120px; background:rgba(16,185,129,0.08); border-radius:50%; z-index:0;"></div>
    </section>
    <section id="announcements" class="py-5 bg-white">
        <div class="container">
            <div class="section-title text-center mb-4">Latest Announcements</div>
            <div class="row g-4">
                @forelse($announcements as $announcement)
                <div class="col-md-4">
                    <div class="card card-hover h-100 animate__animated animate__fadeInUp">
                        <div class="card-body">
                            <span class="announcement-badge mb-2 d-inline-block">
                                <i class="fa fa-bullhorn"></i> Announcement
                            </span>
                            <h5 class="card-title">{{ $announcement->title }}</h5>
                            <p class="card-text">{{ Str::limit($announcement->content, 100) }}
                                <!-- See More Button -->
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                    data-bs-target="#announcementModal{{ $announcement->id }}">
                                    See More
                                </a>
                            </p>
                        </div>
                        <div class="d-flex justify-content-end me-3">
                            <div class="text-muted small mb-2">
                                {{ $announcement->start_date ? \Carbon\Carbon::parse($announcement->start_date)->diffForHumans() : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="announcementModal{{ $announcement->id }}" tabindex="-1"
                    aria-labelledby="announcementModalLabel{{ $announcement->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="announcementModalLabel{{ $announcement->id }}">
                                    {{ $announcement->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{!! nl2br(e($announcement->content)) !!}</p>
                            </div>
                            <div class="modal-footer">
                                <!-- <small class="text-muted">
                                {{ $announcement->start_date ? \Carbon\Carbon::parse($announcement->start_date)->diffForHumans() : '' }}
                            </small> -->
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
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

                            <p class="card-text d-inline">
                                {{ Str::limit($item->content, 100) }}
                                <a href="#" class="btn btn-link p-0 align-baseline text-decoration-none"
                                    data-bs-toggle="modal" data-bs-target="#newsModal{{ $item->id }}">
                                    See More
                                </a>
                            </p>
                        </div>

                        <div class="d-flex justify-content-end me-3">
                            <div class="text-muted small mb-2">
                                {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->diffForHumans() : '' }}
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="newsModal{{ $item->id }}" tabindex="-1"
                    aria-labelledby="newsModalLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newsModalLabel{{ $item->id }}">{{ $item->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($item->image)
                                <img src="{{ $item->image }}" class="img-fluid mb-3" alt="News Image">
                                @endif
                                <p>{!! nl2br(e($item->content)) !!}</p>
                            </div>
                            <div class="modal-footer">
                                <!-- <small class="text-muted">
                                    {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->diffForHumans() : '' }}
                                </small> -->

                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
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
                            <p class="card-text">{{ Str::limit($project->description, 100) }}
                                <!-- See More Button -->
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                    data-bs-target="#projectModal{{ $project->id }}">
                                    See More
                                </a>
                            </p>
                        </div>
                        <div class="d-flex justify-content-end me-3">
                            <div class="text-muted small mb-2">
                                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->diffForHumans() : '' }}
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="projectModal{{ $project->id }}" tabindex="-1"
                    aria-labelledby="projectModalLabel{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="projectModalLabel{{ $project->id }}">{{ $project->title }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($project->image)
                                <img src="{{ $project->image }}" class="img-fluid mb-3" alt="Project Image">
                                @endif
                                <p>{!! nl2br(e($project->description)) !!}</p>
                            </div>
                            <div class="modal-footer">
                                <!-- <small class="text-muted">
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->diffForHumans() : '' }}
                                </small> -->
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
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
