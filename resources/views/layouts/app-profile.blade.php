<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CVHRDC | Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{ asset('css/sweet.css') }}">
    <!-- Card Css -->
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <!-- UI -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/5c14b0052b.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito';
            background-color: #f8f9fa;
        }

        .site-logo {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.2px;
        }

        .profile-nav-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .profile-nav-link {
            padding: 1rem 1.5rem;
            color: #495057;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .profile-nav-link:hover {
            background-color: #f1f3f5;
            color: #0d6efd;
        }

        .profile-nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
        }

        .profile-nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Fix Tailwind Preflight + Bootstrap Button conflict:
           Tailwind resets all buttons to transparent background */
        .btn-primary {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
            opacity: 1 !important;
        }

        .btn-primary:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
            opacity: 1 !important;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Role-based Navigation -->
    <div class="mt-3 mx-lg-5 mx-3 shadow-sm">
        @php
            $user = Auth::user();
            $isAdmin = false;
            if ($user && $user->roles->isNotEmpty()) {
                $roleName = strtolower($user->roles[0]->name);
                if (in_array($roleName, ['superadmin', 'admin', 'rmc', 'ruc'])) {
                    $isAdmin = true;
                }
            }
        @endphp

        @if($isAdmin)
            @include('layouts.Admin.navigation')
        @else
            @include('layouts.Users.navigation')
        @endif
    </div>

    <!-- Page Content -->
    <main>
        <div class="py-5">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </main>

    @php
        $footer = \App\Models\Footer::first();
    @endphp

    @if($isAdmin)
        @include('layouts.Admin.footer', ['footer' => $footer])
    @else
        @include('layouts.Users.footer', ['footer' => $footer])
    @endif

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>

</html>
