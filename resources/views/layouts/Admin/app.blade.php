<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>CVHRDC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">



    <!-- Include CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{ asset('css/sweet.css') }}">


    @vite(['resources/css/app.css','resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito';
        }

    </style>

</head>

<body class="antialiased">
    <!-- renders here the navigation -->
    @include('layouts.Admin.navigation')

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

    @stack('scripts')

    <!-- jQuery (required for some Bootstrap functionality) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle with Popper (required for modal, dropdown, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetALert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
