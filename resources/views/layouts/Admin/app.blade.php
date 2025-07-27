<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>{{ config('app.name', 'CVHRDC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Bundle with Popper (required for modal, dropdown, etc.) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-VkW1ibzE0sm7iV8YwnTZb8SnHqGpB1gQnNHH2jl+vOpNv4QYAGbhvX7MCi6e/hfB" crossorigin="anonymous"></script>

        <!-- Include CKEditor CDN -->
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

        @vite(['resources/css/app.css','resources/scss/app.scss', 'resources/js/app.js'])

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
    </body>
</html>


