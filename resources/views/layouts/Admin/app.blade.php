<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>CVHRDC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">



    <!-- Include CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{ asset('css/sweet.css') }}">
    <!-- Card Css -->
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <!-- UI -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/5c14b0052b.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css','resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito';
        }

        /* Logo uses Poppins for a cleaner brand look */
        .site-logo {
            font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            letter-spacing: 0.2px;
        }

        /* Fix SweetAlert2 + Bootstrap CSS conflict:
           Bootstrap's .btn sets background-color: transparent which bleeds into Swal buttons */
        .swal2-confirm,
        .swal2-cancel {
            display: inline-block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .swal2-confirm {
            background-color: var(--swal2-confirm-button-background-color, #3085d6) !important;
            color: #fff !important;
            border: none !important;
        }

        .swal2-cancel {
            background-color: var(--swal2-cancel-button-background-color, #6c757d) !important;
            color: #fff !important;
            border: none !important;
        }

        .swal2-actions button:focus {
            box-shadow: none !important;
        }
    </style>

</head>

<body class="antialiased">
    <!-- renders here the navigation -->
    <div class="mt-3 mx-5 shadow-sm">
        @include('layouts.Admin.navigation')
    </div>

    <!-- Page Content -->
    <main>
        <!-- Maintenance mode note for admins -->


        @php
        $maintenance = \App\Models\Maintenance::where('active', true)->first();
        @endphp
        @if($maintenance)
        <div class="alert alert-warning text-center mb-3">
            <strong>Note:</strong> The system is currently in <span class="text-danger">maintenance mode</span>.<br>
            Only the user-side is affected. The admin dashboard remains fully accessible.<br>
            <span class="text-muted">{{ $maintenance->message }}</span>
        </div>
        @endif

        <!-- starts to render the blade content -->
        @yield('content')

    </main>

    @php
    $footer = \App\Models\Footer::first();
    @endphp
    @include('layouts.Admin.footer', ['footer' => $footer])

    <!-- jQuery (required for some Bootstrap functionality) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle with Popper (required for modal, dropdown, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetALert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>
